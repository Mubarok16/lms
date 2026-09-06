@extends('admin.app-admin')
@section('ketjudul','Akademik')
@section('judul','Edit Jadwal Kuliah')
@section('content')
<form method="POST" action="{{ route('admin.jadwal.update',$jadwal) }}" class="max-w-3xl grid md:grid-cols-2 gap-4">@csrf @method('PUT')
<div class="md:col-span-2"><label class="block text-sm font-medium mb-1">Kelas</label><select name="kelas_id" required class="w-full rounded-lg border-line">@foreach($kelas as $k)<option value="{{ $k->id }}" @selected($jadwal->kelas_id==$k->id)>{{ $k->kode_mk }} — {{ $k->matakuliah->nama_mk }} ({{ $k->kode_kelas }})</option>@endforeach</select></div>
<div><label class="block text-sm font-medium mb-1">Hari</label><select name="hari" required class="w-full rounded-lg border-line">@foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h)<option @selected($jadwal->hari===$h)>{{ $h }}</option>@endforeach</select></div>
<div><label class="block text-sm font-medium mb-1">Ruang</label><input name="ruang" value="{{ $jadwal->ruang }}" class="w-full rounded-lg border-line"></div>
<div><label class="block text-sm font-medium mb-1">Jam Mulai</label><input type="time" name="jam_mulai" value="{{ substr($jadwal->jam_mulai,0,5) }}" required class="w-full rounded-lg border-line"></div>
<div><label class="block text-sm font-medium mb-1">Jam Selesai</label><input type="time" name="jam_selesai" value="{{ substr($jadwal->jam_selesai,0,5) }}" required class="w-full rounded-lg border-line"></div>
<div class="md:col-span-2"><label class="block text-sm font-medium mb-1">Keterangan</label><input name="keterangan" value="{{ $jadwal->keterangan }}" class="w-full rounded-lg border-line"></div>
<div class="md:col-span-2 flex gap-3"><a href="{{ route('admin.jadwal.index') }}" class="rounded-lg border border-line px-4 py-2.5">Batal</a><button class="rounded-lg bg-ink text-white px-5 py-2.5 font-semibold">Simpan Perubahan</button></div></form>
@endsection
