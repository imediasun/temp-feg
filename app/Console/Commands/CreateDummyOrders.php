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

    protected $startDate = null;

    protected $endDate = null;

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
        //execute dummy orders in staging environment only
        if (env('SCENE', 'development') !== 'staging') {
            return;
        }        
        $this->startDate = '2017-01-01';
        $this->endDate = '2017-02-28';
        $created_date=$this->rand_date($this->startDate,$this->endDate);
        $counter=rand(1,5);
      // echo 'total number of orders:'.$counter.'----';
        for($i=0;$i < $counter;$i++) {
          // echo $counter;
            $this->createOrder($created_date,$i);
        }
    }
    function createOrder($created_date=null,$order_no=null)
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
        $iproduct_sku=array();
        $iproduct_caseprice=array();
        $iitem_name=array();
        $iprice=array();
        $iquantity=array();
        $itotal=array();

        $j=1;
        foreach($product_data as $pd)
        {
            $qty=rand(1,50);
            $iproduct_id[]=$pd->id;
            $iproduct_sku[] = $pd->sku;
            $iproduct_caseprice[] = $pd->case_price;
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
        ,'order_type_id'=>$order_type,'po_number'=>$po_number,'date_received'=>$date_received,'created_at'=>date('Y-m-d h:i:sa',strtotime($created_date)),'updated_at'=>date('Y-m-d h:i:sa',strtotime($created_date)));

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
                $order_contents['case_price']=$iproduct_caseprice[$k];
                $order_contents['sku']=$iproduct_sku[$k];

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
              //  echo 'in received parts';
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
                //order receive should be greater than created date
                \DB::insert('INSERT INTO order_received (`order_id`,`order_line_item_id`,`quantity`,`received_by`, `status`, `date_received`, `notes`)
							 	  		   VALUES (' . $order_id . ',' . $order_contents_ids[$i] . ',' . $iquantity[$i] . ',' . $user_id . ',' . $status . ', "' .date('Y-m-d',rand( strtotime($created_date), strtotime($this->endDate))). '" , "' . $notes. '" )');
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
        else if($table == "order_type"){
            $id = \DB::select('select id from ' . $table ." Where order_type != 'Parts for Games'");
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
   
}
