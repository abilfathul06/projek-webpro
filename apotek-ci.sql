-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2022 at 02:34 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `apotek-ci`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nama`, `username`, `password`) VALUES
(1, 'Timur Yulis', 'admin', '$2y$10$9702eQ5HAGt2R9iCOH0pOOU08rcR.91w2Qjaalqj87kEjbkWFxHl6');

-- --------------------------------------------------------

--
-- Table structure for table `detail_pembelian`
--

CREATE TABLE `detail_pembelian` (
  `id` int(11) NOT NULL,
  `pembelian_id` int(11) NOT NULL,
  `kode_obat` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detail_pembelian`
--

INSERT INTO `detail_pembelian` (`id`, `pembelian_id`, `kode_obat`, `jumlah`) VALUES
(24, 7, 'BTDN', 15),
(25, 7, 'ENTR01', 10);

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id` int(11) NOT NULL,
  `transaksi_id` int(11) NOT NULL,
  `kode_obat` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id`, `transaksi_id`, `kode_obat`, `jumlah`) VALUES
(20, 20, 'BTDN', 3),
(21, 20, 'ENTR01', 5),
(22, 21, 'BTDN', 3),
(23, 21, 'DCGN', 2);

--
-- Triggers `detail_transaksi`
--
DELIMITER $$
CREATE TRIGGER `kurangi_stok_obat` BEFORE INSERT ON `detail_transaksi` FOR EACH ROW BEGIN
	DECLARE stok_sisa INT;
    SELECT stok INTO stok_sisa FROM obat WHERE kode = NEW.kode_obat;
    IF stok_sisa < NEW.jumlah THEN
    	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stok tidak mencukupi';
    END IF;
	UPDATE obat SET stok = stok - NEW.jumlah WHERE kode = NEW.kode_obat;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `obat`
--

CREATE TABLE `obat` (
  `kode` varchar(100) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `nama_obat` varchar(255) NOT NULL,
  `produsen` varchar(100) NOT NULL,
  `stok` int(11) UNSIGNED NOT NULL,
  `foto` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL,
  `harga_beli` int(11) DEFAULT NULL,
  `flag_del` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `obat`
--

INSERT INTO `obat` (`kode`, `supplier_id`, `nama_obat`, `produsen`, `stok`, `foto`, `harga`, `harga_beli`, `flag_del`) VALUES
('ACTF', 3, 'Actifed', 'Procter & Gamble', 25, '2022-08-21-07-32-27_630225db46528.jpg', 66000, 30000, 0),
('BTDN', 1, 'Bethadine', 'Ovo', 14, '2019-07-04-11-23-20_5d1e27f85dc0b.jpg', 11000, 5000, 0),
('DCGN', 1, 'Decolgen', 'Medifarma Laboratories', 18, '2022-08-21-06-48-01_63021b71b1d31.jpg', 5000, 2000, 0),
('ENTR01', 3, 'Entro Stop', 'Elevina', 10, '2019-07-04-11-22-33_5d1e27c92eb50.jpg', 5000, 2500, 0),
('INST', 1, 'Insto', 'Limo', 12, '2019-07-04-11-27-04_5d1e28d83a4df.jpg', 15000, 7500, 0),
('MXGR', 1, 'Mixagrip', 'Kalbe Farma', 20, '2022-08-21-06-39-11_6302195fefea0.jpg', 5000, 2000, 0),
('OXCN', 1, 'Oxycan', 'Chandra Medika', 20, '2022-08-21-06-30-03_6302173be2073.jpg', 50000, 25000, 0),
('PNDL', 4, 'Panadol', 'GlaxoSmithKline', 20, '2022-08-21-07-28-47_630224ff00a67.jpg', 10000, 5000, 0),
('SLDX', 1, 'Siladex', 'Limo', 20, '2019-07-04-11-26-06_5d1e289e36909.jpg', 15000, 7500, 0),
('VI44', 3, 'Vicks Formula 44', 'Procter & Gamble', 30, '2022-08-21-07-31-18_63022596d6f0e.png', 20000, 10000, 0),
('VIVP', 4, 'Vicks Vaporub', 'Procter & Gamble', 20, '2022-08-21-07-30-12_63022554e8ad8.png', 30000, 15000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `id` int(11) NOT NULL,
  `tgl` datetime DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`id`, `tgl`, `admin_id`) VALUES
(7, '2022-08-21 04:23:55', 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `kota` varchar(100) NOT NULL,
  `telp` varchar(13) NOT NULL,
  `flag_del` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `nama`, `alamat`, `kota`, `telp`, `flag_del`) VALUES
(1, 'Linmas Farma', 'Simpang Lima', 'Semarang', '021123123', 0),
(2, 'Ajib Jaya', 'Kuningan', 'Jakarta', '021123123', 0),
(3, 'Tenmas Supplier', 'Ciparay', 'Bandung', '0213123123', 0),
(4, 'Roket Supplier', 'Bojong Gedhe', 'Bogor', '02139394839', 0);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `tgl` datetime NOT NULL,
  `nama_pembeli` varchar(255) NOT NULL,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `tgl`, `nama_pembeli`, `admin_id`) VALUES
(20, '2022-08-21 04:25:23', 'Anjas', 1),
(21, '2022-08-21 06:54:35', 'Robert', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pembelian_id` (`pembelian_id`),
  ADD KEY `kode_obat` (`kode_obat`);

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_id` (`transaksi_id`),
  ADD KEY `kode_obat` (`kode_obat`);

--
-- Indexes for table `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`kode`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pembelian_ibfk_1` (`admin_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  ADD CONSTRAINT `detail_pembelian_ibfk_1` FOREIGN KEY (`pembelian_id`) REFERENCES `pembelian` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_pembelian_ibfk_2` FOREIGN KEY (`kode_obat`) REFERENCES `obat` (`kode`),
  ADD CONSTRAINT `detail_pembelian_ibfk_3` FOREIGN KEY (`kode_obat`) REFERENCES `obat` (`kode`) ON DELETE NO ACTION;

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`kode_obat`) REFERENCES `obat` (`kode`),
  ADD CONSTRAINT `detail_transaksi_ibfk_3` FOREIGN KEY (`kode_obat`) REFERENCES `obat` (`kode`) ON DELETE NO ACTION;

--
-- Constraints for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD CONSTRAINT `pembelian_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
