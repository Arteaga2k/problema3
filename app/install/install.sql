-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-12-2014 a las 21:19:27
-- Versión del servidor: 5.6.20
-- Versión de PHP: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prueba`
--

DROP TABLE IF EXISTS `prueba`;
CREATE TABLE IF NOT EXISTS `prueba` (
`id` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=10 ;

--
-- Volcado de datos para la tabla `prueba`
--

INSERT INTO `prueba` (`id`, `nombre`) VALUES
(1, 'carlos'),
(2, 'carlos'),
(3, 'carlos'),
(4, 'carlos'),
(5, 'carlos'),
(6, 'carlos'),
(7, 'carlos'),
(8, 'carlos'),
(9, 'carlos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_envio`
--

DROP TABLE IF EXISTS `tbl_envio`;
CREATE TABLE IF NOT EXISTS `tbl_envio` (
`id_envio` int(11) NOT NULL,
  `direccion` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `poblacion` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `codpostal` int(5) NOT NULL,
  `provincia` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `estado` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `fec_creacion` date NOT NULL,
  `fec_entrega` date NOT NULL,
  `observaciones` varchar(300) COLLATE utf8_spanish_ci NOT NULL,
  `nombre` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `apellido1` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `apellido2` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `razonsocial` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `telefono1` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `telefono2` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `zona_entrega` int(11) NOT NULL,
  `zona_recepcion` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=68 ;

--
-- Volcado de datos para la tabla `tbl_envio`
--

INSERT INTO `tbl_envio` (`id_envio`, `direccion`, `poblacion`, `codpostal`, `provincia`, `email`, `estado`, `fec_creacion`, `fec_entrega`, `observaciones`, `nombre`, `apellido1`, `apellido2`, `razonsocial`, `telefono1`, `telefono2`, `zona_entrega`, `zona_recepcion`) VALUES
(6, 'avd huelva 9', 'aljaraque', 21110, '2', 'cav1662@hotmail.com', 'e', '2014-11-27', '2014-11-29', '&#60;img src=&#34;http://php.net/manual/es/images/c0d23d2d6769e53e24a1b3136c064577-php_logo.png&#34;&#62;&#60;br&#62;', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(7, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'e', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(8, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(9, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(10, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(11, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(12, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(13, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(14, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(15, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(16, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(17, 'avd huelva 6', 'aljaraque', 21110, '2', 'cav1662@hotmail.com', 'd', '2014-11-27', '2014-11-29', 'defectuoso', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(18, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(19, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(20, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(21, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(22, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(23, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(24, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(25, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(26, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(27, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(28, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(29, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(30, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(31, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(32, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(33, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(34, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(35, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(36, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(37, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(38, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(39, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(40, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(41, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(42, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(43, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(44, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(45, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(46, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(47, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(48, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(49, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(50, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(51, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(52, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(53, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(54, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(55, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(56, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(57, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(58, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(59, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(60, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(61, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(62, 'avd huelva 6', 'aljaraque', 21110, '20', 'cav1662@hotmail.com', 'p', '2014-11-27', '2014-11-29', '', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(63, 'avd huelva 6', 'aljaraque', 21110, '2', 'cav1662@hotmail.com', 'd', '2014-11-27', '2014-11-29', '&#60;br&#62;', 'carlos', 'arteaga', 'virella', 'onuba sl', '65156156', '45456456', 2, 2),
(64, 'direccion', 'carmona', 21110, '9', 'ejemplo@gmail.com', 'p', '2014-09-16', '2015-01-31', 'probando observaciones', 'nombre', 'jejejeje', 'jejejeje', 'pepe sl', '1212121', '1212121', 4, 4),
(66, 'sdsd', 'sdsdsd', 21110, '2', 'cav1662@hotmail.com', 'd', '2014-11-29', '0000-00-00', 'sdaas', 'sdsds', 'sdsds', 'sdsds', 'sds', '1212121', '121212', 2, 2),
(67, 'zzzzzzz', 'zzzz', 21110, '50', 'zzz@zgo.com', 'p', '2014-12-01', '0000-00-00', 'z', 'zzz', 'zzz', 'zz', 'zz', '11111', '1111', 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_provincia`
--

DROP TABLE IF EXISTS `tbl_provincia`;
CREATE TABLE IF NOT EXISTS `tbl_provincia` (
  `id_provincia` smallint(6) DEFAULT NULL,
  `provincia` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tbl_provincia`
--

INSERT INTO `tbl_provincia` (`id_provincia`, `provincia`) VALUES
(2, 'Albacete'),
(3, 'Alicante/Alacant'),
(4, 'Almería'),
(1, 'Araba/Álava'),
(33, 'Asturias'),
(5, 'Ávila'),
(6, 'Badajoz'),
(7, 'Balears, Illes'),
(8, 'Barcelona'),
(48, 'Bizkaia'),
(9, 'Burgos'),
(10, 'Cáceres'),
(11, 'Cádiz'),
(39, 'Cantabria'),
(12, 'Castellón/Castelló'),
(51, 'Ceuta'),
(13, 'Ciudad Real'),
(14, 'Córdoba'),
(15, 'Coruña, A'),
(16, 'Cuenca'),
(20, 'Gipuzkoa'),
(17, 'Girona'),
(18, 'Granada'),
(19, 'Guadalajara'),
(21, 'Huelva'),
(22, 'Huesca'),
(23, 'Jaén'),
(24, 'León'),
(27, 'Lugo'),
(25, 'Lleida'),
(28, 'Madrid'),
(29, 'Málaga'),
(52, 'Melilla'),
(30, 'Murcia'),
(31, 'Navarra'),
(32, 'Ourense'),
(34, 'Palencia'),
(35, 'Palmas, Las'),
(36, 'Pontevedra'),
(26, 'Rioja, La'),
(37, 'Salamanca'),
(38, 'Santa Cruz de Tenerife'),
(40, 'Segovia'),
(41, 'Sevilla'),
(42, 'Soria'),
(43, 'Tarragona'),
(44, 'Teruel'),
(45, 'Toledo'),
(46, 'Valencia/València'),
(47, 'Valladolid'),
(49, 'Zamora'),
(50, 'Zaragoza');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_usuario`
--

DROP TABLE IF EXISTS `tbl_usuario`;
CREATE TABLE IF NOT EXISTS `tbl_usuario` (
`id_usuario` int(11) NOT NULL,
  `username` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `password_hash` longtext COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `remember_token` varchar(10000) COLLATE utf8_spanish_ci NOT NULL,
  `configuracion` mediumtext COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=10 ;

--
-- Volcado de datos para la tabla `tbl_usuario`
--

INSERT INTO `tbl_usuario` (`id_usuario`, `username`, `password_hash`, `email`, `remember_token`, `configuracion`) VALUES
(6, 'arteaga', '$2y$10$zky33SibzmrDUngOmPb1xeKZAREQFtoa2ZXK0MjGJKDoWH3SKYSWa', 'cav1662@hotmail.com', 'c28ab140de4d36f05fefef710baa33efb0ee7ada2ec7e8fb41b2dfa62a6fef64', '{"TEMA":"grey-theme.css","COOKIE_RUNTIME":"3434","REGS_PAG":"3","AVATAR":"glyphicon glyphicon-send"}'),
(9, 'Push push', '$2y$10$heUhCKd8PFVQOaWCNT59CudSd6Vtf/QyCrQGNr2ZXpviH5N0z2cMC', 'user@kenollega.com', '', '{"COOKIE_RUNTIME":1209600,"REGS_PAG":10,"AVATAR":"glyphicon glyphicon-user","TEMA":"blue-theme.css"}');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_zona`
--

DROP TABLE IF EXISTS `tbl_zona`;
CREATE TABLE IF NOT EXISTS `tbl_zona` (
`id_zona` int(11) NOT NULL,
  `nombrezona` varchar(60) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `tbl_zona`
--

INSERT INTO `tbl_zona` (`id_zona`, `nombrezona`) VALUES
(2, 'administracion'),
(3, 'recursos humanos'),
(4, 'almacenr');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `prueba`
--
ALTER TABLE `prueba`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tbl_envio`
--
ALTER TABLE `tbl_envio`
 ADD PRIMARY KEY (`id_envio`), ADD KEY `zona_entrega` (`zona_entrega`), ADD KEY `zona_recepcion` (`zona_recepcion`);

--
-- Indices de la tabla `tbl_usuario`
--
ALTER TABLE `tbl_usuario`
 ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `tbl_zona`
--
ALTER TABLE `tbl_zona`
 ADD PRIMARY KEY (`id_zona`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `prueba`
--
ALTER TABLE `prueba`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `tbl_envio`
--
ALTER TABLE `tbl_envio`
MODIFY `id_envio` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=68;
--
-- AUTO_INCREMENT de la tabla `tbl_usuario`
--
ALTER TABLE `tbl_usuario`
MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `tbl_zona`
--
ALTER TABLE `tbl_zona`
MODIFY `id_zona` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tbl_envio`
--
ALTER TABLE `tbl_envio`
ADD CONSTRAINT `tbl_envio_ibfk_1` FOREIGN KEY (`zona_entrega`) REFERENCES `tbl_zona` (`id_zona`),
ADD CONSTRAINT `tbl_envio_ibfk_2` FOREIGN KEY (`zona_recepcion`) REFERENCES `tbl_zona` (`id_zona`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
