@extends('student.app-student')
@section('ketjudul', 'Quiz')
@section('judul', 'Quiz per Mata Kuliah')
@section('content')
<div class="mb-6"><h1 class="font-display text-xl font-semibold">Quiz Saya</h1><p class="mt-1 text-sm text-ink/50">Pilih mata kuliah untuk melihat quiz yang tersedia.</p></div>
<div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
@forelse($kelasList as $kelas)
<a href="{{ route('student.akademik.quiz.kelas',$kelas) }}" class="rounded-2xl border border-line bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"><p class="text-xs font-mono text-ink/45">{{ $kelas->kode_mk }} · Kelas {{ $kelas->kode_kelas }}</p><h2 class="mt-2 font-display text-lg font-semibold">{{ $kelas->matakuliah->nama_mk }}</h2><div class="mt-5 flex items-center justify-between gap-3"><span class="rounded-full bg-paper px-3 py-1 text-xs font-semibold">{{ $kelas->jumlah_quiz }} quiz</span>@if($kelas->quiz_belum > 0)<span class="rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700">{{ $kelas->quiz_belum }} belum dikerjakan</span>@else<span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">Selesai</span>@endif</div></a>
@empty<div class="sm:col-span-2 xl:col-span-3 rounded-2xl border border-dashed border-line bg-white p-10 text-center text-sm text-ink/50">Kamu belum terdaftar di mata kuliah apa pun.</div>@endforelse
</div>
@endsection
