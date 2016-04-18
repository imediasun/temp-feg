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
        return "SELECT CONCAT(location.id,' | ',location.location_name) as location,location_budget.location_id,location_budget.id,YEAR(location_budget.budget_date) As budget_year,
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

    public static function queryWhere($current_year = null)
    {
        if (!$current_year) {
            $year=\Session::get('budget_year');
            if(isset($year))
            {
                $current_year=$year;
            }
            else {
                $current_year = date('Y');
            }
            }
        return " WHERE location.id=location_budget.location_id AND YEAR(location_budget.budget_date)=$current_year AND location_budget.id IS NOT NULL";
    }

    public static function queryGroup()
    {
        return " GROUP BY location_budget.location_id ";
    }

    public function insertRow($data, $id, $location_id = null, $budget_year = null)
    {

        $table = with(new static)->table;
        $key = with(new static)->primaryKey;
        $vals = array();
        foreach ($data as $budget) {
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

    public static function getRow($id=0,$cond=null)
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
            self::querySelect() .
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
