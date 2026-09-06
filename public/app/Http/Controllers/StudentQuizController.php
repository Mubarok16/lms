<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizJawaban;
use App\Models\QuizJawabanDetail;
use Illuminate\Http\Request;

class StudentQuizController extends Controller
{
    public function show(Quiz $quiz)
    {
        $mahasiswa = auth()->user()->student;
        $sessionKey = 'quiz_mulai_' . $quiz->id . '_' . auth()->user()->student->id;
        abort_unless($quiz->is_published, 404);

        $mahasiswaKelas = \App\Models\PengajaranMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->whereHas('kelas.pengajaranDosen', fn ($q) => $q->where('id', $quiz->pengajaran_dosen_id))
            ->exists();
        abort_unless($mahasiswaKelas, 403);

        $quiz->load('questions');

        $jawabanSaya = QuizJawaban::where('quiz_id', $quiz->id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();
        $waktuSelesai = null;
        if (!$jawabanSaya && $quiz->durasi_menit) {
            $waktuMulai = session($sessionKey);
            $waktuSelesai = \Carbon\Carbon::parse($waktuMulai)->addMinutes($quiz->durasi_menit);
        }
        return view('student.quiz.show', compact('quiz', 'jawabanSaya', 'waktuSelesai'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $mahasiswa = auth()->user()->student;

        abort_unless($quiz->is_published, 404);
        $mahasiswaKelas = \App\Models\PengajaranMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->whereHas('kelas.pengajaranDosen', fn ($q) => $q->where('id', $quiz->pengajaran_dosen_id))
            ->exists();
        abort_unless($mahasiswaKelas, 403);

        abort_if(
            QuizJawaban::where('quiz_id', $quiz->id)->where('mahasiswa_id', $mahasiswa->id)->exists(),
            403,
            'Kamu sudah pernah mengerjakan quiz ini.'
        );

        $validated = $request->validate([
            'jawaban'     => 'nullable|array',
            'jawaban.*'   => 'nullable|in:A,B,C,D,E',
            'auto_submit' => 'nullable|boolean',
        ]);

        $jawabanArray = $validated['jawaban'] ?? [];

        $this->simpanJawaban($quiz, $mahasiswa, $jawabanArray);

        session()->forget('quiz_mulai_' . $quiz->id . '_' . $mahasiswa->id);

        $isAutoSubmit = $request->boolean('auto_submit');

        return redirect()
            ->route('student.quiz.show', $quiz->id)
            ->with('success', $isAutoSubmit
                ? 'Waktu habis, jawaban kamu otomatis dikirim.'
                : "Quiz selesai!");
    }

    private function autoSubmitKosong(Quiz $quiz, $mahasiswa, string $sessionKey)
    {
        // Guard: kalau ternyata sudah ada jawaban (race condition), skip
        if (QuizJawaban::where('quiz_id', $quiz->id)->where('mahasiswa_id', $mahasiswa->id)->exists()) {
            session()->forget($sessionKey);
            return redirect()->route('student.quiz.show', $quiz->id);
        }

        $this->simpanJawaban($quiz, $mahasiswa, []);
        session()->forget($sessionKey);

        return redirect()
            ->route('student.quiz.show', $quiz->id)
            ->with('success', 'Waktu pengerjaan sudah habis, jawaban dikirim otomatis.');
    }

    private function simpanJawaban(Quiz $quiz, $mahasiswa, array $jawabanArray)
    {
        $jumlahBenar = 0;

        $quizJawaban = QuizJawaban::create([
            'quiz_id'      => $quiz->id,
            'mahasiswa_id' => $mahasiswa->id,
            'waktu_submit' => now(),
        ]);

        foreach ($quiz->questions as $question) {
            $dipilih = $jawabanArray[$question->id] ?? null;
            $benar   = $dipilih === $question->kunci_jawaban;

            if ($benar) {
                $jumlahBenar++;
            }

            QuizJawabanDetail::create([
                'quiz_jawaban_id'  => $quizJawaban->id,
                'quiz_question_id' => $question->id,
                'jawaban_dipilih'  => $dipilih,
                'is_benar'         => $benar,
            ]);
        }

        $totalSoal = $quiz->questions->count();
        $skor = $totalSoal > 0 ? round(($jumlahBenar / $totalSoal) * 100, 2) : 0;

        $quizJawaban->update(['skor' => $skor]);

        return $quizJawaban;
    }
}
