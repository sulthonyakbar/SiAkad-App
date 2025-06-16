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
        Schema::create('nilais', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('nilai_uh')->default(0);
            $table->integer('nilai_uts')->default(0);
            $table->integer('nilai_uas')->default(0);
            $table->integer('nilai_akhir')->default(0);
            $table->uuid('ks_id');
            $table->foreign('ks_id')->references('id')->on('kartu_studis')->onDelete('cascade');
            $table->uuid('mapel_id');
            $table->foreign('mapel_id')->references('id')->on('mata_pelajarans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
