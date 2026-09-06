<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sesi_absensi_id')
                ->constrained('sesi_absensi')
                ->cascadeOnDelete();

            $table->foreignId('mahasiswa_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])
                ->default('hadir');

            $table->timestamp('waktu_absen')->nullable(); // kapan mahasiswa scan
            $table->string('ip_address', 45)->nullable();  // opsional, buat deteksi kecurangan
            $table->text('catatan')->nullable();            // opsional, misal alasan izin/sakit

            $table->timestamps();

            // 1 mahasiswa cuma bisa absen 1x per sesi
            $table->unique(['sesi_absensi_id', 'mahasiswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
