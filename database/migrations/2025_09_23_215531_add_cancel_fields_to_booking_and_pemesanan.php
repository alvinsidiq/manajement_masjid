<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            if (!Schema::hasColumn('booking', 'cancel_reason')) {
                $table->text('cancel_reason')->nullable()->after('hold_expires_at');
            }
            if (!Schema::hasColumn('booking', 'cancelled_at')) {
                $table->dateTime('cancelled_at')->nullable()->after('cancel_reason');
            }
            $table->index(['cancelled_at']);
        });

        Schema::table('pemesanan', function (Blueprint $table) {
            if (!Schema::hasColumn('pemesanan', 'cancel_reason')) {
                $table->text('cancel_reason')->nullable()->after('alasan_penolakan');
            }
            // cancelled_at is already added by 2025_01_07_000000_alter_pemesanan_add_cancel_fields.php
            // No need to add it here.
            // $table->dateTime('cancelled_at')->nullable()->after('cancel_reason');
            // $table->index(['cancelled_at']); // Index might already exist or be part of a composite index
        });
    }

    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            if (Schema::hasColumn('booking', 'cancel_reason')) {
                $table->dropColumn('cancel_reason');
            }
            if (Schema::hasColumn('booking', 'cancelled_at')) {
                $table->dropIndex(['cancelled_at']);
                $table->dropColumn('cancelled_at');
            }
        });
        Schema::table('pemesanan', function (Blueprint $table) {
            if (Schema::hasColumn('pemesanan', 'cancel_reason')) {
                $table->dropColumn('cancel_reason');
            }
            // Do not drop cancelled_at or its index as it's managed by another migration
        });
    }
};