<?php

namespace App\Console\Commands;

use App\Models\Core\Users;
use App\User;
use Illuminate\Console\Command;

class SyncUserLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
        protected $signature = 'sync:user_locations';

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
         Users::SyncActiveUserLocations();

   }
}
