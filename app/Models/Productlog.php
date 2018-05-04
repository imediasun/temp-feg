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
  ''                AS search_all_fields,
  products.*,
  pLOG.created_at as productLogCreatedAt
FROM reserved_qty_log pLOG
  INNER JOIN products
    ON pLOG.variation_id = products.variation_id
  LEFT JOIN order_contents
    ON products.id = order_contents.product_id
  LEFT JOIN orders
    ON orders.id = order_contents.order_id  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE pLOG.id IN(SELECT
                   MAX(id)
                 FROM reserved_qty_log
                 GROUP BY variation_id)
    AND products.id IS NOT NULL
    AND products.is_reserved = 1  ";
	}
	
	public static function queryGroup(){
		return " GROUP BY products.vendor_description, products.sku, products.vendor_id, products.case_price  ";
	}

}
