<?php

namespace App\Exports;

use App\Models\PengajaranDosen;
use App\Models\QuizJawaban;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QuizNilaiSheet implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected Collection $quizzes;
    protected Collection $mahasiswaList;

    public function __construct(protected int $pengajaranDosenId)
    {
        $pengajaranDosen = PengajaranDosen::with([
            'kelas.matakuliah',
            'kelas.mahasiswa.user',
        ])->findOrFail($pengajaranDosenId);

        // Semua quiz milik pengajaran (dosen + kelas) ini, urut berdasarkan dibuat
        $this->quizzes = $pengajaranDosen->quizzes()
            ->orderBy('id')
            ->get();

        // Semua mahasiswa yang terdaftar di kelas ini
        $this->mahasiswaList = $pengajaranDosen->kelas->mahasiswa;
    }

    public function title(): string
    {
        return 'Nilai Quiz';
    }

    public function headings(): array
    {
        $headings = ['No', 'NIM', 'Nama Mahasiswa'];

        foreach ($this->quizzes as $quiz) {
            $headings[] = $quiz->judul;
        }

        $headings[] = 'Rata-rata';

        return $headings;
    }

    public function array(): array
    {
        // Ambil semua jawaban quiz untuk pengajaran ini sekaligus (hindari N+1)
        $quizIds = $this->quizzes->pluck('id');

        $jawabanMap = QuizJawaban::whereIn('quiz_id', $quizIds)
            ->get()
            ->groupBy('mahasiswa_id');

        $rows = [];
        $no = 1;

        foreach ($this->mahasiswaList as $mhs) {
            $row = [
                $no++,
                $mhs->nim ?? '-',
                $mhs->user->name ?? '-',
            ];

            $totalSkor = 0;
            $jumlahDinilai = 0;

            foreach ($this->quizzes as $quiz) {
                $jawaban = optional($jawabanMap->get($mhs->id))
                    ->firstWhere('quiz_id', $quiz->id);

                $skor = $jawaban->skor ?? null;

                $row[] = $skor !== null ? (float) $skor : '-';

                if ($skor !== null) {
                    $totalSkor += (float) $skor;
                    $jumlahDinilai++;
                }
            }

            $row[] = $jumlahDinilai > 0
                ? round($totalSkor / $jumlahDinilai, 2)
                : '-';

            $rows[] = $row;
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
