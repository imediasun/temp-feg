<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class tablecols extends Sximo  {
	
	protected $table = 'user_module_config';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT user_module_config.* FROM user_module_config  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE user_module_config.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
