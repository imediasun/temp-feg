<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class freightquoters extends Sximo  {
	
	protected $table = 'freight_companies';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT freight_companies.* FROM freight_companies  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE freight_companies.id IS NOT NULL ";
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
