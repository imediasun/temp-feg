<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Library\FEG\System\FEGSystemHelper;

class EnableBlockedOrderItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enable:blocked_items';

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

        $blocked_items = \DB::select("SELECT id FROM requests WHERE blocked_at <= (NOW() - INTERVAL ".env('ENABLE_BLOCKED_ITEMS_TIME')." MINUTE )");
        $count = count($blocked_items);
        $blocked_items = implode(',',$blocked_items);
        $L->log($count.' Blocked records found');
        $L->log(' Blocked records IDs = '.$blocked_items);
        \DB::update('update requests set blocked_at = null WHERE id IN ('.$blocked_items.')');
        $L->log($count .' requests blocked_at field set to null where id =   '.$blocked_items);
        $L->log('Cron job for updating blocked items END');
        return true;
    }
}
