-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 21, 2018 at 06:07 PM
-- Server version: 5.5.59-0+deb8u1
-- PHP Version: 5.6.33-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `encounter`
--

-- --------------------------------------------------------

--
-- Table structure for table `participations`
--

CREATE TABLE IF NOT EXISTS `participations` (
`partID` int(11) NOT NULL,
  `EventID` int(11) NOT NULL,
  `MemberID` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE IF NOT EXISTS `requests` (
`requestID` int(11) NOT NULL,
  `owner` int(11) NOT NULL,
  `requester` int(11) NOT NULL,
  `eventID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `scheduled`
--

CREATE TABLE IF NOT EXISTS `scheduled` (
`id` int(11) NOT NULL,
  `Title` varchar(240) NOT NULL,
  `Description` varchar(400) NOT NULL,
  `Location` varchar(240) NOT NULL,
  `locDescription` varchar(240) DEFAULT NULL,
  `lat` varchar(240) NOT NULL,
  `lng` varchar(240) NOT NULL,
  `city` varchar(240) NOT NULL,
  `Tag` varchar(240) DEFAULT NULL,
  `Date` date NOT NULL,
  `Time` varchar(240) NOT NULL,
  `Max` int(11) NOT NULL,
  `particNum` int(30) NOT NULL DEFAULT '1',
  `owner` int(11) NOT NULL,
  `messages` longtext
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `UserName` varchar(240) NOT NULL,
  `Checked` int(10) NOT NULL DEFAULT '0',
  `ImageName` varchar(240) NOT NULL,
  `FirstName` varchar(240) NOT NULL,
  `LastName` varchar(240) NOT NULL,
  `gender` int(2) NOT NULL,
  `city` varchar(240) NOT NULL,
  `about` longtext NOT NULL,
  `Email` varchar(240) NOT NULL,
  `Password` varchar(240) NOT NULL,
  `ReqCount` int(11) NOT NULL DEFAULT '0',
  `EncCount` int(11) NOT NULL DEFAULT '0',
  `allowedReq` int(11) NOT NULL,
  `allowedCre` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `participations`
--
ALTER TABLE `participations`
 ADD PRIMARY KEY (`partID`), ADD UNIQUE KEY `partID` (`partID`), ADD KEY `particip` (`MemberID`), ADD KEY `event` (`EventID`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
 ADD PRIMARY KEY (`requestID`), ADD UNIQUE KEY `requestID` (`requestID`), ADD KEY `OwnerID` (`owner`), ADD KEY `eventID` (`eventID`), ADD KEY `requesterID` (`requester`);

--
-- Indexes for table `scheduled`
--
ALTER TABLE `scheduled`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `participations`
--
ALTER TABLE `participations`
MODIFY `partID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=80;
--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
MODIFY `requestID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `scheduled`
--
ALTER TABLE `scheduled`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=62;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `participations`
--
ALTER TABLE `participations`
ADD CONSTRAINT `event` FOREIGN KEY (`EventID`) REFERENCES `scheduled` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `particip` FOREIGN KEY (`MemberID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
ADD CONSTRAINT `OwnerID` FOREIGN KEY (`owner`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `eventID` FOREIGN KEY (`eventID`) REFERENCES `scheduled` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `requesterID` FOREIGN KEY (`requester`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `scheduled`
--
ALTER TABLE `scheduled`
KEY `ADD FOREIGN KEY` (`owner`) USING BTREE,
ADD CONSTRAINT `owner_key` FOREIGN KEY (`owner`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
