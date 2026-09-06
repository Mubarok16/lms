@extends('student.app-student')

@section('ketjudul')
Detail Tugas
@endsection

@section('judul')
{{ $tugas->judul }}
@endsection

@section('content')
<div class="bg-paper">
    <div class="mx-auto max-w-4xl px-6 py-8">

        {{-- Flash Messages --}}
        @if (session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-inside list-disc space-y-0.5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Navigation --}}
        <div class="mb-6">
            <a href="{{ route('student.matakuliah.show', ['kelas' => $tugas->pengajaranDosen->kelas_id]) }}" class="text-sm text-ink/70 hover:text-primary">
                &larr; Kembali ke kelas
            </a>
        </div>

        {{-- Header & Detail Tugas --}}
        <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">
            <h1 class="font-display text-xl font-bold text-ink">{{ $tugas->judul }}</h1>

            @if ($tugas->deskripsi)
            <p class="mt-2 text-sm text-ink/70">{{ $tugas->deskripsi }}</p>
            @endif

            <div class="mt-5 flex flex-wrap items-center gap-3">
                {{-- Deadline --}}
                @if ($tugas->deadline)
                @php
                $isOverdue = now()->greaterThan($tugas->deadline);
                $isToday = now()->isSameDay($tugas->deadline);
                $isSoon = now()->diffInDays($tugas->deadline, false) <= 3 && !$isOverdue;
                    @endphp

                    <div class="flex items-center gap-3 rounded-xl border px-4 py-3 {{ $isOverdue ? 'bg-red-50 border-red-200' : ($isToday ? 'bg-orange-50 border-orange-200' : ($isSoon ? 'bg-yellow-50 border-yellow-200' : 'bg-blue-50 border-blue-200')) }}">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg {{ $isOverdue ? 'bg-red-100 text-red-600' : ($isToday ? 'bg-orange-100 text-orange-600' : ($isSoon ? 'bg-yellow-100 text-yellow-600' : 'bg-blue-100 text-blue-600')) }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-12 8h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-ink/50">BATAS PENGUMPULAN</p>
                        <p class="text-sm font-bold {{ $isOverdue ? 'text-red-600' : ($isToday ? 'text-orange-600' : ($isSoon ? 'text-yellow-700' : 'text-blue-600')) }}">
                            {{ $tugas->deadline->translatedFormat('d M Y, H:i') }}
                        </p>
                        @if ($isOverdue)
                        <p class="mt-0.5 text-xs font-semibold text-red-600">⚠ Deadline sudah lewat</p>
                        @elseif ($isToday)
                        <p class="mt-0.5 text-xs font-semibold text-orange-600">🔥 Deadline hari ini</p>
                        @elseif ($isSoon)
                        <p class="mt-0.5 text-xs font-semibold text-yellow-700">⏰ Deadline segera berakhir</p>
                        @else
                        <p class="mt-0.5 text-xs text-ink/50">Masih tersedia</p>
                        @endif
                    </div>
            </div>
            @endif

            {{-- Bobot Nilai --}}
            @if ($tugas->bobot_nilai)
            <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 12v-2" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-ink/50">BOBOT NILAI</p>
                    <p class="text-lg font-bold text-emerald-600">{{ $tugas->bobot_nilai }}%</p>
                </div>
            </div>
            @endif
        </div>

        {{-- File Soal dari Dosen --}}
        @if ($tugas->files->count())
        <div class="mt-6 border-t border-line pt-6">
            <h2 class="font-display text-base font-semibold text-ink">File Tugas</h2>
            <div class="mt-3 space-y-4">
                @foreach ($tugas->files as $file)
                @php $url = asset('storage/' . $file->file_path); @endphp
                <div class="rounded-xl border border-line p-3">
                    <div class="mb-2 flex items-center justify-between">
                        <span class="text-xs font-semibold text-ink/60">
                            Lampiran {{ $file->urutan }} ({{ strtoupper($file->file_type) }})
                        </span>
                        <a href="{{ $url }}" target="_blank" class="text-xs font-medium text-primary hover:underline">
                            Buka di tab baru ↗
                        </a>
                    </div>

                    @if (strtolower($file->file_type) === 'pdf')
                    <iframe src="{{ $url }}" class="h-[600px] w-full rounded-lg border border-line"></iframe>
                    @else
                    <img src="{{ $url }}" alt="Lampiran {{ $file->urutan }}" class="w-full rounded-lg border border-line object-contain">
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Status Jawaban Saya --}}
    <div class="mt-6 rounded-2xl border border-line bg-white p-6 shadow-sm">
        <h2 class="font-display text-base font-semibold text-ink">Jawaban Saya</h2>

        @if ($jawabanSaya)
        <div class="mt-3 flex items-center gap-2">
            <span class="text-sm text-ink/60">Status:</span>
            @if ($jawabanSaya->status === 'sudah_dikoreksi')
            <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                Sudah Dikoreksi
            </span>
            @else
            <span class="rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700">
                Menunggu Koreksi
            </span>
            @endif
        </div>

        <p class="mt-2 text-xs text-ink/50">
            Dikumpulkan: {{ $jawabanSaya->waktu_submit?->translatedFormat('d M Y, H:i') }}
        </p>

        @if ($jawabanSaya->files->count())
        <div class="mt-3 space-y-4">
            @foreach ($jawabanSaya->files as $file)
            @php $url = asset('storage/' . $file->file_path); @endphp
            <div class="rounded-xl border border-line p-3">
                <div class="mb-2 flex items-center justify-between">
                    <span class="text-xs font-semibold text-ink/60">
                        File {{ $file->urutan }} ({{ strtoupper($file->file_type) }})
                    </span>
                    <a href="{{ $url }}" target="_blank" class="text-xs font-medium text-primary hover:underline">
                        Buka di tab baru ↗
                    </a>
                </div>

                @if (strtolower($file->file_type) === 'pdf')
                <iframe src="{{ $url }}" class="h-[600px] w-full rounded-lg border border-line"></iframe>
                @else
                <img src="{{ $url }}" alt="File {{ $file->urutan }}" class="w-full rounded-lg border border-line object-contain">
                @endif
            </div>
            @endforeach
        </div>
        @endif

        @if ($jawabanSaya->status === 'sudah_dikoreksi')
        <div class="mt-4 rounded-xl bg-paper p-4">
            <p class="text-sm font-semibold text-ink">
                Nilai: {{ $jawabanSaya->skor }}
            </p>
            @if ($jawabanSaya->catatan_koreksi)
            <p class="mt-1 text-sm text-ink/70">
                Catatan: {{ $jawabanSaya->catatan_koreksi }}
            </p>
            @endif
        </div>
        @endif

        @else
        <p class="mt-2 text-sm text-ink/50">Anda belum mengumpulkan jawaban.</p>
        @endif
    </div>

    {{-- Form Submit / Resubmit --}}
    <div class="mt-6 rounded-2xl border border-line bg-white p-6 shadow-sm">
        <h2 class="font-display text-base font-semibold text-ink">
            {{ $jawabanSaya ? 'Kumpulkan Ulang Jawaban' : 'Kumpulkan Jawaban' }}
        </h2>
        <p class="mt-1 text-sm text-ink/50">
            Bisa upload lebih dari satu file (PDF atau foto). Mengumpulkan ulang akan menggantikan file sebelumnya.
        </p>

        <form method="POST" action="{{ route('student.tugas.submit', $tugas) }}" enctype="multipart/form-data" class="mt-4 flex flex-col gap-3">
            @csrf

            <input type="file" name="files[]" accept=".pdf,image/*" multiple required
                class="block w-full text-sm text-ink/70 file:mr-3 file:rounded-lg file:border-0 file:bg-paper file:px-4 file:py-2 file:text-sm file:font-semibold file:text-ink hover:file:bg-line/30">

            <button type="submit" class="self-start rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primaryDark">
                {{ $jawabanSaya ? 'Kumpulkan Ulang' : 'Kumpulkan' }}
            </button>
        </form>
    </div>

</div>
</div>
@endsection