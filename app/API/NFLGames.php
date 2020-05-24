<?php

namespace App\API;

use App\Game;
use App\Stadium;
use App\Team;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class NFLGames extends Model
{
    private $apiBaseUrl = "https://api.sportsdata.io/v3/nfl";
    private $apiKey = "eb6298e992f04d598f4d33e692fd8bb6";
    private $gameType = 'nfl';

    public function sync()
    {
    	$this->syncTeams();
    }

    public function syncAll()
    {
        $team = $this->syncTeams();
        $game = $this->syncGames();
        $stadium = $this->syncStadiums();

        return $team && $game && $stadium;
    }

    public function syncTeams()
    {
        $teams = Http::withHeaders(['Ocp-Apim-Subscription-Key' => $this->apiKey])
                    ->get("{$this->apiBaseUrl}/scores/json/AllTeams")
                    ->body();

        $mappedTeams = collect(json_decode($teams))->map(function ($team) {
            $date = now();

            $output = [
                'TeamType' => $this->gameType,
                'Key' => $team->Key,
                'GlobalTeamID' => $team->GlobalTeamID,
                'City' => $team->City,
                'Name' => $team->Name,
                'StadiumID' => $team->StadiumID,
                'All' => json_encode($team),
                'created_at' => $date,
                'updated_at' => $date
            ];

            return $output;
        });

        DB::beginTransaction();

        try {
            DB::table('teams')->truncate();
            DB::table('teams')->insert($mappedTeams->toArray());
            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollback();

            return false;
        }
    }

    public function syncStadiums()
    {
        $stadiums = Http::withHeaders(['Ocp-Apim-Subscription-Key' => $this->apiKey])
                    ->get("{$this->apiBaseUrl}/scores/json/Stadiums")
                    ->body();

        $mappedStadiums = collect(json_decode($stadiums))->map(function ($stadium) {
            $date = now();

            $output = [
                'StadiumType' => $this->gameType,
                'StadiumID' => $stadium->StadiumID,
                'Name' => $stadium->Name,
                'City' => $stadium->City,
                'State' => $stadium->State,
                'Country' => $stadium->Country,
                'All' => json_encode($stadium),
                'created_at' => $date,
                'updated_at' => $date
            ];

            return $output;
        });

        DB::beginTransaction();

        try {
            DB::table('stadiums')->truncate();
            DB::table('stadiums')->insert($mappedStadiums->toArray());
            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollback();

            return false;
        }
    }

    public function syncGames()
    {
        // Current Timeframe
        $timeframe = Http::withHeaders(['Ocp-Apim-Subscription-Key' => $this->apiKey])
                    ->get("{$this->apiBaseUrl}/scores/json/Timeframes/all")
                    ->body();

        // Store the timeframes
        Redis::set('nfl_timeframes', $timeframe);

        // Populate the database with games
        $allTimeframes = collect(json_decode(Redis::get('nfl_timeframes')));

        $seasons = $allTimeframes->filter(function ($timeframe) {
            return $timeframe->HasGames;
        })->map(function ($timeframe) {
            return $timeframe->ApiSeason;
        })->unique()->values();

        $client = new Client([
            'base_uri' => $this->apiBaseUrl,
            'headers' => [
                'Ocp-Apim-Subscription-Key' => $this->apiKey
            ]
        ]);

        $requests = function ($seasons) {
            foreach ($seasons as $season) {
                yield new Request('GET', "{$this->apiBaseUrl}/scores/json/Scores/{$season}");
            }
        };

        $responses = Pool::batch($client, $requests($seasons), ['concurrency' => 5]);

        $games = collect();

        foreach ($responses as $response) {
            $rawGames = json_decode($response->getBody()->getContents());

            $mappedGames = collect($rawGames)->map(function ($game) {
                $date = now();

                $output = [
                    'GameType' => $this->gameType,
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

                return $output;
            });

            $games->add($mappedGames);
        }

        DB::beginTransaction();

        try {
            DB::table('games')->truncate();
            DB::table('games')->insert($games->collapse()->toArray());
            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollback();

            return false;
        }
    }

    public function gamesForWeek($timeframe)
    {
        $scores = collect(Http::withHeaders(['Ocp-Apim-Subscription-Key' => $this->apiKey])
                    ->get("{$this->apiBaseUrl}/scores/json/ScoresByWeek/{$timeframe->ApiSeason}/{$timeframe->ApiWeek}")
                    ->json());

        return $scores;
    }

    public function timeframeFromDate($date = null)
    {
    	if (is_null($date)) {
    		$date = now();
    	}

    	$localTimeframes = collect(json_decode(Redis::get('nfl_timeframes')));

    	$localTimeframes = $localTimeframes->map(function ($timeframe) {
        	$timeframe->StartDate = Carbon::parse($timeframe->StartDate);
        	$timeframe->EndDate = Carbon::parse($timeframe->EndDate);
        	return $timeframe;
        });

        return $localTimeframes->where('StartDate', '<=', $date)->where('EndDate', '>=', $date)->first();
    }
}