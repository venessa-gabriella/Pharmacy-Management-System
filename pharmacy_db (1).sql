-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2026 at 09:40 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pharmacy_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` int(11) NOT NULL,
  `medicine_name` varchar(100) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `price` decimal(10,2) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`id`, `medicine_name`, `category`, `quantity`, `price`, `expiry_date`, `created_at`) VALUES
(1, 'Paracetamol', 'Painkillers', 90, 50.00, '2027-12-20', '2026-11-06 07:37:47'),
(3, 'Amoxicillin', 'Antibiotics', 53, 250.00, '2027-08-10', '2026-11-06 07:37:47'),
(4, 'Cetirizine', 'Antihistamines', 69, 80.00, '2027-11-25', '2026-11-06 07:37:47'),
(5, 'Vitamin C', 'Vitamins', 117, 150.00, '2028-01-15', '2026-11-06 07:37:47'),
(6, 'Cough Syrup', 'Cough and Cold', 50, 180.00, '2027-09-12', '2026-11-06 07:37:47'),
(7, 'ORS Sachets', 'Rehydration', 100, 30.00, '2027-06-20', '2026-11-06 07:37:47'),
(8, 'Clotrimazole Cream', 'Antifungals', 36, 200.00, '2027-12-10', '2026-11-06 07:37:47'),
(9, 'Amlodipine', 'Blood Pressure', 40, 100.00, '2027-10-30', '2026-11-06 07:37:47'),
(10, 'Metformin', 'Diabetes', 40, 130.00, '2027-11-18', '2026-11-06 07:37:47'),
(11, 'Face masks', 'protection', 100, 20.00, '2029-02-02', '2026-11-06 07:41:14'),
(12, 'Hand sanitizers', 'safety', 23, 350.00, '2030-12-26', '2026-11-06 07:42:32'),
(13, 'Pregnancy test kits', 'test', 198, 50.00, '2033-12-30', '2026-11-06 07:43:29'),
(14, 'Bandages', 'Other', 50, 200.00, '2030-09-28', '2026-11-06 07:45:00'),
(15, 'Digital blood pressure monitors', 'monitor', 70, 3500.00, '2032-10-31', '2026-11-06 07:46:42'),
(16, 'Ketotifen eye drops', 'Eye Medicines', 150, 340.00, '2029-06-14', '2026-11-06 07:52:05'),
(17, 'Ketotifen eye drops', 'Eye Medicines', 150, 340.00, '2029-06-14', '2026-11-06 07:59:11'),
(18, 'Ketotifen eye drops', 'Eye Medicines', 146, 340.00, '2029-06-14', '2026-11-06 07:59:29'),
(19, 'Ketotifen eye drops', 'Eye Medicines', 150, 340.00, '2029-06-14', '2026-11-06 08:00:22');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `medicine_id`, `quantity`, `total_amount`, `order_date`) VALUES
(1, 'elvis', 4, 21, 1680.00, '2026-11-06 08:04:09'),
(2, 'gorgina', 13, 2, 100.00, '2026-11-06 08:04:51'),
(3, 'linus', 8, 4, 800.00, '2026-11-06 08:05:30'),
(4, 'christine', 5, 3, 450.00, '2026-11-06 08:06:07'),
(5, 'Aliana', 3, 7, 1750.00, '2026-11-06 08:06:45'),
(6, 'Julian', 1, 10, 500.00, '2026-11-06 08:07:28'),
(7, 'felix', 12, 2, 700.00, '2026-11-06 08:09:01'),
(8, 'vanessa', 18, 4, 1360.00, '2026-11-06 08:09:41'),
(9, 'Evylne', 10, 25, 3250.00, '2026-11-06 08:19:15'),
(10, 'maryann', 9, 30, 3000.00, '2026-11-06 08:19:58'),
(11, 'Hilda', 15, 15, 52500.00, '2026-11-06 08:20:57'),
(12, 'Hilda', 15, 15, 52500.00, '2026-11-06 08:21:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medicine_id` (`medicine_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
