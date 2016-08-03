<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class gameservicehistory extends Sximo
{

    protected $table = 'game_service_history';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }

    public static function querySelect()
    {

        return "  SELECT id,game_id,problem,down_user_id,solution,up_user_id,date_up,
                  CONCAT(IF(date_down IS NULL,'',date_down),'<br/>',IF(date_up IS NULL,'',date_up)) AS date_down,
                  DATEDIFF(date_up,date_down) as days_down
                  FROM game_service_history  ";
    }

    public static function queryWhere()
    {

        return "  WHERE game_service_history.id IS NOT NULL ";
    }

    public static function queryGroup()
    {
        return "  ";
    }

    public static function getSearchFilters()
    {
        $finalFilter = array();
        if (isset($_GET['search'])) {
            $filters_raw = trim($_GET['search'], "|");
            $filters = explode("|", $filters_raw);

            foreach ($filters as $filter) {
                $columnFilter = explode(":", $filter);
                if (isset($columnFilter) && isset($columnFilter[0]) && isset($columnFilter[2])) {
                    $finalFilter[$columnFilter[0]] = $columnFilter[2];
                }
            }
        }
        return $finalFilter;
    }

    function getGameNames()
    {
        $row = \DB::select('select id,game_name from game');
        return $row;
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

}