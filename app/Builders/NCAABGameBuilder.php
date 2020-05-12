<?php

namespace App\Builders;

use App\Builders\GameBuilder;
use Illuminate\Support\Facades\Cache;

class NCAABGameBuilder extends GameBuilder
{
    private $teams;

    public function __construct()
    {
        $this->teams = Cache::get('ncaab_teams');
    }

    public function buildGame($game)
    {
        $homeTeam = $this->teams->where('TeamID', $game['HomeTeamID'])->first();
        $awayTeam = $this->teams->where('TeamID', $game['AwayTeamID'])->first();

        return [
            'game_id' => $game['GameID'],
            'game_time' => $game['DateTime'],
            'home_team' => [
                'id' => $game['HomeTeamID'],
                'name' => $game['HomeTeam'],
                'full_name' => $homeTeam['Name'],
                'rotation_number' => $game['HomeRotationNumber'],
                'score' => $game['HomeTeamScore'],
                'money_line' => $game['HomeTeamMoneyLine'],
                'logo' => $homeTeam['TeamLogoUrl']
            ],
            'away_team' => [
                'id' => $game['AwayTeamID'],
                'name' => $game['AwayTeam'],
                'full_name' => $awayTeam['Name'],
                'rotation_number' => $game['AwayRotationNumber'],
                'score' => $game['AwayTeamScore'],
                'money_line' => $game['AwayTeamMoneyLine'],
                'logo' => $awayTeam['TeamLogoUrl']
            ],
            'periods' => $game['Periods'],
            'stadium' => $game['Stadium'],
            'status' => $game['Status']
        ];
    }
}
