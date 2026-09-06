@extends('lecturer.app-lecturer-create-materi')

@section('ketjudul')
    Daftar Quiz
@endsection

@section('judul')
    {{ $pengajaranDosen->pengajaran->matakuliah->nama_mk ?? 'Quiz' }}
@endsection

@section('content')
    <div class="bg-paper">
        <div class="mx-auto max-w-5xl px-6 py-8">

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

            {{-- Header --}}
            <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <h1 class="font-display text-xl font-semibold text-ink">
                        Daftar Quiz
                    </h1>
                    <p class="mt-1 text-sm text-ink/50">
                        Kelola quiz pilihan ganda untuk mata kuliah ini.
                    </p>
                </div>

                <a href="{{ route('lecturer.quiz.create', $pengajaranDosen->id) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg
                       bg-ink px-4 py-2.5 text-sm font-semibold text-white
                       transition hover:bg-primaryDark">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Quiz
                </a>
            </div>

            {{-- List Quiz --}}
            <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
                <div class="divide-y divide-line">

                    @forelse ($quizzes as $quiz)
                        <a href="{{ route('lecturer.quiz.show', $quiz) }}"
                            class="flex items-center justify-between gap-4 p-5 transition hover:bg-paper">

                            <div class="min-w-0 flex-1">
                                <h3 class="truncate text-sm font-semibold text-ink">
                                    {{ $quiz->judul }}
                                </h3>
                                <p class="mt-1 text-xs text-ink/50">
                                    {{ $quiz->questions_count }} soal
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

                        <div class="p-6">
                            <div class="rounded-xl border border-dashed border-line bg-paper p-8 text-center">
                                <p class="text-sm text-ink/50">
                                    Belum ada quiz. Klik "Tambah Quiz" untuk mulai membuat.
                                </p>
                            </div>
                        </div>
                    @endforelse

                </div>
            </div>

        </div>
    </div>
@endsection
