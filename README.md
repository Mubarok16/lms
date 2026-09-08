# LMS - Learning Management System

Learning Management System berbasis **Laravel 12** untuk pengelolaan kegiatan perkuliahan antara **Admin, Dosen, dan Mahasiswa**.

Project ini memiliki fitur pengelolaan mata kuliah, kelas, materi, tugas, quiz, absensi, nilai, jadwal kuliah, serta chat per mata kuliah.

---

## Fitur Utama

### Admin

- Manajemen program studi
- Manajemen dosen
- Manajemen mahasiswa
- Manajemen mata kuliah
- Manajemen kelas
- Pengaturan pengajaran dosen dan mahasiswa
- Manajemen jadwal mata kuliah
- Tambah, edit, dan hapus jadwal
- Import jadwal mata kuliah melalui Excel
- Melihat ruang chat mata kuliah

### Dosen

- Melihat mata kuliah yang diampu
- Mengelola materi perkuliahan
- Mengelola pertemuan
- Mengelola absensi
- Membuat dan mengelola tugas
- Melihat mahasiswa yang mengumpulkan tugas
- Melakukan koreksi dan pemberian nilai tugas
- Membuat dan mengelola quiz
- Melihat mahasiswa yang mengerjakan quiz
- Melihat detail jawaban quiz mahasiswa
- Melihat rekap nilai mahasiswa per mata kuliah
- Melihat rekap tugas, quiz, dan absensi mahasiswa
- Melihat jadwal kuliah
- Chat dua arah dengan mahasiswa pada setiap mata kuliah

### Mahasiswa

- Melihat mata kuliah yang diikuti
- Mengakses materi perkuliahan
- Mengikuti absensi
- Melihat dan mengumpulkan tugas
- Mengerjakan quiz
- Melihat nilai per mata kuliah
- Melihat rekap nilai tugas
- Melihat nilai quiz
- Melihat rekap absensi
- Melihat jadwal kuliah
- Chat dua arah dengan dosen dan peserta kelas

---

# Teknologi

Project menggunakan:

- PHP `^8.2`
- Laravel `^12.0`
- Laravel Breeze
- Blade Template
- Tailwind CSS
- Alpine.js
- Vite
- SQLite sebagai konfigurasi database default
- Maatwebsite Excel untuk import jadwal
- Yajra DataTables
- Composer
- Node.js dan NPM

---

# Requirement

Pastikan perangkat sudah memiliki:

- PHP 8.2 atau lebih baru
- Composer
- Node.js
- NPM
- Git
- Ekstensi PHP SQLite apabila menggunakan SQLite

Untuk Windows, project dapat dijalankan menggunakan **Laragon**.

---

# Instalasi Project

## 1. Clone Repository

```bash
git clone URL_REPOSITORY_GITHUB
```

Masuk ke folder project:

```bash
cd lms-main
```

Jika project diperoleh dalam bentuk ZIP, extract ZIP kemudian buka terminal pada folder project.

Contoh pada Laragon:

```text
C:\laragon\www\lms-main
```

---

## 2. Install Dependency PHP

```bash
composer install
```

---

## 3. Install Dependency Frontend

```bash
npm install
```

---

## 4. Buat File `.env`

Jika file `.env` belum tersedia:

### Windows CMD / Laragon Terminal

```bash
copy .env.example .env
```

### Linux / macOS

```bash
cp .env.example .env
```

---

## 5. Generate Application Key

```bash
php artisan key:generate
```

---

# Konfigurasi Database

Project secara default menggunakan **SQLite**.

Pastikan konfigurasi `.env` berisi:

```env
DB_CONNECTION=sqlite
```

Buat file database SQLite apabila belum tersedia:

```bash
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
```

File database akan berada di:

```text
database/database.sqlite
```

---

## Alternatif: Menggunakan MySQL

Jika ingin menggunakan MySQL, ubah `.env` menjadi contoh berikut:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms
DB_USERNAME=root
DB_PASSWORD=
```

Buat database `lms` terlebih dahulu melalui phpMyAdmin, HeidiSQL, atau tool database lainnya.

---

# Migrasi dan Seeder

## Instalasi Pertama / Database Development Baru

Untuk membuat seluruh tabel sekaligus memasukkan data awal:

```bash
php artisan migrate:fresh --seed
```

> **Perhatian:** `migrate:fresh` akan menghapus seluruh tabel dan data yang sudah ada.

Gunakan perintah ini hanya pada database baru atau database development yang datanya boleh dihapus.

---

## Database yang Sudah Berisi Data

Jika database sudah memiliki data penting, gunakan:

```bash
php artisan migrate
```

Jangan menggunakan:

```bash
php artisan migrate:fresh
```

karena seluruh data akan dihapus.

---

## Menjalankan Seeder Saja

Semua seeder utama:

```bash
php artisan db:seed
```

Seeder user admin:

```bash
php artisan db:seed --class=UserSeeder
```

Seeder program studi:

```bash
php artisan db:seed --class=ProdiSeeder
```

Seeder data akademik demo:

```bash
php artisan db:seed --class=AcademicDemoSeeder
```

Seeder menggunakan `updateOrCreate` / `firstOrCreate` pada data utama sehingga lebih aman ketika dijalankan ulang.

---

# Data User Awal

Setelah menjalankan:

```bash
php artisan migrate:fresh --seed
```

akun berikut tersedia untuk pengujian.

| Role | Nama | Email | Password |
|---|---|---|---|
| Admin | Administrator | `admin@example.com` | `admin123` |
| Dosen | Dosen Demo | `dosen@example.com` | `dosen123` |
| Mahasiswa | Mahasiswa Demo | `mahasiswa@example.com` | `mahasiswa123` |

> Akun tersebut adalah akun development/demo. Ganti password sebelum digunakan pada lingkungan production.

---

# Data Akademik Awal

Seeder juga membuat beberapa data untuk kebutuhan pengujian.

## Program Studi

- Teknik Komputer
- Teknik Sipil
- Teknik Lingkungan

## Mata Kuliah Demo

### Pemrograman Web

```text
Kode MK : TK101
Kelas   : A
Semester: 5
```

### Basis Data

```text
Kode MK : TK102
Kelas   : A
Semester: 5
```

Dosen dan mahasiswa demo sudah dihubungkan dengan mata kuliah tersebut sehingga fitur dapat langsung diuji setelah seeding.

Data demo juga mencakup:

- Jadwal mata kuliah
- Chat mata kuliah
- Tugas
- Jawaban tugas
- Nilai tugas
- Quiz
- Soal quiz
- Jawaban quiz mahasiswa
- Nilai quiz
- Sesi absensi
- Data kehadiran mahasiswa

---

# Storage File

Project menggunakan Laravel public storage untuk beberapa file upload.

Setelah instalasi, wajib jalankan:

```bash
php artisan storage:link
```

Kemudian bersihkan cache Laravel:

```bash
php artisan optimize:clear
```

Hal ini penting agar file seperti PDF tugas dapat diakses melalui:

```text
/storage/...
```

Jika `public/storage` sudah ada tetapi bermasalah, hapus symbolic link lama kemudian jalankan kembali:

```bash
php artisan storage:link
```

---

# Menjalankan Project

## Terminal 1 - Laravel

```bash
php artisan serve
```

Secara default aplikasi dapat dibuka melalui:

```text
http://127.0.0.1:8000
```

## Terminal 2 - Vite

```bash
npm run dev
```

Biarkan kedua terminal tetap berjalan selama development.

---

## Alternatif Menjalankan Development

Project juga menyediakan script Composer:

```bash
composer run dev
```

Script tersebut dapat menjalankan beberapa service development secara bersamaan.

---

# Build Frontend untuk Production

Untuk membuat asset production:

```bash
npm run build
```

---

# Langkah Instalasi Cepat

Untuk instalasi development baru menggunakan SQLite:

```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate:fresh --seed
php artisan storage:link
php artisan optimize:clear
```

Kemudian jalankan:

```bash
php artisan serve
```

Pada terminal lain:

```bash
npm run dev
```

---

# Import Jadwal Mata Kuliah

Import jadwal hanya dapat dilakukan oleh **Admin**.

Kolom file Excel:

| Kolom | Keterangan |
|---|---|
| `kode_mk` | Kode mata kuliah |
| `kode_kelas` | Kode kelas |
| `hari` | Hari perkuliahan |
| `jam_mulai` | Jam mulai |
| `jam_selesai` | Jam selesai |
| `ruangan` | Nama/nomor ruangan |

Contoh:

| kode_mk | kode_kelas | hari | jam_mulai | jam_selesai | ruangan |
|---|---|---|---|---|---|
| TK101 | A | Senin | 08:00 | 10:30 | Lab Komputer 1 |
| TK102 | A | Rabu | 10:00 | 12:30 | Ruang 203 |

---

# Alur Tugas Dosen

```text
Sidebar Tugas
    ↓
Pilih Mata Kuliah
    ↓
Daftar Tugas
    ↓
Pilih Tugas
    ↓
Daftar Mahasiswa yang Submit
    ↓
Koreksi Jawaban
    ↓
Simpan Nilai
```

---

# Alur Quiz Dosen

```text
Sidebar Quiz
    ↓
Pilih Mata Kuliah
    ↓
Daftar Quiz
    ↓
Pilih Quiz
    ↓
Daftar Mahasiswa
    ↓
Pilih Mahasiswa
    ↓
Detail Jawaban Quiz
```

Detail jawaban menampilkan jawaban mahasiswa, kunci jawaban, benar/salah, dan skor.

---

# Alur Nilai Dosen

```text
Sidebar Nilai
    ↓
Pilih Mata Kuliah
    ↓
Daftar Mahasiswa
    ↓
Pilih Mahasiswa
    ↓
Rekap Tugas + Quiz + Absensi
```

---

# Hak Akses

## Admin

Admin memiliki akses pengelolaan data akademik dan jadwal.

## Dosen

Dosen hanya dapat mengakses kelas dan mata kuliah yang diampu.

## Mahasiswa

Mahasiswa hanya dapat mengakses kelas dan mata kuliah yang diikuti.

Pembatasan dilakukan pada sisi route/controller, sehingga pengguna tidak seharusnya dapat membuka data kelas lain hanya dengan mengganti ID URL.

---

# Chat Mata Kuliah

Chat tersedia berdasarkan kelas/mata kuliah.

- Dosen dapat mengirim dan membalas pesan.
- Mahasiswa dapat mengirim dan membalas pesan.
- Admin dapat melihat ruang chat.
- Pesan tersimpan di database.
- Halaman mengambil pesan baru secara berkala.

---

# Jadwal Mata Kuliah

Hak akses jadwal:

| Role | Lihat | Tambah | Edit | Hapus | Import |
|---|---:|---:|---:|---:|---:|
| Admin | ✅ | ✅ | ✅ | ✅ | ✅ |
| Dosen | ✅ | ❌ | ❌ | ❌ | ❌ |
| Mahasiswa | ✅ | ❌ | ❌ | ❌ | ❌ |

---

# Membersihkan Cache

Jika setelah mengubah route, Blade, `.env`, atau konfigurasi masih muncul tampilan/error lama:

```bash
php artisan optimize:clear
```

Untuk membersihkan cache view saja:

```bash
php artisan view:clear
```

---

# Troubleshooting

## `Undefined variable $slot`

Pastikan view yang menggunakan layout mahasiswa memakai layout yang sesuai, misalnya:

```blade
@extends('student.app-student')
```

Jangan menggunakan layout Blade Component melalui `@extends` apabila layout tersebut bergantung pada:

```blade
{{ $slot }}
```

---

## PDF / File Upload Menampilkan `403 Forbidden`

Jalankan:

```bash
php artisan storage:link
php artisan optimize:clear
```

Pastikan file upload tersedia pada:

```text
storage/app/public/
```

dan symbolic link:

```text
public/storage
```

sudah dibuat.

---

## Error `UNIQUE constraint failed: users.email`

Jangan membuat user seed menggunakan `create()` berulang kali untuk email yang sama.

Seeder project sudah menggunakan pendekatan seperti:

```php
User::updateOrCreate(...)
```

atau:

```php
User::firstOrCreate(...)
```

sehingga dapat dijalankan kembali tanpa membuat email duplikat.

---

# Akses dari HP pada Jaringan yang Sama

Cari IPv4 komputer:

```bash
ipconfig
```

Kemudian jalankan Laravel:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Misalnya IP komputer:

```text
192.168.1.10
```

ubah `.env`:

```env
APP_URL=http://192.168.1.10:8000
```

Bersihkan konfigurasi:

```bash
php artisan config:clear
```

Kemudian dari HP buka:

```text
http://192.168.1.10:8000
```

Pastikan komputer dan HP berada pada jaringan yang sama dan Windows Firewall mengizinkan koneksi ke PHP/Laravel.

---

# Struktur Akun Development

```text
ADMIN
Email    : admin@example.com
Password : admin123

DOSEN
Email    : dosen@example.com
Password : dosen123

MAHASISWA
Email    : mahasiswa@example.com
Password : mahasiswa123
```

---

# Catatan Keamanan

Sebelum deployment production:

1. Ganti seluruh password akun demo.
2. Gunakan `APP_ENV=production`.
3. Gunakan `APP_DEBUG=false`.
4. Gunakan database production yang sesuai.
5. Jangan commit file `.env`.
6. Pastikan permission storage benar.
7. Jalankan build frontend production.
8. Pastikan akses file upload dilindungi sesuai kebutuhan aplikasi.

---

# License

Project dikembangkan untuk kebutuhan Learning Management System / sistem akademik.

