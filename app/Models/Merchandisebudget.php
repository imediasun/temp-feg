<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class merchandisebudget extends Sximo  {
	
	protected $table = 'location_budget';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "SELECT CONCAT(l.id,'|',l.location_name) AS  location,lb.*,YEAR (lb.budget_date) as budget_year FROM location l,location_budget lb";
	}	

	public static function queryWhere($current_year=null){
		if(!$current_year)
        {
            $current_year=date('Y');
        }
		return " WHERE l.id=lb.location_id AND YEAR(lb.budget_date)=$current_year AND lb.id IS NOT NULL";
	}
	
	public static function queryGroup(){
		return " GROUP BY budget_year,lb.location_id ";
	}
	

}
