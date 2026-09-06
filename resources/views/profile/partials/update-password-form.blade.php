<section>

    <form method="post"
        action="{{ route('password.update') }}"
        class="space-y-5">

        @csrf
        @method('put')


        {{-- PASSWORD SAAT INI --}}
        <div>

            <label
                for="current_password"
                class="block text-sm font-medium text-ink mb-2">
                Password Saat Ini
            </label>

            <input
                id="current_password"
                name="current_password"
                type="password"
                autocomplete="current-password"
                class="w-full rounded-lg border border-ink/15
                       bg-paper px-4 py-2.5
                       text-sm text-ink
                       focus:border-teal
                       focus:ring-2 focus:ring-teal/20
                       outline-none transition">

            @error('current_password', 'updatePassword')
            <p class="mt-1 text-xs text-coral">
                {{ $message }}
            </p>
            @enderror

        </div>


        {{-- PASSWORD BARU --}}
        <div>

            <label
                for="password"
                class="block text-sm font-medium text-ink mb-2">
                Password Baru
            </label>

            <input
                id="password"
                name="password"
                type="password"
                autocomplete="new-password"
                class="w-full rounded-lg border border-ink/15
                       bg-paper px-4 py-2.5
                       text-sm text-ink
                       focus:border-teal
                       focus:ring-2 focus:ring-teal/20
                       outline-none transition">

            @error('password', 'updatePassword')
            <p class="mt-1 text-xs text-coral">
                {{ $message }}
            </p>
            @enderror

        </div>


        {{-- KONFIRMASI PASSWORD --}}
        <div>

            <label
                for="password_confirmation"
                class="block text-sm font-medium text-ink mb-2">
                Konfirmasi Password Baru
            </label>

            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                autocomplete="new-password"
                class="w-full rounded-lg border border-ink/15
                       bg-paper px-4 py-2.5
                       text-sm text-ink
                       focus:border-teal
                       focus:ring-2 focus:ring-teal/20
                       outline-none transition">

            @error('password_confirmation', 'updatePassword')
            <p class="mt-1 text-xs text-coral">
                {{ $message }}
            </p>
            @enderror

        </div>


        {{-- BUTTON --}}
        <div class="pt-2">

            <button
                type="submit"
                class="inline-flex items-center gap-2
               rounded-lg
               bg-teal
               px-5 py-2.5
               text-sm font-medium text-white
               hover:bg-teal/90
               transition">

                <svg
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2">
                    <rect x="3" y="11" width="18" height="10" rx="2" />
                    <path d="M7 11V7a5 5 0 0110 0v4" />
                </svg>

                Ubah Password

            </button>

            {{-- NOTIFIKASI BERHASIL --}}
            @if (session('status') === 'password-updated')
            <div class="flex items-center gap-2 mt-3">

                <svg
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    class="text-teal">
                    <path d="M20 6L9 17l-5-5" />
                </svg>

                <p class="text-xs text-teal font-medium">
                    Password berhasil diperbarui.
                </p>

            </div>
            @endif

        </div>

    </form>

</section>