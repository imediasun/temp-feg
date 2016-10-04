<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateDummyOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:dummy_orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $created_date=$this->rand_date('2016-09-01','2016-10-30');
        $counter=rand(1,5);
      // echo 'total number of orders:'.$counter.'----';
        for($i=0;$i < $counter;$i++) {
          // echo $counter;
            $this->createOrder($created_date,$i,$created_date);
        }
    }
    function createOrder($created_date,$order_no=null,$created_date=null)
    {

        $user_id=$this->getId('users');
        $location_id=$this->getId('user_locations',$user_id);
        $vendor_id=$this->getId('vendor');
        $status_id=1;
        $freight_id=$this->getId('freight');
        $order_type=$this->getId('order_type');
        $company_id=$this->getId('company');
       // die ('here...');
        $po_number=$location_id.'-'.date('mdy',strtotime($created_date)).'-'.$this->increamentPO();
        $created_time = strtotime($created_date);
        $days=rand(1,59);
        $date_received= date('Y-m-d',strtotime($created_date.' +'.$days.' days'));

        $order_contents=array();
        $counter=rand(1,5);
        $order_description="";
        $order_total=0;
        $product_ids=array();

        for($i = 0;$i <  $counter ; $i++)
        {
            $product_ids[$i]=$this->getId('products');

        }

        $product_data=$this->getProductData($product_ids);
        $iproduct_id=array();
        $iproduct_description=array();
        $iitem_name=array();
        $iprice=array();
        $iquantity=array();
        $itotal=array();

        $j=1;
        foreach($product_data as $pd)
        {
            $qty=rand(1,50);
            $iproduct_id[]=$pd->id;
            $iproduct_description[]=$pd->id.'-'.$pd->vendor_description;
            $iitem_name[]=$pd->vendor_description;
            $iprice[]=$pd->case_price;
            $iquantity[]=$qty;
            $total_price=$pd->case_price*$qty;
            $itotal[] =$total_price;
            $order_description.=' | item' . $j . ' - (' . $qty . ') '  .$pd->vendor_description. ' @ $' . $pd->unit_price . ' ea.';
            $order_total += $itotal[$j-1];
            $j++;
        }


        $orders_data=array('user_id'=>$user_id,'date_ordered'=>$created_date,'location_id'=>$location_id,'vendor_id'=>$vendor_id,'order_total'=>$order_total,
            'status_id'=>$status_id,'freight_id'=>$freight_id,'order_description'=>$order_description,'company_id'=>$company_id
        ,'order_type_id'=>$order_type,'po_number'=>$po_number,'date_received'=>$date_received,'created_at'=>date('Y-m-d'),'updated_at'=>date('Y-m-d'));

        $inserted=\DB::table('orders')->insert($orders_data);
        if($inserted)
        {
            $order_id = \DB::getPdo()->lastInsertId();

            for($i=0;$i<count($iproduct_id);$i++)
            {
                $iorder_id[]=$order_id;
            }



            $received_qtys=array();
            for($k=0; $k < count($iorder_id);$k++) {
                $order_contents['order_id']=$iorder_id[$k];
                $order_contents['product_id']=$iproduct_id[$k];
                $order_contents['product_description']=$iproduct_description[$k];
                $order_contents['price']=isset($iprice[$k])?$iprice[$k]:0;
                $order_contents['qty']=$iquantity[$k];
                $order_contents['item_name']=$iitem_name[$k];
                $order_contents['total']=$itotal[$k];
                \DB::table('order_contents')->insert($order_contents);
                $order_contents_ids[$k]=\DB::getPdo()->lastInsertId();

            }

            $order_receipt_data=array();
            $order_receipt_data['order_id']=$order_id;
            $order_receipt_data['order_count']=count($iorder_id);
            $order_receipt_data['order_status']=$this->getId('order_status');
            $order_receipt_data['order_type_id']=$order_type;
            $order_receipt_data['location_id']=$location_id;
            $order_receipt_data['user_id']=$user_id;
            $order_receipt_data['added_to_inventory']=0;
            $order_receipt_data['added']=0;
            $random_num=rand(1,count($order_contents_ids));
            $received_part_ids=array();
            $rand_keys=array_rand($order_contents_ids,$random_num);
            $item_received=array();
            if(count($rand_keys)>1)
            foreach($rand_keys as $rk)
            {
                $received_part_ids[]=$order_contents_ids[$rk];


            }
            $qtyarr=array();
            if(count($received_part_ids)==0)
            {
                //echo 'in received parts';
                $order_receipt_data['order_status']=2;

            }
            else{
              //  echo $order_id.'----';
             //   echo 'select id,qty from order_contents where id in ('.implode(",",$received_part_ids).')';

                $qtys=\DB::select('select id,qty from order_contents where id in ('.implode(",",$received_part_ids).')');
                foreach($qtys as $q)
                {
                   $qtyarr[$q->id]=$q->qty;
                }

            }
            $received_item_qty = $iquantity;
            $order_receipt_data['ItemsId'] = $order_contents_ids;
            $order_receipt_data['items_received']="";

            $cond=0;
            //die();
            for ($i = 0; $i < count($order_contents_ids); $i++) {
                $status = 1;

                if(count($qtyarr) > 0 && isset($received_part_ids[$i]) )
                {
                    $val=rand(1,$qtyarr[$received_part_ids[$i]]-2);
                    $cond = $val ;
                    $notes="Some Items Received";
                }
                else
                {
                    $cond = $iquantity[$i];
                    $notes="All Items Received";
                }
                \DB::insert('INSERT INTO order_received (`order_id`,`order_line_item_id`,`quantity`,`received_by`, `status`, `date_received`, `notes`)
							 	  		   VALUES (' . $order_id . ',' . $order_contents_ids[$i] . ',' . $iquantity[$i] . ',' . $user_id . ',' . $status . ', "' . date('Y-m-d') . '" , "' . $notes. '" )');
                \DB::update('UPDATE order_contents
								 	 	 SET item_received ='.$cond . '
							   	   	   WHERE id = '. $order_contents_ids[$i]);
            }
            $date_received = date("Y-m-d", strtotime($date_received));
            $data = array('date_received'=>$date_received,
                'status_id' => $status,
                'notes' => $notes,
                'tracking_number' => "",
                'received_by' => $this->getId('users'),
                'added_to_inventory' => 0);
            \DB::table('orders')->where('id', $order_id)->update($data);
        }
        else
        {
            echo "some error occured";
        }
    }
    function getId($table,$user=null)
    {
        if($table == "products")
        {
            $id = \DB::select('select id from ' . $table .' Where inactive !=1');
        }
        elseif($user != NULL)
        {
            $id=\DB::select('select location_id as id from  user_locations where user_id='.$user);
           if(count($id) == 0)
           {
               $id=2012;
               return $id;
           }


        }
        else {
            $id = \DB::select('select id from ' . $table);
        }
        $rand_value=rand(1,count($id));
        if(count($id)>0) {
    $id = $id[$rand_value - 1]->id;
        }
        else{
            die();
        }
        return $id;
    }
    function getProductData($product_ids)
    {
        $product_data=\DB::select('select id,sku,vendor_description,item_description,unit_price,case_price,retail_price from products where  id in ('.implode(',',$product_ids).')');

        return $product_data;
    }
    function increamentPO()
    {
        $today = date('mdy');
        $po = \DB::select("select po_number from orders where po_number like '%-$today-%' order by id desc limit 0,1");
        if(!empty($po)){
            $po = array_reverse(explode('-', $po[0]->po_number));
            return ++$po[0];
        }
        return 1;
    }
    function rand_date($min_date, $max_date) {
        /* Gets 2 dates as string, earlier and later date.
           Returns date in between them.
        */

        $min_epoch = strtotime($min_date);
        $max_epoch = strtotime($max_date);

        $rand_epoch = rand($min_epoch, $max_epoch);

        return date('Y-m-d H:i:s', $rand_epoch);
    }
    function postReceiveorder(Request $request, $id = null)
    {
        $received_part_ids = array();
        $order_id = $request->get('order_id');
        $item_count = $request->get('item_count');
        $notes = $request->get('notes');
        $order_status = $request->get('order_status');
        $added_to_inventory = $request->get('added_to_inventory');
        $user_id = $request->get('user_id');
        $added = 0;
        if(!empty($request->get('receivedInParts')))
        {
            $received_part_ids = $request->get('receivedInParts');
        }
        else
        {
            // close order
            $order_status = 2;
        }
        $received_qtys = $request->get('receivedQty');
        $item_ids = $request->get('itemsID');
        $received_item_qty = $request->get('receivedItemsQty');
        for ($i = 0; $i < count($item_ids); $i++) {
            $status = 1;
            if(in_array($item_ids[$i], $received_part_ids))
                $status = 2;
            \DB::insert('INSERT INTO order_received (`order_id`,`order_line_item_id`,`quantity`,`received_by`, `status`, `date_received`, `notes`)
							 	  		   VALUES (' . $order_id . ',' . $item_ids[$i] . ',' . $received_qtys[$i] . ',' . $user_id . ',' . $status . ', "' . date('Y-m-d') . '" , "' . $notes. '" )');
            \DB::update('UPDATE order_contents
								 	 	 SET item_received = '. $received_item_qty[$i]. '+'. $received_qtys[$i] . '
							   	   	   WHERE id = '. $item_ids[$i]);
        }
        $rules = array();
        if (empty($notes)) {
            $rules['order_status'] = "required:min:2";
        }
        if ($order_status == 5) // Advanced Replacement Returned.. require tracking number
        {
            $rules['tracking_number'] = "required|min:3";
            $tracking_number = $request->get('tracking_number');
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            if (!empty($item_count) && $added_to_inventory == 0) {

                ///////APPLY PRIZES TO THE PROPER GAMES / LOCATIONS
                for ($i = 1; $i <= $item_count; $i++) {
                    $product_id = $request->get('product_id_' . $i);
                    $order_qty = $request->get('order_qty_' . $i);
                    $game = $request->get('game_' . $i);

                    // IF NO GAME SELECTED, INSERT INTO INVENTORY FOR USER'S LOCATION
                    if (!empty($game)) {
                        // IF ALL AVAILABLE QUANTITIES ARE ALLOCATED TO THE GAME
                        $allGame = array('product_id' => $product_id);
                        \DB::table('game')->where('id', $game)->update($allGame);
                    }

                    $location_id = $request->get('location_id');

                    $query = \DB::select('SELECT id
											 FROM merch_inventory
											WHERE product_id = ' . $product_id . '
								   	 		  AND location_id = ' . $location_id . '');

                    if (count($query) == 1) {
                        \DB::update('UPDATE merch_inventory
								 	 	 SET product_qty = product_qty + ' . $order_qty . '
							   	   	   WHERE product_id = ' . $product_id . '
								   	     AND location_id = ' . $location_id);
                    } else {
                        \DB::insert('INSERT INTO merch_inventory (`location_id`,`product_id`,`product_qty`,`user_id`)
							 	  		   VALUES (' . $location_id . ',' . $product_id . ',' . $order_qty . ',' . $user_id . ')');
                    }
                }
                $added = 1;
            }
            $date_received = $request->get('date_received');
            $date_received = date("Y-m-d", strtotime($date_received));
            $data = array('date_received'=>$date_received,
                'status_id' => $order_status,
                'notes' => $notes,
                'tracking_number' => $request->get('tracking_number'),
                'received_by' => $request->get('user_id'),
                'added_to_inventory' => $added);
            \DB::table('orders')->where('id', $request->get('order_id'))->update($data);
            return response()->json(array(
                'status' => 'success',
                'message' => \Lang::get('core.note_success')
            ));
        } else {

            $message = $this->validateListError($validator->getMessageBag()->toArray());
            return response()->json(array(
                'message' => $message,
                'status' => 'error'
            ));
        }
    }
}
