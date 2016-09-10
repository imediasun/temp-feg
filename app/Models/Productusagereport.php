<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class productusagereport extends Sximo  {
	
	protected $table = 'requests';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT requests.* FROM requests  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE requests.id IS NOT NULL ";
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

		$offset = ($page-1) * $limit ;
		$limitConditional = ($page !=0 && $limit !=0) ? "LIMIT  $offset , $limit" : '';
		$orderConditional = ($sort !='' && $order !='') ?  " ORDER BY {$sort} {$order} " :
                ' ORDER BY V.vendor_name, P.prod_type_id, P.vendor_description ';

		// Update permission global / own access new ver 1.1
		$table = with(new static)->table;
		if($global == 0 )
			$params .= " AND {$table}.entry_by ='".\Session::get('uid')."'";
		// End Update permission global / own access new ver 1.1

		$rows = array();
        $total = 0;
        
        $filters = self::getSearchFilters();
        $date_start = @$filters['start_date'];
        $date_end = @$filters['end_date'];
        $location_id = @$filters['location_id'];
        $vendor_id = @$filters['vendor_id'];
        $prod_type_id = @$filters['prod_type_id'];
        $prod_sub_type_id = @$filters['prod_sub_type_id'];
        
        if (empty($date_start) || empty($date_end)) {
            $message = "To view the contents of this report, please select a date range and other search filter.";
        }
        else {
            
            $whereLocation = "";
            $whereVendor = "";
            $whereOrderType ="";
            $whereProdType = "";
            if (!empty($location_id)) {
                $whereLocation = "AND L.id IN ($location_id) ";                
            }
            if (!empty($vendor_id)) {
                $whereVendor = "AND V.id IN ($vendor_id) ";
            }
            if (!empty($prod_type_id)) {
                $whereOrderType = "AND P.prod_type_id IN ($prod_type_id) ";
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
            
            $mainQuery = "SELECT requests.id, 
									 V.vendor_name,
									 P.vendor_description AS Product,
									 P.ticket_value,
									 P.num_items,
									 ROUND(P.case_price / P.num_items,2) AS Unit_Price,
									 SUM(requests.qty) AS Cases_Ordered,
									 P.case_price AS Case_Price,
									 SUM(requests.qty * P.case_price) AS Total_Spent,
									 T.order_type AS Order_Type,
									 D.type_description AS Product_Type,
									 requests.location_id,
									 requests.id as vendor_id,
									 requests.id as prod_type_id,
									 requests.id as prod_sub_type_id,
									 requests.process_date as start_date,
									 requests.process_date as end_date ";
            $totalQuery = "SELECT count(*) as total ";
            
            $fromQuery = " FROM requests 
						   LEFT JOIN location L ON L.id = requests.location_id
						   LEFT JOIN products P ON P.id = requests.product_id
						   LEFT JOIN vendor V ON V.id = P.vendor_id
						   LEFT JOIN order_type T ON T.id = P.prod_type_id
						   LEFT JOIN product_type D ON D.id = P.prod_sub_type_id
						   LEFT JOIN users U ON U.id = requests.process_user_id ";
            
            $whereQuery = " WHERE requests.status_id = 2
                            AND requests.process_date >= '$date_start'
                            AND requests.process_date <= '$date_end' 
                             $whereLocation $whereVendor $whereOrderType $whereProdType ";
            
            $groupQuery = " GROUP BY P.id ";
            
        
            
            $finalDataQuery = "$mainQuery $fromQuery $whereQuery $groupQuery $orderConditional $limitConditional";
            $finalTotalQuery = "$totalQuery $fromQuery $whereQuery $groupQuery";
            
            $rawRows = \DB::select($finalDataQuery);
            $rows = self::processRows($rawRows);
            
            $totalRows = \DB::select($finalTotalQuery);
            if (!empty($totalRows)) {
                $total = count($totalRows);
            }
            
            $topMessage = "Showing data from " . (date("m/d/Y",$date_start_stamp)) . " to " .
                    (date("m/d/Y",$date_end_stamp));
        }      
                
		return $results = array(
            'topMessage' => $topMessage,
            'bottomMessage' => $bottomMessage,
            'message' => $message,
            'rows'=> $rows, 
            'total' => $total
        );


	}
	    
    public static function getSearchFilters() {
        $finalFilter = array();
        if (isset($_GET['search'])) {
            $filters_raw = trim($_GET['search'], "|");
            $filters = explode("|", $filters_raw);

            foreach($filters as $filter) {
                $columnFilter = explode(":", $filter);
                if (isset($columnFilter) && isset($columnFilter[0]) && isset($columnFilter[2])) {
                    $finalFilter[$columnFilter[0]] = $columnFilter[2];
                }
            }
        }
        return $finalFilter;
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
