<x-guest-layout>



    <div class="w-full max-w-md">

        <!-- Register Card -->
        <div class="bg-white rounded-2xl border border-[#DCE6F5] shadow-xl overflow-hidden">

            <!-- Header -->
            <div class="bg-[#0F2A4D] px-6 py-6 text-center">

                <!-- Logo -->
                <div class="w-8 h-8 mx-auto rounded-lg bg-[#F5F8FC]/10 border border-white/10 flex items-center justify-center">
                    <span class="font-display text-xl font-semibold text-[#93C5FD]">
                        U
                    </span>
                </div>

                <h1 class="mt-1 font-display text-2xl font-semibold text-white">
                    Buat akun baru
                </h1>

                <p class="mt-1 text-sm text-white/60">
                    Lengkapi data berikut untuk mulai menggunakan E-Learning UNWIR.
                </p>

            </div>


            <!-- Form -->
            <div class="px-6 py-6">


                <form method="POST" action="{{ route('register') }}">

                    @csrf


                    <!-- Name -->
                    <div>

                        <label
                            for="name"
                            class="block text-sm font-medium text-[#0F2A4D]">

                            Nama Lengkap

                        </label>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Masukkan nama lengkap"
                            class="mt-2 block w-full rounded-xl border-[#DCE6F5] bg-[#F5F8FC]/50 px-4 py-3 text-sm text-[#0F2A4D] placeholder:text-[#0F2A4D]/35 shadow-sm transition focus:border-[#2563EB] focus:ring-[#2563EB]">

                        <x-input-error
                            :messages="$errors->get('name')"
                            class="mt-2" />

                    </div>


                    <!-- Email -->
                    <div class="mt-2">

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
                            autocomplete="username"
                            placeholder="Masukkan email aktif"
                            class="mt-2 block w-full rounded-xl border-[#DCE6F5] bg-[#F5F8FC]/50 px-4 py-3 text-sm text-[#0F2A4D] placeholder:text-[#0F2A4D]/35 shadow-sm transition focus:border-[#2563EB] focus:ring-[#2563EB]">

                        <x-input-error
                            :messages="$errors->get('email')"
                            class="mt-2" />

                    </div>


                    <!-- Password -->
                    <div class="mt-2">

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
                            autocomplete="new-password"
                            placeholder="Buat password"
                            class="mt-2 block w-full rounded-xl border-[#DCE6F5] bg-[#F5F8FC]/50 px-4 py-3 text-sm text-[#0F2A4D] placeholder:text-[#0F2A4D]/35 shadow-sm transition focus:border-[#2563EB] focus:ring-[#2563EB]">

                        <x-input-error
                            :messages="$errors->get('password')"
                            class="mt-2" />

                    </div>


                    <!-- Confirm Password -->
                    <div class="mt-2">

                        <label
                            for="password_confirmation"
                            class="block text-sm font-medium text-[#0F2A4D]">

                            Konfirmasi Password

                        </label>

                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Masukkan kembali password"
                            class="mt-2 block w-full rounded-xl border-[#DCE6F5] bg-[#F5F8FC]/50 px-4 py-3 text-sm text-[#0F2A4D] placeholder:text-[#0F2A4D]/35 shadow-sm transition focus:border-[#2563EB] focus:ring-[#2563EB]">

                        <x-input-error
                            :messages="$errors->get('password_confirmation')"
                            class="mt-2" />

                    </div>


                    <!-- Register Button -->
                    <button
                        type="submit"
                        class="mt-2 w-full rounded-full bg-[#1D4ED8] px-5 py-3.5 text-sm font-medium text-[#F5F8FC] shadow-[0_5px_0_0_#12326b] transition-all hover:bg-[#2563EB] active:translate-y-1 active:shadow-none focus:outline-none focus:ring-2 focus:ring-[#2563EB] focus:ring-offset-2">

                        Buat Akun & Mulai Belajar

                    </button>


                    <!-- Login Link -->
                    <div class="mt-2 pt-2 border-t border-[#DCE6F5] text-center">

                        <p class="text-sm text-[#0F2A4D]/55">

                            Sudah memiliki akun?

                            <a
                                href="{{ route('login') }}"
                                class="font-semibold text-[#2563EB] hover:text-[#0F2A4D] transition-colors">

                                Masuk sekarang →

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

    </div>



</x-guest-layout>