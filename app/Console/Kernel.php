<?php

namespace App\Console;

use App\API\RealtimeSyncService;
use App\Jobs\DailySync;
use App\Jobs\SyncGames;
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
        $schedule->call(function () {
            SyncGames::dispatch('nba')->onQueue('realtime');
            SyncGames::dispatch('nfl')->onQueue('realtime');
            SyncGames::dispatch('nhl')->onQueue('realtime');
            SyncGames::dispatch('mlb')->onQueue('realtime');
            SyncGames::dispatch('ncaaf')->onQueue('realtime');
            SyncGames::dispatch('ncaab')->onQueue('realtime');
        })->everyMinute();

        $schedule->call(function () {
            DailySync::dispatch()->onQueue('daily');
        })->everySixHours();
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
