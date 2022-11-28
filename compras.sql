-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-11-2022 a las 15:41:05
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `smartpoi_negocio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `ID_Compra` int(11) NOT NULL COMMENT 'Clave Ãºnica de registro de la compra',
  `Fecha` datetime NOT NULL COMMENT 'Fecha y hora de la compra',
  `FK_Usuario` int(11) NOT NULL COMMENT 'Clave de referencia del empleado que realizo la compra',
  `FK_Proveedor` int(11) NOT NULL COMMENT 'Clave de referencia del proveedor al que se le hizo la compra',
  `Total` double NOT NULL COMMENT 'Monto total de la compra',
  `Estatus` varchar(50) NOT NULL COMMENT 'Muestra el estado de la factura generada si ya esta pagada o pendiente de pago.',
  `Clase` int(11) NOT NULL COMMENT 'Muestra la clase a la que pertenece, compra de materia prima o compra de producto a la venta.',
  `Dias_Pagar` int(11) NOT NULL,
  `Tipo_Compra` varchar(50) NOT NULL,
  `Fecha_Credito` datetime NOT NULL,
  `FK_Caja` int(11) NOT NULL,
  `FK_Sucursal` int(11) NOT NULL,
  `Descuento` double NOT NULL,
  `Impuestos` tinytext NOT NULL,
  `Anticipo` double NOT NULL,
  `Ordenes_Compra` tinytext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`ID_Compra`),
  ADD KEY `empleado` (`FK_Usuario`,`FK_Proveedor`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `ID_Compra` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave Ãºnica de registro de la compra';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
