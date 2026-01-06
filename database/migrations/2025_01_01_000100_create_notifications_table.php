<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   public function up(): void {
       Schema::create('notifikasi', function (Blueprint $table) {
           $table->bigIncrements('notifikasi_id');
           $table->unsignedInteger('user_id')->index();
           $table->unsignedBigInteger('referensi_id')->nullable()->index();
           $table->enum('jenis_referensi', ['pemesanan','payment','kegiatan','umum'])->default('umum')->index();
           $table->text('pesan');
           $table->boolean('terkirim')->default(false)->index();
           $table->dateTime('waktu_kirim')->nullable()->index();
           $table->string('status_pengiriman')->nullable()->index();
           $table->timestamps();

           $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
       });
   }
   public function down(): void {
       Schema::dropIfExists('notifikasi');
   }
};

