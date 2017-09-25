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
            $location_id = \Session::get('selected_location');
        }
        if (empty($location_id)) {
            return ReportHelpers::buildBlankResultDataDueToNoLocation();
        }

        $defaultEndDate = DBHelpers::getHighestRecorded('orders', 'date_ordered');
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
                $whereLocation = "AND O.location_id = ($location_id) ";
            }
            if (!empty($vendor_id)) {
                $whereVendor = "AND V.id IN ($vendor_id) ";
            }
            if (!empty($order_type_id)) {
                $whereOrderType = "AND O.order_type_id IN ($order_type_id) ";
            }
            if (!empty($prod_type_id)) {
                $whereProdType = "AND P.prod_type_id IN ($prod_type_id) ";
            }
            if (!empty($prod_sub_type_id)) {
                $whereProdSubType = "AND P.prod_sub_type_id IN ($prod_sub_type_id) ";
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
            $mainQuery = "
            SELECT id,sku,num_items,'' AS unit_inventory_count,'' AS total_inventory_value,GROUP_CONCAT(DISTINCT order_type) AS Order_Type,GROUP_CONCAT(DISTINCT prod_type_id) AS Product_Type,GROUP_CONCAT(DISTINCT type_description) AS Product_Sub_Type,vendor_name,Product,ticket_value
            ,Unit_Price,IF(order_type_id IN (".$casePriceCats."),num_items*SUM(qty),SUM(qty)) AS Cases_Ordered,Case_Price,CAST((SUM(total)) AS DECIMAL(12,5)) AS Total_Spent,location_id,start_date,end_date
             FROM ( 
                    SELECT P.id ,
                    P.sku,
                    P.num_items,
                    T1.order_type,O.order_type_id,
                    P.prod_type_id,
                    D.type_description,
                    V.vendor_name AS vendor_name,
                    OC.item_name AS Product,
                    P.ticket_value,
                    OC.price AS Unit_Price,
                    OC.qty,
                    OC.case_price AS Case_Price,
                    OC.total,
                    O.location_id,
                    O.date_ordered AS start_date,
                    O.date_ordered AS end_date
                        ";
            $mainQueryEnd  = " ) AS t ";
            $orderBy = " ORDER BY P.id ASC LIMIT 0 , 20000000000000";

            $catQuery = "Select distinct T1.order_type";

            $fromQuery = " FROM order_contents OC 
                           LEFT JOIN products P ON P.id = OC.product_id 
                           JOIN orders O ON O.id = OC.order_id
						   LEFT JOIN location L ON L.id = O.location_id
						   LEFT JOIN vendor V ON V.id = O.vendor_id 
						   LEFT JOIN order_type T1 ON T1.id = O.order_type_id
						   LEFT JOIN product_type D ON D.id = P.prod_sub_type_id
						   
						   ";

            $whereQuery = " WHERE O.status_id != ".order::ORDER_VOID_STATUS ." AND O.date_ordered >= '$date_start'
                            AND O.date_ordered <= '$date_end' 
                             $whereLocation $whereVendor $whereOrderType $whereProdType $whereProdSubType ";

            // both group by quires are same
            $groupQuery = " GROUP BY OC.item_name,OC.case_price ";
            $groupQuery2 = " GROUP BY Product,Case_Price ";


            $finalTotalQuery = "$mainQuery $fromQuery $whereQuery $orderBy $mainQueryEnd $groupQuery2";
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

            $finalDataQuery = "$mainQuery $fromQuery $whereQuery $orderBy $mainQueryEnd $groupQuery2 $orderConditional $limitConditional ";
            $finalCatQuery = "$catQuery $fromQuery $whereQuery $groupQuery";
            \Log::info("Inventory Report final Data query \n ".$finalDataQuery);
            $rawRows = \DB::select($finalDataQuery);
            $rawCats = \DB::select($finalCatQuery);
            $rows = self::processRows($rawRows);

            $humanDateRange = ReportHelpers::humanifyDateRangeMessage($date_start, $date_end);
            $location = Location::find($location_id)->location_name;
            $topMessage = "Inventory Report $humanDateRange $location $location_id";
        }

        return $results = array(
            'topMessage' => $topMessage,
            'bottomMessage' => $bottomMessage,
            'message' => $message,
            'rows'=> $rows,
            'categories'=> $rawCats,
            'total' => $total
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
