<?php

namespace App\Console\Commands;
use DB;
use Illuminate\Console\Command;
use App\Models\Ticketcomment;

class AutoCloseOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autocloseorder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '15 days ago orders is uuto closed';

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
        $user_id = -1;
        $notes = 'close by cron job';
        $orders  = \DB::select("Select date_ordered, id FROM orders WHERE status_id != 2 AND date_ordered <= DATE_ADD(CURDATE(), INTERVAL -15 DAY) limit 0,10");
        foreach($orders as $order)
        {
            echo $order->id;
            $order_received  = \DB::select("Select * FROM order_received WHERE order_id = $order->id");
            if(count($order_received) > 0)
            {
                $order_contents  = \DB::select("Select * FROM order_contents WHERE order_id = $order->id");
                foreach($order_contents as $order_content)
                {
                    echo 'update order_contents table';
                    \DB::update('UPDATE order_contents
								 	 	 SET item_received = '. $order_content->qty . '
							   	   	   WHERE id = '. $order_content->id);
                }
                echo 'update orders table';
                $status = 2;
                \DB::update('UPDATE orders
								 	 	 SET date_received = "'. date('Y-m-d') . '"
								 	 	 , status_id = '. $status . '
								 	 	 , received_by = '. $user_id .'
								 	 	 , notes = "'. $notes . '"
							   	   	      WHERE id = '. $order->id);
            }
        }
    }
}
