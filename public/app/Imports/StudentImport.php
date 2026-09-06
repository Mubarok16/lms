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
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class StudentImport implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows
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
                    'password' => Hash::make(
                        (string) $row['nim']
                    ),
                    'role' => 'student',
                ]);

                /*
                 * Buat data student
                 */
                Student::create([
                    'user_id' => $user->id,
                    'nim' => (string) $row['nim'],
                    'prodi_id' => $prodi->id,
                    'semester' => (int) $row['semester'],
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

            'nim' => [
                'required',
                'numeric',
                'digits_between:1,20',
                'unique:students,nim',
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

            'semester' => [
                'required',
                'integer',
                'between:1,14',
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

            'nim.required' =>
            'NIM wajib diisi.',

            'nim.numeric' =>
            'NIM hanya boleh berupa angka.',

            'nim.digits_between' =>
            'NIM harus terdiri dari 1 sampai 20 digit.',

            'nim.unique' =>
            'NIM sudah terdaftar.',

            'nama.required' =>
            'Nama mahasiswa wajib diisi.',

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

            'semester.required' =>
            'Semester wajib diisi.',

            'semester.integer' =>
            'Semester harus berupa angka.',

            'semester.between' =>
            'Semester harus antara 1 sampai 14.',
        ];
    }
}
