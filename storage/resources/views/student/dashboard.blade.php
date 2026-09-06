@extends('student.app-student')
@section('ketjudul')
Dashboard
@endsection

@section('judul')
Ringkasan E-Learning UNWIR
@endsection

@section('content')

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

  <div class="bg-white border border-line rounded-2xl p-6">
    <div class="flex items-center justify-between">
      <div class="w-11 h-11 rounded-lg bg-teal/10 flex items-center justify-center">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="1.8">
          <path d="M4 19.5A2.5 2.5 0 016.5 17H20" />
          <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" />
        </svg>
      </div>
      <span class="text-xs font-mono px-2 py-1 rounded-full bg-teal/10 text-teal">Ganjil 2025/2026</span>
    </div>
    <p class="font-display text-3xl font-semibold mt-5">150+</p>
    <p class="text-sm text-ink/55 mt-1">Mata Kuliah Aktif</p>
  </div>

  <div class="bg-white border border-line rounded-2xl p-6">
    <div class="flex items-center justify-between">
      <div class="w-11 h-11 rounded-lg bg-coral/10 flex items-center justify-center">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1D4ED8" stroke-width="1.8">
          <path d="M20 21v-2a4 4 0 00-3-3.87" />
          <path d="M14 3.13a4 4 0 010 7.75" />
          <circle cx="9" cy="7" r="4" />
          <path d="M2 21v-2a4 4 0 013-3.87" />
        </svg>
      </div>
      <span class="text-xs font-mono px-2 py-1 rounded-full bg-coral/10 text-coral">6 Fakultas</span>
    </div>
    <p class="font-display text-3xl font-semibold mt-5">450+</p>
    <p class="text-sm text-ink/55 mt-1">Dosen Pengajar</p>
  </div>

  <div class="bg-white border border-line rounded-2xl p-6">
    <div class="flex items-center justify-between">
      <div class="w-11 h-11 rounded-lg bg-amber/15 flex items-center justify-center">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0F2A4D" stroke-width="1.8">
          <path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z" />
          <path d="M6 12v5c0 1.66 2.69 3 6 3s6-1.34 6-3v-5" />
        </svg>
      </div>
      <span class="text-xs font-mono px-2 py-1 rounded-full bg-amber/15 text-ink">Aktif</span>
    </div>
    <p class="font-display text-3xl font-semibold mt-5">12.000+</p>
    <p class="text-sm text-ink/55 mt-1">Mahasiswa Terdaftar</p>
  </div>

</div>
@endsection