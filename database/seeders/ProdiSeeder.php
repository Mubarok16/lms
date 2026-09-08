<?php

namespace Database\Seeders;

use App\Models\Prodi;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'Teknik Komputer',
            'Teknik Sipil',
            'Teknik Lingkungan',
        ] as $namaProdi) {
            Prodi::updateOrCreate(
                ['nama_prodi' => $namaProdi],
                []
            );
        }
    }
}
