@extends('lecturer.app-lecturer')
@section('ketjudul','Penilaian')
@section('judul','Daftar Mahasiswa')
@section('content')
<div class="space-y-6">
  <a href="{{ route('lecturer.nilai') }}" class="text-sm text-teal">← Kembali ke mata kuliah</a>
  <div class="bg-white border border-line rounded-2xl p-5">
    <p class="text-xs font-mono text-ink/45">{{ $pengajaranDosen->kelas->kode_mk }} · Kelas {{ $pengajaranDosen->kelas->kode_kelas }}</p>
    <h2 class="font-display text-xl font-semibold mt-1">{{ $pengajaranDosen->kelas->matakuliah->nama_mk }}</h2>
  </div>
  <div class="overflow-hidden bg-white border border-line rounded-2xl">
    <div class="p-5 border-b border-line"><h3 class="font-display font-semibold">Mahasiswa Mengikuti Mata Kuliah</h3></div>
    <div class="overflow-x-auto"><table class="min-w-full text-sm"><thead class="bg-paper"><tr><th class="p-4 text-left">No</th><th class="p-4 text-left">NIM</th><th class="p-4 text-left">Nama</th><th class="p-4 text-right">Aksi</th></tr></thead><tbody class="divide-y divide-line">
    @forelse($students as $i => $student)
      <tr><td class="p-4">{{ $i+1 }}</td><td class="p-4 font-mono">{{ $student->nim }}</td><td class="p-4 font-medium">{{ $student->user->name }}</td><td class="p-4 text-right"><a class="inline-flex px-3 py-2 rounded-lg bg-teal text-white text-xs font-medium" href="{{ route('lecturer.nilai.mahasiswa.detail', [$pengajaranDosen, $student]) }}">Lihat Detail</a></td></tr>
    @empty<tr><td colspan="4" class="p-12 text-center text-ink/45">Belum ada mahasiswa.</td></tr>@endforelse
    </tbody></table></div>
  </div>
</div>
@endsection
