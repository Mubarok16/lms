@extends('lecturer.app-lecturer-create-materi')

@section('judul')
Rekap Absensi
@endsection

@section('content')



{{-- =================================================
            HEADER + TOMBOL DOWNLOAD
        ================================================== --}}
<div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

    <div>
        <h1 class="font-display text-xl font-semibold text-ink">
            Rekap Absensi
        </h1>
        <p class="mt-1 text-sm text-ink/50">
            Rekapitulasi kehadiran mahasiswa per pertemuan.
        </p>
    </div>

    <a href="{{ route('lecturer.absensi.rekapSemua.export', $pengajaranDosen->id) }}"
        class="inline-flex items-center justify-center gap-2 rounded-lg bg-ink px-4 py-2.5
                       text-sm font-semibold text-white transition hover:bg-primaryDark">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M7 10l5 5 5-5M12 15V3" />
        </svg>
        Download Excel
    </a>

</div>

{{-- =================================================
            LIST SESI ABSENSI
        ================================================== --}}
<div class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
    <div class="divide-y divide-line">

        @forelse ($sesiAbsensiList as $s)
        <div x-data="{ showRekap: false }" class="p-5">

            <div class="flex items-center justify-between gap-4">

                <a href="{{ route('lecturer.absensi.show', $s->id) }}"
                    class="min-w-0 flex-1 rounded-lg -m-2 p-2 transition hover:bg-paper">
                    <h3 class="text-sm font-semibold text-ink">
                        Pertemuan {{ $s->pertemuan_ke }}
                        @if ($s->judul)
                        — {{ $s->judul }}
                        @endif
                    </h3>
                    <p class="mt-1 text-xs text-ink/50">
                        {{ $s->dibuka_pada->format('d M Y, H:i') }}
                        • {{ $s->absensi->count() }} mahasiswa hadir
                    </p>
                </a>

                <div class="flex shrink-0 items-center gap-2">

                    @if ($s->isExpired())
                    <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-600">
                        Ditutup
                    </span>
                    @else
                    <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                        Aktif
                    </span>
                    @endif

                    {{-- Tombol Rekap --}}
                    <button type="button" @click="showRekap = true"
                        class="rounded-lg border border-line px-3 py-1.5 text-xs font-semibold text-ink
                                       transition hover:bg-paper">
                        Lihat Rekap
                    </button>

                </div>

            </div>

            {{-- =================================================
                        MODAL: REKAP ABSENSI PER PERTEMUAN
                    ================================================== --}}
            <div x-show="showRekap" x-cloak @keydown.escape.window="showRekap = false"
                class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none;">

                {{-- Backdrop --}}
                <div x-show="showRekap" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" @click="showRekap = false"
                    class="absolute inset-0 bg-ink/40"></div>

                {{-- Modal Box --}}
                <div x-show="showRekap" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative w-full max-w-2xl max-h-[85vh] overflow-y-auto rounded-2xl bg-white p-6 shadow-xl">

                    {{-- Header Modal --}}
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="font-display text-lg font-semibold text-ink">
                                Rekap Absensi
                            </h2>
                            <p class="mt-1 text-sm text-ink/50">
                                Pertemuan {{ $s->pertemuan_ke }}
                                @if ($s->judul)
                                — {{ $s->judul }}
                                @endif
                            </p>
                        </div>
                        <button type="button" @click="showRekap = false"
                            class="rounded-lg p-1.5 text-ink/40 transition hover:bg-paper hover:text-ink">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Ringkasan Status --}}
                    @php
                    $rekapCount = [
                    'hadir' => $s->absensi->where('status', 'hadir')->count(),
                    'izin' => $s->absensi->where('status', 'izin')->count(),
                    'sakit' => $s->absensi->where('status', 'sakit')->count(),
                    'alpha' => $s->absensi->where('status', 'alpha')->count(),
                    ];
                    @endphp
                    <div class="mt-4 grid grid-cols-4 gap-2">
                        <div class="rounded-lg bg-green-50 p-3 text-center">
                            <p class="text-lg font-semibold text-green-700">{{ $rekapCount['hadir'] }}</p>
                            <p class="text-xs text-green-700/70">Hadir</p>
                        </div>
                        <div class="rounded-lg bg-blue-50 p-3 text-center">
                            <p class="text-lg font-semibold text-blue-700">{{ $rekapCount['izin'] }}</p>
                            <p class="text-xs text-blue-700/70">Izin</p>
                        </div>
                        <div class="rounded-lg bg-yellow-50 p-3 text-center">
                            <p class="text-lg font-semibold text-yellow-700">{{ $rekapCount['sakit'] }}</p>
                            <p class="text-xs text-yellow-700/70">Sakit</p>
                        </div>
                        <div class="rounded-lg bg-red-50 p-3 text-center">
                            <p class="text-lg font-semibold text-red-700">{{ $rekapCount['alpha'] }}</p>
                            <p class="text-xs text-red-700/70">Alpha</p>
                        </div>
                    </div>

                    {{-- Tabel Detail Mahasiswa --}}
                    <div class="mt-5 overflow-hidden rounded-xl border border-line">
                        <table class="w-full text-sm">
                            <thead class="bg-paper">
                                <tr>
                                    <th class="px-4 py-2.5 text-left text-xs font-semibold text-ink/60">Mahasiswa</th>
                                    <th class="px-4 py-2.5 text-left text-xs font-semibold text-ink/60">NIM</th>
                                    <th class="px-4 py-2.5 text-left text-xs font-semibold text-ink/60">Status</th>
                                    <th class="px-4 py-2.5 text-left text-xs font-semibold text-ink/60">Waktu Absen</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-line">
                                @forelse ($s->absensi as $a)
                                <tr>
                                    <td class="px-4 py-2.5 text-ink">
                                        {{ $a->mahasiswa->user->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2.5 text-ink/60">
                                        {{ $a->mahasiswa->nim ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2.5">
                                        @php
                                        $statusColor = match ($a->status) {
                                        'hadir' => 'bg-green-50 text-green-700',
                                        'izin' => 'bg-blue-50 text-blue-700',
                                        'sakit' => 'bg-yellow-50 text-yellow-700',
                                        'alpha' => 'bg-red-50 text-red-700',
                                        default => 'bg-paper text-ink/60',
                                        };
                                        @endphp
                                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusColor }}">
                                            {{ ucfirst($a->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2.5 text-ink/60">
                                        {{ $a->waktu_absen ? $a->waktu_absen->format('d M Y, H:i') : '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-sm text-ink/50">
                                        Belum ada mahasiswa yang absen.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Mahasiswa yang belum absen (opsional) --}}
                    @php
                    $sudahAbsenIds = $s->absensi->pluck('mahasiswa_id');
                    $belumAbsen = $pengajaran->mahasiswa->whereNotIn('id', $sudahAbsenIds);
                    @endphp
                    @if ($belumAbsen->count())
                    <div class="mt-4 rounded-xl border border-dashed border-line bg-paper p-4">
                        <p class="text-xs font-semibold text-ink/60">
                            {{ $belumAbsen->count() }} mahasiswa belum absen:
                        </p>
                        <p class="mt-1 text-xs text-ink/50">
                            {{ $belumAbsen->pluck('user.name')->filter()->implode(', ') }}
                        </p>
                    </div>
                    @endif

                </div>
            </div>

        </div>
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



@endsection