<?php

namespace App\Exports;

use App\Models\PengajaranDosen;
use App\Models\TugasJawaban;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TugasNilaiSheet implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected Collection $tugasList;
    protected Collection $mahasiswaList;

    public function __construct(protected int $pengajaranDosenId)
    {
        $pengajaranDosen = PengajaranDosen::with([
            'kelas.matakuliah',
            'kelas.mahasiswa.user',
        ])->findOrFail($pengajaranDosenId);

        // Semua tugas milik pengajaran (dosen + kelas) ini
        $this->tugasList = $pengajaranDosen->tugas()
            ->orderBy('id')
            ->get();

        $this->mahasiswaList = $pengajaranDosen->kelas->mahasiswa;
    }

    public function title(): string
    {
        return 'Nilai Tugas';
    }

    public function headings(): array
    {
        $headings = ['No', 'NIM', 'Nama Mahasiswa'];

        foreach ($this->tugasList as $tugas) {
            $headings[] = $tugas->judul;
        }

        $headings[] = 'Rata-rata';

        return $headings;
    }

    public function array(): array
    {
        $tugasIds = $this->tugasList->pluck('id');

        $jawabanMap = TugasJawaban::whereIn('tugas_id', $tugasIds)
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

            foreach ($this->tugasList as $tugas) {
                $jawaban = optional($jawabanMap->get($mhs->id))
                    ->firstWhere('tugas_id', $tugas->id);

                // Hanya hitung skor yang sudah dikoreksi
                $skor = ($jawaban && $jawaban->status === 'sudah_dikoreksi')
                    ? $jawaban->skor
                    : null;

                if ($jawaban && $skor === null) {
                    $label = $jawaban->status === 'menunggu_koreksi'
                        ? 'Belum dikoreksi'
                        : 'Belum submit';
                } elseif (!$jawaban) {
                    $label = 'Belum submit';
                } else {
                    $label = (float) $skor;
                }

                $row[] = $label;

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
