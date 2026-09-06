<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Models\Prodi;
use App\Models\Matakuliah;
use App\Models\Materi;
use App\Models\PengajaranDosen;
use App\Models\PengajaranMahasiswa;
use App\Models\Quiz;
use App\Models\Tugas;

class MatakuliahController extends Controller
{
    //======================================
    // halaman matkull
    //======================================
    public function index(Request $request)
    {
        $query = Matakuliah::with('prodi');

        // Pencarian kode atau nama matakuliah
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('kode_mk', 'LIKE', "%{$search}%")
                    ->orWhere('nama_mk', 'LIKE', "%{$search}%");
            });
        }

        // Filter program studi
        if ($request->filled('prodi_id')) {

            $query->where(
                'prodi_id',
                $request->prodi_id
            );
        }

        // Pagination
        $matakuliahs = $query
            ->orderBy('kode_mk')
            ->paginate(10)
            ->withQueryString();

        // Data prodi
        $prodis = Prodi::orderBy('nama_prodi')->get();

        return view(
            'admin.matakuliah-index',
            compact(
                'matakuliahs',
                'prodis'
            )
        );
    }

    //======================================
    // proses tambah matkul
    //======================================
    public function storeMatkul(Request $request)
    {
        $validated = $request->validate([
            'kode_mk' => [
                'required',
                'string',
                'max:20',
                'unique:matakuliah,kode_mk',
            ],

            'nama_mk' => [
                'required',
                'string',
                'max:255',
            ],

            'prodi_id' => [
                'required',
                'integer',
                'exists:prodi,id',
            ],

            'sks' => [
                'required',
                'integer',
                'min:1',
                'max:6',
            ],
        ], [
            'kode_mk.required' =>
            'Kode matakuliah wajib diisi.',

            'kode_mk.string' =>
            'Kode matakuliah harus berupa teks.',

            'kode_mk.max' =>
            'Kode matakuliah maksimal 20 karakter.',

            'kode_mk.unique' =>
            'Kode matakuliah sudah digunakan.',

            'nama_mk.required' =>
            'Nama matakuliah wajib diisi.',

            'nama_mk.string' =>
            'Nama matakuliah harus berupa teks.',

            'nama_mk.max' =>
            'Nama matakuliah maksimal 255 karakter.',

            'prodi_id.required' =>
            'Program studi wajib dipilih.',

            'prodi_id.exists' =>
            'Program studi yang dipilih tidak ditemukan.',

            'sks.required' =>
            'SKS wajib diisi.',

            'sks.integer' =>
            'SKS harus berupa angka.',

            'sks.min' =>
            'SKS minimal 1.',

            'sks.max' =>
            'SKS maksimal 6.',
        ]);

        try {

            Matakuliah::create($validated);

            return redirect()
                ->route('matakuliah.index')
                ->with(
                    'success',
                    'Data matakuliah berhasil ditambahkan.'
                );
        } catch (\Throwable $e) {

            report($e);

            return redirect()
                ->route('matakuliah.index')
                ->withInput()
                ->with(
                    'error',
                    'Data matakuliah gagal ditambahkan.'
                );
        }
    }

    public function dosen_dan_mhs()
    {
        $prodiList = Prodi::all();

        return view('admin.dosen-pengampu', compact('prodiList'));
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $matakuliah = Matakuliah::query()
            ->where('nama_mk', 'like', "%{$keyword}%")
            ->orWhere('kode_mk', 'like', "%{$keyword}%")
            ->limit(20)
            ->get([
                'kode_mk',
                'nama_mk',
            ]);

        return response()->json($matakuliah);
    }

    public function index_mhs()
    {
        $mahasiswa = auth()->user()->student; // sesuaikan cara ambil data student dari user login

        $pengajaranList = PengajaranMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->with('kelas.matakuliah')
            ->get();

        return view('student.matakuliah.index', compact('pengajaranList'));
    }
    public function show(Kelas $kelas)
    {
        $mahasiswa = auth()->user()->student;

        $terdaftar = PengajaranMahasiswa::where('kelas_id', $kelas->id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->exists();

        abort_unless($terdaftar, 403, 'Kamu tidak terdaftar di kelas ini.');

        $kelas->load('matakuliah');

        $pengajaranDosenIds = PengajaranDosen::where('kelas_id', $kelas->id)->pluck('id');

        $materiList = Materi::whereIn('pengajaran_id', $pengajaranDosenIds)
            ->latest()
            ->get();

        $tugasList = Tugas::whereIn('pengajaran_dosen_id', $pengajaranDosenIds)
            ->with(['files', 'jawaban' => function ($q) use ($mahasiswa) {
                $q->where('mahasiswa_id', $mahasiswa->id);
            }])
            ->latest()
            ->get();

        $quizList = Quiz::whereIn('pengajaran_dosen_id', $pengajaranDosenIds)
            ->where('is_published', true)
            ->withCount('questions')
            ->get();

        $dosenList = PengajaranDosen::where('kelas_id', $kelas->id)
            ->with('lecturer.user')
            ->get();

        return view('student.matakuliah.show', compact(
            'kelas',
            'materiList',
            'tugasList',
            'quizList',
            'dosenList'
        ));
    }
}
