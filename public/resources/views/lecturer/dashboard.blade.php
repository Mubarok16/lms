@extends('lecturer.app-lecturer')
@section('ketjudul')Dashboard@endsection
@section('judul')Ringkasan E-Learning UNWIR@endsection
@section('content')
<div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-5">
@foreach([
 ['label'=>'Mata Kuliah Saya','value'=>$stats['courses']],['label'=>'Kelas','value'=>$stats['classes']],['label'=>'Materi','value'=>$stats['materials']],['label'=>'Tugas','value'=>$stats['tasks']],['label'=>'Kuis','value'=>$stats['quizzes']],['label'=>'Menunggu Koreksi','value'=>$stats['pending']]
] as $card)<div class="bg-white border border-line rounded-2xl p-6"><p class="text-xs font-mono uppercase tracking-wider text-ink/45">{{ $card['label'] }}</p><p class="font-display text-3xl font-semibold mt-3">{{ number_format($card['value']) }}</p></div>@endforeach
</div>
<div class="mt-6 bg-white border border-line rounded-2xl p-6"><div class="flex items-center justify-between mb-4"><div><h2 class="font-semibold">Kelas yang Anda ampu</h2><p class="text-sm text-ink/50">Akses cepat ke materi, absensi, tugas, dan kuis.</p></div><a href="{{ route('matakuliah.ampu') }}" class="text-sm text-teal hover:underline">Semua mata kuliah</a></div>
<div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4">@forelse($courses as $course)<a href="{{ route('pengajaran.show', $course->kelas_id) }}" class="border border-line rounded-xl p-4 hover:border-teal/40 hover:bg-paper transition"><p class="font-medium">{{ $course->kelas->matakuliah->nama_mk ?? '-' }}</p><p class="text-xs text-ink/45 font-mono mt-1">{{ $course->kelas->matakuliah->kode_mk ?? '-' }} · Kelas {{ $course->kelas->kode_kelas ?? '-' }}</p></a>@empty<div class="text-sm text-ink/45">Belum ada kelas yang diampu.</div>@endforelse</div></div>
@endsection
