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
        Schema::create('orang_tuas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->text('alamat_ortu');
            $table->string('no_telp_ortu', 20);
            $table->string('pekerjaan_ayah');
            $table->string('pendidikan_ayah');
            $table->string('penghasilan_ayah');
            $table->string('pekerjaan_ibu');
            $table->string('pendidikan_ibu');
            $table->string('penghasilan_ibu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orang_tuas');
    }
};
