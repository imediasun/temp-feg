<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class gamestitle extends Sximo  {
	
	protected $table = 'game_title';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){

        return "  SELECT
  game_title.*,
  vendor.vendor_name,
  game_type.game_type_short
FROM game_title
LEFT JOIN vendor ON vendor.id = game_title.mfg_id
LEFT JOIN game_type ON game_type.id = game_title.game_type_id ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE game_title.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
    public static function getRow( $id )
    {
        $row= \DB::table('game_title')
            ->leftJoin('vendor', 'game_title.mfg_id', '=', 'vendor.id')
            ->select('game_title.*','vendor.vendor_name')
            ->where('game_title.id','=',$id)
            ->get();
        return $row;
    }
	

}
