@extends('lecturer.app-lecturer-create-materi')

@section('ketjudul')
    Koreksi Jawaban
@endsection

@section('judul')
    {{ $tugas->judul }}
@endsection

@section('content')
    <div class="bg-paper">
        <div class="mx-auto max-w-4xl px-6 py-8">

            <div class="mb-6">
                <a href="{{ route('lecturer.tugas.jawaban.index', $tugas) }}"
                    class="text-sm font-medium text-ink/50 hover:text-ink">
                    &larr; Kembali ke daftar jawaban
                </a>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-inside list-disc space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Info Mahasiswa --}}
            <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="font-display text-lg font-semibold text-ink">
                            {{ $jawaban->mahasiswa->user->name ?? '-' }}
                        </h1>
                        <p class="mt-1 text-xs text-ink/40">
                            NIM: {{ $jawaban->mahasiswa->nim ?? '-' }}
                        </p>
                        <p class="mt-1 text-sm text-ink/50">
                            @if ($jawaban->waktu_submit)
                                Dikumpulkan: {{ $jawaban->waktu_submit->translatedFormat('d M Y, H:i') }}
                            @endif
                        </p>
                    </div>

                    @if ($jawaban->status === 'sudah_dikoreksi')
                        <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                            Sudah Dikoreksi
                        </span>
                    @else
                        <span class="rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700">
                            Menunggu Koreksi
                        </span>
                    @endif
                </div>
            </div>

            {{-- File Jawaban --}}
            <div class="mt-6 rounded-2xl border border-line bg-white p-6 shadow-sm">
                <h2 class="font-display text-base font-semibold text-ink">File Jawaban</h2>

                @if ($jawaban->files->count())
                    <ul class="mt-3 space-y-2">
                        @foreach ($jawaban->files as $file)
                            <li>
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                    class="text-sm font-medium text-primary hover:underline">
                                    📄 File {{ $file->urutan }} ({{ strtoupper($file->file_type) }})
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    {{-- Preview gambar jika ada --}}
                    @php $fotos = $jawaban->files->where('file_type', 'foto'); @endphp
                    @if ($fotos->count())
                        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                            @foreach ($fotos as $foto)
                                <a href="{{ asset('storage/' . $foto->file_path) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $foto->file_path) }}"
                                        class="aspect-square w-full rounded-lg border border-line object-cover">
                                </a>
                            @endforeach
                        </div>
                    @endif
                @else
                    <p class="mt-2 text-sm text-ink/50">Mahasiswa belum mengupload file.</p>
                @endif
            </div>

            {{-- Form Koreksi --}}
            <div class="mt-6 rounded-2xl border border-line bg-white p-6 shadow-sm">
                <h2 class="font-display text-base font-semibold text-ink">
                    {{ $jawaban->status === 'sudah_dikoreksi' ? 'Ubah Koreksi' : 'Beri Koreksi' }}
                </h2>

                <form method="POST" action="{{ route('lecturer.tugas.jawaban.koreksi', [$tugas, $jawaban]) }}"
                    class="mt-4 flex flex-col gap-4">
                    @csrf

                    <div>
                        <label class="mb-1 block text-sm font-medium text-ink">Nilai (0 - 100)</label>
                        <input type="number" name="skor" min="0" max="100" step="0.01"
                            value="{{ old('skor', $jawaban->skor) }}" required
                            class="w-full rounded-lg border border-line px-3 py-2 text-sm
                               focus:border-ink focus:outline-none">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-ink">Catatan (opsional)</label>
                        <textarea name="catatan_koreksi" rows="4"
                            class="w-full rounded-lg border border-line px-3 py-2 text-sm
                               focus:border-ink focus:outline-none">{{ old('catatan_koreksi', $jawaban->catatan_koreksi) }}</textarea>
                    </div>

                    <button type="submit"
                        class="self-start rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white
                           transition hover:bg-primaryDark">
                        Simpan Koreksi
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection
