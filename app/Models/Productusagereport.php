<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use SiteHelpers;
use App\Library\ReportHelpers;
use App\Library\DBHelpers;
use \App\Models\Sximo\Module;
class productusagereport extends Sximo  {

    protected $table = 'orders';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){

        return "  SELECT orders.* FROM orders  ";
    }

    public static function queryWhere(  ){

        return "  WHERE orders.id IS NOT NULL ";
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
            $location_id = SiteHelpers::getCurrentUserLocationsFromSession();
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
                $whereLocation = "AND O.location_id IN ($location_id) ";
            }
            if (!empty($vendor_id)) {
                $whereVendor = "AND V.id IN ($vendor_id) ";
            }
            if (!empty($prod_type_id)) {
                $whereProdType = "AND P.prod_type_id IN ($prod_type_id) ";
            }
            if (!empty($order_type_id)) {
                $whereOrderType = "AND O.order_type_id IN ($order_type_id) ";
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
            $mainQuery = "select OCID,id,orderId,sku,num_items,Order_Type,Product_Type,Product_Sub_Type,ticket_value,
            (select price from order_contents OC where OC.item_name = Product AND OC.order_id = maxOrderId limit 1) as Unit_Price,
            (select case_price from order_contents OC where OC.item_name = Product AND OC.order_id = maxOrderId limit 1) as Case_Price,
            Cases_Ordered,vendor_name,Product,Case_Price_Group,Total_Spent,location_id,start_date,end_date from (
            
            SELECT max(OCID) as OCID,
            max(id) as id,GROUP_CONCAT(DISTINCT orderId SEPARATOR '-') as orderId,max(orderId) as maxOrderId, max(sku) as sku, max(num_items) as num_items,
            GROUP_CONCAT(DISTINCT order_type) AS Order_Type,
            GROUP_CONCAT(DISTINCT prod_type_id) AS Product_Type,
            GROUP_CONCAT(DISTINCT type_description) AS Product_Sub_Type,
            vendor_name,Product,max(ticket_value) as ticket_value
            , Unit_Price,
            SUM(qty) AS Cases_Ordered,
            IF(order_type_id IN(".$casePriceCats."), Case_Price,Unit_Price) AS Case_Price_Group,
            Case_Price,TRUNCATE((SUM(TRUNCATE(total, 3))),3) AS Total_Spent,location_id,start_date,end_date
             FROM (
            Select O.id as orderId,
                   P.id,
                   OC.id as OCID,
                   P.sku,
                   V.vendor_name as vendor_name,
                   OC.item_name AS Product,
                   IF(P.ticket_value = 0, '', P.ticket_value) AS ticket_value,
                   IF(P.num_items = '' OR P.num_items IS NULL, 0, P.num_items) AS num_items,
				   OC.price AS Unit_Price,
				   OC.qty,
				   O.order_type_id,
				   OC.case_price AS Case_Price,
				   OC.total,
				   T1.order_type,
				   P.prod_type_id,
				   D.type_description,
				   O.location_id,
				   O.date_ordered AS start_date,
				   O.date_ordered AS end_date 
                        ";
            $mainQueryEnd  = " ) AS t ";
            $mainQueryEnd2  = " ) AS m ";

            $fromQuery = " FROM order_contents OC 
                           JOIN orders O ON O.id = OC.order_id 
						   LEFT JOIN location L ON L.id = O.location_id
						   LEFT JOIN products P ON P.id = OC.product_id 
						   LEFT JOIN vendor V ON V.id = O.vendor_id 
						   LEFT JOIN order_type T1 ON T1.id = O.order_type_id
						   LEFT JOIN product_type D ON D.id = P.prod_sub_type_id
						   
						   
						   ";

            $whereQuery = " WHERE O.date_ordered >= '$date_start'
                            AND O.date_ordered <= '$date_end' 
                             $whereLocation $whereVendor $whereOrderType $whereProdType $whereProdSubType ";
            /*$whereQuery = " WHERE requests.status_id = 2
                            AND requests.process_date >= '$date_start'
                            AND requests.process_date <= '$date_end'
                             $whereLocation $whereVendor $whereOrderType $whereProdType ";*/

            $groupQuery = " GROUP BY Product , sku";
//            $groupQuery = " GROUP BY P.id ";

            $finalTotalQuery = "$mainQuery $fromQuery $whereQuery $mainQueryEnd $groupQuery $mainQueryEnd2";
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
                ' ORDER BY vendor_name, Product_Type ';

            $finalDataQuery = "$mainQuery $fromQuery $whereQuery $mainQueryEnd $groupQuery $mainQueryEnd2 $orderConditional $limitConditional";
            \Log::info("Product Usage final Data query \n ".$finalDataQuery);
            $rawRows = \DB::select($finalDataQuery);
            $rows = self::processRows($rawRows);

            $humanDateRange = ReportHelpers::humanifyDateRangeMessage($date_start, $date_end);
            $topMessage = "Products usage $humanDateRange";
        }

        return $results = array(
            'topMessage' => $topMessage,
            'bottomMessage' => $bottomMessage,
            'message' => $message,
            'rows'=> $rows,
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
