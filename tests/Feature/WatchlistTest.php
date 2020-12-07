<?php

namespace Tests\Feature;

use App\Game;
use App\User;
use App\Watchlist;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WatchlistTest extends TestCase
{
    use RefreshDatabase;

    public function testUserAddingGamesToTheirWatchlist()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]);

        $response = $this->actingAs($user)->post('/api/watchlist', [
            'gameId' => 'TestGameID',
        ]);

        $response->assertStatus(201);
        $this->assertCount(1, $user->watchlist);
        $this->assertEquals('TestGameID', $user->watchlist->first()->game_id);
        $this->assertTrue(Watchlist::first()->user->is($user));
    }

    public function testFetchingGamesFromWatchlist()
    {
        $this->withoutExceptionHandling();

        // Setup
        $user = factory(User::class)->create([
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]);

        $this->actingAs($user)->post('/api/watchlist', [
            'gameId' => 'TestGameID',
        ]);

        // Condition
        $response = $this->get('/api/watchlist');

        // Assertions
        $response->assertStatus(200);
        tap($response->decodeResponseJson(), function ($games) {
            $this->assertCount(1, $games);
            $this->assertEquals('TestGameID', $games[0]['game_id']);
        });
    }
}
