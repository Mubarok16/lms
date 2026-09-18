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
use App\Http\Controllers\Lecturer\PengajuanController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $jumlahMahasiswa  = Student::count();
    $jumlahMatakuliah = Matakuliah::count();
    $jumlahProdi      = Prodi::count();
    $jumlahDosen      = Lecturer::count();

    $matakuliah = Matakuliah::with('prodi')->take(2)->get();

    $kursusPopuler = PengajaranMahasiswa::query()
        ->with(['kelas.matakuliah.prodi'])
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

/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT (semua role login lewat sini)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    $user = Auth::user();

    return match ($user->role) {
        'admin'    => redirect()->route('admin.dashboard'),
        'lecturer' => redirect()->route('lecturer.dashboard'),
        'student'  => redirect()->route('student.dashboard'),
        default    => abort(403),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| SHARED (butuh login, tapi tidak spesifik role)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Profile umum
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    // Chat & jadwal (lintas role)
    Route::get('/chat', [CourseChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{kelas}', [CourseChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{kelas}', [CourseChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/{kelas}/messages', [CourseChatController::class, 'messages'])->name('chat.messages');

    Route::get('/jadwal', [JadwalMatakuliahController::class, 'index'])->name('jadwal.index');

    // Link scan absensi via token (mis. dari QR fisik) — dibiarkan di luar prefix student
    // karena URL ini kemungkinan sudah tercetak/terdistribusi.
    Route::get('absensi/scan/{token}', [AbsensiController::class, 'scan'])->name('mahasiswa.absensi.scan');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard', function () {
        $totalMatkul    = Matakuliah::count();
        $totalDosen     = Lecturer::count();
        $totalMahasiswa = Student::count();

        return view('admin.dashboard', compact('totalMatkul', 'totalDosen', 'totalMahasiswa'));
    })->name('admin.dashboard');

    // Profile admin
    Route::get('/profile', [ProfileController::class, 'edit_admin'])->name('admin.profile.edit');
    Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'update_admin'])->name('admin.profile.update');

    // Akun dosen
    Route::get('/akun-dosen', [AccountController::class, 'index'])->name('akun_dosen.index');
    Route::post('/dosen', [AccountController::class, 'store'])->name('admin.dosen.buatAkun');
    Route::get('/dosen/import', [AccountController::class, 'import_dosen'])->name('dosen.import');
    Route::post('/dosen/import', [AccountController::class, 'import'])->name('admin.dosen.import.process');
    Route::get('/akun-dosen/{id}', [AccountController::class, 'show'])->name('admin.dosen.show');
    Route::put('/akun-dosen/{id}', [AccountController::class, 'update'])->name('admin.dosen.update');
    Route::delete('/akun-dosen/{id}', [AccountController::class, 'destroy'])->name('admin.dosen.destroy');
    Route::get('/dosen/{prodi}', [DosenController::class, 'show'])->name('dosen.prodi');

    // Akun mahasiswa
    Route::get('/akun-mahasiswa', [AccountController::class, 'index_mahasiswa'])->name('akun_mahasiswa.index');
    Route::post('/mahasiswa', [AccountController::class, 'store_mahasiswa'])->name('admin.mahasiswa.buatAkun');
    Route::get('/mahasiswa/import', [AccountController::class, 'import_mahasiswa'])->name('mahasiswa.import');
    Route::post('/mahasiswa/import', [AccountController::class, 'importStudent'])->name('admin.mahasiswa.import.process');
    Route::put('/akun-mahasiswa/{id}', [AccountController::class, 'update_mahasiswa'])->name('admin.mahasiswa.update');
    Route::delete('/akun-mahasiswa/{id}', [AccountController::class, 'destroy_mahasiswa'])->name('admin.mahasiswa.destroy');

    // Matakuliah (CRUD + search)
    Route::get('/matakuliah', [MatakuliahController::class, 'index'])->name('matakuliah.index');
    Route::post('/matakuliah', [MatakuliahController::class, 'store'])->name('admin.tambah.matkul');
    Route::put('/matakuliah/{matakuliah}', [MatakuliahController::class, 'update'])->name('matakuliah.update');
    Route::delete('/matakuliah/{matakuliah}', [MatakuliahController::class, 'destroy'])->name('matakuliah.destroy');
    Route::get('/matakuliah/search', [MatakuliahController::class, 'search'])->name('admin.matakuliah.search');
    Route::get('/penugasan-mk', [MatakuliahController::class, 'dosen_dan_mhs'])->name('matakuliah.pengampu');

    // Pengajaran & kelas (penugasan dosen ke kelas, peserta kelas)
    Route::post('/pengajaran', [PengajaranController::class, 'store'])->name('admin.pengajaran.store');
    Route::get('/pengajaran/{lecturer}/matakuliah', [PengajaranController::class, 'matakuliah'])->name('admin.pengajaran.matakuliah');

    Route::post('/pengajaran/{pengajaran}/peserta', [PengajaranController::class, 'tambahPeserta'])->name('admin.pengajaran.peserta.store');
    Route::get('/peserta-mk', [PengajaranController::class, 'show_mk'])->name('peserta.mk');

    Route::get('/kelas/{kelas}/students', [PengajaranController::class, 'searchStudents'])->name('kelas.students.search');
    Route::get('/kelas/{kelas}/peserta', [PengajaranController::class, 'daftarPeserta'])->name('kelas.peserta');
    Route::post('/kelas/{kelas}/peserta', [PengajaranController::class, 'tambahPeserta'])->name('kelas.peserta.store');
    Route::delete('/kelas/{id}', [PengajaranController::class, 'destroy'])->name('kelas.destroy');

    // Jadwal matakuliah
    Route::post('/jadwal', [JadwalMatakuliahController::class, 'store'])->name('admin.jadwal.store');
    Route::put('/jadwal/{jadwal}', [JadwalMatakuliahController::class, 'update'])->name('admin.jadwal.update');
    Route::delete('/jadwal/{jadwal}', [JadwalMatakuliahController::class, 'destroy'])->name('admin.jadwal.destroy');
    Route::post('/jadwal/import', [JadwalMatakuliahController::class, 'import'])->name('admin.jadwal.import');
    Route::get('/jadwal/template', [JadwalMatakuliahController::class, 'template'])->name('admin.jadwal.template');
});

/*
|--------------------------------------------------------------------------
| LECTURER
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:lecturer'])->prefix('lecturer')->group(function () {

    Route::get('/dashboard', function () {
        $totalMatkul    = Matakuliah::count();
        $totalDosen     = Lecturer::count();
        $totalMahasiswa = Student::count();

        return view('lecturer.dashboard', compact('totalMatkul', 'totalDosen', 'totalMahasiswa'));
    })->name('lecturer.dashboard');

    // Profile lecturer
    Route::get('/profile', [ProfileController::class, 'edit_lecturer'])->name('lecturer.profile.edit');
    Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'update_lecturer'])->name('lecturer.profile.update');

    // Mata kuliah yang diampu
    Route::get('/matakuliahsaya', [PengajaranController::class, 'mk_saya'])->name('matakuliah.ampu');



    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('lecturer.pengajuan.index');
    Route::post('/pengajuan/{pengajuan}/approve', [PengajuanController::class, 'approve'])->name('lecturer.pengajuan.approve');
    Route::post('/pengajuan/{pengajuan}/reject', [PengajuanController::class, 'reject'])->name('lecturer.pengajuan.reject');

    // Materi
    Route::get('/pengajaran/{pengajaran}/materi/create', [MateriController::class, 'create'])->name('materi.create');
    Route::post('/pengajaran/{pengajaran}/materi', [MateriController::class, 'store'])->name('materi.store');
    Route::get('/materi/{materi}/edit', [MateriController::class, 'edit'])->name('materi.edit');
    Route::put('/materi/{materi}', [MateriController::class, 'update'])->name('materi.update');
    Route::delete('/materi/{materi}', [MateriController::class, 'destroy'])->name('materi.destroy');
    Route::get('/pengajaran/{id}', [PengajaranController::class, 'show'])->name('pengajaran.show');

    // Absensi (sesi)
    Route::post('/pengajaran/{pengajaran}/absensi', [SesiAbsensiController::class, 'store'])->name('lecturer.absensi.store');
    Route::get('/absensi/{sesi}', [SesiAbsensiController::class, 'show'])->name('lecturer.absensi.show');
    Route::post('/absensi/{sesi}/tutup', [SesiAbsensiController::class, 'tutup'])->name('lecturer.absensi.tutup');
    Route::get('/absensi/{sesi}/count', [SesiAbsensiController::class, 'count'])->name('lecturer.absensi.count');
    Route::get('/absensi/{sesi}/rekap', [SesiAbsensiController::class, 'rekap'])->name('lecturer.absensi.rekap');
    Route::get('/pengajaran/{pengajaranDosen}/absensi/rekap', [AbsensiController::class, 'rekapSemua'])->name('lecturer.absensi.rekapSemua');
    Route::get('/pengajaran/{pengajaranDosen}/absensi/rekap/export', [AbsensiController::class, 'exportRekap'])->name('lecturer.absensi.rekapSemua.export');

    // Quiz
    Route::prefix('quiz')->name('lecturer.quiz.')->group(function () {
        Route::get('/{pengajaranDosen}', [QuizController::class, 'index'])->name('index');
        Route::get('/{pengajaranDosen}/create', [QuizController::class, 'create'])->name('create');
        Route::post('/{pengajaranDosen}', [QuizController::class, 'store'])->name('store');
        Route::get('/{quiz}/template', [QuizController::class, 'downloadTemplate'])->name('template');
        Route::post('/{quiz}/import', [QuizController::class, 'import'])->name('import');
        Route::get('/detail/{quiz}', [QuizController::class, 'show'])->name('show');
        Route::patch('/{quiz}/publish', [QuizController::class, 'publish'])->name('publish');
        Route::post('/question/{quizQuestion}/gambar', [QuizController::class, 'uploadGambarSoal'])->name('question.gambar');
    });

    // Tugas
    Route::get('/tugas/create/{pengajaranDosen}', [LecturerTugasController::class, 'create'])->name('tugas.create');
    Route::post('/tugas/{pengajaranDosen}', [LecturerTugasController::class, 'store'])->name('tugas.store');
    Route::get('/tugas/{tugas}/edit', [LecturerTugasController::class, 'edit'])->name('tugas.edit');
    Route::put('/tugas/{tugas}', [LecturerTugasController::class, 'update'])->name('tugas.update');
    Route::delete('/tugas/{tugas}', [LecturerTugasController::class, 'destroy'])->name('tugas.destroy');
    Route::get('/tugas/{tugas}', [LecturerTugasController::class, 'show'])->name('tugas.show');
    Route::get('/tugas/{tugas}/jawaban', [LecturerTugasController::class, 'jawabanIndex'])->name('tugas.jawaban.index');
    Route::get('/tugas/{tugas}/jawaban/{jawaban}', [LecturerTugasController::class, 'jawabanShow'])->name('tugas.jawaban.show');
    Route::post('/tugas/{tugas}/jawaban/{jawaban}/koreksi', [LecturerTugasController::class, 'koreksi'])->name('tugas.jawaban.koreksi');

    // Rekap nilai
    Route::get('/pengajaran-dosen/{pengajaranDosen}/rekap-nilai', [PengajaranController::class, 'rekapNilai'])->name('lecturer.rekap.nilai');

    // Akademik (tugas / quiz / nilai)
    Route::prefix('akademik')->name('lecturer.akademik.')->group(function () {
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
});

/*
|--------------------------------------------------------------------------
| STUDENT
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:student'])->prefix('student')->group(function () {

    Route::get('/dashboard', function () {
        $mahasiswa = Auth::user()->student;
        abort_unless($mahasiswa, 403, 'Akun ini tidak terdaftar sebagai mahasiswa.');

        $kelasIds      = PengajaranMahasiswa::where('mahasiswa_id', $mahasiswa->id)->pluck('kelas_id');
        $pengajaranIds = \App\Models\PengajaranDosen::whereIn('kelas_id', $kelasIds)->pluck('id');

        $totalMatkul = $kelasIds->unique()->count();

        $pendingTugas = \App\Models\Tugas::whereIn('pengajaran_dosen_id', $pengajaranIds)
            ->whereDoesntHave('jawaban', fn($q) => $q->where('mahasiswa_id', $mahasiswa->id))
            ->count();

        $pendingQuiz = \App\Models\Quiz::whereIn('pengajaran_dosen_id', $pengajaranIds)
            ->where('is_published', true)
            ->whereDoesntHave('jawaban', fn($q) => $q->where('mahasiswa_id', $mahasiswa->id))
            ->count();

        $tugasAvg = \App\Models\TugasJawaban::where('mahasiswa_id', $mahasiswa->id)
            ->whereNotNull('skor')
            ->whereHas('tugas', fn($q) => $q->whereIn('pengajaran_dosen_id', $pengajaranIds))
            ->avg('skor');

        $quizAvg = \App\Models\QuizJawaban::where('mahasiswa_id', $mahasiswa->id)
            ->whereNotNull('skor')
            ->whereHas('quiz', fn($q) => $q->whereIn('pengajaran_dosen_id', $pengajaranIds))
            ->avg('skor');

        $nilaiRataRata = collect([$tugasAvg, $quizAvg])->filter(fn($v) => $v !== null)->avg();

        $tugasTerbaru = \App\Models\Tugas::whereIn('pengajaran_dosen_id', $pengajaranIds)
            ->with('pengajaranDosen.kelas.matakuliah')
            ->whereDoesntHave('jawaban', fn($q) => $q->where('mahasiswa_id', $mahasiswa->id))
            ->orderByRaw('CASE WHEN deadline IS NULL THEN 1 ELSE 0 END')
            ->orderBy('deadline')
            ->take(5)
            ->get();

        $quizTerbaru = \App\Models\Quiz::whereIn('pengajaran_dosen_id', $pengajaranIds)
            ->where('is_published', true)
            ->with('pengajaranDosen.kelas.matakuliah')
            ->whereDoesntHave('jawaban', fn($q) => $q->where('mahasiswa_id', $mahasiswa->id))
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
    })->name('student.dashboard');

    // Matakuliah — dua fungsi berbeda, dua URI berbeda
    Route::get('/matakuliah', [MatakuliahController::class, 'index_mhs'])->name('student.matakuliah.index');
    Route::get('/matakuliah/cari', [MatakuliahController::class, 'index_daftar_mk'])->name('student.matakuliah.cari');
    Route::get('/matakuliah/{kelas}', [MatakuliahController::class, 'show'])->name('student.matakuliah.show');
    Route::post('/matakuliah/{kelas}/ambil', [MatakuliahController::class, 'ambilMk'])->name('student.matakuliah.ambil');


    // Quiz
    Route::get('/quiz/{quiz}', [StudentQuizController::class, 'show'])->name('student.quiz.show');
    Route::post('/quiz/{quiz}/submit', [StudentQuizController::class, 'submit'])->name('student.quiz.submit');

    // Absensi
    Route::get('/absensi/scan', [StudentAbsensiController::class, 'scan'])->name('student.absensi.scan');
    Route::post('/absensi/absen', [StudentAbsensiController::class, 'absen'])->name('student.absensi.absen');

    Route::get('/absensi/rekap/{kelas}', [StudentAbsensiController::class, 'rekap'])
        ->name('student.absensi.rekap');

    // Tugas
    Route::get('/tugas/{tugas}', [StudentTugasController::class, 'show'])->name('student.tugas.show');
    Route::post('/tugas/{tugas}/submit', [StudentTugasController::class, 'submit'])->name('student.tugas.submit');

    // Akademik (tugas / quiz / nilai)
    Route::prefix('akademik')->name('student.akademik.')->group(function () {
        Route::get('/tugas', [StudentAkademikController::class, 'tugasIndex'])->name('tugas.index');
        Route::get('/tugas/kelas/{kelas}', [StudentAkademikController::class, 'tugasKelas'])->name('tugas.kelas');

        Route::get('/quiz', [StudentAkademikController::class, 'quizIndex'])->name('quiz.index');
        Route::get('/quiz/kelas/{kelas}', [StudentAkademikController::class, 'quizKelas'])->name('quiz.kelas');

        Route::get('/nilai', [StudentAkademikController::class, 'nilaiIndex'])->name('nilai.index');
        Route::get('/nilai/kelas/{kelas}', [StudentAkademikController::class, 'nilaiKelas'])->name('nilai.kelas');
    });
});

require __DIR__ . '/auth.php';
