@extends('student.app-student')
@section('ketjudul', 'Tugas')
@section('judul', 'Daftar Tugas')
@section('content')
<div class="mb-6"><a href="{{ route('student.akademik.tugas.index') }}" class="text-sm text-teal hover:underline">← Kembali ke mata kuliah</a><h1 class="mt-2 font-display text-xl font-semibold">{{ $kelas->matakuliah->nama_mk }}</h1><p class="mt-1 text-sm text-ink/50">{{ $kelas->kode_mk }} · Kelas {{ $kelas->kode_kelas }}</p></div>
<div class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm"><div class="divide-y divide-line">
@forelse($tugasList as $tugas)@php($jawaban=$tugas->jawaban->first())<a href="{{ route('student.tugas.show',$tugas) }}" class="flex flex-col gap-3 p-5 hover:bg-paper sm:flex-row sm:items-center sm:justify-between"><div><p class="font-semibold">{{ $tugas->judul }}</p><p class="mt-1 text-xs text-ink/50">Deadline: {{ $tugas->deadline?->format('d M Y H:i') ?? 'Tanpa deadline' }}</p></div><div class="flex items-center gap-3">@if($jawaban?->status === 'sudah_dikoreksi')<span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">Nilai {{ number_format((float)$jawaban->skor,2) }}</span>@elseif($jawaban)<span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">Terkumpul</span>@else<span class="rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700">Belum submit</span>@endif<span class="font-semibold text-teal">Buka →</span></div></a>
@empty<div class="p-10 text-center text-sm text-ink/50">Belum ada tugas pada mata kuliah ini.</div>@endforelse
</div></div>
@endsection
