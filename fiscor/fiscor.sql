-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-08-2024 a las 07:18:52
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
(1, 'Costos Directos', 1),
(2, 'Costos Indirectos', 1),
(3, 'Personal (Obrero)', 1),
(4, 'Personal (Administrativo)', 1),
(5, 'Materiales', 1),
(6, 'Equipamiento', 1),
(7, 'Logística', 1),
(8, 'Consultoría', 1),
(9, 'Impuestos', 1),
(10, 'Seguros', 1),
(11, 'Publicidad y Marketing', 1),
(12, 'Investigación y Desarrollo', 1),
(13, 'Tecnología y Software', 1),
(14, 'Mantenimiento', 1),
(15, 'Capacitación y Formación', 1),
(16, 'Viajes y Viáticos', 1),
(17, 'Servicios Públicos', 1),
(18, 'Auditoría y Control de Calidad', 1),
(19, 'Proveedores Externos', 1),
(20, 'Otros Gastos Administrativos', 1);

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
(23, 1, 1, 1, '2024-08-29', 1123, '1231123', 'pago');

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
(1, 1, 'Materiales de construcción', 1),
(2, 1, 'Alquiler de maquinaria', 1),
(3, 2, 'Alquiler de oficina', 1),
(4, 2, 'Servicios públicos', 1),
(5, 3, 'Salario de obreros', 1),
(6, 3, 'EPP (Equipo de Protección Personal)', 1),
(7, 4, 'Salario de administrativos', 1),
(8, 4, 'Bonificaciones', 1),
(9, 5, 'Cemento', 1),
(10, 5, 'Acero', 1),
(11, 6, 'Grúa', 1),
(12, 6, 'Andamios', 1),
(13, 7, 'Transporte de materiales', 1),
(14, 7, 'Almacenamiento', 1),
(15, 8, 'Asesoría legal', 1),
(16, 8, 'Asesoría contable', 1),
(17, 9, 'Impuesto sobre la renta', 1),
(18, 9, 'Impuesto municipal', 1),
(19, 10, 'Seguro contra accidentes', 1),
(20, 10, 'Seguro de maquinaria', 1),
(21, 11, 'Publicidad en redes sociales', 1),
(22, 11, 'Publicidad en medios tradicionales', 1),
(23, 12, 'Pruebas de prototipo', 1),
(24, 12, 'Investigación de mercado', 1),
(25, 13, 'Licencias de software', 1),
(26, 13, 'Compra de hardware', 1),
(27, 14, 'Mantenimiento de maquinaria', 1),
(28, 14, 'Mantenimiento de edificios', 1),
(29, 15, 'Cursos de capacitación', 1),
(30, 15, 'Talleres y seminarios', 1),
(31, 16, 'Pasajes aéreos', 1),
(32, 16, 'Alojamiento', 1),
(33, 17, 'Agua', 1),
(34, 17, 'Electricidad', 1),
(35, 18, 'Auditoría externa', 1),
(36, 18, 'Inspección de calidad', 1),
(37, 19, 'Servicios de terceros', 1),
(38, 19, 'Proveedores de material', 1),
(39, 20, 'Papelería y suministros', 1),
(40, 20, 'Gastos bancarios', 1),
(41, 1, 'Mano de obra directa', 1),
(42, 1, 'Gastos de transporte de materiales', 1),
(43, 2, 'Servicios de limpieza', 1),
(44, 2, 'Consultoría general', 1),
(45, 3, 'Uniformes de trabajo', 1),
(46, 3, 'Incentivos para obreros', 1),
(47, 4, 'Capacitación para administrativos', 1),
(48, 4, 'Viajes administrativos', 1),
(49, 5, 'Madera', 1),
(50, 5, 'Vidrio', 1),
(51, 6, 'Herramientas manuales', 1),
(52, 6, 'Equipos de protección', 1),
(53, 7, 'Gestión de inventario', 1),
(54, 7, 'Manejo de residuos', 1),
(55, 8, 'Consultoría técnica', 1),
(56, 8, 'Consultoría de seguridad', 1),
(57, 9, 'Impuestos de importación', 1),
(58, 9, 'Impuestos de vehículos', 1),
(59, 10, 'Seguro de empleados', 1),
(60, 10, 'Seguro de responsabilidad civil', 1),
(61, 11, 'Campañas de email marketing', 1),
(62, 11, 'Publicidad en eventos', 1),
(63, 12, 'Ensayos en laboratorio', 1),
(64, 12, 'Desarrollo de software', 1),
(65, 13, 'Soporte técnico', 1),
(66, 13, 'Servicios en la nube', 1),
(67, 14, 'Mantenimiento de vehículos', 1),
(68, 14, 'Mantenimiento de redes eléctricas', 1),
(69, 15, 'Formación en seguridad laboral', 1),
(70, 15, 'Capacitación en nuevas tecnologías', 1),
(71, 16, 'Combustible', 1),
(72, 16, 'Alimentación durante viajes', 1),
(73, 17, 'Servicio de internet', 1),
(74, 17, 'Servicio telefónico', 1),
(75, 18, 'Auditoría interna', 1),
(76, 18, 'Supervisión de procedimientos', 1),
(77, 19, 'Subcontratistas', 1),
(78, 19, 'Logística externa', 1),
(79, 20, 'Gastos de representación', 1),
(80, 20, 'Costos legales', 1);

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
(1, 1, 57, 3249.00),
(43, 1, 1, 678.00),
(7, 2, 68, 7056.00),
(41, 1, 3, 857.56),
(5, 2, 43, 3678.00),
(33, 2, 1, 958.00),
(34, 2, 1, 1189.00),
(73, 2, 1, 1594.00),
(9, 1, 25, 1363.00),
(5, 1, 3, 1935.00);

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
(1, 'Expansión de Oficina Central', 'Proyecto para la ampliación y remodelación de la oficina central de la empresa', 1),
(2, 'Mensualidad', 'Año escolar 2024', 1);

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
(2, 0, 'UtechG', '123456', 'Miguel', 'Gutierrez'),
(3, 0, 'mariaeug_123', '123456', 'maria elena', 'gutierrez suarez');

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
  MODIFY `ID_Categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `gasto`
--
ALTER TABLE `gasto`
  MODIFY `ID_Gasto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `item`
--
ALTER TABLE `item`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT de la tabla `proyecto`
--
ALTER TABLE `proyecto`
  MODIFY `ID_Proyecto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `ID_Rol` tinyint(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `ID_Usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `gasto`
--
ALTER TABLE `gasto`
  ADD CONSTRAINT `gasto_ibfk_2` FOREIGN KEY (`ID_Item`) REFERENCES `presupuesto` (`id_item`) ON UPDATE CASCADE,
  ADD CONSTRAINT `gasto_ibfk_4` FOREIGN KEY (`ID_Usuario`) REFERENCES `usuario` (`ID_Usuario`) ON UPDATE CASCADE,
  ADD CONSTRAINT `gasto_ibfk_5` FOREIGN KEY (`ID_Proyecto`) REFERENCES `presupuesto` (`id_proyecto`) ON UPDATE CASCADE;

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
