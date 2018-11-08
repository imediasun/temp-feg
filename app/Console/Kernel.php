<?php

namespace App\Console;

use DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\ReadComment::class,
        \App\Console\Commands\AutoCloseOrder::class,
        \App\Console\Commands\Elm5TaskManager::class,
        \App\Console\Commands\CreateProductMeta::class,
        \App\Console\Commands\CleanProductMeta::class,
        \App\Console\Commands\CreateDummyOrders::class,
        \App\Console\Commands\SyncUserLocations::class,
        \App\Console\Commands\RefreshOAuthToken::class,
        \App\Console\Commands\ResetEmailsToAllActiveUsers::class,
        \App\Console\Commands\EnableBlockedOrderItems::class,
        \App\Console\Commands\RestorePONumber::class,
        \App\Console\Commands\CheckStuff::class,
        \App\Console\Commands\CheckNetSuiteApi::class,
        \App\Console\Commands\InjectFieldToModule::class,
        \App\Console\Commands\VendorImportProduct::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //giving error
        $schedule->command('refresh:token')->cron('*/50 * * * * *')->withoutOverlapping();;
        $schedule->command('comments:read')->everyMinute();
        $schedule->command('autocloseorder')->daily();
        $schedule->command('inspire')->everyMinute();
        //turning off to allow client to test and avoid from varying counts
        $schedule->command('create:dummy_order')->cron('*/30 * * * * *')->withoutOverlapping();;
        $schedule->command('elm5taskmanager')->everyMinute();
        $schedule->command('enable:blocked_order_items')->everyMinute();
        $schedule->command('restore:po')->everyMinute();
        $schedule->command('check:stuff')->daily();

        $schedule->command('cleanproductmeta')->hourly();
        $schedule->command('checkapi')->hourly();

    }
}
