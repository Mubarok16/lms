@extends('lecturer.app-lecturer-create-materi')

@section('judul')
Absensi - {{ $sesi->kelas->matakuliah->nama_mk }}
@endsection

@section('content')
<div class="bg-paper min-h-screen">
    <div class="mx-auto max-w-2xl px-6 py-10">

        @if (session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
        @endif

        <div class="rounded-2xl border border-line bg-white p-8 text-center shadow-sm"
            x-data="{
                sisaDetik: {{ max(0, now()->diffInSeconds($sesi->ditutup_pada, false)) }},
                expired: {{ $sesi->isExpired() ? 'true' : 'false' }},
                get menit() { return Math.floor(this.sisaDetik / 60) },
                get detik() { return this.sisaDetik % 60 },
                mulai() {
                    if (this.expired) return;
                    setInterval(() => {
                        if (this.sisaDetik > 0) {
                            this.sisaDetik--;
                        } else {
                            this.expired = true;
                        }
                    }, 1000);
                }
            }"
            x-init="mulai()">

            {{-- Info Sesi --}}
            <p class="text-sm text-ink/50">
                Pertemuan ke-{{ $sesi->pertemuan_ke }}
                @if ($sesi->judul) • {{ $sesi->judul }} @endif
            </p>

            <h1 class="mt-1 font-display text-xl font-semibold text-ink">
                {{ $sesi->kelas->matakuliah->nama_mk }}
            </h1>

            {{-- QR Code --}}
            <div class="mt-6 flex justify-center">
                <div class="relative">
                    <div id="qrcode" class="rounded-xl border border-line p-4"
                        :class="expired ? 'opacity-20' : ''"></div>

                    {{-- Overlay saat expired --}}
                    <div x-show="expired" x-cloak
                        class="absolute inset-0 flex items-center justify-center">
                        <span class="rounded-lg bg-ink px-4 py-2 text-sm font-semibold text-white">
                            QR Kedaluwarsa
                        </span>
                    </div>
                </div>
            </div>

            {{-- Countdown --}}
            <div class="mt-6">
                <p class="text-xs font-medium text-ink/40">Sisa Waktu</p>
                <p class="mt-1 font-display text-3xl font-bold tabular-nums"
                    :class="expired ? 'text-red-500' : 'text-ink'">
                    <template x-if="!expired">
                        <span>
                            <span x-text="String(menit).padStart(2,'0')"></span>:<span
                                x-text="String(detik).padStart(2,'0')"></span>
                        </span>
                    </template>
                    <template x-if="expired">
                        <span>00:00</span>
                    </template>
                </p>
            </div>

            {{-- Live Counter Hadir --}}
            <div class="mt-6 flex items-center justify-center gap-2 text-sm text-ink/60">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1a4 4 0 10-8 0 4 4 0 008 0zm6-2a4 4 0 10-8 0" />
                </svg>
                <span id="jumlah-hadir">{{ $sesi->absensi()->count() }}</span> mahasiswa sudah absen
            </div>

            {{-- Aksi --}}
            <div class="mt-8 flex justify-center gap-3">
                <a href="{{ route('lecturer.absensi.rekap', $sesi->id) }}"
                    class="rounded-lg border border-line px-4 py-2.5 text-sm font-semibold text-ink
                           transition hover:bg-paper">
                    Lihat Rekap
                </a>

                @if (!$sesi->isExpired())
                <form method="POST" action="{{ route('lecturer.absensi.tutup', $sesi->id) }}">
                    @csrf
                    <button type="submit"
                        class="rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white
                               transition hover:bg-primaryDark">
                        Tutup Sekarang
                    </button>
                </form>
                @endif
            </div>

        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    new QRCode(document.getElementById("qrcode"), {
        text: "{{ route('mahasiswa.absensi.scan', $sesi->token) }}",
        width: 220,
        height: 220,
    });

    // Polling jumlah hadir tiap 5 detik (opsional, biar real-time tanpa refresh)
    setInterval(() => {
        fetch("{{ route('lecturer.absensi.count', $sesi->id) }}")
            .then(res => res.json())
            .then(data => {
                document.getElementById('jumlah-hadir').innerText = data.total;
            });
    }, 5000);
</script>
@endsection