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
        Schema::create('detail_presensis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alpa'])->default('Hadir');
            $table->uuid('presensi_id');
            $table->foreign('presensi_id')->references('id')->on('presensis')->onDelete('cascade');
            $table->uuid('siswa_id');
            $table->foreign('siswa_id')->references('id')->on('siswas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_presensis');
    }
};
