<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Request, Log;
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

	public static function queryWhere($start_date = '', $end_date = ''){

        $location= \Session::get('selected_location');
        if(!empty($start_date) && !empty($end_date))
            return "WHERE   game_earnings.loc_id =$location and  date(game_earnings.date_start) BETWEEN  '$start_date' and '$end_date'";

        return "WHERE   game_earnings.loc_id =$location ";



        //@todo get get location id from session
        //@todo get week range date from post or get parameters
		/*
		 * actual query is not giving any result
		return "  WHERE location_id = 2012 AND game_type_id = 3
                  AND DATE(date_start) >= CURRENT_DATE AND DATE(date_end)<= CURRENT_DATE+INTERVAL 7 DAY ";
		*/
		//temp query to get results
      /*  return "  WHERE location_id = 2012 AND game_type_id = 3
                  AND DATE(date_start) >= '2015-09-06' AND DATE(date_end)<= '2015-09-12' ";  */
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

    public static function getComboselect($params, $limit = null, $parent = null) {
        $tableName = $params[0];
        if($tableName == 'location'){
            return parent::getUserAssignedLocation($params,$limit,$parent);
        }
        else{
            return parent::getComboselect($params,$limit,$parent);
        }
    }


    public static function getRows($args, $cond = null, $start_date = '', $end_date = '') {
        $table = with(new static)->table;
        $key = with(new static)->primaryKey;

        extract(array_merge(array(
            'page' => '0',
            'limit' => '0',
            'sort' => '',
            'order' => '',
            'params' => '',
            'global' => 1
        ), $args));


        $orderConditional = ($sort != '' && $order != '') ? " ORDER BY {$sort} {$order} " : '';

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
            $select .= self::queryWhere($cond);
        }
        else {
            if($start_date != '' && $end_date != '')
                $select .= self::queryWhere($start_date, $end_date);
            else
                $select .= self::queryWhere();
        }

        if(!empty($createdFrom)){
            $select .= " AND created_at BETWEEN '$createdFrom' AND '$createdTo'";
            $createdFlag = true;
        }

        if(!empty($updatedFrom)){

            if($createdFlag){
                $select .= " OR updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
            }
            else{
                $select .= " AND updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
            }

        }

        if(!empty($order_type_id)){
            $select .= " AND order_type_id='$order_type_id'";
        }
        if(!empty($status_id)){
            $select .= " AND status_id='$status_id'";
        }

        Log::info("Total Query : ".$select . " {$params} " . self::queryGroup() . " {$orderConditional}");
        $counter_select =\DB::select($select . " {$params} " . self::queryGroup() . " {$orderConditional}");
        $total= count($counter_select);
        if($table=="img_uploads")
        {
            $total="";
        }

        $offset = ($page - 1) * $limit;
        if ($offset >= $total) {
            $page = ceil($total/$limit);
            $offset = ($page-1) * $limit ;
        }
        $limitConditional = ($page != 0 && $limit != 0) ? "LIMIT  $offset , $limit" : '';

        Log::info("Query : ".$select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ");
        $result = \DB::select($select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ");

        if ($key == '') {
            $key = '*';
        } else {
            $key = $table . "." . $key;
        }

        return $results = array('rows' => $result, 'total' => $total);
    }
}
