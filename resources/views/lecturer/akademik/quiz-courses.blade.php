@extends('lecturer.app-lecturer-create-materi')
@section('ketjudul', 'Quiz')
@section('judul', 'Quiz per Mata Kuliah')
@section('content')
<div class="mb-6">
    <h1 class="font-display text-xl font-semibold">Pilih Mata Kuliah</h1>
    <p class="mt-1 text-sm text-ink/50">Pilih mata kuliah untuk melihat quiz dan jawaban mahasiswa.</p>
</div>
<div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
@forelse($pengajaranList as $pengajaran)
    <a href="{{ route('lecturer.akademik.quiz.list', $pengajaran) }}" class="group rounded-2xl border border-line bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
        <p class="text-xs font-mono text-ink/45">{{ $pengajaran->kelas->kode_mk }} · Kelas {{ $pengajaran->kelas->kode_kelas }}</p>
        <h2 class="mt-2 font-display text-lg font-semibold">{{ $pengajaran->kelas->matakuliah->nama_mk }}</h2>
        <div class="mt-5 flex items-center justify-between"><span class="rounded-full bg-paper px-3 py-1 text-xs font-semibold">{{ $pengajaran->quizzes_count }} quiz</span><span class="text-sm font-semibold text-teal">Lihat quiz →</span></div>
    </a>
@empty
    <div class="sm:col-span-2 xl:col-span-3 rounded-2xl border border-dashed border-line bg-white p-10 text-center text-sm text-ink/50">Belum ada mata kuliah yang Anda ampu.</div>
@endforelse
</div>
@endsection
