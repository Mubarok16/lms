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
                <a href="{{ route('student.matakuliah.show', ['kelas' => $tugas->pengajaranDosen->kelas_id]) }}"
                    class="text-sm text-ink/70 hover:text-primary">
                    &larr; Kembali ke kelas
                </a>

                {{-- Informasi Tugas --}}
                <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">

                    {{-- Batas Pengumpulan --}}
                    @if ($tugas->deadline)
                        @php
                            $isOverdue = now()->greaterThan($tugas->deadline);
                            $isToday = now()->isSameDay($tugas->deadline);
                            $isSoon = now()->diffInDays($tugas->deadline, false) <= 3 && !$isOverdue;
                        @endphp

                        <div class="flex items-center gap-3 rounded-xl border border-line bg-paper/60 px-4 py-3">

                            {{-- Icon --}}
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-ink text-white">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M8 7V3m8 4V3m-9 4h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>

                            {{-- Content --}}
                            <div class="min-w-0">
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-ink/50">
                                    Batas Pengumpulan
                                </p>

                                <p class="mt-0.5 text-sm font-bold text-ink">
                                    {{ $tugas->deadline->translatedFormat('d M Y') }}
                                    <span class="font-medium text-ink/50">
                                        · {{ $tugas->deadline->format('H:i') }} WIB
                                    </span>
                                </p>


                                {{-- Status --}}
                                @if ($isOverdue)
                                    <p class="mt-0.5 text-[10px] font-semibold text-red-600">
                                        ● Deadline telah lewat
                                    </p>
                                @elseif ($isToday)
                                    <p class="mt-0.5 text-[10px] font-semibold text-amber-600">
                                        ● Deadline hari ini
                                    </p>
                                @elseif ($isSoon)
                                    <p class="mt-0.5 text-[10px] font-semibold text-amber-600">
                                        ● Segera berakhir
                                    </p>
                                @else
                                    <p class="mt-0.5 text-[10px] text-ink/45">
                                        ● Pengumpulan tersedia
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Bobot Nilai --}}
                    @if ($tugas->bobot_nilai)
                        <div class="flex items-center gap-3 rounded-xl border border-line bg-paper/60 px-4 py-3">

                            {{-- Icon --}}
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-ink text-white">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M9 14.25l6-6m-6.75-3h.008v.008H8.25V5.25zm7.5 9h.008v.008h-.008v-.008zM19.5 12a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z" />
                                </svg>
                            </div>

                            {{-- Content --}}
                            <div>
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-ink/50">
                                    Bobot Penilaian
                                </p>

                                <p class="mt-0.5 text-sm font-bold text-ink">
                                    {{ $tugas->bobot_nilai }}%
                                    <span class="ml-1 text-[10px] font-medium text-ink/45">
                                        dari nilai akhir
                                    </span>
                                </p>
                            </div>
                        </div>
                    @endif


                </div>

            </div>



            {{-- Header & Detail Tugas --}}
            <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">
                <h1 class="font-display text-xl font-bold text-ink">{{ $tugas->judul }}</h1>

                @if ($tugas->deskripsi)
                    <p class="mt-2 text-sm text-ink/70">{{ $tugas->deskripsi }}</p>
                @endif

            </div>



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
                                <a href="{{ $url }}" target="_blank"
                                    class="text-xs font-medium text-primary hover:underline">
                                    Buka di tab baru ↗
                                </a>
                            </div>

                            @if (strtolower($file->file_type) === 'pdf')
                                <iframe src="{{ $url }}"
                                    class="h-[600px] w-full rounded-lg border border-line"></iframe>
                            @else
                                <img src="{{ $url }}" alt="Lampiran {{ $file->urutan }}"
                                    class="w-full rounded-lg border border-line object-contain">
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
                                <a href="{{ $url }}" target="_blank"
                                    class="text-xs font-medium text-primary hover:underline">
                                    Buka di tab baru ↗
                                </a>
                            </div>

                            @if (strtolower($file->file_type) === 'pdf')
                                <iframe src="{{ $url }}"
                                    class="h-[600px] w-full rounded-lg border border-line"></iframe>
                            @else
                                <img src="{{ $url }}" alt="File {{ $file->urutan }}"
                                    class="w-full rounded-lg border border-line object-contain">
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
    @if (now()->lte($tugas->deadline))
        <div class="mt-6 rounded-2xl border border-line bg-white p-6 shadow-sm">
            <h2 class="font-display text-base font-semibold text-ink">
                {{ $jawabanSaya ? 'Kumpulkan Ulang Jawaban' : 'Kumpulkan Jawaban' }}
            </h2>

            <p class="mt-1 text-sm text-ink/50">
                Bisa upload lebih dari satu file (PDF atau foto).
                Mengumpulkan ulang akan menggantikan file sebelumnya.
            </p>

            <form method="POST" action="{{ route('student.tugas.submit', $tugas) }}" enctype="multipart/form-data"
                class="mt-4 flex flex-col gap-3">

                @csrf

                <input type="file" name="files[]" accept=".pdf,image/*" multiple required
                    class="block w-full text-sm text-ink/70 file:mr-3 file:rounded-lg file:border-0 file:bg-paper file:px-4 file:py-2 file:text-sm file:font-semibold file:text-ink hover:file:bg-line/30">

                <button type="submit"
                    class="self-start rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primaryDark">
                    {{ $jawabanSaya ? 'Kumpulkan Ulang' : 'Kumpulkan' }}
                </button>
            </form>
        </div>
    @else
        {{-- Deadline sudah lewat --}}
        <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-6">
            <h2 class="font-display text-base font-semibold text-red-700">
                Pengumpulan Ditutup
            </h2>

            <p class="mt-1 text-sm text-red-600">
                Batas pengumpulan tugas telah berakhir.
                Anda sudah tidak dapat mengumpulkan atau mengubah jawaban.
            </p>

            <p class="mt-2 text-xs text-red-500">
                Deadline:
                {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y, H:i') }}
            </p>
        </div>
    @endif

    </div>
    </div>
@endsection
