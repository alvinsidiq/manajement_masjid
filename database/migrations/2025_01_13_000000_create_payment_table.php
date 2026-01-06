<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->bigIncrements('payment_id');
            $table->unsignedBigInteger('pemesanan_id');
            $table->enum('gateway', ['manual','midtrans','xendit'])->default('manual')->index();
            $table->string('method')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('currency', 8)->default('IDR');
            $table->enum('status', ['pending','paid','failed','expired','refunded'])->default('pending')->index();
            $table->string('external_ref')->nullable()->index();
            $table->text('snap_url_or_qris')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->json('payload_raw')->nullable();
            $table->timestamps();

            $table->foreign('pemesanan_id')->references('pemesanan_id')->on('pemesanan')->onDelete('cascade');
            $table->index(['pemesanan_id','created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};

