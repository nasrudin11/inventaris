-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2024 at 04:42 PM
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
  `harga` decimal(10,0) NOT NULL,
  `deskripsi` varchar(100) NOT NULL,
  `status_barang` enum('Baik','Rusak','Dipinjam','Maintenance') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `id_user`, `nama_barang`, `kategori`, `stok`, `lokasi`, `harga`, `deskripsi`, `status_barang`) VALUES
(29, 4, 'Keyboard', 'Elektronik', 20, 'Ruang A', 0, '', 'Baik'),
(30, 4, 'Meja', 'Mebel', 20, '', 0, '', 'Baik'),
(31, 4, 'CPU', 'Elektronik', 22, '', 0, '', 'Baik'),
(32, 5, 'Keyboards', 'Elektronik', 20, '', 50000, '', 'Baik'),
(33, 5, 'Meja', 'Mebel', 20, '', 200000, '', 'Baik'),
(35, 4, 'Mouse', 'Elektronik', 25, '', 0, '', 'Baik'),
(36, 4, 'Monitor', 'Elektronik', 10, '', 0, '', 'Baik'),
(37, 5, 'Proyektor', 'Elektronik', 12, '', 200000, '', 'Baik'),
(38, 5, 'Monitor', 'Elektronik', 12, '', 3000000, '', 'Baik');

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
  `tgl_main_kembali` date NOT NULL DEFAULT current_timestamp(),
  `status_maintenance` enum('maintenance','dikembalikan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance`
--

INSERT INTO `maintenance` (`id_maintenance`, `id_user`, `id_barang`, `jumlah`, `tgl_maintenance`, `tgl_main_kembali`, `status_maintenance`) VALUES
(1, 4, 29, 5, '2024-05-29', '2024-06-02', 'dikembalikan'),
(2, 4, 29, 3, '2024-06-16', '2024-06-16', 'maintenance');

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pemesanan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_user2` int(11) NOT NULL,
  `tgl_pemesanan` datetime NOT NULL DEFAULT current_timestamp(),
  `total_harga` decimal(10,0) NOT NULL,
  `status` enum('menunggu','dikonfirmasi') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id_pemesanan`, `id_user`, `id_user2`, `tgl_pemesanan`, `total_harga`, `status`) VALUES
(27, 4, 5, '2024-06-11 23:50:08', 500000, 'dikonfirmasi'),
(28, 4, 5, '2024-06-13 18:14:59', 550000, 'menunggu');

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

--
-- Dumping data for table `pemesanan_dtl`
--

INSERT INTO `pemesanan_dtl` (`id_pemesanan_dtl`, `id_pemesanan`, `id_barang`, `jumlah`, `harga`) VALUES
(18, 27, 32, 2, 50000),
(19, 27, 33, 2, 200000),
(20, 28, 32, 3, 50000),
(21, 28, 33, 2, 200000);

--
-- Triggers `pemesanan_dtl`
--
DELIMITER $$
CREATE TRIGGER `hitung_total_harga` AFTER INSERT ON `pemesanan_dtl` FOR EACH ROW BEGIN
    DECLARE total_harga DECIMAL(10,2);
    
    -- Menghitung total harga untuk pemesanan ini
    SELECT SUM(jumlah * harga) INTO total_harga
    FROM pemesanan_dtl
    WHERE id_pemesanan = NEW.id_pemesanan;
    
    -- Memperbarui total harga pada tabel pemesanan
    UPDATE pemesanan
    SET total_harga = total_harga
    WHERE id_pemesanan = NEW.id_pemesanan;
END
$$
DELIMITER ;

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
(3, 4, 'Muhammad Yusuf ', '2024-05-29', '2024-06-01', 'dipinjam'),
(5, 4, 'Ilham Firmansyah', '2024-06-02', '2024-06-05', 'dikembalikan');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman_dtl`
--

CREATE TABLE `peminjaman_dtl` (
  `id_peminjaman_dtl` int(11) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tgl_pengembalian_dtl` date NOT NULL DEFAULT current_timestamp(),
  `status` enum('dipinjam','dikembalikan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman_dtl`
--

INSERT INTO `peminjaman_dtl` (`id_peminjaman_dtl`, `id_peminjaman`, `id_barang`, `jumlah`, `tgl_pengembalian_dtl`, `status`) VALUES
(2, 2, 30, 5, '0000-00-00', 'dikembalikan'),
(3, 3, 29, 5, '0000-00-00', 'dipinjam'),
(5, 5, 30, 2, '2024-06-02', 'dikembalikan'),
(6, 5, 31, 2, '2024-06-02', 'dikembalikan');

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
(3, 5, 'Everlasting Retail', '08353523323', '', 'Fig-2-Venn-Diagram-of-Text-Mining.png', '');

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
(68, 4, 'Edit the item', '2024-05-30 06:10:32'),
(69, 4, 'has been login', '2024-06-02 08:02:41'),
(70, 4, 'has been login', '2024-06-02 18:21:19'),
(71, 4, 'Adding a new loan in the name of (Ilham Firmansyah)', '2024-06-02 18:53:04'),
(72, 4, 'Edit the item', '2024-06-02 19:14:38'),
(73, 4, 'Edit the item', '2024-06-02 19:18:00'),
(74, 4, 'has been login', '2024-06-02 21:57:38'),
(75, 4, 'Return the loans item', '2024-06-02 22:06:28'),
(76, 4, 'Adding a new item (Monitor)', '2024-06-02 22:15:50'),
(77, 4, 'has been login', '2024-06-03 07:06:54'),
(78, 4, 'has been login', '2024-06-03 12:27:31'),
(79, 4, 'has been login', '2024-06-05 13:03:25'),
(80, 4, 'Adding a new order', '2024-06-05 15:02:55'),
(81, 4, 'Adding a new order', '2024-06-05 15:25:49'),
(82, 4, 'Adding a new order', '2024-06-05 15:26:08'),
(83, 4, 'Adding a new order', '2024-06-05 15:26:34'),
(84, 4, 'Adding a new order', '2024-06-05 15:27:27'),
(85, 4, 'Adding a new order', '2024-06-05 15:32:24'),
(86, 4, 'Adding a new order', '2024-06-05 15:34:41'),
(87, 4, 'Adding a new order', '2024-06-05 15:35:21'),
(88, 4, 'Adding a new order', '2024-06-05 15:37:37'),
(89, 4, 'Adding a new order', '2024-06-05 15:42:47'),
(90, 4, 'Adding a new order', '2024-06-05 15:45:31'),
(91, 4, 'Adding a new order', '2024-06-05 15:46:52'),
(92, 4, 'Menambahkan pesanan baru', '2024-06-05 17:07:28'),
(93, 4, 'Menambahkan pesanan baru', '2024-06-05 17:09:46'),
(94, 4, 'has been login', '2024-06-05 20:34:14'),
(95, 4, 'has been login', '2024-06-08 19:09:23'),
(96, 4, 'has been login', '2024-06-09 21:13:04'),
(97, 4, 'has been login', '2024-06-09 22:04:31'),
(98, 4, 'has been login', '2024-06-11 20:13:36'),
(99, 5, 'has been login', '2024-06-11 20:22:31'),
(100, 5, 'has been login', '2024-06-11 23:48:29'),
(101, 4, 'has been login', '2024-06-11 23:48:47'),
(102, 4, 'Menambahkan pesanan baru', '2024-06-11 23:50:08'),
(103, 5, 'has been login', '2024-06-11 23:54:35'),
(104, 4, 'has been login', '2024-06-12 21:27:09'),
(105, 5, 'has been login', '2024-06-12 21:45:45'),
(106, 5, 'Edit the item', '2024-06-12 22:03:15'),
(107, 5, 'Edit the item', '2024-06-12 22:08:03'),
(108, 4, 'has been login', '2024-06-12 22:09:30'),
(109, 4, 'has been login', '2024-06-13 18:14:17'),
(110, 4, 'Menambahkan pesanan baru', '2024-06-13 18:14:59'),
(111, 4, 'has been login', '2024-06-14 07:18:56'),
(112, 5, 'has been login', '2024-06-14 07:53:44'),
(113, 5, 'Adding a new item (Proyektor)', '2024-06-14 08:42:48'),
(114, 5, 'Deleting an item', '2024-06-14 08:50:06'),
(115, 4, 'has been login', '2024-06-14 10:04:15'),
(116, 5, 'has been login', '2024-06-14 10:04:45'),
(117, 5, 'Edit the item', '2024-06-14 10:08:46'),
(118, 5, 'Edit the item', '2024-06-14 10:08:56'),
(119, 5, 'Edit the item', '2024-06-14 10:10:36'),
(120, 5, 'Edit the item', '2024-06-14 10:11:07'),
(121, 5, 'Edit the item', '2024-06-14 10:12:21'),
(122, 4, 'has been login', '2024-06-14 10:14:47'),
(123, 4, 'Edit the item', '2024-06-14 10:17:08'),
(124, 4, 'has been login', '2024-06-16 06:07:04'),
(125, 4, 'has been login', '2024-06-16 06:48:23'),
(126, 4, 'has been login', '2024-06-16 20:04:36'),
(127, 4, 'Adding a new maintenance ()', '2024-06-16 20:04:57'),
(128, 5, 'has been login', '2024-06-16 20:08:26'),
(129, 5, 'Edit the item', '2024-06-16 20:08:47'),
(130, 5, 'Adding a new item (Monitor)', '2024-06-16 20:10:36'),
(131, 5, 'Edit the item', '2024-06-16 20:14:11');

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
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_user2` (`id_user2`);

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
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `id_maintenance` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id_pemesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `pemesanan_dtl`
--
ALTER TABLE `pemesanan_dtl`
  MODIFY `id_pemesanan_dtl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `peminjaman_dtl`
--
ALTER TABLE `peminjaman_dtl`
  MODIFY `id_peminjaman_dtl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `profil`
--
ALTER TABLE `profil`
  MODIFY `id_profil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `riwayat`
--
ALTER TABLE `riwayat`
  MODIFY `id_riwayat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

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
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`id_user2`) REFERENCES `user` (`id_user`);

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
