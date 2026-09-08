@extends('admin.app-admin')

@section('ketjudul')
Selamat Datang di
@endsection

@section('judul')
Akun Pengguna E-Learning
@endsection

@section('content')

<div class="flex justify-end gap-3 mb-4">

    <!-- Tombol Tambah Dosen -->
    <button
        type="button"
        onclick="document.getElementById('modalUser').classList.remove('hidden')"
        class="inline-flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-lg bg-teal text-white hover:bg-teal/90 transition-colors">

        + Tambah Dosen

    </button>


    <!-- Tombol Tambah Banyak Dosen -->
    <a
        href="{{ route('dosen.import') }}"
        class="inline-flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-lg bg-teal text-white hover:bg-teal/90 transition-colors">

        + Tambah Banyak Dosen

    </a>

</div>


<!-- Header Card -->
<div class="flex items-center justify-between mb-5">

    <div>

        <h2 class="font-display text-lg font-semibold">
            Daftar Akun Dosen
        </h2>

        <p class="text-sm text-ink/50 mt-0.5">
            ===========================
        </p>

    </div>

    <a
        href="#"
        class="text-sm font-medium text-teal hover:underline">

        Lihat Semua

    </a>

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
                    NIDN
                </th>

                <th class="py-3 pr-4 font-medium text-ink/50 font-mono text-xs uppercase tracking-wide">
                    Nama Dosen
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

            @forelse ($akundosen as $mk)

            <tr class="border-b border-line last:border-0 hover:bg-paper/60 transition-colors">

                {{-- No --}}
                <td class="py-3 pr-4 font-mono text-xs text-ink/60">

                    {{ $loop->iteration }}

                </td>


                {{-- NIDN --}}
                <td class="py-3 pr-4 font-medium">

                    {{ $mk->nidn }}

                </td>


                {{-- Nama --}}
                <td class="py-3 pr-4 text-ink/70">

                    {{ $mk->user->name }}

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


                        {{-- BUTTON EDIT --}}
                        <button
                            type="button"
                            onclick="openEditModal({{ $mk->id }})"
                            class="relative z-20 inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium bg-blue-500 text-white hover:bg-blue-600 transition-colors cursor-pointer">

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


                        {{-- BUTTON DELETE --}}
                        <form
                            action="{{ route('admin.dosen.destroy', $mk->id) }}"
                            method="POST"
                            class="inline-block relative z-20"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $mk->user->name }}?')">

                            @csrf

                            @method('DELETE')


                            <button
                                type="submit"
                                class="relative z-20 inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium bg-red-500 text-white hover:bg-red-600 transition-colors cursor-pointer">

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
                    colspan="6"
                    class="py-6 text-center text-ink/40 text-sm">

                    Belum ada data akun dosen.

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>



{{-- =========================================================
     MODAL TAMBAH DOSEN
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
                Tambah Akun Dosen
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
            action="{{ route('admin.dosen.buatAkun') }}"
            method="POST">

            @csrf


            {{-- NIDN --}}
            <div class="mb-4">

                <label
                    class="block text-sm font-medium text-ink/70 mb-1">

                    NIDN

                </label>

                <input
                    id="inputNidn"
                    type="text"
                    required
                    name="nidn"
                    value="{{ old('nidn') }}"
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40"
                    placeholder="Masukkan Nomor Induk Dosen">

            </div>


            {{-- Nama --}}
            <div class="mb-4">

                <label
                    class="block text-sm font-medium text-ink/70 mb-1">

                    Nama Lengkap

                </label>

                <input
                    id="inputNama"
                    type="text"
                    required
                    name="name"
                    value="{{ old('name') }}"
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40"
                    placeholder="Masukkan nama lengkap">

            </div>


            {{-- Email --}}
            <div class="mb-4">

                <label
                    class="block text-sm font-medium text-ink/70 mb-1">

                    Email

                </label>

                <input
                    type="email"
                    required
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40"
                    placeholder="nama@email.com">

            </div>


            {{-- Phone --}}
            <div class="mb-4">

                <label
                    class="block text-sm font-medium text-ink/70 mb-1">

                    No. HP

                </label>

                <input
                    id="inputPhone"
                    type="text"
                    name="phone"
                    value="{{ old('phone') }}"
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40"
                    placeholder="Masukkan No. HP">

            </div>


            {{-- Program Studi --}}
            <div class="mb-4">

                <label
                    class="block text-sm font-medium text-ink/70 mb-1">

                    Program Studi

                </label>


                <select
                    name="prodi_id"
                    required
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-teal/40">

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


            {{-- Button --}}
            <div class="flex justify-end gap-2">

                <button
                    type="button"
                    onclick="document.getElementById('modalUser').classList.add('hidden')"
                    class="px-4 py-2 text-sm font-medium rounded-lg border border-line text-ink/70 hover:bg-paper/60">

                    Batal

                </button>


                <button
                    type="submit"
                    class="px-4 py-2 text-sm font-medium rounded-lg bg-teal text-white hover:bg-teal/90">

                    Simpan

                </button>

            </div>

        </form>

    </div>

</div>



{{-- =========================================================
     MODAL EDIT DOSEN
========================================================= --}}

<div
    id="modalEditUser"
    class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">


    {{-- Overlay --}}
    <div
        class="absolute inset-0 bg-black/40"
        onclick="closeEditModal()">
    </div>


    {{-- Konten Modal --}}
    <div class="relative bg-white w-full max-w-md rounded-xl shadow-lg p-6">


        {{-- Header --}}
        <div class="flex items-center justify-between mb-4">

            <h3 class="font-display text-lg font-semibold">

                Edit Akun Dosen

            </h3>


            <button
                type="button"
                onclick="closeEditModal()"
                class="text-ink/40 hover:text-ink/70 text-xl leading-none">

                &times;

            </button>

        </div>


        {{-- Form --}}
        <form
            id="formEditUser"
            method="POST">

            @csrf

            @method('PUT')


            {{-- NIDN --}}
            <div class="mb-4">

                <label
                    class="block text-sm font-medium text-ink/70 mb-1">

                    NIDN

                </label>


                <input
                    id="editNidn"
                    type="text"
                    name="nidn"
                    required
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">

            </div>


            {{-- Nama --}}
            <div class="mb-4">

                <label
                    class="block text-sm font-medium text-ink/70 mb-1">

                    Nama Lengkap

                </label>


                <input
                    id="editNama"
                    type="text"
                    name="name"
                    required
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">

            </div>


            {{-- Email --}}
            <div class="mb-4">

                <label
                    class="block text-sm font-medium text-ink/70 mb-1">

                    Email

                </label>


                <input
                    id="editEmail"
                    type="email"
                    name="email"
                    required
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">

            </div>


            {{-- Program Studi --}}
            <div class="mb-4">

                <label
                    class="block text-sm font-medium text-ink/70 mb-1">

                    Program Studi

                </label>


                <select
                    id="editProdi"
                    name="prodi_id"
                    required
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-teal/40">

                    <option
                        value=""
                        disabled>

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

                <label
                    class="block text-sm font-medium text-ink/70 mb-1">

                    Nomor Telepon

                </label>


                <input
                    id="editPhone"
                    type="text"
                    name="phone"
                    class="w-full border border-line rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40"
                    placeholder="Masukkan nomor telepon">

            </div>


            {{-- Button --}}
            <div class="flex justify-end gap-2">

                <button
                    type="button"
                    onclick="closeEditModal()"
                    class="px-4 py-2 text-sm font-medium rounded-lg border border-line text-ink/70 hover:bg-paper/60">

                    Batal

                </button>


                <button
                    type="submit"
                    class="px-4 py-2 text-sm font-medium rounded-lg bg-teal text-white hover:bg-teal/90">

                    Simpan Perubahan

                </button>

            </div>

        </form>

    </div>

</div>



<script>
    /*
    |--------------------------------------------------------------------------
    | OPEN EDIT MODAL
    |--------------------------------------------------------------------------
    */

    function openEditModal(id) {

        fetch(`/admin/akun-dosen/${id}`)

            .then(response => {

                if (!response.ok) {

                    throw new Error(
                        'Data dosen tidak ditemukan'
                    );

                }

                return response.json();

            })

            .then(data => {

                /*
                |--------------------------------------------------------------------------
                | Isi data ke form edit
                |--------------------------------------------------------------------------
                */

                document.getElementById('editNidn').value =
                    data.nidn ?? '';

                document.getElementById('editNama').value =
                    data.name ?? '';

                document.getElementById('editEmail').value =
                    data.email ?? '';

                document.getElementById('editProdi').value =
                    data.prodi_id ?? '';

                document.getElementById('editPhone').value =
                    data.phone ?? '';


                /*
                |--------------------------------------------------------------------------
                | Action form
                |--------------------------------------------------------------------------
                */

                document.getElementById('formEditUser').action =
                    `/admin/akun-dosen/${data.id}`;


                /*
                |--------------------------------------------------------------------------
                | Tampilkan modal
                |--------------------------------------------------------------------------
                */

                document.getElementById('modalEditUser')
                    .classList.remove('hidden');

            })


            .catch(error => {

                console.error(error);

                alert(
                    'Gagal mengambil data dosen.'
                );

            });

    }



    /*
    |--------------------------------------------------------------------------
    | CLOSE EDIT MODAL
    |--------------------------------------------------------------------------
    */

    function closeEditModal() {

        document.getElementById('modalEditUser')
            .classList.add('hidden');

    }
</script>

@endsection