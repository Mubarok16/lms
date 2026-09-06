@extends('lecturer.app-lecturer-create-materi')
@section('ketjudul')
Dashboard
@endsection

@section('judul')
Daftar Mata Kuliah
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 px-6 py-8">

    <div class="mx-auto max-w-7xl">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">
                Pengajaran
            </h1>

            <p class="mt-1 text-sm text-black-500">
                Daftar mata kuliah.
            </p>
        </div>


        {{-- Card Mata Kuliah --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">

            @forelse ($kelas as $item)

            <a href="{{ route('pengajaran.show', $item->id) }}"
                class="group relative overflow-hidden rounded-2xl border border-line bg-white shadow-sm
                   transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">

                {{-- Top Accent --}}
                <div class="h-1.5 bg-ink"></div>

                <div class="p-6">

                    {{-- Header Card --}}
                    <div class="flex items-start justify-end">
                        {{-- Icon Mata Kuliah --}}
                        <div
                            class="rounded-full bg-paper px-3 py-1 text-xs font-semibold
                               text-ink/70 border border-line
                               transition group-hover:border-blue-200
                               group-hover:bg-blue-50 group-hover:text-teal">
                            {{ $item->kode_mk }}
                        </div>
                    </div>


                    {{-- Mata Kuliah --}}
                    <div class="mt-5">

                        <p class="font-mono text-[10px] font-medium uppercase tracking-[0.15em] text-ink/40">
                            Mata Kuliah
                        </p>

                        <h2
                            class="mt-1.5 line-clamp-2 text-xl font-semibold leading-snug text-ink
                               transition-colors duration-300
                               group-hover:text-teal">
                            {{ $item->matakuliah->nama_mk }}
                        </h2>

                    </div>

                    {{-- Divider --}}
                    <div class="my-5 h-px bg-line"></div>

                    {{-- Footer / Action --}}
                    <div
                        class="mt-6 flex items-center justify-between rounded-xl
                           bg-paper px-4 py-3
                           transition-all duration-300
                           group-hover:bg-ink">

                        <span
                            class="text-sm font-medium text-ink/70
                               transition-colors duration-300
                               group-hover:text-white">

                            Kelola Mata Kuliah

                        </span>

                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-lg
                               bg-white text-ink/50 shadow-sm
                               transition-all duration-300
                               group-hover:translate-x-1 group-hover:text-teal">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>

                    </div>

                </div>

            </a>


            @empty

            {{-- Empty State --}}
            <div
                class="col-span-full flex flex-col items-center justify-center
                   rounded-2xl border border-dashed border-line
                   bg-white px-6 py-16 text-center">

                <div
                    class="flex h-14 w-14 items-center justify-center
                       rounded-full bg-paper text-ink/40">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-7 w-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5
                           5.836 5 4.5 5.746 4.5 6.667v10.666
                           C4.5 18.254 5.836 19 7.5 19
                           c1.746 0 3.332.477 4.5 1.253
                           m0-14C13.168 5.477 14.754 5 16.5 5
                           c1.664 0 3 .746 3 1.667v10.666
                           C19.5 18.254 18.164 19 16.5 19
                           c-1.746 0-3.332.477-4.5 1.253" />

                    </svg>

                </div>

                <h3 class="mt-4 text-base font-semibold text-ink">
                    Belum Ada Mata Kuliah
                </h3>

                <p class="mt-1 max-w-sm text-sm text-ink/50">
                    Belum terdapat mata kuliah yang ditugaskan kepada Anda.
                </p>

            </div>

            @endforelse

        </div>

    </div>

</div>

@endsection