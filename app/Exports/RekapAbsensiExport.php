<?php

namespace App\Exports;

use App\Models\PengajaranDosen;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapAbsensiExport implements FromArray, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    protected $pengajaranDosen;
    protected $sesiAbsensiList;
    protected $mahasiswaList;

    public function __construct(PengajaranDosen $pengajaranDosen, $sesiAbsensiList, $mahasiswaList)
    {
        $this->pengajaranDosen = $pengajaranDosen;
        $this->sesiAbsensiList = $sesiAbsensiList;
        $this->mahasiswaList = $mahasiswaList;
    }

    public function title(): string
    {
        return 'Rekap Absensi';
    }

    public function headings(): array
    {
        $headings = ['No', 'NIM', 'Nama Mahasiswa'];

        foreach ($this->sesiAbsensiList as $s) {
            $headings[] = 'P' . $s->pertemuan_ke;
        }

        $headings[] = 'Hadir';
        $headings[] = 'Izin';
        $headings[] = 'Sakit';
        $headings[] = 'Alpha';

        return $headings;
    }

    public function array(): array
    {
        $rows = [];
        $no = 1;

        foreach ($this->mahasiswaList as $mhs) {
            $row = [
                $no++,
                $mhs->nim ?? '-',
                $mhs->user->name ?? '-',
            ];

            $countHadir = 0;
            $countIzin = 0;
            $countSakit = 0;
            $countAlpha = 0;

            foreach ($this->sesiAbsensiList as $s) {
                $absen = $s->absensi->firstWhere('mahasiswa_id', $mhs->id);

                if ($absen) {
                    $status = match ($absen->status) {
                        'hadir' => 'H',
                        'izin' => 'I',
                        'sakit' => 'S',
                        'alpha' => 'A',
                        default => '-',
                    };

                    match ($absen->status) {
                        'hadir' => $countHadir++,
                        'izin' => $countIzin++,
                        'sakit' => $countSakit++,
                        'alpha' => $countAlpha++,
                        default => null,
                    };
                } else {
                    // Belum absen dianggap alpha
                    $status = 'A';
                    $countAlpha++;
                }

                $row[] = $status;
            }

            $row[] = $countHadir;
            $row[] = $countIzin;
            $row[] = $countSakit;
            $row[] = $countAlpha;

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

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 30,
        ];
    }
}
