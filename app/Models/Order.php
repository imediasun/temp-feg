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
	public function getOrderQuery($order_id,$mode=null)
    {
        $data['order_loc_id'] ='';
        $data['order_vendor_id'] ='' ;
        $data['order_type'] = '';
        $data['order_company_id'] ='' ;
        $data['order_freight_id'] = '';
        $data['orderDescriptionArray'] = '';
        $data['orderPriceArray'] = '';
        $data['orderQtyArray'] = '';
        $data['orderProductIdArray'] = '';
        $data['orderRequestIdArray'] = '';
        $data['requests_item_count'] = '';
        $data['today']=$this->get_local_time();
        $data['order_total']='0.00';
        $data['po_1']='0';
        $data['po_2']=date('d').date('m').date('y');
        $data['po_3']=0;
        $data['po_notes']='';
        $data['requests_item_count'] = 0;
        if($order_id!=0) {
            $order_query = \DB::select('SELECT location_id,vendor_id, date_ordered,order_total,order_type_id,company_id,freight_id,po_notes,po_number FROM orders WHERE id = ' . $order_id);
            if (count($order_query) == 1) {
                $data['order_loc_id'] = $order_query[0]->location_id;
                $data['order_vendor_id'] = $order_query[0]->vendor_id;
                $data['order_type'] = $order_query[0]->order_type_id;
                $data['order_company_id'] = $order_query[0]->company_id;
                $data['order_freight_id'] = $order_query[0]->freight_id;
                $data['today'] = $order_query[0]->date_ordered;
                $data['order_total'] = $order_query[0]->order_total;
            }
            $content_query = \DB::select('SELECT IF(O.product_id = 0, O.product_description, P.vendor_description) AS description,O.price AS price,O.qty AS qty
												 FROM order_contents O LEFT JOIN products P ON P.id = O.product_id WHERE O.order_id = ' . $order_id);

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
            if ($mode == 'edit') {
                $data['today'] = $order_query[0]->date_ordered;
                $data['po_notes'] = $order_query[0]->po_notes;
                $data['po_number'] = $order_query[0]->po_number;
                $poArr = array("", "", "");
                if (isset($data['po_number'])) {
                    $poArr = explode("-", $data['po_number']);
                    $data['po_1'] = $poArr[0];
                    $data['po_2'] = isset($poArr[1])?$poArr[1]:"";
                    $data['po_3'] = isset($poArr[2])?$poArr[2]:"";
                }
                $data['po_notes'] = $order_query[0]->po_notes;
            }
             $data['today'] = ($mode) ? $order_query[0]->date_ordered : $this->get_local_time('date');
        }
        return $data;
    }
    function getPoNumber($po_full)
    {
        $query = \DB::select('SELECT po_number FROM orders WHERE po_number = "'.$po_full.'"');
        if (count($query) > 0)
            {
                $po_message = 'taken';
            }
            else
            {
                $po_message = 'available';
            }
        return $po_message;
        }
     public function get_local_time($type=null)
{
    $year = date('Y');
    $month = date('m');
    $day = date('d');
    $dayText = date('D');

    $yearmonthday = $year . '-' . $month . '-' . $day;
    if($type='date')
    {
        return $yearmonthday;
    }
}
}
