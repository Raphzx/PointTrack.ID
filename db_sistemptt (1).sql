-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 11, 2026 at 03:30 AM
-- Server version: 8.0.40
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sistemptt`
--

-- --------------------------------------------------------

--
-- Table structure for table `jenis_pelanggaran`
--

CREATE TABLE `jenis_pelanggaran` (
  `id` int NOT NULL,
  `nama_pelanggaran` varchar(100) DEFAULT NULL,
  `poin` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jenis_pelanggaran`
--

INSERT INTO `jenis_pelanggaran` (`id`, `nama_pelanggaran`, `poin`) VALUES
(1, 'Terlambat', 5),
(2, 'Berkelahi', 30);

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int NOT NULL,
  `nama_kelas` varchar(50) DEFAULT NULL,
  `tingkat` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `nama_kelas`, `tingkat`) VALUES
(1, 'Farmasi A', 'X'),
(2, 'Farmasi B', 'X'),
(3, 'Farmasi C', 'X'),
(4, 'Rekayasa Perangkat Lunak', 'X'),
(5, 'Desain Komunikasi Visual', 'X'),
(6, 'Kecantikan', 'X'),
(7, 'Farmasi A', 'XI'),
(8, 'Farmasi B', 'XI'),
(9, 'Farmasi C', 'XI'),
(10, 'Rekayasa Perangkat Lunak', 'XI'),
(11, 'Desain Komunikasi Visual', 'XI'),
(12, 'Kecantikan', 'XI'),
(13, 'Farmasi A', 'XII'),
(14, 'Farmasi B', 'XII'),
(15, 'Farmasi C', 'XII'),
(16, 'Rekayasa Perangkat Lunak', 'XII'),
(17, 'Desain Komunikasi Visual', 'XII'),
(18, 'Kecantikan', 'XII');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggaran`
--

CREATE TABLE `pelanggaran` (
  `id` int NOT NULL,
  `riwayat_kelas_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `pelanggaran_id` int DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pelanggaran`
--

INSERT INTO `pelanggaran` (`id`, `riwayat_kelas_id`, `user_id`, `pelanggaran_id`, `tanggal`, `keterangan`) VALUES
(2, 3, 4, 1, '2026-02-03', 'Input otomatis'),
(3, 3, 4, 1, '2026-02-04', 'Input otomatis'),
(5, 3, 4, 1, '2026-02-07', 'Input otomatis'),
(6, 13, 4, 1, '2026-02-07', 'Input otomatis'),
(8, 2, 4, 1, '2026-02-04', 'Input otomatis'),
(9, 13, 4, 2, '2026-02-25', 'Input otomatis'),
(12, 13, 4, 1, '2026-02-19', 'Input otomatis'),
(13, 13, 4, 1, '2026-04-18', 'Input otomatis'),
(14, 13, 4, 2, '2026-04-18', 'Input otomatis'),
(16, 15, 4, 2, '2026-04-22', 'Input otomatis'),
(17, 15, 4, 1, '2026-04-22', 'Input otomatis'),
(18, 13, 4, 1, '2026-04-22', 'Input otomatis'),
(19, 13, 4, 2, '2026-04-22', 'Input otomatis');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_kelas`
--

CREATE TABLE `riwayat_kelas` (
  `id` int NOT NULL,
  `siswa_id` int DEFAULT NULL,
  `kelas_id` int DEFAULT NULL,
  `tahun_pelajaran_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `riwayat_kelas`
--

INSERT INTO `riwayat_kelas` (`id`, `siswa_id`, `kelas_id`, `tahun_pelajaran_id`) VALUES
(2, 2, 2, 3),
(3, 3, 10, 3),
(4, 4, 16, 3),
(9, 1, 14, 9),
(11, 8, 9, 3),
(13, 8, 10, 5),
(14, 9, 10, 3),
(15, 10, 5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` int NOT NULL,
  `nis` varchar(20) DEFAULT NULL,
  `nama_siswa` varchar(100) DEFAULT NULL,
  `alamat` text,
  `tahun_masuk` year DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `nis`, `nama_siswa`, `alamat`, `tahun_masuk`) VALUES
(1, '2024001', 'Budi Santoso', 'Jl. Ahmad Yani No. 10', '2025'),
(2, '2024002', 'Siti Aminah', 'Jl. Hasan Basri No. 5', '2025'),
(3, '2023001', 'Aldi Maulana Fitri', 'Jl. Handil Bakti', '2025'),
(4, '2022001', 'Ramardo Gengster', 'Jl. Handil Bakti', '2023'),
(8, '123456', 'Aldi', 'sibal', '2025'),
(9, '122333', 'andi', 'jl.simpang anim', '2026'),
(10, '1234567', 'putri', 'jl apa aja', '2026');

-- --------------------------------------------------------

--
-- Table structure for table `tahun_pelajaran`
--

CREATE TABLE `tahun_pelajaran` (
  `id` int NOT NULL,
  `nama_tahun` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tahun_pelajaran`
--

INSERT INTO `tahun_pelajaran` (`id`, `nama_tahun`, `status`) VALUES
(3, '2025/2026', 'Aktif'),
(5, '2026/2027', 'Tidak Aktif'),
(9, '2027/2028', 'Tidak Aktif'),
(10, '2024/2025', 'Tidak Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `fullname`, `role`, `status`) VALUES
(4, 'admin123@gmail.com', '$2y$10$oV5IT4GhPNBZKr/V1v.M0.Wjxx2EMXcHr8QU07.JXO/T7AjyrYTGu', 'admin', 'admin', 'aktif'),
(9, 'admin@gmail.com', '$2y$10$/lGwDUvuCOcnKIJC4mYXS.SdV1ewOhd1qAZMGKPyCQkVdIpoGGMgi', 'admin', 'admin', 'aktif'),
(12, 'guru@gmail.com', '$2y$10$OJJw8X1WEcfXdfux.ADDmeuJxZIzHjSK9NbmwrMrpbFV1T0ge7e/K', 'Guru', 'guru', 'aktif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jenis_pelanggaran`
--
ALTER TABLE `jenis_pelanggaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pelanggaran`
--
ALTER TABLE `pelanggaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `riwayat_kelas_id` (`riwayat_kelas_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `pelanggaran_id` (`pelanggaran_id`);

--
-- Indexes for table `riwayat_kelas`
--
ALTER TABLE `riwayat_kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `tahun_pelajaran_id` (`tahun_pelajaran_id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tahun_pelajaran`
--
ALTER TABLE `tahun_pelajaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jenis_pelanggaran`
--
ALTER TABLE `jenis_pelanggaran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `pelanggaran`
--
ALTER TABLE `pelanggaran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `riwayat_kelas`
--
ALTER TABLE `riwayat_kelas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tahun_pelajaran`
--
ALTER TABLE `tahun_pelajaran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pelanggaran`
--
ALTER TABLE `pelanggaran`
  ADD CONSTRAINT `pelanggaran_ibfk_1` FOREIGN KEY (`riwayat_kelas_id`) REFERENCES `riwayat_kelas` (`id`),
  ADD CONSTRAINT `pelanggaran_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `pelanggaran_ibfk_3` FOREIGN KEY (`pelanggaran_id`) REFERENCES `jenis_pelanggaran` (`id`);

--
-- Constraints for table `riwayat_kelas`
--
ALTER TABLE `riwayat_kelas`
  ADD CONSTRAINT `riwayat_kelas_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`),
  ADD CONSTRAINT `riwayat_kelas_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`),
  ADD CONSTRAINT `riwayat_kelas_ibfk_3` FOREIGN KEY (`tahun_pelajaran_id`) REFERENCES `tahun_pelajaran` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
