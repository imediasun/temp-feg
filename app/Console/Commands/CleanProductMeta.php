<?php

namespace App\Console\Commands;

use App\Library\FEG\System\FEGSystemHelper;
use Illuminate\Console\Command;
use App\Library\MyLog;

class CleanProductMeta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanproductmeta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean Product Meta posted_to_api_at field';

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

        $L = new MyLog("CleanProductMeta.log", "FEGCronTasks/clean-product-meta", "Tasks");
        $this->line('Start clean Product Meta Cron Task');
        $L->log('Start clean Product Meta Cron Task');

        $messages = FEGSystemHelper::cleanProductMeta(['commandObj' => $this]);

        foreach($messages as $message) {
            $this->line($message);
            $L->log($message);
        }

        $L->log('End Clean Product Cron Tasks');
        $this->line('End Clean Product Cron Tasks');
    }

}
