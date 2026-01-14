<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE booking MODIFY status ENUM('hold','expired','submitted','cancelled','proses','setuju','tolak') NOT NULL DEFAULT 'hold'");
        }

        DB::table('booking')
            ->where('status', 'submitted')
            ->update(['status' => 'proses']);

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE booking MODIFY status ENUM('hold','proses','setuju','tolak','expired','cancelled') NOT NULL DEFAULT 'hold'");
        }
    }

    public function down(): void
    {
        DB::table('booking')
            ->whereIn('status', ['proses','setuju','tolak'])
            ->update(['status' => 'submitted']);

        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE booking MODIFY status ENUM('hold','expired','submitted','cancelled') NOT NULL DEFAULT 'hold'");
        }
    }
};
