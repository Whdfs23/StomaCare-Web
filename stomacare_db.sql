-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2026 at 03:14 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stomacare_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `food_diary`
--

CREATE TABLE `food_diary` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_makan` enum('Pagi','Siang','Malam','Camilan') NOT NULL,
  `makanan` text NOT NULL,
  `minuman` text DEFAULT NULL,
  `porsi` varchar(50) DEFAULT NULL,
  `gejala` text DEFAULT NULL,
  `nyeri` tinyint(4) NOT NULL DEFAULT 0,
  `kondisi` varchar(100) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `food_diary`
--

INSERT INTO `food_diary` (`id`, `user_id`, `tanggal`, `waktu_makan`, `makanan`, `minuman`, `porsi`, `gejala`, `nyeri`, `kondisi`, `catatan`, `created_at`) VALUES
(1, 1, '2026-04-07', 'Siang', 'Seblak Pedas Level 5', 'Es Teh', 'Normal', '[\"Mual\", \"Nyeri Ulu Hati\"]', 7, 'Buruk', 'Makan seblak, perut langsung perih banget', '2026-04-07 13:34:59'),
(2, 1, '2026-04-07', 'Pagi', 'Bubur Ayam', 'Air Putih', 'Normal', '[\"Tidak Ada\"]', 0, 'Baik', 'Aman sentosa buat sarapan', '2026-04-07 13:34:59'),
(3, 1, '2026-04-06', 'Malam', 'Mie Ayam Ekstra Saus', 'Es Jeruk', 'Banyak', '[\"Heartburn\", \"Kembung\"]', 5, 'Kurang Baik', 'Agak begah dan panas di dada setelah makan mie ayamnya', '2026-04-07 13:34:59'),
(4, 1, '2026-04-05', 'Camilan', 'Kopi Susu & Gorengan', 'Kopi Es', 'Sedikit', '[\"Sendawa\", \"Mual\"]', 4, 'Cukup', 'Ngopi sore sambil ngoding bikin asam lambung naik dikit', '2026-04-07 13:34:59'),
(5, 1, '2026-04-04', 'Siang', 'Nasi Sayur Bayam', 'Air Putih', 'Normal', '[\"Tidak Ada\"]', 0, 'Baik', 'Makan sehat, perut super nyaman', '2026-04-07 13:34:59'),
(6, 1, '2026-04-03', 'Pagi', 'Roti Gandum', 'Teh Hangat', 'Sedikit', '[\"Sendawa\"]', 1, 'Baik', 'Sarapan simpel', '2026-04-07 13:34:59'),
(7, 1, '2026-04-03', 'Malam', 'Nasi Goreng Kambing', 'Es Teh Manis', 'Normal', '[\"Kembung\"]', 3, 'Cukup', 'Bumbunya agak terlalu kuat', '2026-04-07 13:34:59'),
(8, 1, '2026-04-02', 'Malam', 'Ayam Geprek Sambal Korek', 'Air Es', 'Normal', '[\"Nyeri Ulu Hati\", \"Diare\", \"Mual\"]', 8, 'Buruk', 'Nyesel makan pedes malem-malem', '2026-04-07 13:34:59'),
(9, 1, '2026-04-01', 'Siang', 'Soto Ayam', 'Air Putih', 'Normal', '[\"Tidak Ada\"]', 0, 'Baik', 'Seger dan aman di lambung', '2026-04-07 13:34:59'),
(10, 1, '2026-03-31', 'Camilan', 'Keripik Balado', 'Minuman Bersoda', 'Banyak', '[\"Kembung\", \"Sendawa\", \"Mual\"]', 6, 'Kurang Baik', 'Ngemil sambil nugas bikin perut kembung parah', '2026-04-07 13:34:59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `reset_token`, `reset_expires`) VALUES
(1, 'zulfa', 'zulfanashihin@gmail.com', '$2y$10$CKo8xLB.uU7WTAnOr3p1NOLEVzFs8c5XZjb.Ex3H1aRcVSu0GpcjC', '2026-04-07 12:43:23', NULL, NULL),
(2, 'zulfa1', 'zulfa@mail.com', '$2y$10$08eaXrSv303qL5AMb.pi1u.25USSAPAB74OA5lT8IYloFaeGyTI8u', '2026-04-07 12:55:54', NULL, NULL),
(3, 'zulfa2', 'zulfa.nashihin@students.untidar.ac.id', '$2y$10$jOMKVJdTTEdq.TzrZbiPLOeu7pGY7/CsGm5I5qoPSLr5b5.3ecQnW', '2026-04-07 23:54:12', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `food_diary`
--
ALTER TABLE `food_diary`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `food_diary`
--
ALTER TABLE `food_diary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `food_diary`
--
ALTER TABLE `food_diary`
  ADD CONSTRAINT `food_diary_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
