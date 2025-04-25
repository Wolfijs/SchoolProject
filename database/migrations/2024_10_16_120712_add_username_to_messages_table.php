<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the 'username' column does not exist before adding
        if (!Schema::hasColumn('messages', 'username')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->string('username')->after('content'); // Adjust the order as needed
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Remove the username column if it exists
            if (Schema::hasColumn('messages', 'username')) {
                $table->dropColumn('username');
            }
        });
    }
};
