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
        Schema::create('tugas_jawaban_file', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tugas_jawaban_id')
                ->constrained('tugas_jawaban')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('file_path');
            $table->enum('file_type', ['pdf', 'foto']);
            $table->unsignedInteger('urutan')->default(1); // urutan halaman kalau foto multi-page

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_jawaban_file');
    }
};
