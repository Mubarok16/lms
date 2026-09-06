@extends('layouts.app')
@section('content')
<div class="min-h-[70vh] bg-paper py-16 px-6"><div class="max-w-3xl mx-auto bg-white border border-line rounded-2xl p-8"><p class="text-xs font-mono uppercase tracking-wider text-ink/40">E-Learning UNWIR</p><h1 class="font-display text-3xl font-semibold mt-2">{{ $title }}</h1><p class="mt-5 text-ink/65 leading-relaxed">{{ $description }}</p><a href="{{ route('home') }}" class="inline-flex mt-8 rounded-lg bg-ink px-5 py-2.5 text-white font-semibold">Kembali ke Beranda</a></div></div>
@endsection
