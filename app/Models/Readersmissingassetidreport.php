<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Library\ReportHelpers;
use App\Library\DBHelpers;
use SiteHelpers;

class readersmissingassetidreport extends Sximo  {
	
	public function __construct() {
		parent::__construct();
	}

	public static function processRows( $rows ){
        $newRows = array();
        foreach($rows as $row) {

            $row->date_start = date("m/d/Y", strtotime($row->date_start));
            $row->date_end = date("m/d/Y", strtotime($row->date_end));
            $row->game_total = '$' . number_format($row->game_total,2);
//
//            $reader_id = $row->reader_id;
//            $store_id_position = strripos($reader_id, '_');
//            if ($store_id_position !== FALSE) {
//                $reader_id = "<span style='color:#ccc;'>" . 
//                        substr_replace($reader_id, "_</span>", $store_id_position, 1);
//                $row->reader_id = $reader_id;
//            }
            
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
            'date_start' => '', 'date_end' => '', 
            'debit_type_id' => '','location_id' => '', 'reader_id' => ''
        ));        
        extract($filters);
        if (empty($location_id)) {
            $location_id = SiteHelpers::getCurrentUserLocationsFromSession();
        }    
        if (empty($location_id)) {
            return ReportHelpers::buildBlankResultDataDueToNoLocation();
        } 
        
        if (empty($reader_id) || (!empty($date_start) && !empty($date_end))) {
            $defaultEndDate = DBHelpers::getHighestRecorded('game_earnings', 'date_start');
            ReportHelpers::dateRangeFix($date_start, $date_end, true, $defaultEndDate, 7);
            //ReportHelpers::dateRangeFix($date_start, $date_end);
        }
        
		$offset = ($page-1) * $limit ;
        $total = ReportHelpers::getReadersMissingAssetIdCount($date_start, $date_end, $location_id, $debit_type_id, $reader_id);
        if ($offset >= $total && $limit != 0) {
            $page = ceil($total/$limit);
            $offset = ($page-1) * $limit ;
        }           
		$limitConditional = ($page !=0 && $limit !=0) ? " LIMIT  $offset , $limit" : '';    
        
        $mainQuery = ReportHelpers::getReadersMissingAssetIdQuery($date_start, $date_end, $location_id, $debit_type_id, $reader_id, $sort, $order);
        $mainQuery .= $limitConditional;
        $rawRows = \DB::select($mainQuery);
        $rows = self::processRows($rawRows);            
        
        if ($total == 0) {
            $message = "To view the contents of this report, please select a date range and other search filter.";
	}
        $humanDateRange = ReportHelpers::humanifyDateRangeMessage($date_start, $date_end);
        $topMessage = "Readers with missing Asset ID $humanDateRange";
        
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
