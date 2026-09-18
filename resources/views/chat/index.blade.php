@extends(Auth::user()->role === 'admin' ? 'admin.app-admin' : (Auth::user()->role === 'lecturer' ? 'lecturer.app-lecturer' : 'student.app-student'))

@section('ketjudul', 'Komunikasi')
@section('judul', 'Chat Mata Kuliah')

@section('content')
<div class="space-y-5">
    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-sm text-ink/70">
        Pilih mata kuliah untuk masuk ke ruang chat. Pesan dapat dibaca dan dibalas oleh dosen serta mahasiswa yang terdaftar pada kelas tersebut.
    </div>

    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse($classes as $kelas)
            <a href="{{ route('chat.show', $kelas) }}" class="group block border border-line rounded-xl p-5 hover:border-blue-300 hover:shadow-sm transition bg-white">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-ink group-hover:text-blue-700">{{ $kelas->matakuliah->nama_mk ?? $kelas->kode_mk }}</p>
                        <p class="text-xs text-ink/50 font-mono mt-1">{{ $kelas->kode_mk }} · Kelas {{ $kelas->kode_kelas }}</p>
                    </div>
                </div>
            </a>
        @empty
            <div class="md:col-span-2 xl:col-span-3 border border-dashed border-line rounded-xl p-8 text-center text-ink/50">
                Belum ada mata kuliah yang dapat diakses untuk chat.
            </div>
        @endforelse
    </div>
</div>
@endsection
