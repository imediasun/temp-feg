<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Library\ReportHelpers;

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
            
            $reader_id = $row->reader_id;
            $store_id_position = strripos($reader_id, '_');
            if ($store_id_position !== FALSE) {
                $reader_id = "<span style='color:#ccc;'>" . 
                        substr_replace($reader_id, "_</span>", $store_id_position, 1);
                $row->reader_id = $reader_id;
            }
            
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
            'date_start' => '', 'date_end' => '', 
            'debit_type_id' => '','location_id' => '', 'reader_id' => ''
        ));        
        extract($filters);
        if (empty($reader_id) || (!empty($date_start) && !empty($date_end))) {
            ReportHelpers::dateRangeFix($date_start, $date_end);
        }
        
        $mainQuery = ReportHelpers::getReadersMissingAssetIdQuery($date_start, $date_end, $location_id, $debit_type_id, $reader_id, $sort, $order);
        $mainQuery .= $limitConditional;
        $total = ReportHelpers::getReadersMissingAssetIdCount($date_start, $date_end, $location_id, $debit_type_id, $reader_id);
        $rawRows = \DB::select($mainQuery);
        $rows = self::processRows($rawRows);            
        
        if ($total == 0) {
            $message = "No data found. Try searhing with other filters.";
	}
        $humanDateRange = ReportHelpers::humanifyDateRangeMessage($date_start, $date_end);
        $topMessage = "Readers with missing Asset IDs $humanDateRange";
        
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
