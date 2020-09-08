<?php

namespace App\Console;

use App\API\RealtimeSyncService;
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
        $service = new RealtimeSyncService();

        $schedule->call(function () use ($service) {
            $service->nba();
        })->everyMinute();

        $schedule->call(function () use ($service) {
            $service->nfl();
        })->everyMinute();

        $schedule->call(function () use ($service) {
            $service->nhl();
        })->everyMinute();

        $schedule->call(function () use ($service) {
            $service->mlb();
        })->everyMinute();

        $schedule->call(function () use ($service) {
            $service->ncaaf();
        })->everyMinute();

        $schedule->call(function () use ($service) {
            $service->ncaab();
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
