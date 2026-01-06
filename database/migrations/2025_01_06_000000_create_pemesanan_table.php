<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->bigIncrements('pemesanan_id');
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('ruangan_id');
            $table->unsignedBigInteger('jadwal_id')->nullable();
            $table->unsignedBigInteger('booking_id')->nullable();

            $table->string('tujuan_pemesanan');
            $table->enum('status', ['menunggu_verifikasi','diterima','ditolak','dibatalkan','selesai'])
                  ->default('menunggu_verifikasi')->index();
            $table->text('catatan')->nullable();
            $table->text('alasan_penolakan')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('ruangan_id')->references('ruangan_id')->on('ruangan')->onDelete('restrict');

            $table->index(['ruangan_id','status']);
            $table->index(['user_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};

