<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class department extends Sximo  {
	
	protected $table = 'departments';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT departments.* FROM departments  ";
	}	

	public static function queryWhere(  ){
		
		return " where 1 = 1 ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
