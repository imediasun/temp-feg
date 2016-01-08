<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class topgame extends Sximo  {
	
	protected $table = 'game_earnings';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}
	public static function get_top_games($locationsThatReportedIdString = null, $date_start = null, $top_or_bottom = null, $amount = null, $game_type = null, $test_piece = null, $group_by = null)
	{
		//end date is current date
		//start date is 1 week before
		$date_end = date('Y-m-d');
		//$date_end = "2015-12-17";//@todo remove hardcoded date
		$date_start = "DATE_SUB('$date_end', INTERVAL 1 WEEK)";
		$locationCondition = "";
		$havingAverage = "";
		if(isset($_GET['search'])) {
			$filters = explode("|", trim($_GET['search'], "|"));
			$columnFilters = array();
			foreach ($filters as $filter) {
				$columnFilter = explode(":", $filter);
				//print_r($columnFilter);
				if (!empty($columnFilter)) {
					if ($columnFilter[0] === "date_start") {
						$date_start = "\"".$columnFilter[2]."\"";
					}
					if ($columnFilter[0] === "date_end") {
						$date_end = $columnFilter[2];
						$date_start = "DATE_SUB('$date_end', INTERVAL 1 WEEK)";
					}
					if ($columnFilter[0] === "loc_id") {
						$locationCondition = " AND L.id = ".$columnFilter[2];
					}
					if ($columnFilter[0] === "average") {
						$havingAverage = " Having Average = ".$columnFilter[2];
					}

				}
			}
		}

		if(empty($amount))
		{
			$limit = 'LIMIT 25';
		}
		else
		{
			$limit = 'LIMIT '.$amount;
		}

		if(empty($top_or_bottom) || $top_or_bottom == 'top')
		{
			$top_or_bottom = 'ORDER BY Average DESC';
		}
		else
		{
			$top_or_bottom = 'ORDER BY Average ASC';
		}

		if(empty($test_piece) || $test_piece == 'not_test')
		{
			$where_test_piece = 'AND G.test_piece = 0';
		}
		else
		{
			$where_test_piece = 'AND G.test_piece = 1';
		}

		if(empty($group_by) || $group_by == 'game_title')
		{
			$group_by_expression = 'GROUP BY G.game_title_id';
		}
		else
		{
			$group_by_expression = 'GROUP BY G.id';
		}
		//adding group by executes query very slow. No response till 5 minutes
		$group_by_expression = '';

		// id game_type game_type_short
		// 1 Coin Action COIN
		// 2 Kiddie/Table KDR
		// 3 Merchandise MER
		// 4 Redemption RED
		// 5 Video VDO
		// 6 Ticket/Changer/Kiosk TCK
		// 7 Photo PHTO
		// 8 Major Attraction ATTR
		// 9 Furniture and Fixtures F&F
		// 10 Game AccessoryACC

		if(empty($game_type))
		{
			$where_game_type = '';
		}
		else if($game_type == 'all')
		{
			$where_game_type = 'AND Y.id IN(1,2,3,4,5,7,8)';
		}
		else if($game_type == 'attractions')
		{
			$where_game_type = 'AND Y.id IN(8)';
		}
		else if($game_type == 'not_attractions')
		{
			$where_game_type = 'AND Y.id IN(1,2,3,4,5,7)';
		}

		//echo $date_start."=>".$date_end;
		//exit;


		//////////////////////
		// TOP/BOTTOM GAMES //
		//////////////////////
		$topGamesQuery = 'SELECT T.game_title AS Game,
											T.id,
											Y.game_type_short AS Type,
											L.location_name,
											IF(G.test_piece = 1,"1",
											   (SELECT COUNT(*)
												  FROM game G
											 LEFT JOIN game_title T ON T.id = G.game_title_id
											 LEFT JOIN location L ON L.id = G.location_id
												 WHERE T.id = (SELECT G.game_title_id FROM game G WHERE id = E.game_id AND L.reporting = 1) GROUP BY T.game_title)
											) AS Count,
											IF(G.test_piece = 0,
												ROUND(SUM(CASE WHEN E.debit_type_id = 1 THEN E.total_notional_value ELSE E.std_actual_cash END)),
												ROUND(SUM(CASE WHEN E.debit_type_id = 1 THEN E.total_notional_value ELSE E.std_actual_cash END)
												/(SELECT COUNT(*)
												    FROM game G
											   LEFT JOIN game_title T ON T.id = G.game_title_id
				  	  						   LEFT JOIN location L ON L.id = G.location_id
												   WHERE T.id = (SELECT G.game_title_id FROM game G WHERE id = E.game_id AND L.reporting = 1) GROUP BY T.game_title)
												,2)
											) AS Average,
											ROUND(SUM(CASE WHEN E.debit_type_id = 1 THEN E.total_notional_value ELSE E.std_actual_cash END
											),2) AS Total,

											  (SELECT COUNT(*)
											   FROM game G
											   LEFT JOIN game_title T ON T.id = G.game_title_id
			  	  							   LEFT JOIN location L ON L.id = G.location_id
											   WHERE T.id = (SELECT G.game_title_id FROM game G WHERE id = E.game_id AND L.reporting = 1) GROUP BY T.game_title) AS "# Games"
											   FROM game_earnings E
										  LEFT JOIN game G ON G.id = E.game_id
										  LEFT JOIN game_title T ON T.id = G.game_title_id
										  LEFT JOIN game_type Y ON Y.id = T.game_type_id
										  LEFT JOIN location L ON L.id = E.loc_id
											  WHERE E.date_start BETWEEN '.$date_start.' AND "'.$date_end.'"
											    AND G.sold = 0
											    AND G.status_id != 3
												   '.$where_game_type.'
												   '.$locationCondition.'
												   '.$where_test_piece.'
												   '.$group_by_expression.'
												   '.$havingAverage;

		//removed part AND L.id IN '.$locationsThatReportedIdString.'
		//echo $topGamesQuery;exit;
		return $topGamesQuery;
	}

	public static function querySelect()
	{
		//@todo call function for top_games
		$selectQuery = self::get_top_games(null, null, 'top', '40');
		return $selectQuery;
	}

	public static function getRows( $args )
	{
		$table = with(new static)->table;
		$key = with(new static)->primaryKey;

		extract( array_merge( array(
			'page' 		=> '0' ,
			'limit'  	=> '0' ,
			'sort' 		=> 'Average' ,
			'order' 	=> 'desc' ,
			'params' 	=> '' ,
			'global'	=> 1
		), $args ));
		//hardcoded sorting
		$sort = "Average";
		$order = "Desc";

		$offset = ($page-1) * $limit ;
		$limitConditional = ($page !=0 && $limit !=0) ? "LIMIT  $offset , $limit" : '';
		$orderConditional = ($sort !='' && $order !='') ?  " ORDER BY {$sort} {$order} " : '';

		// Update permission global / own access new ver 1.1
		$table = with(new static)->table;
		if($global == 0 )
			$params .= " AND {$table}.entry_by ='".\Session::get('uid')."'";
		// End Update permission global / own access new ver 1.1

		$rows = array();
		$selectQuery = self::querySelect() . self::queryWhere()." {$orderConditional}  {$limitConditional} ";

		$result = \DB::select($selectQuery);
		$cleanResult = array();
		foreach($result as $data)
		{
			if(!empty($data->id))
			{
				$cleanResult[] = $data;
			}
		}
		$result = $cleanResult;

		if($key =='' ) { $key ='*'; } else { $key = $table.".".$key ; }
		$counter_select = preg_replace( '/[\s]*SELECT(.*)FROM/Usi', 'SELECT count('.$key.') as total FROM', self::querySelect() );

		//total query becomes too huge
		$total = \DB::select( self::querySelect() . self::queryWhere() ." {$orderConditional}  ");
		$total = count($total);
		return $results = array('rows'=> $result , 'total' => $total);


	}



	public static function queryWhere(  ){
		
		return "  ";
	}
	
	public static function queryGroup(){
		return "  ";
	}


}
