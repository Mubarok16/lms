<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\PengajaranDosen;
use App\Models\PengajaranMahasiswa;
use App\Models\Quiz;
use App\Models\QuizJawaban;
use App\Models\SesiAbsensi;
use App\Models\Tugas;
use App\Models\TugasJawaban;

class AkademikController extends Controller
{
    public function tugasIndex()
    {
        $kelasList = $this->kelasSaya();

        foreach ($kelasList as $kelas) {
            $pengajaranIds = PengajaranDosen::where('kelas_id', $kelas->id)->pluck('id');
            $kelas->jumlah_tugas = Tugas::whereIn('pengajaran_dosen_id', $pengajaranIds)->count();
            $kelas->tugas_belum = Tugas::whereIn('pengajaran_dosen_id', $pengajaranIds)
                ->whereDoesntHave('jawaban', fn ($q) => $q->where('mahasiswa_id', $this->studentId()))
                ->count();
        }

        return view('student.akademik.tugas-index', compact('kelasList'));
    }

    public function tugasKelas(Kelas $kelas)
    {
        $this->authorizeKelas($kelas);
        $kelas->load('matakuliah');
        $studentId = $this->studentId();
        $pengajaranIds = PengajaranDosen::where('kelas_id', $kelas->id)->pluck('id');

        $tugasList = Tugas::whereIn('pengajaran_dosen_id', $pengajaranIds)
            ->with(['jawaban' => fn ($q) => $q->where('mahasiswa_id', $studentId)])
            ->latest()
            ->get();

        return view('student.akademik.tugas-kelas', compact('kelas', 'tugasList'));
    }

    public function quizIndex()
    {
        $kelasList = $this->kelasSaya();

        foreach ($kelasList as $kelas) {
            $pengajaranIds = PengajaranDosen::where('kelas_id', $kelas->id)->pluck('id');
            $kelas->jumlah_quiz = Quiz::whereIn('pengajaran_dosen_id', $pengajaranIds)
                ->where('is_published', true)
                ->count();
            $kelas->quiz_belum = Quiz::whereIn('pengajaran_dosen_id', $pengajaranIds)
                ->where('is_published', true)
                ->whereDoesntHave('jawaban', fn ($q) => $q->where('mahasiswa_id', $this->studentId()))
                ->count();
        }

        return view('student.akademik.quiz-index', compact('kelasList'));
    }

    public function quizKelas(Kelas $kelas)
    {
        $this->authorizeKelas($kelas);
        $kelas->load('matakuliah');
        $studentId = $this->studentId();
        $pengajaranIds = PengajaranDosen::where('kelas_id', $kelas->id)->pluck('id');

        $quizList = Quiz::whereIn('pengajaran_dosen_id', $pengajaranIds)
            ->where('is_published', true)
            ->withCount('questions')
            ->with(['jawaban' => fn ($q) => $q->where('mahasiswa_id', $studentId)])
            ->latest()
            ->get();

        return view('student.akademik.quiz-kelas', compact('kelas', 'quizList'));
    }

    public function nilaiIndex()
    {
        $kelasList = $this->kelasSaya();
        $studentId = $this->studentId();

        foreach ($kelasList as $kelas) {
            $summary = $this->buildSummary($kelas, $studentId);
            $kelas->tugas_avg = $summary['tugas_avg'];
            $kelas->quiz_avg = $summary['quiz_avg'];
            $kelas->attendance_percent = $summary['attendance_percent'];
        }

        return view('student.akademik.nilai-index', compact('kelasList'));
    }

    public function nilaiKelas(Kelas $kelas)
    {
        $this->authorizeKelas($kelas);
        $kelas->load('matakuliah');
        $detail = $this->buildDetail($kelas, $this->studentId());

        return view('student.akademik.nilai-kelas', compact('kelas', 'detail'));
    }

    private function kelasSaya()
    {
        $studentId = $this->studentId();

        return Kelas::whereHas('pengajaranMahasiswa', fn ($q) => $q->where('mahasiswa_id', $studentId))
            ->with('matakuliah')
            ->orderBy('kode_mk')
            ->orderBy('kode_kelas')
            ->get();
    }

    private function authorizeKelas(Kelas $kelas): void
    {
        abort_unless(
            PengajaranMahasiswa::where('kelas_id', $kelas->id)
                ->where('mahasiswa_id', $this->studentId())
                ->exists(),
            403,
            'Kamu tidak terdaftar di kelas ini.'
        );
    }

    private function studentId(): int
    {
        $student = auth()->user()->student;
        abort_unless($student, 403, 'Akun ini tidak terdaftar sebagai mahasiswa.');

        return (int) $student->id;
    }

    private function buildSummary(Kelas $kelas, int $studentId): array
    {
        $pengajaranIds = PengajaranDosen::where('kelas_id', $kelas->id)->pluck('id');
        $tugasIds = Tugas::whereIn('pengajaran_dosen_id', $pengajaranIds)->pluck('id');
        $quizIds = Quiz::whereIn('pengajaran_dosen_id', $pengajaranIds)->pluck('id');

        $tugasScores = TugasJawaban::where('mahasiswa_id', $studentId)
            ->whereIn('tugas_id', $tugasIds)
            ->whereNotNull('skor')
            ->pluck('skor');
        $quizScores = QuizJawaban::where('mahasiswa_id', $studentId)
            ->whereIn('quiz_id', $quizIds)
            ->whereNotNull('skor')
            ->pluck('skor');

        $sesiIds = SesiAbsensi::where('kelas_id', $kelas->id)->pluck('id');
        $hadir = Absensi::where('mahasiswa_id', $studentId)
            ->whereIn('sesi_absensi_id', $sesiIds)
            ->where('status', 'hadir')
            ->count();

        return [
            'tugas_avg' => $tugasScores->isNotEmpty() ? round((float) $tugasScores->avg(), 2) : null,
            'quiz_avg' => $quizScores->isNotEmpty() ? round((float) $quizScores->avg(), 2) : null,
            'attendance_percent' => $sesiIds->count() > 0 ? round(($hadir / $sesiIds->count()) * 100, 2) : null,
        ];
    }

    private function buildDetail(Kelas $kelas, int $studentId): array
    {
        $pengajaranIds = PengajaranDosen::where('kelas_id', $kelas->id)->pluck('id');

        $tugas = Tugas::whereIn('pengajaran_dosen_id', $pengajaranIds)->latest()->get();
        $tugasJawaban = TugasJawaban::where('mahasiswa_id', $studentId)
            ->whereIn('tugas_id', $tugas->pluck('id'))
            ->get()
            ->keyBy('tugas_id');

        $quizzes = Quiz::whereIn('pengajaran_dosen_id', $pengajaranIds)
            ->where('is_published', true)
            ->latest()
            ->get();
        $quizJawaban = QuizJawaban::where('mahasiswa_id', $studentId)
            ->whereIn('quiz_id', $quizzes->pluck('id'))
            ->get()
            ->keyBy('quiz_id');

        $sesi = SesiAbsensi::where('kelas_id', $kelas->id)->orderBy('pertemuan_ke')->get();
        $absensi = Absensi::where('mahasiswa_id', $studentId)
            ->whereIn('sesi_absensi_id', $sesi->pluck('id'))
            ->get()
            ->keyBy('sesi_absensi_id');

        $tugasScores = $tugasJawaban->pluck('skor')->filter(fn ($v) => $v !== null);
        $quizScores = $quizJawaban->pluck('skor')->filter(fn ($v) => $v !== null);
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
