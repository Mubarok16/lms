# Akses Laravel dari HP melalui Jaringan Lokal

Panduan menjalankan project Laravel di komputer agar dapat diakses melalui HP yang berada pada jaringan Wi-Fi/LAN yang sama.

## 1. Cek IP Address Komputer

Buka **Command Prompt (CMD)** atau PowerShell di komputer, kemudian jalankan:

```bash
ipconfig
```

Cari bagian **IPv4 Address** pada adapter jaringan yang sedang digunakan.

Contoh:

```text
IPv4 Address. . . . . . . . . . . : 10.166.126.191
```

Catat alamat IP tersebut karena akan digunakan untuk mengakses Laravel dari HP.

> Pastikan komputer dan HP terhubung ke jaringan Wi-Fi/LAN yang sama.

---

## 2. Jalankan Laravel agar Bisa Diakses dari HP

Secara default, Laravel hanya dapat diakses dari komputer melalui `localhost`.

Jalankan Laravel dengan:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Jika berhasil, biasanya akan muncul:

```text
INFO  Server running on [http://0.0.0.0:8000].
```

`0.0.0.0` membuat Laravel menerima koneksi dari perangkat lain yang berada dalam jaringan yang sama.

---

## 3. Update APP_URL pada `.env`

Buka file:

```text
.env
```

Cari:

```env
APP_URL=http://localhost
```

Kemudian ubah menjadi IP komputer.

Contoh jika IPv4 komputer adalah:

```text
10.166.126.191
```

maka:

```env
APP_URL=http://10.166.126.191:8000
```

Pengaturan ini penting terutama jika aplikasi menggunakan `route()` untuk menghasilkan URL absolut, misalnya pada QR Code absensi.

Contoh:

```php
route('mahasiswa.absensi.scan', $token)
```

URL yang dihasilkan akan mengarah ke alamat komputer yang dapat diakses melalui jaringan lokal.

---

## 4. Bersihkan Cache Konfigurasi Laravel

Setelah mengubah `.env`, jalankan:

```bash
php artisan config:clear
```

Jika diperlukan, bisa juga menjalankan:

```bash
php artisan cache:clear
```

---

## 5. Akses Laravel dari HP

Setelah Laravel berjalan, buka browser di HP.

Jangan menggunakan:

```text
http://localhost:8000
```

Gunakan IP komputer:

```text
http://10.166.126.191:8000
```

Sesuaikan dengan IPv4 komputer kamu.

Contoh:

```text
http://10.166.126.191:8000
```

Jika halaman Laravel muncul, berarti HP sudah berhasil terhubung ke aplikasi Laravel di komputer.

---

## 6. Jika Tidak Bisa Diakses dari HP

Jika Laravel sudah dijalankan tetapi HP tidak dapat membuka halaman, periksa beberapa hal berikut.

### A. Pastikan HP dan komputer berada pada jaringan yang sama

Contoh:

```text
Komputer → Wi-Fi Kampus
HP       → Wi-Fi Kampus
```

Keduanya harus berada pada jaringan yang memungkinkan komunikasi antar-perangkat.

### B. Pastikan IP yang digunakan benar

Jalankan kembali:

```bash
ipconfig
```

Kemudian gunakan IPv4 dari adapter jaringan yang aktif.

### C. Periksa Windows Firewall

Windows Firewall dapat memblokir koneksi ke port `8000`.

Jika muncul permintaan izin dari Windows Firewall ketika menjalankan Laravel, izinkan akses pada jaringan yang sesuai.

### D. Pastikan Laravel masih berjalan

Terminal harus tetap menjalankan:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Jangan menutup terminal tersebut selama aplikasi ingin diakses dari HP.

---

# Update Project Laravel ke GitHub

Setelah melakukan perubahan pada project Laravel, gunakan Git untuk mengirim perubahan ke repository GitHub.

## 1. Cek perubahan

Buka terminal di folder project Laravel:

```bash
git status
```

Perintah ini akan menampilkan file yang berubah, ditambahkan, atau dihapus.

---

## 2. Tambahkan perubahan ke Git

Untuk menambahkan semua perubahan:

```bash
git add .
```

Kemudian cek kembali:

```bash
git status
```

---

## 3. Buat Commit

Contoh:

```bash
git commit -m "Update fitur absensi"
```

Gunakan pesan commit yang menjelaskan perubahan yang dilakukan.

Contoh lainnya:

```bash
git commit -m "Add QR code attendance"
```

atau:

```bash
git commit -m "Fix attendance session"
```

---

## 4. Pull Perubahan Terbaru dari GitHub

Sebelum melakukan push, sebaiknya ambil perubahan terbaru dari GitHub:

```bash
git pull origin main
```

Jika project menggunakan branch lain, sesuaikan `main` dengan branch yang digunakan.

Contoh:

```bash
git pull origin develop
```

Jika terjadi conflict, selesaikan conflict terlebih dahulu sebelum melanjutkan.

---

## 5. Push ke GitHub

Jika menggunakan branch `main`:

```bash
git push origin main
```

Jika menggunakan branch `develop`:

```bash
git push origin develop
```

Setelah berhasil, perubahan project lokal sudah dikirim ke repository GitHub.

---

# Alur Singkat

### Menjalankan Laravel agar bisa diakses HP

```bash
ipconfig
```

Cari IPv4, misalnya:

```text
10.166.126.191
```

Kemudian:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

`.env`:

```env
APP_URL=http://10.166.126.191:8000
```

Kemudian:

```bash
php artisan config:clear
```

Akses dari HP:

```text
http://10.166.126.191:8000
```

### Update project ke GitHub

```bash
git status
git add .
git commit -m "Update fitur"
git pull origin main
git push origin main
```

> **Catatan:** Jangan memasukkan file `.env` ke GitHub karena file tersebut biasanya berisi konfigurasi database, password, API key, dan informasi sensitif lainnya. Pastikan `.env` sudah tercantum di `.gitignore`.

## Install maatwebsite/excel.

aplikasi ini menggunakan file excel sebagai templating, maka perlu dilakukan instalasi maatwebsite/excel, caranya:

```bash
composer require maatwebsite/excel
```

kalau error, pstikan di composer.json seperti ini:
```bash
"maatwebsite/excel": "^3.1",
```
