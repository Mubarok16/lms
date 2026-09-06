<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\QuizNilaiSheet;
use App\Exports\TugasNilaiSheet;

class RekapNilaiExport implements WithMultipleSheets
{
    public function __construct(protected int $pengajaranDosenId) {}

    public function sheets(): array
    {
        return [
            new QuizNilaiSheet($this->pengajaranDosenId),
            new TugasNilaiSheet($this->pengajaranDosenId),
        ];
    }
}
