# Fitur Tugas, Quiz, dan Nilai per Mata Kuliah

## Dosen

### Tugas
Alur sidebar:

`Tugas -> Mata Kuliah -> Daftar Tugas -> Mahasiswa yang Submit -> Koreksi`

- Mata kuliah dibatasi hanya yang diampu dosen login.
- Koreksi tetap menggunakan fitur koreksi tugas yang sudah ada.
- Urutan submission sudah kompatibel SQLite dan MySQL.

### Quiz
Alur sidebar:

`Quiz -> Mata Kuliah -> Daftar Quiz -> Daftar Mahasiswa -> Detail Jawaban`

Detail jawaban menampilkan:
- soal,
- jawaban mahasiswa,
- kunci jawaban,
- status benar/salah,
- skor quiz.

### Nilai
Alur sidebar:

`Nilai -> Mata Kuliah -> Daftar Mahasiswa -> Rekap Mahasiswa`

Rekap berisi:
- setiap tugas dan nilai koreksinya,
- setiap quiz dan skornya,
- setiap pertemuan absensi,
- jumlah hadir/izin/sakit/alpha,
- rata-rata tugas,
- rata-rata quiz,
- persentase hadir.

## Mahasiswa

Sidebar mahasiswa sekarang memiliki menu terpisah:
- Tugas
- Quiz
- Nilai
- Jadwal Kuliah
- Pesan

Mahasiswa hanya dapat mengakses tugas, quiz, nilai, dan kelas yang memang diikutinya.

Dashboard mahasiswa menampilkan:
- jumlah mata kuliah yang diikuti,
- jumlah tugas yang belum dikumpulkan,
- jumlah quiz yang belum dikerjakan,
- rata-rata nilai tugas + quiz yang sudah dinilai,
- daftar tugas yang perlu dikerjakan,
- daftar quiz yang tersedia.

## Seeder demo

`AcademicDemoSeeder` sekarang juga mengisi contoh:
- tugas yang sudah dikoreksi,
- tugas yang belum dikerjakan,
- quiz yang sudah dikerjakan,
- quiz yang belum dikerjakan,
- jawaban detail quiz,
- sesi absensi dan status kehadiran.

Akun demo:

- Admin: `admin@example.com` / `admin123`
- Dosen: `dosen@example.com` / `dosen123`
- Mahasiswa: `mahasiswa@example.com` / `mahasiswa123`

Untuk database development yang boleh dihapus:

```bash
php artisan migrate:fresh --seed
```

Untuk database yang sudah berisi data penting, jangan gunakan `migrate:fresh`. Cukup gunakan migration normal jika ada migration baru:

```bash
php artisan migrate
```

Pada penambahan fitur Tugas/Quiz/Nilai ini tidak ada migration baru.

## Menjalankan frontend

Jika `node_modules` belum ada:

```bash
npm install
npm run dev
```

Jalankan Laravel pada terminal lain:

```bash
php artisan serve
```
