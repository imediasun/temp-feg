<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class product extends Sximo  {
	
	protected $table = 'tb_products';
	protected $primaryKey = 'productId';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_products.* FROM tb_products  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE tb_products.productId IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
