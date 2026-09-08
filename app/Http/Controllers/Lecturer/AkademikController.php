<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\PengajaranDosen;
use App\Models\Quiz;
use App\Models\QuizJawaban;
use App\Models\SesiAbsensi;
use App\Models\Student;
use App\Models\Tugas;
use App\Models\TugasJawaban;
use Illuminate\Support\Collection;

class AkademikController extends Controller
{
    public function tugasCourses()
    {
        $pengajaranList = $this->pengajaranSaya()
            ->loadCount('tugas');

        return view('lecturer.akademik.tugas-courses', compact('pengajaranList'));
    }

    public function tugasList(PengajaranDosen $pengajaranDosen)
    {
        $this->authorizePengajaran($pengajaranDosen);
        $pengajaranDosen->load('kelas.matakuliah');

        $tugasList = Tugas::where('pengajaran_dosen_id', $pengajaranDosen->id)
            ->withCount('jawaban')
            ->latest()
            ->get();

        return view('lecturer.akademik.tugas-list', compact('pengajaranDosen', 'tugasList'));
    }

    public function quizCourses()
    {
        $pengajaranList = $this->pengajaranSaya()
            ->loadCount('quizzes');

        return view('lecturer.akademik.quiz-courses', compact('pengajaranList'));
    }

    public function quizList(PengajaranDosen $pengajaranDosen)
    {
        $this->authorizePengajaran($pengajaranDosen);
        $pengajaranDosen->load('kelas.matakuliah');

        $quizList = Quiz::where('pengajaran_dosen_id', $pengajaranDosen->id)
            ->withCount(['questions', 'jawaban'])
            ->latest()
            ->get();

        return view('lecturer.akademik.quiz-list', compact('pengajaranDosen', 'quizList'));
    }

    public function quizJawabanIndex(Quiz $quiz)
    {
        $quiz->load('pengajaranDosen.kelas.matakuliah');
        $this->authorizePengajaran($quiz->pengajaranDosen);

        $kelas = $quiz->pengajaranDosen->kelas;
        $students = $kelas->mahasiswa()
            ->with('user')
            ->orderBy('nim')
            ->get();

        $jawabanByStudent = QuizJawaban::where('quiz_id', $quiz->id)
            ->with('mahasiswa.user')
            ->get()
            ->keyBy('mahasiswa_id');

        return view('lecturer.akademik.quiz-jawaban-index', compact(
            'quiz',
            'students',
            'jawabanByStudent'
        ));
    }

    public function quizJawabanShow(Quiz $quiz, QuizJawaban $jawaban)
    {
        $quiz->load('pengajaranDosen.kelas.matakuliah');
        $this->authorizePengajaran($quiz->pengajaranDosen);
        abort_unless($jawaban->quiz_id === $quiz->id, 404);

        $jawaban->load([
            'mahasiswa.user',
            'detail.question',
        ]);

        return view('lecturer.akademik.quiz-jawaban-show', compact('quiz', 'jawaban'));
    }

    public function nilaiCourses()
    {
        $pengajaranList = $this->pengajaranSaya();

        foreach ($pengajaranList as $pengajaran) {
            $pengajaran->jumlah_mahasiswa = $pengajaran->kelas->mahasiswa()->count();
        }

        return view('lecturer.akademik.nilai-courses', compact('pengajaranList'));
    }

    public function nilaiStudents(PengajaranDosen $pengajaranDosen)
    {
        $this->authorizePengajaran($pengajaranDosen);
        $pengajaranDosen->load('kelas.matakuliah');

        $students = $pengajaranDosen->kelas->mahasiswa()
            ->with('user')
            ->orderBy('nim')
            ->get();

        $rekap = $students->map(function (Student $student) use ($pengajaranDosen) {
            return $this->buildStudentSummary($pengajaranDosen, $student);
        });

        return view('lecturer.akademik.nilai-students', compact(
            'pengajaranDosen',
            'rekap'
        ));
    }

    public function nilaiStudent(PengajaranDosen $pengajaranDosen, Student $student)
    {
        $this->authorizePengajaran($pengajaranDosen);
        $pengajaranDosen->load('kelas.matakuliah');

        abort_unless(
            $pengajaranDosen->kelas->mahasiswa()->where('students.id', $student->id)->exists(),
            404,
            'Mahasiswa tidak terdaftar pada kelas ini.'
        );

        $student->load('user');
        $detail = $this->buildStudentDetail($pengajaranDosen, $student);

        return view('lecturer.akademik.nilai-student', compact(
            'pengajaranDosen',
            'student',
            'detail'
        ));
    }

    private function pengajaranSaya(): Collection
    {
        $lecturer = auth()->user()->lecturer;
        abort_unless($lecturer, 403, 'Akun ini tidak terdaftar sebagai dosen.');

        return PengajaranDosen::where('dosen_id', $lecturer->id)
            ->with('kelas.matakuliah')
            ->orderByDesc('id')
            ->get();
    }

    private function authorizePengajaran(PengajaranDosen $pengajaranDosen): void
    {
        $lecturer = auth()->user()->lecturer;
        abort_unless(
            $lecturer && (int) $pengajaranDosen->dosen_id === (int) $lecturer->id,
            403,
            'Anda tidak mengampu mata kuliah ini.'
        );
    }

    private function buildStudentSummary(PengajaranDosen $pengajaranDosen, Student $student): array
    {
        $tugasIds = Tugas::where('pengajaran_dosen_id', $pengajaranDosen->id)->pluck('id');
        $quizIds = Quiz::where('pengajaran_dosen_id', $pengajaranDosen->id)->pluck('id');

        $tugasNilai = TugasJawaban::where('mahasiswa_id', $student->id)
            ->whereIn('tugas_id', $tugasIds)
            ->whereNotNull('skor')
            ->pluck('skor');

        $quizNilai = QuizJawaban::where('mahasiswa_id', $student->id)
            ->whereIn('quiz_id', $quizIds)
            ->whereNotNull('skor')
            ->pluck('skor');

        $sesi = SesiAbsensi::where('kelas_id', $pengajaranDosen->kelas_id)->pluck('id');
        $absensi = Absensi::where('mahasiswa_id', $student->id)
            ->whereIn('sesi_absensi_id', $sesi)
            ->get();

        $totalSesi = $sesi->count();
        $hadir = $absensi->where('status', 'hadir')->count();
        $persenHadir = $totalSesi > 0 ? round(($hadir / $totalSesi) * 100, 2) : null;

        return [
            'student' => $student,
            'tugas_avg' => $tugasNilai->isNotEmpty() ? round((float) $tugasNilai->avg(), 2) : null,
            'quiz_avg' => $quizNilai->isNotEmpty() ? round((float) $quizNilai->avg(), 2) : null,
            'attendance_percent' => $persenHadir,
            'tugas_dinilai' => $tugasNilai->count(),
            'quiz_dikerjakan' => $quizNilai->count(),
            'total_sesi' => $totalSesi,
        ];
    }

    private function buildStudentDetail(PengajaranDosen $pengajaranDosen, Student $student): array
    {
        $tugas = Tugas::where('pengajaran_dosen_id', $pengajaranDosen->id)
            ->latest()
            ->get();

        $tugasJawaban = TugasJawaban::where('mahasiswa_id', $student->id)
            ->whereIn('tugas_id', $tugas->pluck('id'))
            ->get()
            ->keyBy('tugas_id');

        $quizzes = Quiz::where('pengajaran_dosen_id', $pengajaranDosen->id)
            ->latest()
            ->get();

        $quizJawaban = QuizJawaban::where('mahasiswa_id', $student->id)
            ->whereIn('quiz_id', $quizzes->pluck('id'))
            ->get()
            ->keyBy('quiz_id');

        $sesi = SesiAbsensi::where('kelas_id', $pengajaranDosen->kelas_id)
            ->orderBy('pertemuan_ke')
            ->get();

        $absensi = Absensi::where('mahasiswa_id', $student->id)
            ->whereIn('sesi_absensi_id', $sesi->pluck('id'))
            ->get()
            ->keyBy('sesi_absensi_id');

        $tugasScores = $tugasJawaban->pluck('skor')->filter(fn ($value) => $value !== null);
        $quizScores = $quizJawaban->pluck('skor')->filter(fn ($value) => $value !== null);
        $hadir = $absensi->where('status', 'hadir')->count();
        $izin = $absensi->where('status', 'izin')->count();
        $sakit = $absensi->where('status', 'sakit')->count();
        $alphaTercatat = $absensi->where('status', 'alpha')->count();
        $belumTercatat = max(0, $sesi->count() - $absensi->count());

        return [
            'tugas' => $tugas,
            'tugasJawaban' => $tugasJawaban,
            'quizzes' => $quizzes,
            'quizJawaban' => $quizJawaban,
            'sesi' => $sesi,
            'absensi' => $absensi,
            'tugas_avg' => $tugasScores->isNotEmpty() ? round((float) $tugasScores->avg(), 2) : null,
            'quiz_avg' => $quizScores->isNotEmpty() ? round((float) $quizScores->avg(), 2) : null,
            'attendance_percent' => $sesi->count() > 0 ? round(($hadir / $sesi->count()) * 100, 2) : null,
            'hadir' => $hadir,
            'izin' => $izin,
            'sakit' => $sakit,
            'alpha' => $alphaTercatat + $belumTercatat,
        ];
    }
}
