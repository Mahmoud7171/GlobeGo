-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2025 at 05:31 AM
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
-- Database: `globego_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `attractions`
--

CREATE TABLE `attractions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `total_reviews` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attractions`
--

INSERT INTO `attractions` (`id`, `name`, `description`, `location`, `latitude`, `longitude`, `category`, `image_url`, `rating`, `total_reviews`, `created_at`, `updated_at`) VALUES
(1, 'Eiffel Tower', 'Iconic iron tower and symbol of Paris, offering panoramic city views from its observation decks.', 'Paris, France', NULL, NULL, 'Landmark', 'images/Louvre.jpg', 0.00, 0, '2025-10-14 17:35:34', '2025-12-20 19:02:47'),
(2, 'Colosseum', 'Ancient Roman amphitheater and one of the most famous landmarks in the world.', 'Rome, Italy', NULL, NULL, 'Historical', 'images/colosseum.jpg', 0.00, 0, '2025-10-14 17:35:34', '2025-12-20 17:39:00'),
(3, 'Times Square', 'Major commercial intersection and tourist destination in Manhattan.', 'New York, USA', NULL, NULL, 'Landmark', 'images/NYC.png', 0.00, 0, '2025-10-14 17:35:34', '2025-10-14 19:36:26'),
(4, 'Tower Bridge', 'Victorian suspension bridge over the River Thames in London.', 'London, UK', NULL, NULL, 'Landmark', 'images/TowerBridge.jpg', 0.00, 0, '2025-10-14 17:35:34', '2025-10-14 19:40:44'),
(5, 'Sagrada Familia', 'Unfinished basilica designed by Antoni Gaudí in Barcelona.', 'Barcelona, Spain', NULL, NULL, 'Religious', 'images/SargadaFamilia.jpg', 0.00, 0, '2025-10-14 17:35:34', '2025-10-14 19:40:44'),
(7, 'Machu Picchu', 'Visit Machu Picchu in Cusco, Peru', 'Cusco, Peru', NULL, NULL, 'Historical Tour', 'images/MachuPicchu.jpeg', 0.00, 0, '2025-12-19 18:34:32', '2025-12-19 18:34:32'),
(8, 'Taj Mahal', 'Visit Taj Mahal in Agra, India', 'Agra, India', NULL, NULL, 'Cultural Tour', 'images/TajMahal.jpg', 0.00, 0, '2025-12-19 18:34:32', '2025-12-19 18:34:32'),
(9, 'Great Wall of China', 'Visit Great Wall of China in Beijing, China', 'Beijing, China', NULL, NULL, 'Historical Tour', 'images/GreatWallOfChina.jpg', 0.00, 0, '2025-12-19 18:34:32', '2025-12-19 18:34:32'),
(10, 'Christ the Redeemer', 'Visit Christ the Redeemer in Rio de Janeiro, Brazil', 'Rio de Janeiro, Brazil', NULL, NULL, 'Cultural Tour', 'images/Christ.jpg', 0.00, 0, '2025-12-19 18:34:32', '2025-12-19 18:34:32'),
(11, 'Sydney Opera House', 'Visit Sydney Opera House in Sydney, Australia', 'Sydney, Australia', NULL, NULL, 'Cultural Tour', 'images/OperaHouse.jpg', 0.00, 0, '2025-12-19 18:34:32', '2025-12-19 18:34:32'),
(12, 'Angkor Wat', 'Visit Angkor Wat in Siem Reap, Cambodia', 'Siem Reap, Cambodia', NULL, NULL, 'Historical Tour', 'images/AngkorWat.jpg', 0.00, 0, '2025-12-19 18:34:32', '2025-12-19 18:34:32'),
(13, 'Petra', 'Visit Petra in Petra, Jordan', 'Petra, Jordan', NULL, NULL, 'Historical Tour', 'images/Petra.jpg', 0.00, 0, '2025-12-19 18:34:32', '2025-12-19 18:34:32'),
(14, 'Stonehenge', 'Visit Stonehenge in Wiltshire, UK', 'Wiltshire, UK', NULL, NULL, 'Historical Tour', 'images/Stonehenge.jpg', 0.00, 0, '2025-12-19 18:34:32', '2025-12-19 18:34:32'),
(15, 'Shibuya Crossway', 'Visit Shibuya Crossway in Tokyo, Japan', 'Tokyo, Japan', NULL, NULL, 'City Tour', 'images/Shibuya.jpg', 0.00, 0, '2025-12-19 18:34:32', '2025-12-19 18:34:32');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `tourist_id` int(11) NOT NULL,
  `tour_schedule_id` int(11) NOT NULL,
  `booking_reference` varchar(20) NOT NULL,
  `num_participants` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `cancellation_fee` decimal(10,2) DEFAULT 0.00,
  `cancellation_fee_paid` tinyint(1) DEFAULT 0,
  `cancellation_fee_payment_method` varchar(50) DEFAULT NULL,
  `cancellation_fee_payment_reference` varchar(255) DEFAULT NULL,
  `cancellation_fee_paid_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `payment_status` enum('pending','paid','refunded') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_reference` varchar(255) DEFAULT NULL,
  `cancellation_penalty` decimal(10,2) DEFAULT 0.00,
  `penalty_paid` tinyint(1) DEFAULT 0,
  `penalty_payment_method` varchar(50) DEFAULT NULL,
  `penalty_payment_reference` varchar(255) DEFAULT NULL,
  `penalty_paid_at` datetime DEFAULT NULL,
  `booking_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `tourist_id`, `tour_schedule_id`, `booking_reference`, `num_participants`, `total_price`, `cancellation_fee`, `cancellation_fee_paid`, `cancellation_fee_payment_method`, `cancellation_fee_payment_reference`, `cancellation_fee_paid_at`, `status`, `payment_status`, `payment_method`, `payment_reference`, `cancellation_penalty`, `penalty_paid`, `penalty_payment_method`, `penalty_payment_reference`, `penalty_paid_at`, `booking_notes`, `created_at`, `updated_at`) VALUES
(5, 2, 92, 'GG68EFC07B91FE4', 2, 90.00, 0.00, 0, NULL, NULL, NULL, 'pending', 'pending', NULL, NULL, 0.00, 0, NULL, NULL, NULL, '', '2025-10-15 15:40:43', '2025-10-15 15:40:43'),
(6, 10, 116, 'GG693DEDFD83AF5', 2, 90.00, 0.00, 0, NULL, NULL, NULL, 'pending', 'pending', NULL, NULL, 0.00, 0, NULL, NULL, NULL, 'I have a special wedding ring, I want to display it on Eiffel tower', '2025-12-13 22:51:41', '2025-12-13 22:51:41'),
(7, 11, 115, 'GG693DEFD889740', 1, 45.00, 0.00, 0, NULL, NULL, NULL, 'pending', 'pending', NULL, NULL, 0.00, 0, NULL, NULL, NULL, '', '2025-12-13 22:59:36', '2025-12-13 22:59:36'),
(8, 12, 117, 'GG694037ED7C1DC', 3, 135.00, 0.00, 0, NULL, NULL, NULL, 'cancelled', 'pending', NULL, NULL, 0.00, 0, NULL, NULL, NULL, '', '2025-12-15 16:31:41', '2025-12-15 16:32:18'),
(9, 12, 116, 'GG6940383736E49', 1, 45.00, 0.00, 0, NULL, NULL, NULL, 'confirmed', 'pending', NULL, NULL, 0.00, 0, NULL, NULL, NULL, '', '2025-12-15 16:32:55', '2025-12-18 14:22:24'),
(11, 13, 958, 'GG69440E47DB2A4', 1, 45.00, 0.00, 0, NULL, NULL, NULL, 'confirmed', 'paid', 'credit_card', 'CC-69440E47DB1A6', 0.00, 0, NULL, NULL, NULL, '', '2025-12-18 14:23:03', '2025-12-18 14:27:06'),
(12, 15, 1003, 'GG694418B98EB5E', 1, 55.00, 0.00, 0, NULL, NULL, NULL, 'confirmed', 'paid', 'credit_card', 'CC-694418B98EAE1', 0.00, 0, NULL, NULL, NULL, '', '2025-12-18 15:07:37', '2025-12-18 15:07:57'),
(13, 15, 984, 'GG69441A2ECD51D', 1, 85.00, 0.00, 0, NULL, NULL, NULL, 'cancelled', 'paid', 'credit_card', 'CC-69441A2ECD465', 0.00, 0, NULL, NULL, NULL, '', '2025-12-18 15:13:50', '2025-12-18 16:22:27'),
(14, 15, 1008, 'GG6944270A25778', 1, 75.00, 0.00, 0, NULL, NULL, NULL, 'cancelled', 'paid', 'credit_card', 'CC-6944270A25698', 0.00, 0, NULL, NULL, NULL, '', '2025-12-18 16:08:42', '2025-12-18 16:22:33'),
(15, 15, 1008, 'GG69442A39AC282', 1, 75.00, 0.00, 0, NULL, NULL, NULL, 'confirmed', 'paid', 'credit_card', 'CC-69442A39AC157', 0.00, 0, NULL, NULL, NULL, '', '2025-12-18 16:22:17', '2025-12-18 16:22:48'),
(16, 13, 965, 'GG69442CA16CE4C', 1, 65.00, 0.00, 0, NULL, NULL, NULL, 'pending', 'paid', 'credit_card', 'CC-69442CA16CD97', 0.00, 0, NULL, NULL, NULL, '', '2025-12-18 16:32:33', '2025-12-18 16:32:33'),
(17, 15, 1066, 'GG6945EB6C55C20', 1, 120.00, 0.00, 0, NULL, NULL, NULL, 'cancelled', 'paid', 'credit_card', 'CC-6945EB6C554FF', 0.00, 0, NULL, NULL, NULL, '', '2025-12-20 00:18:52', '2025-12-20 19:00:12'),
(18, 15, 1013, 'GG6945EC4D61327', 1, 85.00, 0.00, 0, NULL, NULL, NULL, 'cancelled', 'paid', 'credit_card', 'CC-6945EC4D612D3', 0.00, 0, NULL, NULL, NULL, '', '2025-12-20 00:22:37', '2025-12-20 15:49:07'),
(27, 15, 1012, 'GG6945F8972517B', 1, 85.00, 0.00, 0, NULL, NULL, NULL, 'confirmed', 'paid', 'paypal', 'PP-6945F8972513D', 0.00, 0, NULL, NULL, NULL, '', '2025-12-20 01:15:03', '2025-12-20 01:15:05'),
(28, 15, 1066, 'GG6946257B8C8B8', 1, 120.00, 0.00, 0, NULL, NULL, NULL, 'cancelled', 'paid', 'credit_card', 'CC-6946257B8C748', 0.00, 0, NULL, NULL, NULL, '', '2025-12-20 04:26:35', '2025-12-20 19:08:59'),
(29, 15, 1066, 'GG6946393A74948', 1, 120.00, 0.00, 0, NULL, NULL, NULL, 'cancelled', 'paid', 'visa', 'CC-6946393A7489F', 0.00, 1, NULL, NULL, NULL, '', '2025-12-20 05:50:50', '2025-12-20 16:04:35'),
(30, 15, 1012, 'GG6946C29147C4D', 1, 254.15, 0.00, 0, NULL, NULL, NULL, 'confirmed', 'paid', 'visa', 'VISA-XXXX3456', 0.00, 0, NULL, NULL, NULL, '', '2025-12-20 15:36:49', '2025-12-20 15:36:49'),
(31, 15, 1066, 'GG6946C48CD1553', 4, 480.00, 0.00, 0, NULL, NULL, NULL, 'cancelled', 'paid', 'paypal', 'PAYPAL-6946c48cd14f9', 0.00, 0, NULL, NULL, NULL, '', '2025-12-20 15:45:16', '2025-12-20 18:15:34'),
(32, 15, 1068, 'GG6948D94B0F9BF', 1, 120.00, 0.00, 0, NULL, NULL, NULL, 'pending', 'pending', 'cash', 'CASH-PENDING', 0.00, 0, NULL, NULL, NULL, '', '2025-12-22 05:38:19', '2025-12-22 05:38:19'),
(33, 15, 966, 'GG694959173432F', 1, 65.00, 0.00, 0, NULL, NULL, NULL, 'pending', 'pending', 'cash', 'CASH-PENDING', 0.00, 0, NULL, NULL, NULL, '', '2025-12-22 14:43:35', '2025-12-22 14:43:35'),
(34, 30, 966, 'GG6949B40AA1599', 4, 260.00, 0.00, 0, NULL, NULL, NULL, 'confirmed', 'paid', 'visa', 'VISA-XXXX3456', 0.00, 0, NULL, NULL, NULL, '', '2025-12-22 21:11:38', '2025-12-22 21:11:38');

-- --------------------------------------------------------

--
-- Table structure for table `contact_reports`
--

CREATE TABLE `contact_reports` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','replied','archived') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_reports`
--

INSERT INTO `contact_reports` (`id`, `name`, `email`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Mahmoud', 'mahmoud123@gmail.com', 'Regarding Tour Guide behaviour', 'Recently i want to the India&#039;s tour and the guide, shah roh khan was treating the customers in bad way', 'new', '2025-12-19 22:03:47', '2025-12-20 20:50:26');

-- --------------------------------------------------------

--
-- Table structure for table `fines`
--

CREATE TABLE `fines` (
  `id` int(11) NOT NULL,
  `tourist_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `booking_reference` varchar(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `original_price` decimal(10,2) NOT NULL,
  `status` enum('pending','paid') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_reference` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `paid_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fines`
--

INSERT INTO `fines` (`id`, `tourist_id`, `booking_id`, `booking_reference`, `amount`, `original_price`, `status`, `payment_method`, `payment_reference`, `created_at`, `paid_at`) VALUES
(1, 15, 31, 'GG6946C48CD1553', 120.00, 480.00, 'paid', 'visa', 'VISA-XXXX3456', '2025-12-20 18:15:34', '2025-12-20 18:39:11'),
(2, 15, 17, 'GG6945EB6C55C20', 30.00, 120.00, 'pending', NULL, NULL, '2025-12-20 19:00:12', NULL),
(3, 15, 28, 'GG6946257B8C8B8', 30.00, 120.00, 'pending', NULL, NULL, '2025-12-20 19:08:59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `otp_verifications`
--

CREATE TABLE `otp_verifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `purpose` varchar(50) NOT NULL,
  `reference_id` varchar(100) DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp_verifications`
--

INSERT INTO `otp_verifications` (`id`, `user_id`, `email`, `otp_code`, `purpose`, `reference_id`, `expires_at`, `verified`, `created_at`) VALUES
(1, 27, 'omarhossam11111@gmail.com', '604416', 'payment', NULL, '2025-12-20 00:58:34', 1, '2025-12-20 00:48:34'),
(2, 27, 'omarhossam11111@gmail.com', '703299', 'payment', NULL, '2025-12-20 00:59:25', 0, '2025-12-20 00:49:25'),
(3, 27, 'omarhossam11111@gmail.com', '888430', 'payment', NULL, '2025-12-20 01:03:41', 1, '2025-12-20 00:53:41'),
(4, 27, 'omarhossam11111@gmail.com', '522398', 'payment', NULL, '2025-12-20 01:07:53', 0, '2025-12-20 00:57:53'),
(5, 27, 'omarhossam11111@gmail.com', '423144', 'payment', NULL, '2025-12-20 01:11:11', 0, '2025-12-20 01:01:11');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`id`, `user_id`, `token`, `expires_at`, `created_at`) VALUES
(6, 2, 'cfe388bd600e7a38345208be114d299e9216634c5c1128d5d1adf696b02cef56', '2025-12-20 06:10:02', '2025-12-20 05:10:02');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `tourist_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tours`
--

CREATE TABLE `tours` (
  `id` int(11) NOT NULL,
  `guide_id` int(11) NOT NULL,
  `guide_bio` text DEFAULT NULL,
  `attraction_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_hours` int(11) NOT NULL,
  `max_participants` int(11) NOT NULL,
  `meeting_point` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','cancelled') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tours`
--

INSERT INTO `tours` (`id`, `guide_id`, `guide_bio`, `attraction_id`, `title`, `description`, `price`, `duration_hours`, `max_participants`, `meeting_point`, `category`, `image_url`, `status`, `created_at`, `updated_at`) VALUES
(9, 6, NULL, 1, 'Paris Evening Walk', 'Experience the magic of Paris at night with a guided walk through the City of Light. Discover hidden gems and iconic landmarks.', 400.00, 2, 12, 'Trocadéro Metro Station', 'Walking Tour', 'images/Louvre.jpg', 'active', '2025-10-14 20:13:40', '2025-12-20 19:02:47'),
(10, 7, NULL, 2, 'Ancient Rome Discovery', 'Explore the Colosseum and surrounding ancient Roman ruins with an expert guide. Uncover the secrets of the Roman Empire.', 65.00, 3, 15, 'Colosseum Main Entrance', 'Historical Tour', 'images/colosseum.jpg', 'active', '2025-10-14 20:13:40', '2025-12-20 17:45:30'),
(11, 7, NULL, 3, 'NYC Food Adventure', 'Taste the best of New York City through its diverse culinary scene. Sample authentic flavors from around the world.', 85.00, 4, 10, 'Union Square Park', 'Food Tour', 'images/NYC.png', 'active', '2025-10-14 20:13:40', '2025-10-14 20:15:51'),
(14, 9, NULL, 4, 'London Tower Bridge Experience', 'Discover the iconic Tower Bridge with a guided tour including the high-level walkways and Victorian engine rooms. Learn about the bridge\'s history and enjoy stunning views of the Thames.', 55.00, 2, 20, 'Tower Bridge Exhibition Entrance', 'Historical Tour', 'images/TowerBridge.jpg', 'active', '2025-12-18 14:47:05', '2025-12-18 15:02:36'),
(15, 5, NULL, 5, 'Sagrada Familia & Gaudí\'s Barcelona', 'Explore Antoni Gaudí\'s masterpiece, the Sagrada Familia, and discover the architectural genius behind this unfinished basilica. Includes skip-the-line access and expert commentary.', 75.00, 3, 15, 'Sagrada Familia Main Entrance', 'Cultural Tour', 'images/SargadaFamilia.jpg', 'active', '2025-12-18 14:47:05', '2025-12-18 14:47:05'),
(16, 17, 'Expert guide specializing in Middle Eastern history and archaeology. Fluent in English, Arabic, and French - perfect for exploring ancient Egyptian history and the wonders of the pharaohs.', NULL, 'The Grand Egyptian Museum', 'Explore the magnificent Grand Egyptian Museum, home to the world\'s largest collection of ancient Egyptian artifacts. Discover the treasures of Tutankhamun and experience the rich history of ancient Egypt with our expert guide.', 299.00, 4, 20, 'Grand Egyptian Museum Main Entrance', 'Museum Tour', 'images/egypt.webp', 'active', '2025-12-19 18:34:32', '2025-12-20 04:40:51'),
(17, 24, NULL, 7, 'Machu Picchu Adventure', 'Journey to the ancient Incan citadel of Machu Picchu, one of the New Seven Wonders of the World. Explore the mysterious ruins, learn about Incan culture, and enjoy breathtaking mountain views.', 120.00, 8, 15, 'Aguas Calientes Train Station', 'Historical Tour', 'images/MachuPicchu.jpeg', 'cancelled', '2025-12-19 18:34:32', '2025-12-19 19:03:45'),
(18, 23, NULL, 8, 'Taj Mahal Experience', 'Visit the iconic Taj Mahal, a symbol of eternal love and one of the most beautiful buildings in the world. Learn about Mughal architecture and the romantic story behind this UNESCO World Heritage site.', 249.00, 3, 20, 'Taj Mahal East Gate', 'Cultural Tour', 'images/TajMahal.jpg', 'active', '2025-12-19 18:34:32', '2025-12-20 04:08:37'),
(19, 20, NULL, 9, 'Great Wall of China', 'Walk along the magnificent Great Wall of China, one of the greatest architectural achievements in human history. Experience breathtaking views and learn about the wall\'s fascinating history spanning over 2,000 years.', 95.00, 5, 18, 'Badaling Great Wall Visitor Center', 'Historical Tour', 'images/GreatWallOfChina.jpg', 'active', '2025-12-19 18:34:32', '2025-12-19 18:39:07'),
(20, 18, NULL, 10, 'Christ the Redeemer', 'Visit the iconic Christ the Redeemer statue, one of the New Seven Wonders of the World. Enjoy panoramic views of Rio de Janeiro from the top of Corcovado Mountain and learn about this symbol of Brazilian culture.', 70.00, 3, 20, 'Corcovado Train Station', 'Cultural Tour', 'images/Christ.jpg', 'active', '2025-12-19 18:34:32', '2025-12-19 18:39:07'),
(21, 21, NULL, 11, 'Sydney Opera House', 'Explore the world-famous Sydney Opera House, an architectural masterpiece and UNESCO World Heritage site. Take a guided tour of the iconic building and learn about its design, history, and cultural significance.', 80.00, 2, 25, 'Sydney Opera House Main Entrance', 'Cultural Tour', 'images/OperaHouse.jpg', 'active', '2025-12-19 18:34:32', '2025-12-19 19:05:10'),
(22, 19, NULL, 12, 'Angkor Wat Temple Complex', 'Discover the magnificent Angkor Wat, the largest religious monument in the world. Explore the ancient Khmer temples, witness stunning sunrise views, and learn about the rich history of the Khmer Empire.', 90.00, 6, 15, 'Angkor Wat Main Entrance', 'Historical Tour', 'images/AngkorWat.jpg', 'active', '2025-12-19 18:34:32', '2025-12-19 18:39:07'),
(23, 17, 'Expert guide specializing in Middle Eastern history and archaeology. Fluent in English, Arabic, and French - perfect for Petra tours in Jordan.', 13, 'Petra - The Rose City', 'Explore the ancient city of Petra, carved into rose-red sandstone cliffs. Walk through the Siq, discover the Treasury, and learn about the Nabataean civilization that created this architectural wonder.', 100.00, 5, 18, 'Petra Visitor Center', 'Historical Tour', 'images/Petra.jpg', 'active', '2025-12-19 18:34:32', '2025-12-20 04:40:51'),
(24, 9, 'Expert guide specializing in historical sites and ancient monuments. Fluent in English, Arabic, and French - perfect for Stonehenge tours in the UK.', 14, 'Stonehenge Mystery Tour', 'Visit the mysterious Stonehenge, one of the world\'s most famous prehistoric monuments. Learn about the theories surrounding its construction and purpose, and experience the mystical atmosphere of this ancient site.', 65.00, 3, 20, 'Stonehenge Visitor Center', 'Historical Tour', 'images/Stonehenge.jpg', 'active', '2025-12-19 18:34:32', '2025-12-20 20:16:06'),
(25, 22, NULL, 15, 'Shibuya Crossway Experience', 'Experience the world\'s busiest pedestrian crossing in the heart of Tokyo. Watch thousands of people cross simultaneously and explore the vibrant Shibuya district with its shopping, dining, and entertainment.', 279.00, 2, 15, 'Hachiko Statue, Shibuya Station', 'City Tour', 'images/Shibuya.jpg', 'active', '2025-12-19 18:34:32', '2025-12-20 04:08:37'),
(26, 5, 'Expert guide specializing in Middle Eastern history and archaeology. Fluent in English, Arabic, and French - perfect for exploring ancient Egyptian history and the wonders of the pharaohs.', NULL, 'The Grand Egyptian Museum', 'Explore the magnificent Grand Egyptian Museum, home to the world\'s largest collection of ancient Egyptian artifacts. Discover the treasures of Tutankhamun and experience the rich history of ancient Egypt with our expert guide.', 85.00, 4, 20, 'Grand Egyptian Museum Main Entrance', 'Museum Tour', 'images/egypt.webp', 'cancelled', '2025-12-19 18:39:13', '2025-12-20 04:40:51'),
(27, 24, NULL, 7, 'Machu Picchu Adventure', 'Journey to the ancient Incan citadel of Machu Picchu, one of the New Seven Wonders of the World. Explore the mysterious ruins, learn about Incan culture, and enjoy breathtaking mountain views.', 120.00, 8, 15, 'Aguas Calientes Train Station', 'Historical Tour', 'images/MachuPicchu.jpeg', 'active', '2025-12-19 18:39:13', '2025-12-19 22:39:41'),
(28, 7, NULL, 8, 'Taj Mahal Experience', 'Visit the iconic Taj Mahal, a symbol of eternal love and one of the most beautiful buildings in the world. Learn about Mughal architecture and the romantic story behind this UNESCO World Heritage site.', 75.00, 3, 20, 'Taj Mahal East Gate', 'Cultural Tour', 'images/TajMahal.jpg', 'cancelled', '2025-12-19 18:39:13', '2025-12-19 19:04:48'),
(29, 8, NULL, 9, 'Great Wall of China', 'Walk along the magnificent Great Wall of China, one of the greatest architectural achievements in human history. Experience breathtaking views and learn about the wall\'s fascinating history spanning over 2,000 years.', 95.00, 5, 18, 'Badaling Great Wall Visitor Center', 'Historical Tour', 'images/GreatWallOfChina.jpg', 'cancelled', '2025-12-19 18:39:13', '2025-12-19 19:04:33'),
(30, 9, NULL, 10, 'Christ the Redeemer', 'Visit the iconic Christ the Redeemer statue, one of the New Seven Wonders of the World. Enjoy panoramic views of Rio de Janeiro from the top of Corcovado Mountain and learn about this symbol of Brazilian culture.', 70.00, 3, 20, 'Corcovado Train Station', 'Cultural Tour', 'images/Christ.jpg', 'cancelled', '2025-12-19 18:39:13', '2025-12-19 19:05:01'),
(31, 17, NULL, 11, 'Sydney Opera House', 'Explore the world-famous Sydney Opera House, an architectural masterpiece and UNESCO World Heritage site. Take a guided tour of the iconic building and learn about its design, history, and cultural significance.', 80.00, 2, 25, 'Sydney Opera House Main Entrance', 'Cultural Tour', 'images/OperaHouse.jpg', 'cancelled', '2025-12-19 18:39:13', '2025-12-19 19:05:06'),
(32, 18, NULL, 12, 'Angkor Wat Temple Complex', 'Discover the magnificent Angkor Wat, the largest religious monument in the world. Explore the ancient Khmer temples, witness stunning sunrise views, and learn about the rich history of the Khmer Empire.', 90.00, 6, 15, 'Angkor Wat Main Entrance', 'Historical Tour', 'images/AngkorWat.jpg', 'cancelled', '2025-12-19 18:39:13', '2025-12-19 19:04:24'),
(33, 19, 'Expert guide specializing in Middle Eastern history and archaeology. Fluent in English, Arabic, and French - perfect for Petra tours in Jordan.', 13, 'Petra - The Rose City', 'Explore the ancient city of Petra, carved into rose-red sandstone cliffs. Walk through the Siq, discover the Treasury, and learn about the Nabataean civilization that created this architectural wonder.', 100.00, 5, 18, 'Petra Visitor Center', 'Historical Tour', 'images/Petra.jpg', 'cancelled', '2025-12-19 18:39:13', '2025-12-20 04:40:51'),
(34, 20, 'Expert guide specializing in historical sites and ancient monuments. Fluent in English, Arabic, and French - perfect for Stonehenge tours in the UK.', 14, 'Stonehenge Mystery Tour', 'Visit the mysterious Stonehenge, one of the world\'s most famous prehistoric monuments. Learn about the theories surrounding its construction and purpose, and experience the mystical atmosphere of this ancient site.', 65.00, 3, 20, 'Stonehenge Visitor Center', 'Historical Tour', 'images/Stonehenge.jpg', 'cancelled', '2025-12-19 18:39:13', '2025-12-20 04:40:51'),
(35, 21, NULL, 15, 'Shibuya Crossway Experience', 'Experience the world\'s busiest pedestrian crossing in the heart of Tokyo. Watch thousands of people cross simultaneously and explore the vibrant Shibuya district with its shopping, dining, and entertainment.', 55.00, 2, 15, 'Hachiko Statue, Shibuya Station', 'City Tour', 'images/Shibuya.jpg', 'cancelled', '2025-12-19 18:39:13', '2025-12-19 19:05:36');

-- --------------------------------------------------------

--
-- Table structure for table `tour_schedules`
--

CREATE TABLE `tour_schedules` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `tour_date` date NOT NULL,
  `tour_time` time NOT NULL,
  `available_spots` int(11) NOT NULL,
  `status` enum('available','full','cancelled') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tour_schedules`
--

INSERT INTO `tour_schedules` (`id`, `tour_id`, `tour_date`, `tour_time`, `available_spots`, `status`, `created_at`) VALUES
(90, 9, '2025-10-15', '10:00:00', 12, 'available', '2025-10-14 20:13:40'),
(91, 9, '2025-10-16', '14:00:00', 12, 'available', '2025-10-14 20:13:40'),
(92, 9, '2025-10-17', '10:00:00', 10, 'available', '2025-10-14 20:13:40'),
(93, 10, '2025-10-15', '10:00:00', 15, 'available', '2025-10-14 20:13:40'),
(94, 10, '2025-10-16', '14:00:00', 15, 'available', '2025-10-14 20:13:40'),
(95, 10, '2025-10-17', '10:00:00', 15, 'available', '2025-10-14 20:13:40'),
(96, 11, '2025-10-15', '10:00:00', 10, 'available', '2025-10-14 20:13:40'),
(97, 11, '2025-10-16', '14:00:00', 10, 'available', '2025-10-14 20:13:40'),
(98, 11, '2025-10-17', '10:00:00', 10, 'available', '2025-10-14 20:13:40'),
(115, 9, '2025-12-14', '10:00:00', 11, 'available', '2025-12-13 22:50:27'),
(116, 9, '2025-12-15', '14:00:00', 9, 'available', '2025-12-13 22:50:27'),
(117, 9, '2025-12-16', '10:00:00', 12, 'available', '2025-12-13 22:50:27'),
(947, 9, '2025-12-18', '10:00:00', 12, 'available', '2025-12-17 18:27:17'),
(948, 9, '2025-12-22', '10:00:00', 12, 'available', '2025-12-17 18:27:17'),
(949, 9, '2025-12-26', '10:00:00', 12, 'available', '2025-12-17 18:27:17'),
(950, 9, '2025-12-30', '10:00:00', 12, 'available', '2025-12-17 18:27:17'),
(951, 9, '2026-01-03', '10:00:00', 12, 'available', '2025-12-17 18:27:17'),
(952, 9, '2026-01-07', '10:00:00', 12, 'available', '2025-12-17 18:27:17'),
(953, 9, '2026-01-11', '10:00:00', 12, 'available', '2025-12-17 18:27:17'),
(954, 9, '2026-01-15', '10:00:00', 12, 'available', '2025-12-17 18:27:17'),
(955, 9, '2026-01-19', '10:00:00', 12, 'available', '2025-12-17 18:27:17'),
(956, 9, '2026-01-23', '10:00:00', 12, 'available', '2025-12-17 18:27:17'),
(957, 9, '2026-01-27', '10:00:00', 12, 'available', '2025-12-17 18:27:17'),
(958, 9, '2026-01-31', '10:00:00', 11, 'available', '2025-12-17 18:27:17'),
(959, 9, '2026-02-04', '10:00:00', 12, 'available', '2025-12-17 18:27:17'),
(960, 9, '2026-02-08', '10:00:00', 12, 'available', '2025-12-17 18:27:17'),
(961, 9, '2026-02-12', '10:00:00', 12, 'available', '2025-12-17 18:27:17'),
(962, 9, '2026-02-16', '10:00:00', 12, 'available', '2025-12-17 18:27:17'),
(963, 9, '2026-02-20', '10:00:00', 12, 'available', '2025-12-17 18:27:17'),
(964, 9, '2026-02-24', '10:00:00', 12, 'available', '2025-12-17 18:27:17'),
(965, 10, '2025-12-18', '10:00:00', 14, 'available', '2025-12-17 18:27:17'),
(966, 10, '2025-12-22', '10:00:00', 10, 'available', '2025-12-17 18:27:17'),
(967, 10, '2025-12-26', '10:00:00', 15, 'available', '2025-12-17 18:27:17'),
(968, 10, '2025-12-30', '10:00:00', 15, 'available', '2025-12-17 18:27:17'),
(969, 10, '2026-01-03', '10:00:00', 15, 'available', '2025-12-17 18:27:17'),
(970, 10, '2026-01-07', '10:00:00', 15, 'available', '2025-12-17 18:27:17'),
(971, 10, '2026-01-11', '10:00:00', 15, 'available', '2025-12-17 18:27:17'),
(972, 10, '2026-01-15', '10:00:00', 15, 'available', '2025-12-17 18:27:17'),
(973, 10, '2026-01-19', '10:00:00', 15, 'available', '2025-12-17 18:27:17'),
(974, 10, '2026-01-23', '10:00:00', 15, 'available', '2025-12-17 18:27:17'),
(975, 10, '2026-01-27', '10:00:00', 15, 'available', '2025-12-17 18:27:17'),
(976, 10, '2026-01-31', '10:00:00', 15, 'available', '2025-12-17 18:27:17'),
(977, 10, '2026-02-04', '10:00:00', 15, 'available', '2025-12-17 18:27:17'),
(978, 10, '2026-02-08', '10:00:00', 15, 'available', '2025-12-17 18:27:17'),
(979, 10, '2026-02-12', '10:00:00', 15, 'available', '2025-12-17 18:27:17'),
(980, 10, '2026-02-16', '10:00:00', 15, 'available', '2025-12-17 18:27:17'),
(981, 10, '2026-02-20', '10:00:00', 15, 'available', '2025-12-17 18:27:17'),
(982, 10, '2026-02-24', '10:00:00', 15, 'available', '2025-12-17 18:27:17'),
(983, 11, '2025-12-18', '10:00:00', 10, 'available', '2025-12-17 18:27:17'),
(984, 11, '2025-12-22', '10:00:00', 10, 'available', '2025-12-17 18:27:17'),
(985, 11, '2025-12-26', '10:00:00', 10, 'available', '2025-12-17 18:27:17'),
(986, 11, '2025-12-30', '10:00:00', 10, 'available', '2025-12-17 18:27:17'),
(987, 11, '2026-01-03', '10:00:00', 10, 'available', '2025-12-17 18:27:17'),
(988, 11, '2026-01-07', '10:00:00', 10, 'available', '2025-12-17 18:27:17'),
(989, 11, '2026-01-11', '10:00:00', 10, 'available', '2025-12-17 18:27:17'),
(990, 11, '2026-01-15', '10:00:00', 10, 'available', '2025-12-17 18:27:17'),
(991, 11, '2026-01-19', '10:00:00', 10, 'available', '2025-12-17 18:27:17'),
(992, 11, '2026-01-23', '10:00:00', 10, 'available', '2025-12-17 18:27:17'),
(993, 11, '2026-01-27', '10:00:00', 10, 'available', '2025-12-17 18:27:17'),
(994, 11, '2026-01-31', '10:00:00', 10, 'available', '2025-12-17 18:27:17'),
(995, 11, '2026-02-04', '10:00:00', 10, 'available', '2025-12-17 18:27:17'),
(996, 11, '2026-02-08', '10:00:00', 10, 'available', '2025-12-17 18:27:17'),
(997, 11, '2026-02-12', '10:00:00', 10, 'available', '2025-12-17 18:27:17'),
(998, 11, '2026-02-16', '10:00:00', 10, 'available', '2025-12-17 18:27:17'),
(999, 11, '2026-02-20', '10:00:00', 10, 'available', '2025-12-17 18:27:17'),
(1000, 11, '2026-02-24', '10:00:00', 10, 'available', '2025-12-17 18:27:17'),
(1001, 14, '2025-12-19', '10:00:00', 20, 'available', '2025-12-18 14:47:05'),
(1002, 14, '2025-12-20', '14:00:00', 20, 'available', '2025-12-18 14:47:05'),
(1003, 14, '2025-12-21', '10:00:00', 19, 'available', '2025-12-18 14:47:05'),
(1004, 14, '2025-12-22', '14:00:00', 20, 'available', '2025-12-18 14:47:05'),
(1005, 14, '2025-12-23', '10:00:00', 20, 'available', '2025-12-18 14:47:05'),
(1006, 15, '2025-12-19', '10:00:00', 15, 'available', '2025-12-18 14:47:05'),
(1007, 15, '2025-12-20', '14:00:00', 15, 'available', '2025-12-18 14:47:05'),
(1008, 15, '2025-12-21', '10:00:00', 14, 'available', '2025-12-18 14:47:05'),
(1009, 15, '2025-12-22', '14:00:00', 15, 'available', '2025-12-18 14:47:05'),
(1010, 15, '2025-12-23', '10:00:00', 15, 'available', '2025-12-18 14:47:05'),
(1011, 16, '2025-12-20', '10:00:00', 20, 'available', '2025-12-19 18:34:32'),
(1012, 16, '2025-12-21', '14:00:00', 18, 'available', '2025-12-19 18:34:32'),
(1013, 16, '2025-12-22', '10:00:00', 20, 'available', '2025-12-19 18:34:32'),
(1014, 16, '2025-12-23', '14:00:00', 20, 'available', '2025-12-19 18:34:32'),
(1015, 16, '2025-12-24', '10:00:00', 19, 'available', '2025-12-19 18:34:32'),
(1016, 17, '2025-12-20', '10:00:00', 15, 'available', '2025-12-19 18:34:32'),
(1017, 17, '2025-12-21', '14:00:00', 15, 'available', '2025-12-19 18:34:32'),
(1018, 17, '2025-12-22', '10:00:00', 15, 'available', '2025-12-19 18:34:32'),
(1019, 17, '2025-12-23', '14:00:00', 15, 'available', '2025-12-19 18:34:32'),
(1020, 17, '2025-12-24', '10:00:00', 15, 'available', '2025-12-19 18:34:32'),
(1021, 18, '2025-12-20', '10:00:00', 20, 'available', '2025-12-19 18:34:32'),
(1022, 18, '2025-12-21', '14:00:00', 20, 'available', '2025-12-19 18:34:32'),
(1023, 18, '2025-12-22', '10:00:00', 19, 'available', '2025-12-19 18:34:32'),
(1024, 18, '2025-12-23', '14:00:00', 20, 'available', '2025-12-19 18:34:32'),
(1025, 18, '2025-12-24', '10:00:00', 20, 'available', '2025-12-19 18:34:32'),
(1026, 19, '2025-12-20', '10:00:00', 18, 'available', '2025-12-19 18:34:32'),
(1027, 19, '2025-12-21', '14:00:00', 18, 'available', '2025-12-19 18:34:32'),
(1028, 19, '2025-12-22', '10:00:00', 18, 'available', '2025-12-19 18:34:32'),
(1029, 19, '2025-12-23', '14:00:00', 18, 'available', '2025-12-19 18:34:32'),
(1030, 19, '2025-12-24', '10:00:00', 18, 'available', '2025-12-19 18:34:32'),
(1031, 20, '2025-12-20', '10:00:00', 20, 'available', '2025-12-19 18:34:32'),
(1032, 20, '2025-12-21', '14:00:00', 20, 'available', '2025-12-19 18:34:32'),
(1033, 20, '2025-12-22', '10:00:00', 20, 'available', '2025-12-19 18:34:32'),
(1034, 20, '2025-12-23', '14:00:00', 20, 'available', '2025-12-19 18:34:32'),
(1035, 20, '2025-12-24', '10:00:00', 20, 'available', '2025-12-19 18:34:32'),
(1036, 21, '2025-12-20', '10:00:00', 25, 'available', '2025-12-19 18:34:32'),
(1037, 21, '2025-12-21', '14:00:00', 25, 'available', '2025-12-19 18:34:32'),
(1038, 21, '2025-12-22', '10:00:00', 25, 'available', '2025-12-19 18:34:32'),
(1039, 21, '2025-12-23', '14:00:00', 25, 'available', '2025-12-19 18:34:32'),
(1040, 21, '2025-12-24', '10:00:00', 25, 'available', '2025-12-19 18:34:32'),
(1041, 22, '2025-12-20', '10:00:00', 15, 'available', '2025-12-19 18:34:32'),
(1042, 22, '2025-12-21', '14:00:00', 15, 'available', '2025-12-19 18:34:32'),
(1043, 22, '2025-12-22', '10:00:00', 15, 'available', '2025-12-19 18:34:32'),
(1044, 22, '2025-12-23', '14:00:00', 15, 'available', '2025-12-19 18:34:32'),
(1045, 22, '2025-12-24', '10:00:00', 15, 'available', '2025-12-19 18:34:32'),
(1046, 23, '2025-12-20', '10:00:00', 18, 'available', '2025-12-19 18:34:32'),
(1047, 23, '2025-12-21', '14:00:00', 18, 'available', '2025-12-19 18:34:32'),
(1048, 23, '2025-12-22', '10:00:00', 18, 'available', '2025-12-19 18:34:32'),
(1049, 23, '2025-12-23', '14:00:00', 18, 'available', '2025-12-19 18:34:32'),
(1050, 23, '2025-12-24', '10:00:00', 18, 'available', '2025-12-19 18:34:32'),
(1051, 24, '2025-12-20', '10:00:00', 20, 'available', '2025-12-19 18:34:32'),
(1052, 24, '2025-12-21', '14:00:00', 20, 'available', '2025-12-19 18:34:32'),
(1053, 24, '2025-12-22', '10:00:00', 20, 'available', '2025-12-19 18:34:32'),
(1054, 24, '2025-12-23', '14:00:00', 20, 'available', '2025-12-19 18:34:32'),
(1055, 24, '2025-12-24', '10:00:00', 20, 'available', '2025-12-19 18:34:32'),
(1056, 25, '2025-12-20', '10:00:00', 15, 'available', '2025-12-19 18:34:32'),
(1057, 25, '2025-12-21', '14:00:00', 15, 'available', '2025-12-19 18:34:32'),
(1058, 25, '2025-12-22', '10:00:00', 15, 'available', '2025-12-19 18:34:32'),
(1059, 25, '2025-12-23', '14:00:00', 15, 'available', '2025-12-19 18:34:32'),
(1060, 25, '2025-12-24', '10:00:00', 15, 'available', '2025-12-19 18:34:32'),
(1061, 26, '2025-12-20', '10:00:00', 20, 'available', '2025-12-19 18:39:13'),
(1062, 26, '2025-12-21', '14:00:00', 20, 'available', '2025-12-19 18:39:13'),
(1063, 26, '2025-12-22', '10:00:00', 20, 'available', '2025-12-19 18:39:13'),
(1064, 26, '2025-12-23', '14:00:00', 20, 'available', '2025-12-19 18:39:13'),
(1065, 26, '2025-12-24', '10:00:00', 20, 'available', '2025-12-19 18:39:13'),
(1066, 27, '2025-12-20', '10:00:00', 16, 'available', '2025-12-19 18:39:13'),
(1067, 27, '2025-12-21', '14:00:00', 15, 'available', '2025-12-19 18:39:13'),
(1068, 27, '2025-12-22', '10:00:00', 14, 'available', '2025-12-19 18:39:13'),
(1069, 27, '2025-12-23', '14:00:00', 15, 'available', '2025-12-19 18:39:13'),
(1070, 27, '2025-12-24', '10:00:00', 15, 'available', '2025-12-19 18:39:13'),
(1071, 28, '2025-12-20', '10:00:00', 20, 'available', '2025-12-19 18:39:13'),
(1072, 28, '2025-12-21', '14:00:00', 20, 'available', '2025-12-19 18:39:13'),
(1073, 28, '2025-12-22', '10:00:00', 20, 'available', '2025-12-19 18:39:13'),
(1074, 28, '2025-12-23', '14:00:00', 20, 'available', '2025-12-19 18:39:13'),
(1075, 28, '2025-12-24', '10:00:00', 20, 'available', '2025-12-19 18:39:13'),
(1076, 29, '2025-12-20', '10:00:00', 18, 'available', '2025-12-19 18:39:13'),
(1077, 29, '2025-12-21', '14:00:00', 18, 'available', '2025-12-19 18:39:13'),
(1078, 29, '2025-12-22', '10:00:00', 18, 'available', '2025-12-19 18:39:13'),
(1079, 29, '2025-12-23', '14:00:00', 18, 'available', '2025-12-19 18:39:13'),
(1080, 29, '2025-12-24', '10:00:00', 18, 'available', '2025-12-19 18:39:13'),
(1081, 30, '2025-12-20', '10:00:00', 20, 'available', '2025-12-19 18:39:13'),
(1082, 30, '2025-12-21', '14:00:00', 20, 'available', '2025-12-19 18:39:13'),
(1083, 30, '2025-12-22', '10:00:00', 20, 'available', '2025-12-19 18:39:13'),
(1084, 30, '2025-12-23', '14:00:00', 20, 'available', '2025-12-19 18:39:13'),
(1085, 30, '2025-12-24', '10:00:00', 20, 'available', '2025-12-19 18:39:13'),
(1086, 31, '2025-12-20', '10:00:00', 25, 'available', '2025-12-19 18:39:13'),
(1087, 31, '2025-12-21', '14:00:00', 25, 'available', '2025-12-19 18:39:13'),
(1088, 31, '2025-12-22', '10:00:00', 25, 'available', '2025-12-19 18:39:13'),
(1089, 31, '2025-12-23', '14:00:00', 25, 'available', '2025-12-19 18:39:13'),
(1090, 31, '2025-12-24', '10:00:00', 25, 'available', '2025-12-19 18:39:13'),
(1091, 32, '2025-12-20', '10:00:00', 15, 'available', '2025-12-19 18:39:13'),
(1092, 32, '2025-12-21', '14:00:00', 15, 'available', '2025-12-19 18:39:13'),
(1093, 32, '2025-12-22', '10:00:00', 15, 'available', '2025-12-19 18:39:13'),
(1094, 32, '2025-12-23', '14:00:00', 15, 'available', '2025-12-19 18:39:13'),
(1095, 32, '2025-12-24', '10:00:00', 15, 'available', '2025-12-19 18:39:13'),
(1096, 33, '2025-12-20', '10:00:00', 18, 'available', '2025-12-19 18:39:13'),
(1097, 33, '2025-12-21', '14:00:00', 18, 'available', '2025-12-19 18:39:13'),
(1098, 33, '2025-12-22', '10:00:00', 18, 'available', '2025-12-19 18:39:13'),
(1099, 33, '2025-12-23', '14:00:00', 18, 'available', '2025-12-19 18:39:13'),
(1100, 33, '2025-12-24', '10:00:00', 18, 'available', '2025-12-19 18:39:13'),
(1101, 34, '2025-12-20', '10:00:00', 20, 'available', '2025-12-19 18:39:13'),
(1102, 34, '2025-12-21', '14:00:00', 20, 'available', '2025-12-19 18:39:13'),
(1103, 34, '2025-12-22', '10:00:00', 20, 'available', '2025-12-19 18:39:13'),
(1104, 34, '2025-12-23', '14:00:00', 20, 'available', '2025-12-19 18:39:13'),
(1105, 34, '2025-12-24', '10:00:00', 20, 'available', '2025-12-19 18:39:13'),
(1106, 35, '2025-12-20', '10:00:00', 15, 'available', '2025-12-19 18:39:13'),
(1107, 35, '2025-12-21', '14:00:00', 15, 'available', '2025-12-19 18:39:13'),
(1108, 35, '2025-12-22', '10:00:00', 15, 'available', '2025-12-19 18:39:13'),
(1109, 35, '2025-12-23', '14:00:00', 15, 'available', '2025-12-19 18:39:13'),
(1110, 35, '2025-12-24', '10:00:00', 15, 'available', '2025-12-19 18:39:13'),
(1111, 27, '2025-12-27', '21:30:00', 14, 'available', '2025-12-20 00:26:53'),
(1112, 27, '2025-12-31', '10:30:00', 15, 'available', '2025-12-20 06:21:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `role` enum('tourist','guide','admin') NOT NULL DEFAULT 'tourist',
  `phone` varchar(20) DEFAULT NULL,
  `national_id` varchar(50) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `criminal_records` tinyint(1) DEFAULT 0,
  `application_status` enum('pending','under_review','approved','rejected') DEFAULT 'pending',
  `application_notes` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `languages` varchar(255) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT 0,
  `status` enum('active','suspended','pending') DEFAULT 'active',
  `suspend_until` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `password_reset_token` varchar(64) DEFAULT NULL,
  `password_reset_expires` datetime DEFAULT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `role`, `phone`, `national_id`, `date_of_birth`, `address`, `criminal_records`, `application_status`, `application_notes`, `profile_image`, `bio`, `languages`, `verified`, `status`, `suspend_until`, `created_at`, `updated_at`, `password_reset_token`, `password_reset_expires`, `reset_token`, `reset_token_expires`) VALUES
(2, 'mahmoudashraf1@gmail.com', '$2y$10$WrkOYjpGA.VMeCsr8aSFBOlfthN3K8rZkO0G1mxNm5G5Ac82Lp0OW', 'Mahmoud', 'Ashraf', 'tourist', '01033560036', NULL, NULL, NULL, 0, 'pending', NULL, NULL, NULL, NULL, 0, 'active', NULL, '2025-10-14 17:49:35', '2025-10-14 17:49:35', NULL, NULL, NULL, NULL),
(3, 'admin@globego.com', '$2y$10$QpDe6ab3IfuCeqT/LYXC/O5NSoVxlPyB4AAeWhurjMEofkJ5iO2VW', 'Admin', 'User', 'admin', NULL, NULL, NULL, NULL, 0, 'pending', NULL, NULL, NULL, NULL, 1, 'active', NULL, '2025-10-14 18:42:24', '2025-10-14 18:42:24', NULL, NULL, NULL, NULL),
(5, 'alex.martinez@globego.com', '$2y$10$tRTUyoii441XvaXLOQMvo.vNoPW3ZQEuTIuAgUDJ3V9qYTU4bJXiG', 'Alex', 'Martinez', 'guide', '', NULL, NULL, NULL, 0, 'approved', NULL, 'images/TourGuide1.png', 'Alex Martinez\r\nA historian and archaeologist specializing in the Middle East. Multilingual guide for the Grand Egyptian Museum and Gaudis Barcelona.', 'English, Spanish', 1, 'active', NULL, '2025-10-14 20:13:40', '2025-12-20 20:08:02', NULL, NULL, NULL, NULL),
(6, 'sarah.johnson@globego.com', '$2y$10$tRTUyoii441XvaXLOQMvo.vNoPW3ZQEuTIuAgUDJ3V9qYTU4bJXiG', 'Sam', 'Johnson', 'guide', NULL, NULL, NULL, NULL, 0, 'approved', NULL, 'images/TourGuide2.jpg', 'Professional guide with expertise in art, architecture, and local cuisine.', 'English, French', 1, 'active', NULL, '2025-10-14 20:13:40', '2025-12-19 17:41:51', NULL, NULL, NULL, NULL),
(7, 'michael.brown@globego.com', '$2y$10$tRTUyoii441XvaXLOQMvo.vNoPW3ZQEuTIuAgUDJ3V9qYTU4bJXiG', 'Michael', 'Brown', 'guide', NULL, NULL, NULL, NULL, 0, 'approved', NULL, 'images/TourGuide3.jpg', 'Local expert passionate about sharing hidden gems and authentic experiences.', 'English, Italian', 1, 'active', NULL, '2025-10-14 20:13:40', '2025-12-19 17:41:53', NULL, NULL, NULL, NULL),
(8, 'emma.wilson@globego.com', '$2y$10$tRTUyoii441XvaXLOQMvo.vNoPW3ZQEuTIuAgUDJ3V9qYTU4bJXiG', 'Emma', 'Wilson', 'guide', NULL, NULL, NULL, NULL, 0, 'approved', NULL, 'images/TourGuide4.jpg', 'Certified guide with deep knowledge of local history and traditions.', 'English, German', 1, 'active', NULL, '2025-10-14 20:13:40', '2025-12-19 17:41:55', NULL, NULL, NULL, NULL),
(9, 'megan.fox@globego.com', '$2y$10$tRTUyoii441XvaXLOQMvo.vNoPW3ZQEuTIuAgUDJ3V9qYTU4bJXiG', 'Megan', 'Fox', 'guide', '', NULL, NULL, NULL, 0, 'approved', NULL, 'images/TourGuide6.jpg', 'Megan Fox is a history and culture guide specializing in tours of Christ the Redeemer and London Tower Bridge. She is an expert in British history and ancient monuments, including Stonehenge. Fluent in English.', 'English, Portuguese', 1, 'active', NULL, '2025-10-14 20:13:40', '2025-12-20 20:22:33', NULL, NULL, NULL, NULL),
(10, 'taftaf69@gmail.com', '$2y$10$uvi2uxzQDMRZuygnj3Gw8OF/UJGzm.P/BeD/F1bSW8lbA5Dr14ZyK', 'Taftaf', 'Mahmoud', 'tourist', '01033560036', NULL, NULL, NULL, 0, 'pending', NULL, NULL, NULL, NULL, 0, 'active', NULL, '2025-12-13 22:49:44', '2025-12-19 17:41:42', NULL, NULL, NULL, NULL),
(11, 'Joe123@gmail.com', '$2y$10$lOLLJWKWp.BDr.JcNaXGpO23JQ7rd3cEfR1B.hy4fBuEKGZfTj6fS', 'EL AB', 'JOE', 'tourist', '01033560036', NULL, NULL, NULL, 0, 'pending', NULL, NULL, NULL, NULL, 0, 'active', NULL, '2025-12-13 22:58:45', '2025-12-19 17:41:40', NULL, NULL, NULL, NULL),
(12, 'karim123@gmail.com', '$2y$10$H12BpzAH685KSyr9.eArWeC0i4C5u5Ulh/KJXAo05DQd7wFXOFg/e', 'Karim', 'Sherine', 'tourist', '01033560036', NULL, NULL, NULL, 0, 'pending', NULL, NULL, NULL, NULL, 0, 'active', NULL, '2025-12-15 16:31:02', '2025-12-15 16:31:02', NULL, NULL, NULL, NULL),
(13, 'mahmoud123@gmail.com', '$2y$10$mUwEpW6UnFmXY5APC1muLu63O0rUmvUB14jVdX2rKE2trWfha9t/a', 'mahmoud', 'ashraf', 'tourist', '+201069878637', NULL, NULL, NULL, 0, 'pending', NULL, NULL, NULL, NULL, 0, 'active', NULL, '2025-12-17 11:49:56', '2025-12-17 11:49:56', NULL, NULL, NULL, NULL),
(15, 'mahmoudalalfi7@gmail.com', '$2y$10$REbFqVgsPVUp9Vzk.6517uBI6cGVgU/lJw2/9TVO7Oldc4/Q7CPGu', 'Mahmoud', 'Ashraf', 'tourist', '01033560036', NULL, NULL, NULL, 0, 'pending', NULL, '', '', '', 0, 'active', NULL, '2025-12-18 15:07:13', '2025-12-22 21:05:59', NULL, NULL, '31c978c99dbeecb82defabf3483c53cca0483623ed8f533da0dfa6583db163d2', '2025-12-23 21:05:59'),
(17, 'mina@globego.com', '$2y$10$uTAouLtNpEkP1ll2unbtNuYF1UkVwdFzH/eUpx2JvhO/4ueg311IS', 'Mina', 'Hassan', 'guide', '', NULL, NULL, NULL, 0, 'approved', NULL, 'images/Mina.jpg', 'A Middle Eastern history and archaeology specialist. His passion for ancient wonders makes him the perfect guide for exploring the treasures of the Grand Egyptian Museum and the secrets of Petra’s Rose City.', 'English, Arabic, French', 1, 'active', NULL, '2025-12-19 18:34:19', '2025-12-20 20:11:07', NULL, NULL, NULL, NULL),
(18, 'lila@globego.com', '$2y$10$MrH46G3oPizOUPJxOzB1iuMYmAXUqzRdSRWeojRG88nkt9FqUpxeO', 'Lila', 'Silva', 'guide', '', NULL, NULL, NULL, 0, 'approved', NULL, 'images/Lela.jpg', 'A passionate Brazilian local guide with deep expertise in the culture and landmarks of Rio de Janeiro. Her knowledge and energy bring every tour to life, making visits to Christ the Redeemer both personal and memorable.', 'English, Portuguese', 1, 'active', NULL, '2025-12-19 18:34:20', '2025-12-20 20:11:55', NULL, NULL, NULL, NULL),
(19, 'kiara@globego.com', '$2y$10$mDUxPZHkCznIpdpY0dM4L.aZmjyU.AWwph3Gv7cfJtcPbLsYO9IvK', 'Kiara', 'Sok', 'guide', NULL, NULL, NULL, NULL, 0, 'approved', NULL, 'images/kiara.jpg', 'Cambodian heritage expert and licensed tour guide. Fluent in English and Khmer, specializing in Angkor Wat history.', 'English, Khmer', 1, 'active', NULL, '2025-12-19 18:34:20', '2025-12-19 18:34:20', NULL, NULL, NULL, NULL),
(20, 'li@globego.com', '$2y$10$lNfACsKrV.xUIliNeUpm3OM9SlbR0hygzGilRkxcDCtV.Wy6A3sM6', 'Li', 'Wei', 'guide', NULL, NULL, NULL, NULL, 0, 'approved', NULL, 'images/Li (李).jpg', 'Professional guide with extensive knowledge of Chinese history and the Great Wall. Speaks English and Mandarin.', 'English, Mandarin', 1, 'active', NULL, '2025-12-19 18:34:20', '2025-12-19 18:34:20', NULL, NULL, NULL, NULL),
(21, 'barbara@globego.com', '$2y$10$Xh8cLU0mvs9nPMZR8dOxPu9J9UorpHth.uFC3lQkkG1MuGVZUV9om', 'Barbara', 'Martin', 'guide', NULL, NULL, NULL, NULL, 0, 'approved', NULL, 'images/Barbara.jpg', 'Sydney local guide with expertise in architecture and performing arts. Fluent in English and French.', 'English, French', 1, 'active', NULL, '2025-12-19 18:34:20', '2025-12-19 18:34:20', NULL, NULL, NULL, NULL),
(22, 'yuki@globego.com', '$2y$10$yL8.jTgQw3NQmkUue5faP.JPVS5sDmJsnvamdw24gzAKRo8Yn.yhC', 'Yuki', 'Tanaka', 'guide', NULL, NULL, NULL, NULL, 0, 'approved', NULL, 'images/Yuki (雪).webp', 'Tokyo native guide specializing in Shibuya district and Japanese culture. Speaks English and Japanese fluently.', 'English, Japanese', 1, 'active', NULL, '2025-12-19 18:34:20', '2025-12-19 18:34:20', NULL, NULL, NULL, NULL),
(23, 'shahrukh.khan@globego.com', '$2y$10$REYpexCWq4Imx4tU21/ak.AJOiQuhTczZSsnYdMLyylJ2XQkeVM7.', 'Shah Rukh', 'Khan', 'guide', NULL, NULL, NULL, NULL, 0, 'approved', NULL, 'images/shah-rukh-khan.webp', 'Heritage guide with deep knowledge of Mughal architecture and Indian history. Fluent in English, Hindi, and Urdu - perfect for Indian tours.', 'English, Hindi, Urdu', 1, 'active', NULL, '2025-12-19 18:34:20', '2025-12-19 18:39:07', NULL, NULL, NULL, NULL),
(24, 'carlos@globego.com', '$2y$10$kWr7fj9vu3rRqsNUjHwvpO/OU4WpnXURURlVbTPG7ZWcSyfjyfl/2', 'Carlos', 'Rodriguez', 'guide', '', NULL, NULL, NULL, 0, 'approved', NULL, 'images/TourGuide3.jpg', 'A Peruvian guide with deep expertise in Incan history and Andean culture. His passion for Machu Picchu shines through every historical tour', 'English, Spanish', 1, 'active', NULL, '2025-12-19 18:39:07', '2025-12-20 20:09:22', NULL, NULL, NULL, NULL),
(25, 'mahmoudalalfi55@gmail.com', '$2y$10$Yq5Gz1XomoZTnZHWgbPfr.MZDHjNPfWD1p5aeD2TjHbTjCRc8GK8m', 'mahmoud', 'Ashraf', 'tourist', '01033560036', NULL, NULL, NULL, 0, 'pending', NULL, NULL, NULL, NULL, 0, 'active', NULL, '2025-12-19 22:54:40', '2025-12-19 22:54:40', NULL, NULL, NULL, NULL),
(28, 'mahmoudalafi55@gmail.com', '$2y$10$X8E9qECrc/yjPsZlGOpgpuQSif5DyoX5iKb9ye08iQojf/gD/suyW', 'Alice', 'Wonderland', 'guide', '01033560036', '0100988221112', '2001-12-02', 'Britian', 1, 'approved', NULL, NULL, NULL, NULL, 1, 'active', NULL, '2025-12-20 19:17:33', '2025-12-20 19:17:53', NULL, NULL, NULL, NULL),
(30, 'omarhossam11111@gmail.com', '$2y$10$DTp7vHqTBf0c.aXSkfZ1yOA/AQZMz.8bnxhlbdnte8thq88IWaK36', 'Omar', 'Hossam', 'tourist', '+200102601991', NULL, NULL, NULL, 0, 'pending', NULL, NULL, NULL, NULL, 0, 'active', NULL, '2025-12-22 21:09:14', '2025-12-22 21:13:51', NULL, NULL, '509a81f48b7f1cb2c6c4748d5eef06917dfa6ec1cf50077efbbca910e1e0c625', '2025-12-23 21:13:51'),
(31, 'karimselim@gmail.com', '$2y$10$284Rwh1q3DxOsUTh3KnOU.aUI0KhGO.BzLlq8dyeYnj7liuQQfaAO', 'Karim', 'Selim', 'guide', '+2001033560036', '30507040131100', '2005-02-02', '20, 3, Cairo, Cairo, 10001, Egypt', 1, 'rejected', NULL, NULL, NULL, NULL, 0, 'suspended', NULL, '2025-12-22 21:22:00', '2025-12-22 21:25:21', NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attractions`
--
ALTER TABLE `attractions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_reference` (`booking_reference`),
  ADD KEY `tourist_id` (`tourist_id`),
  ADD KEY `tour_schedule_id` (`tour_schedule_id`);

--
-- Indexes for table `contact_reports`
--
ALTER TABLE `contact_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `fines`
--
ALTER TABLE `fines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tourist_id` (`tourist_id`),
  ADD KEY `idx_booking_id` (`booking_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `otp_verifications`
--
ALTER TABLE `otp_verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email_otp` (`email`,`otp_code`),
  ADD KEY `idx_user_purpose` (`user_id`,`purpose`),
  ADD KEY `idx_expires` (`expires_at`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `idx_token` (`token`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_expires_at` (`expires_at`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_booking_review` (`booking_id`),
  ADD KEY `tourist_id` (`tourist_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guide_id` (`guide_id`),
  ADD KEY `attraction_id` (`attraction_id`);

--
-- Indexes for table `tour_schedules`
--
ALTER TABLE `tour_schedules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_tour_datetime` (`tour_id`,`tour_date`,`tour_time`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_suspend_until` (`suspend_until`),
  ADD KEY `idx_application_status` (`application_status`),
  ADD KEY `idx_password_reset_token` (`password_reset_token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attractions`
--
ALTER TABLE `attractions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `contact_reports`
--
ALTER TABLE `contact_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fines`
--
ALTER TABLE `fines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `otp_verifications`
--
ALTER TABLE `otp_verifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tours`
--
ALTER TABLE `tours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tour_schedules`
--
ALTER TABLE `tour_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1113;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`tourist_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`tour_schedule_id`) REFERENCES `tour_schedules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fines`
--
ALTER TABLE `fines`
  ADD CONSTRAINT `fines_ibfk_1` FOREIGN KEY (`tourist_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fines_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD CONSTRAINT `password_reset_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`tourist_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tours`
--
ALTER TABLE `tours`
  ADD CONSTRAINT `tours_ibfk_1` FOREIGN KEY (`guide_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tours_ibfk_2` FOREIGN KEY (`attraction_id`) REFERENCES `attractions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tour_schedules`
--
ALTER TABLE `tour_schedules`
  ADD CONSTRAINT `tour_schedules_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
