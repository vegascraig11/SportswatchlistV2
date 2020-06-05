<?php

namespace App;

use App\Facades\JSONBuilder;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
	protected $casts = [
		'Date' => 'datetime'
	];

    protected $appends = ['stadium'];

    public function homeTeam()
    {
    	return $this->hasOne(Team::class, 'GlobalTeamID', 'GlobalHomeTeamID');
    }

    public function awayTeam()
    {
    	return $this->hasOne(Team::class, 'GlobalTeamID', 'GlobalAwayTeamID');
    }

    public function stadiums()
    {
        return $this->hasMany(Stadium::class, 'StadiumID', 'StadiumID');
    }

    public function getStadiumAttribute()
    {
        return $this->stadiums->where('StadiumType', $this->GameType)->first();
    }

    public function toArray()
    {
        $builder = "App\Builders\\" . strtoupper($this->GameType) . "JSONBuilder";
        
        return $builder::buildFor($this);
    }
}
