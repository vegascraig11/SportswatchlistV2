<?php

namespace Tests\Feature;

use App\API\NFLGames;
use App\Stadium;
use App\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NFLGamesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // We need to populate the databse with the NFL game data
        $this->nfl = new NFLGames();
        $this->nfl->populateAll();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStadiumAndTeamRealtionship()
    {
        // Atlanta Falcons / API_ID 2
        // Mercedes-Benz Stadium / API_ID 45
        $team = Team::where('GlobalTeamID', 2)->first();

        $this->assertEquals($team->stadium->StadiumID, 45);
        $this->assertEquals($team->stadium->Name, 'Mercedes-Benz Stadium');
    }

    public function testFetchingGamesOnADay()
    {
        $this->withoutExceptionHandling();

        $responseA = $this->getJson('/api/nfl/gamesByDate/2020-SEP-10'); // there should be 1 game according to the 2020 schedule
        $responseB = $this->getJson('/api/nfl/gamesByDate/2020-SEP-13'); // there should be 13 games according to the 2020 schedule

        $responseA->assertStatus(200);
        $this->assertCount(1, $responseA->decodeResponseJson());
        $responseB->assertStatus(200);
        $this->assertCount(13, $responseB->decodeResponseJson());
    }

    public function testDailyGameSyncing()
    {
        dd($this->nfl->dailySync());
    }
}
