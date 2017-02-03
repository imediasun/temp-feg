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
        //CONCAT(IF(date_down IS NULL,'',date_down),'<br/>',IF(date_up IS NULL,'',date_up)) AS
        return "  SELECT game_service_history.id,game_service_history.game_id,game.game_name,game_service_history.problem,game_service_history.down_user_id,game_service_history.solution,game_service_history.up_user_id,game_service_history.date_up,
                   game_service_history.date_down,
                  DATEDIFF(game_service_history.date_up,game_service_history.date_down) as days_down
                  FROM game_service_history left outer join game on game_service_history.game_id=game.id ";
    }

    public static function queryWhere()
    {

        return "  WHERE game_service_history.id IS NOT NULL ";
    }

    public static function queryGroup()
    {
        return "  ";
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