<?php

namespace App\API;

use App\Game;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class NcaabApiService extends SportsDataApiService
{
    private $http;
    private $apiKey;
    private $apiBaseUrl = 'https://api.sportsdata.io/v3/cbb';

    public function __construct()
    {
        $this->apiKey = config('services.apiKeys.ncaab');
        $this->http = Http::withHeaders(['Ocp-Apim-Subscription-Key' => $this->apiKey]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getTeams()
    {
        try {
            $response = $this->http
                ->get("{$this->apiBaseUrl}/scores/json/teams")
                ->body();

            return collect(json_decode($response));
        } catch (\Throwable $exception) {
            report($exception);

            return collect();
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getStadiums()
    {
        try {
            $response = $this->http
                ->get("{$this->apiBaseUrl}/scores/json/Stadiums")
                ->body();

            return collect(json_decode($response));
        } catch (\Throwable $exception) {
            report($exception);

            return collect();
        }
    }

    /**
     * @param null $season
     * @return \Illuminate\Support\Collection
     */
    public function getGamesBySeason($season = null)
    {
        $season = $season ?? $this->getCurrentSeason();

        try {
            $response = $this->http
                ->get("{$this->apiBaseUrl}/scores/json/Games/{$season}")
                ->body();

            return collect(json_decode($response));
        } catch (\Throwable $exception) {
            report($exception);
            return collect();
        }
    }

    /**
     * @param null $date
     * @return \Illuminate\Support\Collection
     */
    public function getGamesByDate($date = null)
    {
        if (is_null($date)) {
            $date = now();
        }

        $date = Carbon::parse($date)->format('Y-M-d');

        try {
            $response = $this->http
                ->get("{$this->apiBaseUrl}/scores/json/GamesByDate/{$date}")
                ->body();

            return collect(json_decode($response));
        } catch (\Throwable $exception) {
            report($exception);
            return collect();
        }
    }

    public function getBoxScoreById($gameId)
    {
        if (is_null($gameId)) {
            return collect();
        }

        try {
            $response = $this->http
                ->get("{$this->apiBaseUrl}/stats/json/BoxScore/{$gameId}")
                ->body();

            return collect(json_decode($response));
        } catch (\Throwable $exception) {
            report($exception);
            return collect();
        }
    }

    /**
     * @param null $date
     * @return \Illuminate\Support\Collection
     */
    public function getBoxScoresByDate($date = null)
    {
        if (is_null($date)) {
            $date = now();
        }

        $date = Carbon::parse($date)->format('Y-M-d');

        try {
            $response = $this->http
                ->get("{$this->apiBaseUrl}/stats/json/BoxScores/{$date}")
                ->body();

            return collect(json_decode($response));
        } catch (\Throwable $exception) {
            report($exception);
            return collect();
        }
    }

    /**
     * @return string | null
     */
    public function getCurrentSeason()
    {
        try {
            $response = $this->http->get("{$this->apiBaseUrl}/scores/json/CurrentSeason");
            return json_decode($response)->ApiSeason;
        } catch (\Throwable $exception) {
            report($exception);
            return null;
        }
    }

    /**
     * @return boolean
     */
    public function areGamesInProgress()
    {
        try {
            $response = $this->http->get("{$this->apiBaseUrl}/scores/json/AreAnyGamesInProgress");
            return $response->body();
        } catch (\Throwable $exception) {
            report($exception);
            return false;
        }
    }

    /**
     * @param null $date
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPopulatedGamesByDate($date = null)
    {
        $apiGames = $this->getGamesByDate($date);

        if ($apiGames->count() === 0) {
            return new EloquentCollection();
        }

        $mappedGames = $apiGames->map(function ($game) {
            return $this->mapGame($game);
        });

        DB::beginTransaction();

        try {
            DB::table('games')->insert($mappedGames->toArray());
            DB::commit();

            return Game::whereIn('GlobalGameID', $mappedGames->map(
                function ($game) { return $game['GlobalGameID']; }
            )->toArray())->get();
        } catch (Exception $e) {
            DB::rollback();
            return new EloquentCollection();
        }
    }

    /**
     * @return array
     */
    public function mapGame($game)
    {
        if (!$game) {
            return [];
        }

        $date = now();

        return [
            'GameType' => 'ncaab',
            'GlobalGameID' => $game->GlobalGameID,
            'Date' => $game->Day,
            'GlobalAwayTeamID' => $game->GlobalAwayTeamID,
            'GlobalHomeTeamID' => $game->GlobalHomeTeamID,
            'Status' => $game->Status,
            'StadiumID' => $game->StadiumID,
            'All' => json_encode($game),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
