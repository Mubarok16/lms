@extends('student.app-student')
@section('ketjudul')
Dashboard
@endsection

@section('judul')
Daftar Mata Kuliah
@endsection

@section('content')

<div class="lg:col-span-3">

    <div class="mb-6">
        <h1 class="font-display text-xl font-semibold text-ink">
            Mata Kuliah Saya
        </h1>
        <p class="mt-1 text-sm text-ink/50">
            Daftar mata kuliah yang kamu ambil semester ini.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

        @forelse ($pengajaranList as $pengajaran)
        <div class="group flex flex-col overflow-hidden rounded-2xl border border-line bg-white shadow-sm
                            transition hover:-translate-y-0.5 hover:shadow-md">

            {{-- Aksen atas --}}
            <div class="h-1.5 w-full bg-gradient-to-r from-ink to-ink/40"></div>

            <div class="flex flex-1 flex-col p-5">

                {{-- Icon + SKS badge --}}
                <div class="flex items-start justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-paper text-ink">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M4 19.5A2.5 2.5 0 016.5 17H20" />
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" />
                        </svg>
                    </div>

                    <span class="rounded-full bg-paper px-2.5 py-1 text-xs font-semibold text-ink/60">
                        {{ $pengajaran->kelas->matakuliah->sks }} SKS
                    </span>
                </div>

                {{-- Info matkul --}}
                <div class="mt-4 flex-1">
                    <h2 class="font-display text-base font-semibold leading-snug text-ink">
                        {{ $pengajaran->kelas->matakuliah->nama_mk }}
                    </h2>

                    <p class="mt-1.5 text-xs text-ink/50">
                        {{ $pengajaran->kelas->kode_mk }} • Kelas {{ $pengajaran->kelas->kode_kelas }}
                    </p>
                </div>

                {{-- Tombol --}}
                <a href="{{ route('student.matakuliah.show', $pengajaran->kelas->id) }}"
                    class="mt-5 flex items-center justify-center gap-2 rounded-lg bg-ink px-4 py-2.5
                                   text-sm font-semibold text-white transition
                                   hover:bg-primaryDark">
                    Buka Mata Kuliah
                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>

            </div>

        </div>
        @empty

        <div class="col-span-full">
            <div class="rounded-2xl border border-dashed border-line bg-white p-10 text-center">
                <p class="text-sm text-ink/50">
                    Kamu belum terdaftar di mata kuliah apapun.
                </p>
            </div>
        </div>
        @endforelse

    </div>

</div>

@endsection