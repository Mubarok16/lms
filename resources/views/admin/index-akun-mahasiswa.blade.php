@extends('admin.app-admin')

@section('ketjudul')
Selamat Datang di
@endsection

@section('judul')
Akun Mahasiswa
@endsection

@section('content')

@if (session('success'))
<div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
    {{ session('success') }}
</div>
@endif

@if ($errors->any())
<div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
    <p class="font-semibold">Data belum dapat disimpan.</p>
    <ul class="mt-1 list-disc pl-5">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <form action="{{ route('akun_mahasiswa.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 -translate-y-1/2 text-ink/40">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.3-4.3" />
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NPM, nama, email, prodi..." class="w-72 rounded-lg border border-line py-2 pl-9 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">
        </div>
        <button type="submit" class="rounded-lg bg-teal px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-teal/90">Cari</button>
        @if (request('search'))
        <a href="{{ route('akun_mahasiswa.index') }}" class="px-2 text-sm font-medium text-ink/50 hover:text-ink/80">Reset</a>
        @endif
    </form>

    <div class="flex flex-wrap gap-3">
        <button type="button" onclick="openCreateMahasiswa()" class="inline-flex items-center gap-2 rounded-lg bg-teal px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-teal/90">
            + Tambah Mahasiswa
        </button>
        <a href="{{ route('mahasiswa.import') }}" class="inline-flex items-center gap-2 rounded-lg bg-teal px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-teal/90">
            + Tambah Banyak Mahasiswa
        </a>
    </div>
</div>

<div class="mb-5 flex items-center justify-between">
    <div>
        <h2 class="font-display text-lg font-semibold">Daftar Akun Mahasiswa</h2>
        <p class="mt-1 text-xs text-ink/50">
            @if (request('search'))
                Hasil pencarian "{{ request('search') }}" · {{ $akunmahasiswa->total() }} data
            @else
                Total {{ $akunmahasiswa->total() }} akun mahasiswa
            @endif
        </p>
    </div>
</div>

<div class="overflow-x-auto rounded-xl border border-line bg-white">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-line bg-paper/50 text-left">
                <th class="px-4 py-3 font-mono text-xs font-medium uppercase tracking-wide text-ink/50">No</th>
                <th class="px-4 py-3 font-mono text-xs font-medium uppercase tracking-wide text-ink/50">NPM</th>
                <th class="px-4 py-3 font-mono text-xs font-medium uppercase tracking-wide text-ink/50">Nama Mahasiswa</th>
                <th class="px-4 py-3 font-mono text-xs font-medium uppercase tracking-wide text-ink/50">Prodi</th>
                <th class="px-4 py-3 font-mono text-xs font-medium uppercase tracking-wide text-ink/50">Angkatan</th>
                <th class="px-4 py-3 font-mono text-xs font-medium uppercase tracking-wide text-ink/50">Email</th>
                <th class="px-4 py-3 font-mono text-xs font-medium uppercase tracking-wide text-ink/50">No. HP</th>
                <th class="px-4 py-3 font-mono text-xs font-medium uppercase tracking-wide text-ink/50">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($akunmahasiswa as $mahasiswa)
            <tr class="border-b border-line last:border-0 hover:bg-paper/60">
                <td class="px-4 py-3 font-mono text-xs text-ink/60">{{ ($akunmahasiswa->firstItem() ?? 1) + $loop->index }}</td>
                <td class="px-4 py-3 font-medium">{{ $mahasiswa->nim }}</td>
                <td class="px-4 py-3 text-ink/70">{{ $mahasiswa->user?->name ?? '-' }}</td>
                <td class="px-4 py-3 text-ink/70">{{ $mahasiswa->prodi?->nama_prodi ?? '-' }}</td>
                <td class="px-4 py-3 text-ink/70">{{ $mahasiswa->angkatan }}</td>
                <td class="px-4 py-3 text-ink/70">{{ $mahasiswa->user?->email ?? '-' }}</td>
                <td class="px-4 py-3 text-ink/70">{{ $mahasiswa->phone ?: '-' }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="openEditMahasiswa({{ $mahasiswa->id }})" class="inline-flex items-center gap-1 rounded-md bg-blue-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-600">
                            Edit
                        </button>
                        <form action="{{ route('admin.mahasiswa.destroy', $mahasiswa->id) }}" method="POST" onsubmit="return confirmDelete('mahasiswa')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1 rounded-md bg-red-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-600">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-4 py-8 text-center text-sm text-ink/40">
                    @if (request('search'))
                        Tidak ditemukan data mahasiswa untuk pencarian "{{ request('search') }}".
                    @else
                        Belum ada data akun mahasiswa.
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-5">
    {{ $akunmahasiswa->links() }}
</div>

{{-- MODAL TAMBAH MAHASISWA --}}
<div id="modalUser" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40" onclick="closeCreateMahasiswa()"></div>
    <div class="relative max-h-[90vh] w-full max-w-md overflow-y-auto rounded-xl bg-white p-6 shadow-lg">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="font-display text-lg font-semibold">Tambah Akun Mahasiswa</h3>
            <button type="button" onclick="closeCreateMahasiswa()" class="text-xl leading-none text-ink/40 hover:text-ink/70">&times;</button>
        </div>

        <form action="{{ route('admin.mahasiswa.buatAkun') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-ink/70">NPM</label>
                <input type="text" name="nim" required value="{{ old('nim') }}" class="w-full rounded-lg border border-line px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">
            </div>
            <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-ink/70">Nama Lengkap</label>
                <input type="text" name="name" required value="{{ old('name') }}" class="w-full rounded-lg border border-line px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">
            </div>
            <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-ink/70">Email</label>
                <input type="email" name="email" required value="{{ old('email') }}" class="w-full rounded-lg border border-line px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">
            </div>
            <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-ink/70">Angkatan</label>
                <input type="number" name="angkatan" required min="2000" max="2100" value="{{ old('angkatan') }}" class="w-full rounded-lg border border-line px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">
            </div>
            <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-ink/70">Program Studi</label>
                <select name="prodi_id" required class="w-full rounded-lg border border-line bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">
                    <option value="" disabled {{ old('prodi_id') ? '' : 'selected' }}>Pilih Program Studi</option>
                    @foreach ($prodi as $item)
                    <option value="{{ $item->id }}" {{ (string) old('prodi_id') === (string) $item->id ? 'selected' : '' }}>{{ $item->nama_prodi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-ink/70">Nomor Telepon</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-lg border border-line px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeCreateMahasiswa()" class="rounded-lg border border-line px-4 py-2 text-sm font-medium text-ink/70 hover:bg-paper/60">Batal</button>
                <button type="submit" class="rounded-lg bg-teal px-4 py-2 text-sm font-medium text-white hover:bg-teal/90">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT MAHASISWA --}}
<div id="modalEditMahasiswa" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40" onclick="closeEditMahasiswa()"></div>
    <div class="relative max-h-[90vh] w-full max-w-md overflow-y-auto rounded-xl bg-white p-6 shadow-lg">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="font-display text-lg font-semibold">Edit Akun Mahasiswa</h3>
            <button type="button" onclick="closeEditMahasiswa()" class="text-xl leading-none text-ink/40 hover:text-ink/70">&times;</button>
        </div>

        <form id="formEditMahasiswa" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-ink/70">NPM</label>
                <input id="editMahasiswaNim" type="text" name="nim" required class="w-full rounded-lg border border-line px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">
            </div>
            <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-ink/70">Nama Lengkap</label>
                <input id="editMahasiswaName" type="text" name="name" required class="w-full rounded-lg border border-line px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">
            </div>
            <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-ink/70">Email</label>
                <input id="editMahasiswaEmail" type="email" name="email" required class="w-full rounded-lg border border-line px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">
            </div>
            <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-ink/70">Angkatan</label>
                <input id="editMahasiswaAngkatan" type="number" name="angkatan" required min="2000" max="2100" class="w-full rounded-lg border border-line px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">
            </div>
            <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-ink/70">Program Studi</label>
                <select id="editMahasiswaProdi" name="prodi_id" required class="w-full rounded-lg border border-line bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">
                    <option value="" disabled>Pilih Program Studi</option>
                    @foreach ($prodi as $item)
                    <option value="{{ $item->id }}">{{ $item->nama_prodi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-ink/70">Nomor Telepon</label>
                <input id="editMahasiswaPhone" type="text" name="phone" class="w-full rounded-lg border border-line px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditMahasiswa()" class="rounded-lg border border-line px-4 py-2 text-sm font-medium text-ink/70 hover:bg-paper/60">Batal</button>
                <button type="submit" class="rounded-lg bg-teal px-4 py-2 text-sm font-medium text-white hover:bg-teal/90">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@php
    $mahasiswaRowsForEdit = $akunmahasiswa->getCollection()->mapWithKeys(function ($mahasiswa) {
        return [
            (string) $mahasiswa->id => [
                'id' => $mahasiswa->id,
                'nim' => $mahasiswa->nim,
                'name' => optional($mahasiswa->user)->name,
                'email' => optional($mahasiswa->user)->email,
                'prodi_id' => $mahasiswa->prodi_id,
                'angkatan' => $mahasiswa->angkatan,
                'phone' => $mahasiswa->phone,
            ],
        ];
    })->all();
@endphp

<script>
    const mahasiswaRows = @json($mahasiswaRowsForEdit);
    const mahasiswaUpdateUrl = @json(route('admin.mahasiswa.update', ['id' => '__ID__']));

    function openCreateMahasiswa() {
        document.getElementById('modalUser').classList.remove('hidden');
    }

    function closeCreateMahasiswa() {
        document.getElementById('modalUser').classList.add('hidden');
    }

    function openEditMahasiswa(id) {
        const data = mahasiswaRows[id];
        if (!data) {
            alert('Data mahasiswa tidak ditemukan pada halaman ini.');
            return;
        }

        document.getElementById('formEditMahasiswa').action = mahasiswaUpdateUrl.replace('__ID__', id);
        document.getElementById('editMahasiswaNim').value = data.nim ?? '';
        document.getElementById('editMahasiswaName').value = data.name ?? '';
        document.getElementById('editMahasiswaEmail').value = data.email ?? '';
        document.getElementById('editMahasiswaProdi').value = data.prodi_id ?? '';
        document.getElementById('editMahasiswaAngkatan').value = data.angkatan ?? '';
        document.getElementById('editMahasiswaPhone').value = data.phone ?? '';
        document.getElementById('modalEditMahasiswa').classList.remove('hidden');
    }

    function closeEditMahasiswa() {
        document.getElementById('modalEditMahasiswa').classList.add('hidden');
    }

    function confirmDelete(type) {
        return confirm(`Apakah Anda yakin ingin menghapus akun ${type} ini? Data akademik terkait yang mengikuti aturan cascade juga dapat ikut terhapus.`);
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeCreateMahasiswa();
            closeEditMahasiswa();
        }
    });
</script>

@endsection
