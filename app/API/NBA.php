<?php

namespace App\API;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class NBA extends Model
{
	private $apiBaseUrl = "https://api.sportsdata.io/v3/nba";
	private $gameType = 'nba';
	private $apiKey;

	public function __construct()
	{
		$this->apiKey = config('services.apiKeys.nba');
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
}
