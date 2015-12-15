<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class orderdetail extends Sximo  {
	
	protected $table = 'orderdetails';
	protected $primaryKey = 'productCode';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT orderdetails.* FROM orderdetails  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE orderdetails.productCode IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
