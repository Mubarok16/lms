<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajaranMahasiswa;
use App\Models\SesiAbsensi;
use App\Models\Absensi;
use App\Models\PengajaranDosen;
use App\Exports\RekapAbsensiExport;
use Maatwebsite\Excel\Facades\Excel;

class AbsensiController extends Controller
{
    public function scan(string $token)
    {
        $sesi = SesiAbsensi::where('token', $token)->firstOrFail();

        if ($sesi->isExpired()) {
            return redirect('/')->with('error', 'QR absensi sudah kedaluwarsa.');
        }

        // Cek dulu apakah user sudah login SEBELUM akses ->student
        if (!auth()->check() || !auth()->user()->student) {
            // simpan intended url supaya setelah login diarahkan balik ke sini
            session(['url.intended' => url()->current()]);
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk absen.');
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

        // GANTI: redirect ke rekap, bukan ke '/'
        return redirect()
            ->route('student.absensi.rekap', $sesi->kelas_id)
            ->with('success', $pesan);
    }

    public function rekapSemua(PengajaranDosen $pengajaranDosen)
    {
        $pengajaran = $pengajaranDosen->kelas; // alias biar sama kayak view utama

        $sesiAbsensiList = SesiAbsensi::where('kelas_id', $pengajaranDosen->kelas_id)
            ->with('absensi.mahasiswa.user')
            ->orderBy('pertemuan_ke')
            ->get();

        $mahasiswaList = $pengajaran->mahasiswa;

        return view('lecturer.absensi.rekapsemua', compact(
            'pengajaran',
            'pengajaranDosen',
            'sesiAbsensiList',
            'mahasiswaList'
        ));
    }


    public function exportRekap(PengajaranDosen $pengajaranDosen)
    {
        $pengajaran = $pengajaranDosen->kelas;

        $sesiAbsensiList = SesiAbsensi::where('kelas_id', $pengajaranDosen->kelas_id)
            ->with('absensi')
            ->orderBy('pertemuan_ke')
            ->get();

        $mahasiswaList = $pengajaran->mahasiswa;

        $namaFile = 'Rekap-Absensi-' . str_replace(' ', '-', $pengajaran->matakuliah->nama_mk) . '.xlsx';

        return Excel::download(
            new RekapAbsensiExport($pengajaranDosen, $sesiAbsensiList, $mahasiswaList),
            $namaFile
        );
    }
}
