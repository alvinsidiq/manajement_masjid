<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('arsip', function (Blueprint $table) {
            $table->bigIncrements('arsip_id');
            $table->string('judul')->index();
            $table->text('deskripsi')->nullable();
            $table->string('dokumen');
            $table->unsignedInteger('uploaded_by')->nullable();
            $table->timestamps();

            $table->foreign('uploaded_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsip');
    }
};
