<?php

namespace App\Builders;

use App\Game;

class NFLJSONBuilder implements JSONBuilder
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
                'id' => $all->GlobalHomeTeamID,
                'name' => $all->HomeTeam,
                'full_name' => $game->homeTeam->fullName,
                'rotation_number' => $all->HomeRotationNumber,
                'score' => $all->HomeScore,
                'money_line' => $all->HomeTeamMoneyLine,
                'point_spread_money_line' => $all->PointSpreadHomeTeamMoneyLine,
                'logo' => $game->homeTeam->logo
            ],
            'away_team' => [
                'id' => $all->GlobalAwayTeamID,
                'name' => $all->AwayTeam,
                'full_name' => $game->awayTeam->fullName,
                'rotation_number' => $all->AwayRotationNumber,
                'score' => $all->AwayScore,
                'money_line' => $all->AwayTeamMoneyLine,
                'point_spread_money_line' => $all->PointSpreadAwayTeamMoneyLine,
                'logo' => $game->awayTeam->logo
            ],
            'over_under' => $all->OverUnder,
            'stadium' => $all->StadiumDetails,
            'status' => $all->Status,
            'point_spread' => $all->PointSpread
        ];
	}
}