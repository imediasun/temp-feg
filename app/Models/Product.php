<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class product extends Sximo  {
	
	protected $table = 'products';
	protected $primaryKey = 'productId';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT products.* FROM products  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE products.productId IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
