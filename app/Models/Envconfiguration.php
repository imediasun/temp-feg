<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Envconfiguration extends Sximo  {
	
	protected $table = 'env_settings';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT env_settings.* FROM env_settings  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE env_settings.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

}
