<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lobby extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_name',
        'skill_level',
        'playstyle',
        'region',
        'description',
        'max_players',
        'players_count',
        'user_id',
    ];

    // Define the relationship with the user who created the lobby
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the many-to-many relationship with users who joined the lobby
    public function players()
    {
        return $this->belongsToMany(User::class, 'lobby_user', 'lobby_id', 'user_id'); // Adjust 'lobby_user' if your pivot table name is different
    }
}
