<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LobbyChatMessage extends Model
{
    protected $table = 'lobby_chat_messages';

    protected $fillable = [
        'content',
        'user_id',
        'lobby_id',
    ];
}
