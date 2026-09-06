<?php

namespace App\Imports;

use App\Models\QuizQuestion;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuizQuestionsImport implements ToCollection, WithHeadingRow
{
    public function __construct(protected int $quizId)
    {
    }

    public function collection(Collection $rows)
    {
        $pilihanValid = ['A', 'B', 'C', 'D', 'E'];
        $errors = [];
        $dataToInsert = [];

        foreach ($rows as $i => $row) {
            $baris = $i + 2; // +2 karena baris 1 = heading, index mulai dari 0

            $pertanyaan = trim((string) ($row['pertanyaan'] ?? ''));

            // Lewati baris yang benar-benar kosong
            if (blank($pertanyaan)) {
                continue;
            }

            $pilihanA = trim((string) ($row['pilihan_a'] ?? ''));
            $pilihanB = trim((string) ($row['pilihan_b'] ?? ''));
            $pilihanC = trim((string) ($row['pilihan_c'] ?? ''));
            $pilihanD = trim((string) ($row['pilihan_d'] ?? ''));
            $pilihanE = trim((string) ($row['pilihan_e'] ?? ''));
            $kunci = strtoupper(trim((string) ($row['kunci_jawaban'] ?? '')));

            if (blank($pilihanA) || blank($pilihanB)) {
                $errors[] = "Baris {$baris}: Pilihan A dan Pilihan B wajib diisi.";
                continue;
            }

            if (! in_array($kunci, $pilihanValid, true)) {
                $errors[] = "Baris {$baris}: Kunci jawaban harus salah satu dari A/B/C/D/E.";
                continue;
            }

            $petaPilihan = [
                'A' => $pilihanA,
                'B' => $pilihanB,
                'C' => $pilihanC,
                'D' => $pilihanD,
                'E' => $pilihanE,
            ];

            if (blank($petaPilihan[$kunci])) {
                $errors[] = "Baris {$baris}: Kunci jawaban '{$kunci}' tidak boleh merujuk ke pilihan yang kosong.";
                continue;
            }

            $dataToInsert[] = [
                'quiz_id' => $this->quizId,
                'nomor' => $row['no'] ?? ($i + 1),
                'pertanyaan' => $pertanyaan,
                'pilihan_a' => $pilihanA,
                'pilihan_b' => $pilihanB,
                'pilihan_c' => $pilihanC ?: null,
                'pilihan_d' => $pilihanD ?: null,
                'pilihan_e' => $pilihanE ?: null,
                'kunci_jawaban' => $kunci,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (! empty($errors)) {
            throw ValidationException::withMessages(['file' => $errors]);
        }

        if (empty($dataToInsert)) {
            throw ValidationException::withMessages([
                'file' => ['Tidak ada soal yang valid ditemukan di dalam file.'],
            ]);
        }

        DB::transaction(function () use ($dataToInsert) {
            // Hapus soal lama sebelum import ulang, supaya tidak dobel
            QuizQuestion::where('quiz_id', $this->quizId)->delete();
            QuizQuestion::insert($dataToInsert);
        });
    }
}
