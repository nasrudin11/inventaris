-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 20, 2024 at 02:17 AM
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
  `gambar_barang` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `id_user`, `nama_barang`, `kategori`, `stok`, `lokasi`, `harga`, `gambar_barang`) VALUES
(51, 4, 'Mesin Cetak Offset', 'Mesin', 5, 'Gudang A', 0, ''),
(52, 4, 'Kertas HVS A4', 'Bahan Baku', 900, 'Gudang B', 0, ''),
(53, 4, 'Tinta Hitam', 'Bahan Baku', 200, 'Gudang C', 0, ''),
(54, 4, 'Tinta Warna', 'Bahan Baku', 247, 'Gudang C', 0, ''),
(55, 4, 'Komputer Desain Grafis', 'Elektronik', 10, 'Ruang IT', 0, ''),
(56, 4, 'Printer Laser', 'Elektronik', 8, 'Ruang IT', 0, ''),
(57, 4, 'Scanner', 'Elektronik', 6, 'Ruang IT', 0, ''),
(58, 4, 'Mesin Laminating', 'Mesin', 4, 'Gudang A', 0, ''),
(59, 4, 'Stapler Heavy Duty', 'Peralatan Kantor', 18, 'Ruang Operasional', 0, ''),
(60, 4, 'Lemari Arsip', 'Peralatan Kantor', 15, 'Ruang Arsip', 0, ''),
(61, 5, 'Mesin Cetak Offset', 'Mesin', 50, '', 150000000, '6673687c3b923_offset.jpg'),
(62, 5, 'Kertas HVS A4', 'Bahan Baku', 50000, '', 50000, '6673688cd0806_hvs.webp'),
(63, 5, 'Tinta Hitam', 'Bahan Baku', 300, '', 300000, '6673689b03566_tinta hitam.jpg'),
(64, 5, 'Tinta Warna', 'Bahan Baku', 500, '', 350000, '667368a770e48_tinta warna.jpg'),
(66, 5, 'Printer Laser', 'Elektronik', 80, '', 5000000, '66736f12bbaea_printer.png'),
(67, 5, 'Scanner', 'Elektronik', 50, '', 3000000, '667368bf2d061_scanner.webp'),
(68, 5, 'Mesin Laminating', 'Mesin', 40, '', 10000000, '667368d2c96b5_laminating.avif'),
(69, 5, 'Stapler Heavy Duty', 'Peralatan Kantor', 100, '', 150000, '667368e36937b_stapler.jpg');

--
-- Triggers `barang`
--
DELIMITER $$
CREATE TRIGGER `insert_status` AFTER INSERT ON `barang` FOR EACH ROW BEGIN
    INSERT INTO status_barang (id_barang, nama_status) VALUES (NEW.id_barang, 'Baik');
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `barang_keluar`
--

CREATE TABLE `barang_keluar` (
  `id_barang_keluar` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tgl_keluar` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang_keluar`
--

INSERT INTO `barang_keluar` (`id_barang_keluar`, `id_barang`, `id_user`, `jumlah`, `tgl_keluar`) VALUES
(2, 52, 4, 100, '2024-06-20 06:38:54'),
(3, 54, 4, 3, '2024-06-20 06:39:11');

--
-- Triggers `barang_keluar`
--
DELIMITER $$
CREATE TRIGGER `trg_barang_keluar_after_insert` AFTER INSERT ON `barang_keluar` FOR EACH ROW BEGIN
    UPDATE barang
    SET stok = stok - NEW.jumlah
    WHERE id_barang = NEW.id_barang;
END
$$
DELIMITER ;

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
-- Triggers `maintenance`
--
DELIMITER $$
CREATE TRIGGER `add_stock` AFTER UPDATE ON `maintenance` FOR EACH ROW BEGIN
    IF NEW.status_maintenance = 'dikembalikan' AND OLD.status_maintenance != 'dikembalikan' THEN
        UPDATE barang
        SET stok = stok + NEW.jumlah
        WHERE id_barang = NEW.id_barang;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `reduce_stock` AFTER INSERT ON `maintenance` FOR EACH ROW BEGIN
    UPDATE barang
    SET stok = stok - NEW.jumlah
    WHERE id_barang = NEW.id_barang;
END
$$
DELIMITER ;

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
(28, 4, 5, '2024-06-13 18:14:59', 550000, 'menunggu'),
(29, 4, 5, '2024-06-20 06:50:16', 8500000, 'menunggu');

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
(22, 29, 62, 100, 50000),
(23, 29, 64, 10, 350000);

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
(3, 4, 'Muhammad Yusuf ', '2024-05-29', '2024-06-01', 'dikembalikan'),
(5, 4, 'Ilham Firmansyah', '2024-06-02', '2024-06-05', 'dikembalikan'),
(6, 4, 'Ahmad Nasrudin Jamil', '2024-06-19', '2024-06-21', 'dikembalikan'),
(7, 4, 'Aziz', '2024-06-19', '2024-06-20', 'dipinjam'),
(8, 4, 'Aziz', '2024-06-19', '2024-06-20', 'dipinjam'),
(9, 4, 'Aziz', '2024-06-19', '2024-06-20', 'dipinjam'),
(10, 4, 'Aziz', '2024-06-19', '2024-06-20', 'dipinjam'),
(11, 4, 'Aziz', '2024-06-19', '2024-06-20', 'dipinjam'),
(12, 4, 'Aziz', '2024-06-19', '2024-06-20', 'dikembalikan'),
(13, 4, 'Aziz', '2024-06-19', '2024-06-20', 'dikembalikan'),
(14, 4, 'Aziz', '2024-06-19', '2024-06-20', 'dikembalikan'),
(15, 4, 'Muhammad Yusuf ', '2024-06-20', '2024-06-21', 'dipinjam');

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
(16, 15, 59, 2, '2024-06-20', 'dipinjam');

--
-- Triggers `peminjaman_dtl`
--
DELIMITER $$
CREATE TRIGGER `mengembalikan_stok_barang` AFTER UPDATE ON `peminjaman_dtl` FOR EACH ROW BEGIN
    IF NEW.status = 'dikembalikan' THEN
        -- Kembalikan stok barang
        UPDATE barang
        SET stok = stok + NEW.jumlah
        WHERE id_barang = NEW.id_barang;

        -- Hitung total barang dalam peminjaman
        SET @total_barang := (SELECT COUNT(*) FROM peminjaman_dtl WHERE id_peminjaman = NEW.id_peminjaman);

        -- Hitung total barang yang sudah dikembalikan
        SET @total_dikembalikan := (SELECT COUNT(*) FROM peminjaman_dtl WHERE id_peminjaman = NEW.id_peminjaman AND status = 'dikembalikan');

        -- Jika semua barang telah dikembalikan, update status peminjaman
        IF @total_barang = @total_dikembalikan THEN
            UPDATE peminjaman
            SET status = 'dikembalikan'
            WHERE id_peminjaman = NEW.id_peminjaman;
        END IF;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `mengurangi_stok_barang` AFTER INSERT ON `peminjaman_dtl` FOR EACH ROW BEGIN
    UPDATE barang
    SET stok = stok - NEW.jumlah
    WHERE id_barang = NEW.id_barang;
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
(3, 5, 'Everlasting Retail', '08353523323', 'Surabaya', 'Fig-2-Venn-Diagram-of-Text-Mining.png', '');

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
(131, 5, 'Edit the item', '2024-06-16 20:14:11'),
(132, 5, 'has been login', '2024-06-17 13:30:58'),
(133, 4, 'has been login', '2024-06-18 22:52:14'),
(134, 4, 'has been login', '2024-06-18 23:07:43'),
(135, 4, 'Adding a new item (Keyboard)', '2024-06-19 00:17:10'),
(136, 4, 'has been login', '2024-06-19 13:51:05'),
(137, 4, 'has been login', '2024-06-19 18:20:02'),
(138, 4, 'Adding a new loan in the name of (Ahmad Nasrudin Jamil)', '2024-06-19 18:21:01'),
(139, 4, 'Return the loans item', '2024-06-19 18:21:25'),
(140, 4, 'Return the loans item', '2024-06-19 18:30:27'),
(141, 4, 'Return the loans item', '2024-06-19 18:32:26'),
(142, 4, 'has been login', '2024-06-19 20:35:02'),
(143, 4, 'Adding a new maintenance for item with ID 36', '2024-06-19 20:51:01'),
(144, 4, 'Return the loans item', '2024-06-19 20:51:18'),
(145, 4, 'Adding a new maintenance for item with ID 29', '2024-06-19 21:26:47'),
(146, 4, 'Adding a new loan in the name of (Aziz)', '2024-06-19 21:27:04'),
(147, 4, 'Adding a new item (Kertas A4)', '2024-06-19 21:52:29'),
(148, 5, 'has been login', '2024-06-19 22:20:55'),
(149, 4, 'has been login', '2024-06-19 22:21:41'),
(150, 4, 'Deleting an item', '2024-06-19 22:42:25'),
(151, 4, 'Deleting an item', '2024-06-19 22:42:33'),
(152, 4, 'Deleting an item', '2024-06-19 22:42:54'),
(153, 4, 'Deleting an item', '2024-06-19 22:43:00'),
(154, 4, 'Deleting an item', '2024-06-19 22:43:04'),
(155, 4, 'Adding a new loan in the name of (Aziz)', '2024-06-19 22:47:35'),
(156, 4, 'Adding a new maintenance for item with ID 43', '2024-06-19 22:51:33'),
(157, 4, 'Return the maintenance item with ID ', '2024-06-19 22:55:50'),
(158, 4, 'Return the maintenance item with ID ', '2024-06-19 22:56:56'),
(159, 4, 'Return the maintenance item with ID ', '2024-06-19 22:57:01'),
(160, 4, 'Return the maintenance item with ID 43', '2024-06-19 23:05:44'),
(161, 4, 'Adding a new loan in the name of (Aziz)', '2024-06-19 23:26:50'),
(162, 4, 'Return the borrowed item with ID 43 and status Rusak', '2024-06-19 23:27:02'),
(163, 4, 'Adding a new loan in the name of (Aziz)', '2024-06-19 23:30:26'),
(164, 4, 'Return the borrowed item with ID 43 and status Rusak', '2024-06-19 23:33:19'),
(165, 4, 'Return the borrowed item with ID 43 and status Rusak', '2024-06-19 23:39:41'),
(166, 4, 'Return the borrowed item with ID 43 and status Rusak', '2024-06-19 23:45:12'),
(167, 4, 'Return the borrowed item with ID 43 and status Rusak', '2024-06-19 23:50:14'),
(168, 4, 'Adding a new maintenance for item with ID 43', '2024-06-19 23:58:35'),
(169, 4, 'Return the maintenance item with ID 43', '2024-06-19 23:58:42'),
(170, 4, 'Return the maintenance item with ID 43', '2024-06-20 00:00:52'),
(171, 4, 'Deleting an item', '2024-06-20 00:31:18'),
(172, 5, 'has been login', '2024-06-20 00:35:26'),
(173, 5, 'Deleting an item', '2024-06-20 00:37:47'),
(174, 5, 'Deleting an item', '2024-06-20 00:37:54'),
(175, 5, 'Deleting an item', '2024-06-20 00:37:58'),
(176, 5, 'Adding a new item (Tinta Merah)', '2024-06-20 01:08:16'),
(177, 4, 'has been login', '2024-06-20 01:29:40'),
(178, 4, 'has been login', '2024-06-20 05:17:08'),
(179, 4, 'Adding a new item (Kertas A4)', '2024-06-20 05:17:24'),
(180, 4, 'Deleting an item', '2024-06-20 05:17:32'),
(181, 4, 'Adding a new item (Lemari Arsip)', '2024-06-20 06:04:12'),
(182, 5, 'has been login', '2024-06-20 06:05:27'),
(183, 5, 'Deleting an item', '2024-06-20 06:15:41'),
(184, 5, 'Deleting an item', '2024-06-20 06:15:47'),
(185, 5, 'Deleting an item', '2024-06-20 06:15:50'),
(186, 5, 'Deleting an item', '2024-06-20 06:23:20'),
(187, 4, 'has been login', '2024-06-20 06:38:20'),
(188, 4, 'Adding a new item (Kertas HVS A4)', '2024-06-20 06:38:54'),
(189, 4, 'Adding a new item (Tinta Warna)', '2024-06-20 06:39:11'),
(190, 4, 'Adding a new loan in the name of (Muhammad Yusuf )', '2024-06-20 06:39:49'),
(191, 4, 'Menambahkan pesanan baru', '2024-06-20 06:50:17'),
(192, 5, 'has been login', '2024-06-20 06:51:28'),
(193, 5, 'Edit the item', '2024-06-20 06:51:46'),
(194, 4, 'has been login', '2024-06-20 06:53:14'),
(195, 4, 'Edit the item', '2024-06-20 06:58:19'),
(196, 4, 'Edit the item', '2024-06-20 06:58:30'),
(197, 4, 'Adding stock of item Tinta Warna (100) ', '2024-06-20 07:01:38');

-- --------------------------------------------------------

--
-- Table structure for table `status_barang`
--

CREATE TABLE `status_barang` (
  `id_status` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `nama_status` enum('Baik','Rusak','Maintenance','Dipinjam') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status_barang`
--

INSERT INTO `status_barang` (`id_status`, `id_barang`, `nama_status`) VALUES
(17, 51, 'Baik'),
(18, 52, 'Baik'),
(19, 53, 'Baik'),
(20, 54, 'Baik'),
(21, 55, 'Baik'),
(22, 56, 'Baik'),
(23, 57, 'Baik'),
(24, 58, 'Baik'),
(25, 59, 'Baik'),
(26, 60, 'Baik'),
(27, 61, 'Baik'),
(28, 62, 'Baik'),
(29, 63, 'Baik'),
(30, 64, 'Baik'),
(32, 66, 'Baik'),
(33, 67, 'Baik'),
(34, 68, 'Baik'),
(35, 69, 'Baik'),
(36, 59, 'Dipinjam');

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
-- Indexes for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD PRIMARY KEY (`id_barang_keluar`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD PRIMARY KEY (`id_maintenance`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `maintenance_ibfk_1` (`id_barang`);

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
  ADD KEY `pemesanan_dtl_ibfk_2` (`id_barang`);

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
  ADD KEY `peminjaman_dtl_ibfk_2` (`id_barang`);

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
-- Indexes for table `status_barang`
--
ALTER TABLE `status_barang`
  ADD PRIMARY KEY (`id_status`),
  ADD KEY `id_barang` (`id_barang`);

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
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  MODIFY `id_barang_keluar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `id_maintenance` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id_pemesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `pemesanan_dtl`
--
ALTER TABLE `pemesanan_dtl`
  MODIFY `id_pemesanan_dtl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `peminjaman_dtl`
--
ALTER TABLE `peminjaman_dtl`
  MODIFY `id_peminjaman_dtl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `profil`
--
ALTER TABLE `profil`
  MODIFY `id_profil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `riwayat`
--
ALTER TABLE `riwayat`
  MODIFY `id_riwayat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;

--
-- AUTO_INCREMENT for table `status_barang`
--
ALTER TABLE `status_barang`
  MODIFY `id_status` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

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
-- Constraints for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD CONSTRAINT `barang_keluar_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `barang_keluar_ibfk_3` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE;

--
-- Constraints for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD CONSTRAINT `maintenance_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE,
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
  ADD CONSTRAINT `pemesanan_dtl_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `peminjaman_dtl_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE;

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

--
-- Constraints for table `status_barang`
--
ALTER TABLE `status_barang`
  ADD CONSTRAINT `status_barang_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
