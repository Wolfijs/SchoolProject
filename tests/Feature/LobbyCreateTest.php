<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LobbyCreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_create_lobby()
    {
        $user = User::factory()->create();

        $lobbyData = [
            'name' => 'Test Lobby',
            'max_players' => 4,
        ];

        $response = $this->actingAs($user)->post(route('lobby.store'), $lobbyData);

        $response->assertRedirect(); 
        $this->assertDatabaseHas('lobbies', [
            'name' => 'Test Lobby',
            'max_players' => 4,
        ]);
    }

    /** @test */
    public function guest_cannot_create_lobby()
    {
        $lobbyData = [
            'name' => 'Guest Lobby',
            'max_players' => 5,
        ];

        $response = $this->post(route('lobby.store'), $lobbyData);

        $response->assertRedirect(route('login')); 
        $this->assertDatabaseMissing('lobbies', [
            'name' => 'Guest Lobby',
        ]);
    }
}
