@extends('lecturer.app-lecturer-create-materi')

@section('ketjudul')
    Rekap Nilai
@endsection

@section('judul')
    Rekap Tugas & Quiz
@endsection

@section('content')
    @php
        $selectedKelas = $pengajaran?->kelas;
        $tugasSelected = $selectedKelas
            ? $tugasList->filter(fn ($item) => $item->pengajaranDosen?->kelas_id === $selectedKelas->id)
            : $tugasList;
        $quizSelected = $selectedKelas
            ? $quizList->filter(fn ($item) => $item->pengajaranDosen?->kelas_id === $selectedKelas->id)
            : $quizList;
    @endphp

    <div class="space-y-6">
        {{-- Filter --}}
        <div class="rounded-2xl border border-line bg-white p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-mono uppercase tracking-wider text-ink/40">Rekap nilai dosen</p>
                    <h2 class="mt-1 text-lg font-semibold">Tugas dan Quiz Mahasiswa</h2>
                    <p class="mt-1 text-sm text-ink/50">
                        Nilai diambil langsung dari hasil pengumpulan tugas dan pengerjaan quiz.
                    </p>
                </div>

                <form method="GET" action="{{ route('lecturer.nilai.index') }}" class="flex flex-col sm:flex-row gap-2">
                    <select name="kelas_id" class="min-w-[250px] rounded-lg border-line text-sm focus:border-teal focus:ring-teal">
                        <option value="">Semua kelas</option>
                        @foreach ($pengajaranList as $item)
                            <option value="{{ $item->kelas_id }}" @selected($kelasId == $item->kelas_id)>
                                {{ $item->kelas?->matakuliah?->nama_mk ?? '-' }} — Kelas {{ $item->kelas?->kode_kelas ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                    <button class="rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white hover:bg-ink/90">
                        Tampilkan
                    </button>
                </form>
            </div>
        </div>

        {{-- Statistik --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-line bg-white p-5">
                <p class="text-xs text-ink/45">Tugas</p>
                <p class="mt-2 text-3xl font-semibold">{{ $tugasSelected->count() }}</p>
                <p class="mt-1 text-xs text-ink/40">{{ $selectedKelas ? 'pada kelas terpilih' : 'seluruh kelas' }}</p>
            </div>
            <div class="rounded-2xl border border-line bg-white p-5">
                <p class="text-xs text-ink/45">Quiz</p>
                <p class="mt-2 text-3xl font-semibold">{{ $quizSelected->count() }}</p>
                <p class="mt-1 text-xs text-ink/40">{{ $quizList->where('is_published', true)->count() }} quiz dipublish</p>
            </div>
            <div class="rounded-2xl border border-line bg-white p-5">
                <p class="text-xs text-ink/45">Belum dikoreksi</p>
                <p class="mt-2 text-3xl font-semibold text-amber">{{ $pendingTugas }}</p>
                <p class="mt-1 text-xs text-ink/40">submission tugas</p>
            </div>
            <div class="rounded-2xl border border-line bg-white p-5">
                <p class="text-xs text-ink/45">Total submission</p>
                <p class="mt-2 text-3xl font-semibold">{{ $totalSubmissions }}</p>
                <p class="mt-1 text-xs text-ink/40">tugas + quiz</p>
            </div>
        </div>

        {{-- Rekap --}}
        <div class="overflow-hidden rounded-2xl border border-line bg-white">
            <div class="border-b border-line px-5 py-4">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="font-semibold">Rekap Per Mahasiswa</h2>
                        <p class="text-xs text-ink/45 mt-1">
                            Rata-rata dihitung dari nilai yang sudah tersedia. Tanda — berarti belum ada nilai.
                        </p>
                    </div>
                    @if ($selectedKelas)
                        <span class="rounded-full bg-teal/10 px-3 py-1 text-xs font-medium text-teal">
                            {{ $selectedKelas->matakuliah?->nama_mk ?? '-' }} · Kelas {{ $selectedKelas->kode_kelas }}
                        </span>
                    @endif
                </div>
            </div>

            @if ($rows->isEmpty())
                <div class="p-10 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-paper text-ink/35">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M4 19.5A2.5 2.5 0 016.5 17H20" />
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" />
                        </svg>
                    </div>
                    <p class="mt-3 text-sm font-medium">Belum ada mahasiswa</p>
                    <p class="mt-1 text-xs text-ink/45">Tambahkan peserta ke kelas terlebih dahulu.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-line bg-paper/70 text-left">
                                <th rowspan="2" class="whitespace-nowrap px-4 py-3 font-semibold">No</th>
                                <th rowspan="2" class="whitespace-nowrap px-4 py-3 font-semibold">Mahasiswa</th>
                                <th rowspan="2" class="whitespace-nowrap px-4 py-3 font-semibold">NIM</th>
                                @if ($selectedKelas)
                                    @if ($tugasSelected->isNotEmpty())
                                        <th colspan="{{ $tugasSelected->count() }}" class="border-l border-line px-4 py-2 text-center font-semibold">Tugas</th>
                                    @endif
                                    @if ($quizSelected->isNotEmpty())
                                        <th colspan="{{ $quizSelected->count() }}" class="border-l border-line px-4 py-2 text-center font-semibold">Quiz</th>
                                    @endif
                                    <th rowspan="2" class="border-l border-line whitespace-nowrap px-4 py-3 text-center font-semibold">Rata-rata Tugas</th>
                                    <th rowspan="2" class="whitespace-nowrap px-4 py-3 text-center font-semibold">Rata-rata Quiz</th>
                                @else
                                    <th rowspan="2" class="border-l border-line whitespace-nowrap px-4 py-3 font-semibold">Kelas</th>
                                    <th rowspan="2" class="whitespace-nowrap px-4 py-3 text-center font-semibold">Rata-rata Tugas</th>
                                    <th rowspan="2" class="whitespace-nowrap px-4 py-3 text-center font-semibold">Rata-rata Quiz</th>
                                @endif
                            </tr>
                            @if ($selectedKelas)
                                <tr class="border-b border-line bg-paper/70 text-left">
                                    @foreach ($tugasSelected as $tugas)
                                        <th class="border-l border-line px-4 py-2 text-xs font-medium">{{ $tugas->judul }}</th>
                                    @endforeach
                                    @foreach ($quizSelected as $quiz)
                                        <th class="border-l border-line px-4 py-2 text-xs font-medium">{{ $quiz->judul }}</th>
                                    @endforeach
                                </tr>
                            @endif
                        </thead>
                        <tbody class="divide-y divide-line">
                            @foreach ($rows as $index => $row)
                                <tr class="hover:bg-paper/60">
                                    <td class="px-4 py-3 text-ink/50">{{ $index + 1 }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 font-medium">{{ $row['nama'] }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 font-mono text-xs text-ink/55">{{ $row['nim'] }}</td>
                                    @if ($selectedKelas)
                                        @foreach ($tugasSelected as $tugas)
                                            @php $score = $row['tugas']->get($tugas->id); @endphp
                                            <td class="border-l border-line px-4 py-3 text-center font-medium">
                                                {{ $score !== null ? number_format((float) $score, 2) : '—' }}
                                            </td>
                                        @endforeach
                                        @foreach ($quizSelected as $quiz)
                                            @php $score = $row['quiz']->get($quiz->id); @endphp
                                            <td class="border-l border-line px-4 py-3 text-center font-medium">
                                                {{ $score !== null ? number_format((float) $score, 2) : '—' }}
                                            </td>
                                        @endforeach
                                    @else
                                        @php $kelas = $pengajaranList->firstWhere('kelas_id', $row['kelas_id'])?->kelas; @endphp
                                        <td class="border-l border-line whitespace-nowrap px-4 py-3 text-xs">
                                            {{ $kelas?->matakuliah?->nama_mk ?? '-' }} · {{ $kelas?->kode_kelas ?? '-' }}
                                        </td>
                                    @endif
                                    <td class="border-l border-line px-4 py-3 text-center font-semibold">
                                        {{ $row['rata_tugas'] !== null ? number_format($row['rata_tugas'], 2) : '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-semibold">
                                        {{ $row['rata_quiz'] !== null ? number_format($row['rata_quiz'], 2) : '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
