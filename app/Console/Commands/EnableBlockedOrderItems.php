<?php

namespace App\Console\Commands;

use App\Models\managefegrequeststore;
use Illuminate\Console\Command;
use App\Library\FEG\System\FEGSystemHelper;

class EnableBlockedOrderItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enable:blocked_order_items';

    protected $L = null;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This commands enable the items in ManageFegRequestStore that where disabled for 10 mins';

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
        if (env('DONT_ENABLE_BLOCKED_ITEMS', false) === true) {

            return;
        }

        $L = $this->L = FEGSystemHelper::setLogger($this->L, "enable-blocked-items.log", "FEGEnableBlockedItems/EnableBlockedItems", "ENABLE_BLOCKED_ITEMS");
        $L->log('Start getting blocked items');

        //$blocked_items = \DB::select("SELECT id FROM requests WHERE blocked_at <= (NOW() - INTERVAL ".env('ENABLE_BLOCKED_ITEMS_TIME')." MINUTE )");
        //$blocked_items = managefegrequeststore::where('blocked_at', '<=',date("Y-m-d h:i:s",strtotime(date("Y-m-d h:i:s")." +10 minutes")))->get();
        $blocked_items = managefegrequeststore::whereNotNull('blocked_at')->get();
        $count = count($blocked_items);
        $L->log($count.' Blocked records found');
        $blocked_items_ids = implode(',',$blocked_items->pluck('id')->all());
        $L->log(' Blocked records IDs = '.$blocked_items_ids);
        foreach($blocked_items as $blocked_item)
        {
            $L->log('Checking condition '.date("Y-m-d H:i:s",strtotime($blocked_item->blocked_at." +10 minutes")) .' < '. date("Y-m-d H:i:s"));
            $L->log('Checking condition '.date("Y-m-d H:i:s") .' - '. $blocked_item->blocked_at .'>='. 10 );

            $blockedDateTime = \DateTime::createFromFormat('Y-m-d H:i:s',$blocked_item->blocked_at);
            $blockedTimeStamp = $blockedDateTime->getTimestamp();
            $elapsedTime = (time() - $blockedTimeStamp)/60.00;
            if($elapsedTime >= 10)
            {
                \DB::update('update requests set blocked_at = null WHERE id = '.$blocked_item->id);
                $L->log('table requests blocked_at field set to null where id =   '.$blocked_item->id.'and blocked_at = '.$blocked_item->blocked_at );
            }
            else
            {
                $L->log('table requests blocked_at field not set to null where id =   '.$blocked_item->id.'and blocked_at = '.$blocked_item->blocked_at );
            }
        }

        $L->log('Cron job for updating blocked items END');
        return true;
    }
}
