-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Mar 2023 pada 14.07
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_kasir`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `detailpesanan`
--

CREATE TABLE `detailpesanan` (
  `idDetailPesanan` int(11) NOT NULL,
  `kodePesanan` char(30) NOT NULL,
  `jumlahPesanan` int(11) NOT NULL,
  `totalPesanan` int(11) NOT NULL,
  `tglPesanan` date NOT NULL,
  `pelanggan` varchar(100) DEFAULT NULL,
  `produkId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detailpesanan`
--

INSERT INTO `detailpesanan` (`idDetailPesanan`, `kodePesanan`, `jumlahPesanan`, `totalPesanan`, `tglPesanan`, `pelanggan`, `produkId`) VALUES
(48, 'F2303020001', 10, 280000, '2023-03-02', '-', 12),
(49, 'F2303020001', 20, 550000, '2023-03-02', '-', 13),
(50, 'F2303020002', 1, 27500, '2023-03-02', '-', 13),
(51, 'F2303020002', 1, 32800, '2023-03-02', '-', 15),
(52, 'F2303020003', 20, 70000, '2023-03-02', '-', 17),
(53, 'F2303020003', 1, 49900, '2023-03-02', '-', 14),
(54, 'F2303020004', 1, 3500, '2023-03-02', '-', 17),
(55, 'F2303020005', 100, 350000, '2023-03-02', '-', 17),
(56, 'F2303040001', 3, 102000, '2023-03-04', '-', 16),
(57, 'F2303040001', 1, 32800, '2023-03-04', '-', 15),
(58, 'F2303040002', 1, 25000, '2023-03-04', '-', 21),
(59, 'F2303040002', 2, 7000, '2023-03-04', '-', 18);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `idKategori` int(11) NOT NULL,
  `namaKategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`idKategori`, `namaKategori`) VALUES
(4, 'Buku Lama'),
(6, 'Majalah Dewasa'),
(7, 'Perlengkapan Bayi'),
(8, ' Sepatu Pria'),
(9, 'Deodorant'),
(10, 'Parfume'),
(11, 'Body Spray'),
(12, 'Rokok'),
(13, 'Air Mineral'),
(14, 'Mie');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `idPesanan` int(11) NOT NULL,
  `kodePesanan` char(30) NOT NULL,
  `jumlahPesanan` int(11) NOT NULL,
  `totalPesanan` int(11) NOT NULL,
  `tglPesanan` date NOT NULL,
  `pelanggan` varchar(100) DEFAULT NULL,
  `produkId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `idProduk` int(11) NOT NULL,
  `kodeProduk` char(20) NOT NULL,
  `namaProduk` varchar(100) NOT NULL,
  `stokProduk` int(11) NOT NULL,
  `hargaProduk` int(11) NOT NULL,
  `kategoriId` int(11) NOT NULL,
  `satuan` varchar(100) DEFAULT '-',
  `tgl_input` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`idProduk`, `kodeProduk`, `namaProduk`, `stokProduk`, `hargaProduk`, `kategoriId`, `satuan`, `tgl_input`) VALUES
(12, 'PD01030002', 'Posh Energi Red 100 ML', 200, 28000, 11, 'PCS', '2023-03-01'),
(13, 'PD01030003', 'Aqua 20 ML', 222, 27500, 13, 'DUS', '2023-03-01'),
(14, 'PD01030004', 'Aqua 100 ML', 108, 49900, 13, 'DUS', '2023-03-01'),
(15, 'PD01030005', 'Surya 16 BTG', 667, 32800, 12, 'DUS', '2023-03-01'),
(16, 'PD01030006', 'Nivea Men 40ML', 115, 34000, 9, '', '2023-03-01'),
(17, 'PD01030007', 'Mie Sedap Goreng per PCS', 88, 3500, 14, 'PCS', '2023-03-01'),
(18, 'PD01030008', 'Mie Sedap Soto', 1015, 3500, 14, 'PCS', '2023-03-01'),
(19, 'PD01030009', 'Indomie Goreng', 288, 45000, 14, 'DUS', '2023-03-01'),
(20, 'PD01030010', 'Note Book', 285, 31100, 4, 'PCK', '2023-03-01'),
(21, 'PD04030001', 'Sampoerna Splash 16 BTG', 89, 25000, 12, 'PCS', '2023-03-04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `idUser` int(11) NOT NULL,
  `kodeUser` char(20) NOT NULL,
  `namaUser` varchar(100) NOT NULL,
  `telpUser` varchar(20) DEFAULT NULL,
  `password` varchar(50) NOT NULL,
  `akses` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`idUser`, `kodeUser`, `namaUser`, `telpUser`, `password`, `akses`) VALUES
(1, 'KY02918', 'Syahrun Fathan Hidayah', '081342504930', '21232f297a57a5a743894a0e4a801fc3', 2);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `detailpesanan`
--
ALTER TABLE `detailpesanan`
  ADD PRIMARY KEY (`idDetailPesanan`),
  ADD KEY `produkId` (`produkId`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`idKategori`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`idPesanan`),
  ADD KEY `produkId` (`produkId`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`idProduk`),
  ADD KEY `kategoriId` (`kategoriId`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `detailpesanan`
--
ALTER TABLE `detailpesanan`
  MODIFY `idDetailPesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `idKategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `idPesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `idProduk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detailpesanan`
--
ALTER TABLE `detailpesanan`
  ADD CONSTRAINT `detailpesanan_ibfk_1` FOREIGN KEY (`produkId`) REFERENCES `produk` (`idProduk`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`produkId`) REFERENCES `produk` (`idProduk`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`kategoriId`) REFERENCES `kategori` (`idKategori`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
