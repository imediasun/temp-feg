<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class gamesdown extends Sximo  {
	
	protected $table = 'game';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  )
	{
		$startDate = "2015-12-01";
		$endDate = "2015-12-30";
		self::getGamesNotReporting($startDate, $endDate);

		return "  SELECT game.* FROM game  ";
	}

	public static function getEarningGamesQuery($startDate, $endDate)
	{
		return 'SELECT E.game_id, E.reader_id, CONCAT(L.id, " - ", L.location_name_short) AS Location
									     FROM game_earnings E
									LEFT JOIN location L ON L.id = E.loc_id
									    WHERE E.date_start BETWEEN "'.$startDate.'" AND "'.$endDate.'"
									 GROUP BY (CASE WHEN E.game_id = 0 THEN E.reader_id ELSE E.game_id END)
									   HAVING SUM(E.std_plays) > 0
									 ORDER BY E.loc_id';
	}

	public static function getEarningGamesData($startDate, $endDate)
	{
		$results = \DB::Select(self::getEarningGamesQuery($startDate, $endDate));
		$readerIssueNumber = 0;
		$gamesInEarning = array();
		foreach($results as $row)
		{
			if(($row->game_id == 0 || empty($row->game_id)) && substr($row->reader_id, -3) !== "__0" )
			{
				$readerIssueNumber++;
			}
			// ELSE CREATE A LIST OF ALL THE GAMES THAT REPORTED PROPERLY
			else
			{

				$gamesInEarning[] = $row;
			}
		}

		return $gamesInEarning;
	}

	public static function getGamesNotReporting($startDate, $endDate)
	{
		$locationsNotResponding = Reports::getLocationNotRespondingData($startDate, $endDate);
		$earningGames = self::getEarningGamesData($startDate, $endDate);

		$gamesId = array();
		$locationId = array();
		$gamesInEarningsString = '';
		$locationNotRespondingString = '';
		$gamesDown = array();

		if(!empty($earningGames))
		{
			foreach($earningGames as $earningGame)
			{
				$gamesId[] = $earningGame->game_id;
			}
			$gamesInEarningsString = implode(',', $gamesId);
		}

		if(!empty($locationsNotResponding))
		{
			foreach($locationsNotResponding as $location)
			{
				$locationId[] = $location->id;
			}
			$locationNotRespondingString = 'AND L.id NOT IN(';
			$locationNotRespondingString .= implode(',', $locationId);
			$locationNotRespondingString .= ')';
		}

		$locationsThatReportedQuery = \DB::select('SELECT L.id
										   			FROM location L
										  		   WHERE L.reporting = 1
												 	   '.$locationNotRespondingString);

		foreach ($locationsThatReportedQuery as $row) {
			echo "inside loop";
			$locationMessage = '';
			$locationGamesNotReportingList = '';
			$locationId = $row->id;
			//$locationsThatReportedIdString = $locationsThatReportedIdString . $row->id . ',';

			/////////////////////////////////////////////////////////////////
			// LOCATIONS WHERE SOME GAMES ARE NOT REPORTING THAT SHOULD BE //
			/////////////////////////////////////////////////////////////////
			$locationGamesNotReportingQuery = \DB::select('SELECT CONCAT(G.id, " | ", IF(G.test_piece = 1,CONCAT("**TEST** ",T.game_title),T.game_title)) AS Game,
																 CONCAT(L.id, " - ", L.location_name_short) AS Location,
																 DATEDIFF("' . $startDate . '",(SELECT E.date_start
																							   FROM game_earnings E
																							  WHERE E.game_id = G.id
																						   ORDER BY E.date_start DESC
																							  LIMIT 1)) AS LastPlayed
															FROM game G
													   LEFT JOIN game_title T ON T.id = G.game_title_id
													   LEFT JOIN location L ON L.id = G.location_id
													   LEFT JOIN game_type Y ON Y.id = T.game_type_id
														   WHERE L.id = ' . $locationId . '
															 AND G.id NOT IN(' . $gamesInEarningsString . ')
															 AND G.not_debit = 0
															 AND G.sold = 0
															 AND Y.id NOT IN(6,9,10)
														ORDER BY LastPlayed DESC');

			foreach ($locationGamesNotReportingQuery as $row2)
			{
				$gamesDown[] = $row2;
			}
		}

		print_r($gamesDown);
		exit;

	}

	public static function queryWhere(  ){
		
		return "  WHERE game.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
