<?php

namespace App\Builders;

use App\Game;
use Illuminate\Support\Str;

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
            'point_spread' => $all->PointSpread,
            'quarter' => $all->Quarter,
            'quarter_description' => $all->QuarterDescription,
            'time_remaining' => $all->TimeRemaining,
            'quarters' => [
                [
                    'Name' => '1',
                    'QuarterID' => Str::random(4),
                    'AwayScore' => $all->AwayScoreQuarter1,
                    'HomeScore' => $all->HomeScoreQuarter1,
                ],
                [
                    'Name' => '2',
                    'QuarterID' => Str::random(4),
                    'AwayScore' => $all->AwayScoreQuarter2,
                    'HomeScore' => $all->HomeScoreQuarter2,
                ],
                [
                    'Name' => '3',
                    'QuarterID' => Str::random(4),
                    'AwayScore' => $all->AwayScoreQuarter3,
                    'HomeScore' => $all->HomeScoreQuarter3,
                ],
                [
                    'Name' => '4',
                    'QuarterID' => Str::random(4),
                    'AwayScore' => $all->AwayScoreQuarter4,
                    'HomeScore' => $all->HomeScoreQuarter4,
                ],
                [
                    'Name' => 'OT',
                    'QuarterID' => Str::random(4),
                    'AwayScore' => $all->AwayScoreOvertime,
                    'HomeScore' => $all->HomeScoreOvertime,
                ],
            ],
            'has_started' => $all->HasStarted,
            'possession' => $all->Possession,
            'down_and_distance' => $all->DownAndDistance,
            'yard_line' => $all->YardLine,
            'yard_line_territory' => $all->YardLineTerritory,
        ];
	}
}