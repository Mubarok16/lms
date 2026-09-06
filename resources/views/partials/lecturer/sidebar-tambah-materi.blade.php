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
     <a href="{{ route('lecturer.profile.edit') }}" class="block">
       <img
         src="{{ Auth::user()->profile_photo
            ? asset('storage/' . Auth::user()->profile_photo)
            : 'https://i.pravatar.cc/80?img=32' }}"
         class="w-11 h-11 rounded-full object-cover border-2 border-white/10 cursor-pointer hover:opacity-80 transition"
         alt="Foto Profil">
     </a>
     <div class="min-w-0">
       <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
       <p class="text-xs text-paper/45 truncate font-mono"> {{ Auth::user()->lecturer->nidn }} · {{ Auth::user()->lecturer?->prodi_id?->nama_prodi ?? '-' }}</p>
     </div>
   </div>
 </div>

 <!-- Navigasi utama -->
<nav class="flex-1 overflow-y-auto px-4 py-5">
  <div class="space-y-1">
    <a href="{{ route('lecturer.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('lecturer.dashboard') ? 'bg-white/10 text-paper' : 'text-paper/70 hover:bg-white/5 hover:text-paper' }} text-sm font-medium transition-colors">Dashboard</a>
    <a href="{{ route('lecturer.matakuliah.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('lecturer.matakuliah.*') || request()->routeIs('lecturer.pengajaran.*') ? 'bg-white/10 text-paper' : 'text-paper/70 hover:bg-white/5 hover:text-paper' }} text-sm font-medium transition-colors">Mata Kuliah Saya</a>
    <a href="{{ route('lecturer.tugas.kuis') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-paper/70 hover:bg-white/5 hover:text-paper text-sm font-medium transition-colors">Tugas & Kuis</a>
    <a href="{{ route('lecturer.nilai') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-paper/70 hover:bg-white/5 hover:text-paper text-sm font-medium transition-colors">Nilai</a>
    <a href="{{ route('jadwal.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-paper/70 hover:bg-white/5 hover:text-paper text-sm font-medium transition-colors">Jadwal Kuliah</a>
    <a href="{{ route('messages.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-paper/70 hover:bg-white/5 hover:text-paper text-sm font-medium transition-colors">Pesan</a>
  </div>
</nav>
<div class="px-4 py-4 border-t border-white/10 shrink-0 space-y-1">
  <a href="{{ route('lecturer.profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-paper/70 hover:bg-white/5 hover:text-paper text-sm font-medium transition-colors">Pengaturan</a>
  <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-paper/70 hover:bg-white/5 hover:text-paper text-sm font-medium transition-colors">Keluar</button></form>
</div>