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
        Schema::create('aktivitas_harians', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kegiatan');
            $table->string('kendala');
            $table->text('deskripsi')->nullable();
            $table->string('foto')->nullable();
            $table->uuid('siswa_id');
            $table->foreign('siswa_id')->references('id')->on('siswas')->onDelete('cascade');
            $table->uuid('feedback_id')->nullable();
            $table->foreign('feedback_id')->references('id')->on('feedback')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_harians');
    }
};
