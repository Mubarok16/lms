@extends('student.app-student')
@section('ketjudul')Aktivitas Pembelajaran@endsection
@section('judul')Tugas & Kuis@endsection
@section('content')
<div class="grid xl:grid-cols-2 gap-6">
<section class="bg-white border border-line rounded-2xl overflow-hidden"><div class="p-5 border-b border-line"><h2 class="font-semibold">Tugas</h2></div><div class="divide-y divide-line">
@forelse($tasks as $task)<div class="p-5"><div class="flex justify-between gap-3"><div><a href="{{ route('student.tugas.show',$task) }}" class="font-medium hover:text-teal">{{ $task->judul }}</a><p class="text-sm text-ink/50 mt-1">{{ $task->pengajaranDosen->kelas->matakuliah->nama_mk ?? '-' }} · {{ $task->pengajaranDosen->kelas->kode_kelas ?? '-' }}</p></div><span class="text-xs px-2 py-1 rounded-full {{ optional($task->jawaban_saya)->status === 'sudah_dikoreksi' ? 'bg-teal/10 text-teal' : 'bg-amber/15' }}">{{ optional($task->jawaban_saya)->status ? str_replace('_',' ',optional($task->jawaban_saya)->status) : 'Belum dikumpulkan' }}</span></div>@if($task->deadline)<p class="text-xs text-ink/45 mt-3">Deadline: {{ $task->deadline->format('d M Y H:i') }}</p>@endif</div>@empty<div class="p-8 text-center text-sm text-ink/45">Belum ada tugas.</div>@endforelse</div></section>
<section class="bg-white border border-line rounded-2xl overflow-hidden"><div class="p-5 border-b border-line"><h2 class="font-semibold">Kuis</h2></div><div class="divide-y divide-line">
@forelse($quizzes as $quiz)<div class="p-5"><div class="flex justify-between gap-3"><div><a href="{{ route('student.quiz.show',$quiz) }}" class="font-medium hover:text-teal">{{ $quiz->judul }}</a><p class="text-sm text-ink/50 mt-1">{{ $quiz->pengajaranDosen->kelas->matakuliah->nama_mk ?? '-' }} · {{ $quiz->questions_count }} soal</p></div><span class="text-xs px-2 py-1 rounded-full {{ $quiz->jawaban_saya ? 'bg-teal/10 text-teal' : 'bg-coral/10 text-coral' }}">{{ $quiz->jawaban_saya ? 'Sudah dikerjakan' : 'Belum dikerjakan' }}</span></div></div>@empty<div class="p-8 text-center text-sm text-ink/45">Belum ada kuis aktif.</div>@endforelse</div></section>
</div>
@endsection
