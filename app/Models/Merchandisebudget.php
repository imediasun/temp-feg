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
		
		return "SELECT CONCAT(location.id,' | ',location.location_name) as location,location_budget.location_id,location_budget.id ,
 SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Jan' THEN location_budget.budget_value ELSE 0 END) Jan
 , SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Feb' THEN location_budget.budget_value ELSE 0 END) Feb
 , SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Mar' THEN location_budget.budget_value ELSE 0 END) March ,
 SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Apr' THEN location_budget.budget_value ELSE 0 END) April ,
 SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='May' THEN location_budget.budget_value ELSE 0 END) May ,
 SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Jun' THEN location_budget.budget_value ELSE 0 END) June ,
  SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Jul' THEN location_budget.budget_value ELSE 0 END) July,
  SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Aug' THEN location_budget.budget_value ELSE 0 END) August,
  SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Sep' THEN location_budget.budget_value ELSE 0 END) September,
  SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Oct' THEN location_budget.budget_value ELSE 0 END) Octuber,
  SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Nov' THEN location_budget.budget_value ELSE 0 END) November,
  SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Dec' THEN location_budget.budget_value ELSE 0 END) December
  FROM location,location_budget ";
	}	

	public static function queryWhere($current_year=null){
		if(!$current_year)
        {
            $current_year=date('Y');
        }
		return " WHERE location.id=location_budget.location_id AND YEAR(location_budget.budget_date)=$current_year AND location_budget.id IS NOT NULL";
	}
	
	public static function queryGroup(){
		return " GROUP BY location_budget.location_id ";
	}
    public  function insertRow( $data , $id)
    {
        $table = with(new static)->table;
        $key = with(new static)->primaryKey;
        $vals=array();
        foreach($data as $budget)
        {
         $vals[]=$budget;
        }

        if($id == NULL )
        {
            if(isset($data['createdOn'])) $data['createdOn'] = date("Y-m-d H:i:s");
            if(isset($data['updatedOn'])) $data['updatedOn'] = date("Y-m-d H:i:s");
            \DB::table( $table)->insert($vals);
        } else {
            // Update here
            // update created field if any

            if(isset($data['createdOn'])) unset($data['createdOn']);
            if(isset($data['updatedOn'])) $data['updatedOn'] = date("Y-m-d H:i:s");
            \DB::table($table)->where($key,$id)->update($data);

        }
        return $id;
    }


}
