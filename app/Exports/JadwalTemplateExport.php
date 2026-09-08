<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JadwalTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [['IF101', 'A', 'Senin', '08:00', '09:40', 'Ruang 101']];
    }

    public function headings(): array
    {
        return ['kode_mk', 'kode_kelas', 'hari', 'jam_mulai', 'jam_selesai', 'ruangan'];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
