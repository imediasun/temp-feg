<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class vendor extends Sximo  {
	
	protected $table = 'vendor';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT vendor.* FROM vendor ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE vendor.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
