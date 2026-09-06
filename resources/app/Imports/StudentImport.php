<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Student;
use App\Models\Prodi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows): void
    {
        DB::transaction(function () use ($rows) {
            foreach ($rows as $index => $row) {
                $nim = trim((string) ($row['nim'] ?? ''));
                $nama = trim((string) ($row['nama'] ?? ''));
                $email = trim((string) ($row['email'] ?? ''));
                $programStudi = trim((string) ($row['program_studi'] ?? ''));
                $angkatanRaw = $row['angkatan'] ?? $row['semester'] ?? null;

                if ($nim === '' && $nama === '' && $email === '') {
                    continue;
                }

                $baris = $index + 2;
                if ($nim === '' || !ctype_digit($nim)) {
                    throw new \Exception("Baris {$baris}: NIM wajib berupa angka.");
                }
                if ($nama === '') {
                    throw new \Exception("Baris {$baris}: Nama mahasiswa wajib diisi.");
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception("Baris {$baris}: Email tidak valid.");
                }
                if ($programStudi === '') {
                    throw new \Exception("Baris {$baris}: Program studi wajib diisi.");
                }
                $angkatan = trim((string) $angkatanRaw);
                if (!preg_match('/^\d{4}$/', $angkatan)) {
                    throw new \Exception("Baris {$baris}: Tahun angkatan harus terdiri dari 4 digit.");
                }

                $prodi = Prodi::whereRaw('LOWER(nama_prodi) = ?', [strtolower($programStudi)])->first();
                if (!$prodi) {
                    throw new \Exception("Baris {$baris}: Program studi \"{$programStudi}\" tidak ditemukan di database.");
                }
                if (Student::where('nim', $nim)->exists()) {
                    throw new \Exception("Baris {$baris}: NIM {$nim} sudah terdaftar.");
                }
                if (User::where('email', $email)->exists()) {
                    throw new \Exception("Baris {$baris}: Email {$email} sudah digunakan.");
                }

                $user = User::create([
                    'name' => $nama,
                    'email' => $email,
                    'password' => Hash::make($nim),
                    'role' => 'student',
                ]);

                Student::create([
                    'user_id' => $user->id,
                    'nim' => $nim,
                    'prodi_id' => $prodi->id,
                    'angkatan' => (int) $angkatan,
                    'phone' => !empty($row['phone']) ? (string) $row['phone'] : null,
                ]);
            }
        });
    }

}
