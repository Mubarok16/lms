<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();

            $table->string('kode_mk', 20);

            $table->foreign('kode_mk')
                ->references('kode_mk')
                ->on('matakuliah')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('kode_kelas', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};