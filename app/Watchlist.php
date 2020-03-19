<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
  protected $guarded = [];

  protected $dates = ['game_time'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
