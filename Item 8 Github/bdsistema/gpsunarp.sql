-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 19-05-2014 a las 02:34:25
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `gpsunarp`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `pedido_externo`(IN id_titulo INT,IN id_usuario_sol INT,IN Tipo_ped INT,IN Fecha_ped VARCHAR(20),IN numero_atencion VARCHAR(50),IN coment VARCHAR(50))
BEGIN

INSERT INTO pedidos_titulo (SELECT NULL,id_titulo,id_usuario_sol,1,''); 

SET @var1  = (select MAX(id_pedido_titulo) from pedidos_titulo); 
SET @var2  = (select lpad(@var1,6,'0'));
UPDATE pedidos_titulo SET numero_pedido = @var2  where id_pedido_titulo = @var1;

INSERT INTO pedidos (SELECT NULL,id_usuario_sol,Tipo_ped,Fecha_ped,'','',numero_atencion,@var1,coment);


END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pedido_externo_v2`(OUT salida INT,IN id_titulo INT,IN id_usuario_sol INT,IN Tipo_ped INT,IN Fecha_ped VARCHAR(20),IN numero_atencion VARCHAR(50),IN coment VARCHAR(50))
BEGIN

INSERT INTO pedidos_titulo (SELECT NULL,id_titulo,id_usuario_sol,1,''); 

SET @var1  = (select MAX(id_pedido_titulo) from pedidos_titulo); 
SET @var2  = (select lpad(@var1,6,'0'));
UPDATE pedidos_titulo SET numero_pedido = @var2  where id_pedido_titulo = @var1;

INSERT INTO pedidos (SELECT NULL,id_usuario_sol,Tipo_ped,Fecha_ped,'','',numero_atencion,@var1,coment);

SET salida = (select MAX(id_pedido) from pedidos);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pedido_externo_v3`(OUT id_pedtit INT,OUT salida INT,IN id_titulo INT,IN id_usuario_sol INT,IN Tipo_ped INT,IN Fecha_ped VARCHAR(20),IN numero_atencion VARCHAR(50),IN coment VARCHAR(50))
BEGIN

INSERT INTO pedidos_titulo (SELECT NULL,id_titulo,id_usuario_sol,1,''); 

SET @var1  = (select MAX(id_pedido_titulo) from pedidos_titulo); 
SET id_pedtit  = (select MAX(id_pedido_titulo) from pedidos_titulo);
SET @var2  = (select lpad(@var1,6,'0'));
UPDATE pedidos_titulo SET numero_pedido = @var2  where id_pedido_titulo = @var1;

INSERT INTO pedidos (SELECT NULL,id_usuario_sol,Tipo_ped,Fecha_ped,'','',numero_atencion,@var1,coment);

SET salida = (select MAX(id_pedido) from pedidos);

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_pedido`
--

CREATE TABLE IF NOT EXISTS `estados_pedido` (
  `id_estado_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_estado_pedido`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE IF NOT EXISTS `pedidos` (
  `id_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `fk_Id_usuario` int(11) NOT NULL,
  `Tipo_Pedido` int(11) NOT NULL,
  `fecha_pedido` datetime DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL,
  `fecha_devolucion` datetime DEFAULT NULL,
  `nro_atencion` varchar(8) DEFAULT NULL,
  `id_pedido_tit` int(11) DEFAULT NULL,
  `comentario` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id_pedido`),
  KEY `fk_ped_usua` (`fk_Id_usuario`),
  KEY `fk_tip_ped` (`Tipo_Pedido`),
  KEY `fk_ped_tit_ped` (`id_pedido_tit`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos_titulo`
--

CREATE TABLE IF NOT EXISTS `pedidos_titulo` (
  `id_pedido_titulo` int(10) NOT NULL AUTO_INCREMENT,
  `fk_Id_Titulo` int(11) NOT NULL,
  `fk_Id_Usuario` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `numero_pedido` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_pedido_titulo`),
  KEY `fk_ped_usu` (`fk_Id_Usuario`),
  KEY `fk_tit_pedi` (`fk_Id_Titulo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sunarp_area`
--

CREATE TABLE IF NOT EXISTS `sunarp_area` (
  `id_area` int(11) NOT NULL AUTO_INCREMENT,
  `Codigo` char(5) DEFAULT NULL,
  `Nombre` varchar(20) DEFAULT NULL,
  `Anexo` char(4) DEFAULT NULL,
  `Piso` char(4) DEFAULT NULL,
  `Direccion` varchar(20) DEFAULT NULL,
  `Descripcion` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_area`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sunarp_oficina`
--

CREATE TABLE IF NOT EXISTS `sunarp_oficina` (
  `id_oficina` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` char(5) DEFAULT NULL,
  `fk_area` int(11) DEFAULT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `anexo` char(5) DEFAULT NULL,
  `piso` char(5) DEFAULT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_oficina`),
  KEY `fk_area_of` (`fk_area`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sunarp_usuarios`
--

CREATE TABLE IF NOT EXISTS `sunarp_usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` char(5) DEFAULT NULL,
  `fk_Id_Tipo_Usuario` int(11) NOT NULL,
  `fk_Id_Tipo_Oficina` int(11) NOT NULL,
  `usuario` varchar(10) DEFAULT NULL,
  `passw` varchar(10) DEFAULT NULL,
  `ape_paterno` varchar(20) DEFAULT NULL,
  `ape_materno` varchar(20) DEFAULT NULL,
  `nombres` varchar(20) DEFAULT NULL,
  `dni` char(10) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `celular` int(10) DEFAULT NULL,
  `telefono` int(10) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `fk_tip_usu` (`fk_Id_Tipo_Usuario`),
  KEY `fk_tip_ofi` (`fk_Id_Tipo_Oficina`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sunar_tipos_usuario`
--

CREATE TABLE IF NOT EXISTS `sunar_tipos_usuario` (
  `id_tipo_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_tipo_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_registro`
--

CREATE TABLE IF NOT EXISTS `tipos_registro` (
  `id_tipo_registro` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_tipo_registro`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `titulos`
--

CREATE TABLE IF NOT EXISTS `titulos` (
  `id_titulo` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` char(5) DEFAULT NULL,
  `fk_Id_Tipo_Registro_Titulo` int(11) NOT NULL,
  `fk_Id_Tomo` int(11) DEFAULT NULL,
  `numero` varchar(9) DEFAULT NULL,
  `anio` char(4) DEFAULT NULL,
  `mes` char(2) DEFAULT NULL,
  `numero_fojas` int(11) DEFAULT NULL,
  `numero_planos` int(11) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `fk_usuario_propietario` int(11) NOT NULL,
  `nombrearchivo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_titulo`),
  KEY `fk_tip_reg` (`fk_Id_Tipo_Registro_Titulo`),
  KEY `fk_tomo` (`fk_Id_Tomo`),
  KEY `fk_usuario` (`fk_usuario_propietario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tomos`
--

CREATE TABLE IF NOT EXISTS `tomos` (
  `id_tomo` int(11) NOT NULL AUTO_INCREMENT,
  `mes` char(2) DEFAULT NULL,
  `anio` char(4) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `fk_Id_Estado_Pedido` int(11) NOT NULL,
  `titulo_inicial` varchar(9) DEFAULT NULL,
  `titulo_final` varchar(9) DEFAULT NULL,
  `nr_tomo` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_tomo`),
  KEY `fk_est_ped` (`fk_Id_Estado_Pedido`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_ped_tit_ped` FOREIGN KEY (`id_pedido_tit`) REFERENCES `pedidos_titulo` (`id_pedido_titulo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ped_usua` FOREIGN KEY (`fk_Id_usuario`) REFERENCES `sunarp_usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pedidos_titulo`
--
ALTER TABLE `pedidos_titulo`
  ADD CONSTRAINT `fk_ped_usu` FOREIGN KEY (`fk_Id_Usuario`) REFERENCES `sunarp_usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tit_pedi` FOREIGN KEY (`fk_Id_Titulo`) REFERENCES `titulos` (`id_titulo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `sunarp_oficina`
--
ALTER TABLE `sunarp_oficina`
  ADD CONSTRAINT `fk_area_of` FOREIGN KEY (`fk_area`) REFERENCES `sunarp_area` (`id_area`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `sunarp_usuarios`
--
ALTER TABLE `sunarp_usuarios`
  ADD CONSTRAINT `fk_tip_ofi` FOREIGN KEY (`fk_Id_Tipo_Oficina`) REFERENCES `sunarp_oficina` (`id_oficina`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tip_usu` FOREIGN KEY (`fk_Id_Tipo_Usuario`) REFERENCES `sunar_tipos_usuario` (`id_tipo_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `titulos`
--
ALTER TABLE `titulos`
  ADD CONSTRAINT `fk_tip_reg` FOREIGN KEY (`fk_Id_Tipo_Registro_Titulo`) REFERENCES `tipos_registro` (`id_tipo_registro`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tomo` FOREIGN KEY (`fk_Id_Tomo`) REFERENCES `tomos` (`id_tomo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`fk_usuario_propietario`) REFERENCES `sunarp_usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tomos`
--
ALTER TABLE `tomos`
  ADD CONSTRAINT `fk_est_ped` FOREIGN KEY (`fk_Id_Estado_Pedido`) REFERENCES `estados_pedido` (`id_estado_pedido`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
