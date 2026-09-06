<!-- Brand -->
<div class="h-20 flex items-center gap-2.5 px-6 border-b border-white/10 shrink-0">
  <span class="w-9 h-9 rounded-lg bg-amber/20 border border-amber/30 flex items-center justify-center">
    <span class="text-amber font-display font-semibold text-lg">U</span>
  </span>
  <div class="leading-tight">
    <p class="font-display text-lg font-semibold tracking-tight">E-Learning</p>
    <p class="text-[11px] font-mono uppercase tracking-wider text-paper/40">Univ. Wiralodra</p>
  </div>
  <button @click="sidebarOpen = false" class="ml-auto lg:hidden text-paper/50 hover:text-paper">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
      <path d="M18 6L6 18M6 6l12 12" />
    </svg>
  </button>
</div>

<!-- Profil singkat -->
<div class="px-6 py-5 border-b border-white/10 shrink-0">
  <div class="flex items-center gap-3">
    <a href="{{ route('profile.edit') }}" class="block">
      <img
        src="{{ Auth::user()->profile_photo
            ? asset('storage/' . Auth::user()->profile_photo)
            : 'https://i.pravatar.cc/80?img=32' }}"
        class="w-11 h-11 rounded-full object-cover border-2 border-white/10 cursor-pointer hover:opacity-80 transition"
        alt="Foto Profil">
    </a>
    <div class="min-w-0">
      <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
      <p class="text-xs text-paper/45 truncate font-mono"> {{ Auth::user()->student->nim }} · {{ Auth::user()->student?->prodi?->nama_prodi ?? '-' }}</p>
    </div>
  </div>
</div>

<!-- Navigasi utama + daftar isi kursus -->
<nav class="flex-1 overflow-y-auto px-4 py-5 space-y-6">

  <div class="space-y-1">
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-white/10 text-paper text-sm font-medium">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <rect x="3" y="3" width="7" height="9" rx="1.5" />
        <rect x="14" y="3" width="7" height="5" rx="1.5" />
        <rect x="14" y="12" width="7" height="9" rx="1.5" />
        <rect x="3" y="16" width="7" height="5" rx="1.5" />
      </svg>
      Dashboard
    </a>
    <a href="{{ route('student.matakuliah.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-paper/70 hover:bg-white/5 hover:text-paper text-sm font-medium transition-colors">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <path d="M4 19.5A2.5 2.5 0 016.5 17H20" />
        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" />
      </svg>
      Mata Kuliah Saya
    </a>
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-paper/70 hover:bg-white/5 hover:text-paper text-sm font-medium transition-colors">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <path d="M9 11l3 3L22 4" />
        <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
      </svg>
      Tugas & Kuis
      <span class="ml-auto bg-coral text-white text-[11px] font-mono px-1.5 py-0.5 rounded-full">3</span>
    </a>
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-paper/70 hover:bg-white/5 hover:text-paper text-sm font-medium transition-colors">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <path d="M3 3v18h18" />
        <path d="M7 15l4-6 3 4 5-8" />
      </svg>
      Nilai
    </a>
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-paper/70 hover:bg-white/5 hover:text-paper text-sm font-medium transition-colors">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <rect x="3" y="4" width="18" height="16" rx="2" />
        <path d="M3 10h18M8 2v4M16 2v4" />
      </svg>
      Jadwal Kuliah
    </a>
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-paper/70 hover:bg-white/5 hover:text-paper text-sm font-medium transition-colors">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" />
      </svg>
      Pesan
    </a>
  </div>

  <!-- Kursus yang sedang dibuka -->
  <div>
    <button @click="courseOpen = !courseOpen" class="w-full flex items-center gap-2 px-3 mb-2 text-[11px] font-mono uppercase tracking-wider text-paper/40">
      <span>Sedang Dibuka</span>
      <svg :class="courseOpen ? 'rotate-90' : ''" class="ml-auto transition-transform" width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.6">
        <path d="M4 2l4 4-4 4" />
      </svg>
    </button>

    <div x-show="courseOpen" x-cloak class="rounded-xl bg-white/5 border border-white/10 p-3">
      <p class="text-sm font-medium leading-snug">Pemrograman Web Lanjut</p>
      <p class="text-[11px] text-paper/40 font-mono mt-0.5 mb-3">TI · Semester 5 · 3 SKS</p>

      <ol class="space-y-0.5">
        <li>
          <a href="#" class="flex items-center gap-2.5 px-2 py-1.5 rounded-md text-paper/60 hover:bg-white/5 hover:text-paper text-[13px] transition-colors">
            <span class="w-4 h-4 rounded-full bg-teal/40 flex items-center justify-center shrink-0"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3">
                <path d="M20 6L9 17l-5-5" />
              </svg></span>
            Pengumuman Umum
          </a>
        </li>
        <li>
          <a href="#" class="flex items-center gap-2.5 px-2 py-1.5 rounded-md text-paper/60 hover:bg-white/5 hover:text-paper text-[13px] transition-colors">
            <span class="w-4 h-4 rounded-full bg-teal/40 flex items-center justify-center shrink-0"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3">
                <path d="M20 6L9 17l-5-5" />
              </svg></span>
            Pertemuan 1 — Pengantar REST API
          </a>
        </li>
        <li>
          <a href="#" class="flex items-center gap-2.5 px-2 py-1.5 rounded-md text-paper/60 hover:bg-white/5 hover:text-paper text-[13px] transition-colors">
            <span class="w-4 h-4 rounded-full bg-teal/40 flex items-center justify-center shrink-0"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3">
                <path d="M20 6L9 17l-5-5" />
              </svg></span>
            Pertemuan 2 — Autentikasi JWT
          </a>
        </li>
        <li>
          <a href="#" class="flex items-center gap-2.5 px-2 py-1.5 rounded-md bg-amber/15 text-paper text-[13px] font-medium">
            <span class="w-4 h-4 rounded-full border-2 border-amber flex items-center justify-center shrink-0"></span>
            Pertemuan 9 — Middleware & Validasi
          </a>
        </li>
        <li>
          <a href="#" class="flex items-center gap-2.5 px-2 py-1.5 rounded-md text-paper/40 hover:bg-white/5 hover:text-paper/70 text-[13px] transition-colors">
            <span class="w-4 h-4 rounded-full border border-white/20 shrink-0"></span>
            Pertemuan 10 — Testing API
          </a>
        </li>
        <li>
          <a href="#" class="flex items-center gap-2.5 px-2 py-1.5 rounded-md text-paper/40 hover:bg-white/5 hover:text-paper/70 text-[13px] transition-colors">
            <span class="w-4 h-4 rounded-full border border-white/20 shrink-0"></span>
            Pertemuan 11 — Deployment
          </a>
        </li>
      </ol>

      <div class="mt-3 pt-3 border-t border-white/10">
        <div class="flex items-center justify-between text-[11px] text-paper/40 mb-1.5 font-mono">
          <span>PROGRES</span><span>62%</span>
        </div>
        <div class="h-1.5 bg-white/10 rounded-full overflow-hidden">
          <div class="h-full bg-amber rounded-full" style="width:62%"></div>
        </div>
      </div>
    </div>
  </div>

</nav>

<!-- Bottom -->
<div class="px-4 py-4 border-t border-white/10 shrink-0 space-y-1">
  <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-paper/70 hover:bg-white/5 hover:text-paper text-sm font-medium transition-colors">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
      <circle cx="12" cy="12" r="3" />
      <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06A1.65 1.65 0 004.6 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06A1.65 1.65 0 009 4.6a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06A1.65 1.65 0 0019.4 9c.36.15.68.4.9.72" />
    </svg>
    Pengaturan
  </a>
  <form id="logout-form" method="POST" action="{{ route('logout') }}">
    @csrf

    <a href="{{ route('logout') }}"
      onclick="event.preventDefault(); this.closest('form').submit();"
      class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-paper/70 hover:bg-white/5 hover:text-paper text-sm font-medium transition-colors">

      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" />
        <path d="M16 17l5-5-5-5" />
        <path d="M21 12H9" />
      </svg>

      Keluar
    </a>
  </form>
</div>