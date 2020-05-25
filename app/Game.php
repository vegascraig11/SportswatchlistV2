<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
	protected $casts = [
		'Date' => 'datetime'
	];

    public function type()
    {
    	return $this->belongsTo(GameType::class, 'type_id');
    }

    public function homeTeam()
    {
    	return $this->hasOne(Team::class, 'GlobalTeamID', 'GlobalHomeTeamID');
    }

    public function awayTeam()
    {
    	return $this->hasOne(Team::class, 'GlobalTeamID', 'GlobalAwayTeamID');
    }

    public function toArray()
    {
    	$game = json_decode($this->All);

    	return [
    		'id' => $this->id,
    		'game_id' => $game->GlobalGameID,
    		'game_time' => $game->DateTime,
            'game_type' => $this->GameType,
    		'home_team' => [
    			'id' => $game->GlobalHomeTeamID,
    			'name' => $game->HomeTeam,
    			'full_name' => $this->homeTeam->fullName,
    			'rotation_number' => $game->HomeRotationNumber,
    			'score' => $game->HomeScore,
    			'money_line' => $game->HomeTeamMoneyLine,
    			'point_spread_money_line' => $game->PointSpreadHomeTeamMoneyLine,
    			'logo' => $this->homeTeam->logo
    		],
    		'away_team' => [
    			'id' => $game->GlobalAwayTeamID,
    			'name' => $game->AwayTeam,
    			'full_name' => $this->awayTeam->fullName,
    			'rotation_number' => $game->AwayRotationNumber,
    			'score' => $game->AwayScore,
    			'money_line' => $game->AwayTeamMoneyLine,
    			'point_spread_money_line' => $game->PointSpreadAwayTeamMoneyLine,
    			'logo' => $this->awayTeam->logo
    		],
    		'over_under' => $game->OverUnder,
    		'stadium' => $game->StadiumDetails,
    		'status' => $game->Status
    	];
    }
}
