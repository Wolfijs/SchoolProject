<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LobbyOptionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('lobby_options')->insert([
            [
                'game_name' => 'Fortnite',
                'skill_level' => 'Beginner',
                'playstyle' => 'Competitive',
                'region' => 'North America',
                'description' => 'Squad-based competitive play.',
            ],
            [
                'game_name' => 'Fortnite',
                'skill_level' => 'Intermediate',
                'playstyle' => 'Casual',
                'region' => 'North America',
                'description' => 'Fun matches with friends.',
            ],
            [
                'game_name' => 'CS 2',
                'skill_level' => 'Advanced',
                'playstyle' => 'Competitive',
                'region' => 'Europe',
                'description' => 'High-stakes competitive games.',
            ],
            [
                'game_name' => 'Apex Legends',
                'skill_level' => 'Advanced',
                'playstyle' => 'Ranked',
                'region' => 'Asia',
                'description' => 'Ranked play to climb the leaderboard.',
            ],
            [
                'game_name' => 'Call of Duty: Warzone',
                'skill_level' => 'Intermediate',
                'playstyle' => 'Battle Royale',
                'region' => 'South America',
                'description' => 'Battle royale matches for squad play.',
            ],
            [
                'game_name' => 'NBA 2K25',
                'skill_level' => 'Beginner',
                'playstyle' => 'Casual',
                'region' => 'North America',
                'description' => 'Casual games for fun and practice.',
            ],
            [
                'game_name' => 'NBA 2K25',
                'skill_level' => 'Advanced',
                'playstyle' => 'Competitive',
                'region' => 'North America',
                'description' => 'Competitive matches in the league.',
            ],
            [
                'game_name' => 'Rocket League',
                'skill_level' => 'Intermediate',
                'playstyle' => 'Casual',
                'region' => 'Europe',
                'description' => 'Fun and competitive car soccer.',
            ],
            [
                'game_name' => 'Valorant',
                'skill_level' => 'Advanced',
                'playstyle' => 'Competitive',
                'region' => 'Asia',
                'description' => 'Team-based tactical shooter.',
            ],
        ]);
    }
}
