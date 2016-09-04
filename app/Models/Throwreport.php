<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class throwreport extends Sximo  {
	
	protected $table = 'game';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "SELECT game_earnings.date_start,game_earnings.date_end,SUM(std_actual_cash) AS revenue_total,products.item_description,products.retail_price,game.*
                FROM game 
                JOIN products ON game.product_id = products.id
                JOIN game_earnings ON game_earnings.game_id = game.id ";
	}	

	public static function queryWhere(  ){
	    //@todo get get location id from session
        //@todo get week range date from post or get parameters
		/*
		 * actual query is not giving any result
		return "  WHERE location_id = 2012 AND game_type_id = 3
                  AND DATE(date_start) >= CURRENT_DATE AND DATE(date_end)<= CURRENT_DATE+INTERVAL 7 DAY ";
		*/
		//temp query to get results
        return "  WHERE location_id = 2012 AND game_type_id = 3
                  AND DATE(date_start) >= '2015-09-06' AND DATE(date_end)<= '2015-09-12' ";
	}
	
	public static function queryGroup(){
		return " GROUP BY game.id ";
	}
	
    public static function getSearchFilters() {
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
}
