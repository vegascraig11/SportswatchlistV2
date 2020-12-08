<?php

namespace App\Builders;

use App\Game;

class NCAABJSONBuilder implements JSONBuilder
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
                'full_name' => is_null($game->homeTeam) ? $all->HomeTeam : $game->homeTeam->fullName,
                'rotation_number' => $all->HomeRotationNumber,
                'score' => $all->HomeTeamScore,
                'money_line' => $all->HomeTeamMoneyLine,
                'logo' => is_null($game->homeTeam) ? '' : $game->homeTeam->logo,
            ],
            'away_team' => [
                'id' => $all->AwayTeamID,
                'name' => $all->AwayTeam,
                'full_name' => is_null($game->awayTeam) ? $all->AwayTeam : $game->awayTeam->fullName,
                'rotation_number' => $all->AwayRotationNumber,
                'score' => $all->AwayTeamScore,
                'money_line' => $all->AwayTeamMoneyLine,
                'logo' => is_null($game->awayTeam) ? '' : $game->awayTeam->logo
            ],
            'over_under' => $all->OverUnder,
            'period' => $all->Period,
            'periods' => $all->Periods,
            'stadium' => $all->Stadium,
            'status' => $all->Status,
            'over_under' => $all->OverUnder,
            'point_spread' => $all->PointSpread,
            'time_remaining_seconds' => $all->TimeRemainingSeconds,
            'time_remaining_minutes' => $all->TimeRemainingMinutes,
        ];
	}
}