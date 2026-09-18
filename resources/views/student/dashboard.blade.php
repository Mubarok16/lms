@extends('student.app-student')
@section('ketjudul', 'Dashboard')
@section('judul', 'Ringkasan Belajar Saya')
@section('content')
<div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
    <a href="{{ route('student.matakuliah.index') }}" class="rounded-2xl border border-line bg-white p-6 shadow-sm transition hover:shadow-md">
        <p class="text-xs font-mono uppercase tracking-wide text-ink/45">Mata Kuliah</p>
        <p class="mt-4 font-display text-3xl font-semibold">{{ $totalMatkul }}</p>
        <p class="mt-1 text-sm text-ink/50">Mata kuliah yang diikuti</p>
    </a>
    <a href="{{ route('student.akademik.tugas.index') }}" class="rounded-2xl border border-line bg-white p-6 shadow-sm transition hover:shadow-md">
        <p class="text-xs font-mono uppercase tracking-wide text-ink/45">Tugas</p>
        <p class="mt-4 font-display text-3xl font-semibold">{{ $pendingTugas }}</p>
        <p class="mt-1 text-sm text-ink/50">Belum dikumpulkan</p>
    </a>
    <a href="{{ route('student.akademik.quiz.index') }}" class="rounded-2xl border border-line bg-white p-6 shadow-sm transition hover:shadow-md">
        <p class="text-xs font-mono uppercase tracking-wide text-ink/45">Quiz</p>
        <p class="mt-4 font-display text-3xl font-semibold">{{ $pendingQuiz }}</p>
        <p class="mt-1 text-sm text-ink/50">Belum dikerjakan</p>
    </a>
    <a href="{{ route('student.akademik.nilai.index') }}" class="rounded-2xl border border-line bg-white p-6 shadow-sm transition hover:shadow-md">
        <p class="text-xs font-mono uppercase tracking-wide text-ink/45">Rata-rata Nilai</p>
        <p class="mt-4 font-display text-3xl font-semibold">{{ $nilaiRataRata !== null ? number_format((float)$nilaiRataRata, 2) : '-' }}</p>
        <p class="mt-1 text-sm text-ink/50">Rata-rata tugas dan quiz yang sudah dinilai</p>
    </a>
</div>

<div class="mt-8 grid gap-6 xl:grid-cols-2">
    <section class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-line p-5">
            <div><h2 class="font-display text-lg font-semibold">Tugas yang Perlu Dikerjakan</h2><p class="mt-1 text-xs text-ink/45">Tugas yang belum kamu submit.</p></div>
            <a href="{{ route('student.akademik.tugas.index') }}" class="text-sm font-semibold text-teal">Lihat semua</a>
        </div>
        <div class="divide-y divide-line">
            @forelse($tugasTerbaru as $tugas)
                <a href="{{ route('student.tugas.show', $tugas) }}" class="block p-5 transition hover:bg-paper">
                    <div class="flex items-start justify-between gap-4">
                        <div><p class="font-semibold">{{ $tugas->judul }}</p><p class="mt-1 text-xs text-ink/45">{{ $tugas->pengajaranDosen->kelas->matakuliah->nama_mk ?? '-' }} · Kelas {{ $tugas->pengajaranDosen->kelas->kode_kelas ?? '-' }}</p></div>
                        <span class="shrink-0 rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700">{{ $tugas->deadline?->format('d M H:i') ?? 'Tanpa deadline' }}</span>
                    </div>
                </a>
            @empty
                <div class="p-8 text-center text-sm text-ink/50">Tidak ada tugas yang belum dikumpulkan.</div>
            @endforelse
        </div>
    </section>

    <section class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-line p-5">
            <div><h2 class="font-display text-lg font-semibold">Quiz yang Tersedia</h2><p class="mt-1 text-xs text-ink/45">Quiz published yang belum kamu kerjakan.</p></div>
            <a href="{{ route('student.akademik.quiz.index') }}" class="text-sm font-semibold text-teal">Lihat semua</a>
        </div>
        <div class="divide-y divide-line">
            @forelse($quizTerbaru as $quiz)
                <a href="{{ route('student.quiz.show', $quiz) }}" class="block p-5 transition hover:bg-paper">
                    <div class="flex items-start justify-between gap-4"><div><p class="font-semibold">{{ $quiz->judul }}</p><p class="mt-1 text-xs text-ink/45">{{ $quiz->pengajaranDosen->kelas->matakuliah->nama_mk ?? '-' }} · Kelas {{ $quiz->pengajaranDosen->kelas->kode_kelas ?? '-' }}</p></div>@if($quiz->durasi_menit)<span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $quiz->durasi_menit }} menit</span>@endif</div>
                </a>
            @empty
                <div class="p-8 text-center text-sm text-ink/50">Tidak ada quiz yang belum dikerjakan.</div>
            @endforelse
        </div>
    </section>
</div>
@endsection
