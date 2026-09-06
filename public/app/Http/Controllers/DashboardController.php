<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Lecturer;
use App\Models\Matakuliah;
use App\Models\PengajaranDosen;
use App\Models\PengajaranMahasiswa;
use App\Models\Quiz;
use App\Models\QuizJawaban;
use App\Models\SesiAbsensi;
use App\Models\Student;
use App\Models\Tugas;
use App\Models\TugasJawaban;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        $stats = [
            'users' => User::count(),
            'students' => Student::count(),
            'lecturers' => Lecturer::count(),
            'courses' => Matakuliah::count(),
            'classes' => Kelas::count(),
            'teaching' => PengajaranDosen::count(),
        ];

        $recentTeaching = PengajaranDosen::with(['lecturer.user', 'kelas.matakuliah'])
            ->latest()->take(8)->get();

        return view('admin.dashboard', compact('stats', 'recentTeaching'));
    }

    public function lecturerDashboard()
    {
        $lecturer = auth()->user()->lecturer;
        abort_unless($lecturer, 403);

        $teachingIds = PengajaranDosen::where('dosen_id', $lecturer->id)->pluck('id');
        $classIds = PengajaranDosen::where('dosen_id', $lecturer->id)->pluck('kelas_id');

        $stats = [
            'courses' => $teachingIds->count(),
            'classes' => $classIds->unique()->count(),
            'materials' => \App\Models\Materi::whereIn('pengajaran_id', $teachingIds)->count(),
            'tasks' => Tugas::whereIn('pengajaran_dosen_id', $teachingIds)->count(),
            'quizzes' => Quiz::whereIn('pengajaran_dosen_id', $teachingIds)->count(),
            'pending' => TugasJawaban::whereHas('tugas', fn ($q) => $q->whereIn('pengajaran_dosen_id', $teachingIds))
                ->where('status', 'menunggu_koreksi')->count(),
        ];

        $courses = PengajaranDosen::with(['kelas.matakuliah'])
            ->where('dosen_id', $lecturer->id)->latest()->take(6)->get();

        return view('lecturer.dashboard', compact('stats', 'courses'));
    }

    public function studentDashboard()
    {
        $student = auth()->user()->student;
        abort_unless($student, 403);

        $classIds = PengajaranMahasiswa::where('mahasiswa_id', $student->id)->pluck('kelas_id');
        $teachingIds = PengajaranDosen::whereIn('kelas_id', $classIds)->pluck('id');

        $stats = [
            'courses' => $classIds->unique()->count(),
            'tasks' => Tugas::whereIn('pengajaran_dosen_id', $teachingIds)->count(),
            'quizzes' => Quiz::whereIn('pengajaran_dosen_id', $teachingIds)->where('is_published', true)->count(),
            'submitted_tasks' => TugasJawaban::where('mahasiswa_id', $student->id)->count(),
            'quiz_done' => QuizJawaban::where('mahasiswa_id', $student->id)->count(),
        ];

        $upcomingTasks = Tugas::with(['pengajaranDosen.kelas.matakuliah'])
            ->whereIn('pengajaran_dosen_id', $teachingIds)
            ->whereNotNull('deadline')
            ->where('deadline', '>=', now())
            ->orderBy('deadline')->take(5)->get();

        return view('student.dashboard', compact('stats', 'upcomingTasks'));
    }

    public function lecturerTasks()
    {
        $lecturer = auth()->user()->lecturer;
        abort_unless($lecturer, 403);
        $teachingIds = PengajaranDosen::where('dosen_id', $lecturer->id)->pluck('id');

        $tasks = Tugas::with(['pengajaranDosen.kelas.matakuliah'])
            ->withCount(['jawaban as pending_count' => fn ($q) => $q->where('status', 'menunggu_koreksi')])
            ->whereIn('pengajaran_dosen_id', $teachingIds)->latest()->paginate(10);
        $quizzes = Quiz::with(['pengajaranDosen.kelas.matakuliah'])
            ->withCount('questions')
            ->whereIn('pengajaran_dosen_id', $teachingIds)->latest()->get();

        return view('lecturer.tasks-index', compact('tasks', 'quizzes'));
    }

    public function studentTasks()
    {
        $student = auth()->user()->student;
        abort_unless($student, 403);
        $classIds = PengajaranMahasiswa::where('mahasiswa_id', $student->id)->pluck('kelas_id');
        $teachingIds = PengajaranDosen::whereIn('kelas_id', $classIds)->pluck('id');

        $tasks = Tugas::with(['pengajaranDosen.kelas.matakuliah'])
            ->whereIn('pengajaran_dosen_id', $teachingIds)->latest()->get()
            ->map(function ($task) use ($student) {
                $task->jawaban_saya = $task->jawaban()->where('mahasiswa_id', $student->id)->first();
                return $task;
            });

        $quizzes = Quiz::with(['pengajaranDosen.kelas.matakuliah'])
            ->withCount('questions')
            ->whereIn('pengajaran_dosen_id', $teachingIds)
            ->where('is_published', true)->latest()->get()
            ->map(function ($quiz) use ($student) {
                $quiz->jawaban_saya = $quiz->jawaban()->where('mahasiswa_id', $student->id)->first();
                return $quiz;
            });

        return view('student.tasks-index', compact('tasks', 'quizzes'));
    }

    public function studentGrades()
    {
        $student = auth()->user()->student;
        abort_unless($student, 403);

        $quizGrades = QuizJawaban::with(['quiz.pengajaranDosen.kelas.matakuliah'])
            ->where('mahasiswa_id', $student->id)->latest('waktu_submit')->get();
        $taskGrades = TugasJawaban::with(['tugas.pengajaranDosen.kelas.matakuliah'])
            ->where('mahasiswa_id', $student->id)->whereNotNull('skor')->latest('dikoreksi_at')->get();

        return view('student.grades', compact('quizGrades', 'taskGrades'));
    }

    public function lecturerGrades()
    {
        $lecturer = auth()->user()->lecturer;
        abort_unless($lecturer, 403);
        $teachingIds = PengajaranDosen::where('dosen_id', $lecturer->id)->pluck('id');

        $taskGrades = TugasJawaban::with(['mahasiswa.user', 'tugas.pengajaranDosen.kelas.matakuliah'])
            ->whereHas('tugas', fn ($q) => $q->whereIn('pengajaran_dosen_id', $teachingIds))
            ->latest('dikoreksi_at')->paginate(15, ['*'], 'tasks');
        $quizGrades = QuizJawaban::with(['mahasiswa.user', 'quiz.pengajaranDosen.kelas.matakuliah'])
            ->whereHas('quiz', fn ($q) => $q->whereIn('pengajaran_dosen_id', $teachingIds))
            ->latest('waktu_submit')->paginate(15, ['*'], 'quizzes');

        return view('lecturer.grades', compact('taskGrades', 'quizGrades'));
    }

    public function schedule()
    {
        $user = auth()->user();

        if ($user->role === 'student') {
            $student = $user->student;
            $classIds = PengajaranMahasiswa::where('mahasiswa_id', $student->id)->pluck('kelas_id');
            $classes = Kelas::with(['matakuliah', 'pengajaranDosen.lecturer.user'])
                ->whereIn('id', $classIds)->get();
            $sessions = SesiAbsensi::with(['kelas.matakuliah', 'dosen.user'])
                ->whereIn('kelas_id', $classIds)->latest('dibuka_pada')->take(30)->get();
        } elseif ($user->role === 'lecturer') {
            $lecturer = $user->lecturer;
            $classIds = PengajaranDosen::where('dosen_id', $lecturer->id)->pluck('kelas_id');
            $classes = Kelas::with(['matakuliah', 'pengajaranDosen.lecturer.user'])
                ->whereIn('id', $classIds)->get();
            $sessions = SesiAbsensi::with(['kelas.matakuliah', 'dosen.user'])
                ->where('dosen_id', $lecturer->id)->latest('dibuka_pada')->take(30)->get();
        } else {
            $classes = Kelas::with(['matakuliah', 'pengajaranDosen.lecturer.user'])->latest()->get();
            $sessions = SesiAbsensi::with(['kelas.matakuliah', 'dosen.user'])->latest('dibuka_pada')->take(50)->get();
        }

        return view('shared.schedule', compact('classes', 'sessions'));
    }

    public function messages()
    {
        $user = auth()->user();
        $contacts = User::query()
            ->where('id', '!=', $user->id)
            ->whereIn('role', $user->role === 'student' ? ['lecturer', 'admin'] : ['student', 'lecturer', 'admin'])
            ->orderBy('name')->get(['id', 'name', 'email', 'role']);

        return view('shared.messages', compact('contacts'));
    }
}
