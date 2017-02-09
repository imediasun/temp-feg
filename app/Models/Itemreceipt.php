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

    public static function querySelect(){

    return " SELECT orders.*,order_received.order_id FROM orders INNER JOIN order_received ON
orders.id=order_received.order_id ";


    }

    public static function queryWhere($range=null){

        return "  WHERE orders.id IS NOT NULL  $range ";
    }

    public static function queryGroup(){
        return " GROUP BY order_received.order_id ";
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
            $sort = "orders.id";
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
        $cond="";

        if(!empty($args['createdFrom']) && isset($args['createdFrom'])){
            $cond .= " AND order_received.created_at BETWEEN '".$args['createdFrom']."' AND '".$args['createdTo']."'";
            $createdFlag = true;
        }
        if ($cond != null) {
            $select .= self::queryWhere($cond);
        }
        else {

            $select .= self::queryWhere();
        }
        if(!empty($updatedFrom)){

            if($createdFlag){
                $select .= " OR updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
            }
            else{
                $select .= " AND updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
            }

        }
        \Log::info("Total Query : ".$select . " {$params} " . "  self::queryGroup() "." {$orderConditional}");
        //why added group by in order
        $counter_select =\DB::select($select . " {$params} "  . self::queryGroup() .  " {$orderConditional}");
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
//echo $select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ";
       // die();
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
        $order_ids=array();
        $where="";
        foreach($data as $record)
{
        $order_ids[]=$record->id;
}
        if(!empty($param['createdFrom'])){
            $where .= " AND order_received.created_at BETWEEN '".$param['createdFrom']."' AND '".$param['createdTo']."'";
            $createdFlag = true;
        }
        $qry_in_string=implode(',',$order_ids);
        if(empty($qry_in_string))
            $qry_in_string = "''";
        $order_received_data=\DB::select("select *from order_received where order_id in($qry_in_string) $where");
        $order_received_ids=\DB::select("select order_id from order_received where order_id in($qry_in_string) $where group by order_id");
       // echo "select order_id from order_received where order_id in($qry_in_string) $where group by order_id";
        //all order contents place them in relevent order
        foreach($data as $order_data) {

                foreach ($order_received_ids as $order_ids) {
                    if ($order_ids->order_id == $order_data->id) {
                            $result[$order_data->id] = (array)$order_data;
                            $result[$order_data->id]['id'] = $order_data->id;
                        }
                    }
            

            /* unset($result[$record->order_id]['order_id']);
             unset($result[$record->order_id]['order_line_item_id']);
             unset($result[$record->order_id]['status']);*/
            foreach ($order_received_data as $record) {
                if ($order_data->id == $record->order_id) {
                    $result[$record->order_id]['receipts'][] = [
                        'id' => $record->id,
                        'order_id' => $record->order_id,
                        'order_line_item_id' => $record->order_line_item_id,
                        'quantity' => $record->quantity,
                        'received_by' => $record->received_by,
                        'date_received' => $record->date_received,
                        'created_at' => $record->created_at,
                        'notes' => $record->notes,
                        'status' => $record->status
                    ];
                }
            }
        }

        return array_values($result);
    }



}
