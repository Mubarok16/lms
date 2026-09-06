<?php

use App\Http\Controllers\AcademicController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MatakuliahController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PengajaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\SesiAbsensiController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\Student\TugasController as StudentTugasController;
use App\Http\Controllers\StudentAbsensiController;
use App\Http\Controllers\StudentQuizController;
use App\Http\Controllers\Lecturer\TugasController as LecturerTugasController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('layouts.lamandepan'))->name('home');
Route::get('/info/{page}', [SiteController::class, 'info'])->name('site.info');
Route::get('/absensi/scan/{token}', [AbsensiController::class, 'scan'])->middleware(['auth','verified','role:student'])->name('mahasiswa.absensi.scan');

Route::get('/dashboard', function () {
    return match (Auth::user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'lecturer' => redirect()->route('lecturer.dashboard'),
        'student' => redirect()->route('student.dashboard'),
        default => abort(403),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    Route::get('/jadwal-kuliah', [AcademicController::class, 'schedule'])->name('jadwal.index');
    Route::get('/pesan', [MessageController::class, 'index'])->name('messages.index');
    Route::patch('/pesan/{message}/read', [MessageController::class, 'read'])->name('messages.read');
});

Route::middleware(['auth','verified','role:admin'])->post('/pesan', [MessageController::class, 'store'])->name('messages.store');

// ==================== ADMIN ====================
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit_admin'])->name('profile.edit');
    Route::match(['put','patch'], '/profile', [ProfileController::class, 'update_admin'])->name('profile.update');

    Route::get('/akun/dosen', [AccountController::class, 'index'])->name('akun.dosen.index');
    Route::get('/akun/dosen/import', [AccountController::class, 'import_dosen'])->name('akun.dosen.import');
    Route::get('/akun/dosen/import-page', [AccountController::class, 'import_dosen'])->name('dosen.import');
    Route::post('/akun/dosen', [AccountController::class, 'store'])->name('dosen.buatAkun');
    Route::get('/akun/dosen/{lecturer}/edit', [AccountController::class, 'editLecturer'])->name('dosen.edit');
    Route::put('/akun/dosen/{lecturer}', [AccountController::class, 'updateLecturer'])->name('dosen.update');
    Route::delete('/akun/dosen/{lecturer}', [AccountController::class, 'destroyLecturer'])->name('dosen.destroy');
    Route::post('/akun/dosen/import', [AccountController::class, 'import'])->name('dosen.import.process');

    Route::get('/akun/mahasiswa', [AccountController::class, 'index_mahasiswa'])->name('akun.mahasiswa.index');
    Route::get('/akun/mahasiswa/import', [AccountController::class, 'import_mahasiswa'])->name('mahasiswa.import');
    Route::post('/akun/mahasiswa', [AccountController::class, 'store_mahasiswa'])->name('mahasiswa.buatAkun');
    Route::post('/akun/mahasiswa/import', [AccountController::class, 'importStudent'])->name('mahasiswa.import.process');
    Route::get('/akun/mahasiswa/{student}/edit', [AccountController::class, 'editStudent'])->name('mahasiswa.edit');
    Route::put('/akun/mahasiswa/{student}', [AccountController::class, 'updateStudent'])->name('mahasiswa.update');
    Route::delete('/akun/mahasiswa/{student}', [AccountController::class, 'destroyStudent'])->name('mahasiswa.destroy');

    Route::get('/matakuliah', [MatakuliahController::class, 'index'])->name('matakuliah.index');
    Route::post('/matakuliah', [MatakuliahController::class, 'storeMatkul'])->name('tambah.matkul');
    Route::get('/matakuliah/search', [MatakuliahController::class, 'search'])->name('matakuliah.search');
    Route::get('/penugasan-mk', [MatakuliahController::class, 'dosen_dan_mhs'])->name('matakuliah.pengampu');
    Route::get('/dosen/{prodi}', [DosenController::class, 'show'])->name('dosen.prodi');

    Route::post('/pengajaran', [PengajaranController::class, 'store'])->name('pengajaran.store');
    Route::get('/pengajaran/{lecturer}/matakuliah', [PengajaranController::class, 'matakuliah'])->name('pengajaran.matakuliah');
    Route::get('/peserta-mk', [PengajaranController::class, 'show_mk'])->name('peserta.mk');
    Route::post('/pengajaran/{pengajaran}/peserta', [PengajaranController::class, 'tambahPeserta'])->name('pengajaran.peserta.store');
    Route::get('/kelas/{kelas}/students', [PengajaranController::class, 'searchStudents'])->name('kelas.students');
    Route::post('/kelas/{kelas}/peserta', [PengajaranController::class, 'tambahPeserta'])->name('kelas.peserta.store');
    Route::get('/kelas/{kelas}/peserta', [PengajaranController::class, 'daftarPeserta'])->name('kelas.peserta');
    Route::delete('/kelas/{id}', [PengajaranController::class, 'destroy'])->name('kelas.destroy');

    Route::get('/jadwal-kuliah', [AcademicController::class, 'schedule'])->name('jadwal.index');
    Route::post('/jadwal-kuliah', [AcademicController::class, 'storeSchedule'])->name('jadwal.store');
    Route::get('/jadwal-kuliah/{jadwal}/edit', [AcademicController::class, 'editSchedule'])->name('jadwal.edit');
    Route::put('/jadwal-kuliah/{jadwal}', [AcademicController::class, 'updateSchedule'])->name('jadwal.update');
    Route::delete('/jadwal-kuliah/{jadwal}', [AcademicController::class, 'destroySchedule'])->name('jadwal.destroy');
});

// Backward-compatible names used by existing views, now protected by admin middleware.
Route::middleware(['auth','verified','role:admin'])->group(function () {
    Route::get('/akun_dosen', [AccountController::class, 'index'])->name('akun_dosen.index');
    Route::get('/akun_mahasiswa', [AccountController::class, 'index_mahasiswa'])->name('akun_mahasiswa.index');
    Route::get('/matakuliah', [MatakuliahController::class, 'index'])->name('matakuliah.index');
    Route::get('/penugasan_mk', [MatakuliahController::class, 'dosen_dan_mhs'])->name('matakuliah.pengampu');
    Route::get('/dosen/{prodi}', [DosenController::class, 'show'])->name('dosen.prodi');
    Route::get('/matakuliah/search', [MatakuliahController::class, 'search'])->name('admin.matakuliah.search');
    Route::post('/pengajaran', [PengajaranController::class, 'store'])->name('admin.pengajaran.store');
    Route::get('/pengajaran/{lecturer}/matakuliah', [PengajaranController::class, 'matakuliah'])->name('admin.pengajaran.matakuliah');
    Route::get('/peserta_mk', [PengajaranController::class, 'show_mk'])->name('peserta.mk');
    Route::post('/admin/pengajaran/{pengajaran}/peserta', [PengajaranController::class, 'tambahPeserta'])->name('admin.pengajaran.peserta.store');
    Route::get('/kelas/{kelas}/students/search', [PengajaranController::class, 'searchStudents'])->name('kelas.students.search');
});

// ==================== LECTURER ====================
Route::middleware(['auth','verified','role:lecturer'])->prefix('lecturer')->name('lecturer.')->group(function () {
    Route::view('/dashboard', 'lecturer.dashboard')->name('dashboard');
    Route::get('/matakuliah', [PengajaranController::class, 'mk_saya'])->name('matakuliah.index');
    Route::get('/pengajaran/{id}', [PengajaranController::class, 'show'])->name('pengajaran.show');

    Route::get('/pengajaran/{pengajaran}/materi/create', [MateriController::class, 'create'])->name('materi.create');
    Route::post('/pengajaran/{pengajaran}/materi', [MateriController::class, 'store'])->name('materi.store');
    Route::get('/materi/{materi}/edit', [MateriController::class, 'edit'])->name('materi.edit');
    Route::put('/materi/{materi}', [MateriController::class, 'update'])->name('materi.update');
    Route::delete('/materi/{materi}', [MateriController::class, 'destroy'])->name('materi.destroy');

    Route::post('/pengajaran/{pengajaran}/absensi', [SesiAbsensiController::class, 'store'])->name('absensi.store');
    Route::get('/absensi/{sesi}', [SesiAbsensiController::class, 'show'])->name('absensi.show');
    Route::post('/absensi/{sesi}/tutup', [SesiAbsensiController::class, 'tutup'])->name('absensi.tutup');
    Route::get('/absensi/{sesi}/count', [SesiAbsensiController::class, 'count'])->name('absensi.count');
    Route::get('/absensi/{sesi}/rekap', [SesiAbsensiController::class, 'rekap'])->name('absensi.rekap');

    Route::get('/quiz/{pengajaranDosen}', [QuizController::class, 'index'])->name('quiz.index');
    Route::get('/quiz/{pengajaranDosen}/create', [QuizController::class, 'create'])->name('quiz.create');
    Route::post('/quiz/{pengajaranDosen}', [QuizController::class, 'store'])->name('quiz.store');
    Route::get('/quiz/{quiz}/template', [QuizController::class, 'downloadTemplate'])->name('quiz.template');
    Route::delete('/quiz/{quiz}', [QuizController::class, 'destroy'])->name('quiz.destroy');
    Route::post('/quiz/{quiz}/import', [QuizController::class, 'import'])->name('quiz.import');
    Route::get('/quiz/detail/{quiz}', [QuizController::class, 'show'])->name('quiz.show');
    Route::get('/quiz/{quiz}/hasil/{jawaban}', [QuizController::class, 'hasilShow'])->name('quiz.hasil.show');
    Route::patch('/quiz/{quiz}/publish', [QuizController::class, 'publish'])->name('quiz.publish');
    Route::post('/quiz/question/{quizQuestion}/gambar', [QuizController::class, 'uploadGambarSoal'])->name('quiz.question.gambar');

    Route::get('/tugas-kuis', [AcademicController::class, 'lecturerTasks'])->name('tugas.kuis');
    Route::get('/nilai', [AcademicController::class, 'lecturerGrades'])->name('nilai');
    Route::get('/tugas/create/{pengajaranDosen}', [LecturerTugasController::class, 'create'])->name('tugas.create');
    Route::post('/tugas/{pengajaranDosen}', [LecturerTugasController::class, 'store'])->name('tugas.store');
    Route::get('/tugas/{tugas}/edit', [LecturerTugasController::class, 'edit'])->name('tugas.edit');
    Route::put('/tugas/{tugas}', [LecturerTugasController::class, 'update'])->name('tugas.update');
    Route::delete('/tugas/{tugas}', [LecturerTugasController::class, 'destroy'])->name('tugas.destroy');
    Route::get('/tugas/{tugas}/jawaban', [LecturerTugasController::class, 'jawabanIndex'])->name('tugas.jawaban.index');
    Route::get('/tugas/{tugas}/jawaban/{jawaban}', [LecturerTugasController::class, 'jawabanShow'])->name('tugas.jawaban.show');
    Route::post('/tugas/{tugas}/jawaban/{jawaban}/koreksi', [LecturerTugasController::class, 'koreksi'])->name('tugas.jawaban.koreksi');
    Route::get('/tugas/{tugas}', [LecturerTugasController::class, 'show'])->name('tugas.show');

    Route::get('/profile', [ProfileController::class, 'edit_lecturer'])->name('profile.edit');
    Route::match(['put','patch'], '/profile', [ProfileController::class, 'update_lecturer'])->name('profile.update');
});

// ==================== STUDENT ====================
Route::middleware(['auth','verified','role:student'])->prefix('student')->name('student.')->group(function () {
    Route::view('/dashboard', 'student.dashboard')->name('dashboard');
    Route::get('/matakuliah', [MatakuliahController::class, 'index_mhs'])->name('matakuliah.index');
    Route::get('/matakuliah/{kelas}', [MatakuliahController::class, 'show'])->name('matakuliah.show');
    Route::get('/tugas-kuis', [AcademicController::class, 'studentTasks'])->name('tugas.kuis');
    Route::get('/nilai', [AcademicController::class, 'studentGrades'])->name('nilai');
    Route::get('/quiz/{quiz}', [StudentQuizController::class, 'show'])->name('quiz.show');
    Route::post('/quiz/{quiz}/submit', [StudentQuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/tugas/{tugas}', [StudentTugasController::class, 'show'])->name('tugas.show');
    Route::post('/tugas/{tugas}/submit', [StudentTugasController::class, 'submit'])->name('tugas.submit');
    Route::get('/absensi/scan', [StudentAbsensiController::class, 'scan'])->name('absensi.scan');
    Route::post('/absensi/absen', [StudentAbsensiController::class, 'absen'])->name('absensi.absen');
});

require __DIR__.'/auth.php';
