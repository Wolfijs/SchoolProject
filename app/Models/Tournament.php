<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'game',
        'platform',
        'external_link',
        'max_players',
        'current_players',
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'tournament_participants')
            ->withTimestamps();
    }

    public function isFull()
    {
        return $this->current_players >= $this->max_players;
    }

    public function hasParticipant(User $user)
    {
        return $this->participants()->where('user_id', $user->id)->exists();
    }
} 