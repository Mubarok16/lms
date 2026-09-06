@extends('lecturer.app-lecturer')

@section('ketjudul')
    Mata Kuliah oleh
    @foreach ($pengajaran->pengajaranDosen as $pj)
        {{ $pj->lecturer->user->name }}
    @endforeach
@endsection

@section('judul')
    {{ $pengajaran->matakuliah->nama_mk }}
@endsection

@section('content')

    <div class="bg-paper">

        {{-- =========================================================
        HEADER MATA KULIAH
    ========================================================== --}}


        {{-- =========================================================
        MAIN CONTENT
    ========================================================== --}}
        <div class="mx-auto max-w-7xl px-6 py-8">

            {{-- Notifikasi sukses (hapus, tambah, dll) --}}
            @if (session('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">


                {{-- =================================================
                LEFT CONTENT
            ================================================== --}}
                <div class="lg:col-span-2">


                    {{-- =================================================
                    NAVIGATION
                ================================================== --}}
                    <div class="mb-6 overflow-x-auto">

                        <div class="flex min-w-max gap-1 rounded-xl border border-line bg-white p-1">

                            {{-- Materi --}}
                            <a href="#materi" class="rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white">
                                Materi
                            </a>

                            {{-- Tugas --}}
                            <a href="#tugas"
                                class="rounded-lg px-4 py-2.5 text-sm font-medium text-ink/60
                                   transition hover:bg-paper hover:text-ink">
                                Tugas
                            </a>

                            {{-- Quiz --}}
                            <a href="#quiz"
                                class="rounded-lg px-4 py-2.5 text-sm font-medium text-ink/60
                                   transition hover:bg-paper hover:text-ink">
                                Quiz
                            </a>

                            {{-- Mahasiswa --}}
                            <a href="#mahasiswa"
                                class="rounded-lg px-4 py-2.5 text-sm font-medium text-ink/60
                                   transition hover:bg-paper hover:text-ink">
                                Mahasiswa
                            </a>
                            {{-- Absensi --}}
                            <a href="#absensi"
                                class="rounded-lg px-4 py-2.5 text-sm font-medium text-ink/60
                                   transition hover:bg-paper hover:text-ink">
                                Absensi
                            </a>
                        </div>

                    </div>


                    {{-- =================================================
                    MATERI
                ================================================== --}}
                    <div id="materi" class="rounded-2xl border border-line bg-white shadow-sm">

                        {{-- Header Materi --}}
                        <div
                            class="flex flex-col justify-between gap-4 border-b border-line p-6
                               sm:flex-row sm:items-center">

                            <div>

                                <h2 class="font-display text-lg font-semibold text-ink">
                                    Materi Pembelajaran
                                </h2>

                                <p class="mt-1 text-sm text-ink/50">
                                    Kelola materi yang akan diberikan kepada mahasiswa.
                                </p>

                            </div>


                            {{-- Tambah Materi --}}
                            <a href="{{ route('lecturer.materi.create', $pengajaranDosen->id) }}"
                                class="inline-flex items-center justify-center gap-2 rounded-lg
                                   bg-ink px-4 py-2.5 text-sm font-semibold text-white
                                   transition hover:bg-primaryDark">

                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />

                                </svg>

                                Tambah Materi

                            </a>

                        </div>


                        {{-- =================================================
                        LIST MATERI (dari database, dengan embed player)
                    ================================================== --}}
                        <div class="divide-y divide-line">

                            @forelse ($materiList as $materi)
                                {{-- Label tipe konten yang dimiliki materi ini, misal "PDF, Video" --}}
                                @php
                                    $labelTipe = $materi->files
                                        ->map(function ($file) {
                                            return match ($file->tipe) {
                                                'pdf' => 'PDF',
                                                'audio' => 'Audio',
                                                'video_youtube' => 'Video',
                                                default => ucfirst($file->tipe),
                                            };
                                        })
                                        ->unique()
                                        ->implode(', ');
                                @endphp

                                <div x-data="{ open: false }">

                                    {{-- Header (klik untuk expand/collapse) --}}
                                    <div class="flex items-center gap-4 p-5">

                                        <button type="button" @click="open = !open"
                                            class="flex flex-1 items-center gap-4 text-left min-w-0">

                                            {{-- Icon --}}
                                            <div
                                                class="flex h-11 w-11 shrink-0 items-center justify-center
                                               rounded-xl bg-paper text-ink">

                                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5
                                                               S4.168 5.477 3 6.253v13
                                                               C4.168 18.477 5.754 18 7.5 18
                                                               s3.332.477 4.5 1.253
                                                               m0-13C13.168 5.477 14.754 5
                                                               16.5 5c1.746 0 3.332.477 4.5 1.253v13
                                                               C19.832 18.477 18.246 18 16.5 18
                                                               c-1.746 0-3.332.477-4.5 1.253" />

                                                </svg>

                                            </div>


                                            {{-- Informasi --}}
                                            <div class="min-w-0 flex-1">

                                                <h3 class="truncate text-sm font-semibold text-ink">
                                                    {{ $materi->judul }}
                                                </h3>

                                                <p class="mt-1 text-xs text-ink/50">
                                                    Materi pembelajaran
                                                    @if ($labelTipe)
                                                        • {{ $labelTipe }}
                                                    @endif
                                                </p>

                                            </div>

                                            {{-- Chevron indikator expand --}}
                                            <svg class="h-4 w-4 shrink-0 text-ink/30 transition-transform"
                                                :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>

                                        </button>


                                        {{-- Menu --}}
                                        <div class="relative shrink-0" x-data="{ menuOpen: false }">

                                            <button type="button" @click="menuOpen = !menuOpen"
                                                @click.outside="menuOpen = false"
                                                class="rounded-lg p-2 text-ink/35
                                               transition hover:bg-paper hover:text-ink">

                                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 5v.01M12 12v.01M12 19v.01" />

                                                </svg>

                                            </button>

                                            <div x-show="menuOpen" x-cloak
                                                class="absolute right-0 z-10 mt-1 w-40 rounded-lg border
                                               border-line bg-white py-1 shadow-lg">

                                                <a href="{{ route('lecturer.materi.edit', $materi->id) }}"
                                                    class="block w-full px-4 py-2 text-left text-sm
                                                   text-ink/70 hover:bg-paper">
                                                    Edit
                                                </a>

                                                <form method="POST"
                                                    action="{{ route('lecturer.materi.destroy', $materi->id) }}"
                                                    onsubmit="return confirm('Yakin ingin menghapus materi ini?')">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                        class="block w-full px-4 py-2 text-left text-sm
                                                       text-red-600 hover:bg-red-50">
                                                        Hapus
                                                    </button>
                                                </form>

                                            </div>

                                        </div>

                                    </div>


                                    {{-- Body: konten embed (PDF, audio, video) --}}
                                    <div x-show="open" x-cloak class="space-y-5 px-5 pb-5">

                                        @if ($materi->deskripsi)
                                            <div class="prose prose-sm max-w-none text-sm text-ink/60">
                                                {!! $materi->deskripsi !!}
                                            </div>
                                        @endif

                                        @foreach ($materi->files as $file)
                                            @if ($file->tipe === 'pdf')
                                                {{-- Embed PDF --}}
                                                <div>
                                                    <div class="mb-2 flex items-center justify-between">
                                                        <span class="text-xs font-medium text-ink/50">
                                                            {{ $file->nama_asli ?? 'Dokumen PDF' }}
                                                        </span>
                                                        <a href="{{ $file->url }}" target="_blank"
                                                            class="text-xs font-medium text-blue-600 hover:underline">
                                                            Buka di tab baru
                                                        </a>
                                                    </div>

                                                    <iframe src="{{ $file->url }}"
                                                        class="h-[480px] w-full rounded-xl border border-line"
                                                        loading="lazy"></iframe>
                                                </div>
                                            @elseif ($file->tipe === 'audio')
                                                {{-- Embed Audio Player --}}
                                                <div>
                                                    <p class="mb-2 text-xs font-medium text-ink/50">
                                                        {{ $file->nama_asli ?? 'Rekaman Audio' }}
                                                    </p>

                                                    <audio controls class="w-full">
                                                        <source src="{{ $file->url }}">
                                                        Browser kamu tidak mendukung pemutar audio.
                                                    </audio>
                                                </div>
                                            @elseif ($file->tipe === 'video_youtube' && $file->youtube_embed_url)
                                                {{-- Embed Video YouTube --}}
                                                <div>
                                                    <p class="mb-2 text-xs font-medium text-ink/50">
                                                        Video Pembelajaran
                                                    </p>

                                                    <div
                                                        class="aspect-video w-full overflow-hidden rounded-xl border border-line">
                                                        <iframe src="{{ $file->youtube_embed_url }}" class="h-full w-full"
                                                            loading="lazy"
                                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                            allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach

                                    </div>

                                </div>

                            @empty

                                {{-- Empty State --}}
                                <div class="p-6">

                                    <div
                                        class="rounded-xl border border-dashed border-line
                                       bg-paper p-8 text-center">

                                        <p class="text-sm text-ink/50">
                                            Belum ada materi. Klik "Tambah Materi" untuk mulai menambahkan.
                                        </p>

                                    </div>

                                </div>
                            @endforelse

                        </div>

                    </div>


                    {{-- =================================================
                    TUGAS
                ================================================== --}}
                    <div id="tugas" class="mt-8 overflow-hidden rounded-2xl border border-line bg-white shadow-sm">

                        {{-- Header --}}
                        <div
                            class="flex flex-col justify-between gap-4 border-b border-line p-6
                               sm:flex-row sm:items-center">

                            <div>

                                <h2 class="font-display text-lg font-semibold text-ink">
                                    Tugas
                                </h2>

                                <p class="mt-1 text-sm text-ink/50">
                                    Kelola tugas dan pengumpulan mahasiswa.
                                </p>

                            </div>


                            <a href="{{ route('lecturer.tugas.create', $pengajaranDosen->id) }}"
                                class="rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white
           transition hover:bg-primaryDark">
                                + Tambah Tugas
                            </a>

                        </div>





                        {{-- List Tugas --}}
                        <div class="divide-y divide-line">

                            @forelse ($tugasList as $tugas)
                                <div class="flex items-center justify-between gap-4 p-5">

                                    <a href="{{ route('lecturer.tugas.show', $tugas->id) }}" class="min-w-0 flex-1">
                                        <h3 class="truncate text-sm font-semibold text-ink hover:underline">
                                            {{ $tugas->judul }}
                                        </h3>
                                        <p class="mt-1 text-xs text-ink/50">
                                            @if ($tugas->deadline)
                                                Deadline: {{ $tugas->deadline->format('d M Y, H:i') }}
                                            @else
                                                Tanpa deadline
                                            @endif
                                            • {{ $tugas->jawaban_count ?? $tugas->jawaban->count() }} pengumpulan
                                        </p>
                                    </a>
                                    <a href="{{ route('lecturer.tugas.jawaban.index', $tugas) }}"
                                        class="rounded-lg border border-line px-4 py-2.5 text-sm font-semibold text-ink transition hover:bg-paper">
                                        Lihat & Koreksi Jawaban
                                    </a>
                                    <div class="flex shrink-0 items-center gap-2">
                                        <a href="{{ route('lecturer.tugas.show', $tugas->id) }}"
                                            class="rounded-lg border border-line px-3 py-1.5 text-xs font-semibold text-ink hover:bg-paper">
                                            Lihat
                                        </a>
                                        <a href="{{ route('lecturer.tugas.edit', $tugas->id) }}"
                                            class="rounded-lg border border-line px-3 py-1.5 text-xs font-semibold text-ink hover:bg-paper">
                                            Edit
                                        </a>
                                    </div>

                                </div>
                            @empty

                                {{-- Empty State --}}
                                <div class="p-6">

                                    <div
                                        class="rounded-xl border border-dashed border-line
                   bg-paper p-8 text-center">

                                        <p class="text-sm text-ink/50">
                                            Belum ada tugas.
                                        </p>

                                    </div>

                                </div>
                            @endforelse



                        </div>

                    </div>


                    {{-- =================================================
                    QUIZ
                ================================================== --}}
                    <div id="quiz" class="mt-8 overflow-hidden rounded-2xl border border-line bg-white shadow-sm">

                        {{-- Header --}}
                        <div
                            class="flex flex-col justify-between gap-4 border-b border-line p-6
                               sm:flex-row sm:items-center">

                            <div>

                                <h2 class="font-display text-lg font-semibold text-ink">
                                    Quiz
                                </h2>

                                <p class="mt-1 text-sm text-ink/50">
                                    Kelola quiz pilihan ganda untuk mata kuliah ini.
                                </p>

                            </div>

                            {{-- Buat Quiz --}}
                            <a href="{{ route('lecturer.quiz.index', $pengajaranDosen->id) }}"
                                class="inline-flex items-center justify-center gap-2 rounded-lg
                                   bg-ink px-4 py-2.5 text-sm font-semibold text-white
                                   transition hover:bg-primaryDark">

                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />

                                </svg>

                                Buat Quiz

                            </a>

                        </div>


                        {{-- List Quiz --}}
                        <div class="divide-y divide-line">

                            @forelse ($quizList ?? [] as $quiz)
                                <a href="{{ route('lecturer.quiz.show', $quiz) }}"
                                    class="flex items-center justify-between gap-4 p-5 transition hover:bg-paper">

                                    <div class="min-w-0 flex-1">
                                        <h3 class="truncate text-sm font-semibold text-ink">
                                            {{ $quiz->judul }}
                                        </h3>
                                        <p class="mt-1 text-xs text-ink/50">
                                            {{ $quiz->questions_count ?? $quiz->questions->count() }} soal
                                            @if ($quiz->durasi_menit)
                                                • {{ $quiz->durasi_menit }} menit
                                            @endif
                                        </p>
                                    </div>

                                    @if ($quiz->is_published)
                                        <span
                                            class="shrink-0 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                            Published
                                        </span>
                                    @else
                                        <span
                                            class="shrink-0 rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700">
                                            Draft
                                        </span>
                                    @endif

                                </a>
                            @empty

                                {{-- Empty State --}}
                                <div class="p-6">

                                    <div
                                        class="rounded-xl border border-dashed border-line
                                       bg-paper p-8 text-center">

                                        <p class="text-sm text-ink/50">
                                            Belum ada quiz. Klik "Buat Quiz" untuk mulai menambahkan.
                                        </p>

                                    </div>

                                </div>
                            @endforelse

                        </div>

                    </div>

                    {{-- =================================================
                 MAHASISWA
                ================================================== --}}
                    <div id="mahasiswa" class="mt-8 overflow-hidden rounded-2xl border border-line bg-white shadow-sm">

                        {{-- Header --}}
                        <div
                            class="flex flex-col justify-between gap-4 border-b border-line p-6
                sm:flex-row sm:items-center">
                            <div>
                                <h2 class="font-display text-lg font-semibold text-ink">
                                    Mahasiswa
                                </h2>
                                <p class="mt-1 text-sm text-ink/50">
                                    Daftar mahasiswa yang mengambil kelas ini.
                                </p>
                            </div>

                            <span class="text-sm font-medium text-ink/60">
                                {{ $pengajaran->mahasiswa->count() }} mahasiswa
                            </span>
                        </div>

                        {{-- List Mahasiswa --}}
                        <div class="divide-y divide-line">

                            @forelse ($pengajaran->mahasiswa as $mhs)
                                <div class="flex items-center gap-4 p-5">

                                    {{-- Avatar --}}
                                    <div
                                        class="flex h-11 w-11 shrink-0 items-center justify-center
                        rounded-full bg-paper text-sm font-semibold text-ink">
                                        {{ strtoupper(substr($mhs->user->name ?? 'M', 0, 1)) }}
                                    </div>

                                    {{-- Info --}}
                                    <div class="min-w-0 flex-1">
                                        <h3 class="truncate text-sm font-semibold text-ink">
                                            {{ $mhs->user->name ?? '-' }}
                                        </h3>
                                        <p class="mt-1 text-xs text-ink/50">
                                            NIM: {{ $mhs->nim ?? '-' }}
                                        </p>
                                    </div>

                                </div>
                            @empty

                                {{-- Empty State --}}
                                <div class="p-6">
                                    <div
                                        class="rounded-xl border border-dashed border-line
                        bg-paper p-8 text-center">
                                        <p class="text-sm text-ink/50">
                                            Belum ada mahasiswa yang mengambil kelas ini.
                                        </p>
                                    </div>
                                </div>
                            @endforelse

                        </div>
                    </div>
                    {{-- =================================================
                    ABSENSI
                ================================================== --}}
                    {{-- =================================================
    ABSENSI
================================================== --}}
                    <div id="absensi" class="mt-8 overflow-hidden rounded-2xl border border-line bg-white shadow-sm">

                        {{-- Header --}}
                        <div
                            class="flex flex-col justify-between gap-4 border-b border-line p-6
                sm:flex-row sm:items-center">
                            <div>
                                <h2 class="font-display text-lg font-semibold text-ink">
                                    Absensi
                                </h2>
                                <p class="mt-1 text-sm text-ink/50">
                                    Kelola kehadiran mahasiswa per pertemuan.
                                </p>
                            </div>

                            <button type="button" @click="$dispatch('open-modal-absensi')"
                                class="rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white
                   transition hover:bg-primaryDark">
                                + Buka Absensi
                            </button>
                        </div>

                        {{-- List Sesi Absensi --}}
                        <div class="divide-y divide-line">

                            @forelse ($sesiAbsensiList as $s)
                                <a href="{{ route('lecturer.absensi.show', $s->id) }}"
                                    class="flex items-center justify-between gap-4 p-5 transition hover:bg-paper">

                                    <div>
                                        <h3 class="text-sm font-semibold text-ink">
                                            Pertemuan {{ $s->pertemuan_ke }}
                                            @if ($s->judul)
                                                — {{ $s->judul }}
                                            @endif
                                        </h3>
                                        <p class="mt-1 text-xs text-ink/50">
                                            {{ $s->dibuka_pada->format('d M Y, H:i') }}
                                            • {{ $s->absensi()->count() }} mahasiswa hadir
                                        </p>
                                    </div>

                                    @if ($s->isExpired())
                                        <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-600">
                                            Ditutup
                                        </span>
                                    @else
                                        <span
                                            class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                            Aktif
                                        </span>
                                    @endif

                                </a>
                            @empty

                                <div class="p-6">
                                    <div class="rounded-xl border border-dashed border-line bg-paper p-8 text-center">
                                        <p class="text-sm text-ink/50">
                                            Belum ada sesi absensi untuk mata kuliah ini.
                                        </p>
                                    </div>
                                </div>
                            @endforelse

                        </div>
                    </div>
                </div>


                {{-- =================================================
                RIGHT SIDEBAR
            ================================================== --}}
                <div class="space-y-6">


                    {{-- =================================================
                    AKSI CEPAT
                ================================================== --}}
                    <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">

                        <h2 class="font-display text-base font-semibold text-ink">
                            Aksi Cepat
                        </h2>


                        <div class="mt-4 space-y-3">


                            {{-- Tambah Materi --}}
                            <a href="{{ route('lecturer.materi.create', $pengajaranDosen->id) }}"
                                class="flex w-full items-center gap-3 rounded-xl border border-line
                                   p-3 text-left transition hover:bg-paper">

                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-lg
                                       bg-paper text-ink">

                                    +

                                </div>

                                <div>

                                    <p class="text-sm font-semibold text-ink">
                                        Tambah Materi
                                    </p>

                                    <p class="text-xs text-ink/50">
                                        Upload materi pembelajaran
                                    </p>

                                </div>

                            </a>


                            {{-- Tambah Tugas --}}
                            <a href="{{ route('lecturer.tugas.create', $pengajaranDosen->id) }}"
                                class="flex w-full items-center gap-3 rounded-xl border border-line
                                   p-3 text-left transition hover:bg-paper">

                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-lg
                                       bg-paper text-ink">

                                    +

                                </div>

                                <div>

                                    <p class="text-sm font-semibold text-ink">
                                        Buat Tugas
                                    </p>

                                    <p class="text-xs text-ink/50">
                                        Tambahkan tugas
                                    </p>

                                </div>

                            </a>


                            {{-- Buat Quiz --}}
                            <a href="{{ route('lecturer.quiz.index', $pengajaranDosen->id) }}"
                                class="flex w-full items-center gap-3 rounded-xl border border-line
                                   p-3 text-left transition hover:bg-paper">

                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-lg
                                       bg-paper text-ink">

                                    +

                                </div>

                                <div>

                                    <p class="text-sm font-semibold text-ink">
                                        Buat Quiz
                                    </p>

                                    <p class="text-xs text-ink/50">
                                        Tambahkan quiz
                                    </p>

                                </div>

                            </a>


                        </div>

                    </div>


                    {{-- =================================================
                    RINGKASAN
                ================================================== --}}
                    <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">

                        <h2 class="font-display text-base font-semibold text-ink">
                            Ringkasan
                        </h2>


                        <div class="mt-5 space-y-4">


                            <div class="flex items-center justify-between">

                                <span class="text-sm text-ink/50">
                                    Materi
                                </span>

                                <span class="font-semibold text-ink">
                                    {{ $materiList->count() }}
                                </span>

                            </div>


                            <div class="flex items-center justify-between">
                                <span class="text-sm text-ink/50">Tugas</span>
                                <span class="font-semibold text-ink">
                                    {{ $tugasList->count() }}
                                </span>
                            </div>


                            <div class="flex items-center justify-between">

                                <span class="text-sm text-ink/50">
                                    Quiz
                                </span>

                                <span class="font-semibold text-ink">
                                    {{ isset($quizList) ? $quizList->count() : 0 }}
                                </span>

                            </div>


                            <div class="flex items-center justify-between">
                                <span class="text-sm text-ink/50">Mahasiswa</span>
                                <span class="font-semibold text-ink">
                                    {{ $pengajaran->mahasiswa->count() }}
                                </span>
                            </div>


                        </div>

                    </div>


                </div>

            </div>

        </div>

    </div>
    {{-- =================================================
    MODAL: BUKA ABSENSI
================================================== --}}
    <div x-data="{ open: false }" x-show="open" x-cloak @open-modal-absensi.window="open = true"
        @keydown.escape.window="open = false" class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="display: none;">

        {{-- Backdrop --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false"
            class="absolute inset-0 bg-ink/40"></div>

        {{-- Modal Box --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">

            {{-- Header --}}
            <div class="flex items-center justify-between">
                <h2 class="font-display text-lg font-semibold text-ink">
                    Buka Sesi Absensi
                </h2>
                <button type="button" @click="open = false"
                    class="rounded-lg p-1.5 text-ink/40 transition hover:bg-paper hover:text-ink">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <p class="mt-1 text-sm text-ink/50">
                QR code akan digenerate otomatis setelah sesi dibuka.
            </p>

            {{-- Form --}}
            <form method="POST" action="{{ route('lecturer.absensi.store', $pengajaran->id) }}" class="mt-5 space-y-4">
                @csrf

                {{-- Pertemuan Ke --}}
                <div>
                    <label class="block text-sm font-medium text-ink">
                        Pertemuan Ke
                    </label>
                    <input type="number" name="pertemuan_ke" min="1" required
                        value="{{ old('pertemuan_ke', $sesiAbsensiList->count() + 1) }}"
                        class="mt-1.5 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm
                           text-ink outline-none transition focus:border-ink">
                    @error('pertemuan_ke')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Judul --}}
                <div>
                    <label class="block text-sm font-medium text-ink">
                        Judul / Topik <span class="text-ink/40">(opsional)</span>
                    </label>
                    <input type="text" name="judul" value="{{ old('judul') }}"
                        placeholder="Misal: Normalisasi Database"
                        class="mt-1.5 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm
                           text-ink outline-none transition focus:border-ink">
                    @error('judul')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Durasi --}}
                <div>
                    <label class="block text-sm font-medium text-ink">
                        Durasi QR Aktif (menit)
                    </label>
                    <input type="number" name="durasi_menit" min="1" max="180" required
                        value="{{ old('durasi_menit', 15) }}"
                        class="mt-1.5 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm
                           text-ink outline-none transition focus:border-ink">
                    @error('durasi_menit')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Aksi --}}
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="open = false"
                        class="rounded-lg border border-line px-4 py-2.5 text-sm font-semibold text-ink
                           transition hover:bg-paper">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white
                           transition hover:bg-primaryDark">
                        Buka Absensi
                    </button>
                </div>

            </form>

        </div>

    </div>
@endsection
