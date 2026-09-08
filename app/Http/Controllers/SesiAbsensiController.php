<?php

namespace App\Http\Controllers;


use App\Models\Kelas;
use App\Models\SesiAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SesiAbsensiController extends Controller
{
    public function store(Request $request, $pengajaran)
    {
        $request->validate([
            'pertemuan_ke' => 'required|integer|min:1',
            'judul' => 'nullable|string|max:255',
            'durasi_menit' => 'required|integer|min:1|max:180',
        ]);

        $kelas = Kelas::findOrFail($pengajaran);

        $lecturer = Auth::user()->lecturer;

        if (!$lecturer) {
            abort(403, 'Akun ini tidak terdaftar sebagai dosen.');
        }

        $mengajar = $kelas->pengajaranDosen()
            ->where('dosen_id', $lecturer->id)
            ->exists();

        if (!$mengajar) {
            abort(403, 'Anda tidak mengajar di kelas ini.');
        }

        $pertemuanKe = (int) $request->pertemuan_ke;
        $durasiMenit = (int) $request->durasi_menit;

        $dibukaPada = now();

        $sesi = SesiAbsensi::create([
            'kelas_id' => $kelas->id,
            'dosen_id' => $lecturer->id,
            'pertemuan_ke' => $pertemuanKe,
            'judul' => $request->judul,
            'token' => Str::random(40),
            'durasi_menit' => $durasiMenit,
            'dibuka_pada' => $dibukaPada,
            'ditutup_pada' => $dibukaPada->copy()->addMinutes($durasiMenit),
        ]);

        return redirect()
            ->route('lecturer.absensi.show', $sesi->id)
            ->with('success', 'Sesi absensi dibuka.');
    }

    public function show(SesiAbsensi $sesi)
    {
        return view('lecturer.absensi.show', compact('sesi'));
    }

    public function tutup(SesiAbsensi $sesi)
    {
        $sesi->update(['ditutup_pada' => now()]);

        return back()->with('success', 'Sesi absensi ditutup.');
    }

    public function count(SesiAbsensi $sesi)
    {
        return response()->json([
            'total' => $sesi->absensi()->count(),
        ]);
    }

    public function rekap(SesiAbsensi $sesi)
    {
        $sesi->load(['kelas.matakuliah', 'kelas.mahasiswa.user', 'absensi']);

        return view('lecturer.absensi.rekap', compact('sesi'));
    }
}
