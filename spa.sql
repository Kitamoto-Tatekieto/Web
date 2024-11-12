-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: spa
-- ------------------------------------------------------
-- Server version	10.4.28-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cliente` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `TD` char(2) NOT NULL DEFAULT '0',
  `DNI` int(10) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Apellido` varchar(50) NOT NULL,
  `Genero` char(2) NOT NULL DEFAULT '',
  `Telefono` int(10) NOT NULL DEFAULT 0,
  `Correo` varchar(50) NOT NULL,
  `Direccion` varchar(50) NOT NULL,
  `estado` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (4,'CC',1130269305,'Daniel','De alba','MC',665454665,'Dani@gmail.com','Calle 13A #26-129','0');
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_reserva`
--

DROP TABLE IF EXISTS `detalle_reserva`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_reserva` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `reserva_id` int(10) NOT NULL,
  `servicio_id` int(10) NOT NULL,
  `cantidad` int(10) NOT NULL,
  `estado` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_detalle_factura_servicio` (`servicio_id`) USING BTREE,
  KEY `FK_detalle_factura_factura` (`reserva_id`) USING BTREE,
  CONSTRAINT `FK_detalle_reserva_reserva` FOREIGN KEY (`reserva_id`) REFERENCES `reserva` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_detalle_reserva_servicio` FOREIGN KEY (`servicio_id`) REFERENCES `servicio` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_reserva`
--

LOCK TABLES `detalle_reserva` WRITE;
/*!40000 ALTER TABLE `detalle_reserva` DISABLE KEYS */;
INSERT INTO `detalle_reserva` VALUES (54,45,39,1,'0');
/*!40000 ALTER TABLE `detalle_reserva` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto`
--

DROP TABLE IF EXISTS `producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producto` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nom_pro` varchar(50) NOT NULL,
  `desc_pro` varchar(50) NOT NULL,
  `tipo_pro` varchar(50) NOT NULL,
  `valor_pro` int(10) NOT NULL,
  `cant_pro` int(10) NOT NULL,
  `estado` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto`
--

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` VALUES (14,'crema de aguacate','crema humestante','crema',20000,20,'0');
/*!40000 ALTER TABLE `producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reserva`
--

DROP TABLE IF EXISTS `reserva`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reserva` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(10) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `iva` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fecha_creacion` date NOT NULL,
  `estado` char(1) NOT NULL DEFAULT '0',
  `fecha_reserva` date NOT NULL,
  `hora` time NOT NULL,
  `trabajador_id` int(10) NOT NULL,
  `estado_pago` enum('Paga','No paga') NOT NULL DEFAULT 'No paga',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_factura_cliente` (`cliente_id`),
  KEY `FK_reserva_trabajador` (`trabajador_id`),
  CONSTRAINT `FK_factura_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_reserva_trabajador` FOREIGN KEY (`trabajador_id`) REFERENCES `trabajador` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reserva`
--

LOCK TABLES `reserva` WRITE;
/*!40000 ALTER TABLE `reserva` DISABLE KEYS */;
INSERT INTO `reserva` VALUES (45,4,60000.00,9600.00,69600.00,'2024-10-26','0','2024-10-27','15:30:00',3,'No paga');
/*!40000 ALTER TABLE `reserva` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicio`
--

DROP TABLE IF EXISTS `servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicio` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(50) NOT NULL DEFAULT '',
  `Valor` int(10) NOT NULL DEFAULT 0,
  `Detalles` varchar(50) NOT NULL DEFAULT '',
  `estado` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicio`
--

LOCK TABLES `servicio` WRITE;
/*!40000 ALTER TABLE `servicio` DISABLE KEYS */;
INSERT INTO `servicio` VALUES (39,'Masaje',60000,'masaje con durancion de 40min,h  ','0');
/*!40000 ALTER TABLE `servicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicio_producto`
--

DROP TABLE IF EXISTS `servicio_producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicio_producto` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serv_id` int(10) NOT NULL,
  `id_producto` int(10) NOT NULL,
  `estado` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_servicio_producto_producto` (`id_producto`),
  KEY `FK_servicio_producto_servicio` (`serv_id`) USING BTREE,
  CONSTRAINT `FK_servicio_producto_producto` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_servicio_producto_servicio` FOREIGN KEY (`serv_id`) REFERENCES `servicio` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicio_producto`
--

LOCK TABLES `servicio_producto` WRITE;
/*!40000 ALTER TABLE `servicio_producto` DISABLE KEYS */;
INSERT INTO `servicio_producto` VALUES (62,39,14,'0');
/*!40000 ALTER TABLE `servicio_producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trabajador`
--

DROP TABLE IF EXISTS `trabajador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trabajador` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(50) NOT NULL,
  `Contrasena` varchar(230) NOT NULL DEFAULT '',
  `TD` char(3) NOT NULL,
  `DNI` int(10) NOT NULL DEFAULT 0,
  `Apellido` varchar(50) NOT NULL,
  `Puesto` char(2) NOT NULL DEFAULT '',
  `Correo` varchar(50) NOT NULL,
  `Telefono` int(10) NOT NULL DEFAULT 0,
  `Direccion` varchar(50) NOT NULL,
  `estado` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trabajador`
--

LOCK TABLES `trabajador` WRITE;
/*!40000 ALTER TABLE `trabajador` DISABLE KEYS */;
INSERT INTO `trabajador` VALUES (3,'Tali','$2y$10$PinWxzRh3m.ZDr.rHEW.1uAjKdk7PUUdBsWD0O8JtDFnzywU9epge','CC',321,'Cova','AD','Cova@gmail.com',2147483647,'La chinitatoo','0'),(10,'Jose','$2y$10$2xXgKRtCugwXJA1D3QwjLuOHaU7Q.YzhZ90tVSt/EI8yKR2moY3Ca','CC',123,'Bermejo','EM','JB@gmail.com',2147483647,'La chinita','0');
/*!40000 ALTER TABLE `trabajador` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-28 17:54:11
