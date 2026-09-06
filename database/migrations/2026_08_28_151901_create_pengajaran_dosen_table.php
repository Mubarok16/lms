<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajaran_dosen', function (Blueprint $table) {
            $table->id();

            $table->foreignId('dosen_id')
                ->constrained('lecturer')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('kelas_id')
                ->constrained('kelas')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->timestamps();

            $table->unique(['dosen_id', 'kelas_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajaran_dosen');
    }
};