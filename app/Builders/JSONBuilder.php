<?php

namespace App\Builders;

use App\Game;

interface JSONBuilder
{
	public static function buildFor(Game $game);
}