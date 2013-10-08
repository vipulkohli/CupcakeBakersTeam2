CREATE DATABASE  IF NOT EXISTS `customcupcakes` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `customcupcakes`;
-- MySQL dump 10.13  Distrib 5.5.32, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: customcupcakes
-- ------------------------------------------------------
-- Server version	5.5.32-0ubuntu0.12.04.1

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
-- Table structure for table `cupcake_toppings`
--

DROP TABLE IF EXISTS `cupcake_toppings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cupcake_toppings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cupcake_id` int(11) NOT NULL,
  `topping_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cupcake_id_UNIQUE` (`cupcake_id`,`topping_id`),
  KEY `fk_cupcake_toppings_2_idx` (`topping_id`),
  KEY `index3` (`cupcake_id`),
  CONSTRAINT `fk_cupcake_toppings_1` FOREIGN KEY (`cupcake_id`) REFERENCES `cupcakes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cupcake_toppings_2` FOREIGN KEY (`topping_id`) REFERENCES `toppings` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cupcake_toppings`
--

LOCK TABLES `cupcake_toppings` WRITE;
/*!40000 ALTER TABLE `cupcake_toppings` DISABLE KEYS */;
INSERT INTO `cupcake_toppings` VALUES (1,1,1),(25,1,6),(49,1,10),(2,2,2),(26,2,5),(50,2,9),(3,3,3),(27,3,4),(51,3,8),(28,4,3),(4,4,4),(52,4,7),(29,5,2),(5,5,5),(53,5,6),(30,6,1),(54,6,5),(6,6,6),(31,7,1),(55,7,4),(7,7,7),(32,8,2),(56,8,3),(8,8,8),(57,9,2),(33,9,3),(9,9,9),(58,10,1),(34,10,4),(10,10,10),(59,11,1),(35,11,5),(11,11,11),(36,12,6),(12,12,12),(37,13,7),(13,13,13),(38,14,8),(14,14,14),(39,15,9),(15,15,15),(40,16,10),(16,16,15),(41,17,11),(17,17,14),(42,18,12),(18,18,13),(19,19,12),(43,19,13),(20,20,11),(44,20,14),(21,21,10),(45,21,15),(22,22,9),(46,22,13),(23,23,8),(47,23,12),(24,24,7),(48,24,11);
/*!40000 ALTER TABLE `cupcake_toppings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cupcakes`
--

DROP TABLE IF EXISTS `cupcakes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cupcakes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icing_id` int(11) NOT NULL,
  `flavor_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `filling_id` int(11) NOT NULL DEFAULT '-1',
  `quantity` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_cupcakes_1_idx` (`order_id`),
  KEY `fk_cupcakes_2_idx` (`filling_id`),
  KEY `fk_cupcakes_3_idx` (`flavor_id`),
  KEY `fk_cupcakes_4_idx` (`icing_id`),
  CONSTRAINT `fk_cupcakes_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_cupcakes_2` FOREIGN KEY (`filling_id`) REFERENCES `fillings` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_cupcakes_3` FOREIGN KEY (`flavor_id`) REFERENCES `flavors` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_cupcakes_4` FOREIGN KEY (`icing_id`) REFERENCES `icings` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cupcakes`
--

LOCK TABLES `cupcakes` WRITE;
/*!40000 ALTER TABLE `cupcakes` DISABLE KEYS */;
INSERT INTO `cupcakes` VALUES (1,1,1,NULL,9,1),(2,2,2,NULL,2,1),(3,3,3,NULL,3,1),(4,4,4,NULL,4,1),(5,5,5,NULL,5,1),(6,6,6,NULL,6,1),(7,7,7,NULL,7,1),(8,8,8,NULL,8,1),(9,9,9,NULL,9,1),(10,10,10,NULL,5,1),(11,11,11,NULL,2,1),(12,12,12,NULL,3,1),(13,13,13,NULL,1,1),(14,14,14,NULL,5,1),(15,15,1,NULL,6,1),(16,16,2,NULL,1,1),(17,17,3,NULL,8,1),(18,18,4,NULL,9,1),(19,19,5,NULL,4,1),(20,20,6,NULL,2,1),(21,21,7,NULL,3,1),(22,22,8,NULL,4,1),(23,23,9,NULL,5,1),(24,24,10,NULL,6,1),(25,1,11,NULL,7,1),(26,2,12,NULL,1,1),(27,3,13,NULL,9,1),(28,4,14,NULL,8,1),(29,5,1,NULL,2,1);
/*!40000 ALTER TABLE `cupcakes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `cupcake_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_favorites_1_idx` (`cupcake_id`),
  KEY `fk_favorites_2_idx` (`user_id`),
  CONSTRAINT `fk_favorites_1` FOREIGN KEY (`cupcake_id`) REFERENCES `cupcakes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_favorites_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorites`
--

LOCK TABLES `favorites` WRITE;
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
INSERT INTO `favorites` VALUES (1,1,1,''),(2,1,2,''),(3,2,3,''),(4,2,4,''),(5,3,5,''),(6,3,6,''),(7,4,7,''),(8,4,8,''),(9,5,9,''),(10,5,10,''),(11,6,11,''),(12,6,12,''),(13,7,13,''),(14,7,14,''),(15,8,15,''),(16,8,16,''),(17,9,17,''),(18,9,18,''),(19,10,19,''),(20,10,20,''),(21,1,21,''),(22,2,22,''),(23,3,23,''),(24,4,24,''),(25,5,25,''),(26,6,26,''),(27,7,27,''),(28,8,28,''),(29,9,29,'');
/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fillings`
--

DROP TABLE IF EXISTS `fillings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fillings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `price` double NOT NULL DEFAULT '0.5',
  `rgb` varchar(45) NOT NULL,
  `quantity_sold` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fillings`
--

LOCK TABLES `fillings` WRITE;
/*!40000 ALTER TABLE `fillings` DISABLE KEYS */;
INSERT INTO `fillings` VALUES (1,'No Filling',0.5,'#ffffff',0),(2,'Blueberry',0.5,'#8599ce',0),(3,'Blackberry',0.5,'#56107b',0),(4,'Lemon Meringue',0.5,'#f8ef6e',0),(5,'Strawbery',0.5,'#ec2c3a',0),(6,'Plum',0.5,'#ce87a9',0),(7,'Pomegranite',0.5,'#ec7696',0),(8,'Chocolate Ganache',0.5,'#694628',0),(9,'Vanilla',0.5,'#f8f8f8',0);
/*!40000 ALTER TABLE `fillings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `flavors`
--

DROP TABLE IF EXISTS `flavors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `flavors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `price` double NOT NULL DEFAULT '0.5',
  `img_url` varchar(45) NOT NULL,
  `quantity_sold` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flavors`
--

LOCK TABLES `flavors` WRITE;
/*!40000 ALTER TABLE `flavors` DISABLE KEYS */;
INSERT INTO `flavors` VALUES (1,'Banana',0.5,'banana.PNG',0),(2,'Carrot',0.5,'carrot.PNG',0),(3,'Chocolate',0.5,'chocolate.PNG',0),(4,'Coconut',0.5,'coconut.PNG',0),(5,'Cranberry',0.5,'cranberry.PNG',0),(6,'Dark Chocolate',0.5,'dark_chocolate.PNG',0),(7,'Grape',0.5,'grape.PNG',0),(8,'Kiwi',0.5,'kiwi.PNG',0),(9,'Lemon',0.5,'lemon.PNG',0),(10,'Milk Chocolate',0.5,'milk_chocolate.PNG',0),(11,'Orange Peel',0.5,'orange.PNG',0),(12,'Pineapple',0.5,'pineapple.PNG',0),(13,'Red Velvet',0.5,'redvelvet.PNG',0),(14,'Vanilla',0.5,'vanilla.PNG',0);
/*!40000 ALTER TABLE `flavors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `icings`
--

DROP TABLE IF EXISTS `icings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `price` double NOT NULL DEFAULT '0.5',
  `img_url` varchar(45) NOT NULL,
  `quantity_sold` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `icings`
--

LOCK TABLES `icings` WRITE;
/*!40000 ALTER TABLE `icings` DISABLE KEYS */;
INSERT INTO `icings` VALUES (1,'Vanilla',0.5,'vanilla_frosting.png',0),(2,'Chocolate',0.5,'chocolate_frosting.png',0),(3,'Creme Cheese',0.5,'creme_cheese_frosting.png',0),(4,'Lemon`',0.5,'lemon_frosting.png',0),(5,'Honey & Rosemary',0.5,'honey_rosemary_frosting.png',0),(6,'Vegan Vanilla',0.5,'vegan_vanilla_frosting.png',0),(7,'Salted Caramel',0.5,'salted_caramel_frosting.png',0),(8,'Vanilla Rose',0.5,'vanilla_rose_frosting.png',0),(9,'Buttered Popcorn',0.5,'buttered_popcorn_frosting.png',0),(10,'Cinnamon Toast',0.5,'cinnamon_toast_frosting.png',0),(11,'Lavender Caramel',0.5,'lavender_caramel_frosting.png',0),(12,'Cookie Dough',0.5,'cookie_dough_frosting.png',0),(13,'Raspberry Ripple',0.5,'raspberry_ripple_frosting.png',0),(14,'Raspberry White Chocolate',0.5,'raspberry_ripple_frosting.png',0),(15,'Caramel Mudslide',0.5,'caramel_mudslide_frosting.png',0),(16,'Earl Grey',0.5,'earl_grey_frosting.png',0),(17,'Lemon Poppyseed',0.5,'lemon_poppyseed_frosting.png',0),(18,'Chocolate Orange',0.5,'chocolate_orange_frosting.png',0),(19,'Strawberry Cremem',0.5,'strawberries_creame_frosting.png',0),(20,'Toffe Apple',0.5,'toffee_apple_frosting.png',0),(21,'Chocolate Hazlenut',0.5,'chocolate_hazelnut_frosting.png',0),(22,'Chai Latte',0.5,'chai_latte_frosting.png',0),(23,'Banana Pie',0.5,'banana_pie_frosting.png',0),(24,'Blueberry Cheesecake',0.5,'blueberry_cheesecake_frosting.png',0);
/*!40000 ALTER TABLE `icings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total_price` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_orders_1_idx` (`user_id`),
  CONSTRAINT `fk_orders_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `toppings`
--

DROP TABLE IF EXISTS `toppings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `toppings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `price` double NOT NULL DEFAULT '0.5',
  `quantity_sold` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `toppings`
--

LOCK TABLES `toppings` WRITE;
/*!40000 ALTER TABLE `toppings` DISABLE KEYS */;
INSERT INTO `toppings` VALUES (1,'Sprinkles',0.5,0),(2,'Mini Chocolate Chips',0.5,0),(3,'Mini Marshmellows',0.5,0),(4,'Bacon',0.5,0),(5,'Oreo Bits',0.5,0),(6,'Twix Bits',0.5,0),(7,'M&M\'s',0.5,0),(8,'Reese\'s Pieces',0.5,0),(9,'Butterfinger Bits',0.5,0),(10,'Snicker Bits',0.5,0),(11,'Skittles',0.5,0),(12,'Craisins',0.5,0),(13,'Maraschino Cherries',0.5,0),(14,'Gummy Bears',0.5,0),(15,'Pop Rocks',0.5,0);
/*!40000 ALTER TABLE `toppings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `password` varchar(100) NOT NULL,
  `salt` varchar(30) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(45) NOT NULL,
  `state` varchar(2) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `date_created` datetime NOT NULL,
  `is_on_mailing_list` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'BobbyDDickerson@armyspy.com','Bobby','Dickerson','$2a$05$q1AvLFfzNkd2I3aSHr2Z6.TKcrKbr2fprjQiyftjmI35R7HyIC3zW','q1AvLFfzNkd2I3aSHr2Z6','310-706-5713','4458 Jett Lane','Pomona','CA','91766','2013-10-08 09:58:45',0),(2,'JohnMHoran@cuvox.de','John','Horan','$2a$05$xxu3QpRqawJ.31x/ruiIj.2.ozVT4WZdw4KqTwF6p3iSP0mhPiO/K','xxu3QpRqawJ.31x/ruiIj','802-906-9635','814 Marion Street','Brattleboro','VT','5301','2013-10-08 09:58:45',0),(3,'LulaTBenjamin@einrot.com','Lula','Benjamin','$2a$05$NdG6UrHi4gyeBZM4RfE82.uoQCjeZ0i5zDKYeDeVp4kcqqxx7enWq','NdG6UrHi4gyeBZM4RfE82','641-740-3120','826 Park Boulevard','Casey','IA','50048','2013-10-08 09:58:45',0),(4,'FranklinIHills@rhyta.com','Franklin','Hills','$2a$05$DjJJCu7UmHyBjZ1UHNJd/.EIOHLqkRlsfqpRL6UhnwZ4EK6FrfhcG','DjJJCu7UmHyBjZ1UHNJd/','402-647-8591','1741 Snowbird Lane','Colon','NE','68018','2013-10-08 09:58:45',0),(5,'SamuelCBlevins@cuvox.de','Samuel','Blevins','$2a$05$mblhcLcqxb/Ai7AoRQAXA.ThvshVdiN6k4oxV2wSUnghR82fgbQDq','mblhcLcqxb/Ai7AoRQAXA','815-982-3812','2063 Memory Lane','Sterling','IL','61081','2013-10-08 09:58:45',0),(6,'WilliamRRaymond@cuvox.de','William','Raymond','$2a$05$rMYDgg81lOUdDtnw3Jj38.dTNPGyasHgr4tNW6lYK9.bCCUQJWrV.','rMYDgg81lOUdDtnw3Jj38','732-432-0200','889 Finwood Road','South River','NJ','8882','2013-10-08 09:58:45',0),(7,'JaniceRRobertson@superrito.com','Janice','Robertson','$2a$05$arzQYxmo7EP.NAG3ioJD9.KQZSqQMbIpVg7A1zZfoCnPamY7bSS5C','arzQYxmo7EP.NAG3ioJD9','479-214-4112','1694 Green Hill Road','Clarksville','AR','72830','2013-10-08 09:58:45',0),(8,'LashawnTLambert@einrot.com','Lashawn','Lambert','$2a$05$PTuWoCxxkfBZazeXlJHhE.l6.WSekFCuat.a558KmAixGr07N77Wq','PTuWoCxxkfBZazeXlJHhE','859-955-0616','4867 Counts Lane','Cincinnati','KY','45203','2013-10-08 09:58:45',0),(9,'VanessaGSeals@dayrep.com','Vanessa','Seals','$2a$05$0d6gNBPrdwC6anNJPc6An.Yx4u210WRRv7ztGBxrvvvrS/uTXqSXm','0d6gNBPrdwC6anNJPc6An','417-629-4257','2940 Chandler Drive','Joplin','MO','64801','2013-10-08 09:58:45',0),(10,'BethanyETong@dayrep.com','Bethany','Tong','$2a$05$CqqN3yH/OQ69to51bTFIP.QMZeDmTSsmEhS4Upsf6ea1mtJOrJpsO','CqqN3yH/OQ69to51bTFIP','937-260-7087','2197 Pursglove Court','Harrison (Twp) Montgomery','OH','45406','2013-10-08 09:58:45',0);
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

-- Dump completed on 2013-10-08 11:08:45
