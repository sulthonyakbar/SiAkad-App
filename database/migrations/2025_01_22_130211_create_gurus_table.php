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
        Schema::create('gurus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_guru');
            $table->string('jabatan');
            $table->enum('status', ['Aktif', 'Nonaktif']);
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('NIP', 20)->unique();
            $table->string('pangkat');
            $table->string('NUPTK', 50)->unique();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('pendidikan');
            $table->date('mulai_bekerja');
            $table->string('sertifikasi')->nullable();
            $table->string('no_telp', 20);
            $table->text('alamat');
            $table->string('foto')->nullable();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
