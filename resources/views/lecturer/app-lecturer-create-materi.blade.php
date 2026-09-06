<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — E-Learning UNWIR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;0,9..144,700&family=Inter:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink: '#0F2A4D',
                        paper: '#F5F8FC',
                        amber: '#60A5FA',
                        teal: '#2563EB',
                        coral: '#1D4ED8',
                        line: '#DCE6F5',
                    },
                    fontFamily: {
                        display: ['Fraunces', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                        mono: ['IBM Plex Mono', 'monospace'],
                    },
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #F5F8FC;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: #B9CDEE;
            border-radius: 999px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }
    </style>

    {{-- Tempat menampung CSS tambahan dari halaman anak, misal CDN Quill --}}
    @stack('styles')
</head>

<body class="font-sans text-ink antialiased">

    <div x-data="{ sidebarOpen: false, courseOpen: true }" class="min-h-screen flex">

        <!-- ============ OVERLAY (mobile) ============ -->
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
            class="fixed inset-0 bg-ink/40 z-30 lg:hidden"></div>

        <!-- ============ SIDEBAR ============ -->
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed lg:sticky top-0 left-0 h-screen w-72 shrink-0 bg-ink text-paper z-40 flex flex-col transition-transform duration-200">
            @include('partials.lecturer.sidebar-tambah-materi')

        </aside>

        <!-- ============ MAIN ============ -->
        <div class="flex-1 min-w-0 flex flex-col">

            <!-- Topbar -->
            <header class="h-20 bg-white border-b border-line flex items-center gap-4 px-5 lg:px-8 sticky top-0 z-20">
                <button @click="sidebarOpen = true" class="lg:hidden text-ink/60 hover:text-ink">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M3 6h18M3 12h18M3 18h18" />
                    </svg>
                </button>

                <div class="relative hidden sm:block w-full max-w-xs">
                    <!-- <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-ink/30" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="11" cy="11" r="8" />
                        <path d="M21 21l-4.3-4.3" />
                    </svg> -->
                    <!-- <input type="text" placeholder="Cari mata kuliah..." class="w-full pl-9 pr-3 py-2.5 rounded-lg bg-paper border border-line text-sm focus:outline-none focus:ring-2 focus:ring-teal/30 focus:border-teal"> -->
                </div>

                <div class="ml-auto flex items-center gap-4">

                    {{-- Notification --}}
                    <button class="relative text-ink/50 hover:text-ink transition">
                        <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M18 8a6 6 0 00-12 0c0 7-3 9-3 9h18s-3-2-3-9" />
                            <path d="M13.73 21a2 2 0 01-3.46 0" />
                        </svg>

                        <span class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-coral"></span>
                    </button>

                    <div class="w-px h-6 bg-line hidden sm:block"></div>

                    {{-- Profile Dropdown --}}
                    <div class="relative" x-data="{ open: false }">

                        {{-- Button Profile --}}
                        <button
                            @click="open = !open"
                            class="flex items-center gap-2.5 focus:outline-none">

                            <img
                                src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo)  : asset('images/default-profile.png') }}"
                                class="w-9 h-9 rounded-full object-cover"
                                alt="{{ Auth::user()->name }}">

                            <div class="hidden sm:block leading-tight text-left">
                                <p class="text-sm font-medium">
                                    {{ Auth::user()->name }}
                                </p>

                                <p class="text-xs text-ink/45">
                                    {{ Auth::user()->role }}
                                </p>
                            </div>

                            {{-- Arrow --}}
                            <svg
                                class="w-4 h-4 text-ink/50 transition-transform"
                                :class="{ 'rotate-180': open }"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>

                        </button>

                        {{-- Dropdown Menu --}}
                        <div
                            x-show="open"
                            @click.outside="open = false"
                            x-transition
                            class="absolute right-0 mt-3 w-44 bg-white border border-line rounded-xl shadow-lg overflow-hidden z-50">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button
                                    type="submit"
                                    class="w-full flex items-center gap-2 px-4 py-3 text-sm text-red-500 hover:bg-red-50 transition">
                                    {{-- Logout Icon --}}
                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                                    </svg>

                                    Logout
                                </button>
                            </form>

                        </div>

                    </div>

                </div>
            </header>

            <main class="flex-1 p-5 lg:p-8">

                <div class="mb-7">
                    <p class="text-sm text-ink/50 font-mono">@yield('ketjudul')</p>
                    <h1 class="font-display text-2xl lg:text-3xl font-semibold tracking-tight mt-1">@yield('judul')
                    </h1>
                </div>
                <div class="bg-white border border-line rounded-2xl p-6 mt-6">
                    @if ($errors->any())
                    <div class="mb-5 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700">
                        <p class="text-sm font-semibold">
                            Data gagal disimpan
                        </p>

                        <ul class="mt-1 text-sm list-disc list-inside">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @if (session('success'))
                    <div class="mb-5 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700">
                        <p class="text-sm font-semibold">
                            Data berhasil disimpan
                        </p>
                    </div>
                    @endif
                    @if (session('error'))
                    <div class="mb-5 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700">
                        <p class="text-sm font-semibold">
                            Data gagal disimpan
                        </p>
                        <ul class="text-sm space-y-1 list-disc list-inside">

                            @foreach (session('error') as $error)
                            <li>
                                Baris {{ $error['row'] }}:
                                {{ implode(', ', $error['errors']) }}
                            </li>
                            @endforeach

                        </ul>
                    </div>
                    @endif
                    @yield('content')
                </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Tempat menampung JS tambahan dari halaman anak, misal script Quill --}}
    @stack('scripts')
</body>

</html>