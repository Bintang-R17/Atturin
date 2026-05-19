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
        Schema::table('community_events', function (Blueprint $table) {
            $table->boolean('show_joined_players_public')->default(true)->after('iuran_per_pemain');
            $table->boolean('show_joined_player_contacts_public')->default(false)->after('show_joined_players_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('community_events', function (Blueprint $table) {
            $table->dropColumn([
                'show_joined_players_public',
                'show_joined_player_contacts_public',
            ]);
        });
    }
};
