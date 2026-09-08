<?php

namespace App\Http\Controllers;

use App\Exports\QuizTemplateExport;
use App\Imports\QuizQuestionsImport;
use App\Models\PengajaranDosen;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class QuizController extends Controller
{
    /**
     * Daftar quiz untuk satu mata kuliah (pengajaran dosen).
     */
    public function index(PengajaranDosen $pengajaranDosen)
    {
        $this->authorizePengajaran($pengajaranDosen);
        $quizzes = Quiz::where('pengajaran_dosen_id', $pengajaranDosen->id)
            ->withCount('questions')
            ->latest()
            ->get();

        return view('lecturer.quiz.index', compact('pengajaranDosen', 'quizzes'));
    }

    /**
     * Form buat quiz baru.
     */
    public function create(PengajaranDosen $pengajaranDosen)
    {
        $this->authorizePengajaran($pengajaranDosen);
        return view('lecturer.quiz.create', compact('pengajaranDosen'));
    }

    /**
     * Simpan quiz baru (masih kosong, belum ada soal).
     */
    public function store(Request $request, PengajaranDosen $pengajaranDosen): RedirectResponse
    {
        $this->authorizePengajaran($pengajaranDosen);
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'durasi_menit' => ['nullable', 'integer', 'min:1', 'max:600'],
        ]);

        $quiz = Quiz::create([
            'pengajaran_dosen_id' => $pengajaranDosen->id,
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'durasi_menit' => $validated['durasi_menit'] ?? null,
        ]);

        return redirect()
            ->route('lecturer.quiz.show', $quiz)
            ->with('success', 'Quiz berhasil dibuat. Silakan download template lalu upload soal.');
    }

    /**
     * Detail quiz: download template, upload soal, preview, publish.
     */
    public function show(Quiz $quiz)
    {
        $this->authorizeQuiz($quiz);
        $quiz->load('questions', 'pengajaranDosen');

        return view('lecturer.quiz.show', compact('quiz'));
    }

    /**
     * Download template Excel kosong (dengan 1 baris contoh).
     */
    public function downloadTemplate(Quiz $quiz)
    {
        $this->authorizeQuiz($quiz);
        $namaFile = 'template-quiz-' . $quiz->id . '.xlsx';

        return Excel::download(new QuizTemplateExport, $namaFile);
    }

    /**
     * Upload & import soal dari file Excel yang sudah diisi dosen.
     */
    public function import(Request $request, Quiz $quiz): RedirectResponse
    {
        $this->authorizeQuiz($quiz);
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls'],
        ]);

        try {
            Excel::import(new QuizQuestionsImport($quiz->id), $request->file('file'));
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()
            ->route('lecturer.quiz.show', $quiz)
            ->with('success', 'Soal berhasil diimport.');
    }

    /**
     * Publish quiz supaya bisa dilihat/dikerjakan mahasiswa.
     */
    public function publish(Quiz $quiz): RedirectResponse
    {
        $this->authorizeQuiz($quiz);
        if ($quiz->questions()->count() === 0) {
            return back()->with('error', 'Quiz belum punya soal, upload soal terlebih dahulu sebelum publish.');
        }

        $quiz->update(['is_published' => true]);

        return redirect()
            ->route('lecturer.quiz.show', $quiz)
            ->with('success', 'Quiz berhasil dipublish dan bisa dilihat mahasiswa.');
    }

    /**
     * Hapus quiz beserta soal-soalnya.
     */
    public function destroy(Quiz $quiz): RedirectResponse
    {
        $this->authorizeQuiz($quiz);
        $pengajaranDosenId = $quiz->pengajaran_dosen_id;
        $quiz->delete();

        return redirect()
            ->route('lecturer.quiz.index', $pengajaranDosenId)
            ->with('success', 'Quiz berhasil dihapus.');
    }
    public function uploadGambarSoal(Request $request, QuizQuestion $quizQuestion)
    {
        $quizQuestion->loadMissing('quiz.pengajaranDosen');
        $this->authorizePengajaran($quizQuestion->quiz->pengajaranDosen);
        $request->validate([
            'gambar' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($quizQuestion->gambar) {
            Storage::disk('public')->delete($quizQuestion->gambar);
        }

        $path = $request->file('gambar')->store('soal-gambar', 'public');
        $quizQuestion->update(['gambar' => $path]);

        return redirect()->back()->with('success', 'Gambar soal berhasil ditambahkan.');
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

    private function authorizeQuiz(Quiz $quiz): void
    {
        $quiz->loadMissing('pengajaranDosen');
        $this->authorizePengajaran($quiz->pengajaranDosen);
    }

}
