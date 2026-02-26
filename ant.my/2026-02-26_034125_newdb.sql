-- MySQL dump 10.13  Distrib 8.4.7, for Win64 (x86_64)
--
-- Host: mysql-8.4    Database: newdb
-- ------------------------------------------------------
-- Server version	8.4.7

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `user_tokens`
--

DROP TABLE IF EXISTS `user_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `token_hash` char(64) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_ip` varchar(45) DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_used_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_token_hash` (`token_hash`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_expires_at` (`expires_at`),
  CONSTRAINT `fk_user_tokens_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=213 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_tokens`
--

LOCK TABLES `user_tokens` WRITE;
/*!40000 ALTER TABLE `user_tokens` DISABLE KEYS */;
INSERT INTO `user_tokens` VALUES (180,2,'967ffb1136344ff49c8589d3472ed3982bada4cfd7484a65f13cd83277335657','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0','127.0.0.1','2026-02-28 01:37:46','2026-02-25 22:37:46','2026-02-25 22:37:46'),(181,2,'1ae6465099f8f625a6093f0aac12829ee68c9aee6587cf786b5f664b6ee62122','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0','127.0.0.1','2026-02-28 01:41:45','2026-02-25 22:41:45','2026-02-25 22:41:45'),(184,2,'a69b40d100afa04f0e3200dc4ba59b66ecccb873fd030b9e08bc48ffcd1f5f49','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0','127.0.0.1','2026-02-28 01:57:24','2026-02-25 22:57:24','2026-02-25 22:57:24'),(185,2,'90e9a54f03c97de7c7246e7596822a60e5e9b8022e55d97274d63b95a5518d95','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0','127.0.0.1','2026-02-28 02:07:29','2026-02-25 23:07:29','2026-02-25 23:07:29'),(196,2,'567a4551d767abbf6926ec9de0430a50f4deca239f082cf0c3d60fe2f756d3cb','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0','127.0.0.1','2026-02-28 02:41:53','2026-02-25 23:41:53','2026-02-25 23:41:53'),(204,2,'73c28d005b504b3c5f6ede67ead68425a2c1aefd851a3a67f94dac4daf5b1380','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0','127.0.0.1','2026-02-28 02:58:29','2026-02-25 23:58:29','2026-02-25 23:58:29'),(206,2,'b3ccd9a462bd15520a8fd8d3246d4801a58f842a3039806f6b0b7d11d66afee1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0','127.0.0.1','2026-02-28 03:18:17','2026-02-26 00:18:17','2026-02-26 00:18:17'),(207,2,'c4c214de9df73207779867530899bbe355a9a723a1a307a15f1a26e7dd39ca91','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0','127.0.0.1','2026-02-28 03:19:39','2026-02-26 00:19:39','2026-02-26 00:19:39'),(211,2,'3bc6c8b36301a56a1d7ca8e12ea635cb88a738edb1bdbafb15bb7c68df1c6464','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0','127.0.0.1','2026-02-28 03:28:01','2026-02-26 00:28:01','2026-02-26 00:28:01'),(212,2,'9361b132e0e1336c68a4e7189c3face19a523b4cedcb8de842437898fe3f18bf','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0','127.0.0.1','2026-02-28 03:36:19','2026-02-26 00:36:19','2026-02-26 00:36:19');
/*!40000 ALTER TABLE `user_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `role` enum('admin','user','guest') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','aaa@ee.tt','$2y$12$CehthLX3BoZ/53RS/UO/j.s.Y1LOuTC2GXPErJTIcCz1kqqc0TcCC',1,'admin','2026-02-19 12:03:28','2026-02-26 03:26:24'),(2,'user','bbb@ee.tt','$2y$12$/JIv5NEWG.PglDS7QsVGVugrirR6NiiPSlDm9NOmAoB7DqV6gDppq',1,'user','2026-02-19 12:03:28','2026-02-26 03:36:19'),(41,'ban','4567@ddd.rwe','$2y$12$/JIv5NEWG.PglDS7QsVGVugrirR6NiiPSlDm9NOmAoB7DqV6gDppq',0,'user','2026-02-25 22:00:59','2026-02-26 01:00:59'),(42,'кен7г','rtyurtyu@dwqer.ert','$2y$12$pkJXvJtZMUA76dmuxJk3L.pUVV7u7ngv9dmYZqJFg93QymsqAnuLq',1,'user','2026-02-25 23:01:06','2026-02-26 02:01:06'),(43,'ewrq','qew@eqw.rewrewf','$2y$12$heZvMLGoOJVjPKEZeJ7Goel159FDXZNveYJJjj5xt7tp.TLMHlZXG',1,'user','2026-02-25 23:28:27','2026-02-26 02:28:27'),(44,'[po','safd@fff.ty','$2y$12$XFJM3yW1jUOhvkeFha93guoowVzuvcMJaI895pnvuBIlMSJT6bqq6',1,'user','2026-02-26 00:28:51','2026-02-26 03:28:51');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'newdb'
--

--
-- Dumping routines for database 'newdb'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-26  3:41:25
