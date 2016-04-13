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
	public function getOrderQuery($order_id)
    {

        $order_query = \DB::select('SELECT location_id,vendor_id, order_type_id,company_id,freight_id FROM orders WHERE id = ' . $order_id);
        if (count($order_query) == 1) {
            $data['order_loc_id'] = $order_query[0]->location_id;
            $data['order_vendor_id'] = $order_query[0]->vendor_id;
            $data['order_type'] = $order_query[0]->order_type_id;
            $data['order_company_id'] = $order_query[0]->company_id;
            $data['order_freight_id'] = $order_query[0]->freight_id;
        }
        $content_query = \DB::select('SELECT IF(O.product_id = 0, O.product_description, P.vendor_description) AS description,O.price AS price,O.qty AS qty
												 FROM order_contents O LEFT JOIN products P ON P.id = O.product_id WHERE O.order_id = ' . $order_id);
        $data['requests_item_count'] = 0;
        if ($content_query) {
            foreach ($content_query as $row) {
                $data['requests_item_count'] = $data['requests_item_count'] + 1;
                $orderDescriptionArray[] = $row->description;
                $orderPriceArray[] = $row->price;
                $orderQtyArray[] = $row->qty;
            }
            $data['orderDescriptionArray'] = $orderDescriptionArray;
            $data['orderPriceArray'] = $orderPriceArray;
            $data['orderQtyArray'] = $orderQtyArray;
            $data['prefill_type'] = 'clone';
        }

        return $data;
    }

}
