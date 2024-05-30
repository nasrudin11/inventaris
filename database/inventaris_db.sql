-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2024 at 01:33 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventaris_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_barang` varchar(30) NOT NULL,
  `kategori` varchar(20) NOT NULL,
  `stok` int(11) NOT NULL,
  `lokasi` varchar(20) NOT NULL,
  `deskripsi` varchar(100) NOT NULL,
  `status_barang` enum('Baik','Rusak','Dipinjam','Maintenance') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `id_user`, `nama_barang`, `kategori`, `stok`, `lokasi`, `deskripsi`, `status_barang`) VALUES
(29, 4, 'Keyboard', 'Elektronik', 20, '', '', 'Baik'),
(30, 4, 'Meja', 'Mebel', 20, '', '', 'Baik'),
(31, 4, 'CPU', 'Elektronik', 22, '', '', 'Baik'),
(32, 5, 'Keyboard', 'Elektronik', 20, '', '', 'Baik'),
(33, 5, 'Meja', 'Mebel', 20, '', '', 'Baik'),
(34, 5, 'CPU', 'Elektronik', 22, '', '', 'Baik'),
(35, 4, 'Mouse', 'Elektronik', 25, '', '', 'Baik');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance`
--

CREATE TABLE `maintenance` (
  `id_maintenance` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tgl_maintenance` date NOT NULL DEFAULT current_timestamp(),
  `status_maintenance` enum('maintenance','dikembalikan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance`
--

INSERT INTO `maintenance` (`id_maintenance`, `id_user`, `id_barang`, `jumlah`, `tgl_maintenance`, `status_maintenance`) VALUES
(1, 4, 29, 5, '2024-05-29', 'dikembalikan');

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pemesanan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tgl_pemesanan` datetime NOT NULL,
  `total_harga` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan_dtl`
--

CREATE TABLE `pemesanan_dtl` (
  `id_pemesanan_dtl` int(11) NOT NULL,
  `id_pemesanan` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_peminjam` varchar(30) NOT NULL,
  `tgl_peminjaman` date NOT NULL,
  `tgl_pengembalian` date NOT NULL,
  `status` enum('dipinjam','dikembalikan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `id_user`, `nama_peminjam`, `tgl_peminjaman`, `tgl_pengembalian`, `status`) VALUES
(2, 4, 'Rahul Permana', '2024-05-26', '2024-05-30', 'dikembalikan'),
(3, 4, 'Muhammad Yusuf ', '2024-05-29', '2024-06-01', 'dipinjam');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman_dtl`
--

CREATE TABLE `peminjaman_dtl` (
  `id_peminjaman_dtl` int(11) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tgl_pengembalian_dtl` date NOT NULL,
  `status` enum('dipinjam','dikembalikan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman_dtl`
--

INSERT INTO `peminjaman_dtl` (`id_peminjaman_dtl`, `id_peminjaman`, `id_barang`, `jumlah`, `tgl_pengembalian_dtl`, `status`) VALUES
(2, 2, 30, 5, '0000-00-00', 'dikembalikan'),
(3, 3, 29, 5, '0000-00-00', 'dipinjam');

--
-- Triggers `peminjaman_dtl`
--
DELIMITER $$
CREATE TRIGGER `update_peminjaman_status` AFTER UPDATE ON `peminjaman_dtl` FOR EACH ROW BEGIN
    DECLARE total_barang INT;
    DECLARE total_dikembalikan INT;

    -- Hitung total barang dalam peminjaman
    SELECT COUNT(*) INTO total_barang
    FROM peminjaman_dtl
    WHERE id_peminjaman = NEW.id_peminjaman;

    -- Hitung total barang yang sudah dikembalikan
    SELECT COUNT(*) INTO total_dikembalikan
    FROM peminjaman_dtl
    WHERE id_peminjaman = NEW.id_peminjaman
    AND status = 'dikembalikan';

    -- Jika semua barang telah dikembalikan, update status peminjaman
    IF total_barang = total_dikembalikan THEN
        UPDATE peminjaman
        SET status = 'dikembalikan'
        WHERE id_peminjaman = NEW.id_peminjaman;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `profil`
--

CREATE TABLE `profil` (
  `id_profil` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `no_tlp` varchar(14) NOT NULL,
  `alamat` varchar(40) NOT NULL,
  `gambar_profil` varchar(255) NOT NULL,
  `deskripsi` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profil`
--

INSERT INTO `profil` (`id_profil`, `id_user`, `nama`, `no_tlp`, `alamat`, `gambar_profil`, `deskripsi`) VALUES
(2, 4, 'Nextwave Corporation', '08823552232', 'Surabaya', 's.webp', ''),
(3, 5, 'Everlasting Retail', '', '', 'Fig-2-Venn-Diagram-of-Text-Mining.png', '');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat`
--

CREATE TABLE `riwayat` (
  `id_riwayat` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `riwayat` varchar(100) NOT NULL,
  `tgl_riwayat` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riwayat`
--

INSERT INTO `riwayat` (`id_riwayat`, `id_user`, `riwayat`, `tgl_riwayat`) VALUES
(18, 4, 'has been login', '2024-04-24 20:23:07'),
(19, 4, 'has been login', '2024-04-24 21:21:47'),
(20, 4, 'Adding a new item (Keyboard)', '2024-04-24 21:21:56'),
(21, 4, 'Adding stock of item Keyboard (2) ', '2024-04-24 21:23:19'),
(22, 4, 'Adding stock of item Keyboard (2) ', '2024-04-24 21:24:56'),
(23, 4, 'Adding stock of item Keyboard (2) ', '2024-04-24 21:25:56'),
(24, 4, 'has been login', '2024-04-24 21:26:23'),
(25, 4, 'Adding stock of item Keyboard (2) ', '2024-04-24 21:26:31'),
(26, 4, 'Adding a new item (Meja)', '2024-04-24 21:29:46'),
(27, 4, 'Adding a new item (CPU)', '2024-04-24 21:31:08'),
(28, 4, 'Deleting an item', '2024-04-24 21:31:51'),
(29, 4, 'Adding a new item (CPU)', '2024-04-24 21:32:10'),
(30, 5, 'has been login', '2024-04-24 21:35:12'),
(31, 5, 'has been login', '2024-04-24 21:45:33'),
(32, 4, 'has been login', '2024-04-24 21:46:12'),
(33, 5, 'has been login', '2024-04-24 21:53:23'),
(34, 5, 'Adding a new item (Keyboard)', '2024-04-24 21:53:35'),
(35, 4, 'has been login', '2024-04-25 00:28:14'),
(36, 4, 'Deleting an item', '2024-04-25 01:15:07'),
(37, 4, 'Deleting an item', '2024-04-25 01:15:10'),
(38, 4, 'Deleting an item', '2024-04-25 01:15:13'),
(39, 4, 'Deleting an item', '2024-04-25 01:39:34'),
(40, 4, 'Deleting an item', '2024-04-25 01:39:39'),
(41, 4, 'Deleting an item', '2024-04-25 01:39:45'),
(42, 4, 'Deleting an item', '2024-04-25 01:47:00'),
(43, 4, 'Deleting an item', '2024-04-25 01:47:03'),
(44, 4, 'Deleting an item', '2024-04-25 01:47:05'),
(45, 4, 'Deleting an item', '2024-04-25 01:47:12'),
(46, 4, 'Deleting an item', '2024-04-25 01:47:17'),
(47, 4, 'Deleting an item', '2024-04-25 01:47:21'),
(48, 5, 'has been login', '2024-04-25 06:32:33'),
(49, 5, 'Deleting an item', '2024-04-25 06:33:56'),
(50, 4, 'has been login', '2024-05-23 16:12:47'),
(51, 4, 'Adding a new item (Mouse)', '2024-05-23 16:13:23'),
(52, 4, 'has been login', '2024-05-26 09:37:42'),
(53, 5, 'has been login', '2024-05-26 09:50:39'),
(54, 4, 'has been login', '2024-05-26 10:22:51'),
(55, 4, 'has been login', '2024-05-26 16:51:50'),
(56, 4, 'has been login', '2024-05-26 19:41:54'),
(57, 4, 'Adding a new item (Rahul Permana)', '2024-05-26 19:54:15'),
(58, 4, 'Adding a new item (Rahul Permana)', '2024-05-26 20:26:10'),
(59, 4, 'has been login', '2024-05-29 18:06:07'),
(60, 4, 'Adding a new loans in the name of (Muhammad Yusuf Ibrahim)', '2024-05-29 18:32:52'),
(61, 4, 'Adding a new loans in the name of ()', '2024-05-29 18:42:06'),
(62, 4, 'Adding a new maintenance ()', '2024-05-29 18:43:22'),
(63, 4, 'Edit the item', '2024-05-29 19:54:49'),
(64, 4, 'Edit the item', '2024-05-29 19:58:26'),
(65, 4, 'Edit the item', '2024-05-29 19:58:50'),
(66, 4, 'has been login', '2024-05-30 05:37:35'),
(67, 4, 'Edit the item', '2024-05-30 05:47:12'),
(68, 4, 'Edit the item', '2024-05-30 06:10:32');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('kantor','distributor') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `email`, `password`, `role`) VALUES
(4, 'nextwave@gmail.com', '$2y$10$f2xybQ8mxILlfZder/6pieY16nqwE7wn1JiQxavMx9DX4MFNTrsP6', 'kantor'),
(5, 'everlasting@gmail.com', '$2y$10$ge5JO9WtT/zv/.X3m6QwFO/bwX/hyQ/J9wuYutSVdOaql4Uv3Sjg6', 'distributor');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD PRIMARY KEY (`id_maintenance`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id_pemesanan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `pemesanan_dtl`
--
ALTER TABLE `pemesanan_dtl`
  ADD PRIMARY KEY (`id_pemesanan_dtl`),
  ADD KEY `id_pemesanan` (`id_pemesanan`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `peminjaman_dtl`
--
ALTER TABLE `peminjaman_dtl`
  ADD PRIMARY KEY (`id_peminjaman_dtl`),
  ADD KEY `id_peminjaman` (`id_peminjaman`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `profil`
--
ALTER TABLE `profil`
  ADD PRIMARY KEY (`id_profil`),
  ADD KEY `'id_user` (`id_user`);

--
-- Indexes for table `riwayat`
--
ALTER TABLE `riwayat`
  ADD PRIMARY KEY (`id_riwayat`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `id_maintenance` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id_pemesanan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemesanan_dtl`
--
ALTER TABLE `pemesanan_dtl`
  MODIFY `id_pemesanan_dtl` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `peminjaman_dtl`
--
ALTER TABLE `peminjaman_dtl`
  MODIFY `id_peminjaman_dtl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `profil`
--
ALTER TABLE `profil`
  MODIFY `id_profil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `riwayat`
--
ALTER TABLE `riwayat`
  MODIFY `id_riwayat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD CONSTRAINT `maintenance_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  ADD CONSTRAINT `maintenance_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `pemesanan_dtl`
--
ALTER TABLE `pemesanan_dtl`
  ADD CONSTRAINT `pemesanan_dtl_ibfk_1` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`),
  ADD CONSTRAINT `pemesanan_dtl_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`);

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `peminjaman_dtl`
--
ALTER TABLE `peminjaman_dtl`
  ADD CONSTRAINT `peminjaman_dtl_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`),
  ADD CONSTRAINT `peminjaman_dtl_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`);

--
-- Constraints for table `profil`
--
ALTER TABLE `profil`
  ADD CONSTRAINT `profil_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `riwayat`
--
ALTER TABLE `riwayat`
  ADD CONSTRAINT `riwayat_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
