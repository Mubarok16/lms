@extends('lecturer.app-lecturer')

@section('ketjudul')
Detail Jawaban Quiz
@endsection

@section('judul')
{{ $quiz->judul }}
@endsection

@section('content')
<div class="bg-paper">
    <div class="mx-auto max-w-4xl px-6 py-8">
        <a href="{{ route('lecturer.quiz.show', $quiz) }}" class="text-sm font-medium text-ink/60 hover:text-ink">&larr; Kembali ke quiz</a>

        <div class="mt-5 rounded-2xl border border-line bg-white p-6 shadow-sm">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                <div>
                    <h1 class="font-display text-xl font-semibold text-ink">{{ $jawaban->mahasiswa->user->name ?? '-' }}</h1>
                    <p class="mt-1 text-sm text-ink/50">NIM: {{ $jawaban->mahasiswa->nim ?? '-' }}</p>
                    <p class="mt-1 text-xs text-ink/50">Dikumpulkan: {{ $jawaban->waktu_submit?->translatedFormat('d M Y, H:i') ?? '-' }}</p>
                </div>
                <div class="rounded-xl bg-paper px-5 py-3 text-center">
                    <p class="text-xs text-ink/50">Nilai</p>
                    <p class="text-2xl font-bold text-ink">{{ number_format((float) $jawaban->skor, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6 space-y-4">
            @foreach($quiz->questions as $question)
                @php($detail = $details->get($question->id))
                <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">
                    <p class="text-sm font-semibold text-ink">{{ $question->nomor }}. {{ $question->pertanyaan }}</p>
                    @if($question->gambar)
                        <img src="{{ asset('storage/'.$question->gambar) }}" class="mt-3 max-h-64 rounded-lg border border-line" alt="Gambar soal">
                    @endif
                    <div class="mt-4 space-y-2">
                        @foreach($question->pilihanTersedia() as $huruf => $teks)
                            <div class="rounded-lg border px-3 py-2 text-sm {{ $detail && $detail->jawaban_dipilih === $huruf ? ($detail->is_benar ? 'border-green-300 bg-green-50 text-green-800' : 'border-red-300 bg-red-50 text-red-800') : ($huruf === $question->kunci_jawaban ? 'border-green-200 bg-green-50/50 text-green-800' : 'border-line text-ink/70') }}">
                                <span class="font-semibold">{{ $huruf }}.</span> {{ $teks }}
                                @if($detail && $detail->jawaban_dipilih === $huruf) <span class="ml-2 text-xs font-semibold">(Jawaban Mahasiswa)</span> @endif
                                @if($huruf === $question->kunci_jawaban) <span class="ml-2 text-xs font-semibold">(Kunci)</span> @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-xs font-semibold {{ $detail?->is_benar ? 'text-green-700' : 'text-red-700' }}">
                        {{ $detail?->is_benar ? 'Jawaban benar' : 'Jawaban salah / tidak dijawab' }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
