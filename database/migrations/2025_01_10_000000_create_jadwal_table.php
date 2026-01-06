<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->bigIncrements('jadwal_id');
            $table->unsignedBigInteger('kegiatan_id');
            $table->unsignedBigInteger('ruangan_id')->nullable();
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->enum('status', ['rutin','berkala','khusus'])->default('khusus')->index();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('kegiatan_id')->references('kegiatan_id')->on('kegiatan')->onDelete('cascade');
            $table->foreign('ruangan_id')->references('ruangan_id')->on('ruangan')->onDelete('set null');

            $table->index(['kegiatan_id','tanggal_mulai']);
            $table->index(['ruangan_id','tanggal_mulai']);
            $table->index(['tanggal_mulai','tanggal_selesai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};

