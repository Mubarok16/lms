<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambahkan prodi_id terlebih dahulu
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('prodi_id')
                ->nullable()
                ->after('study_program')
                ->constrained('prodi')
                ->cascadeOnDelete();
        });

        // 2. Pindahkan data lama dari study_program ke prodi_id
        DB::table('students')
            ->select('id', 'study_program')
            ->orderBy('id')
            ->chunk(100, function ($students) {

                foreach ($students as $student) {

                    $prodi = DB::table('prodi')
                        ->where('nama_prodi', $student->study_program)
                        ->first();

                    if ($prodi) {
                        DB::table('students')
                            ->where('id', $student->id)
                            ->update([
                                'prodi_id' => $prodi->id
                            ]);
                    }
                }
            });

        // 3. Hapus kolom study_program lama
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('study_program');
        });
    }

    public function down(): void
    {
        // 1. Kembalikan kolom study_program
        Schema::table('students', function (Blueprint $table) {
            $table->string('study_program')
                ->nullable()
                ->after('prodi_id');
        });

        // 2. Kembalikan nama prodi dari relasi prodi
        DB::table('students')
            ->select('id', 'prodi_id')
            ->orderBy('id')
            ->chunk(100, function ($students) {

                foreach ($students as $student) {

                    $prodi = DB::table('prodi')
                        ->where('id', $student->prodi_id)
                        ->first();

                    if ($prodi) {
                        DB::table('students')
                            ->where('id', $student->id)
                            ->update([
                                'study_program' => $prodi->nama_prodi
                            ]);
                    }
                }
            });

        // 3. Hapus foreign key prodi_id
        Schema::table('students', function (Blueprint $table) {
            $table->dropConstrainedForeignId('prodi_id');
        });
    }
};
