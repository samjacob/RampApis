-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 04, 2014 at 12:48 PM
-- Server version: 5.6.14
-- PHP Version: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ramp`
--

-- --------------------------------------------------------

--
-- Table structure for table `PREFERENCES`
--

CREATE TABLE IF NOT EXISTS `PREFERENCES` (
  `PREF_ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER_ID` int(11) NOT NULL,
  `WIDGET_ID` int(3) NOT NULL,
  `ACT_IND` tinyint(1) NOT NULL,
  PRIMARY KEY (`PREF_ID`),
  KEY `USER_ID` (`USER_ID`),
  KEY `WIDGET_ID` (`WIDGET_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `PREFERENCES`
--

INSERT INTO `PREFERENCES` (`PREF_ID`, `USER_ID`, `WIDGET_ID`, `ACT_IND`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1),
(3, 1, 3, 1),
(4, 2, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `RAMP_USERS`
--

CREATE TABLE IF NOT EXISTS `RAMP_USERS` (
  `USER_ID` int(11) NOT NULL AUTO_INCREMENT,
  `EMAIL` varchar(100) NOT NULL,
  `PWD` varchar(25) NOT NULL,
  `FIRST_NAME` varchar(100) NOT NULL,
  `LAST_NAME` varchar(100) NOT NULL,
  `ADMIN_USER` tinyint(1) NOT NULL,
  `PREF_STATUS` tinyint(1) NOT NULL,
  `ACT_IND` tinyint(1) NOT NULL,
  PRIMARY KEY (`USER_ID`),
  KEY `USER_ID` (`USER_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `RAMP_USERS`
--

INSERT INTO `RAMP_USERS` (`USER_ID`, `EMAIL`, `PWD`, `FIRST_NAME`, `LAST_NAME`, `ADMIN_USER`, `PREF_STATUS`, `ACT_IND`) VALUES
(1, 'samjacob.vethanayagam@cognizant.com', 'jaaneman79', 'SamJacob', 'Vethanayagam', 0, 0, 1),
(2, 'VinodKumar.Radhakrishnan@cognizant.com', 'vinod', 'VinodKumar', 'Radhakrishnan', 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `WIDGET`
--

CREATE TABLE IF NOT EXISTS `WIDGET` (
  `WIDGET_ID` int(3) NOT NULL AUTO_INCREMENT,
  `WIDGET_NAME` varchar(100) NOT NULL,
  `WIDGET_TYPE_ID` int(3) NOT NULL,
  `DESCRIPTION` varchar(255) NOT NULL,
  `ACT_IND` tinyint(1) NOT NULL,
  PRIMARY KEY (`WIDGET_ID`),
  KEY `WIDGET_TYPE_ID` (`WIDGET_TYPE_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `WIDGET`
--

INSERT INTO `WIDGET` (`WIDGET_ID`, `WIDGET_NAME`, `WIDGET_TYPE_ID`, `DESCRIPTION`, `ACT_IND`) VALUES
(1, 'Chart 1', 1, 'Chart one', 1),
(2, 'Chart 2', 1, 'Chart two', 1),
(3, 'Chart 3', 3, 'Chart three', 1),
(4, 'Chart 4', 2, 'Chart four', 1);

-- --------------------------------------------------------

--
-- Table structure for table `WIDGET_MASTER`
--

CREATE TABLE IF NOT EXISTS `WIDGET_MASTER` (
  `WIDGET_TYPE_ID` int(3) NOT NULL AUTO_INCREMENT,
  `WIDGET_TYPE_NAME` varchar(100) NOT NULL,
  `DESCRIPTION` varchar(255) NOT NULL,
  `ACT_IND` tinyint(1) NOT NULL,
  PRIMARY KEY (`WIDGET_TYPE_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `WIDGET_MASTER`
--

INSERT INTO `WIDGET_MASTER` (`WIDGET_TYPE_ID`, `WIDGET_TYPE_NAME`, `DESCRIPTION`, `ACT_IND`) VALUES
(1, 'Velocity', 'Velocity', 1),
(2, 'Current Iteration Burn-Up', 'Current Iteration Burn-Up', 1),
(3, 'Story Type Breakdown', 'Story Type Breakdown', 1),
(4, 'Release Burn Down', 'Release Burn Down', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `PREFERENCES`
--
ALTER TABLE `PREFERENCES`
  ADD CONSTRAINT `preferences_ibfk_2` FOREIGN KEY (`WIDGET_ID`) REFERENCES `WIDGET` (`WIDGET_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `preferences_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `RAMP_USERS` (`USER_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `WIDGET`
--
ALTER TABLE `WIDGET`
  ADD CONSTRAINT `widget_ibfk_1` FOREIGN KEY (`WIDGET_TYPE_ID`) REFERENCES `WIDGET_MASTER` (`WIDGET_TYPE_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
