<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_kelas');
            $table->string('ruang', 20)->unique();
            $table->uuid('guru_id');
            $table->foreign('guru_id')->references('id')->on('gurus')->onDelete('cascade');
            $table->uuid('angkatan_id')->nullable();
            $table->foreign('angkatan_id')->references('id')->on('angkatans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
