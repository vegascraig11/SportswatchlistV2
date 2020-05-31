<?php

namespace App\Builders;

use App\Game;

class NCAAFJSONBuilder implements JSONBuilder
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
                'logo' => json_decode($game->homeTeam->All)->TeamLogoUrl
            ],
            'away_team' => [
                'id' => $all->AwayTeamID,
                'name' => $all->AwayTeam,
                'full_name' => $game->awayTeam->fullName,
                'rotation_number' => $all->AwayRotationNumber,
                'score' => $all->AwayTeamScore,
                'money_line' => $all->AwayTeamMoneyLine,
                'logo' => json_decode($game->awayTeam->All)->TeamLogoUrl
            ],
            'periods' => $all->Periods,
            'stadium' => $game->stadium,
            'status' => $all->Status
        ];
	}
}