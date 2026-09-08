<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- logo unwir -->
  <link rel="icon" type="image/png" href="{{ asset('image/logoUnwir.png') }}">
  <title>{{ config('app.name', 'Laravel') }}</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Inter:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
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

    .dot-grid {
      background-image: radial-gradient(#C7D8F0 1px, transparent 1px);
      background-size: 22px 22px;
    }

    .highlight-mark {
      position: relative;
      white-space: nowrap;
    }

    .highlight-mark::after {
      content: "";
      position: absolute;
      left: -2px;
      right: -2px;
      bottom: 2px;
      height: 0.5em;
      background: #93C5FD;
      z-index: -1;
      transform: rotate(-1deg);
    }

    .tab-label {
      writing-mode: vertical-rl;
    }

    ::selection {
      background: #93C5FD;
      color: #0F2A4D;
    }
  </style>
</head>

<body class="font-sans text-ink antialiased">

  <!-- ============ NAVBAR ============ -->
  <header class="sticky top-0 z-50 bg-paper/90 backdrop-blur border-b border-line">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 h-20 flex items-center justify-between">
      <a href="/" class="flex items-center gap-2.5">
        <span class="w-9 h-9 rounded-lg bg-ink flex items-center justify-center">
          <span class="text-amber font-display font-semibold text-lg">U</span>
        </span>
        <span class="font-display text-xl font-semibold tracking-tight">E-Learning UNWIR</span>
      </a>

      <nav class="hidden lg:flex items-center gap-9 text-[15px] font-medium text-ink/70">
        <a href="#fitur" class="hover:text-ink transition-colors">Fitur</a>
        <a href="#kursus" class="hover:text-ink transition-colors">Mata Kuliah</a>
        <a href="#cara-kerja" class="hover:text-ink transition-colors">Cara Pakai</a>
        <a href="#testimoni" class="hover:text-ink transition-colors">Testimoni</a>

      </nav>

      <div class="flex items-center gap-3">
        @if (Route::has('login'))
        @auth
        <a
          href="{{ url('/dashboard') }}"
          class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
          Dashboard
        </a>
        @else
        <a
          href="{{ route('login') }}"
          class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
          Log in
        </a>

        @if (Route::has('register'))
        <a
          href="{{ route('register') }}"
          class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
          Register
        </a>
        @endif
        @endauth
        @endif

      </div>
    </div>
  </header>

  <!-- ============ HERO ============ -->
  <section class="relative overflow-hidden dot-grid">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 pt-20 pb-24 lg:pt-28 lg:pb-32 grid lg:grid-cols-2 gap-16 items-center">

      <div>
        <span class="inline-flex items-center gap-2 font-mono text-xs uppercase tracking-wider text-teal bg-teal/10 border border-teal/20 rounded-full px-3 py-1.5">
          <span class="w-1.5 h-1.5 rounded-full bg-teal"></span>
          Semester Genap 2025/2026 — Perkuliahan Aktif
        </span>

        <h1 class="font-display text-[2.75rem] sm:text-5xl lg:text-[3.4rem] leading-[1.08] font-semibold tracking-tight mt-6">
          Satu tempat untuk<br class="hidden sm:block">
          <span class="highlight-mark">semua perkuliahanmu</span> di <em class="italic font-medium">UNWIR.</em>
        </h1>

        <p class="text-lg text-ink/65 leading-relaxed mt-6 max-w-md">
          E-Learning UNWIR menyatukan materi kuliah, tugas, kuis, dan nilai dalam satu dashboard — khusus untuk mahasiswa Universitas Wiralodra.
        </p>

        <div class="flex flex-wrap items-center gap-4 mt-9">
          <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-coral text-paper font-medium px-6 py-3.5 rounded-full hover:bg-coral/90 transition-colors shadow-[0_6px_0_0_#12326b] active:translate-y-1 active:shadow-none">
            Masuk dengan Akun SIMAKO
          </a>
          <a href="#cara-kerja" class="inline-flex items-center gap-2 font-medium text-ink/80 hover:text-ink px-2 py-3.5 transition-colors">
            <span class="w-9 h-9 rounded-full border border-ink/20 flex items-center justify-center">
              <svg width="12" height="14" viewBox="0 0 12 14" fill="none">
                <path d="M11 7L0.5 13.06V0.94L11 7Z" fill="currentColor" />
              </svg>
            </span>
            Lihat cara pakainya
          </a>
        </div>

        <div class="flex items-center gap-5 mt-10 pt-8 border-t border-line">
          <div class="flex -space-x-3">
            <img class="w-10 h-10 rounded-full border-2 border-paper object-cover" src="https://i.pravatar.cc/80?img=32" alt="">
            <img class="w-10 h-10 rounded-full border-2 border-paper object-cover" src="https://i.pravatar.cc/80?img=47" alt="">
            <img class="w-10 h-10 rounded-full border-2 border-paper object-cover" src="https://i.pravatar.cc/80?img=15" alt="">
          </div>
          <p class="text-sm text-ink/60">
            Digunakan oleh
            <span class="font-semibold text-ink">
              {{ number_format($jumlahMahasiswa, 0, ',', '.') }}+
            </span>
            mahasiswa Universitas Wiralodra
          </p>
        </div>
      </div>

      <!-- hero visual: stacked course cards like index cards on a desk -->
      <div class="relative h-[440px] hidden lg:block">

        @foreach ($matakuliah as $index => $mk)

        <div class="
            absolute
            {{ $index == 0 ? 'right-6 top-2 rotate-[4deg]' : 'left-2 top-32 -rotate-[3deg]' }}
            w-80 bg-white border border-line rounded-2xl shadow-xl p-5
        ">

          <div class="flex items-center justify-between">

            {{-- Nama Program Studi --}}
            <span class="
                    font-mono text-[11px] uppercase tracking-wider
                    {{ $index == 0 ? 'text-teal bg-teal/10' : 'text-coral bg-coral/10' }}
                    px-2 py-1 rounded
                ">
              {{ $mk->prodi->nama_prodi ?? 'Program Studi' }}
            </span>

            {{-- Kode Mata Kuliah --}}
            <span class="font-mono text-[11px] text-ink/40">
              {{ $mk->kode_mk }}
            </span>

          </div>

          {{-- Nama Mata Kuliah --}}
          <h3 class="font-display text-lg font-medium mt-3">
            {{ $mk->nama_mk }}
          </h3>

          {{-- SKS --}}
          <p class="text-xs text-ink/50 mt-4">
            {{ $mk->sks }} SKS
          </p>

        </div>

        @endforeach

      </div>

    </div>
  </section>

  <!-- ============ STATS STRIP ============ -->
  <section class="border-y border-line bg-ink text-paper">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 py-10 grid grid-cols-2 lg:grid-cols-4 gap-8">

      {{-- Jumlah Mata Kuliah --}}
      <div>
        <p class="font-display text-3xl font-semibold">
          {{ number_format($jumlahMatakuliah, 0, ',', '.') }}+
        </p>
        <p class="text-sm text-paper/50 mt-1">
          Mata kuliah daring
        </p>
      </div>

      {{-- Jumlah Mahasiswa --}}
      <div>
        <p class="font-display text-3xl font-semibold">
          {{ number_format($jumlahMahasiswa, 0, ',', '.') }}+
        </p>
        <p class="text-sm text-paper/50 mt-1">
          Mahasiswa aktif
        </p>
      </div>

      {{-- Jumlah Program Studi --}}
      <div>
        <p class="font-display text-3xl font-semibold">
          {{ number_format($jumlahProdi, 0, ',', '.') }}
        </p>
        <p class="text-sm text-paper/50 mt-1">
          Program Studi terhubung
        </p>
      </div>

      {{-- Jumlah Dosen --}}
      <div>
        <p class="font-display text-3xl font-semibold">
          {{ number_format($jumlahDosen, 0, ',', '.') }}+
        </p>
        <p class="text-sm text-paper/50 mt-1">
          Dosen pengampu
        </p>
      </div>

    </div>
  </section>

  <!-- ============ FITUR ============ -->
  <section id="fitur" class="max-w-7xl mx-auto px-6 lg:px-10 py-24 lg:py-28">
    <div class="max-w-xl">
      <span class="font-mono text-xs uppercase tracking-wider text-ink/40">Kenapa E-Learning UNWIR</span>
      <h2 class="font-display text-3xl lg:text-4xl font-semibold tracking-tight mt-3">
        Dibangun untuk mendukung proses belajar-mengajar di UNWIR, dari kelas sampai nilai akhir.
      </h2>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-14">

      <div class="border border-line rounded-2xl p-7 hover:border-ink/30 hover:-translate-y-1 transition-all bg-white">
        <div class="w-11 h-11 rounded-lg bg-amber/20 flex items-center justify-center">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0F2A4D" stroke-width="1.8">
            <path d="M12 3l9 4.5-9 4.5-9-4.5L12 3z" />
            <path d="M7 10.5V16c0 1.1 2.24 3 5 3s5-1.9 5-3v-5.5" />
          </svg>
        </div>
        <h3 class="font-display text-lg font-medium mt-5">Materi per pertemuan</h3>
        <p class="text-sm text-ink/60 leading-relaxed mt-2">Materi kuliah disusun per pertemuan sesuai RPS, bukan tumpukan file tanpa urutan.</p>
      </div>

      <div class="border border-line rounded-2xl p-7 hover:border-ink/30 hover:-translate-y-1 transition-all bg-white">
        <div class="w-11 h-11 rounded-lg bg-teal/15 flex items-center justify-center">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0F2A4D" stroke-width="1.8">
            <path d="M9 11l3 3L22 4" />
            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
          </svg>
        </div>
        <h3 class="font-display text-lg font-medium mt-5">Tugas & kuis daring</h3>
        <p class="text-sm text-ink/60 leading-relaxed mt-2">Dosen dapat memberi tugas, kuis, dan ujian daring lengkap dengan batas waktu pengumpulan.</p>
      </div>

      <div class="border border-line rounded-2xl p-7 hover:border-ink/30 hover:-translate-y-1 transition-all bg-white">
        <div class="w-11 h-11 rounded-lg bg-coral/15 flex items-center justify-center">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0F2A4D" stroke-width="1.8">
            <circle cx="12" cy="8" r="4" />
            <path d="M4 21v-1a7 7 0 0114 0v1" />
          </svg>
        </div>
        <h3 class="font-display text-lg font-medium mt-5">Diskusi langsung dengan dosen</h3>
        <p class="text-sm text-ink/60 leading-relaxed mt-2">Forum diskusi di tiap mata kuliah memudahkan mahasiswa bertanya langsung ke dosen pengampu.</p>
      </div>

      <div class="border border-line rounded-2xl p-7 hover:border-ink/30 hover:-translate-y-1 transition-all bg-white">
        <div class="w-11 h-11 rounded-lg bg-amber/20 flex items-center justify-center">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0F2A4D" stroke-width="1.8">
            <rect x="3" y="4" width="18" height="16" rx="2" />
            <path d="M3 10h18M8 2v4M16 2v4" />
          </svg>
        </div>
        <h3 class="font-display text-lg font-medium mt-5">Jadwal kuliah terintegrasi</h3>
        <p class="text-sm text-ink/60 leading-relaxed mt-2">Jadwal kelas daring maupun tatap muka tersinkron otomatis dengan kalender akademik.</p>
      </div>

      <div class="border border-line rounded-2xl p-7 hover:border-ink/30 hover:-translate-y-1 transition-all bg-white">
        <div class="w-11 h-11 rounded-lg bg-teal/15 flex items-center justify-center">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0F2A4D" stroke-width="1.8">
            <path d="M12 15a4 4 0 004-4V6a4 4 0 00-8 0v5a4 4 0 004 4z" />
            <path d="M19 11a7 7 0 01-14 0M12 19v3" />
          </svg>
        </div>
        <h3 class="font-display text-lg font-medium mt-5">Presensi otomatis</h3>
        <p class="text-sm text-ink/60 leading-relaxed mt-2">Kehadiran tercatat otomatis saat mahasiswa mengakses materi atau mengikuti kelas daring.</p>
      </div>

      <div class="border border-line rounded-2xl p-7 hover:border-ink/30 hover:-translate-y-1 transition-all bg-white">
        <div class="w-11 h-11 rounded-lg bg-coral/15 flex items-center justify-center">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0F2A4D" stroke-width="1.8">
            <path d="M3 3v18h18" />
            <path d="M7 15l4-6 3 4 5-8" />
          </svg>
        </div>
        <h3 class="font-display text-lg font-medium mt-5">Rekap nilai real-time</h3>
        <p class="text-sm text-ink/60 leading-relaxed mt-2">Nilai tugas, kuis, UTS, dan UAS bisa dipantau mahasiswa kapan saja tanpa menunggu pengumuman.</p>
      </div>

    </div>
  </section>

  <!-- ============ KURSUS POPULER ============ -->
  <section id="kursus" class="bg-white border-y border-line">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 py-24 lg:py-28">
      <div class="flex flex-wrap items-end justify-between gap-6">
        <div>
          <span class="font-mono text-xs uppercase tracking-wider text-ink/40">Mata Kuliah Aktif</span>
          <h2 class="font-display text-3xl lg:text-4xl font-semibold tracking-tight mt-3">Mata kuliah yang paling banyak diakses mahasiswa.</h2>
        </div>
        <a href="#" class="inline-flex items-center gap-1.5 font-medium text-ink border-b border-ink/30 hover:border-ink pb-0.5 transition-colors">
          Lihat semua mata kuliah
          <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
            <path d="M1 7H13M13 7L7.5 1.5M13 7L7.5 12.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </a>
      </div>

      <!-- <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-7 mt-14">

        <article class="group border border-line rounded-2xl overflow-hidden hover:shadow-lg transition-shadow">
          <div class="h-44 bg-gradient-to-br from-teal to-ink relative overflow-hidden">
            <span class="absolute top-3 left-3 font-mono text-[11px] uppercase tracking-wider bg-white/90 text-ink px-2.5 py-1 rounded-full">Teknik Informatika</span>
          </div>
          <div class="p-6">
            <div class="flex items-center gap-1 text-ink/50 text-xs font-mono">Prodi Teknik Informatika · Semester 5</div>
            <h3 class="font-display text-lg font-medium mt-2">Pemrograman Web Lanjut</h3>
            <p class="text-sm text-ink/55 mt-1.5">3 SKS · 16 pertemuan · Ganjil 2025/2026</p>
            <div class="flex items-center justify-between mt-5 pt-5 border-t border-line">
              <span class="font-semibold">142 mahasiswa</span>
              <span class="text-sm text-teal font-medium group-hover:translate-x-1 transition-transform">Lihat kelas →</span>
            </div>
          </div>
        </article>

        <article class="group border border-line rounded-2xl overflow-hidden hover:shadow-lg transition-shadow">
          <div class="h-44 bg-gradient-to-br from-coral to-ink relative overflow-hidden">
            <span class="absolute top-3 left-3 font-mono text-[11px] uppercase tracking-wider bg-white/90 text-ink px-2.5 py-1 rounded-full">Manajemen</span>
          </div>
          <div class="p-6">
            <div class="flex items-center gap-1 text-ink/50 text-xs font-mono">Prodi Manajemen · Semester 3</div>
            <h3 class="font-display text-lg font-medium mt-2">Manajemen Keuangan Perusahaan</h3>
            <p class="text-sm text-ink/55 mt-1.5">3 SKS · 16 pertemuan · Ganjil 2025/2026</p>
            <div class="flex items-center justify-between mt-5 pt-5 border-t border-line">
              <span class="font-semibold">118 mahasiswa</span>
              <span class="text-sm text-teal font-medium group-hover:translate-x-1 transition-transform">Lihat kelas →</span>
            </div>
          </div>
        </article>

        <article class="group border border-line rounded-2xl overflow-hidden hover:shadow-lg transition-shadow">
          <div class="h-44 bg-gradient-to-br from-amber to-ink relative overflow-hidden">
            <span class="absolute top-3 left-3 font-mono text-[11px] uppercase tracking-wider bg-white/90 text-ink px-2.5 py-1 rounded-full">Hukum</span>
          </div>
          <div class="p-6">
            <div class="flex items-center gap-1 text-ink/50 text-xs font-mono">Prodi Ilmu Hukum · Semester 1</div>
            <h3 class="font-display text-lg font-medium mt-2">Pengantar Hukum Perdata</h3>
            <p class="text-sm text-ink/55 mt-1.5">2 SKS · 16 pertemuan · Ganjil 2025/2026</p>
            <div class="flex items-center justify-between mt-5 pt-5 border-t border-line">
              <span class="font-semibold">96 mahasiswa</span>
              <span class="text-sm text-teal font-medium group-hover:translate-x-1 transition-transform">Lihat kelas →</span>
            </div>
          </div>
        </article>

      </div> -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-7 mt-14">

        @forelse ($kursusPopuler as $index => $item)

        @php
        $warna = match ($index) {
        0 => 'from-teal to-ink',
        1 => 'from-coral to-ink',
        default => 'from-amber to-ink',
        };
        @endphp

        <article class="group border border-line rounded-2xl overflow-hidden hover:shadow-lg transition-shadow">

          {{-- Header Card --}}
          <div class="h-44 bg-gradient-to-br {{ $warna }} relative overflow-hidden">

            {{-- Nama Prodi --}}
            <span class="absolute top-3 left-3 font-mono text-[11px] uppercase tracking-wider bg-white/90 text-ink px-2.5 py-1 rounded-full">
              {{ $item->kelas->matakuliah->prodi->nama_prodi ?? 'Program Studi' }}
            </span>

          </div>

          {{-- Isi Card --}}
          <div class="p-6">

            {{-- Prodi dan Kode Kelas --}}
            <!-- <div class="flex items-center gap-1 text-ink/50 text-xs font-mono">
              Prodi {{ $item->kelas->matakuliah->prodi->nama_prodi ?? '-' }}
              · Semester {{ $item->kelas->semester ?? '-' }}
            </div> -->

            {{-- Nama Mata Kuliah --}}
            <h3 class="font-display text-lg font-medium mt-2">
              {{ $item->kelas->matakuliah->nama_mk ?? 'Mata Kuliah' }}
            </h3>

            {{-- SKS dan Kode Mata Kuliah --}}
            <p class="text-sm text-ink/55 mt-1.5">
              {{ $item->kelas->matakuliah->sks ?? 0 }} SKS
              · {{ $item->kelas->matakuliah->kode_mk ?? '-' }}
            </p>

            {{-- Jumlah Mahasiswa --}}
            <div class="flex items-center justify-between mt-5 pt-5 border-t border-line">

              <span class="font-semibold">
                {{ number_format($item->jumlah_mahasiswa, 0, ',', '.') }} mahasiswa
              </span>

              <span class="text-sm text-teal font-medium group-hover:translate-x-1 transition-transform">
                Lihat kelas →
              </span>

            </div>

          </div>

        </article>

        @empty

        <div class="col-span-full text-center py-12">
          <p class="text-ink/50">
            Belum ada mata kuliah yang tersedia.
          </p>
        </div>

        @endforelse

      </div>
    </div>
  </section>

  <!-- ============ CARA KERJA ============ -->
  <section id="cara-kerja" class="max-w-7xl mx-auto px-6 lg:px-10 py-24 lg:py-28">
    <div class="max-w-xl">
      <span class="font-mono text-xs uppercase tracking-wider text-ink/40">Cara Pakai</span>
      <h2 class="font-display text-3xl lg:text-4xl font-semibold tracking-tight mt-3">Empat langkah, dari login sampai lihat nilai.</h2>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 mt-14 relative">
      <div class="hidden lg:block absolute top-6 left-0 right-0 h-px bg-line"></div>

      <div class="relative">
        <span class="font-mono text-sm w-12 h-12 rounded-full bg-ink text-paper flex items-center justify-center relative z-10">01</span>
        <h3 class="font-display text-lg font-medium mt-5">Masuk dengan akun SIMAKO</h3>
        <p class="text-sm text-ink/60 leading-relaxed mt-2">Gunakan NIM dan kata sandi SIMAKO kamu untuk masuk ke E-Learning UNWIR.</p>
      </div>
      <div class="relative">
        <span class="font-mono text-sm w-12 h-12 rounded-full bg-ink text-paper flex items-center justify-center relative z-10">02</span>
        <h3 class="font-display text-lg font-medium mt-5">Akses mata kuliah aktif</h3>
        <p class="text-sm text-ink/60 leading-relaxed mt-2">Semua mata kuliah yang kamu ambil di KRS otomatis muncul di dashboard.</p>
      </div>
      <div class="relative">
        <span class="font-mono text-sm w-12 h-12 rounded-full bg-ink text-paper flex items-center justify-center relative z-10">03</span>
        <h3 class="font-display text-lg font-medium mt-5">Kerjakan tugas & kuis</h3>
        <p class="text-sm text-ink/60 leading-relaxed mt-2">Unggah tugas dan ikuti kuis sesuai tenggat waktu yang ditentukan dosen.</p>
      </div>
      <div class="relative">
        <span class="font-mono text-sm w-12 h-12 rounded-full bg-amber text-ink flex items-center justify-center relative z-10">04</span>
        <h3 class="font-display text-lg font-medium mt-5">Pantau nilai</h3>
        <p class="text-sm text-ink/60 leading-relaxed mt-2">Nilai tugas, UTS, dan UAS bisa langsung dicek di E-Learning UNWIR.</p>
      </div>
    </div>
  </section>

  <!-- ============ TESTIMONI ============ -->
  <section id="testimoni" class="bg-ink text-paper">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 py-24 lg:py-28">
      <span class="font-mono text-xs uppercase tracking-wider text-paper/40">Testimoni</span>
      <h2 class="font-display text-3xl lg:text-4xl font-semibold tracking-tight mt-3 max-w-lg">Kata mahasiswa yang sudah pakai E-Learning UNWIR.</h2>

      <div class="grid md:grid-cols-3 gap-6 mt-14">
        <div class="bg-white/5 border border-white/10 rounded-2xl p-7">
          <p class="text-paper/80 leading-relaxed">"Semua materi kuliah ada di satu tempat, jadi nggak perlu lagi cari-cari file di grup WhatsApp yang berantakan."</p>
          <div class="flex items-center gap-3 mt-6">
            <img class="w-10 h-10 rounded-full object-cover" src="https://i.pravatar.cc/80?img=5" alt="">
            <div>
              <p class="text-sm font-medium">Dinda Ayu Pratiwi</p>
              <p class="text-xs text-paper/40">Teknik Informatika, Semester 5</p>
            </div>
          </div>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-2xl p-7">
          <p class="text-paper/80 leading-relaxed">"Batas waktu tugas kelihatan jelas, jadi saya nggak pernah lagi telat kumpul tugas dari dosen."</p>
          <div class="flex items-center gap-3 mt-6">
            <img class="w-10 h-10 rounded-full object-cover" src="https://i.pravatar.cc/80?img=12" alt="">
            <div>
              <p class="text-sm font-medium">Farhan Ramadhan</p>
              <p class="text-xs text-paper/40">Manajemen, Semester 3</p>
            </div>
          </div>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-2xl p-7">
          <p class="text-paper/80 leading-relaxed">"Nilai UTS dan tugas bisa langsung dicek di sini, jadi lebih tenang nunggu hasilnya dibanding lewat WA dosen."</p>
          <div class="flex items-center gap-3 mt-6">
            <img class="w-10 h-10 rounded-full object-cover" src="https://i.pravatar.cc/80?img=25" alt="">
            <div>
              <p class="text-sm font-medium">Salsabila Putri</p>
              <p class="text-xs text-paper/40">Ilmu Hukum, Semester 1</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ CTA BANNER ============ -->
  <section class="max-w-7xl mx-auto px-6 lg:px-10 py-24">
    <div class="dot-grid border border-line rounded-3xl px-8 py-16 lg:py-20 text-center relative overflow-hidden">
      <h2 class="font-display text-3xl lg:text-[2.6rem] font-semibold tracking-tight max-w-2xl mx-auto leading-tight">
        Lanjutkan perkuliahanmu <span class="highlight-mark">hari ini juga.</span>
      </h2>
      <p class="text-ink/60 mt-4 max-w-md mx-auto">Gunakan akun SIMAKO kamu — tidak perlu mendaftar akun baru.</p>
      <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-coral text-paper font-medium px-7 py-3.5 rounded-full hover:bg-coral/90 transition-colors mt-8 shadow-[0_6px_0_0_#12326b] active:translate-y-1 active:shadow-none">
        Masuk ke E-Learning UNWIR
      </a>
    </div>
  </section>

  <!-- ============ FOOTER ============ -->
  <footer class="border-t border-line bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 py-16 grid sm:grid-cols-2 lg:grid-cols-5 gap-10">
      <div class="lg:col-span-2">
        <div class="flex items-center gap-2.5">
          <span class="w-9 h-9 rounded-lg bg-ink flex items-center justify-center">
            <span class="text-amber font-display font-semibold text-lg">U</span>
          </span>
          <span class="font-display text-xl font-semibold">E-Learning UNWIR</span>
        </div>
        <p class="text-sm text-ink/55 mt-4 max-w-xs leading-relaxed">Platform pembelajaran daring resmi Universitas Wiralodra — satu tempat untuk materi kuliah, tugas, dan nilai.</p>
      </div>
      <div>
        <p class="font-medium text-sm">Layanan</p>
        <ul class="text-sm text-ink/55 space-y-2.5 mt-4">
          <li><a href="#fitur" class="hover:text-ink transition-colors">Fitur</a></li>
          <li><a href="#kursus" class="hover:text-ink transition-colors">Mata Kuliah</a></li>
          <li><a href="#cara-kerja" class="hover:text-ink transition-colors">Cara Pakai</a></li>
        </ul>
      </div>
      <div>
        <p class="font-medium text-sm">Universitas</p>
        <ul class="text-sm text-ink/55 space-y-2.5 mt-4">
          <li><a href="#" class="hover:text-ink transition-colors">Tentang UNWIR</a></li>
          <li><a href="#" class="hover:text-ink transition-colors">Fakultas & Prodi</a></li>
          <li><a href="#" class="hover:text-ink transition-colors">Kalender Akademik</a></li>
        </ul>
      </div>
      <div>
        <p class="font-medium text-sm">Bantuan</p>
        <ul class="text-sm text-ink/55 space-y-2.5 mt-4">
          <li><a href="#" class="hover:text-ink transition-colors">Pusat Bantuan</a></li>
          <li><a href="#" class="hover:text-ink transition-colors">Kebijakan Privasi</a></li>
          <li><a href="#" class="hover:text-ink transition-colors">Hubungi BAAK</a></li>
        </ul>
      </div>
    </div>
    <div class="border-t border-line">
      <div class="max-w-7xl mx-auto px-6 lg:px-10 py-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-ink/40">
        <p>© 2026 E-Learning UNWIR — Universitas Wiralodra. Seluruh hak cipta dilindungi.</p>
        <p>Dibuat dengan Laravel 12 & Tailwind CSS</p>
      </div>
    </div>
  </footer>

</body>

</html>