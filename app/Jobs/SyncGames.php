<?php

namespace App\Jobs;

use App\API\RealtimeSyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncGames implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $league;

    public $maxExceptions = 5;

    public function __construct($league)
    {
        $this->league = $league;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(RealtimeSyncService $service)
    {
        $iterations = 0;
        $bigStart = now();
        $limit = 6;
        $remianing = 59;

        while ($iterations < $limit) {
            $start = now();

            Log::debug("Pass " . ($iterations + 1) . ": {$this->league} sync started at " . $start->toDateTimeString());
            $service->{$this->league}();
            Log::debug("Pass " . ($iterations + 1) . ": {$this->league} sync ended at " . $start->toDateTimeString());

            $iterations++;

            $diff = floor(now()->diffInMilliseconds($start) / 1000);
            $remianing -= $diff;

            if ($diff < 60) {
                Log::debug("Pass {$iterations} took {$diff} seconds.");

                if ($diff < 10) {
                    if ($iterations == $limit) {
                        sleep(58 - now()->diffInSeconds($bigStart));
                        continue;
                    }

                    sleep(10 - ($diff + 1));
                    continue;
                }

                $possibleRuns = floor($remianing / 10) - 1;
                Log::debug("Can possibly run {$possibleRuns} times in the remianing seconds.");

                $oldLimit = $limit;
                $limit = $iterations + $possibleRuns;

                if ($oldLimit != $limit) {
                    Log::debug("Iteration limit changed from {$oldLimit} to {$limit}");
                }
                continue;
            }

            break;
        }
    }
}
