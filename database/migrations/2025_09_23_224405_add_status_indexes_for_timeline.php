<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->index(['user_id','status','created_at'], 'pemesanan_user_status_created_idx');
        });
        Schema::table('booking', function (Blueprint $table) {
            $table->index(['user_id','status','created_at'], 'booking_user_status_created_idx');
        });
    }

    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->dropIndex('pemesanan_user_status_created_idx');
        });
        Schema::table('booking', function (Blueprint $table) {
            $table->dropIndex('booking_user_status_created_idx');
        });
    }
};