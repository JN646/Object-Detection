-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 27, 2019 at 05:09 PM
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
-- Table structure for table `class_types`
--

CREATE TABLE `class_types` (
  `class_id` int(11) NOT NULL,
  `class_number` int(11) DEFAULT NULL,
  `class_name` varchar(100) DEFAULT NULL,
  `class_icon` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `class_types`
--

INSERT INTO `class_types` (`class_id`, `class_number`, `class_name`, `class_icon`) VALUES
(1, 0, 'person', '<i class=\"fas fa-user-alt\"></i>'),
(2, 1, 'bicycle', '<i class=\"fas fa-bicycle\"></i>'),
(3, 2, 'car', '<i class=\"fas fa-car\"></i>'),
(4, 3, 'motorbike', '<i class=\"fas fa-motorcycle\"></i>'),
(5, 4, 'aeroplane', '<i class=\"fas fa-plane\"></i>'),
(6, 5, 'bus', '<i class=\"fas fa-bus\"></i>'),
(7, 6, 'train', '<i class=\"fas fa-train\"></i>'),
(8, 7, 'truck', '<i class=\"fas fa-truck\"></i>'),
(9, 8, 'boat', '<i class=\"fas fa-ship\"></i>'),
(10, 9, 'traffic light', '<i class=\"fas fa-traffic-light\"></i>'),
(11, 10, 'fire hydrant', NULL),
(12, 11, 'stop sign', NULL),
(13, 12, 'parking meter', NULL),
(14, 13, 'bench', NULL),
(15, 14, 'bird', NULL),
(16, 15, 'cat', NULL),
(17, 16, 'dog', NULL),
(18, 17, 'horse', NULL),
(19, 18, 'sheep', NULL),
(20, 19, 'cow', NULL),
(21, 20, 'elephant', NULL),
(22, 21, 'bear', NULL),
(23, 22, 'zebra', NULL),
(24, 23, 'giraffe', NULL),
(25, 24, 'backpack', NULL),
(26, 25, 'umbrella', NULL),
(27, 26, 'handbag', NULL),
(28, 27, 'tie', NULL),
(29, 28, 'suitcase', NULL),
(30, 29, 'frisbee', NULL),
(31, 30, 'skis', NULL),
(32, 31, 'snowboard', NULL),
(33, 32, 'sports ball', NULL),
(34, 33, 'kite', NULL),
(35, 34, 'baseball bat', NULL),
(36, 35, 'baseball glove', NULL),
(37, 36, 'skateboard', NULL),
(38, 37, 'surfboard', NULL),
(39, 38, 'tennis racket', NULL),
(40, 39, 'bottle', NULL),
(41, 40, 'wine glass', NULL),
(42, 41, 'cup', NULL),
(43, 42, 'fork', NULL),
(44, 43, 'knife', NULL),
(45, 44, 'spoon', NULL),
(46, 45, 'bowl', NULL),
(47, 46, 'banana', NULL),
(48, 47, 'apple', NULL),
(49, 48, 'sandwich', NULL),
(50, 49, 'orange', NULL),
(51, 50, 'broccoli', NULL),
(52, 51, 'carrot', NULL),
(53, 52, 'hot dog', NULL),
(54, 53, 'pizza', NULL),
(55, 54, 'donut', NULL),
(56, 55, 'cake', NULL),
(57, 56, 'chair', '<i class=\"fas fa-chair\"></i>'),
(58, 57, 'sofa', '<i class=\"fas fa-couch\"></i>'),
(59, 58, 'potted plant', '<i class=\"fas fa-seedling\"></i>'),
(60, 59, 'bed', '<i class=\"fas fa-bed\"></i>'),
(61, 60, 'dining table', NULL),
(62, 61, 'toilet', NULL),
(63, 62, 'tv monitor', NULL),
(64, 63, 'laptop', NULL),
(65, 64, 'mouse', NULL),
(66, 65, 'remote', NULL),
(67, 66, 'keyboard', NULL),
(68, 67, 'cell phone', NULL),
(69, 68, 'microwave', NULL),
(70, 69, 'oven', NULL),
(71, 70, 'toaster', NULL),
(72, 71, 'sink', NULL),
(73, 72, 'refrigerator', NULL),
(74, 73, 'book', NULL),
(75, 74, 'clock', NULL),
(76, 75, 'vase', NULL),
(77, 76, 'scissors', NULL),
(78, 77, 'teddy bear', NULL),
(79, 78, 'hair drier', NULL),
(80, 79, 'toothbrush', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `counter`
--

CREATE TABLE `counter` (
  `count_id` int(11) NOT NULL,
  `count_deviceID` int(11) NOT NULL,
  `count_class` varchar(100) NOT NULL,
  `count_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `count_confidence` float NOT NULL,
  `count_lat` decimal(9,6) DEFAULT '0.000000',
  `count_long` decimal(9,6) DEFAULT '0.000000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `counter`
--

INSERT INTO `counter` (`count_id`, `count_deviceID`, `count_class`, `count_time`, `count_confidence`, `count_lat`, `count_long`) VALUES
(1, 1, '0', '2019-05-27 15:52:03', 0.990302, '52.134600', '-0.466300'),
(2, 1, '57', '2019-05-27 15:52:03', 0.838912, '52.134600', '-0.466300'),
(3, 1, '0', '2019-05-27 15:52:05', 0.979557, '52.134600', '-0.466300'),
(4, 1, '57', '2019-05-27 15:52:05', 0.871794, '52.134600', '-0.466300'),
(5, 1, '0', '2019-05-27 15:52:06', 0.967575, '52.134600', '-0.466300'),
(6, 1, '57', '2019-05-27 15:52:06', 0.727199, '52.134600', '-0.466300'),
(7, 1, '0', '2019-05-27 15:52:07', 0.973632, '52.134600', '-0.466300'),
(8, 1, '57', '2019-05-27 15:52:07', 0.676, '52.134600', '-0.466300'),
(9, 1, '0', '2019-05-27 15:52:09', 0.977845, '52.134600', '-0.466300'),
(10, 1, '57', '2019-05-27 15:52:09', 0.633582, NULL, NULL),
(11, 1, '0', '2019-05-27 15:52:10', 0.958984, '52.134600', '-0.466300'),
(12, 1, '57', '2019-05-27 15:52:10', 0.670125, '52.134600', '-0.466300'),
(13, 1, '0', '2019-05-27 15:52:11', 0.984241, '52.134600', '-0.466300'),
(14, 1, '57', '2019-05-27 15:52:11', 0.739304, '52.134600', '-0.466300'),
(15, 1, '0', '2019-05-27 15:52:13', 0.963358, '52.134600', '-0.466300'),
(16, 1, '56', '2019-05-27 15:52:13', 0.698201, '52.134600', '-0.466300'),
(17, 1, '0', '2019-05-27 15:52:14', 0.970826, '52.134600', '-0.466300'),
(18, 1, '57', '2019-05-27 15:52:14', 0.757767, '52.134600', '-0.466300'),
(19, 1, '0', '2019-05-27 15:52:15', 0.976841, '52.134600', '-0.466300'),
(20, 1, '57', '2019-05-27 15:52:15', 0.737003, '52.134600', '-0.466300');

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `device_id` int(11) NOT NULL,
  `device_name` varchar(100) DEFAULT NULL,
  `device_location` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`device_id`, `device_name`, `device_location`) VALUES
(1, 'Client 1', 'Office'),
(2, 'Client 2', 'Car Park');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class_types`
--
ALTER TABLE `class_types`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `counter`
--
ALTER TABLE `counter`
  ADD PRIMARY KEY (`count_id`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`device_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class_types`
--
ALTER TABLE `class_types`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `counter`
--
ALTER TABLE `counter`
  MODIFY `count_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `device_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
