<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Request, Log;
class throwreportinstantwin extends Sximo  {
	
	protected $table = 'merch_throws';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){

		return "SELECT merch_throws.*, game.game_name FROM  merch_throws
                       join game on merch_throws.game_id = game.id";
	}	

	public static function queryWhere(  ){
		$location= \Session::get('selected_location');
		$previous_week = strtotime("-1 week +1 day");
		$start_week = strtotime("last sunday midnight",$previous_week);
		$end_week = strtotime("next saturday",$start_week);
		$filters = self::getSearchFilters();
		$dateStart = @$filters['date_start'];
		$dateEnd = @$filters['date_end'];
		$dateStart_expression = "";
		if(!empty($dateStart))
		{
			$dateStart = date("Y-m-d", strtotime($dateStart));
			$dateStart_expression = " AND merch_throws.date_start  >= '$dateStart'";
		}
		else
		{
			$dateStart = self::getStartDayOfWeek();
			$dateStart = date("Y-m-d", strtotime($dateStart));
			$dateStart_expression = " AND merch_throws.date_start  >= '$dateStart'";

		}
		$dateEnd_expression = "";
		if(!empty($dateEnd))
		{
			$dateEnd = date("Y-m-d", strtotime($dateEnd));
			$dateEnd_expression = " AND merch_throws.date_end  <= '$dateEnd'";
		}
		else
		{
			$dateEnd = self::getEndDayOfWeek();
			$dateEnd = date("Y-m-d", strtotime($dateEnd));
			$dateEnd_expression = " AND merch_throws.date_end  <= '$dateEnd'";
		}

		$where = " WHERE merch_throws.location_id =$location $dateStart_expression $dateEnd_expression ";
		return $where;

	}
	
	public static function queryGroup(){
		return "  ";
	}


	public static function getComboselect($params, $limit = null, $parent = null) {
		$tableName = $params[0];
		if($tableName == 'location'){
			return parent::getUserAssignedLocation($params,$limit,$parent);
		}
		else{
			return parent::getComboselect($params,$limit,$parent);
		}
	}


	public static function getStartDayOfWeek()
	{
		$previous_week = strtotime("-1 week +1 day");
		$start_week = strtotime("last sunday midnight",$previous_week);
		$dateStart = date("m/d/Y",  $start_week);
		return $dateStart;
	}

	public static function getEndDayOfWeek()
	{
		$previous_week = strtotime("-1 week +1 day");
		$start_week = strtotime("last sunday midnight",$previous_week);
		$end_week = strtotime("next saturday",$start_week);
		$dateEnd =  date("m/d/Y",$end_week);
		return $dateEnd;
	}


}
