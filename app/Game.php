<?php

namespace App;

use App\Facades\JSONBuilder;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
	protected $casts = [
		'Date' => 'datetime'
	];

    public function homeTeam()
    {
    	return $this->hasOne(Team::class, 'GlobalTeamID', 'GlobalHomeTeamID');
    }

    public function awayTeam()
    {
    	return $this->hasOne(Team::class, 'GlobalTeamID', 'GlobalAwayTeamID');
    }

    public function stadium()
    {
        return $this->hasOne(Stadium::class, 'StadiumID', 'StadiumID');
    }

    public function toArray()
    {
        $builder = "App\Builders\\" . strtoupper($this->GameType) . "JSONBuilder";
        
        return $builder::buildFor($this);
    }
}
