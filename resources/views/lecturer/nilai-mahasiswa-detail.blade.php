@extends('lecturer.app-lecturer')
@section('ketjudul', 'Penilaian')
@section('judul', 'Detail Aktivitas Mahasiswa')
@section('content')
    <div class="space-y-6">
        <a href="{{ route('lecturer.nilai.mahasiswa', $pengajaranDosen) }}" class="text-sm text-teal">← Kembali ke daftar
            mahasiswa</a>
        <div class="bg-white border border-line rounded-2xl p-5">
            <p class="text-xs text-ink/45 font-mono">{{ $student->nim }}</p>
            <h2 class="font-display text-2xl font-semibold mt-1">{{ $student->user->name }}</h2>
            <p class="text-sm text-ink/55 mt-1">{{ $pengajaranDosen->kelas->matakuliah->nama_mk }} · Kelas
                {{ $pengajaranDosen->kelas->kode_kelas }}</p>
        </div>
        <div class="grid lg:grid-cols-3 gap-5">
            <section class="lg:col-span-2 bg-white border border-line rounded-2xl overflow-hidden">
                <div class="p-5 border-b border-line">
                    <h3 class="font-display font-semibold">Tugas</h3>
                </div>
                <div class="divide-y divide-line">
                    @forelse($tugas as $item)
                        @php($jawaban = $item->jawaban->first())
                        <div class="p-5 flex items-center justify-between gap-4">
                            <div>
                                <p class="font-medium">{{ $item->judul }}</p>
                                <p class="text-xs text-ink/45 mt-1">
                                    {{ $jawaban?->status ? str_replace('_', ' ', ucfirst($jawaban->status)) : 'Belum submit' }}
                                    · {{ $jawaban?->waktu_submit?->format('d M Y H:i') ?? '-' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold">{{ $jawaban?->skor ?? '-' }}</p>
                                <p class="text-xs text-ink/45">Nilai</p>
                            </div>
                        </div>
                    @empty <div class="p-10 text-center text-ink/45">Belum ada tugas.</div>
                    @endforelse
                </div>
            </section>
            <section class="bg-white border border-line rounded-2xl overflow-hidden">
                <div class="p-5 border-b border-line">
                    <h3 class="font-display font-semibold">Quiz</h3>
                </div>
                <div class="divide-y divide-line">
                    @forelse($quiz as $item)
                        @php($jawaban = $item->jawaban->first())
                        <div class="p-5">
                            <p class="font-medium">{{ $item->judul }}</p>
                            <div class="flex justify-between mt-2 text-sm"><span
                                    class="text-ink/50">{{ $jawaban?->waktu_submit?->format('d M Y H:i') ?? 'Belum submit' }}</span><strong>{{ $jawaban?->skor ?? '-' }}</strong>
                            </div>
                        </div>
                    @empty <div class="p-10 text-center text-ink/45">Belum ada quiz.</div>
                    @endforelse
                </div>
            </section>
        </div>
        <section class="bg-white border border-line rounded-2xl overflow-hidden">
            <div class="p-5 border-b border-line">
                <h3 class="font-display font-semibold">Riwayat Absensi</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-paper">
                        <tr>
                            <th class="p-4 text-left">Pertemuan</th>
                            <th class="p-4 text-left">Judul</th>
                            <th class="p-4 text-left">Waktu</th>
                            <th class="p-4 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @forelse($sesiAbsensi as $sesi)
                            @php($absen = $sesi->absensi->first()) <tr>
                                <td class="p-4">{{ $sesi->pertemuan_ke }}</td>
                                <td class="p-4">{{ $sesi->judul ?: 'Pertemuan ' . $sesi->pertemuan_ke }}</td>
                                <td class="p-4">{{ $absen?->waktu_absen?->format('d M Y H:i') ?? '-' }}</td>
                                <td class="p-4"><span
                                        class="px-2.5 py-1 rounded-full text-xs {{ $absen ? 'bg-emerald-50 text-emerald-700' : 'bg-paper text-ink/50' }}">{{ $absen?->status ? ucfirst($absen->status) : 'Tidak hadir' }}</span>
                                </td>
                            </tr>
                        @empty <tr>
                                <td colspan="4" class="p-10 text-center text-ink/45">Belum ada sesi absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
