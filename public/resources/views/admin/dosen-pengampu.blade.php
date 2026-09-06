@extends('admin.app-admin')

@section('ketjudul')
DAFTAR
@endsection

@section('judul')
Program Studi
@endsection

@section('content')

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

    @forelse ($prodiList as $prodi)
        <a href="{{ route('dosen.prodi', $prodi->id) }}"
           class="group block bg-white border border-line rounded-2xl p-5 hover:border-teal hover:shadow-md transition-all duration-200">
            <div class="w-11 h-11 rounded-xl bg-paper border border-line flex items-center justify-center text-xl mb-4 group-hover:bg-teal/10">
                🎓
            </div>
            <h3 class="font-display text-lg font-semibold text-ink mb-1">
                {{ $prodi->nama_prodi }}
            </h3>
            <p class="text-sm text-ink/50 font-mono">
                Lihat dosen pengampu →
            </p>
        </a>
    @empty
        <p class="text-ink/50 col-span-full">Belum ada data program studi.</p>
    @endforelse

</div>

@endsection