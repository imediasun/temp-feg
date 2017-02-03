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
        return " SELECT * FROM 
            (SELECT 
                gsh.id,
                gsh.game_id,
                g.game_title_id,
                gt.game_title,
                gsh.location_id,                    
                l.location_name,
                gsh.problem,
                gsh.down_user_id,
                gsh.date_down,
                gsh.solution,
                gsh.up_user_id,
                gsh.date_up,
                DATEDIFF(IF(gsh.date_up IS NULL, CURRENT_DATE(), gsh.date_up),gsh.date_down) AS days_down
            FROM game_service_history gsh
            LEFT JOIN location l ON l.id = gsh.location_id
            LEFT JOIN game g ON gsh.game_id=g.id
            LEFT JOIN game_title gt ON gt.id = g.game_title_id) AS game_service_history ";
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
