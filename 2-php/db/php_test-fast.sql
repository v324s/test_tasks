-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Сен 09 2022 г., 13:16
-- Версия сервера: 10.3.22-MariaDB
-- Версия PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `php_test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `login` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` tinytext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `admins`
--

INSERT INTO `admins` (`id`, `login`, `password`) VALUES
(1, 'admin', '3f571ab24d54baf70b44bb4ce6c88214'),
(2, 'moder', '4297f44b13955235245b2497399d7a93');

-- --------------------------------------------------------

--
-- Структура таблицы `data`
--

CREATE TABLE `data` (
  `id` int(11) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `name` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `data`
--

INSERT INTO `data` (`id`, `parent`, `name`, `description`) VALUES
(1, NULL, 'Гараж', 'Возле дома'),
(3, NULL, 'Сад', 'На даче'),
(4, NULL, 'Одноразка', 'курилка такая'),
(5, NULL, 'Ручка', 'Шариковая'),
(6, NULL, 'Компьютер', NULL),
(14, 5, 'Стержень', 'с чернилами'),
(17, 4, 'аккумулятор', 'для работы'),
(18, NULL, 'Клавиатура', 'компьютерная'),
(19, NULL, 'Мышь', NULL),
(20, 18, 'Кнопки', NULL),
(21, 19, 'Скролл', NULL),
(25, 14, 'чернила', 'для письма'),
(32, 20, 'шрифт', 'кнопки'),
(33, 32, 'Белый', NULL),
(34, 19, 'Сенсер', NULL),
(35, NULL, 'Аудиосистема', 'песни играть'),
(36, NULL, 'Автомобиль', NULL),
(37, 36, '4 колеса', NULL),
(38, 36, 'Двигатель', NULL),
(39, 36, 'Цвет', 'Синий');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `data`
--
ALTER TABLE `data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
