<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
  protected $guarded = [];

  protected $dates = ['game_time'];

  protected $with = ['game'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function game()
  {
  	return $this->hasOne(Game::class, 'GlobalGameID', 'game_id');
  }
}
