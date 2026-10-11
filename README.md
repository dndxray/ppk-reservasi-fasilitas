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
│       └── beranda.blade.php
├── routes/
│   └── web.php                  ← Definisi seluruh route aplikasi
├── loka.sql                     ← SQL dump untuk setup cepat (opsional)
└── .env.example                 ← Template konfigurasi environment
```

---

## Prasyarat

- **Laragon Full** (sudah termasuk PHP 8.2+, MySQL, Composer, Node.js, npm, Git).
- Jika memakai Laragon Lite, install Node.js manual dari [nodejs.org](https://nodejs.org).

---

## Langkah Instalasi

```bash
# 1. Clone repository ke folder www Laragon
cd C:\laragon\www
git clone https://github.com/dndxray/ppk-reservasi-fasilitas.git
cd ppk-reservasi-fasilitas

# 2. Install dependensi PHP
composer install

# 3. Install dependensi Node.js
npm install

# 4. Salin file environment
copy .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Konfigurasi database di file .env
#    DB_CONNECTION=mysql
#    DB_HOST=127.0.0.1
#    DB_PORT=3306
#    DB_DATABASE=loka
#    DB_USERNAME=root
#    DB_PASSWORD=

# 7. Setup database (pilih SALAH SATU cara berikut):

#    Cara A — Migration + Seeder (butuh create DB manual):
mysql -u root -e "CREATE DATABASE loka CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate --seed

#    Cara B — Import file SQL (instalasi cepat, DB dibuat otomatis):
#       Buka HeidiSQL (bawaan Laragon)
#       → Connect: 127.0.0.1 / root / (password kosong)
#       → File → Run SQL file → pilih loka.sql
#       Database "loka" akan dibuat otomatis beserta data contoh

# 8. Buat symbolic link untuk file upload
php artisan storage:link

# 9. Compile asset frontend (Tailwind + Alpine)
npm run build

# 10. Jalankan server lokal
php artisan serve
```

Akses aplikasi melalui **http://127.0.0.1:8000**

---

## Akun Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@loka.test | password |
| Petugas | petugas@gmail.com | password |
| Pengguna | pengguna@gmail.com | password |

> Jika login gagal, jalankan `php artisan tinker` lalu reset:
> `App\Models\User::find(2)->update(['password' => bcrypt('password')]);`

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
| **Server (PHP)** | Validasi di Controller (`validate()`) dan helper `filterKetersediaan()` pada `FacilityController` |
| **Client (HTML5)** | Atribut `required`, `min`, `max`, `type="number"`, `type="date"`, `type="time"` pada form |

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

---

## Catatan Teknis

- **Validasi waktu** dilakukan di server (`FacilityController::filterKetersediaan`), bukan hanya di tampilan kalender, untuk mencegah bypass melalui request langsung (Postman/curl).
- **Prepared Statement** otomatis di Eloquent — mencegah SQL injection.
- **Session** menyimpan status login dan riwayat fasilitas terakhir dilihat (tidak disimpan di cookie).
- **Cookie** hanya dipakai untuk session ID dan CSRF token dengan atribut HttpOnly + SameSite=Lax.
- **Setup database** dapat dilakukan dengan 2 cara: `migrate --seed` (development) atau import `loka.sql` (instalasi cepat).

---

## Troubleshooting

| Error | Solusi |
|-------|--------|
| `Vite manifest not found` | Jalankan `npm run build` |
| `Access denied for user` | Cek `DB_USERNAME` & `DB_PASSWORD` di `.env` |
| `No application encryption key` | Jalankan `php artisan key:generate` |
| Gambar tidak muncul | Jalankan `php artisan storage:link` |
| Perubahan `.env` tidak terbaca | Jalankan `php artisan config:clear` |
