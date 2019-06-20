<?php namespace App\Models;

use App\Library\FEG\System\FEGSystemHelper;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Log, View, Auth;
use App\Models\Ordersetting;
use App\Search\Searchable;
use App\Repositories\Shopfegrequeststore\ShopfegrequeststoreRepository;
use App\Repositories\Shopfegrequeststore\ElasticsearchShopfegrequeststoreRepository;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Client;

class shopfegrequeststore extends Sximo  {
    use Searchable;

	protected $table = 'products';
	protected $primaryKey = 'id';
	public function __construct() {
		parent::__construct();
	}
	public static function querySelect(  ){

	    $subQueries = self::subQueriesProductsSelect();
		
		return "SELECT products.*,vendor.vendor_name,vendor.hide as vendor_hide,vendor.status as vendor_status,O.order_type,T.product_type,$subQueries FROM products
                LEFT JOIN vendor ON (products.vendor_id = vendor.id)
                LEFT JOIN order_type O ON (O.id = products.prod_type_id)
                LEFT JOIN product_type T ON (T.id = products.prod_sub_type_id)";
	}

    public function vendor()
    {
        return $this->hasOne('App\Models\Vendor','id','vendor_id');
    }

   public static function queryWhere($cond=null){
       $return=" WHERE products.id IS NOT NULL ";

		if(is_array($cond))
        {
            $cond= array_filter($cond);
            if(!empty($cond))
            {
                $prodType=isset($cond['order_type'])?$cond['order_type']:"";
                $prodSubType=isset($cond['product_type'])?$cond['product_type']:"";
              if($cond['type'] == "store")
              {
                      $group_ids = self :: get_location_group_ids(\SiteHelpers::getLocationDetails(\Session::get('uid')));
                       if(is_numeric($prodType) && !empty($prodType) && empty($prodSubType))
                      {
                          $return.= " AND products.prod_type_id = ".$prodType."
							AND products.vendor_description != ''
							AND IF(products.limit_to_loc_group_id = 0, products.limit_to_loc_group_id = 0, products.limit_to_loc_group_id IN(".$group_ids."))
                            AND products.in_development = 0";
                      }
                      else if(is_numeric($prodType) && !empty($prodSubType) && !empty($prodSubType))
                      {
                         $return.=" AND products.prod_type_id = ".$prodType."
							AND products.prod_sub_type_id = ".$prodSubType."
							AND products.vendor_description != ''
							AND products.in_development = 0";
                      }
                      else
                      {
                          $return.=" AND products.sku != ''
                          AND products.in_development = 0";

                      }
              }
                if(array_search("active",$cond))
                {
                    $return.= " AND products.inactive=0";
                }
                elseif(array_search("inactive",$cond))
                {
                    $return.= " AND products.inactive=1";
                }
                elseif(array_search("all",$cond)){

                    $return.= " ";
                }
                else{
                    $return.=" AND products.inactive=0";
                }

                if(!empty($cond['filterBy'])){
                    $filterBy = $cond['filterBy'];
                    if($filterBy == 'hot'){
                        $return.=" having(products.hot_item = 1)";
                    }elseif($filterBy == 'new'){
                        $return.=" having(is_new >= 1)";
                    }elseif($filterBy == 'backinstock'){
                        $return.=" having(is_backinstock >= 1)";
                    }elseif($filterBy == 'favorite'){
                        $return.=" having(is_favorite >= 1)";
                    }

                }

            }
        }
        return $return;

	}
	
	public static function queryGroup(){
		return "  ";
	}
    public static function get_location_group_ids($reg_loc_ids)
    {
        $ids=array();
        foreach($reg_loc_ids as $loc_id)
        {
            $ids[]=$loc_id->id;
        }
        $reg_loc_ids=implode(',',$ids);
        $query =\DB::select('SELECT GROUP_CONCAT(DISTINCT loc_group_id) AS group_ids FROM location WHERE id IN('.$reg_loc_ids.')');
       if (count($query) == 1)
        {
            $data['loc_group_ids'] = $query[0]->group_ids;
        }
        return $data['loc_group_ids'];
    }
    function shoppingCart($active_inactive = null, $prodType = null, $prodSubType = null)
    {
      if(false)
      {

      }else
        {
            $location_id = \Session::get('selected_location');
            $data['selected_location'] = $location_id;
            // SHOPPING CART TOTALS (SHOWN ABOVE CART) START
            $data['shopping_cart_total'] = '';
            $data['amt_short'] = '';
            $data['amt_short_message'] = '';
            $query = \DB::select('SELECT V.vendor_name,V.id AS vendor_id,V.min_order_amt,SUM(R.qty*P.case_price) AS total,
                                V.min_order_amt - SUM(R.qty*P.case_price) AS amt_short FROM requests R
								LEFT JOIN products P ON P.id = R.product_id
								LEFT JOIN vendor V ON V.id = P.vendor_id
									WHERE R.status_id = 4
									  AND R.location_id = "'.$location_id .'"
								 GROUP BY V.vendor_name');
            foreach ($query as $row)
            {
                $row = array(
                    'vendor_name' => $row->vendor_name,
                    'vendor_id' => $row->vendor_id,
                    'vendor_min_order_amt' => $row->min_order_amt,
                    'vendor_total' => $row->total,
                    'amt_short' => $row->amt_short
                );

                $array[] = $row;

                if($row['amt_short'] > 0)
                {
                    $data['amt_short_message'] = 'Your '.$data['amt_short_message'].$row['vendor_name'].' order is short by $'.$row['amt_short'].'. ';
                }

                $data['shopping_cart_total'] = $data['shopping_cart_total'] + $row['vendor_total'];
            }
            if(isset($array))
            {
                $data['items_in_cart'] = 'yes';
                //$data['subtotals'] = $array;
            }
            else
            {
                $data['items_in_cart'] = 'no';
            }
            // SHOPPING CART TOTALS (SHOWN ABOVE CART) END

            // NEW PRODUCTS (SHOWN ABOVE STORE) START
           // $today = $this->get_local_time('date');

            $newQuery = \DB::select('SELECT CONCAT(V.vendor_name," - <b>",P.vendor_description," </b> ($",P.case_price," / ", P.num_items," items) per case <br />") AS item,
										  P.id as PID
									 FROM products P
								LEFT JOIN vendor V ON V.id = P.vendor_id
									WHERE P.date_added > subdate(current_date, 14)
									  AND P.vendor_description != ""
								 ORDER BY RAND()
									LIMIT 4');


            foreach ($newQuery as $row)
            {
                $row = array(
                    'item' => $row->item,
                    'new_product_id' => $row->PID
                );

                $newArray[] = $row;
            }
            if(isset($newArray))
            {
                $data['new_products'] = $newArray;
            }
            // NEW PRODUCTS (SHOWN ABOVE STORE) END
            /// TEMPORARILY LIMIT REQUESTABLE TYPES -- WHERE id IN(2,3)///
                $data['title_2'] = 'Cart - '.\Session::get('selected_location_name');
            $data['title'] = 'Store';
            return $data;
        }
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

        //dump($args);

        if(isset($_SESSION['shopfegrequeststore_search'])&& !empty($_SESSION['shopfegrequeststore_search'])){
            $explode_string=explode('|',$_SESSION['shopfegrequeststore_search']);
            $second_explode=explode(':',$explode_string[0]);
            $elastic = function (ShopfegrequeststoreRepository $repository)  {
                if(isset($_SESSION['shopfegrequeststore_search'])){
                    $explode_string=explode('|',$_SESSION['shopfegrequeststore_search']);
                    //dump($explode_string);
                    $result['status_id']=null;
                    $result['is_api_visible']=null;
                    $result['invoice_verified']=null;
                    foreach($explode_string as $k=>$param){
                        $second_explode=explode(':',$param);
                        switch($second_explode[0]){
                            case 'search_all_fields':
                                $main_search=$second_explode[2];
                                break;
                            case 'status_id':
                                $result['status_id']=$second_explode[2];
                                break;
                            case 'is_api_visible':
                                $result['is_api_visible']=$second_explode[2];
                                break;
                            case 'invoice_verified':
                                $result['invoice_verified']=$second_explode[2];
                                break;

                        }
                        //dump($second_explode);
                    }
                    //$second_explode=explode(':',$explode_string[0]);

                }

                if(empty($main_search)){
                    unset($_SESSION['shopfegrequeststore_search']);
                }
                else{
                    $result['shopfegrequeststore'] = $repository->search((string) $main_search);
                     //dump('orders=>',$result['shopfegrequeststore']);
                    return $result;
                }
                return false;
            };
            $client = ClientBuilder::create()->setHosts(config('services.search.hosts'))->build();
            $el=new ElasticsearchShopfegrequeststoreRepository($client);
            $pre_products=$elastic($el);

            if($pre_products['shopfegrequeststore']!=null){


                if($pre_products['invoice_verified']) {
                    //dump('intvalinvoice_verified',intval($pre_products['invoice_verified']));
                    $pre_products['shopfegrequeststore'] = $pre_products['shopfegrequeststore']->where('invoice_verified', intval($pre_products['invoice_verified']));
                }
                if($pre_products['is_api_visible']) {
                    //dump('intvalis_api_visible',intval($pre_products['is_api_visible']));
                    $pre_products['shopfegrequeststore'] = $pre_products['shopfegrequeststore']->where('is_api_visible', intval($pre_products['is_api_visible']));
                }
                if(intval($pre_products['status_id'])!==0){
                    //dump('intvalstatus_id',intval($pre_products['status_id']));
                    $pre_products['shopfegrequeststore']=$pre_products['shopfegrequeststore']->where('status_id',intval($pre_products['status_id']));
                }

                $products=$pre_products['shopfegrequeststore'];
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
                dump('products',$products);

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


        if(isset($_SESSION['shopfegrequeststore_search']) && !empty($_SESSION['shopfegrequeststore_search'])){
            //var_dump('ses=>',$_SESSION['order_search']);
            if(isset($products)){
                $result=$products;}
            unset($_SESSION['shopfegrequeststore_search']);
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



    function getRecentlyAddedProducts()
    {
        $location_id = \Session::get('selected_location');
        $data['selected_location'] = $location_id;

        $newQuery = \DB::select('SELECT CONCAT(O.order_type," - </b><i>",V.vendor_name,"</i> - <b>",P.vendor_description," </b> ($",P.case_price," / ", P.num_items," items) per case") AS item,
								P.id as PID  FROM products P
								LEFT JOIN vendor V ON V.id = P.vendor_id
								LEFT JOIN order_type O ON O.id = P.prod_type_id
                                WHERE P.date_added > subdate(current_date, 10)
                                AND P.vendor_description != ""');


        foreach ($newQuery as $row)
        {
            $row = array(
                'item' => $row->item,
                'new_product_id' => $row->PID
            );

            $newArray[] = $row;
        }
        if(isset($newArray))
        {
            $data['new_products'] = $newArray;
        }
        // NEW PRODUCTS (SHOWN ABOVE STORE) END
        $data['title'] = 'Recently Added Products';
        return $data;
    }

    function getRecentlyAddedProduct()
    {
        $location_id = \Session::get('selected_location');
        $data['selected_location'] = $location_id;

        $newQuery = \DB::select('SELECT CONCAT(O.order_type," - </b><i>",V.vendor_name,"</i> - <b>",P.vendor_description," </b> ($",P.case_price," / ", P.num_items," items) per case") AS item,
								P.id as PID  FROM products P
								LEFT JOIN vendor V ON V.id = P.vendor_id
								LEFT JOIN order_type O ON O.id = P.prod_type_id
                                WHERE P.date_added > subdate(current_date, 10)
                                AND P.vendor_description != ""
                                LIMIT 1');


        foreach ($newQuery as $row)
        {
            $row = array(
                'item' => $row->item,
                'new_product_id' => $row->PID
            );

            $newArray[] = $row;
        }
        if(isset($newArray))
        {
            $data['new_products'] = $newArray;
        }
        // NEW PRODUCTS (SHOWN ABOVE STORE) END
        $data['title'] = 'Recently Added Products';
        return $data;
    }


    function newGraphicRequest($data)
    {
        $last_inserted_id=\DB::table('new_graphics_request')->insertGetId($data);
        $locationName = $this->get_location_info_by_id($data['location_id'], 'location_name');
        $game_info=explode('-',$data['description']);
        $mangeGraphicRequestURL = url("managenewgraphicrequests");
        $graphicApproveLink = "http://{$_SERVER['HTTP_HOST']}/managenewgraphicrequests/approve/$last_inserted_id";
        $graphicDenyLink = "http://{$_SERVER['HTTP_HOST']}/managenewgraphicrequests/deny/$last_inserted_id";
        $description='';
        if(strlen($data['description'])>=140){
            $description=substr($data['description'], 0, 140).'...';
        }else{
            $description=$data['description'];
        }

        $OrderSetting = new Ordersetting();
        $GraphicsSender = "";
        $GraphicsReceiver = "";

        $GraphicsRequestSetting = $OrderSetting->where("is_graphics_setting", 1)->first();
        if ($GraphicsRequestSetting) {
            $GraphicsSender = $GraphicsRequestSetting->graphics_sender_content;
            $GraphicsReceiver = $GraphicsRequestSetting->graphics_recever_content;
        }

        $messageWithLink = View::make('shopfegrequeststore.emails.graphic-request-submitter-link', array(
            'title' => $game_info[0],
            'date' => \DateHelpers::formatDate($data['request_date']),
            'submitter' => \Session::get('fid'),
            'location_id' => $data['location_id'],
            'location_name' => $locationName,
            'description' => $description,
            'request_link' => $mangeGraphicRequestURL,
            'approve_link' => $graphicApproveLink,
            'deny_link' => $graphicDenyLink,
            'GraphicsReceiverContent' => $GraphicsReceiver

        ))->render();

        $from = \Session::get('eid');
        $subject = 'New Graphics Request for '.$locationName;

        $configName = 'Request new custom graphics email';
        $receipts = FEGSystemHelper::getSystemEmailRecipients($configName,$data['location_id']);
        $message = $messageWithLink;//$baseMessage.$links.$messageEnd;


        FEGSystemHelper::sendSystemEmail(array_merge($receipts, array(
            'subject' => $subject,
            'message' => $message,
            'preferGoogleOAuthMail' => true,
            'isTest' => env('APP_ENV', 'development') !== 'production'? true : false,
            'configName' => $configName,
            'from' => $from,
            'replyTo' => $from,
        )));

        $receipientsForEmailWihtoutLinksConfigName = 'New custom graphics notification without links';
        $receipientsForEmailWihtoutLinks = FEGSystemHelper::getSystemEmailRecipients($receipientsForEmailWihtoutLinksConfigName,$data['location_id']);

        $messageWithoutLink = View::make('shopfegrequeststore.emails.graphic-request-submitter', array(
            'submitterEmailAddress' => \Session::get('eid'),
            'GraphicsSenderContent' => $GraphicsSender
        ))->render();

        if(empty($receipientsForEmailWihtoutLinks['to'])){
            $receipientsForEmailWihtoutLinks['to']=Auth::user()->email;
        }

        FEGSystemHelper::sendSystemEmail(array_merge($receipientsForEmailWihtoutLinks, array(
            'subject' => $subject,
            'message' => $messageWithoutLink,
//            'preferGoogleOAuthMail' => true,
            'isTest' => env('APP_ENV', 'development') !== 'production',
            'from' => $from,
            'replyTo' => $from,
        )));

        return $last_inserted_id;
    }

    /**
     * override location drop down menu
     * @param $params
     * @param null $limit
     * @param null $parent
     * @return mixed
     */
    public static function getComboselect($params, $limit = null, $parent = null) {
        $tableName = $params[0];
        if($tableName == 'location'){
            return parent::getUserAssignedLocation($params,$limit,$parent);
        }
        else{
            return parent::getComboselect($params,$limit,$parent);
        }
    }


	

}
