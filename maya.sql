-- MySQL dump 10.13  Distrib 8.0.18, for Win64 (x86_64)
--
-- Host: localhost    Database: maya
-- ------------------------------------------------------
-- Server version	8.0.18

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
-- Table structure for table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorie`
--

LOCK TABLES `categorie` WRITE;
/*!40000 ALTER TABLE `categorie` DISABLE KEYS */;
INSERT INTO `categorie` VALUES (1,'Fruits'),(2,' Aromatiques'),(3,'Légumes'),(4,'Conserves');
/*!40000 ALTER TABLE `categorie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_contact` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_en_relation` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` VALUES (3,'Richard','chartierlucas@outlook.fr','0606156126','popopo','2020-04-02'),(4,'euhhh wtf','addaad','afaffa','fafafaaf','2020-04-02');
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fournisseur`
--

DROP TABLE IF EXISTS `fournisseur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fournisseur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tel` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_en_relation` date NOT NULL,
  `nom_contact` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fournisseur`
--

LOCK TABLES `fournisseur` WRITE;
/*!40000 ALTER TABLE `fournisseur` DISABLE KEYS */;
INSERT INTO `fournisseur` VALUES (3,'Hello','Paul','Becker','paulhello@outook.fr','0758964856','2020-04-03','28 rue zizani'),(4,'Meyer','Tiffany','Becker','meyertiffany@gmail.com','0752964536','2020-04-03','63 rue des pleurs');
/*!40000 ALTER TABLE `fournisseur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration_versions`
--

DROP TABLE IF EXISTS `migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration_versions`
--

LOCK TABLES `migration_versions` WRITE;
/*!40000 ALTER TABLE `migration_versions` DISABLE KEYS */;
INSERT INTO `migration_versions` VALUES ('20200228180140','2020-02-28 18:03:56');
/*!40000 ALTER TABLE `migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produit`
--

DROP TABLE IF EXISTS `produit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` decimal(7,2) NOT NULL,
  `date_creation` datetime NOT NULL,
  `categorie_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produit`
--

LOCK TABLES `produit` WRITE;
/*!40000 ALTER TABLE `produit` DISABLE KEYS */;
INSERT INTO `produit` VALUES (11,'lardon',3.95,'0000-00-00 00:00:00',0),(12,'crème fraiche',2.59,'0000-00-00 00:00:00',0),(13,'persil',1.65,'0000-00-00 00:00:00',0),(14,'oeuf',1.69,'0000-00-00 00:00:00',0),(15,'thon',2.59,'0000-00-00 00:00:00',0),(16,'mayo',1.65,'0000-00-00 00:00:00',0),(17,'pain',1.69,'0000-00-00 00:00:00',0),(18,'salade',1.00,'0000-00-00 00:00:00',0),(19,'tomate',1.12,'0000-00-00 00:00:00',0);
/*!40000 ALTER TABLE `produit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recette`
--

DROP TABLE IF EXISTS `recette`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recette` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descr` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recette`
--

LOCK TABLES `recette` WRITE;
/*!40000 ALTER TABLE `recette` DISABLE KEYS */;
INSERT INTO `recette` VALUES (1,'Pâte carbonara','Pâte à la crème et aux lardons avec du persil et du parmesan'),(2,'Courgette farcie','Courgette farcie traditionnelle'),(3,'Soupe','Soupe aux 6 légumes de la mamie'),(4,'SandwichthonMayo','Sandwich thon mayo meilleur que dans les boulangeries');
/*!40000 ALTER TABLE `recette` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recette_produit`
--

DROP TABLE IF EXISTS `recette_produit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recette_produit` (
  `recette_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  PRIMARY KEY (`recette_id`,`produit_id`),
  KEY `IDX_EDDD365D89312FE9` (`recette_id`),
  KEY `IDX_EDDD365DF347EFB` (`produit_id`),
  CONSTRAINT `FK_EDDD365D89312FE9` FOREIGN KEY (`recette_id`) REFERENCES `recette` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_EDDD365DF347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recette_produit`
--

LOCK TABLES `recette_produit` WRITE;
/*!40000 ALTER TABLE `recette_produit` DISABLE KEYS */;
INSERT INTO `recette_produit` VALUES (1,11),(1,12),(1,13),(1,14),(4,15),(4,16),(4,17),(4,18),(4,19);
/*!40000 ALTER TABLE `recette_produit` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-04-06 11:23:44
