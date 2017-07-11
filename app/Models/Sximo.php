<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Library\FEG\System\FEGSystemHelper;
use Request, Log,Redirect,Session;
use App\Library\SximoEloquentBuilder;
use App\Library\SximoQueryBuilder;
use App\Models\Core\Groups;
class Sximo extends Model {

    public static $getRowsQuery = null;

    public function newEloquentBuilder($query)
    {
        return new SximoEloquentBuilder($query);
    }
    protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();

        $grammar = $conn->getQueryGrammar();

        return new SximoQueryBuilder($conn, $grammar, $conn->getPostProcessor());
    }
    public static function insertLog($module, $task ,$note = '', $conditions = '',$params = null)
    {
        $table = 'tb_logs';
        $user = (is_object(\Auth::user()) ? \Auth::user()->id : 'User Not Logged In');
        $impersonatedUserIdPath = Session::has('return_id') ? Session::get('return_id') : [];
        $impersonatedUser = 'No Impersonation';
        if(!empty($impersonatedUserIdPath))
        {
            $impersonatedUser = array_pop($impersonatedUserIdPath);
        }
        $cronTask = (Request::ip() == "127.0.0.1");
        /*if($cronTask)
        {
            $user = "System";
        }*/
        $data = array(
            'auditID' => '',
            'note' => $note,
            'ipaddress' => Request::ip(),
            'user_id' => $user,
            'module'  => $module,
            'task'    => $task,
            'params' => $params,
            'conditions' => $conditions
        );

        $l = '';
        $L =  FEGSystemHelper::setLogger($l, "user-action-logs.log", "FEGUserActions", "USER_ACTIONS");
        if(!$cronTask)
        {
            /*$cronTask ? $L->log('--------------------Start CronJobActions logging------------------') : */
            $L->log('--------------------Start UserActions logging------------------');

        $L->log("User ID ",$user);
        $L->log("Actual User ID " , $impersonatedUser);
        $L->log("User IP ",Request::ip());
        $L->log("User Browser ",isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT']: "CLI");
        $L->log("Module or Table : ".$module, $note);
        $L->log("Task : ".$task);
        $L->log("Conditions : ".json_encode($conditions));
        $L->log("Parameters : " . json_encode($params));

            /*$cronTask ? $L->log('--------------------End CronJobActions logging------------------') : */
            $L->log('--------------------End UserActions logging------------------');
            $id = \DB::table($table)->insertGetId($data);
            return $id;
        }
        return 0;
    }


    public static function parseNumber($num)
    {
        return number_format((float)$num, 3, '.', '');
    }

    public static function getRows($args, $cond = null) {
        $table = with(new static)->table;
        $key = with(new static)->primaryKey;
        extract(array_merge(array(
            'page' => '0',
            'limit' => '0',
            'sort' => '',
            'extraSorts' => [],
            'order' => '',
            'params' => '',
            'global' => 1
                        ), $args));


        $orderConditional = ($sort != '' && $order != '') ? " ORDER BY {$sort} {$order} " : '';
        if (!empty($extraSorts)) {
            if (empty($orderConditional)) {
                $orderConditional = " ORDER BY ";
            }
            else {
                $orderConditional .= ", ";
            }
            $extraOrderConditionals = [];
            foreach($extraSorts as $extraSortItem) {
                $extraSortItem[0] = '`'.$extraSortItem[0].'`';
                $extraOrderConditionals[] = implode(' ', $extraSortItem);
            }
            $orderConditional .= implode(', ', $extraOrderConditionals);
        }

        // Update permission global / own access new ver 1.1
        $table = with(new static)->table;
        if ($global == 0)
            $params .= " AND {$table}.entry_by ='" . \Session::get('uid') . "'";
        // End Update permission global / own access new ver 1.1

        $rows = array();
        $select = self::querySelect();

        /*

        */
        $createdFlag = false;

        if ($cond != null) {
            $select .= self::queryWhere($cond);
        }
        else {
            $select .= self::queryWhere();
        }

        if(!empty($createdFrom)){
            if($cond != 'only_api_visible')
            {
                $select .= " AND created_at BETWEEN '$createdFrom' AND '$createdTo'";
            }
            else
            {
                $select .= " AND api_created_at BETWEEN '$createdFrom' AND '$createdTo'";
            }
            $createdFlag = true;
        }

        if(!empty($updatedFrom)){

            if($createdFlag){
                if($cond != 'only_api_visible')
                {
                    $select .= " OR updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
                else
                {
                    $select .= " OR api_updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
            }
            else{
                if($cond != 'only_api_visible')
                {
                    $select .= " AND updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
                else
                {
                    $select .= " AND api_updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
            }

        }

        if(!empty($order_type_id)){
            $select .= " AND order_type_id in($order_type_id)";
        }
        if(!empty($status_id)){
            $select .= " AND status_id='$status_id'";
        }
        if(!empty($active)){//added for location
            $select .= " AND location.active='$active'";
        }

        Log::info("Total Query : ".$select . " {$params} " . self::queryGroup() . " {$orderConditional}");
        $counter_select =\DB::select($select . " {$params} " . self::queryGroup() . " {$orderConditional}");
        $total= count($counter_select);
        if($table=="img_uploads")
        {
            $total="";
        }
        
        $offset = ($page - 1) * $limit;
        if ($offset >= $total && $total != 0) {
            $page = ceil($total/$limit);
            $offset = ($page-1) * $limit ;
        }

        $limitConditional = ($page != 0 && $limit != 0) ? "LIMIT  $offset , $limit" : '';
       // echo $select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ";
        Log::info("Query : ".$select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ");
        self::$getRowsQuery = $select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ";
        $result = \DB::select($select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ");

        if ($key == '') {
            $key = '*';
        } else {
            $key = $table . "." . $key;
        }
        return $results = array('rows' => $result, 'total' => $total);
    }

    public static function getRow($id) {
        $table = with(new static)->table;
        $key = with(new static)->primaryKey;

        $result = \DB::select(
                        self::querySelect() .
                        self::queryWhere() .
                        " AND " . $table . "." . $key . " = '{$id}' " .
                        self::queryGroup()
        );
        if (count($result) <= 0) {
            $result = array();
        } else {

            $result = $result[0];
        }
        return $result;
    }

    public function cleanData($data){
        return array_map('trim',$data);
    }

    public  function insertRow($data, $id = null) {

        $data = $this->cleanData($data);

        $timestampTables = array('vendor','products','orders', 'departments', 'system_email_report_manager');
        $table = with(new static)->table;
        $key = with(new static)->primaryKey;
        if ($id == NULL) {
            // Insert Here

            if(in_array($table,$timestampTables)){
                $data['created_at'] = date('Y-m-d H:i:s');
            }
            if (isset($data['createdOn']))
                $data['createdOn'] = date("Y-m-d H:i:s");
            if (isset($data['updatedOn']))
                $data['updatedOn'] = date("Y-m-d H:i:s");
            $id = \DB::table($table)->insertGetId($data);
        } else {
            // Update here
            // update created field if any
            if(in_array($table,$timestampTables)){
                $data['updated_at'] = date('Y-m-d H:i:s');
            }
            if (isset($data['created_at']))
                unset($data['created_at']);
            if (isset($data['createdOn']))
                unset($data['createdOn']);
            if (isset($data['updatedOn']))
                $data['updatedOn'] = date("Y-m-d H:i:s");
            \DB::table($table)->where($key, $id)->update($data);
        }
        return $id;
    }

    function intersectCols($arr1, $arr2) {

    }

    static function makeInfo($id) {
        $row = \DB::table('tb_module')->where('module_name', $id)->get();
        $data = array();
        foreach ($row as $r) {
            $langs = (json_decode($r->module_lang, true));
            $data['id'] = $r->module_id;
            $data['title'] = \SiteHelpers::infoLang($r->module_title, $langs, 'title');
            $data['note'] = \SiteHelpers::infoLang($r->module_note, $langs, 'note');
            $data['table'] = $r->module_db;
            $data['key'] = $r->module_db_key;
            $data['config'] = \SiteHelpers::CF_decode_json($r->module_config);
            $field = array();
            foreach ($data['config']['grid'] as $fs) {
                foreach ($fs as $f)
                    $field[] = $fs['field'];
            }
            $data['field'] = $field;
            $data['setting'] = array(
                'module_route' => (isset($data['config']['setting']['module_route']) ? $data['config']['setting']['module_route'] : $r->module_id),
                'gridtype' => (isset($data['config']['setting']['gridtype']) ? $data['config']['setting']['gridtype'] : 'native'),
                'orderby' => (isset($data['config']['setting']['orderby']) ? $data['config']['setting']['orderby'] : $r->module_db_key),
                'ordertype' => (isset($data['config']['setting']['ordertype']) ? $data['config']['setting']['ordertype'] : 'asc'),
                'perpage' => (isset($data['config']['setting']['perpage']) ? $data['config']['setting']['perpage'] : '20'),
                'frozen' => (isset($data['config']['setting']['frozen']) ? $data['config']['setting']['frozen'] : 'false'),
                'form-method' => (isset($data['config']['setting']['form-method']) ? $data['config']['setting']['form-method'] : 'native'),
                'view-method' => (isset($data['config']['setting']['view-method']) ? $data['config']['setting']['view-method'] : 'native'),
                'inline' => (isset($data['config']['setting']['inline']) ? $data['config']['setting']['inline'] : 'false'),
                'hideadvancedsearchoperators' => (isset($data['config']['setting']['hideadvancedsearchoperators']) ? $data['config']['setting']['hideadvancedsearchoperators'] : 'false' ),
                'hiderowcountcolumn' => (isset($data['config']['setting']['hiderowcountcolumn']) ? $data['config']['setting']['hiderowcountcolumn'] : 'false' ),                
                'usesimplesearch' => (isset($data['config']['setting']['usesimplesearch']) ? $data['config']['setting']['usesimplesearch'] : 'true' ),                
                'publicaccess' => (isset($data['config']['setting']['publicaccess']) ? $data['config']['setting']['publicaccess'] : true ),
                'simplesearchbuttonwidth' => (isset($data['config']['setting']['simplesearchbuttonwidth']) ? $data['config']['setting']['simplesearchbuttonwidth'] : '' ),                
                'disablepagination' => (isset($data['config']['setting']['disablepagination']) ? $data['config']['setting']['disablepagination'] : 'false' ),
                'disablesort' => (isset($data['config']['setting']['disablesort']) ? $data['config']['setting']['disablesort'] : 'false' ),
                'disableactioncheckbox' => (isset($data['config']['setting']['disableactioncheckbox']) ? $data['config']['setting']['disableactioncheckbox'] : 'false' ),
                'disablerowactions' => (isset($data['config']['setting']['disablerowactions']) ? $data['config']['setting']['disablerowactions'] : 'false' ),
            );

            if ($data['setting']['disablepagination'] == 'true') {
                $data['setting']['perpage'] = 0;
            }
        }
        return $data;
    }

    static function getComboselect($params, $limit = null, $parent = null) {

        $limit = explode(':', $limit);
        $parent = explode(':', $parent);

        if (count($limit) >= 3) {
            $table = $params[0];
            $condition = $limit[0] . " `" . $limit[1] . "` " . $limit[2] . " " . $limit[3] . " ";
            if (count($parent) >= 2) {
                $row = \DB::table($table)->where($parent[0], $parent[1])->get();
                $query = "SELECT * FROM " . $table . " " . $condition . " AND " . $parent[0] . " = '" . $parent[1] . "'";
                if(!empty($params) && isset($params[2])){
                    $query .= " order by ".$params[2];
                }
                $row = \DB::select($query);
            } else {

                $row = \DB::select("SELECT * FROM " . $table . " " . $condition);
            }
        } else {

            $table = $params[0];
            if (count($parent) >= 2) {
                $row = \DB::table($table)->where($parent[0], $parent[1])->orderby($params[2])->get();
            } else {

                $order = substr($params['2'], 0, strpos($params['2'], '|'));
                if (!$order) {


                    $order = $params['2'];
                }
                if (!isset($params['3'])) {
                    $row = \DB::table($table)->where($order,'!='," ")->orderBy($order,'asc')->get();
                } else {

                    $row = \DB::table($table)->where($params['3'], $params['4'])->orderBy($order, 'asc')->get();
                }
            }
        }


        return $row;
    }

    public static function getColoumnInfo($result) {
        $pdo = \DB::getPdo();
        $res = $pdo->query($result);
        $i = 0;
        $coll = array();
        while ($i < $res->columnCount()) {
            $info = $res->getColumnMeta($i);
            $coll[] = $info;
            $i++;
        }
        return $coll;
    }

    function validAccess($id) {

        $row = \DB::table('tb_groups_access')->where('module_id', '=', $id)
                ->where('group_id', '=', \Session::get('gid'))
                ->get();

        if (count($row) >= 1) {
            $row = $row[0];
            if ($row->access_data != '') {
                $data = json_decode($row->access_data, true);
            } else {
                $data = array();
            }
            return $data;
        } else {
            return false;
        }
    }

    function validPageAccess($page, $groupId = null) {
        if (empty($groupId)) {
            $groupId = \Session::get('gid');
        }
        $row = \DB::table('tb_pages')->where('alias', '=', $page)
                ->first();

        if (!empty($row)) {
            $data = ['is_view' => 0];
            if ($row->access != '') {
                $accsss = json_decode($row->access, true);
                $data['is_view'] = isset($accsss[$groupId])? $accsss[$groupId] : 0;
            }
            return $data;
        }
        else {
            return false;
        }
    }

    static function getColumnTable($table) {
        $columns = array();
        foreach (\DB::select("SHOW COLUMNS FROM $table") as $column) {
            //print_r($column);
            $columns[$column->Field] = '';
        }


        return $columns;
    }

    static function getTableList($db) {
        $t = array();
        $dbname = 'Tables_in_' . $db;
        foreach (\DB::select("SHOW TABLES FROM {$db}") as $table) {
            $t[$table->$dbname] = $table->$dbname;
        }
        return $t;
    }

    static function getTableField($table) {
        $columns = array();
        foreach (\DB::select("SHOW COLUMNS FROM $table") as $column)
            $columns[$column->Field] = $column->Field;
        return $columns;
    }

    public static function searchOperation($operate) {
        $val = '';
        switch ($operate) {
            case 'equal':
                $val = '=';
                break;
            case 'bigger_equal':
                $val = '>=';
                break;
            case 'smaller_equal':
                $val = '<=';
                break;
            case 'smaller':
                $val = '<';
                break;
            case 'bigger':
                $val = '>';
                break;
            case 'not_null':
                $val = 'not_null';
                break;

            case 'is_null':
                $val = 'is_null';
                break;

            case 'like':
                $val = 'like';
                break;

            case 'between':
                $val = 'between';
                break;

            default:
                $val = '=';
                break;
        }
        return $val;
    }

    public function checkModule($config_name, $module_id) {
        $id = \DB::table('user_module_config')->where('config_name', '=', $config_name)->where('module_id', '=', $module_id)->pluck('id');
        return $id;
    }

    public function getModuleConfig($module_id, $config_id) {

        $res = \DB::table('user_module_config')->where('module_id', '=', $module_id)->where('id', '=', $config_id)->get();

        return $res;
    }

    public function getLocations($id) {
        $locations = array();
        $i = 0;
        $selected_loc = \DB::table('user_locations')->select('location_id')->where('user_id', '=', $id)->get();
        foreach ($selected_loc as $loc) {
            $locations[$i] = $loc->location_id;
            $i++;
        }
        return implode(',', $locations);
    }

    public function inserLocations($locations, $userid, $id) {

        $loc = "";
        $i = 0;
            foreach ($locations as $location) {
                $loc[$i] = array('user_id' => $userid, 'location_id' => $location);
                $i++;

            }

        if ($id != NULL) {
            \DB::table('user_locations')->where('user_id', '=', $userid)->delete();
        }

        \DB::table('user_locations')->insert($loc);
    }

    function getLocation($location_id) {
        $row = \DB::table('location')
                ->join('region', 'location.region_id', '=', 'region.id')
                ->join('company', 'location.company_id', '=', 'company.id')
                ->select('location.*', 'region.region', 'company.company_name_short')
                ->where('location.id', '=', $location_id)
                ->get();
        return $row;
    }

    function getServiceHistory($asset_id) {
        $row = \DB::table('game_service_history')
                        ->leftJoin('location as l', 'game_service_history.location_id', '=', 'l.id')
                        ->leftJoin('game as g', 'game_service_history.game_id', '=', 'g.id')
                        ->leftJoin('game_title as gt', 'g.game_title_id', '=', 'gt.id')
                        ->leftJoin('users as u1', 'game_service_history.down_user_id', '=', 'u1.id')
                        ->leftJoin('users as u2', 'game_service_history.up_user_id', '=', 'u2.id')
                        ->select(
                                'game_service_history.id', 
                                'game_service_history.game_id', 
                                'game_service_history.location_id', 
                                'game_service_history.date_down', 
                                'game_service_history.problem', 
                                'game_service_history.down_user_id', 
                                'game_service_history.solution', 
                                'game_service_history.date_up', 
                                'game_service_history.up_user_id', 
                                'l.id as location_id', 
                                'l.location_name', 
                                'l.location_name_short', 
                                'gt.id as game_title_id', 
                                'gt.game_title', 
                                'u1.first_name as down_first_name', 
                                'u1.last_name as down_last_name', 
                                'u2.first_name as up_first_name', 
                                'u2.last_name as up_last_name'
                            )
                        ->whereRaw('gt.id = (SELECT game_title_id FROM game WHERE id = '.$asset_id.')')                        
                        ->orderBy('id', 'desc')
                        ->get();
        return $row;
    }

    function getMoveHistory($asset_id = null) {
        $query = \DB::table('game_move_history')
                ->leftJoin('users as u1', 'game_move_history.from_by', '=', 'u1.id')
                ->leftJoin('users as u2', 'game_move_history.to_by', '=', 'u2.id')
                ->leftJoin('location as l1', 'game_move_history.from_loc', '=', 'l1.id')
                ->leftJoin('location as l2', 'game_move_history.to_loc', '=', 'l2.id')
                ->leftJoin('game as g', 'game_move_history.game_id', '=', 'g.id')
                ->leftJoin('game_title as gt', 'g.game_title_id', '=', 'gt.id')
                ->select('game_move_history.*', 'gt.game_title', 'u1.first_name as from_first_name','u1.last_name as from_last_name', 'u2.first_name as to_first_name', 'u2.last_name as to_last_name', 'l1.location_name as from_location','l1.id as from_location_id', 'l2.location_name as to_location','l2.id as to_location_id');
        if (!is_null($asset_id)) {
            $assetIds = explode(',', ''.$asset_id);
            $query = $query->whereIn('game_id', $assetIds);
        }
        $query->orderBy('id', 'desc');
        //die($query->toSql());
        $row = $query->get();
        return $row;
    }

    function moveHistory() {

// of course to revert the fetch mode you need to set it again

        $row = \DB::select('SELECT CONCAT(A.id," | ",IF(A.test_piece = 1,CONCAT("**TEST** ",T.game_title),T.game_title)) AS Game,
										 CONCAT(G.from_loc," | ", L1.location_name_short) AS from_location,
										 U1.username AS from_name,
										 G.from_date,
										 CONCAT(G.to_loc," | ", L2.location_name_short) AS to_location,
										 U2.username AS to_name,
										 IF(G.to_date = "0000-00-00 00:00:00","", G.to_date) AS to_date
									FROM game_move_history G
							   LEFT JOIN game A ON A.id = G.game_id
							   LEFT JOIN users U1 ON G.from_by = U1.id
							   LEFT JOIN users U2 ON G.to_by = U2.id
							   LEFT JOIN game M ON G.game_id = M.id
							   LEFT JOIN game_title T ON M.game_title_id = T.id
							   LEFT JOIN location L1 ON G.from_loc = L1.id
							   LEFT JOIN location L2 ON G.to_loc = L2.id');
        return $row;
    }

    function getPendingList($asset_id = null) {
        $rows = \DB::Select("SELECT 
                    V.vendor_name AS Manufacturer, 
                    T.game_title AS Game_Title, 
                    G.version, 
                    G.serial, 
                    G.id, 
                    G.location_id, 
                    L.city, 
                    L.state, 
                    G.sale_price AS Wholesale,
                    IF(G.sale_price >= 1000,
                        ROUND(((G.sale_price*1.1)-1)/10+.5)*10+5,
                        (G.sale_price+100)
                        ) AS Retail, 
                    G.notes 
            FROM game G  
            LEFT JOIN game_title T ON G.game_title_id = T.id 
            LEFT JOIN vendor V ON V.id = T.mfg_id 
            LEFT JOIN location L ON G.location_id = L.id 
            WHERE 
                G.sale_pending = 1 
                AND G.sold = 0" . 
                (empty($asset_id)? "": " AND G.id IN ($asset_id)"). 
            " ORDER BY T.game_title ASC, G.location_id");
        return $rows;
    }

    function getForSaleList($asset_id = null) {
        $rows = \DB::Select("SELECT 
                    V.vendor_name AS Manufacturer,
                    T.game_title AS Game_Title, 
                    G.version, 
                    G.serial, 
                    IF(G.date_in_service = '0000-00-00','', G.date_in_service) AS 'date_service', 
                    G.id, 
                    G.location_id, 
                    L.city, 
                    L.state, 
                    G.sale_price AS Wholesale,
                    IF(G.sale_price >= 1000,
                        ROUND(((G.sale_price*1.1)-1)/10+.5)*10+5,
                        (G.sale_price+100)
                        ) AS Retail
                FROM game G
                LEFT JOIN game_title T ON G.game_title_id = T.id
                LEFT JOIN vendor V ON V.id = T.mfg_id
                LEFT JOIN location L ON G.location_id = L.id
            WHERE G.for_sale = 1
                AND G.sale_pending = 0 
                AND G.status_id!=3 
                AND G.sold = 0" . 
                (empty($asset_id)? "": " AND G.id IN ($asset_id)"). 
            " ORDER BY T.game_title ASC, G.location_id");
        return $rows;
    }

    function getVendorPorductlist($vendor_id) {
        $row = \DB::Select("SELECT V.vendor_name AS Vendor, P.vendor_description AS Description, P.sku, ROUND(P.case_price/P.num_items,2) AS Unit_Price,
									 P.num_items AS Items_Per_Case, P.case_price AS Case_Price, P.ticket_value AS Ticket_Value, O.order_type AS Order_Type,
									 T.type_description AS Product_Type,  Y.yesno AS INACTIVE FROM products P
						   LEFT JOIN vendor V ON V.id = P.vendor_id
						   LEFT JOIN product_type T ON T.id = P.prod_sub_type_id
						   LEFT JOIN order_type O ON O.id = P.prod_type_id
						   LEFT JOIN yes_no Y on Y.id = P.inactive
                           WHERE P.vendor_id=$vendor_id
							ORDER BY P.vendor_description");
        return $row;
    }

    function getOrderData($order_id,$pass = null) {

        $case_price_categories = [];
        if(isset($pass['calculate price according to case price']))
        {
            $case_price_categories = explode(',',$pass['calculate price according to case price']->data_options);
        }
        $case_price_if_no_unit_categories = [];
        if(isset($pass['use case price if unit price is 0.00']))
        {
            $case_price_if_no_unit_categories = explode(',',$pass['use case price if unit price is 0.00']->data_options);
        }
        \DB::setFetchMode(\PDO::FETCH_ASSOC);
        $row = \DB::select('SELECT U1.first_name, 
                                    U1.last_name,
                                    U1.email,
                                    C.company_name_short,
                                    C.company_name_long,
                                    O.date_ordered,
										  O.order_type_id,
                                          L.location_name_short AS loc_name_short,
                                          L.id AS loc_id,
                                          L.loading_info,
										  L.loc_ship_to AS loc_ship_to,
                                          U2.email AS loc_contact_email, 
                                          U3.email AS loc_merch_contact_email,
										  V.vendor_name,
                                          V.street1 AS vend_street1,
                                          V.city AS vend_city,
                                          V.state AS vend_state,
                                          V.zip AS vend_zip,
										  V.contact AS vend_contact,
                                          V.email AS vend_email,
                                          V.bill_account_num as billing_account,
                                          O.order_description,
                                          O.order_total,
                                          O.po_number,
										  O.alt_address,
                                          F.freight_type, 
                                          O.new_format,
                                          O.po_notes
								     FROM orders O
								LEFT JOIN company C ON C.id = O.company_id
								LEFT JOIN location L ON L.id = O.location_id
								LEFT JOIN users U1 ON U1.id = O.user_id
								LEFT JOIN user_locations UL2 ON UL2.location_id = L.id AND UL2.group_id=101
								LEFT JOIN users U2 ON U2.id = UL2.user_id
								LEFT JOIN user_locations UL3 ON UL3.location_id = L.id AND UL3.group_id=102
								LEFT JOIN users U3 ON U3.id = UL3.user_id
								LEFT JOIN vendor V ON V.id = O.vendor_id
								LEFT JOIN freight F ON F.id = O.freight_id
								    WHERE O.id=' . $order_id);
        $alt_address = $row[0]['alt_address'];
        if (empty($row[0]['loc_ship_to'])) {
            $location_id = $row[0]['loc_id'];
        } else {
            $location_id = $row[0]['loc_ship_to'];
            $row[0]['for_location'] = $row[0]['loc_name_short'];
        }
        if (empty($alt_address)) {
            $query = \DB::select('SELECT location_name,street1,city,state,zip,attn FROM location WHERE id=' . $location_id);
            $row[0]['po_location'] = $query[0]['location_name'];
            $row[0]['po_street1_ship'] = $query[0]['street1'];
            $row[0]['po_city_ship'] = $query[0]['city'];
            $row[0]['po_state_ship'] = $query[0]['state'];
            $row[0]['po_zip_ship'] = $query[0]['zip'];
            $row[0]['po_attn'] = $query[0]['attn'];
        } else {
            $shippingDetail = explode("|",$alt_address);
            $pipe1 = strpos($alt_address, '|');
            $pipe2 = strpos($alt_address, '|', $pipe1 + 1);
            $pipe3 = strpos($alt_address, '|', $pipe2 + 1);
            $pipe4 = strpos($alt_address, '|', $pipe3 + 1);
            $location = substr($alt_address, 0, $pipe1);
            $street = substr($alt_address, $pipe1 + 1, $pipe2 - $pipe1 - 1);


            $loading_info_new = substr($alt_address, $pipe3 + 1);

            $row[0]['po_location'] = $location;
            $row[0]['po_street1_ship'] = $street;
            $row[0]['po_city_ship'] = $shippingDetail[2];
            $row[0]['loading_info'] = $loading_info_new;
            $row[0]['po_state_ship'] = $shippingDetail[3];
            $row[0]['po_city_zip'] =  $shippingDetail[4];
            $row[0]['po_add_notes'] = $shippingDetail[5];
            $row[0]['po_attn'] = '';
            $row[0]['company_name_long'] = '';
        }
        if ($row[0]['new_format'] == 1) {
            $contentsQuery = \DB::select("SELECT O.item_name AS description,if(O.product_id=0,O.sku,P.sku) AS sku, O.price AS price, O.qty AS qty,O.case_price
                                            FROM order_contents O 
                                            LEFT JOIN products P ON P.id = O.product_id 
                                            WHERE O.order_id = $order_id");
            $row[0]['requests_item_count'] = 0;
            $orderTypeId=$row[0]['order_type_id'];
            foreach ($contentsQuery as $r) {
                $row[0]['requests_item_count'] = $row[0]['requests_item_count'] + 1;
                //if sku is not empty then concat it with description for PO PDF
                $orderDescriptionArray[] = empty($r['sku'])?$r['description']:$r['description']." (SKU - {$r['sku']})";
                $orderPriceArray[] = $r['price'];
                $orderQtyArray[] = $r['qty'];
                if(in_array($orderTypeId,$case_price_categories))
                {
                    $orderItemsPriceArray[] = $r['case_price'];
                }
                elseif(in_array($orderTypeId,$case_price_if_no_unit_categories))
                {
                    $orderItemsPriceArray[] = ($r['price'] == 0.00)?$r['case_price']:$r['price'];
                }
                else
                {
                    $orderItemsPriceArray[] = $r['price'];
                }
            }

            $row[0]['orderDescriptionArray'] = $orderDescriptionArray;
            $row[0]['orderPriceArray'] = $orderPriceArray;
            $row[0]['orderItemsPriceArray']=$orderItemsPriceArray;
            $row[0]['orderQtyArray'] = $orderQtyArray;
        }

        \DB::setFetchMode(\PDO::FETCH_CLASS);
        return $row;
    }

    function get_user_data($data = null) {
        $data['user_id'] = \Session::get('uid');
        $data['company_id'] = \Session::get('company_id');
        $company_id = $data['company_id'];
        if ($company_id == 1 || $company_id == 2 || $company_id == 3) {
            $data['company'] = 'Family Entertainment Group';
        }
        if ($company_id == 4) {
            $data['company'] = 'Cleveland Coin Machine Exchange';
        }
        if ($company_id == 5) {
            $data['company'] = 'Wilderness Resorts';
        }
        if ($company_id == 6) {
            $data['company'] = 'Fiesta Village';
        }
        $data['user_name'] = \Session::get('user_name');
        $data['first_name'] = \Session::get('ufname');
        $data['last_name'] = \Session::get('ulname');
        $data['email'] = \Session::get('eid');
        $data['selected_location'] = \Session::get('selected_location');
        $data['selected_location_name'] = \Session::get('selected_location_name');
        $user_level = \Session::get('gid');

        if ($user_level == Groups::USER) {
            $data['user_level'] = 'user';
        }
        if ($user_level == Groups::PARTNER) {
            $data['user_level'] = 'partner';
        }
        if ($user_level == Groups::MERCHANDISE_MANAGER) {
            $data['user_level'] = 'merchmgr';
        }
        if ($user_level == Groups::FIELD_MANAGER) {
            $data['user_level'] = 'fieldmgr';
        }
        if ($user_level == Groups::OFFICE_MANAGER) {
            $data['user_level'] = 'officemgr';
        }
        if ($user_level == Groups::DISTRICT_MANAGER) {
            $data['user_level'] = 'distmgr';
        } // TREATED AS REGULAR USER - BELOW
        if ($user_level == Groups::FINANCE_MANAGER) {
            $data['user_level'] = 'financemgr';
        }
        if ($user_level == Groups::PARTNER_PLUS) {
            $data['user_level'] = 'partnerplus';
        } //ADDS ACCESS TO MERCH REQUEST
        if ($user_level == Groups::GUEST) {
            $data['user_level'] = 'guest';
        }
        if ($user_level == Groups::SUPPER_ADMIN) {
            $data['user_level'] = 'superadmin';
        }
        if ($user_level == Groups::TECHNICAL_MANAGER) {
            $data['user_level'] = 'techmgr';
        }
        if ($user_level == Groups::USER || $user_level == Groups::PARTNER || $user_level == Groups::DISTRICT_MANAGER || $user_level == Groups::PARTNER_PLUS || $user_level == Groups::TECHNICAL_MANAGER) {
            $data['user_group'] = 'regusers';
        }
        if ($user_level == Groups::MERCHANDISE_MANAGER || $user_level == Groups::FIELD_MANAGER || $user_level == Groups::OFFICE_MANAGER || $user_level == Groups::FINANCE_MANAGER || $user_level == Groups::GUEST || $user_level == Groups::SUPPER_ADMIN) {
            $data['user_group'] = 'allmgrs';
        }
        $get_locations_by_region = \Session::get('get_locations_by_region');
        //$login_type = $this->session->userdata('login_type');
        // $data['loc_1'] = \Session::get('user_locations[0]->id');

        $loc_count = 10;
        $data['email_2'] = \Session::get('email_2');
        $data['primary_phone'] = \Session::get('primary_phone');
        $data['secondary_phone'] = \Session::get('secondary_phone');
        $data['street'] = \Session::get('street');
        $data['city'] = \Session::get('city');
        $data['state'] = \Session::get('state');
        $data['zip'] = \Session::get('zip');
        $data['reg_id'] = \Session::get('reg_id');
        //$data['reg_name'] = $this->session->userdata('region');
        //$data['reg_loc_ids'] = $this->session->userdata('reg_loc_ids');
        $data['restricted_mgr_email'] = \Session::get('restricted_mgr_email');
        $data['restricted_user_email'] = \Session::get('restricted_user_email');

        /*  $this->load->library('user_agent');
          if ($this->agent->is_mobile())
          {
          $data['agent'] = $this->agent->mobile();
          $data['agent_type'] = 'mobile';
          }
          else if ($this->agent->is_browser())
          {
          $data['agent'] = $this->agent->browser().' '.$this->agent->version();
          $data['agent_type'] = 'browser';
          }
          else if ($this->agent->is_robot())
          {
          $data['agent'] = $this->agent->robot();
          $data['agent_type'] = 'robot';
          }
          else
          {
          $data['agent'] = 'Unidentified User Agent';
          $data['agent_type'] = 'undefined';
          }
         */

        // HEADER DETAIL START
        $header_detail = $data['first_name'] . ' is viewing location <b>' . $data['selected_location'] . ' | ' . $data['selected_location_name'] . '</b>. Select to change your location view - ';
        $locations_count = 0;
        // HEADER DETAIL END
        $browser_info = $_SERVER['HTTP_USER_AGENT'];
        return $data;
    }

    /**
     * Get Location info (as object or as string) from location table
     * If a field name is passed as second parameter then the value for that field is returned. 
     * For example, to get the name of a location use get_location_info_by_id(2001, 'location_name')
     * 
     * @param number $loc_id
     * @param string $field
     * @param string $default
     * @return mixed
     */
    public function get_location_info_by_id($loc_id = null, $field = null, $default = "No Location with That ID") {
        return \SiteHelpers::getLocationInfoById($loc_id, $field, $default);
    }
    public function get_game_info_by_id($game_id=null, $field=null, $default = 'NONE')
    {
        $fieldName = empty($field) ? " G.*, T.game_title, T.game_type_id as game_type_id_from_title " : $field;
        $query =\DB::select('SELECT '.$fieldName.'
								 FROM game_title T
						 	LEFT JOIN game G ON G.game_title_id = T.id
							    WHERE G.id = '.$game_id);

        $data = [];
        if (isset($query[0])) {
            $data = $query[0];
        }
        if (is_null($field)) {
            return $data;
        }
        $field2 = explode('.',$field);
        if(isset($field2[1]))
        {
            $field = $field2[1];
        }
        if (!empty($data)) {
            if (is_array($data)) {
                $value = $data[$field];
            }        
            else {
                $value = $data->$field;
            }
        }
        if (empty($value)) {
            $value = $default;
        }
        return $value;

    }
    public function get_user_emails($user_level = null, $loc_id = null)
    {
        $comma_separated_emails = '';
        $location_statement = '';
        $region_statement = '';

        if (!empty($loc_id)) {

            $location_statement = " AND UL.location_id IN ($loc_id)";
            $query_table = 'users';
        }

        if ($user_level == 'all_users') {
            $user_level_statement = ' AND U.group_id IN('.Groups::USER.','.Groups::PARTNER.','.Groups::MERCHANDISE_MANAGER.','.Groups::FIELD_MANAGER.','.Groups::OFFICE_MANAGER.','.Groups::DISTRICT_MANAGER.','.Groups::FINANCE_MANAGER.','.Groups::PARTNER_PLUS.','.Groups::GUEST.','.Groups::SUPPER_ADMIN.') ';
            $query_table = 'users';
        }
        if ($user_level == 'all_employees') {
            $user_level_statement = ' AND U.group_id IN('.Groups::USER.','.Groups::MERCHANDISE_MANAGER.','.Groups::FIELD_MANAGER.','.Groups::OFFICE_MANAGER.','.Groups::DISTRICT_MANAGER.','.Groups::FINANCE_MANAGER.','.Groups::SUPPER_ADMIN.') ';
            $query_table = 'users';
        }
        if ($user_level == 'all_managers') {
            $user_level_statement = ' AND U.group_id IN('.Groups::MERCHANDISE_MANAGER.','.Groups::FIELD_MANAGER.','.Groups::OFFICE_MANAGER.','.Groups::DISTRICT_MANAGER.','.Groups::FINANCE_MANAGER.','.Groups::SUPPER_ADMIN.') ';
            $query_table = 'users';
        }
        if ($user_level == 'technical_contact') {
            $user_level_statement = ' AND U.is_tech_contact = 1 ';
            $location_statement = " AND L.id IN($loc_id) ";
            $query_table = 'users';
        }
        if ($user_level == 'users_plus_district_managers') {
            $user_level_statement = ' AND (U.group_id IN('.Groups::USER.','.Groups::OFFICE_MANAGER.','.Groups::DISTRICT_MANAGER.')  ' .
                    ' OR U.id IN (SELECT user_id FROM user_locations WHERE group_id IN ('.Groups::DISTRICT_MANAGER.') AND location_id IN (' . $loc_id . '))) ';
            $location_statement = " AND L.id IN($loc_id) ";
            $query_table = 'users';
        }
        if ($user_level == 'users_plus_district_and_field_managers') {
            $location_statement = " AND L.id IN ($loc_id) ";
            $user_level_statement = ' AND (U.group_id IN('.Groups::USER.','.Groups::FIELD_MANAGER.','.Groups::OFFICE_MANAGER.','.Groups::DISTRICT_MANAGER.') '.
                    ' OR U.id IN (SELECT user_id FROM user_locations WHERE group_id IN (1,6) AND location_id IN (' . $loc_id . '))) ';
            $query_table = 'users';
        }
        if ($user_level == 'location_manager_and_field_manager') {
            $location_statement = ' AND L.id = ' . $loc_id;
            $query_table = 'location';
        }
        // 1	User	user
        // 2	Partner	partner
        // 3	Merch Manager	merchmgr
        // 4	Field Manager	fieldmgr
        // 5	Office Manager	officemgr
        // 6	District Manager	distmgr
        // 7	Finance Manager	financemgr
        // 8	Partner Plus	partnerplus
        // 9	Guest	guest
        // 10	Super Admin	superadmin


        if ($query_table == 'users') {
            $sql = 'SELECT GROUP_CONCAT(DISTINCT U.email separator ",") as Emails
								 FROM users U
								LEFT JOIN user_locations UL ON UL.user_id=U.id
								LEFT JOIN location L ON L.id=UL.location_id
                               WHERE U.active = 1 '
                . $user_level_statement
                . $location_statement;
            $query =\DB::select($sql);
            foreach ($query as $row) {
                $comma_separated_emails = $row->Emails;
            }

        } else if ($query_table == 'location') {
            $sql = 'SELECT CONCAT(Umain.email,", ",Ufield.email,", ",Uregion.email) as Emails
									 FROM location L
                                LEFT JOIN user_locations ULc ON ULc.location_id = L.id AND ULc.group_id=101
                                LEFT JOIN user_locations ULf ON ULf.location_id = L.id AND ULf.group_id=1
                                LEFT JOIN user_locations ULr ON ULr.location_id = L.id AND ULr.group_id=6
								LEFT JOIN users Umain ON Umain.id = ULc.user_id
								LEFT JOIN users Ufield ON Ufield.id = ULf.user_id
								LEFT JOIN users Uregion ON Uregion.id = ULr.user_id
									WHERE Umain.active = 1
									  AND Ufield.active = 1
									  AND Uregion.active = 1
									    ' . $location_statement;
            $query = \DB::select($sql);


            foreach ($query as $row) {
                $comma_separated_emails = $row->Emails;
            }
        }

        return $comma_separated_emails;
    }

    public static function getUserAssignedLocation(){
        $locations = \SiteHelpers::getLocationDetails(\Session::get('uid'));
        return $locations;
    }

    function totallyRecordInCart()
    {
        if(empty(\Session::get('selected_location')))
        {
            $obj = new \stdClass();
            $obj->total = 0;
            $total = [
                0 => $obj
            ];
            return $total;
        }
        /*$data['user_level'] = \Session::get('gid');
        if ($data['user_level'] == Groups::MERCHANDISE_MANAGER || $data['user_level'] == Groups::FIELD_MANAGER || $data['user_level'] == Groups::OFFICE_MANAGER || $data['user_level'] == Groups::FINANCE_MANAGER || $data['user_level'] == Groups::GUEST || $data['user_level'] == Groups::SUPPER_ADMIN) {
           $status_id = 9; /// 9 IS USED AS AN ARBITRARY DELIMETER TO KEEP CART SEPERATE FROM LOCATIONS' OWN
        } else {
            $status_id = 4;
        }*/
        $status_id = 4;
        return \DB::select("SELECT COUNT(*) as total FROM requests WHERE request_user_id = ".\Session::get('uid')." AND status_id = $status_id AND location_id = ".\Session::get('selected_location'));
    }

    public static function processApiData($json,$param=null){
        return $json;
    }

    /**
     * Returns submitted search filter values in an associative array
     * LIMITATION: This is an archaic version with simple targets of getting filters into almost a flat array
     *      It does not return the filter criteria/operator. 
     *      It does not return both the values of a BETWEEN type filter. It only picks up the first value.
     *      For advanced search use getSearchFiltersAsArray method instead
     * 
     * @param array $requiredFilters [optional] If this parameter is given only 
     *                      filters with names matching the keys of this array are returned. 
     *                      Example: ['locaton_id' => '', 'game_id' => ''] will return an array 
     *                                  ['locaton_id' => value, 'game_id' => value] 
     * 
     *                      If a value is specified for the key of the parameter array, that value
     *                      replaces the key of the returning array
     * 
     *                      Example: ['locaton_id' => 'locId', 'game_id' => 'game', 'game_title' => ''] will return an array 
     *                                  ['locId' => value, 'game' => value, 'game_title' => value] 
     * 
     * @return array In format ['filterName' => value, ...]
     */    
    public static function getSearchFilters($requiredFilters = array()) {
        $receivedFilters = array();
        $finalFilters = array();
        if (isset($_GET['search'])) {
            $filters_raw = trim($_GET['search'], "|");
            $filters = explode("|", $filters_raw);

            foreach($filters as $filter) {
                $columnFilter = explode(":", $filter);
                if (isset($columnFilter) && isset($columnFilter[0]) && isset($columnFilter[2])) {
                    $receivedFilters[$columnFilter[0]] = $columnFilter[2];
                }
            }
        }
                
        if (empty($requiredFilters)) {
            $finalFilters = $receivedFilters;
        }
        else {
            foreach($requiredFilters as $fieldName => $variableName) {
                if (empty($variableName)) {
                    $variableName = $fieldName;
                }
                if (isset($receivedFilters[$fieldName])) {
                    $finalFilters[$variableName] = $receivedFilters[$fieldName];
                }
                else {
                    $finalFilters[$variableName] = '';
                }
            }
        }
        
        return $finalFilters;
    }    
    
    /**
     * Returns submitted search filter values in an associative array
     * This is an advanced version `getSearchFilters` method where instead of the value
     * each array item of the returned array (filterItem) contains is an associative array with 
     *  fieldName, operator, value, and optional value2 keys
     * 
     * @param string $customSearchString
     * @param array $requiredFilters [optional] If this parameter is given only 
     *                      filters with names matching the keys of this array are returned. 
     *                      Example: ['locaton_id' => '', 'game_id' => ''] will return an array 
     *                                  ['locaton_id' => 'filterItem', 'game_id' => 'filterItem'] 
     * 
     *                      If a value is specified for the key of the parameter array, that value
     *                      replaces the key of the returning array
     * 
     *                      Example: ['locaton_id' => 'locId', 'game_id' => 'game', 'game_title' => ''] will return an array 
     *                                  ['locId' => 'filterItem', 'game' => 'filterItem', 'game_title' => 'filterItem'] 
     * 
     *                  ** NOTE: In case a filter is not set but is sought by $requiredFilters a blank array is returned for that key
     * 
     * @return array In format [
     *                          'filterName' => [
     *                              'fieldName' => 'filterName' , 
     *                              'operator' => 'filterOperator', 
     *                              'value' => 'filterValue or firstValue when BETWEEN operator is used', 
     *                              'valeu2' => 'optional secondValue when BETWEEN operator is used'
     *                          ], 
     *                          ...]
     */   
    public static function getSearchFiltersAsArray($customSearchString = '', $requiredFilters = array()) {
        $receivedFilters = array();
        $finalFilters = array();
        $searchQuerystring = !empty($customSearchString) ? $customSearchString : 
                (isset($_GET['search']) ? $_GET['search'] : '');
        
        if ($searchQuerystring) {
            $filters_raw = trim($searchQuerystring, "|");
            $filters = explode("|", $filters_raw);

            foreach($filters as $filter) {
                $columnFilter = explode(":", $filter);
                $filterData = array();
                list($fieldName, $operator, $value) = $columnFilter;
                $filterData['fieldName'] = $fieldName;
                $filterData['operator'] = $operator;
                $filterData['value'] = $value;
                if (isset($columnFilter[3])) {
                    $filterData['value2'] = $columnFilter[3];
                }
                $receivedFilters[$fieldName] = $filterData;
            }
        }
        
        if (empty($requiredFilters)) {
            $finalFilters = $receivedFilters;
        }
        else {
            foreach($requiredFilters as $fieldName => $variableName) {
                if (empty($variableName)) {
                    $variableName = $fieldName;
                }
                if (isset($receivedFilters[$fieldName])) {
                    $finalFilters[$variableName] = $receivedFilters[$fieldName];
                }
                else {
                    $finalFilters[$variableName] = [];
                }
            }
        }
        return $finalFilters;        
    }
    
    /**
     * Rebuilds a filter querystring from a filter array with with structure resembling the array returned by getSearchFiltersAsArray method
     * Hence, its a reverse of getSearchFiltersAsArray method
     * @param array $filters Array in the following format: [
     *                          'filterName' => [
     *                              'fieldName' => 'filterName' , 
     *                              'operator' => 'filterOperator', 
     *                              'value' => 'filterValue or firstValue when BETWEEN operator is used', 
     *                              'valeu2' => 'optional secondValue when BETWEEN operator is used'
     *                          ], 
     *                          ...]
     * @return string search querystring value and looks like `filterName:operator:value:value2|filterName2:operator:value:value2|`
     */
    public static function buildSearchQuerystringFromArray($filters = array()) {
        $qs = '';
        $qsArray = array();
        foreach($filters as $item) {
            $qsArray[] = implode(':', array_values($item));
        }
        $qs = implode('|', $qsArray).'|';
        
        return $qs;
    }
    
    /**
     * Merge and removes filters to existing filter array
     *  
     * @param array $receivedFilters    [optional] Filter array with structure resembling the array returned by getSearchFiltersAsArray method. 
     *              If not specified it tries to auto generate the array from GET request querystring
     * @param array $add    More items to add or replace (if same key is found) 
     * @param array $skip   Items to remove from the $receivedFilters
     * @return array Filter array with structure resembling the array returned by getSearchFiltersAsArray method. 
     */
    public static function mergeSearchFilters($receivedFilters = null, $add = array(), $skip = array()) {
        $filters = empty($receivedFilters) ? self::getSearchFiltersAsArray() : $receivedFilters;
                
        if (!empty($add)) {
            foreach ($add as $key => $item) {
                $filters[$key] = $item;
            }
        }
        if (!empty($skip)) {
            foreach ($skip as $key) {
                if (isset($filters[$key])) {
                    unset($filters[$key]);
                }
            }
        }
        
        return $filters;        
    }   
    
    /**
     * Shorthand wrapper function to add/delete filters to search querystring 
     * @param array $add    [optional] Items to add or replace (if same key is found) 
     * @param array $skip   [optional] Items to remove from the $receivedFilters
     * @param string $customSearchString    [optional] search querystring
     * @return string   search querystring value and looks like `filterName:operator:value:value2|filterName2:operator:value:value2|` 
     */
    public static function rebuildSearchQuery($add = array(), $skip = array(), $customSearchString = '') {
        $filters = self::getSearchFiltersAsArray($customSearchString);
        $newFilters = self::mergeSearchFilters($filters, $add, $skip);
        $qs = self::buildSearchQuerystringFromArray($newFilters);
        return $qs;
    }
    
    public static function passwordForgetEmails()
    {

        $user_data=\DB::select('SELECT id,email FROM users WHERE active=1');
        $subject = "[ " . CNF_APPNAME . " ] REQUEST PASSWORD RESET ";
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: ' . CNF_APPNAME . ' <' . CNF_EMAIL . '>' . "\r\n";
        foreach($user_data as $email)
        {

           // $user_emails[]= $email->email;
            if (isset($email->email) && !empty($email->email)) {
                $data = array('id' =>$email->id);
                $to = $email->email;
                $message = view('user.emails.auth.reminder', $data);

                //@todo please enable email line in producton environment when itneded to send emails to all users
                FEGSystemHelper::sendSystemEmail(['to'=>$to,
                    'subject' => $subject,
                    'message' => $message,
                    'headers' =>$headers,
                    'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
                    'from' => CNF_EMAIL,
                    'configName' => 'Password Reset Email To All Users'
                ]);
            }

        }
       // $user_emails_string=implode(',',$user_emails);
          return \Redirect::to('user/login')->with('message', \SiteHelpers::alert('success', 'Emails sent successfully'));



    }
    public function populateVendorsDropdown()
    {
        $gid=\Session::get('gid');
        if($gid == Groups::PARTNER || $gid == Groups::PARTNER_PLUS || $gid == Groups::GUEST)
        {
            $where = 'WHERE V.partner_hide = 0 and V.isgame = 1';
        }
        else
        {
            $where = 'Where V.isgame = 1';
        }

        $query = \DB::select('SELECT V.id AS id,
							          V.vendor_name AS text
								 FROM vendor V
								 	  '.$where.' order by V.vendor_name');

        foreach ($query as $row)
        {
            $row = array(
                'id' => $row->id,
                'text' => $row->text
            );
            $array[] = $row;
        }
        return $array;
    }
    function get_youtube_id_from_url($url)
    {
        if (stristr($url,'youtu.be/'))
        {
            preg_match('/(https:|http:|)(\/\/www\.|\/\/|)(.*?)\/(.{11})/i', $url, $final_ID);
            return $final_ID[4];
        }
        elseif(stristr($url,'youtube.com/'))
        {
            preg_match('/(https:|http:|)(\/\/www\.|\/\/|)(.*?)\/(.{11})/i', $url, $final_ID);
            return $final_ID[4];
        }
        else
        {
            @preg_match('/(https:|http:|):(\/\/www\.|\/\/|)(.*?)\/(embed\/|watch.*?v=|)([a-z_A-Z0-9\-]{11})/i', $url, $IDD);
            if(isset($IDD[5]) && !empty($IDD[5]))
            return $IDD[5];
            else
                return false;
        }
    }
    public function populateGamesDropdown($location = null)
    {
        if(empty($location))
        {
            $concat = 'CONCAT(IF(G.location_id = 0, "IN TRANSIT", G.location_id)," | ",IF(G.test_piece = 1,CONCAT("**TEST** ",T.game_title),T.game_title)," | ",G.id)';
            $where = '';
            $orderBy = 'G.status_id DESC,T.game_title';
        }
        else
        {
            if($location == 'plus_notes')
            {
                $concat = 'CONCAT(IF(G.location_id = 0, "IN TRANSIT", G.location_id), " | ",T.game_title," | ",G.id, IF(G.notes = "","", CONCAT(" (",G.notes,")")))';
                $where = '';
            }
            else
            {
                //$concat = 'CONCAT(G.location_id," | ",T.game_title," | ",G.id)';
                $concat = 'CONCAT(IF(G.location_id = 0, "IN TRANSIT", G.location_id), " | ",T.game_title," | ",G.id)';

                $where = 'AND G.location_id in (0,'.$location.')';
            }
            $orderBy = 'G.status_id DESC,L.id,T.game_title';
        }
        $query = \DB::select('SELECT G.id AS id,
									  '.$concat.' AS text
								 FROM game G
							LEFT JOIN game_title T ON T.id = G.game_title_id
							LEFT JOIN location L ON L.id = G.location_id
								WHERE G.sold = 0
									  '.$where.'
							 ORDER BY '.$orderBy);

        foreach ($query as $row) {
            if (!is_null($row->text)) {
                $row = array(
                    'id' => $row->id,
                    'text' => $row->text
                );
                $gamesArray[] = $row;

            }
        }
        if(empty($gamesArray))
        {
            $gamesArray[] = array("id"=> "none"  ,'text'=> 'No Game Found For Selected Location','disabled'=> true);
        }
        $array = $gamesArray;
        return $array;
    }
}
