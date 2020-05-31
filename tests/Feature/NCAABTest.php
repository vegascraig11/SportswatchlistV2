<?php

namespace Tests\Feature;

use App\API\NCAAB;
use App\Game;
use App\Stadium;
use App\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NCAABTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // We need to populate the databse with the NCAAB game data
        $this->ncaab = new NCAAB();
        // $this->ncaab->populateAll();
    }

    // These tests aren't extensive and should be worked on more.
    // These are basically testing if the function did "something."
    public function testPopulatingStadiums()
    {
        $pre = Stadium::all();

        $this->ncaab->populateStadiums();

        $after = Stadium::all();

        $this->assertCount(0, $pre);
        $this->assertCount(471, $after);
    }

    public function testPopulatingTeams()
    {
        $pre = Team::all();

        $this->ncaab->populateTeams();

        $after = Team::all();

        $this->assertCount(0, $pre);
        $this->assertCount(896, $after);
    }

    public function testPopulatingGamesForTheSeason()
    {
        $this->markTestIncomplete();
        
        $pre = Game::all();

        $this->ncaab->populateGames();

        $after = Game::all();

        $this->assertCount(0, $pre);
        $this->assertCount(1232, $after); // For 2020REG Season
    }

    public function testFetchingGamesByDate()
    {
        $this->withoutExceptionHandling();

        $this->ncaab->populateStadiums();
        $this->ncaab->populateTeams();
        $this->ncaab->populateGames();

        $responseA = $this->getJson('/api/ncaab/gamesByDate/2019-OCT-22'); // there should be 2 games according to the schedule
        $responseB = $this->getJson('/api/ncaab/gamesByDate/2019-OCT-26'); // there should be 10 games according to the schedule

        $responseA->assertStatus(200);
        $this->assertCount(2, $responseA->decodeResponseJson());
        $responseB->assertStatus(200);
        $this->assertCount(10, $responseB->decodeResponseJson());
    }
}
