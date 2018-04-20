<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class merchandisebudget extends Sximo
{

    protected $table = 'location_budget';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }

    public static function querySelect()
    {
        return "SELECT CONCAT(location.id,' | ',location.location_name) as location,location.location_name,location_budget.location_id,location.location_name_short as location_name_short,location_budget.id,YEAR(location_budget.budget_date) As budget_year,
 SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Jan' THEN location_budget.budget_value ELSE 0 END) Jan
 , SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Feb' THEN location_budget.budget_value ELSE 0 END) Feb
 , SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Mar' THEN location_budget.budget_value ELSE 0 END) March ,
  SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Apr' THEN location_budget.budget_value ELSE 0 END) April ,
  SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='May' THEN location_budget.budget_value ELSE 0 END) May ,
  SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Jun' THEN location_budget.budget_value ELSE 0 END) June ,
  SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Jul' THEN location_budget.budget_value ELSE 0 END) July,
  SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Aug' THEN location_budget.budget_value ELSE 0 END) August,
  SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Sep' THEN location_budget.budget_value ELSE 0 END) September,
  SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Oct' THEN location_budget.budget_value ELSE 0 END) October,
  SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Nov' THEN location_budget.budget_value ELSE 0 END) November,
  SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Dec' THEN location_budget.budget_value ELSE 0 END) December
  FROM location,location_budget ";
    }
    public static function querySelectVal()
    {
        
        return "SELECT 
            CONCAT(location.id,' | ',location.location_name) as location,location.location_name,
            location_budget.location_id,
            location_budget.id,
            YEAR(location_budget.budget_date) As budget_year,

            SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Jan' THEN location_budget.budget_value ELSE 0.00 END) Jan,
            SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Feb' THEN location_budget.budget_value ELSE 0.00 END) Feb,
            SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Mar' THEN location_budget.budget_value ELSE 0.00 END) March ,
            SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Apr' THEN location_budget.budget_value ELSE 0.00 END) April ,
            SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='May' THEN location_budget.budget_value ELSE 0.00 END) May ,
            SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Jun' THEN location_budget.budget_value ELSE 0.00 END) June ,
            SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Jul' THEN location_budget.budget_value ELSE 0.00 END) July,
            SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Aug' THEN location_budget.budget_value ELSE 0.00 END) August,
            SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Sep' THEN location_budget.budget_value ELSE 0.00 END) September,
            SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Oct' THEN location_budget.budget_value ELSE 0.00 END) October,
            SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Nov' THEN location_budget.budget_value ELSE 0.00 END) November,
            SUM(CASE WHEN DATE_FORMAT(location_budget.budget_date,'%b')='Dec' THEN location_budget.budget_value ELSE 0.00 END) December
  
        FROM location,location_budget ";
    }

    public static function queryWhere($current_year = 0 , $advanceSearch)
    {

        if (!$current_year) {
            $year=\Session::get('budget_year');
            if(isset($year))
            {
                $current_year=$year;
            } else {
                $current_year = date('Y');
            }
            if($advanceSearch == true)
            {
                $current_year = null;
            }
            }
        $selectedLocations = \SiteHelpers::getCurrentUserLocationsFromSession();
        if($current_year==null)
        {
            $yearWhere = '';
        }
        else{
            $yearWhere = ' AND YEAR(location_budget.budget_date)='.$current_year;
        }

        return " WHERE location.id=location_budget.location_id$yearWhere AND location_budget.id IS NOT NULL AND location.id IN ($selectedLocations)";
    }

    public static function queryGroup($advanceSearch)
    {
        $advanceGroupBy = '';
        if($advanceSearch==true)
        {
            $advanceGroupBy =',YEAR(location_budget.budget_date)';
        }
        return " GROUP BY location_budget.location_id ".$advanceGroupBy;
    }

    public function insertRow($data, $id = null, $location_id = null, $budget_year = null)
    {

        $table = with(new static)->table;
        $key = with(new static)->primaryKey;
        $vals = array();
        foreach ($data as $budget) {
            $budget['budget_value'] = str_replace('$','',$budget['budget_value']);
            $vals[] = $budget;
        }


        if ($id == NULL) {

            if (isset($data['createdOn'])) $data['createdOn'] = date("Y-m-d H:i:s");
            if (isset($data['updatedOn'])) $data['updatedOn'] = date("Y-m-d H:i:s");
            \DB::delete("DELETE FROM location_budget where location_id=$location_id and YEAR(budget_date)=$budget_year");
           \DB::table($table)->insert($vals);
            } else {
            // Update here
            // update created field if any
            if (isset($data['createdOn'])) unset($data['createdOn']);
            if (isset($data['updatedOn'])) $data['updatedOn'] = date("Y-m-d H:i:s");
            \DB::delete("DELETE FROM location_budget where location_id=$location_id and YEAR(budget_date)=$budget_year");
            \DB::table($table)->insert($vals);
        }
       // return $id;
    }

    public static function getRow($id=0, $isFormatted = true)
    {
        $table = with(new static)->table;
        $key = with(new static)->primaryKey;
        $res=\DB::select("select location_id,YEAR(budget_date) as year from $table where id=".$id);
        $year=$location_id=0;
        if($res) {
            $year = $res[0]->year;
            $location_id = $res[0]->location_id;
        }
        $result = \DB::select(
            ($isFormatted ? self::querySelect() : self::querySelectVal()) .
            self::queryWhere($year) .
            " AND " . $table . ".location_id  = $location_id" .
            self::queryGroup()
        );
        if (count($result) <= 0) {
            $result = array();
        } else {

            $result = $result[0];
        }
        return $result;
    }


}
