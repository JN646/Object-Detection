-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 23, 2019 at 06:28 PM
-- Server version: 5.7.23
-- PHP Version: 7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `objectTracker2`
--

-- --------------------------------------------------------

--
-- Table structure for table `counter`
--

CREATE TABLE `counter` (
  `count_id` int(11) NOT NULL,
  `count_deviceID` int(11) NOT NULL,
  `count_class` varchar(100) NOT NULL,
  `count_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `count_confidence` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `counter`
--

INSERT INTO `counter` (`count_id`, `count_deviceID`, `count_class`, `count_time`, `count_confidence`) VALUES
(1, 1, 'person', '2019-05-18 11:38:15', 0.997778),
(2, 1, 'sofa', '2019-05-18 11:38:15', 0.738003),
(3, 1, 'person', '2019-05-18 11:43:08', 0.998721),
(4, 1, 'sofa', '2019-05-18 11:43:08', 0.848795),
(5, 1, 'person', '2019-05-18 11:43:16', 0.998369),
(6, 1, 'sofa', '2019-05-18 11:43:16', 0.665362),
(7, 1, 'person', '2019-05-18 11:43:29', 0.854517),
(8, 1, 'person', '2019-05-18 11:43:30', 0.977491),
(9, 1, 'sofa', '2019-05-18 11:43:30', 0.593766),
(10, 1, 'person', '2019-05-18 11:43:31', 0.985371),
(11, 1, 'sofa', '2019-05-18 11:43:31', 0.540528),
(12, 1, 'person', '2019-05-18 11:43:32', 0.981357),
(13, 1, 'sofa', '2019-05-18 11:43:32', 0.574354),
(14, 1, 'person', '2019-05-18 11:43:33', 0.986386),
(15, 1, 'sofa', '2019-05-18 11:43:33', 0.796252),
(16, 1, 'person', '2019-05-18 11:43:34', 0.994121),
(17, 1, 'sofa', '2019-05-18 11:43:34', 0.631796),
(18, 1, 'person', '2019-05-18 11:43:35', 0.995409),
(19, 1, 'sofa', '2019-05-18 11:43:35', 0.59057),
(20, 1, 'person', '2019-05-18 11:43:36', 0.994393),
(21, 1, 'sofa', '2019-05-18 11:43:36', 0.688995),
(22, 1, 'person', '2019-05-18 11:43:37', 0.996875),
(23, 1, 'cup', '2019-05-18 11:43:37', 0.607144),
(24, 1, 'person', '2019-05-18 11:43:38', 0.99839),
(25, 1, 'person', '2019-05-18 11:43:39', 0.995803),
(26, 1, 'person', '2019-05-18 11:43:40', 0.991775),
(27, 1, 'sofa', '2019-05-18 11:43:40', 0.54954),
(28, 1, 'cup', '2019-05-18 11:43:40', 0.509672),
(29, 1, 'person', '2019-05-18 11:43:41', 0.99486),
(30, 1, 'person', '2019-05-18 11:43:42', 0.992211),
(31, 1, 'sofa', '2019-05-18 11:43:42', 0.675602),
(32, 1, 'person', '2019-05-18 11:43:43', 0.979227),
(33, 1, 'sofa', '2019-05-18 11:43:43', 0.55878),
(34, 1, 'person', '2019-05-18 11:43:44', 0.996118),
(35, 1, 'sofa', '2019-05-18 11:43:44', 0.524119),
(36, 1, 'person', '2019-05-18 11:43:45', 0.996449),
(37, 1, 'sofa', '2019-05-18 11:43:45', 0.606566),
(38, 1, 'person', '2019-05-18 11:43:46', 0.997863),
(39, 1, 'sofa', '2019-05-18 11:43:46', 0.825357),
(40, 1, 'cup', '2019-05-18 11:43:46', 0.534463),
(41, 1, 'person', '2019-05-18 11:43:53', 0.960749),
(42, 1, 'sofa', '2019-05-18 11:43:53', 0.708854),
(43, 1, 'cup', '2019-05-18 11:43:53', 0.548398),
(44, 1, 'person', '2019-05-18 11:43:54', 0.995948),
(45, 1, 'sofa', '2019-05-18 11:43:54', 0.726622),
(46, 1, 'person', '2019-05-18 11:43:55', 0.995989),
(47, 1, 'sofa', '2019-05-18 11:43:55', 0.854101),
(48, 1, 'person', '2019-05-18 11:43:56', 0.996841),
(49, 1, 'sofa', '2019-05-18 11:43:56', 0.805489);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `counter`
--
ALTER TABLE `counter`
  ADD PRIMARY KEY (`count_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `counter`
--
ALTER TABLE `counter`
  MODIFY `count_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
