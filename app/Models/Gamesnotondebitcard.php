<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class gamesnotondebitcard extends Sximo  {
	
	protected $table = 'game';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect( $isCount = false  ){
		$filters = self::getSearchFilters();
        $location = @$filters['location_id'];
        $debit_type_id = @$filters['debit_type_id'];
        $locationQuery = "";
        if (!empty($location)) {
            $locationQuery = " AND game.location_id IN ($location) "; 
        }
        $debitSystemQuery = "";
        if (!empty($debit_type_id)) {
            $debitSystemQuery = " AND debit_type.id IN ($debit_type_id) "; 
        }
        
        if ($isCount) {
           $sqlFields = "count(game.id) as totalCount"; 
        }
        else {
            $sqlFields = "game.id, 
                       game.game_name, 
                       game.game_title_id,
                       game_title.game_title, 
                       game.location_id,
                       location.location_name_short as location_name,
                       location.debit_type_id,
                       debit_type.company as debit_system,
                       game.not_debit_reason,
                       game.not_debit,
                       game.sold";
        }
        
        $sql = "SELECT $sqlFields 
                FROM game 
                LEFT JOIN game_title ON game_title.id = game.game_title_id
                LEFT JOIN location ON location.id = game.location_id
                LEFT JOIN debit_type ON debit_type.id = location.debit_type_id
                WHERE game.not_debit = 1 AND game.sold = 0 
                
                $locationQuery $debitSystemQuery ";
        
		return $sql;
	}	

	public static function queryWhere(  ){
		
		return " WHERE game.not_debit = 1  AND game.sold = 0 ";
	}
	
	public static function queryGroup(){
		return "  ";
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
		$limitConditional = ($page !=0 && $limit !=0) ? "LIMIT  $offset , $limit" : '';
		$orderConditional = ($sort !='' && $order !='') ?  " ORDER BY {$sort} {$order} " : '';

		// Update permission global / own access new ver 1.1
		$table = with(new static)->table;
		if($global == 0 )
			$params .= " AND {$table}.entry_by ='".\Session::get('uid')."'";
		// End Update permission global / own access new ver 1.1

        $selectQuery = self::querySelect(). " {$orderConditional} {$limitConditional}";
        $rows = \DB::select($selectQuery);
        
        $total = 0;
        $totalQuery = self::querySelect(true);
        $totalRows = \DB::select($totalQuery);
        if (!empty($totalRows) && isset($totalRows[0])) {
            $total = $totalRows[0]->totalCount;
        }
		
		return $results = array(
                    'topMessage' => $topMessage,
                    'bottomMessage' => $bottomMessage,
                    'message' => $message,
                    
                    'rows'=> $rows, 
                    'total' => $total
                );


	}
	

}
