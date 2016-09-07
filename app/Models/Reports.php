<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Library\ReportHelpers;

class reports extends Sximo  {

	public function __construct() {
		parent::__construct();
	}
	public static function processRows( $rows ){
        $newRows = array();
        foreach($rows as $row) {
            $row->date_start = date("m/d/Y", strtotime($row->date_start));
            $row->date_end = date("m/d/Y", strtotime($row->date_end));
            $row->not_reporting_date = empty($row->not_reporting_date) ? 'Never' : date("m/d/Y", strtotime($row->not_reporting_date));
            $row->date_last_reported = empty($row->date_last_reported) ? 'Never' : date("m/d/Y", strtotime($row->date_last_reported));
            $row->days_not_reporting = is_null($row->days_not_reporting) ? 'Never' : $row->days_not_reporting;
            $newRows[] = $row;
        }
		return $newRows;
	}        
	public static function getRows( $args, $cond = null )
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

        $mainQuery = ReportHelpers::getLocationNotReportingQuery($date_start, $date_end, $location_id, $debit_type_id, $sort, $order);
        $mainQuery .= $limitConditional;
        $total = ReportHelpers::getLocationNotReportingCount($date_start, $date_end, $location_id, $debit_type_id);
        $rawRows = \DB::select($mainQuery);
        $rows = self::processRows($rawRows);        
        
        if ($total == 0) {
            $message = "No data found. Try searching with other filters.";
        }		        
        $humanDateRange = ReportHelpers::humanifyDateRangeMessage($date_start, $date_end);
        $topMessage = "Locations not reporting or closed $humanDateRange";

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
