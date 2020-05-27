<?php

namespace Tests\Feature;

use App\API\NBA;
use App\Game;
use App\Stadium;
use App\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NBATest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // We need to populate the databse with the NBA game data
        $this->nba = new NBA();
        // $this->nba->populateAll();
    }

    // These tests aren't extensive and should be worked on more.
    // These are basically testing if the function did "something."
    public function testPopulatingNBAStadiums()
    {
        $pre = Stadium::all();

        $this->nba->populateStadiums();

        $after = Stadium::all();

        $this->assertCount(0, $pre);
        $this->assertCount(52, $after);
    }

    public function testPopulatingTeams()
    {
        $pre = Team::all();

        $this->nba->populateTeams();

        $after = Team::all();

        $this->assertCount(0, $pre);
        $this->assertCount(32, $after);
    }

    public function testPopulatingGamesForTheSeason()
    {
        $pre = Game::all();

        $this->nba->populateGames();

        $after = Game::all();

        $this->assertCount(0, $pre);
        $this->assertCount(1232, $after); // For 2020REG Season
    }

    public function testFetchingGamesByDate()
    {
        $this->withoutExceptionHandling();

        $this->nba->populateStadiums();
        $this->nba->populateTeams();
        $this->nba->populateGames();

        $responseA = $this->getJson('/api/nba/gamesByDate/2019-OCT-22'); // there should be 2 games according to the schedule
        $responseB = $this->getJson('/api/nba/gamesByDate/2019-OCT-26'); // there should be 10 games according to the schedule

        $responseA->assertStatus(200);
        $this->assertCount(2, $responseA->decodeResponseJson());
        $responseB->assertStatus(200);
        $this->assertCount(10, $responseB->decodeResponseJson());
    }
}
