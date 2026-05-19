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
        Schema::table('futsal_matches', function (Blueprint $table) {
            $table->string('kategori')->default('Futsal')->after('nama_match');
            $table->text('link_maps')->nullable()->after('tempat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('futsal_matches', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'link_maps']);
        });
    }
};
