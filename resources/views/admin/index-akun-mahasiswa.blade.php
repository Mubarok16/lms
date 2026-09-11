@extends('admin.app-admin')

@section('ketjudul')
Selamat Datang di
@endsection

@section('judul')
Akun Mahasiswa
@endsection

@section('content')

<div class="flex items-center justify-between gap-3 mb-4 flex-wrap">

    <!-- Form Search -->
    <form
        action="{{ route('akun_mahasiswa.index') }}"
        method="GET"
        class="flex items-center gap-2">

        <div class="relative">

            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="absolute left-3 top-1/2 -translate-y-1/2 text-ink/40">

                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.3-4.3" />

            </svg>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari NPM, nama, atau email..."
                class="w-64 border border-line rounded-lg pl-9 pr-3 py-2 text-sm
                focus:outline-none focus:ring-2 focus:ring-teal/40">

        </div>

        <button
            type="submit"
            class="inline-flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-lg
            bg-teal text-white hover:bg-teal/90 transition-colors">

            Cari

        </button>

        @if (request('search'))
        <a
            href="{{ route('akun_mahasiswa.index') }}"
            class="text-sm font-medium text-ink/50 hover:text-ink/80 px-2">

            Reset

        </a>
        @endif

    </form>


    <div class="flex gap-3">

        <!-- Tombol Tambah Mahasiswa -->
        <button
            type="button"
            onclick="document.getElementById('modalUser').classList.remove('hidden')"
            class="inline-flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-lg bg-teal text-white hover:bg-teal/90 transition-colors">

            + Tambah Mahasiswa

        </button>


        <!-- Tombol Tambah Banyak Mahasiswa -->
        <a
            href="{{ route('mahasiswa.import') }}"
            class="inline-flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-lg bg-teal text-white hover:bg-teal/90 transition-colors">

            + Tambah Banyak Mahasiswa

        </a>

    </div>

</div>



{{-- =========================================================
     MODAL EDIT MAHASISWA
========================================================= --}}

<div
    id="modalEditMahasiswa"
    class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">

    {{-- Overlay --}}
    <div
        class="absolute inset-0 bg-black/40"
        onclick="closeEditMahasiswaModal()">
    </div>


    {{-- Modal --}}
    <div class="relative bg-white w-full max-w-md rounded-xl shadow-lg p-6">


        {{-- Header --}}
        <div class="flex items-center justify-between mb-4">

            <h3 class="font-display text-lg font-semibold">
                Edit Akun Mahasiswa
            </h3>


            <button
                type="button"
                onclick="closeEditMahasiswaModal()"
                class="text-ink/40 hover:text-ink/70 text-xl leading-none">

                &times;

            </button>

        </div>


        {{-- Form --}}
        <form
            id="formEditMahasiswa"
            method="POST">

            @csrf
            @method('PUT')


            {{-- NPM --}}
            <div class="mb-4">

                <label class="block text-sm font-medium text-ink/70 mb-1">
                    NPM
                </label>

                <input
                    id="editNim"
                    type="text"
                    name="nim"
                    required
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm
                    focus:outline-none focus:ring-2 focus:ring-teal/40">

            </div>


            {{-- Nama --}}
            <div class="mb-4">

                <label class="block text-sm font-medium text-ink/70 mb-1">
                    Nama Lengkap
                </label>

                <input
                    id="editNamaMahasiswa"
                    type="text"
                    name="name"
                    required
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm
                    focus:outline-none focus:ring-2 focus:ring-teal/40">

            </div>


            {{-- Email --}}
            <div class="mb-4">

                <label class="block text-sm font-medium text-ink/70 mb-1">
                    Email
                </label>

                <input
                    id="editEmailMahasiswa"
                    type="email"
                    name="email"
                    required
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm
                    focus:outline-none focus:ring-2 focus:ring-teal/40">

            </div>


            {{-- Angkatan --}}
            <div class="mb-4">

                <label class="block text-sm font-medium text-ink/70 mb-1">
                    Angkatan
                </label>

                <input
                    id="editAngkatan"
                    type="text"
                    name="angkatan"
                    required
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm
                    focus:outline-none focus:ring-2 focus:ring-teal/40">

            </div>


            {{-- Program Studi --}}
            <div class="mb-4">

                <label class="block text-sm font-medium text-ink/70 mb-1">
                    Program Studi
                </label>

                <select
                    id="editProdiMahasiswa"
                    name="prodi_id"
                    required
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm
                    bg-white focus:outline-none focus:ring-2 focus:ring-teal/40">

                    <option value="" disabled>
                        Pilih Program Studi
                    </option>


                    @foreach ($prodi as $item)

                    <option value="{{ $item->id }}">
                        {{ $item->nama_prodi }}
                    </option>

                    @endforeach

                </select>

            </div>


            {{-- Phone --}}
            <div class="mb-4">

                <label class="block text-sm font-medium text-ink/70 mb-1">
                    Nomor Telepon
                </label>

                <input
                    id="editPhoneMahasiswa"
                    type="text"
                    name="phone"
                    value="{{ old('phone') }}"
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm
                    focus:outline-none focus:ring-2 focus:ring-teal/40"
                    placeholder="Masukkan nomor telepon">

            </div>


            {{-- Button --}}
            <div class="flex justify-end gap-2">

                <button
                    type="button"
                    onclick="closeEditMahasiswaModal()"
                    class="px-4 py-2 text-sm font-medium rounded-lg
                    border border-line text-ink/70 hover:bg-paper/60">

                    Batal

                </button>


                <button
                    type="submit"
                    class="px-4 py-2 text-sm font-medium rounded-lg
                    bg-teal text-white hover:bg-teal/90">

                    Simpan Perubahan

                </button>

            </div>

        </form>

    </div>

</div>



<!-- Header Card -->

<div class="flex items-center justify-between mb-5">

    <div>

        <h2 class="font-display text-lg font-semibold">
            Daftar Akun Mahasiswa
        </h2>

        @if (request('search'))
        <p class="text-xs text-ink/50 mt-1">
            Hasil pencarian untuk: "{{ request('search') }}" ({{ $akunmahasiswa->total() }} data)
        </p>
        @endif

    </div>

</div>



<!-- Tabel -->

<div class="overflow-x-auto">

    <table class="w-full text-sm">

        <thead>

            <tr class="border-b border-line text-left">

                <th class="py-3 pr-4 font-medium text-ink/50 font-mono text-xs uppercase tracking-wide">
                    No
                </th>

                <th class="py-3 pr-4 font-medium text-ink/50 font-mono text-xs uppercase tracking-wide">
                    NPM
                </th>

                <th class="py-3 pr-4 font-medium text-ink/50 font-mono text-xs uppercase tracking-wide">
                    Nama Mahasiswa
                </th>

                <th class="py-3 pr-4 font-medium text-ink/50 font-mono text-xs uppercase tracking-wide">
                    Prodi
                </th>

                <th class="py-3 pr-4 font-medium text-ink/50 font-mono text-xs uppercase tracking-wide">
                    Angkatan
                </th>

                <th class="py-3 pr-4 font-medium text-ink/50 font-mono text-xs uppercase tracking-wide">
                    Email
                </th>

                <th class="py-3 pr-4 font-medium text-ink/50 font-mono text-xs uppercase tracking-wide">
                    No. HP
                </th>

                <th class="py-3 pr-4 font-medium text-ink/50 font-mono text-xs uppercase tracking-wide">
                    Aksi
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse ($akunmahasiswa as $mk)

            <tr class="border-b border-line last:border-0 hover:bg-paper/60 transition-colors">


                {{-- No (menyesuaikan nomor urut lintas halaman) --}}
                <td class="py-3 pr-4 font-mono text-xs text-ink/60">

                    {{ ($akunmahasiswa->currentPage() - 1) * $akunmahasiswa->perPage() + $loop->iteration }}

                </td>


                {{-- NPM --}}
                <td class="py-3 pr-4 font-medium">

                    {{ $mk->nim }}

                </td>


                {{-- Nama --}}
                <td class="py-3 pr-4 text-ink/70">

                    {{ $mk->user->name }}

                </td>


                {{-- Prodi --}}
                <td class="py-3 pr-4 text-ink/70">

                    {{ $mk->prodi->nama_prodi }}

                </td>


                {{-- Angkatan --}}
                <td class="py-3 pr-4 text-ink/70">

                    {{ $mk->angkatan }}

                </td>


                {{-- Email --}}
                <td class="py-3 pr-4 text-ink/70">

                    {{ $mk->user->email }}

                </td>


                {{-- Phone --}}
                <td class="py-3 pr-4 text-ink/70">

                    {{ $mk->phone ?? '-' }}

                </td>


                {{-- Aksi --}}
                <td class="py-3 pr-4">

                    <div class="relative z-10 flex items-center gap-2">


                        {{-- EDIT --}}
                        <button
                            type="button"
                            onclick="openEditMahasiswaModal(
                                {{ $mk->id }},
                                @js($mk->nim),
                                @js($mk->user->name),
                                @js($mk->user->email),
                                {{ $mk->prodi_id }},
                                @js($mk->angkatan),
                                @js($mk->phone)
                            )"
                            class="relative z-20 inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium
                            bg-blue-500 text-white hover:bg-blue-600 transition-colors cursor-pointer">


                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="14"
                                height="14"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">

                                <path d="M12 20h9" />

                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z" />

                            </svg>


                            Edit

                        </button>



                        {{-- DELETE --}}
                        <form
                            action="{{ route('admin.mahasiswa.destroy', $mk->id) }}"
                            method="POST"
                            class="inline-block relative z-20"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $mk->user->name }}?')">

                            @csrf

                            @method('DELETE')


                            <button
                                type="submit"
                                class="relative z-20 inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium
                                bg-red-500 text-white hover:bg-red-600 transition-colors cursor-pointer">


                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="14"
                                    height="14"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round">

                                    <path d="M3 6h18" />

                                    <path d="M8 6V4h8v2" />

                                    <path d="M19 6v14H5V6" />

                                    <path d="M10 11v6" />

                                    <path d="M14 11v6" />

                                </svg>


                                Delete

                            </button>

                        </form>

                    </div>

                </td>

            </tr>


            @empty

            <tr>

                <td
                    colspan="8"
                    class="py-6 text-center text-ink/40 text-sm">

                    @if (request('search'))
                    Tidak ditemukan data untuk pencarian "{{ request('search') }}".
                    @else
                    Belum ada data akun mahasiswa.
                    @endif

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>


<!-- Pagination -->
<div class="mt-5">

    {{ $akunmahasiswa->appends(request()->query())->links() }}

</div>



{{-- =========================================================
     MODAL TAMBAH MAHASISWA
========================================================= --}}

<div
    id="modalUser"
    class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">


    {{-- Overlay --}}
    <div
        class="absolute inset-0 bg-black/40"
        onclick="document.getElementById('modalUser').classList.add('hidden')">
    </div>


    {{-- Konten Modal --}}
    <div class="relative bg-white w-full max-w-md rounded-xl shadow-lg p-6">


        {{-- Header --}}
        <div class="flex items-center justify-between mb-4">

            <h3 class="font-display text-lg font-semibold">
                Tambah Akun Mahasiswa
            </h3>


            <button
                type="button"
                onclick="document.getElementById('modalUser').classList.add('hidden')"
                class="text-ink/40 hover:text-ink/70 text-xl leading-none">

                &times;

            </button>

        </div>


        {{-- Form --}}
        <form
            action="{{ route('admin.mahasiswa.buatAkun') }}"
            method="POST">

            @csrf


            {{-- NPM --}}
            <div class="mb-4">

                <label class="block text-sm font-medium text-ink/70 mb-1">
                    NPM
                </label>

                <input
                    id="inputNim"
                    name="nim"
                    type="text"
                    required
                    value="{{ old('nim') }}"
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm
                    focus:outline-none focus:ring-2 focus:ring-teal/40"
                    placeholder="Masukkan Nomor Induk Mahasiswa">

            </div>


            {{-- Nama --}}
            <div class="mb-4">

                <label class="block text-sm font-medium text-ink/70 mb-1">
                    Nama Lengkap
                </label>

                <input
                    id="inputNama"
                    name="name"
                    type="text"
                    required
                    value="{{ old('name') }}"
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm
                    focus:outline-none focus:ring-2 focus:ring-teal/40"
                    placeholder="Masukkan nama lengkap">

            </div>


            {{-- Email --}}
            <div class="mb-4">

                <label class="block text-sm font-medium text-ink/70 mb-1">
                    Email
                </label>

                <input
                    id="inputEmail"
                    name="email"
                    type="email"
                    required
                    value="{{ old('email') }}"
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm
                    focus:outline-none focus:ring-2 focus:ring-teal/40"
                    placeholder="nama@email.com">

            </div>


            {{-- Angkatan --}}
            <div class="mb-4">

                <label class="block text-sm font-medium text-ink/70 mb-1">
                    Angkatan
                </label>

                <input
                    id="inputAngkatan"
                    name="angkatan"
                    type="number"
                    required
                    value="{{ old('angkatan') }}"
                    min="2000"
                    max="2100"
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm
                    focus:outline-none focus:ring-2 focus:ring-teal/40"
                    placeholder="Contoh: 2022">

            </div>


            {{-- Program Studi --}}
            <div class="mb-4">

                <label class="block text-sm font-medium text-ink/70 mb-1">
                    Program Studi
                </label>


                <select
                    name="prodi_id"
                    required
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm
                    bg-white focus:outline-none focus:ring-2 focus:ring-teal/40">


                    <option
                        value=""
                        disabled
                        {{ old('prodi_id') ? '' : 'selected' }}>

                        Pilih Program Studi

                    </option>


                    @foreach ($prodi as $item)

                    <option
                        value="{{ $item->id }}"
                        {{ old('prodi_id') == $item->id ? 'selected' : '' }}>

                        {{ $item->nama_prodi }}

                    </option>

                    @endforeach

                </select>


                @error('prodi_id')

                <p class="mt-1 text-xs text-red-500">

                    {{ $message }}

                </p>

                @enderror

            </div>


            {{-- Phone --}}
            <div class="mb-4">

                <label class="block text-sm font-medium text-ink/70 mb-1">
                    No. HP
                </label>


                <input
                    id="inputPhone"
                    name="phone"
                    type="text"
                    value="{{ old('phone') }}"
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm
                    focus:outline-none focus:ring-2 focus:ring-teal/40"
                    placeholder="Contoh: 081234567890">


            </div>


            {{-- Button --}}
            <div class="flex justify-end gap-2">

                <button
                    type="button"
                    onclick="document.getElementById('modalUser').classList.add('hidden')"
                    class="px-4 py-2 text-sm font-medium rounded-lg
                    border border-line text-ink/70 hover:bg-paper/60">

                    Batal

                </button>


                <button
                    type="submit"
                    class="px-4 py-2 text-sm font-medium rounded-lg
                    bg-teal text-white hover:bg-teal/90">

                    Simpan

                </button>

            </div>

        </form>

    </div>

</div>



<script>
    /*
    |--------------------------------------------------------------------------
    | OPEN EDIT MAHASISWA MODAL
    |--------------------------------------------------------------------------
    */

    function openEditMahasiswaModal(
        id,
        nim,
        nama,
        email,
        prodiId,
        angkatan,
        phone
    ) {


        // Action form
        document.getElementById('formEditMahasiswa').action =
            `/admin/akun-mahasiswa/${id}`;


        // Isi NPM
        document.getElementById('editNim').value =
            nim ?? '';


        // Isi Nama
        document.getElementById('editNamaMahasiswa').value =
            nama ?? '';


        // Isi Email
        document.getElementById('editEmailMahasiswa').value =
            email ?? '';


        // Isi Program Studi
        document.getElementById('editProdiMahasiswa').value =
            prodiId ?? '';


        // Isi Angkatan
        document.getElementById('editAngkatan').value =
            angkatan ?? '';


        // Isi Phone
        document.getElementById('editPhoneMahasiswa').value =
            phone ?? '';


        // Tampilkan modal
        document.getElementById('modalEditMahasiswa')
            .classList.remove('hidden');

    }



    /*
    |--------------------------------------------------------------------------
    | CLOSE EDIT MAHASISWA MODAL
    |--------------------------------------------------------------------------
    */

    function closeEditMahasiswaModal() {

        document.getElementById('modalEditMahasiswa')
            .classList.add('hidden');

    }
</script>

@endsection