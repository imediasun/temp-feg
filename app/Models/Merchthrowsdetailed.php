<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class merchthrowsdetailed extends Sximo  {
	
	protected $table = 'merch_throws';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}
    public static function build_query(){
        $filters = self::getSearchFilters();
        $location = @$filters['location_id'];
        $dateStart = @$filters['date_start'];
        $dateEnd = @$filters['date_end'];
        $loc_table_expression = " ";
        if (!empty($location)) {
            $loc_table_expression = " AND merch_throws.location_id IN ($location) ";
        }
        $dateStart_expression= " ";
        if (!empty($dateStart)) {
            $dateStart_expression = " AND merch_throws.date_start >= '$dateStart' ";
        }
        $dateEnd_expression = " ";
        if (!empty($dateEnd)) {
            $dateEnd_expression = " AND merch_throws.date_end <= '$dateEnd' ";
        }

        $sql = "SELECT  G.id as id,
                        I.game_title,
                        CONCAT(G.id,' | ',I.game_title) AS 'Game',
									 merch_throws.price_per_play,
									 IF(merch_throws.product_id_1 = 0, '--', P1.vendor_description) AS 'Product_1',
									 IF(merch_throws.product_qty_1 = 0, '--', merch_throws.product_qty_1) AS 'QTY_1',
									 IF(merch_throws.product_cogs_1 = 0, '--', merch_throws.product_cogs_1) AS 'COGS_1',
									 IF(merch_throws.product_throw_1 = 0, '--', merch_throws.product_throw_1) AS 'Throw_%_1',
									 IF(merch_throws.product_id_2 = 0, '--', P2.vendor_description) AS 'Prod_2',
									 IF(merch_throws.product_qty_2 = 0, '--', merch_throws.product_qty_2) AS 'QTY_2',
									 IF(merch_throws.product_cogs_2 = 0, '--', merch_throws.product_cogs_2) AS 'COGS_2',
									 IF(merch_throws.product_throw_2 = 0, '--', merch_throws.product_throw_2) AS 'Throw_%_2',
									 IF(merch_throws.product_id_3 = 0, '--', P3.vendor_description) AS 'Prod_3',
									 IF(merch_throws.product_qty_3 = 0, '--', merch_throws.product_qty_3) AS 'QTY_3',
									 IF(merch_throws.product_cogs_3 = 0, '--', merch_throws.product_cogs_3) AS 'COGS_3',
									 IF(merch_throws.product_throw_3 = 0, '--', merch_throws.product_throw_3) AS 'Throw_%_3',
									 IF(merch_throws.product_id_4 = 0, '--', P4.vendor_description) AS 'Prod_4',
									 IF(merch_throws.product_qty_4 = 0, '--', merch_throws.product_qty_4) AS 'QTY_4',
									 IF(merch_throws.product_cogs_4 = 0, '--', merch_throws.product_cogs_4) AS 'COGS_4',
									 IF(merch_throws.product_throw_4 = 0, '--', merch_throws.product_throw_4) AS 'Throw_%_4',
									 IF(merch_throws.product_id_5 = 0, '--', P5.vendor_description) AS 'Prod_5',
									 IF(merch_throws.product_qty_5 = 0, '--', merch_throws.product_qty_5) AS 'QTY_5',
									 IF(merch_throws.product_cogs_5 = 0, '--', merch_throws.product_cogs_5) AS 'COGS_5',
									 IF(merch_throws.product_throw_5 = 0, '--', merch_throws.product_throw_5) AS 'Throw_%_5',
									 merch_throws.game_earnings AS 'GAME_EARNINGS',
									 (merch_throws.product_cogs_1 + merch_throws.product_cogs_2 + merch_throws.product_cogs_3 + merch_throws.product_cogs_4 + merch_throws.product_cogs_5) AS 'GAME_COGS',
									 merch_throws.game_throw AS 'GAME_THROW',
                                     merch_throws.location_id,
                                     L.location_name_short as 'Location_Name',									 
									 CONCAT(L.id,' - ', L.location_name_short) AS 'Location',
									 CONCAT(U.first_name,' ',U.last_name) AS 'Submitted_By',									 
									 merch_throws.date_start,
									 merch_throws.date_end,
									 merch_throws.notes
							      FROM merch_throws 
						     LEFT JOIN location L ON L.id = merch_throws.location_id
						     LEFT JOIN products P1 ON P1.id = merch_throws.product_id_1
						     LEFT JOIN products P2 ON P2.id = merch_throws.product_id_2
						     LEFT JOIN products P3 ON P3.id = merch_throws.product_id_3
						     LEFT JOIN products P4 ON P4.id = merch_throws.product_id_4
						     LEFT JOIN products P5 ON P5.id = merch_throws.product_id_5
						     LEFT JOIN game G ON G.id = merch_throws.game_id
						     LEFT JOIN game_title I ON I.id = G.game_title_id
						     LEFT JOIN users U ON U.id = merch_throws.user_id
                                WHERE merch_throws.location_id <> 0 
                                    $loc_table_expression
                                    $dateStart_expression
                                    $dateEnd_expression";
        return $sql;
    }    
		
	public static function querySelect( $isCount = false  ){
        $sql = "";
        
		return $sql;
	}	
	public static function queryWhere(  ){
		
		return " WHERE location_id <> 0 ";
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
		$orderConditional = "ORDER BY date_start DESC, L.id DESC";//($sort !='' && $order !='') ?  " ORDER BY {$sort} {$order} " : '';

		// Update permission global / own access new ver 1.1
		$table = with(new static)->table;
		if($global == 0 )
			$params .= " AND {$table}.entry_by ='".\Session::get('uid')."'";
		// End Update permission global / own access new ver 1.1
        
        $mainQuery = self::build_query();
        $selectQuery = $mainQuery. " {$orderConditional} {$limitConditional}";
        $rawRows = \DB::select($selectQuery);
        $rows = self::processRows($rawRows);
        
        $total = 0;
        $totalRows = \DB::select($mainQuery);
        if (!empty($totalRows)) {
            $total = count($totalRows);
        }
		
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

            $dsEpoch = strtotime($row->date_start);
            if ($dsEpoch !== FALSE && $dsEpoch > 0) {
                $row->date_start = date("m/d/Y", strtotime($row->date_start));
            }
            else {
                $row->date_start = "";
            }            
            $deEpoch = strtotime($row->date_end);
            if ($deEpoch !== FALSE && $deEpoch > 0) {
                $row->date_end = date("m/d/Y", strtotime($row->date_end));
            }
            else {
                $row->date_end = "";
            } 
            $newRows[] = $row;
        }
		return $newRows;
	}     

}
