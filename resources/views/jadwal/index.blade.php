@extends(Auth::user()->role === 'admin' ? 'admin.app-admin' : (Auth::user()->role === 'lecturer' ? 'lecturer.app-lecturer' : 'student.app-student'))

@section('ketjudul', 'Akademik')
@section('judul', 'Jadwal Mata Kuliah')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    @if(Auth::user()->role === 'admin')
    <div class="grid xl:grid-cols-2 gap-5">
        <div class="border border-line rounded-xl p-5">
            <h2 class="font-semibold mb-4">Tambah Jadwal</h2>
            <form method="POST" action="{{ route('admin.jadwal.store') }}" class="grid sm:grid-cols-2 gap-4">
                @csrf
                <div class="sm:col-span-2">
                    <label class="text-xs font-medium text-ink/60">Mata Kuliah / Kelas</label>
                    <select name="kelas_id" required class="mt-1 w-full rounded-lg border-line text-sm">
                        <option value="">Pilih kelas</option>
                        @foreach($classes as $kelas)
                            <option value="{{ $kelas->id }}">{{ $kelas->kode_mk }} - {{ $kelas->matakuliah->nama_mk ?? '-' }} (Kelas {{ $kelas->kode_kelas }})</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="text-xs font-medium text-ink/60">Hari</label><select name="hari" required class="mt-1 w-full rounded-lg border-line text-sm">@foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $hari)<option>{{ $hari }}</option>@endforeach</select></div>
                <div><label class="text-xs font-medium text-ink/60">Ruangan</label><input name="ruangan" maxlength="100" class="mt-1 w-full rounded-lg border-line text-sm" placeholder="Contoh: R.201"></div>
                <div><label class="text-xs font-medium text-ink/60">Jam Mulai</label><input type="time" name="jam_mulai" required class="mt-1 w-full rounded-lg border-line text-sm"></div>
                <div><label class="text-xs font-medium text-ink/60">Jam Selesai</label><input type="time" name="jam_selesai" required class="mt-1 w-full rounded-lg border-line text-sm"></div>
                <div class="sm:col-span-2"><button class="px-4 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold">Simpan Jadwal</button></div>
            </form>
        </div>

        <div class="border border-line rounded-xl p-5">
            <h2 class="font-semibold mb-2">Import Jadwal</h2>
            <p class="text-sm text-ink/55 mb-4">Format kolom: kode_mk, kode_kelas, hari, jam_mulai, jam_selesai, ruangan.</p>
            <a href="{{ route('admin.jadwal.template') }}" class="inline-flex mb-4 text-sm font-medium text-blue-600 hover:text-blue-800">Unduh template Excel</a>
            <form method="POST" action="{{ route('admin.jadwal.import') }}" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="block w-full text-sm border border-line rounded-lg p-2">
                @error('file')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                <button class="px-4 py-2.5 rounded-lg bg-ink text-white text-sm font-semibold">Import Semua Jadwal</button>
            </form>
        </div>
    </div>
    @endif

    <div class="border border-line rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-line"><h2 class="font-semibold">Daftar Jadwal</h2></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-ink/55 text-left"><tr><th class="px-4 py-3">Hari</th><th class="px-4 py-3">Mata Kuliah</th><th class="px-4 py-3">Kelas</th><th class="px-4 py-3">Waktu</th><th class="px-4 py-3">Ruangan</th>@if(Auth::user()->role === 'admin')<th class="px-4 py-3">Aksi</th>@endif</tr></thead>
                <tbody class="divide-y divide-line">
                @forelse($schedules as $jadwal)
                    <tr class="align-top">
                        @if(Auth::user()->role === 'admin')
                        <form method="POST" action="{{ route('admin.jadwal.update', $jadwal) }}">@csrf @method('PUT')
                        <td class="px-4 py-3"><select name="hari" class="rounded-lg border-line text-sm">@foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $hari)<option @selected($jadwal->hari===$hari)>{{ $hari }}</option>@endforeach</select></td>
                        <td class="px-4 py-3 min-w-[260px]"><select name="kelas_id" class="w-full rounded-lg border-line text-sm">@foreach($classes as $kelas)<option value="{{ $kelas->id }}" @selected($jadwal->kelas_id===$kelas->id)>{{ $kelas->kode_mk }} - {{ $kelas->matakuliah->nama_mk ?? '-' }} / {{ $kelas->kode_kelas }}</option>@endforeach</select></td>
                        <td class="px-4 py-3">{{ $jadwal->kelas->kode_kelas }}</td>
                        <td class="px-4 py-3 whitespace-nowrap"><input type="time" name="jam_mulai" value="{{ substr($jadwal->jam_mulai,0,5) }}" class="rounded-lg border-line text-sm"> <span class="mx-1">-</span> <input type="time" name="jam_selesai" value="{{ substr($jadwal->jam_selesai,0,5) }}" class="rounded-lg border-line text-sm"></td>
                        <td class="px-4 py-3"><input name="ruangan" value="{{ $jadwal->ruangan }}" class="w-28 rounded-lg border-line text-sm"></td>
                        <td class="px-4 py-3 whitespace-nowrap"><button class="text-blue-600 hover:text-blue-800 font-medium">Simpan</button></form><form method="POST" action="{{ route('admin.jadwal.destroy', $jadwal) }}" class="inline ml-3" onsubmit="return confirm('Hapus jadwal ini?')">@csrf @method('DELETE')<button class="text-red-600 hover:text-red-800">Hapus</button></form></td>
                        @else
                        <td class="px-4 py-3 font-medium">{{ $jadwal->hari }}</td>
                        <td class="px-4 py-3"><p class="font-medium">{{ $jadwal->kelas->matakuliah->nama_mk ?? $jadwal->kelas->kode_mk }}</p><p class="text-xs text-ink/40 font-mono">{{ $jadwal->kelas->kode_mk }}</p></td>
                        <td class="px-4 py-3">{{ $jadwal->kelas->kode_kelas }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ substr($jadwal->jam_mulai,0,5) }} - {{ substr($jadwal->jam_selesai,0,5) }}</td>
                        <td class="px-4 py-3">{{ $jadwal->ruangan ?: '-' }}</td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-ink/45">Belum ada jadwal mata kuliah.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
