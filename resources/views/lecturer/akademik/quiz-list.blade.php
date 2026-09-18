@extends('lecturer.app-lecturer-create-materi')
@section('ketjudul', 'Quiz')
@section('judul', 'Daftar Quiz')
@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <a href="{{ route('lecturer.akademik.quiz.courses') }}" class="text-sm text-teal hover:underline">← Kembali ke mata kuliah</a>
        <h1 class="mt-2 font-display text-xl font-semibold">{{ $pengajaranDosen->kelas->matakuliah->nama_mk }}</h1>
        <p class="mt-1 text-sm text-ink/50">{{ $pengajaranDosen->kelas->kode_mk }} · Kelas {{ $pengajaranDosen->kelas->kode_kelas }}</p>
    </div>
    <a href="{{ route('lecturer.quiz.create', $pengajaranDosen) }}" class="rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white">+ Tambah Quiz</a>
</div>
<div class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
<div class="divide-y divide-line">
@forelse($quizList as $quiz)
    <a href="{{ route('lecturer.akademik.quiz.jawaban.index', $quiz) }}" class="flex flex-col gap-3 p-5 hover:bg-paper sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex flex-wrap items-center gap-2"><h2 class="font-semibold">{{ $quiz->judul }}</h2><span class="rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $quiz->is_published ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }}">{{ $quiz->is_published ? 'Published' : 'Draft' }}</span></div>
            <p class="mt-1 text-xs text-ink/50">{{ $quiz->questions_count }} soal · {{ $quiz->durasi_menit ? $quiz->durasi_menit.' menit' : 'tanpa batas durasi' }}</p>
        </div>
        <div class="flex items-center gap-3"><span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $quiz->jawaban_count }} submit</span><span class="text-sm font-semibold text-teal">Lihat mahasiswa →</span></div>
    </a>
@empty
    <div class="p-10 text-center text-sm text-ink/50">Belum ada quiz pada mata kuliah ini.</div>
@endforelse
</div>
</div>
@endsection
