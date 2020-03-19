<?php

namespace Tests\Feature;

use App\User;
use App\Watchlist;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WatchlistTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_add_games_to_their_watchlist()
    {
        $this->withoutExceptionHandling();

        $gameId = '12345';
        $gameType = 'test-type';
        $user = factory(User::class)->create([
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]);

        $response = $this->actingAs($user)->post('/api/watchlist', [
            'gameId' => $gameId,
            'gameType' => $gameType,
            'gameTime' => Carbon::parse('Jan 12, 2020 6:00AM')
        ]);

        $response->assertStatus(201);
        $this->assertCount(1, $user->watchlist);
        $this->assertEquals($gameId, $user->watchlist->first()->game_id);
        $this->assertEquals($gameType, $user->watchlist->first()->game_type);
        $this->assertEquals('Jan 12, 2020 6:00AM', $user->watchlist->first()->game_time->format('M j, Y g:iA'));
        $this->assertTrue(Watchlist::first()->user->is($user));
    }
}
