<?php

namespace App\API;

use App\Game;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MLB extends Model
{
    private $apiBaseUrl = "https://api.sportsdata.io/v3/mlb";
    private $gameType = 'mlb';
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.apiKeys.mlb');
    }

    public function populateAll()
    {
        $this->populateStadiums();
        $this->populateTeams();
        $this->populateGames();
    }

    public function populateStadiums()
    {
        $stadiums = Http::withHeaders(['Ocp-Apim-Subscription-Key' => $this->apiKey])
                    ->get("{$this->apiBaseUrl}/scores/json/Stadiums")
                    ->body();

        // The response needs to be checked for success first

        $mappedStadiums = collect(json_decode($stadiums))->map(function ($stadium) {
            $date = now();

            $output = [
                'StadiumType' => $this->gameType,
                'StadiumID' => $stadium->StadiumID,
                'Name' => $stadium->Name,
                'City' => $stadium->City,
                'Country' => $stadium->Country,
                'All' => json_encode($stadium),
                'created_at' => $date,
                'updated_at' => $date
            ];

            return $output;
        });

        DB::beginTransaction();

        try {
            DB::table('stadiums')->insert($mappedStadiums->toArray());
            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollback();

            return false;
        }
    }

    public function populateTeams()
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
            DB::table('teams')->insert($mappedTeams->toArray());
            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollback();

            return false;
        }
    }

    public function populateGames()
    {
        $currentSeason = Http::withHeaders(['Ocp-Apim-Subscription-Key' => $this->apiKey])
                            ->get("{$this->apiBaseUrl}/scores/json/CurrentSeason")
                            ->body();

        $apiSeason = json_decode($currentSeason)->ApiSeason;

        $gamesForTheSeason = Http::withHeaders(['Ocp-Apim-Subscription-Key' => $this->apiKey])
                                ->get("{$this->apiBaseUrl}/scores/json/Games/{$apiSeason}")
                                ->body();

        collect(json_decode($gamesForTheSeason))->chunk(100)->each(function ($chunk) {
            $mappedGames = $chunk->map(function ($game) {
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

            DB::beginTransaction();

            try {
                DB::table('games')->insert($mappedGames->toArray());
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
            }
        });
    }

    public function daily($day = null)
    {
        $day = $day ?? now();

        $day = $day->format('Y-M-d');

        $gamesToday = Http::withHeaders(['Ocp-Apim-Subscription-Key' => $this->apiKey])
                        ->get("{$this->apiBaseUrl}/scores/json/GamesByDate/{$day}")
                        ->body();

        $mappedGames = collect(json_decode($gamesToday))->map(function ($game) {
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

        $mappedGames->each(function ($game) {
            DB::table('games')
                ->updateOrInsert(
                    ['GlobalGameID' => $game['GlobalGameID']],
                    $game
                );
        });

        $gamesFromDb = Game::whereIn(
            'GlobalGameID',
            $mappedGames->map(
                function ($game) {
                    return $game['GlobalGameID'];
                }
            )->toArray()
        )->get();
    }
}
