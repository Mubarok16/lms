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
    Schema::create('pengajaran_mahasiswa', function (Blueprint $table) {
        $table->id();

        $table->foreignId('kelas_id')
            ->constrained('kelas')
            ->cascadeOnDelete();

        $table->foreignId('mahasiswa_id')
            ->constrained('students')
            ->cascadeOnDelete();

        $table->timestamps();

        $table->unique(['kelas_id', 'mahasiswa_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('pengajaran_mahasiswa');
}
};
