<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class itemreceipt extends Sximo  {
	
	protected $table = 'order_received';
	protected $primaryKey = 'id';

	public function __construct() {
	    ini_set('memory_limit','1G');
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT orders.* FROM orders  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE orders.id IS NOT NULL ";
	}

	public static function queryGroup(){
		return "GROUP BY orders.id ";
	}


    public static function processApiData($json)
    {
        return self::addOrderReceiptItems($json);
    }


    public static function addOrderReceiptItems($data){
        $orders = [];
        //extract order id for query to order_contents order_id in (1,2,3)
        foreach($data as &$record){
            $orders[] = $record['id'];
            $record['receipts'] = [];
        }
        $query = "select * from order_received where order_id in (".implode(',',$orders).")";
        $result = \DB::select($query);
        //all order contents place them in relevent order
        foreach($result as $item){
            $orderId = $item->order_id;
            foreach($data as &$record){
                if($record['id'] == $orderId){
                    break;
                }
            }
            $record['receipts'][] = (array)$item;
        }
        return $data;
    }



}
