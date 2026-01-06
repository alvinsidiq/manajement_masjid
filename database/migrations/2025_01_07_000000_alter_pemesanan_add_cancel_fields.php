<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->unsignedInteger('dibatalkan_oleh')->nullable()->after('alasan_penolakan');
            $table->text('alasan_pembatalan')->nullable()->after('dibatalkan_oleh');
            $table->dateTime('cancelled_at')->nullable()->after('alasan_pembatalan');

            $table->index(['status','cancelled_at']);
            $table->foreign('dibatalkan_oleh')->references('user_id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->dropForeign(['dibatalkan_oleh']);
            $table->dropColumn(['dibatalkan_oleh','alasan_pembatalan','cancelled_at']);
        });
    }
};

