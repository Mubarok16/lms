<x-guest-layout>



        <div class="w-full max-w-md">

            <!-- Login Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

                <!-- Header -->
                <div class="bg-[#0F2A4D] px-8 py-10 text-center">

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

                    <!-- Session Status -->
                    <x-auth-session-status
                        class="mb-4"
                        :status="session('status')" />

                    <div class="mb-6">

                        <h2 class="text-2xl font-semibold text-gray-800">
                            Selamat Datang
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Silakan masuk untuk melanjutkan ke sistem.
                        </p>

                    </div>


                    <form method="POST" action="{{ route('login') }}">

                        @csrf


                        <!-- Email -->
                        <div>

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
                                autofocus
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
                                autocomplete="current-password"
                                placeholder="Masukkan password"
                                class="mt-2 block w-full rounded-lg border-gray-300
                            shadow-sm focus:border-[#2563EB]
                            focus:ring-[#2563EB]">

                            <x-input-error
                                :messages="$errors->get('password')"
                                class="mt-2" />

                        </div>


                        <!-- Remember & Forgot Password -->
                        <div class="mt-5 flex items-center justify-between">

                            <label class="flex items-center">

                                <input
                                    id="remember_me"
                                    type="checkbox"
                                    name="remember"
                                    class="rounded border-gray-300
                                text-[#2563EB]
                                shadow-sm
                                focus:ring-[#2563EB]">

                                <span class="ml-2 text-sm text-gray-600">
                                    Ingat saya
                                </span>

                            </label>


                            @if (Route::has('password.request'))

                            <a
                                href="{{ route('password.request') }}"
                                class="text-sm font-medium text-[#2563EB]
                                hover:text-[#0F2A4D]">
                                Lupa password?
                            </a>

                            @endif

                        </div>


                        <!-- Login Button -->
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
                            Masuk
                        </button>

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