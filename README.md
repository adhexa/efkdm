# e-FKDM (Elektronik Forum Kewaspadaan Dini Masyarakat)

Sistem Informasi Pelaporan & Deteksi Dini Kerawanan Daerah berbasis **Laravel 11**, **Tailwind CSS CDN**, dan **SQLite**.

---

## 🚀 Fitur Utama Aplikasi

1. **Halaman Beranda & Publik (`/`)**:
   - Informasi FKDM & Kesbangpol.
   - Peringatan darurat & statistik real-time.
   - Form lapor kejadian cepat bagi warga.

2. **Dashboard Peta GIS Interaktif (`/dashboard`)**:
   - **Peta Leaflet.js**: Menampilkan sebaran titik potensi kerawanan berdasarkan koordinat lokasi insiden (Warna Merah/Kuning/Hijau).
   - **Analisis Grafik Chart.js**: Distribusi laporan per kategori isu.
   - **Ringkasan KPI**: Laporan masuk, status penanganan, dan tingkat risiko.
   - **Filter & Pencarian**: Filter berdasarkan wilayah, kategori, status, dan tingkat kerawanan.

3. **Manajemen Laporan Deteksi Dini (`/reports`)**:
   - Pembuatan Laporan Informasi (LI) lengkap dengan *location picker* pada peta.
   - Status penanganan (Menunggu Verifikasi, Terverifikasi, Dalam Penanganan, Selesai, Ditolak).
   - Timeline log riwayat penanganan oleh petugas.
   - **Cetak Laporan Resmi (`/reports/{id}/print`)**: Format dokumen cetak Laporan Informasi (LI) untuk Kesbangpol / Pimpinan.

4. **Multi Peran (Role-based Access)**:
   - **Admin / Kesbangpol**: Akses penuh ke seluruh fitur dan manajemen sistem.
   - **Anggota FKDM**: Verifikasi laporan lapangan, pembaruan status kerawanan, dan penambahan catatan tindak lanjut.
   - **Masyarakat**: Mengirim laporan dan memantau status laporan pribadi.

---

## 🛠️ Langkah-Langkah Instalasi & Pengoperasian

Karena perintah `composer install` akan Anda jalankan secara manual, ikuti langkah mudah berikut pada terminal di folder proyek ini (`d:\Bengkel-Programer\efkdm`):

### 1. Install Dependensi PHP Composer
```bash
composer install
```

### 2. Generate Application Key
```bash
php artisan key:generate
```

### 3. Migrasi & Seed Database SQLite
File database SQLite (`database/database.sqlite`) dan file konfigurasi `.env` sudah siap pakai. Jalankan perintah berikut untuk mengisi data awal demo:
```bash
php artisan migrate --seed
```

### 4. Jalankan Server Lokal
```bash
php artisan serve
```
Akses aplikasi melalui peramban web di: **`http://localhost:8000`**

---

## 🔑 Akun Demo Pengujian

Setiap akun telah di-seed otomatis dengan password default: `password123`

| Peran (Role) | Email | Password | Hak Akses |
| :--- | :--- | :--- | :--- |
| **Admin Kesbangpol** | `admin@efkdm.go.id` | `password123` | Akses penuh dashboard, cetak LI, update status |
| **Anggota FKDM** | `fkdm@efkdm.go.id` | `password123` | Verifikasi laporan, tindak lanjut lapangan, cetak LI |
| **Warga Pelapor** | `warga@efkdm.go.id` | `password123` | Kirim laporan & cek status laporan pribadi |

---

## 📂 Struktur Utama Proyek

- `app/Models/` : Model `User`, `Category`, `Report`, `ReportAction`.
- `app/Http/Controllers/` : `HomeController`, `ReportController`, `DashboardController`, `AuthController`.
- `database/migrations/` : Skema tabel SQLite.
- `database/seeders/` : `DatabaseSeeder.php`.
- `routes/web.php` : Rute navigasi e-FKDM.
- `resources/views/` : Layout Blade + Tailwind CSS CDN, Leaflet.js, & Chart.js.
