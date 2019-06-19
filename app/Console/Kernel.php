<?php

namespace App\Console;

use App\Console\Commands\ExtractGoogleDriveFiles;
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
        \App\Console\Commands\ReindexCommand::class,
        \App\Console\Commands\ReindexOrderCommand::class,
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
        \App\Console\Commands\SendVendorScheduleEmails::class,
        \App\Console\Commands\CheckEnvConfiguration::class,
        \App\Console\Commands\ExtractGoogleDriveFiles::class,
        \App\Console\Commands\ExtractGoogleDriveLoctionsReports::class,
        \App\Console\Commands\RefreshGoogleDriveAccessToken::class
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
        //$schedule->command('refresh:token')->cron('*/50 * * * * *')->withoutOverlapping();;
        //this command was not executing properly from this file. under FEG-2855 this command moved to FEG tasks in admin panel
        //to run after every 50 minutes
        //$schedule->command('refresh:token')->cron('*/50 * * * *');
        $schedule->command('comments:read')->everyMinute();
        $schedule->command('autocloseorder')->daily();
        $schedule->command('inspire')->everyMinute();
        //turning off to allow client to test and avoid from varying counts
        $schedule->command('create:dummy_order')->cron('*/30 * * * *')->withoutOverlapping();;
        $schedule->command('elm5taskmanager')->everyMinute();
        $schedule->command('enable:blocked_order_items')->everyMinute();
        $schedule->command('restore:po')->everyMinute();
        $schedule->command('check:stuff')->daily();

        $schedule->command('cleanproductmeta')->hourly();
        $schedule->command('checkapi')->hourly();

        //export product list to their respective vendors
        $schedule->command('email:sendvendorschedule')->daily();
        $schedule->command('vendorproduct:import')->withoutOverlapping(2);

        $schedule->command('env:checkenv')->daily();

//        $schedule->command('extract:googledrivefiles Daily')->daily();//Get Daily google drive files
//        $schedule->command('extract:googledrivefiles Weekly')->weekly();//Get Daily google drive files
//        $schedule->command('extract:googledrivefiles Monthly')->monthly();//Get Daily google drive files
//        $schedule->command('extract:googledrivefiles 13Weeks')->weekly();//Get Daily google drive files
//
        $schedule->command('extract:googledrivelocations')->weekly();//Get the google drive location files ID (Daily, weekly, monthly, 13 weeks)
        //$schedule->command('refresh:googledriveaccesstoken')->cron('*/55 * * * *');//Refresh Google drive access token.

        //2AM daily
        $schedule->command('extract:googledrivefiles Daily')->cron('0 2 * * *');
        //5AM Weekly
        $schedule->command('extract:googledrivefiles Weekly')->cron('0 5 * * *');
        ///6AM Monthly
        $schedule->command('extract:googledrivefiles Monthly')->cron('0 6 * * *');
        //7AM 13 weeks
        $schedule->command('extract:googledrivefiles 13Weeks')->cron('0 7 * * *');


    }
}
