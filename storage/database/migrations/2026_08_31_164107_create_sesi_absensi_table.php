<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('sesi_absensi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kelas_id')
                ->constrained('kelas')
                ->cascadeOnDelete();

            $table->foreignId('dosen_id')
                ->constrained('lecturer')
                ->cascadeOnDelete();

            $table->unsignedInteger('pertemuan_ke');
            $table->string('judul')->nullable();

            $table->string('token', 64)->unique();

            $table->unsignedInteger('durasi_menit')->default(15); // lama QR berlaku
            $table->timestamp('dibuka_pada');
            $table->timestamp('ditutup_pada'); // = dibuka_pada + durasi_menit, dihitung saat create

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesi_absensi');
    }
};
