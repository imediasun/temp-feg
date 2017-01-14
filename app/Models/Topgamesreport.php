<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Library\ReportHelpers;
use SiteHelpers;

class topgamesreport extends Sximo  {
	
	public function __construct() {
		parent::__construct();
	}

	public static function processRows( $rows ){
        $newRows = array();
        foreach($rows as $row) {
		
            $row->date_start = date("m/d/Y", strtotime($row->date_start));
            $row->date_end = date("m/d/Y", strtotime($row->date_end));
		          
            $row->game_average = '$' . number_format($row->game_average,2);
            $row->game_total = '$' . number_format($row->game_total,2);
                       
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
            'date_start' => '', 'date_end' => '', 'game_cat_id' => '', 'game_type_id'  => '',
            'debit_type_id' => '', 'game_on_test' => '', 'location_id' => ''
        ));        
        extract($filters);
        ReportHelpers::dateRangeFix($date_start, $date_end);        
        if (empty($location_id)) {
            $location_id = SiteHelpers::getCurrentUserLocationsFromSession();
        }
		$offset = ($page-1) * $limit ;
        $total = ReportHelpers::getGameRankCount($date_start, $date_end, $location_id, $debit_type_id, $game_type_id, $game_cat_id, $game_on_test);
        if ($offset >= $total) {
            $page = ceil($total/$limit);
            $offset = ($page-1) * $limit ;
        }           
		$limitConditional = ($page !=0 && $limit !=0) ? " LIMIT  $offset , $limit" : '';
        
        $mainQuery = ReportHelpers::getGameRankQuery($date_start, $date_end, $location_id, $debit_type_id, $game_type_id, $game_cat_id, $game_on_test, $sort, $order);
        $mainQuery .= $limitConditional;
        $rawRows = \DB::select($mainQuery);
        $rows = self::processRows($rawRows);            
        
        if ($total == 0) {
            $message = "To view the contents of this report, please select a date range and other search filter.";
	}
        $humanDateRange = ReportHelpers::humanifyDateRangeMessage($date_start, $date_end);
        $topMessage = "Game Play Ranking $humanDateRange";
        
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
