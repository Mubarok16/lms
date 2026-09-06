@extends('admin.app-admin')
@section('ketjudul')
    Selamat Datang di
@endsection

@section('judul')
    Akun Mahasiswa
@endsection

@section('content')
    @php
        $akundosen = [
            [
                'no' => '1',
                'nidn' => '402842017',
                'dosen' => 'Dr. Andi Saputra, M.Kom',
                'email' => 'dosen@unwir.ac.id',
                'aksi' => 'Aktif',
            ],
            [
                'no' => '2',
                'nidn' => '402842017',
                'dosen' => 'Rina Wulandari, M.T',
                'email' => 'dosen@unwir.ac.id',
                'aksi' => 'Aktif',
            ],
            [
                'no' => '3',
                'nidn' => '402842017',
                'dosen' => 'Prof. Bambang Hartono',
                'email' => 'dosen@unwir.ac.id',
                'aksi' => 'Aktif',
            ],
        ];
    @endphp

    <div class="flex justify-end gap-3 mb-4">

        <!-- Tombol Tambah Dosen -->
        <button type="button" onclick="document.getElementById('modalUser').classList.remove('hidden')"
            class="inline-flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-lg bg-teal text-white hover:bg-teal/90 transition-colors">
            + Tambah Mahasiswa
        </button>

        <!-- Tombol Tambah Banyak Dosen -->
        <a href="{{ route('mahasiswa.import') }}"
            class="inline-flex items-center gap-2 text-sm font-medium px-4 py-2 rounded-lg bg-teal text-white hover:bg-teal/90 transition-colors">
            + Tambah Banyak Mahasiswa
        </a>

    </div>

    <!-- Header Card -->
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="font-display text-lg font-semibold">Daftar Akun Mahasiswa</h2>

        </div>
        <a href="#" class="text-sm font-medium text-teal hover:underline">Lihat Semua</a>
    </div>

    <!-- Tabel -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-line text-left">
                    <th class="py-3 pr-4 font-medium text-ink/50 font-mono text-xs uppercase tracking-wide">No</th>
                    <th class="py-3 pr-4 font-medium text-ink/50 font-mono text-xs uppercase tracking-wide">NPM</th>
                    <th class="py-3 pr-4 font-medium text-ink/50 font-mono text-xs uppercase tracking-wide">Nama Mahasiswa
                    </th>
                    <th class="py-3 pr-4 font-medium text-ink/50 font-mono text-xs uppercase tracking-wide">Prodi</th>
                    <th class="py-3 pr-4 font-medium text-ink/50 font-mono text-xs uppercase tracking-wide">Angkatan</th>
                    <th class="py-3 pr-4 font-medium text-ink/50 font-mono text-xs uppercase tracking-wide">email</th>
                    <th class="py-3 pr-4 font-medium text-ink/50 font-mono text-xs uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($akunmahasiswa as $mk)
                    <tr class="border-b border-line last:border-0 hover:bg-paper/60 transition-colors">
                        <td class="py-3 pr-4 font-mono text-xs text-ink/60">{{ $loop->iteration }}</td>
                        <td class="py-3 pr-4 font-medium">{{ $mk['nim'] }}</td>
                        <td class="py-3 pr-4 text-ink/70">{{ $mk->user->name }}</td>
                        <td class="py-3 pr-4 text-ink/70">{{ $mk->prodi->nama_prodi }}</td>
                        <td class="py-3 pr-4 text-ink/70">{{ $mk->angkatan }}</td>
                        <td class="py-3 pr-4 text-ink/70">{{ $mk->user->email }}</td>
                        <td class="py-3 pr-4">
                            <div class="flex items-center gap-2">

                                <!-- Edit -->
                                <button type="button" onclick="openEditModal('{{ $mk['no'] }}')"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium
                   bg-blue-500 text-white hover:bg-blue-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 20h9" />
                                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                    </svg>
                                    Edit
                                </button>

                                <!-- Delete -->
                                <button type="button" onclick="deleteDosen('{{ $mk['no'] }}')"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium
                   bg-red-500 text-white hover:bg-red-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 6h18" />
                                        <path d="M8 6V4h8v2" />
                                        <path d="M19 6v14H5V6" />
                                        <path d="M10 11v6" />
                                        <path d="M14 11v6" />
                                    </svg>
                                    Delete
                                </button>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-ink/40 text-sm">Belum ada data mata kuliah.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Pengguna -->
    <div id="modalUser" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/40" onclick="document.getElementById('modalUser').classList.add('hidden')">
        </div>

        <!-- Konten Modal -->
        <div class="relative bg-white w-full max-w-md rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-display text-lg font-semibold">Tambah Akun Mahasiswa</h3>
                <button type="button" onclick="document.getElementById('modalUser').classList.add('hidden')"
                    class="text-ink/40 hover:text-ink/70 text-xl leading-none">
                    &times;
                </button>
            </div>

            <form action="{{ route('admin.mahasiswa.buatAkun') }}" method="POST">
                @csrf
                <div class="mb-4">

                    <label class="block text-sm font-medium text-ink/70 mb-1">NPM</label>
                    <input id="inputNim" name="nim" type="number" required
                        class="w-full border border-line rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40"
                        placeholder="Masukkan Nomor Induk Mahasiswa">
                </div>
                <div class="mb-4">

                    <label class="block text-sm font-medium text-ink/70 mb-1">Nama Lengkap</label>
                    <input id="inputNama" name="name" type="text" required
                        class="w-full border border-line rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40"
                        placeholder="Masukkan nama lengkap">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-ink/70 mb-1">Email</label>
                    <input id="inputEmail" name="email" type="email" required
                        class="w-full border border-line rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40"
                        placeholder="nama@email.com">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-ink/70 mb-1">Angkatan</label>
                    <input id="inputSemester" name="angkatan" type="number" required
                        class="w-full border border-line rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal/40"
                        placeholder="Masukkan Tahun Masuk">
                </div>

                <div class="mb-4">

                    <label class="block text-sm font-medium text-ink/70 mb-1">
                        Program Studi
                    </label>

                    <select name="prodi_id" required
                        class="w-full border border-line rounded-lg px-3 py-2 text-sm
                      bg-white focus:outline-none focus:ring-2 focus:ring-teal/40">
                        <option value="" disabled {{ old('prodi_id') ? '' : 'selected' }}>
                            Pilih Program Studi
                        </option>

                        @foreach ($prodi as $item)
                            <option value="{{ $item->id }}" {{ old('prodi_id') == $item->id ? 'selected' : '' }}>
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


                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modalUser').classList.add('hidden')"
                        class="px-4 py-2 text-sm font-medium rounded-lg border border-line text-ink/70 hover:bg-paper/60">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium rounded-lg bg-teal text-white hover:bg-teal/90">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
