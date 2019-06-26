<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Search\Searchable;
use App\Repositories\Vendor\VendorRepository;
use App\Repositories\Vendor\ElasticsearchVendorRepository;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Client;
class vendor extends Sximo  {
    use Searchable;
	protected $table = 'vendor';
	protected $primaryKey = 'id';
	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){

        return "SELECT
  vendor.*,
  CONCAT(users.first_name, ' ', users.last_name) AS updated_by_user,
  countries.country_name
FROM vendor
  LEFT JOIN users
    ON vendor.updated_by = users.id
  LEFT JOIN countries
    ON countries.id = vendor.country_id ";
	}	

	public static function queryWhere(  ){

        $filters = self::getSearchFilters(['hide' => '', 'status' => '']);
        $hide = $status = "";
        if ($filters['hide'] == '') {
            $hide = "AND vendor.hide = 0 ";
        }
        if ($filters['status'] == '') {
            $status = "AND vendor.status = 1 ";
        }
        		
		return "  WHERE vendor.id IS NOT NULL $hide $status";
	}
	
	public static function queryGroup(){
		return "  ";
	}

    /**
     * @param $json
     * @param null $param
     * @return array
     */

	public static function processApiData($json,$param=null)
    {
        //loop over all records and check if website is not empty then add http:// prefix for it
        $data = array();
        foreach($json as $record){
            if(!empty($record['website'])){
                if(strpos($record['website'],'http') === false){
                    $record['website'] = 'http://'.$record['website'];
                }
            }
            $data[] = $record;
        }
        return $data;
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

        if(isset($_SESSION['vendor_search'])&& !empty($_SESSION['vendor_search'])){
            $explode_string=explode('|',$_SESSION['vendor_search']);
            $second_explode=explode(':',$explode_string[0]);
            $elastic = function (VendorRepository $repository)  {
                if(isset($_SESSION['vendor_search'])){
                    $explode_string=explode('|',$_SESSION['vendor_search']);
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
                            case 'status':
                                $result['status']=$second_explode[2];
                                break;
                            case 'hide':
                                $result['hide']=$second_explode[2];
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
                    unset($_SESSION['vendor_search']);
                }
                else{
                    $result['vendor'] = $repository->search((string) $main_search);
                    //dump('orders=>',$result['shopfegrequeststore']);
                    return $result;
                }
                return false;
            };
            $client = ClientBuilder::create()->setHosts(config('services.search.hosts'))->build();
            $el=new ElasticsearchVendorRepository($client);
            $pre_products=$elastic($el);

            if($pre_products['vendor']!=null){

                if($pre_products['invoice_verified']) {
                    //dump('intvalinvoice_verified',intval($pre_products['invoice_verified']));
                    $pre_products['vendor'] = $pre_products['vendor']->where('invoice_verified', intval($pre_products['invoice_verified']));
                }
                if(intval($pre_products['hide'])!==null && intval($pre_products['hide'])!==-1) {
                    //dump('intvalis_api_visible',intval($pre_products['is_api_visible']));
                    $pre_products['vendor'] = $pre_products['vendor']->where('hide', intval($pre_products['hide']));
                }
                if(intval($pre_products['status'])!==null && intval($pre_products['status'])!==-1){
                    $pre_products['vendor']=$pre_products['vendor']->where('status',intval($pre_products['status']));
                }

                $products=$pre_products['vendor'];
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
                //dump('products',$products);

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

       // Log::info("Total Query : ".$select . " {$params} " . self::queryGroup() . " {$orderConditional}");
       $counter_select =\DB::select($select . " {$params} " . self::queryGroup() . " {$orderConditional}");


        if(!isset($search_total)) {
            $total = count($counter_select);//
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
        //Log::info("Query : ".$select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ");
        self::$getRowsQuery = $select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ";


        if(isset($_SESSION['vendor_search']) && !empty($_SESSION['vendor_search'])){
            //var_dump('ses=>',$_SESSION['order_search']);
            if(isset($products)){
                $result=$products;}
            unset($_SESSION['vendor_search']);
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


}
