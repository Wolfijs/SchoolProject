<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{

    public function test_user_can_update_profile_with_valid_data()
    {
        $user = User::factory()->create([
            'password' => Hash::make('OldPassword123!'),
        ]);

        $this->actingAs($user);

        $response = $this->put(route('profile.update'), [
            'name' => 'Jauns Vārds',
            'email' => 'new@example.com',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('status', 'Profils veiksmigi atjaunināts!');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Jauns Vārds',
            'email' => 'new@example.com',
        ]);
    }

    public function test_user_cannot_update_profile_with_invalid_data()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->from(route('profile.edit'))->put(route('profile.update'), [
            'name' => '', // Invalid: required
            'email' => '', // Invalid: required
            'password' => 'abc', // Invalid: too weak
            'password_confirmation' => 'abc',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHasErrors(['name', 'email', 'password']);

        $this->assertDatabaseMissing('users', [
            'name' => '',
            'email' => '',
        ]);
    }
}
