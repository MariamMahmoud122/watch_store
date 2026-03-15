-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 15, 2026 at 08:08 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `watch_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `qty` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `title`, `price`, `image`, `qty`) VALUES
(1, 1, 3, 'Omega Luxury Silver', 520.00, 'watch3.jpg', 1),
(2, 1, 3, 'Omega Luxury Silver', 520.00, 'watch3.jpg', 1),
(3, 1, 3, 'Omega Luxury Silver', 520.00, 'watch3.jpg', 1),
(4, 1, 1, 'Rolex Classic Gold', 250.00, 'watch1.jpg', 1),
(5, 1, 3, 'Omega Luxury Silver', 520.00, 'watch3.jpg', 1),
(6, 1, 3, 'Omega Luxury Silver', 520.00, 'watch3.jpg', 1),
(7, 1, 3, 'Omega Luxury Silver', 520.00, 'watch3.jpg', 1),
(8, 1, 4, 'Omega Luxury Silver', 600.00, 'watch5.jpg', 1),
(9, 1, 4, 'Omega Luxury Silver', 600.00, 'watch5.jpg', 1),
(10, 1, 3, 'Omega Luxury Silver', 520.00, 'watch3.jpg', 1),
(11, 1, 3, 'Omega Luxury Silver', 520.00, 'watch3.jpg', 1),
(12, 1, 2, 'Casio Sport Digital', 399.00, 'watch2.jpg', 1),
(13, 1, 2, 'Casio Sport Digital', 399.00, 'watch2.jpg', 1),
(14, 1, 4, 'Omega Luxury Silver', 600.00, 'watch5.jpg', 1),
(15, 1, 4, 'Omega Luxury Silver', 600.00, 'watch5.jpg', 1),
(16, 1, 3, 'Omega Luxury Silver', 520.00, 'watch3.jpg', 1),
(21, 2, 4, 'Omega Luxury Silver', 600.00, 'watch5.jpg', 2),
(22, 2, 3, 'Omega Luxury Silver', 520.00, 'watch3.jpg', 1),
(26, 3, 3, 'Omega Luxury Silver', 520.00, 'watch3.jpg', 3),
(27, 3, 4, 'Omega Luxury Silver', 600.00, 'watch5.jpg', 1),
(28, 2, 2, 'Casio Sport Digital', 399.00, 'watch2.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Classic'),
(2, 'Smart'),
(3, 'Luxury'),
(4, 'Sports');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','cancelled') DEFAULT 'pending',
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `paid_at` datetime DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `status`, `address`, `phone`, `created_at`, `paid_at`, `cancelled_at`) VALUES
(1, 2, 2760.00, 'pending', NULL, NULL, '2026-03-15 04:30:15', NULL, NULL),
(2, 2, 1120.00, '', NULL, NULL, '2026-03-15 04:39:10', NULL, NULL),
(3, 3, 649.00, 'cancelled', NULL, NULL, '2026-03-15 04:46:33', NULL, '2026-03-15 08:10:19'),
(4, 3, 1560.00, '', NULL, NULL, '2026-03-15 05:16:56', NULL, NULL),
(5, 3, 0.00, '', 'الشهداء', '01111111111', '2026-03-15 05:20:33', '2026-03-15 08:09:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `qty`, `price`) VALUES
(1, 1, 3, 3, 520.00),
(2, 1, 4, 2, 600.00),
(3, 2, 4, 1, 600.00),
(4, 2, 3, 1, 520.00),
(5, 3, 2, 1, 399.00),
(6, 3, 1, 1, 250.00),
(7, 4, 3, 3, 520.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 10,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `title`, `price`, `description`, `image`, `stock`, `created_at`) VALUES
(1, 1, 'Rolex Classic Gold', 250.00, '', 'watch1.jpg', 10, '2026-03-11 11:09:00'),
(2, 1, 'Casio Sport Digital', 399.00, '', 'watch2.jpg', 10, '2026-03-11 19:28:48'),
(3, 1, 'Omega Luxury Silver', 520.00, '', 'watch3.jpg', 10, '2026-03-11 19:29:30'),
(4, 2, 'Omega Luxury Silver', 600.00, '', 'watch5.jpg', 10, '2026-03-11 19:33:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(20) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'CVC', 'cvc@gmail.com', 'ZXCVB12345!', '2026-03-12 09:39:36', 'user'),
(2, 'Hoda', 'hoda@gmail.com', 'zxcvb12345!', '2026-03-12 20:37:40', 'admin'),
(3, 'Mariam', 'Mariam@gmail.com', '$2y$10$XeQ.F7r9PBXaX1bPl38St./wYAN33qgNtaZblry0DugwnZTgFgFg.', '2026-03-15 04:44:48', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
