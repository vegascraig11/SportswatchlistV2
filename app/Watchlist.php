<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
  protected $guarded = [];

  protected $with = ['game'];

  protected $casts = [
    'settings' => 'json',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function game()
  {
  	return $this->hasOne(Game::class, 'GlobalGameID', 'game_id');
  }
}
