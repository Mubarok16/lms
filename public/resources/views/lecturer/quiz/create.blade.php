@extends('lecturer.app-lecturer-create-materi')

@section('ketjudul')
    Tambah Quiz
@endsection

@section('judul')
    {{ $pengajaranDosen->pengajaran->matakuliah->nama_mk ?? 'Quiz' }}
@endsection

@section('content')
    <div class="bg-paper">
        <div class="mx-auto max-w-2xl px-6 py-8">

            <div class="mb-6">
                <a href="{{ route('lecturer.quiz.index', $pengajaranDosen->id) }}"
                    class="text-sm font-medium text-ink/50 hover:text-ink">
                    &larr; Kembali ke daftar quiz
                </a>
            </div>

            <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">

                <h1 class="font-display text-lg font-semibold text-ink">
                    Buat Quiz Baru
                </h1>
                <p class="mt-1 text-sm text-ink/50">
                    Isi informasi dasar dulu. Soal akan diisi lewat upload template Excel di langkah berikutnya.
                </p>

                <form method="POST" action="{{ route('lecturer.quiz.store', $pengajaranDosen->id) }}" class="mt-6 space-y-4">
                    @csrf

                    {{-- Judul --}}
                    <div>
                        <label class="block text-sm font-medium text-ink">
                            Judul Quiz
                        </label>
                        <input type="text" name="judul" required value="{{ old('judul') }}"
                            placeholder="Misal: Quiz Pertemuan 5 - Normalisasi Database"
                            class="mt-1.5 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm
                               text-ink outline-none transition focus:border-ink">
                        @error('judul')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="block text-sm font-medium text-ink">
                            Deskripsi <span class="text-ink/40">(opsional)</span>
                        </label>
                        <textarea name="deskripsi" rows="3" placeholder="Instruksi singkat untuk mahasiswa"
                            class="mt-1.5 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm
                               text-ink outline-none transition focus:border-ink">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Durasi --}}
                    <div>
                        <label class="block text-sm font-medium text-ink">
                            Durasi Pengerjaan (menit) <span class="text-ink/40">(opsional)</span>
                        </label>
                        <input type="number" name="durasi_menit" min="1" max="600"
                            value="{{ old('durasi_menit') }}" placeholder="Misal: 30"
                            class="mt-1.5 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm
                               text-ink outline-none transition focus:border-ink">
                        @error('durasi_menit')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Aksi --}}
                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('lecturer.quiz.index', $pengajaranDosen->id) }}"
                            class="rounded-lg border border-line px-4 py-2.5 text-sm font-semibold text-ink
                               transition hover:bg-paper">
                            Batal
                        </a>
                        <button type="submit"
                            class="rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white
                               transition hover:bg-primaryDark">
                            Simpan & Lanjut
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
@endsection
