<?php

namespace App\Imports;

use App\Models\JadwalMatakuliah;
use App\Models\Kelas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JadwalMatakuliahImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public function collection(Collection $rows): void
    {
        DB::transaction(function () use ($rows) {
            foreach ($rows as $index => $row) {
                $line = $index + 2;
                $kodeMk = trim((string) ($row['kode_mk'] ?? ''));
                $kodeKelas = trim((string) ($row['kode_kelas'] ?? ''));
                $hari = ucfirst(strtolower(trim((string) ($row['hari'] ?? ''))));
                $mulai = $this->normalizeTime($row['jam_mulai'] ?? null);
                $selesai = $this->normalizeTime($row['jam_selesai'] ?? null);

                if (!$kodeMk || !$kodeKelas || !$hari || !$mulai || !$selesai) {
                    throw new \RuntimeException("Baris {$line}: kode_mk, kode_kelas, hari, jam_mulai, dan jam_selesai wajib diisi.");
                }
                if (!in_array($hari, ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'], true)) {
                    throw new \RuntimeException("Baris {$line}: hari tidak valid.");
                }
                if ($selesai <= $mulai) {
                    throw new \RuntimeException("Baris {$line}: jam_selesai harus setelah jam_mulai.");
                }

                $kelas = Kelas::where('kode_mk', $kodeMk)->where('kode_kelas', $kodeKelas)->first();
                if (!$kelas) {
                    throw new \RuntimeException("Baris {$line}: kelas {$kodeMk} / {$kodeKelas} tidak ditemukan.");
                }

                JadwalMatakuliah::updateOrCreate(
                    ['kelas_id' => $kelas->id, 'hari' => $hari, 'jam_mulai' => $mulai],
                    ['jam_selesai' => $selesai, 'ruangan' => trim((string) ($row['ruangan'] ?? '')) ?: null]
                );
            }
        });
    }

    private function normalizeTime($value): ?string
    {
        if ($value === null || $value === '') return null;
        if (is_numeric($value)) {
            $seconds = (int) round(((float) $value) * 86400);
            return sprintf('%02d:%02d', intdiv($seconds, 3600) % 24, intdiv($seconds % 3600, 60));
        }
        $value = trim((string) $value);
        foreach (['H:i', 'H:i:s', 'G:i', 'G:i:s'] as $format) {
            $date = \DateTime::createFromFormat($format, $value);
            if ($date && $date->format($format) === $value) return $date->format('H:i');
        }
        return null;
    }
}
