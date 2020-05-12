<?php

namespace App\Builders;

use App\Builders\GameBuilder;
use Illuminate\Support\Facades\Cache;

class MLBGameBuilder extends GameBuilder {
    private $teams;
    private $stadiums;

    public function __construct()
    {
        $this->teams = Cache::get('mlb_teams');
        $this->stadiums = Cache::get('mlb_stadiums');
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
                'runs' => $game['HomeTeamRuns'],
                'errors' => $game['HomeTeamErrors'],
                'hits' => $game['HomeTeamHits'],
                'money_line' => $game['HomeTeamMoneyLine'],
                'point_spread_money_line' => $game['PointSpreadHomeTeamMoneyLine'],
                'logo' => $homeTeam['WikipediaLogoUrl']
            ],
            'away_team' => [
                'id' => $game['AwayTeamID'],
                'name' => $game['AwayTeam'],
                'full_name' => $awayTeam['City'].' '.$awayTeam['Name'],
                'rotation_number' => $game['AwayRotationNumber'],
                'runs' => $game['AwayTeamRuns'],
                'errors' => $game['AwayTeamErrors'],
                'hits' => $game['AwayTeamHits'],
                'money_line' => $game['AwayTeamMoneyLine'],
                'point_spread_money_line' => $game['PointSpreadAwayTeamMoneyLine'],
                'logo' => $awayTeam['WikipediaLogoUrl']
            ],
            'innings' => $game['Innings'],
            'stadium' => $stadium,
            'status' => $game['Status'],
            'winningPitcher' => $game['WinningPitcher'],
            'losingPitcher' => $game['LosingPitcher']
        ];
    }
}
