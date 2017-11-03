<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use SiteHelpers;
use App\Library\ReportHelpers;
use App\Library\DBHelpers;
use \App\Models\Sximo\Module;

class inventoryreport extends Sximo  {

    protected $table = 'orders';
    protected $primaryKey = 'id';
    const INSTANT_WIN = 8;
    const OFFICE_SUPPLIES = 6;
    const PARTY_SUPPLIES = 17;
    const REDEMPTION_PRICES = 7;
    const ADVANCE_REPLACEMENT = 2;
    public static $orderTypesForNetSuite = [
        self::INSTANT_WIN,
        self::OFFICE_SUPPLIES,
        self::PARTY_SUPPLIES,
        self::REDEMPTION_PRICES
    ];
    public static $orderTypesForUnitPrice = [
        self::INSTANT_WIN,
        self::OFFICE_SUPPLIES,
        self::REDEMPTION_PRICES
    ];
    public static $orderTypesForGroupBy = [
        self::INSTANT_WIN,
        self::OFFICE_SUPPLIES,
        self::REDEMPTION_PRICES,
        self::ADVANCE_REPLACEMENT
    ];
    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){

        return "  SELECT orders.* FROM orders  ";
    }

    public static function queryWhere(  ){

        return "  WHERE orders.id IS NOT NULL AND orders.status_id != ".order::ORDER_VOID_STATUS ." ";
    }

    public static function queryGroup(){
        return "  ";
    }

    public static function getRows( $args,$cond=null )
    {
        $table = with(new static)->table;
        $key = with(new static)->primaryKey;
        $forExcel = 0;
        if($cond == "ForExcel")
        {
            $forExcel = 1;
            $cond = null;
        }
        $topMessage = "";
        $bottomMessage = "";
        $message = "";

        extract( array_merge( array(
            'page' 		=> '0' ,
            'limit'  	=> '0' ,
            'sort' 		=> '' ,
            'order' 	=> '' ,
            'params' 	=> '' ,
            'global'	=> 1
        ), $args ));

        $rows = array();
        $total = 0;

        $filters = self::getSearchFilters();
        $date_start = @$filters['start_date'];
        $date_end = @$filters['end_date'];
        $location_id = @$filters['location_id'];
        $vendor_id = @$filters['vendor_id'];
        $order_type_id = @$filters['Order_Type'];
        $prod_type_id = @$filters['Product_Type'];
        $prod_sub_type_id = @$filters['Product_Sub_Type'];
        if (empty($location_id)) {
            $forAllLocations = true;
            $location_id = SiteHelpers::getCurrentUserLocationsFromSession();
        }
        if (empty($location_id)) {
            return ReportHelpers::buildBlankResultDataDueToNoLocation();
        }

        $defaultEndDate = DBHelpers::getHighestRecorded('orders', 'created_at');
        ReportHelpers::dateRangeFix($date_start, $date_end, true, $defaultEndDate, 7);
        if (empty($date_start) || empty($date_end)) {
            $message = "To view the contents of this report, please select a date range and other search filter.";
        }
        else {

            $whereLocation = "";
            $whereVendor = "";
            $whereOrderType ="";
            $whereProdType = "";
            $whereProdSubType = "";
            if (!empty($location_id)) {
                $whereLocation = "AND O.location_id IN ($location_id) ";
            }
            if (!empty($vendor_id)) {
                $whereVendor = "AND V.id IN ($vendor_id) ";
            }
            if (!empty($order_type_id)) {
                $whereOrderType = "AND O.order_type_id IN ($order_type_id) ";
            }
            if (!empty($prod_type_id)) {
                $whereProdType = "AND OC.prod_type_id IN ($prod_type_id) ";
            }
            if (!empty($prod_sub_type_id)) {
                $whereProdSubType = "AND OC.prod_sub_type_id IN ($prod_sub_type_id) ";
            }
            $module_id = Module::name2id('order');
            $case_price_permission = \FEGSPass::getPasses($module_id,'module.order.special.calculatepriceaccordingtocaseprice',false);
            $casePriceCats = $case_price_permission["calculate price according to case price"]->data_options;


            $date_start_stamp = strtotime($date_start);
            $date_end_stamp = strtotime($date_end);
            if ($date_end_stamp < $date_start_stamp) {
                $t = $date_start;
                $date_start = $date_end;
                $date_end = $t;
                $t = $date_start_stamp;
                $date_start_stamp = $date_end_stamp;
                $date_end_stamp = $t;
            }
            $UserFill = $forExcel?"USER":"";
            $separator = "' <br> '";
            if(isset($forExcel) && $forExcel == 1)
            {
                $separator = "' , '";
            }
            $mainQuery = "
            SELECT 
            max(id) as id,GROUP_CONCAT(DISTINCT orderId ORDER BY orderId DESC SEPARATOR ' - ' ) as orderId, max(sku) as sku, max(num_items) as num_items, 
            '' AS unit_inventory_count,'' AS total_inventory_value,
            GROUP_CONCAT(DISTINCT order_type ORDER BY order_type SEPARATOR ' , ' ) AS Order_Type,
            GROUP_CONCAT(DISTINCT location_name ORDER BY location_name SEPARATOR $separator ) AS location_id,
            Product_Type,is_api_visible,
            type_description AS Product_Sub_Type,
            vendor_name,Product,max(ticket_value) as ticket_value
            ,Unit_Price,
            IF(order_type_id IN ($casePriceCats),IF(max(num_items) is null OR MAX(num_items) = 0  , SUM(qty), (max(num_items)*SUM(qty))),SUM(qty)) AS Cases_Ordered,
            Case_Price,SUM(IF(order_type_id IN ($casePriceCats),(Case_Price * qty),(Unit_Price*qty))) AS Total_Spent,start_date,end_date
            ,qty_per_case
             FROM ( 
                    SELECT P.id , O.id as orderId,
                    IF(OC.sku = '' OR OC.sku IS NULL,P.sku,OC.sku) AS sku,
                    P.num_items,
                    T.order_type Product_Type,
                    T1.order_type,O.order_type_id,
                    OC.prod_type_id,
                    D.type_description,
                    V.vendor_name AS vendor_name,
                    OC.item_name AS Product,
                    OC.ticket_value,
                    IF(OC.prod_type_id in (".implode(',',self::$orderTypesForUnitPrice)."),TRUNCATE(OC.case_price/OC.qty_per_case,5),OC.price) AS Unit_Price,
                    OC.qty,
                    OC.qty_per_case,
                    O.is_api_visible,
                    IF(O.is_api_visible = 1 , OC.case_price,'$UserFill') AS Case_Price,
                    OC.total,
                    O.location_id,
                    L.location_name,
                    O.created_at AS start_date,
                    O.created_at AS end_date
                        ";
            $mainQueryEnd  = " ) AS t ";
            //$orderBy = " ORDER BY P.id ASC LIMIT 0 , 20000000000000";

            $catQuery = "Select distinct T.order_type AS order_type ";

            $fromQuery = " FROM order_contents OC 
                           LEFT JOIN products P ON P.id = OC.product_id 
                           JOIN orders O ON O.id = OC.order_id
						   LEFT JOIN location L ON L.id = O.location_id
						   LEFT JOIN vendor V ON V.id = OC.vendor_id 
						   LEFT JOIN order_type T1 ON T1.id = O.order_type_id
						   LEFT JOIN order_type T ON T.id = OC.prod_type_id
						   LEFT JOIN product_type D ON D.id = OC.prod_sub_type_id
						   
						   ";
            $closeOrderStatus = order::ORDER_CLOSED_STATUS;
            if(is_array($closeOrderStatus))
            {
                $closeOrderStatus = implode(',',$closeOrderStatus);
            }
            $whereQuery = " WHERE O.status_id != ".order::ORDER_VOID_STATUS ." AND O.status_id IN ($closeOrderStatus) AND O.created_at >= '$date_start'
                            AND O.created_at <= '$date_end' 
                             $whereLocation $whereVendor $whereOrderType $whereProdType $whereProdSubType ";

            $groupByTypes = implode(',',self::$orderTypesForGroupBy);
            // both group by quires are same
            $groupQuery = " GROUP BY OC.item_name,OC.qty_per_case,order_type ,IF( OC.prod_type_id IN (".$groupByTypes."), OC.case_price , OC.price )";
            $groupQuery2 = " GROUP BY Product,qty_per_case,Product_Type,sku,is_api_visible,IF( Product_Type IN (".$groupByTypes.") , Case_Price , Unit_Price ) ";


            $finalTotalQuery = "$mainQuery $fromQuery $whereQuery $mainQueryEnd $groupQuery2";
            $totalRows = \DB::select($finalTotalQuery);
            if (!empty($totalRows)) {
                $total = count($totalRows);
            }
            $offset = ($page-1) * $limit ;
            if ($offset >= $total && $limit != 0) {
                $page = ceil($total/$limit);
                $offset = ($page-1) * $limit ;
            }
            $limitConditional = ($page !=0 && $limit !=0) ? " LIMIT  $offset , $limit" : '';

            $orderConditional = ($sort !='' && $order !='') ?  " ORDER BY {$sort} {$order} " :
                ' ORDER BY Unit_Price ';

            // order by before group by will show the product List item instead of freehand item if both with same name and case price exists

            $finalDataQuery = "$mainQuery $fromQuery $whereQuery $mainQueryEnd $groupQuery2 $orderConditional $limitConditional ";
            $finalCatQuery = "$catQuery $fromQuery $whereQuery $groupQuery";
            \Log::info("Inventory Report final Data query \n ".$finalDataQuery);
            //\Log::info("Inventory Report final Cat query \n ".$finalCatQuery);
            $rawRows = \DB::select($finalDataQuery);
            $rawCats = \DB::select($finalCatQuery);
            $rows = self::processRows($rawRows);

            $humanDateRange = ReportHelpers::humanifyDateRangeMessage($date_start, $date_end);
            $location = Location::whereIn('id',explode(',',$location_id))->lists('location_name')->implode(', ');
            if(isset($forAllLocations))
            {
                $location_id = 'All Locations';
            }
            $topMessage = "Inventory Report $humanDateRange ($location_id)";
        }

        return $results = array(
            'topMessage' => $topMessage,
            'bottomMessage' => $bottomMessage,
            'message' => $message,
            'rows'=> $rows,
            'categories'=> $rawCats,
            'total' => $total,
            'excelExcludeFormatting' => ['Unit Price','Case Price','Total Spent']
        );


    }

    public static function processRows( $rows ){
        $newRows = array();
        foreach($rows as $row) {

            $row->start_date = date("m/d/Y", strtotime($row->start_date));
            $row->start_date = date("m/d/Y", strtotime($row->start_date));

            $newRows[] = $row;
        }
        return $newRows;
    }
}
