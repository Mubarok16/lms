@extends('lecturer.app-lecturer-create-materi')

@section('ketjudul')
Detail Jawaban Quiz
@endsection

@section('judul')
{{ $quiz->judul }}
@endsection

@section('content')
<div class="bg-paper">
    <div class="mx-auto max-w-4xl px-6 py-8">
        <div class="mb-6">
            <a href="{{ route('lecturer.quiz.jawaban.index', $quiz) }}" class="text-sm font-medium text-ink/50 hover:text-ink">&larr; Kembali ke hasil quiz</a>
        </div>

        <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                <div>
                    <h1 class="font-display text-lg font-semibold text-ink">{{ $jawaban->mahasiswa->user->name ?? '-' }}</h1>
                    <p class="mt-1 text-xs text-ink/40">NIM: {{ $jawaban->mahasiswa->nim ?? '-' }}</p>
                    <p class="mt-1 text-sm text-ink/50">Submit: {{ $jawaban->waktu_submit?->translatedFormat('d M Y, H:i') }}</p>
                </div>
                <div class="rounded-xl bg-paper px-5 py-3 text-center">
                    <p class="text-xs text-ink/50">Nilai</p>
                    <p class="text-2xl font-bold text-ink">{{ $jawaban->skor ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
            <div class="border-b border-line p-6">
                <h2 class="font-display text-base font-semibold text-ink">Detail Jawaban</h2>
                <p class="mt-1 text-sm text-ink/50">Jawaban mahasiswa dibandingkan dengan kunci jawaban.</p>
            </div>
            <div class="divide-y divide-line">
                @foreach ($quiz->questions as $question)
                    @php $detail = $jawaban->detail->firstWhere('quiz_question_id', $question->id); @endphp
                    <div class="p-6">
                        <div class="flex gap-3">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-paper text-xs font-semibold text-ink">{{ $question->nomor }}</span>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-ink">{{ $question->pertanyaan }}</p>
                                <div class="mt-3 grid gap-2 sm:grid-cols-2">
                                    @foreach ($question->pilihanTersedia() as $huruf => $teks)
                                        <div class="rounded-lg border px-3 py-2 text-sm {{ $detail?->jawaban_dipilih === $huruf ? ($detail->is_benar ? 'border-green-300 bg-green-50 text-green-800' : 'border-red-300 bg-red-50 text-red-800') : ($question->kunci_jawaban === $huruf ? 'border-green-200 bg-green-50/50 text-green-800' : 'border-line text-ink/70') }}">
                                            <span class="font-semibold">{{ $huruf }}.</span> {{ $teks }}
                                            @if ($detail?->jawaban_dipilih === $huruf)<span class="ml-1 text-xs font-semibold">(Jawaban mahasiswa)</span>@endif
                                            @if ($question->kunci_jawaban === $huruf)<span class="ml-1 text-xs font-semibold">(Kunci)</span>@endif
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-3 text-xs font-medium {{ $detail?->is_benar ? 'text-green-700' : 'text-red-700' }}">
                                    {{ $detail?->jawaban_dipilih ? 'Jawaban: '.$detail->jawaban_dipilih : 'Tidak menjawab' }}
                                    • {{ $detail?->is_benar ? 'Benar' : 'Salah' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
