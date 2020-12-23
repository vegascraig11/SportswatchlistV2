<?php

namespace App\Jobs;

use App\Events\WatchlistGameStatusChanged;
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

    protected $games;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($games)
    {
        $this->games = $games;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $watchlist = Watchlist::whereIn('game_id', $this->games->pluck('GlobalGameID')->values())->get();

        foreach ($games as $game) {
            $lastStatus = Redis::get("game_{$game->GlobalGameID}_last_status");
            
            if (!$lastStatus) {
                $lastStatus = $game->Status;
                Redis::set("game_{$game->GlobalGameID}_last_status", $game->Status);
            }

            // Game Start
            if ($lastStatus == 'Scheduled' && $game->Status == 'InProgress') {
                foreach ($watchlist as $g) {
                    if (Redis::get("user_{$g->user->id}-game_{$game->GlobalGameID}-start_notified")) {
                        continue;
                    }

                    $message = $game->awayTeam->name . ' vs ' . $game->homeTeam->name . ' has started.';
                    $g->user->notify(new GameHasStarted($message));
                    event(new WatchlistGameStatusChanged($message, $g->user->id));
                    Redis::set("user_{$g->user->id}-game_{$game->GlobalGameID}-start_notified", true);
                }
            }

            // Game End
            if ($lastStatus == 'InProgress' && array_search($game->Status, ['Final', 'F/OT']) !== false) {
                foreach ($watchlist as $g) {
                    if (Redis::get("user_{$g->user->id}-game_{$game->GlobalGameID}-end_notified")) {
                        continue;
                    }

                    $message = $game->awayTeam->name . ' vs ' . $game->homeTeam->name . ' has ended.';
                    $g->user->notify(new GameHasStarted($message));
                    event(new WatchlistGameStatusChanged($message, $g->user->id));
                    Redis::set("user_{$g->user->id}-game_{$game->GlobalGameID}-end_notified", true);
                }
            }
        }
    }
}
