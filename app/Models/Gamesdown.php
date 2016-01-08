<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class gamesdown extends Sximo  {
	
	protected $table = 'game';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){

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
		$earningGames = self::getEarningGamesQuery($startDate, $endDate);

		print_r($locationsNotResponding);
		print_r($earningGames);
		exit;

	}

	public static function queryWhere(  ){
		
		return "  WHERE game.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
