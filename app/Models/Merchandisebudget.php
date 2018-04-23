<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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

    public static function getRows($args, $cond = null , $advanceSearch = false) {
        $table = with(new static)->table;
        $key = with(new static)->primaryKey;
        extract(array_merge(array(
            'page' => '0',
            'limit' => '0',
            'sort' => '',
            'extraSorts' => [],
            'order' => '',
            'params' => '',
            'global' => 1
        ), $args));


        $orderConditional = ($sort != '' && $order != '') ? " ORDER BY {$sort} {$order} " : '';
        if (!empty($extraSorts)) {
            if (empty($orderConditional)) {
                $orderConditional = " ORDER BY ";
            }
            else {
                $orderConditional .= ", ";
            }
            $extraOrderConditionals = [];
            foreach($extraSorts as $extraSortItem) {
                $extraSortItem[0] = '`'.$extraSortItem[0].'`';
                $extraOrderConditionals[] = implode(' ', $extraSortItem);
            }
            $orderConditional .= implode(', ', $extraOrderConditionals);
        }

        // Update permission global / own access new ver 1.1
        $table = with(new static)->table;
        if ($global == 0)
            $params .= " AND {$table}.entry_by ='" . \Session::get('uid') . "'";
        // End Update permission global / own access new ver 1.1

        $rows = array();
        $select = self::querySelect();

        /*

        */
        $createdFlag = false;

        if ($cond != null) {
            $select .= self::queryWhere($cond , $advanceSearch);
        }
        else {
            $select .= self::queryWhere(null , $advanceSearch);
        }

        if(!empty($createdFrom)){
            if($cond != 'only_api_visible')
            {
                $select .= " AND created_at BETWEEN '$createdFrom' AND '$createdTo'";
            }
            else
            {
                $select .= " AND api_created_at BETWEEN '$createdFrom' AND '$createdTo'";
            }
            $createdFlag = true;
        }

        if(!empty($updatedFrom)){

            if($createdFlag){
                if($cond != 'only_api_visible')
                {
                    $select .= " OR updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
                else
                {
                    $select .= " OR api_updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
            }
            else{
                if($cond != 'only_api_visible')
                {
                    $select .= " AND updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
                else
                {
                    $select .= " AND api_updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
            }

        }

        if(!empty($order_type_id)){
            $select .= " AND order_type_id in($order_type_id)";
        }
        if(!empty($status_id)){
            $select .= " AND status_id='$status_id'";
        }
        if(!empty($active)){//added for location
            $select .= " AND location.active='$active'";
        }

        Log::info("Total Query : ---------------".$select . " {$params} " . self::queryGroup($advanceSearch) . " {$orderConditional}");
        $counter_select =\DB::select($select . " {$params} " . self::queryGroup($advanceSearch) . " {$orderConditional}");
        $total= count($counter_select);
        if($table=="img_uploads")
        {
            $total="";
        }

        $offset = ($page - 1) * $limit;
        if ($offset >= $total && $total != 0 && $limit != 0) {
            $page = ceil($total/$limit);
            $offset = ($page-1) * $limit ;
        }

        $limitConditional = ($page != 0 && $limit != 0) ? "LIMIT  $offset , $limit" : '';
        // echo $select . " {$params} " . self::queryGroup($advanceSearch) . " {$orderConditional}  {$limitConditional} ";
        Log::info("Query : ".$select . " {$params} " . self::queryGroup($advanceSearch) . " {$orderConditional}  {$limitConditional} ");
        self::$getRowsQuery = $select . " {$params} " . self::queryGroup($advanceSearch) . " {$orderConditional}  {$limitConditional} ";
        $result = \DB::select($select . " {$params} " . self::queryGroup($advanceSearch) . " {$orderConditional}  {$limitConditional} ");

        if ($key == '') {
            $key = '*';
        } else {
            $key = $table . "." . $key;
        }
        return $results = array('rows' => $result, 'total' => $total);
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
