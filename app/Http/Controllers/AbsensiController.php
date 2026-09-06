<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajaranMahasiswa;
use App\Models\SesiAbsensi;
use App\Models\Absensi;

class AbsensiController extends Controller
{
    public function scan(string $token)
    {
        $sesi = SesiAbsensi::where('token', $token)->firstOrFail();

        if ($sesi->isExpired()) {
            return redirect('/')->with('error', 'QR absensi sudah kedaluwarsa.');
        }

        $mahasiswaId = auth()->user()->student->id;

        $terdaftar = PengajaranMahasiswa::where('kelas_id', $sesi->kelas_id)
            ->where('mahasiswa_id', $mahasiswaId)
            ->exists();

        if (! $terdaftar) {
            return redirect('/')->with('error', 'Kamu tidak terdaftar di kelas ini.');
        }

        $absensi = Absensi::firstOrCreate(
            [
                'sesi_absensi_id' => $sesi->id,
                'mahasiswa_id' => $mahasiswaId,
            ],
            [
                'status' => 'hadir',
                'waktu_absen' => now(),
                'ip_address' => request()->ip(),
            ]
        );

        $pesan = $absensi->wasRecentlyCreated
            ? 'Absensi berhasil dicatat.'
            : 'Kamu sudah absen di sesi ini.';

        return redirect('/')->with('success', $pesan);
    }
}
