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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\PasswordController;
use App\Models\Absensi;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.lamandepan');
});

use Illuminate\Support\Facades\Auth;

Route::get('/dashboard', function () {

    $user = Auth::user();

    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'lecturer' => redirect()->route('lecturer.dashboard'),
        'student' => redirect()->route('student.dashboard'),
        default => abort(403),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])
    ->middleware(['auth', 'role:admin'])->name('admin.dashboard');

Route::get('/lecturer/dashboard', [DashboardController::class, 'lecturerDashboard'])
    ->middleware(['auth', 'role:lecturer'])->name('lecturer.dashboard');

Route::get('/student/dashboard', [DashboardController::class, 'studentDashboard'])
    ->middleware(['auth', 'role:student'])->name('student.dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/jadwal-kuliah', [DashboardController::class, 'schedule'])->name('schedule.index');
    Route::get('/pesan', [DashboardController::class, 'messages'])->name('messages.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    // ============================================================
    // PROSES TAMBAH AKUN DOSEN
    // ============================================================
    Route::post('/dosen', [AccountController::class, 'store'])
        ->name('admin.dosen.buatAkun');

    Route::post('/dosen/import', [AccountController::class, 'import'])
        ->name('admin.dosen.import.process');


    // ============================================================
    // PROSES TAMBAH AKUN MAHASISWA
    // ============================================================
    Route::post('/mahasiswa', [AccountController::class, 'store_mahasiswa'])
        ->name('admin.mahasiswa.buatAkun');

    Route::post('/mahasiswa/import', [AccountController::class, 'importStudent'])
        ->name('admin.mahasiswa.import.process');


    // ============================================================
    // PROSES TAMBAH MATAKULIAH
    // ============================================================
    Route::post('/matakuliah', [MatakuliahController::class, 'storeMatkul'])
        ->name('admin.tambah.matkul');
});

Route::get('/akun_dosen', [AccountController::class, 'index'])->middleware(['auth','role:admin'])->name('akun_dosen.index');

Route::get('/akun/dosen/import', [AccountController::class, 'import_dosen'])->middleware(['auth','role:admin'])->name('dosen.import');

Route::get('/akun_mahasiswa', [AccountController::class, 'index_mahasiswa'])->middleware(['auth','role:admin'])->name('akun_mahasiswa.index');

Route::get('/akun/mahasiswa/import', [AccountController::class, 'import_mahasiswa'])->middleware(['auth','role:admin'])->name('mahasiswa.import');

Route::get('/matakuliah', [MatakuliahController::class, 'index'])->middleware(['auth','role:admin'])->name('matakuliah.index');

Route::get('/penugasan_mk', [MatakuliahController::class, 'dosen_dan_mhs'])->middleware(['auth','role:admin'])->name('matakuliah.pengampu');

Route::get('/dosen/{prodi}', [DosenController::class, 'show'])->middleware(['auth','role:admin'])->name('dosen.prodi');

Route::get(
    '/matakuliah/search',
    [MatakuliahController::class, 'search']
)->middleware(['auth','role:admin'])->name('admin.matakuliah.search');

Route::post(
    '/pengajaran',
    [PengajaranController::class, 'store']
)->middleware(['auth','role:admin'])->name('admin.pengajaran.store');

Route::get(
    '/pengajaran/{lecturer}/matakuliah',
    [PengajaranController::class, 'matakuliah']
)->middleware(['auth','role:admin'])->name('admin.pengajaran.matakuliah');

Route::get('/peserta_mk', [PengajaranController::class, 'show_mk'])->middleware(['auth','role:admin'])->name('peserta.mk');



Route::post(
    '/admin/pengajaran/{pengajaran}/peserta',
    [PengajaranController::class, 'tambahPeserta']
)->middleware(['auth','role:admin'])->name('admin.pengajaran.peserta.store');


Route::get('/matakuliahsaya', [PengajaranController::class, 'mk_saya'])
    ->middleware(['auth','role:lecturer'])->name('matakuliah.ampu');

Route::get('/pengajaran/{id}', [PengajaranController::class, 'show'])
    ->middleware('auth')->name('pengajaran.show');
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
Route::delete('/kelas/{id}', [PengajaranController::class, 'destroy'])->middleware(['auth','role:admin'])->name('kelas.destroy');



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
Route::middleware(['auth', 'role:lecturer'])->prefix('lecturer')->name('lecturer.')->group(function () {

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
    ->middleware(['auth','role:student'])
    ->name('mahasiswa.absensi.scan');

Route::middleware(['auth', 'role:lecturer'])->prefix('quiz')->name('lecturer.quiz.')->group(function () {
    Route::get('/{pengajaranDosen}', [QuizController::class, 'index'])->name('index');
    Route::get('/{pengajaranDosen}/create', [QuizController::class, 'create'])->name('create');
    Route::post('/{pengajaranDosen}', [QuizController::class, 'store'])->name('store');
    Route::get('/{quiz}/template', [QuizController::class, 'downloadTemplate'])->name('template');
    Route::post('/{quiz}/import', [QuizController::class, 'import'])->name('import');
    Route::get('/detail/{quiz}', [QuizController::class, 'show'])->name('show');
    Route::patch('/{quiz}/publish', [QuizController::class, 'publish'])->name('publish');
    Route::delete('/{quiz}', [QuizController::class, 'destroy'])->name('destroy');
});

Route::post('lecturer/quiz/question/{quizQuestion}/gambar', [QuizController::class, 'uploadGambarSoal'])
    ->middleware(['auth','role:lecturer'])->name('lecturer.quiz.question.gambar');


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
});

Route::get('lecturer/tugas-kuis', [DashboardController::class, 'lecturerTasks'])
    ->middleware(['auth', 'role:lecturer'])->name('lecturer.tasks.index');
Route::get('lecturer/nilai', [DashboardController::class, 'lecturerGrades'])
    ->middleware(['auth', 'role:lecturer'])->name('lecturer.grades');

Route::get('student/tugas-kuis', [DashboardController::class, 'studentTasks'])
    ->middleware(['auth', 'role:student'])->name('student.tasks.index');
Route::get('student/nilai', [DashboardController::class, 'studentGrades'])
    ->middleware(['auth', 'role:student'])->name('student.grades');

Route::get('student/matakuliah', [MatakuliahController::class, 'index_mhs'])
    ->middleware(['auth', 'role:student'])->name('student.matakuliah.index');

Route::get('student/matakuliah/{kelas}', [MatakuliahController::class, 'show'])
    ->middleware(['auth', 'role:student'])->name('student.matakuliah.show');

Route::get('student/quiz/{quiz}', [StudentQuizController::class, 'show'])
    ->middleware(['auth', 'role:student'])->name('student.quiz.show');

Route::post('student/quiz/{quiz}/submit', [StudentQuizController::class, 'submit'])
    ->middleware(['auth', 'role:student'])->name('student.quiz.submit');

Route::get('student/absensi/scan', [StudentAbsensiController::class, 'scan'])
    ->middleware(['auth', 'role:student'])->name('student.absensi.scan');

Route::post('student/absensi/absen', [StudentAbsensiController::class, 'absen'])
    ->middleware(['auth', 'role:student'])->name('student.absensi.absen');

Route::middleware(['auth', 'role:student']) // sesuaikan nama middleware Anda
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('tugas/{tugas}', [StudentTugasController::class, 'show'])->name('tugas.show');
        Route::post('tugas/{tugas}/submit', [StudentTugasController::class, 'submit'])->name('tugas.submit');
    });



    
require __DIR__ . '/auth.php';
