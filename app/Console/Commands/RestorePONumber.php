<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RestorePONumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restore:po';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore Unused PO Numbers.';

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
        if (!env('AUTOMATIC_UNUSED_PO_RESTORE', false)) {
            return;
        }
        $count = \DB::table('po_track')->where('enabled', '0')->where('created_at', '<=', \DB::raw('DATE_SUB(NOW(), INTERVAL '.env("UNUSED_PO_RESTORE_TIMEOUT", "120").' MINUTE)'))->delete();
        if($count){
            \Log::info($count." Unused PO's Restored.");
            echo $count." Unused PO's Restored.";
        }
    }
}
