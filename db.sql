-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 01, 2020 at 02:24 PM
-- Server version: 5.7.26
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf32_bin NOT NULL,
  `owner` varchar(255) COLLATE utf32_bin NOT NULL,
  `ownerID` int(11) NOT NULL,
  `address` text COLLATE utf32_bin NOT NULL,
  `zip` int(11) NOT NULL,
  `services` text COLLATE utf32_bin NOT NULL,
  `website` text COLLATE utf32_bin,
  `images` text COLLATE utf32_bin,
  `facebook` text COLLATE utf32_bin,
  `twitter` text COLLATE utf32_bin,
  `instagram` text COLLATE utf32_bin,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

-- --------------------------------------------------------

--
-- Table structure for table `passwordreset`
--

DROP TABLE IF EXISTS `passwordreset`;
CREATE TABLE IF NOT EXISTS `passwordreset` (
  `hash` varchar(150) NOT NULL,
  `email` varchar(50) NOT NULL,
  `requestedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) COLLATE utf32_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`ID`, `tag`) VALUES
(1, 'Dentist'),
(2, 'Anesthesiologist'),
(3, 'Cardiologist'),
(4, 'Dermatologist');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
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
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `name`, `surname`, `email`, `profilePicture`, `password`, `verified`, `hasClinic`, `role`, `dateAdded`) VALUES
(13, 'Imran', 'Isak', 'imran1701d@gmail.com', '', '$2y$10$4K2Kini9aK2cEcpL8Gt1O.a85Oas/VH.K.Puh97gZFJbdfTzccU2.', 1, 0, 'admin', '2020-09-07'),
(35, 'Lt.', 'Worf', 'worf@starfleet.com', '/media/pictures/2020-11-11-22-25-59_wurf.jpg', '$2y$10$FgWFb81KiJB/XMmN93YbM.jb8TeQ6xhOT2Pov9MlMfnze.W1FKvEG', 1, 0, 'user', '2020-11-11');

-- --------------------------------------------------------

--
-- Table structure for table `verifications`
--

DROP TABLE IF EXISTS `verifications`;
CREATE TABLE IF NOT EXISTS `verifications` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(1000) NOT NULL,
  `userEmail` varchar(150) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `verifications`
--

INSERT INTO `verifications` (`ID`, `hash`, `userEmail`) VALUES
(47, '72d6adee48a066abed00f3c896d1b5df', 'elbaridi@gmail.com');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
