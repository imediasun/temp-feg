<?php namespace App\Models;


use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class closedlocations extends Sximo  {
	
	protected $table = 'location';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}
        public static function build_query( $datestamp, $debit_type = 0){
            $debitTypeQuery = "";
            if (!empty($debit_type)) {
                $debitTypeQuery = " AND location.debit_type_id = $debit_type";
            }
            
            $date_start = date("Y-m-d", $datestamp);
            $date_start_display = date("m/d/Y", $datestamp);            
            $date_end =  date("Y-m-d", $datestamp+86400);            
            $day_name = date("D", strtotime($date_start));

            $sql = "SELECT '$date_start_display' as closed_date, 
                            location.id, 
                            location.location_name_short as location_name, 
                            location.debit_type_id, 
                            '$date_start_display' as date_start,
                            '$date_start_display' as date_end,
                            debit_type.company as debit_system "
                    . "FROM location " .
                    "JOIN debit_type on debit_type.id = location.debit_type_id ".
                    "WHERE location.reporting = 1 ".  
                    "AND location.not_reporting_{$day_name} = 0 ".
                    "AND location.id NOT IN ( ".
                        "SELECT  loc_id FROM game_earnings ".
                        "WHERE date_start BETWEEN ".
                        "'{$date_start}' AND '{$date_end}') ".
                         $debitTypeQuery;
            return $sql;
        }
	
	public static function querySelect(  ){		
            return self::build_query();

	}	

	public static function queryWhere(  ){
		
		return " ";
	}
	
	public static function queryGroup(){
		return "   ";
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
        private static function getSearchFilters() {
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
                
                $filters = self::getSearchFilters();
                $date_start = @$filters['date_start'];
                $date_end = @$filters['date_end'];
                $debit_type = @$filters['debit_type_id'];
                $last_sync = self::getLastSyncDate();
                $last_sync_stamp = strtotime($last_sync);
                
                if (empty($date_start)) {
                    if (empty($date_end)) {
                        $date_start = date("Y-m-d", strtotime("-1 day"));
                    }
                    else {
                        $date_start =  date("Y-m-d", strtotime("-1 day", strtotime($date_end)));                        
                    }                    
                }
                if (empty($date_end)) {
                    if (empty($date_start)) {
                        $date_end = date("Y-m-d");
                    }
                    else {
                        $date_end =  date("Y-m-d", strtotime("+1 day", strtotime($date_start)));                        
                    }                    
                }
                else {
                    $date_end =  date("Y-m-d", strtotime("+1 day", strtotime($date_end)));    
                }
                
                $date_start_stamp = strtotime($date_start);
                $date_end_stamp = strtotime($date_end);
                $date_end_previous_date_stamp = strtotime("-1 day", $date_end_stamp);       
                $date_end_previous_date = date("Y-m-d", $date_end_previous_date_stamp);       

                if ($date_start_stamp > $date_end_stamp) {
                    $date_end_stamp = $date_start_stamp + 86400;
                    $date_end = date("Y-m-d", $date_end_stamp);
                    $date_start_stamp = $date_end_previous_date_stamp;
                    $date_start = $date_end_previous_date;
                    $date_end_previous_date_stamp = strtotime("-1 day", $date_end_stamp);       
                    $date_end_previous_date = date("Y-m-d", $date_end_previous_date_stamp);       
                }                
                
                $dateDifference = self::dateDifference($date_start, $date_end);
                if(empty($last_sync)) {
                    $topMessage = "No game earnings data received.";
                }
                else {
                    $topMessage = "Latest game earnings data received on " . date("m/d/Y", $last_sync_stamp) . ".";
                }
                if ($date_start_stamp == $date_end_previous_date_stamp) {
                    $topMessage .= " Displaying Closed Locations or not reporting for " . date("m/d/Y", $date_start_stamp) . ".";
                }
                else {
                    $topMessage .= " Displaying Closed Locations or not reporting between " . date("m/d/Y", $date_start_stamp) . " and " . date("m/d/Y", $date_end_previous_date_stamp) . ". ";
                }
                
                
                if ($dateDifference > 31) {
                    $message = "Date range can not be more than 31 days.";
                }
                else {
                    
                    for($queryDateStamp=$date_start_stamp; $queryDateStamp < $date_end_stamp; $queryDateStamp+=86400) {
                        //var_dump(date("Y-m-d", $queryDateStamp));
                        if ($queryDateStamp <= $last_sync_stamp) {
                            $selectQuery = self::build_query($queryDateStamp, $debit_type);//. " {$limitConditional} ";
                            //var_dump(date("Y-m-d", $queryDateStamp));
                            $result = \DB::select($selectQuery);
                            $rows = array_merge($rows, $result);                            
                        }
                    }

                }
                
                
		$total = count($rows);
		return $results = array(
                    'topMessage' => $topMessage,
                    'bottomMessage' => $bottomMessage,
                    'message' => $message,
                    
                    'rows'=> $rows, 
                    'total' => $total
                );


	}
	

}
