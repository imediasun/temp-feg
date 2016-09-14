<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class nonfegreaders extends Sximo  {
	
	protected $table = 'reader_exclude';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect2(  ){		
		return " SELECT id, loc_id AS location_id, loc_id AS location_name, debit_type_id, debit_type_id AS debit_type, reader_id, reason FROM reader_exclude ";
	}	
	public static function querySelect( $isCount = false  ){
		$filters = self::getSearchFilters();
        $location = @$filters['location_id'];
        $debitType = @$filters['debit_type_id'];
        $locationQuery = "";
        if (!empty($location)) {
            $locationQuery = "AND reader_exclude.loc_id IN ($location) "; 
        }
        $debitTypeQuery = "";
        if (!empty($debitType)) {
            $debitTypeQuery = "AND reader_exclude.debit_type_id IN ($debitType) "; 
        }
        
        if ($isCount) {
           $sqlFields = "count(reader_exclude.id) as totalCount"; 
        }
        else {
            $sqlFields = "reader_exclude.id, 
                            reader_exclude.loc_id AS location_id,
                            location.location_name_short as location_name, 
                            reader_exclude.debit_type_id, 
                            debit_type.company AS debit_type,
                            reader_exclude.reader_id,
                            reader_exclude.reason";
        }
        
        $sql = "SELECT $sqlFields 
                FROM reader_exclude 
                LEFT JOIN debit_type ON debit_type.id = reader_exclude.debit_type_id
                LEFT JOIN location ON location.id = reader_exclude.loc_id
                WHERE reader_exclude.loc_id <> 0 $locationQuery $debitTypeQuery";
        
		return $sql;
	}
	public static function queryWhere(  ){
		
		return " WHERE reader_id IS NOT NULL ";
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

        $total = 0;
        $totalQuery = self::querySelect(true);
        $totalRows = \DB::select($totalQuery);
        if (!empty($totalRows) && isset($totalRows[0])) {
            $total = $totalRows[0]->totalCount;
        }
        $offset = ($page-1) * $limit ;
        if ($offset >= $total) {
            $page = ceil($total/$limit);
            $offset = ($page-1) * $limit ;
        }           
        $limitConditional = ($page !=0 && $limit !=0) ? " LIMIT  $offset , $limit" : '';    
       
		$orderConditional = ($sort !='' && $order !='') ?  " ORDER BY {$sort} {$order} " : '';
        
        $selectQuery = self::querySelect(). " {$orderConditional} {$limitConditional}";
        $rawRows = \DB::select($selectQuery);
        $rows = self::processRows($rawRows);
        
		return $results = array(
                    'topMessage' => $topMessage,
                    'bottomMessage' => $bottomMessage,
                    'message' => $message,
                    
                    'rows'=> $rows, 
                    'total' => $total
                );


	}
	public static function processRows( $rows ){
        $newRows = array();
        foreach($rows as $row) {
            
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

}
