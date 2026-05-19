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
            $table->string('metode_pembayaran')->default('tunai')->after('slot_max');
            $table->decimal('iuran_per_pemain', 12, 2)->default(0)->after('metode_pembayaran');
        });

        Schema::table('event_participant', function (Blueprint $table) {
            $table->string('payment_method')->default('tunai')->after('hadir');
            $table->decimal('payment_amount', 12, 2)->default(0)->after('payment_method');
            $table->string('payment_status')->default('pending')->after('payment_amount');
            $table->string('payment_reference')->nullable()->after('payment_status');
            $table->timestamp('payment_paid_at')->nullable()->after('payment_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_participant', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'payment_amount',
                'payment_status',
                'payment_reference',
                'payment_paid_at',
            ]);
        });

        Schema::table('community_events', function (Blueprint $table) {
            $table->dropColumn([
                'metode_pembayaran',
                'iuran_per_pemain',
            ]);
        });
    }
};
