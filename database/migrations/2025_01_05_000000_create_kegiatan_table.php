<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->bigIncrements('kegiatan_id');
            $table->string('nama_kegiatan')->index();
            $table->text('deskripsi')->nullable();
            $table->enum('jenis_kegiatan', ['rutin','berkala','khusus'])->default('rutin')->index();
            $table->string('penanggung_jawab')->nullable();
            $table->string('no_telephone', 32)->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};

