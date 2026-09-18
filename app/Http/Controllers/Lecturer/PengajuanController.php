<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\PengajaranDosen;
use App\Models\PengajaranMahasiswa;
use App\Models\PengajuanKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    /**
     * Daftar mahasiswa yang mengajukan kuliah ke kelas-kelas milik dosen ini.
     */
    public function index()
    {
        $dosen = Auth::user()->lecturer;
        abort_unless($dosen, 403, 'Akun ini tidak terdaftar sebagai dosen.');

        $kelasIds = PengajaranDosen::where('dosen_id', $dosen->id)->pluck('kelas_id');

        $pengajuans = PengajuanKelas::whereIn('kelas_id', $kelasIds)
            ->where('status', 'pending')
            ->with(['mahasiswa', 'kelas.matakuliah'])
            ->latest()
            ->get();

        return view('lecturer.pengajuan.index', compact('pengajuans'));
    }

    /**
     * Setujui pengajuan: pindahkan mahasiswa ke pengajaran_mahasiswa (resmi masuk kelas).
     */
    public function approve(PengajuanKelas $pengajuan)
    {
        $this->pastikanDosenPemilikKelas($pengajuan);

        DB::transaction(function () use ($pengajuan) {
            PengajaranMahasiswa::firstOrCreate([
                'kelas_id' => $pengajuan->kelas_id,
                'mahasiswa_id' => $pengajuan->mahasiswa_id,
            ]);

            $pengajuan->update([
                'status' => 'disetujui',
                'diproses_oleh' => Auth::user()->lecturer->id,
                'diproses_at' => now(),
            ]);
        });

        return back()->with('success', 'Pengajuan disetujui, mahasiswa sudah masuk kelas.');
    }

    /**
     * Tolak pengajuan, opsional dengan catatan alasan.
     */
    public function reject(Request $request, PengajuanKelas $pengajuan)
    {
        $this->pastikanDosenPemilikKelas($pengajuan);

        $request->validate([
            'catatan_dosen' => 'nullable|string|max:255',
        ]);

        $pengajuan->update([
            'status' => 'ditolak',
            'catatan_dosen' => $request->catatan_dosen,
            'diproses_oleh' => Auth::user()->lecturer->id,
            'diproses_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan ditolak.');
    }

    /**
     * Pastikan dosen yang login memang pengampu kelas dari pengajuan ini,
     * supaya dosen lain tidak bisa approve/reject pengajuan kelas orang lain.
     */
    private function pastikanDosenPemilikKelas(PengajuanKelas $pengajuan): void
    {
        $dosen = Auth::user()->lecturer;
        abort_unless($dosen, 403);

        $mengajarKelasIni = PengajaranDosen::where('dosen_id', $dosen->id)
            ->where('kelas_id', $pengajuan->kelas_id)
            ->exists();

        abort_unless($mengajarKelasIni, 403, 'Anda tidak mengajar kelas ini.');
    }
}
