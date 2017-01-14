<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use SiteHelpers;

class merchthrowssimple extends Sximo  {
	
	protected $table = 'merch_throws';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}
    public static function build_query(){
        $filters = self::getSearchFilters();
        $location = @$filters['location_id'];
        if (empty($location)) {
            $location = SiteHelpers::getCurrentUserLocationsFromSession();
        }        
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

        $sql = "SELECT merch_throws.game_id as id,
                                    CONCAT(G.id,' | ',I.game_title) AS 'Game',
                                    I.game_title,
                                    merch_throws.price_per_play,
                                    CONCAT(IF(merch_throws.product_id_1 = 0,'',P1.vendor_description),IF(merch_throws.product_id_2 = 0,'',CONCAT(' | ',P2.vendor_description)),IF(merch_throws.product_id_3 = 0,'',CONCAT(' | ',P3.vendor_description)),IF(merch_throws.product_id_4 = 0,'',CONCAT(' | ',P4.vendor_description)),IF(merch_throws.product_id_5 = 0,'',CONCAT(' | ',P5.vendor_description))) 
                                        AS Prizes,
                                    merch_throws.game_earnings AS 'Earnings',
                                    (merch_throws.product_cogs_1 + merch_throws.product_cogs_2 + merch_throws.product_cogs_3 + merch_throws.product_cogs_4 + merch_throws.product_cogs_5) 
                                        AS 'COGS',
                                     merch_throws.game_throw AS 'Throw',
                                     merch_throws.location_id,
                                     L.location_name_short as 'Location Name',
                                     CONCAT(L.id,' - ', L.location_name_short) AS 'Location',
                                     CONCAT(U.first_name,' ',U.last_name) AS 'Submitted By',									 
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
                                    $dateEnd_expression
                               GROUP BY G.id";
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
        $mainQuery = self::build_query();
        $totalRows = \DB::select($mainQuery);
        if (!empty($totalRows)) {
            $total = count($totalRows);
        }
        $offset = ($page-1) * $limit ;
        if ($offset >= $total) {
            $page = ceil($total/$limit);
            $offset = ($page-1) * $limit ;
        }           
		$limitConditional = ($page !=0 && $limit !=0) ? "LIMIT  $offset , $limit" : '';
        
		$orderConditional = ($sort !='' && $order !='') ?  " ORDER BY {$sort} {$order} " : "ORDER BY G.id DESC";

        $selectQuery = $mainQuery. " {$orderConditional} {$limitConditional}";
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
