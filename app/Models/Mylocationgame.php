<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class mylocationgame extends Sximo  {
	
	protected $table = 'game';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT game.* FROM game  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE game.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
    public static function getRow( $id )
    {
        $row= \DB::table('game')
            ->leftJoin('game_status', 'game.status_id', '=', 'game_status.id')
            ->leftJoin('game_type','game.game_type_id','=','game_type.id')
            ->leftJoin('game_title','game.game_title_id','=','game_title.id')
            ->leftJoin('game_version','game.version_id','=','game_version.id')
            ->leftJoin('location','game.location_id','=','location.id')
            ->leftJoin('vendor','game.mfg_id','=','vendor.id')
            ->leftJoin('users','game.last_edited_by','=','users.id')
            ->leftJoin('location as l2','game.prev_location_id','=','l2.id')
            ->select('game.*','game_status.game_status','game_title.*','game_type.game_type','game_version.version','location.location_name','vendor.*','game.id as asset_number','users.first_name','users.last_name','l2.location_name as previous_location')
            ->where('game.id','=',$id)
            ->get();
        return $row;
    }

}
