<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            if (! Schema::hasColumn('kegiatan', 'approval_status')) {
                $table->enum('approval_status', ['pending','approved','rejected'])
                    ->default('pending')
                    ->after('jenis_kegiatan')
                    ->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            if (Schema::hasColumn('kegiatan', 'approval_status')) {
                $table->dropColumn('approval_status');
            }
        });
    }
};
