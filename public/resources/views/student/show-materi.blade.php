@extends('student.app-student')

@section('ketjudul')
Mata Kuliah
@endsection

@section('judul')
{{ $pengajaran->matakuliah->nama_mk }}
@endsection

@section('content')

<div class="bg-paper">

    <div class="mx-auto max-w-7xl px-6 py-8">

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

                        <a href="#materi"
                            class="rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white">
                            Materi
                        </a>

                        <a href="#tugas"
                            class="rounded-lg px-4 py-2.5 text-sm font-medium text-ink/60
                                   transition hover:bg-paper hover:text-ink">
                            Tugas
                        </a>

                        <a href="#quiz"
                            class="rounded-lg px-4 py-2.5 text-sm font-medium text-ink/60
                                   transition hover:bg-paper hover:text-ink">
                            Quiz
                        </a>

                    </div>

                </div>


                {{-- =================================================
                    MATERI (read-only, tanpa tombol kelola)
                ================================================== --}}
                <div id="materi"
                    class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">

                    {{-- Header --}}
                    <div class="border-b border-line p-6">

                        <h2 class="font-display text-lg font-semibold text-ink">
                            Materi Pembelajaran
                        </h2>

                        <p class="mt-1 text-sm text-ink/50">
                            {{ $materiList->count() }} materi tersedia untuk mata kuliah ini.
                        </p>

                    </div>


                    {{-- List Materi --}}
                    <div class="divide-y divide-line">

                        @forelse ($materiList as $materi)

                        @php
                        $labelTipe = $materi->files->map(function ($file) {
                        return match ($file->tipe) {
                        'pdf' => 'PDF',
                        'audio' => 'Audio',
                        'video_youtube' => 'Video',
                        default => ucfirst($file->tipe),
                        };
                        })->unique()->implode(', ');
                        @endphp

                        <div x-data="{ open: false }">

                            {{-- Header materi, klik untuk expand --}}
                            <button type="button" @click="open = !open"
                                class="flex w-full items-center gap-4 p-5 text-left">

                                {{-- Icon --}}
                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center
                                           rounded-xl bg-paper text-ink">

                                    <svg class="h-5 w-5"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5
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

                                {{-- Badge "Sudah dibuka" (opsional, kalau ada tracking progress) --}}
                                {{--
                                <span class="shrink-0 rounded-full bg-green-50 px-2.5 py-1 text-[11px]
                                             font-medium text-green-600">
                                    Selesai
                                </span>
                                --}}

                                {{-- Chevron --}}
                                <svg class="h-4 w-4 shrink-0 text-ink/30 transition-transform"
                                    :class="open ? 'rotate-180' : ''"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>

                            </button>


                            {{-- Body: embed konten --}}
                            <div x-show="open" x-cloak class="space-y-5 px-5 pb-5">

                                @if ($materi->deskripsi)
                                <p class="text-sm text-ink/60">
                                    {{ $materi->deskripsi }}
                                </p>
                                @endif

                                @foreach ($materi->files as $file)

                                @if ($file->tipe === 'pdf')

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

                                <div>
                                    <p class="mb-2 text-xs font-medium text-ink/50">
                                        Video Pembelajaran
                                    </p>

                                    <div class="aspect-video w-full overflow-hidden rounded-xl border border-line">
                                        <iframe
                                            src="{{ $file->youtube_embed_url }}"
                                            class="h-full w-full"
                                            loading="lazy"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen></iframe>
                                    </div>
                                </div>

                                @endif

                                @endforeach

                                {{-- Tombol tandai selesai (opsional, butuh tabel progress terpisah) --}}
                                {{--
                                <button type="button"
                                    class="rounded-lg bg-ink px-4 py-2 text-xs font-semibold text-white
                                           hover:bg-primaryDark">
                                    Tandai Sudah Dipelajari
                                </button>
                                --}}

                            </div>

                        </div>

                        @empty

                        <div class="p-6">
                            <div class="rounded-xl border border-dashed border-line bg-paper p-8 text-center">
                                <p class="text-sm text-ink/50">
                                    Belum ada materi yang diunggah dosen.
                                </p>
                            </div>
                        </div>

                        @endforelse

                    </div>

                </div>


                {{-- =================================================
                    TUGAS
                ================================================== --}}
                <div id="tugas"
                    class="mt-8 overflow-hidden rounded-2xl border border-line bg-white shadow-sm">

                    <div class="border-b border-line p-6">
                        <h2 class="font-display text-lg font-semibold text-ink">
                            Tugas
                        </h2>
                        <p class="mt-1 text-sm text-ink/50">
                            Daftar tugas yang perlu kamu kerjakan.
                        </p>
                    </div>

                    <div class="p-6">
                        <div class="rounded-xl border border-dashed border-line bg-paper p-8 text-center">
                            <p class="text-sm text-ink/50">
                                Belum ada tugas.
                            </p>
                        </div>
                    </div>

                </div>


            </div>


            {{-- =================================================
                RIGHT SIDEBAR
            ================================================== --}}
            <div class="space-y-6">


                {{-- =================================================
                    INFO DOSEN PENGAMPU (pengganti "Aksi Cepat" milik dosen)
                ================================================== --}}
                <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">

                    <h2 class="font-display text-base font-semibold text-ink">
                        Dosen Pengampu
                    </h2>

                    <div class="mt-4 space-y-3">

                        @foreach ($pengajaran->pengajaranDosen as $pj)
                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center
                                        rounded-full bg-paper text-sm font-semibold text-ink">
                                {{ strtoupper(substr($pj->lecturer->user->name, 0, 1)) }}
                            </div>

                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-ink">
                                    {{ $pj->lecturer->user->name }}
                                </p>
                                <p class="truncate text-xs text-ink/50">
                                    {{ $pj->lecturer->user->email }}
                                </p>
                            </div>

                        </div>
                        @endforeach

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
                            <span class="text-sm text-ink/50">Materi</span>
                            <span class="font-semibold text-ink">{{ $materiList->count() }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-ink/50">Tugas</span>
                            <span class="font-semibold text-ink">0</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-ink/50">Quiz</span>
                            <span class="font-semibold text-ink">0</span>
                        </div>

                    </div>

                    {{-- Progress belajar (opsional, butuh tabel progress) --}}
                    <div class="mt-5 border-t border-line pt-4">
                        <div class="mb-1.5 flex items-center justify-between text-xs text-ink/40">
                            <span>PROGRES BELAJAR</span>
                            <span>0%</span>
                        </div>
                        <div class="h-1.5 overflow-hidden rounded-full bg-paper">
                            <div class="h-full rounded-full bg-amber" style="width:0%"></div>
                        </div>
                    </div>

                </div>


            </div>

        </div>

    </div>

</div>

@endsection