<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lobbies', function (Blueprint $table) {
            $table->string('photo')->nullable(); // Add the photo column (nullable)
        });
    }
    
    public function down()
    {
        Schema::table('lobbies', function (Blueprint $table) {
            $table->dropColumn('photo'); // Remove the photo column if rolled back
        });
    }
    
};
