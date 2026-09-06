<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('jadwal_kuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->string('hari', 15);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('ruang', 100)->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
            $table->index(['hari','jam_mulai']);
        });
    }
    public function down(): void { Schema::dropIfExists('jadwal_kuliah'); }
};
