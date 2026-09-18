<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_kelas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kelas_id')
                ->constrained('kelas')
                ->cascadeOnDelete();

            $table->foreignId('mahasiswa_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->enum('status', ['pending', 'disetujui', 'ditolak'])
                ->default('pending');

            $table->text('catatan_dosen')->nullable();

            $table->foreignId('diproses_oleh')
                ->nullable()
                ->constrained('lecturer')
                ->nullOnDelete();

            $table->timestamp('diproses_at')->nullable();

            $table->timestamps();

            $table->unique(['kelas_id', 'mahasiswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_kelas');
    }
};
