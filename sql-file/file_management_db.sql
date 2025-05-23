-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 23 May 2025, 18:09:41
-- Sunucu sürümü: 9.1.0
-- PHP Sürümü: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `file_management_db`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `files`
--

DROP TABLE IF EXISTS `files`;
CREATE TABLE IF NOT EXISTS `files` (
  `id` int NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `file_type` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `isShow` tinyint(1) NOT NULL,
  `file_size` int NOT NULL,
  `upload_date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `files`
--

INSERT INTO `files` (`id`, `file_name`, `file_path`, `file_type`, `isShow`, `file_size`, `upload_date`) VALUES
(66, 'images.jfif', 'uploads/baf53227-fc89-42bd-8f6b-595cab1b4741.jfif', 'image/jpeg', 1, 8648, '2025-05-23 21:06:31'),
(68, 'test.txt', 'uploads/118dec2c-0c79-421a-bfc6-7d0b888fd2a8.txt', 'text/plain', 0, 1, '2025-05-23 21:08:29'),
(67, '360_F_564330523_ptIW4LwoZqrlGTIZHs1ZKwkaTJLPL7tK.jpg', 'uploads/c87c1327-a795-4616-82d8-52964fb1d74e.jpg', 'image/jpeg', 1, 95056, '2025-05-23 21:08:10');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `routes`
--

DROP TABLE IF EXISTS `routes`;
CREATE TABLE IF NOT EXISTS `routes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `routeName` varchar(250) COLLATE utf8mb4_turkish_ci NOT NULL,
  `routeFilePath` varchar(250) COLLATE utf8mb4_turkish_ci NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `routes`
--

INSERT INTO `routes` (`id`, `routeName`, `routeFilePath`, `createdAt`, `updatedAt`) VALUES
(1, 'profile', './pages/my-profile.php', '2025-05-21 21:06:55', '2025-05-21 21:11:18'),
(2, '', './pages/homepage.php', '2025-05-21 21:14:55', '2025-05-21 21:14:55'),
(3, 'my-datas', './pages/datas.php', '2025-05-22 10:43:22', '2025-05-22 10:43:22');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
