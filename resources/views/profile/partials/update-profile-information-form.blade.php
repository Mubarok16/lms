<section>

    <form method="post"
        action="{{ route('profile.update') }}"
        enctype="multipart/form-data"
        class="space-y-6">

        @csrf
        @method('patch')


        {{-- FOTO PROFIL --}}
        <div class="flex items-center gap-5">

            <div class="relative shrink-0">

                <img
                    src="{{ $user->profile_photo
                        ? asset('storage/' . $user->profile_photo)
                        : 'https://i.pravatar.cc/100?img=32' }}"
                    class="w-24 h-24 rounded-full object-cover border-2 border-amber/30"
                    alt="Foto Profil">

            </div>

            <div class="min-w-0">

                <label class="block text-sm font-medium text-ink mb-2">
                    Foto Profil
                </label>

                <input
                    type="file"
                    name="profile_photo"
                    accept="image/jpeg,image/png,image/webp"
                    class="block w-full text-xs text-ink/50
                           file:mr-3 file:py-2 file:px-3
                           file:rounded-lg file:border-0
                           file:text-xs file:font-medium
                           file:bg-amber/15 file:text-ink
                           hover:file:bg-amber/25
                           cursor-pointer">

                <p class="mt-2 text-[11px] text-ink/40 font-mono">
                    JPG, PNG, WEBP · Maks. 2 MB
                </p>

                @error('profile_photo')
                <p class="mt-1 text-xs text-coral">
                    {{ $message }}
                </p>
                @enderror

            </div>

        </div>


        {{-- NAMA --}}
        <div>

            <label
                for="name"
                class="block text-sm font-medium text-ink mb-2">
                Nama
            </label>

            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name', $user->name) }}"
                disabled
                autofocus
                autocomplete="name"
                class="w-full rounded-lg border border-ink/10
                               bg-ink/5 px-4 py-2.5
                               text-sm text-ink/60
                               cursor-not-allowed">

            @error('name')
            <p class="mt-1 text-xs text-coral">
                {{ $message }}
            </p>
            @enderror

        </div>


        {{-- EMAIL --}}
        <div>

            <label
                for="email"
                class="block text-sm font-medium text-ink mb-2">
                Email
            </label>

            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username"
                class="w-full rounded-lg border border-ink/15
                       bg-paper px-4 py-2.5
                       text-sm text-ink
                       focus:border-amber
                       focus:ring-2 focus:ring-amber/20
                       outline-none transition">

            @error('email')
            <p class="mt-1 text-xs text-coral">
                {{ $message }}
            </p>
            @enderror

        </div>


        {{-- NIM & PRODI --}}
        @if($user->student)

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- NIM --}}
            <div>

                <label class="block text-sm font-medium text-ink mb-2">
                    NIM
                </label>

                <div class="relative">

                    <input
                        type="text"
                        value="{{ $user->student->nim }}"
                        disabled
                        class="w-full rounded-lg border border-ink/10
                                   bg-ink/5 px-4 py-2.5
                                   text-sm text-ink/60
                                   cursor-not-allowed">

                </div>

            </div>


            {{-- PROGRAM STUDI --}}
            <div>

                <label class="block text-sm font-medium text-ink mb-2">
                    Program Studi
                </label>

                <input
                    type="text"
                    value="{{ $user->student->prodi?->nama_prodi ?? '-' }}"
                    disabled
                    class="w-full rounded-lg border border-ink/10
                               bg-ink/5 px-4 py-2.5
                               text-sm text-ink/60
                               cursor-not-allowed">

            </div>

        </div>

        @endif


        {{-- BUTTON --}}
        <div class="flex items-center gap-4 pt-2">

            <button
                type="submit"
                class="inline-flex items-center gap-2
                       rounded-lg
                       bg-amber
                       px-5 py-2.5
                       text-sm font-medium text-white
                       hover:bg-amber/90
                       transition">

                <svg
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2">
                    <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
                    <path d="M17 21v-8H7v8M7 3v5h8" />
                </svg>

                Simpan Perubahan

            </button>


            @if (session('status') === 'profile-updated')

            <p class="text-xs text-teal font-mono">
                ✓ Profil berhasil diperbarui
            </p>

            @endif

        </div>

    </form>

</section>