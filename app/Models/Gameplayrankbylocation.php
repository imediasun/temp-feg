<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Library\ReportHelpers;

class gameplayrankbylocation extends Sximo  {
    
	public function __construct() {
		parent::__construct();		
	}	    
	
	public static function processRows( $rows ){
        $newRows = array();
        foreach($rows as $row) {
            
            $dateCount = $row->days_reported_count;
            $fullDaysCount = $row->days_count;
            $dateCountText = "FULL";
            if($dateCount < $fullDaysCount) {
                $dateCountText = "PART";
            }
            
            $row->date_start = date("m/d/Y", strtotime($row->date_start));
            $row->date_end = date("m/d/Y", strtotime($row->date_end));
            
            $row->days_reported_text = $dateCountText;
            $row->days_reported = "$dateCountText ($dateCount)";            
            $row->pgpd_avg = '$' . number_format($row->pgpd_avg,2);
            $row->location_total = '$' . number_format($row->location_total,2);
                       
            $newRows[] = $row;
        }
		return $newRows;
	}        
	public static function getRows( $args, $cond = null )
	{
		extract(array_merge( array(
			'page' 		=> '0' ,
			'limit'  	=> '0' ,
			'sort' 		=> '' ,
			'order' 	=> '' ,
			'params' 	=> '' ,
			'global'	=> 1
		), $args ));
        
        //$offset = ($page-1) * $limit ;
        
        $bottomMessage = "";
        $message = "";                

        $filters = ReportHelpers::getSearchFilters(array(
            'date_start' => '', 'date_end' => '', 'id' => 'location_id', 'debit_type_id'  => ''
        ));        
        extract($filters);
        ReportHelpers::dateRangeFix($date_start, $date_end);        
        
        $mainQuery = ReportHelpers::getLocationRanksQuery($date_start, $date_end, $location_id, $debit_type_id, $sort, $order);
        $rawRows = \DB::select($mainQuery);
        $rows = self::processRows($rawRows);            
        $total = count($rows);                       
        
        if ($total == 0) {
            $message = "No data found. Try searhing with other filters.";
        }		        
        $humanDateRange = ReportHelpers::humanifyDateRangeMessage($date_start, $date_end);
        $topMessage = "Game Play Ranking by Location by Per Game Per Day (PGPD) Average $humanDateRange";
        
		$results = array(
            'topMessage' => $topMessage,
            'bottomMessage' => $bottomMessage,
            'message' => $message,

            'rows'=> $rows, 
            'total' => $total
        );
        
        return $results;
        
	}
}
