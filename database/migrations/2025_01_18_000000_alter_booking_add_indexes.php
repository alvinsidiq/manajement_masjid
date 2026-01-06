<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Tambahkan index hanya jika belum ada (idempotent)
        if (! $this->indexExists('booking', 'booking_user_id_status_index')) {
            Schema::table('booking', function (Blueprint $table) {
                $table->index(['user_id','status']);
            });
        }
        if (! $this->indexExists('booking', 'booking_ruangan_id_hari_tanggal_index')) {
            Schema::table('booking', function (Blueprint $table) {
                $table->index(['ruangan_id','hari_tanggal']);
            });
        }
        if (! $this->indexExists('booking', 'booking_hold_expires_at_index')) {
            Schema::table('booking', function (Blueprint $table) {
                $table->index(['hold_expires_at']);
            });
        }
        if (! $this->indexExists('booking', 'uniq_booking_slot_status')) {
            Schema::table('booking', function (Blueprint $table) {
                $table->unique(['ruangan_id','hari_tanggal','jam','status'], 'uniq_booking_slot_status');
            });
        }
    }

    public function down(): void
    {
        if ($this->indexExists('booking', 'booking_user_id_status_index')) {
            Schema::table('booking', function (Blueprint $table) {
                $table->dropIndex('booking_user_id_status_index');
            });
        }
        if ($this->indexExists('booking', 'booking_ruangan_id_hari_tanggal_index')) {
            Schema::table('booking', function (Blueprint $table) {
                $table->dropIndex('booking_ruangan_id_hari_tanggal_index');
            });
        }
        if ($this->indexExists('booking', 'booking_hold_expires_at_index')) {
            Schema::table('booking', function (Blueprint $table) {
                $table->dropIndex('booking_hold_expires_at_index');
            });
        }
        if ($this->indexExists('booking', 'uniq_booking_slot_status')) {
            Schema::table('booking', function (Blueprint $table) {
                $table->dropUnique('uniq_booking_slot_status');
            });
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $db = DB::getDatabaseName();
        $row = DB::selectOne(
            'SELECT 1 FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ? LIMIT 1',
            [$db, $table, $indexName]
        );
        return (bool) $row;
    }
};
