<?php

// tests/Feature/LobbyJoinTest.php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Lobby;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LobbyJoinTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_join_lobby_with_free_slots()
    {
        $user = User::factory()->create();
        $lobby = Lobby::factory()->create([
            'max_players' => 3
        ]);

        // Pievieno jau divus lietotājus, bet ne maksimumu
        $lobby->users()->attach(User::factory(2)->create());

        $response = $this->actingAs($user)->post(route('lobby.join', $lobby->id));

        $response->assertRedirect(); // Pārbauda, ka notiek redirect
        $this->assertDatabaseHas('lobby_user', [
            'user_id' => $user->id,
            'lobby_id' => $lobby->id,
        ]);
    }

    /** @test */
    public function user_cannot_join_full_lobby()
    {
        $user = User::factory()->create();
        $lobby = Lobby::factory()->create([
            'max_players' => 3
        ]);

        // Pievieno jau trīs lietotājus, lobby ir pilns
        $lobby->users()->attach(User::factory(3)->create());

        $response = $this->actingAs($user)->post(route('lobby.join', $lobby->id));

        $response->assertStatus(403); // Piemēram, ja atgriež forbidden
        $this->assertDatabaseMissing('lobby_user', [
            'user_id' => $user->id,
            'lobby_id' => $lobby->id,
        ]);
    }
}
