<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\PengajaranDosen;
use App\Models\Tugas;
use App\Models\TugasFile;
use App\Models\TugasJawaban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    private function authorizeTeaching(PengajaranDosen $pengajaranDosen): void
    {
        abort_unless(auth()->user()->lecturer && $pengajaranDosen->dosen_id === auth()->user()->lecturer->id, 403);
    }

    private function authorizeTugas(Tugas $tugas): void
    {
        $tugas->loadMissing('pengajaranDosen');
        abort_unless(auth()->user()->lecturer && $tugas->pengajaranDosen->dosen_id === auth()->user()->lecturer->id, 403);
    }

    public function create(PengajaranDosen $pengajaranDosen)
    {
        $this->authorizeTeaching($pengajaranDosen);
        $pengajaranDosen->load('kelas.matakuliah');

        return view('lecturer.tugas.create', compact('pengajaranDosen'));
    }

    public function store(Request $request, PengajaranDosen $pengajaranDosen)
    {
        $this->authorizeTeaching($pengajaranDosen);
        $validated = $request->validate([
            'judul'       => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'deadline'    => 'nullable|date',
            'bobot_nilai' => 'nullable|integer|min:0|max:100',
            'files'       => 'nullable|array',
            'files.*'     => 'file|mimes:pdf,jpg,jpeg,png|max:10240', // max 10MB per file
        ]);

        $tugas = Tugas::create([
            'pengajaran_dosen_id' => $pengajaranDosen->id,
            'judul'               => $validated['judul'],
            'deskripsi'           => $validated['deskripsi'] ?? null,
            'deadline'            => $validated['deadline'] ?? null,
            'bobot_nilai'         => $validated['bobot_nilai'] ?? null,
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $index => $file) {
                $path = $file->store('tugas-soal', 'public');

                $extension = strtolower($file->getClientOriginalExtension());
                $fileType  = $extension === 'pdf' ? 'pdf' : 'gambar';

                TugasFile::create([
                    'tugas_id'  => $tugas->id,
                    'file_path' => $path,
                    'file_type' => $fileType,
                    'urutan'    => $index + 1,
                ]);
            }
        }

        return redirect()
            ->route('lecturer.pengajaran.show', $pengajaranDosen->kelas_id) // sesuaikan dengan route show kamu
            ->with('success', 'Tugas berhasil ditambahkan.');
    }
    public function edit(Tugas $tugas)
    {
        $this->authorizeTugas($tugas);
        $tugas->load(['files', 'pengajaranDosen.kelas.matakuliah']);

        return view('lecturer.tugas.edit', compact('tugas'));
    }

    public function update(Request $request, Tugas $tugas)
    {
        $this->authorizeTugas($tugas);
        $validated = $request->validate([
            'judul'       => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'deadline'    => 'nullable|date',
            'bobot_nilai' => 'nullable|integer|min:0|max:100',
            'files'       => 'nullable|array',
            'files.*'     => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
            'hapus_file'  => 'nullable|array',
            'hapus_file.*' => 'integer|exists:tugas_file,id',
        ]);

        $tugas->update([
            'judul'       => $validated['judul'],
            'deskripsi'   => $validated['deskripsi'] ?? null,
            'deadline'    => $validated['deadline'] ?? null,
            'bobot_nilai' => $validated['bobot_nilai'] ?? null,
        ]);

        // Hapus file yang dicentang untuk dihapus
        if (!empty($validated['hapus_file'])) {
            $filesToDelete = TugasFile::whereIn('id', $validated['hapus_file'])
                ->where('tugas_id', $tugas->id)
                ->get();

            foreach ($filesToDelete as $file) {
                Storage::disk('public')->delete($file->file_path);
                $file->delete();
            }
        }

        // Tambah file baru kalau ada upload
        if ($request->hasFile('files')) {
            $urutanAwal = $tugas->files()->max('urutan') ?? 0;

            foreach ($request->file('files') as $index => $file) {
                $path = $file->store('tugas-soal', 'public');
                $extension = strtolower($file->getClientOriginalExtension());
                $fileType  = $extension === 'pdf' ? 'pdf' : 'gambar';

                TugasFile::create([
                    'tugas_id'  => $tugas->id,
                    'file_path' => $path,
                    'file_type' => $fileType,
                    'urutan'    => $urutanAwal + $index + 1,
                ]);
            }
        }

        return redirect()
            ->route('lecturer.pengajaran.show', ['id' => $tugas->pengajaranDosen->kelas_id])
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Tugas $tugas)
    {
        $this->authorizeTugas($tugas);
        $kelasId = $tugas->pengajaranDosen->kelas_id;
        foreach ($tugas->files as $file) {
            Storage::disk('public')->delete($file->file_path);
        }

        $tugas->delete();

        return redirect()
            ->route('lecturer.pengajaran.show', ['id' => $kelasId])
            ->with('success', 'Tugas berhasil dihapus.');
    }
    public function show(Tugas $tugas)
    {
        $this->authorizeTugas($tugas);
        $tugas->load(['files', 'pengajaranDosen.kelas.matakuliah']);

        // Tampilkan hanya submission yang benar-benar sudah dikirim mahasiswa.
        $jawabanList = TugasJawaban::with(['mahasiswa.user', 'files'])
            ->where('tugas_id', $tugas->id)
            ->whereNotNull('waktu_submit')
            ->orderByRaw("CASE status WHEN 'menunggu_koreksi' THEN 1 WHEN 'sudah_dikoreksi' THEN 2 ELSE 3 END")
            ->orderBy('waktu_submit')
            ->get();

        return view('lecturer.tugas.show', compact('tugas', 'jawabanList'));
    }

// ... di dalam class TugasController (lecturer)

public function jawabanIndex(Tugas $tugas)
{
    $this->authorizeTugas($tugas);
    $tugas->load('pengajaranDosen');

    $jawabanList = TugasJawaban::with('mahasiswa.user', 'files')
        ->where('tugas_id', $tugas->id)
        ->orderByRaw("CASE status WHEN 'menunggu_koreksi' THEN 1 WHEN 'sudah_dikoreksi' THEN 2 WHEN 'belum_submit' THEN 3 ELSE 4 END")
        ->orderBy('waktu_submit')
        ->get();

    return view('lecturer.tugas.jawaban-index', compact('tugas', 'jawabanList'));
}

public function jawabanShow(Tugas $tugas, TugasJawaban $jawaban)
{
    $this->authorizeTugas($tugas);
    abort_if($jawaban->tugas_id !== $tugas->id, 404);

    $jawaban->load('mahasiswa.user', 'files');

    return view('lecturer.tugas.jawaban-show', compact('tugas', 'jawaban'));
}

public function koreksi(Request $request, Tugas $tugas, TugasJawaban $jawaban)
{
    $this->authorizeTugas($tugas);
    abort_if($jawaban->tugas_id !== $tugas->id, 404);

    $request->validate([
        'skor'            => 'required|numeric|min:0|max:100',
        'catatan_koreksi' => 'nullable|string|max:2000',
    ]);

    $jawaban->update([
        'skor'            => $request->skor,
        'catatan_koreksi' => $request->catatan_koreksi,
        'status'          => 'sudah_dikoreksi',
        'dikoreksi_oleh'  => auth()->user()->lecturer->id, // sesuaikan relasi user->lecturer Anda
        'dikoreksi_at'    => now(),
    ]);

    return redirect()
        ->route('lecturer.tugas.jawaban.index', $tugas)
        ->with('success', 'Koreksi berhasil disimpan.');
}
}
