<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class order extends Sximo  {
	
	protected $table = 'tb_orders';
	protected $primaryKey = 'orderNumber';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_orders.* FROM tb_orders ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE tb_orders.orderNumber IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
