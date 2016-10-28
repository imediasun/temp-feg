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
		
		return "  SELECT orders.*,order_received.* FROM orders JOIN order_received ON order_received.order_id = orders.id ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE order_received.id IS NOT NULL ";
	}

	public static function queryGroup(){
		return " ";
	}


    public static function processApiData($json,$param=null)
    {
        return self::addOrderReceiptItems($json,$param);
    }

    public static function getRows($args, $cond = null) {
        $table = with(new static)->table;
        $key = with(new static)->primaryKey;

        extract(array_merge(array(
            'page' => '0',
            'limit' => '0',
            'sort' => '',
            'order' => '',
            'params' => '',
            'global' => 1
        ), $args));

        if($sort == "id"){
            $sort = "order_received.id";
        }
        $orderConditional = ($sort != '' && $order != '') ? " ORDER BY {$sort} {$order} " : '';

        // Update permission global / own access new ver 1.1
        $table = with(new static)->table;
        if ($global == 0)
            $params .= " AND {$table}.entry_by ='" . \Session::get('uid') . "'";
        // End Update permission global / own access new ver 1.1

        $rows = array();
        $select = self::querySelect();

        /*

        */
        $createdFlag = false;

        if ($cond != null) {
            $select .= self::queryWhere($cond);
        }
        else {
            $select .= self::queryWhere();
        }

        if(!empty($createdFrom)){
            $select .= " AND order_received.date_received BETWEEN '$createdFrom' AND '$createdTo'";
            $createdFlag = true;
        }

        if(!empty($updatedFrom)){

            if($createdFlag){
                $select .= " OR updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
            }
            else{
                $select .= " AND updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
            }

        }

        \Log::info("Total Query : ".$select . " {$params} " . " {$orderConditional}");
        //why added group by in order
        $counter_select =\DB::select($select . " {$params} " . " {$orderConditional}");
        $total= count($counter_select);
        if($table=="img_uploads")
        {
            $total="";
        }

        $offset = ($page - 1) * $limit;
        if ($offset >= $total && $total != 0) {
            $page = ceil($total/$limit);
            $offset = ($page-1) * $limit ;
        }
        $limitConditional = ($page != 0 && $limit != 0) ? "LIMIT  $offset , $limit" : '';

        \Log::info("Query : ".$select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ");
        $result = \DB::select($select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ");

        if ($key == '') {
            $key = '*';
        } else {
            $key = $table . "." . $key;
        }

        return $results = array('rows' => $result, 'total' => $total);
    }


    public static function addOrderReceiptItems($data,$param=null){
        $result = [];
        //all order contents place them in relevent order
        foreach($data as $record){
            if(!isset($result[$record->order_id])){
                $result[$record->order_id] = (array)$record;
                $result[$record->order_id]['id'] = $record->order_id;
            }
            unset($result[$record->order_id]['order_id']);
            unset($result[$record->order_id]['order_line_item_id']);
            unset($result[$record->order_id]['status']);
            $result[$record->order_id]['receipts'][] = [
                'id' => $record->id,
                'order_id' => $record->order_id,
                'order_line_item_id' => $record->order_line_item_id,
                'quantity' => $record->quantity,
                'received_by' => $record->received_by,
                'date_received' => $record->date_received,
                'notes' => $record->notes,
                'status' => $record->status
            ];
        }
        //print_r($result);
        //exit;
        return array_values($result);
    }



}
