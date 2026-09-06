@extends('lecturer.app-lecturer-create-materi')

@section('ketjudul')
Tambah Tugas
@endsection

@section('judul')
{{ $pengajaranDosen->kelas->matakuliah->nama_mk }}
@endsection

@section('content')
<div class="bg-paper">
    <div class="mx-auto max-w-3xl px-6 py-8">

        <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">

            <h2 class="font-display text-lg font-semibold text-ink">
                Tambah Tugas Baru
            </h2>
            <p class="mt-1 text-sm text-ink/50">
                Isi soal dan deskripsi tugas untuk mahasiswa.
            </p>

            <form method="POST"
                action="{{ route('lecturer.tugas.store', $pengajaranDosen->id) }}"
                enctype="multipart/form-data"
                class="mt-6 space-y-5">
                @csrf

                {{-- Judul --}}
                <div>
                    <label class="block text-sm font-medium text-ink">Judul Tugas</label>
                    <input type="text" name="judul" required value="{{ old('judul') }}"
                        placeholder="Misal: Essai Bab 3 - Normalisasi Database"
                        class="mt-1.5 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm
                               text-ink outline-none transition focus:border-ink">
                    @error('judul')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi / Soal --}}
                <div>
                    <label class="block text-sm font-medium text-ink">Deskripsi / Soal</label>
                    <textarea name="deskripsi" rows="6"
                        placeholder="Tuliskan soal essai di sini..."
                        class="mt-1.5 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm
                               text-ink outline-none transition focus:border-ink">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Lampiran gambar/pdf soal --}}
                <div>
                    <label class="block text-sm font-medium text-ink">
                        Lampiran Soal <span class="text-ink/40">(opsional, gambar/PDF, boleh lebih dari 1)</span>
                    </label>
                    <input type="file" name="files[]" multiple accept=".pdf,.jpg,.jpeg,.png"
                        class="mt-1.5 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm text-ink
                               file:mr-4 file:rounded-md file:border-0 file:bg-paper file:px-3 file:py-1.5
                               file:text-sm file:font-medium file:text-ink">
                    @error('files.*')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deadline & Bobot Nilai --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-ink">Deadline</label>
                        <input type="datetime-local" name="deadline" value="{{ old('deadline') }}"
                            class="mt-1.5 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm
                                   text-ink outline-none transition focus:border-ink">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink">Bobot Nilai (%)</label>
                        <input type="number" name="bobot_nilai" min="0" max="100" value="{{ old('bobot_nilai') }}"
                            class="mt-1.5 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm
                                   text-ink outline-none transition focus:border-ink">
                    </div>
                </div>

                {{-- Aksi --}}
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ url()->previous() }}"
                        class="rounded-lg border border-line px-4 py-2.5 text-sm font-semibold text-ink
                               transition hover:bg-paper">
                        Batal
                    </a>
                    <button type="submit"
                        class="rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white
                               transition hover:bg-primaryDark">
                        Simpan Tugas
                    </button>
                </div>

            </form>

        </div>

    </div>
</div>
@endsection