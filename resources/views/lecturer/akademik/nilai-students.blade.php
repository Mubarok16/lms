@extends('lecturer.app-lecturer-create-materi')
@section('ketjudul', 'Nilai')
@section('judul', 'Daftar Nilai Mahasiswa')
@section('content')
<div class="mb-6"><a href="{{ route('lecturer.akademik.nilai.courses') }}" class="text-sm text-teal hover:underline">← Kembali ke mata kuliah</a><h1 class="mt-2 font-display text-xl font-semibold">{{ $pengajaranDosen->kelas->matakuliah->nama_mk }}</h1><p class="mt-1 text-sm text-ink/50">{{ $pengajaranDosen->kelas->kode_mk }} · Kelas {{ $pengajaranDosen->kelas->kode_kelas }}</p></div>
<div class="overflow-x-auto rounded-2xl border border-line bg-white shadow-sm"><table class="min-w-full text-sm"><thead class="bg-paper text-left text-xs uppercase tracking-wide text-ink/50"><tr><th class="px-5 py-3">Mahasiswa</th><th class="px-5 py-3">Tugas</th><th class="px-5 py-3">Quiz</th><th class="px-5 py-3">Kehadiran</th><th class="px-5 py-3 text-right">Detail</th></tr></thead><tbody class="divide-y divide-line">
@forelse($rekap as $row)<tr><td class="px-5 py-4"><p class="font-medium">{{ $row['student']->user->name ?? '-' }}</p><p class="text-xs text-ink/45">{{ $row['student']->nim }}</p></td><td class="px-5 py-4">{{ $row['tugas_avg'] !== null ? number_format($row['tugas_avg'],2) : '-' }} <span class="text-xs text-ink/40">({{ $row['tugas_dinilai'] }} dinilai)</span></td><td class="px-5 py-4">{{ $row['quiz_avg'] !== null ? number_format($row['quiz_avg'],2) : '-' }} <span class="text-xs text-ink/40">({{ $row['quiz_dikerjakan'] }} quiz)</span></td><td class="px-5 py-4">{{ $row['attendance_percent'] !== null ? number_format($row['attendance_percent'],2).'%' : '-' }}</td><td class="px-5 py-4 text-right"><a href="{{ route('lecturer.akademik.nilai.student', [$pengajaranDosen, $row['student']]) }}" class="font-semibold text-teal hover:underline">Lihat rekap</a></td></tr>
@empty<tr><td colspan="5" class="px-5 py-10 text-center text-ink/50">Belum ada mahasiswa di kelas ini.</td></tr>@endforelse
</tbody></table></div>
@endsection
