-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 08-10-2024 a las 08:48:46
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mktusers`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_conexiones`
--

CREATE TABLE `tbl_conexiones` (
  `ID` int NOT NULL,
  `FECHA` date NOT NULL,
  `CONEXIONES` int DEFAULT '0',
  `ID_TAG` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_scripts`
--

CREATE TABLE `tbl_scripts` (
  `ID` int NOT NULL,
  `SCRIPT` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tbl_scripts`
--

INSERT INTO `tbl_scripts` (`ID`, `SCRIPT`) VALUES
(1, ':local usuario $user;\n\n/log info \"Usuario: $usuario\";\n\n/tool fetch url=\"http://192.168.168.24/mktusers/RegistroConexion?usuario=$usuario\" output=none;'),
(2, ':local descarga $\"bytes-out\"\n:local carga $\"bytes-in\"\n:local usuario $\"user\"\n\n/log info \"Usuario: $usuario - Descarga: $descarga bytes - Carga: $carga bytes\"\n\n/tool fetch url=\"http://192.168.168.24/mktusers/RegistroTrafico?descarga=$descarga&carga=$carga\" output=none;\n');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_tags`
--

CREATE TABLE `tbl_tags` (
  `ID` int NOT NULL,
  `NOMBRE` varchar(50) NOT NULL,
  `COLOR` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `DELETED_AT` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_trafico`
--

CREATE TABLE `tbl_trafico` (
  `ID` int NOT NULL,
  `FECHA` date NOT NULL,
  `BYTES_DESCARGA` bigint NOT NULL,
  `BYTES_CARGA` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_usuarios_mkt`
--

CREATE TABLE `tbl_usuarios_mkt` (
  `ID` int NOT NULL,
  `ID_MKT` varchar(50) DEFAULT NULL,
  `ID_TAG` int DEFAULT NULL,
  `NOMBRE` varchar(100) NOT NULL,
  `CONEXIONES` int DEFAULT NULL COMMENT ' Un inicio de sesion con cookie tambien cuenta',
  `FECHA_ALTA` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_usuarios_web`
--

CREATE TABLE `tbl_usuarios_web` (
  `ID` int NOT NULL,
  `USUARIO` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `PASSWORD` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ROL` int NOT NULL,
  `LAST_LOGIN` datetime DEFAULT NULL,
  `DELETED_AT` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_usuarios_web`
--

INSERT INTO `tbl_usuarios_web` (`ID`, `USUARIO`, `PASSWORD`, `ROL`, `LAST_LOGIN`, `DELETED_AT`) VALUES
(1, 'admin', '$2y$10$LvuCUQxwIdBHHN7.EqyS7.hDuEqBidzW22HCkXKXPrEAl.65KUan6', 1, '2024-10-08 09:02:13', NULL),
(2, 'noadmin', '$2y$10$j7nB79OHLFsseoR.ddNGIOUp3s1lYMOp77hfUxY2c1oH5o90GSsWa', 0, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tbl_conexiones`
--
ALTER TABLE `tbl_conexiones`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `tbl_scripts`
--
ALTER TABLE `tbl_scripts`
  ADD PRIMARY KEY (`ID`);
ALTER TABLE `tbl_scripts` ADD FULLTEXT KEY `SCRIPT` (`SCRIPT`);

--
-- Indices de la tabla `tbl_tags`
--
ALTER TABLE `tbl_tags`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `tbl_trafico`
--
ALTER TABLE `tbl_trafico`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `tbl_usuarios_mkt`
--
ALTER TABLE `tbl_usuarios_mkt`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `tbl_usuarios_web`
--
ALTER TABLE `tbl_usuarios_web`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tbl_conexiones`
--
ALTER TABLE `tbl_conexiones`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `tbl_scripts`
--
ALTER TABLE `tbl_scripts`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tbl_tags`
--
ALTER TABLE `tbl_tags`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `tbl_trafico`
--
ALTER TABLE `tbl_trafico`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tbl_usuarios_mkt`
--
ALTER TABLE `tbl_usuarios_mkt`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT de la tabla `tbl_usuarios_web`
--
ALTER TABLE `tbl_usuarios_web`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
