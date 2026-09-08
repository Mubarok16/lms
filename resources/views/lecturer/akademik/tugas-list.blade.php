@extends('lecturer.app-lecturer-create-materi')
@section('ketjudul', 'Tugas')
@section('judul', 'Daftar Tugas')
@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <a href="{{ route('lecturer.akademik.tugas.courses') }}" class="text-sm text-teal hover:underline">← Kembali ke mata kuliah</a>
        <h1 class="mt-2 font-display text-xl font-semibold">{{ $pengajaranDosen->kelas->matakuliah->nama_mk }}</h1>
        <p class="mt-1 text-sm text-ink/50">{{ $pengajaranDosen->kelas->kode_mk }} · Kelas {{ $pengajaranDosen->kelas->kode_kelas }}</p>
    </div>
    <a href="{{ route('lecturer.tugas.create', $pengajaranDosen) }}" class="rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white hover:bg-primaryDark">+ Tambah Tugas</a>
</div>
<div class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
    <div class="divide-y divide-line">
    @forelse($tugasList as $tugas)
        <a href="{{ route('lecturer.tugas.jawaban.index', $tugas) }}" class="flex flex-col gap-3 p-5 transition hover:bg-paper sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold">{{ $tugas->judul }}</h2>
                <p class="mt-1 text-xs text-ink/50">Deadline: {{ $tugas->deadline?->format('d M Y H:i') ?? 'Tanpa deadline' }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $tugas->jawaban_count }} submit</span>
                <span class="text-sm font-semibold text-teal">Buka koreksi →</span>
            </div>
        </a>
    @empty
        <div class="p-10 text-center text-sm text-ink/50">Belum ada tugas pada mata kuliah ini.</div>
    @endforelse
    </div>
</div>
@endsection
