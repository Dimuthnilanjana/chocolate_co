-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 06, 2024 at 07:57 AM
-- Server version: 8.0.31
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `orderpayment`
--

DROP TABLE IF EXISTS `orderpayment`;
CREATE TABLE IF NOT EXISTS `orderpayment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orderpayment`
--

INSERT INTO `orderpayment` (`id`, `order_id`, `payment_date`, `payment_method`, `status`) VALUES
(15, 80, '2024-03-06', 'cash_on_delivery', 'paid'),
(16, 81, '2024-03-06', 'cash_on_delivery', 'paid'),
(17, 82, '2024-03-06', 'card_payment', 'paid'),
(18, 83, '2024-03-06', 'card_payment', 'paid'),
(19, 84, '2024-03-06', 'cash_on_delivery', 'paid'),
(20, 86, '2024-03-06', 'card_payment', 'paid'),
(21, 88, '2024-03-06', 'cash_on_delivery', 'paid'),
(22, 89, '2024-03-06', 'cash_on_delivery', 'paid'),
(23, 90, '2024-03-06', 'cash_on_delivery', 'paid'),
(24, 91, '2024-03-06', 'cash_on_delivery', 'paid'),
(25, 92, '2024-03-06', 'cash_on_delivery', 'paid'),
(26, 93, '2024-03-06', 'cash_on_delivery', 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `order_date` date DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `TotalAmount` int DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_date`, `user_id`, `TotalAmount`) VALUES
(80, '2024-03-05', 2, 24),
(81, '2024-03-05', 5, 7),
(82, '2024-03-05', 5, 28),
(83, '2024-03-06', 5, 39),
(84, '2024-03-06', 1, 6),
(85, '2024-03-06', 1, 0),
(86, '2024-03-06', 1, 6),
(87, '2024-03-06', 1, 6),
(88, '2024-03-06', 1, 18),
(89, '2024-03-06', 1, 18),
(90, '2024-03-06', 1, 86),
(91, '2024-03-06', 1, 21),
(92, '2024-03-06', 6, 14),
(93, '2024-03-06', 6, 21);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`) VALUES
(80, 12, 2),
(80, 11, 2),
(81, 13, 1),
(82, 13, 4),
(83, 14, 3),
(83, 13, 3),
(84, 14, 1),
(86, 14, 1),
(87, 14, 1),
(88, 14, 3),
(89, 14, 3),
(90, 13, 2),
(90, 14, 11),
(90, 14, 1),
(91, 13, 3),
(92, 13, 2),
(93, 15, 3),
(93, 14, 2);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` int NOT NULL,
  `stock` int NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `stock`, `image`) VALUES
(13, 'Milkey Chocolates', 7, 1, 'Default_chocolates_3.jpg'),
(15, 'white choco', 3, 6, 'Default_chocolates_2.jpg'),
(14, 'Dark Chocolate', 6, 14, 'Default_chocolates_0.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `user_add` varchar(250) NOT NULL,
  `user_mail` varchar(100) NOT NULL,
  `user_tel` varchar(10) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_add`, `user_mail`, `user_tel`, `password`) VALUES
(1, 'dimuth', '2nd mail post,poddiwala,maththaka', 'dimuthnilanjana7@gmail.com', '+947021826', '1234567'),
(2, 'nilanjana', '2nd mail post,poddiwala,maththaka', 'dimuthnilanjana@gmail.com', '0778929045', 'dimuth@2000'),
(3, 'ww', 'www', 'www@gmail.com', '0769801828', '123456'),
(4, 'test', 'test', 'test@gmail.com', '1234567890', 'test'),
(5, 'test2', 'test2', 'test2@gmail.com', '0767905044', 'test'),
(6, 'test4', 'test', 'dimuthnilanjana7@gmail.com', '+947021826', '1234');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
