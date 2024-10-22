<?php

namespace App\Events;

use App\Models\Lobby;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LobbyUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // The lobby instance that will be broadcasted
    public $lobby;

    // Constructor to initialize the event with the lobby instance
    public function __construct(Lobby $lobby)
    {
        $this->lobby = $lobby;
    }

    // Define the channel on which the event will be broadcasted
    public function broadcastOn()
    {
        // Broadcasting to a specific lobby channel based on the lobby ID
        return new Channel('lobby.' . $this->lobby->id);
    }

    // Define the data that will be broadcasted with the event
    public function broadcastWith()
    {
        // Include relevant lobby data in the broadcast
        return [
            'id' => $this->lobby->id,
            'game_name' => $this->lobby->game_name,  // Game name of the lobby
            'players_count' => $this->lobby->players_count,  // Current players count
            'max_players' => $this->lobby->max_players,  // Maximum number of players
        ];
    }
}
