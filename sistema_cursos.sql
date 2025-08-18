-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-08-2025 a las 19:53:56
-- Versión del servidor: 11.8.2-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_cursos`
--
CREATE DATABASE IF NOT EXISTS `sistema_cursos` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci;
USE `sistema_cursos`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `profesor_id` int(11) NOT NULL,
  `creado_en` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id`, `nombre`, `codigo`, `profesor_id`, `creado_en`) VALUES
(1, 'Matemáticas Avanzadas', 'MAT-101', 1, '2025-08-18 16:16:44'),
(2, 'Literatura Contemporánea', 'LIT-201', 2, '2025-08-18 16:16:44'),
(3, 'Programación en Python', 'PROG-301', 3, '2025-08-18 16:16:44'),
(4, 'Historia del Arte', 'ART-102', 1, '2025-08-18 16:16:44'),
(5, 'Inglés Avanzado', 'ING-202', 2, '2025-08-18 16:16:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones`
--

CREATE TABLE `inscripciones` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `fecha_inscripcion` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `inscripciones`
--

INSERT INTO `inscripciones` (`id`, `alumno_id`, `curso_id`, `fecha_inscripcion`) VALUES
(1, 4, 1, '2025-08-18 16:16:44'),
(2, 4, 3, '2025-08-18 16:16:44'),
(3, 5, 2, '2025-08-18 16:16:44'),
(4, 5, 5, '2025-08-18 16:16:44'),
(5, 6, 1, '2025-08-18 16:16:44'),
(6, 6, 4, '2025-08-18 16:16:44'),
(7, 7, 3, '2025-08-18 16:16:44'),
(8, 7, 5, '2025-08-18 16:16:44'),
(9, 8, 2, '2025-08-18 16:16:44'),
(10, 8, 4, '2025-08-18 16:16:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('profesor','alumno') NOT NULL,
  `creado_en` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `contrasena`, `rol`, `creado_en`) VALUES
(1, 'Profesor Juan Pérez', 'juan.perez@escuela.com', '1234', 'profesor', '2025-08-18 16:16:43'),
(2, 'Profesora María García', 'maria.garcia@escuela.com', '1234', 'profesor', '2025-08-18 16:16:43'),
(3, 'Profesor Carlos López', 'carlos.lopez@escuela.com', '1234', 'profesor', '2025-08-18 16:16:43'),
(4, 'Alumno Pedro Gómez', 'pedro.gomez@escuela.com', '1234', 'alumno', '2025-08-18 16:16:44'),
(5, 'Alumna Ana Rodríguez', 'ana.rodriguez@escuela.com', '1234', 'alumno', '2025-08-18 16:16:44'),
(6, 'Alumno Luis Martínez', 'luis.martinez@escuela.com', '1234', 'alumno', '2025-08-18 16:16:44'),
(7, 'Alumna Sofía Fernández', 'sofia.fernandez@escuela.com', '1234', 'alumno', '2025-08-18 16:16:44'),
(8, 'Alumno Miguel Torres', 'miguel.torres@escuela.com', '1234', 'alumno', '2025-08-18 16:16:44');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `profesor_id` (`profesor_id`);

--
-- Indices de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alumno_id` (`alumno_id`,`curso_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`profesor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD CONSTRAINT `inscripciones_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscripciones_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
