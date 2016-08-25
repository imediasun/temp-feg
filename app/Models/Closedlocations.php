<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Library\ReportHelpers;

class closedlocations extends Sximo  {
	
	public function __construct() {
		parent::__construct();
	}
            
	public static function getRows( $args,$cond=null )
	{
		extract( array_merge( array(
			'page' 		=> '0' ,
			'limit'  	=> '0' ,
			'sort' 		=> '' ,
			'order' 	=> '' ,
			'params' 	=> '' ,
			'global'	=> 1
		), $args ));

        $bottomMessage = "";
        $message = "";                
        
		$offset = ($page-1) * $limit ;
		$limitConditional = ($page !=0 && $limit !=0) ? " LIMIT  $offset , $limit" : '';

        $filters = ReportHelpers::getSearchFilters(array(
            'date_start' => '', 'date_end' => '', 'id' => 'location_id', 'debit_type_id'  => ''
        ));        
        extract($filters);
        ReportHelpers::dateRangeFix($date_start, $date_end);        

        $mainQuery = ReportHelpers::getClosedLocationsQuery($date_start, $date_end, $location_id, $debit_type_id, $sort, $order);
        $mainQuery .= $limitConditional;
        $total = ReportHelpers::getClosedLocationsCount($date_start, $date_end, $location_id, $debit_type_id);
        $rows = \DB::select($mainQuery);
                
        if ($total == 0) {
            $message = "No data found. Try searhing with other filters.";
                    }
        $humanDateRange = ReportHelpers::humanifyDateRangeMessage($date_start, $date_end);
        $topMessage = "Locations marked as closed $humanDateRange";
                
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
