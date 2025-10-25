-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 25, 2025 at 08:41 AM
-- Server version: 11.5.2-MariaDB
-- PHP Version: 8.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eqiyu_kita`
--

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

DROP TABLE IF EXISTS `berita`;
CREATE TABLE IF NOT EXISTS `berita` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `judul` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `konten` text NOT NULL,
  `gambar_utama` varchar(255) DEFAULT NULL,
  `penulis` varchar(100) DEFAULT NULL,
  `tanggal_terbit` datetime DEFAULT current_timestamp(),
  `status` enum('draft','publish') DEFAULT 'draft',
  `kategori_id` int(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `kategori_id` (`kategori_id`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `berita_tag`
--

DROP TABLE IF EXISTS `berita_tag`;
CREATE TABLE IF NOT EXISTS `berita_tag` (
  `berita_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`berita_id`,`tag_id`),
  KEY `tag_id` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bonus_kelas`
--

DROP TABLE IF EXISTS `bonus_kelas`;
CREATE TABLE IF NOT EXISTS `bonus_kelas` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_kelas` varchar(20) NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `path_file` text NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `kode_kelas` (`kode_kelas`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_online`
--

DROP TABLE IF EXISTS `course_online`;
CREATE TABLE IF NOT EXISTS `course_online` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kelas_id` int(10) UNSIGNED NOT NULL,
  `judul_modul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `kelas_id` (`kelas_id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

DROP TABLE IF EXISTS `folders`;
CREATE TABLE IF NOT EXISTS `folders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `google_photos_url` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=158 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_kelas`
--

DROP TABLE IF EXISTS `jadwal_kelas`;
CREATE TABLE IF NOT EXISTS `jadwal_kelas` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kelas_id` int(10) UNSIGNED NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `lokasi` enum('malang','jogja') DEFAULT NULL,
  `instruktur` varchar(100) DEFAULT NULL,
  `kapasitas` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kelas_id` (`kelas_id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori_berita`
--

DROP TABLE IF EXISTS `kategori_berita`;
CREATE TABLE IF NOT EXISTS `kategori_berita` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nama_kategori` (`nama_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

DROP TABLE IF EXISTS `kelas`;
CREATE TABLE IF NOT EXISTS `kelas` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_kelas` varchar(20) NOT NULL,
  `nama_kelas` varchar(100) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `deskripsi_singkat` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(12,2) NOT NULL,
  `durasi` varchar(50) DEFAULT NULL,
  `kategori` enum('Kursus','Jasa','kursusonline') DEFAULT NULL,
  `gambar_utama` varchar(255) DEFAULT NULL,
  `gambar_tambahan` text DEFAULT NULL,
  `status_kelas` enum('aktif','nonaktif','segera') DEFAULT 'aktif',
  `badge` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `kota_tersedia` varchar(255) DEFAULT NULL COMMENT 'Daftar kota tempat kelas tersedia, dipisahkan dengan koma',
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_kelas` (`kode_kelas`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `modul_file`
--

DROP TABLE IF EXISTS `modul_file`;
CREATE TABLE IF NOT EXISTS `modul_file` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` int(10) UNSIGNED NOT NULL,
  `tipe` varchar(20) NOT NULL COMMENT 'Jenis file: video, pdf, doc, docx, xls, xlsx, dll',
  `judul_file` varchar(255) DEFAULT NULL,
  `file_url` text NOT NULL,
  `urutan` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`)
) ENGINE=MyISAM AUTO_INCREMENT=133 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registrasi`
--

DROP TABLE IF EXISTS `registrasi`;
CREATE TABLE IF NOT EXISTS `registrasi` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_kelas` varchar(20) NOT NULL,
  `kode_voucher` varchar(50) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `kabupaten` varchar(100) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `kodepos` varchar(10) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `biaya_total` decimal(12,2) NOT NULL,
  `biaya_dibayar` decimal(12,2) DEFAULT 0.00,
  `status_pembayaran` enum('DP 50%','lunas') DEFAULT 'lunas',
  `tanggal_daftar` datetime DEFAULT current_timestamp(),
  `tanggal_update` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `jadwal_id` int(10) UNSIGNED DEFAULT NULL,
  `akses_aktif` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=tidak aktif, 1=aktif',
  PRIMARY KEY (`id`),
  KEY `jadwal_id` (`jadwal_id`),
  KEY `kode_voucher` (`kode_voucher`)
) ENGINE=InnoDB AUTO_INCREMENT=2592 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Triggers `registrasi`
--
DROP TRIGGER IF EXISTS `trg_update_nama_kelas_on_registrasi_update`;
DELIMITER $$
CREATE TRIGGER `trg_update_nama_kelas_on_registrasi_update` AFTER UPDATE ON `registrasi` FOR EACH ROW BEGIN
    UPDATE sertifikat s
    SET s.nama_kelas = NEW.kode_kelas
    WHERE s.registrasi_id = NEW.id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `registrasi_jasa`
--

DROP TABLE IF EXISTS `registrasi_jasa`;
CREATE TABLE IF NOT EXISTS `registrasi_jasa` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `jasa_id` int(10) UNSIGNED NOT NULL,
  `nama_pendaftar` varchar(100) NOT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `deskripsi_kebutuhan` text DEFAULT NULL,
  `kode_voucher` varchar(50) DEFAULT NULL,
  `tanggal_daftar` datetime DEFAULT current_timestamp(),
  `status_pembayaran` enum('lunas') DEFAULT 'lunas',
  `biaya_total` decimal(12,2) DEFAULT NULL,
  `catatan_admin` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jasa_id` (`jasa_id`),
  KEY `kode_voucher` (`kode_voucher`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sertifikat`
--

DROP TABLE IF EXISTS `sertifikat`;
CREATE TABLE IF NOT EXISTS `sertifikat` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `registrasi_id` int(10) UNSIGNED NOT NULL,
  `nama_pemilik` varchar(100) DEFAULT NULL,
  `kota_kelas` varchar(100) DEFAULT NULL,
  `nomor_sertifikat` varchar(50) NOT NULL,
  `tanggal_terbit` date DEFAULT curdate(),
  `nama_kelas` varchar(100) DEFAULT NULL,
  `status` enum('aktif','dibatalkan') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nomor_sertifikat` (`nomor_sertifikat`),
  KEY `registrasi_id` (`registrasi_id`),
  KEY `idx_nama_pemilik` (`nama_pemilik`),
  KEY `idx_nama_kelas` (`nama_kelas`)
) ENGINE=InnoDB AUTO_INCREMENT=2517 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

DROP TABLE IF EXISTS `tag`;
CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_tag` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nama_tag` (`nama_tag`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','admin','staff') NOT NULL DEFAULT 'staff',
  `nama_lengkap` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_sertifikat_detail`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `view_sertifikat_detail`;
CREATE TABLE IF NOT EXISTS `view_sertifikat_detail` (
`nomor_sertifikat` varchar(50)
,`tanggal_terbit` date
,`status` enum('aktif','dibatalkan')
,`nama_kelas` varchar(100)
,`nama_pemilik` varchar(100)
,`kota_kelas` varchar(100)
,`nama` varchar(100)
,`kode_kelas` varchar(20)
);

-- --------------------------------------------------------

--
-- Table structure for table `voucher`
--

DROP TABLE IF EXISTS `voucher`;
CREATE TABLE IF NOT EXISTS `voucher` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_voucher` varchar(50) NOT NULL,
  `kelas_id` int(10) UNSIGNED NOT NULL,
  `diskon_persen` decimal(5,2) NOT NULL,
  `tanggal_berlaku_mulai` date DEFAULT NULL,
  `tanggal_berlaku_sampai` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_voucher` (`kode_voucher`),
  KEY `kelas_id` (`kelas_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure for view `view_sertifikat_detail`
--
DROP TABLE IF EXISTS `view_sertifikat_detail`;

DROP VIEW IF EXISTS `view_sertifikat_detail`;
CREATE ALGORITHM=UNDEFINED DEFINER=`eqiw7359`@`localhost` SQL SECURITY DEFINER VIEW `view_sertifikat_detail`  AS SELECT `s`.`nomor_sertifikat` AS `nomor_sertifikat`, `s`.`tanggal_terbit` AS `tanggal_terbit`, `s`.`status` AS `status`, `s`.`nama_kelas` AS `nama_kelas`, `s`.`nama_pemilik` AS `nama_pemilik`, `s`.`kota_kelas` AS `kota_kelas`, `r`.`nama` AS `nama`, `r`.`kode_kelas` AS `kode_kelas` FROM (((`sertifikat` `s` join `registrasi` `r` on(`s`.`registrasi_id` = `r`.`id`)) left join `jadwal_kelas` `jk` on(`r`.`jadwal_id` = `jk`.`id`)) left join `kelas` `k` on(`jk`.`kelas_id` = `k`.`id`)) ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `berita`
--
ALTER TABLE `berita`
  ADD CONSTRAINT `berita_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_berita` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `berita_tag`
--
ALTER TABLE `berita_tag`
  ADD CONSTRAINT `berita_tag_ibfk_1` FOREIGN KEY (`berita_id`) REFERENCES `berita` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `berita_tag_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bonus_kelas`
--
ALTER TABLE `bonus_kelas`
  ADD CONSTRAINT `bonus_kelas_ibfk_1` FOREIGN KEY (`kode_kelas`) REFERENCES `kelas` (`kode_kelas`);

--
-- Constraints for table `folders`
--
ALTER TABLE `folders`
  ADD CONSTRAINT `folders_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `folders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jadwal_kelas`
--
ALTER TABLE `jadwal_kelas`
  ADD CONSTRAINT `jadwal_kelas_ibfk_1` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`);

--
-- Constraints for table `registrasi`
--
ALTER TABLE `registrasi`
  ADD CONSTRAINT `registrasi_ibfk_1` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal_kelas` (`id`),
  ADD CONSTRAINT `registrasi_ibfk_2` FOREIGN KEY (`kode_voucher`) REFERENCES `voucher` (`kode_voucher`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `registrasi_jasa`
--
ALTER TABLE `registrasi_jasa`
  ADD CONSTRAINT `registrasi_jasa_ibfk_1` FOREIGN KEY (`jasa_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `registrasi_jasa_ibfk_2` FOREIGN KEY (`kode_voucher`) REFERENCES `voucher` (`kode_voucher`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `sertifikat`
--
ALTER TABLE `sertifikat`
  ADD CONSTRAINT `sertifikat_ibfk_1` FOREIGN KEY (`registrasi_id`) REFERENCES `registrasi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `voucher`
--
ALTER TABLE `voucher`
  ADD CONSTRAINT `voucher_ibfk_1` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
