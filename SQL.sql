-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 02, 2024 at 05:08 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `secureprog`
--

-- --------------------------------------------------------

--
-- Table structure for table `aboutus`
--

CREATE TABLE `aboutus` (
  `about_id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `message` text DEFAULT NULL,
  `send_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `aboutus`
--

INSERT INTO `aboutus` (`about_id`, `name`, `email`, `message`, `send_at`) VALUES
(1, 'victor benaya', 'vicbe@gmail.com', 'Ngohee', '2023-10-05 16:01:00'),
(2, 'leo', 'betrand@gmail.com', 'tes123 wih keren juga ni fitur', '2023-10-06 21:00:00'),
(3, 'asdf', 'asdfasdfa@gmail.com', 'wah keren banget kaq', '2024-01-02 15:19:33'),
(4, 'asdf', 'asdfasdfa@gmail.com', 'DSADFFFFFDFAFA', '2024-01-02 15:39:04'),
(5, 'asdfadsfaf', 'asdf@asdf.com', 'asdjfahgsdf iasdfgail sdhfasidfnal kjsdhlads', '2024-01-02 15:55:13');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `item_name` text DEFAULT NULL,
  `item_picture` text DEFAULT NULL,
  `item_desc` text DEFAULT NULL,
  `item_stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `item_picture`, `item_desc`, `item_stock`) VALUES
(1, 'Fefe goreng', 'https://media.discordapp.net/attachments/1156182611383824435/1165674085464948766/FEFE3_01_-_Copy.jpg?ex=6547b597&is=65354097&hm=6818a9608a6a1fc48e15979c417f242f88c8fad5d358dbc3c07990d9ba353098&=&width=888&height=888', 'Fefe', 1),
(2, 'Fefe goreng v2', 'https://media.discordapp.net/attachments/1156182611383824435/1165674085464948766/FEFE3_01_-_Copy.jpg?ex=6547b597&is=65354097&hm=6818a9608a6a1fc48e15979c417f242f88c8fad5d358dbc3c07990d9ba353098&=&width=888&height=888', 'Fefe', 1),
(3, 'Kabel bro', 'https://images.tokopedia.net/img/cache/700/product-1/2020/4/23/14645870/14645870_21ea11bf-c625-45d5-9df1-8c2e2f7257b9_750_750', 'Kabel fiber mantapp!!!', 4),
(4, 'sample foto', 'https://2.img-dpreview.com/files/p/TS1200x900~sample_galleries/5484254968/2438176383.jpg', 'foto gunung', 20),
(5, 'A9 Photo sample', 'https://1.img-dpreview.com/files/p/TS1200x900~sample_galleries/5484254968/7237628841.jpg', 'A9 Photo sample', 1);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `sender_id` int(11) UNSIGNED NOT NULL,
  `report_type` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `send_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`report_id`, `sender_id`, `report_type`, `description`, `send_time`) VALUES
(1, 2, 'Kritik dan Saran', 'Ini web apaan dah, isinya putih, gaada warna sama sekali, ga rekomen dah intinya', '2023-10-05 16:03:53'),
(2, 1, 'Lainnya', 'Kucingku kemarin nyangkut diatas pohon, bisa bantu turunin ga ya?', '2023-10-06 21:53:02'),
(3, 3, 'Lainnya', 'hehehehehehehhehehehehehehehhehehehehehehehhe', '2024-01-02 15:38:41'),
(4, 3, 'Kritik dan Saran', 'KEREN BGT KAKKEREN BGT KAKKEREN BGT KAKKEREN BGT KAKKEREN BGT KAK', '2024-01-02 15:38:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `name` text DEFAULT NULL,
  `username` varchar(15) DEFAULT NULL,
  `email` text DEFAULT NULL,
  `phone_number` bigint(20) NOT NULL,
  `password` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `username`, `email`, `phone_number`, `password`, `created_at`) VALUES
(0, 'Admin', 'admin', 'admin@gmail.com', 1500505, '$2y$10$TFa9fnwa1AHNS/O/BrQlX.elKR5VTFpIfB9iIRwgxNNeNoX26TcfW', '2023-10-22 22:13:50'),
(1, 'Bertrand R.M.', 'Lawson Schwantz', 'laws@gmail.com', 6281234567890, '$2y$10$nKjHOYIUs2qsrb1Y5AhLhe5Kg1NO1.yEY.TMSVjNyxIUD/P5.L3Ne', '2023-10-05 16:00:50'),
(2, 'Fefe', 'Thunder', 'bukanpetir@gmail.com', 6289876543210, '$2y$10$73CNI76svhIdYR8Mjjdq7uVpCkPfAVb9W25HqmZBX7rGv6lWzA9sS', '2023-10-06 20:43:00'),
(3, 'Kentang', 'kentang123', 'kentang@gmail.com', 81398690548, '$2y$10$EG1KcOzdamsZcHddY8xm/OfBYaJKLl9/vykqf1AorapnvYunsoeYK', '2024-01-02 05:34:05'),
(4, 'HEHEHIHI', 'HEHEHIHI', 'HEHEHIHI@gmail.com', 81398690548, '$2y$10$ay3l5ZE19iWRGqOpMCxpVeGF8/zQvZuPswmbvvuCal6qe4k6bZnv2', '2024-01-02 15:40:38');

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `username` varchar(255) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `activity_timestamp` timestamp NULL DEFAULT NULL,
  `login_status` tinyint(1) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_sessions`
--

INSERT INTO `user_sessions` (`username`, `session_id`, `activity_timestamp`, `login_status`, `user_agent`) VALUES
('admin', '2i4ucl47r81btuedpaof943qfr', '2024-01-02 15:39:38', 0, NULL),
('HEHEHIHI', 'hlktffrcebdf1sn06p6pte8ohd', '2024-01-02 15:30:10', 0, NULL),
('kentang123', '2i4ucl47r81btuedpaof943qfr', '2024-01-02 15:39:06', 0, NULL),
('Lawson Schwantz', '764rne6u5cc48692if97tas12b', '2024-01-02 15:55:26', 0, NULL),
('Thunder', 'nt818pbf8pcbkl199tg5dc0um0', '2024-01-02 09:34:23', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aboutus`
--
ALTER TABLE `aboutus`
  ADD PRIMARY KEY (`about_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `senderid` (`sender_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aboutus`
--
ALTER TABLE `aboutus`
  MODIFY `about_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `senderid` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
