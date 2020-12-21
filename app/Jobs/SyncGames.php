<?php

namespace App\Jobs;

use App\API\RealtimeSyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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

            $service->{$this->league}();

            $iterations++;

            $diff = floor(now()->diffInMilliseconds($start) / 1000);
            $remianing -= $diff;

            if ($diff < 60) {
                if ($diff < 10) {
                    if ($iterations == $limit) {
                        sleep(58 - now()->diffInSeconds($bigStart));
                        continue;
                    }

                    sleep(10 - ($diff + 1));
                    continue;
                }

                $possibleRuns = floor($remianing / 10) - 1;

                $oldLimit = $limit;
                $limit = $iterations + $possibleRuns;

                continue;
            }

            break;
        }
    }
}
