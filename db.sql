-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 14, 2021 at 10:47 PM
-- Server version: 8.0.22
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chmeds`
--

-- --------------------------------------------------------

--
-- Table structure for table `clinics`
--

DROP TABLE IF EXISTS `clinics`;
CREATE TABLE IF NOT EXISTS `clinics` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf32 COLLATE utf32_bin NOT NULL,
  `owner` varchar(255) CHARACTER SET utf32 COLLATE utf32_bin NOT NULL,
  `ownerID` int NOT NULL,
  `email` varchar(255) CHARACTER SET utf32 COLLATE utf32_bin NOT NULL,
  `address` text CHARACTER SET utf32 COLLATE utf32_bin NOT NULL,
  `zip` int NOT NULL,
  `services` text CHARACTER SET utf32 COLLATE utf32_bin NOT NULL,
  `website` text CHARACTER SET utf32 COLLATE utf32_bin,
  `images` text COLLATE utf32_bin NOT NULL,
  `facebook` text CHARACTER SET utf32 COLLATE utf32_bin,
  `twitter` text CHARACTER SET utf32 COLLATE utf32_bin,
  `instagram` text CHARACTER SET utf32 COLLATE utf32_bin,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `score` float NOT NULL DEFAULT '0',
  `numberOfReviews` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `clinics`
--

INSERT INTO `clinics` (`ID`, `name`, `owner`, `ownerID`, `email`, `address`, `zip`, `services`, `website`, `images`, `facebook`, `twitter`, `instagram`, `approved`, `featured`, `score`, `numberOfReviews`) VALUES
(30, 'Med Bay', 'Jean-Luc Picard', 37, 'medbayent@starfleet.com', 'Everywhere', 1, 'anesthesiologist,dermatologist,nicu,novi jeje,nezz ni ja', 'http://site.com', 'a:3:{i:0;s:43:\"/media/pictures/2021-01-09-01-37-11_ds9.jpg\";i:1;s:47:\"/media/pictures/2021-01-09-01-37-11_slika_1.jpg\";i:2;s:47:\"/media/pictures/2021-01-09-01-37-11_slika_2.jpg\";}', 'http://facebook.com/clinic', '', '', 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `passwordreset`
--

DROP TABLE IF EXISTS `passwordreset`;
CREATE TABLE IF NOT EXISTS `passwordreset` (
  `hash` varchar(150) NOT NULL,
  `email` varchar(50) NOT NULL,
  `requestedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ID` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `review` text NOT NULL,
  `clinicID` int NOT NULL,
  `personID` int NOT NULL,
  `score` int NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) CHARACTER SET utf32 COLLATE utf32_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`ID`, `tag`) VALUES
(1, 'Dentist'),
(2, 'Anesthesiologist'),
(3, 'Cardiologist'),
(4, 'Dermatologist'),
(6, 'Anesthesia care'),
(7, 'Nicu'),
(8, 'Apnea'),
(9, 'Asthma'),
(10, 'Neurology'),
(11, 'Transplant'),
(12, 'Urology'),
(13, 'Psychologist'),
(14, 'New tag'),
(15, 'testt'),
(16, 'novi jeje'),
(17, 'nezz ni ja');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `profilePicture` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `hasClinic` tinyint(1) NOT NULL DEFAULT '0',
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `dateAdded` date NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `name`, `surname`, `email`, `profilePicture`, `password`, `verified`, `hasClinic`, `role`, `dateAdded`) VALUES
(13, 'Imran', 'Isak', 'imran1701d@gmail.com', '', '$2y$10$4K2Kini9aK2cEcpL8Gt1O.a85Oas/VH.K.Puh97gZFJbdfTzccU2.', 1, 0, 'admin', '2020-09-07'),
(35, 'Lt.', 'Worf', 'worf@starfleet.com', '/media/pictures/2020-11-11-22-25-59_wurf.jpg', '$2y$10$FgWFb81KiJB/XMmN93YbM.jb8TeQ6xhOT2Pov9MlMfnze.W1FKvEG', 1, 0, 'user', '2020-11-11'),
(37, 'Jean-Luc', 'Picard', 'picard@starfleet.com', '/media/pictures/2020-12-28-18-09-23_jean-luc.jpg', '$2y$10$rhyrz96BqyYEYtdGYKNRPOJf0ADKc28c32uDWZxDcbWuL8fvaAS8.', 1, 0, 'user', '2020-12-28'),
(38, 'Top', 'Paris', 'tomparis@starfleet.com', '/media/pictures/2020-12-28-18-45-01_Top_Paris.jpg', '$2y$10$lz7V2EliL7oNm0YuZR9g/urlQxwcMDSgEhXREmc37OW2oAV11wsgi', 1, 0, 'user', '2020-12-28'),
(39, 'Benjamin', 'Sisko', 'badass@starfleet.com', '/media/pictures/2020-12-28-18-47-21_benjamin_sisko.jpg', '$2y$10$XIdRCbtb5NJlWIEw766KZOfjUBaMtLmJG.bMYUnXP1RgMliyu6Pra', 1, 0, 'user', '2020-12-28');

-- --------------------------------------------------------

--
-- Table structure for table `verifications`
--

DROP TABLE IF EXISTS `verifications`;
CREATE TABLE IF NOT EXISTS `verifications` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `hash` varchar(1000) NOT NULL,
  `userEmail` varchar(150) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `verifications`
--

INSERT INTO `verifications` (`ID`, `hash`, `userEmail`) VALUES
(47, '72d6adee48a066abed00f3c896d1b5df', 'elbaridi@gmail.com');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
