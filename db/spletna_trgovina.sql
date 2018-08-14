-- MySQL dump 10.13  Distrib 5.7.22, for Linux (x86_64)
--
-- Host: localhost    Database: spletna_trgovina
-- ------------------------------------------------------
-- Server version	5.7.22-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `artikel`
--

DROP TABLE IF EXISTS `artikel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `artikel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `opis` text,
  `cena` float NOT NULL,
  `zaloga` int(11) DEFAULT NULL,
  `ime` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `artikel`
--

LOCK TABLES `artikel` WRITE;
/*!40000 ALTER TABLE `artikel` DISABLE KEYS */;
INSERT INTO `artikel` VALUES (1,'Copati iz bombaza, rjavi',12,100,'Copati'),(2,'Usnjeni copati, rjavi',16,50,'Copati, usnjeni');
/*!40000 ALTER TABLE `artikel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `racun`
--

DROP TABLE IF EXISTS `racun`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `racun` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postavka` float NOT NULL,
  `status` text NOT NULL,
  `item_ids` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `racun`
--

LOCK TABLES `racun` WRITE;
/*!40000 ALTER TABLE `racun` DISABLE KEYS */;
/*!40000 ALTER TABLE `racun` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uporabnik`
--

DROP TABLE IF EXISTS `uporabnik`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uporabnik` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vloga` text NOT NULL,
  `ime` text NOT NULL,
  `priimek` text NOT NULL,
  `email` text NOT NULL,
  `geslo` text NOT NULL,
  `telefon` int(11) DEFAULT NULL,
  `naslov` text,
  `status` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uporabnik`
--

LOCK TABLES `uporabnik` WRITE;
/*!40000 ALTER TABLE `uporabnik` DISABLE KEYS */;
INSERT INTO `uporabnik` VALUES (1,'administrator','lenart','gazvoda','gazvoda@localhost.com','$2y$10$qUlo2cP8VwUrsOD3yLocoe87XbUgqnoJrC4u.iJooYRrLXsWxHcwS',NULL,NULL,'aktiven'),(2,'administrator','mihael','podplatnik','podplatnik@localhost.com','$2y$10$AkrFtpM0Fvp/sxOsD6s69uV67QF7e9FPPmLzAD4D1awgTbDTBwq6C',NULL,NULL,'aktiven'),(3,'prodajalec','joze','novak','novak@localhost.com','$2y$10$qPinrBoQAaq/.gY447lceuLW7Icbx05OlBtFw3d7ytz8J5oRGnlA2',NULL,NULL,'aktiven'),(4,'prodajalec','tanja','zupancic','zupancic@localhost.com','$2y$10$w3ds6KZiElD08u9ryUhU3ugK2946SL8ZcdMF1Zqp.9DSJbfNOgkSC',NULL,NULL,'neaktiven'),(5,'stranka','miha','pecnik','pecnik@localhost.com','$2y$10$z8mm21WvHLuxr9FW0jnQHe4iYsIZvr4N7qBNmZqqFdsLshgHxsoXO',51051051,'Naslov 1, 1000 Ljubljana, Slovenija','aktiven'),(6,'stranka','jan','hartman','hartman@localhost.com','$2y$10$gYfjopGSMcJWkjg/sh3CI.OnJLXwNkPqisx/u1yC.3cq3HkgoW6k2',31031031,'Naslov 2, 8000 Novo mesto, Slovenija','neaktiven');
/*!40000 ALTER TABLE `uporabnik` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-08-14 21:40:32
