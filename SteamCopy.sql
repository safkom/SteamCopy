-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 28, 2023 at 09:16 PM
-- Server version: 10.6.15-MariaDB-log
-- PHP Version: 8.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `safkoeu_steam`
--

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `id` int(11) NOT NULL,
  `requester_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `prosnja_sprejeta` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `igre`
--

CREATE TABLE `igre` (
  `id` int(11) NOT NULL,
  `ime` varchar(200) NOT NULL,
  `opis` text DEFAULT NULL,
  `cena` float NOT NULL,
  `uporabnik_id` int(11) DEFAULT NULL,
  `file_url` text NOT NULL,
  `zanr_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `komentarji`
--

CREATE TABLE `komentarji` (
  `id` int(11) NOT NULL,
  `text` varchar(500) NOT NULL,
  `profil_id` int(11) NOT NULL,
  `pisatelj_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mnenja`
--

CREATE TABLE `mnenja` (
  `id` int(11) NOT NULL,
  `ocena` int(11) NOT NULL,
  `text` text NOT NULL,
  `uporabnik_id` int(11) DEFAULT NULL,
  `igra_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nakupi`
--

CREATE TABLE `nakupi` (
  `id` int(11) NOT NULL,
  `datum` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `uporabnik_id` int(11) NOT NULL,
  `igra_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slike`
--

CREATE TABLE `slike` (
  `id` int(11) NOT NULL,
  `url` varchar(500) NOT NULL,
  `opis` varchar(20) DEFAULT NULL,
  `igra_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uporabniki`
--

CREATE TABLE `uporabniki` (
  `id` int(11) NOT NULL,
  `ime` varchar(200) NOT NULL,
  `priimek` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `mail` varchar(200) NOT NULL,
  `geslo` varchar(200) NOT NULL,
  `slika_id` int(11) NOT NULL DEFAULT 1,
  `admin` int(11) NOT NULL,
  `opis` text DEFAULT NULL,
  `banned` int(11) DEFAULT 0,
  `google_id` text DEFAULT NULL,
  `denar` float NOT NULL DEFAULT 100
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zanri`
--

CREATE TABLE `zanri` (
  `id` int(11) NOT NULL,
  `ime` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovenian_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IX_Relationship11` (`requester_id`),
  ADD KEY `IX_Relationship13` (`user_id`);

--
-- Indexes for table `igre`
--
ALTER TABLE `igre`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ime` (`ime`),
  ADD KEY `IX_Relationship3` (`uporabnik_id`),
  ADD KEY `IX_Relationship21` (`zanr_id`);

--
-- Indexes for table `komentarji`
--
ALTER TABLE `komentarji`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IX_Relationship8` (`profil_id`),
  ADD KEY `IX_Relationship9` (`pisatelj_id`);

--
-- Indexes for table `mnenja`
--
ALTER TABLE `mnenja`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IX_Relationship16` (`uporabnik_id`),
  ADD KEY `IX_Relationship18` (`igra_id`);

--
-- Indexes for table `nakupi`
--
ALTER TABLE `nakupi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IX_Relationship4` (`uporabnik_id`),
  ADD KEY `IX_Relationship7` (`igra_id`);

--
-- Indexes for table `slike`
--
ALTER TABLE `slike`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IX_Relationship10` (`igra_id`);

--
-- Indexes for table `uporabniki`
--
ALTER TABLE `uporabniki`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IX_Relationship1` (`slika_id`);

--
-- Indexes for table `zanri`
--
ALTER TABLE `zanri`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `igre`
--
ALTER TABLE `igre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `komentarji`
--
ALTER TABLE `komentarji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mnenja`
--
ALTER TABLE `mnenja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nakupi`
--
ALTER TABLE `nakupi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slike`
--
ALTER TABLE `slike`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uporabniki`
--
ALTER TABLE `uporabniki`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zanri`
--
ALTER TABLE `zanri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `Relationship11` FOREIGN KEY (`requester_id`) REFERENCES `uporabniki` (`id`),
  ADD CONSTRAINT `Relationship13` FOREIGN KEY (`user_id`) REFERENCES `uporabniki` (`id`);

--
-- Constraints for table `igre`
--
ALTER TABLE `igre`
  ADD CONSTRAINT `Relationship21` FOREIGN KEY (`zanr_id`) REFERENCES `zanri` (`id`),
  ADD CONSTRAINT `Relationship3` FOREIGN KEY (`uporabnik_id`) REFERENCES `uporabniki` (`id`);

--
-- Constraints for table `komentarji`
--
ALTER TABLE `komentarji`
  ADD CONSTRAINT `Relationship8` FOREIGN KEY (`profil_id`) REFERENCES `uporabniki` (`id`),
  ADD CONSTRAINT `Relationship9` FOREIGN KEY (`pisatelj_id`) REFERENCES `uporabniki` (`id`);

--
-- Constraints for table `mnenja`
--
ALTER TABLE `mnenja`
  ADD CONSTRAINT `Relationship16` FOREIGN KEY (`uporabnik_id`) REFERENCES `uporabniki` (`id`),
  ADD CONSTRAINT `Relationship18` FOREIGN KEY (`igra_id`) REFERENCES `igre` (`id`);

--
-- Constraints for table `nakupi`
--
ALTER TABLE `nakupi`
  ADD CONSTRAINT `Relationship4` FOREIGN KEY (`uporabnik_id`) REFERENCES `uporabniki` (`id`),
  ADD CONSTRAINT `Relationship7` FOREIGN KEY (`igra_id`) REFERENCES `igre` (`id`);

--
-- Constraints for table `slike`
--
ALTER TABLE `slike`
  ADD CONSTRAINT `Relationship10` FOREIGN KEY (`igra_id`) REFERENCES `igre` (`id`);

--
-- Constraints for table `uporabniki`
--
ALTER TABLE `uporabniki`
  ADD CONSTRAINT `Relationship1` FOREIGN KEY (`slika_id`) REFERENCES `slike` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
