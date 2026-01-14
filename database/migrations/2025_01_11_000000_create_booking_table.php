<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->bigIncrements('booking_id');
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('ruangan_id');
            $table->dateTime('hari_tanggal');
            $table->time('jam');
            $table->enum('status', ['hold','proses','setuju','tolak','expired','cancelled'])->default('hold')->index();
            $table->dateTime('hold_expires_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('ruangan_id')->references('ruangan_id')->on('ruangan')->onDelete('cascade');

            $table->index(['ruangan_id','hari_tanggal','jam']);
            $table->index(['user_id','created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
