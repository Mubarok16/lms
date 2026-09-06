<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materi_files', function (Blueprint $table) {
            $table->id();

            $table->foreignId('materi_id')
                ->constrained('materis')
                ->cascadeOnDelete();

            $table->enum('tipe', ['pdf', 'audio', 'video_youtube']);

            // dipakai untuk tipe pdf & audio (path di storage)
            $table->string('file_path')->nullable();

            // nama file asli saat diupload dosen, untuk ditampilkan ke user
            $table->string('nama_asli')->nullable();

            // dipakai untuk tipe video_youtube
            $table->string('youtube_url')->nullable();
            $table->string('youtube_id')->nullable();

            $table->integer('urutan')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi_files');
    }
};
