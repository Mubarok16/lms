# LMS – Perbaikan dan Verifikasi

Perbaikan utama pada versi ini:

- Memperbaiki import mahasiswa agar menggunakan `angkatan` sesuai schema terbaru, sekaligus tetap menerima kolom Excel lama `semester`.
- Memperbaiki validasi akun mahasiswa (`prodi_id` dan `angkatan`).
- Memperbaiki import dosen/mahasiswa agar baris Excel kosong tidak diproses dan error dapat ditampilkan dengan aman.
- Memperbaiki `UserFactory` agar role memiliki nilai default sehingga test/auth tidak gagal karena kolom enum `role` kosong.
- Memperbaiki timer quiz: waktu mulai sekarang dibuat saat mahasiswa pertama kali membuka quiz dan menggunakan session key per mahasiswa.
- Membatasi akses quiz berdasarkan kelas dan dosen pemiliknya.
- Membatasi akses materi, tugas, sesi absensi, dan koreksi tugas berdasarkan dosen pemilik kelas.
- Membatasi halaman mahasiswa berdasarkan role mahasiswa dan keanggotaan kelas.
- Memperbaiki pengurutan status tugas agar kompatibel dengan SQLite (tidak lagi memakai `FIELD()`).
- Memperbaiki validasi peserta kelas yang sebelumnya memiliki array validation key ganda.
- Membatasi daftar mata kuliah dosen hanya ke kelas yang benar-benar diampu dosen tersebut.
- Menambahkan route hapus quiz yang sebelumnya sudah memiliki method controller tetapi belum memiliki route.
- Menghapus route profile PUT duplikat; route profile update menggunakan PATCH.
- Memperbaiki nama tabel pada `down()` migration `materis`.
- Memperbaiki error display agar dapat menerima error string maupun array.
- Menghapus relasi self-reference yang tidak diperlukan pada model `Student`.
- Menjaga NIM sebagai input teks agar angka dengan leading zero tidak hilang.

## Verifikasi yang berhasil dilakukan

- Semua file PHP pada `app`, `config`, `database`, `routes`, dan `tests` lolos `php -l`.
- Laravel berhasil bootstrap dan `artisan route:list` berhasil dijalankan.
- Ditemukan 86 route tanpa duplicate method+URI.
- Semua controller class yang direferensikan route ditemukan.
- Semua JavaScript pada `resources/js` lolos `node --check`.
- `git diff --check` tidak menemukan whitespace error.

## Catatan environment pengujian

Environment pengujian ini tidak memiliki ekstensi PHP `pdo_sqlite`, `dom`, dan `xmlwriter`, sehingga migrasi database, PHPUnit Feature Test, dan Blade view cache tidak dapat dieksekusi di environment ini. Ini adalah keterbatasan environment pengujian, bukan error pada source code aplikasi.

Di komputer lokal, jalankan:

```bash
composer install
npm install
php artisan migrate:fresh --seed
php artisan storage:link
npm run build
php artisan serve
```

Pastikan PHP lokal memiliki ekstensi `mbstring`, `pdo_sqlite` (jika memakai SQLite), `dom`, dan `xmlwriter`.
