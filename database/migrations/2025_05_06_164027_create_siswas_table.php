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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_siswa');
            $table->string('nomor_induk', 20)->unique();
            $table->string('NISN', 20)->unique();
            $table->string('NIK', 20)->unique();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('no_telp_siswa', 20);
            $table->text('alamat_siswa');
            $table->string('foto')->nullable();
            $table->string('tamatan');
            $table->date('tanggal_lulus');
            $table->string('STTB')->unique();
            $table->string('lama_belajar');
            $table->string('pindahan')->nullable();
            $table->text('alasan')->nullable();
            $table->enum('status', ['Aktif', 'Nonaktif', 'Lulus']);
            $table->unsignedBigInteger('orangtua_id');
            $table->foreign('orangtua_id')->references('id')->on('orang_tuas')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('angkatan_id')->nullable();
            $table->foreign('angkatan_id')->references('id')->on('angkatans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
