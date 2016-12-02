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
        
        try {
            
            Elm5Tasks::addSchedules();
            Elm5Tasks::runTasks();
            
        } catch(\Exception $e) {
            
            $this->logTaskManagerError($e);
        }
        
        $L->log('End Cron Tasks');
    }
    private function logTaskManagerError($e) {
        global $_scheduleId;
        $errorMessage = $e->getMessage();
        $errorFile = $e->getFile();
        $errorLine = $e->getLine();
        $errorTrace = str_replace('\\\\', "\\", 
                str_replace('\\r', "\r", 
                str_replace('\\n', "\n", 
                str_replace('\\t', "\t", 
                str_replace('\\r\\n', "\r\n", 
                json_encode($e->getTrace(), JSON_UNESCAPED_SLASHES))))));
        
        $generalErrorMessage = "Task Manager Error ";

        if (!empty($_scheduleId)) {
            $generalErrorMessage .= " while running schedule ID: $_scheduleId";
        }
        
        $eL = new MyLog("task-manager-error.log", "FEGCronTasks", "FEG Cron Tasks");
        $eL->error($generalErrorMessage);
        $eL->error('Message: '. $errorMessage);
        $eL->error('File: '. $errorFile . " (line: $errorLine)");
        $eL->error('Trace: '. $errorTrace);
        //JSON_PRETTY_PRINT
    }     
}
