<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class order extends Sximo  {
	
	protected $table = 'orders';
	protected $primaryKey = 'id';
    const OPENID1=1,OPENID2=3,OPENID3=4,FIXED_ASSET_ID=9,PRO_IN_DEV=18,CLOSEID1=2,CLOSEID2=5;
	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){

		return "  SELECT orders.*,order_type.is_merch FROM orders,order_type  ";
	}	

	public static function queryWhere( $cond=null ){
        $return="WHERE orders.order_type_id=order_type.id AND";
		switch($cond)
        {
            case 'ALL':
                $return.=" orders.id IS NOT NULL";
                break;
            case 'OPEN':
                $return.=" orders.status_id IN(".self::OPENID1.",". self::OPENID2 .",". self::OPENID3.") AND order_type.is_merch = 1";
                break;
            case 'FIXED_ASSET':
                $return.=" orders.order_type_id = ".self::FIXED_ASSET_ID;
                break;
            case 'PRO_IN_DEV':
                $return.="  orders.order_type_id = ".self::PRO_IN_DEV;
                break;
            case 'CLOSED':
                $return.="  orders.status_id IN(".self::CLOSEID1.",". self::CLOSEID2.") AND order_type.is_merch = 1";
                break;
            default:
                $return.=" orders.id IS NOT NULL";
        }

		return $return;
	}
	
	public static function queryGroup(){
		return "GROUP BY orders.id  ";
	}
	

}
