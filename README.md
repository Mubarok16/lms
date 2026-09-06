# E-Learning UNWIR

Aplikasi LMS Laravel 12 dengan role **Admin, Dosen, dan Mahasiswa**.

## Perbaikan versi ini

- Seluruh menu sidebar utama diarahkan ke halaman nyata; tidak ada lagi `href="#"` pada sidebar.
- Dashboard menggunakan data database, bukan angka demo.
- Modul akun dosen/mahasiswa mendukung tambah, import, edit, dan hapus.
- Modul mata kuliah, penugasan dosen, peserta kelas, materi, tugas, kuis, dan absensi dipertahankan serta diperketat authorization-nya.
- Ditambahkan **Tugas & Kuis** terpadu untuk dosen dan mahasiswa.
- Ditambahkan **Rekap Nilai** untuk dosen dan mahasiswa.
- Ditambahkan **Jadwal Kuliah** dengan tambah, edit, hapus, serta tampilan berbeda sesuai role.
- Ditambahkan **Pesan** internal; admin dapat mengirim pesan ke pengguna tertentu atau seluruh pengguna.
- Ditambahkan halaman informasi publik untuk link footer yang sebelumnya kosong.
- Ditambahkan seed akun demo untuk tiga role.
- Route yang berkaitan dengan role sekarang dilindungi middleware autentikasi, verifikasi, dan role.
- Ditambahkan pengecekan kepemilikan kelas/tugas/kuis/absensi agar pengguna tidak dapat membuka data milik role atau kelas lain.

## Setup

```bash
composer install
npm install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

## Akun demo

| Role | Email | Password |
|---|---|---|
| Admin | admin@example.com | admin123 |
| Dosen | dosen@example.com | dosen12345 |
| Mahasiswa | mahasiswa@example.com | mahasiswa123 |

Setelah login, administrator dapat membuat data mata kuliah, kelas, penugasan dosen, peserta, jadwal, dan pesan. Dosen kemudian dapat mengelola materi, tugas, kuis, dan absensi. Mahasiswa dapat mengikuti mata kuliah, mengumpulkan tugas, mengerjakan kuis, melihat nilai, dan melakukan absensi.

## Validasi yang tersedia

Seluruh file PHP pada `app`, `routes`, dan `database` telah diperiksa dengan PHP syntax checker. Pengujian runtime Laravel dan build Vite tetap perlu dijalankan pada mesin yang memiliki Composer dan dependency Node terpasang.
