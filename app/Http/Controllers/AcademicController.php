<?php

namespace App\Http\Controllers;

use App\Models\JadwalKuliah;
use App\Models\Kelas;
use App\Models\PengajaranDosen;
use App\Models\Tugas;
use App\Models\Quiz;
use App\Models\TugasJawaban;
use App\Models\QuizJawaban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcademicController extends Controller
{
    private function lecturerTeaching()
    {
        $lecturer = Auth::user()->lecturer;
        abort_unless($lecturer, 403);
        return PengajaranDosen::where('dosen_id', $lecturer->id);
    }

    public function lecturerTasks()
    {
        $ids = $this->lecturerTeaching()->pluck('id');
        $tugas = Tugas::with('pengajaranDosen.kelas.matakuliah')->whereIn('pengajaran_dosen_id',$ids)->latest()->get();
        $quiz = Quiz::with('pengajaranDosen.kelas.matakuliah')->whereIn('pengajaran_dosen_id',$ids)->latest()->get();
        return view('lecturer.tugas-kuis', compact('tugas','quiz'));
    }

    public function lecturerGrades()
    {
        $ids = $this->lecturerTeaching()->pluck('id');
        $tugas = TugasJawaban::with(['tugas.pengajaranDosen.kelas.matakuliah','mahasiswa.user'])
            ->whereHas('tugas', fn($q)=>$q->whereIn('pengajaran_dosen_id',$ids))->latest('updated_at')->get();
        $quiz = QuizJawaban::with(['quiz.pengajaranDosen.kelas.matakuliah','mahasiswa.user'])
            ->whereHas('quiz', fn($q)=>$q->whereIn('pengajaran_dosen_id',$ids))->latest('updated_at')->get();
        return view('lecturer.nilai', compact('tugas','quiz'));
    }

    public function studentTasks()
    {
        $student = Auth::user()->student; abort_unless($student,403);
        $kelasIds = \App\Models\PengajaranMahasiswa::where('mahasiswa_id', $student->id)->pluck('kelas_id');
        $teachingIds = PengajaranDosen::whereIn('kelas_id',$kelasIds)->pluck('id');
        $tugas = Tugas::with('pengajaranDosen.kelas.matakuliah')->whereIn('pengajaran_dosen_id',$teachingIds)->orderBy('deadline')->get();
        $quiz = Quiz::with('pengajaranDosen.kelas.matakuliah')->whereIn('pengajaran_dosen_id',$teachingIds)->where('is_published',true)->latest()->get();
        return view('student.tugas-kuis', compact('tugas','quiz'));
    }

    public function studentGrades()
    {
        $student = Auth::user()->student; abort_unless($student,403);
        $tugas = TugasJawaban::with('tugas.pengajaranDosen.kelas.matakuliah')->where('mahasiswa_id',$student->id)->latest()->get();
        $quiz = QuizJawaban::with('quiz.pengajaranDosen.kelas.matakuliah')->where('mahasiswa_id',$student->id)->latest()->get();
        return view('student.nilai', compact('tugas','quiz'));
    }

    public function schedule()
    {
        $user = Auth::user();
        $q = JadwalKuliah::with(['kelas.matakuliah','kelas.pengajaranDosen.lecturer.user']);
        if ($user->role === 'lecturer') {
            $id = $user->lecturer?->id; $q->whereHas('kelas.pengajaranDosen', fn($x)=>$x->where('dosen_id',$id));
        } elseif ($user->role === 'student') {
            $id = $user->student?->id; $q->whereHas('kelas.pengajaranMahasiswa', fn($x)=>$x->where('mahasiswa_id',$id));
        }
        $jadwal = $q->orderByRaw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 WHEN 'Minggu' THEN 7 ELSE 8 END")->orderBy('jam_mulai')->get();
        $kelas = $user->role === 'admin' ? Kelas::with('matakuliah')->orderBy('kode_mk')->get() : collect();
        return view('academic.jadwal', compact('jadwal','kelas'));
    }

    public function storeSchedule(Request $request)
    {
        $data = $request->validate(['kelas_id'=>'required|exists:kelas,id','hari'=>'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu','jam_mulai'=>'required|date_format:H:i','jam_selesai'=>'required|date_format:H:i|after:jam_mulai','ruang'=>'nullable|string|max:100','keterangan'=>'nullable|string|max:255']);
        JadwalKuliah::create($data); return back()->with('success','Jadwal berhasil ditambahkan.');
    }

    public function editSchedule(JadwalKuliah $jadwal)
    {
        $kelas = Kelas::with('matakuliah')->orderBy('kode_mk')->get();
        return view('academic.jadwal-edit', compact('jadwal','kelas'));
    }

    public function updateSchedule(Request $request, JadwalKuliah $jadwal)
    {
        $data = $request->validate(['kelas_id'=>'required|exists:kelas,id','hari'=>'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu','jam_mulai'=>'required|date_format:H:i','jam_selesai'=>'required|date_format:H:i|after:jam_mulai','ruang'=>'nullable|string|max:100','keterangan'=>'nullable|string|max:255']);
        $jadwal->update($data); return redirect()->route('admin.jadwal.index')->with('success','Jadwal berhasil diperbarui.');
    }

    public function destroySchedule(JadwalKuliah $jadwal)
    { $jadwal->delete(); return back()->with('success','Jadwal berhasil dihapus.'); }
}
