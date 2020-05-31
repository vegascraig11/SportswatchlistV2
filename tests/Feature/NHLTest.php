<?php

namespace Tests\Feature;

use App\API\NHL;
use App\Game;
use App\Stadium;
use App\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NHLTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // We need to populate the databse with the NHL game data
        $this->nhl = new NHL();
        // $this->nhl->populateAll();
    }

    // These tests aren't extensive and should be worked on more.
    // These are basically testing if the function did "something."
    public function testPopulatingStadiums()
    {
        $pre = Stadium::all();

        $this->nhl->populateStadiums();

        $after = Stadium::all();

        $this->assertCount(0, $pre);
        $this->assertCount(35, $after);
    }

    public function testPopulatingTeams()
    {
        $pre = Team::all();

        $this->nhl->populateTeams();

        $after = Team::all();

        $this->assertCount(0, $pre);
        $this->assertCount(35, $after);
    }

    public function testPopulatingGamesForTheSeason()
    {
        $pre = Game::all();

        $this->nhl->populateGames();

        $after = Game::all();

        $this->assertCount(0, $pre);
        $this->assertCount(1272, $after); // For 2020REG Season
    }

    public function testFetchingGamesByDate()
    {
        $this->withoutExceptionHandling();

        $this->nhl->populateStadiums();
        $this->nhl->populateTeams();
        $this->nhl->populateGames();

        $responseA = $this->getJson('/api/nhl/gamesByDate/2019-DEC-28'); // there should be 9 games according to the schedule
        $responseB = $this->getJson('/api/nhl/gamesByDate/2020-FEB-29'); // there should be 12 games according to the schedule

        $responseA->assertStatus(200);
        $this->assertCount(9, $responseA->decodeResponseJson());
        $responseB->assertStatus(200);
        $this->assertCount(12, $responseB->decodeResponseJson());
    }
}
