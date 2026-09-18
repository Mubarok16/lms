@extends('admin.app-admin')

@section('ketjudul')
Selamat Datang di
@endsection

@section('judul')
Akun Dosen
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
    <form action="{{ route('akun_dosen.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 -translate-y-1/2 text-ink/40">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.3-4.3" />
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIDN, nama, email, prodi..." class="w-72 rounded-lg border border-line py-2 pl-9 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">
        </div>
        <button type="submit" class="rounded-lg bg-teal px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-teal/90">Cari</button>
        @if (request('search'))
        <a href="{{ route('akun_dosen.index') }}" class="px-2 text-sm font-medium text-ink/50 hover:text-ink/80">Reset</a>
        @endif
    </form>

    <div class="flex flex-wrap gap-3">
        <button type="button" onclick="openCreateDosen()" class="inline-flex items-center gap-2 rounded-lg bg-teal px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-teal/90">
            + Tambah Dosen
        </button>
        <a href="{{ route('dosen.import') }}" class="inline-flex items-center gap-2 rounded-lg bg-teal px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-teal/90">
            + Tambah Banyak Dosen
        </a>
    </div>
</div>

<div class="mb-5 flex items-center justify-between">
    <div>
        <h2 class="font-display text-lg font-semibold">Daftar Akun Dosen</h2>
        <p class="mt-1 text-xs text-ink/50">
            @if (request('search'))
                Hasil pencarian "{{ request('search') }}" · {{ $akundosen->total() }} data
            @else
                Total {{ $akundosen->total() }} akun dosen
            @endif
        </p>
    </div>
</div>

<div class="overflow-x-auto rounded-xl border border-line bg-white">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-line bg-paper/50 text-left">
                <th class="px-4 py-3 font-mono text-xs font-medium uppercase tracking-wide text-ink/50">No</th>
                <th class="px-4 py-3 font-mono text-xs font-medium uppercase tracking-wide text-ink/50">NIDN</th>
                <th class="px-4 py-3 font-mono text-xs font-medium uppercase tracking-wide text-ink/50">Nama Dosen</th>
                <th class="px-4 py-3 font-mono text-xs font-medium uppercase tracking-wide text-ink/50">Program Studi</th>
                <th class="px-4 py-3 font-mono text-xs font-medium uppercase tracking-wide text-ink/50">Email</th>
                <th class="px-4 py-3 font-mono text-xs font-medium uppercase tracking-wide text-ink/50">No. HP</th>
                <th class="px-4 py-3 font-mono text-xs font-medium uppercase tracking-wide text-ink/50">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($akundosen as $dosen)
            <tr class="border-b border-line last:border-0 hover:bg-paper/60">
                <td class="px-4 py-3 font-mono text-xs text-ink/60">{{ ($akundosen->firstItem() ?? 1) + $loop->index }}</td>
                <td class="px-4 py-3 font-medium">{{ $dosen->nidn }}</td>
                <td class="px-4 py-3 text-ink/70">{{ $dosen->user?->name ?? '-' }}</td>
                <td class="px-4 py-3 text-ink/70">{{ $dosen->prodi?->nama_prodi ?? '-' }}</td>
                <td class="px-4 py-3 text-ink/70">{{ $dosen->user?->email ?? '-' }}</td>
                <td class="px-4 py-3 text-ink/70">{{ $dosen->phone ?: '-' }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="openEditDosen({{ $dosen->id }})" class="inline-flex items-center gap-1 rounded-md bg-blue-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-600">
                            Edit
                        </button>
                        <form action="{{ route('admin.dosen.destroy', $dosen->id) }}" method="POST" onsubmit="return confirmDelete('dosen')">
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
                <td colspan="7" class="px-4 py-8 text-center text-sm text-ink/40">
                    @if (request('search'))
                        Tidak ditemukan data dosen untuk pencarian "{{ request('search') }}".
                    @else
                        Belum ada data akun dosen.
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-5">
    {{ $akundosen->links() }}
</div>

{{-- MODAL TAMBAH DOSEN --}}
<div id="modalUser" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40" onclick="closeCreateDosen()"></div>
    <div class="relative max-h-[90vh] w-full max-w-md overflow-y-auto rounded-xl bg-white p-6 shadow-lg">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="font-display text-lg font-semibold">Tambah Akun Dosen</h3>
            <button type="button" onclick="closeCreateDosen()" class="text-xl leading-none text-ink/40 hover:text-ink/70">&times;</button>
        </div>

        <form action="{{ route('admin.dosen.buatAkun') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-ink/70">NIDN</label>
                <input type="text" name="nidn" required value="{{ old('nidn') }}" class="w-full rounded-lg border border-line px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">
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
                <button type="button" onclick="closeCreateDosen()" class="rounded-lg border border-line px-4 py-2 text-sm font-medium text-ink/70 hover:bg-paper/60">Batal</button>
                <button type="submit" class="rounded-lg bg-teal px-4 py-2 text-sm font-medium text-white hover:bg-teal/90">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT DOSEN --}}
<div id="modalEditDosen" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40" onclick="closeEditDosen()"></div>
    <div class="relative max-h-[90vh] w-full max-w-md overflow-y-auto rounded-xl bg-white p-6 shadow-lg">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="font-display text-lg font-semibold">Edit Akun Dosen</h3>
            <button type="button" onclick="closeEditDosen()" class="text-xl leading-none text-ink/40 hover:text-ink/70">&times;</button>
        </div>

        <form id="formEditDosen" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-ink/70">NIDN</label>
                <input id="editDosenNidn" type="text" name="nidn" required class="w-full rounded-lg border border-line px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">
            </div>
            <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-ink/70">Nama Lengkap</label>
                <input id="editDosenName" type="text" name="name" required class="w-full rounded-lg border border-line px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">
            </div>
            <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-ink/70">Email</label>
                <input id="editDosenEmail" type="email" name="email" required class="w-full rounded-lg border border-line px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">
            </div>
            <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-ink/70">Program Studi</label>
                <select id="editDosenProdi" name="prodi_id" required class="w-full rounded-lg border border-line bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">
                    <option value="" disabled>Pilih Program Studi</option>
                    @foreach ($prodi as $item)
                    <option value="{{ $item->id }}">{{ $item->nama_prodi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-ink/70">Nomor Telepon</label>
                <input id="editDosenPhone" type="text" name="phone" class="w-full rounded-lg border border-line px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditDosen()" class="rounded-lg border border-line px-4 py-2 text-sm font-medium text-ink/70 hover:bg-paper/60">Batal</button>
                <button type="submit" class="rounded-lg bg-teal px-4 py-2 text-sm font-medium text-white hover:bg-teal/90">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@php
    $dosenRowsForEdit = $akundosen->getCollection()->mapWithKeys(function ($dosen) {
        return [
            (string) $dosen->id => [
                'id' => $dosen->id,
                'nidn' => $dosen->nidn,
                'name' => optional($dosen->user)->name,
                'email' => optional($dosen->user)->email,
                'prodi_id' => $dosen->prodi_id,
                'phone' => $dosen->phone,
            ],
        ];
    })->all();
@endphp

<script>
    const dosenRows = @json($dosenRowsForEdit);
    const dosenUpdateUrl = @json(route('admin.dosen.update', ['id' => '__ID__']));

    function openCreateDosen() {
        document.getElementById('modalUser').classList.remove('hidden');
    }

    function closeCreateDosen() {
        document.getElementById('modalUser').classList.add('hidden');
    }

    function openEditDosen(id) {
        const data = dosenRows[id];
        if (!data) {
            alert('Data dosen tidak ditemukan pada halaman ini.');
            return;
        }

        document.getElementById('formEditDosen').action = dosenUpdateUrl.replace('__ID__', id);
        document.getElementById('editDosenNidn').value = data.nidn ?? '';
        document.getElementById('editDosenName').value = data.name ?? '';
        document.getElementById('editDosenEmail').value = data.email ?? '';
        document.getElementById('editDosenProdi').value = data.prodi_id ?? '';
        document.getElementById('editDosenPhone').value = data.phone ?? '';
        document.getElementById('modalEditDosen').classList.remove('hidden');
    }

    function closeEditDosen() {
        document.getElementById('modalEditDosen').classList.add('hidden');
    }

    function confirmDelete(type) {
        return confirm(`Apakah Anda yakin ingin menghapus akun ${type} ini? Data akademik terkait yang mengikuti aturan cascade juga dapat ikut terhapus.`);
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeCreateDosen();
            closeEditDosen();
        }
    });
</script>

@endsection
