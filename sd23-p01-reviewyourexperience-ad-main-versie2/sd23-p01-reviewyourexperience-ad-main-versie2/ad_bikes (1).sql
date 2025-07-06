-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2025 at 12:06 PM
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
-- Database: `ad_bikes`
--

-- --------------------------------------------------------

--
-- Table structure for table `fietsen`
--

CREATE TABLE `fietsen` (
  `id` int(11) NOT NULL,
  `categorie` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `prijs` decimal(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fietsen`
--

INSERT INTO `fietsen` (`id`, `categorie`, `img`, `prijs`) VALUES
(1, 'racefietsen', 'racefietsen-1.webp', 12499.00),
(2, 'racefietsen', 'racefietsen-2.webp', 14599.00),
(3, 'racefietsen', 'racefietsen-3.webp', 10499.00),
(4, 'mountainfietsen', 'mountainfietsen-1.webp', 1233.00),
(5, 'mountainfietsen', 'mountainfietsen-2.webp', 999.00),
(6, 'mountainfietsen', 'mountainfietsen-3.webp', 999.00),
(7, 'stadfietsen', 'stadfietsen-1.webp', 800.00),
(8, 'stadfietsen', 'stadfietsen-2.webp', 999.00),
(9, 'stadfietsen', 'stadfietsen-2.webp', 999.00);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `bike_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`id`, `bike_id`, `name`, `content`, `created_at`) VALUES
(8, 4, 'andre', 'lelijke fiets&#13;&#10;', '2025-01-14 11:12:37'),
(9, 4, '2', 'wd', '2025-01-14 11:13:05'),
(10, 4, '12', 'lelijk', '2025-01-14 11:13:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(2, 'tyrone', 'tyronetexas40@gmail.com', '$2y$10$PezqgjWHafOxrfugc1vWO.eTyYITSA5TpanqFtOaZMpGZxJzZy41m', '2025-01-21 12:17:15'),
(3, 'Gemairo070', 'gemairo070@gmail.com', '$2y$10$IQZc8eOfqraztlV/ToBcmuO9sE8856kkInlEs.0Xre2sbjJUvQRsu', '2025-01-22 10:16:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fietsen`
--
ALTER TABLE `fietsen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fietsen`
--
ALTER TABLE `fietsen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
