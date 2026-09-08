<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_matakuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->string('hari', 20);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('ruangan', 100)->nullable();
            $table->timestamps();
            $table->unique(['kelas_id', 'hari', 'jam_mulai'], 'jadwal_kelas_hari_jam_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_matakuliah');
    }
};
