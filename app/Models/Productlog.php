<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class productlog extends Sximo  {
	
	protected $table = 'products';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT products.* FROM products  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE products.id IS NOT NULL and products.is_reserved = 1";
	}
	
	public static function queryGroup(){
		return " GROUP BY products.vendor_description, products.sku, products.vendor_id, products.case_price ";
	}

}
