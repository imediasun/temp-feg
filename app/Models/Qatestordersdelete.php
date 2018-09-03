<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class qatestordersdelete extends Sximo  {
	
	protected $table = 'orders';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT orders.* FROM orders  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE orders.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
    public static function deleteAllTestOrder($ids){
	      OrderContent::whereIn('order_id',$ids)->delete();
	      orderdetail::whereIn('orderNumber',$ids)->delete();
	      OrderRelation::whereIn('order_id',$ids)->delete();
	      OrderSendDetails::whereIn('order_id',$ids)->delete();
	      OrderReceived::whereIn('order_id',$ids)->delete();
    }
}
