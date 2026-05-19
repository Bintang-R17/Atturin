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
        if (!Schema::hasColumn('community_events', 'kategori')) {
            Schema::table('community_events', function (Blueprint $table) {
                $table->string('kategori')->default('Futsal')->after('nama_event');
            });
        }

        if (!Schema::hasColumn('community_events', 'link_maps')) {
            Schema::table('community_events', function (Blueprint $table) {
                $table->text('link_maps')->nullable()->after('tempat');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $dropColumns = [];
        if (Schema::hasColumn('community_events', 'kategori')) {
            $dropColumns[] = 'kategori';
        }
        if (Schema::hasColumn('community_events', 'link_maps')) {
            $dropColumns[] = 'link_maps';
        }

        if ($dropColumns) {
            Schema::table('community_events', function (Blueprint $table) use ($dropColumns) {
                $table->dropColumn($dropColumns);
            });
        }
    }
};
