<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QuizTemplateExport implements FromArray, WithHeadings, WithStyles
{
    /**
     * Baris contoh supaya dosen paham format pengisian.
     */
    public function array(): array
    {
        return [
            [1, 'Contoh: Ibukota Indonesia adalah?', 'Jakarta', 'Bandung', 'Surabaya', 'Medan', '', 'A'],
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Pertanyaan',
            'Pilihan A',
            'Pilihan B',
            'Pilihan C',
            'Pilihan D',
            'Pilihan E',
            'Kunci Jawaban',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
