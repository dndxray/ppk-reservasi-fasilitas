# Sistem Reservasi & Pelaporan Fasilitas Kampus

Aplikasi web berbasis Laravel untuk mengelola penggunaan fasilitas kampus seperti ruang kelas, aula, laboratorium, alat, dan lapangan. Pengguna dapat mengecek ketersediaan fasilitas, mengajukan reservasi, dan melaporkan kerusakan. Petugas dan admin memproses kedua alur secara terpusat dalam satu sistem.

> Tugas Mata Kuliah **Pengembangan Platform Khusus (PPK)** 2026

---

## Struktur Proyek

```
ppk-reservasi-fasilitas/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/           ← Controller untuk admin
│   │   │   ├── Auth/            ← Controller autentikasi (Breeze)
│   │   │   ├── Petugas/         ← Controller untuk petugas
│   │   │   ├── FacilityController.php
│   │   │   ├── ReportController.php
│   │   │   └── ReservationController.php
│   │   ├── Middleware/
│   │   │   └── CekRole.php      ← Middleware penjaga akses per role
│   │   └── Requests/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Facility.php
│   │   ├── Reservation.php
│   │   └── Report.php
│   └── Providers/
├── config/                      ← Konfigurasi aplikasi Laravel
├── database/
│   ├── migrations/              ← Skema tabel database
│   └── seeders/
├── public/                      ← Entry point & aset publik (icons, images)
├── resources/
│   └── views/
│       ├── admin/               ← Tampilan khusus admin
│       ├── auth/                ← Tampilan login, register, dll.
│       ├── components/          ← Komponen UI reusable
│       ├── fasilitas/           ← Tampilan publik daftar & detail fasilitas
│       ├── layouts/             ← Layout utama (app, guest, sidebar, navbar)
│       ├── petugas/             ← Tampilan khusus petugas
│       ├── reports/             ← Tampilan laporan kerusakan
│       ├── reservations/        ← Tampilan reservasi pengguna
│       ├── beranda.blade.php
│       └── welcome.blade.php
└── routes/
    └── web.php                  ← Definisi seluruh route aplikasi
```

---


### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/dndxray/ppk-reservasi-fasilitas.git
cd ppk-reservasi-fasilitas

# 2. Install dependensi PHP
composer install

# 3. Salin file environment
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Konfigurasi database di file .env
#    DB_DATABASE=nama_database
#    DB_USERNAME=root
#    DB_PASSWORD=

# 6. Jalankan migrasi & seeder
php artisan migrate --seed

# 7. Buat symbolic link storage
php artisan storage:link

# 8. Jalankan server lokal
php artisan serve
```

Akses melalui **http://127.0.0.1:8000**



---

## Aktor & Hak Akses

| Aktor | Login | Hak Akses |
|-------|-------|-----------|
| **Pengunjung** | Tidak perlu | Melihat daftar fasilitas & ketersediaan slot waktu (tanpa detail pemohon) |
| **Pengguna** (mahasiswa / dosen / staf) | Perlu | Mengajukan reservasi, melaporkan kerusakan fasilitas |
| **Petugas** | Perlu | Memproses antrian reservasi & laporan, memperbarui status fasilitas |
| **Admin** | Perlu | Mengelola data master fasilitas, akun pengguna/petugas, dan rekap lintas fasilitas |

---

## Fitur Utama

### Pengunjung (tanpa login)
- Melihat daftar fasilitas beserta status ketersediaan per slot waktu (tersedia / tidak tersedia)
- Mencari fasilitas berdasarkan tipe, lokasi, atau kapasitas
- Tidak dapat melihat detail pemohon atau tujuan penggunaan reservasi

### Pengguna
- Registrasi akun mandiri (menunggu verifikasi admin)
- Login & logout
- Mengajukan reservasi fasilitas pada rentang waktu tertentu beserta tujuan penggunaan
- Membatalkan reservasi sendiri sebelum batas waktu tertentu
- Melihat riwayat dan status reservasi, termasuk detail lengkapnya
- Melaporkan kerusakan/masalah pada fasilitas (kategori, deskripsi, foto)
- Melihat status laporan yang pernah diajukan

### Petugas
- Melihat dashboard/antrian reservasi dan laporan yang menunggu diproses
- Menyetujui atau menolak reservasi masuk secara manual
- Sistem mencegah persetujuan reservasi yang bentrok jadwal pada fasilitas yang sama
- Membatalkan reservasi yang sudah disetujui dalam kondisi mendesak, disertai alasan pembatalan
- Mengubah status laporan (menunggu / diproses / selesai / ditolak) beserta catatan resolusi
- Menandai fasilitas berstatus **"dalam perbaikan"** berdasarkan laporan yang ditangani, dan mengembalikan ke aktif setelah selesai
- Melihat rekap laporan kerusakan per fasilitas (export CSV / Excel / PDF)

### Admin
- Mendaftarkan akun petugas secara langsung (petugas tidak bisa registrasi mandiri)
- Mendaftarkan akun pengguna secara langsung
- Memverifikasi atau menolak akun pengguna hasil registrasi mandiri
- Mengelola data fasilitas (tambah / edit / nonaktifkan)
- Melihat dashboard ringkasan: total pengguna, petugas, fasilitas, dan reservasi bulan ini
- Melihat dan mengekspor rekap okupansi fasilitas & frekuensi kerusakan (CSV / Excel / PDF)

---

## Ketentuan Waktu Reservasi

- Jam operasional: **07.00 – 20.00**
- Slot waktu tetap berdurasi **30 menit** (contoh: 07.00–07.30, 07.30–08.00, dst.)
- `waktu_mulai` dan `waktu_selesai` wajib berada dalam rentang jam operasional dan merupakan kelipatan slot 30 menit
- Validasi waktu dilakukan di **sisi server** (PHP), tidak hanya melalui tampilan kalender di sisi client

---

## Validasi Data

| Lapisan | Implementasi |
|---------|-------------|
| **Server (PHP)** | Laravel Form Request, validasi di Controller (`validate()`) |
| **Client (JavaScript)** | Validasi interaktif pada form reservasi dan laporan sebelum submit |

---

## Alur Status

### Reservasi
```
Diajukan
  └─► Menunggu Persetujuan
        ├─► Disetujui
        │     └─► Dibatalkan (oleh pengguna sebelum batas waktu,
        │                     atau oleh petugas dalam kondisi mendesak)
        └─► Ditolak
```

### Laporan Kerusakan
```
Menunggu
  └─► Diproses
        ├─► Selesai
        └─► Ditolak
```

> Saat laporan sedang diproses, petugas dapat menandai fasilitas terkait sebagai **"dalam perbaikan"** sehingga fasilitas tersebut tidak dapat direservasi hingga perbaikan selesai.
