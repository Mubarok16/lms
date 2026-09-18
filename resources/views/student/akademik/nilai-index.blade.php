@extends('student.app-student')
@section('ketjudul', 'Nilai')
@section('judul', 'Nilai per Mata Kuliah')
@section('content')
<div class="mb-6"><h1 class="font-display text-xl font-semibold">Nilai Saya</h1><p class="mt-1 text-sm text-ink/50">Lihat rekap tugas, quiz, dan kehadiran untuk setiap mata kuliah.</p></div>
<div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
@forelse($kelasList as $kelas)<a href="{{ route('student.akademik.nilai.kelas',$kelas) }}" class="rounded-2xl border border-line bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"><p class="text-xs font-mono text-ink/45">{{ $kelas->kode_mk }} · Kelas {{ $kelas->kode_kelas }}</p><h2 class="mt-2 font-display text-lg font-semibold">{{ $kelas->matakuliah->nama_mk }}</h2><div class="mt-5 grid grid-cols-3 gap-2 text-center"><div class="rounded-lg bg-paper p-2"><p class="text-[10px] uppercase text-ink/40">Tugas</p><p class="mt-1 text-sm font-semibold">{{ $kelas->tugas_avg !== null ? number_format($kelas->tugas_avg,2) : '-' }}</p></div><div class="rounded-lg bg-paper p-2"><p class="text-[10px] uppercase text-ink/40">Quiz</p><p class="mt-1 text-sm font-semibold">{{ $kelas->quiz_avg !== null ? number_format($kelas->quiz_avg,2) : '-' }}</p></div><div class="rounded-lg bg-paper p-2"><p class="text-[10px] uppercase text-ink/40">Hadir</p><p class="mt-1 text-sm font-semibold">{{ $kelas->attendance_percent !== null ? number_format($kelas->attendance_percent,0).'%' : '-' }}</p></div></div></a>@empty<div class="sm:col-span-2 xl:col-span-3 rounded-2xl border border-dashed border-line bg-white p-10 text-center text-sm text-ink/50">Belum ada mata kuliah.</div>@endforelse
</div>
@endsection
