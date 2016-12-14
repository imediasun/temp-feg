<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Library\MyLog;
use App\Library\Elm5Tasks;

class Elm5TaskManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elm5taskmanager';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Element 5 Digital Task Manager';

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
        $L = new MyLog("task-manager-command.log", "FEGCronTasks", "FEGCronTasks");
        $L->log('Start Cron Tasks');
        Elm5Tasks::addSchedules();
        Elm5Tasks::runTasks();
        $L->log('End Cron Tasks');
    }
}
