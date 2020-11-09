<?php

namespace App\Builders;

use App\Game;

class MLBJSONBuilder implements JSONBuilder
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
                'runs' => $all->HomeTeamRuns,
                'errors' => $all->HomeTeamErrors,
                'hits' => $all->HomeTeamHits,
                'money_line' => $all->HomeTeamMoneyLine,
                'point_spread_money_line' => $all->PointSpreadHomeTeamMoneyLine,
                'logo' => $game->homeTeam->logo
            ],
            'away_team' => [
                'id' => $all->AwayTeamID,
                'name' => $all->AwayTeam,
                'full_name' => $game->awayTeam->fullName,
                'rotation_number' => $all->AwayRotationNumber,
                'runs' => $all->AwayTeamRuns,
                'errors' => $all->AwayTeamErrors,
                'hits' => $all->AwayTeamHits,
                'money_line' => $all->AwayTeamMoneyLine,
                'point_spread_money_line' => $all->PointSpreadAwayTeamMoneyLine,
                'logo' => $game->awayTeam->logo
            ],
            'inning' => $all->Inning,
            'innings' => $all->Innings,
            'stadium' => $game->stadium,
            'status' => $all->Status,
            'current_pitcher' => $all->CurrentPitcher,
            'current_hitter' => $all->CurrentHitter,
            'winningPitcher' => $all->WinningPitcher,
            'losingPitcher' => $all->LosingPitcher,
            'over_under' => $all->OverUnder,
            'point_spread' => $all->PointSpread,
        ];
	}
}