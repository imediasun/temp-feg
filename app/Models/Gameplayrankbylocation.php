<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class gameplayrankbylocation extends Sximo  {
	
	protected $table = 'game_earnings';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
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
    
    public static function build_query($dateStart = ""){


        $sql = "SELECT  location.id AS id,
                        location.location_name_short AS location_name,
                        D.company AS debit_system,
                        location.debit_type_id,
						'$dateStart' as date_start,						
												ROUND(SUM(CASE WHEN E.debit_type_id = 1 THEN E.total_notional_value ELSE E.std_actual_cash END)
														/(SELECT COUNT(*) 
												            FROM game G
												           WHERE G.location_id = E.loc_id)
															/(SELECT COUNT( loc_id )
																FROM (
																    SELECT * 
																    FROM game_earnings GE
																    WHERE GE.date_start
																    BETWEEN DATE_SUB('$dateStart', INTERVAL 6 DAY )
																    AND DATE_ADD( '$dateStart', INTERVAL 1 DAY )
																    AND GE.game_id !=0
																    GROUP BY GE.loc_id, GE.date_start
																    HAVING COUNT( GE.loc_id ) > 0
																) AS DateCount
																WHERE loc_id = E.loc_id),2) AS Average,
																
												(SELECT COUNT( loc_id )
												FROM (
												    SELECT * 
												    FROM game_earnings GE
												    WHERE GE.date_start
												    BETWEEN DATE_SUB( '$dateStart', INTERVAL 6 DAY )
												    AND DATE_ADD( '$dateStart', INTERVAL 1 DAY )
												    AND GE.game_id !=0
												    GROUP BY GE.loc_id, GE.date_start
												    HAVING COUNT( GE.loc_id ) > 0
												) AS DateCount
												WHERE loc_id = E.loc_id) AS DateCount,
												
												ROUND(SUM(CASE WHEN E.debit_type_id = 1 THEN E.total_notional_value ELSE E.std_actual_cash END),2) AS Total,
												
												(SELECT COUNT(*) 
												 FROM game G
												 WHERE G.location_id = E.loc_id) AS GameCount
												 
                                        FROM location 
                                        LEFT JOIN game_earnings E ON E.loc_id = location.id
                                        LEFT JOIN game G ON G.id = E.game_id
                                        LEFT JOIN game_title T ON T.id = G.game_title_id
                                        LEFT JOIN game_type Y ON Y.id = T.game_type_id
                                        LEFT JOIN debit_type D ON D.id = E.debit_type_id
                                        WHERE location.reporting = 1
                                        AND E.date_start 
                                        BETWEEN DATE_SUB( '$dateStart', INTERVAL 6 DAY )
                                        AND DATE_ADD( '$dateStart', INTERVAL 1 DAY )
                                        GROUP BY location.id
                                        ORDER BY Average DESC";
        return $sql;
    }     
	public static function querySelect(  ){
		
		return " SELECT * from game_earnings ";
	}	

	public static function queryWhere(  ){
		
		return " WHERE id IS NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	
	public static function processRows($rows){
        $newRows = array();
        foreach($rows as $row) {
            $newRow = new \stdClass();
            
            $newRow->id = $row->id;
            $newRow->location_name = $row->location_name;
            $newRow->date_start = date("m/d/Y", strtotime($row->date_start));
            $newRow->debit_system = $row->debit_system;
            $newRow->debit_type_id = $row->debit_type_id;
            
            
            $dateCount = $row->DateCount;
            $dateCountText = "FULL";
            if($dateCount < 7) {
                $dateCountText = "PART";
            }
            
            $newRow->days_reported_count = $dateCount;
            $newRow->days_reported_text = $dateCountText;
            $newRow->days_reported = "$dateCountText ($dateCount)";
            $newRow->game_count = $row->GameCount;
            $newRow->pgpd_avg = '$' . number_format($row->Average,2);
            $newRow->location_total = '$' . number_format($row->Total,2);
                       
            $newRows[] = $newRow;
        }
		return $newRows;
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
		$limitConditional = "";($page !=0 && $limit !=0) ? "LIMIT  $offset , $limit" : '';
		$orderConditional = "";//($sort !='' && $order !='') ?  " ORDER BY {$sort} {$order} " : '';

		// Update permission global / own access new ver 1.1
		$table = with(new static)->table;
		if($global == 0 )
			$params .= " AND {$table}.entry_by ='".\Session::get('uid')."'";
		// End Update permission global / own access new ver 1.1
        $filters = self::getSearchFilters();
        $dateStart = @$filters['date_start'];
        $rows = array();
        $total = 0;
        
        if (empty($dateStart)) {
            $message = "Select a date from Search";
        }
        else {
            $mainQuery = self::build_query($dateStart);
            $selectQuery = $mainQuery. " {$orderConditional} {$limitConditional}";
            $rawRows = \DB::select($selectQuery);
            $total = count($rows);           
            
            $rows = self::processRows($rawRows);            
        }
        
        $topMessage = "Weekly Game Play Ranking by Location by Per Game Per Day (PGPD) Average";
        
		
		return $results = array(
                    'topMessage' => $topMessage,
                    'bottomMessage' => $bottomMessage,
                    'message' => $message,
                    
                    'rows'=> $rows, 
                    'total' => $total
                );


	}
}
