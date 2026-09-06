<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Pengajaran;
use App\Models\Student;
use App\Models\Kelas;
use App\Models\PengajaranDosen;
use App\Models\PengajaranMahasiswa;
use Illuminate\Support\Facades\Auth;
use App\Models\SesiAbsensi;
use App\Models\Tugas;

class PengajaranController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | TAMBAH MATA KULIAH / DOSEN
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'lecturer_id' => [
                'required',
                'exists:lecturer,id',
            ],

            'kode_mk' => [
                'required',
                'exists:matakuliah,kode_mk',
            ],
        ]);

        try {

            $hasil = DB::transaction(function () use ($request) {

                /*
                 * Cari kelas berdasarkan kode MK
                 */
                $kelas = Kelas::where(
                    'kode_mk',
                    $request->kode_mk
                )->first();


                /*
                 * Kalau kelas belum ada,
                 * buat kelas A
                 */
                if (!$kelas) {

                    $jumlahKelas = Kelas::where(
                        'kode_mk',
                        $request->kode_mk
                    )->count();

                    $kodeKelas = chr(65 + $jumlahKelas);

                    $kelas = Kelas::create([
                        'kode_mk'    => $request->kode_mk,
                        'kode_kelas' => $kodeKelas,
                    ]);
                }


                /*
                 * Cek dosen sudah ada atau belum
                 */
                $sudahAda = PengajaranDosen::where(
                    'dosen_id',
                    $request->lecturer_id
                )
                    ->where(
                        'kelas_id',
                        $kelas->id
                    )
                    ->exists();


                if ($sudahAda) {

                    return [
                        'success' => false,
                        'message' =>
                        'Dosen tersebut sudah terdaftar pada kelas ini.',
                    ];
                }


                /*
                 * Simpan dosen ke kelas
                 */
                PengajaranDosen::create([
                    'dosen_id' => $request->lecturer_id,
                    'kelas_id' => $kelas->id,
                ]);


                return [
                    'success' => true,
                    'message' =>
                    'Mata kuliah berhasil ditambahkan.',
                ];
            });


            return response()->json($hasil);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' =>
                'Gagal menyimpan data.',
                'error' =>
                $e->getMessage(),
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN MATA KULIAH DOSEN
    |--------------------------------------------------------------------------
    */

    public function matakuliah($lecturer)
    {
        try {

            $pengajaranDosen = PengajaranDosen::with([
                'kelas.matakuliah'
            ])
                ->where('dosen_id', $lecturer)
                ->get();


            $data = $pengajaranDosen->map(function ($item) {

                return [

                    'kelas_id' =>
                    $item->kelas->id,

                    'kode_mk' =>
                    $item->kelas->matakuliah->kode_mk,

                    'nama_mk' =>
                    $item->kelas->matakuliah->nama_mk,

                    'sks' =>
                    $item->kelas->matakuliah->sks,

                    'kode_kelas' =>
                    $item->kelas->kode_kelas,

                ];
            });


            return response()->json($data);
        } catch (\Exception $e) {

            return response()->json([

                'message' =>
                'Gagal mengambil data mata kuliah.',

                'error' =>
                $e->getMessage(),

            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | HALAMAN PESERTA MATA KULIAH
    |--------------------------------------------------------------------------
    */

    public function show_mk()
    {
        $pengajaran = PengajaranDosen::with([

            'lecturer.user',

            'lecturer.prodi',

            'kelas.matakuliah',

        ])
            ->latest()
            ->get();


        return view(
            'admin.peserta-mk',
            compact('pengajaran')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH MAHASISWA
    |--------------------------------------------------------------------------
    */

    public function searchStudents(Request $request, Kelas $kelas)
    {
        try {

            $search = trim($request->get('search', ''));

            $students = Student::with([
                'user',
                'prodi'
            ])
                ->when($search, function ($query) use ($search) {

                    $query->where(function ($q) use ($search) {

                        $q->where('nim', 'like', "%{$search}%")
                            ->orWhereHas('user', function ($user) use ($search) {

                                $user->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                );
                            });
                    });
                })
                ->orderBy('nim')
                ->limit(20)
                ->get();

            return response()->json($students);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data mahasiswa.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function tambahPeserta(Request $request, Kelas $kelas)
    {
        $request->validate([
            'student_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'student_ids.*' => [
                'integer',
                'exists:students,id',
            ],
        ]);

        $pengajaran = Kelas::where(
            'id',
            $kelas->id
        )->firstOrFail();

        $data = collect($request->student_ids)
            ->unique()
            ->map(function ($studentId) use ($pengajaran) {

                return [
                    'kelas_id' => $pengajaran->id,
                    'mahasiswa_id' => $studentId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })
            ->toArray();

        PengajaranMahasiswa::insertOrIgnore($data);

        return response()->json([
            'success' => true,
            'message' => count($data)
                . ' mahasiswa berhasil ditambahkan.',
        ]);
    }
    /*
    |--------------------------------------------------------------------------
    | MATA KULIAH SAYA
    |--------------------------------------------------------------------------
    */

    public function mk_saya()
    {
        $kelas = Kelas::with([
            'pengajaranDosen.lecturer', //ambil fungsi lecturer dari pengajaranDosen
            'matakuliah'
        ])->get();


        return view(
            'lecturer.matakuliah-saya',
            compact('kelas')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL PENGAJARAN
    |--------------------------------------------------------------------------
    */



    public function show($id)
    {
        $kelas = Kelas::with([
            'pengajaranDosen.lecturer.user', // untuk daftar nama dosen pengampu, ditampilkan di @section('ketjudul')
            'matakuliah',
            'mahasiswa.user', // <-- ini yang baru
        ])
            ->findOrFail($id); // id kelas

        // Cari dosen yang sedang login
        $lecturer = Auth::user()->lecturer;

        if (!$lecturer) {
            abort(403, 'Akun ini tidak terdaftar sebagai dosen.');
        }

        // Cari record pengajaran_dosen milik dosen ini untuk kelas ini
        $pengajaranDosen = PengajaranDosen::with([
            'materi.files' => function ($query) {
                $query->orderBy('urutan');
            }
        ])
            ->where('kelas_id', $kelas->id)
            ->where('dosen_id', $lecturer->id)
            ->first();

        if (!$pengajaranDosen) {
            // dosen ini tidak mengajar di kelas ini, jangan izinkan akses
            abort(403, 'Anda tidak mengajar di kelas ini.');
        }

        // urutkan materi terbaru di atas (opsional, sesuaikan selera)
        $materiList = $pengajaranDosen->materi()
            ->with('files')
            ->orderBy('urutan')
            ->orderByDesc('created_at')
            ->get();

        $sesiAbsensiList = SesiAbsensi::where('kelas_id', $kelas->id)
            ->latest('dibuka_pada')
            ->get();

        // ...kode yang sudah ada (ambil $pengajaran, $materiList, $sesiAbsensiList, dll)

        $quizList = \App\Models\Quiz::where('pengajaran_dosen_id', $pengajaranDosen->id)
            ->withCount('questions')
            ->latest()
            ->get();

        $tugasList = Tugas::where('pengajaran_dosen_id', $pengajaranDosen->id)
            ->withCount('jawaban')
            ->latest()
            ->get();

        return view(
            'lecturer.show',
            [
                'pengajaran' => $kelas,               // dipakai untuk info kelas & mata kuliah
                'pengajaranDosen' => $pengajaranDosen, // dipakai untuk link Tambah Materi
                'materiList' => $materiList,           // dipakai untuk render daftar materi
                'sesiAbsensiList' => $sesiAbsensiList, // <-- tambahan
                'quizList' => $quizList,
                'tugasList' => $tugasList // tambahkan ini
            ]
        );
    }
    public function daftarPeserta(Kelas $kelas)
    {
        try {

            $peserta = PengajaranMahasiswa::with([
                'student.user',
                'student.prodi',
            ])
                ->where('kelas_id', $kelas->id)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $peserta,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil daftar peserta.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil dihapus'
        ]);
    }
}
