<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class pendingrequest extends Sximo  {
	
	protected $table = 'requests';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT requests.* FROM requests  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE requests.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
