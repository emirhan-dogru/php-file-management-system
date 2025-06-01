-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 01 Haz 2025, 21:36:13
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
  `user_id` int NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `file_type` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `isShow` tinyint(1) NOT NULL,
  `file_size` int NOT NULL,
  `upload_date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=115 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `files`
--

INSERT INTO `files` (`id`, `user_id`, `file_name`, `file_path`, `file_type`, `isShow`, `file_size`, `upload_date`) VALUES
(66, 1, 'images.jfif', 'uploads/baf53227-fc89-42bd-8f6b-595cab1b4741.jfif', 'image/jpeg', 1, 8648, '2025-05-23 21:06:31'),
(68, 1, 'test.txt', 'uploads/118dec2c-0c79-421a-bfc6-7d0b888fd2a8.txt', 'text/plain', 0, 1, '2025-05-23 21:08:29'),
(67, 1, '360_F_564330523_ptIW4LwoZqrlGTIZHs1ZKwkaTJLPL7tK.jpg', 'uploads/c87c1327-a795-4616-82d8-52964fb1d74e.jpg', 'image/jpeg', 1, 95056, '2025-05-23 21:08:10'),
(113, 12, 'LICENSE.txt', 'uploads/8cfc9ecb-71c9-4a55-83a4-6f5b2b584e1a.txt', 'text/plain', 0, 1070, '2025-06-01 23:51:08');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tokens`
--

DROP TABLE IF EXISTS `tokens`;
CREATE TABLE IF NOT EXISTS `tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `token` varchar(500) COLLATE utf8mb4_turkish_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `expires_at` datetime NOT NULL,
  `is_valid` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `tokens`
--

INSERT INTO `tokens` (`id`, `user_id`, `token`, `created_at`, `expires_at`, `is_valid`) VALUES
(1, 1, 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2ZpbGUtbWFuYWdlbWVudCIsImF1ZCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvZmlsZS1tYW5hZ2VtZW50IiwiaWF0IjoxNzQ4MTMxNzY4LCJleHAiOjE3NDgxMzUzNjgsInVzZXJfaWQiOjEsImVtYWlsIjoidGVzdEBlbWlyaGFuZG9ncnUuY29tLnRyIiwidXNlcm5hbWUiOiJFbWlyaGFuIERvXHUwMTFmcnUifQ.bcoYpsQ40JnxJLw2py8Swvd2bvI2vLPV1sUHJ2vs3-c', '2025-05-25 03:09:28', '2025-05-25 05:09:28', 0),
(8, 1, 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2ZpbGUtbWFuYWdlbWVudCIsImF1ZCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvZmlsZS1tYW5hZ2VtZW50IiwiaWF0IjoxNzQ4MTYyNTMwLCJleHAiOjE3NDgxNjYxMzAsInVzZXJfaWQiOjEsImVtYWlsIjoidGVzdEBlbWlyaGFuZG9ncnUuY29tLnRyIiwidXNlcm5hbWUiOiJFbWlyaGFuIERvXHUwMTFmcnUiLCJqdGkiOiJlMzg4YTNiZC0zN2U4LTQzNjItYTA5ZS1kYjY3ZDdhOGJmZjUifQ.fXKJ0wO0icvTpMoE3_aEj_RVANlMkM3nOsx2vh1hzwU', '2025-05-25 11:42:10', '2025-05-25 11:42:10', 0),
(9, 1, 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2ZpbGUtbWFuYWdlbWVudCIsImF1ZCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvZmlsZS1tYW5hZ2VtZW50IiwiaWF0IjoxNzQ4MTYzMzQwLCJleHAiOjE3NDgxNjY5NDAsInVzZXJfaWQiOjEsImVtYWlsIjoidGVzdEBlbWlyaGFuZG9ncnUuY29tLnRyIiwidXNlcm5hbWUiOiJFbWlyaGFuIERvXHUwMTFmcnUiLCJqdGkiOiJmMDZhOGM2Yy00MjQ0LTQ2ZWEtYjI1NS1kMDNkNjI2ZjFlZDIifQ.VzqmARwFtf4O7uxU_0xOhXqgza0-xhd9hHSQWV-no8o', '2025-05-25 11:55:40', '2025-05-25 11:55:40', 0),
(27, 12, 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2ZpbGUtbWFuYWdlbWVudCIsImF1ZCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvZmlsZS1tYW5hZ2VtZW50IiwiaWF0IjoxNzQ4NzY3Mjg1LCJleHAiOjE3NDg3NzA4ODUsInVzZXJfaWQiOjEyLCJlbWFpbCI6ImVkQGVtaXJoYW5kb2dydS5jb20udHIiLCJ1c2VybmFtZSI6InRlc3QgRG9cdTAxMWZydSIsImp0aSI6Ijg3NWYxNzE1LTg1OGUtNGY3Yi1iNThjLTU4ODUxYmUyYzlkZCJ9.oYaxHl2MApxxRxi6-SJ7fXBy5LzizOz2jFJ6gs49Qkk', '2025-06-01 11:41:25', '2025-06-01 12:41:25', 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(191) COLLATE utf8mb4_turkish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `username`, `created_at`) VALUES
(1, 'test@emirhandogru.com.tr', '$2y$10$lU3iV1yAo.4bi0CiIMSQP.vO9o58YAE4QxhRhprr3ke4xr2k86UNC', 'Emirhan Doğru', '2025-05-25 02:55:46'),
(12, 'ed@emirhandogru.com.tr', '$2y$10$4FzwyLhIVJkgIzLc61gyGe1iPEOdtJObHgKr1VTnQs3lRi/ScGqSa', 'test Doğru', '2025-05-29 00:00:13');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
