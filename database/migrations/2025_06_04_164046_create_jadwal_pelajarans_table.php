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
        Schema::create('jadwal_pelajarans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('hari');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->uuid('mapel_id');
            $table->foreign('mapel_id')->references('id')->on('mata_pelajarans')->onDelete('cascade');
            $table->uuid('kelas_id');
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');
            $table->uuid('guru_id');
            $table->foreign('guru_id')->references('id')->on('gurus')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_pelajarans');
    }
};
