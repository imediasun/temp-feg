<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Library\ReportHelpers;

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

		$offset = ($page-1) * $limit ;
		$limitConditional = ($page !=0 && $limit !=0) ? " LIMIT  $offset , $limit" : '';

        $filters = ReportHelpers::getSearchFilters(array(
            'date_start' => '', 'date_end' => '', 'debit_type_id' => '', 'location_id' => ''
        ));        
        extract($filters);
        ReportHelpers::dateRangeFix($date_start, $date_end);        
        $mainQuery = ReportHelpers::getMerchandizeExpensesQuery($date_start, $date_end, $location_id, $debit_type_id, $sort, $order);
        $mainQuery .= $limitConditional;
        $total = ReportHelpers::getMerchandizeExpensesCount($date_start, $date_end, $location_id, $debit_type_id);
        $rawRows = \DB::select($mainQuery);
        $rows = self::processRows($rawRows);            
        
        if ($total == 0) {
            $message = "No data found. Try searching with other filters.";
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
