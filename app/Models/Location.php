<?php namespace App\Models;

use App\Library\MyLog;
use App\Library\FEGDBRelationHelpers;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use SiteHelpers;
use App\Search\Searchable;
use App\Repositories\Location\LocationRepository;
use App\Repositories\Location\ElasticsearchLocationRepository;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Client;

class location extends Sximo  {

    use Searchable;
	protected $table = 'location';
	protected $primaryKey = 'id';
    const LOCATION_TYPE_SACOA=1;
    const DEBIT_TYPES = [1,2];

	public function __construct() {
		parent::__construct();
		
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

        if(isset($_SESSION['location_search'])&& !empty($_SESSION['location_search'])){
            $explode_string=explode('|',$_SESSION['location_search']);
            $second_explode=explode(':',$explode_string[0]);
            $elastic = function (LocationRepository $repository)  {
                if(isset($_SESSION['location_search'])){
                    $explode_string=explode('|',$_SESSION['location_search']);
                    //dump($explode_string);
                    $result['status_id']=null;
                    $result['is_api_visible']=null;
                    $result['invoice_verified']=null;
                    foreach($explode_string as $k=>$param){
                        $second_explode=explode(':',$param);
                        switch($second_explode[0]){
                            case 'location_name':

                                $main_search=$second_explode[2];
                                break;
                            case 'active':
                                $result['active']=$second_explode[2];
                                break;
                            case 'id':
                                $result['id']=$second_explode[2];
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
                    unset($_SESSION['location_search']);
                }
                else{
                    $result['location'] = $repository->search((string) $main_search);
                   // dump('orders=>',$result['location']);
                    return $result;
                }
                return false;
            };
            $client = ClientBuilder::create()->setHosts(config('services.search.hosts'))->build();
            $el=new ElasticsearchLocationRepository($client);
            $pre_products=$elastic($el);

            if($pre_products['location']!=null){


                if($pre_products['invoice_verified']) {
                    //dump('intvalinvoice_verified',intval($pre_products['invoice_verified']));
                    $pre_products['location'] = $pre_products['location']->where('invoice_verified', intval($pre_products['invoice_verified']));
                }
                if(isset($pre_products['id']) && $pre_products['id']) {
                    //dump('intvalis_api_visible',intval($pre_products['is_api_visible']));
                    $pre_products['location'] = $pre_products['location']->where('id', intval($pre_products['id']));
                }
                if(intval($pre_products['active']) && intval($pre_products['active'])!=-1 ){
                    //dump('intvalstatus_id',intval($pre_products['status_id']));
                    $pre_products['location']=$pre_products['location']->where('active',intval($pre_products['active']));
                }

                $products=$pre_products['location'];
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


        if(isset($_SESSION['location_search']) && !empty($_SESSION['location_search'])){
            //var_dump('ses=>',$_SESSION['order_search']);
            if(isset($products)){
                $result=$products;}
            unset($_SESSION['location_search']);
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




    public static function getQuery( ) {
        $sql = "SELECT
  location.general_manager_id,
  location.technical_user_id,
  location.regional_manager_id,
  location.vp_id,
  location.contact_id,
  location.merch_contact_id,
  location.id,
  location.store_id,
  location.location_name,
  location.location_name_short,
  location.mail_attention,
  location.street1,
  location.city,
  location.state,
  location.zip,
  location.fedex_number,
  location.attn,
  location.company_id,
  location.self_owned,
  location.loading_info,
  location.post_add_action_done,
  location.date_added,
  location.date_opened,
  location.date_closed,
  location.region_id,
  location.loc_group_id,
  location.debit_type_id,
  location.can_ship,
  location.loc_ship_to,
  location.phone,
  location.bestbuy_store_number,
  location.bill_debit_type,
  location.bill_debit_amt,
  location.bill_debit_detail,
  location.bill_ticket_type,
  location.bill_ticket_amt,
  location.bill_ticket_detail,
  location.bill_thermalpaper_type,
  location.bill_thermalpaper_amt,
  location.bill_thermalpaper_detail,
  location.bill_token_type,
  location.bill_token_amt,
  location.bill_token_detail,
  location.bill_license_type,
  location.bill_license_amt,
  location.bill_license_detail,
  location.bill_attraction_type,
  location.bill_attraction_amt,
  location.bill_attraction_detail,
  location.bill_redemption_type,
  location.bill_redemption_amt,
  location.bill_redemption_detail,
  location.bill_instant_type,
  location.bill_instant_amt,
  location.bill_instant_detail,
  location.no_games,
  location.liftgate,
  location.ipaddress,
  location.reporting,
  location.active,
  location.freight_id,
  '' as product_type_ids,
  '' as product_ids
FROM location
  LEFT JOIN debit_type debittype_c
    ON debittype_c.id = location.debit_type_id
  LEFT JOIN company cmp
    ON cmp.id = location.company_id
  LEFT JOIN loc_group
    ON loc_group.id = location.loc_group_id
  LEFT JOIN users u1
    ON u1.id = location.general_manager_id
  LEFT JOIN users u2
    ON u2.id = location.technical_user_id
  LEFT JOIN users u3
    ON u3.id = location.vp_id
  LEFT JOIN users u4
    ON u4.id = location.contact_id
  LEFT JOIN users u5
    ON u5.id = location.regional_manager_id
  LEFT JOIN users u6
    ON u6.id = merch_contact_id ";
        return $sql;
    }

    public static function querySelect()
    {
		return self::getQuery();
	}	

	public static function queryWhere(  ){
		
		return " Where location.id is not null  ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

    public static function getRow($id)
    {       
        if (empty($id)) {
            return false;
        }
        $sql = self::querySelect();
        $rows = \DB::select($sql." WHERE location.id='$id'");
        return $rows;
    }
    public static function reportingLocations(){
        $sql = "SELECT DISTINCT RL.location_id FROM report_locations RL INNER JOIN location L ON L.id = RL.location_id WHERE L.active = 1";
        \Log::info("Reporing Location Query: ".$sql);
        $logger = new MyLog('reporting-locations.log', 'FEGCronTasks/reporting-locations', 'ReportingLocations');
        $rows = \DB::select($sql);
        $reportinglocations = null;
        if(count($rows)>0){
            foreach($rows as $row){
            ($reportinglocations == null) ? $reportinglocations = $row->location_id:$reportinglocations .= ','.$row->location_id;
            }
        }
        $logger->log("Reporing Locations: ",$reportinglocations);
        return $reportinglocations;
    }


    public function excludedProductTypes(){
        $excludedProductTypeIds = FEGDBRelationHelpers::getCustomRelationRecords($this->id, self::class, Ordertyperestrictions::class, 1, true, false)->pluck('ordertyperestrictions_id')->toArray();
        return Ordertyperestrictions::whereIn('id', $excludedProductTypeIds);
    }

    public function excludedProducts(){
        $excludedProductIds = FEGDBRelationHelpers::getCustomRelationRecords($this->id, self::class, product::class, 1, true, false)->pluck('product_id')->toArray();
        return Product::whereIn('id', $excludedProductIds);
    }
    public function setExcludedData($rows){
        $returnData = [];
        foreach ($rows as $row){
          // $excludedData = FEGDBRelationHelpers::getExcludedProductTypeAndExcludedProductIds($row->id, true,  true);
            $excludedProductIds = FEGDBRelationHelpers::getCustomRelationRecords($row->id,location::class,product::class,1, true, false)->pluck('product_id')->toArray();
            $excludedProductTypeIds = FEGDBRelationHelpers::getCustomRelationRecords($row->id,location::class,Ordertyperestrictions::class,1, true, false)->pluck('ordertyperestrictions_id')->toArray();
            $excludedData = [
                'excluded_product_ids' =>$excludedProductIds,
                'excluded_product_type_ids' => $excludedProductTypeIds
            ];
            $productTypeData =  $productTypes = '';
            $productsData = [];
            if(!empty($excludedData['excluded_product_type_ids'])) {
                $productTypeData = Ordertyperestrictions::select(\DB::raw('group_concat(order_type ORDER BY order_type ASC) as product_types'))->whereIn('id', $excludedData['excluded_product_type_ids'])->get()->pluck('product_types')->toArray();
            }
            if(!empty($excludedData['excluded_product_ids'])) {
                $productsData = product::whereIn('id', $excludedData['excluded_product_ids'])->orderBy('vendor_description', 'asc')->groupBy('vendor_description')->groupBy('sku')->groupBy('vendor_id')->groupBy('case_price')->get()->lists('vendor_description')->toArray();
            }

            if(!empty($productTypeData[0])){
                $productTypes = str_replace(",","<br>",$productTypeData[0]);
                $row->product_type_ids = $productTypes.'.';
            }

            $row->product_ids = '';
            if(count($productsData) != 0){
                $productName= implode("<br>", $productsData);
                $row->product_ids = $productName.'.';
            }

            $returnData[]=$row;
        }

        return $returnData;
    }
}
