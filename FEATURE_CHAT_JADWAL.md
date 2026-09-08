# Fitur Chat & Jadwal Mata Kuliah

## Setelah project dijalankan

1. Pastikan dependency project terpasang (`composer install` jika folder `vendor` belum ada).
2. Pastikan `.env` dan koneksi database benar.
3. Jalankan migration:

```bash
php artisan migrate
```

4. Jalankan aplikasi seperti biasa.

## Chat mata kuliah

- Menu **Pesan** tersedia di sidebar admin, dosen, dan mahasiswa.
- Mata kuliah yang dapat diakses juga tampil langsung di bawah menu Pesan.
- Admin dapat melihat semua ruang chat kelas.
- Dosen hanya dapat masuk ke kelas yang diampu melalui `pengajaran_dosen`.
- Mahasiswa hanya dapat masuk ke kelas yang diikuti melalui `pengajaran_mahasiswa`.
- Pesan tersimpan di database dan halaman mengambil balasan baru otomatis setiap 3 detik.

## Jadwal mata kuliah

- Menu **Jadwal Kuliah** tersedia di sidebar.
- Admin dapat tambah, edit, hapus, dan import jadwal.
- Dosen dan mahasiswa hanya dapat melihat jadwal kelas masing-masing.
- Template import dapat diunduh dari halaman Jadwal oleh admin.

Kolom import Excel:

- `kode_mk`
- `kode_kelas`
- `hari`
- `jam_mulai`
- `jam_selesai`
- `ruangan`

Contoh waktu: `08:00` dan `09:40`.
