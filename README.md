# Sistem Reservasi & Pelaporan Fasilitas Kampus

Aplikasi web untuk mengelola penggunaan fasilitas kampus seperti ruang kelas, aula, laboratorium, alat, dan lapangan. 

---

## Aktor & Hak Akses

| Aktor | Login | Akses |
|---|---|---|
| **Pengunjung** | Tidak perlu | Melihat daftar fasilitas dan ketersediaan jadwal (tanpa detail pemohon) |
| **Pengguna** (mahasiswa/dosen/staf) | Perlu | Mengajukan reservasi, melaporkan kerusakan fasilitas |
| **Petugas** | Perlu | Memproses antrian reservasi & laporan, memperbarui status fasilitas |
| **Admin** | Perlu | Mengelola data master fasilitas, akun, dan rekap lintas fasilitas |

---

## Fitur Utama

### Pengunjung
- Melihat daftar fasilitas beserta status ketersediaan per slot waktu (tersedia/tidak tersedia)
- Mencari fasilitas berdasarkan tipe, lokasi, atau kapasitas
- Tidak dapat melihat detail pemohon atau tujuan penggunaan reservasi

### Pengguna
- Registrasi dan login akun
- Mengajukan reservasi fasilitas pada rentang waktu tertentu beserta tujuan penggunaan
- Membatalkan reservasi sendiri sebelum batas waktu tertentu
- Melihat riwayat dan status reservasi, termasuk detail lengkapnya
- Melaporkan kerusakan/masalah pada fasilitas (kategori, deskripsi, foto)
- Melihat status laporan yang pernah diajukan

### Petugas
- Melihat dashboard/antrian reservasi dan laporan yang menunggu diproses
- Menyetujui atau menolak reservasi yang masuk secara manual
- Sistem mencegah persetujuan reservasi yang bentrok jadwal pada fasilitas yang sama
- Membatalkan reservasi yang sudah disetujui dalam kondisi mendesak, disertai alasan pembatalan
- Mengubah status laporan (baru/diproses/selesai/ditolak) beserta catatan resolusi
- Menandai fasilitas berstatus "dalam perbaikan" terkait laporan yang sedang ditangani, dan mengembalikan ke status aktif setelah selesai

### Admin
- Mendaftarkan akun petugas secara langsung (petugas tidak melakukan registrasi mandiri)
- Mendaftarkan akun pengguna secara langsung tanpa melalui registrasi mandiri
- Memverifikasi atau menolak akun pengguna hasil registrasi mandiri sebelum dapat digunakan untuk login
- Mengelola data fasilitas (tambah/edit/nonaktifkan)
- Melihat dan mengekspor rekap okupansi fasilitas serta frekuensi kerusakan per fasilitas/lokasi (CSV/Excel/PDF)

---

## Ketentuan Waktu Reservasi

- Jam operasional fasilitas: **07.00 – 20.00**
- Reservasi menggunakan slot waktu tetap berdurasi **30 menit** (contoh: 07.00–07.30, 07.30–08.00, dst.)
- `start_time` dan `end_time` wajib berada dalam rentang jam operasional dan merupakan kelipatan slot 30 menit
- Validasi rentang waktu dilakukan di **sisi server**, bukan hanya pada tampilan kalender di sisi client

---

## Alur Status

**Reservasi**
```
Diajukan → Menunggu Persetujuan → Disetujui / Ditolak
Disetujui → Dibatalkan (oleh pengguna sebelum batas waktu, atau oleh petugas dalam kondisi mendesak)
```

**Laporan Kerusakan**
```
Baru → Diproses → Selesai / Ditolak
```

Saat laporan sedang ditangani, petugas dapat menandai fasilitas terkait sebagai "dalam perbaikan", lalu mengembalikannya ke status aktif setelah perbaikan selesai.

---

## Academic Project
Project ini dikembangkan sebagai tugas mata kuliah Pengembangan Platform Khusus (PPK) 2026.
