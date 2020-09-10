-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2013 at 02:37 PM
-- Server version: 5.6.11
-- PHP Version: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dblabb`
--
CREATE DATABASE IF NOT EXISTS `dblabb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `dblabb`;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(10) unsigned NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `type`, `description`) VALUES
(1, 1, 'Apple'),
(2, 2, 'Lord of the Rings');

-- --------------------------------------------------------

--
-- Table structure for table `itemtypes`
--

CREATE TABLE IF NOT EXISTS `itemtypes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `itemtypes`
--

INSERT INTO `itemtypes` (`id`, `name`) VALUES
(2, 'Book'),
(1, 'Fruit');

-- --------------------------------------------------------

--
-- Table structure for table `loan`
--

CREATE TABLE IF NOT EXISTS `loan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item` int(10) unsigned NOT NULL,
  `user` int(10) unsigned NOT NULL,
  `loandate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `returndate` date NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `item` (`item`,`user`,`active`),
  KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` char(60) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `userdata`
--

CREATE TABLE IF NOT EXISTS `userdata` (
  `userid` int(10) unsigned NOT NULL,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL,
  `birthdate` date NOT NULL,
  `address` varchar(64) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `fk_items_itemtypes_id` FOREIGN KEY (`type`) REFERENCES `itemtypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `loan`
--
ALTER TABLE `loan`
  ADD CONSTRAINT `fk_loan_user_id` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_loan_items_id` FOREIGN KEY (`item`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `userdata`
--
ALTER TABLE `userdata`
  ADD CONSTRAINT `fk_userdata_user_id` FOREIGN KEY (`userid`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
