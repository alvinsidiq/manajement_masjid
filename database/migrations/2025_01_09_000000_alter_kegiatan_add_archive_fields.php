<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false)->index()->after('no_telephone');
            $table->dateTime('archived_at')->nullable()->after('is_archived');
            $table->unsignedInteger('archived_by')->nullable()->after('archived_at');
            $table->text('archive_reason')->nullable()->after('archived_by');
            $table->foreign('archived_by')->references('user_id')->on('users')->onDelete('set null');
            $table->index(['archived_at']);
        });
    }

    public function down(): void
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->dropForeign(['archived_by']);
            $table->dropIndex(['archived_at']);
            $table->dropColumn(['is_archived','archived_at','archived_by','archive_reason']);
        });
    }
};

