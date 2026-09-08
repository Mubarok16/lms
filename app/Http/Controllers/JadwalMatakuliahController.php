<?php

namespace App\Http\Controllers;

use App\Exports\JadwalTemplateExport;
use App\Imports\JadwalMatakuliahImport;
use App\Models\JadwalMatakuliah;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class JadwalMatakuliahController extends Controller
{
    public function index(Request $request)
    {
        $kelasQuery = $this->accessibleClasses($request);
        $kelasIds = (clone $kelasQuery)->pluck('kelas.id');
        $classes = $request->user()->role === 'admin' ? $kelasQuery->get() : collect();

        $schedules = JadwalMatakuliah::with(['kelas.matakuliah'])
            ->whereIn('kelas_id', $kelasIds)
            ->orderByRaw("CASE LOWER(hari) WHEN 'senin' THEN 1 WHEN 'selasa' THEN 2 WHEN 'rabu' THEN 3 WHEN 'kamis' THEN 4 WHEN 'jumat' THEN 5 WHEN 'sabtu' THEN 6 WHEN 'minggu' THEN 7 ELSE 8 END")
            ->orderBy('jam_mulai')
            ->get();

        return view('jadwal.index', compact('schedules', 'classes'));
    }

    public function store(Request $request)
    {
        $this->ensureAdmin($request);
        JadwalMatakuliah::create($this->validated($request));
        return back()->with('success', 'Jadwal mata kuliah berhasil ditambahkan.');
    }

    public function update(Request $request, JadwalMatakuliah $jadwal)
    {
        $this->ensureAdmin($request);
        $jadwal->update($this->validated($request, $jadwal));
        return back()->with('success', 'Jadwal mata kuliah berhasil diperbarui.');
    }

    public function destroy(Request $request, JadwalMatakuliah $jadwal)
    {
        $this->ensureAdmin($request);
        $jadwal->delete();
        return back()->with('success', 'Jadwal mata kuliah berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $this->ensureAdmin($request);
        $request->validate(['file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120']]);

        try {
            Excel::import(new JadwalMatakuliahImport, $request->file('file'));
        } catch (\Throwable $e) {
            return back()->withErrors(['file' => 'Import gagal: '.$e->getMessage()]);
        }

        return back()->with('success', 'Import jadwal mata kuliah berhasil.');
    }

    public function template(Request $request)
    {
        $this->ensureAdmin($request);
        return Excel::download(new JadwalTemplateExport, 'template-jadwal-matakuliah.xlsx');
    }

    private function validated(Request $request, ?JadwalMatakuliah $jadwal = null): array
    {
        $uniqueSchedule = Rule::unique('jadwal_matakuliah', 'jam_mulai')
            ->where(fn ($q) => $q
                ->where('kelas_id', $request->input('kelas_id'))
                ->where('hari', $request->input('hari')));

        if ($jadwal) {
            $uniqueSchedule->ignore($jadwal->id);
        }

        return $request->validate([
            'kelas_id' => ['required', 'exists:kelas,id'],
            'hari' => ['required', Rule::in(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'])],
            'jam_mulai' => ['required', 'date_format:H:i', $uniqueSchedule],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'ruangan' => ['nullable', 'string', 'max:100'],
        ], [
            'jam_mulai.unique' => 'Jadwal untuk kelas, hari, dan jam mulai tersebut sudah ada.',
        ]);
    }

    private function ensureAdmin(Request $request): void
    {
        abort_unless($request->user()?->role === 'admin', 403, 'Hanya admin yang dapat mengubah jadwal.');
    }

    private function accessibleClasses(Request $request)
    {
        $user = $request->user();
        $query = Kelas::query()->with('matakuliah')->orderBy('kode_mk')->orderBy('kode_kelas');

        if ($user->role === 'admin') return $query;
        if ($user->role === 'lecturer') {
            $id = optional($user->lecturer)->id;
            return $query->whereHas('pengajaranDosen', fn ($q) => $q->where('dosen_id', $id ?? 0));
        }
        if ($user->role === 'student') {
            $id = optional($user->student)->id;
            return $query->whereHas('pengajaranMahasiswa', fn ($q) => $q->where('mahasiswa_id', $id ?? 0));
        }
        return $query->whereRaw('1 = 0');
    }
}
