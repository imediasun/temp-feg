<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Library\ReportHelpers;
use SiteHelpers;

class merchandiseexpensesreport extends Sximo  {
	
	public function __construct() {
		parent::__construct();
	}

	public static function processRows( $rows ){
        $newRows = array();
        foreach($rows as $row) {

            $row->date_start = date("F Y", strtotime($row->date_start));
            $row->date_end = date("F Y", strtotime($row->date_end));
		          
            $row->merch_budget = '$' . number_format($row->merch_budget,2);
            $row->merch_expense = '$' . number_format($row->merch_expense,2);
            $row->utilization = '$' . number_format($row->utilization,2);
                       
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
	
        $bottomMessage = "";
        $message = "";                

        $filters = self::getSearchFilters(array(
            'date_start' => '', 'date_end' => '', 'debit_type_id' => '', 'location_id' => ''
        ));        
        extract($filters);
        if (empty($location_id)) {
            $location_id = SiteHelpers::getCurrentUserLocationsFromSession();
        }
        if (empty($location_id)) {
            return ReportHelpers::buildBlankResultDataDueToNoLocation();
        } 
        
        ReportHelpers::dateRangeFix($date_start, $date_end); 
        
        $total = ReportHelpers::getMerchandizeExpensesCount($date_start, $date_end, $location_id, $debit_type_id);
		$offset = ($page-1) * $limit ;
        if ($offset >= $total) {
            $page = ceil($total/$limit);
            $offset = ($page-1) * $limit ;
        }         
		$limitConditional = ($page !=0 && $limit !=0) ? " LIMIT  $offset , $limit" : '';
        
        $mainQuery = ReportHelpers::getMerchandizeExpensesQuery($date_start, $date_end, $location_id, $debit_type_id, $sort, $order);
        $mainQuery .= $limitConditional;
        $rawRows = \DB::select($mainQuery);
        $rows = self::processRows($rawRows);            
        
        if ($total == 0) {
            $message = "To view the contents of this report, please select a date range and other search filter.";
	}
        $humanDateRange = ReportHelpers::humanifyDateRangeMessage($date_start, $date_end, "F Y");
        $topMessage = "Merchandise Expenses $humanDateRange";
        
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
