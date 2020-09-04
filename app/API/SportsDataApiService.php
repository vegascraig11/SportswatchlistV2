<?php

namespace App\API;

abstract class SportsDataApiService
{
    public abstract function getTeams();

    public abstract function getStadiums();

    public abstract function getGamesBySeason($season = null);

    public abstract function getGamesByDate($date = null);

    public abstract function getBoxScoreById($gameId);

    public abstract function getBoxScoresByDate($date = null);
}
