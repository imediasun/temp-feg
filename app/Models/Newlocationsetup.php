<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class newlocationsetup extends Sximo  {
	
	protected $table = 'new_location_setups';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT new_location_setups.* FROM new_location_setups  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE new_location_setups.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

}
