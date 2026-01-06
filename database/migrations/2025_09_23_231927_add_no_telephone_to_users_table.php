<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users','no_telephone')) {
                $table->string('no_telephone', 25)->nullable()->after('email');
            }
            // Ensure username column exists, but do not add unique index here
            if (!Schema::hasColumn('users','username')) {
                $table->string('username')->after('user_id');
            }

            // Add index for no_telephone if it doesn't exist
            // Laravel's Schema::table will automatically check if an index with the same columns exists
            // and won't re-add it if it does.
            $table->index(['no_telephone']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users','no_telephone')) {
                $table->dropIndex(['no_telephone']);
                $table->dropColumn('no_telephone');
            }
        });
    }
};