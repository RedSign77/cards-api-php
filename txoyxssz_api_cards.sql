-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 04, 2025 at 06:15 AM
-- Server version: 10.6.22-MariaDB-cll-lve
-- PHP Version: 8.3.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `txoyxssz_api_cards`
--

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `Id` bigint(20) NOT NULL,
  `GameId` bigint(20) NOT NULL,
  `TypeId` bigint(20) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Image` varchar(255) DEFAULT NULL,
  `Text` mediumtext DEFAULT NULL,
  `Data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`Data`)),
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `DeletedAt` timestamp NULL DEFAULT NULL,
  `ArchivedAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`Id`, `GameId`, `TypeId`, `Name`, `Image`, `Text`, `Data`, `CreatedAt`, `UpdatedAt`, `DeletedAt`, `ArchivedAt`) VALUES
(1, 1, 1, 'Earth', NULL, NULL, NULL, '2024-05-18 13:18:35', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cardtypes`
--

CREATE TABLE `cardtypes` (
  `Id` bigint(20) NOT NULL COMMENT 'CardType Id',
  `GameId` bigint(20) NOT NULL,
  `Name` varchar(200) NOT NULL,
  `TypeText` varchar(255) NOT NULL,
  `TypeDescription` mediumtext DEFAULT NULL,
  `Image` int(11) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `DeletedAt` timestamp NULL DEFAULT NULL,
  `ArchivedAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cardtypes`
--

INSERT INTO `cardtypes` (`Id`, `GameId`, `Name`, `TypeText`, `TypeDescription`, `Image`, `CreatedAt`, `UpdatedAt`, `DeletedAt`, `ArchivedAt`) VALUES
(1, 1, 'Civilization', 'Civilization Card', NULL, NULL, '2024-05-18 13:13:36', NULL, NULL, NULL),
(2, 1, 'Action', 'Action Card', NULL, NULL, '2024-05-18 13:13:58', NULL, NULL, NULL),
(3, 1, 'Hero', 'Hero Card', NULL, NULL, '2024-05-18 13:14:18', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `creators`
--

CREATE TABLE `creators` (
  `Id` bigint(20) NOT NULL COMMENT 'Creator Id',
  `Name` varchar(200) NOT NULL,
  `Email` varchar(200) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `DeletedAt` timestamp NULL DEFAULT NULL,
  `BlockedAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `creators`
--

INSERT INTO `creators` (`Id`, `Name`, `Email`, `Password`, `CreatedAt`, `UpdatedAt`, `DeletedAt`, `BlockedAt`) VALUES
(1, 'Zoltán Németh', 'signred@gmail.com', 'e1b9f7c2e7c7e2a06b6305a5fd440760', '2024-05-18 13:00:27', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `decks`
--

CREATE TABLE `decks` (
  `id` bigint(20) NOT NULL COMMENT 'Deck Id',
  `CreatorId` bigint(20) NOT NULL,
  `GameId` bigint(20) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` mediumtext DEFAULT NULL,
  `Data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`Data`)),
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `DeletedAt` timestamp NULL DEFAULT NULL,
  `ArchivedAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `decks`
--

INSERT INTO `decks` (`id`, `CreatorId`, `GameId`, `Name`, `Description`, `Data`, `CreatedAt`, `UpdatedAt`, `DeletedAt`, `ArchivedAt`) VALUES
(1, 1, 1, 'Default Playable Deck', NULL, NULL, '2024-05-19 11:16:44', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `Id` bigint(20) NOT NULL COMMENT 'Game Id',
  `CreatorId` bigint(20) NOT NULL,
  `Name` varchar(200) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `DeletedAt` timestamp NULL DEFAULT NULL,
  `ArchivedAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`Id`, `CreatorId`, `Name`, `CreatedAt`, `UpdatedAt`, `DeletedAt`, `ArchivedAt`) VALUES
(1, 1, 'Essentia: War of Worlds', '2024-05-18 13:03:37', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `CardGameIndex` (`GameId`),
  ADD KEY `CardTypeIndex` (`TypeId`);

--
-- Indexes for table `cardtypes`
--
ALTER TABLE `cardtypes`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `GameIndex` (`GameId`);

--
-- Indexes for table `creators`
--
ALTER TABLE `creators`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `decks`
--
ALTER TABLE `decks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `DeckCreatorIndex` (`CreatorId`),
  ADD KEY `DeckGameIndex` (`GameId`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `CreatorIndex` (`CreatorId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cardtypes`
--
ALTER TABLE `cardtypes`
  MODIFY `Id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'CardType Id', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `creators`
--
ALTER TABLE `creators`
  MODIFY `Id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Creator Id', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `decks`
--
ALTER TABLE `decks`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Deck Id', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `Id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Game Id', AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `cards_ibfk_1` FOREIGN KEY (`GameId`) REFERENCES `games` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cards_ibfk_2` FOREIGN KEY (`TypeId`) REFERENCES `cardtypes` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cardtypes`
--
ALTER TABLE `cardtypes`
  ADD CONSTRAINT `cardtypes_ibfk_1` FOREIGN KEY (`GameId`) REFERENCES `games` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `decks`
--
ALTER TABLE `decks`
  ADD CONSTRAINT `decks_ibfk_1` FOREIGN KEY (`GameId`) REFERENCES `games` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `decks_ibfk_2` FOREIGN KEY (`CreatorId`) REFERENCES `creators` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_ibfk_1` FOREIGN KEY (`CreatorId`) REFERENCES `creators` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
