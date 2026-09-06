@extends('lecturer.app-lecturer-create-materi')

@section('ketjudul')
Daftar Hasil Quiz
@endsection

@section('judul')
{{ $quiz->judul }}
@endsection

@section('content')
<div class="bg-paper">
    <div class="mx-auto max-w-4xl px-6 py-8">
        <div class="mb-6">
            <a href="{{ route('lecturer.quiz.show', $quiz) }}" class="text-sm font-medium text-ink/50 hover:text-ink">&larr; Kembali ke detail quiz</a>
        </div>
        <div class="rounded-2xl border border-line bg-white shadow-sm">
            <div class="border-b border-line p-6">
                <h1 class="font-display text-lg font-semibold text-ink">Mahasiswa yang Sudah Mengerjakan</h1>
                <p class="mt-1 text-sm text-ink/50">{{ $jawabanList->count() }} mahasiswa sudah submit quiz.</p>
            </div>
            <div class="divide-y divide-line">
                @forelse ($jawabanList as $jawaban)
                    <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-ink">{{ $jawaban->mahasiswa->user->name ?? '-' }}</p>
                            <p class="mt-0.5 text-xs text-ink/40">NIM: {{ $jawaban->mahasiswa->nim ?? '-' }}</p>
                            <p class="mt-0.5 text-xs text-ink/50">Submit: {{ $jawaban->waktu_submit?->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="rounded-full bg-green-50 px-3 py-1 text-sm font-semibold text-green-700">Nilai: {{ $jawaban->skor ?? 0 }}</span>
                            <a href="{{ route('lecturer.quiz.jawaban.show', [$quiz, $jawaban]) }}" class="rounded-lg border border-line px-3 py-2 text-xs font-semibold text-ink hover:bg-paper">Detail Jawaban</a>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-ink/50">Belum ada mahasiswa yang mengerjakan quiz.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
