<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Request, Log;
class throwreport extends Sximo  {

    protected $table = 'merch_throws';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){

        return "SELECT merch_throws.*, game.game_name, products.item_description FROM  merch_throws
                       join game on merch_throws.game_id = game.id
                       join products on merch_throws.product_id_1 = products.id ";
    }

    public static function queryWhere(){

        $location= \Session::get('selected_location');
        $previous_week = strtotime("-1 week +1 day");
        $start_week = strtotime("last sunday midnight",$previous_week);
        $end_week = strtotime("next saturday",$start_week);
        $filters = self::getSearchFilters();
        $dateStart = @$filters['date_start'];
        $dateEnd = @$filters['date_end'];
        $dateStart_expression = "";
        if(!empty($dateStart))
        {
            $dateStart = date("Y-m-d", strtotime($dateStart));
            $dateStart_expression = "  AND merch_throws.date_start  >= '$dateStart'";
        }
        else
        {
            $dateStart = self::getStartDayOfWeek();
            $dateStart = date("Y-m-d", strtotime($dateStart));
            $dateStart_expression = "  AND merch_throws.date_start  >= '$dateStart'";

        }
        $dateEnd_expression = "";
        if(!empty($dateEnd))
        {
            $dateEnd = date("Y-m-d", strtotime($dateEnd));
            $dateEnd_expression = "  AND merch_throws.date_end  <= '$dateEnd'";
        }
        else
        {
            $dateEnd = self::getEndDayOfWeek();
            $dateEnd = date("Y-m-d", strtotime($dateEnd));
            $dateEnd_expression = "  AND merch_throws.date_end  <= '$dateEnd'";
        }

            $where = " WHERE   merch_throws.location_id =$location and flag = 0 $dateStart_expression $dateEnd_expression ";
        return $where;



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


    public static function getStartDayOfWeek()
    {
        $previous_week = strtotime("-1 week +1 day");
        $start_week = strtotime("last sunday midnight",$previous_week);
        $dateStart = date("m/d/Y",  $start_week);
        return $dateStart;
    }

    public static function getEndDayOfWeek()
    {
        $previous_week = strtotime("-1 week +1 day");
        $start_week = strtotime("last sunday midnight",$previous_week);
        $end_week = strtotime("next saturday",$start_week);
        $dateEnd =  date("m/d/Y",$end_week);
        return $dateEnd;
    }
}
