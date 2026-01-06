<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kegiatan_pendaftaran', function (Blueprint $table) {
            $table->bigIncrements('pendaftaran_id');
            $table->unsignedBigInteger('kegiatan_id')->index();
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->string('nama');
            $table->string('email');
            $table->string('no_telephone', 32);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('kegiatan_id')->references('kegiatan_id')->on('kegiatan')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan_pendaftaran');
    }
};
