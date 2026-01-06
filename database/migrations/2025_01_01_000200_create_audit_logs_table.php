<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->nullable(); // actor (boleh null jika sistem)
            $table->string('action'); // e.g. booking.cancel, pemesanan.cancel
            $table->string('reference_type'); // Booking|Pemesanan
            $table->unsignedBigInteger('reference_id');
            $table->json('meta')->nullable(); // alasan, status lama/baru, ip, ua, dll
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id','action']);
            $table->index(['reference_type','reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};