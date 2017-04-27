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
		
		return "  SELECT game_title.* FROM game_title  ";
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
