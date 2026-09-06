@php($layout = auth()->user()->role === 'admin' ? 'admin.app-admin' : (auth()->user()->role === 'lecturer' ? 'lecturer.app-lecturer' : 'student.app-student'))
@extends($layout)
@section('ketjudul')Komunikasi@endsection
@section('judul')Pesan@endsection
@section('content')
<div class="mb-5 p-4 rounded-xl bg-amber/10 border border-amber/20 text-sm text-ink/70">Database saat ini belum memiliki tabel pesan. Agar migrasi tidak diubah, fitur ini menyediakan direktori kontak dan kanal email langsung. Penyimpanan chat/inbox persisten baru bisa dibuat jika tabel pesan ditambahkan.</div>
<div class="bg-white border border-line rounded-2xl overflow-hidden"><div class="p-5 border-b border-line"><h2 class="font-semibold">Kontak</h2><p class="text-sm text-ink/50 mt-1">Hubungi pengguna lain melalui email yang terdaftar.</p></div><div class="divide-y divide-line">@forelse($contacts as $contact)<div class="p-5 flex items-center justify-between gap-4"><div><p class="font-medium">{{ $contact->name }}</p><p class="text-xs text-ink/45 mt-1 uppercase">{{ $contact->role }}</p><p class="text-sm text-ink/55 mt-1">{{ $contact->email }}</p></div><a href="mailto:{{ $contact->email }}" class="px-4 py-2 rounded-lg bg-teal text-white text-sm">Kirim Email</a></div>@empty<div class="p-8 text-center text-sm text-ink/45">Tidak ada kontak.</div>@endforelse</div></div>
@endsection
