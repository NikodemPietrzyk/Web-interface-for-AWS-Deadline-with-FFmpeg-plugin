-- MySQL dump 10.19  Distrib 10.3.35-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: ConverterDB
-- ------------------------------------------------------
-- Server version	10.3.35-MariaDB

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
-- Table structure for table `auth_token`
--

DROP TABLE IF EXISTS `auth_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_token` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `selector` varchar(255) NOT NULL,
  `validator` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `expiry` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `auth_token_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job`
--

DROP TABLE IF EXISTS `job`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL,
  `output_file` varchar(400) DEFAULT NULL,
  `send_email` tinyint(1) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `preset_global_id` smallint(5) unsigned DEFAULT NULL,
  `preset_user_id` bigint(20) unsigned DEFAULT NULL,
  `submission_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `preset_global_id` (`preset_global_id`),
  KEY `preset_user_id` (`preset_user_id`),
  CONSTRAINT `job_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE,
  CONSTRAINT `job_ibfk_2` FOREIGN KEY (`preset_global_id`) REFERENCES `preset_global` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `job_ibfk_3` FOREIGN KEY (`preset_user_id`) REFERENCES `preset_user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=271 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `preset_global`
--

DROP TABLE IF EXISTS `preset_global`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `preset_global` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `codec` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `width` smallint(5) unsigned DEFAULT NULL,
  `height` smallint(5) unsigned DEFAULT NULL,
  `bitrate` smallint(5) unsigned DEFAULT NULL,
  `framerate` float NOT NULL,
  `audio` tinyint(1) NOT NULL DEFAULT 1,
  `audio_bitrate` smallint(5) unsigned NOT NULL,
  `send_email` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `preset_user`
--

DROP TABLE IF EXISTS `preset_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `preset_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `codec` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `width` smallint(5) unsigned DEFAULT NULL,
  `height` smallint(5) unsigned DEFAULT NULL,
  `bitrate` smallint(5) unsigned DEFAULT NULL,
  `framerate` float NOT NULL,
  `audio` tinyint(1) NOT NULL DEFAULT 1,
  `audio_bitrate` smallint(5) unsigned NOT NULL,
  `send_email` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `prest_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10056 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `cookie` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `priority` int(11) DEFAULT 50,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-06-01 13:02:42
