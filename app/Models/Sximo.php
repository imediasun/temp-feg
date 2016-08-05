<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sximo extends Model {

    public static function getRows($args, $cond = null) {

        $table = with(new static)->table;
        $key = with(new static)->primaryKey;

        extract(array_merge(array(
            'page' => '0',
            'limit' => '0',
            'sort' => '',
            'order' => '',
            'params' => '',
            'global' => 1
                        ), $args));


        $offset = ($page - 1) * $limit;
        $limitConditional = ($page != 0 && $limit != 0) ? "LIMIT  $offset , $limit" : '';
        $orderConditional = ($sort != '' && $order != '') ? " ORDER BY {$sort} {$order} " : '';

        // Update permission global / own access new ver 1.1
        $table = with(new static)->table;
        if ($global == 0)
            $params .= " AND {$table}.entry_by ='" . \Session::get('uid') . "'";
        // End Update permission global / own access new ver 1.1

        $rows = array();
        $select = self::querySelect();

        if(!empty($createdFrom)){
            $cond = "AND DATE(created_at) BETWEEN '$createdFrom' AND '$createdTo'";
        }

        if(!empty($updatedFrom)){

            if(!empty($cond)){
                $cond .= " OR DATE(updated_at) BETWEEN '$updatedFrom' AND '$updatedTo'";
            }
            else{
                $cond .= " AND DATE(updated_at) BETWEEN '$updatedFrom' AND '$updatedTo'";
            }

        }

        if ($cond != null) {
            $select .= self::queryWhere($cond);
        } else {
            $select .= self::queryWhere();
        }
        $result = \DB::select($select . " {$params} " . $cond .self::queryGroup() . " {$orderConditional}  {$limitConditional} ");

        if ($key == '') {
            $key = '*';
        } else {
            $key = $table . "." . $key;
        }

        $counter_select = preg_replace('/[\s]*SELECT(.*)FROM/Usi', 'SELECT count(' . $key . ') as total FROM', $select);
       
        if ($table == "orders") {
            $total = "27000";
        }
        elseif($table=="img_uploads")
        {
        $total="";    
        }
        elseif($table=="freight_orders")
        {
            $total = \DB::select($select . "
				{$params} " . self::queryGroup());
            $total=count($total);
        }
        else {
            $total = \DB::select($counter_select . "
				{$params} " . self::queryGroup());
            $total = $total[0]->total;
            //$total = 1000;       
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

    public  function insertRow($data, $id) {

        $timestampTables = array('vendor','products','orders');
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
                'gridtype' => (isset($data['config']['setting']['gridtype']) ? $data['config']['setting']['gridtype'] : 'native'),
                'orderby' => (isset($data['config']['setting']['orderby']) ? $data['config']['setting']['orderby'] : $r->module_db_key),
                'ordertype' => (isset($data['config']['setting']['ordertype']) ? $data['config']['setting']['ordertype'] : 'asc'),
                'perpage' => (isset($data['config']['setting']['perpage']) ? $data['config']['setting']['perpage'] : '10'),
                'frozen' => (isset($data['config']['setting']['frozen']) ? $data['config']['setting']['frozen'] : 'false'),
                'form-method' => (isset($data['config']['setting']['form-method']) ? $data['config']['setting']['form-method'] : 'native'),
                'view-method' => (isset($data['config']['setting']['view-method']) ? $data['config']['setting']['view-method'] : 'native'),
                'inline' => (isset($data['config']['setting']['inline']) ? $data['config']['setting']['inline'] : 'false'),
                'usesimplesearch' => (isset($data['config']['setting']['usesimplesearch']) ? $data['config']['setting']['usesimplesearch'] : 'false' ),
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
                $row = \DB::select("SELECT * FROM " . $table . " " . $condition . " AND " . $parent[0] . " = '" . $parent[1] . "'");
            } else {
                $row = \DB::select("SELECT * FROM " . $table . " " . $condition);
            }
        } else {

            $table = $params[0];
            if (count($parent) >= 2) {
                $row = \DB::table($table)->where($parent[0], $parent[1])->get();
            } else {
                $order = substr($params['2'], 0, strpos($params['2'], '|'));
                if (!$order) {
                    $order = $params['2'];
                }
                if (!isset($params['3'])) {
                    $row = \DB::table($table)->orderBy($order, 'asc')->get();
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
                        ->leftJoin('users as u1', 'game_service_history.down_user_id', '=', 'u1.id')
                        ->leftJoin('users as u2', 'game_service_history.up_user_id', '=', 'u2.id')
                        ->select('game_service_history.*', 'u1.first_name as down_first_name', 'u1.last_name as down_last_name', 'u2.first_name as up_first_name', 'u2.last_name as up_last_name')
                        ->where('game_id', '=', $asset_id)->get();
        return $row;
    }

    function getMoveHistory($asset_id = null) {
        $row = \DB::table('game_move_history')
                ->leftJoin('users as u1', 'game_move_history.from_by', '=', 'u1.id')
                ->leftJoin('users as u2', 'game_move_history.to_by', '=', 'u2.id')
                ->leftJoin('location as l1', 'game_move_history.from_loc', '=', 'l1.id')
                ->leftJoin('location as l2', 'game_move_history.to_loc', '=', 'l2.id')
                ->select('game_move_history.*', 'u1.username as from_name', 'u2.username as to_name', 'l1.location_name as from_location', 'l2.location_name as to_location');
        if ($asset_id != null) {

            $row = $row->where('game_id', '=', $asset_id);
        }
        $row = $row->get();
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

    function getPendingList() {
        $rows = \DB::Select("SELECT V.vendor_name AS Manufacturer,T.game_title AS Game_Title, G.version, G.serial, G.id, G.location_id, L.city, L.state, G.sale_price AS Wholesale,
									IF(G.sale_price >= 1000,
									ROUND(((G.sale_price*1.1)-1)/10+.5)*10+5,
									(G.sale_price+100)
									) AS Retail, G.notes FROM game G  LEFT JOIN game_title T ON G.game_title_id = T.id LEFT JOIN vendor V ON V.id = T.mfg_id LEFT JOIN location L ON G.location_id = L.id WHERE G.sale_pending = 1 AND G.sold = 0 ORDER BY T.game_title ASC, G.location_id");
        return $rows;
    }

    function getForSaleList() {
        $rows = \DB::Select("SELECT V.vendor_name AS Manufacturer,T.game_title AS Game_Title, G.version, G.serial, IF(G.date_in_service = '0000-00-00','', G.date_in_service) AS 'date_service', G.id, G.location_id, L.city, L.state, G.sale_price AS Wholesale,
										IF(G.sale_price >= 1000,
										ROUND(((G.sale_price*1.1)-1)/10+.5)*10+5,
										(G.sale_price+100)
										) AS Retail
									FROM game G
							   LEFT JOIN game_title T ON G.game_title_id = T.id
							   LEFT JOIN vendor V ON V.id = T.mfg_id
							   LEFT JOIN location L ON G.location_id = L.id
								   WHERE G.for_sale = 1
    AND G.sale_pending = 0 AND G.status_id!=3 AND G.sold = 0 ORDER BY T.game_title ASC, G.location_id");
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

    function getOrderData($order_id) {
        \DB::setFetchMode(\PDO::FETCH_ASSOC);
        $row = \DB::select('SELECT U1.first_name, U1.last_name,U1.email,C.company_name_short,C.company_name_long,O.date_ordered,
										  O.order_type_id,L.location_name_short AS loc_name_short,L.id AS loc_id,L.loading_info,
										  L.loc_ship_to AS loc_ship_to,U2.email AS loc_contact_email, U3.email AS loc_merch_contact_email,
										  V.vendor_name,V.street1 AS vend_street1,V.city AS vend_city,V.state AS vend_state,V.zip AS vend_zip,
										  V.contact AS vend_contact,V.email AS vend_email,O.order_description,O.order_total,O.po_number,
										  O.alt_address,F.freight_type, O.new_format,O.po_notes
								     FROM orders O
								LEFT JOIN company C ON C.id = O.company_id
								LEFT JOIN location L ON L.id = O.location_id
								LEFT JOIN users U1 ON U1.id = O.user_id
								LEFT JOIN users U2 ON U2.id = L.contact_id
								LEFT JOIN users U3 ON U3.id = L.merch_contact_id
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
            $pipe1 = strpos($alt_address, '|');
            $pipe2 = strpos($alt_address, '|', $pipe1 + 1);
            $pipe3 = strpos($alt_address, '|', $pipe2 + 1);

            $location = substr($alt_address, 0, $pipe1);
            $street = substr($alt_address, $pipe1 + 1, $pipe2 - $pipe1 - 1);
            $city_state_zip = substr($alt_address, $pipe2 + 1, $pipe3 - $pipe2 - 1);
            $loading_info_new = substr($alt_address, $pipe3 + 1);

            $row[0]['po_location'] = $location;
            $row[0]['po_street1_ship'] = $street;
            $row[0]['po_city_ship'] = $city_state_zip;
            $row[0]['loading_info'] = $loading_info_new;
            $row[0]['po_state_ship'] = '';
            $row[0]['po_city_shippo_zip_ship'] = '';
            $row[0]['po_attn'] = '';
            $row[0]['company_name_long'] = '';
        }
        if ($row[0]['new_format'] == 1) {
            $contentsQuery = \DB::select('SELECT IF(O.product_description = "" && O.product_id != 0, CONCAT(P.vendor_description, " (SKU-",P.sku,")"), O.product_description) AS description,
													  O.price AS price, O.qty AS qty FROM order_contents O LEFT JOIN products P ON P.id = O.product_id
												WHERE O.order_id = ' . $order_id);
            $row[0]['requests_item_count'] = 0;
            foreach ($contentsQuery as $r) {

                $row[0]['requests_item_count'] = $row[0]['requests_item_count'] + 1;
                $orderDescriptionArray[] = $r['description'];
                $orderPriceArray[] = $r['price'];
                $orderQtyArray[] = $r['qty'];
            }

            $row[0]['orderDescriptionArray'] = $orderDescriptionArray;
            $row[0]['orderPriceArray'] = $orderPriceArray;
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
        if ($user_level == 1) {
            $data['user_level'] = 'user';
        }
        if ($user_level == 2) {
            $data['user_level'] = 'partner';
        }
        if ($user_level == 3) {
            $data['user_level'] = 'merchmgr';
        }
        if ($user_level == 4) {
            $data['user_level'] = 'fieldmgr';
        }
        if ($user_level == 5) {
            $data['user_level'] = 'officemgr';
        }
        if ($user_level == 6) {
            $data['user_level'] = 'distmgr';
        } // TREATED AS REGULAR USER - BELOW
        if ($user_level == 7) {
            $data['user_level'] = 'financemgr';
        }
        if ($user_level == 8) {
            $data['user_level'] = 'partnerplus';
        } //ADDS ACCESS TO MERCH REQUEST
        if ($user_level == 9) {
            $data['user_level'] = 'guest';
        }
        if ($user_level == 10) {
            $data['user_level'] = 'superadmin';
        }
        if ($user_level == 11) {
            $data['user_level'] = 'techmgr';
        }
        if ($user_level == 1 || $user_level == 2 || $user_level == 6 || $user_level == 8 || $user_level == 11) {
            $data['user_group'] = 'regusers';
        }
        if ($user_level == 3 || $user_level == 4 || $user_level == 5 || $user_level == 7 || $user_level == 9 || $user_level == 10) {
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

    public function get_location_info_by_id($loc_id = null, $field = null) {

        if(is_null($field)){
            $query = \DB::select('SELECT * FROM location WHERE id = ' . $loc_id);
        }
        else{
            $query = \DB::select('SELECT ' . $field . ' FROM location WHERE id = ' . $loc_id);
        }

        foreach ($query as $row) {
            $location_info = $row->$field;
        }

        if (empty($location_info)) {
            $location_info = 'No Location with That ID';
        }
        return $location_info;
    }
    public function get_game_info_by_id($game_id=null,$field=null)
    {
        $query =\DB::select('SELECT '.$field.'
								 FROM game_title T
						 	LEFT JOIN game G ON G.game_title_id = T.id
							    WHERE G.id = '.$game_id);

        foreach($query as $row)
        {
            $game_info = $row->$field;
        }

        if(empty($game_info))
        {
            $game_info = 'NONE';
        }

        return $game_info;

    }
    public function get_user_emails($user_level = null, $loc_id = null)
    {
        $comma_separated_emails = '';
        $location_statement = '';
        $region_statement = '';

        if (!empty($loc_id)) {

            $location_statement = 'AND UL.location_id=' . $loc_id;
            $query_table = 'users';
        }

        if ($user_level == 'all_users') {
            $user_level_statement = ' AND U.group_id IN(1,2,3,4,5,6,7,8,9,10)';
            $query_table = 'users';
        }
        if ($user_level == 'all_employees') {
            $user_level_statement = ' AND U.group_id IN(1,3,4,5,6,7,10)';
            $query_table = 'users';
        }
        if ($user_level == 'all_managers') {
            $user_level_statement = ' AND U.group_id IN(3,4,5,6,7,10)';
            $query_table = 'users';
        }
        if ($user_level == 'technical_contact') {
            $user_level_statement = ' AND U.is_tech_contact = 1';
            $location_statement = ' AND L.id = ' . $loc_id;
            $query_table = 'users';
        }
        if ($user_level == 'users_plus_district_managers') {
            $user_level_statement = ' AND U.group_id IN(1,5,6)';
            $location_statement = ' L.id=' . $loc_id . ' OR U.reg_id = (SELECT L.region_id FROM location L WHERE L.id = ' . $loc_id . '))';
            $query_table = 'users';
        }
        if ($user_level == 'users_plus_district_and_field_managers') {
            $user_level_statement = ' AND U.group_id IN(1,4,5,6)';
            $location_statement = ' AND (UL.location_id = ' . $loc_id . ' OR U.id = (SELECT L.field_manager_id FROM location L WHERE L.id = ' . $loc_id . ') OR U.reg_id = (SELECT L.region_id FROM location L WHERE L.id = ' . $loc_id . '))';
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
								LEFT JOIN region R ON R.id = L.region_id
                               WHERE U.active = 1'
                . $user_level_statement
                . $location_statement;
            $query =\DB::select($sql);
            foreach ($query as $row) {
                $comma_separated_emails = $row->Emails;
            }

        } else if ($query_table == 'location') {

            $query = \DB::select('SELECT CONCAT(Umain.email,", ",Ufield.email,", ",Uregion.email) as Emails
									 FROM location L
								LEFT JOIN region R ON R.id = L.region_id
								LEFT JOIN users Umain ON Umain.id = L.contact_id
								LEFT JOIN users Ufield ON Ufield.id = L.field_manager_id
								LEFT JOIN users Uregion ON Uregion.id = R.dist_mgr_id
									WHERE Umain.active = 1
									  AND Ufield.active = 1
									  AND Uregion.active = 1
									    ' . $location_statement);


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


}
