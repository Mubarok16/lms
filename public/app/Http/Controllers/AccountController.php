<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Validators\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\User;
use App\Models\Lecturer;
use App\Models\Student;
use App\Imports\LecturerImport;
use App\Models\Prodi;

use App\Imports\StudentImport;

class AccountController extends Controller
{
    // ============================================================
    // MENAMPILKAN DATA AKUN DOSEN
    // ============================================================
    public function index()
    {
        $prodi = Prodi::latest()
            ->get();

        $akundosen = Lecturer::with('user')
            ->latest()
            ->get();

        return view('admin.index-akun_dosen', compact('akundosen', 'prodi'));
    }

    // ============================================================
    // HALAMAN IMPORT DOSEN
    // ============================================================
    public function import_dosen()
    {
        return view('admin.import-dosen');
    }

    // ============================================================
    // PROSES TAMBAH AKUN DOSEN
    // ============================================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nidn' => [
                'required',
                'string',
                'max:20',
                'unique:lecturer,nidn',
            ],

            'name' => [
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

            'prodi_id' => [
                'required',
                'integer',
                'exists:prodi,id',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],
        ]);

        DB::transaction(function () use ($validated) {

            /*
             * Password awal menggunakan NIDN.
             *
             * Contoh:
             * NIDN = 0428019701
             * Password awal = 0428019701
             */

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['nidn'],
                'role' => 'lecturer',
            ]);

            Lecturer::create([
                'user_id' => $user->id,
                'nidn' => $validated['nidn'],
                'prodi_id' => $validated['prodi_id'],
                'phone' => $validated['phone'] ?? null,
            ]);
        });

        return redirect()
            ->route('akun_dosen.index')
            ->with('success', 'Akun dosen berhasil dibuat.');
    }

    // ============================================================
    // PROSES IMPORT AKUN DOSEN DARI EXCEL
    // ============================================================
    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => [
                'required',
                'file',
                'mimes:xlsx,xls',
                'max:5120',
            ],
        ], [
            'file_excel.required' => 'File Excel wajib dipilih.',
            'file_excel.file' => 'File yang diupload tidak valid.',
            'file_excel.mimes' => 'File harus berformat .xlsx atau .xls.',
            'file_excel.max' => 'Ukuran file maksimal 5 MB.',
        ]);

        try {

            Excel::import(
                new LecturerImport,
                $request->file('file_excel')
            );

            return redirect()
                ->route('dosen.import')
                ->with('success', 'Data akun dosen berhasil diimport.');
        } catch (ValidationException $e) {

            $errors = [];

            foreach ($e->failures() as $failure) {

                $errors[] = [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values(),
                ];
            }

            return redirect()
                ->route('dosen.import')
                ->with('error', $errors);
        } catch (\Throwable $e) {

            report($e);

            return redirect()
                ->route('dosen.import')
                ->with('error', [
                    [
                        'row' => null,
                        'attribute' => null,
                        'errors' => [
                            $e->getMessage(),
                        ],
                        'values' => [],
                    ],
                ]);
        }
    }
    // ============================================================
    // HALAMAN AKUN MAHASISWA
    // ============================================================
    public function index_mahasiswa()
    {
        $prodi = Prodi::latest()->get();

        $akunmahasiswa = Student::with([
            'user',
            'prodi'
        ])
            ->latest()
            ->get();

        return view(
            'admin.index-akun-mahasiswa',
            compact('prodi', 'akunmahasiswa')
        );
    }

    // ============================================================
    // HALAMAN IMPORT MAHASISWA
    // ============================================================
    public function import_mahasiswa()
    {
        $prodi = Prodi::get();

        return view('admin.import-mahasiswa', compact('prodi'));
    }

    // ============================================================
    // PROSES TAMBAH AKUN MAHASISWA
    // ============================================================
    public function store_mahasiswa(Request $request)
    {
        $validated = $request->validate([
            'nim' => [
                'required',
                'string',
                'max:12',
                'unique:students,nim',
            ],

            'name' => [
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

            'prodi_id' => [
                'required',
                'string',
                'max:255',
            ],
            'angkatan' => [
                'required',
                'string',
                'max:4',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],
        ]);

        DB::transaction(function () use ($validated) {

            /*
             * Password awal menggunakan npm.
             *
             * Contoh:
             * NPM = 1234567890
             * Password awal = 1234567890
             */

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['nim'],
                'role' => 'student',
            ]);

            Student::create([
                'user_id' => $user->id,
                'nim' => $validated['nim'],
                'prodi_id' => $validated['prodi_id'],
                'angkatan' => $validated['angkatan'],
                'phone' => $validated['phone'] ?? null,
            ]);
        });

        return redirect()
            ->route('akun_mahasiswa.index')
            ->with('success', 'Akun mahasiswa berhasil dibuat.');
    }

    // ============================================================
    // PROSES IMPORT AKUN MAHASISWA DARI EXCEL
    // ============================================================
    public function importStudent(Request $request)
    {
        $request->validate([
            'file_excel' => [
                'required',
                'file',
                'mimes:xlsx,xls',
                'max:5120',
            ],
        ], [
            'file_excel.required' =>
            'File Excel wajib dipilih.',

            'file_excel.file' =>
            'File yang diupload tidak valid.',

            'file_excel.mimes' =>
            'File harus berformat .xlsx atau .xls.',

            'file_excel.max' =>
            'Ukuran file maksimal 5 MB.',
        ]);

        try {

            Excel::import(
                new StudentImport,
                $request->file('file_excel')
            );

            return redirect()
                ->route('mahasiswa.import')
                ->with(
                    'success',
                    'Data akun mahasiswa berhasil diimport.'
                );
        } catch (ValidationException $e) {

            $errors = [];

            foreach ($e->failures() as $failure) {

                $errors[] = [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values(),
                ];
            }

            return redirect()
                ->route('mahasiswa.import')
                ->with('error', $errors);
        } catch (\Throwable $e) {

            report($e);

            return redirect()
                ->route('mahasiswa.import')
                ->with(
                    'error',
                    'Import gagal. Terjadi kesalahan saat memproses file Excel.'
                );
        }
    }
}
