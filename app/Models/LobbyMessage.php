<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LobbyMessage extends Model
{
    use HasFactory;

    protected $table = 'lobby_messages'; // Just in case

    protected $fillable = [
        'user_id',
        'lobby_id',
        'message'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lobby()
    {
        return $this->belongsTo(Lobby::class);
    }
}
