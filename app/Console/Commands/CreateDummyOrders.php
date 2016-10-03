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
        $created_date=date('Y-m-d',strtotime('2016-09-01'));

        $user_id=$this->getId('users');

        $location_id=$this->getId('location');
        $vendor_id=$this->getId('vendor');
        $status_id=1;
        $freight_id=$this->getId('freight');
        $order_type=$this->getId('order_type');
        $company_id=$this->getId('company');
        $po_number=$location_id.'-'.date('mdy',strtotime($created_date)).'-'.$this->increamentPO();
         $date_received="";
        $order_contents=array();
        $counter=rand(1,5);
        $order_description="";
        $order_total=0;

        for($i = 1;$i <= $counter ; $i++)
        {
        $product_id=$this->getId('products');

        $product_data=$this->getProductData($product_id);


            $qty=rand(0,100);
            $j=1;
            foreach($product_data as $pd)
            {
                $order_contents['product_id']=$product_id;
                $order_contents['product_description']=$pd->vendor_description;
                $order_contents['price']=$pd->unit_price;
                $order_contents['qty']=$qty;
                $order_contents['total']=$pd->unit_price*$qty;
                $order_description.=' | item' . $j . ' - (' . $qty . ') '  .$pd->vendor_description. ' @ $' . $pd->unit_price . ' ea.';
                $order_total += $order_contents['total'];
                $j++;
            }
        }
        $orders_data=array('user_id'=>$user_id,'date_ordered'=>$created_date,'location_id'=>$location_id,'vendor_id'=>$vendor_id,'order_total'=>$order_total,
              'status_id'=>$status_id,'freight_id'=>$freight_id,'order_description'=>$order_description,'company_id'=>$company_id
             ,'order_type_id'=>$order_type,'po_number'=>$po_number,'date_received'=>$date_received,'created_at'=>date('Y-m-d'));

    $inserted=\DB::table('orders')->insert($orders_data);
        if($inserted)
        {
            echo "order created successfully";
        }
        else
        {
            echo "some error occured";
        }
    }
    function getId($table)
    {
        $id=\DB::select('select id from '.$table);
        $rand_value=rand(0,count($id));
        $id=$id[$rand_value]->id;
        return $id;
    }
    function getProductData($product_id)
    {
        $product_data=\DB::select('select id,sku,vendor_description,item_description,unit_price,case_price,retail_price from products where inactive!=1 and id='.$product_id);

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
}
