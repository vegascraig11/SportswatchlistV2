<?php

namespace Tests\Feature;

use App\API\NCAAF;
use App\Game;
use App\Stadium;
use App\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NCAAFTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // We need to populate the databse with the NCAAF game data
        $this->ncaaf = new NCAAF();
        // $this->ncaaf->populateAll();
    }

    // These tests aren't extensive and should be worked on more.
    // These are basically testing if the function did "something."
    public function testPopulatingStadiums()
    {
        $pre = Stadium::all();

        $this->ncaaf->populateStadiums();

        $after = Stadium::all();

        $this->assertCount(0, $pre);
        $this->assertCount(198, $after);
    }

    public function testPopulatingTeams()
    {
        $pre = Team::all();

        $this->ncaaf->populateTeams();

        $after = Team::all();

        $this->assertCount(0, $pre);
        $this->assertCount(247, $after);
    }

    public function testPopulatingGamesForTheSeason()
    {
        $pre = Game::all();

        $this->ncaaf->populateGames();

        $after = Game::all();

        $this->assertCount(0, $pre);
        $this->assertCount(840, $after); // For 2020REG Season
    }

    public function testFetchingGamesByDate()
    {
        $this->withoutExceptionHandling();

        $this->ncaaf->populateStadiums();
        $this->ncaaf->populateTeams();
        $this->ncaaf->populateGames();

        $responseA = $this->getJson('/api/ncaaf/gamesByDate/2020-SEP-26'); // there should be 59 games according to the schedule
        $responseB = $this->getJson('/api/ncaaf/gamesByDate/2020-NOV-07'); // there should be 53 games according to the schedule

        $responseA->assertStatus(200);
        $this->assertCount(59, $responseA->decodeResponseJson());
        $responseB->assertStatus(200);
        $this->assertCount(53, $responseB->decodeResponseJson());
    }
}
