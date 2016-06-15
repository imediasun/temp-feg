<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class course extends Sximo  {
	
	protected $table = 'courses';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT courses.*,DATE_FORMAT(courses.start_date, '%d-%m-%Y') as start_date,
                  DATE_FORMAT(courses.end_date, '%d-%m-%Y') as end_date,
                  DATE_FORMAT(courses.advertisement_start_date, '%d-%m-%Y') as advertisement_start_date,
                  DATE_FORMAT(courses.advertisement_end_date, '%d-%m-%Y') as advertisement_end_date FROM courses  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE courses.id IS NOT NULL ";
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
