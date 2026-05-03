-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 23, 2025 at 04:30 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `penggajian`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_absen`
--

CREATE TABLE `admin_absen` (
  `id_absen` int(11) NOT NULL,
  `NIP` varchar(20) NOT NULL,
  `nama_user` varchar(100) NOT NULL,
  `posisi` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `jam_masuk` datetime NOT NULL,
  `jam_keluar` datetime DEFAULT NULL,
  `durasi_kerja` time DEFAULT NULL,
  `status_kehadiran` varchar(50) DEFAULT NULL,
  `lembur` enum('iya','tidak','','') DEFAULT NULL,
  `foto_lembur` varchar(225) NOT NULL,
  `jam_lembur` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_absen`
--

INSERT INTO `admin_absen` (`id_absen`, `NIP`, `nama_user`, `posisi`, `image`, `jam_masuk`, `jam_keluar`, `durasi_kerja`, `status_kehadiran`, `lembur`, `foto_lembur`, `jam_lembur`) VALUES
(16, 'K0004', 'Yani', 'Karyawan', 'uploads/678285f54f6b8-WhatsApp Image 2025-01-11 at 09.06.10_d61792d7.jpg', '2025-01-11 21:53:41', '2025-01-11 22:17:42', '00:24:01', 'Lembur', NULL, 'uploads/6782902d1126e-WhatsApp Image 2025-01-11 at 09.02.43_5736c75e.jpg', '2025-01-11 22:37:17'),
(17, 'K0004', 'Yani', 'Karyawan', 'uploads/678286c5ea093-WhatsApp Image 2025-01-11 at 09.06.10_d61792d7.jpg', '2025-01-11 21:57:09', '2025-01-11 22:17:42', '00:20:33', 'Lembur', NULL, 'uploads/6782902d1126e-WhatsApp Image 2025-01-11 at 09.02.43_5736c75e.jpg', '2025-01-11 22:37:17'),
(18, 'K0004', 'Yani', 'Karyawan', 'uploads/6782901e160d0-WhatsApp Image 2025-01-11 at 09.11.47_9f8f4145.jpg', '2025-01-11 22:37:02', '2025-01-11 22:38:47', '00:01:45', 'Lembur', NULL, 'uploads/67829a6c96a9e-WhatsApp Image 2025-01-11 at 09.11.47_9f8f4145.jpg', '2025-01-11 23:21:00'),
(19, 'K0006', 'Yuka', 'Karyawan', 'uploads/678291c08a0a4-WhatsApp Image 2025-01-11 at 09.11.47_9f8f4145.jpg', '2025-01-11 22:44:00', '2025-01-11 22:48:24', '00:04:24', 'Lembur', NULL, 'uploads/678292df57933-WhatsApp Image 2025-01-11 at 09.06.10_d61792d7.jpg', '2025-01-11 22:48:47'),
(20, 'K0006', 'Yuka', 'Karyawan', 'uploads/678292d80eb9b-WhatsApp Image 2025-01-11 at 09.11.47_9f8f4145.jpg', '2025-01-11 22:48:40', '2025-01-11 22:50:20', '00:01:40', 'Lembur', NULL, 'uploads/678293c355448-WhatsApp Image 2025-01-11 at 09.06.10_d61792d7.jpg', '2025-01-11 22:52:35'),
(21, 'K0006', 'Yuka', 'Karyawan', 'uploads/678293ba14d5c-WhatsApp Image 2025-01-11 at 09.02.43_5736c75e.jpg', '2025-01-11 22:52:26', '2025-01-11 22:58:16', '00:05:50', 'Lembur', NULL, 'uploads/678298caf267b-Hero Form.png', '2025-01-11 23:14:02'),
(22, 'K0006', 'Yuka', 'Karyawan', 'uploads/678296c4d610a-WhatsApp Image 2025-01-11 at 09.06.10_d61792d7.jpg', '2025-01-11 23:05:24', '2025-01-11 23:11:26', '00:06:02', 'Lembur', NULL, 'uploads/678298caf267b-Hero Form.png', '2025-01-11 23:14:02'),
(23, 'K0001', 'Rai mika', 'Karyawan', 'uploads/678298995fe70-WhatsApp Image 2025-01-11 at 09.02.43_5736c75e.jpg', '2025-01-11 23:13:13', NULL, NULL, 'Hadir', NULL, '', NULL),
(24, 'K0006', 'Yuka', 'Karyawan', 'uploads/678298bb36a49-WhatsApp Image 2025-01-11 at 09.02.43_5736c75e.jpg', '2025-01-11 23:13:47', '2025-01-11 23:18:01', '00:04:14', 'Hadir', NULL, '', NULL),
(25, 'K0004', 'Yani', 'Karyawan', 'uploads/67829a5fe4dbd-WhatsApp Image 2025-01-11 at 09.11.47_9f8f4145.jpg', '2025-01-11 23:20:47', '2025-01-11 23:21:21', '00:00:34', 'Hadir', NULL, '', NULL),
(26, 'K0004', 'Yani', 'Karyawan', 'uploads/67829a87bb060-WhatsApp Image 2025-01-11 at 09.06.10_d61792d7.jpg', '2025-01-11 23:21:27', '2025-01-11 23:22:02', '00:00:35', 'Hadir', NULL, 'uploads/67829a95e39cb-WhatsApp Image 2025-01-11 at 09.06.10_d61792d7.jpg', '2025-01-11 23:21:41'),
(27, 'K0004', 'Yani', 'Karyawan', 'uploads/67829c0e7d947-Hero Form.png', '2025-01-11 23:27:58', '2025-01-11 23:28:26', '00:00:28', 'Hadir', NULL, 'uploads/67829c1f58472-WhatsApp Image 2025-01-11 at 09.02.43_5736c75e.jpg', '2025-01-11 23:28:15'),
(28, 'K0004', 'Yani', 'Karyawan', 'uploads/67829d48e37d8-WhatsApp Image 2025-01-11 at 09.06.10_d61792d7.jpg', '2025-01-11 23:33:12', '2025-01-11 23:37:19', '00:04:07', 'Hadir', 'tidak', 'uploads/67829d572975c-WhatsApp Image 2025-01-11 at 09.02.43_5736c75e.jpg', '2025-01-11 23:33:27'),
(29, 'K0004', 'Yani', 'Karyawan', 'uploads/67829e525ac17-WhatsApp Image 2025-01-11 at 09.06.10_d61792d7.jpg', '2025-01-11 23:37:38', '2025-01-11 23:37:54', '00:00:16', 'Hadir', NULL, '', NULL),
(30, 'K0004', 'Yani', 'Karyawan', 'uploads/67829e7e4953e-WhatsApp Image 2025-01-11 at 09.11.47_9f8f4145.jpg', '2025-01-11 23:38:22', '2025-01-11 23:38:47', '00:00:25', 'Hadir', NULL, 'uploads/67829e8fb51ff-WhatsApp Image 2025-01-11 at 09.11.47_9f8f4145.jpg', '2025-01-11 23:38:39'),
(31, 'K0004', 'Yani', 'Karyawan', 'uploads/67829f6ace420-classdiagram-erd.drawio.png', '2025-01-11 23:42:18', '2025-01-11 23:49:37', NULL, 'Tidak Hadir', NULL, '', NULL),
(32, 'K0004', 'Yani', 'Karyawan', 'uploads/6782a133c5bb4-WhatsApp Image 2025-01-11 at 09.06.10_d61792d7.jpg', '2025-01-11 23:49:55', '2025-01-11 23:50:21', NULL, 'Lembur', 'tidak', 'uploads/6782a144b1569-WhatsApp Image 2025-01-11 at 09.02.43_5736c75e.jpg', '2025-01-11 23:50:12'),
(33, 'K0004', 'Yani', 'Karyawan', 'uploads/6782a24dcb05b-WhatsApp Image 2025-01-11 at 09.11.47_9f8f4145.jpg', '2025-01-11 23:54:37', '2025-01-11 23:54:57', '00:00:20', 'Hadir', NULL, 'uploads/6782a259f3be7-WhatsApp Image 2025-01-11 at 09.06.10_d61792d7.jpg', '2025-01-11 23:54:50'),
(34, 'K0006', 'Yuka', 'Karyawan', '', '2025-01-12 00:03:19', '2025-01-12 00:03:31', '00:00:12', 'Tidak Hadir', 'iya', '', '2025-01-12 00:03:22'),
(35, 'K0006', 'Yuka', 'Karyawan', '', '2025-01-12 00:04:33', '2025-01-12 00:06:25', '00:01:52', 'Tidak Hadir', 'iya', '', '2025-01-12 00:04:42'),
(36, 'K0006', 'Yuka', 'Karyawan', 'uploads/6782a5fee469f-WhatsApp Image 2025-01-11 at 09.11.47_9f8f4145.jpg', '2025-01-12 00:10:22', '2025-01-12 00:12:44', '00:02:22', 'Tidak Hadir', 'iya', 'uploads/6782a60c86e57-WhatsApp Image 2025-01-11 at 09.02.43_5736c75e.jpg', '2025-01-12 00:10:36'),
(37, 'K0006', 'Yuka', 'Karyawan', 'uploads/6782a6fe2c113-WhatsApp Image 2025-01-11 at 09.02.43_5736c75e.jpg', '2025-01-12 00:14:38', '2025-01-12 00:14:44', '00:00:06', 'Hadir', 'iya', 'uploads/6782a7035007a-WhatsApp Image 2025-01-11 at 09.02.43_5736c75e.jpg', '2025-01-12 00:14:43'),
(38, 'K0004', 'Yani', 'Karyawan', 'uploads/6782a73ec3ac5-WhatsApp Image 2025-01-11 at 09.11.47_9f8f4145.jpg', '2025-01-12 00:15:42', '2025-01-12 00:15:54', '00:00:12', 'Hadir', 'iya', 'uploads/6782a743db57b-WhatsApp Image 2025-01-11 at 09.02.43_5736c75e.jpg', '2025-01-12 00:15:47'),
(39, 'K0004', 'Yani', 'Karyawan', 'uploads/6782a7ee4c076-WhatsApp Image 2025-01-11 at 09.06.10_d61792d7.jpg', '2025-01-12 00:18:38', '2025-01-12 00:18:43', '00:00:05', 'Hadir', 'tidak', '', NULL),
(40, 'K0004', 'Yani', 'karyawan', 'uploads/67832520eab54-WhatsApp Image 2025-01-11 at 09.11.47_9f8f4145.jpg', '2025-01-12 09:12:48', '2025-01-12 09:13:09', '00:00:21', 'Hadir', 'iya', 'uploads/67832533d289d-WhatsApp Image 2025-01-11 at 09.06.10_d61792d7.jpg', '2025-01-12 09:13:07'),
(41, 'K0004', 'Yani', 'karyawan', 'uploads/6784b2a9601cb-Screenshot 2025-01-13 123435.png', '2025-01-13 13:28:57', '2025-01-13 13:29:06', '00:00:09', 'Hadir', 'tidak', '', NULL),
(42, 'K0001', 'Rai mika', 'admin', 'uploads/6785af31c7d34-Screenshot 2024-04-16 132444.png', '2025-01-14 07:26:25', NULL, NULL, 'Tidak Hadir', 'tidak', '', NULL),
(43, 'K0004', 'azizi', 'karyawan', 'uploads/6785b9f233626-Screenshot 2025-01-14 075728.png', '2025-01-14 08:12:18', NULL, NULL, 'Tidak Hadir', 'tidak', '', NULL),
(44, 'K0011', 'Yoo si jin', 'karyawan', 'uploads/6785c1765c784-Screenshot 2024-04-16 132444.png', '2025-01-14 08:44:22', '2025-01-14 08:44:32', '00:00:10', 'Hadir', 'tidak', '', NULL),
(45, 'K0011', 'Yoo si jin', 'karyawan', 'uploads/6785c19585674-Screenshot 2024-04-16 132444.png', '2025-01-14 08:44:53', '2025-01-14 08:45:20', '00:00:27', 'Hadir', 'iya', 'uploads/6785c1a5d3b40-Screenshot 2024-04-16 132444.png', '2025-01-14 08:45:09');

-- --------------------------------------------------------

--
-- Table structure for table `admin_pengajuan_cuti`
--

CREATE TABLE `admin_pengajuan_cuti` (
  `id_cuti` varchar(10) NOT NULL,
  `NIP` varchar(10) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `Hak` varchar(20) NOT NULL,
  `tanggal_awal` date NOT NULL,
  `tanggal_akhir` date NOT NULL,
  `jenis_cuti` varchar(100) NOT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `konfirmasi_permohonan` enum('setujui','tolak','','') NOT NULL,
  `status` varchar(20) NOT NULL,
  `komentar` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_pengajuan_cuti`
--

INSERT INTO `admin_pengajuan_cuti` (`id_cuti`, `NIP`, `nama`, `Hak`, `tanggal_awal`, `tanggal_akhir`, `jenis_cuti`, `tanggal_pengajuan`, `konfirmasi_permohonan`, `status`, `komentar`) VALUES
('CT001', 'K0004', 'Yani', '', '2025-01-17', '2025-01-10', 'Liburan', '2025-01-10', '', 'Ditolak', ''),
('CT002', 'K0004', 'Yani', '', '2025-01-02', '2025-01-08', 'Liburan', '2025-01-10', '', 'Ditolak', ''),
('CT003', 'K0004', 'Yani', '', '2025-01-03', '2025-01-08', 'Liburan', '2025-01-10', '', 'Ditolak', ''),
('CT004', '0001', 'win', '', '2025-01-11', '2025-01-16', 'Liburan', '2025-01-10', '', 'Disetujui', ''),
('CT005', 'K0004', 'zizi', '', '2025-01-09', '2025-01-31', 'burung melahirkan', '2025-01-13', '', 'Disetujui', ''),
('CT006', 'K0009', 'Rania', '', '2025-01-01', '2025-01-04', 'Liburan', '2025-01-14', '', '', ''),
('CT007', 'K0011', 'Yosi', '', '2025-01-14', '2025-01-15', 'Sakit', '2025-01-14', '', 'Ditolak', '');

-- --------------------------------------------------------

--
-- Table structure for table `admin_penggajian`
--

CREATE TABLE `admin_penggajian` (
  `NIP` char(10) NOT NULL,
  `nama_user` varchar(50) NOT NULL,
  `hak` varchar(25) NOT NULL,
  `tanggal_gaji` date NOT NULL,
  `base_salary` float NOT NULL,
  `pot_BPJS` int(15) NOT NULL,
  `transportasi` int(15) NOT NULL,
  `pot_absen` varchar(10) NOT NULL,
  `salary` bigint(15) NOT NULL,
  `periode` varchar(40) NOT NULL,
  `opsi_lembur` enum('Ya','Tidak','','') NOT NULL,
  `lembur` enum('Iya','Tidak','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_penggajian`
--

INSERT INTO `admin_penggajian` (`NIP`, `nama_user`, `hak`, `tanggal_gaji`, `base_salary`, `pot_BPJS`, `transportasi`, `pot_absen`, `salary`, `periode`, `opsi_lembur`, `lembur`) VALUES
('K0001', 'Rai mika', 'admin', '2025-01-14', 2025, 20000, 20000, '2000', 2048000, '2000000', 'Ya', 'Iya'),
('K0004', 'azizi', 'karyawan', '2025-01-23', 300000, 2000, 2000, '222', 349778, '2025-01-01 to 2025-03-31', 'Ya', 'Iya'),
('K0010', 'Yoo si jin', 'karyawan', '2025-01-14', 2025, 20000, 20000, '150000', 1850000, '2000000', 'Ya', 'Tidak');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` varchar(6) NOT NULL,
  `NIP` char(10) NOT NULL,
  `nama_user` varchar(50) NOT NULL,
  `username` varchar(14) NOT NULL,
  `password` varchar(8) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `no_telp` varchar(15) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `hak` enum('admin','karyawan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `NIP`, `nama_user`, `username`, `password`, `tgl_lahir`, `no_telp`, `alamat`, `hak`) VALUES
('U002', 'K0001', 'Rai mika', 'izan', '22', '2024-12-10', '0893282472', 'Cimahi', 'admin'),
('U004', 'K0004', 'azizi', 'yani', '123', '2024-12-03', '086253480', 'Bandung', 'karyawan'),
('U006', 'K0006', 'Yuka', 'yuka', '22', '2024-12-25', '0897653587', 'Cibeber', 'karyawan'),
('U007', 'K0007', 'Raizan S', '', '', '2024-12-31', '08625348091', 'CIMAHI', 'admin'),
('U009', 'K0009', 'Raniaa', 'nia', '234', '2025-01-14', '086252348293', 'Cibeber', 'karyawan'),
('U010', 'K0010', 'Yoo si jin', 'Ryuujinnn', 'ganteng1', '2025-01-14', '0895346960283', 'Bandung', 'karyawan'),
('U011', 'K0011', 'Yoo si', 'Ryuujinnn', '12345', '2025-01-14', '089534696', 'Cimahi', 'karyawan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_absen`
--
ALTER TABLE `admin_absen`
  ADD PRIMARY KEY (`id_absen`);

--
-- Indexes for table `admin_pengajuan_cuti`
--
ALTER TABLE `admin_pengajuan_cuti`
  ADD PRIMARY KEY (`id_cuti`);

--
-- Indexes for table `admin_penggajian`
--
ALTER TABLE `admin_penggajian`
  ADD PRIMARY KEY (`NIP`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`NIP`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_absen`
--
ALTER TABLE `admin_absen`
  MODIFY `id_absen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
