<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use SiteHelpers;
use App\Library\ReportHelpers;
use App\Library\DBHelpers;

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
        $prod_type_id = @$filters['prod_type_id'];
        $prod_sub_type_id = @$filters['prod_sub_type_id'];
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
            if (!empty($location_id)) {
                $whereLocation = "AND O.location_id = ($location_id) ";
            }
            if (!empty($vendor_id)) {
                $whereVendor = "AND V.id IN ($vendor_id) ";
            }
            if (!empty($prod_type_id)) {
                $whereOrderType = "AND O.order_type_id IN ($prod_type_id) ";
            }
            if (!empty($prod_sub_type_id)) {
                $whereProdType = "AND P.prod_sub_type_id IN ($prod_sub_type_id) ";
            }


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
            Select P.id,
                   P.sku,
                   P.num_items,
                   '' as unit_inventory_count,
                   '' as total_inventory_value,
                   T1.order_type AS Order_Type,
                   D.type_description AS Product_Type,
                   V.vendor_name as vendor_name,
                   IF(OC.product_id = 0,OC.item_name,P.vendor_description) AS Product,
                   P.ticket_value,
				   ROUND(P.case_price / P.num_items,2) AS Unit_Price,
				   SUM(P.num_items*OC.qty) AS Cases_Ordered,
				   OC.case_price AS Case_Price,
				   SUM(OC.total) AS Total_Spent,O.location_id,
				   O.date_ordered AS start_date,
				   O.date_ordered AS end_date
                        ";
            $catQuery = "Select distinct T1.order_type";
            $totalQuery = "SELECT count(*) as total,IF(OC.product_id = 0,OC.item_name,P.vendor_description) AS Product";

            $fromQuery = " FROM order_contents OC 
                           LEFT JOIN products P ON P.id = OC.product_id 
                           JOIN orders O ON O.id = OC.order_id
						   LEFT JOIN location L ON L.id = O.location_id
						   LEFT JOIN vendor V ON V.id = O.vendor_id 
						   LEFT JOIN order_type T1 ON T1.id = O.order_type_id
						   LEFT JOIN product_type D ON D.id = P.prod_sub_type_id
						   
						   ";

            $whereQuery = " WHERE O.date_ordered >= '$date_start'
                            AND O.date_ordered <= '$date_end' 
                             $whereLocation $whereVendor $whereOrderType $whereProdType ";

            $groupQuery = " GROUP BY (CASE WHEN (O.is_freehand = 1) THEN Product ELSE P.id END ),OC.case_price ";


            $finalTotalQuery = "$totalQuery $fromQuery $whereQuery $groupQuery";
            $totalRows = \DB::select($finalTotalQuery);
            if (!empty($totalRows)) {
                $total = $totalRows[0]->total;
            }
            $offset = ($page-1) * $limit ;
            if ($offset >= $total && $limit != 0) {
                $page = ceil($total/$limit);
                $offset = ($page-1) * $limit ;
            }
            $limitConditional = ($page !=0 && $limit !=0) ? " LIMIT  $offset , $limit" : '';

            $orderConditional = ($sort !='' && $order !='') ?  " ORDER BY {$sort} {$order} " :
                ' ORDER BY Unit_Price ';

            $finalDataQuery = "$mainQuery $fromQuery $whereQuery $groupQuery $orderConditional $limitConditional";
            $finalCatQuery = "$catQuery $fromQuery $whereQuery";
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
