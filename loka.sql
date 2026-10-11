
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for loka
CREATE DATABASE IF NOT EXISTS `loka` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `loka`;

-- Dumping structure for table loka.cache
DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table loka.cache: ~0 rows (approximately)

-- Dumping structure for table loka.cache_locks
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table loka.cache_locks: ~0 rows (approximately)

-- Dumping structure for table loka.facilities
DROP TABLE IF EXISTS `facilities`;
CREATE TABLE IF NOT EXISTS `facilities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_fasilitas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kapasitas` int unsigned DEFAULT NULL,
  `kuantitas` int unsigned DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('aktif','dalam_perbaikan','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table loka.facilities: ~0 rows (approximately)
INSERT INTO `facilities` (`id`, `nama_fasilitas`, `tipe`, `lokasi`, `kapasitas`, `kuantitas`, `deskripsi`, `foto`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Ruang Kelas A101', 'Ruang Kelas', 'Gedung A', 40, NULL, 'Ruang kelas lantai 1 dengan AC dan proyektor.', NULL, 'aktif', '2026-10-11 02:51:17', '2026-10-11 02:51:17', NULL),
	(2, 'Ruang Kelas A102', 'Ruang Kelas', 'Gedung A', 40, NULL, 'Ruang kelas lantai 1, dipakai untuk kuliah teori.', NULL, 'aktif', '2026-10-11 02:51:17', '2026-10-11 02:51:17', NULL),
	(3, 'Ruang Kelas B201', 'Ruang Kelas', 'Gedung B', 30, NULL, 'Ruang kelas lantai 2 untuk kelas kecil.', NULL, 'aktif', '2026-10-11 02:51:17', '2026-10-11 02:51:17', NULL),
	(4, 'Aula Utama', 'Aula', 'Gedung C', 300, NULL, 'Aula besar untuk seminar dan wisuda.', NULL, 'aktif', '2026-10-11 02:51:17', '2026-10-11 02:51:17', NULL),
	(5, 'Aula Serbaguna', 'Aula', 'Gedung C', 150, NULL, 'Aula untuk kegiatan organisasi mahasiswa.', NULL, 'aktif', '2026-10-11 02:51:17', '2026-10-11 02:51:17', NULL);

-- Dumping structure for table loka.failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table loka.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table loka.jobs
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table loka.jobs: ~0 rows (approximately)

-- Dumping structure for table loka.job_batches
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table loka.job_batches: ~0 rows (approximately)

-- Dumping structure for table loka.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table loka.migrations: ~1 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_09_04_100000_add_role_and_profile_fields_to_users_table', 1),
	(5, '2026_09_04_100001_create_facilities_table', 1),
	(6, '2026_09_04_100002_create_reservations_table', 1),
	(7, '2026_09_04_100003_create_reports_table', 1),
	(8, '2026_09_23_095847_add_tanggal_ditemukan_to_reports_table', 1),
	(9, '2026_09_30_105722_add_foto_to_facilities_table', 1),
	(10, '2026_09_30_203000_change_status_baru_to_menunggu_in_reports_table', 1),
	(11, '2026_10_03_150608_tambah_nonaktif_ke_status_verifikasi_users', 1),
	(12, '2026_10_03_233651_tambah_kuantitas_ke_facilities', 1),
	(13, '2026_10_10_150000_add_alat_fields_to_reservations_table', 1),
	(14, '2026_10_10_200101_add_jenis_pengguna_to_users_table', 1),
	(15, '2026_10_10_211419_add_unique_to_no_telepon_in_users_table', 1),
	(16, '2026_10_11_073352_add_deleted_at_to_facilities_table', 1),
	(17, '2026_10_11_075130_add_deleted_at_to_users_table', 1);

-- Dumping structure for table loka.password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table loka.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table loka.reports
DROP TABLE IF EXISTS `reports`;
CREATE TABLE IF NOT EXISTS `reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `facility_id` bigint unsigned NOT NULL,
  `kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_ditemukan` date DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('menunggu','baru','diproses','selesai','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `catatan_resolusi` text COLLATE utf8mb4_unicode_ci,
  `diproses_oleh` bigint unsigned DEFAULT NULL,
  `diselesaikan_pada` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reports_user_id_foreign` (`user_id`),
  KEY `reports_facility_id_foreign` (`facility_id`),
  KEY `reports_diproses_oleh_foreign` (`diproses_oleh`),
  CONSTRAINT `reports_diproses_oleh_foreign` FOREIGN KEY (`diproses_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reports_facility_id_foreign` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table loka.reports: ~0 rows (approximately)

-- Dumping structure for table loka.reservations
DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `facility_id` bigint unsigned NOT NULL,
  `jumlah` int unsigned DEFAULT NULL,
  `tanggal` date NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time NOT NULL,
  `tujuan_penggunaan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('menunggu','disetujui','ditolak','dibatalkan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `alasan_pembatalan` text COLLATE utf8mb4_unicode_ci,
  `diproses_oleh` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reservations_user_id_foreign` (`user_id`),
  KEY `reservations_facility_id_foreign` (`facility_id`),
  KEY `reservations_diproses_oleh_foreign` (`diproses_oleh`),
  CONSTRAINT `reservations_diproses_oleh_foreign` FOREIGN KEY (`diproses_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reservations_facility_id_foreign` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reservations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table loka.reservations: ~0 rows (approximately)

-- Dumping structure for table loka.sessions
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table loka.sessions: ~0 rows (approximately)

-- Dumping structure for table loka.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('pengguna','petugas','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pengguna',
  `nim_nip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telepon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_verifikasi` enum('menunggu','terverifikasi','ditolak','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `jenis_pengguna` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_no_telepon_unique` (`no_telepon`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table loka.users: ~1 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `role`, `nim_nip`, `no_telepon`, `status_verifikasi`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `jenis_pengguna`, `deleted_at`) VALUES
	(1, 'Test User', 'test@example.com', 'pengguna', NULL, NULL, 'menunggu', '2026-10-11 02:36:26', '$2y$12$SuO1XY9l2K8MfF0cwKN5BO9maLduHzDEuJy3j6.hllzAvJ1w6p/Ni', 'coblCIRtyd', '2026-10-11 02:36:27', '2026-10-11 02:36:27', NULL, NULL),
	(2, 'Admin Loka', 'admin@loka.test', 'admin', '198501012010011001', '081200000001', 'terverifikasi', '2026-10-11 02:44:39', '$2y$12$BeeZA7gmrtWXoNzCarfBEe./Pf1hU6HI792dH6kjIdEmKXsedytF6', NULL, '2026-10-11 02:44:39', '2026-10-11 02:44:39', NULL, NULL),
	(3, 'pengguna', 'pengguna@gmail.com', 'pengguna', NULL, '0888888888', 'terverifikasi', NULL, '$2y$12$7rDV7XUQMVuTPKDZMyyY9.iOWQcoOT7XI6T2WgQpcrj6nfkFvYA9u', NULL, '2026-10-11 02:46:52', '2026-10-11 02:46:52', 'mahasiswa', NULL),
	(4, 'petugas', 'petugas@gmail.com', 'petugas', NULL, '0888888892', 'terverifikasi', NULL, '$2y$12$8XbY8k6XdFbKBCjLio3gD.Ft1rgvF9ynBTQ5.yS00kKnGbGbOhAB6', NULL, '2026-10-11 02:47:46', '2026-10-11 02:47:46', NULL, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
