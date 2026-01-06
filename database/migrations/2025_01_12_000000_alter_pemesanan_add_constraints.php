<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            if (!Schema::hasColumn('pemesanan', 'jadwal_id')) {
                $table->unsignedBigInteger('jadwal_id')->nullable()->after('ruangan_id');
            }
            if (!Schema::hasColumn('pemesanan', 'booking_id')) {
                $table->unsignedBigInteger('booking_id')->nullable()->after('jadwal_id');
            }

            // indexes may already exist from earlier sessions; skip to avoid duplicates

            // add foreign keys if not present
            try { $table->foreign('jadwal_id')->references('jadwal_id')->on('jadwal')->onDelete('set null'); } catch (\Throwable $e) {}
            try { $table->foreign('booking_id')->references('booking_id')->on('booking')->onDelete('set null'); } catch (\Throwable $e) {}
            try { $table->unique(['booking_id']); } catch (\Throwable $e) {}
        });
    }

    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            try { $table->dropUnique('pemesanan_booking_id_unique'); } catch (\Throwable $e) {}
            try { $table->dropForeign(['booking_id']); } catch (\Throwable $e) {}
            try { $table->dropForeign(['jadwal_id']); } catch (\Throwable $e) {}
            try { $table->dropForeign(['ruangan_id']); } catch (\Throwable $e) {}
            try { $table->dropForeign(['user_id']); } catch (\Throwable $e) {}
            try { $table->dropIndex(['status','created_at']); } catch (\Throwable $e) {}
            try { $table->dropIndex(['user_id','created_at']); } catch (\Throwable $e) {}
            try { $table->dropIndex(['ruangan_id','created_at']); } catch (\Throwable $e) {}
        });
    }
};
