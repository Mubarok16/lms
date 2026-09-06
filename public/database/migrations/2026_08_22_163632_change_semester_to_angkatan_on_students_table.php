<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->integer('angkatan')->after('prodi_id');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('semester');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedTinyInteger('semester')->after('prodi_id');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('angkatan');
        });
    }
};