<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\MateriFile;
use App\Models\PengajaranDosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    /**
     * Pastikan pengajaran_dosen ini benar-benar milik dosen yang sedang login.
     * Mencegah dosen A menambah/lihat form materi milik dosen B lewat URL.
     */
    private function authorizePengajaran(PengajaranDosen $pengajaran): void
    {
        $lecturer = Auth::user()->lecturer;

        if (!$lecturer || $pengajaran->dosen_id !== $lecturer->id) {
            abort(403, 'Anda tidak memiliki akses ke pengajaran ini.');
        }
    }

    /**
     * Tampilkan halaman form tambah materi.
     */
    public function create(PengajaranDosen $pengajaran)
    {
        $this->authorizePengajaran($pengajaran);


        // eager load supaya bisa tampilkan nama dosen & nama mk di halaman form
        // matakuliah diakses lewat kelas, karena pengajaran_dosen tidak punya relasi langsung ke matakuliah
        $pengajaran->load('lecturer.user', 'kelas.matakuliah');

        return view('lecturer.materi.create', compact('pengajaran'));
    }

    /**
     * Simpan materi baru beserta file-file di dalamnya.
     */
    public function store(Request $request, PengajaranDosen $pengajaran)
    {
        $this->authorizePengajaran($pengajaran);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',

            // satu file per jenis
            'pdf_file' => 'nullable|file|mimes:pdf|max:20480', // 20MB
            'audio_file' => 'nullable|file|mimes:mp3,wav,m4a|max:20480',
            'youtube_url' => 'nullable|url',
        ]);

        // pastikan minimal ada satu konten yang diisi
        $adaPdf = $request->hasFile('pdf_file');
        $adaAudio = $request->hasFile('audio_file');
        $adaYoutube = filled($request->input('youtube_url'));

        if (!$adaPdf && !$adaAudio && !$adaYoutube) {
            return back()
                ->withInput()
                ->withErrors(['konten' => 'Minimal isi salah satu: file PDF, file audio, atau link YouTube.']);
        }

        DB::transaction(function () use ($request, $pengajaran) {

            $materi = Materi::create([
                'pengajaran_id' => $pengajaran->id,
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
            ]);

            $urutan = 0;

            // simpan file PDF
            if ($request->hasFile('pdf_file')) {
                $file = $request->file('pdf_file');
                $path = $file->store('materi/pdf', 'public');

                MateriFile::create([
                    'materi_id' => $materi->id,
                    'tipe' => 'pdf',
                    'file_path' => $path,
                    'nama_asli' => $file->getClientOriginalName(),
                    'urutan' => $urutan++,
                ]);
            }

            // simpan file audio
            if ($request->hasFile('audio_file')) {
                $file = $request->file('audio_file');
                $path = $file->store('materi/audio', 'public');

                MateriFile::create([
                    'materi_id' => $materi->id,
                    'tipe' => 'audio',
                    'file_path' => $path,
                    'nama_asli' => $file->getClientOriginalName(),
                    'urutan' => $urutan++,
                ]);
            }

            // simpan link youtube
            if (filled($request->input('youtube_url'))) {
                $url = $request->input('youtube_url');

                MateriFile::create([
                    'materi_id' => $materi->id,
                    'tipe' => 'video_youtube',
                    'youtube_url' => $url,
                    'youtube_id' => $this->extractYoutubeId($url),
                    'urutan' => $urutan++,
                ]);
            }
        });

        return redirect()
            ->route('pengajaran.show', $pengajaran->kelas_id)
            ->with('success', 'Materi berhasil ditambahkan.');
    }

    /**
     * Hapus materi beserta file fisik yang menyertainya.
     */
    public function destroy(Materi $materi)
    {
        $this->authorizePengajaran($materi->pengajaran);

        DB::transaction(function () use ($materi) {

            foreach ($materi->files as $file) {
                if ($file->file_path) {
                    Storage::disk('public')->delete($file->file_path);
                }
            }

            $materi->delete(); // materi_files ikut terhapus lewat cascadeOnDelete
        });

        return back()->with('success', 'Materi berhasil dihapus.');
    }

    /**
     * Ambil video ID dari berbagai format URL YouTube.
     * Mendukung: youtube.com/watch?v=, youtu.be/, youtube.com/embed/
     */
    private function extractYoutubeId(string $url): ?string
    {
        preg_match(
            '/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/',
            $url,
            $matches
        );

        return $matches[1] ?? null;
    }
    /**
     * Tampilkan form edit materi, lengkap dengan file yang sudah ada.
     */
    public function edit(Materi $materi)
    {
        $this->authorizePengajaran($materi->pengajaran);

        $materi->load('files', 'pengajaran.kelas.matakuliah');

        // ambil file existing per tipe (asumsi: maksimal 1 file per tipe)
        $pdfFile = $materi->files->firstWhere('tipe', 'pdf');
        $audioFile = $materi->files->firstWhere('tipe', 'audio');
        $youtubeFile = $materi->files->firstWhere('tipe', 'video_youtube');

        return view('lecturer.materi.edit', compact('materi', 'pdfFile', 'audioFile', 'youtubeFile'));
    }

    /**
     * Update materi: judul, deskripsi, dan file-file di dalamnya.
     * Setiap jenis file bisa: dibiarkan, diganti (upload baru), atau dihapus (checkbox remove_*).
     */
    public function update(Request $request, Materi $materi)
    {
        $this->authorizePengajaran($materi->pengajaran);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',

            'pdf_file' => 'nullable|file|mimes:pdf|max:20480',
            'audio_file' => 'nullable|file|mimes:mp3,wav,m4a|max:20480',
            'youtube_url' => 'nullable|url',

            'remove_pdf' => 'nullable|boolean',
            'remove_audio' => 'nullable|boolean',
            'remove_youtube' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request, $materi) {

            $materi->update([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
            ]);

            $this->syncFile($materi, 'pdf', $request->file('pdf_file'), (bool) $request->boolean('remove_pdf'), 'materi/pdf');
            $this->syncFile($materi, 'audio', $request->file('audio_file'), (bool) $request->boolean('remove_audio'), 'materi/audio');
            $this->syncYoutube($materi, $request->input('youtube_url'), (bool) $request->boolean('remove_youtube'));
        });

        // pastikan minimal masih ada satu konten tersisa setelah update
        if ($materi->files()->count() === 0) {
            return back()
                ->withInput()
                ->withErrors(['konten' => 'Materi harus memiliki minimal satu konten: PDF, audio, atau video YouTube.']);
        }

        return redirect()
            ->route('pengajaran.show', $materi->pengajaran->kelas_id)
            ->with('success', 'Materi berhasil diperbarui.');
    }

    /**
     * Sinkronisasi file (pdf/audio) untuk satu materi:
     * - kalau remove dicentang -> hapus file lama
     * - kalau ada upload baru -> hapus file lama (kalau ada), simpan yang baru
     * - kalau tidak ada perubahan -> biarkan
     */
    private function syncFile(Materi $materi, string $tipe, $uploadedFile, bool $remove, string $folder): void
    {
        $existing = $materi->files()->where('tipe', $tipe)->first();

        if ($remove) {
            if ($existing) {
                if ($existing->file_path) {
                    Storage::disk('public')->delete($existing->file_path);
                }
                $existing->delete();
            }
            return;
        }

        if ($uploadedFile) {
            // hapus file lama dulu kalau ada penggantian
            if ($existing) {
                if ($existing->file_path) {
                    Storage::disk('public')->delete($existing->file_path);
                }
                $existing->delete();
            }

            $path = $uploadedFile->store($folder, 'public');

            MateriFile::create([
                'materi_id' => $materi->id,
                'tipe' => $tipe,
                'file_path' => $path,
                'nama_asli' => $uploadedFile->getClientOriginalName(),
                'urutan' => $existing->urutan ?? 0,
            ]);
        }

        // kalau tidak remove dan tidak ada upload baru, biarkan yang lama tetap ada
    }

    /**
     * Sinkronisasi link YouTube untuk satu materi.
     */
    private function syncYoutube(Materi $materi, ?string $url, bool $remove): void
    {
        $existing = $materi->files()->where('tipe', 'video_youtube')->first();

        if ($remove) {
            $existing?->delete();
            return;
        }

        if (filled($url)) {
            if ($existing) {
                $existing->update([
                    'youtube_url' => $url,
                    'youtube_id' => $this->extractYoutubeId($url),
                ]);
            } else {
                MateriFile::create([
                    'materi_id' => $materi->id,
                    'tipe' => 'video_youtube',
                    'youtube_url' => $url,
                    'youtube_id' => $this->extractYoutubeId($url),
                    'urutan' => 0,
                ]);
            }
        }

        // kalau url kosong dan tidak remove, biarkan yang lama tetap ada
    }
}
