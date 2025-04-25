<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LobbiesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('lobbies')->insert([
            [
                'game_name' => 'Fortnite',
                'skill_level' => 'Intermediate',
                'playstyle' => 'Competitive',
                'region' => 'North America',
                'creator_nickname' => 'Gamer123',
                'description' => 'Looking for skilled players to join my squad.',
                'max_players' => 5,
                'players_count' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'game_name' => 'CS 2',
                'skill_level' => 'Advanced',
                'playstyle' => 'Casual',
                'region' => 'Europe',
                'creator_nickname' => 'ProPlayer',
                'description' => 'Casual lobby for a few rounds of CS.',
                'max_players' => 6,
                'players_count' => 4,
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
            
        ]);
    }
}
