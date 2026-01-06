<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ruangan', function (Blueprint $table) {
            $table->bigIncrements('ruangan_id');
            $table->string('nama_ruangan')->unique();
            $table->text('deskripsi')->nullable();
            $table->json('fasilitas')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status', ['aktif','nonaktif','maintenance'])->default('aktif')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ruangan');
    }
};

