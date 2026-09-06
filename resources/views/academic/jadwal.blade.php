@php($isAdmin = auth()->user()->role === 'admin')
@extends($isAdmin ? 'admin.app-admin' : (auth()->user()->role === 'lecturer' ? 'lecturer.app-lecturer' : 'student.app-student'))
@section('ketjudul','Akademik')
@section('judul','Jadwal Kuliah')
@section('content')
<div class="space-y-6">
@if($isAdmin)
<div class="rounded-2xl border border-line bg-paper p-5">
<h2 class="font-display text-lg font-semibold">Tambah Jadwal</h2>
<form method="POST" action="{{ route('admin.jadwal.store') }}" class="mt-4 grid md:grid-cols-6 gap-3">@csrf
<select name="kelas_id" required class="rounded-lg border-line md:col-span-2"><option value="">Pilih kelas</option>@foreach($kelas as $k)<option value="{{ $k->id }}">{{ $k->kode_mk }} — {{ $k->matakuliah->nama_mk }} ({{ $k->kode_kelas }})</option>@endforeach</select>
<select name="hari" required class="rounded-lg border-line"><option value="">Hari</option>@foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h)<option>{{ $h }}</option>@endforeach</select>
<input type="time" name="jam_mulai" required class="rounded-lg border-line"><input type="time" name="jam_selesai" required class="rounded-lg border-line"><input name="ruang" placeholder="Ruang" class="rounded-lg border-line">
<button class="md:col-span-1 rounded-lg bg-ink px-4 py-2 text-white font-semibold">Simpan</button>
</form></div>@endif
<div class="overflow-hidden rounded-2xl border border-line bg-white"><div class="overflow-x-auto"><table class="min-w-full text-sm"><thead class="bg-paper"><tr><th class="p-4 text-left">Hari</th><th class="p-4 text-left">Waktu</th><th class="p-4 text-left">Mata Kuliah</th><th class="p-4 text-left">Kelas</th><th class="p-4 text-left">Dosen</th><th class="p-4 text-left">Ruang</th>@if($isAdmin)<th class="p-4"></th>@endif</tr></thead><tbody class="divide-y divide-line">@forelse($jadwal as $j)<tr><td class="p-4 font-medium">{{ $j->hari }}</td><td class="p-4 font-mono">{{ substr($j->jam_mulai,0,5) }}–{{ substr($j->jam_selesai,0,5) }}</td><td class="p-4">{{ $j->kelas->matakuliah->nama_mk }}</td><td class="p-4">{{ $j->kelas->kode_kelas }}</td><td class="p-4">{{ $j->kelas->pengajaranDosen->map(fn($p)=>$p->lecturer->user->name ?? '-')->join(', ') }}</td><td class="p-4">{{ $j->ruang ?: '-' }}</td>@if($isAdmin)<td class="p-4 flex gap-3"><a href="{{ route('admin.jadwal.edit',$j) }}" class="text-teal hover:underline">Edit</a><form method="POST" action="{{ route('admin.jadwal.destroy',$j) }}" onsubmit="return confirm('Hapus jadwal ini?')">@csrf @method('DELETE')<button class="text-red-600 hover:underline">Hapus</button></form></td>@endif</tr>@empty<tr><td colspan="{{ $isAdmin ? 7 : 6 }}" class="p-12 text-center text-ink/45">Belum ada jadwal kuliah.</td></tr>@endforelse</tbody></table></div></div>
</div>@endsection
