@extends('lecturer.app-lecturer-create-materi')

@section('ketjudul')
Koreksi Tugas
@endsection

@section('judul')
{{ $tugas->judul }}
@endsection

@section('content')
<div class="bg-paper">
    <div class="mx-auto max-w-4xl px-6 py-8">

        <div class="mb-6">
            <a href="{{ route('pengajaran.show', ['id' => $tugas->pengajaranDosen->kelas_id]) }}">
                &larr; Kembali ke kelas
            </a>
        </div>

        @if (session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
        @endif

        {{-- Header Tugas --}}
        <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">
            <h1 class="font-display text-lg font-semibold text-ink">{{ $tugas->judul }}</h1>
            <p class="mt-1 text-sm text-ink/50">
                {{ $jawabanList->count() }} mahasiswa mengumpulkan
            </p>
        </div>

        {{-- Daftar Jawaban --}}
        <div class="mt-6 overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
            <div class="divide-y divide-line">
                @forelse ($jawabanList as $j)
                <a href="{{ route('lecturer.tugas.jawaban.show', [$tugas, $j]) }}"
                    class="flex items-center justify-between gap-4 p-5 transition hover:bg-paper">
                    <div>
                        <p class="text-sm font-semibold text-ink">
                            {{ $j->mahasiswa->user->name ?? '-' }}
                        </p>
                        <p class="mt-0.5 text-xs text-ink/40">
                            NIM: {{ $j->mahasiswa->nim ?? '-' }}
                        </p>
                        <p class="mt-0.5 text-xs text-ink/50">
                            @if ($j->waktu_submit)
                            Dikumpulkan: {{ $j->waktu_submit->translatedFormat('d M Y, H:i') }}
                            @else
                            Belum mengumpulkan
                            @endif
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        @if ($j->status === 'sudah_dikoreksi')
                        <span class="text-sm font-semibold text-ink">{{ $j->skor }}</span>
                        <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                            Sudah Dikoreksi
                        </span>
                        @else
                        <span class="rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700">
                            Menunggu Koreksi
                        </span>
                        @endif
                    </div>
                </a>
                @empty
                <div class="p-6">
                    <div class="rounded-xl border border-dashed border-line bg-paper p-8 text-center">
                        <p class="text-sm text-ink/50">Belum ada mahasiswa yang mengumpulkan tugas.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection