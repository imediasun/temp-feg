<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class expensecategories extends Sximo  {
	
	protected $table = 'expense_category_mapping';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
			
		return "  SELECT expense_category_mapping.*, order_type.order_type as order_type_name, product_type.type_description 
  				  FROM expense_category_mapping
  				  INNER JOIN order_type ON (expense_category_mapping.order_type = order_type.id)
  				  LEFT JOIN product_type ON (expense_category_mapping.product_type = product_type.id)  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE expense_category_mapping.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

}
