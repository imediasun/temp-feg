<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class bottomgamesreport extends Sximo  {
	
	protected $table = 'game_earnings';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return " SELECT id,
	'' AS game_name,
	'' AS game_type,
	0 AS game_average,
	0 AS game_total,
	0 AS games_count,
	date_start AS start_date,
	date_end AS end_date,
	loc_id as location_id,
	loc_id as location_name
	   FROM game_earnings ";
	}	

	public static function queryWhere(  ){
		
		return " WHERE game_earnings.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
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
