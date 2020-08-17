<?php

namespace App\Console;

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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->call(function () {
            $mlb = new \App\API\MLB();
            $mlb->daily();
            $mlb->daily(now()->subDays(1));
        })->everyMinute();

        $schedule->call(function () {
            $nba = new \App\API\NBA();
            $nba->daily();
            $nba->daily(now()->subDays(1));
        })->everyMinute();

        $schedule->call(function () {
            $nhl = new \App\API\NHL();
            $nhl->daily();
            $nhl->daily(now()->subDays(1));
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
