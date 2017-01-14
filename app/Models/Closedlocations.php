<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Library\ReportHelpers;
use SiteHelpers;

class closedlocations extends Sximo  {
	
	public function __construct() {
		parent::__construct();
	}
	public static function processRows( $rows ){
        $newRows = array();
        foreach($rows as $row) {
            $row->date_start = date("m/d/Y", strtotime($row->date_start));
            $row->date_end = date("m/d/Y", strtotime($row->date_end));
            $row->closed_date = date("m/d/Y", strtotime($row->closed_date));
            $newRows[] = $row;
        }
		return $newRows;
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
        
        $filters = self::getSearchFilters(array(
            'date_start' => '', 'date_end' => '', 'id' => 'location_id', 'debit_type_id'  => ''
        ));        
        extract($filters);
        ReportHelpers::dateRangeFix($date_start, $date_end);        
        
        if (empty($location_id)) {
            $location_id = SiteHelpers::getCurrentUserLocationsFromSession();
        }
        $total = ReportHelpers::getClosedLocationsCount($date_start, $date_end, $location_id, $debit_type_id);
        $offset = ($page-1) * $limit ;
        if ($offset >= $total) {
            $page = ceil($total/$limit);
            $offset = ($page-1) * $limit ;
        }   
		$limitConditional = ($page !=0 && $limit !=0) ? " LIMIT  $offset , $limit" : '';
        
        $mainQuery = ReportHelpers::getClosedLocationsQuery($date_start, $date_end, $location_id, $debit_type_id, $sort, $order);
        $mainQuery .= $limitConditional;
        $rawRows = \DB::select($mainQuery);
        $rows = self::processRows($rawRows);
                
        if ($total == 0) {
            $message = "To view the contents of this report, please select a date range and other search filter.";
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
