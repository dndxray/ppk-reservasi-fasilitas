<?php
// data dummy untuk merge pertaman doang
namespace Database\Seeders;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DataDummySeeder extends Seeder
{
    public function run(): void
    {
        $daftarFasilitas = [
            ['nama_fasilitas' => 'Ruang Kelas A101', 'tipe' => 'Ruang Kelas', 'lokasi' => 'Gedung A', 'kapasitas' => 40, 'deskripsi' => 'Ruang kelas lantai 1 dengan AC dan proyektor.', 'status' => 'aktif'],
            ['nama_fasilitas' => 'Ruang Kelas A102', 'tipe' => 'Ruang Kelas', 'lokasi' => 'Gedung A', 'kapasitas' => 40, 'deskripsi' => 'Ruang kelas lantai 1, dipakai untuk kuliah teori.', 'status' => 'aktif'],
            ['nama_fasilitas' => 'Ruang Kelas B201', 'tipe' => 'Ruang Kelas', 'lokasi' => 'Gedung B', 'kapasitas' => 30, 'deskripsi' => 'Ruang kelas lantai 2 untuk kelas kecil.', 'status' => 'aktif'],
            ['nama_fasilitas' => 'Aula Utama', 'tipe' => 'Aula', 'lokasi' => 'Gedung C', 'kapasitas' => 300, 'deskripsi' => 'Aula besar untuk seminar dan wisuda.', 'status' => 'aktif'],
            ['nama_fasilitas' => 'Aula Serbaguna', 'tipe' => 'Aula', 'lokasi' => 'Gedung C', 'kapasitas' => 150, 'deskripsi' => 'Aula untuk kegiatan organisasi mahasiswa.', 'status' => 'aktif'],
            ['nama_fasilitas' => 'Laboratorium Komputer 1', 'tipe' => 'Laboratorium', 'lokasi' => 'Gedung B', 'kapasitas' => 30, 'deskripsi' => 'Lab komputer untuk praktikum pemrograman.', 'status' => 'aktif'],
            ['nama_fasilitas' => 'Laboratorium Jaringan', 'tipe' => 'Laboratorium', 'lokasi' => 'Gedung B', 'kapasitas' => 25, 'deskripsi' => 'Lab jaringan, sedang perbaikan switch.', 'status' => 'dalam_perbaikan'],
            ['nama_fasilitas' => 'Laboratorium Multimedia', 'tipe' => 'Laboratorium', 'lokasi' => 'Gedung C', 'kapasitas' => 35, 'deskripsi' => 'Lab multimedia dengan komputer grafis.', 'status' => 'aktif'],
            ['nama_fasilitas' => 'Proyektor Portabel', 'tipe' => 'Alat', 'lokasi' => 'Gudang Sarana', 'kapasitas' => 1, 'deskripsi' => 'Proyektor yang bisa dipinjam untuk kegiatan.', 'status' => 'aktif'],
            ['nama_fasilitas' => 'Kamera DSLR', 'tipe' => 'Alat', 'lokasi' => 'Gudang Sarana', 'kapasitas' => 1, 'deskripsi' => 'Kamera dokumentasi kegiatan kampus.', 'status' => 'aktif'],
            ['nama_fasilitas' => 'Lapangan Futsal', 'tipe' => 'Lapangan', 'lokasi' => 'Area Olahraga', 'kapasitas' => 20, 'deskripsi' => 'Lapangan futsal bergantung jadwal olahraga.', 'status' => 'aktif'],
            ['nama_fasilitas' => 'Lapangan Basket', 'tipe' => 'Lapangan', 'lokasi' => 'Area Olahraga', 'kapasitas' => 24, 'deskripsi' => 'Lapangan basket, sementara nonaktif untuk perawatan ring.', 'status' => 'nonaktif'],
        ];

        foreach ($daftarFasilitas as $data) {
            Facility::updateOrCreate(
                ['nama_fasilitas' => $data['nama_fasilitas']],
                $data
            );
        }

        $daftarAkun = [
            ['name' => 'Admin Loka', 'email' => 'admin@loka.test', 'role' => 'admin', 'nim_nip' => '198501012010011001', 'no_telepon' => '081200000001', 'status_verifikasi' => 'terverifikasi'],
            ['name' => 'Petugas Loka', 'email' => 'petugas@loka.test', 'role' => 'petugas', 'nim_nip' => '199001012015011002', 'no_telepon' => '081200000002', 'status_verifikasi' => 'terverifikasi'],
            ['name' => 'Dinda Pengguna', 'email' => 'pengguna@loka.test', 'role' => 'pengguna', 'nim_nip' => '2211501001', 'no_telepon' => '081200000003', 'status_verifikasi' => 'terverifikasi'],
            ['name' => 'Akun Belum Diverifikasi', 'email' => 'belumverifikasi@loka.test', 'role' => 'pengguna', 'nim_nip' => '2211501002', 'no_telepon' => '081200000004', 'status_verifikasi' => 'menunggu'],
        ];

        foreach ($daftarAkun as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ])
            );
        }

        $fasilitas = Facility::where('nama_fasilitas', 'Ruang Kelas A101')->first();
        $pengguna = User::where('email', 'pengguna@loka.test')->first();

        if ($fasilitas && $pengguna) {
            $tanggal = now()->addDay()->toDateString();

            $contohReservasi = [
                ['waktu_mulai' => '08:00:00', 'waktu_selesai' => '09:00:00', 'status' => 'disetujui', 'tujuan_penggunaan' => 'Kuliah tambahan Pemrograman Web'],
                ['waktu_mulai' => '10:00:00', 'waktu_selesai' => '10:30:00', 'status' => 'menunggu', 'tujuan_penggunaan' => 'Rapat kelompok tugas besar'],
            ];

            foreach ($contohReservasi as $data) {
                DB::table('reservations')->updateOrInsert(
                    [
                        'facility_id' => $fasilitas->id,
                        'tanggal' => $tanggal,
                        'waktu_mulai' => $data['waktu_mulai'],
                        'waktu_selesai' => $data['waktu_selesai'],
                    ],
                    [
                        'user_id' => $pengguna->id,
                        'tujuan_penggunaan' => $data['tujuan_penggunaan'],
                        'status' => $data['status'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
