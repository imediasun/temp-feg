<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Location;
use App\Library\MyLog;

class game extends Sximo  {
	
	protected $table = 'game';
	protected $primaryKey = 'id';
    public $timestamps = false;

	public function __construct() {
		parent::__construct();
		
	}
	public function gameTitle()
	{
		return $this->hasOne('App\Models\Gamestitle','id','game_title_id');
	}

	public static function querySelect(  ){
		
		return "  SELECT game.* FROM game  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE game.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

    /**
     * @param array $params
     * @return null Or Collection
     */
    public static function reportingLocationGameReaders($params = array())
    {
        extract(array_merge(array(
            'date_start' => date("Y-m-d", strtotime("-1 day")),
            'date_end' => date("Y-m-d")
        ), $params));
        $logger = new MyLog('reporting-locations-game-readers.log', 'FEGCronTasks/reporting-locations-game-readers', 'ReportingLocationsGameReaders');
        $reportingLocations = Location::reportingLocations();
        if ($reportingLocations) {
            $logger->log("start finding game readers");
            $result = \DB::table("game_earnings")
                ->select("game_id", "loc_id as location_id", "reader_id",'date_start')
                ->whereIn("loc_id", explode(",", $reportingLocations))->where(\DB::raw("date(date_start)"), '=', $date_start)
                ->groupby("reader_id")->groupby("loc_id")->groupby("game_id")->get();
            if ($result) {
                $logger->log("Game Readers: ", $result);
            }
            $logger->log("end finding game readers");
            return $result;
        }
        return null;
    }
	

}
