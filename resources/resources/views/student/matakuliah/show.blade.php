@extends('student.app-student')

@section('ketjudul')
Mata Kuliah oleh
@foreach ($dosenList as $pj)
{{ $pj->lecturer->user->name }}@if (!$loop->last), @endif
@endforeach
@endsection

@section('judul')
{{ $kelas->matakuliah->nama_mk }}
@endsection

@section('content')

<div class="lg:col-span-2">

    {{-- Navigation --}}
    <div class="mb-6 overflow-x-auto">
        <div class="flex min-w-max gap-1 rounded-xl border border-line bg-white p-1">
            <a href="#materi" class="rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white">
                Materi
            </a>
            <a href="#tugas" class="rounded-lg px-4 py-2.5 text-sm font-medium text-ink/60 transition hover:bg-paper hover:text-ink">
                Tugas
            </a>
            <a href="#quiz" class="rounded-lg px-4 py-2.5 text-sm font-medium text-ink/60 transition hover:bg-paper hover:text-ink">
                Quiz
            </a>
            <a href="#absensi" class="rounded-lg px-4 py-2.5 text-sm font-medium text-ink/60 transition hover:bg-paper hover:text-ink">
                Absensi
            </a>
        </div>
    </div>

    {{-- =================================================
        MATERI
        ================================================== --}}
    <div id="materi" class="rounded-2xl border border-line bg-white shadow-sm">

        <div class="border-b border-line p-6">
            <h2 class="font-display text-lg font-semibold text-ink">Materi Pembelajaran</h2>
            <p class="mt-1 text-sm text-ink/50">Materi yang telah dibagikan oleh dosen.</p>
        </div>

        <div class="divide-y divide-line">
            @forelse ($materiList as $materi)
            <div x-data="{ open: false }">
                <button type="button" @click="open = !open"
                    class="flex w-full items-center gap-4 p-5 text-left">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-paper text-ink">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1">
                        <h3 class="truncate text-sm font-semibold text-ink">{{ $materi->judul }}</h3>
                        <p class="mt-1 text-xs text-ink/50">Materi pembelajaran</p>
                    </div>

                    <svg class="h-4 w-4 shrink-0 text-ink/30 transition-transform" :class="open ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-cloak class="space-y-5 px-5 pb-5">
                    @if ($materi->deskripsi)
                    <div class="prose prose-sm max-w-none text-sm text-ink/60">
                        {!! $materi->deskripsi !!}
                    </div>
                    @endif

                    @foreach ($materi->files as $file)
                    @if ($file->tipe === 'pdf')
                    <iframe src="{{ $file->url }}" class="h-[480px] w-full rounded-xl border border-line" loading="lazy"></iframe>
                    @elseif ($file->tipe === 'audio')
                    <audio controls class="w-full">
                        <source src="{{ $file->url }}">
                    </audio>
                    @elseif ($file->tipe === 'video_youtube' && $file->youtube_embed_url)
                    <div class="aspect-video w-full overflow-hidden rounded-xl border border-line">
                        <iframe src="{{ $file->youtube_embed_url }}" class="h-full w-full" loading="lazy" allowfullscreen></iframe>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @empty
            <div class="p-6">
                <div class="rounded-xl border border-dashed border-line bg-paper p-8 text-center">
                    <p class="text-sm text-ink/50">Belum ada materi.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    {{-- =================================================
        TUGAS
        ================================================== --}}
    <div id="tugas" class="mt-8 overflow-hidden rounded-2xl border border-line bg-white shadow-sm">

        <div class="border-b border-line p-6">
            <h2 class="font-display text-lg font-semibold text-ink">Tugas</h2>
            <p class="mt-1 text-sm text-ink/50">Kerjakan dan kumpulkan tugas sebelum deadline.</p>
        </div>

        <div class="divide-y divide-line">
            @forelse ($tugasList as $tugas)
            @php
            $jawabanSaya = $tugas->jawaban->first();
            $sudahSubmit = $jawabanSaya && $jawabanSaya->status !== 'belum_submit';
            $sudahDinilai = $jawabanSaya && $jawabanSaya->status === 'sudah_dikoreksi';
            $lewatDeadline = $tugas->deadline && now()->greaterThan($tugas->deadline);
            @endphp

            <div class="flex items-center justify-between gap-4 p-5">

                <div class="min-w-0 flex-1">
                    <h3 class="truncate text-sm font-semibold text-ink">{{ $tugas->judul }}</h3>
                    <p class="mt-1 text-xs text-ink/50">
                        @if ($tugas->deadline)
                        Deadline: {{ $tugas->deadline->format('d M Y, H:i') }}
                        @else
                        Tanpa deadline
                        @endif
                    </p>
                </div>

                <div class="shrink-0">
                    @if ($sudahDinilai)
                    <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                        Nilai: {{ $jawabanSaya->skor }}
                    </span>
                    @elseif ($sudahSubmit)
                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                        Terkumpul
                    </span>
                    @elseif ($lewatDeadline)
                    <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-600">
                        Terlambat
                    </span>
                    @else
                    <span class="rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700">
                        Belum Dikerjakan
                    </span>
                    @endif
                </div>

                <a href="{{ route('student.tugas.show', $tugas->id) }}"
                    class="shrink-0 rounded-lg border border-line px-3 py-1.5 text-xs font-semibold text-ink transition hover:bg-paper">
                    Lihat
                </a>

            </div>
            @empty
            <div class="p-6">
                <div class="rounded-xl border border-dashed border-line bg-paper p-8 text-center">
                    <p class="text-sm text-ink/50">Belum ada tugas.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    {{-- =================================================
        QUIZ
        ================================================== --}}
    <div id="quiz" class="mt-8 overflow-hidden rounded-2xl border border-line bg-white shadow-sm">

        <div class="border-b border-line p-6">
            <h2 class="font-display text-lg font-semibold text-ink">Quiz</h2>
            <p class="mt-1 text-sm text-ink/50">Quiz pilihan ganda untuk mata kuliah ini.</p>
        </div>

        <div class="divide-y divide-line">
            @forelse ($quizList as $quiz)
            <a href="{{ route('student.quiz.show', $quiz->id) }}"
                class="flex items-center justify-between gap-4 p-5 transition hover:bg-paper">

                <div class="min-w-0 flex-1">
                    <h3 class="truncate text-sm font-semibold text-ink">{{ $quiz->judul }}</h3>
                    <p class="mt-1 text-xs text-ink/50">
                        {{ $quiz->questions_count }} soal
                        @if ($quiz->durasi_menit)
                        • {{ $quiz->durasi_menit }} menit
                        @endif
                    </p>
                </div>

                <svg class="h-4 w-4 shrink-0 text-ink/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
            @empty
            <div class="p-6">
                <div class="rounded-xl border border-dashed border-line bg-paper p-8 text-center">
                    <p class="text-sm text-ink/50">Belum ada quiz.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    {{-- =================================================
        ABSENSI
        ================================================== --}}
    <div id="absensi" class="mt-8 overflow-hidden rounded-2xl border border-line bg-white shadow-sm">

        <div class="border-b border-line p-6">
            <h2 class="font-display text-lg font-semibold text-ink">Absensi</h2>
            <p class="mt-1 text-sm text-ink/50">Scan QR yang dibagikan dosen untuk mengisi kehadiran.</p>
        </div>

        <div class="p-6">
            <a href="{{ route('student.absensi.scan') }}"
                class="flex items-center justify-center gap-2 rounded-lg bg-ink px-4 py-3
                           text-sm font-semibold text-white transition hover:bg-primaryDark">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Scan QR Absensi
            </a>
        </div>
    </div>

</div>

{{-- =================================================
    SIDEBAR
    ================================================== --}}
<div class="space-y-6">

    {{-- Info Mata Kuliah --}}
    <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">
        <h2 class="font-display text-base font-semibold text-ink">Info Mata Kuliah</h2>

        <div class="mt-4 space-y-3 text-sm">
            <div class="flex items-center justify-between">
                <span class="text-ink/50">Kode MK</span>
                <span class="font-semibold text-ink">{{ $kelas->kode_mk }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-ink/50">Kelas</span>
                <span class="font-semibold text-ink">{{ $kelas->kode_kelas }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-ink/50">SKS</span>
                <span class="font-semibold text-ink">{{ $kelas->matakuliah->sks }}</span>
            </div>
        </div>
    </div>

    {{-- Dosen Pengajar --}}
    <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">
        <h2 class="font-display text-base font-semibold text-ink">Dosen Pengajar</h2>

        <div class="mt-4 space-y-3">
            @forelse ($dosenList as $pj)
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-paper text-sm font-semibold text-ink">
                    {{ strtoupper(substr($pj->lecturer->user->name ?? 'D', 0, 1)) }}
                </div>
                <p class="text-sm font-medium text-ink">{{ $pj->lecturer->user->name }}</p>
            </div>
            @empty
            <p class="text-sm text-ink/50">Belum ada dosen pengajar.</p>
            @endforelse
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">
        <h2 class="font-display text-base font-semibold text-ink">Ringkasan</h2>

        <div class="mt-5 space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-sm text-ink/50">Materi</span>
                <span class="font-semibold text-ink">{{ $materiList->count() }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-ink/50">Tugas</span>
                <span class="font-semibold text-ink">{{ $tugasList->count() }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-ink/50">Quiz</span>
                <span class="font-semibold text-ink">{{ $quizList->count() }}</span>
            </div>
        </div>
    </div>

</div>

@endsection