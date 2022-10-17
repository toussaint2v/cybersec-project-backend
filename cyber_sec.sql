-- MySQL dump 10.13  Distrib 8.0.30, for macos12.6 (arm64)
--
-- Host: 127.0.0.1    Database: cyber_sec
-- ------------------------------------------------------
-- Server version	8.0.30

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
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `post_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `content` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `comments_id_uindex` (`id`),
  KEY `comments_posts_id_fk` (`post_id`),
  KEY `comments_users_id_fk` (`user_id`),
  CONSTRAINT `comments_posts_id_fk` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  CONSTRAINT `comments_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `friends_invitations`
--

DROP TABLE IF EXISTS `friends_invitations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `friends_invitations` (
  `from` int NOT NULL,
  `to` int NOT NULL,
  `accepted` tinyint(1) NOT NULL,
  `opened` tinyint(1) NOT NULL,
  PRIMARY KEY (`from`,`to`),
  KEY `friends_invitations_users_to_fk` (`to`),
  CONSTRAINT `friends_invitations_users_from_fk` FOREIGN KEY (`from`) REFERENCES `users` (`id`),
  CONSTRAINT `friends_invitations_users_null_fk` FOREIGN KEY (`from`) REFERENCES `users` (`id`),
  CONSTRAINT `friends_invitations_users_to_fk` FOREIGN KEY (`to`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `friends_invitations`
--

LOCK TABLES `friends_invitations` WRITE;
/*!40000 ALTER TABLE `friends_invitations` DISABLE KEYS */;
INSERT INTO `friends_invitations` VALUES (22,49,0,0),(22,50,0,0);
/*!40000 ALTER TABLE `friends_invitations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `from` int DEFAULT NULL,
  `to` int DEFAULT NULL,
  `content` varchar(64) NOT NULL,
  `opened` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `messages_id_uindex` (`id`),
  KEY `messages_users_id_fk` (`from`),
  KEY `messages_users_id_fk_2` (`to`),
  CONSTRAINT `messages_users_id_fk` FOREIGN KEY (`from`) REFERENCES `users` (`id`),
  CONSTRAINT `messages_users_id_fk_2` FOREIGN KEY (`to`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(64) DEFAULT NULL,
  `description` varchar(64) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_id_uindex` (`id`),
  KEY `posts_users_id_fk` (`user_id`),
  CONSTRAINT `posts_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(64) DEFAULT NULL,
  `address` varchar(64) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `first_name` varchar(64) DEFAULT NULL,
  `birthDate` date DEFAULT NULL,
  `age` int DEFAULT NULL,
  `token` varchar(128) DEFAULT NULL,
  `token_expiration` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_id_uindex` (`id`),
  UNIQUE KEY `users_username_uindex` (`username`),
  UNIQUE KEY `users_email_uindex` (`email`),
  UNIQUE KEY `users_token_uindex` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (22,'toussaint','toussaint.carlotti@gmail.com','$2y$10$RObfFyHbS6dtK/dtjM0aEOYYdBXWlsG1F4Vb9AnNIT2VThC/r1JPS','Rue Colonel Peraldi','carlotti','toussaint','2022-09-17',21,NULL,NULL),(49,'rtrtr','toussaint.carlotti@gmail.comrtr','$2y$10$ZfTIEeJDPegaJRPNpPGTFeu0HP2RjfNSntU54QijFfWSb9hx4/k6K',NULL,'toussaint','toussaint',NULL,NULL,NULL,NULL),(50,'test','toussaint.carlotti@gmail.comfrefer','$2y$10$tF4hI2vQyoI/bk/CMuFn7uwfQGK57Utcym/e9302CM/Tb4askNZe6',NULL,'tourfef','touref',NULL,NULL,'',NULL);
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

-- Dump completed on 2022-10-17 17:01:06
