<?php

namespace Database\Seeders;

use App\Models\CourseChat;
use App\Models\Absensi;
use App\Models\Quiz;
use App\Models\QuizJawaban;
use App\Models\QuizJawabanDetail;
use App\Models\QuizQuestion;
use App\Models\SesiAbsensi;
use App\Models\Tugas;
use App\Models\TugasJawaban;
use App\Models\JadwalMatakuliah;
use App\Models\Kelas;
use App\Models\Lecturer;
use App\Models\Matakuliah;
use App\Models\PengajaranDosen;
use App\Models\PengajaranMahasiswa;
use App\Models\Prodi;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AcademicDemoSeeder extends Seeder
{
    public function run(): void
    {
        $prodi = Prodi::where('nama_prodi', 'Teknik Komputer')->firstOrFail();

        // =========================
        // DOSEN DEMO
        // =========================
        $dosenUser = User::updateOrCreate(
            ['email' => 'dosen@example.com'],
            [
                'name' => 'Dosen Demo',
                'role' => 'lecturer',
                'email_verified_at' => now(),
                'password' => Hash::make('dosen123'),
            ]
        );

        $dosen = Lecturer::updateOrCreate(
            ['user_id' => $dosenUser->id],
            [
                'nidn' => '0123456789',
                'prodi_id' => $prodi->id,
                'phone' => '081234567890',
            ]
        );

        // =========================
        // MAHASISWA DEMO
        // =========================
        $mahasiswaUser = User::updateOrCreate(
            ['email' => 'mahasiswa@example.com'],
            [
                'name' => 'Mahasiswa Demo',
                'role' => 'student',
                'email_verified_at' => now(),
                'password' => Hash::make('mahasiswa123'),
            ]
        );

        $mahasiswa = Student::updateOrCreate(
            ['user_id' => $mahasiswaUser->id],
            [
                'nim' => '23100001',
                'prodi_id' => $prodi->id,
                'angkatan' => 2023,
                'phone' => '081298765432',
            ]
        );

        // =========================
        // MATA KULIAH
        // =========================
        $pemrogramanWeb = Matakuliah::updateOrCreate(
            ['kode_mk' => 'TK101'],
            [
                'nama_mk' => 'Pemrograman Web',
                'prodi_id' => $prodi->id,
                'sks' => 3,
            ]
        );

        $basisData = Matakuliah::updateOrCreate(
            ['kode_mk' => 'TK102'],
            [
                'nama_mk' => 'Basis Data',
                'prodi_id' => $prodi->id,
                'sks' => 3,
            ]
        );

        // =========================
        // KELAS
        // =========================
        $kelasWeb = Kelas::updateOrCreate(
            [
                'kode_mk' => $pemrogramanWeb->kode_mk,
                'kode_kelas' => 'A',
            ],
            ['semester' => 5]
        );

        $kelasBasisData = Kelas::updateOrCreate(
            [
                'kode_mk' => $basisData->kode_mk,
                'kode_kelas' => 'A',
            ],
            ['semester' => 5]
        );

        // =========================
        // PENGAJARAN DOSEN
        // =========================
        foreach ([$kelasWeb, $kelasBasisData] as $kelas) {
            PengajaranDosen::firstOrCreate([
                'dosen_id' => $dosen->id,
                'kelas_id' => $kelas->id,
            ]);

            PengajaranMahasiswa::firstOrCreate([
                'kelas_id' => $kelas->id,
                'mahasiswa_id' => $mahasiswa->id,
            ]);
        }

        // =========================
        // JADWAL MATA KULIAH
        // =========================
        JadwalMatakuliah::updateOrCreate(
            [
                'kelas_id' => $kelasWeb->id,
                'hari' => 'Senin',
                'jam_mulai' => '08:00:00',
            ],
            [
                'jam_selesai' => '10:30:00',
                'ruangan' => 'Lab Komputer 1',
            ]
        );

        JadwalMatakuliah::updateOrCreate(
            [
                'kelas_id' => $kelasBasisData->id,
                'hari' => 'Rabu',
                'jam_mulai' => '10:00:00',
            ],
            [
                'jam_selesai' => '12:30:00',
                'ruangan' => 'Ruang 203',
            ]
        );

        // =========================
        // CHAT DEMO DUA ARAH
        // =========================
        CourseChat::firstOrCreate([
            'kelas_id' => $kelasWeb->id,
            'user_id' => $dosenUser->id,
            'message' => 'Selamat datang di chat Pemrograman Web. Silakan gunakan ruang ini untuk diskusi perkuliahan.',
        ]);

        CourseChat::firstOrCreate([
            'kelas_id' => $kelasWeb->id,
            'user_id' => $mahasiswaUser->id,
            'message' => 'Baik Pak/Bu, terima kasih. Apakah materi pertemuan pertama sudah bisa dipelajari?',
        ]);

        CourseChat::firstOrCreate([
            'kelas_id' => $kelasWeb->id,
            'user_id' => $dosenUser->id,
            'message' => 'Sudah. Silakan cek menu materi pada kelas Pemrograman Web.',
        ]);

        // =========================
        // DATA DEMO TUGAS, QUIZ, NILAI & ABSENSI
        // =========================
        $pengajaranWeb = PengajaranDosen::where('dosen_id', $dosen->id)
            ->where('kelas_id', $kelasWeb->id)
            ->firstOrFail();

        $tugas1 = Tugas::updateOrCreate(
            [
                'pengajaran_dosen_id' => $pengajaranWeb->id,
                'judul' => 'Tugas 1 - HTML dan CSS',
            ],
            [
                'deskripsi' => 'Buat halaman profil sederhana menggunakan HTML dan CSS.',
                'deadline' => now()->addDays(5),
                'bobot_nilai' => 20,
            ]
        );

        TugasJawaban::updateOrCreate(
            [
                'tugas_id' => $tugas1->id,
                'mahasiswa_id' => $mahasiswa->id,
            ],
            [
                'waktu_submit' => now()->subDay(),
                'skor' => 88,
                'catatan_koreksi' => 'Struktur sudah baik. Rapikan konsistensi spacing.',
                'dikoreksi_oleh' => $dosen->id,
                'dikoreksi_at' => now(),
                'status' => 'sudah_dikoreksi',
            ]
        );

        Tugas::updateOrCreate(
            [
                'pengajaran_dosen_id' => $pengajaranWeb->id,
                'judul' => 'Tugas 2 - Routing Laravel',
            ],
            [
                'deskripsi' => 'Buat route, controller, dan view sederhana pada Laravel.',
                'deadline' => now()->addDays(10),
                'bobot_nilai' => 20,
            ]
        );

        $quiz1 = Quiz::updateOrCreate(
            [
                'pengajaran_dosen_id' => $pengajaranWeb->id,
                'judul' => 'Quiz 1 - Dasar Web',
            ],
            [
                'deskripsi' => 'Quiz dasar HTML dan HTTP.',
                'durasi_menit' => 15,
                'is_published' => true,
            ]
        );

        $q1 = QuizQuestion::updateOrCreate(
            ['quiz_id' => $quiz1->id, 'nomor' => 1],
            [
                'pertanyaan' => 'Tag HTML untuk membuat tautan adalah?',
                'pilihan_a' => '<a>',
                'pilihan_b' => '<link>',
                'pilihan_c' => '<p>',
                'pilihan_d' => '<div>',
                'pilihan_e' => null,
                'kunci_jawaban' => 'A',
            ]
        );

        $q2 = QuizQuestion::updateOrCreate(
            ['quiz_id' => $quiz1->id, 'nomor' => 2],
            [
                'pertanyaan' => 'Kode status HTTP untuk request berhasil adalah?',
                'pilihan_a' => '404',
                'pilihan_b' => '500',
                'pilihan_c' => '200',
                'pilihan_d' => '301',
                'pilihan_e' => null,
                'kunci_jawaban' => 'C',
            ]
        );

        $quizJawaban = QuizJawaban::updateOrCreate(
            [
                'quiz_id' => $quiz1->id,
                'mahasiswa_id' => $mahasiswa->id,
            ],
            [
                'skor' => 100,
                'waktu_submit' => now()->subHours(12),
            ]
        );

        QuizJawabanDetail::updateOrCreate(
            ['quiz_jawaban_id' => $quizJawaban->id, 'quiz_question_id' => $q1->id],
            ['jawaban_dipilih' => 'A', 'is_benar' => true]
        );
        QuizJawabanDetail::updateOrCreate(
            ['quiz_jawaban_id' => $quizJawaban->id, 'quiz_question_id' => $q2->id],
            ['jawaban_dipilih' => 'C', 'is_benar' => true]
        );

        $quiz2 = Quiz::updateOrCreate(
            [
                'pengajaran_dosen_id' => $pengajaranWeb->id,
                'judul' => 'Quiz 2 - Laravel Dasar',
            ],
            [
                'deskripsi' => 'Quiz routing dan MVC Laravel.',
                'durasi_menit' => 20,
                'is_published' => true,
            ]
        );

        QuizQuestion::updateOrCreate(
            ['quiz_id' => $quiz2->id, 'nomor' => 1],
            [
                'pertanyaan' => 'File utama untuk mendefinisikan route web Laravel adalah?',
                'pilihan_a' => 'routes/web.php',
                'pilihan_b' => 'app.php',
                'pilihan_c' => 'index.php',
                'pilihan_d' => 'composer.json',
                'pilihan_e' => null,
                'kunci_jawaban' => 'A',
            ]
        );

        foreach ([
            [1, 'Pengenalan Pemrograman Web', 'hadir'],
            [2, 'HTML dan CSS', 'hadir'],
            [3, 'Dasar Laravel', 'izin'],
        ] as [$pertemuan, $judul, $status]) {
            $dibuka = now()->subDays(10 - $pertemuan)->setTime(8, 0);
            $sesi = SesiAbsensi::updateOrCreate(
                [
                    'kelas_id' => $kelasWeb->id,
                    'pertemuan_ke' => $pertemuan,
                ],
                [
                    'dosen_id' => $dosen->id,
                    'judul' => $judul,
                    'token' => 'demo-web-' . $kelasWeb->id . '-' . $pertemuan,
                    'durasi_menit' => 15,
                    'dibuka_pada' => $dibuka,
                    'ditutup_pada' => $dibuka->copy()->addMinutes(15),
                ]
            );

            Absensi::updateOrCreate(
                [
                    'sesi_absensi_id' => $sesi->id,
                    'mahasiswa_id' => $mahasiswa->id,
                ],
                [
                    'status' => $status,
                    'waktu_absen' => $status === 'hadir' ? $dibuka->copy()->addMinutes(3) : null,
                    'ip_address' => '127.0.0.1',
                    'catatan' => $status === 'izin' ? 'Izin kegiatan akademik.' : null,
                ]
            );
        }

    }
}
