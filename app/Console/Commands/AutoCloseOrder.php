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
        $orders  = \DB::select("Select date_ordered, id FROM orders WHERE id != 2 AND date_ordered <= DATE_ADD(CURDATE(), INTERVAL -15 DAY) limit 0,10");
        foreach($orders as $order)
        {
            $status = 1;
            $order_contents  = \DB::select("Select * FROM order_contents WHERE order_id = $order->id");
            foreach($order_contents as $order_content)
            {
                \DB::insert('INSERT INTO order_received (`order_id`,`order_line_item_id`,`quantity`,`received_by`, `status`, `date_received`, `notes`)
							 	  		   VALUES (' . $order_content->order_id . ',' . $order_content->id . ',' .$order_content->qty . ',' . $user_id . ',' . $status . ', "' . date('Y-m-d') . '" , "' . $notes. '" )');
                \DB::update('UPDATE order_contents
								 	 	 SET item_received = '. $order_content->qty . '
							   	   	   WHERE id = '. $order_content->id);
                $status = 2;
                \DB::update('UPDATE orders
								 	 	 SET date_received = "'. date('Y-m-d') . '"
								 	 	 AND status_id = '. $status . '
								 	 	 AND received_by = '. $user_id .'
								 	 	 AND notes = "'. $notes . '"
							   	   	      WHERE id = '. $order->id);

            }
        }
    }
}
