-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Дек 19 2025 г., 10:26
-- Версия сервера: 11.4.7-MariaDB-ubu2404
-- Версия PHP: 8.3.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `zenkova_florabloom`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Букеты'),
(2, 'Монобукеты'),
(3, 'Композиции'),
(4, 'Подарочные наборы'),
(5, 'Розы'),
(6, 'Тюльпаны'),
(7, 'Пионы'),
(8, 'Орхидеи'),
(9, 'Герберы'),
(10, 'Хризантемы'),
(11, 'Свадебные букеты'),
(12, 'Лилии'),
(13, 'Лилии');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `payment_type` enum('online','cash') NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` set('Новый','Подтверждён','Готовится','Выполнен') NOT NULL DEFAULT 'Новый'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `address`, `payment_type`, `total_price`, `created_at`, `status`) VALUES
(2, 2, 'ул. Пушкина, 5', 'cash', 4500.00, '2025-12-13 12:40:17', 'Новый'),
(3, 4, 'ул. Ленина, 10', 'online', 8000.00, '2025-12-13 13:46:20', 'Новый'),
(4, 8, 'ул. Ленина, 10', 'online', 8000.00, '2025-12-16 00:35:21', 'Новый'),
(5, 8, 'ул. Ленина, 10', 'online', 8000.00, '2025-12-16 00:39:07', 'Новый'),
(6, 8, 'ул. Ленина, 10', 'online', 8000.00, '2025-12-16 00:39:46', 'Новый'),
(7, 8, 'ул. Ленина, 10', 'online', 8000.00, '2025-12-16 00:41:59', 'Новый'),
(8, 8, 'ул. Ленина, 10', 'online', 8000.00, '2025-12-16 01:08:26', 'Новый');

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Дамп данных таблицы `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `count`, `price`) VALUES
(4, 2, 2, 1, 2700.00),
(5, 2, 10, 1, 1800.00),
(6, 3, 1, 2, 2500.00),
(7, 3, 3, 1, 3000.00),
(8, 4, 1, 2, 2500.00),
(9, 4, 3, 1, 3000.00),
(10, 5, 1, 2, 2500.00),
(11, 5, 3, 1, 3000.00),
(12, 6, 1, 2, 2500.00),
(13, 6, 3, 1, 3000.00),
(14, 7, 1, 2, 2500.00),
(15, 7, 3, 1, 3000.00),
(16, 8, 1, 2, 2500.00),
(17, 8, 3, 1, 3000.00);

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `title`, `category_id`, `price`, `image`, `created_at`) VALUES
(1, 'Букет «Нежность»', 1, 2500.00, '1.jpg', '2025-12-13 12:40:17'),
(2, 'Букет «Мимоза»', 1, 2700.00, '2.jpg', '2025-12-13 12:40:17'),
(3, 'Монобукет из роз', 2, 3000.00, '3.jpg', '2025-12-13 12:40:17'),
(4, 'Монобукет из пионов', 2, 4200.00, '4.jpg', '2025-12-13 12:40:17'),
(5, 'Композиция «Сладкий персик»', 3, 3100.00, '5.jpg', '2025-12-13 12:40:17'),
(6, 'Композиция в коробке «Love»', 3, 4500.00, '6.jpg', '2025-12-13 12:40:17'),
(7, 'Подарочный набор «Романтика»', 4, 5200.00, '7.jpg', '2025-12-13 12:40:17'),
(8, 'Подарочный набор «Chocolate»', 4, 5800.00, '8.jpg', '2025-12-13 12:40:17'),
(9, 'Розы красные 15 шт.', 5, 3300.00, '9.jpg', '2025-12-13 12:40:17'),
(10, 'Розы белые 21 шт.', 5, 4500.00, '10.jpg', '2025-12-13 12:40:17'),
(11, 'Тюльпаны розовые 15 шт.', 6, 1900.00, '11.jpg', '2025-12-13 12:40:17'),
(12, 'Тюльпаны белые 25 шт.', 6, 2900.00, '12.jpg', '2025-12-13 12:40:17'),
(13, 'Пионы нежно-розовые 11 шт.', 7, 3900.00, '13.jpg', '2025-12-13 12:40:17'),
(14, 'Пионы коралловые 15 шт.', 7, 4800.00, '14.jpg', '2025-12-13 12:40:17'),
(15, 'Орхидеи фаленопсис белые', 8, 5200.00, '15.jpg', '2025-12-13 12:40:17'),
(16, 'Орхидеи фаленопсис розовые', 8, 5400.00, '16.jpg', '2025-12-13 12:40:17'),
(17, 'Букет из гербер «Солнечный»', 9, 2100.00, '17.jpg', '2025-12-13 12:40:17'),
(18, 'Герберы микс 15 шт.', 9, 2500.00, '18.jpg', '2025-12-13 12:40:17'),
(19, 'Хризантемы кустовые белые', 10, 8808.00, '19.jpg', '2025-12-13 12:40:17'),
(22, 'Букет «Наступление весныы»', 1, 4700.00, 'uploads/6944a05975c41.jpg', '2025-12-19 00:46:17'),
(23, 'Букет «Мяу»', 1, 5000.00, 'uploads/6944b2fc461e5.jpg', '2025-12-19 02:05:48');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(150) NOT NULL,
  `last_name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(250) NOT NULL,
  `photo` varchar(250) DEFAULT NULL,
  `biography` varchar(400) DEFAULT NULL,
  `date_of_creation` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `check_email` tinyint(4) NOT NULL DEFAULT 0,
  `administrator` tinyint(1) NOT NULL DEFAULT 0,
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `photo`, `biography`, `date_of_creation`, `check_email`, `administrator`, `token`) VALUES
(1, 'Иван', 'Петров', 'ivan123@gmail.com', '$2y$13$kM2JrYxF9zXk1YBzi7HFOOj.LD1k6upFZcMbUlB9YQnzoz3a6UOxS$2y$13$w3ybzK0DJ1GxdmUkcZzXPe5d4aW3qKClq1lOawFJ5L8q7tWfX9FfS$2y$13$uZpW6fZKqFJ3f4ZJ6j7E9u4dXnKp8nZ5ZKxZrP9ZkM1m0VZ7H1eYy', NULL, NULL, '2025-12-16 01:10:42', 1, 0, 'E9NAf7N2_n4poSLBTUtFfj3Afh1VJECu'),
(2, 'Мария', 'Смирнова', 'maria456@gmail.com', '$2y$13$Zr6FJpZ9Z1E7M5KxP9nKpY0R7ZJ6Z4mHk5uZ3X7r1Z9LJvK8M', NULL, NULL, '2025-12-13 13:14:02', 1, 0, NULL),
(3, 'Алексей', 'Иванов', 'alex789@gmail.com', '$2y$13$O3MZyZ1Hk5M7k6N0bK2RLezKpYpZ9PqzX7CwZr1m0FJvLJz6R7Z9K', NULL, NULL, '2025-12-19 02:05:00', 1, 1, 'endSc19xDJXPL4ytn6ZRUyJs3KHiM3YI'),
(4, 'Алексей', 'Смирнов', 'alex.smirnov2025@gmail.com', '$2y$13$SQ4ICSLVouNSUrEaqEaJb.O.0GqB8oF4Gwx6LsvdkAgPe6N.T8ZLK', NULL, NULL, '2025-12-15 21:55:14', 0, 1, 'Vt94xxyq2kRnu3SFUk6J06eEIAdHjPGC'),
(5, 'Алексей', 'Смирнов', 'alex.smirnov2025@gmail.co1m', '$2y$13$OXaJ9zkBADMhzrEX/esyRuVjzUw39OGQ6iO/zWgzRDAhAVeHjeY3u', NULL, NULL, '2025-12-15 21:07:10', 0, 0, NULL),
(6, 'Максим', 'Петров', 'maks10050@gmail.com', '$2y$13$5ncIHf9lLoc9w4qOs1iJmuJgsAVq7pIPLn3l/BOpVx4BTjSxif6Wa', NULL, NULL, '2025-12-15 21:13:16', 0, 0, NULL),
(7, 'Ульяна', 'Мирная', 'uly2025@gmail.com', '$2y$13$8Je/2Aqqo2Ea.Cnl4QmXO.W1/Gdc1zGo1nC9yE.XqSKo8GbYyIWVK', NULL, NULL, '2025-12-15 21:41:21', 0, 0, '4XBs6sqf6TkzTiezMJxLRlGC4HfswcIl'),
(8, 'Мария', 'Казакова', 'maria2007@gmail.com', '$2y$13$IOk/pMCMTTDlY.n5B/AtoOZ5Zg2aIScFKaV2b68znGsImWjffUvDe', NULL, NULL, '2025-12-16 01:47:47', 0, 0, '7PSrLllPe8Nn-JLpV-CDVpb5tqEMX5Hu');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_user_fk` (`user_id`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oi_order_fk` (`order_id`),
  ADD KEY `oi_product_fk` (`product_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_fk` (`category_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `order_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `oi_order_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `oi_product_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_fk` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
