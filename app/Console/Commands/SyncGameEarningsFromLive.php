<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Library\SyncFromOldLiveHelpers;

class SyncGameEarningsFromLive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syncgameearningsfromlive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer Game Earnings data from live database';

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
        Log::info('Start Sync From Live');
        SyncFromOldLiveHelpers::livesync();
        Log::info('End Sync From Live');
    }
}
