@extends('student.app-student')

@section('ketjudul')
Absensi
@endsection

@section('judul')
Rekap Absensi
@endsection

@section('content')

<div class="lg:col-span-3">

    <div class="mx-auto max-w-2xl">

        @if (session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
        @endif

        {{-- Header info mata kuliah --}}
        <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">
            <p class="text-sm text-ink/50">Rekap Kehadiran</p>
            <h1 class="mt-1 font-display text-xl font-semibold text-ink">
                {{ $kelas->matakuliah->nama_mk }}
            </h1>

            {{-- Ringkasan status --}}
            @php
            $totalHadir = $riwayat->where('status', 'hadir')->count();
            $totalIzin = $riwayat->where('status', 'izin')->count();
            $totalSakit = $riwayat->where('status', 'sakit')->count();
            $totalAlpha = $riwayat->where('status', 'alpha')->count();
            @endphp

            <div class="mt-5 grid grid-cols-4 gap-2 text-center">
                <div class="rounded-xl bg-green-50 py-3">
                    <p class="text-lg font-bold text-green-700">{{ $totalHadir }}</p>
                    <p class="text-xs text-green-700/70">Hadir</p>
                </div>
                <div class="rounded-xl bg-blue-50 py-3">
                    <p class="text-lg font-bold text-blue-700">{{ $totalIzin }}</p>
                    <p class="text-xs text-blue-700/70">Izin</p>
                </div>
                <div class="rounded-xl bg-yellow-50 py-3">
                    <p class="text-lg font-bold text-yellow-700">{{ $totalSakit }}</p>
                    <p class="text-xs text-yellow-700/70">Sakit</p>
                </div>
                <div class="rounded-xl bg-red-50 py-3">
                    <p class="text-lg font-bold text-red-700">{{ $totalAlpha }}</p>
                    <p class="text-xs text-red-700/70">Alpha</p>
                </div>
            </div>
        </div>

        {{-- Daftar riwayat kehadiran --}}
        <div class="mt-6 rounded-2xl border border-line bg-white shadow-sm">
            <div class="border-b border-line px-6 py-4">
                <h2 class="font-display text-sm font-semibold text-ink">
                    Riwayat Kehadiran
                </h2>
            </div>

            <div class="divide-y divide-line">
                @forelse ($riwayat as $item)
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <p class="text-sm font-medium text-ink">
                            Pertemuan ke-{{ $item->sesi->pertemuan_ke }}
                            @if ($item->sesi->judul)
                            — {{ $item->sesi->judul }}
                            @endif
                        </p>
                        <p class="mt-0.5 text-xs text-ink/50">
                            @if ($item->waktu_absen)
                            {{ \Carbon\Carbon::parse($item->waktu_absen)->translatedFormat('d M Y, H:i') }}
                            @else
                            Belum absen
                            @endif
                        </p>
                        @if ($item->catatan)
                        <p class="mt-1 text-xs italic text-ink/40">
                            "{{ $item->catatan }}"
                        </p>
                        @endif
                    </div>

                    <span @class([ 'rounded-full px-3 py-1 text-xs font-semibold' , 'bg-green-50 text-green-700'=> $item->status === 'hadir',
                        'bg-blue-50 text-blue-700' => $item->status === 'izin',
                        'bg-yellow-50 text-yellow-700' => $item->status === 'sakit',
                        'bg-red-50 text-red-700' => $item->status === 'alpha',
                        ])>
                        {{ ucfirst($item->status) }}
                    </span>
                </div>
                @empty
                <div class="px-6 py-10 text-center text-sm text-ink/40">
                    Belum ada riwayat absensi untuk mata kuliah ini.
                </div>
                @endforelse
            </div>
        </div>

    </div>

</div>

@endsection