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

        return "SELECT game.*,IF(game.test_piece = 1, CONCAT('**TEST** ',T.game_title),T.game_title) as game_name,game.game_name as gname,
               IF(game.location_id = 0, '<em style=\"color:red\">IN TRANSIT</em>', L1.location_name_short), CONCAT(L1.id,' | ',L1.location_name_short) as location,
               IF(game.intended_first_location IS NULL, '<em style=\"color:red\">Check Notes to Confirm</em>', CONCAT(L3.id, ' | ',L3.location_name_short)) as intended_first_location,T.img,
               IF(T.game_title LIKE '%*TEST*%','', CONCAT('../qr/',game.id,'.png')),If(L2.id IS NULL, 'NEW GAME' , CONCAT(L2.id,' | ',L2.location_name_short)) ,
               game_status.game_status,If(Y.game_type IS NULL, '' , Y.game_type) ,If(V.vendor_name IS NULL, '' , V.vendor_name),T.game_type_id,T.img
               From game
               LEFT JOIN location L1 ON (game.location_id = L1.id)
			   LEFT JOIN location L2 ON (game.prev_location_id = L2.id)
			   LEFT JOIN location L3 ON (game.intended_first_location = L3.id)
			   LEFT JOIN region ON (L1.region_id = region.id)
			   LEFT JOIN game_status ON (game.status_id = game_status.id)
			   LEFT JOIN game_title T ON (game.game_title_id = T.id)
			   LEFT JOIN game_type Y ON (T.game_type_id = Y.id)
			   LEFT JOIN vendor V ON (T.mfg_id = V.id)";
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
