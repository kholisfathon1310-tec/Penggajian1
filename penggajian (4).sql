-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2025 at 05:28 PM
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
-- Table structure for table `absen`
--

CREATE TABLE `absen` (
  `id_absen` char(10) NOT NULL,
  `NIP` char(10) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `tgl_masuk` date NOT NULL,
  `jabatan` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(41, 'K0004', 'Yani', 'karyawan', 'uploads/6784b2a9601cb-Screenshot 2025-01-13 123435.png', '2025-01-13 13:28:57', '2025-01-13 13:29:06', '00:00:09', 'Hadir', 'tidak', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_dashboard`
--

CREATE TABLE `admin_dashboard` (
  `id_dashboard` int(11) NOT NULL,
  `nama_admin` int(11) NOT NULL,
  `no_telpon` int(15) NOT NULL,
  `posisi` varchar(30) NOT NULL,
  `departemen` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_data_karyawan`
--

CREATE TABLE `admin_data_karyawan` (
  `id_data_karyawan` varchar(10) NOT NULL,
  `nama_karyawan` varchar(50) NOT NULL,
  `NIP` varchar(10) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` varchar(50) NOT NULL,
  `no_telpon` int(15) NOT NULL,
  `posisi` varchar(30) NOT NULL,
  `departemen` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
('CT005', 'K0004', 'zizi', '', '2025-01-09', '2025-01-31', 'burung melahirkan', '2025-01-13', '', '', '');

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
  `status` varchar(20) NOT NULL,
  `total` int(15) NOT NULL,
  `opsi_lembur` enum('Ya','Tidak','','') NOT NULL,
  `gaji_lembur_per_jam` int(15) DEFAULT NULL,
  `lembur` enum('Iya','Tidak','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_penggajian`
--

INSERT INTO `admin_penggajian` (`NIP`, `nama_user`, `hak`, `tanggal_gaji`, `base_salary`, `pot_BPJS`, `transportasi`, `pot_absen`, `salary`, `periode`, `status`, `total`, `opsi_lembur`, `gaji_lembur_per_jam`, `lembur`) VALUES
('K0001', 'Rai mika', 'admin', '2025-01-13', 2000000, 20000, 2000000, '250000', 3730050, '2025-04-01 to 2025-06-30', '', 0, 'Ya', NULL, 'Iya'),
('K0004', 'Yani', 'karyawan', '2025-01-13', 2000000, 2, 1, '-1', 2000050, '', '', 0, 'Ya', NULL, 'Iya');

-- --------------------------------------------------------

--
-- Table structure for table `admin_schedule`
--

CREATE TABLE `admin_schedule` (
  `id_schedule` varchar(10) NOT NULL,
  `nama_karyawan` varchar(50) NOT NULL,
  `NIP` varchar(10) NOT NULL,
  `posisi` varchar(30) NOT NULL,
  `tanggal` date NOT NULL,
  `hari` varchar(15) NOT NULL,
  `shift` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gajikaryawan`
--

CREATE TABLE `gajikaryawan` (
  `NIP` varchar(10) NOT NULL,
  `Nama` varchar(50) NOT NULL,
  `Jabatan` varchar(20) NOT NULL,
  `Salary` float(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id_jadwal` varchar(10) NOT NULL,
  `nama_karyawan` varchar(50) NOT NULL,
  `NIP` varchar(10) NOT NULL,
  `posisi` varchar(30) NOT NULL,
  `hari` varchar(15) NOT NULL,
  `tanggal_kerja` date NOT NULL,
  `shift_kerja` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `NIP` char(10) NOT NULL,
  `Nama` varchar(50) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `alamat` varchar(50) NOT NULL,
  `jabatan` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `NIP` varchar(10) NOT NULL,
  `Nama` varchar(35) NOT NULL,
  `Tanggal_Lahir` date NOT NULL,
  `No.Telepon` int(15) NOT NULL,
  `alamat` varchar(50) NOT NULL,
  `jabatan` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id_schedule` int(11) NOT NULL,
  `nama` varchar(35) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `keterangan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
('U004', 'K0004', 'azizi', 'yani', '123', '2024-12-03', '086253480', 'Garut', 'karyawan'),
('U006', 'K0006', 'Yuka', 'yuka', '22', '2024-12-25', '0897653587', 'Cibeber', 'karyawan'),
('U007', 'K0007', 'Raizan S', '', '', '2024-12-31', '08625348091', 'CIMAHI', 'admin'),
('U008', 'K0008', 'Soraya', '', '', '2001-01-01', '08625332482', 'Sumedang', 'karyawan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absen`
--
ALTER TABLE `absen`
  ADD PRIMARY KEY (`id_absen`),
  ADD KEY `NIP` (`NIP`);

--
-- Indexes for table `admin_absen`
--
ALTER TABLE `admin_absen`
  ADD PRIMARY KEY (`id_absen`);

--
-- Indexes for table `admin_dashboard`
--
ALTER TABLE `admin_dashboard`
  ADD PRIMARY KEY (`id_dashboard`);

--
-- Indexes for table `admin_data_karyawan`
--
ALTER TABLE `admin_data_karyawan`
  ADD PRIMARY KEY (`id_data_karyawan`);

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
-- Indexes for table `admin_schedule`
--
ALTER TABLE `admin_schedule`
  ADD PRIMARY KEY (`id_schedule`);

--
-- Indexes for table `gajikaryawan`
--
ALTER TABLE `gajikaryawan`
  ADD PRIMARY KEY (`NIP`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id_jadwal`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`NIP`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`NIP`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id_schedule`);

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
  MODIFY `id_absen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absen`
--
ALTER TABLE `absen`
  ADD CONSTRAINT `absen_ibfk_1` FOREIGN KEY (`NIP`) REFERENCES `karyawan` (`NIP`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
