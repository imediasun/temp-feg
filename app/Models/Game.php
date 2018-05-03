<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class game extends Sximo  {
	
	protected $table = 'game';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}
	public function gameTitle()
	{
		return $this->hasOne('App\Models\Gamestitle','id','game_title_id');
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
	

}
