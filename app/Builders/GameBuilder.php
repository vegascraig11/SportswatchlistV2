<?php

namespace App\Builders;

abstract class GameBuilder {
    public function build($games)
    {
        return collect($games)->map(function ($game) {
            return $this->buildGame($game);
        });
    }

    public abstract function buildGame($game);
}
