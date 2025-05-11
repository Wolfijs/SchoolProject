<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register broadcasting routes with web and auth middleware
        Broadcast::routes(['middleware' => ['web', 'auth']]);

        // Authorize lobby channels
        Broadcast::channel('lobby.{lobbyId}', function ($user, $lobbyId) {
            $lobby = \App\Models\Lobby::find($lobbyId);
            if (!$lobby) {
                return false;
            }
            return $lobby->players()->where('user_id', $user->id)->exists();
        });
    }
} 