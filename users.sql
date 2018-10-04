-- phpMyAdmin SQL Dump
-- version 4.7.8
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 27, 2018 at 03:29 AM
-- Server version: 10.1.35-MariaDB-1~xenial
-- PHP Version: 7.1.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scpt`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `resetmd5` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('students','sonadmin','hospadmin','superadmin') NOT NULL DEFAULT 'students',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `resetmd5`, `password`, `email`, `role`, `created`, `firstname`, `lastname`) VALUES
(27, 'sajiv.francis', '\'\'', '', '$2y$12$a2Hc/mroonzwOZb.sZCf4eq6XWx.fe7bCgfbG.OC3YySXcJkv3412', 'sajiv.francis@student.fairfield.edu', 'students', '2018-07-24 05:17:08', 'Sajiv', 'Francis'),
(28, 'hospadmin', '\'\'', '', '$2y$12$gXZoyH64DNDWsBMdi5RUjujYw1wvS.4422i5lL.opMK8rweFeFkEi', 'hospadmin@scpt.gwiddle.co.uk', 'hospadmin', '2018-07-24 15:05:01', 'HOSP', 'ADMIN'),
(29, 'sonadmin', '\'\'', '', '$2y$12$L2dg9DK2DtXkXQ/8BAgtKOH1Pe6GRTUEP24AJsQ.X0ZLPrYcryfy2', 'sonadmin@scpt.gwiddle.co.uk', 'sonadmin', '2018-07-24 15:05:01', 'SON', 'ADMIN'),
(30, 'superadmin', '\'\'', '', '$2y$12$yD5IPUvyJBmzp7lX9qBwSeP9mW.ZDJ1TE.NidrDC7uQTDmu3FLaPC', 'superadmin@scpt.gwiddle.co.uk', 'superadmin', '2018-07-24 15:07:05', 'SUPER', 'ADMIN');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

-- user_id links to the users-table (in code)
  CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11),
  `title` varchar(255) NOT NULL,
  `desc` text,
  `start` DATETIME NULL DEFAULT NULL,
  `startColor` varchar(15) DEFAULT 'blue',
  `end` DATETIME NULL DEFAULT NULL,
  `endColor` varchar(15) DEFAULT 'red',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;


-- if the status of a reservation is confirmed, then the event is closed for additional reservations
CREATE TABLE `reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11),
  `user_id` int(11),
  `title` varchar(255) NOT NULL,
  `desc` text,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hosp_confirm_id` int(11) DEFAULT 0,
  `hosp_confirm_timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `sona_confirm_timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `sona_confirm_id` int(11) DEFAULT 0,
  `status` enum('pending','sonaconfirmed','hospconfirmed','confirmed') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
