@extends('student.app-student')
@section('ketjudul')Dashboard@endsection
@section('judul')Ringkasan E-Learning UNWIR@endsection
@section('content')
<div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-5">
@foreach([
 ['label'=>'Mata Kuliah','value'=>$stats['courses']],['label'=>'Tugas','value'=>$stats['tasks']],['label'=>'Kuis Aktif','value'=>$stats['quizzes']],['label'=>'Kuis Dikerjakan','value'=>$stats['quiz_done']]
] as $card)<div class="bg-white border border-line rounded-2xl p-6"><p class="text-xs font-mono uppercase tracking-wider text-ink/45">{{ $card['label'] }}</p><p class="font-display text-3xl font-semibold mt-3">{{ number_format($card['value']) }}</p></div>@endforeach
</div>
<div class="mt-6 bg-white border border-line rounded-2xl overflow-hidden"><div class="p-5 border-b border-line"><h2 class="font-semibold">Tugas mendatang</h2><p class="text-sm text-ink/50 mt-1">Deadline terdekat dari mata kuliah yang Anda ikuti.</p></div><div class="divide-y divide-line">
@forelse($upcomingTasks as $task)<a href="{{ route('student.tugas.show', $task) }}" class="block p-5 hover:bg-paper transition"><div class="flex items-start justify-between gap-4"><div><p class="font-medium">{{ $task->judul }}</p><p class="text-sm text-ink/50 mt-1">{{ $task->pengajaranDosen->kelas->matakuliah->nama_mk ?? '-' }} · Kelas {{ $task->pengajaranDosen->kelas->kode_kelas ?? '-' }}</p></div><span class="text-xs font-mono px-2 py-1 rounded-full bg-amber/15">{{ $task->deadline->format('d M Y H:i') }}</span></div></a>@empty<div class="p-8 text-center text-sm text-ink/45">Tidak ada deadline terdekat.</div>@endforelse</div></div>
@endsection
