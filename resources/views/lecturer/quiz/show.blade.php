@extends('lecturer.app-lecturer-create-materi')

@section('ketjudul')
Detail Quiz
@endsection

@section('judul')
{{ $quiz->judul }}
@endsection

@section('content')
<div class="bg-paper">
    <div class="mx-auto max-w-4xl px-6 py-8">

        <div class="mb-6">
            <a href="{{ route('lecturer.quiz.index', $quiz->pengajaran_dosen_id) }}"
                class="text-sm font-medium text-ink/50 hover:text-ink">
                &larr; Kembali ke daftar quiz
            </a>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
        @endif

        @if (session('error'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <p class="font-semibold">Import gagal:</p>
            <ul class="mt-1 list-inside list-disc space-y-0.5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Header Quiz --}}
        <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="font-display text-lg font-semibold text-ink">
                            {{ $quiz->judul }}
                        </h1>
                        @if ($quiz->is_published)
                        <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                            Published
                        </span>
                        @else
                        <span class="rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700">
                            Draft
                        </span>
                        @endif
                    </div>

                    @if ($quiz->deskripsi)
                    <p class="mt-2 text-sm text-ink/60">{{ $quiz->deskripsi }}</p>
                    @endif

                    <p class="mt-2 text-xs text-ink/50">
                        {{ $quiz->questions->count() }} soal
                        @if ($quiz->durasi_menit)
                        • Durasi {{ $quiz->durasi_menit }} menit
                        @endif
                    </p>
                </div>

                @if (!$quiz->is_published)
                <form method="POST" action="{{ route('lecturer.quiz.publish', $quiz) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white
                               transition hover:bg-primaryDark">
                        Publish Quiz
                    </button>
                </form>
                @endif
            </div>
        </div>

        {{-- Upload Soal --}}
        <div class="mt-6 rounded-2xl border border-line bg-white p-6 shadow-sm">
            <h2 class="font-display text-base font-semibold text-ink">
                Isi Soal via Template Excel
            </h2>
            <p class="mt-1 text-sm text-ink/50">
                1. Download template &rarr; 2. Isi pertanyaan, pilihan, dan kunci jawaban &rarr; 3. Upload kembali di
                sini.
                Upload ulang akan menggantikan seluruh soal yang sudah ada.
            </p>

            <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center">

                <a href="{{ route('lecturer.quiz.template', $quiz) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-line
                           px-4 py-2.5 text-sm font-semibold text-ink transition hover:bg-paper">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                    </svg>
                    Download Template
                </a>

                <form method="POST" action="{{ route('lecturer.quiz.import', $quiz) }}" enctype="multipart/form-data"
                    class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center">
                    @csrf

                    <input type="file" name="file" accept=".xlsx,.xls" required
                        class="block w-full text-sm text-ink/70
                               file:mr-3 file:rounded-lg file:border-0 file:bg-paper
                               file:px-4 file:py-2 file:text-sm file:font-semibold file:text-ink
                               hover:file:bg-line/30">

                    <button type="submit"
                        class="shrink-0 rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white
                               transition hover:bg-primaryDark">
                        Upload & Import
                    </button>
                </form>

            </div>
        </div>

        {{-- Preview Soal --}}
        <div class="mt-6 overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
            <div class="border-b border-line p-6">
                <h2 class="font-display text-base font-semibold text-ink">
                    Soal-soal Pilihan Ganda
                </h2>
                <p class="mt-1 text-sm text-ink/50">
                    Anda dapat menambahkan gambar untuk tiap soal
                </p>
            </div>

            <div class="divide-y divide-line">

                @forelse ($quiz->questions as $q)
                <div class="p-6">
                    <p class="text-sm font-semibold text-ink">
                        {{ $q->nomor }}. {{ $q->pertanyaan }}
                    </p>
                    <button type="button"
                        onclick="openEditSoalModal(@js([
            'id'         => $q->id,
            'nomor'      => $q->nomor,
            'pertanyaan' => $q->pertanyaan,
            'a'          => $q->pilihan_a,
            'b'          => $q->pilihan_b,
            'c'          => $q->pilihan_c,
            'd'          => $q->pilihan_d,
            'e'          => $q->pilihan_e,
            'kunci'      => $q->kunci_jawaban,
        ]))"
                        class="inline-flex shrink-0 items-center gap-1 rounded-lg border border-line px-3 py-1.5
               text-xs font-semibold text-ink transition hover:bg-paper">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z" />
                        </svg>
                        Edit Soal
                    </button>
                    {{-- Gambar soal --}}
                    <div class="mt-3">
                        @if ($q->gambar)
                        <img src="{{ asset('storage/' . $q->gambar) }}" alt="Gambar soal {{ $q->nomor }}"
                            class="mb-2 max-w-sm rounded-lg border border-line object-cover">
                        @endif

                        <form method="POST" action="{{ route('lecturer.quiz.question.gambar', $q) }}"
                            enctype="multipart/form-data" class="flex flex-wrap items-center gap-2">
                            @csrf
                            <input type="file" name="gambar" accept="image/*" required
                                class="block text-xs text-ink/70
                       file:mr-2 file:rounded-lg file:border-0 file:bg-paper
                       file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-ink
                       hover:file:bg-line/30">
                            <button type="submit"
                                class="rounded-lg border border-line px-3 py-1.5 text-xs font-semibold text-ink
                       transition hover:bg-paper">
                                {{ $q->gambar ? 'Ganti Gambar' : 'Upload Gambar' }}
                            </button>
                        </form>
                    </div>

                    {{-- Pilihan jawaban --}}
                    <div class="mt-3 space-y-1.5">
                        @foreach ($q->pilihanTersedia() as $huruf => $teks)
                        <div
                            class="flex items-center gap-2 text-sm
                {{ $huruf === $q->kunci_jawaban ? 'font-semibold text-green-700' : 'text-ink/70' }}">
                            <span
                                class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full
                     border text-xs
                     {{ $huruf === $q->kunci_jawaban ? 'border-green-600 bg-green-50' : 'border-line' }}">
                                {{ $huruf }}
                            </span>
                            {{ $teks }}
                            @if ($huruf === $q->kunci_jawaban)
                            <span class="text-xs text-green-700">(Kunci Jawaban)</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @empty
                <div class="p-6">
                    <div class="rounded-xl border border-dashed border-line bg-paper p-8 text-center">
                        <p class="text-sm text-ink/50">
                            Belum ada soal. Download template dan upload untuk menambahkan soal.
                        </p>
                    </div>
                </div>
                @endforelse

            </div>
        </div>

    </div>
</div>
{{-- MODAL EDIT SOAL --}}
<div id="modalEditSoal" class="fixed inset-0 z-50 flex hidden items-center justify-center p-4">

    <div class="absolute inset-0 bg-black/40" onclick="closeEditSoalModal()"></div>

    <div class="relative max-h-[90vh] w-full max-w-xl overflow-y-auto rounded-xl bg-white p-6 shadow-lg">

        <div class="mb-4 flex items-center justify-between">
            <h3 class="font-display text-lg font-semibold">
                Edit Soal <span id="editSoalNomor"></span>
            </h3>
            <button type="button" onclick="closeEditSoalModal()"
                class="text-xl leading-none text-ink/40 hover:text-ink/70">&times;</button>
        </div>

        <form id="formEditSoal" method="POST"
            data-action-template="{{ route('lecturer.quiz.question.update', ':id') }}">
            @csrf
            @method('PUT')

            {{-- Pertanyaan --}}
            <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-ink/70">Pertanyaan</label>
                <textarea id="editPertanyaan" name="pertanyaan" rows="3" required
                    class="w-full rounded-lg border border-line px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-teal/40"></textarea>
            </div>

            {{-- Pilihan --}}
            <div class="mb-1 flex items-center justify-between">
                <label class="text-sm font-medium text-ink/70">Pilihan Jawaban</label>
                <span class="text-xs text-ink/40">Pilih radio untuk menandai kunci jawaban</span>
            </div>

            <div class="mb-4 space-y-2">
                @foreach (['A', 'B', 'C', 'D', 'E'] as $h)
                <div class="flex items-center gap-2">
                    <input type="radio" name="kunci_jawaban" value="{{ $h }}"
                        id="editKunci{{ $h }}" required
                        class="h-4 w-4 shrink-0 accent-green-600">

                    <span class="w-4 shrink-0 text-sm font-semibold text-ink/70">{{ $h }}</span>

                    <input type="text" name="pilihan_{{ strtolower($h) }}"
                        id="editPilihan{{ $h }}" maxlength="255"
                        {{ in_array($h, ['A', 'B']) ? 'required' : '' }}
                        placeholder="{{ in_array($h, ['A', 'B']) ? 'Wajib diisi' : 'Opsional' }}"
                        class="w-full rounded-lg border border-line px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-teal/40">
                </div>
                @endforeach
            </div>

            <p class="mb-4 text-xs text-ink/40">
                Pilihan C, D, dan E boleh dikosongkan. Kunci jawaban harus menunjuk pilihan yang terisi.
            </p>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditSoalModal()"
                    class="rounded-lg border border-line px-4 py-2 text-sm font-medium text-ink/70 hover:bg-paper/60">
                    Batal
                </button>
                <button type="submit"
                    class="rounded-lg bg-ink px-4 py-2 text-sm font-semibold text-white transition hover:bg-primaryDark">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditSoalModal(d) {
        const form = document.getElementById('formEditSoal');
        form.action = form.dataset.actionTemplate.replace(':id', d.id);

        document.getElementById('editSoalNomor').textContent = 'No. ' + d.nomor;
        document.getElementById('editPertanyaan').value = d.pertanyaan ?? '';

        document.getElementById('editPilihanA').value = d.a ?? '';
        document.getElementById('editPilihanB').value = d.b ?? '';
        document.getElementById('editPilihanC').value = d.c ?? '';
        document.getElementById('editPilihanD').value = d.d ?? '';
        document.getElementById('editPilihanE').value = d.e ?? '';

        const radio = document.getElementById('editKunci' + d.kunci);
        if (radio) radio.checked = true;

        document.getElementById('modalEditSoal').classList.remove('hidden');
    }

    function closeEditSoalModal() {
        document.getElementById('modalEditSoal').classList.add('hidden');
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeEditSoalModal();
    });
</script>
@endsection