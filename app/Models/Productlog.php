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
		
		return "  SELECT
   ''                          AS search_all_fields,
  products.*,
  reserved_qty_log.id AS logid
FROM products
  INNER JOIN order_contents
    ON products.id = order_contents.product_id
  INNER JOIN orders
    ON orders.id = order_contents.order_id
  INNER JOIN reserved_qty_log
    ON reserved_qty_log .variation_id = products.variation_id  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE products.id IS NOT NULL and products.is_reserved = 1 ";
	}
	
	public static function queryGroup(){
		return " GROUP BY products.vendor_description, products.sku, products.vendor_id, products.case_price ";
	}

}
