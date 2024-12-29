-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 25, 2024 at 05:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventario`
--
CREATE DATABASE IF NOT EXISTS `inventario` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `inventario`;

-- --------------------------------------------------------

--
-- Table structure for table `categoria`
--

CREATE TABLE `categoria` (
  `categoria_id` int(8) NOT NULL,
  `categoria_nombre` varchar(64) NOT NULL,
  `categoria_ubicacion` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `producto`
--

CREATE TABLE `producto` (
  `producto_id` int(16) NOT NULL,
  `producto_codigo` varchar(64) NOT NULL,
  `producto_nombre` varchar(64) NOT NULL,
  `producto_precio` decimal(30,2) NOT NULL,
  `producto_stock` int(32) NOT NULL,
  `producto_foto` varchar(512) NOT NULL,
  `categoria_id` int(8) NOT NULL,
  `usuario_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `usuario_id` int(8) NOT NULL,
  `usuario_nombre` varchar(32) NOT NULL,
  `usuario_apellido` varchar(32) NOT NULL,
  `usuario_usuario` varchar(16) NOT NULL,
  `usuario_clave` varchar(256) NOT NULL,
  `usuario_email` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`usuario_id`, `usuario_nombre`, `usuario_apellido`, `usuario_usuario`, `usuario_clave`, `usuario_email`) VALUES
(3, 'Jose', 'Hernandez', 'hdez59', '$2y$10$CWdIkcOKbdy8zOclab.2iO439TBCguvVcuXtIrGMWe817yCQ339iC', 'jose@test.com'),
(4, 'Fer', 'Hernandez', 'fer2000', '$2y$10$Iik9RTgMy/Tpcpw1HynUYelzsisfPWuIWrmXoUBAf6a5H7GcoeYMC', 'fer@test.com'),
(5, 'Hugo', 'Sobrino', 'hugo', '$2y$10$xCech1M55XgErrRQoTet9enI/VI3vgU6GaDT1ZZgQEF8j8oEp19qm', 'hugo@test.com'),
(6, 'Paco', 'Sobrino', 'paco', '$2y$10$HyFi7eV3m0d/fyGtq4epz.M5KHeUm0Q0eEWBNgpFLIgrQat5.KCUa', 'paco@test.com'),
(7, 'Luis', 'Sobrino', 'luis', '$2y$10$HQU5QnmedAhXXXg05U9cL.sKAT/tn5KC6SR06lhjYISujO8ZZCxYy', 'luis@test.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`categoria_id`);

--
-- Indexes for table `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`producto_id`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usuario_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categoria`
--
ALTER TABLE `categoria`
  MODIFY `categoria_id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `producto`
--
ALTER TABLE `producto`
  MODIFY `producto_id` int(16) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usuario_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`categoria_id`),
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
