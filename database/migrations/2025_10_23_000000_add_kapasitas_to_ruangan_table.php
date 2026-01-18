<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ruangan', function (Blueprint $table) {
            if (!Schema::hasColumn('ruangan', 'kapasitas')) {
                $table->unsignedInteger('kapasitas')->default(0)->after('nama_ruangan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ruangan', function (Blueprint $table) {
            if (Schema::hasColumn('ruangan', 'kapasitas')) {
                $table->dropColumn('kapasitas');
            }
        });
    }
};
