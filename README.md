# SIM Penjualan

## Deskripsi Singkat
Aplikasi Sistem Informasi Manajemen (SIM) Penjualan berbasis web untuk membantu tim penjualan mengelola data barang, pelanggan, transaksi, dan laporan dalam satu dashboard yang modern. Sistem mendukung multi-level user (admin, kasir, manager) dengan tampilan interaktif dan notifikasi toast.

## Stack Teknologi
- PHP 7.x (native, PDO)
- MySQL / MariaDB
- HTML5 & CSS3 kustom + Font Awesome
- JavaScript (jQuery, Vanilla Tilt, komponen toast)

## Fitur Utama
- Autentikasi berbasis sesi & cookie dengan tiga peran utama: admin, kasir, dan manager.
- Manajemen master data: barang, jenis barang, pelanggan, serta pengelolaan pengguna.
- Modul transaksi kasir lengkap dengan pencarian cepat barang/pelanggan dan cetak struk.
- Laporan penjualan dengan filter per tanggal, bulan, dan tahun serta opsi cetak.
- Pengaturan ulang kata sandi mandiri bagi pengguna yang sudah masuk.

## Cara Menjalankan
1. Pastikan lingkungan PHP dan MySQL aktif (mis. XAMPP, Laragon, atau stack serupa).
2. Salin repositori ini ke direktori web server Anda (contoh: `htdocs/sim-penjualan-main`).
3. Buat database dengan nama `db_penjualan` lalu impor berkas `db_penjualan.sql`.
4. Jika kredensial database berbeda, sesuaikan konfigurasi pada `system/proses.php`.
5. Aktifkan web server, lalu akses aplikasi melalui `http://localhost/sim-penjualan-main/login.php`.
6. Masuk menggunakan salah satu akun awal berikut:

   | Username | Password | Level    |
   |----------|----------|----------|
   | admin    | admin    | admin    |
   | manager  | manager  | manager  |
   | kasir    | kasir    | kasir    |

## Screenshot Tampilan UI
![Dashboard](assets/screenshots/dashboard-placeholder.png)

> Ganti `assets/screenshots/dashboard-placeholder.png` dengan tangkapan layar terbaru dari dashboard aplikasi Anda.

