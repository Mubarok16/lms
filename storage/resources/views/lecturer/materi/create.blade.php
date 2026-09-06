@extends('lecturer.app-lecturer-create-materi')

@section('ketjudul')
Tambah Materi
@endsection

@section('judul')
{{ $pengajaran->kelas->matakuliah->nama_mk }}
@endsection

@section('content')

<div class="bg-paper">

    <div class="mx-auto max-w-3xl px-6 py-8">

        {{-- =================================================
            BACK LINK
        ================================================== --}}
        <a href="{{ route('pengajaran.show', $pengajaran->kelas_id) }}"
            class="inline-flex items-center gap-1.5 text-sm font-medium text-ink/50
                   transition hover:text-ink">

            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 19l-7-7 7-7" />
            </svg>

            Kembali ke {{ $pengajaran->kelas->matakuliah->nama_mk }}

        </a>


        {{-- =================================================
            FORM CARD
        ================================================== --}}
        <div class="mt-6 overflow-hidden rounded-2xl border border-line bg-white shadow-sm">

            {{-- Header --}}
            <div class="border-b border-line p-6">

                <h2 class="font-display text-lg font-semibold text-ink">
                    Tambah Materi
                </h2>

                <p class="mt-1 text-sm text-ink/50">
                    Lengkapi judul materi, lalu sertakan minimal satu jenis konten:
                    PDF, audio, atau tautan video YouTube.
                </p>

            </div>


            {{-- Error umum --}}
            @if ($errors->any())
            <div class="border-b border-line bg-red-50 p-4">
                <ul class="space-y-1 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif


            <form method="POST"
                action="{{ route('lecturer.materi.store', $pengajaran->id) }}"
                enctype="multipart/form-data"
                class="divide-y divide-line">

                @csrf

                {{-- =================================================
                    INFO DASAR
                ================================================== --}}
                <div class="space-y-5 p-6">

                    {{-- Judul --}}
                    <div>
                        <label for="judul" class="mb-1.5 block text-sm font-medium text-ink">
                            Judul Materi
                        </label>

                        <input type="text" name="judul" id="judul"
                            value="{{ old('judul') }}"
                            placeholder="Contoh: Pertemuan 3 — Selector CSS"
                            class="w-full rounded-lg border border-line px-3.5 py-2.5 text-sm
                                   text-ink placeholder:text-ink/35
                                   focus:border-ink focus:outline-none focus:ring-1 focus:ring-ink">

                        @error('judul')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-ink">
                            Deskripsi <span class="font-normal text-ink/40">(opsional)</span>
                        </label>

                        {{-- Toolbar & area editor Quill --}}
                        <div class="overflow-hidden rounded-lg border border-line focus-within:border-ink focus-within:ring-1 focus-within:ring-ink">
                            <div id="deskripsi-editor" style="height: 160px;">{!! old('deskripsi') !!}</div>
                        </div>

                        {{-- Textarea asli disembunyikan, dipakai untuk submit ke server --}}
                        <textarea name="deskripsi" id="deskripsi" class="hidden">{{ old('deskripsi') }}</textarea>

                        @error('deskripsi')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>


                {{-- =================================================
                    KONTEN: PDF
                ================================================== --}}
                <div class="p-6">

                    <div class="flex items-start gap-3">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center
                                    rounded-lg bg-paper text-ink">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0
                                       01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>

                        <div class="flex-1">

                            <label for="pdf_file" class="mb-1.5 block text-sm font-medium text-ink">
                                File PDF <span class="font-normal text-ink/40">(opsional)</span>
                            </label>

                            <input type="file" name="pdf_file" id="pdf_file" accept="application/pdf"
                                class="block w-full text-sm text-ink/70
                                       file:mr-3 file:rounded-lg file:border-0 file:bg-paper
                                       file:px-3.5 file:py-2 file:text-sm file:font-medium
                                       file:text-ink hover:file:bg-line/60">

                            <p class="mt-1.5 text-xs text-ink/40">
                                Maksimal 20MB.
                            </p>

                            @error('pdf_file')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    KONTEN: AUDIO
                ================================================== --}}
                <div class="p-6">

                    <div class="flex items-start gap-3">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center
                                    rounded-lg bg-paper text-ink">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11a7 7 0 01-14 0m7 7v4m-4 0h8M12 1a3 3 0 00-3 3v7a3 3
                                       0 006 0V4a3 3 0 00-3-3z" />
                            </svg>
                        </div>

                        <div class="flex-1">

                            <label for="audio_file" class="mb-1.5 block text-sm font-medium text-ink">
                                File Audio <span class="font-normal text-ink/40">(opsional)</span>
                            </label>

                            <input type="file" name="audio_file" id="audio_file" accept="audio/mp3,audio/wav,audio/x-m4a,.mp3,.wav,.m4a"
                                class="block w-full text-sm text-ink/70
                                       file:mr-3 file:rounded-lg file:border-0 file:bg-paper
                                       file:px-3.5 file:py-2 file:text-sm file:font-medium
                                       file:text-ink hover:file:bg-line/60">

                            <p class="mt-1.5 text-xs text-ink/40">
                                Format MP3, WAV, atau M4A. Maksimal 20MB.
                            </p>

                            @error('audio_file')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    KONTEN: VIDEO YOUTUBE
                ================================================== --}}
                <div class="p-6">

                    <div class="flex items-start gap-3">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center
                                    rounded-lg bg-paper text-ink">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15
                                       14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0
                                       002 2z" />
                            </svg>
                        </div>

                        <div class="flex-1">

                            <label for="youtube_url" class="mb-1.5 block text-sm font-medium text-ink">
                                Tautan Video YouTube <span class="font-normal text-ink/40">(opsional)</span>
                            </label>

                            <input type="url" name="youtube_url" id="youtube_url"
                                value="{{ old('youtube_url') }}"
                                placeholder="https://www.youtube.com/watch?v=..."
                                oninput="previewYoutube(this.value)"
                                class="w-full rounded-lg border border-line px-3.5 py-2.5 text-sm
                                       text-ink placeholder:text-ink/35
                                       focus:border-ink focus:outline-none focus:ring-1 focus:ring-ink">

                            {{-- Preview embed, muncul otomatis saat link valid --}}
                            <div id="youtubePreviewWrapper" class="mt-3 hidden">
                                <div class="aspect-video w-full overflow-hidden rounded-xl border border-line">
                                    <iframe id="youtubePreviewFrame"
                                        class="h-full w-full"
                                        src=""
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                                </div>
                            </div>

                            @error('youtube_url')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror

                        </div>

                    </div>

                </div>

                <script>
                    function previewYoutube(url) {
                        const match = url.match(
                            /(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/
                        );

                        const wrapper = document.getElementById('youtubePreviewWrapper');
                        const frame = document.getElementById('youtubePreviewFrame');

                        if (match) {
                            frame.src = `https://www.youtube.com/embed/${match[1]}`;
                            wrapper.classList.remove('hidden');
                        } else {
                            frame.src = '';
                            wrapper.classList.add('hidden');
                        }
                    }
                </script>


                {{-- =================================================
                    ACTIONS
                ================================================== --}}
                <div class="flex items-center justify-end gap-3 bg-paper/60 p-6">

                    <a href="{{ route('pengajaran.show', $pengajaran->kelas_id) }}"
                        class="rounded-lg px-4 py-2.5 text-sm font-medium text-ink/60
                               transition hover:bg-paper hover:text-ink">
                        Batal
                    </a>

                    <button type="submit"
                        class="rounded-lg bg-ink px-5 py-2.5 text-sm font-semibold text-white
                               transition hover:bg-primaryDark">
                        Simpan Materi
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection

{{-- CDN Quill.js — rich text editor untuk deskripsi materi --}}
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const quill = new Quill('#deskripsi-editor', {
            theme: 'snow',
            placeholder: 'Ringkasan singkat isi materi ini...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{
                        size: ['small', false, 'large', 'huge']
                    }],
                    [{
                        color: []
                    }, {
                        background: []
                    }],
                    [{
                        list: 'ordered'
                    }, {
                        list: 'bullet'
                    }],
                    ['clean'],
                ],
            },
        });

        const hiddenTextarea = document.getElementById('deskripsi');

        // sinkronkan tiap kali konten editor berubah
        quill.on('text-change', function() {
            hiddenTextarea.value = quill.root.innerHTML;
        });

        // jaga-jaga: sinkronkan sekali lagi tepat sebelum form dikirim
        document.querySelector('form').addEventListener('submit', function() {
            hiddenTextarea.value = quill.root.innerHTML;
        });

    });
</script>
@endpush