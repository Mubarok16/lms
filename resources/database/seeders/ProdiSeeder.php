<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('prodi')->insert([
            [
                'nama_prodi' => 'Teknik Komputer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_prodi' => 'Teknik Sipil',
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'nama_prodi' => 'Teknik Lingkungan',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}