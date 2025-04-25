<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        // Check if the 'users' table exists
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('nickname')->nullable(); // Add this to your users table migration
                $table->string('email')->unique(); // User's email
                $table->string('password'); // User's password
                $table->timestamps(); // Timestamps
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
