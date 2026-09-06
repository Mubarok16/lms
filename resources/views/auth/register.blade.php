<x-guest-layout>


        <div class="w-full max-w-md">

            <!-- Register Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

                <!-- Header -->
                <div class="bg-[#0F2A4D] px-6 py-6 text-center">

                    <h1 class="text-3xl font-bold text-white">
                        LMS UNWIR
                    </h1>

                    <p class="mt-2 text-sm text-blue-100">
                        Learning Management System
                    </p>

                    <p class="mt-1 text-xs text-blue-200">
                        Universitas Wiralodra
                    </p>

                </div>


                <!-- Form -->
                <div class="px-8 py-8">

                    <div class="mb-6">

                        <h2 class="text-2xl font-semibold text-gray-800">
                            Buat Akun
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Lengkapi data berikut untuk membuat akun baru.
                        </p>

                    </div>


                    <form method="POST" action="{{ route('register') }}">

                        @csrf


                        <!-- Name -->
                        <div>

                            <label
                                for="name"
                                class="block text-sm font-medium text-gray-700">
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
                                class="mt-2 block w-full rounded-lg border-gray-300
                            shadow-sm focus:border-[#2563EB]
                            focus:ring-[#2563EB]">

                            <x-input-error
                                :messages="$errors->get('name')"
                                class="mt-2" />

                        </div>


                        <!-- Email -->
                        <div class="mt-5">

                            <label
                                for="email"
                                class="block text-sm font-medium text-gray-700">
                                Email
                            </label>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="username"
                                placeholder="Masukkan email"
                                class="mt-2 block w-full rounded-lg border-gray-300
                            shadow-sm focus:border-[#2563EB]
                            focus:ring-[#2563EB]">

                            <x-input-error
                                :messages="$errors->get('email')"
                                class="mt-2" />

                        </div>


                        <!-- Password -->
                        <div class="mt-5">

                            <label
                                for="password"
                                class="block text-sm font-medium text-gray-700">
                                Password
                            </label>

                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Masukkan password"
                                class="mt-2 block w-full rounded-lg border-gray-300
                            shadow-sm focus:border-[#2563EB]
                            focus:ring-[#2563EB]">

                            <x-input-error
                                :messages="$errors->get('password')"
                                class="mt-2" />

                        </div>


                        <!-- Confirm Password -->
                        <div class="mt-5">

                            <label
                                for="password_confirmation"
                                class="block text-sm font-medium text-gray-700">
                                Konfirmasi Password
                            </label>

                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Masukkan kembali password"
                                class="mt-2 block w-full rounded-lg border-gray-300
                            shadow-sm focus:border-[#2563EB]
                            focus:ring-[#2563EB]">

                            <x-input-error
                                :messages="$errors->get('password_confirmation')"
                                class="mt-2" />

                        </div>


                        <!-- Register Button -->
                        <button
                            type="submit"
                            class="mt-6 w-full rounded-lg bg-[#0F2A4D]
                        px-4 py-3 text-sm font-semibold text-white
                        shadow-md transition duration-200
                        hover:bg-[#2563EB]
                        focus:outline-none
                        focus:ring-2
                        focus:ring-[#2563EB]
                        focus:ring-offset-2">
                            Daftar Sekarang
                        </button>


                        <!-- Login Link -->
                        <div class="mt-6 text-center">

                            <p class="text-sm text-gray-500">

                                Sudah memiliki akun?

                                <a
                                    href="{{ route('login') }}"
                                    class="font-semibold text-[#2563EB]
                                hover:text-[#0F2A4D]">
                                    Masuk
                                </a>

                            </p>

                        </div>

                    </form>

                </div>


                <!-- Footer -->
                <div class="border-t border-gray-100 px-8 py-4 text-center">

                    <p class="text-xs text-gray-400">
                        © {{ date('Y') }} Universitas Wiralodra
                    </p>

                </div>

            </div>

        </div>



</x-guest-layout>