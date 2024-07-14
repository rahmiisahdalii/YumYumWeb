-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 14 Tem 2024, 23:25:36
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `yumrum`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `su` int(11) DEFAULT 2,
  `resim` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`, `su`, `resim`) VALUES
(1, 'admin', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 1, NULL),
(16, 'poppeyes', '4f197c99a78b8411f1cf48ab409a0a6d176b99b7', 2, 'logo.svg'),
(20, 'tester', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 2, 'logo.svg'),
(21, 'zurnaci', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 2, 'logo.svg'),
(25, 'firmaYemek', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 2, 'logo.svg'),
(26, 'baloğlu', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 2, 'logo.svg'),
(27, 'kafelab', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 2, 'logo.svg');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'fast food'),
(2, 'drinks'),
(3, 'main dish'),
(4, 'dessert');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `coupon_string` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `end_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `min_price` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `coupons`
--

INSERT INTO `coupons` (`id`, `admin_id`, `coupon_string`, `type`, `amount`, `end_date`, `min_price`) VALUES
(15, 16, 'POPINDIRIM10', 'percent', 10, '2024-06-04 21:00:00', 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `log_messages`
--

CREATE TABLE `log_messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `reciever_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `message_date` timestamp NULL DEFAULT current_timestamp(),
  `message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `log_messages`
--

INSERT INTO `log_messages` (`id`, `sender_id`, `reciever_id`, `product_id`, `message_date`, `message`) VALUES
(1, 6, 16, 11, '2024-05-22 18:06:20', 'merhaba'),
(3, 6, 16, 11, '2024-05-22 18:25:38', 'merhaba çaylar nerde kaldı?'),
(4, 16, 6, 11, '2024-05-22 18:25:53', 'yolda efendim gelmek üzere'),
(9, 16, 6, 6, '2024-05-22 18:37:29', 'GELMEK ÜZERE'),
(10, 6, 16, 11, '2024-05-22 18:37:54', 'ÇAYLARI YOLLLAA');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `reciever_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `message_date` timestamp NULL DEFAULT current_timestamp(),
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tetikleyiciler `messages`
--
DELIMITER $$
CREATE TRIGGER `log_message_insert` AFTER INSERT ON `messages` FOR EACH ROW BEGIN
    INSERT INTO log_messages (sender_id, reciever_id , product_id, message_date, message)
    VALUES (NEW.sender_id, NEW.reciever_id, NEW.product_id, NEW.message_date, NEW.message);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `number` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(11) NOT NULL,
  `placed_on` timestamp NULL DEFAULT current_timestamp(),
  `payment_status` varchar(255) DEFAULT 'pending',
  `admin_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`, `admin_id`, `product_id`) VALUES
(56, 7, 'çay', '123456789', 'mlsa@mlsa.com', 'credit card', '12, 1, bağlık, hatip sokak, zonguldak, ev, türkiye - 67300', 'çay (20 x 1)', 20, '2024-05-22 21:12:45', 'completed', 16, 11),
(57, 7, 'Popchiken', '123456789', 'mlsa@mlsa.com', 'credit card', '12, 1, bağlık, hatip sokak, zonguldak, ev, türkiye - 67300', 'popchiken (111 x 3)', 333, '2024-05-22 21:33:20', 'completed', 16, 6);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `price`, `image`, `admin_id`) VALUES
(1, 'Burger King', 'fast food', 150, 'burger.png', 4),
(5, 'zurna durum', 'fast food', 313, 'zurna.jpg', 6),
(6, 'Popchiken', 'main dish', 111, 'popchicken.png', 16),
(7, 'Su', 'drinks', 61, 'su.png', 19),
(8, 'Profiterol', 'desserts', 161, 'profiterol.webp', 20),
(9, 'Köfte', 'main dish', 321, 'köfte.jpg', 20),
(10, 'tavuk zurna', 'fast food', 110, 'zurna.jpg', 21),
(11, 'Çay', 'drinks', 15, 'çay.webp', 20),
(12, 'Pizza', 'fast food', 350, 'home-img-3.png', 25);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `product_evaluation`
--

CREATE TABLE `product_evaluation` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `placed_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `evo_message` varchar(255) DEFAULT NULL,
  `is_evo` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `product_evaluation`
--

INSERT INTO `product_evaluation` (`id`, `product_id`, `admin_id`, `user_id`, `placed_on`, `evo_message`, `is_evo`) VALUES
(3, 11, 16, 7, '2024-05-22 21:12:45', 'yemek harikaydı', 1),
(4, 6, 16, 7, '2024-05-22 21:33:20', 'bu ürüne bayıldımm', 1),
(5, 6, 16, 6, '2024-05-22 21:57:11', 'popchicken çok iyiii', 1),
(6, 6, 16, 7, '2024-05-24 11:50:46', 'hızlı teslimat', 1),
(7, 10, 21, 5, '2024-05-24 12:53:51', 'güzel bir lezzet', 1),
(8, 6, 16, 8, '2024-05-25 10:07:47', 'lanet bi yemek', 1),
(9, 11, 16, 8, '2024-05-25 10:07:47', 'çok iyidi', 1),
(10, 6, 16, 8, '2024-05-25 10:26:16', 'usluunuz kötü', 1),
(11, 11, 16, 8, '2024-05-25 10:26:16', NULL, 0),
(12, 6, 16, 8, '2024-05-25 19:39:01', NULL, 0),
(13, 11, 16, 8, '2024-05-25 19:39:01', NULL, 0),
(14, 6, 16, 8, '2024-05-25 19:31:03', NULL, 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `restaurant_demand`
--

CREATE TABLE `restaurant_demand` (
  `id` int(11) NOT NULL,
  `username` varchar(55) NOT NULL,
  `password` varchar(50) NOT NULL,
  `restaurant_image` varchar(255) NOT NULL,
  `restaurant_info` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `restaurant_demand`
--

INSERT INTO `restaurant_demand` (`id`, `username`, `password`, `restaurant_image`, `restaurant_info`, `status`) VALUES
(1, 'deneme12345', '123', '/update/resim.jpg', 'tatlici', 1),
(7, 'yemekye.co', '123456', 'mlsa.png', 'adasdad', 1),
(12, 'firmaYemek', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'dish-1.png', 'yemekk', 1),
(14, 'baloğlu', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'menu-6.jpg', 'harika bi yer', 1),
(15, 'kafelab', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'home-img-2.png', 'yeni bir firmayım sisizskfjd', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `number` varchar(10) NOT NULL,
  `password` varchar(50) NOT NULL,
  `address` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `number`, `password`, `address`) VALUES
(9, 'ali', 'mail@mail.com', '1234566789', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'a, a, a, a, a, a, a - 45400');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `log_messages`
--
ALTER TABLE `log_messages`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `product_evaluation`
--
ALTER TABLE `product_evaluation`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `restaurant_demand`
--
ALTER TABLE `restaurant_demand`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Tablo için AUTO_INCREMENT değeri `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- Tablo için AUTO_INCREMENT değeri `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Tablo için AUTO_INCREMENT değeri `log_messages`
--
ALTER TABLE `log_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Tablo için AUTO_INCREMENT değeri `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Tablo için AUTO_INCREMENT değeri `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- Tablo için AUTO_INCREMENT değeri `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Tablo için AUTO_INCREMENT değeri `product_evaluation`
--
ALTER TABLE `product_evaluation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Tablo için AUTO_INCREMENT değeri `restaurant_demand`
--
ALTER TABLE `restaurant_demand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`id`) REFERENCES `admin` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
