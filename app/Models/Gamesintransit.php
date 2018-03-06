<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class gamesintransit extends Sximo
{

    protected $table = 'game';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }

    public static function querySelect()
    {

        return "SELECT game.*, IF(game.test_piece = 1, CONCAT('**TEST** ',game_title.game_title),game_title.game_title) AS game_name, game.game_name          AS gname, IF(game.location_id = 0, '<em style=\"color:red\">IN TRANSIT</em>', L1.location_name_short), CONCAT(L1.id,' | ',L1.location_name_short) AS location, IF(game.intended_first_location IS NULL OR game.intended_first_location=0, '<em style=\"color:red\">Check Notes to Confirm</em>', CONCAT(L3.id, ' | ',L3.location_name_short)) AS intended_first_location,
  game_title.img,
  IF(game_title.game_title LIKE '%*TEST*%','', CONCAT('../qr/',game.id,'.png')),
  IF(L2.id IS NULL, 'NEW GAME', CONCAT(L2.id,' | ',L2.location_name_short)),
  game_status.game_status,
  IF(Y.game_type IS NULL, '', Y.game_type),
  IF(V.vendor_name IS NULL, '', V.vendor_name),
  game_title.game_type_id,
  game_title.img
FROM game
  LEFT JOIN location L1
    ON (game.location_id = L1.id)
  LEFT JOIN location L2
    ON (game.prev_location_id = L2.id)
  LEFT JOIN location L3
    ON (game.intended_first_location = L3.id)
  LEFT JOIN region
    ON (L1.region_id = region.id)
  LEFT JOIN game_status
    ON (game.status_id = game_status.id)
  LEFT JOIN game_title
    ON (game.game_title_id = game_title.id)
  LEFT JOIN game_type Y
    ON (game_title.game_type_id = Y.id)
  LEFT JOIN vendor V
    ON (game_title.mfg_id = V.id)
  LEFT JOIN game_version 
   ON game_version.id = game.version_id ";
    }

    public static function queryWhere()
    {
        $where="";
//        $user_level=\Session::get('gid');
//        $user_locations= $user_locations = \SiteHelpers::getLocationDetails(\Session::get('uid'));
//        $user_locations= json_decode(json_encode($user_locations),true);
//        $locations=array();
//        foreach($user_locations as $location)
//        {
//            $locations[]=$location['id'];
//        }
//        $user_locations=implode(',',$locations);
//        if( $user_level == 1 || $user_level == 2 || $user_level == 6 || $user_level == 8 ||  $user_level == 11) {
//            $where .= "  WHERE( intended_first_location IN(".$user_locations.") OR
//                         intended_first_location IS NULL OR
//                         prev_location_id IN(".$user_locations.") OR
//                         location_id IN(".$user_locations.")) AND status_id=3 AND sold = 0";
//        }
//        else
//        {
            $where .= " WHERE status_id=3 AND sold = 0";
//        }
        return $where;

    }

    public static function queryGroup()
    {
        return "  ";
    }


}
