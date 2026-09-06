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
        // create_quiz_questions_table
Schema::create('quiz_questions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
    $table->integer('nomor');
    $table->text('pertanyaan');
    $table->string('pilihan_a');
    $table->string('pilihan_b');
    $table->string('pilihan_c')->nullable();
    $table->string('pilihan_d')->nullable();
    $table->string('pilihan_e')->nullable();
    $table->enum('kunci_jawaban', ['A','B','C','D','E']);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
