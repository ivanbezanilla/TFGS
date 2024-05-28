CREATE DATABASE  IF NOT EXISTS `sistema_riego` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `sistema_riego`;
-- MySQL dump 10.13  Distrib 8.0.33, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: sistema_riego
-- ------------------------------------------------------
-- Server version	8.0.36-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `arduino`
--

DROP TABLE IF EXISTS `arduino`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `arduino` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) DEFAULT NULL,
  `id_user` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_id_user` (`id_user`),
  CONSTRAINT `arduino_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arduino`
--

LOCK TABLES `arduino` WRITE;
/*!40000 ALTER TABLE `arduino` DISABLE KEYS */;
INSERT INTO `arduino` VALUES (1,'192.168.0.100',1);
/*!40000 ALTER TABLE `arduino` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `datos_sensores`
--

DROP TABLE IF EXISTS `datos_sensores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `datos_sensores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `temperatura` varchar(45) DEFAULT NULL,
  `humedad` varchar(45) DEFAULT NULL,
  `humedad_suelo` varchar(45) DEFAULT NULL,
  `fecha_hora` datetime DEFAULT NULL,
  `id_ard` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_arduino_sensores_idx` (`id_ard`),
  CONSTRAINT `fk_arduino_sensores` FOREIGN KEY (`id_ard`) REFERENCES `arduino` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `datos_sensores`
--

LOCK TABLES `datos_sensores` WRITE;
/*!40000 ALTER TABLE `datos_sensores` DISABLE KEYS */;
INSERT INTO `datos_sensores` VALUES (1,' 27C',' 50%',' 37%','2024-05-10 11:07:47',NULL),(2,' 27C',' 50%',' 38%','2024-05-10 11:08:50',NULL),(3,' 27C',' 50%',' 38%','2024-05-10 11:08:58',NULL),(4,' 27C',' 50%',' 38%','2024-05-10 11:09:01',NULL),(5,' 27C',' 50%',' 38%','2024-05-10 11:09:11',NULL),(6,' 27C',' 50%',' 39%','2024-05-10 11:09:18',NULL),(7,' 27C',' 50%',' 39%','2024-05-10 11:11:34',NULL),(8,' 27C',' 50%',' 39%','2024-05-10 11:21:53',NULL),(9,' 27C',' 50%',' 40%','2024-05-10 11:35:19',NULL);
/*!40000 ALTER TABLE `datos_sensores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registros_riego`
--

DROP TABLE IF EXISTS `registros_riego`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registros_riego` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  `id_ard` int DEFAULT NULL,
  `fecha_hora` datetime DEFAULT NULL,
  `duracion_segundos` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_ard` (`id_ard`),
  CONSTRAINT `registros_riego_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  CONSTRAINT `registros_riego_ibfk_2` FOREIGN KEY (`id_ard`) REFERENCES `arduino` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registros_riego`
--

LOCK TABLES `registros_riego` WRITE;
/*!40000 ALTER TABLE `registros_riego` DISABLE KEYS */;
/*!40000 ALTER TABLE `registros_riego` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email` (`email`),
  UNIQUE KEY `telefono_UNIQUE` (`telefono`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Ivan','Bezanilla López','ibezanillal01@educantabria.es','Alisal2023','Calle Principal 123','123456789');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-10 13:40:34
