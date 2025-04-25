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
        'creator_nickname',
        'players_count',
        'photo',
        'user_id'
    ];

    // Relationship with the creator user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with all players in the lobby
    public function players()
    {
        return $this->belongsToMany(User::class, 'lobby_user', 'lobby_id', 'user_id');
    }

    // Messages in this lobby
    // public function messages()
    // {
    //     return $this->hasMany(Message::class);
    // }
    //     public function chatMessages()
    // {
    //     return $this->hasMany(LobbyChatMessage::class);
    // }
    public function messages()
{
    return $this->hasMany(LobbyMessage::class);
}

    

    
}