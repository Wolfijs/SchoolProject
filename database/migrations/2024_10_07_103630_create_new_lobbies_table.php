<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewLobbiesTable extends Migration
{
    public function up()
    {
        // Drop the lobbies table if it exists
        Schema::dropIfExists('lobbies');

        // Create a new lobbies table
        Schema::create('lobbies', function (Blueprint $table) {
            $table->id();
            $table->string('game_name');
            $table->string('skill_level');
            $table->string('playstyle');
            $table->string('region');
            $table->text('description');
            $table->integer('max_players');
            $table->string('creator_nickname'); // Ensure this line is present
            $table->integer('players_count')->default(0);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        // Drop the lobbies table when rolling back the migration
        Schema::dropIfExists('lobbies');
    }
}
