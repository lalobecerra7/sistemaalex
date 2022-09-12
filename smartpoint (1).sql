-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-09-2022 a las 23:09:46
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
-- Base de datos: `smartpoint`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajas`
--

CREATE TABLE `cajas` (
  `ID_Caja` int(11) NOT NULL,
  `FK_Sucursal` int(11) NOT NULL,
  `Nombre` tinytext NOT NULL,
  `Detalles` text NOT NULL,
  `Estado` tinyint(1) NOT NULL COMMENT 'Cerrada(0) Abierta(1)',
  `FK_Usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cajas`
--

INSERT INTO `cajas` (`ID_Caja`, `FK_Sucursal`, `Nombre`, `Detalles`, `Estado`, `FK_Usuario`) VALUES
(1, 1, 'Caja 1', '', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `ID_Cliente` int(11) NOT NULL,
  `Nombre` tinytext NOT NULL,
  `Email` tinytext NOT NULL,
  `Telefono` tinytext NOT NULL,
  `Segundo_Telefono` tinytext NOT NULL,
  `Calle` tinytext NOT NULL,
  `No_Interior` varchar(11) NOT NULL,
  `No_Exterior` varchar(11) NOT NULL,
  `Colonia` tinytext NOT NULL,
  `CP` varchar(11) NOT NULL,
  `Ciudad` tinytext NOT NULL,
  `Estado` tinytext NOT NULL,
  `Pais` tinytext NOT NULL,
  `RFC` varchar(30) NOT NULL,
  `Es_Empresa` tinyint(1) NOT NULL,
  `Contacto` tinytext NOT NULL,
  `Telefono_Contacto` tinytext NOT NULL,
  `Email_Contacto` tinytext NOT NULL,
  `Porcentaje_Descuento` double NOT NULL,
  `Credito` double NOT NULL,
  `Fecha_Registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE `departamentos` (
  `ID_Departamento` int(11) NOT NULL,
  `Nombre` tinytext NOT NULL,
  `Descripcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `departamentos`
--

INSERT INTO `departamentos` (`ID_Departamento`, `Nombre`, `Descripcion`) VALUES
(1, 'Ventas', 'Area de ventas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_caja`
--

CREATE TABLE `detalles_caja` (
  `ID_Detalle_Caja` int(11) NOT NULL,
  `FK_Caja` int(11) NOT NULL,
  `Fecha_Abrir` datetime NOT NULL,
  `Monto_Abrir` double NOT NULL,
  `FK_Usuario_Abrir` int(11) NOT NULL,
  `Fecha_Cierre` datetime NOT NULL,
  `Monto_Cierre` double NOT NULL,
  `FK_Usuario_Cierre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `detalles_caja`
--

INSERT INTO `detalles_caja` (`ID_Detalle_Caja`, `FK_Caja`, `Fecha_Abrir`, `Monto_Abrir`, `FK_Usuario_Abrir`, `Fecha_Cierre`, `Monto_Cierre`, `FK_Usuario_Cierre`) VALUES
(3, 1, '2022-02-02 11:03:00', 500, 1, '0000-00-00 00:00:00', 0, 0),
(4, 1, '2022-08-31 15:19:00', 0, 1, '0000-00-00 00:00:00', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_impuestos_productos`
--

CREATE TABLE `detalles_impuestos_productos` (
  `ID_Detalle_Im_Producto` int(11) NOT NULL,
  `FK_Producto` int(11) NOT NULL,
  `FK_Sucursal` int(11) NOT NULL,
  `FK_Impuesto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_impuestos_ticket`
--

CREATE TABLE `detalles_impuestos_ticket` (
  `ID_Detalle_Im_Ticket` int(11) NOT NULL,
  `FK_Ticket` int(11) NOT NULL,
  `FK_Impuesto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_productos`
--

CREATE TABLE `detalles_productos` (
  `ID_Detalle_Producto` int(11) NOT NULL,
  `FK_Producto` int(11) NOT NULL,
  `FK_Sucursal` int(11) NOT NULL,
  `Costo` double NOT NULL,
  `Precio` double NOT NULL,
  `Precio_Mayoreo` double NOT NULL,
  `Minimo` double NOT NULL,
  `Maximo` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_ventas`
--

CREATE TABLE `detalles_ventas` (
  `ID_Detalle_Venta` int(11) NOT NULL,
  `FK_Venta` int(11) NOT NULL,
  `FK_Producto` int(11) NOT NULL,
  `Descripcion` int(11) NOT NULL,
  `Precio` double NOT NULL,
  `Cantidad` double NOT NULL,
  `Descuento` double NOT NULL,
  `Total` double NOT NULL,
  `Devuelto` tinyint(1) NOT NULL,
  `Fecha_Devolucion` datetime NOT NULL,
  `Regreso_Inventario` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `general`
--

CREATE TABLE `general` (
  `ID_General` int(11) NOT NULL,
  `Calle` text NOT NULL,
  `No_Exterior` varchar(11) NOT NULL,
  `No_Interior` varchar(11) NOT NULL,
  `Colonia` tinytext NOT NULL,
  `CP` varchar(11) NOT NULL,
  `Ciudad` tinytext NOT NULL,
  `Estado` tinytext NOT NULL,
  `Pais` tinytext NOT NULL,
  `Telefono` int(11) NOT NULL,
  `Email` int(11) NOT NULL,
  `Imagen` text NOT NULL,
  `Ticket` tinyint(1) NOT NULL COMMENT 'Poner la misma imagen en ticket',
  `Imagen_Ticket` text NOT NULL,
  `Modena` tinytext NOT NULL,
  `Simbolo` varchar(10) NOT NULL,
  `Origen` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_caja`
--

CREATE TABLE `historial_caja` (
  `ID_Historial` int(11) NOT NULL,
  `FK_Detalle_Caja` int(11) NOT NULL,
  `FK_Usuario` int(11) NOT NULL,
  `Fecha_Uso` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `historial_caja`
--

INSERT INTO `historial_caja` (`ID_Historial`, `FK_Detalle_Caja`, `FK_Usuario`, `Fecha_Uso`) VALUES
(1, 4, 1, '2022-09-02 09:11:00'),
(2, 4, 1, '2022-09-02 11:25:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuestos`
--

CREATE TABLE `impuestos` (
  `ID_Impuesto` int(11) NOT NULL,
  `Nombre` varchar(30) NOT NULL,
  `Descripcion` text NOT NULL,
  `Porcentaje` double NOT NULL,
  `Ticket` tinyint(1) NOT NULL COMMENT 'Incluir en ticket en automatico',
  `Productos` tinyint(1) NOT NULL COMMENT 'Incluir en productos en automatico',
  `Predeterminado` tinyint(1) NOT NULL COMMENT 'Saber si el impuesto aparecera predeterminado'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `ID_Inventario` int(11) NOT NULL,
  `FK_Producto` int(11) NOT NULL,
  `Cantidad` int(11) NOT NULL,
  `FK_Sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `merma`
--

CREATE TABLE `merma` (
  `ID_Merma` int(11) NOT NULL,
  `FK_Producto` int(11) NOT NULL,
  `FK_Sucursal` int(11) NOT NULL,
  `Cantidad` double NOT NULL,
  `Fecha_Merma` datetime NOT NULL,
  `Fecha_Registro` datetime NOT NULL,
  `Motivo` varchar(300) NOT NULL,
  `FK_Usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `ID_Producto` int(11) NOT NULL,
  `Codigo` varchar(300) NOT NULL,
  `Descripcion` tinytext NOT NULL,
  `Tipo` int(2) NOT NULL COMMENT 'Producto(1) - Materia(2)',
  `Clase` varchar(30) NOT NULL COMMENT 'Pieza o Granel',
  `FK_Unidad` int(11) NOT NULL,
  `Poner_Unidad` tinyint(1) NOT NULL COMMENT 'Poner unidad en ticket (1)Si (0)No',
  `Costo` double NOT NULL COMMENT 'General',
  `Precio` double NOT NULL COMMENT 'General',
  `Precio_Mayoreo` double NOT NULL COMMENT 'General',
  `FK_Departamento` int(11) NOT NULL,
  `Detalles` text NOT NULL,
  `Minimo` double NOT NULL COMMENT 'General',
  `Maximo` double NOT NULL COMMENT 'General',
  `Fecha_Registro` datetime NOT NULL,
  `Imagen` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`ID_Producto`, `Codigo`, `Descripcion`, `Tipo`, `Clase`, `FK_Unidad`, `Poner_Unidad`, `Costo`, `Precio`, `Precio_Mayoreo`, `FK_Departamento`, `Detalles`, `Minimo`, `Maximo`, `Fecha_Registro`, `Imagen`) VALUES
(8, '2222', '333', 1, 'Pieza', 0, 1, 0, 4444, 0, 0, '', 0, 0, '2022-05-24 10:41:00', ''),
(10, '55', '22', 1, 'Pieza', 0, 1, 0, 33, 0, 0, 'PRUEBA5', 0, 0, '2022-05-24 15:17:00', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursales`
--

CREATE TABLE `sucursales` (
  `ID_Sucursal` int(11) NOT NULL,
  `Nombre` tinytext NOT NULL,
  `FK_Encargado` int(11) NOT NULL,
  `Calle` text NOT NULL,
  `No_Exterior` varchar(11) NOT NULL,
  `No_Interior` varchar(11) NOT NULL,
  `Colonia` tinytext NOT NULL,
  `CP` varchar(11) NOT NULL,
  `Ciudad` tinytext NOT NULL,
  `Estado` tinytext NOT NULL,
  `Pais` tinytext NOT NULL,
  `Email` text NOT NULL,
  `Telefono` tinytext NOT NULL,
  `Segundo_Telefono` tinytext NOT NULL,
  `RFC` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sucursales`
--

INSERT INTO `sucursales` (`ID_Sucursal`, `Nombre`, `FK_Encargado`, `Calle`, `No_Exterior`, `No_Interior`, `Colonia`, `CP`, `Ciudad`, `Estado`, `Pais`, `Email`, `Telefono`, `Segundo_Telefono`, `RFC`) VALUES
(1, 'Sucursal 1', 1, '', '', '', '', '', '', '', '', '', '', '', '');

--
-- Disparadores `sucursales`
--
DELIMITER $$
CREATE TRIGGER `tikect` AFTER INSERT ON `sucursales` FOR EACH ROW BEGIN
	INSERT INTO tickets SET FK_Sucursal = New.ID_Sucursal, Imagen = 1, Nombre = 1, Domicilio =1, Telefono = 1, Email = 1, Total_Letras = 1, Incluir_Mensaje = 1, tickets.Mensaje = "-- !Gracias por tu compra! --";
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickets`
--

CREATE TABLE `tickets` (
  `ID_Ticket` int(11) NOT NULL,
  `FK_Sucursal` int(11) NOT NULL,
  `Imagen` int(2) NOT NULL,
  `Ruta_Imagen` text NOT NULL,
  `Nombre` int(2) NOT NULL,
  `Domicilio` int(2) NOT NULL,
  `Telefono` int(2) NOT NULL,
  `Email` int(2) NOT NULL,
  `Total_Letras` tinyint(1) NOT NULL,
  `Incluir_Mensaje` tinyint(1) NOT NULL,
  `Mensaje` text NOT NULL,
  `Moneda` tinytext NOT NULL,
  `Simbolo` varchar(10) NOT NULL,
  `Origen` text NOT NULL COMMENT 'Pais'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tickets`
--

INSERT INTO `tickets` (`ID_Ticket`, `FK_Sucursal`, `Imagen`, `Ruta_Imagen`, `Nombre`, `Domicilio`, `Telefono`, `Email`, `Total_Letras`, `Incluir_Mensaje`, `Mensaje`, `Moneda`, `Simbolo`, `Origen`) VALUES
(1, 1, 1, '', 1, 1, 1, 1, 1, 1, '-- !Gracias por tu compra! --', '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traslados`
--

CREATE TABLE `traslados` (
  `ID_Traslado` int(11) NOT NULL,
  `FK_Producto` int(11) NOT NULL,
  `FK_Sucursal_Origen` int(11) NOT NULL,
  `FK_Sucursal_Destino` int(11) NOT NULL,
  `Cantidad` double NOT NULL,
  `Fecha_Traslado` datetime NOT NULL,
  `Fecha_Registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidades`
--

CREATE TABLE `unidades` (
  `ID_Unidad` int(11) NOT NULL,
  `Nombre` varchar(30) NOT NULL,
  `Abreviatura` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `unidades`
--

INSERT INTO `unidades` (`ID_Unidad`, `Nombre`, `Abreviatura`) VALUES
(1, 'Pieza', 'Pza.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `ID_Usuario` int(11) NOT NULL,
  `Nombre` tinytext NOT NULL,
  `Primer_Apellido` tinytext NOT NULL,
  `Segundo_Apellido` tinytext NOT NULL,
  `Correo` varchar(300) NOT NULL,
  `Contrasena` varchar(60) NOT NULL,
  `Tipo_Usuario` tinyint(1) NOT NULL,
  `Permisos` text NOT NULL,
  `BD` int(11) NOT NULL,
  `Estatus` tinyint(1) NOT NULL,
  `Intentos` int(11) NOT NULL,
  `Ultimo_Intento` datetime NOT NULL,
  `Tiempo_Inicio` datetime NOT NULL,
  `Tiempo_Final` datetime NOT NULL,
  `Foto` text NOT NULL,
  `Temporal` tinyint(1) NOT NULL,
  `Activo` tinyint(1) NOT NULL,
  `Tipo_Login` int(2) NOT NULL,
  `Conectado` tinyint(1) NOT NULL,
  `Fecha_Alta` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`ID_Usuario`, `Nombre`, `Primer_Apellido`, `Segundo_Apellido`, `Correo`, `Contrasena`, `Tipo_Usuario`, `Permisos`, `BD`, `Estatus`, `Intentos`, `Ultimo_Intento`, `Tiempo_Inicio`, `Tiempo_Final`, `Foto`, `Temporal`, `Activo`, `Tipo_Login`, `Conectado`, `Fecha_Alta`) VALUES
(1, 'Juan', 'Garcia', '', 'jramongarciaangel@gmail.com', '$2y$12$CyZMz5cXGa4AKAnRkh7ZX.ccT4TZC80E0Yb1wh0QpheO4oSh1PDgK', 1, '', 0, 0, 0, '2021-12-09 09:44:00', '2022-09-06 08:20:00', '2021-11-24 10:10:43', '', 0, 1, 1, 0, '2021-04-24 19:20:36');

--
-- Disparadores `usuarios`
--
DELIMITER $$
CREATE TRIGGER `agregar_permisos` AFTER INSERT ON `usuarios` FOR EACH ROW BEGIN
	
    IF New.Tipo_Usuario = 1 THEN
    
    	INSERT INTO suscripciones VALUES (null, New.ID_Usuario, NOW(), DATE(DATE_ADD(NOW(), INTERVAL 1 MONTH)), 0);
        
    END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `ID_Venta` int(11) NOT NULL,
  `FK_Usuario` int(11) NOT NULL COMMENT 'Usuario que hizo la venta',
  `FK_Caja` int(11) NOT NULL,
  `FK_Cliente` int(11) NOT NULL,
  `Descuento` double NOT NULL,
  `Total` double NOT NULL,
  `Tipo_Pago` varchar(60) NOT NULL COMMENT 'Efectivo, Tarjeta, credito, vale, mixto, si es mixto que ingrese cuanto de cada uno, si es por credito checar si el cliente tiene credito',
  `Pago_efectivo` double NOT NULL,
  `Pago_Tarjeta` double NOT NULL,
  `Pago_Credito` double NOT NULL,
  `Pago_Vale` double NOT NULL,
  `Cambio` double NOT NULL,
  `Notas` text NOT NULL,
  `Fecha_Registro` datetime NOT NULL,
  `Cancelada` tinyint(1) NOT NULL,
  `Fecha_Cancelacion` datetime NOT NULL,
  `Regreso_Inventario` tinyint(1) NOT NULL COMMENT 'Si regresa el ticket completo'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cajas`
--
ALTER TABLE `cajas`
  ADD PRIMARY KEY (`ID_Caja`),
  ADD KEY `FK_Sucursal` (`FK_Sucursal`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`ID_Cliente`);

--
-- Indices de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`ID_Departamento`);

--
-- Indices de la tabla `detalles_caja`
--
ALTER TABLE `detalles_caja`
  ADD PRIMARY KEY (`ID_Detalle_Caja`),
  ADD KEY `FK_Caja` (`FK_Caja`);

--
-- Indices de la tabla `detalles_impuestos_productos`
--
ALTER TABLE `detalles_impuestos_productos`
  ADD PRIMARY KEY (`ID_Detalle_Im_Producto`),
  ADD KEY `FK_Impuesto` (`FK_Impuesto`),
  ADD KEY `FK_Producto` (`FK_Producto`),
  ADD KEY `FK_Sucursal` (`FK_Sucursal`);

--
-- Indices de la tabla `detalles_impuestos_ticket`
--
ALTER TABLE `detalles_impuestos_ticket`
  ADD PRIMARY KEY (`ID_Detalle_Im_Ticket`),
  ADD KEY `FK_Impuesto` (`FK_Impuesto`),
  ADD KEY `FK_Ticket` (`FK_Ticket`);

--
-- Indices de la tabla `detalles_productos`
--
ALTER TABLE `detalles_productos`
  ADD PRIMARY KEY (`ID_Detalle_Producto`),
  ADD KEY `FK_Producto` (`FK_Producto`),
  ADD KEY `FK_Sucursal` (`FK_Sucursal`);

--
-- Indices de la tabla `detalles_ventas`
--
ALTER TABLE `detalles_ventas`
  ADD PRIMARY KEY (`ID_Detalle_Venta`),
  ADD KEY `FK_Venta` (`FK_Venta`);

--
-- Indices de la tabla `general`
--
ALTER TABLE `general`
  ADD PRIMARY KEY (`ID_General`);

--
-- Indices de la tabla `historial_caja`
--
ALTER TABLE `historial_caja`
  ADD PRIMARY KEY (`ID_Historial`),
  ADD KEY `FK_Detalle_Caja` (`FK_Detalle_Caja`),
  ADD KEY `FK_Usuario` (`FK_Usuario`);

--
-- Indices de la tabla `impuestos`
--
ALTER TABLE `impuestos`
  ADD PRIMARY KEY (`ID_Impuesto`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`ID_Inventario`),
  ADD KEY `FK_Sucursal` (`FK_Sucursal`),
  ADD KEY `FK_Producto` (`FK_Producto`);

--
-- Indices de la tabla `merma`
--
ALTER TABLE `merma`
  ADD PRIMARY KEY (`ID_Merma`),
  ADD KEY `FK_Producto` (`FK_Producto`),
  ADD KEY `FK_Sucursal` (`FK_Sucursal`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`ID_Producto`),
  ADD UNIQUE KEY `Codigo` (`Codigo`);

--
-- Indices de la tabla `sucursales`
--
ALTER TABLE `sucursales`
  ADD PRIMARY KEY (`ID_Sucursal`);

--
-- Indices de la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ID_Ticket`),
  ADD KEY `FK_Sucursal` (`FK_Sucursal`);

--
-- Indices de la tabla `traslados`
--
ALTER TABLE `traslados`
  ADD PRIMARY KEY (`ID_Traslado`),
  ADD KEY `FK_Sucursal_Destino` (`FK_Sucursal_Destino`),
  ADD KEY `FK_Sucursal_Origen` (`FK_Sucursal_Origen`),
  ADD KEY `FK_Producto` (`FK_Producto`);

--
-- Indices de la tabla `unidades`
--
ALTER TABLE `unidades`
  ADD PRIMARY KEY (`ID_Unidad`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID_Usuario`),
  ADD UNIQUE KEY `Correo` (`Correo`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`ID_Venta`),
  ADD KEY `FK_Usuario` (`FK_Usuario`),
  ADD KEY `FK_Caja` (`FK_Caja`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cajas`
--
ALTER TABLE `cajas`
  MODIFY `ID_Caja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `ID_Cliente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `ID_Departamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalles_caja`
--
ALTER TABLE `detalles_caja`
  MODIFY `ID_Detalle_Caja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `detalles_impuestos_productos`
--
ALTER TABLE `detalles_impuestos_productos`
  MODIFY `ID_Detalle_Im_Producto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalles_impuestos_ticket`
--
ALTER TABLE `detalles_impuestos_ticket`
  MODIFY `ID_Detalle_Im_Ticket` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalles_productos`
--
ALTER TABLE `detalles_productos`
  MODIFY `ID_Detalle_Producto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalles_ventas`
--
ALTER TABLE `detalles_ventas`
  MODIFY `ID_Detalle_Venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `general`
--
ALTER TABLE `general`
  MODIFY `ID_General` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_caja`
--
ALTER TABLE `historial_caja`
  MODIFY `ID_Historial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `impuestos`
--
ALTER TABLE `impuestos`
  MODIFY `ID_Impuesto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `ID_Inventario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `merma`
--
ALTER TABLE `merma`
  MODIFY `ID_Merma` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `ID_Producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `sucursales`
--
ALTER TABLE `sucursales`
  MODIFY `ID_Sucursal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ID_Ticket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `traslados`
--
ALTER TABLE `traslados`
  MODIFY `ID_Traslado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `unidades`
--
ALTER TABLE `unidades`
  MODIFY `ID_Unidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID_Usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `ID_Venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cajas`
--
ALTER TABLE `cajas`
  ADD CONSTRAINT `cajas_ibfk_1` FOREIGN KEY (`FK_Sucursal`) REFERENCES `sucursales` (`ID_Sucursal`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalles_caja`
--
ALTER TABLE `detalles_caja`
  ADD CONSTRAINT `detalles_caja_ibfk_1` FOREIGN KEY (`FK_Caja`) REFERENCES `cajas` (`ID_Caja`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalles_impuestos_productos`
--
ALTER TABLE `detalles_impuestos_productos`
  ADD CONSTRAINT `detalles_impuestos_productos_ibfk_1` FOREIGN KEY (`FK_Impuesto`) REFERENCES `impuestos` (`ID_Impuesto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalles_impuestos_productos_ibfk_2` FOREIGN KEY (`FK_Producto`) REFERENCES `productos` (`ID_Producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalles_impuestos_productos_ibfk_3` FOREIGN KEY (`FK_Sucursal`) REFERENCES `sucursales` (`ID_Sucursal`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalles_impuestos_ticket`
--
ALTER TABLE `detalles_impuestos_ticket`
  ADD CONSTRAINT `detalles_impuestos_ticket_ibfk_1` FOREIGN KEY (`FK_Impuesto`) REFERENCES `impuestos` (`ID_Impuesto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalles_impuestos_ticket_ibfk_2` FOREIGN KEY (`FK_Ticket`) REFERENCES `tickets` (`ID_Ticket`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalles_productos`
--
ALTER TABLE `detalles_productos`
  ADD CONSTRAINT `detalles_productos_ibfk_1` FOREIGN KEY (`FK_Producto`) REFERENCES `productos` (`ID_Producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalles_productos_ibfk_2` FOREIGN KEY (`FK_Sucursal`) REFERENCES `sucursales` (`ID_Sucursal`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalles_ventas`
--
ALTER TABLE `detalles_ventas`
  ADD CONSTRAINT `detalles_ventas_ibfk_1` FOREIGN KEY (`FK_Venta`) REFERENCES `ventas` (`ID_Venta`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `historial_caja`
--
ALTER TABLE `historial_caja`
  ADD CONSTRAINT `historial_caja_ibfk_1` FOREIGN KEY (`FK_Detalle_Caja`) REFERENCES `detalles_caja` (`ID_Detalle_Caja`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `historial_caja_ibfk_2` FOREIGN KEY (`FK_Usuario`) REFERENCES `usuarios` (`ID_Usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `inventario_ibfk_1` FOREIGN KEY (`FK_Sucursal`) REFERENCES `sucursales` (`ID_Sucursal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inventario_ibfk_2` FOREIGN KEY (`FK_Producto`) REFERENCES `productos` (`ID_Producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `merma`
--
ALTER TABLE `merma`
  ADD CONSTRAINT `merma_ibfk_1` FOREIGN KEY (`FK_Producto`) REFERENCES `productos` (`ID_Producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `merma_ibfk_2` FOREIGN KEY (`FK_Sucursal`) REFERENCES `sucursales` (`ID_Sucursal`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`FK_Sucursal`) REFERENCES `sucursales` (`ID_Sucursal`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `traslados`
--
ALTER TABLE `traslados`
  ADD CONSTRAINT `traslados_ibfk_1` FOREIGN KEY (`FK_Sucursal_Destino`) REFERENCES `sucursales` (`ID_Sucursal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `traslados_ibfk_2` FOREIGN KEY (`FK_Sucursal_Origen`) REFERENCES `sucursales` (`ID_Sucursal`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `traslados_ibfk_3` FOREIGN KEY (`FK_Producto`) REFERENCES `productos` (`ID_Producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`FK_Usuario`) REFERENCES `usuarios` (`ID_Usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`FK_Caja`) REFERENCES `cajas` (`ID_Caja`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
