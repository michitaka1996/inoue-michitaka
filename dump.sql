-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 07, 2019 at 11:48 AM
-- Server version: 5.7.23
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `keijiban`
--

-- --------------------------------------------------------

--
-- Table structure for table `board`
--

CREATE TABLE `board` (
  `id` int(11) NOT NULL,
  `sale_user` int(11) DEFAULT NULL,
  `buy_user` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `delete_flg` tinyint(4) DEFAULT '0',
  `create_date` datetime DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `board`
--

INSERT INTO `board` (`id`, `sale_user`, `buy_user`, `product_id`, `delete_flg`, `create_date`, `update_date`) VALUES
(21, 7, 8, 49, 0, '2019-04-14 04:29:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `delete_flg` tinyint(1) NOT NULL DEFAULT '0',
  `create_date` datetime NOT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `delete_flg`, `create_date`, `update_date`) VALUES
(1, 'シューズ', 0, '2019-03-24 00:00:00', '2019-03-24 05:26:34'),
(2, 'ウェア', 0, '2019-03-24 00:00:00', '2019-03-24 05:26:54'),
(3, '筋トレ器具', 0, '2019-03-24 00:00:00', '2019-03-24 05:27:55'),
(4, 'サプリメント', 0, '2019-03-26 00:00:00', '2019-03-26 13:18:38'),
(5, 'ケア用品', 0, '2019-03-26 00:00:00', '2019-03-26 13:30:36');

-- --------------------------------------------------------

--
-- Table structure for table `like`
--

CREATE TABLE `like` (
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `delete_flg` tinyint(1) NOT NULL DEFAULT '0',
  `create_date` datetime DEFAULT NULL,
  `update_date_like` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `like`
--

INSERT INTO `like` (`product_id`, `user_id`, `delete_flg`, `create_date`, `update_date_like`) VALUES
(49, 8, 0, '2019-04-02 11:31:24', '2019-04-02 11:31:24'),
(51, 8, 0, '2019-04-03 02:07:14', '2019-04-03 02:07:14'),
(52, 8, 0, '2019-04-03 02:07:24', '2019-04-03 02:07:24'),
(54, 8, 0, '2019-04-03 05:13:05', '2019-04-03 05:13:05'),
(55, 8, 0, '2019-04-03 05:13:16', '2019-04-03 05:13:16'),
(49, 7, 0, '2019-04-03 08:35:59', '2019-04-03 08:35:59'),
(51, 7, 0, '2019-04-03 08:36:11', '2019-04-03 08:36:11'),
(54, 7, 0, '2019-04-03 08:36:15', '2019-04-03 08:36:15'),
(50, 8, 0, '2019-04-07 04:33:57', '2019-04-07 04:33:57');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `board_id` int(11) DEFAULT NULL,
  `send_date` datetime DEFAULT NULL,
  `to_user` int(11) DEFAULT NULL,
  `from_user` int(11) DEFAULT NULL,
  `msg` varchar(255) DEFAULT NULL,
  `delete_flg` tinyint(1) NOT NULL DEFAULT '0',
  `create_date` datetime DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `board_id`, `send_date`, `to_user`, `from_user`, `msg`, `delete_flg`, `create_date`, `update_date`) VALUES
(31, 21, '2019-04-14 04:29:20', 7, 8, 'g4ehe4hwhe4h', 0, '2019-04-14 04:29:20', NULL),
(32, 21, '2019-04-14 04:29:38', 8, 7, 'hwhwrhwhr', 0, '2019-04-14 04:29:38', NULL),
(33, 21, '2019-04-14 04:32:25', 7, 8, 'じhghごhgはあ', 0, '2019-04-14 04:32:25', NULL),
(34, 21, '2019-04-14 04:32:59', 8, 7, 'hwhwrhrwhrwh', 0, '2019-04-14 04:32:59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `pic1` varchar(255) DEFAULT NULL,
  `pic2` varchar(255) DEFAULT NULL,
  `pic3` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `delete_flg` tinyint(1) NOT NULL DEFAULT '0',
  `create_date` datetime NOT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `category_id`, `comment`, `price`, `pic1`, `pic2`, `pic3`, `user_id`, `delete_flg`, `create_date`, `update_date`) VALUES
(49, 'シューズ', 1, 'kokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokokoko', 21000, 'uploads/f6a4160bf20e6e172f1681c9e3b38a7e8b050be4.jpeg', '', '', 7, 0, '2019-03-29 13:00:55', '2019-03-29 13:00:55'),
(50, 'サプリ', 4, 'ここここここここここここここここここここここここここここここここここここここここここここここここここここここここここここここここここここここここここここここここ', 8900, 'uploads/0949fff63756e0d7aa4547f24c9cb5020df0c1d8.jpeg', '', '', 7, 0, '2019-03-29 13:01:56', '2019-03-29 13:01:56'),
(51, 'ケトルベル', 3, '', 18990, 'uploads/c85225e72a8877801251265ec8f12d5acb84cd03.jpeg', '', '', 8, 0, '2019-03-30 02:42:15', '2019-03-30 02:42:15'),
(52, 'ジャージ', 2, '', 21000, 'uploads/9f8f86d0a85a300cdc558ba9d55098b2a46d23b5.jpeg', '', '', 8, 0, '2019-03-30 02:46:15', '2019-03-30 02:46:15'),
(53, 'シューズ', 1, '', 4444, 'uploads/27ae8d00aebfb21cc26f34eccb48c85403087947.jpeg', 'uploads/27ae8d00aebfb21cc26f34eccb48c85403087947.jpeg', '', 8, 0, '2019-03-30 02:47:13', '2019-03-30 02:47:13'),
(54, 'バーベル', 3, '', 8900, 'uploads/d25f6434bed22356da93628d0ea8ef6bf59a8ef5.jpeg', 'uploads/d25f6434bed22356da93628d0ea8ef6bf59a8ef5.jpeg', 'uploads/d25f6434bed22356da93628d0ea8ef6bf59a8ef5.jpeg', 8, 0, '2019-03-30 02:49:50', '2019-03-30 02:49:50'),
(55, 'シューズ', 1, '', 8900, 'uploads/d25f6434bed22356da93628d0ea8ef6bf59a8ef5.jpeg', 'uploads/d25f6434bed22356da93628d0ea8ef6bf59a8ef5.jpeg', 'uploads/63a03faaefd584ddb9f551b4da4d20865155446b.jpeg', 8, 0, '2019-03-30 02:52:16', '2019-03-30 02:52:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `tel` varchar(255) DEFAULT NULL,
  `addr` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `delete_flg` tinyint(1) DEFAULT '0',
  `create_date` datetime DEFAULT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `tel`, `addr`, `age`, `password`, `login_time`, `pic`, `delete_flg`, `create_date`, `update_date`) VALUES
(7, 'たろうだよ', 'unkotaro@gmail.com', '09030593820', '大阪府大阪市札幌町ハイツ中村494', 22, '$2y$10$doypJmreYuqA/yTgaHEALOdmppM8fw5xDPYzVtfXX/.iaWKTbtjaC', '2019-03-25 04:15:25', 'uploads/1c9e4613fc11b0b0ecd76f46f6410d164b2ac535.jpeg', 0, '2019-03-25 04:15:25', '2019-03-25 04:15:25'),
(8, 'えええ', 'michirug11@i.softbank.jp', '09030593821', 'sssssss', 6, '$2y$10$iNHmYS7863S/hc8kfw4uEutJVmo1OxJlldyr34vQjhPD55fpih.3u', '2019-03-29 12:28:45', 'uploads/ee99518633a62fcd56e4f49536aa2558cf8fb7cd.jpeg', 0, '2019-03-29 12:28:45', '2019-03-29 12:28:45'),
(10, NULL, 'toru@i.softbank.jp', NULL, NULL, NULL, '$2y$10$IYaLIOtHBagJK.VBuHiy3.DIiJ1Fn/YyNstGg/kDgRFjnSyvcQNmu', '2019-05-01 03:04:22', NULL, 0, '2019-05-01 03:04:22', '2019-05-01 03:04:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `board`
--
ALTER TABLE `board`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `board`
--
ALTER TABLE `board`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
