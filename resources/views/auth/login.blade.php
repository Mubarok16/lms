<x-guest-layout>


    <!-- Login Card -->
    <div class="bg-white rounded-2xl border border-[#DCE6F5] shadow-xl overflow-hidden">

        <!-- Header -->
        <div class="bg-[#0F2A4D] px-8 py-8 text-center">

            <!-- Logo -->
            <div class="w-11 h-11 mx-auto rounded-lg bg-[#F5F8FC]/10 border border-white/10 flex items-center justify-center">
                <span class="font-display text-xl font-semibold text-[#93C5FD]">
                    U
                </span>
            </div>

            <h1 class="mt-4 font-display text-2xl font-semibold text-white">
                Selamat datang kembali.
            </h1>

            <p class="mt-2 text-sm text-white/60">
                Masuk untuk melanjutkan aktivitas perkuliahanmu di
                E-Learning UNWIR. </p>

        </div>


        <!-- Form -->
        <div class="px-8 py-8">

            <!-- Session Status -->
            <x-auth-session-status
                class="mb-5"
                :status="session('status')" />





            <form method="POST" action="{{ route('login') }}">

                @csrf


                <!-- Email -->
                <div>

                    <label
                        for="email"
                        class="block text-sm font-medium text-[#0F2A4D]">

                        Email

                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Masukkan email aktif"
                        class="mt-2 block w-full rounded-xl border-[#DCE6F5] bg-[#F5F8FC]/50 px-4 py-3 text-sm text-[#0F2A4D] placeholder:text-[#0F2A4D]/35 shadow-sm transition focus:border-[#2563EB] focus:ring-[#2563EB]">

                    <x-input-error
                        :messages="$errors->get('email')"
                        class="mt-2" />

                </div>


                <!-- Password -->
                <div class="mt-5">

                    <label
                        for="password"
                        class="block text-sm font-medium text-[#0F2A4D]">

                        Password

                    </label>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Masukkan password"
                        class="mt-2 block w-full rounded-xl border-[#DCE6F5] bg-[#F5F8FC]/50 px-4 py-3 text-sm text-[#0F2A4D] placeholder:text-[#0F2A4D]/35 shadow-sm transition focus:border-[#2563EB] focus:ring-[#2563EB]">

                    <x-input-error
                        :messages="$errors->get('password')"
                        class="mt-2" />

                </div>


                <!-- Remember & Forgot Password -->
                <div class="mt-5 flex items-center justify-between">

                    <label class="flex items-center cursor-pointer">

                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                            class="rounded border-[#DCE6F5] text-[#2563EB] shadow-sm focus:ring-[#2563EB]">

                        <span class="ml-2 text-sm text-[#0F2A4D]/60">
                            Ingat saya
                        </span>

                    </label>


                    @if (Route::has('password.request'))

                    <a
                        href="{{ route('password.request') }}"
                        class="text-sm font-medium text-[#2563EB] hover:text-[#0F2A4D] transition-colors">

                        Lupa password?

                    </a>

                    @endif

                </div>


                <!-- Login Button -->
                <button
                    type="submit"
                    class="mt-7 w-full rounded-full bg-[#1D4ED8] px-5 py-3.5 text-sm font-medium text-[#F5F8FC] shadow-[0_5px_0_0_#12326b] transition-all hover:bg-[#2563EB] active:translate-y-1 active:shadow-none focus:outline-none focus:ring-2 focus:ring-[#2563EB] focus:ring-offset-2">

                    Masuk ke E-Learning

                </button>

                <!-- Register Link -->
                <div class="mt-2 pt-2 border-t border-[#DCE6F5] text-center">
                    <p class="text-sm text-[#0F2A4D]/55">
                        Belum memiliki akun?
                        <a
                            href="{{ route('register') }}"
                            class="font-semibold text-[#2563EB] hover:text-[#0F2A4D] transition-colors">
                            Daftar sekarang →
                        </a>
                    </p>
                </div>

            </form>

        </div>


        <!-- Footer -->
        <div class="border-t border-[#DCE6F5] bg-[#F5F8FC]/60 px-8 py-4 text-center">

            <p class="font-mono text-[10px] uppercase tracking-wider text-[#0F2A4D]/40">

                © {{ date('Y') }} Universitas Wiralodra

            </p>

        </div>

    </div>



</x-guest-layout>