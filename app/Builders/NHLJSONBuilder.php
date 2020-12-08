<?php

namespace App\Builders;

use App\Game;

class NHLJSONBuilder implements JSONBuilder
{
	public static function buildFor(Game $game)
	{
		$all = json_decode($game->All);
		
		return [
            'id' => $game->id,
            'game_id' => $all->GlobalGameID,
            'game_time' => $all->DateTime,
            'game_type' => $game->GameType,
            'home_team' => [
                'id' => $all->HomeTeamID,
                'name' => $all->HomeTeam,
                'full_name' => $game->homeTeam->fullName,
                'rotation_number' => $all->HomeRotationNumber,
                'score' => $all->HomeTeamScore,
                'money_line' => $all->HomeTeamMoneyLine,
                'point_spread_money_line' => $all->PointSpreadHomeTeamMoneyLine,
                'logo' => $game->homeTeam->logo
            ],
            'away_team' => [
                'id' => $all->AwayTeamID,
                'name' => $all->AwayTeam,
                'full_name' => $game->awayTeam->fullName,
                'rotation_number' => $all->AwayRotationNumber,
                'score' => $all->AwayTeamScore,
                'money_line' => $all->AwayTeamMoneyLine,
                'point_spread_money_line' => $all->PointSpreadAwayTeamMoneyLine,
                'logo' => $game->awayTeam->logo
            ],
            'stadium' => $game->stadium,
            'status' => $all->Status,
            'periods' => $all->Periods,
            'period' => $all->Period,
            'over_under' => $all->OverUnder,
            'point_spread' => $all->PointSpread,
            'time_remaining_minutes' => $all->TimeRemainingMinutes,
            'time_remaining_seconds' => $all->TimeRemainingSeconds,
        ];
	}
}