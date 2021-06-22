-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 14, 2021 at 12:20 PM
-- Server version: 8.0.21
-- PHP Version: 7.3.21

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
  `images` text CHARACTER SET utf32 COLLATE utf32_bin NOT NULL,
  `facebook` text CHARACTER SET utf32 COLLATE utf32_bin,
  `twitter` text CHARACTER SET utf32 COLLATE utf32_bin,
  `instagram` text CHARACTER SET utf32 COLLATE utf32_bin,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `rating` float NOT NULL DEFAULT '0',
  `numberOfReviews` int NOT NULL DEFAULT '0',
  `dateAdded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `clinics`
--

INSERT INTO `clinics` (`ID`, `name`, `owner`, `ownerID`, `email`, `address`, `zip`, `services`, `website`, `images`, `facebook`, `twitter`, `instagram`, `approved`, `featured`, `rating`, `numberOfReviews`, `dateAdded`) VALUES
(38, 'hrle', 'Hrle The Second The Glorious', 45, 'imran1701d@gmail.com', 'hrle', 72000, 'anesthesia care', 'http://site.com', 'a:1:{i:0;s:48:\"/media/pictures/2021-05-17-10-20-51_artival2.jpg\";}', '', '', '', 1, 0, 0, 0, '2021-05-17 10:20:51'),
(30, 'Med Bay', 'Jean-Luc Picard The gread', 37, 'medbayent@starfleet.com', 'Everywhere', 1, 'anesthesiologist,dermatologist,nicu,novi jeje,nezz ni ja', 'http://site.com', 'a:3:{i:0;s:43:\"/media/pictures/2021-01-09-01-37-11_ds9.jpg\";i:1;s:47:\"/media/pictures/2021-01-09-01-37-11_slika_1.jpg\";i:2;s:47:\"/media/pictures/2021-01-09-01-37-11_slika_2.jpg\";}', 'http://facebook.com/clinic', '', '', 1, 1, 3.6, 5, '2021-01-26 22:37:08'),
(32, 'Twin Mountains Clinic', 'Edward Harmon', 41, 'info@twmc.com', 'Ritter Avenue 1183', 48066, 'pain managment,neurology', 'http://site.com', 'a:4:{i:0;s:65:\"/media/pictures/2021-01-28-21-02-07_Twin_Mountains_Clinic_(1).jpg\";i:1;s:65:\"/media/pictures/2021-01-28-21-02-07_Twin_Mountains_Clinic_(2).jpg\";i:2;s:65:\"/media/pictures/2021-01-28-21-02-07_Twin_Mountains_Clinic_(3).jpg\";i:3;s:65:\"/media/pictures/2021-01-28-21-02-07_Twin_Mountains_Clinic_(4).jpg\";}', '', '', '', 1, 0, 2.5, 4, '2021-01-05 22:37:08'),
(34, 'Kids clinic', 'Pokemon Grendle', 43, 'info@kc.com', 'S Albany Ave', 60623, 'pediatric care', 'http://chicagokids.com', 'a:4:{i:0;s:56:\"/media/pictures/2021-01-28-21-57-35_Kids_clinic_(1).jfif\";i:1;s:55:\"/media/pictures/2021-01-28-21-57-35_Kids_clinic_(1).jpg\";i:2;s:55:\"/media/pictures/2021-01-28-21-57-35_Kids_clinic_(2).jpg\";i:3;s:55:\"/media/pictures/2021-01-28-21-57-35_Kids_clinic_(3).jpg\";}', '', '', '', 1, 0, 4.6, 5, '2021-01-01 22:37:08'),
(36, 'Glory Hole', 'Lt. Worf', 35, 'glory@hole.com', 'Holy Glory', 123, 'apnea,transplant,psychologist,anesthesiologist', 'http://site.com', 'a:1:{i:0;s:50:\"/media/pictures/2021-03-03-15-34-25_glory_hole.jpg\";}', '', '', '', 1, 0, 5, 1, '2021-03-03 15:34:25'),
(37, 'My awesome clinic', 'Lt. Worf', 35, 'place@home.com', 'a-sda 33Aa', 123, 'anesthesia care,dentist,dermatologist', 'http://site.com', 'a:1:{i:0;s:47:\"/media/pictures/2021-03-09-14-11-06_clinic1.jpg\";}', '', '', '', 1, 0, 0, 0, '2021-03-09 14:11:06');

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
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
CREATE TABLE IF NOT EXISTS `reports` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `reporterID` int DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  `propertyID` int NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `resolved` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`ID`, `reporterID`, `type`, `reason`, `propertyID`, `date`, `resolved`) VALUES
(27, 0, 'user', 'user', 35, '2021-03-03 16:23:24', 1),
(26, 0, 'review', 'nor logged in', 13, '2021-03-03 14:57:29', 0),
(25, 42, 'review', 'teet review', 13, '2021-03-03 14:55:32', 0),
(28, 13, 'clinic', 'too deep', 36, '2021-03-04 13:54:56', 0),
(29, 0, 'review', 'fabulous', 24, '2021-03-04 13:55:46', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `review` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `clinicID` int NOT NULL,
  `personID` int NOT NULL,
  `score` int NOT NULL,
  `dateAdded` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`ID`, `review`, `clinicID`, `personID`, `score`, `dateAdded`) VALUES
(4, 'Very, very far away', 30, 37, 4, '2021-02-02 15:54:26'),
(6, 'The staff was kinda rude, and waited forever - but got the job done!', 30, 41, 3, '2021-02-02 15:54:26'),
(8, 'No. Just, no', 30, 42, 1, '2021-02-02 15:54:26'),
(10, 'My friend works here\r\na GLORIOUS place!!!11!!', 30, 43, 5, '2021-02-02 15:54:26'),
(12, 'I am a hamster', 30, 45, 5, '2021-02-02 15:54:26'),
(13, 'Kids LOVE me', 34, 45, 5, '2021-02-02 15:54:26'),
(15, 'I loved it, the staff is great!', 32, 45, 5, '2021-02-02 15:54:26'),
(16, 'Took mah baby boy here. Place seems dirty and the food sucks if the kid stays here for a while, but other than that, it is great!', 34, 42, 4, '2021-01-27 15:54:26'),
(17, 'Eehhhh, I guess they are ok', 32, 42, 3, '2021-02-02 15:54:26'),
(18, 'They made my Klingon baby boy feel much better.\r\nGLORY TO YOU AND YOUR FAMILY!!!', 34, 43, 5, '2021-02-02 15:54:26'),
(19, 'no', 32, 43, 1, '2021-02-02 15:54:26'),
(20, 'I do not approve', 32, 37, 1, '2021-02-02 15:54:26'),
(21, 'work plz', 34, 39, 5, '2021-02-02 15:54:26'),
(23, 'I do not like kids. Still a great place tho ', 34, 37, 4, '2021-02-01 15:54:26'),
(24, 'Hole leads to G L O R Y!', 36, 43, 5, '2021-03-03 18:05:08');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) CHARACTER SET utf32 COLLATE utf32_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`ID`, `tag`) VALUES
(1, 'dentist'),
(2, 'anesthesiologist'),
(3, 'cardiologist'),
(4, 'dermatologist'),
(6, 'anesthesia care'),
(7, 'nicu'),
(8, 'apnea'),
(9, 'asthma'),
(10, 'neurology'),
(11, 'transplant'),
(12, 'urology'),
(13, 'psychologist'),
(18, 'orthopedists'),
(19, 'pain managment'),
(20, 'pediatric care'),
(21, 'nesto novo');

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
  `hasPremium` tinyint(1) NOT NULL DEFAULT '0',
  `dateAdded` date NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `name`, `surname`, `email`, `profilePicture`, `password`, `verified`, `hasClinic`, `role`, `hasPremium`, `dateAdded`) VALUES
(13, 'Imran', 'Isak', 'imran1701d@gmail.com', '/media/pictures/profilepicture.jpg', '$2y$10$sV2vMlFIFoZtAjuOrVVEwuFFEgar3lr4jrz1F6IdiCc9fHAIHzqVu', 1, 0, 'admin', 0, '2020-09-07'),
(35, 'Lt.', 'Worf', 'worf@starfleet.com', '/media/pictures/2020-11-11-22-25-59_wurf.jpg', '$2y$10$FgWFb81KiJB/XMmN93YbM.jb8TeQ6xhOT2Pov9MlMfnze.W1FKvEG', 1, 1, 'user', 0, '2020-11-11'),
(37, 'Jean-Luc', 'Picard The gread', 'picard@starfleet.com', '/media/pictures/2021-02-17-15-56-36_picard2.jpg', '$2y$10$rhyrz96BqyYEYtdGYKNRPOJf0ADKc28c32uDWZxDcbWuL8fvaAS8.', 1, 0, 'user', 0, '2020-12-28'),
(38, 'Top', 'Paris', 'tomparis@starfleet.com', '/media/pictures/2020-12-28-18-45-01_Top_Paris.jpg', '$2y$10$lz7V2EliL7oNm0YuZR9g/urlQxwcMDSgEhXREmc37OW2oAV11wsgi', 1, 0, 'user', 0, '2020-12-28'),
(39, 'Benjamin', 'Sisko', 'badass@starfleet.com', '/media/pictures/2020-12-28-18-47-21_benjamin_sisko.jpg', '$2y$10$XIdRCbtb5NJlWIEw766KZOfjUBaMtLmJG.bMYUnXP1RgMliyu6Pra', 1, 0, 'user', 0, '2020-12-28'),
(40, 'Safet', 'Beg', 'safet@gmail.com', '/media/pictures/2021-01-18-10-48-01_john.jpg', '$2y$10$1zGLCgI9mmGNrj3SrUBAM.Uvd/iYdYaQTtSkDA9bgibibE.8Hj0Pu', 1, 0, 'user', 0, '2021-01-18'),
(41, 'Edward', 'Harmon', 'EdwardSHarmon@jourrapide.com', '/media/pictures/2021-01-28-20-46-22_ce2a95e99faceaf7af19c273b10ebcc1.jpg', '$2y$10$WRMhfkk/rXqQvD9qtuBbSuK3H6QdqaSR82iuUUxwcFQh.GN6.QbuW', 1, 0, 'user', 0, '2021-01-28'),
(42, 'Tehana', 'Kuprešak', 'tehanakupresak@jourrapide.com', '/media/pictures/2021-01-28-21-31-50_sarah-parmenter.jpeg', '$2y$10$IqAa2cl2gyeAgYk/M5.3uOQyB1549kvLBBCHA.MsjoIX0gUK6jvnW', 1, 0, 'user', 0, '2021-01-28'),
(43, 'Pokemon', 'Grendle', 'telpehgrendle@teleworm.us', '/media/pictures/2021-02-17-15-53-59_2021-01-28-21-50-05_images.jpeg', '$2y$10$R5EFiLFGy7mTjvtJS.ftpOCxeNNfFCQgt9VYIJNYTUDQViOETjA96', 1, 0, 'user', 0, '2021-01-28'),
(45, 'Hrle', 'The Second The Glorious', 'hrle@thesecond.com', '/media/pictures/profilepicture.jpg', '$2y$10$u.JRkGQQQT0msGutBL1yT.NKCRvEdIS7iJd9nLKzXWJfQPy6cuQTG', 1, 1, 'user', 0, '2021-01-28'),
(46, 'Chad', 'Ivan', 'chad@ivan.com', '/media/pictures/2021-01-29-13-32-27_gigachad.jpg', '$2y$10$/LroI4dx38woq4kcShfSHuvkggKpxHR/TLEqlwk0kbpO20PV3a9UW', 1, 0, 'user', 0, '2021-01-29'),
(47, 'Velid', 'Tahirovic', 'vela@kuca.com', '/media/pictures/profilepicture.jpg', '$2y$10$dlr0hqLpIWmOaqw1V.ROSeJt8ECE88xdx6D8JeJwSq1HFRRwOJrL.', 1, 0, 'user', 0, '2021-02-03'),
(48, 'Picard', '2', 'picard2@starfleet.com', '/media/pictures/2021-02-16-16-09-44_picard2.jpg', '$2y$10$71B/ElcSREp.2yxG5fFLgumQjm2dkkn7PEUQiQASUJUbBA3mLNHK.', 0, 0, 'user', 0, '2021-02-16'),
(57, 'John', 'Doe', 'john@doeeeeeeee.com', '/media/pictures/2021-06-03-16-06-39_jd1.jfif', '$2y$10$c3sphT62mHU.VfOck0t5OeMt4rN1865Z7C8/sBUmh4U2L60WBpDOO', 0, 0, 'user', 0, '2021-06-03');

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
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `verifications`
--

INSERT INTO `verifications` (`ID`, `hash`, `userEmail`) VALUES
(47, '72d6adee48a066abed00f3c896d1b5df', 'elbaridi@gmail.com'),
(59, 'f61c5bae444a6f63f940f76c6cbcd33a', 'picard2@starfleet.com'),
(60, '8b6ec10650a4282cfac4521e82fe9083', 'john@doeeeeeeee.com'),
(61, '6ec9a0fca2ab5e8439bf15e61d206328', 'john@doeeeeeeee.com');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
