<?php

namespace App\Builders;

use Illuminate\Support\Facades\Cache;

class NHLGameBuilder extends GameBuilder
{
    private $teams;
    private $stadiums;

    public function __construct()
    {
        $this->teams = Cache::get('nhl_teams');
        $this->stadiums = Cache::get('nhl_stadiums');
    }

    public function buildGame($game)
    {
        $homeTeam = $this->teams->where('TeamID', $game['HomeTeamID'])->first();
        $awayTeam = $this->teams->where('TeamID', $game['AwayTeamID'])->first();
        $stadium = $this->stadiums->where('StadiumID', $game['StadiumID'])->first();

        return [
            'game_id' => $game['GameID'],
            'game_time' => $game['DateTime'],
            'home_team' => [
                'id' => $game['HomeTeamID'],
                'name' => $game['HomeTeam'],
                'full_name' => $homeTeam['City'].' '.$homeTeam['Name'],
                'rotation_number' => $game['HomeRotationNumber'],
                'score' => $game['HomeTeamScore'],
                'money_line' => $game['HomeTeamMoneyLine'],
                'point_spread_money_line' => $game['PointSpreadHomeTeamMoneyLine'],
                'logo' => $homeTeam['WikipediaLogoUrl']
            ],
            'away_team' => [
                'id' => $game['AwayTeamID'],
                'name' => $game['AwayTeam'],
                'full_name' => $awayTeam['City'].' '.$awayTeam['Name'],
                'rotation_number' => $game['AwayRotationNumber'],
                'score' => $game['AwayTeamScore'],
                'money_line' => $game['AwayTeamMoneyLine'],
                'point_spread_money_line' => $game['PointSpreadAwayTeamMoneyLine'],
                'logo' => $awayTeam['WikipediaLogoUrl']
            ],
            'stadium' => $stadium,
            'status' => $game['Status']
        ];
    }
}