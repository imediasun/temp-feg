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
  ''               AS search_all_fields,
  prod.*,
  pLOG.id,
  pLOG.variation_id,
  pLOG.created_at
FROM reserved_qty_log pLOG
  INNER JOIN products prod
    ON log.variation_id = prod.variation_id
  INNER JOIN order_contents
    ON prod.id = order_contents.product_id
  INNER JOIN orders
    ON orders.id = order_contents.order_id  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE pLOG.id IN(SELECT
                  MAX(id)
                FROM reserved_qty_log
                GROUP BY variation_id)
    AND prod.id IS NOT NULL
    AND prod.is_reserved = 1  ";
	}
	
	public static function queryGroup(){
		return " GROUP BY prod.vendor_description, prod.sku, prod.vendor_id, prod.case_price  ";
	}

}
