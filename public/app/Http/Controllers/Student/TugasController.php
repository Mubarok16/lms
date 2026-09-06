<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\TugasJawaban;
use App\Models\TugasJawabanFile;
use App\Models\PengajaranMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    public function show(Tugas $tugas)
    {
        $tugas->load('files', 'pengajaranDosen');

        $mahasiswaId = auth()->user()->student->id;
        $terdaftar = PengajaranMahasiswa::where('mahasiswa_id', $mahasiswaId)
            ->whereHas('kelas.pengajaranDosen', fn ($q) => $q->where('id', $tugas->pengajaran_dosen_id))
            ->exists();
        abort_unless($terdaftar, 403);

        $jawabanSaya = TugasJawaban::with('files')
            ->where('tugas_id', $tugas->id)
            ->where('mahasiswa_id', $mahasiswaId)
            ->first();

        return view('student.tugas.show', compact('tugas', 'jawabanSaya'));
    }

    public function submit(Request $request, Tugas $tugas)
    {
        $request->validate([
            'files'   => 'required|array|min:1',
            'files.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10240', // max 10MB/file
        ]);

        $mahasiswaId = auth()->user()->student->id;
        $terdaftar = PengajaranMahasiswa::where('mahasiswa_id', $mahasiswaId)
            ->whereHas('kelas.pengajaranDosen', fn ($q) => $q->where('id', $tugas->pengajaran_dosen_id))
            ->exists();
        abort_unless($terdaftar, 403);
        if ($tugas->deadline && now()->greaterThan($tugas->deadline)) {
            return back()->with('error', 'Deadline tugas sudah lewat.');
        }

        $jawaban = TugasJawaban::firstOrNew([
            'tugas_id'     => $tugas->id,
            'mahasiswa_id' => $mahasiswaId,
        ]);

        $jawaban->waktu_submit = now();
        $jawaban->status = 'menunggu_koreksi';
        // reset koreksi lama kalau ini resubmit
        $jawaban->skor = null;
        $jawaban->catatan_koreksi = null;
        $jawaban->dikoreksi_oleh = null;
        $jawaban->dikoreksi_at = null;
        $jawaban->save();

        // hapus file lama (kalau resubmit)
        foreach ($jawaban->files as $oldFile) {
            Storage::disk('public')->delete($oldFile->file_path);
            $oldFile->delete();
        }

        foreach ($request->file('files') as $i => $file) {
            $ext  = strtolower($file->getClientOriginalExtension());
            $type = $ext === 'pdf' ? 'pdf' : 'foto';
            $path = $file->store('tugas-jawaban', 'public');

            TugasJawabanFile::create([
                'tugas_jawaban_id' => $jawaban->id,
                'file_path'        => $path,
                'file_type'        => $type,
                'urutan'           => $i + 1,
            ]);
        }

        return redirect()
            ->route('student.tugas.show', $tugas)
            ->with('success', 'Jawaban berhasil dikumpulkan.');
    }
}