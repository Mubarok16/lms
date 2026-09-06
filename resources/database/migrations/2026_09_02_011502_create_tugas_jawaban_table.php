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
        Schema::create('tugas_jawaban', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tugas_id')
                ->constrained('tugas')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('mahasiswa_id')
                ->constrained('students')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->dateTime('waktu_submit')->nullable();

            $table->decimal('skor', 5, 2)->nullable(); // misal 0.00 - 100.00
            $table->text('catatan_koreksi')->nullable();

            $table->foreignId('dikoreksi_oleh')
                ->nullable()
                ->constrained('lecturer')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->dateTime('dikoreksi_at')->nullable();

            $table->enum('status', ['belum_submit', 'menunggu_koreksi', 'sudah_dikoreksi'])
                ->default('menunggu_koreksi');

            $table->timestamps();

            $table->unique(['tugas_id', 'mahasiswa_id']); // 1 mahasiswa hanya 1 submission per tugas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_jawaban');
    }
};
