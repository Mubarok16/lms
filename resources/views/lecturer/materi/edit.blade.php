@extends('lecturer.app-lecturer-create-materi')

@section('ketjudul')
Edit Materi
@endsection

@section('judul')
{{ $materi->pengajaran->kelas->matakuliah->nama_mk }}
@endsection

@section('content')

<div class="bg-paper">

    <div class="mx-auto max-w-3xl px-6 py-8">

        {{-- =================================================
            BACK LINK
        ================================================== --}}
        <a href="{{ route('pengajaran.show', $materi->pengajaran->kelas_id) }}"
            class="inline-flex items-center gap-1.5 text-sm font-medium text-ink/50
                   transition hover:text-ink">

            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 19l-7-7 7-7" />
            </svg>

            Kembali ke {{ $materi->pengajaran->kelas->matakuliah->nama_mk }}

        </a>


        {{-- =================================================
            FORM CARD
        ================================================== --}}
        <div class="mt-6 overflow-hidden rounded-2xl border border-line bg-white shadow-sm">

            {{-- Header --}}
            <div class="border-b border-line p-6">

                <h2 class="font-display text-lg font-semibold text-ink">
                    Edit Materi
                </h2>

                <p class="mt-1 text-sm text-ink/50">
                    Ubah judul, deskripsi, atau konten materi. Kosongkan bagian yang tidak ingin diganti.
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
                action="{{ route('lecturer.materi.update', $materi->id) }}"
                enctype="multipart/form-data"
                class="divide-y divide-line">

                @csrf
                @method('PUT')

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
                            value="{{ old('judul', $materi->judul) }}"
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

                        <div class="overflow-hidden rounded-lg border border-line focus-within:border-ink focus-within:ring-1 focus-within:ring-ink">
                            <div id="deskripsi-editor" style="height: 160px;">{!! old('deskripsi', $materi->deskripsi) !!}</div>
                        </div>

                        <textarea name="deskripsi" id="deskripsi" class="hidden">{{ old('deskripsi', $materi->deskripsi) }}</textarea>

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

                            <label class="mb-1.5 block text-sm font-medium text-ink">
                                File PDF
                            </label>

                            @if ($pdfFile)
                            <div class="mb-2 flex items-center justify-between rounded-lg border
                                        border-line bg-paper px-3 py-2 text-sm">

                                <a href="{{ $pdfFile->url }}" target="_blank"
                                    class="truncate text-blue-600 hover:underline">
                                    {{ $pdfFile->nama_asli ?? 'Lihat file PDF saat ini' }}
                                </a>

                                <label class="ml-3 flex shrink-0 items-center gap-1.5 text-xs text-red-600">
                                    <input type="checkbox" name="remove_pdf" value="1"
                                        class="rounded border-line text-red-600 focus:ring-red-500">
                                    Hapus
                                </label>

                            </div>
                            <p class="mb-2 text-xs text-ink/40">
                                Upload file baru di bawah untuk mengganti file di atas.
                            </p>
                            @endif

                            <input type="file" name="pdf_file" accept="application/pdf"
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

                            <label class="mb-1.5 block text-sm font-medium text-ink">
                                File Audio
                            </label>

                            @if ($audioFile)
                            <div class="mb-2 rounded-lg border border-line bg-paper p-3">

                                <div class="mb-2 flex items-center justify-between">
                                    <span class="truncate text-sm text-ink/70">
                                        {{ $audioFile->nama_asli ?? 'Audio saat ini' }}
                                    </span>

                                    <label class="ml-3 flex shrink-0 items-center gap-1.5 text-xs text-red-600">
                                        <input type="checkbox" name="remove_audio" value="1"
                                            class="rounded border-line text-red-600 focus:ring-red-500">
                                        Hapus
                                    </label>
                                </div>

                                <audio controls class="w-full">
                                    <source src="{{ $audioFile->url }}">
                                </audio>

                            </div>
                            <p class="mb-2 text-xs text-ink/40">
                                Upload file baru di bawah untuk mengganti audio di atas.
                            </p>
                            @endif

                            <input type="file" name="audio_file" accept="audio/mp3,audio/wav,audio/x-m4a,.mp3,.wav,.m4a"
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
                                Tautan Video YouTube
                            </label>

                            @if ($youtubeFile)
                            <label class="mb-2 flex items-center gap-1.5 text-xs text-red-600">
                                <input type="checkbox" name="remove_youtube" value="1"
                                    class="rounded border-line text-red-600 focus:ring-red-500">
                                Hapus video ini
                            </label>
                            @endif

                            <input type="url" name="youtube_url" id="youtube_url"
                                value="{{ old('youtube_url', $youtubeFile->youtube_url ?? '') }}"
                                placeholder="https://www.youtube.com/watch?v=..."
                                class="w-full rounded-lg border border-line px-3.5 py-2.5 text-sm
                                       text-ink placeholder:text-ink/35
                                       focus:border-ink focus:outline-none focus:ring-1 focus:ring-ink">

                            <p class="mt-1.5 text-xs text-ink/40">
                                Ganti isi kolom ini untuk mengganti video, atau centang "Hapus" untuk menghapusnya.
                            </p>

                            @error('youtube_url')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    ACTIONS
                ================================================== --}}
                <div class="flex items-center justify-end gap-3 bg-paper/60 p-6">

                    <a href="{{ route('pengajaran.show', $materi->pengajaran->kelas_id) }}"
                        class="rounded-lg px-4 py-2.5 text-sm font-medium text-ink/60
                               transition hover:bg-paper hover:text-ink">
                        Batal
                    </a>

                    <button type="submit"
                        class="rounded-lg bg-ink px-5 py-2.5 text-sm font-semibold text-white
                               transition hover:bg-primaryDark">
                        Simpan Perubahan
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

        quill.on('text-change', function() {
            hiddenTextarea.value = quill.root.innerHTML;
        });

        document.querySelector('form').addEventListener('submit', function() {
            hiddenTextarea.value = quill.root.innerHTML;
        });

    });
</script>
@endpush