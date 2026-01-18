<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nama_lengkap')) {
                $table->string('nama_lengkap', 150)->nullable()->after('username');
            }
            if (!Schema::hasColumn('users', 'nik')) {
                $table->string('nik', 20)->nullable()->after('nama_lengkap');
            }
            if (!Schema::hasColumn('users', 'alamat_lengkap')) {
                $table->text('alamat_lengkap')->nullable()->after('no_telephone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'alamat_lengkap')) {
                $table->dropColumn('alamat_lengkap');
            }
            if (Schema::hasColumn('users', 'nik')) {
                $table->dropColumn('nik');
            }
            if (Schema::hasColumn('users', 'nama_lengkap')) {
                $table->dropColumn('nama_lengkap');
            }
        });
    }
};
