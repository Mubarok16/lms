@extends('lecturer.app-lecturer-create-materi')

@section('judul')
Rekap Absensi - Pertemuan {{ $sesi->pertemuan_ke }}
@endsection

@section('content')
<div class="bg-paper min-h-screen">
    <div class="mx-auto max-w-4xl px-6 py-10">

        {{-- Header --}}
        <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="text-sm text-ink/50">
                    {{ $sesi->kelas->matakuliah->nama_mk }} • Pertemuan {{ $sesi->pertemuan_ke }}
                </p>
                <h1 class="font-display text-xl font-semibold text-ink">
                    Rekap Kehadiran
                </h1>
            </div>

            <div class="text-sm text-ink/50">
                Dibuka: {{ $sesi->dibuka_pada->format('d M Y, H:i') }}
                — Ditutup: {{ $sesi->ditutup_pada->format('H:i') }}
            </div>
        </div>

        {{-- Ringkasan --}}
        @php
        $totalMahasiswa = $sesi->kelas->mahasiswa->count();
        $totalHadir = $sesi->absensi->where('status', 'hadir')->count();
        @endphp

        <div class="mb-6 grid grid-cols-3 gap-4">
            <div class="rounded-xl border border-line bg-white p-4 text-center">
                <p class="text-2xl font-bold text-ink">{{ $totalMahasiswa }}</p>
                <p class="text-xs text-ink/50">Total Mahasiswa</p>
            </div>
            <div class="rounded-xl border border-line bg-white p-4 text-center">
                <p class="text-2xl font-bold text-green-600">{{ $totalHadir }}</p>
                <p class="text-xs text-ink/50">Hadir</p>
            </div>
            <div class="rounded-xl border border-line bg-white p-4 text-center">
                <p class="text-2xl font-bold text-red-500">{{ $totalMahasiswa - $totalHadir }}</p>
                <p class="text-xs text-ink/50">Tidak Hadir</p>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-line bg-paper text-left text-xs font-semibold uppercase text-ink/50">
                        <th class="px-5 py-3">Nama</th>
                        <th class="px-5 py-3">NIM</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Waktu Absen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($sesi->kelas->mahasiswa as $mhs)
                    @php
                    $absen = $sesi->absensi->firstWhere('mahasiswa_id', $mhs->id);
                    @endphp
                    <tr>
                        <td class="px-5 py-3 font-medium text-ink">
                            {{ $mhs->user->name ?? '-' }}
                        </td>
                        <td class="px-5 py-3 text-ink/60">
                            {{ $mhs->nim ?? '-' }}
                        </td>
                        <td class="px-5 py-3">
                            @if ($absen)
                            <span class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">
                                Hadir
                            </span>
                            @else
                            <span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-600">
                                Alpha
                            </span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-ink/60">
                            {{ $absen?->waktu_absen?->format('H:i:s') ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-ink/50">
                            Belum ada mahasiswa terdaftar di kelas ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection