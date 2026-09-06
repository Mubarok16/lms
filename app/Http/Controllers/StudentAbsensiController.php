<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\SesiAbsensi;
use App\Models\PengajaranMahasiswa;
use Illuminate\Http\Request;

class StudentAbsensiController extends Controller
{
    public function scan()
    {
        return view('student.absensi.scan');
    }

    public function absen(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $mahasiswa = auth()->user()->student;

        $sesi = SesiAbsensi::where('token', $request->token)->first();

        if (!$sesi) {
            return response()->json(['success' => false, 'message' => 'QR tidak valid.'], 404);
        }

        if (now()->greaterThan($sesi->ditutup_pada)) {
            return response()->json(['success' => false, 'message' => 'Sesi absensi sudah ditutup.'], 410);
        }

        // pastikan mahasiswa terdaftar di kelas sesi ini
        $terdaftar = PengajaranMahasiswa::where('kelas_id', $sesi->kelas_id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->exists();

        if (!$terdaftar) {
            return response()->json(['success' => false, 'message' => 'Kamu tidak terdaftar di kelas ini.'], 403);
        }

        $sudahAbsen = Absensi::where('sesi_absensi_id', $sesi->id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->exists();

        if ($sudahAbsen) {
            return response()->json(['success' => false, 'message' => 'Kamu sudah absen di sesi ini.'], 409);
        }

        Absensi::create([
            'sesi_absensi_id' => $sesi->id,
            'mahasiswa_id'    => $mahasiswa->id,
            'status'          => 'hadir',
            'waktu_absen'     => now(),
            'ip_address'      => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Absen berhasil untuk Pertemuan {$sesi->pertemuan_ke}" . ($sesi->judul ? " — {$sesi->judul}" : ''),
        ]);
    }
}
