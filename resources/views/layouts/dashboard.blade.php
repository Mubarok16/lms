<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — E-Learning UNWIR</title>
 @vite(['resources/css/app.css', 'resources/js/app.js'])
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
  body { background-color: #F5F8FC; }
  ::-webkit-scrollbar { width: 6px; height: 6px; }
  ::-webkit-scrollbar-thumb { background: #B9CDEE; border-radius: 999px; }
  ::-webkit-scrollbar-track { background: transparent; }
</style>
</head>
<body class="font-sans text-ink antialiased">

<div x-data="{ sidebarOpen: false, courseOpen: true }" class="min-h-screen flex">

  <!-- ============ OVERLAY (mobile) ============ -->
  <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
       class="fixed inset-0 bg-ink/40 z-30 lg:hidden"></div>
<!-- ============ SIDEBAR ============ -->
  <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed lg:sticky top-0 left-0 h-screen w-72 shrink-0 bg-ink text-paper z-40 flex flex-col transition-transform duration-200">
@include('partials.sidebar')
    </aside>

  <!-- ============ MAIN ============ -->
  <div class="flex-1 min-w-0 flex flex-col">

    <!-- Topbar -->
    <header class="h-20 bg-white border-b border-line flex items-center gap-4 px-5 lg:px-8 sticky top-0 z-20">
      <button @click="sidebarOpen = true" class="lg:hidden text-ink/60 hover:text-ink">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
      </button>

      <!-- <div class="relative hidden sm:block w-full max-w-xs">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-ink/30" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/></svg>
        <input type="text" placeholder="Cari mata kuliah..." class="w-full pl-9 pr-3 py-2.5 rounded-lg bg-paper border border-line text-sm focus:outline-none focus:ring-2 focus:ring-teal/30 focus:border-teal">
      </div> -->

      <div class="ml-auto flex items-center gap-4">
        <button class="relative text-ink/50 hover:text-ink">
          <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 8a6 6 0 00-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
          <span class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-coral"></span>
        </button>
        <div class="w-px h-6 bg-line hidden sm:block"></div>
        <div class="flex items-center gap-2.5">
          <img src="https://i.pravatar.cc/80?img=32" class="w-9 h-9 rounded-full object-cover" alt="">
          <div class="hidden sm:block leading-tight">
            <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
            <p class="text-xs text-ink/45">{{ Auth::user()->role }}</p>
          </div>
        </div>
      </div>
    </header>

    <!-- Konten -->
    <main class="flex-1 p-5 lg:p-8">

      <div class="mb-7">
        <p class="text-sm text-ink/50 font-mono">Pemrograman Web Lanjut / Pertemuan 9</p>
        <h1 class="font-display text-2xl lg:text-3xl font-semibold tracking-tight mt-1">Middleware & Validasi Request</h1>
      </div>

      <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">

          <div class="bg-white border border-line rounded-2xl p-6">
            <h2 class="font-display text-lg font-medium">Ringkasan Materi</h2>
            <p class="text-sm text-ink/60 leading-relaxed mt-3">
              Pada pertemuan ini mahasiswa mempelajari cara membuat middleware kustom di Laravel untuk memvalidasi request masuk sebelum mencapai controller, termasuk penanganan error terstruktur.
            </p>
            <div class="flex flex-wrap gap-3 mt-5">
              <a href="#" class="inline-flex items-center gap-2 text-sm font-medium bg-ink text-paper px-4 py-2.5 rounded-lg hover:bg-ink/90 transition-colors">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                Slide Pertemuan 9.pdf
              </a>
              <a href="#" class="inline-flex items-center gap-2 text-sm font-medium border border-line px-4 py-2.5 rounded-lg hover:border-ink/30 transition-colors">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M23 7l-7 5 7 5V7z"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
                Video Rekaman Kelas
              </a>
            </div>
          </div>

          <div class="bg-white border border-line rounded-2xl p-6">
            <div class="flex items-center justify-between">
              <h2 class="font-display text-lg font-medium">Tugas Pertemuan 9</h2>
              <span class="text-xs font-mono px-2.5 py-1 rounded-full bg-coral/10 text-coral">Batas: 3 hari lagi</span>
            </div>
            <p class="text-sm text-ink/60 leading-relaxed mt-3">
              Buat middleware validasi untuk endpoint pendaftaran mata kuliah, lalu kumpulkan berupa repositori GitHub.
            </p>
            <a href="#" class="inline-flex items-center gap-1.5 text-sm font-medium text-teal mt-4 hover:gap-2.5 transition-all">
              Kumpulkan tugas
              <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M1 7H13M13 7L7.5 1.5M13 7L7.5 12.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
          </div>

        </div>

        <!-- Panel kanan: progres & pengumuman -->
        <div class="space-y-6">
          <div class="bg-white border border-line rounded-2xl p-6">
            <h3 class="font-display text-base font-medium">Progres Mata Kuliah</h3>
            <div class="mt-4 h-1.5 bg-line rounded-full overflow-hidden">
              <div class="h-full bg-teal rounded-full" style="width:62%"></div>
            </div>
            <p class="text-xs text-ink/50 mt-2">9 dari 16 pertemuan selesai</p>
          </div>

          <div class="bg-ink text-paper rounded-2xl p-6">
            <h3 class="font-display text-base font-medium">Pengumuman</h3>
            <p class="text-sm text-paper/70 leading-relaxed mt-3">UTS akan dilaksanakan daring pada 14 Oktober 2026 pukul 08.00 melalui menu Tugas & Kuis.</p>
            <p class="text-xs text-paper/40 mt-3 font-mono">2 hari lalu · Dosen Pengampu</p>
          </div>
        </div>
      </div>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>