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

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'role:admin'])->name('admin.dashboard');

Route::get('/lecturer/dashboard', function () {
    return view('lecturer.dashboard');
})->middleware(['auth', 'role:lecturer'])->name('lecturer.dashboard');

Route::get('/student/dashboard', function () {
    return view('student.dashboard');
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



Route::prefix('lecturer')->name('lecturer.')->group(function () {

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

Route::prefix('quiz')->name('lecturer.quiz.')->group(function () {
    Route::get('/{pengajaranDosen}', [QuizController::class, 'index'])->name('index');
    Route::get('/{pengajaranDosen}/create', [QuizController::class, 'create'])->name('create');
    Route::post('/{pengajaranDosen}', [QuizController::class, 'store'])->name('store');
    Route::get('/{quiz}/template', [QuizController::class, 'downloadTemplate'])->name('template');
    Route::post('/{quiz}/import', [QuizController::class, 'import'])->name('import');
    Route::get('/detail/{quiz}', [QuizController::class, 'show'])->name('show');
    Route::patch('/{quiz}/publish', [QuizController::class, 'publish'])->name('publish');
});

Route::post('lecturer/quiz/question/{quizQuestion}/gambar', [QuizController::class, 'uploadGambarSoal'])
    ->name('lecturer.quiz.question.gambar');


Route::prefix('lecturer')->name('lecturer.')->group(function () {
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
Route::get('lecturer/tugas/{tugas}', [LecturerTugasController::class, 'show'])
    ->name('lecturer.tugas.show');


Route::get('student/matakuliah', [MatakuliahController::class, 'index_mhs'])
    ->name('student.matakuliah.index');

Route::get('student/matakuliah/{kelas}', [MatakuliahController::class, 'show'])
    ->name('student.matakuliah.show');

Route::get('student/quiz/{quiz}', [StudentQuizController::class, 'show'])
    ->name('student.quiz.show');

Route::post('student/quiz/{quiz}/submit', [StudentQuizController::class, 'submit'])
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

    
require __DIR__ . '/auth.php';
