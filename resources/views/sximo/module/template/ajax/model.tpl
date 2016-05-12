<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class {class} extends Sximo  {
	
	protected $table = '{table}';
	protected $primaryKey = '{key}';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return " {sql_select} ";
	}	

	public static function queryWhere(  ){
		
		return " {sql_where} ";
	}
	
	public static function queryGroup(){
		return " {sql_group} ";
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
