CREATE DATABASE  IF NOT EXISTS `k00285774_framework_16_resteraunt` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci */;
USE `k00285774_framework_16_resteraunt`;
-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: k00285774_framework_16_resteraunt
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.28-MariaDB

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
-- Table structure for table `chatmsg`
--

DROP TABLE IF EXISTS `chatmsg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chatmsg` (
  `msgID` int(11) NOT NULL AUTO_INCREMENT,
  `msgText` varchar(244) DEFAULT NULL,
  `dateTimeStamp` datetime DEFAULT current_timestamp(),
  `msgAuthorID` varchar(40) DEFAULT NULL,
  `userType` varchar(10) DEFAULT NULL,
  `msgTo` varchar(40) DEFAULT 'ALL',
  PRIMARY KEY (`msgID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chatmsg`
--

LOCK TABLES `chatmsg` WRITE;
/*!40000 ALTER TABLE `chatmsg` DISABLE KEYS */;
/*!40000 ALTER TABLE `chatmsg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `country` (
  `id` int(11) NOT NULL,
  `country_name` varchar(255) NOT NULL,
  `location` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country`
--

LOCK TABLES `country` WRITE;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;
INSERT INTO `country` VALUES (1,'Afghanistan',''),(2,'Albania',''),(3,'Bahamas',''),(4,'Bahrain',''),(5,'Cambodia',''),(6,'Cameroon',''),(7,'Denmark',''),(8,'Djibouti',''),(9,'East Timor',''),(10,'Ecuador',''),(11,'Falkland Islands (Malvinas)',''),(12,'Faroe Islands',''),(13,'Gabon',''),(14,'Gambia',''),(15,'Haiti',''),(16,'Heard and Mc Donald Islands',''),(17,'Iceland',''),(18,'India',''),(19,'Jamaica',''),(20,'Japan',''),(21,'Kenya',''),(22,'Kiribati',''),(23,'Lao Peoples Democratic Republic',''),(24,'Latvia',''),(25,'Macau',''),(26,'Macedonia',''),(27,'Namibia',''),(28,'Nauru',''),(29,'Oman',''),(30,'Pakistan',''),(31,'Palau',''),(32,'Qatar',''),(33,'Reunion',''),(34,'Romania',''),(35,'Saint Kitts and Nevis',''),(36,'Saint Lucia',''),(37,'Taiwan',''),(38,'Tajikistan',''),(39,'Uganda',''),(40,'Ukraine',''),(41,'Vanuatu',''),(42,'Vatican City State',''),(43,'Wallis and Futuna Islands',''),(44,'Western Sahara',''),(45,'Yemen',''),(46,'Yugoslavia',''),(47,'Zaire',''),(48,'Zambia','');
/*!40000 ALTER TABLE `country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `county`
--

DROP TABLE IF EXISTS `county`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `county` (
  `idcounty` int(11) NOT NULL AUTO_INCREMENT,
  `countyName` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idcounty`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `county`
--

LOCK TABLES `county` WRITE;
/*!40000 ALTER TABLE `county` DISABLE KEYS */;
INSERT INTO `county` VALUES (1,'Antrim'),(2,'Armagh'),(3,'Carlow'),(4,'Cavan'),(5,'Clare'),(6,'Cork'),(7,'Donegal'),(8,'Down'),(9,'Dublin'),(10,'DunLaoghaire-Rathdown'),(11,'Fermanagh'),(12,'Fingal'),(13,'Galway'),(14,'Kerry'),(15,'Kildare'),(16,'Kilkenny'),(17,'Laois'),(18,'Leitrim'),(19,'Limerick'),(20,'Londonderry'),(21,'Longford'),(22,'Louth'),(23,'Mayo'),(24,'Meath'),(25,'Monaghan'),(26,'North Tipperary'),(27,'Offaly'),(28,'Roscommon'),(29,'Sligo'),(30,'South Dublin'),(31,'South Tipperary'),(32,'Tipperary'),(33,'Tyrone'),(34,'Waterford'),(35,'Westmeath'),(36,'Wexford'),(37,'Wicklow'),(99,'Unknown County');
/*!40000 ALTER TABLE `county` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order` (
  `customer` int(11) NOT NULL,
  `dish` int(11) NOT NULL,
  PRIMARY KEY (`customer`,`dish`),
  KEY `fk_dish_12_idx` (`dish`),
  CONSTRAINT `fk_customer` FOREIGN KEY (`customer`) REFERENCES `user` (`UserNr`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_dish_12` FOREIGN KEY (`dish`) REFERENCES `restaurantdishes` (`DishID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order`
--

LOCK TABLES `order` WRITE;
/*!40000 ALTER TABLE `order` DISABLE KEYS */;
/*!40000 ALTER TABLE `order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurantdishes`
--

DROP TABLE IF EXISTS `restaurantdishes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `restaurantdishes` (
  `DishID` int(11) NOT NULL AUTO_INCREMENT,
  `DishName` varchar(255) DEFAULT NULL,
  `DishType` enum('Starter','Main','Dessert','Drink') DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`DishID`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurantdishes`
--

LOCK TABLES `restaurantdishes` WRITE;
/*!40000 ALTER TABLE `restaurantdishes` DISABLE KEYS */;
INSERT INTO `restaurantdishes` VALUES (1,'Spring Rolls','Starter',5.99),(2,'Garlic Bread','Starter',4.50),(3,'Chicken Curry','Main',11.99),(4,'Beef Stroganoff','Main',12.99),(5,'Cheesecake','Dessert',6.50),(6,'Ice Cream','Dessert',4.00),(7,'Coke','Drink',1.99),(8,'Beer','Drink',3.99),(9,'Spring Rolls','Starter',5.99),(10,'Garlic Bread','Starter',4.50),(11,'Bruschetta','Starter',5.25),(12,'Soup of the Day','Starter',5.00),(13,'Chicken Satay','Starter',6.75),(14,'Prawn Cocktail','Starter',7.00),(15,'Stuffed Mushrooms','Starter',6.50),(16,'Chicken Curry','Main',11.99),(17,'Beef Stroganoff','Main',12.99),(18,'Vegetable Lasagna','Main',10.99),(19,'Grilled Salmon','Main',13.50),(20,'Duck Confit','Main',15.25),(21,'Spaghetti Bolognese','Main',12.00),(22,'Pork Ribs','Main',14.75),(23,'Steak Frites','Main',16.50),(24,'Fish and Chips','Main',12.50),(25,'Mushroom Risotto','Main',11.50),(26,'Cheesecake','Dessert',6.50),(27,'Ice Cream','Dessert',4.00),(28,'Chocolate Brownie','Dessert',5.00),(29,'Apple Pie','Dessert',5.50),(30,'Tiramisu','Dessert',6.75),(31,'Coke','Drink',1.99),(32,'Beer','Drink',3.99),(33,'Mineral Water','Drink',1.50),(34,'Red Wine','Drink',5.50),(35,'White Wine','Drink',5.50);
/*!40000 ALTER TABLE `restaurantdishes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `UserNr` int(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(45) NOT NULL,
  `LastName` varchar(45) NOT NULL,
  `PassWord` varchar(45) DEFAULT NULL,
  `email` varchar(45) NOT NULL,
  `mobile` varchar(45) DEFAULT NULL,
  `idcounty` int(11) NOT NULL,
  `userID` varchar(45) DEFAULT NULL,
  `userTypeNr` int(11) NOT NULL,
  `userEnabled` tinyint(4) DEFAULT 1,
  PRIMARY KEY (`UserNr`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `userID_UNIQUE` (`userID`),
  KEY `fk_admin_county2_idx` (`idcounty`),
  KEY `fk_user_userType1_idx` (`userTypeNr`),
  CONSTRAINT `fk_admin_county2` FOREIGN KEY (`idcounty`) REFERENCES `county` (`idcounty`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_userType1` FOREIGN KEY (`userTypeNr`) REFERENCES `usertype` (`userTypeNr`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'John','Smith','cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96','jsmith@college.ie','0875869745',4,'jsmith@college.ie',1,1),(2,'Jane','Murphy','cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96','janeh@mail.com','0871234567',13,'janeh@mail.com',3,1),(3,'Harry','Boland','cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96','harry@lit.ie','01234567',2,'harry@lit.ie',3,1),(4,'James','Flannery','cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96','flann@gmail.com','0875426987',3,'flann@gmail.com',2,1),(5,'James','Murphy','cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96','james@framework.com','0862356897',19,'james@framework.com',3,1),(6,'Jack','McKeown','cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96','jack@lit.ie','0875458745',8,'jack@lit.ie',2,1),(25,'elvis','presley','cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96','presley@tus.ie','0865478745',2,'presley@tus.ie',3,1),(42,'Jimm','O','cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96','jimmy@ob.com','085457854',3,'jimmy@ob.com',1,0),(44,'New','Customer','cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96','cust@gmail.com','0854789654',2,'cust@gmail.com',3,1),(45,'John','Customer2','cf8f0c0d32522bc3d2ebe59d1fa46611d3369c96','cust2@cust.com','0587458745',5,'cust2@cust.com',3,1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usertype`
--

DROP TABLE IF EXISTS `usertype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usertype` (
  `userTypeNr` int(11) NOT NULL,
  `userTypeDescr` varchar(45) NOT NULL DEFAULT 'UNKNOWN',
  PRIMARY KEY (`userTypeNr`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usertype`
--

LOCK TABLES `usertype` WRITE;
/*!40000 ALTER TABLE `usertype` DISABLE KEYS */;
INSERT INTO `usertype` VALUES (1,'ADMIN'),(2,'MANAGER'),(3,'CUSTOMER'),(99,'UNKNOWN');
/*!40000 ALTER TABLE `usertype` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-04-17 11:35:30
