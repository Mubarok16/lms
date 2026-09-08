<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\MatakuliahController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\Lecturer\TugasController as LecturerTugasController;
use App\Http\Controllers\Student\TugasController as StudentTugasController;
use App\Http\Controllers\PengajaranController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\SesiAbsensiController;
use App\Http\Controllers\StudentQuizController;
use App\Http\Controllers\StudentAbsensiController;
use App\Models\Absensi;
use App\Models\Student;
use App\Models\Prodi;
use App\Models\Matakuliah;
use App\Models\Lecturer;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CourseChatController;
use App\Http\Controllers\JadwalMatakuliahController;
use App\Http\Controllers\Lecturer\AkademikController as LecturerAkademikController;
use App\Http\Controllers\Student\AkademikController as StudentAkademikController;
use App\Models\PengajaranMahasiswa;


Route::get('/', function () {

    // Statistik
    $jumlahMahasiswa = Student::count();
    $jumlahMatakuliah = Matakuliah::count();
    $jumlahProdi = Prodi::count();
    $jumlahDosen = Lecturer::count();

    // Data mata kuliah untuk hero
    $matakuliah = Matakuliah::with('prodi')
        ->take(2)
        ->get();

    // Kursus populer
    $kursusPopuler = PengajaranMahasiswa::query()
        ->with([
            'kelas.matakuliah.prodi'
        ])
        ->select('kelas_id')
        ->selectRaw('COUNT(DISTINCT mahasiswa_id) as jumlah_mahasiswa')
        ->groupBy('kelas_id')
        ->orderByDesc('jumlah_mahasiswa')
        ->take(3)
        ->get();

    return view('layouts.lamandepan', compact(
        'jumlahMahasiswa',
        'jumlahMatakuliah',
        'jumlahProdi',
        'jumlahDosen',
        'matakuliah',
        'kursusPopuler'
    ));
});


Route::get('/dashboard', function () {

    $user = Auth::user();

    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'lecturer' => redirect()->route('lecturer.dashboard'),
        'student' => redirect()->route('student.dashboard'),
        default => abort(403),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin/dashboard', function () {
    $totalMatkul    = Matakuliah::count();
    $totalDosen     = Lecturer::count();
    $totalMahasiswa = Student::count();

    return view('admin.dashboard', compact('totalMatkul', 'totalDosen', 'totalMahasiswa'));
})->middleware(['auth', 'role:admin'])->name('admin.dashboard');

Route::get('/lecturer/dashboard', function () {
    $totalMatkul    = Matakuliah::count();
    $totalDosen     = Lecturer::count();
    $totalMahasiswa = Student::count();

    return view('lecturer.dashboard', compact('totalMatkul', 'totalDosen', 'totalMahasiswa'));
})->middleware(['auth', 'role:lecturer'])->name('lecturer.dashboard');

Route::get('/student/dashboard', function () {
    $mahasiswa = Auth::user()->student;
    abort_unless($mahasiswa, 403, 'Akun ini tidak terdaftar sebagai mahasiswa.');

    $kelasIds = PengajaranMahasiswa::where('mahasiswa_id', $mahasiswa->id)->pluck('kelas_id');
    $pengajaranIds = \App\Models\PengajaranDosen::whereIn('kelas_id', $kelasIds)->pluck('id');

    $totalMatkul = $kelasIds->unique()->count();

    $pendingTugas = \App\Models\Tugas::whereIn('pengajaran_dosen_id', $pengajaranIds)
        ->whereDoesntHave('jawaban', fn ($q) => $q->where('mahasiswa_id', $mahasiswa->id))
        ->count();

    $pendingQuiz = \App\Models\Quiz::whereIn('pengajaran_dosen_id', $pengajaranIds)
        ->where('is_published', true)
        ->whereDoesntHave('jawaban', fn ($q) => $q->where('mahasiswa_id', $mahasiswa->id))
        ->count();

    $tugasAvg = \App\Models\TugasJawaban::where('mahasiswa_id', $mahasiswa->id)
        ->whereNotNull('skor')
        ->whereHas('tugas', fn ($q) => $q->whereIn('pengajaran_dosen_id', $pengajaranIds))
        ->avg('skor');

    $quizAvg = \App\Models\QuizJawaban::where('mahasiswa_id', $mahasiswa->id)
        ->whereNotNull('skor')
        ->whereHas('quiz', fn ($q) => $q->whereIn('pengajaran_dosen_id', $pengajaranIds))
        ->avg('skor');

    $nilaiRataRata = collect([$tugasAvg, $quizAvg])->filter(fn ($v) => $v !== null)->avg();

    $tugasTerbaru = \App\Models\Tugas::whereIn('pengajaran_dosen_id', $pengajaranIds)
        ->with('pengajaranDosen.kelas.matakuliah')
        ->whereDoesntHave('jawaban', fn ($q) => $q->where('mahasiswa_id', $mahasiswa->id))
        ->orderByRaw('CASE WHEN deadline IS NULL THEN 1 ELSE 0 END')
        ->orderBy('deadline')
        ->take(5)
        ->get();

    $quizTerbaru = \App\Models\Quiz::whereIn('pengajaran_dosen_id', $pengajaranIds)
        ->where('is_published', true)
        ->with('pengajaranDosen.kelas.matakuliah')
        ->whereDoesntHave('jawaban', fn ($q) => $q->where('mahasiswa_id', $mahasiswa->id))
        ->latest()
        ->take(5)
        ->get();

    return view('student.dashboard', compact(
        'totalMatkul',
        'pendingTugas',
        'pendingQuiz',
        'nilaiRataRata',
        'tugasTerbaru',
        'quizTerbaru'
    ));
})->middleware(['auth', 'role:student'])->name('student.dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    // ============================================================
    // PROSES TAMBAH AKUN DOSEN
    // ============================================================
    Route::post('/dosen', [AccountController::class, 'store'])
        ->name('admin.dosen.buatAkun');

    Route::post('/dosen/import', [AccountController::class, 'import'])
        ->name('admin.dosen.import.process');

    Route::post('/dosen/import/process', [AccountController::class, 'import'])
        ->name('dosen.import.process');


    // ============================================================
    // PROSES TAMBAH AKUN MAHASISWA
    // ============================================================
    Route::post('/mahasiswa', [AccountController::class, 'store_mahasiswa'])
        ->name('admin.mahasiswa.buatAkun');

    Route::post('/mahasiswa/import', [AccountController::class, 'importStudent'])
        ->name('admin.mahasiswa.import.process');

    Route::post('/mahasiswa/import/process', [AccountController::class, 'import'])
        ->name('mahasiswa.import.process');


    // ============================================================
    // PROSES TAMBAH MATAKULIAH
    // ============================================================
    Route::post('/matakuliah', [MatakuliahController::class, 'storeMatkul'])
        ->name('admin.tambah.matkul');
});

Route::get('/admin/matakuliah', [MatakuliahController::class, 'index'])->name('matakuliah.index');
Route::post('/admin/matakuliah', [MatakuliahController::class, 'store'])->name('admin.tambah.matkul');
Route::put('/admin/matakuliah/{matakuliah}', [MatakuliahController::class, 'update'])->name('matakuliah.update');
Route::delete('/admin/matakuliah/{matakuliah}', [MatakuliahController::class, 'destroy'])->name('matakuliah.destroy');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit_admin'])
        ->name('profile.edit'); // hasil akhir: lecturer.profile.edit
    Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'update_admin'])
        ->name('profile.update');
});
Route::get('/akun_dosen', [AccountController::class, 'index'])->name('akun_dosen.index');

Route::get('/akun/dosen/import', [AccountController::class, 'import_dosen'])->name('dosen.import');

Route::get('/akun_mahasiswa', [AccountController::class, 'index_mahasiswa'])->name('akun_mahasiswa.index');

Route::get('/akun/mahasiswa/import', [AccountController::class, 'import_mahasiswa'])->name('mahasiswa.import');

Route::get('/matakuliah', [MatakuliahController::class, 'index'])->name('matakuliah.index');

Route::get('/penugasan_mk', [MatakuliahController::class, 'dosen_dan_mhs'])->name('matakuliah.pengampu');

Route::get('/dosen/{prodi}', [DosenController::class, 'show'])->name('dosen.prodi');

Route::get(
    '/matakuliah/search',
    [MatakuliahController::class, 'search']
)->name('admin.matakuliah.search');

Route::post(
    '/pengajaran',
    [PengajaranController::class, 'store']
)->name('admin.pengajaran.store');

Route::get(
    '/pengajaran/{lecturer}/matakuliah',
    [PengajaranController::class, 'matakuliah']
)->name('admin.pengajaran.matakuliah');

Route::get('/peserta_mk', [PengajaranController::class, 'show_mk'])->name('peserta.mk');



Route::post(
    '/admin/pengajaran/{pengajaran}/peserta',
    [PengajaranController::class, 'tambahPeserta']
)->name('admin.pengajaran.peserta.store');


Route::get('/matakuliahsaya', [PengajaranController::class, 'mk_saya'])
    ->name('matakuliah.ampu');

Route::get('/pengajaran/{id}', [PengajaranController::class, 'show'])
    ->name('pengajaran.show');
Route::get(
    '/kelas/{kelas}/students',
    [PengajaranController::class, 'searchStudents']
)->name('kelas.students.search');

Route::get(
    '/kelas/{kelas}/students',
    [PengajaranController::class, 'searchStudents']
)->name('kelas.students');

Route::post(
    '/kelas/{kelas}/peserta',
    [PengajaranController::class, 'tambahPeserta']
)->name('kelas.peserta.store');

Route::get(
    '/kelas/{kelas}/peserta',
    [PengajaranController::class, 'daftarPeserta']
)->name('kelas.peserta');

// routes/web.php
Route::delete('/kelas/{id}', [PengajaranController::class, 'destroy'])->name('kelas.destroy');



Route::middleware(['auth', 'role:lecturer'])->prefix('lecturer')->name('lecturer.')->group(function () {

    // Form tambah materi (untuk pengajaran tertentu)
    Route::get('/pengajaran/{pengajaran}/materi/create', [MateriController::class, 'create'])
        ->name('materi.create');

    // Simpan materi baru
    Route::post('/pengajaran/{pengajaran}/materi', [MateriController::class, 'store'])
        ->name('materi.store');
    // Form edit materi
    Route::get('/materi/{materi}/edit', [MateriController::class, 'edit'])
        ->name('materi.edit');

    // Update materi
    Route::put('/materi/{materi}', [MateriController::class, 'update'])
        ->name('materi.update');
    // Hapus materi
    Route::delete('/materi/{materi}', [MateriController::class, 'destroy'])
        ->name('materi.destroy');
});

// =========================================================
// DOSEN — kelola sesi absensi
// =========================================================
Route::middleware('auth')->prefix('lecturer')->name('lecturer.')->group(function () {

    Route::post('pengajaran/{pengajaran}/absensi', [SesiAbsensiController::class, 'store'])
        ->name('absensi.store');

    Route::get('absensi/{sesi}', [SesiAbsensiController::class, 'show'])
        ->name('absensi.show');

    Route::post('absensi/{sesi}/tutup', [SesiAbsensiController::class, 'tutup'])
        ->name('absensi.tutup');

    Route::get('absensi/{sesi}/count', [SesiAbsensiController::class, 'count'])
        ->name('absensi.count');

    Route::get('absensi/{sesi}/rekap', [SesiAbsensiController::class, 'rekap'])
        ->name('absensi.rekap');
});

// =========================================================
// MAHASISWA — scan QR absensi
// =========================================================
Route::get('absensi/scan/{token}', [AbsensiController::class, 'scan'])
    ->middleware('auth')
    ->name('mahasiswa.absensi.scan');

Route::middleware(['auth', 'role:lecturer'])->prefix('quiz')->name('lecturer.quiz.')->group(function () {
    Route::get('/{pengajaranDosen}', [QuizController::class, 'index'])->name('index');
    Route::get('/{pengajaranDosen}/create', [QuizController::class, 'create'])->name('create');
    Route::post('/{pengajaranDosen}', [QuizController::class, 'store'])->name('store');
    Route::get('/{quiz}/template', [QuizController::class, 'downloadTemplate'])->name('template');
    Route::post('/{quiz}/import', [QuizController::class, 'import'])->name('import');
    Route::get('/detail/{quiz}', [QuizController::class, 'show'])->name('show');
    Route::patch('/{quiz}/publish', [QuizController::class, 'publish'])->name('publish');
});

Route::post('lecturer/quiz/question/{quizQuestion}/gambar', [QuizController::class, 'uploadGambarSoal'])
    ->middleware(['auth', 'role:lecturer'])
    ->name('lecturer.quiz.question.gambar');


Route::middleware(['auth', 'role:lecturer'])->prefix('lecturer')->name('lecturer.')->group(function () {
    // ...route lain yang sudah ada (materi, quiz, dll)

    Route::get('tugas/create/{pengajaranDosen}', [LecturerTugasController::class, 'create'])
        ->name('tugas.create');

    Route::post('tugas/{pengajaranDosen}', [LecturerTugasController::class, 'store'])
        ->name('tugas.store');

    Route::get('tugas/{tugas}/edit', [LecturerTugasController::class, 'edit'])
        ->name('tugas.edit');

    Route::put('tugas/{tugas}', [LecturerTugasController::class, 'update'])
        ->name('tugas.update');

    Route::delete('tugas/{tugas}', [LecturerTugasController::class, 'destroy'])
        ->name('tugas.destroy');

    // Kalau route show belum ada di tempat lain, tambahkan ini:
    Route::get('tugas/{tugas}', [LecturerTugasController::class, 'show'])
        ->name('tugas.show');

    // Koreksi jawaban mahasiswa
    Route::get('tugas/{tugas}/jawaban', [LecturerTugasController::class, 'jawabanIndex'])
        ->name('tugas.jawaban.index');

    Route::get('tugas/{tugas}/jawaban/{jawaban}', [LecturerTugasController::class, 'jawabanShow'])
        ->name('tugas.jawaban.show');

    Route::post('tugas/{tugas}/jawaban/{jawaban}/koreksi', [LecturerTugasController::class, 'koreksi'])
        ->name('tugas.jawaban.koreksi');
    Route::get('/profile', [ProfileController::class, 'edit_lecturer'])
        ->name('profile.edit'); // hasil akhir: lecturer.profile.edit
    Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'update_lecturer'])
        ->name('profile.update');
});
Route::get('lecturer/tugas/{tugas}', [LecturerTugasController::class, 'show'])
    ->middleware(['auth', 'role:lecturer'])
    ->name('lecturer.tugas.show');


Route::get('student/matakuliah', [MatakuliahController::class, 'index_mhs'])
    ->name('student.matakuliah.index');

Route::get('student/matakuliah/{kelas}', [MatakuliahController::class, 'show'])
    ->name('student.matakuliah.show');

Route::get('student/quiz/{quiz}', [StudentQuizController::class, 'show'])
    ->middleware(['auth', 'role:student'])
    ->name('student.quiz.show');

Route::post('student/quiz/{quiz}/submit', [StudentQuizController::class, 'submit'])
    ->middleware(['auth', 'role:student'])
    ->name('student.quiz.submit');

Route::get('student/absensi/scan', [StudentAbsensiController::class, 'scan'])
    ->name('student.absensi.scan');

Route::post('student/absensi/absen', [StudentAbsensiController::class, 'absen'])
    ->name('student.absensi.absen');

Route::middleware(['auth', 'role:student']) // sesuaikan nama middleware Anda
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('tugas/{tugas}', [StudentTugasController::class, 'show'])->name('tugas.show');
        Route::post('tugas/{tugas}/submit', [StudentTugasController::class, 'submit'])->name('tugas.submit');
    });


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::put('/password', [ProfileController::class, 'updatePassword'])
        ->name('password.update');
});


// admin edit dan delete akun dosen
Route::put('/admin/akun-dosen/{id}', [AccountController::class, 'update'])
    ->name('admin.dosen.update');
Route::delete('/admin/akun-dosen/{id}', [AccountController::class, 'destroy'])
    ->name('admin.dosen.destroy');
Route::get('/admin/akun-dosen/{id}', [AccountController::class, 'show'])
    ->name('admin.dosen.show');

// admin edit dan delete akun mhs
Route::put('/admin/akun-mahasiswa/{id}', [AccountController::class, 'update_mahasiswa'])
    ->name('admin.mahasiswa.update');
Route::delete('/admin/akun-mahasiswa/{id}', [AccountController::class, 'destroy_mahasiswa'])
    ->name('admin.mahasiswa.destroy');


Route::get('/lecturer/pengajaran-dosen/{pengajaranDosen}/rekap-nilai', [
    \App\Http\Controllers\PengajaranController::class,
    'rekapNilai',
])->name('lecturer.rekap.nilai');


Route::get(
    '/lecturer/pengajaran/{pengajaranDosen}/absensi/rekap',
    [AbsensiController::class, 'rekapSemua']
)->name('lecturer.absensi.rekapSemua');

Route::get(
    '/lecturer/pengajaran/{pengajaranDosen}/absensi/rekap/export',
    [AbsensiController::class, 'exportRekap']
)->name('lecturer.absensi.rekapSemua.export');


// =========================================================
// CHAT MATA KULIAH & JADWAL
// =========================================================
Route::middleware('auth')->group(function () {
    Route::get('/chat', [CourseChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{kelas}', [CourseChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{kelas}', [CourseChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/{kelas}/messages', [CourseChatController::class, 'messages'])->name('chat.messages');

    Route::get('/jadwal', [JadwalMatakuliahController::class, 'index'])->name('jadwal.index');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/jadwal', [JadwalMatakuliahController::class, 'store'])->name('jadwal.store');
    Route::put('/jadwal/{jadwal}', [JadwalMatakuliahController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{jadwal}', [JadwalMatakuliahController::class, 'destroy'])->name('jadwal.destroy');
    Route::post('/jadwal/import', [JadwalMatakuliahController::class, 'import'])->name('jadwal.import');
    Route::get('/jadwal/template', [JadwalMatakuliahController::class, 'template'])->name('jadwal.template');
});


// =========================================================
// AKADEMIK DOSEN — TUGAS, QUIZ, NILAI
// =========================================================
Route::middleware(['auth', 'role:lecturer'])->prefix('lecturer')->name('lecturer.akademik.')->group(function () {
    Route::get('/tugas-menu', [LecturerAkademikController::class, 'tugasCourses'])->name('tugas.courses');
    Route::get('/tugas-menu/{pengajaranDosen}', [LecturerAkademikController::class, 'tugasList'])->name('tugas.list');

    Route::get('/quiz-menu', [LecturerAkademikController::class, 'quizCourses'])->name('quiz.courses');
    Route::get('/quiz-menu/{pengajaranDosen}', [LecturerAkademikController::class, 'quizList'])->name('quiz.list');
    Route::get('/quiz-jawaban/{quiz}', [LecturerAkademikController::class, 'quizJawabanIndex'])->name('quiz.jawaban.index');
    Route::get('/quiz-jawaban/{quiz}/{jawaban}', [LecturerAkademikController::class, 'quizJawabanShow'])->name('quiz.jawaban.show');

    Route::get('/nilai-menu', [LecturerAkademikController::class, 'nilaiCourses'])->name('nilai.courses');
    Route::get('/nilai-menu/{pengajaranDosen}', [LecturerAkademikController::class, 'nilaiStudents'])->name('nilai.students');
    Route::get('/nilai-menu/{pengajaranDosen}/mahasiswa/{student}', [LecturerAkademikController::class, 'nilaiStudent'])->name('nilai.student');
});

// =========================================================
// AKADEMIK MAHASISWA — TUGAS, QUIZ, NILAI
// =========================================================
Route::middleware(['auth', 'role:student'])->prefix('student/akademik')->name('student.akademik.')->group(function () {
    Route::get('/tugas', [StudentAkademikController::class, 'tugasIndex'])->name('tugas.index');
    Route::get('/tugas/kelas/{kelas}', [StudentAkademikController::class, 'tugasKelas'])->name('tugas.kelas');

    Route::get('/quiz', [StudentAkademikController::class, 'quizIndex'])->name('quiz.index');
    Route::get('/quiz/kelas/{kelas}', [StudentAkademikController::class, 'quizKelas'])->name('quiz.kelas');

    Route::get('/nilai', [StudentAkademikController::class, 'nilaiIndex'])->name('nilai.index');
    Route::get('/nilai/kelas/{kelas}', [StudentAkademikController::class, 'nilaiKelas'])->name('nilai.kelas');
});

require __DIR__ . '/auth.php';
