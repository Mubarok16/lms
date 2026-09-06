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
        Schema::create('quiz_jawaban', function (Blueprint $table) {
            $table->id();

            $table->foreignId('quiz_id')
                ->constrained('quizzes')
                ->cascadeOnDelete();

            $table->foreignId('mahasiswa_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->decimal('skor', 5, 2)->nullable();
            $table->dateTime('waktu_submit')->nullable();

            $table->timestamps();

            $table->unique(['quiz_id', 'mahasiswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_jawaban');
    }
};
