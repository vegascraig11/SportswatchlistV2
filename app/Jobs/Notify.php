<?php

namespace App\Jobs;

use App\Events\WatchlistGameStatusChanged;
use App\Notifications\GameHasEnded;
use App\Notifications\GameHasStarted;
use App\Watchlist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class Notify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $game;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($game)
    {
        $this->game = $game;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $watchlist = Watchlist::where('game_id', $this->game->GlobalGameID)->get();

        $lastStatus = Redis::get("game_{$this->game->GlobalGameID}_last_status");
        
        if (!$lastStatus) {
            $lastStatus = $this->game->Status;
            Redis::set("game_{$this->game->GlobalGameID}_last_status", $this->game->Status);
        }

        // Game Start
        if ($lastStatus == 'Scheduled' && $this->game->Status == 'InProgress') {
            foreach ($watchlist as $g) {
                if (Redis::get("user_{$g->user->id}-game_{$this->game->GlobalGameID}-start_notified")) {
                    continue;
                }

                $message = $this->game->toArray()['away_team']['full_name'] . ' vs ' . $this->game->toArray()['home_team']['full_name'] . ' has started.';
                $g->user->notify(new GameHasStarted($message));
                event(new WatchlistGameStatusChanged($message, $g->user->id));
                Redis::set("user_{$g->user->id}-game_{$this->game->GlobalGameID}-start_notified", true);
            }
        }

        // Game End
        if ($lastStatus == 'InProgress' && array_search($this->game->Status, ['Final', 'F/OT']) !== false) {
            foreach ($watchlist as $g) {
                if (Redis::get("user_{$g->user->id}-game_{$this->game->GlobalGameID}-end_notified")) {
                    continue;
                }

                $message = $this->game->awayTeam->full_name . ' vs ' . $this->game->homeTeam->full_name . ' has ended.';
                $g->user->notify(new GameHasEnded($message));
                event(new WatchlistGameStatusChanged($message, $g->user->id));
                Redis::set("user_{$g->user->id}-game_{$this->game->GlobalGameID}-end_notified", true);
            }
        }
    }
}
