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
use Maatwebsite\Excel\Concerns\WithValidation;

class LecturerImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows): void
    {
        DB::transaction(function () use ($rows) {

            foreach ($rows as $row) {

                /*
                 * Cari prodi berdasarkan nama dari Excel
                 */
                $prodi = Prodi::whereRaw(
                    'LOWER(nama_prodi) = ?',
                    [strtolower(trim($row['program_studi']))]
                )->first();

                /*
                 * Jika prodi tidak ditemukan,
                 * batalkan proses import
                 */
                if (!$prodi) {
                    throw new \Exception(
                        'Program studi "' .
                        $row['program_studi'] .
                        '" tidak ditemukan di database.'
                    );
                }

                /*
                 * Buat akun user
                 */
                $user = User::create([
                    'name' => trim($row['nama']),
                    'email' => trim($row['email']),
                    'password' => Hash::make((string) $row['nidn']),
                    'role' => 'lecturer',
                ]);

                /*
                 * Buat data lecturer
                 */
                Lecturer::create([
                    'user_id' => $user->id,
                    'nidn' => (string) $row['nidn'],
                    'prodi_id' => $prodi->id,
                    'phone' => !empty($row['phone'])
                        ? (string) $row['phone']
                        : null,
                ]);
            }
        });
    }

    public function rules(): array
    {
        return [

            'nidn' => [
                'required',
                'numeric',
                'digits_between:1,20',
                'unique:lecturer,nidn',
            ],

            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'program_studi' => [
                'required',
                'string',
            ],

            'phone' => [
                'nullable',
                'numeric',
                'digits_between:1,20',
            ],
        ];
    }

    public function customValidationMessages(): array
    {
        return [

            'nidn.required' =>
                'NIDN wajib diisi.',

            'nidn.numeric' =>
                'NIDN hanya boleh berupa angka.',

            'nidn.digits_between' =>
                'NIDN harus terdiri dari 1 sampai 20 digit.',

            'nidn.unique' =>
                'NIDN sudah terdaftar.',

            'nama.required' =>
                'Nama dosen wajib diisi.',

            'email.required' =>
                'Email wajib diisi.',

            'email.email' =>
                'Format email tidak valid.',

            'email.unique' =>
                'Email sudah digunakan.',

            'program_studi.required' =>
                'Program studi wajib diisi.',

            'phone.numeric' =>
                'Nomor telepon hanya boleh berupa angka.',

            'phone.digits_between' =>
                'Nomor telepon harus terdiri dari 1 sampai 20 digit.',
        ];
    }
}