-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-08-2024 a las 00:19:05
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `fiscor`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `ID_Categoria` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`ID_Categoria`, `Nombre`, `Estado`) VALUES
(1, 'Materia prima', 1),
(2, 'Insumos', 1),
(3, 'Sueldos y Salarios (personal docente)', 1),
(4, 'Beneficios laborales (personal docente)', 1),
(5, 'Sueldos y Salarios (personal obrero)', 1),
(7, 'Beneficios laborales (personal obrero)	', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gasto`
--

CREATE TABLE `gasto` (
  `ID_Gasto` int(11) NOT NULL,
  `ID_Usuario` int(11) NOT NULL,
  `ID_Proyecto` int(11) NOT NULL,
  `ID_Item` int(11) NOT NULL,
  `Fecha` date NOT NULL,
  `Monto_Gasto` int(11) NOT NULL,
  `Comprobante` varchar(50) NOT NULL,
  `Observacion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `gasto`
--

INSERT INTO `gasto` (`ID_Gasto`, `ID_Usuario`, `ID_Proyecto`, `ID_Item`, `Fecha`, `Monto_Gasto`, `Comprobante`, `Observacion`) VALUES
(2, 1, 1, 1, '2024-08-21', 45, '420405', 'S/O'),
(3, 1, 1, 2, '2024-08-21', 50, '45656', 'Necesario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item`
--

CREATE TABLE `item` (
  `id_item` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `item`
--

INSERT INTO `item` (`id_item`, `id_categoria`, `nombre`, `estado`) VALUES
(1, 1, 'Materiales didacticos', 1),
(2, 2, 'Suministros de educacion fisica', 1),
(3, 2, 'Suministros de laboratoria', 1),
(4, 1, 'Materiales de oficina', 1),
(5, 3, 'Sueldo Base', 1),
(6, 4, 'Aginaldo fin de año', 1),
(7, 4, 'Bono vacacional', 1),
(8, 5, 'Sueldo Base', 1),
(9, 7, 'Aginaldo fin de año', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuesto`
--

CREATE TABLE `presupuesto` (
  `id_item` int(11) NOT NULL,
  `id_proyecto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `monto_presupuesto` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `presupuesto`
--

INSERT INTO `presupuesto` (`id_item`, `id_proyecto`, `cantidad`, `monto_presupuesto`) VALUES
(1, 1, 100, 2249.79),
(2, 1, 50, 1572.87),
(3, 1, 60, 1963.37),
(6, 1, 123, 17455.80),
(7, 1, 123, 8608.84),
(4, 1, 45, 919.00),
(5, 1, 122, 29249.00),
(8, 1, 89, 2249.79),
(9, 1, 89, 5249.58);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto`
--

CREATE TABLE `proyecto` (
  `ID_Proyecto` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Descripcion` varchar(100) NOT NULL,
  `Estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proyecto`
--

INSERT INTO `proyecto` (`ID_Proyecto`, `Nombre`, `Descripcion`, `Estado`) VALUES
(1, 'Mensualidad', 'Año escolar 2024', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `ID_Rol` tinyint(1) NOT NULL,
  `Nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`ID_Rol`, `Nombre`) VALUES
(0, 'Normal'),
(1, 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `ID_Usuario` int(11) NOT NULL,
  `ID_Rol` tinyint(1) NOT NULL DEFAULT 0,
  `Usuario` varchar(50) NOT NULL,
  `Contrasena` varchar(50) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Apellido` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`ID_Usuario`, `ID_Rol`, `Usuario`, `Contrasena`, `Nombre`, `Apellido`) VALUES
(1, 1, 'ByC', 'prueba123', 'SAE', 'WEB'),
(2, 0, 'UtechG', '123456', 'Miguel', 'Gutierrez');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`ID_Categoria`);

--
-- Indices de la tabla `gasto`
--
ALTER TABLE `gasto`
  ADD PRIMARY KEY (`ID_Gasto`),
  ADD KEY `ID_Proyecto` (`ID_Proyecto`),
  ADD KEY `ID_Item` (`ID_Item`),
  ADD KEY `ID_Usuario` (`ID_Usuario`) USING BTREE;

--
-- Indices de la tabla `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `ID_Categoria` (`id_categoria`);

--
-- Indices de la tabla `presupuesto`
--
ALTER TABLE `presupuesto`
  ADD KEY `ID_Item` (`id_item`),
  ADD KEY `ID_Proyecto` (`id_proyecto`);

--
-- Indices de la tabla `proyecto`
--
ALTER TABLE `proyecto`
  ADD PRIMARY KEY (`ID_Proyecto`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`ID_Rol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`ID_Usuario`),
  ADD KEY `ID_Rol` (`ID_Rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `ID_Categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `gasto`
--
ALTER TABLE `gasto`
  MODIFY `ID_Gasto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `item`
--
ALTER TABLE `item`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `proyecto`
--
ALTER TABLE `proyecto`
  MODIFY `ID_Proyecto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `ID_Rol` tinyint(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `ID_Usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `gasto`
--
ALTER TABLE `gasto`
  ADD CONSTRAINT `gasto_ibfk_2` FOREIGN KEY (`ID_Item`) REFERENCES `presupuesto` (`id_item`) ON UPDATE CASCADE,
  ADD CONSTRAINT `gasto_ibfk_3` FOREIGN KEY (`ID_Proyecto`) REFERENCES `presupuesto` (`id_proyecto`) ON UPDATE CASCADE,
  ADD CONSTRAINT `gasto_ibfk_4` FOREIGN KEY (`ID_Usuario`) REFERENCES `usuario` (`ID_Usuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`ID_Categoria`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `presupuesto`
--
ALTER TABLE `presupuesto`
  ADD CONSTRAINT `presupuesto_ibfk_2` FOREIGN KEY (`id_item`) REFERENCES `item` (`id_item`) ON UPDATE CASCADE,
  ADD CONSTRAINT `presupuesto_ibfk_3` FOREIGN KEY (`id_proyecto`) REFERENCES `proyecto` (`ID_Proyecto`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`ID_Rol`) REFERENCES `rol` (`ID_Rol`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
