<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matakuliah', function (Blueprint $table) {
            $table->foreignId('prodi_id')
                ->after('nama_mk')
                ->constrained('prodi')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('matakuliah', function (Blueprint $table) {
            $table->dropConstrainedForeignId('prodi_id');
        });
    }
};