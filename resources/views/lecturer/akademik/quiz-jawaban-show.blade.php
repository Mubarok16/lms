@extends('lecturer.app-lecturer-create-materi')
@section('ketjudul', 'Detail Jawaban Quiz')
@section('judul', 'Jawaban Mahasiswa')
@section('content')
<div class="mb-6">
    <a href="{{ route('lecturer.akademik.quiz.jawaban.index', $quiz) }}" class="text-sm text-teal hover:underline">← Kembali ke daftar mahasiswa</a>
    <div class="mt-3 flex flex-wrap items-end justify-between gap-4">
        <div><h1 class="font-display text-xl font-semibold">{{ $jawaban->mahasiswa->user->name ?? '-' }}</h1><p class="mt-1 text-sm text-ink/50">{{ $jawaban->mahasiswa->nim }} · {{ $quiz->judul }}</p></div>
        <div class="rounded-xl bg-ink px-5 py-3 text-white"><p class="text-xs text-white/60">Skor Quiz</p><p class="text-2xl font-bold">{{ number_format((float)$jawaban->skor, 2) }}</p></div>
    </div>
</div>
<div class="space-y-4">
@foreach($jawaban->detail->sortBy(fn($d) => $d->question->nomor ?? 0) as $detail)
    @php($q = $detail->question)
    <div class="rounded-2xl border border-line bg-white p-5 shadow-sm">
        <div class="flex items-start justify-between gap-4"><h2 class="font-semibold">{{ $q->nomor ?? '-' }}. {{ $q->pertanyaan ?? 'Soal tidak tersedia' }}</h2><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $detail->is_benar ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">{{ $detail->is_benar ? 'Benar' : 'Salah' }}</span></div>
        @if($q?->gambar)<img src="{{ asset('storage/'.$q->gambar) }}" class="mt-4 max-h-64 rounded-xl border border-line" alt="Gambar soal">@endif
        <div class="mt-4 grid gap-3 sm:grid-cols-2">
            <div class="rounded-xl bg-paper p-4"><p class="text-xs font-semibold uppercase text-ink/45">Jawaban mahasiswa</p><p class="mt-1 font-medium">{{ $detail->jawaban_dipilih ?? 'Tidak menjawab' }}@if($detail->jawaban_dipilih && $q) — {{ $q->{'pilihan_'.strtolower($detail->jawaban_dipilih)} }}@endif</p></div>
            <div class="rounded-xl bg-green-50 p-4"><p class="text-xs font-semibold uppercase text-green-700/70">Kunci jawaban</p><p class="mt-1 font-medium text-green-800">{{ $q->kunci_jawaban ?? '-' }}@if($q?->kunci_jawaban) — {{ $q->{'pilihan_'.strtolower($q->kunci_jawaban)} }}@endif</p></div>
        </div>
    </div>
@endforeach
</div>
@endsection
