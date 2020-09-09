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
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(255) NOT NULL,
  `displayname` varchar(45) NOT NULL,
  `user_email` varchar(45) NOT NULL,
  `date_joined` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `displayname_UNIQUE` (`displayname`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Eric','Schwartz','schwartzy','$2y$10$RGunamwNW9fNRhAC9bHqr.jFhcFybQBGsL2E726Qj9gicCALLl3pq','Eric Schwartz','eschwartz@gmail.com','2020-05-21 16:22:47'),(2,'Lisa','Pritchard','lpritchard','$2y$10$.nRleyw2UlhvhBMKSjWj.OntVwXrk8eDzSPrUh8Nay1KxEBwqGUF.','Lisa Pritchard','lpritchard@gmail.com','2020-05-21 16:56:38'),(3,'Jamie','Westergaard','jwestergaard','$2y$10$0jJu0zG7o.e5hCAdxTLP9.INntcLZMxsJVKCeY9POb5A/93pr6Y.K','Jamie Westergaard','jwestergaard@gmail.com','2020-05-21 17:02:09'),(4,'Joshua','Creston','jcreston','$2y$10$N8WHPTb2wQ8EfGSBY1PvE.V/m6bkVmdsum56HRvNtVK7qfObiZ9n.','Josh Creston','jcreston@gmail.com','2020-05-21 17:06:32'),(5,'Elena','Samson','esamson','$2y$10$GX9D4hrfFFra3YGvducu0e7DQUV8rCmkdhEyXFaawiq3GWzoDMCJG','Elena Samson','esamson@gmail.com','2020-05-21 17:12:17'),(6,'Jillian','Hilland','jillyh','$2y$10$FFlArT6dLvxZYT8h7KOrG.IRCws6YR89IiZV3Y8VqP3BA1piteWse','Jill Hilland','jhilland@gmail.com','2020-05-21 17:42:34'),(7,'Sarah','Green','sgreen','$2y$10$9Oi7Ck2Qaom./DHW5f2og.tUEDdIb.OptU50lnF9RXM.S.NP7RcgC','Sarah Green','sgreen@gmail.com','2020-05-21 17:53:31'),(8,'Brad','Fowler','bfowler','$2y$10$oQq8Xu0mS3usPxXmv7TXcuADKH7W3/hqTOS5jaHehs.ZSn7cDqX5y','Brad Fowler','bfowler@gmail.com','2020-05-21 17:54:10'),(9,'Jeff','Bezos','jbezos','$2y$10$EPd2OAXUCe1DLflZAEo3cuUhdw/kZO0f9fJJHNi5Th7vvCPnr46kW','Jeff Bezos','jbezos@gmail.com','2020-05-21 17:54:46');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-05-22 14:46:16
