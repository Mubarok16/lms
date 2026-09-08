@extends('lecturer.app-lecturer-create-materi')

@section('ketjudul')
    Detail Quiz
@endsection

@section('judul')
    {{ $quiz->judul }}
@endsection

@section('content')
    <div class="bg-paper">
        <div class="mx-auto max-w-4xl px-6 py-8">

            <div class="mb-6">
                <a href="{{ route('lecturer.quiz.index', $quiz->pengajaran_dosen_id) }}"
                    class="text-sm font-medium text-ink/50 hover:text-ink">
                    &larr; Kembali ke daftar quiz
                </a>
            </div>

            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="font-semibold">Import gagal:</p>
                    <ul class="mt-1 list-inside list-disc space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Header Quiz --}}
            <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="font-display text-lg font-semibold text-ink">
                                {{ $quiz->judul }}
                            </h1>
                            @if ($quiz->is_published)
                                <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                    Published
                                </span>
                            @else
                                <span class="rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700">
                                    Draft
                                </span>
                            @endif
                        </div>

                        @if ($quiz->deskripsi)
                            <p class="mt-2 text-sm text-ink/60">{{ $quiz->deskripsi }}</p>
                        @endif

                        <p class="mt-2 text-xs text-ink/50">
                            {{ $quiz->questions->count() }} soal
                            @if ($quiz->durasi_menit)
                                • Durasi {{ $quiz->durasi_menit }} menit
                            @endif
                        </p>
                    </div>

                    @if (!$quiz->is_published)
                        <form method="POST" action="{{ route('lecturer.quiz.publish', $quiz) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white
                               transition hover:bg-primaryDark">
                                Publish Quiz
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Upload Soal --}}
            <div class="mt-6 rounded-2xl border border-line bg-white p-6 shadow-sm">
                <h2 class="font-display text-base font-semibold text-ink">
                    Isi Soal via Template Excel
                </h2>
                <p class="mt-1 text-sm text-ink/50">
                    1. Download template &rarr; 2. Isi pertanyaan, pilihan, dan kunci jawaban &rarr; 3. Upload kembali di
                    sini.
                    Upload ulang akan menggantikan seluruh soal yang sudah ada.
                </p>

                <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center">

                    <a href="{{ route('lecturer.quiz.template', $quiz) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-line
                           px-4 py-2.5 text-sm font-semibold text-ink transition hover:bg-paper">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                        </svg>
                        Download Template
                    </a>

                    <form method="POST" action="{{ route('lecturer.quiz.import', $quiz) }}" enctype="multipart/form-data"
                        class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center">
                        @csrf

                        <input type="file" name="file" accept=".xlsx,.xls" required
                            class="block w-full text-sm text-ink/70
                               file:mr-3 file:rounded-lg file:border-0 file:bg-paper
                               file:px-4 file:py-2 file:text-sm file:font-semibold file:text-ink
                               hover:file:bg-line/30">

                        <button type="submit"
                            class="shrink-0 rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white
                               transition hover:bg-primaryDark">
                            Upload & Import
                        </button>
                    </form>

                </div>
            </div>

            {{-- Preview Soal --}}
            <div class="mt-6 overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
                <div class="border-b border-line p-6">
                    <h2 class="font-display text-base font-semibold text-ink">
                        Soal-soal Pilihan Ganda
                    </h2>
                    <p class="mt-1 text-sm text-ink/50">
                        Anda dapat menambahkan gambar untuk tiap soal
                    </p>
                </div>

                <div class="divide-y divide-line">

                    @forelse ($quiz->questions as $q)
                        <div class="p-6">
                            <p class="text-sm font-semibold text-ink">
                                {{ $q->nomor }}. {{ $q->pertanyaan }}
                            </p>

                            {{-- Gambar soal --}}
                            <div class="mt-3">
                                @if ($q->gambar)
                                    <img src="{{ asset('storage/' . $q->gambar) }}" alt="Gambar soal {{ $q->nomor }}"
                                        class="mb-2 max-w-sm rounded-lg border border-line object-cover">
                                @endif

                                <form method="POST" action="{{ route('lecturer.quiz.question.gambar', $q) }}"
                                    enctype="multipart/form-data" class="flex flex-wrap items-center gap-2">
                                    @csrf
                                    <input type="file" name="gambar" accept="image/*" required
                                        class="block text-xs text-ink/70
                       file:mr-2 file:rounded-lg file:border-0 file:bg-paper
                       file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-ink
                       hover:file:bg-line/30">
                                    <button type="submit"
                                        class="rounded-lg border border-line px-3 py-1.5 text-xs font-semibold text-ink
                       transition hover:bg-paper">
                                        {{ $q->gambar ? 'Ganti Gambar' : 'Upload Gambar' }}
                                    </button>
                                </form>
                            </div>

                            {{-- Pilihan jawaban --}}
                            <div class="mt-3 space-y-1.5">
                                @foreach ($q->pilihanTersedia() as $huruf => $teks)
                                    <div
                                        class="flex items-center gap-2 text-sm
                {{ $huruf === $q->kunci_jawaban ? 'font-semibold text-green-700' : 'text-ink/70' }}">
                                        <span
                                            class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full
                     border text-xs
                     {{ $huruf === $q->kunci_jawaban ? 'border-green-600 bg-green-50' : 'border-line' }}">
                                            {{ $huruf }}
                                        </span>
                                        {{ $teks }}
                                        @if ($huruf === $q->kunci_jawaban)
                                            <span class="text-xs text-green-700">(Kunci Jawaban)</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="p-6">
                            <div class="rounded-xl border border-dashed border-line bg-paper p-8 text-center">
                                <p class="text-sm text-ink/50">
                                    Belum ada soal. Download template dan upload untuk menambahkan soal.
                                </p>
                            </div>
                        </div>
                    @endforelse

                </div>
            </div>

        </div>
    </div>
@endsection
