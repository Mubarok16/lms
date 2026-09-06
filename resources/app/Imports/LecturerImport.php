<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Lecturer;
use App\Models\Prodi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LecturerImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows): void
    {
        DB::transaction(function () use ($rows) {

            foreach ($rows as $index => $row) {

                // Abaikan baris template yang benar-benar kosong.
                if (blank(trim((string) ($row['nama'] ?? ''))) && blank(trim((string) ($row['nidn'] ?? '')))) {
                    continue;
                }

                $nidn = trim((string) ($row['nidn'] ?? ''));
                $nama = trim((string) ($row['nama'] ?? ''));
                $email = trim((string) ($row['email'] ?? ''));
                $programStudi = trim((string) ($programStudi ?? ''));

                if ($nidn === '' || !ctype_digit($nidn)) {
                    throw new \Exception('Baris '.($index + 2).': NIDN wajib berupa angka.');
                }
                if ($nama === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception('Baris '.($index + 2).': Nama dan email wajib valid.');
                }
                if ($programStudi === '') {
                    throw new \Exception('Baris '.($index + 2).': Program studi wajib diisi.');
                }
                if (User::where('email', $email)->exists()) {
                    throw new \Exception('Baris '.($index + 2).': Email '.$email.' sudah digunakan.');
                }
                if (Lecturer::where('nidn', $nidn)->exists()) {
                    throw new \Exception('Baris '.($index + 2).': NIDN '.$nidn.' sudah terdaftar.');
                }

                /*
                 * Cari prodi berdasarkan nama dari Excel
                 */
                $prodi = Prodi::whereRaw(
                    'LOWER(nama_prodi) = ?',
                    [strtolower($programStudi)]
                )->first();

                /*
                 * Jika prodi tidak ditemukan,
                 * batalkan proses import
                 */
                if (!$prodi) {
                    throw new \Exception(
                        'Program studi "' .
                        $programStudi .
                        '" tidak ditemukan di database.'
                    );
                }

                /*
                 * Buat akun user
                 */
                $user = User::create([
                    'name' => $nama,
                    'email' => $email,
                    'password' => Hash::make($nidn),
                    'role' => 'lecturer',
                ]);

                /*
                 * Buat data lecturer
                 */
                Lecturer::create([
                    'user_id' => $user->id,
                    'nidn' => $nidn,
                    'prodi_id' => $prodi->id,
                    'phone' => !empty($row['phone'])
                        ? (string) $row['phone']
                        : null,
                ]);
            }
        });
    }

}
