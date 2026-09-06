@extends('admin.app-admin')
@section('ketjudul','Manajemen Akun')
@section('judul')
Edit Akun {{ $type === 'dosen' ? 'Dosen' : 'Mahasiswa' }}
@endsection
@section('content')
<form method="POST" action="{{ $type === 'dosen' ? route('admin.dosen.update',$account) : route('admin.mahasiswa.update',$account) }}" class="max-w-2xl space-y-5">@csrf @method('PUT')
<div class="grid sm:grid-cols-2 gap-4">
<div><label class="block text-sm font-medium mb-1">{{ $type === 'dosen' ? 'NIDN' : 'NIM' }}</label><input name="{{ $type === 'dosen' ? 'nidn' : 'nim' }}" value="{{ old($type === 'dosen' ? 'nidn':'nim', $type === 'dosen' ? $account->nidn : $account->nim) }}" required class="w-full rounded-lg border-line"></div>
<div><label class="block text-sm font-medium mb-1">Nama</label><input name="name" value="{{ old('name',$account->user->name) }}" required class="w-full rounded-lg border-line"></div>
<div><label class="block text-sm font-medium mb-1">Email</label><input type="email" name="email" value="{{ old('email',$account->user->email) }}" required class="w-full rounded-lg border-line"></div>
<div><label class="block text-sm font-medium mb-1">Program Studi</label><select name="prodi_id" required class="w-full rounded-lg border-line">@foreach($prodi as $p)<option value="{{ $p->id }}" @selected($account->prodi_id==$p->id)>{{ $p->nama_prodi }}</option>@endforeach</select></div>
@if($type === 'mahasiswa')<div><label class="block text-sm font-medium mb-1">Angkatan</label><input name="angkatan" value="{{ old('angkatan',$account->angkatan) }}" required class="w-full rounded-lg border-line"></div>@endif
<div><label class="block text-sm font-medium mb-1">No. Telepon</label><input name="phone" value="{{ old('phone',$account->phone) }}" class="w-full rounded-lg border-line"></div>
<div class="sm:col-span-2"><label class="block text-sm font-medium mb-1">Password Baru <span class="font-normal text-ink/40">(kosongkan jika tidak diubah)</span></label><input type="password" name="password" class="w-full rounded-lg border-line"></div>
</div>
<div class="flex gap-3"><a href="{{ $type === 'dosen' ? route('akun_dosen.index') : route('akun_mahasiswa.index') }}" class="rounded-lg border border-line px-4 py-2.5">Batal</a><button class="rounded-lg bg-ink text-white px-5 py-2.5 font-semibold">Simpan Perubahan</button></div>
</form>
@endsection
