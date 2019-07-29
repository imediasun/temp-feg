<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Search\Searchable;
use App\Repositories\Productsubtype\ProductsubtypeRepository;
use App\Repositories\Productsubtype\ElasticsearchProductsubtypeRepository;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Client;

class Productsubtype extends Sximo  {
    use Searchable;
	protected $table = 'product_type';
	protected $primaryKey = 'id';

    use SoftDeletes;
    protected $dates = ['deleted_at'];

	public function __construct() {
		parent::__construct();
		
	}

    public function order_type()
    {
        return $this->hasOne('App\Models\OrderType','id','request_type_id');
    }

	public static function querySelect(  ){
		
		return "  SELECT product_type.*, order_type.order_type FROM product_type 
                  INNER JOIN order_type ON (product_type.request_type_id = order_type.id) ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE product_type.id IS NOT NULL AND product_type.deleted_at IS NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subType(){
        return $this->belongsTo(self::class, 'request_type_id', 'id');
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

        if(isset($_SESSION['productsubtype_search'])&& !empty($_SESSION['productsubtype_search'])){
            $explode_string=explode('|',$_SESSION['productsubtype_search']);
            $second_explode=explode(':',$explode_string[0]);
            $elastic = function (ProductsubtypeRepository $repository)  {
                if(isset($_SESSION['productsubtype_search'])){
                    $explode_string=explode('|',$_SESSION['productsubtype_search']);
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
                    unset($_SESSION['productsubtype_search']);
                }
                else{
                    $result['productsubtype'] = $repository->search((string) $main_search);
                    //dump('orders=>',$result['shopfegrequeststore']);
                    return $result;
                }
                return false;
            };
            $client = ClientBuilder::create()->setHosts(config('services.search.hosts'))->build();
            $el=new ElasticsearchProductsubtypeRepository($client);
            $pre_products=$elastic($el);
            if($pre_products['productsubtype']!=null){


                if($pre_products['invoice_verified']) {
                    //dump('intvalinvoice_verified',intval($pre_products['invoice_verified']));
                    $pre_products['productsubtype'] = $pre_products['productsubtype']->where('invoice_verified', intval($pre_products['invoice_verified']));
                }
                if($pre_products['is_api_visible']) {
                    //dump('intvalis_api_visible',intval($pre_products['is_api_visible']));
                    $pre_products['productsubtype'] = $pre_products['productsubtype']->where('is_api_visible', intval($pre_products['is_api_visible']));
                }
                if(intval($pre_products['status_id'])!==0){
                    //dump('intvalstatus_id',intval($pre_products['status_id']));
                    $pre_products['productsubtype']=$pre_products['productsubtype']->where('status_id',intval($pre_products['status_id']));
                }

                $products=$pre_products['productsubtype'];
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
                //dump('productsubtype',$products);

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

        //Log::info("Total Query : ".$select . " {$params} " . self::queryGroup() . " {$orderConditional}");
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
        //Log::info("Query : ".$select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ");
        self::$getRowsQuery = $select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ";


        if(isset($_SESSION['productsubtype_search']) && !empty($_SESSION['productsubtype_search'])){
            //var_dump('ses=>',$_SESSION['order_search']);
            if(isset($products)){
                $result=$products;}
            unset($_SESSION['productsubtype_search']);
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
