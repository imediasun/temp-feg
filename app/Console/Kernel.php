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
        \App\Console\Commands\SyncGameEarningsFromLive::class,
        \App\Console\Commands\Elm5TaskManager::class,
        \App\Console\Commands\CreateDummyOrders::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('comments:read')->everyMinute();
        $schedule->command('autocloseorder')->daily();
        $schedule->command('inspire')->hourly();
        $schedule->command('syncgameearningsfromlive')->dailyAt('17:00')->withoutOverlapping();
        $schedule->command('create:dummy_order')->everyMinute()->sendOutputTo("C:\\Users\\adnan\\Desktop\\test.txt");
        //$schedule->command('syncgameearningsfromlive')->everyMinute()->withoutOverlapping();
        $schedule->command('elm5taskmanager')->everyMinute();
    }
}
