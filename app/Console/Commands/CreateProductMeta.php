<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Library\MyLog;

class CreateProductMeta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'createproductmeta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CreateProductMeta';

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
        set_time_limit(0);
        $L = new MyLog("CreateProductMeta.log", "FEGTasks", "Tasks");
        $L->log('Start Cron Tasks');
        $this->line('Start Create Product Meta');

        $messages = \App\Library\FEG\Migration\Data::createProductMeta(['commandObj' => $this]);
        foreach($messages as $message) {
            $this->line($message);
        }

        try {


            
        } catch(\Exception $e) {

            $this->line('Error: ', $e->getMessage());
        }

        $this->line('End Create Product Meta');
        $L->log('End Cron Tasks');
    }

}
