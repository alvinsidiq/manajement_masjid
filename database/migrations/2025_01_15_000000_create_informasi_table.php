<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('informasi', function (Blueprint $table) {
            $table->bigIncrements('informasi_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('ringkasan', 500)->nullable();
            $table->text('isi');
            $table->string('foto')->nullable();
            $table->boolean('is_published')->default(true)->index();
            $table->dateTime('published_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informasi');
    }
};

