@extends('admin.app-admin')

@section('ketjudul')
    Selamat Datang di
@endsection

@section('judul')
    Matakuliah
@endsection

@section('content')

    {{-- =========================
        Header
    ========================== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">

        <div>
            <h2 class="font-display text-lg font-semibold">
                Daftar Matakuliah
            </h2>

            <p class="text-sm text-ink/50 mt-0.5">
                Kelola data matakuliah berdasarkan program studi.
            </p>
        </div>

        <button type="button" onclick="document.getElementById('modalTambah').classList.remove('hidden')"
            class="inline-flex items-center justify-center gap-2
                   text-sm font-medium px-4 py-2 rounded-lg
                   bg-teal text-white hover:bg-teal/90
                   transition-colors">
            <span class="text-lg leading-none">+</span>
            Tambah Matakuliah
        </button>

    </div>


    {{-- =========================
        Filter
    ========================== --}}
    <div class="border border-line rounded-xl p-4 mb-5 bg-white">

        <form action="" method="GET" class="flex flex-col sm:flex-row gap-3">

            {{-- Search --}}
            <div class="flex-1">

                <label class="block text-xs font-medium text-ink/50 mb-1">
                    Cari Matakuliah
                </label>

                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Kode atau nama matakuliah..."
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-teal/40">

            </div>


            {{-- Filter Prodi --}}
            <div class="sm:w-64">

                <label class="block text-xs font-medium text-ink/50 mb-1">
                    Program Studi
                </label>

                <select name="prodi_id"
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm
                           bg-white focus:outline-none focus:ring-2 focus:ring-teal/40">

                    <option value="">
                        Semua Program Studi
                    </option>

                    @foreach ($prodis as $prodi)
                        <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>
                            {{ $prodi->nama_prodi }}
                        </option>
                    @endforeach

                </select>

            </div>


            {{-- Tombol Filter --}}
            <div class="flex items-end gap-2">

                <button type="submit"
                    class="px-4 py-2 rounded-lg
                           bg-teal text-white text-sm font-medium
                           hover:bg-teal/90 transition-colors">
                    Cari
                </button>

                <a href="{{ route('matakuliah.index') }}"
                    class="px-4 py-2 rounded-lg
                           border border-line text-ink/70
                           text-sm font-medium
                           hover:bg-paper/60 transition-colors">
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- =========================
        Table Card
    ========================== --}}
    <div class="border border-line rounded-xl overflow-hidden bg-white">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead>
                    <tr class="border-b border-line text-left bg-paper/40">

                        <th
                            class="py-3 px-4 font-medium text-ink/50
                                   font-mono text-xs uppercase tracking-wide">
                            No
                        </th>

                        <th
                            class="py-3 px-4 font-medium text-ink/50
                                   font-mono text-xs uppercase tracking-wide">
                            Kode MK
                        </th>

                        <th
                            class="py-3 px-4 font-medium text-ink/50
                                   font-mono text-xs uppercase tracking-wide">
                            Nama Matakuliah
                        </th>

                        <th
                            class="py-3 px-4 font-medium text-ink/50
                                   font-mono text-xs uppercase tracking-wide">
                            Program Studi
                        </th>

                        <th
                            class="py-3 px-4 font-medium text-ink/50
                                   font-mono text-xs uppercase tracking-wide">
                            SKS
                        </th>

                        <th
                            class="py-3 px-4 font-medium text-ink/50
                                   font-mono text-xs uppercase tracking-wide">
                            Aksi
                        </th>

                    </tr>
                </thead>


                <tbody>

                    @forelse ($matakuliahs as $index => $mk)
                        <tr
                            class="border-b border-line last:border-0
                                   hover:bg-paper/60 transition-colors">

                            {{-- No --}}
                            <td class="py-3 px-4 font-mono text-xs text-ink/60">
                                {{ $matakuliahs->firstItem() + $index }}
                            </td>


                            {{-- Kode --}}
                            <td class="py-3 px-4 font-medium">
                                {{ $mk->kode_mk }}
                            </td>


                            {{-- Nama --}}
                            <td class="py-3 px-4 text-ink/70">
                                {{ $mk->nama_mk }}
                            </td>


                            {{-- Prodi --}}
                            <td class="py-3 px-4 text-ink/70">
                                {{ $mk->prodi->nama_prodi ?? '-' }}
                            </td>


                            {{-- SKS --}}
                            <td class="py-3 px-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-1
                                             rounded-md bg-teal/10 text-teal
                                             text-xs font-medium">
                                    {{ $mk->sks }} SKS
                                </span>
                            </td>


                            {{-- Aksi --}}
                            <td class="py-3 px-4">

                                <div class="flex items-center gap-2">

                                    {{-- Edit --}}
                                    <a href=""
                                        class="inline-flex items-center gap-1
                                               px-3 py-1.5 rounded-md
                                               text-xs font-medium
                                               bg-blue-500 text-white
                                               hover:bg-blue-600
                                               transition-colors">

                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 20h9" />
                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                        </svg>

                                        Edit

                                    </a>


                                    {{-- Delete --}}
                                    <form action="" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus matakuliah ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="inline-flex items-center gap-1
                                                   px-3 py-1.5 rounded-md
                                                   text-xs font-medium
                                                   bg-red-500 text-white
                                                   hover:bg-red-600
                                                   transition-colors">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18" />
                                                <path d="M8 6V4h8v2" />
                                                <path d="M19 6v14H5V6" />
                                                <path d="M10 11v6" />
                                                <path d="M14 11v6" />
                                            </svg>

                                            Hapus

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="py-10 text-center text-ink/40 text-sm">
                                Belum ada data matakuliah.

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if ($matakuliahs->hasPages())
            <div class="px-4 py-3 border-t border-line">
                {{ $matakuliahs->links() }}
            </div>
        @endif

    </div>


    {{-- =========================
        Modal Tambah Matakuliah
    ========================== --}}
    <div id="modalTambah" class="hidden fixed inset-0 z-50
               flex items-center justify-center p-4">

        {{-- Overlay --}}
        <div class="absolute inset-0 bg-black/40" onclick="document.getElementById('modalTambah').classList.add('hidden')">
        </div>


        {{-- Modal --}}
        <div class="relative bg-white w-full max-w-md
                   rounded-xl shadow-lg p-6">

            <div class="flex items-center justify-between mb-5">

                <h3 class="font-display text-lg font-semibold">
                    Tambah Matakuliah
                </h3>

                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')"
                    class="text-ink/40 hover:text-ink/70 text-xl leading-none">
                    &times;
                </button>

            </div>


            <form action="{{ route('admin.tambah.matkul') }}" method="POST">

                @csrf


                {{-- Kode MK --}}
                <div class="mb-4">

                    <label class="block text-sm font-medium text-ink/70 mb-1">
                        Kode Matakuliah
                    </label>

                    <input type="text" name="kode_mk" value="{{ old('kode_mk') }}" required maxlength="20"
                        placeholder="Contoh: TK101"
                        class="w-full border border-line rounded-lg px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-teal/40">

                    @error('kode_mk')
                        <p class="text-xs text-red-500 mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Nama MK --}}
                <div class="mb-4">

                    <label class="block text-sm font-medium text-ink/70 mb-1">
                        Nama Matakuliah
                    </label>

                    <input type="text" name="nama_mk" value="{{ old('nama_mk') }}" required
                        placeholder="Masukkan nama matakuliah"
                        class="w-full border border-line rounded-lg px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-teal/40">

                    @error('nama_mk')
                        <p class="text-xs text-red-500 mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Prodi --}}
                <div class="mb-4">

                    <label class="block text-sm font-medium text-ink/70 mb-1">
                        Program Studi
                    </label>

                    <select name="prodi_id" required
                        class="w-full border border-line rounded-lg px-3 py-2 text-sm
                               bg-white
                               focus:outline-none focus:ring-2 focus:ring-teal/40">

                        <option value="" disabled {{ old('prodi_id') ? '' : 'selected' }}>
                            Pilih Program Studi
                        </option>

                        @foreach ($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }}
                            </option>
                        @endforeach

                    </select>

                    @error('prodi_id')
                        <p class="text-xs text-red-500 mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- SKS --}}
                <div class="mb-5">

                    <label class="block text-sm font-medium text-ink/70 mb-1">
                        SKS
                    </label>

                    <input type="number" name="sks" value="{{ old('sks') }}" required min="1"
                        max="6" placeholder="Contoh: 3"
                        class="w-full border border-line rounded-lg px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-teal/40">

                    @error('sks')
                        <p class="text-xs text-red-500 mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Tombol --}}
                <div class="flex justify-end gap-2">

                    <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')"
                        class="px-4 py-2 text-sm font-medium rounded-lg
                               border border-line text-ink/70
                               hover:bg-paper/60">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium rounded-lg
                               bg-teal text-white hover:bg-teal/90">
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>
@endsection
