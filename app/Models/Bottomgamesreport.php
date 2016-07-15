<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class bottomgamesreport extends Sximo  {
	
	protected $table = 'game_earnings';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){		
		return "  SELECT game_earnings.* FROM game_earnings  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE game_earnings.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

    //////////////////////////////////////////////////////////////////////
    //PARA: Date Should In YYYY-MM-DD Format
    //RESULT FORMAT:
    // '%y Year %m Month %d Day %h Hours %i Minute %s Seconds'        =>  1 Year 3 Month 14 Day 11 Hours 49 Minute 36 Seconds
    // '%y Year %m Month %d Day'                                    =>  1 Year 3 Month 14 Days
    // '%m Month %d Day'                                            =>  3 Month 14 Day
    // '%d Day %h Hours'                                            =>  14 Day 11 Hours
    // '%d Day'                                                        =>  14 Days
    // '%h Hours %i Minute %s Seconds'                                =>  11 Hours 49 Minute 36 Seconds
    // '%i Minute %s Seconds'                                        =>  49 Minute 36 Seconds
    // '%h Hours                                                    =>  11 Hours
    // '%a Days                                                        =>  468 Days
    //////////////////////////////////////////////////////////////////////
    private static function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);

    }        
    private static function getLastSyncDate() {
        $lastDate = "";//date("Y-m-d", strtotime("-1 day"));
        $selectQuery = "SELECT date_start from game_earnings 
            ORDER BY date_start DESC LIMIT 1";
        $result = \DB::select($selectQuery); 
        if (!empty($result)) {
            $lastDate = date("Y-m-d", strtotime($result[0]->date_start));
        }
        return $lastDate;
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
		$orderConditional = ($sort !='' && $order !='') ?  " ORDER BY {$sort} {$order} " : '';

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
        //$debit_type = @$filters['debit_type_id'];
        $location_id = @$filters['location_id'];
        if ($location_id == 'null') {
            $location_id = "";            
        }
        
        $last_sync = self::getLastSyncDate();
        $last_sync_stamp = strtotime($last_sync);

        if (empty($date_start)) {
            if (empty($date_end)) {
                $date_start = date("Y-m-d", strtotime("-1 day"));
            }
            else {
                $date_start = $date_end;                        
            }                    
        }
        if (empty($date_end)) {
            if (empty($date_start)) {
                $date_end = date("Y-m-d");
            }
            else {
                $date_end =  $date_start;                        
            }                    
        }
        

        $date_start_stamp = strtotime($date_start);
        $date_end_stamp = strtotime($date_end);

        if ($date_start_stamp > $date_end_stamp) {
            $date_end_stamp = $date_start_stamp;
            $date_end = $date_start;
        }                

        $dateDifference = self::dateDifference($date_start, $date_end);
        if(empty($last_sync)) {
            $topMessage = "No game earnings data received.";
        }
        else {
            $topMessage = "Latest game earnings data received on " . date("m/d/Y", $last_sync_stamp) . ".";
        }
        if ($date_start_stamp == $date_end_stamp) {
            $topMessage .= " Displaying Bottom Games for " . date("m/d/Y", $date_start_stamp) . ".";
        }
        else {
            $topMessage .= " Displaying Bottom Games between " . date("m/d/Y", $date_start_stamp) . " and " . date("m/d/Y", $date_end_stamp) . ". ";
        }


        if ($dateDifference > 31) {
            $message = "Date range can not be more than 31 days.";
        }
        else {
            
            $selectQuery = self::build_query($date_start_stamp, $date_end_stamp, $location_id, $limitConditional, $orderConditional, $filters);
            //var_dump(date("Y-m-d", $queryDateStamp));
            $rows = \DB::select($selectQuery);
            
            $totalQuery = self::build_query($date_start_stamp, $date_end_stamp, $location_id, "", $orderConditional, $filters);
            $totalRows = \DB::select($totalQuery);
            if (!empty($totalRows) && isset($totalRows[0])) {
                $total = count($totalRows);
            }            
        }
                
		return $results = array(
                    'topMessage' => $topMessage,
                    'bottomMessage' => $bottomMessage,
                    'message' => $message,
                    
                    'rows'=> $rows, 
                    'total' => $total
                );
	}    
    
    
    public static function build_query($date_start_stamp, $date_end_stamp, $location_id, $limitConditional = "", $orderConditional = " ORDER BY game_average ASC ", $filters = array()){
        $loc_sub_expression = empty($location_id) ? "":" AND G.location_id in ($location_id)";
        $date_start = date("Y-m-d", $date_start_stamp);
        $date_start_display = date("m/d/Y", $date_start_stamp);            
        $date_end =  date("Y-m-d", $date_end_stamp);              
        $date_end_display = date("m/d/Y", $date_end_stamp);            
        
        $loc_expression = empty($location_id) ? "":" AND game_earnings.loc_id in ($location_id)";
        $game_expression = "";
        
        $sql = "SELECT  game_earnings.id, 
                        T.game_title AS game_name,
                        Y.game_type_short AS game_type,
                        
                    ROUND(SUM(CASE WHEN game_earnings.debit_type_id = 1 THEN game_earnings.total_notional_value ELSE game_earnings.std_actual_cash END),2) AS game_total,
	
                    count(distinct game_earnings.game_id) AS games_count,

                    ROUND(SUM(CASE WHEN game_earnings.debit_type_id = 1 THEN game_earnings.total_notional_value ELSE game_earnings.std_actual_cash END)/
                        count(distinct game_earnings.game_id),2) AS game_average,                      
    
                    GROUP_CONCAT(DISTINCT CONCAT('', game_earnings.loc_id) ORDER BY game_earnings.loc_id separator ', ') AS location_id,
                    GROUP_CONCAT(DISTINCT L.location_name ORDER BY game_earnings.loc_id separator ', ') AS location_name,
    
                         
                    '$date_start_display' AS start_date,
                        
                    '$date_end_display' AS end_date,
                    GROUP_CONCAT(DISTINCT game_earnings.loc_id ORDER BY game_earnings.loc_id) AS location_id,
                    GROUP_CONCAT(DISTINCT L.location_name_short ORDER BY L.id) AS location_name 

                        
                FROM game_earnings
                    LEFT JOIN game G ON G.id = game_earnings.game_id
                    LEFT JOIN game_title T ON T.id = G.game_title_id
                    LEFT JOIN game_type Y ON Y.id = T.game_type_id
                    LEFT JOIN location L ON L.id = game_earnings.loc_id

                  WHERE game_earnings.date_start >= '$date_start'
                    AND game_earnings.date_end <= '$date_end 23:59:59'
                    AND L.reporting = 1
                    AND G.sold = 0
                    AND G.status_id != 3
                    AND G.test_piece = 0
                    AND Y.id IN(1,2,3,4,5,7)
                    $loc_expression
                    $game_expression
                    
               GROUP BY G.game_title_id
               $orderConditional
               $limitConditional
                ";
        
        //die($sql);
        return $sql;
    }      
}
