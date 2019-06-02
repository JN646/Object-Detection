-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 02, 2019 at 10:04 AM
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
(12, 11, 'stop sign', '<i class=\"fas fa-ban\"></i>'),
(13, 12, 'parking meter', '<i class=\"fas fa-parking\"></i>'),
(14, 13, 'bench', NULL),
(15, 14, 'bird', '<i class=\"fas fa-crow\"></i>'),
(16, 15, 'cat', '<i class=\"fas fa-cat\"></i>'),
(17, 16, 'dog', '<i class=\"fas fa-dog\"></i>'),
(18, 17, 'horse', '<i class=\"fas fa-horse\"></i>'),
(19, 18, 'sheep', NULL),
(20, 19, 'cow', NULL),
(21, 20, 'elephant', NULL),
(22, 21, 'bear', NULL),
(23, 22, 'zebra', NULL),
(24, 23, 'giraffe', NULL),
(25, 24, 'backpack', NULL),
(26, 25, 'umbrella', '<i class=\"fas fa-umbrella\"></i>'),
(27, 26, 'handbag', NULL),
(28, 27, 'tie', NULL),
(29, 28, 'suitcase', NULL),
(30, 29, 'frisbee', NULL),
(31, 30, 'skis', NULL),
(32, 31, 'snowboard', NULL),
(33, 32, 'sports ball', '<i class=\"fas fa-volleyball-ball\"></i>'),
(34, 33, 'kite', NULL),
(35, 34, 'baseball bat', NULL),
(36, 35, 'baseball glove', NULL),
(37, 36, 'skateboard', NULL),
(38, 37, 'surfboard', NULL),
(39, 38, 'tennis racket', NULL),
(40, 39, 'bottle', NULL),
(41, 40, 'wine glass', NULL),
(42, 41, 'cup', '<i class=\"fas fa-mug-hot\"></i>'),
(43, 42, 'fork', '<i class=\"fas fa-utensils\"></i>'),
(44, 43, 'knife', '<i class=\"fas fa-utensils\"></i>'),
(45, 44, 'spoon', '<i class=\"fas fa-utensils\"></i>'),
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
(68, 67, 'mobile phone', '<i class=\"fas fa-mobile-alt\"></i>'),
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
  `count_long` decimal(9,6) DEFAULT '0.000000',
  `count_left` int(11) DEFAULT NULL,
  `count_top` int(11) DEFAULT NULL,
  `count_right` int(11) DEFAULT NULL,
  `count_bottom` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `counter`
--

INSERT INTO `counter` (`count_id`, `count_deviceID`, `count_class`, `count_time`, `count_confidence`, `count_lat`, `count_long`, `count_left`, `count_top`, `count_right`, `count_bottom`) VALUES
(1, 1, '0', '2019-06-01 21:40:04', 0.957323, '52.134600', '-0.466300', 16, 99, 1110, 711),
(2, 1, '0', '2019-06-01 21:40:05', 0.951565, '52.134600', '-0.466300', 70, 122, 1219, 677),
(3, 1, '0', '2019-06-01 21:40:06', 0.956271, '52.134600', '-0.466300', 9, 104, 1117, 705),
(4, 1, '0', '2019-06-01 21:40:08', 0.940659, '52.134600', '-0.466300', -3, 98, 1142, 705),
(5, 1, '0', '2019-06-01 21:40:09', 0.902064, '52.134600', '-0.466300', 203, 132, 1246, 681),
(6, 1, '57', '2019-06-01 21:40:09', 0.833774, '52.134600', '-0.466300', 203, 132, 1246, 681),
(7, 1, '0', '2019-06-01 21:40:10', 0.922137, '52.134600', '-0.466300', 206, 128, 1252, 682),
(8, 1, '0', '2019-06-01 21:40:11', 0.944903, '52.134600', '-0.466300', 198, 137, 1247, 674),
(9, 1, '0', '2019-06-01 21:40:13', 0.937718, '52.134600', '-0.466300', 211, 124, 1245, 684),
(10, 1, '0', '2019-06-01 21:40:14', 0.93259, '52.134600', '-0.466300', 10, 97, 1133, 709),
(11, 1, '0', '2019-06-01 21:40:15', 0.91397, '52.134600', '-0.466300', 83, 119, 1162, 688),
(12, 1, '57', '2019-06-01 21:40:15', 0.705781, '52.134600', '-0.466300', 83, 119, 1162, 688),
(13, 1, '0', '2019-06-01 21:40:16', 0.955601, '52.134600', '-0.466300', 72, 111, 1176, 697),
(14, 1, '57', '2019-06-01 21:40:17', 0.736024, '52.134600', '-0.466300', 43, 115, 1213, 693),
(15, 1, '0', '2019-06-01 21:40:18', 0.98243, '52.134600', '-0.466300', 43, 115, 1213, 693),
(16, 1, '0', '2019-06-01 21:40:19', 0.980705, '52.134600', '-0.466300', 59, 185, 1180, 730),
(17, 1, '0', '2019-06-01 21:40:20', 0.980129, '52.134600', '-0.466300', 58, 125, 1175, 708),
(18, 1, '0', '2019-06-01 21:40:21', 0.962286, '52.134600', '-0.466300', 17, 93, 1117, 705),
(19, 1, '0', '2019-06-01 21:40:22', 0.998076, '52.134600', '-0.466300', 45, 50, 1178, 678),
(20, 1, '0', '2019-06-01 21:40:24', 0.961457, '52.134600', '-0.466300', -7, 81, 1148, 661),
(21, 1, '0', '2019-06-01 21:40:25', 0.934583, '52.134600', '-0.466300', 7, 136, 748, 676),
(22, 1, '0', '2019-06-01 21:40:26', 0.734812, '52.134600', '-0.466300', 35, 108, 1224, 603),
(23, 1, '0', '2019-06-01 21:40:27', 0.93269, '52.134600', '-0.466300', 20, 84, 1245, 656),
(24, 1, '0', '2019-06-01 21:40:28', 0.9981, '52.134600', '-0.466300', 44, 41, 1221, 682),
(25, 1, '0', '2019-06-01 21:40:30', 0.969863, '52.134600', '-0.466300', 255, 28, 1225, 691),
(26, 1, '0', '2019-06-01 21:40:31', 0.996321, '52.134600', '-0.466300', 68, 68, 1212, 735),
(27, 1, '0', '2019-06-01 21:40:32', 0.987539, '52.134600', '-0.466300', 68, 112, 1206, 691),
(28, 1, '67', '2019-06-01 21:40:33', 0.784517, '52.134600', '-0.466300', 29, 129, 538, 680),
(29, 1, '0', '2019-06-01 21:40:33', 0.745639, '52.134600', '-0.466300', 29, 129, 538, 680),
(30, 1, '0', '2019-06-01 21:40:35', 0.782814, '52.134600', '-0.466300', 115, 125, 1179, 693),
(31, 1, '67', '2019-06-01 21:40:35', 0.782642, '52.134600', '-0.466300', 115, 125, 1179, 693),
(32, 1, '0', '2019-06-01 21:40:36', 0.890665, '52.134600', '-0.466300', 105, 32, 1179, 697),
(33, 1, '0', '2019-06-01 21:40:37', 0.944772, '52.134600', '-0.466300', -7, 60, 1283, 752),
(34, 1, '0', '2019-06-01 21:40:38', 0.942972, '52.134600', '-0.466300', 9, 64, 1279, 744),
(35, 1, '0', '2019-06-01 21:40:39', 0.9167, '52.134600', '-0.466300', 43, 227, 1237, 718),
(36, 1, '0', '2019-06-01 21:40:41', 0.972873, '52.134600', '-0.466300', 47, 111, 1200, 692),
(37, 1, '0', '2019-06-01 21:40:42', 0.976687, '52.134600', '-0.466300', 311, 311, 1383, 787),
(38, 1, '67', '2019-06-01 21:40:42', 0.955643, '52.134600', '-0.466300', 311, 311, 1383, 787),
(39, 1, '57', '2019-06-01 21:40:43', 0.84728, '52.134600', '-0.466300', 382, 431, 1543, 979),
(40, 1, '0', '2019-06-01 21:40:43', 0.969907, '52.134600', '-0.466300', 382, 431, 1543, 979),
(41, 1, '67', '2019-06-01 21:40:43', 0.902174, '52.134600', '-0.466300', 382, 431, 1543, 979),
(42, 1, '67', '2019-06-01 21:40:45', 0.9497, '52.134600', '-0.466300', 9, 139, 404, 484),
(43, 1, '0', '2019-06-01 21:40:45', 0.776227, '52.134600', '-0.466300', 9, 139, 404, 484),
(44, 1, '0', '2019-06-01 21:40:46', 0.851412, '52.134600', '-0.466300', 95, 128, 1172, 692),
(45, 1, '0', '2019-06-01 21:40:47', 0.845137, '52.134600', '-0.466300', 77, 158, 1196, 666),
(46, 1, '56', '2019-06-01 21:40:47', 0.746102, '52.134600', '-0.466300', 77, 158, 1196, 666),
(47, 1, '0', '2019-06-01 21:40:48', 0.905704, '52.134600', '-0.466300', -18, 118, 1167, 685),
(48, 1, '0', '2019-06-01 21:40:50', 0.849561, '52.134600', '-0.466300', -3, 119, 1155, 684),
(49, 1, '0', '2019-06-01 21:40:51', 0.893152, '52.134600', '-0.466300', 76, 108, 1181, 693),
(50, 1, '56', '2019-06-01 21:40:51', 0.757688, '52.134600', '-0.466300', 76, 108, 1181, 693),
(51, 1, '0', '2019-06-01 21:47:04', 0.886541, '52.134600', '-0.466300', -33, 150, 1184, 668),
(52, 1, '56', '2019-06-01 21:47:05', 0.754986, '52.134600', '-0.466300', -33, 150, 1184, 668),
(53, 1, '0', '2019-06-01 21:47:06', 0.92456, '52.134600', '-0.466300', -40, 138, 1195, 677),
(54, 1, '0', '2019-06-01 21:47:07', 0.92811, '52.134600', '-0.466300', -26, 129, 1181, 683),
(55, 1, '0', '2019-06-01 21:47:08', 0.946173, '52.134600', '-0.466300', 67, 112, 1208, 631);

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `device_id` int(11) NOT NULL COMMENT 'Device ID',
  `device_name` varchar(100) DEFAULT NULL COMMENT 'Name of the Device',
  `device_location` varchar(100) DEFAULT NULL COMMENT 'Location of the Device',
  `device_clientVersion` varchar(30) DEFAULT NULL COMMENT 'Version of the client software being run',
  `device_confidenceThreshold` float NOT NULL DEFAULT '0.5' COMMENT 'Confidence Threshold',
  `device_classToDetect` varchar(200) DEFAULT NULL COMMENT 'Delimited list of classes to detect'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`device_id`, `device_name`, `device_location`, `device_clientVersion`, `device_confidenceThreshold`, `device_classToDetect`) VALUES
(1, 'Client 1', 'Office', '1.01', 0.7, ''),
(2, 'Client 2', 'Car Park', '', 0.5, '57');

-- --------------------------------------------------------

--
-- Table structure for table `mission`
--

CREATE TABLE `mission` (
  `mission_id` int(11) NOT NULL COMMENT 'Mission ID Number.',
  `mission_name` varchar(250) NOT NULL COMMENT 'Name of the mission.',
  `mission_description` varchar(250) NOT NULL COMMENT 'Description of the mission.',
  `mission_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date Time mission created.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mission`
--

INSERT INTO `mission` (`mission_id`, `mission_name`, `mission_description`, `mission_created`) VALUES
(1, 'People Count', 'Only count people.', '2019-06-02 09:38:37'),
(2, 'Car Count', 'Only count cars.', '2019-06-02 09:38:37');

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
-- Indexes for table `mission`
--
ALTER TABLE `mission`
  ADD PRIMARY KEY (`mission_id`);

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
  MODIFY `count_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `device_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Device ID', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mission`
--
ALTER TABLE `mission`
  MODIFY `mission_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Mission ID Number.', AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
