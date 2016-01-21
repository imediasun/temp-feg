<?php namespace App\Models;

use Faker\Provider\cs_CZ\DateTime;
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
		//calculate last week date
		$dateInterval = new \DateInterval("P1W");
		$dateInterval->invert = 1;
		$dateObject = new \DateTime();
		$date_start = $dateObject->add($dateInterval)->format('Y-m-d');

		//$date_start = "DATE_SUB('$date_end', INTERVAL 1 WEEK)";
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
						$date_start = $columnFilter[2];
					}
					if ($columnFilter[0] === "date_end") {
						$date_end = $columnFilter[2];
					}
					if ($columnFilter[0] === "loc_id") {
						$locationCondition = " AND location.id = ".$columnFilter[2];
					}
					if ($columnFilter[0] === "average") {
						$havingAverage = " Having Average ".self::searchOperation($columnFilter[1])." ".$columnFilter[2];
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
		/*
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
		*/
		//echo "$date_start => $date_end";
		$topGamesQuery =
			'SELECT
				game_earnings.id,
				IF(SUM(std_actual_cash)=0.00,SUM(total_notional_value),SUM(std_actual_cash)) AS total_revenue,
				location_name,
				date_start,
				game_title_id,
				location_id,
				game_title.game_title AS Game
				FROM game_earnings
				JOIN game
				ON game.id = game_earnings.game_id
				JOIN game_title
				ON game.game_title_id = game_title.id
				JOIN location
				ON game.location_id = location.id
				WHERE DATE(date_start) BETWEEN "'.$date_start.'"
				AND "'.$date_end.'"
				AND game_id != 0
				GROUP BY game.game_title_id';
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
		$sort = "total_revenue";
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
				$countResult = \DB::select("select count(id) as game_count from game where location_id = {$data->location_id} and game_title_id = {$data->game_title_id}");
				$data->Total = $countResult[0]->game_count;

				$data->Average =  $data->total_revenue/$data->Total;
				$cleanResult[] = $data;
			}
		}
		usort($cleanResult, function($a, $b) {
			if ($a->Average == $b->Average) {
				return 0;
			}
			return ($a->Average > $b->Average) ? -1 : 1;
		}		);

		//sort result by average
		$result = $cleanResult;
		$total = count($result);
		return $results = array('rows'=> $result , 'total' => $total);


	}

	public static function cmp($a, $b)
	{
		return strcmp($a->Average, $b->Average);
	}



	public static function queryWhere(  ){
		
		return "  ";
	}
	
	public static function queryGroup(){
		return "  ";
	}


}
