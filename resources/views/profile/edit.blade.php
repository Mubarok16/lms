@extends('student.app-student')
@section('ketjudul')

@endsection

@section('judul')
Kelola informasi profil dan keamanan akun Anda.
@endsection

@section('content')

{{-- INFORMASI PROFIL --}}
<div class="bg-white rounded-xl border border-ink/10 shadow-sm overflow-hidden">

    <div class="px-6 py-5 border-b border-ink/10">
        <div class="flex items-center gap-3">

            <span class="w-9 h-9 rounded-lg bg-amber/20 border border-amber/30 flex items-center justify-center">
                <svg
                    width="18"
                    height="18"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8">
                    <circle cx="12" cy="8" r="4" />
                    <path d="M4 21a8 8 0 0116 0" />
                </svg>
            </span>

            <div>
                <h2 class="text-base font-semibold text-ink">
                    Informasi Profil
                </h2>

                <p class="text-xs text-ink/45">
                    Perbarui informasi dasar akun Anda.
                </p>
            </div>

        </div>
    </div>

    <div class="p-6 sm:p-8">

        @include('profile.partials.update-profile-information-form')

    </div>

</div>

{{-- PASSWORD --}}
<div class="bg-white rounded-xl border border-ink/10 shadow-sm overflow-hidden">

    <div class="px-6 py-5 border-b border-ink/10">
        <div class="flex items-center gap-3">

            <span class="w-9 h-9 rounded-lg bg-teal/15 border border-teal/25 flex items-center justify-center">

                <svg
                    width="18"
                    height="18"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8">
                    <rect x="3" y="11" width="18" height="10" rx="2" />
                    <path d="M7 11V7a5 5 0 0110 0v4" />
                </svg>

            </span>

            <div>
                <h2 class="text-base font-semibold text-ink">
                    Keamanan Akun
                </h2>

                <p class="text-xs text-ink/45">
                    Perbarui password untuk menjaga keamanan akun.
                </p>
            </div>

        </div>
    </div>

    <div class="p-6 sm:p-8">

        @include('profile.partials.update-password-form')

    </div>

</div>


@endsection