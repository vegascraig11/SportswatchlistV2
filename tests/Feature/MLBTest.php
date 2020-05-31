<?php

namespace Tests\Feature;

use App\API\MLB;
use App\Game;
use App\Stadium;
use App\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MLBTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // We need to populate the databse with the MLB game data
        $this->mlb = new MLB();
        // $this->mlb->populateAll();
    }

    // These tests aren't extensive and should be worked on more.
    // These are basically testing if the function did "something."
    public function testPopulatingMLBStadiums()
    {
        $pre = Stadium::all();

        $this->mlb->populateStadiums();

        $after = Stadium::all();

        $this->assertCount(0, $pre);
        $this->assertCount(88, $after);
    }

    public function testPopulatingTeams()
    {
        $pre = Team::all();

        $this->mlb->populateTeams();

        $after = Team::all();

        $this->assertCount(0, $pre);
        $this->assertCount(35, $after);
    }

    public function testPopulatingGamesForTheSeason()
    {
        $pre = Game::all();

        $this->mlb->populateGames();

        $after = Game::all();

        $this->assertCount(0, $pre);
        $this->assertCount(2430, $after); // For 2020REG Season
    }

    public function testFetchingGamesByDate()
    {
        $this->withoutExceptionHandling();

        $this->mlb->populateStadiums();
        $this->mlb->populateTeams();
        $this->mlb->populateGames();

        $responseA = $this->getJson('/api/mlb/gamesByDate/2020-APR-26'); // there should be 15 games according to the schedule
        $responseB = $this->getJson('/api/mlb/gamesByDate/2020-JUN-10'); // there should be 15 games according to the schedule

        $responseA->assertStatus(200);
        $this->assertCount(15, $responseA->decodeResponseJson());
        $responseB->assertStatus(200);
        $this->assertCount(15, $responseB->decodeResponseJson());
    }
}
