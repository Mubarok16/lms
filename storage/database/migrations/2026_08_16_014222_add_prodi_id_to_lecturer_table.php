<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom prodi_id (nullable dulu, biar aman saat migrasi data)
        Schema::table('lecturer', function (Blueprint $table) {
            $table->foreignId('prodi_id')
                ->nullable()
                ->after('study_program')
                ->constrained('prodi')
                ->cascadeOnDelete();
        });

        // 2. Pindahkan data lama: cocokkan study_program (string) ke prodi.nama_prodi
        DB::table('lecturer')->select('id', 'study_program')->orderBy('id')->chunk(100, function ($lecturers) {
            foreach ($lecturers as $lecturer) {
                $prodi = DB::table('prodi')->where('nama_prodi', $lecturer->study_program)->first();

                if ($prodi) {
                    DB::table('lecturer')
                        ->where('id', $lecturer->id)
                        ->update(['prodi_id' => $prodi->id]);
                }
            }
        });

        // 3. Hapus kolom study_program yang lama
        Schema::table('lecturer', function (Blueprint $table) {
            $table->dropColumn('study_program');
        });
    }

    public function down(): void
    {
        Schema::table('lecturer', function (Blueprint $table) {
            $table->string('study_program')->nullable()->after('prodi_id');
        });

        Schema::table('lecturer', function (Blueprint $table) {
            $table->dropConstrainedForeignId('prodi_id');
        });
    }
};