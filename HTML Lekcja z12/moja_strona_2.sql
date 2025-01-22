-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 22, 2025 at 11:55 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `moja_strona_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent`) VALUES
(1, 'Komputery', 0),
(2, 'Laptopy', 0);

-- --------------------------------------------------------

--
-- Table structure for table `page_list`
--

CREATE TABLE `page_list` (
  `id` int(11) NOT NULL,
  `page_title` varchar(255) NOT NULL,
  `page_content` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `alias` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `page_list`
--

INSERT INTO `page_list` (`id`, `page_title`, `page_content`, `status`, `alias`) VALUES
(1, 'Nowe2 architektury CNN', 'Test2', 1, 'https://cdn.x-kom.pl/i/setup/images/prod');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `creation_date` date DEFAULT NULL,
  `modification_date` date DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `net_price` int(11) NOT NULL,
  `vat` int(11) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `category` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `description`, `creation_date`, `modification_date`, `expiration_date`, `net_price`, `vat`, `stock_quantity`, `status`, `category`, `image`) VALUES
(4, 'Asus2', 'Laptopp', NULL, NULL, NULL, 20000, 23, 22, 1, 2, 'https://cdn.x-kom.pl/i/setup/images/prod/big/product-new-big,,2024/6/pr_2024_6_3_7_22_45_87_00.jpg'),
(28, 'Apple MacBook Pro 16\" M2 Max', '16.2-inch Laptop, 32GB RAM, 1TB SSD, Space Gray', '2025-01-22', NULL, NULL, 349900, 23, 15, 1, 1, 'https://images.unsplash.com/photo-1611186871348-b1ce696e52c9'),
(29, 'Dell XPS 15 9530', '15.6\" 4K OLED, i7-13700H, 32GB DDR5, 1TB SSD, NVIDIA RTX 4050', '2025-01-22', NULL, NULL, 249900, 23, 20, 1, 1, 'https://images.pexels.com/photos/303383/pexels-photo-303383.jpeg'),
(31, 'Lenovo ThinkPad X1 Carbon', '14\" WUXGA, i5-1240P, 16GB RAM, 512GB SSD, Black', '2025-01-22', NULL, NULL, 159900, 23, 25, 1, 1, 'https://images.pexels.com/photos/109371/pexels-photo-109371.jpeg'),
(32, 'Asus ROG Zephyrus G14', '14\" WQXGA 120Hz, Ryzen 9 7940HS, 32GB RAM, 1TB SSD, RTX 4060', '2025-01-22', NULL, NULL, 219900, 23, 8, 1, 1, 'https://images.unsplash.com/photo-1598550476439-6847785fcea6'),
(33, 'Acer Predator Helios 300', '15.6\" FHD 144Hz, i7-12700H, 16GB RAM, 1TB SSD, RTX 4070', '2025-01-22', NULL, NULL, 199900, 23, 10, 1, 1, 'https://images.pexels.com/photos/1229861/pexels-photo-1229861.jpeg'),
(34, 'Microsoft Surface Laptop 5', '13.5\" PixelSense, i5-1235U, 8GB RAM, 512GB SSD, Platinum', '2025-01-22', NULL, NULL, 129900, 23, 18, 1, 1, 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed'),
(36, 'Samsung Galaxy Book3 Ultra', '16\" AMOLED, i7-1360P, 16GB RAM, 1TB SSD, RTX 4050', '2025-01-22', NULL, NULL, 229900, 23, 12, 1, 1, 'https://images.pexels.com/photos/4050426/pexels-photo-4050426.jpeg'),
(37, 'Apple MacBook Air M2', '13.6-inch Liquid Retina, 8GB RAM, 512GB SSD, Midnight', '2025-01-22', NULL, NULL, 149900, 23, 22, 1, 1, 'https://images.unsplash.com/photo-1618424181497-157f25b6ddd5'),
(38, 'MSI Katana GF76', '17.3\" FHD 144Hz, i5-12500H, 16GB RAM, 512GB SSD, RTX 3050', '2025-01-22', NULL, NULL, 139900, 23, 7, 1, 1, 'https://images.pexels.com/photos/2528118/pexels-photo-2528118.jpeg'),
(40, 'Framework Laptop 13', '13.5\" 2256x1504, i5-1240P, 16GB RAM, 512GB SSD (Modular)', '2025-01-22', NULL, NULL, 159900, 23, 9, 1, 1, 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef'),
(41, 'Alienware m16 R1', '16\" QHD+ 165Hz, i9-13900HX, 32GB RAM, 2TB SSD, RTX 4090', '2025-01-22', NULL, NULL, 399900, 23, 3, 1, 1, 'https://images.pexels.com/photos/1229861/pexels-photo-1229861.jpeg'),
(42, 'Huawei MateBook X Pro', '13.9\" 3K Touch, i7-1260P, 16GB RAM, 1TB SSD, Emerald Green', '2025-01-22', NULL, NULL, 189900, 23, 11, 1, 1, 'https://images.unsplash.com/photo-1603302576837-37561b2e2302'),
(43, 'Lenovo Legion Pro 5', '16\" WQXGA 240Hz, Ryzen 7 7745HX, 32GB RAM, 1TB SSD, RTX 4070', '2025-01-22', NULL, NULL, 199900, 23, 6, 1, 1, 'https://images.pexels.com/photos/777001/pexels-photo-777001.jpeg'),
(45, 'Acer Swift 3', '14\" FHD IPS, Ryzen 5 5625U, 8GB RAM, 512GB SSD, Silver', '2025-01-22', NULL, NULL, 89900, 23, 30, 1, 1, 'https://images.pexels.com/photos/434346/pexels-photo-434346.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_list`
--
ALTER TABLE `page_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `page_list`
--
ALTER TABLE `page_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
