@extends('lecturer.app-lecturer-create-materi')

@section('ketjudul')
Detail Tugas
@endsection

@section('judul')
{{ $tugas->pengajaranDosen->kelas->matakuliah->nama_mk }}
@endsection

@section('content')
<div class="bg-paper">
    <div class="mx-auto max-w-4xl px-6 py-8">

        {{-- Notifikasi --}}
        @if (session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
        @endif

        <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">

            {{-- Header --}}
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                <div>
                    <h2 class="font-display text-xl font-semibold text-ink">
                        {{ $tugas->judul }}
                    </h2>
                    <p class="mt-1 text-sm text-ink/50">
                        @if ($tugas->deadline)
                        Deadline: {{ $tugas->deadline->format('d M Y, H:i') }}
                        @else
                        Tanpa deadline
                        @endif
                        @if ($tugas->bobot_nilai)
                        • Bobot {{ $tugas->bobot_nilai }}%
                        @endif
                    </p>
                </div>

                <div class="flex shrink-0 gap-2">
                    <a href="{{ route('lecturer.tugas.edit', $tugas->id) }}"
                        class="rounded-lg border border-line px-4 py-2.5 text-sm font-semibold text-ink
                               transition hover:bg-paper">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('lecturer.tugas.destroy', $tugas->id) }}"
                        onsubmit="return confirm('Yakin ingin menghapus tugas ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="rounded-lg border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-semibold
                                   text-red-600 transition hover:bg-red-100">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>

            {{-- Deskripsi / Soal --}}
            @if ($tugas->deskripsi)
            <div class="mt-6 border-t border-line pt-6">
                <h3 class="text-sm font-semibold text-ink">Deskripsi / Soal</h3>
                <p class="mt-2 whitespace-pre-line text-sm text-ink/70">
                    {{ $tugas->deskripsi }}
                </p>
            </div>
            @endif

            {{-- Lampiran Soal --}}
            @if ($tugas->files->count())
            <div class="mt-6 border-t border-line pt-6">
                <h3 class="text-sm font-semibold text-ink">Lampiran Soal</h3>

                <div class="mt-3 space-y-4">
                    @foreach ($tugas->files as $file)
                    @if ($file->file_type === 'gambar')
                    <div>
                        <img src="{{ Storage::url($file->file_path) }}"
                            alt="Lampiran soal"
                            class="max-h-[500px] rounded-xl border border-line">
                    </div>
                    @elseif ($file->file_type === 'pdf')
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <span class="text-xs font-medium text-ink/50">
                                {{ basename($file->file_path) }}
                            </span>
                            <a href="{{ Storage::url($file->file_path) }}" target="_blank"
                                class="text-xs font-medium text-blue-600 hover:underline">
                                Buka di tab baru
                            </a>
                        </div>
                        <iframe src="{{ Storage::url($file->file_path) }}"
                            class="h-[480px] w-full rounded-xl border border-line"
                            loading="lazy"></iframe>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Kembali --}}
            <div class="mt-6 border-t border-line pt-6">
                <a href="{{ route('pengajaran.show', ['id' => $tugas->pengajaranDosen->kelas_id]) }}"
                    class="text-sm font-medium text-ink/60 hover:text-ink">
                    ← Kembali ke halaman kelas
                </a>
            </div>

        </div>

    </div>
</div>
@endsection