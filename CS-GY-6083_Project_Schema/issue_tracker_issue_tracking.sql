-- MySQL dump 10.13  Distrib 8.0.19, for macos10.15 (x86_64)
--
-- Host: localhost    Database: issue_tracker
-- ------------------------------------------------------
-- Server version	8.0.19

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
-- Table structure for table `issue_tracking`
--

DROP TABLE IF EXISTS `issue_tracking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `issue_tracking` (
  `issue_id` int NOT NULL,
  `status` int NOT NULL,
  `update_descr` varchar(250) NOT NULL,
  `updated_by` int NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`issue_id`,`status`,`date`,`updated_by`),
  KEY `status` (`status`),
  KEY `issue_tracking_ibfk_3_idx` (`updated_by`),
  CONSTRAINT `issue_tracking_ibfk_1` FOREIGN KEY (`issue_id`) REFERENCES `issue` (`issue_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `issue_tracking_ibfk_2` FOREIGN KEY (`status`) REFERENCES `state` (`state_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `issue_tracking_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `issue_tracking`
--

LOCK TABLES `issue_tracking` WRITE;
/*!40000 ALTER TABLE `issue_tracking` DISABLE KEYS */;
INSERT INTO `issue_tracking` VALUES (1,72,'New issue',3,'2020-05-21 17:07:45'),(1,73,'Working on fix',4,'2020-05-21 17:10:28'),(1,74,'Getting approval from development team',4,'2020-05-21 17:10:44'),(2,96,'New issue',1,'2020-05-21 17:22:56'),(2,97,'Discussed issue with developers',2,'2020-05-21 17:58:57'),(2,97,'Requested more support from developers',2,'2020-05-21 17:59:43'),(2,98,'Working on fix',2,'2020-05-21 17:59:05'),(2,98,'Developers working on issue',2,'2020-05-21 17:59:52'),(3,106,'New issue',6,'2020-05-21 17:48:13'),(3,107,'Performing testing',2,'2020-05-21 18:00:15'),(3,108,'Sent for review',2,'2020-05-21 18:00:38'),(4,106,'New issue',6,'2020-05-21 17:50:32'),(4,107,'Testing performance',2,'2020-05-21 18:01:01'),(4,108,'Management reviewing',5,'2020-05-21 18:06:25'),(4,110,'Issue resolved',5,'2020-05-21 18:06:38'),(5,101,'New issue',1,'2020-05-21 17:52:31'),(5,102,'Working on fix',3,'2020-05-21 17:57:22'),(5,103,'Flagged issue to senior developers',3,'2020-05-21 17:57:45'),(6,101,'New issue',1,'2020-05-21 17:52:47'),(6,102,'Running tests to check performance',4,'2020-05-21 18:02:45'),(6,103,'Flagged to developers',4,'2020-05-21 18:02:53'),(7,72,'New issue',3,'2020-05-21 17:56:44'),(7,73,'Checked issue',7,'2020-05-21 18:08:43'),(7,74,'Final testing and review',7,'2020-05-21 18:08:54'),(8,96,'New issue',2,'2020-05-21 18:01:37'),(8,97,'Reviewing issue',5,'2020-05-21 18:05:14'),(8,97,'Developers working on solution',5,'2020-05-21 18:05:44'),(8,98,'Working on fix with developers',5,'2020-05-21 18:05:22'),(8,98,'Testing solution from developers',5,'2020-05-21 18:05:52'),(9,96,'New issue',5,'2020-05-21 18:04:36'),(10,101,'New issue',6,'2020-05-21 20:35:23'),(10,102,'Working on fix',5,'2020-05-22 13:46:47'),(10,103,'Working on fix',6,'2020-05-22 14:09:14'),(11,101,'New issue',6,'2020-05-21 20:37:22'),(11,102,'Creating design',1,'2020-05-21 20:37:55'),(11,105,'Design sent for review',1,'2020-05-21 20:38:12'),(77,101,'New issue',6,'2020-05-22 14:06:58');
/*!40000 ALTER TABLE `issue_tracking` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-05-22 14:46:14
