-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-11-2022 a las 15:40:56
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
-- Estructura de tabla para la tabla `detalle_compras`
--

CREATE TABLE `detalle_compras` (
  `ID_Detalle_Compra` int(11) NOT NULL COMMENT 'Clave Ãºnica de registro del detalle',
  `FK_Compra` int(11) NOT NULL COMMENT 'Clave de referencia de la compra a la que le atribuye el detalle',
  `FK_Producto` int(11) NOT NULL COMMENT 'Clave de referencia del producto que fue comprado',
  `FK_Sucursal` int(11) NOT NULL,
  `FK_Presentacion` int(11) NOT NULL,
  `Costo` double NOT NULL,
  `Cantidad` double NOT NULL COMMENT 'Cantidad del producto que fue comprado',
  `Subtotal` double NOT NULL COMMENT 'Subtotal del detalle de la compra'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD PRIMARY KEY (`ID_Detalle_Compra`),
  ADD KEY `compra` (`FK_Compra`,`FK_Producto`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  MODIFY `ID_Detalle_Compra` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave Ãºnica de registro del detalle';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
