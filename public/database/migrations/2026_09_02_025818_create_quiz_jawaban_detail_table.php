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
        Schema::create('quiz_jawaban_detail', function (Blueprint $table) {
            $table->id();

            $table->foreignId('quiz_jawaban_id')
                ->constrained('quiz_jawaban')
                ->cascadeOnDelete();

            $table->foreignId('quiz_question_id')
                ->constrained('quiz_questions')
                ->cascadeOnDelete();

            $table->enum('jawaban_dipilih', ['A', 'B', 'C', 'D', 'E'])->nullable();
            $table->boolean('is_benar')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_jawaban_detail');
    }
};
