-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-04-2024 a las 11:16:29
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_riego`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arduino`
--

CREATE TABLE `arduino` (
  `id` int(11) NOT NULL,
  `ip` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `arduino`
--

INSERT INTO `arduino` (`id`, `ip`) VALUES
(1, '192.168.0.100'),
(2, '192.168.0.101');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_sensores`
--

CREATE TABLE `datos_sensores` (
  `id` int(11) NOT NULL,
  `id_ard` int(11) NOT NULL,
  `humedad` float DEFAULT NULL,
  `temperatura` float DEFAULT NULL,
  `humedad_suelo` int(11) DEFAULT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `datos_sensores`
--

INSERT INTO `datos_sensores` (`id`, `id_ard`, `humedad`, `temperatura`, `humedad_suelo`, `fecha_hora`) VALUES
(1, 1, 14, 21, 23, '2024-04-18 14:46:00'),
(2, 2, 34, 32, 34, '2024-04-18 15:04:56'),
(3, 1, 99, 99, 99, '2024-04-18 15:33:57'),
(6, 1, 44, 44, 44, '2024-04-18 15:58:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `apellidos` varchar(45) DEFAULT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nombre`, `apellidos`, `email`, `password`) VALUES
(1, 'Ivan', 'Bezanilla Lopez', 'ibezanillal01@educantabria.es', 'Alisal2023');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `arduino`
--
ALTER TABLE `arduino`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `datos_sensores`
--
ALTER TABLE `datos_sensores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `datos_sensores`
--
ALTER TABLE `datos_sensores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `arduino`
--
ALTER TABLE `arduino`
  ADD CONSTRAINT `fk_arduino_sensores` FOREIGN KEY (`id`) REFERENCES `datos_sensores` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
