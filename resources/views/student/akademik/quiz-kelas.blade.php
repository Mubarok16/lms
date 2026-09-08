@extends('student.app-student')

@section('ketjudul', 'Quiz')
@section('judul', 'Daftar Quiz')

@section('content')

    <div class="mb-6">
        <a
            href="{{ route('student.akademik.quiz.index') }}"
            class="text-sm text-teal hover:underline"
        >
            ← Kembali ke mata kuliah
        </a>

        <h2 class="mt-3 font-display text-xl font-semibold">
            {{ $kelas->matakuliah->nama_mk }}
        </h2>

        <p class="mt-1 text-sm text-ink/50">
            {{ $kelas->kode_mk }}
            · Kelas {{ $kelas->kode_kelas }}
        </p>
    </div>


    <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">

        <div class="divide-y divide-line">

            @forelse ($quizList as $quiz)

                @php
                    $jawaban = $quiz->jawaban->first();
                @endphp

                <a
                    href="{{ route('student.quiz.show', $quiz) }}"
                    class="flex flex-col gap-3 p-5 transition hover:bg-paper sm:flex-row sm:items-center sm:justify-between"
                >

                    {{-- Informasi Quiz --}}
                    <div>

                        <p class="font-semibold text-ink">
                            {{ $quiz->judul }}
                        </p>

                        <p class="mt-1 text-xs text-ink/50">

                            {{ $quiz->questions_count }} soal

                            @if ($quiz->durasi_menit)
                                · {{ $quiz->durasi_menit }} menit
                            @endif

                        </p>

                    </div>


                    {{-- Status Quiz --}}
                    <div class="flex items-center gap-3">

                        @if ($jawaban)

                            <span
                                class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700"
                            >
                                Nilai {{ number_format((float) $jawaban->skor, 2) }}
                            </span>

                        @else

                            <span
                                class="rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700"
                            >
                                Belum dikerjakan
                            </span>

                        @endif


                        <span class="font-semibold text-teal">
                            Buka →
                        </span>

                    </div>

                </a>

            @empty

                <div class="p-10 text-center">

                    <p class="text-sm text-ink/50">
                        Belum ada quiz yang dipublish untuk mata kuliah ini.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

@endsection