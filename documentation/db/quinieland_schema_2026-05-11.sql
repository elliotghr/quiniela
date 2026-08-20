-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: 192.168.5.15    Database: quinieland_development
-- ------------------------------------------------------
-- Server version	8.3.0

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
-- Table structure for table `Bloque`
--

DROP TABLE IF EXISTS `Bloque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Bloque` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` int unsigned NOT NULL,
  `status_bloque_id` int unsigned NOT NULL,
  `quiniela_id` int unsigned NOT NULL,
  `fecha_pago` datetime NOT NULL,
  `participantes` int NOT NULL DEFAULT '0',
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_Bloque_Status_Bloque1_idx` (`status_bloque_id`),
  KEY `fk_Bloque_Usuario1_idx` (`usuario_id`),
  KEY `fk_Bloque_Quiniela1_idx` (`quiniela_id`),
  CONSTRAINT `fk_Bloque_Quiniela1` FOREIGN KEY (`quiniela_id`) REFERENCES `Quiniela` (`id`),
  CONSTRAINT `fk_Bloque_Status_Bloque1` FOREIGN KEY (`status_bloque_id`) REFERENCES `Status_Bloque` (`id`),
  CONSTRAINT `fk_Bloque_Usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `Usuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Bloque`
--

LOCK TABLES `Bloque` WRITE;
/*!40000 ALTER TABLE `Bloque` DISABLE KEYS */;
/*!40000 ALTER TABLE `Bloque` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Datos_Usuario`
--

DROP TABLE IF EXISTS `Datos_Usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Datos_Usuario` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` int unsigned NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido_paterno` varchar(45) DEFAULT NULL,
  `apellido_materno` varchar(45) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `avatar` varchar(45) NOT NULL DEFAULT 'default.png',
  PRIMARY KEY (`id`),
  KEY `fk_Datos_Usuario_Usuario1_idx` (`usuario_id`),
  CONSTRAINT `fk_Datos_Usuario_Usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `Usuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Datos_Usuario`
--

LOCK TABLES `Datos_Usuario` WRITE;
/*!40000 ALTER TABLE `Datos_Usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `Datos_Usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Modulo`
--

DROP TABLE IF EXISTS `Modulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Modulo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `modulo_padre_id` int unsigned DEFAULT NULL,
  `titulo` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `url` varchar(45) DEFAULT NULL,
  `icono` varchar(45) NOT NULL,
  `orden` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Modulo_Modulo1_idx` (`modulo_padre_id`),
  CONSTRAINT `fk_Modulo_Modulo1` FOREIGN KEY (`modulo_padre_id`) REFERENCES `Modulo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Modulo`
--

LOCK TABLES `Modulo` WRITE;
/*!40000 ALTER TABLE `Modulo` DISABLE KEYS */;
INSERT INTO `Modulo` VALUES (1,NULL,'Inicio','Página de Inicio','home','home',1),(2,NULL,'Configuración','Configuración General',NULL,'tools',3),(3,2,'Mi Cuenta','Administrar Cuenta','config/account','user',4),(4,2,'Usuarios','CRUD Usuarios','config/users','users',2),(5,2,'Roles','CRUD Roles','config/rols','tag',1),(6,2,'Accesos','Asignación de Accesos','config/access','shield-alt',3),(7,NULL,'Quinielas','Listado de Quinielas',NULL,'clipboard-list',2),(8,7,'Mis Quinielas','Lista de mis quinielas','quinielas/mis-quinielas','tasks',1);
/*!40000 ALTER TABLE `Modulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Modulo_Rol`
--

DROP TABLE IF EXISTS `Modulo_Rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Modulo_Rol` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `modulo_id` int unsigned NOT NULL,
  `rol_id` int unsigned NOT NULL,
  `escritura` tinyint NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Rol_Modulo_Rol_idx` (`rol_id`),
  KEY `fk_Rol_Modulo_Modulo1_idx` (`modulo_id`),
  CONSTRAINT `fk_Rol_Modulo_Modulo1` FOREIGN KEY (`modulo_id`) REFERENCES `Modulo` (`id`),
  CONSTRAINT `fk_Rol_Modulo_Rol` FOREIGN KEY (`rol_id`) REFERENCES `Rol` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Modulo_Rol`
--

LOCK TABLES `Modulo_Rol` WRITE;
/*!40000 ALTER TABLE `Modulo_Rol` DISABLE KEYS */;
INSERT INTO `Modulo_Rol` VALUES (1,1,1,1),(2,2,1,1),(3,3,1,1),(4,4,1,1),(5,5,1,1),(6,6,1,1),(7,7,1,1),(8,8,1,1),(9,1,2,1),(10,2,2,1),(11,3,2,1),(12,7,2,1),(13,8,2,1);
/*!40000 ALTER TABLE `Modulo_Rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Partido`
--

DROP TABLE IF EXISTS `Partido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Partido` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `pronostico_id` int NOT NULL,
  `partido` int unsigned NOT NULL,
  `pronostico_local` int unsigned DEFAULT NULL,
  `pronostico_visitante` int unsigned DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_cambio` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_Partido_Pronostico1_idx` (`pronostico_id`),
  CONSTRAINT `fk_Partido_Pronostico1` FOREIGN KEY (`pronostico_id`) REFERENCES `Pronostico` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Partido`
--

LOCK TABLES `Partido` WRITE;
/*!40000 ALTER TABLE `Partido` DISABLE KEYS */;
/*!40000 ALTER TABLE `Partido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Pronostico`
--

DROP TABLE IF EXISTS `Pronostico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Pronostico` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quiniela_id` int unsigned NOT NULL,
  `usuario_id` int unsigned NOT NULL,
  `consecutivo` int NOT NULL DEFAULT '1',
  `activo` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_Pronostico_Quiniela1_idx` (`quiniela_id`),
  KEY `fk_Pronostico_Usuario1_idx` (`usuario_id`),
  CONSTRAINT `fk_Pronostico_Quiniela1` FOREIGN KEY (`quiniela_id`) REFERENCES `Quiniela` (`id`),
  CONSTRAINT `fk_Pronostico_Usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `Usuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Pronostico`
--

LOCK TABLES `Pronostico` WRITE;
/*!40000 ALTER TABLE `Pronostico` DISABLE KEYS */;
/*!40000 ALTER TABLE `Pronostico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Quiniela`
--

DROP TABLE IF EXISTS `Quiniela`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Quiniela` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` int unsigned NOT NULL,
  `tipo_quiniela_id` int unsigned NOT NULL,
  `fecha_inicio` varchar(45) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `liga` int unsigned NOT NULL,
  `temporada` varchar(20) NOT NULL,
  `rondas` varchar(250) NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `max_pronosticos` int NOT NULL DEFAULT '1',
  `url_encode` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_Quiniela_Usuario1_idx` (`usuario_id`),
  KEY `fk_Quiniela_Tipo_Quiniela1_idx` (`tipo_quiniela_id`),
  CONSTRAINT `fk_Quiniela_Tipo_Quiniela1` FOREIGN KEY (`tipo_quiniela_id`) REFERENCES `Tipo_Quiniela` (`id`),
  CONSTRAINT `fk_Quiniela_Usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `Usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Quiniela`
--

LOCK TABLES `Quiniela` WRITE;
/*!40000 ALTER TABLE `Quiniela` DISABLE KEYS */;
INSERT INTO `Quiniela` VALUES (1,1,1,'2026-06-10 23:59:59','ANEMEX',1,'2026','Group Stage - 1|Group Stage - 2|Group Stage - 3','2022-05-06 21:15:59',3,'4faaf4f875a4cef3992de33cf8f1f7116aa4400a601180292217dced8d46720837d2a6de28ae6ca73e60778ff43995de1fdd51087c3712a6374525d030e3292e3f377d338dc9fa0a08cf08bf54bc9d6f0d'),(2,1,1,'2026-06-10 23:59:59','Familia',1,'2026','Group Stage - 1|Group Stage - 2|Group Stage - 3','2022-05-06 18:39:25',3,'671b5d16235ce83167367c17bc22998011e2adcd243c31de2ebff024d080f984bc3fff76bb8e26cca5d1c4328d12b244bcb58e09ee6f721c62b05bb3075a251397ea5bcef87e156501f3c8d4280431373a');
/*!40000 ALTER TABLE `Quiniela` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Rol`
--

DROP TABLE IF EXISTS `Rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Rol` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Rol`
--

LOCK TABLES `Rol` WRITE;
/*!40000 ALTER TABLE `Rol` DISABLE KEYS */;
INSERT INTO `Rol` VALUES (1,'Administrador'),(2,'Jugador');
/*!40000 ALTER TABLE `Rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Status_Bloque`
--

DROP TABLE IF EXISTS `Status_Bloque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Status_Bloque` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Status_Bloque`
--

LOCK TABLES `Status_Bloque` WRITE;
/*!40000 ALTER TABLE `Status_Bloque` DISABLE KEYS */;
/*!40000 ALTER TABLE `Status_Bloque` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Tipo_Quiniela`
--

DROP TABLE IF EXISTS `Tipo_Quiniela`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Tipo_Quiniela` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Tipo_Quiniela`
--

LOCK TABLES `Tipo_Quiniela` WRITE;
/*!40000 ALTER TABLE `Tipo_Quiniela` DISABLE KEYS */;
INSERT INTO `Tipo_Quiniela` VALUES (1,'Estándard');
/*!40000 ALTER TABLE `Tipo_Quiniela` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Usuario`
--

DROP TABLE IF EXISTS `Usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Usuario` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `rol_id` int unsigned NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `clave` varchar(60) NOT NULL,
  `primera_vez` tinyint NOT NULL DEFAULT '1',
  `cambio_clave` int NOT NULL DEFAULT '0',
  `fecha_cambio_clave` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_Usuario_Rol1_idx` (`rol_id`),
  CONSTRAINT `fk_Usuario_Rol1` FOREIGN KEY (`rol_id`) REFERENCES `Rol` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Usuario`
--

LOCK TABLES `Usuario` WRITE;
/*!40000 ALTER TABLE `Usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `Usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-11 10:36:08
