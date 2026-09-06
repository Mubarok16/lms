@extends('admin.app-admin')
@section('ketjudul')Dashboard@endsection
@section('judul')Ringkasan E-Learning UNWIR@endsection
@section('content')
<div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-5">
@foreach([
 ['label'=>'Mata Kuliah','value'=>$stats['courses'],'hint'=>'Master mata kuliah'],
 ['label'=>'Kelas','value'=>$stats['classes'],'hint'=>'Kelas aktif'],
 ['label'=>'Dosen','value'=>$stats['lecturers'],'hint'=>'Akun dosen'],
 ['label'=>'Mahasiswa','value'=>$stats['students'],'hint'=>'Akun mahasiswa'],
 ['label'=>'Pengajaran','value'=>$stats['teaching'],'hint'=>'Penugasan dosen'],
 ['label'=>'Total Pengguna','value'=>$stats['users'],'hint'=>'Seluruh akun'],
] as $card)
<div class="bg-white border border-line rounded-2xl p-6">
 <p class="text-xs font-mono uppercase tracking-wider text-ink/45">{{ $card['label'] }}</p>
 <p class="font-display text-3xl font-semibold mt-3">{{ number_format($card['value']) }}</p>
 <p class="text-sm text-ink/55 mt-1">{{ $card['hint'] }}</p>
</div>
@endforeach
</div>
<div class="mt-6 bg-white border border-line rounded-2xl overflow-hidden">
 <div class="p-5 border-b border-line flex items-center justify-between"><div><h2 class="font-semibold">Penugasan terbaru</h2><p class="text-sm text-ink/50 mt-1">Dosen dan kelas yang terakhir dibuat.</p></div><a href="{{ route('matakuliah.pengampu') }}" class="text-sm text-teal hover:underline">Lihat semua</a></div>
 <div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-paper"><tr><th class="text-left px-5 py-3">Mata Kuliah</th><th class="text-left px-5 py-3">Kelas</th><th class="text-left px-5 py-3">Dosen</th></tr></thead><tbody>
 @forelse($recentTeaching as $item)<tr class="border-t border-line"><td class="px-5 py-3 font-medium">{{ $item->kelas->matakuliah->nama_mk ?? '-' }} <span class="text-ink/40">({{ $item->kelas->matakuliah->kode_mk ?? '-' }})</span></td><td class="px-5 py-3">{{ $item->kelas->kode_kelas ?? '-' }}</td><td class="px-5 py-3">{{ $item->lecturer->user->name ?? '-' }}</td></tr>@empty<tr><td colspan="3" class="px-5 py-8 text-center text-ink/45">Belum ada penugasan.</td></tr>@endforelse
 </tbody></table></div>
</div>
@endsection
