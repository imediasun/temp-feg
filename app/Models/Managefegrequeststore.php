<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Search\Searchable;
use App\Repositories\Managefegrequeststore\ManagefegrequeststoreRepository;
use App\Repositories\Managefegrequeststore\ElasticsearchManagefegrequeststoreRepository;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Client;
class managefegrequeststore extends Sximo
{
    use Searchable;
    protected $table = 'requests';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location(){
        return $this->belongsTo(location::class);
    }
    public function vendor(){
        return $this->belongsTo('App\Models\Vendor');
    }
    public function product(){
        return $this->hasOne('App\Models\Product','id','product_id');
    }

    public function vendor_item(){
        return $this->hasManyThrough("App\Models\Product", "App\Models\Vendor",'id' , 'vendor_id','product_id');
    }
    public static function querySelect()
    {

        return "SELECT requests.*,u1.username,products.img,IF(product_id = 0, requests.description, products.vendor_description) as description,
                products.sku,products.case_price,products.retail_price,products.case_price*requests.qty,products.ticket_value,location.location_name_short,location.fedex_number,
                merch_request_status.status,products.size,concat(V1.vendor_name,if(V1.status=0,' (Inactive)','')) as vendor_name,order_type.order_type,product_type.type_description,If(products.reserved_qty = 0, 'No Data' , products.reserved_qty) as reserved_qty,
                IF(products.reserved_qty = 0 OR products.reserved_qty is null,'N/A',products.reserved_qty) as reserved_difference, products.vendor_id,products.prod_type_id,products.prod_sub_type_id  FROM requests
                LEFT JOIN users u1 ON (requests.request_user_id = u1.id)
			    LEFT JOIN products ON (requests.product_id = products.id)
			LEFT JOIN vendor V1 ON (products.vendor_id = V1.id)
			LEFT JOIN location ON (requests.location_id = location.id)
			LEFT JOIN merch_request_status ON (requests.status_id = merch_request_status.id)
			LEFT JOIN order_type ON (order_type.id = products.prod_type_id)
			LEFT JOIN product_type ON (product_type.id = products.prod_sub_type_id)";
    }



    public static function getRows($args, $cond = null) {

        $table = with(new static)->table;
        $key = with(new static)->primaryKey;
        extract(array_merge(array(
            'page' => '0',
            'limit' => '0',
            'sort' => '',
            'extraSorts' => [],
            'customSorts' => [],
            'order' => '',
            'params' => '',
            'global' => 1
        ), $args));

        if(isset($_SESSION['managefegrequeststore_search'])&& !empty($_SESSION['managefegrequeststore_search'])){
            $explode_string=explode('|',$_SESSION['managefegrequeststore_search']);
            $second_explode=explode(':',$explode_string[0]);
            $elastic = function (ManagefegrequeststoreRepository $repository)  {
                if(isset($_SESSION['managefegrequeststore_search'])){
                    $explode_string=explode('|',$_SESSION['managefegrequeststore_search']);
                    //dump($explode_string);
                    $result['vendor']=null;
                    $result['location']=null;
                    foreach($explode_string as $k=>$param){
                        $second_explode=explode(':',$param);
                        switch($second_explode[0]){
                            case 'description':
                                $main_search=$second_explode[2];
                                break;
                            case 'vendor_id':
                                $sec_res_search_string=explode(',',$second_explode[2]);
                                   $result['vendor']=$sec_res_search_string;
                                break;
                            case 'location_id':
                                $result['location']=$second_explode[2];
                                break;


                        }
                    }

                }

                if(empty($main_search)){
                    unset($_SESSION['managefegrequeststore_search']);
                }
                else{
                    $result['managefegrequeststore'] = $repository->search((string) $main_search);
                    foreach($result['managefegrequeststore']as $k=>$v){
                        $self=self::where('id',$v->id)->with('product')->first();
                        $vendor=\App\Models\Vendor::where('id',$self->product->vendor_id)->first();
                        $result['managefegrequeststore'][$k]->vendor_id=$self->product->vendor_id;
                    }


                    //dump('orders=>',$result['managefegrequeststore']);
                    return $result;
                }
                return false;
            };
            $client = ClientBuilder::create()->setHosts(config('services.search.hosts'))->build();
            $el=new ElasticsearchManagefegrequeststoreRepository($client);
            $pre_products=$elastic($el);

            if($pre_products['managefegrequeststore']!=null){

                if($pre_products['location']) {
                    $pre_products['managefegrequeststore'] = $pre_products['managefegrequeststore']->where('location_id', intval($pre_products['location']));
                }
                if(!empty($pre_products['vendor'])){
                   // $pre_products['managefegrequeststore']=$pre_products['managefegrequeststore']->whereIn('vendor_id',$pre_products['vendor']);

                    $products=clone($pre_products['managefegrequeststore']);
                    $pre_products['managefegrequeststore']->map(function ($value, $key) use($pre_products,$products) {
                        if(in_array($value['vendor_id'],$pre_products['vendor'])){
                           $products[$key]= $value;
                        }
                        else{unset($products[$key]);}


                    });

                }
                else{$products=$pre_products['managefegrequeststore'];}
                $total=count($products/*$pre_products['orders']*/);
                $search_total=$total;
                //dump('total1=>',$total);
                $offset = ($page - 1) * $limit;
                if ($offset >= $total && $total != 0 && $limit != 0) {
                    $page = ceil($total/$limit);
                    $offset = ($page-1) * $limit ;
                }




                //dump('offset',$offset);


                if($total>0){
                    $products = $products->chunk($limit);/*$pre_products['orders']*/
                    $products=$products[$page - 1];
                }
                //dump('products1',$products);

            }
        }



        $orderConditional1 = '';
        if (!empty($customSorts)) {
            $customOrderConditionals = [];
            foreach($customSorts as $customSort => $customSortType) {
                $customSortItem = '`'.$customSort.'` '.$customSortType;
                $customOrderConditionals[] = $customSortItem;
            }
            $orderConditional1 = implode(', ', $customOrderConditionals);
            $orderConditional1 = !empty($orderConditional1) ? $orderConditional1.", ":$orderConditional1;
        }

        $orderConditional = ($sort != '' && $order != '') ? " ORDER BY {$orderConditional1} {$sort} {$order} " : '';
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


        if(!isset($search_total)) {
            $total = count($counter_select);
            if ($table == "img_uploads") {
                $total = "";
            }
        }else{
            //dump('here');
            $total = $search_total;
        }

        $offset = ($page - 1) * $limit;
        if ($offset >= $total && $total != 0 && $limit != 0) {
            $page = ceil($total/$limit);
            $offset = ($page-1) * $limit ;
        }

        $limitConditional = ($page != 0 && $limit != 0) ? "LIMIT  $offset , $limit" : '';
        // echo $select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ";
        Log::info("Query : ".$select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ");
        self::$getRowsQuery = $select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ";


        if(isset($_SESSION['managefegrequeststore_search']) && !empty($_SESSION['managefegrequeststore_search'])){
            //var_dump('ses=>',$_SESSION['order_search']);
            if(isset($products)){
                $result=$products;}
            unset($_SESSION['managefegrequeststore_search']);
        }
        else {
            $result = \DB::select($select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ");
        }
        if ($key == '') {
            $key = '*';
        } else {
            $key = $table . "." . $key;
        }

        //dump('total=>',$total);
        return $results = array('rows' => $result, 'total' => $total);
    }





    public static function queryWhere($cond = null)
    {
        $cond['view'] = 'manage';
        $order_type_id = isset($cond['order_type_id']) ? $cond['order_type_id'] : "";
        $location_id = isset($cond['location_id']) ? $cond['location_id'] : "";
        $vendor_id = isset($cond['vendor_id']) ? $cond['vendor_id'] : "";
        $where = "  WHERE requests.id IS NOT NULL AND requests.blocked_at IS NULL ";
        if ($cond['view'] == 'manage') {
            if (!empty($order_type_id)) {
                if (strpos($order_type_id, '-')) {
                    $order_type_id = str_replace('-', ',', $order_type_id);
                }

                if (!empty($location_id)) {
                    if (!empty($vendor_id)) {
                        $where .= " AND requests.status_id IN(1)
						            AND products.prod_type_id IN(" . $order_type_id . ")
						            AND requests.location_id = " . $location_id . "
						            AND V1.id =" . $vendor_id;
                    } else {
                        $where .= " AND requests.status_id IN(1)
						            AND products.prod_type_id IN(" . $order_type_id . ")
						            AND requests.location_id = " . $location_id;
                    }
                } else {
                    $where .= " AND requests.status_id IN(1)
						            AND products.prod_type_id IN(" . $order_type_id . ")";
                }
            } else {
                $where .= " AND requests.status_id IN(1)";
            }
        } elseif ($cond['view'] == 'archive') {

            /*$where .= " AND requests.status_id IN(2,3)";*/
            $where .= " AND requests.status_id IN(1) ";
        }else{
            $where .= " AND requests.status_id IN(1) ";
        }
        return $where;

    }

    public static function queryGroup()
    {
        return "  ";
    }

    /**
     * @param null $v1 => T for Order Type (v1=T6)
     * @param null $v2 => V for Order Vendor(v2=
     * @param null $v3 => L for Order Location(v3=V11)
     * @param null $filter
     * @return mixed
     */
    public static function getManageRequestsInfo($v1 = null, $v2 = null, $v3 = null,$filter=null)
    {
        /**
         * Extract type id from v1,v2 or v3 variable
         */
        if (substr($v1, 0, 1) == 'T') {
            $v1 = substr($v1, 1);
            $TID = $v1;
        } else if (substr($v2, 0, 1) == 'T') {
            $v2 = substr($v2, 1);
            $TID = $v2;
        } else if (substr($v3, 0, 1) == 'T') {
            $v3 = substr($v3, 1);
            $TID = $v3;
        } else {
            $TID = 0;
        }

        /**
         * Extract location id from v1,v2 or v3 variable
         */
        if (substr($v1, 0, 1) == 'L') {
            $v1 = substr($v1, 1);
            $LID = $v1;
        } else if (substr($v2, 0, 1) == 'L') {
            $v2 = substr($v2, 1);
            $LID = $v2;
        } else if (substr($v3, 0, 1) == 'L') {
            $v3 = substr($v3, 1);
            $LID = $v3;
        } else {
            $LID = 0;
        }

        /**
         * Extract vendor id from v1,v2 or v3 variable
         */

        if (substr($v1, 0, 1) == 'V') {
            $v1 = substr($v1, 1);
            $VID = $v1;
        } else if (substr($v2, 0, 1) == 'V') {
            $v2 = substr($v2, 1);
            $VID = $v2;
        } else if (substr($v3, 0, 1) == 'V') {
            $v3 = substr($v3, 1);
            $VID = $v3;
        } else {
            $VID = 0;
        }

        $order_dropdown_data = self::getOrdersDropDownData($filter);
        $data['order_dropdown-data'] = $order_dropdown_data;



        if (!empty($TID)) {
            if (strpos($TID, '-')) {
                $TID_comma_replaced = str_replace('-', ',', $TID);
            } else {
                $TID_comma_replaced = $TID;
            }
            $loc_where = 'WHERE requests.status_id=1  AND requests.blocked_at IS NULL AND products.prod_type_id IN (' . $TID_comma_replaced . ') '.$filter;
            $data['loc_options'] = self::getLocationDropDownData('CONCAT(requests.location_id," | ",location.location_name_short)', $loc_where, 'ORDER BY requests.location_id');
            if (!empty($LID)) {
                $vendor_where='WHERE requests.status_id=1  AND requests.blocked_at IS NULL AND requests.location_id=' . $LID . ' AND products.prod_type_id IN (' . $TID_comma_replaced . ')'.$filter;
                $data['vendor_options'] = self::getVendorDropDownData('CONCAT(vendor_name,IF(vendor.status=0," (Inactive)",""))',$vendor_where, 'ORDER BY vendor.vendor_name');
            } else {
                $data['vendor_options'] = array('' => '<-- Select');
            }
            $order_type_where = "AND P.prod_type_id IN (" . $TID_comma_replaced . ")";
        } else {
            $data['loc_options'] = array('' => '<-- Select');
            $data['vendor_options'] = array('' => '<-- Select');

            $order_type_where = "";
        }
        $data['TID'] = $TID;
        $data['LID'] = $LID;
        $data['VID'] = $VID;
        $number_requests = '';
        $order_type_where =$order_type_where." ". \SiteHelpers::getQueryStringForLocation('requests');

        $query = \DB::select('SELECT COUNT(requests.id) as count,O.order_type AS request_count FROM requests
								LEFT JOIN products P ON P.id = requests.product_id LEFT JOIN order_type O ON O.id = P.prod_type_id
                                WHERE requests.status_id = 1  AND requests.blocked_at IS NULL AND O.order_type IS NOT NULL ' . $order_type_where . ' GROUP BY P.prod_type_id');

        foreach ($query as $index => $row) {
       //     $number_requests = $number_requests ." ".." | <em>". $row->request_count .":</em>";
            if($index == count($query) -1 )
                $number_requests = $number_requests ." ".$row->request_count.": ". $row->count ;
            else
                $number_requests = $number_requests ." ".$row->request_count.": ". $row->count  ;

        }
        $data['number_requests'] = $number_requests;
        $query = \DB::select('SELECT GROUP_CONCAT(order_type) AS order_types  FROM order_type
							  WHERE id != 6 AND id != 10');
        if (count($query) == 1) {
            $data['order_types'] = $query[0]->order_types;
        }
        $data['title'] = 'Manage Requests';
        $data['subtitle1'] = 'Merch Requests';
        $data['subtitle2'] = 'Office Requests';
        $data['subtitle3'] = 'Other Requests';
        return $data;
    }

    public static function getOrdersDropDownData($filter)
    {
        $filter = str_replace('requests.','R.',$filter);
        $filter = str_replace('products.','P.',$filter);
        $filter = str_replace('order_type.','O.',$filter);
        $query = \DB::select('SELECT O.id,O.order_type FROM order_type O
							  LEFT JOIN products P ON P.prod_type_id = O.id
							  LEFT JOIN requests R ON R.product_id = P.id
							  WHERE R.status_id = 1
							  AND R.blocked_at IS NULL
							  GROUP BY O.id
                              ORDER BY O.order_type');

        $query = \DB::select("SELECT O.id,O.order_type FROM requests R
                               JOIN products P ON P.id = R.product_id
                               JOIN order_type O ON O.id = P.prod_type_id
							  WHERE R.status_id = 1
							  AND R.blocked_at IS NULL
							 $filter
							  GROUP BY O.id
                              ORDER BY O.order_type");
        $orderTypesArray = array();
        $haveCategories = 0;
        foreach ($query as $row) {
            if($row->order_type!= 7 && $row->order_type != 8) {
                $row = array(
                'id' => $row->id,
                'text' => $row->order_type
            );
                //$orderTypesArray[] = $row;
               // Removing 'Instant Wind Prizes' and 'Redemption Prizes' from order type array
                if($row['id'] != 7 && $row['id'] != 8) {
                    $orderTypesArray[] = $row;
                }
                else
                {
                    $haveCategories = 1;
                }
            }
        }

        if($haveCategories)
        {
            // Combining 'Instant Win','Redemption' and 'Party' order types in a single category
            $customArray[] = array(
                'id' => '7-8',
                'text' => 'Instant Win, Redemption (combined)'
            );

            $orderTypesArray = array_merge($orderTypesArray, $customArray);
            //$array = array_merge($orderTypesArray);
        }


        return $orderTypesArray;
    }

    public static function getLocationDropDownData($customField, $customWhere, $customOrderBy)
    {
        $data[''] = 'Select Location';

        $query = \DB::select('SELECT location.id AS lid, ' . $customField . 'AS location_name FROM location
							LEFT JOIN requests ON location.id = requests.location_id
							LEFT JOIN products ON products.id = requests.product_id ' . $customWhere . ' ' . $customOrderBy);
        $location_ids = array();
        $locations = self::getUserAssignedLocation();
        foreach($locations  as $location)
            $location_ids[] =  $location->id;

        foreach ($query as $row) {
            if(in_array($row->lid, $location_ids))
                $data[$row->lid] = $row->location_name;
        }

        return $data;
    }

    public static function getVendorDropDownData($customField, $customWhere, $customOrderBy)
    {
        $data[''] = 'Select Vendor';
        $query = \DB::select('SELECT vendor.id AS vid, ' . $customField . ' AS vendor_name FROM vendor
							LEFT JOIN products ON vendor.id = products.vendor_id
							LEFT JOIN requests ON requests.product_id = products.id ' . $customWhere . ' ' . $customOrderBy);
        foreach ($query as $row) {
            $data[$row->vid] = $row->vendor_name;
        }

        return $data;
    }
    function manageRequests($v1 = null, $v2 = null, $v3 = null)
    {
        $user_lever=\Session::get('gid');
        if ($user_lever == Groups::PARTNER)
        {
            redirect('dashboard');
        }
        else
        {

            if(substr($v1, 0, 1) == 'T')
            {
                $v1 = substr($v1, 1);
                $TID = $v1;
            }
            else if(substr($v2, 0, 1) == 'T')
            {
                $v2 = substr($v2, 1);
                $TID = $v2;
            }
            else if(substr($v3, 0, 1) == 'T')
            {
                $v3 = substr($v3, 1);
                $TID = $v3;
            }
            else
            {
                $TID = 0;
            }

            if(substr($v1, 0, 1) == 'L')
            {
                $v1 = substr($v1, 1);
                $LID = $v1;
            }
            else if(substr($v2, 0, 1) == 'L')
            {
                $v2 = substr($v2, 1);
                $LID = $v2;
            }
            else if(substr($v3, 0, 1) == 'L')
            {
                $v3 = substr($v3, 1);
                $LID = $v3;
            }
            else
            {
                $LID = 0;
            }

            if(substr($v1, 0, 1) == 'V')
            {
                $v1 = substr($v1, 1);
                $VID = $v1;
            }
            else if(substr($v2, 0, 1) == 'V')
            {
                $v2 = substr($v2, 1);
                $VID = $v2;
            }
            else if(substr($v3, 0, 1) == 'V')
            {
                $v3 = substr($v3, 1);
                $VID = $v3;
            }
            else
            {
                $VID = 0;
            }

            $data['order_type_options'] = self::getOrdersDropDownData();

            if(!empty($TID))
            {
                if(strpos($TID,'-'))
                {
                    $TID_comma_replaced = str_replace('-',',',$TID);
                }
                else
                {
                    $TID_comma_replaced = $TID;
                }

                $data['loc_options'] = self::getLocationDropDownData('CONCAT(requests.location_id," | ",location.location_name_short)','WHERE requests.status_id=1  AND requests.blocked_at IS NULL AND products.prod_type_id IN ('.$TID_comma_replaced.')','ORDER BY requests.location_id');

                if(!empty($LID))
                {
                    $data['vendor_options'] = self::getVendorDropDownData('vendor_name','WHERE requests.status_id=1  AND requests.blocked_at IS NULL AND requests.location_id='.$LID.' AND products.prod_type_id IN ('.$TID_comma_replaced.')','ORDER BY vendor.vendor_name');
                }
                else
                {
                    $data['vendor_options'] = array('' => '<-- Select');
                }

                $order_type_where = "AND P.prod_type_id IN (".$TID_comma_replaced.")";
            }
            else
            {
                $data['loc_options'] = array('' => '<-- Select');
                $data['vendor_options'] = array('' => '<-- Select');

                $order_type_where = "";
            }
            $data['TID'] = $TID;
            $data['LID'] = $LID;
            $data['VID'] = $VID;
          $number_requests = '';
            $query = \DB::select('SELECT CONCAT("(",COUNT(R.id),") <em>",O.order_type,"</em>, ") AS request_count
									 FROM requests R
								LEFT JOIN products P ON P.id = R.product_id
								LEFT JOIN order_type O ON O.id = P.prod_type_id
									WHERE R.status_id = 1
										'.$order_type_where.'
								 GROUP BY P.prod_type_id HAVING P.prod_type_id IS NOT NULL');

            foreach ($query as $row)
            {
                $number_requests = $number_requests.$row->request_count;
            }
            $data['number_requests'] = substr($number_requests, 0,-2);

            $query = \DB::select('SELECT GROUP_CONCAT(order_type) AS order_types
									 FROM order_type
									WHERE id != 6
									  AND id != 10');
            if (count($query) == 1)
            {
                $data['order_types'] = $query[0]->order_types;
            }

            $data['title'] = 'Manage Requests';
            $data['subtitle1'] = 'Merch Requests';
            $data['subtitle2'] = 'Office Requests';
            $data['subtitle3'] = 'Other Requests';
            return $data;
        }
    }

    public static function getComboselect($params, $limit = null, $parent = null)
    {
        $tableName = $params[0];
        if ($tableName == 'location') {
            return parent::getUserAssignedLocation($params, $limit, $parent);
        } else {
            return parent::getComboselect($params, $limit, $parent);
        }
    }

    function getSearchQueryStringToArray($queryString = '')
    {

        $QueryArray = [];
        $queryArray = explode("|", $queryString);
        foreach ($queryArray as $itemKey) {
            $SearchKey = explode(":", $itemKey);
            if (!empty($SearchKey[2])) {
                $QueryArray[$SearchKey[0]] = $SearchKey[2];
            }
        }
        return $QueryArray;
    }
    function getSearchFilterResult($queryWhere = ''){
        if(!empty($queryWhere)){
            $queryWhere = " AND ".$queryWhere;
        }
        $sql = 'SELECT
              order_type.order_type,
              order_type.id AS product_type_id,
              location_id,
              vendor_id
            FROM requests  JOIN products
            ON requests.product_id = products.id
          JOIN order_type
            ON products.prod_type_id = order_type.id
          JOIN product_type
            ON products.prod_type_id = product_type.id
          LEFT JOIN vendor
            ON vendor.id = products.vendor_id
          LEFT JOIN location
            ON location.id = requests.location_id
            WHERE requests.status_id = 1
                AND blocked_at IS NULL AND location.active = 1  '.$queryWhere.' 
            GROUP BY order_type,location_id,vendor_id
            ORDER BY order_type.order_type,requests.location_id,vendor.vendor_name ASC limit 1';
        \Log::info("Dropdown Query before execution");
        \Log::info("Dropdown Query :".$sql);
        $result = \DB::select($sql);
        \Log::info("Dropdown Query after execution");
        if($result){
            return $result[0];
        }else{
            return false;
        }
    }

}
