<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLobbyUserTable extends Migration
{
    public function up()
    {
        Schema::create('lobby_user', function (Blueprint $table) {
            $table->id(); // Optional: add an auto-incrementing primary key
            $table->foreignId('lobby_id')->constrained()->onDelete('cascade'); // Reference to lobbies table
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Reference to users table
            $table->timestamps(); // Optional: to track created and updated timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('lobby_user');
    }
}
