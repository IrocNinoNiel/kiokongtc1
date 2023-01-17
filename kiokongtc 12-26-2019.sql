CREATE DATABASE  IF NOT EXISTS `kiokongtc` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `kiokongtc`;
-- MySQL dump 10.13  Distrib 8.0.13, for Win64 (x86_64)
--
-- Host: Dataserver    Database: kiokongtc
-- ------------------------------------------------------
-- Server version	5.7.24-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `accadjustment`
--

DROP TABLE IF EXISTS `accadjustment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `accadjustment` (
  `idAccAdjustment` int(11) NOT NULL AUTO_INCREMENT,
  `idAffiliate` int(11) DEFAULT NULL,
  `idReference` int(11) DEFAULT NULL,
  `idReferenceSeries` int(11) DEFAULT NULL,
  `idCostCenter` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `description` char(250) DEFAULT NULL,
  `pType` int(1) DEFAULT NULL COMMENT '1 - Supplier\n2 - Customer',
  `pCode` int(11) DEFAULT NULL,
  `negativeAdjustment` int(1) DEFAULT '0' COMMENT '1 - True\n0 - False',
  `amount` decimal(18,2) DEFAULT '0.00',
  `remarks` text,
  `fident` int(11) DEFAULT NULL,
  `fref` int(11) DEFAULT NULL,
  `frefnum` int(11) DEFAULT NULL,
  `famt` decimal(18,2) DEFAULT '0.00',
  `hasJournal` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `notedBy` int(11) DEFAULT NULL,
  `preparedBy` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '1' COMMENT '1 - Pending\n2 - Approved\n3 - Cancelled',
  PRIMARY KEY (`idAccAdjustment`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accadjustment`
--

LOCK TABLES `accadjustment` WRITE;
/*!40000 ALTER TABLE `accadjustment` DISABLE KEYS */;
/*!40000 ALTER TABLE `accadjustment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `accadjustmenthistory`
--

DROP TABLE IF EXISTS `accadjustmenthistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `accadjustmenthistory` (
  `idAccAdjustmentHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idAccAdjustment` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `idReference` int(11) DEFAULT NULL,
  `idReferenceSeries` int(11) DEFAULT NULL,
  `idCostCenter` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `description` char(250) DEFAULT NULL,
  `pType` int(1) DEFAULT NULL COMMENT '1 - Supplier\n2 - Customer',
  `pCode` int(11) DEFAULT NULL,
  `negativeAdjustment` int(1) DEFAULT '0' COMMENT '1 - True\n0 - False',
  `amount` decimal(18,2) DEFAULT '0.00',
  `remarks` text,
  `fident` int(11) DEFAULT NULL,
  `fref` int(11) DEFAULT NULL,
  `frefnum` int(11) DEFAULT NULL,
  `famt` decimal(18,2) DEFAULT '0.00',
  `hasJournal` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `notedBy` int(11) DEFAULT NULL,
  `preparedBy` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '1' COMMENT '1 - Pending\n2 - Approved\n3 - Cancelled',
  PRIMARY KEY (`idAccAdjustmentHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accadjustmenthistory`
--

LOCK TABLES `accadjustmenthistory` WRITE;
/*!40000 ALTER TABLE `accadjustmenthistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `accadjustmenthistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `accountbegbal`
--

DROP TABLE IF EXISTS `accountbegbal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `accountbegbal` (
  `idAccBegBal` int(11) NOT NULL AUTO_INCREMENT,
  `idAffiliate` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`idAccBegBal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accountbegbal`
--

LOCK TABLES `accountbegbal` WRITE;
/*!40000 ALTER TABLE `accountbegbal` DISABLE KEYS */;
/*!40000 ALTER TABLE `accountbegbal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `adjusted`
--

DROP TABLE IF EXISTS `adjusted`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `adjusted` (
  `idAdjusted` int(11) NOT NULL AUTO_INCREMENT,
  `idBankRecon` int(11) DEFAULT NULL,
  `description` char(250) DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT '0.00',
  `adjustedTag` int(1) DEFAULT '0' COMMENT '1 - Add\n2 - Less',
  PRIMARY KEY (`idAdjusted`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adjusted`
--

LOCK TABLES `adjusted` WRITE;
/*!40000 ALTER TABLE `adjusted` DISABLE KEYS */;
/*!40000 ALTER TABLE `adjusted` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `adjustedhistory`
--

DROP TABLE IF EXISTS `adjustedhistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `adjustedhistory` (
  `idAdjustedHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idAdjusted` int(11) DEFAULT NULL,
  `idBankRecon` int(11) DEFAULT NULL,
  `description` char(250) DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT '0.00',
  `adjustedTag` int(1) DEFAULT '0' COMMENT '1 - Add\n2 - Less',
  PRIMARY KEY (`idAdjustedHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adjustedhistory`
--

LOCK TABLES `adjustedhistory` WRITE;
/*!40000 ALTER TABLE `adjustedhistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `adjustedhistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `affiliate`
--

DROP TABLE IF EXISTS `affiliate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `affiliate` (
  `idAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `affiliateName` text,
  `tagLine` text,
  `address` text,
  `affiliateContactPerson` char(50) DEFAULT NULL,
  `affiliateContactNumber` bigint(11) DEFAULT NULL,
  `email` char(50) DEFAULT NULL,
  `tin` char(20) DEFAULT NULL,
  `vatPercent` decimal(18,2) DEFAULT '0.00',
  `vatType` int(1) DEFAULT '1' COMMENT '1 - Inclusive\n2 - Exclusive',
  `checkedBy` char(50) DEFAULT NULL,
  `reviewedBy` char(50) DEFAULT NULL,
  `approvedBy1` int(11) DEFAULT NULL,
  `approvedBy2` int(11) DEFAULT NULL,
  `accSchedule` int(1) DEFAULT '1' COMMENT '1 - Calendar\n2 - Fiscal',
  `month` int(2) DEFAULT '1' COMMENT '1 - January\n2 - February\n3 - March\n4 - April\n5 - May\n6 - June\n7 - July\n8 - August\n9 - September\n10 - October\n11 - November\n12 - December',
  `remarks` text,
  `refTag` int(1) DEFAULT NULL,
  `logo` text,
  `status` int(1) DEFAULT '0' COMMENT '1 - Active\n2 - Inactive',
  `mainTag` int(1) DEFAULT '0',
  `location` int(11) DEFAULT NULL,
  `dateStart` date DEFAULT NULL,
  PRIMARY KEY (`idAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `affiliate`
--

LOCK TABLES `affiliate` WRITE;
/*!40000 ALTER TABLE `affiliate` DISABLE KEYS */;
INSERT INTO `affiliate` VALUES (9,'The Eyrie of House Arryn','Look up, look down!','Vale of Arryn, Somewhere North','Bronn the chicken.',0,'bronn@housearryn.com','0983782637=--==-=',0.00,1,'Me One','Me Two',NULL,NULL,2,6,NULL,1,NULL,1,0,3,'2019-12-09'),(12,'House Tyrell of Highgarden','Growing Strong','Highgarden','Margaery Tyrell',63245846,'marg@yopmail.com','785462',0.00,2,'Loras Tyrell','Olenna Tyrell',1,52,2,2,'None',1,NULL,1,0,3,NULL),(13,'House Baratheon','Lorem Ipsum',NULL,NULL,2147483647,NULL,'78955525',0.00,2,NULL,NULL,NULL,NULL,1,NULL,'None',2,NULL,1,0,3,NULL),(14,'Affiliate 1',NULL,NULL,NULL,2147483647,NULL,'123456789',0.00,2,NULL,NULL,NULL,NULL,1,NULL,NULL,2,NULL,1,0,3,NULL),(15,'Affiliate 01245','Lorem Ipsum',NULL,NULL,NULL,NULL,'333333333333',0.00,2,NULL,NULL,NULL,NULL,1,12,NULL,2,NULL,1,0,3,NULL),(16,'Affiliate 1001','Lorem Ipsum',NULL,NULL,2147483647,NULL,'4444444',0.00,2,NULL,NULL,NULL,NULL,1,NULL,NULL,2,NULL,1,0,3,NULL),(17,'Affiliate 456',NULL,NULL,NULL,NULL,NULL,'999999999',0.00,2,NULL,NULL,NULL,NULL,1,12,'None',2,NULL,1,0,3,NULL),(18,'Affiliate 500',NULL,NULL,NULL,2147483647,NULL,'789456',0.00,2,NULL,NULL,NULL,NULL,1,NULL,NULL,2,NULL,1,0,3,NULL),(24,'Affiliate Z',NULL,NULL,NULL,978552631,'hazel@gmail.com','455454554545',0.00,2,NULL,NULL,NULL,NULL,1,12,NULL,2,NULL,1,0,3,NULL),(25,'Main Affiliate',NULL,NULL,NULL,NULL,'main@yopmail.com','789666316',0.00,1,NULL,NULL,NULL,NULL,1,12,NULL,2,NULL,1,1,3,NULL),(26,'Affiliate 0000',NULL,NULL,NULL,NULL,'000@gmail.com','44545',0.00,2,NULL,NULL,NULL,NULL,1,12,NULL,2,NULL,1,0,3,NULL),(27,'Affiliate 001',NULL,NULL,NULL,NULL,'af@yopmail.com','78996421',0.00,2,NULL,NULL,NULL,NULL,1,12,NULL,2,NULL,1,0,3,NULL),(28,'Affiliate 2019','Cheers, 2019!','Address','John',912356789,'john@yopmail.com','11222554421',0.00,2,NULL,NULL,NULL,NULL,1,12,NULL,2,NULL,1,0,5,'2019-12-27'),(29,'test affiliate',NULL,NULL,NULL,NULL,NULL,'287462893754',0.00,2,NULL,NULL,NULL,NULL,2,3,NULL,2,NULL,1,0,3,'2017-12-04'),(30,'Non Required Cost Center',NULL,NULL,NULL,NULL,NULL,'123456',0.00,2,NULL,NULL,NULL,NULL,1,12,NULL,2,NULL,1,0,3,'2019-12-12'),(31,'Required Cost Center',NULL,NULL,NULL,NULL,'test@dispostable.com','321654',0.00,2,NULL,NULL,NULL,NULL,1,12,NULL,1,NULL,1,0,4,'2019-12-12'),(32,'Camille Shaffer','Aliquid ut quam amet','In sed blanditiis se','Deleniti rem vel modi commodo libero ipsum sit v',885,'nidufo@mailinator.net','Asperiores sit conse',0.00,2,'Nobis quos aut rerum ut qui fugiat et doloribus de','Vero fugiat incididunt quam ut eu aut ab rerum ut',1,101,1,12,'Est ad distinctio S',2,NULL,1,0,5,'2019-12-12'),(33,'Natalie Maxwell','Eius nihil culpa ut','Sint nostrum invent','Qui quam amet incidunt in provident error ut el',546,'ceso@mailinator.net','Quia eiusmod iure en',0.00,2,'Nam voluptatem omnis dolores voluptatem Ipsum ra','Est in velit tenetur in aut cum et non aut amet m',1,99,0,12,'In omnis lorem nulla',1,NULL,1,0,4,'2019-12-12');
/*!40000 ALTER TABLE `affiliate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `alocations`
--

DROP TABLE IF EXISTS `alocations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `alocations` (
  `idAlocations` int(11) NOT NULL AUTO_INCREMENT,
  `idEu` int(11) DEFAULT NULL,
  `idLocation` int(11) DEFAULT NULL,
  PRIMARY KEY (`idAlocations`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alocations`
--

LOCK TABLES `alocations` WRITE;
/*!40000 ALTER TABLE `alocations` DISABLE KEYS */;
/*!40000 ALTER TABLE `alocations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `amodule`
--

DROP TABLE IF EXISTS `amodule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `amodule` (
  `idAmodule` int(11) NOT NULL AUTO_INCREMENT,
  `idModule` int(11) DEFAULT NULL,
  `idEu` int(11) DEFAULT NULL,
  `moduleType` int(1) DEFAULT '0',
  `canSave` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `canEdit` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `canDelete` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `canPrint` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `canCancel` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `canConfirm` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  PRIMARY KEY (`idAmodule`)
) ENGINE=InnoDB AUTO_INCREMENT=264 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `amodule`
--

LOCK TABLES `amodule` WRITE;
/*!40000 ALTER TABLE `amodule` DISABLE KEYS */;
INSERT INTO `amodule` VALUES (1,1,1,0,1,1,1,1,1,1),(2,2,1,0,1,1,1,1,1,1),(6,6,1,0,1,1,1,1,1,1),(13,4,1,5,1,1,1,1,0,0),(15,5,1,5,1,1,1,1,0,0),(17,3,1,5,1,1,1,1,0,0),(19,9,1,5,1,1,1,1,0,0),(20,7,1,5,1,1,1,1,0,0),(30,1,59,0,1,1,1,1,0,0),(31,2,59,0,1,1,1,1,0,0),(32,3,59,5,1,1,1,1,0,0),(33,4,59,5,1,1,1,1,0,0),(34,5,59,5,1,1,1,1,0,0),(35,6,59,0,1,1,1,1,0,0),(36,7,59,5,1,1,1,1,0,0),(37,8,59,4,1,1,1,1,0,0),(38,9,59,5,1,1,1,1,0,0),(39,4,10,5,1,1,1,1,0,0),(40,7,10,5,1,1,1,1,0,0),(41,5,10,5,1,1,1,1,0,0),(42,6,10,5,1,1,1,1,0,0),(43,9,10,5,1,1,1,1,0,0),(44,3,10,5,1,1,1,1,0,0),(45,2,10,1,1,1,1,1,0,0),(46,8,10,4,1,1,1,1,0,0),(54,4,30,5,1,1,1,1,0,0),(55,7,30,5,1,1,1,1,0,0),(56,5,30,5,1,1,1,1,0,0),(57,6,30,5,1,1,1,1,0,0),(58,9,30,5,1,1,1,1,0,0),(59,3,30,5,1,1,1,1,0,0),(82,1,30,0,0,0,0,0,0,0),(118,8,29,4,1,1,1,1,0,0),(129,4,32,5,1,1,1,1,0,0),(130,7,32,5,1,1,1,1,0,0),(131,5,32,5,1,1,1,1,0,0),(132,6,32,5,1,1,1,1,0,0),(133,9,32,5,1,1,1,1,0,0),(134,3,32,5,1,1,1,1,0,0),(155,10,30,4,1,1,1,1,0,0),(156,11,30,4,1,1,1,1,0,0),(157,13,30,4,1,1,1,1,0,0),(158,8,30,4,1,1,1,1,0,0),(159,12,30,4,1,1,1,1,0,0),(164,14,9,1,1,1,1,1,0,0),(165,16,9,1,1,1,1,1,0,0),(166,2,9,1,1,1,1,1,0,0),(167,15,9,1,1,1,1,1,0,0),(173,10,1,4,1,1,1,1,0,0),(174,11,1,4,1,1,1,1,0,0),(175,13,1,4,1,1,1,1,0,0),(176,8,1,4,1,1,1,1,0,0),(177,12,1,4,1,1,1,1,0,0),(178,14,34,1,1,1,1,1,0,0),(179,16,34,1,1,1,1,1,0,0),(180,2,34,1,1,1,1,1,0,0),(181,15,34,1,1,1,1,1,0,0),(182,1,34,0,1,1,1,1,0,0),(183,10,34,4,1,1,1,1,0,0),(184,11,34,4,1,1,1,1,0,0),(185,13,34,4,1,1,1,1,0,0),(186,8,34,4,1,1,1,1,0,0),(187,12,34,4,1,1,1,1,0,0),(188,4,34,5,1,1,1,1,0,0),(189,7,34,5,1,1,1,1,0,0),(190,5,34,5,1,1,1,1,0,0),(191,6,34,5,1,1,1,1,0,0),(192,9,34,5,1,1,1,1,0,0),(193,3,34,5,1,1,1,1,0,0),(209,14,1,1,1,1,1,1,0,0),(210,16,1,1,1,1,1,1,0,0),(211,2,1,1,1,1,1,1,0,0),(212,2,1,1,1,1,1,1,0,0),(213,18,1,1,1,1,1,1,0,0),(214,17,1,1,1,1,1,1,0,0),(215,15,1,1,1,1,1,1,0,0),(216,14,31,1,1,1,1,1,0,0),(217,16,31,1,1,1,1,1,0,0),(218,2,31,1,1,1,1,1,0,0),(219,18,31,1,1,1,1,1,0,0),(220,17,31,1,1,1,1,1,0,0),(221,15,31,1,1,1,1,1,0,0),(222,1,31,0,1,1,1,1,0,0),(228,4,31,5,1,1,1,1,0,0),(229,7,31,5,1,1,1,1,0,0),(230,5,31,5,1,1,1,1,0,0),(231,6,31,5,1,1,1,1,0,0),(232,9,31,5,1,1,1,1,0,0),(233,3,31,5,1,1,1,1,0,0),(234,14,30,1,1,1,1,1,0,0),(235,16,30,1,1,1,1,1,0,0),(236,2,30,1,1,1,1,1,0,0),(237,18,30,1,1,1,1,1,0,0),(238,17,30,1,1,1,1,1,0,0),(239,15,30,1,1,1,1,1,0,0),(240,14,36,1,1,1,1,1,0,0),(241,16,36,1,1,1,1,1,0,0),(242,2,36,1,1,1,1,1,0,0),(243,18,36,1,1,1,1,1,0,0),(244,17,36,1,1,1,1,1,0,0),(245,15,36,1,1,1,1,1,0,0),(246,10,36,4,1,1,1,1,0,0),(247,11,36,4,1,1,1,1,0,0),(248,13,36,4,1,1,1,1,0,0),(249,8,36,4,1,1,1,1,0,0),(250,12,36,4,1,1,1,1,0,0),(251,4,36,5,1,1,1,1,0,0),(252,7,36,5,1,1,1,1,0,0),(253,5,36,5,1,1,1,1,0,0),(254,6,36,5,1,1,1,1,0,0),(255,9,36,5,1,1,1,1,0,0),(256,3,36,5,1,1,1,1,0,0),(257,10,31,4,1,1,1,1,0,0),(258,11,31,4,1,1,1,1,0,0),(259,13,31,4,1,1,1,1,0,0),(260,8,31,4,1,1,1,1,0,0),(261,12,31,4,1,1,1,1,0,0),(262,18,29,1,1,1,1,1,0,0),(263,19,34,2,1,1,1,1,1,1);
/*!40000 ALTER TABLE `amodule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `autobackup`
--

DROP TABLE IF EXISTS `autobackup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `autobackup` (
  `idAB` int(11) NOT NULL AUTO_INCREMENT,
  `abType` int(1) DEFAULT '3' COMMENT '1 - Daily\n2 - Weekly\n3 - Monthly',
  `abWeek` int(1) DEFAULT '1' COMMENT '1 - Week 1\n2 - Week 2\n3 - Week 3\n4 - Week 4',
  `abDay` int(1) DEFAULT '1' COMMENT '1 - Sunday\n2 - Monday\n3 - Tuesday\n4 - Wednesday\n5 - Thursday\n6 - Friday\n7 - Saturday',
  `abTime` time DEFAULT NULL,
  PRIMARY KEY (`idAB`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `autobackup`
--

LOCK TABLES `autobackup` WRITE;
/*!40000 ALTER TABLE `autobackup` DISABLE KEYS */;
/*!40000 ALTER TABLE `autobackup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `backuphistory`
--

DROP TABLE IF EXISTS `backuphistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `backuphistory` (
  `idBHistory` int(11) NOT NULL AUTO_INCREMENT,
  `bhDate` date DEFAULT NULL,
  `bhTime` time DEFAULT NULL,
  `bhFile` text,
  `bhUser` int(11) DEFAULT NULL,
  PRIMARY KEY (`idBHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backuphistory`
--

LOCK TABLES `backuphistory` WRITE;
/*!40000 ALTER TABLE `backuphistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `backuphistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bank`
--

DROP TABLE IF EXISTS `bank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `bank` (
  `idBank` int(11) NOT NULL AUTO_INCREMENT,
  `bankName` char(20) DEFAULT NULL,
  PRIMARY KEY (`idBank`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank`
--

LOCK TABLES `bank` WRITE;
/*!40000 ALTER TABLE `bank` DISABLE KEYS */;
INSERT INTO `bank` VALUES (1,'Banco de Oro'),(6,'BP Eye'),(7,'Iron Bank'),(8,'World Bank'),(9,'Piggy Bank');
/*!40000 ALTER TABLE `bank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bankaccount`
--

DROP TABLE IF EXISTS `bankaccount`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `bankaccount` (
  `idBankAccount` int(11) NOT NULL AUTO_INCREMENT,
  `idAffiliate` int(11) DEFAULT NULL,
  `idBank` int(11) DEFAULT NULL,
  `bankAccount` char(50) DEFAULT NULL,
  `bankAccountNumber` char(50) DEFAULT NULL,
  `begBal` decimal(18,2) DEFAULT '0.00',
  `idCoa` int(11) DEFAULT NULL,
  `remarks` text,
  PRIMARY KEY (`idBankAccount`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bankaccount`
--

LOCK TABLES `bankaccount` WRITE;
/*!40000 ALTER TABLE `bankaccount` DISABLE KEYS */;
/*!40000 ALTER TABLE `bankaccount` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bankrecon`
--

DROP TABLE IF EXISTS `bankrecon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `bankrecon` (
  `idBankRecon` int(11) NOT NULL AUTO_INCREMENT,
  `idReference` int(11) DEFAULT NULL,
  `idReferenceSeries` int(11) DEFAULT NULL,
  `reconDate` date DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `reconMonth` int(2) DEFAULT NULL COMMENT '1 - January\n2 - February\n3 - March\n4 - April\n5 - May\n6 - June\n7 - July\n8 - August\n9 - September\n10 - October\n11 - November\n12 - December',
  `idBank` int(11) DEFAULT NULL,
  `idBankAccount` int(11) DEFAULT NULL,
  `description` char(250) DEFAULT NULL,
  `remark` char(250) DEFAULT NULL,
  `adjustedBankBal` decimal(18,2) DEFAULT '0.00',
  `adjustedBookBal` decimal(18,2) DEFAULT '0.00',
  `dateModified` datetime DEFAULT NULL,
  `idCostCenter` int(11) DEFAULT NULL,
  `unAdjustedBankBalance` decimal(18,2) DEFAULT '0.00',
  `unAdjustedBookBalance` decimal(18,2) DEFAULT '0.00',
  `reconYear` int(4) DEFAULT NULL,
  `bankBalNextMonth` decimal(18,2) DEFAULT '0.00',
  `hasJournal` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `preparedBy` int(11) DEFAULT NULL,
  `notedBy` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '1' COMMENT '1 - Pending\n2 - Approved\n3 - Cancelled',
  PRIMARY KEY (`idBankRecon`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bankrecon`
--

LOCK TABLES `bankrecon` WRITE;
/*!40000 ALTER TABLE `bankrecon` DISABLE KEYS */;
/*!40000 ALTER TABLE `bankrecon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bankreconhistory`
--

DROP TABLE IF EXISTS `bankreconhistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `bankreconhistory` (
  `idBankReconHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idBankRecon` int(11) DEFAULT NULL,
  `idReference` int(11) DEFAULT NULL,
  `idReferenceSeries` int(11) DEFAULT NULL,
  `reconDate` date DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `reconMonth` int(2) DEFAULT '1' COMMENT '1 - January\n2 - February\n3 - March\n4 - April\n5 - May\n6 - June\n7 - July\n8 - August\n9 - September\n10 - October\n11 - November\n12 - December',
  `idBank` int(11) DEFAULT NULL,
  `idBankAccount` int(11) DEFAULT NULL,
  `description` text,
  `remark` text,
  `adjustedBankBal` decimal(18,2) DEFAULT '0.00',
  `adjustedBookBal` decimal(18,2) DEFAULT '0.00',
  `dateModified` datetime DEFAULT NULL,
  `idCostCenter` int(11) DEFAULT NULL,
  `unAdjustedBankBalance` decimal(18,2) DEFAULT '0.00',
  `unAdjustedBookBalance` decimal(18,2) DEFAULT '0.00',
  `reconYear` int(11) DEFAULT NULL,
  `bankBalNextMonth` decimal(18,2) DEFAULT '0.00',
  `preparedBy` int(11) DEFAULT NULL,
  `notedBy` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '1' COMMENT '1 - Pending\n2 - Approved\n3 - Cancelled',
  PRIMARY KEY (`idBankReconHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bankreconhistory`
--

LOCK TABLES `bankreconhistory` WRITE;
/*!40000 ALTER TABLE `bankreconhistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `bankreconhistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `begbal`
--

DROP TABLE IF EXISTS `begbal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `begbal` (
  `idBegBal` int(11) NOT NULL AUTO_INCREMENT,
  `idAccBegBal` int(11) DEFAULT NULL,
  `idCoa` int(11) DEFAULT NULL,
  `begBal` decimal(18,2) DEFAULT '0.00',
  PRIMARY KEY (`idBegBal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `begbal`
--

LOCK TABLES `begbal` WRITE;
/*!40000 ALTER TABLE `begbal` DISABLE KEYS */;
/*!40000 ALTER TABLE `begbal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `beginningbalances`
--

DROP TABLE IF EXISTS `beginningbalances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `beginningbalances` (
  `idbeginningBalances` int(11) NOT NULL AUTO_INCREMENT,
  `idAffiliate` int(11) DEFAULT NULL,
  `idCostCenter` int(11) DEFAULT NULL,
  `idReference` int(11) DEFAULT NULL,
  `idReferenceSeries` int(11) DEFAULT NULL,
  `idLocation` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `pType` int(1) DEFAULT NULL COMMENT '1 - Supplier\n2 - Customer',
  `pCode` int(11) DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT '0.00',
  `remarks` text,
  `hasJournal` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `notedBy` int(11) DEFAULT NULL,
  `preparedBy` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '1' COMMENT '1 - Pending\n2 - Approved\n3 - Cancelled',
  PRIMARY KEY (`idbeginningBalances`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `beginningbalances`
--

LOCK TABLES `beginningbalances` WRITE;
/*!40000 ALTER TABLE `beginningbalances` DISABLE KEYS */;
/*!40000 ALTER TABLE `beginningbalances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `closing`
--

DROP TABLE IF EXISTS `closing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `closing` (
  `idClosing` int(11) NOT NULL AUTO_INCREMENT,
  `idAffiliate` int(11) DEFAULT NULL,
  `idReference` int(11) DEFAULT NULL,
  `idReferenceSeries` int(11) DEFAULT NULL,
  `description` text,
  `date` date DEFAULT NULL,
  `remarks` text,
  `month` int(2) DEFAULT NULL COMMENT '1 - January\n2 - February\n3 - March\n4 - April\n5 - May\n6 - June\n7 - July\n8 - August\n9 - September\n10 - October\n11 - November\n12 - December',
  `year` int(4) DEFAULT NULL,
  PRIMARY KEY (`idClosing`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `closing`
--

LOCK TABLES `closing` WRITE;
/*!40000 ALTER TABLE `closing` DISABLE KEYS */;
/*!40000 ALTER TABLE `closing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coa`
--

DROP TABLE IF EXISTS `coa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `coa` (
  `idCoa` int(11) NOT NULL AUTO_INCREMENT,
  `acod_c15` char(15) DEFAULT NULL,
  `aname_c30` char(100) DEFAULT NULL,
  `mocod_c1` int(1) unsigned zerofill DEFAULT '0' COMMENT '1 - Assets | 2 - Liabilities | 3 - Capital | 4 -Revenue | 5 -Expenses',
  `chcod_c1` int(1) unsigned zerofill DEFAULT '0',
  `accod_c2` int(2) unsigned zerofill DEFAULT '00',
  `sucod_c3` int(3) unsigned zerofill DEFAULT '000',
  `norm_c2` char(2) DEFAULT NULL,
  `accID` int(2) DEFAULT '0',
  `accountType` int(1) DEFAULT NULL COMMENT '1 - header | 2 - subsidiary',
  `dateModified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `recordedBy` int(11) DEFAULT NULL,
  `cashflow_classification` int(1) DEFAULT '0',
  PRIMARY KEY (`idCoa`)
) ENGINE=MyISAM AUTO_INCREMENT=5136001 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coa`
--

LOCK TABLES `coa` WRITE;
/*!40000 ALTER TABLE `coa` DISABLE KEYS */;
INSERT INTO `coa` VALUES (4101000,'4101','TUITION FEE',4,1,01,000,'CR',14,1,'2017-03-05 15:34:05',1,3),(4102000,'4102','COMPUTER FEE',4,1,02,000,'CR',14,1,'2017-03-05 15:35:51',1,3),(4103000,'4103','TYPING FEE',4,1,03,000,'CR',14,1,'2017-03-05 15:36:47',1,3),(4104000,'4104','ESC',4,1,04,000,'CR',14,1,'2017-03-05 15:37:36',1,3),(4105000,'4105','ESC ADDITIONAL',4,1,05,000,'CR',14,1,'2017-03-05 15:38:35',1,3),(4106000,'4106','SUMMER (ASAP)',4,1,06,000,'CR',14,1,'2017-03-05 15:39:12',1,3),(4107000,'4107','MODULAR CLASS',4,1,07,000,'CR',14,1,'2017-03-05 15:40:24',1,3),(4108000,'4108-00','REGISTRATION/ENTRANCE FEES',4,1,08,000,'CR',14,1,'2017-03-05 15:43:27',1,3),(4108001,'4108-01','MATRICULATION',4,1,08,001,'CR',14,2,'2017-03-05 15:45:16',1,3),(4108002,'4108-02','REPORT CARD, ID, AND LIBRARY CARD',4,1,08,002,'CR',14,2,'2017-03-05 15:46:33',1,3),(4108003,'4108-03','STUDENT HANDBOOK',4,1,08,003,'CR',14,2,'2017-03-05 15:48:43',1,3),(4108004,'4108-04','STUDENT LEDGER/TEST PERMIT CARD/ASSESSMENT',4,1,08,004,'CR',14,2,'2017-03-05 15:52:28',1,3),(4109000,'4109-00','ACTIVITY FEES',4,1,09,000,'CR',14,1,'2017-03-05 15:53:52',1,3),(4109001,'4109-01','STUDENT ACTIVITY FEE',4,1,09,001,'CR',14,2,'2017-03-05 15:55:06',1,3),(4109002,'4109-02','ATHLETIC/CULTURAL FEE',4,1,09,002,'CR',14,2,'2017-03-05 15:56:25',1,3),(4109003,'4109-03','MAPEH/SPORTS FEE',4,1,09,003,'CR',14,2,'2017-03-05 15:57:17',1,3),(4110000,'4110-00','STUDENT SERVICES FEES',4,1,10,000,'CR',14,1,'2017-03-05 15:58:17',1,3),(4110001,'4110-01','TESTING MATERIALS FEE',4,1,10,001,'CR',14,2,'2017-03-05 15:59:25',1,3),(4110002,'4110-02','LIBRARY FEE/AVR FEE',4,1,10,002,'CR',14,2,'2017-03-05 16:00:32',1,3),(4110003,'4110-03','SCIENCE LABORATORY FEE',4,1,10,003,'CR',14,2,'2017-03-05 16:01:36',1,3),(4110004,'4110-04','MEDICAL/DENTAL FEE',4,1,10,004,'CR',14,2,'2017-03-05 16:02:28',1,3),(4110005,'4110-05','GUIDANCE FEE',4,1,10,005,'CR',14,2,'2017-03-05 16:03:12',1,3),(4110006,'4110-06','TLE FEE',4,1,10,006,'CR',14,2,'2017-03-05 16:04:01',1,3),(4111000,'4111-00','MISCELLANEOUS FEES',4,1,11,000,'CR',14,1,'2017-03-05 16:05:03',1,3),(4111001,'4111-01','ASSOCIATION/PTA',4,1,11,001,'CR',14,2,'2017-03-05 16:06:07',1,3),(4111002,'4111-02','STUDENTS PUBLICATION',4,1,11,002,'CR',14,2,'2017-03-05 16:06:51',1,3),(4111003,'4111-03','SECURITY GUARD FEE',4,1,11,003,'CR',14,2,'2017-03-05 16:07:40',1,3),(4111004,'4111-04','PROPERTY DEVELOPMENT FEE',4,1,11,004,'CR',14,2,'2017-03-05 16:08:23',1,3),(4111005,'4111-05','ENERGY FEE',4,1,11,005,'CR',14,2,'2017-03-05 16:09:17',1,3),(4111006,'4111-06','INSURANCE/STUDENT WELFARE FUND',4,1,11,006,'CR',14,2,'2017-03-05 16:10:29',1,3),(4111007,'4111-07','BUACS/CEAP DUE',4,1,11,007,'CR',14,2,'2017-03-05 16:11:36',1,3),(4111008,'4111-08','PE UNIFORM',4,1,11,008,'CR',14,2,'2017-03-05 16:12:23',1,3),(4112000,'4112-00','GRADUATING STUDENTS FEES',4,1,12,000,'CR',14,1,'2017-03-05 16:13:44',1,3),(4112001,'4112-01','GRADUATION FEE',4,1,12,001,'CR',14,2,'2017-03-05 16:14:22',1,3),(4112002,'4112-02','YEARBOOK FEE',4,1,12,002,'CR',14,2,'2017-03-05 16:15:21',1,3),(4112003,'4112-03','SOUVENIR FEE',4,1,12,003,'CR',14,2,'2017-03-05 16:16:06',1,3),(4112004,'4112-04','PICTURE FEE',4,1,12,004,'CR',14,2,'2017-03-05 16:16:50',1,3),(4113000,'4113','CERTIFICATION',4,1,13,000,'CR',14,1,'2017-03-05 16:22:36',1,3),(4114000,'4114','DONATIONS',4,1,14,000,'CR',14,1,'2017-03-05 16:23:23',1,3),(4115000,'4115','USE OF FACILITIES',4,1,15,000,'CR',14,1,'2017-03-05 16:24:23',1,3),(4116000,'4117-00','OTHER INCOME',4,1,16,000,'CR',16,1,'2017-03-05 16:26:38',1,3),(4116001,'4116-01','TEACHERS SALARY SUBSIDY (TSS)',4,1,16,001,'CR',16,2,'2017-03-05 16:34:54',1,3),(4116002,'4116-02','OLD ACCOUNTS',4,1,16,002,'CR',16,2,'2017-03-05 16:34:11',1,3),(4116003,'4116-O3','PHOTOCOPIES',4,1,16,003,'CR',16,2,'2017-03-05 16:38:36',1,3),(4116004,'4116-04','SALES/JUNK BOOKS',4,1,16,004,'CR',16,2,'2017-03-05 16:38:15',1,3),(4116005,'4116-05','STUDENT LEDGER/ TEST PERMIT',4,1,16,005,'CR',16,2,'2017-03-05 16:39:19',1,3),(4116006,'4116-06','BOOK RENTALS',4,1,16,006,'CR',16,2,'2017-03-05 16:40:14',1,3),(5101000,'5101','BASIC SALARY',5,1,01,000,'DR',17,1,'2017-03-05 16:42:54',1,3),(5102000,'5102','COST OF LIVING ALLOWANCE',5,1,02,000,'DR',17,1,'2017-03-05 16:43:51',1,3),(5103000,'5103','OVERLOAD',5,1,03,000,'DR',17,1,'2017-03-05 16:44:26',1,3),(5104000,'5104','POSITION PAY',5,1,04,000,'DR',17,1,'2017-03-05 16:45:08',1,3),(5105000,'5105','ADVISORY',5,1,05,000,'DR',17,1,'2017-03-05 16:45:44',1,3),(5106000,'5106','HONORARIUM/BONUSES/ALLOWANCES',5,1,06,000,'DR',17,1,'2017-03-05 16:46:25',1,3),(5107000,'5107','13TH MONTH PAY',5,1,07,000,'DR',17,1,'2017-03-05 16:47:13',1,3),(5108000,'5108','TFS/TSS',5,1,08,000,'DR',17,1,'2017-03-05 16:48:02',1,3),(5109000,'5109','SSS CONTRIBUTIONS',5,1,09,000,'DR',17,1,'2017-03-04 16:04:44',1,3),(5110000,'5110','PAG-IBIG CONTRIBUTIONS',5,1,10,000,'DR',17,1,'2017-03-05 16:49:15',1,3),(5111000,'5111','CEAP CONTRIBUTIONS',5,1,11,000,'DR',17,1,'2017-03-05 16:49:42',1,3),(5112000,'5112','PHILHEALTH CONTRIBUTIONS',5,1,12,000,'DR',17,1,'2017-03-05 16:50:13',1,3),(5113000,'5113','ADMINISTRATION/FACULTY/STAFF DEVELOPMENT',5,1,13,000,'DR',17,1,'2017-03-05 16:51:01',1,3),(5114000,'5114','PERSONNEL INCENTIVES EXPENSE',5,1,14,000,'DR',17,1,'2017-03-05 16:51:39',1,3),(5115000,'5115','RETIREMENT PAY',5,1,15,000,'DR',17,1,'2017-03-05 16:52:07',1,3),(5116000,'5116','SEPARATION PAY',5,1,16,000,'DR',17,1,'2017-03-05 16:52:39',1,3),(5117000,'5117','SUMMER/ASAP',5,1,17,000,'DR',17,1,'2017-03-05 16:53:14',1,3),(5118000,'5118','MODULAR CLASS EXPENSE',5,1,18,000,'DR',17,1,'2017-03-05 16:55:11',1,3),(5119000,'5119','MISSION SUPPORT',5,1,19,000,'DR',17,1,'2017-03-05 16:55:39',1,3),(5120000,'5120','CONGREGATIONAL SUPPORT',5,1,20,000,'DR',0,1,'2017-03-05 16:56:23',1,3),(5121000,'5121','STIPEND',5,1,21,000,'DR',17,1,'2017-03-05 16:56:51',1,3),(5122000,'5122','COMPUTER EXPENSES',5,1,22,000,'DR',17,1,'2017-03-05 16:57:24',1,3),(5123000,'5123','TYPING EXPENSES',5,1,23,000,'DR',17,1,'2017-03-05 16:58:30',1,3),(5124000,'5124-00','REGISTRATION RELATED EXPENSES',5,1,24,000,'DR',17,1,'2017-03-05 16:59:19',1,3),(5124001,'5124-01','REPORT CARD, ID, LIBRARY CARD EXPENSES',5,1,24,001,'DR',17,2,'2017-03-05 17:00:05',1,3),(5124002,'5124-02','STUDENT HANDBOOK EXPENSES',5,1,24,002,'DR',17,2,'2017-03-05 17:00:48',1,3),(5124003,'5124-03','STUDENT LEDGER/TEST PERMIT/ASSESSMENT CARD',5,1,24,003,'DR',17,2,'2017-03-05 17:01:46',1,3),(5125000,'5125-00','ACTIVITIES RELATED EXPENSES',5,1,25,000,'DR',17,1,'2017-03-05 17:02:30',1,3),(5125001,'5125-01','SCHOOL ACTIVITIES EXPENSE',5,1,25,001,'DR',17,2,'2017-03-05 17:03:08',1,3),(5125002,'5125-02','CAMPUS MINISTRY/RELIGIOUS ACTIVITIES EXPENSE',5,1,25,002,'DR',17,2,'2017-03-05 17:03:58',1,3),(5125003,'5125-03','MAPEH/CAT EXPENSES',5,1,25,003,'DR',17,2,'2017-03-05 17:04:32',1,3),(5125004,'5125-04','ATHLETICS/SPORTS ACTIVITIES EXPENSES',5,1,25,004,'DR',17,2,'2017-03-05 17:05:26',1,3),(5125005,'5125-05','TLE EXPENSES',5,1,25,005,'DR',17,2,'2017-03-05 17:05:59',1,3),(5125006,'5125-06','SCHOOL BAND EXPENSES',5,1,25,006,'DR',17,2,'2017-03-05 17:06:30',1,3),(5125007,'5125-07','COMMUNITY OUTREACH EXPENSES',5,1,25,007,'DR',17,2,'2017-03-05 17:07:09',1,3),(5125008,'5125-08','CULTURAL INTEGRATION EXPENSES',5,1,25,008,'DR',17,2,'2017-03-05 17:07:47',1,3),(5126000,'5126-00','STUDENT SERVICES RELATED EXPENSES',5,1,26,000,'DR',17,1,'2017-03-05 17:08:30',1,3),(5126001,'5126-01','TESTING MATERIALS/TEST PERMIT EXPENSES',5,1,26,001,'DR',17,2,'2017-03-05 17:09:34',1,3),(5126002,'5126-02','LIBRARY EXPENSES',5,1,26,002,'DR',17,2,'2017-03-05 17:10:07',1,3),(5130000,'5130','RESEARCH ACTIVITIES',5,1,30,000,'DR',17,1,'2017-03-05 17:32:32',1,3),(5126003,'5126-03','SCIENCE LABORATORY EXPENSES',5,1,26,003,'DR',17,2,'2017-03-05 17:10:45',1,3),(5126004,'5126-04','MEDICAL/DENTAL EXPENSES',5,1,26,004,'DR',17,2,'2017-03-05 17:11:32',1,3),(1103003,'1102003','CASH IN BANK - LBP CA#0962-1064-12',1,1,03,003,'DR',2,2,'2017-03-05 17:37:09',1,0),(5126005,'5126-05','GUIDANCE EXPENSES',5,1,26,005,'DR',17,2,'2017-03-05 17:12:12',1,3),(5131001,'5131-01','REPAIRS AND MAINTENANCE EXPENSES',5,1,31,001,'DR',17,2,'2017-03-05 17:36:43',1,3),(5126006,'5126-06','SPEECH LABORATORY EXPENSES',5,1,26,006,'DR',17,2,'2017-03-05 17:12:55',1,3),(5131002,'5131-02','AVR REPAIRS AND MAINTENANCE EXPENSES',5,1,31,002,'DR',17,2,'2017-03-05 17:37:38',1,3),(5126007,'5126-07','T.H.E LABORATORY EXPENSES',5,1,26,007,'DR',17,2,'2017-03-05 17:13:38',1,3),(5127000,'5127-00','MISCELLANEOUS EXPENSE',5,1,27,000,'DR',17,1,'2017-03-05 17:14:25',1,3),(5127001,'5127-01','ASSOCIATION/PTA EXPENSES',5,1,27,001,'DR',17,2,'2017-03-05 17:15:15',1,3),(1103002,'1102002','CASH IN BANK - LBP CA#2102-0089-04',1,1,03,002,'DR',2,2,'2019-12-23 11:34:23',1,NULL),(1103001,'1102001','CASH IN BANK - LBP SA#2101-0649-75',1,1,03,001,'DR',2,2,'2019-12-23 11:34:05',1,1),(1103000,'1102','CASH',1,1,03,000,'DR',2,1,'2017-03-05 17:34:21',1,0),(5127002,'5127-02','STUDENTS PUBLICATION EXPENSES',5,1,27,002,'DR',17,2,'2017-03-05 17:20:15',1,3),(5131000,'5131-00','SCHOOL OPERATIONAL EXPENSES',5,1,31,000,'DR',17,1,'2017-03-05 17:36:08',1,3),(5127003,'5127-03','SECURITY GUARD EXPENSES',5,1,27,003,'DR',17,2,'2017-03-05 17:20:54',1,3),(5127004,'5127-04','PROPERTY DEVELOPMENT EXPENSES',5,1,27,004,'DR',17,2,'2017-03-05 17:21:37',1,3),(5127005,'5127-05','PE UNIFORM EXPENSE',5,1,27,005,'DR',17,2,'2017-03-05 17:22:29',1,3),(5128000,'5128-00','GRADUATION STUDENTS/COMPLETERS EXPENSES',5,1,28,000,'DR',17,1,'2017-03-05 17:28:49',1,3),(5128001,'5128-01','GRADUATION (PROGRAM, DIPLOMA, ETC) EXPENSES',5,1,28,001,'DR',17,2,'2017-03-05 17:26:51',1,3),(5128002,'5128-02','YEARBOOK EXPENSES',5,1,28,002,'DR',17,2,'2017-03-05 17:28:25',1,3),(1102000,'1101','PETTY CASH FUND',1,1,02,000,'DR',2,1,'2017-03-05 17:31:09',1,0),(5128003,'5128-03','SOUVENIR EXPENSES',5,1,28,003,'DR',17,2,'2017-03-05 17:29:47',1,3),(5128004,'5128-04','PICTURE EXPENSES',5,1,28,004,'DR',17,2,'2017-03-05 17:30:26',1,3),(5128005,'5128-05','COMPLETERS EXPENSES',5,1,28,005,'DR',17,2,'2017-03-05 17:31:16',1,3),(5129000,'5129','SCHOLARSHIPS AND GRANTS EXPENSES',5,1,29,000,'DR',17,1,'2017-03-05 17:32:00',1,3),(1103004,'1102004','CASH IN BANK - STA. MONICA SA#2306',1,1,03,004,'DR',2,2,'2017-03-05 02:13:18',1,0),(1103005,'1102006','TIME DEPOSIT - STA. MONICA @ 9%',1,1,03,005,'DR',2,2,'2017-03-05 02:12:25',1,0),(1103006,'1102007','TIME DEPOSIT - STA. MONICA @ 8.5%',1,1,03,006,'DR',2,2,'2017-03-05 02:12:15',1,0),(5131003,'5131-03','XEROX/MIMEO/RISO REPAIRS AND MAINTENANCE EXPENSES',5,1,31,003,'DR',17,2,'2017-03-05 17:39:25',1,3),(1103007,'1102008','TIME DEPOSIT - STA. MONICA @ 8.25%',1,1,03,007,'DR',2,2,'2017-03-05 02:12:00',1,0),(5131004,'5131-04','INSURANCE EXPENSE',5,1,31,004,'DR',17,2,'2017-03-05 17:40:02',1,3),(1104000,'1103','TRADE AND OTHER RECEIVABLES',1,1,04,000,'DR',3,1,'2017-03-05 17:51:37',1,0),(5131005,'5131-05','VEHICLE REPAIR AND MAINTENANCE EXPENSE',5,1,31,005,'DR',17,2,'2017-03-05 17:40:59',1,3),(5131006,'5131-06','TAXES AND LICENSES AND PERMIT FEES',5,1,31,006,'DR',17,2,'2017-03-05 17:41:37',1,3),(5131007,'5131-07','ELECTRICITY',5,1,31,007,'DR',17,2,'2017-03-05 17:42:22',1,3),(5131008,'5131-08','WATER',5,1,31,008,'DR',17,2,'2017-03-05 17:43:04',1,3),(1104001,'1103001','RECEIVABLES FROM STUDENTS - GRADE 7',1,1,04,001,'DR',3,2,'2017-03-05 17:43:38',1,0),(5131009,'5131-09','TRANSPORTATION AND TRAVEL',5,1,31,009,'DR',17,2,'2017-03-05 17:43:46',1,3),(1104002,'1103002','RECEIVABLES FROM STUDENTS - GRADE 8',1,1,04,002,'DR',3,2,'2017-03-05 17:44:34',1,0),(5131010,'5131-10','POSTAGE, TELEPHONE, AND COMUNICATION',5,1,31,010,'DR',17,2,'2017-03-05 17:44:49',1,3),(1104003,'1103003','RECEIVABLES FROM STUDENTS - GRADE 9',1,1,04,003,'DR',3,2,'2017-03-05 17:45:12',1,0),(5131011,'5131-11','OFFICE SUPPLIES',5,1,31,011,'DR',17,2,'2017-03-05 17:45:26',1,3),(1104004,'1103004','RECEIVABLES FROM STUDENTS - GRADE 10',1,1,04,004,'DR',3,2,'2017-03-05 17:45:41',1,0),(1104005,'1103005','RECEIVABLES FROM STUDENTS - GRADE 11A',1,1,04,005,'DR',3,2,'2017-03-05 17:46:26',1,0),(5131012,'5131-12','CLASSROOM SUPPLIES',5,1,31,012,'DR',17,2,'2017-03-05 17:46:33',1,3),(1104006,'1103006','RECEIVABLES FROM STUDENTS - GRADE 11B',1,1,04,006,'DR',3,2,'2017-03-05 17:47:15',1,0),(5131013,'5131-13','JANITORIAL SUPPLIES/EXPENSES',5,1,31,013,'DR',17,2,'2017-03-05 17:47:17',1,3),(1104007,'1103007','RECEIVABLES FROM STUDENTS - GRADE 12A',1,1,04,007,'DR',3,2,'2017-03-05 17:47:49',1,0),(5131014,'5131-14','REPRESENTATION EXPENSE',5,1,31,014,'DR',17,2,'2017-03-05 17:47:54',1,3),(1104008,'1103008','RECEIVABLES FROM STUDENTS - GRADE 12B',1,1,04,008,'DR',3,2,'2017-03-05 17:48:18',1,0),(5131015,'5131-15','CHARITABLE EXPENSES',5,1,31,015,'DR',17,2,'2017-03-05 17:48:27',1,3),(5131016,'5131-16','FUEL AND OIL',5,1,31,016,'DR',17,2,'2017-03-05 17:49:05',1,3),(5132000,'5132-00','ADMINISTRATIVE EXPENSES',5,1,32,000,'DR',17,1,'2017-03-05 17:49:59',1,3),(1104009,'1103009','RECEIVABLES FROM STUDENTS - OLD ACCOUNTS',1,1,04,009,'DR',3,2,'2017-03-05 17:49:59',1,0),(5132001,'5132-01','BOT/CORPORATION MEETINGS',5,1,32,001,'DR',17,2,'2017-03-05 17:51:07',1,3),(1104010,'1103010','ADVANCES TO EMPLOYEES',1,1,04,010,'DR',3,2,'2017-03-05 17:51:18',1,0),(5132002,'5132-02','BUACS MEETINGS',5,1,32,002,'DR',17,2,'2017-03-05 17:51:58',1,3),(5132003,'5132-03','BUACS ADMINISTRATORS UPDATING',5,1,32,003,'DR',17,2,'2017-03-05 17:52:41',1,3),(1104011,'1103011','ALLOWANCE FOR DOUBTFUL ACCOUNTS',1,1,04,011,'DR',4,2,'2017-03-05 17:53:04',1,0),(5132004,'5132-04','PROFESSIONAL FEES',5,1,32,004,'DR',17,2,'2017-03-05 17:53:21',1,3),(5132005,'5132-05','DOUBTFUL ACCOUNTS',5,1,32,005,'DR',17,2,'2017-03-05 17:53:56',1,3),(5132006,'5132-06','DONATIONS EXPENSE',5,1,32,006,'DR',17,2,'2017-03-04 22:55:19',1,3),(5132007,'5132-07','DEPRECIATION',5,1,32,007,'DR',17,2,'2017-03-05 17:55:08',1,3),(1105000,'1104','INVENTORIES',1,1,05,000,'DR',5,1,'2017-03-05 17:55:30',1,0),(5132008,'5132-08','BANK SERVICE CHARGES',5,1,32,008,'DR',17,2,'2017-03-05 17:55:47',1,3),(5133000,'5133','FUND RAISING/SOLICITATION EXPENSE',5,1,33,000,'DR',17,1,'2017-03-05 17:56:25',1,3),(5134000,'5134-00','FACILITIES EXPENSE',5,1,34,000,'DR',17,1,'2017-03-05 17:57:16',1,3),(1106000,'1105000','OTHER CURRENT ASSETS',1,1,06,000,'DR',30,1,'2017-03-05 17:57:31',1,0),(5134001,'5134-01','CANTEEN EXPENSE',5,1,34,001,'DR',17,2,'2017-03-05 17:58:05',1,3),(1106001,'1105001','PREPAID INSURANCE',1,1,06,001,'DR',30,2,'2017-03-05 17:58:35',1,0),(1106002,'1105002','SUPPLIES',1,1,06,002,'DR',30,2,'2017-03-05 17:59:13',1,0),(5134002,'5134-02','SHOP EXPENSES',5,1,34,002,'DR',17,2,'2017-03-05 17:59:31',1,3),(5134003,'5134-03','FARM EXPENSES',5,1,34,003,'DR',17,2,'2017-03-05 18:00:11',1,3),(1201000,'1201000','LAND PROPERTY AND EQUIPMENT',1,2,01,000,'DR',9,1,'2017-03-05 18:00:19',1,0),(1201001,'1201001','LAND',1,2,01,001,'DR',9,2,'2017-03-05 02:06:42',1,0),(5135000,'5135-00','BUACS CONTRIBUTIONS',5,1,35,000,'DR',17,1,'2017-03-05 18:00:51',1,3),(1201002,'1201002','BUILDING AND IMPROVEMENTS',1,2,01,002,'DR',9,2,'2017-03-04 12:27:19',1,2),(5135001,'5135-01','SWF CONTRIBUTIONS',5,1,35,001,'DR',17,2,'2017-03-05 18:01:54',1,3),(5135002,'5135-02','BUACS QUIZ ON AIR CONTRIBUTIONS',5,1,35,002,'DR',17,2,'2017-03-05 18:11:41',1,3),(5135003,'5135-03','OTHER BUACS CONTRIBUTIONS',5,1,35,003,'DR',17,2,'2017-03-05 18:12:52',1,3),(5136000,'5136','OTHER EXPENSES',5,1,36,000,'DR',24,1,'2017-03-05 18:13:40',1,3),(1201003,'1201003','FACILITIES AND EQUIPMENT',1,2,01,003,'DR',9,2,'2017-03-04 12:29:54',1,2),(1201004,'1201004','TRANSPORTATION EQUIPMENT',1,2,01,004,'DR',9,2,'2017-03-04 12:31:24',1,2),(1201005,'1201005','LIBRARY BOOKS AND REFERENCE MATERIALS',1,2,01,005,'DR',9,2,'2017-03-04 12:33:03',1,2),(1201006,'1201006','FURNITURE AND FIXTURES',1,2,01,006,'DR',9,2,'2017-03-04 12:33:51',1,2),(1201007,'1201007','CONSTRUCTION IN PROGRESS',1,2,01,007,'DR',9,2,'2017-03-05 02:01:12',1,2),(2101000,'2101','TRADE AND OTHER PAYABLES',2,1,01,000,'CR',12,1,'2017-03-04 12:55:21',1,0),(2101001,'2101001','ACCOUNTS PAYABLE',2,1,01,001,'CR',12,2,'2017-03-04 12:56:37',1,0),(2101002,'2101002','ACCRUED EXPENSES',2,1,01,002,'CR',12,2,'2017-03-04 12:57:45',1,0),(2101003,'2101003','SSS, PHILHEALTH, PAG-IBIG PAYABLE',2,1,01,003,'CR',12,2,'2017-03-04 12:58:21',1,0),(2201000,'2201','RETIREMENT BENEFITS OBLIGATIONS',2,2,01,000,'CR',12,1,'2017-03-04 12:59:10',1,0),(3101000,'3101000','FUND BALANCE',3,1,01,000,'CR',25,1,'2017-03-04 13:01:46',1,0),(5109001,'5109001','SSS CONTRIBUTION - EER SHARE',5,1,09,001,'DR',17,2,'2017-03-04 16:06:28',1,0),(5109002,'5109002','SSS CONTRIBUTION - EE SHARE',5,1,09,002,'DR',17,2,'2017-03-04 16:12:14',1,0),(5112001,'5112001','PHIC - EER SHARE',5,1,12,001,'DR',17,2,'2017-03-04 16:12:33',1,0),(5112002,'5112002','PHIC - EE SHARE',5,1,12,002,'DR',17,2,'2017-03-04 16:12:57',1,0),(5110001,'5110001','PAG-IBIG CONTRIBUTION - EER SHARE',5,1,10,001,'DR',17,2,'2017-03-04 16:13:50',1,0),(5110002,'5110002','PAG-IBIG CONTRIBUTION - EE SHARE',5,1,10,002,'DR',17,2,'2017-03-04 16:18:30',1,0),(1201008,'1201008','INFORMATION AND COMMUNICATION EQUIPMENT',1,2,01,008,'DR',9,2,'2017-03-05 02:02:10',1,2),(1201009,'1201009','COMPUTER SOFTWARE',1,2,01,009,'DR',9,2,'2017-03-05 02:02:52',1,2),(1201010,'1201010','ACCUMULATED DEPRECIATION',1,2,01,010,'CR',10,2,'2017-03-05 02:04:42',1,2),(1103008,'1102009','CASH ON HAND',1,1,03,008,'DR',2,2,'2017-03-05 02:11:49',1,0),(1103009,'1102005','CASH IN BANK - LBP SA#2101-0905-50',1,1,03,009,'DR',2,2,'2017-03-05 02:14:24',1,0),(3101001,'3101001','BEGINNING BALANCE',3,1,01,001,'CR',28,2,'2017-03-05 02:26:29',1,0),(3101002,'3101002','DONATIONS',3,1,01,002,'CR',28,2,'2017-03-05 02:26:55',1,0),(2101004,'2101004','SSS PAYABLE',2,1,01,004,'CR',12,2,'2017-03-05 02:33:58',1,3),(2101005,'2101005','PHILHEALTH PAYABLE',2,1,01,005,'CR',12,2,'2017-03-05 03:06:14',1,2),(2101006,'2101006','PAGIBIG PAYABLE',2,1,01,006,'CR',12,2,'2017-03-05 03:06:52',1,3),(1301000,'1301000','ACCOUNTING SYSTEM SOFTWARE',1,3,01,000,'DR',30,1,'2017-06-21 11:37:32',1,2),(2202000,'2202000','Jays Liabilitiies(Long Term)',2,2,02,000,'CR',1,1,'2019-12-23 12:00:16',34,1),(2202001,'2202001','Jays Liabilities(Subsidiary-LT)',2,2,02,001,'CR',1,2,'2019-12-26 00:46:31',34,1);
/*!40000 ALTER TABLE `coa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coaaffiliate`
--

DROP TABLE IF EXISTS `coaaffiliate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `coaaffiliate` (
  `idCoaAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `idCoa` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCoaAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coaaffiliate`
--

LOCK TABLES `coaaffiliate` WRITE;
/*!40000 ALTER TABLE `coaaffiliate` DISABLE KEYS */;
INSERT INTO `coaaffiliate` VALUES (1,5136000,9),(2,5111000,25),(3,4101000,25),(4,4107000,25),(5,4109000,25),(6,4110000,25),(7,4111000,25),(8,4111002,25),(9,4111004,25),(10,4112002,25),(26,1103001,9),(27,1103001,12),(28,1103001,13),(29,1103002,9),(30,1103002,13),(31,1103002,12),(32,2202000,9),(33,2202000,12),(34,2202000,13),(35,2202001,9),(36,2202001,12),(37,2202001,13);
/*!40000 ALTER TABLE `coaaffiliate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contribution`
--

DROP TABLE IF EXISTS `contribution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `contribution` (
  `idcontribution` int(11) NOT NULL AUTO_INCREMENT,
  `contributionName` char(50) DEFAULT NULL,
  PRIMARY KEY (`idcontribution`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contribution`
--

LOCK TABLES `contribution` WRITE;
/*!40000 ALTER TABLE `contribution` DISABLE KEYS */;
INSERT INTO `contribution` VALUES (1,'sss'),(2,'pag ibig'),(4,'philhealth'),(6,'pagbubuntis');
/*!40000 ALTER TABLE `contribution` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `costaffiliate`
--

DROP TABLE IF EXISTS `costaffiliate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `costaffiliate` (
  `idCostAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `idCostCenter` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCostAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `costaffiliate`
--

LOCK TABLES `costaffiliate` WRITE;
/*!40000 ALTER TABLE `costaffiliate` DISABLE KEYS */;
INSERT INTO `costaffiliate` VALUES (1,10,3),(2,10,1),(3,11,8),(4,11,9),(5,12,9),(6,13,8),(7,13,10),(8,13,9),(9,14,11),(10,14,10),(11,15,8),(12,16,9),(13,16,8),(14,NULL,8),(15,NULL,9),(16,18,9),(17,NULL,9),(18,NULL,8),(19,NULL,8),(20,NULL,9),(21,NULL,8),(22,NULL,9),(23,22,8),(24,22,9),(25,24,11),(26,24,9),(27,24,10),(28,NULL,10),(29,NULL,8),(30,NULL,11),(31,25,11),(32,26,12),(33,26,9),(34,14,NULL),(35,26,NULL),(36,NULL,24),(37,NULL,17),(38,NULL,18),(39,NULL,18),(40,NULL,12),(41,NULL,24),(42,NULL,9),(43,NULL,18),(44,NULL,24),(45,NULL,17),(46,NULL,9),(47,NULL,12),(48,10,12),(49,10,9),(50,14,12),(51,14,9),(52,14,13),(53,14,12),(54,14,13),(55,18,9),(56,33,9),(57,34,9),(58,35,9),(59,36,9),(60,36,12),(61,36,13),(62,37,30),(63,37,31),(64,38,30),(65,38,31),(66,37,31),(67,37,30),(68,39,30),(69,39,31),(70,40,31),(71,41,30),(72,42,31),(73,43,25);
/*!40000 ALTER TABLE `costaffiliate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `costcenter`
--

DROP TABLE IF EXISTS `costcenter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `costcenter` (
  `idCostCenter` int(11) NOT NULL AUTO_INCREMENT,
  `costCenterName` text,
  `remarks` text,
  `status` int(1) DEFAULT '1' COMMENT '1 - Active\n2 - Inactive',
  PRIMARY KEY (`idCostCenter`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `costcenter`
--

LOCK TABLES `costcenter` WRITE;
/*!40000 ALTER TABLE `costcenter` DISABLE KEYS */;
INSERT INTO `costcenter` VALUES (2,'Cost 1',NULL,1),(3,'Cost 2','good cost',1),(4,'Cost 3','Sample remarks',1),(5,'Cost 4',NULL,1),(6,'Cost 5',NULL,1),(7,'Cost 6',NULL,1),(8,'Cost 7',NULL,1),(9,'Cost 8',NULL,1),(10,'Cost 91',NULL,1),(11,'Iron Bank Junior','sdfsdfds',1),(12,'Cost Center 2',NULL,1),(13,'Cost 10001','None',1),(14,'Cost 1090863','None',1),(15,'Cost Center 145165','None',1),(16,'Cost 1234567',NULL,1),(17,'Case 451985','Npne',1),(18,'Cost Mimu','Yuhhhhhh',1),(19,'Cost Center 100010000100011','None',1),(20,'Cost Center 00012',NULL,1),(21,'Cost Center Wazzup','None',1),(22,'Case Center','None',2),(23,'Case','152',1),(24,'Cost Center 101','None',1),(25,'Cost Center Waxx','None',2),(26,'Moonstone',NULL,1),(27,'The Eyes to Eyes',NULL,1),(28,'test123',NULL,1),(29,'wer4rt5ser',NULL,1),(30,'hjhgj',NULL,1),(31,'Cost 101',NULL,1),(32,'CC wo Affiliate',NULL,1),(33,'CC w Affiliate',NULL,1),(34,'Cost 0100',NULL,1),(35,'Cost 45732',NULL,1),(36,'test cost center',NULL,1),(37,'Cost Center',NULL,1),(38,'Disabled Cost Center',NULL,2),(39,'Cost Center 1',NULL,1),(40,'Cost Center for Required',NULL,1),(41,'Cost Center for Non',NULL,1),(42,'New Cost Center',NULL,1),(43,'Sample Main',NULL,1);
/*!40000 ALTER TABLE `costcenter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `customer` (
  `idCustomer` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(50) DEFAULT NULL,
  `email` char(50) DEFAULT NULL,
  `contactNumber` bigint(11) DEFAULT NULL,
  `address` varchar(45) DEFAULT NULL,
  `tin` bigint(11) DEFAULT NULL,
  `paymentMethod` int(1) DEFAULT NULL COMMENT '1 - Cash\n2 - Charge',
  `terms` int(1) DEFAULT NULL COMMENT '1 - 30 Days\n2 - 60 Days\n3 - 90 Dyas\n4 - 120 Days',
  `withCreditLimit` int(1) DEFAULT '0' COMMENT '"0 - False\n1 - True"',
  `creditLimit` decimal(18,2) DEFAULT '0.00',
  `withVAT` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `vatType` int(1) DEFAULT NULL COMMENT '1 - Inclusive\n2 - Exclusive',
  `vatPercent` decimal(18,2) DEFAULT '0.00',
  `penalty` decimal(18,2) DEFAULT '0.00',
  `discount` decimal(18,2) DEFAULT '0.00',
  `withHoldingTax` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `withHoldingTaxRate` decimal(18,2) DEFAULT '0.00',
  `salesGLAcc` int(11) DEFAULT NULL,
  `discountGLAcc` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCustomer`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES (37,'Hazel',NULL,9123456789,'El Salvador',4558761315,1,NULL,0,0.00,0,NULL,0.00,0.00,0.00,0,0.00,NULL,NULL),(38,'Catriona Gray','msuniverse@yopmail.com',9123456789,'Miss Universe',1111155555,2,4,1,1500.00,1,1,12.00,10.00,10.00,0,0.00,NULL,NULL),(40,'Oprah Horne','kucyc@dispostable.com',557,'Deserunt cupiditate',0,2,1,0,0.00,0,NULL,0.00,0.00,0.00,0,0.00,0,0);
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customeraffiliate`
--

DROP TABLE IF EXISTS `customeraffiliate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `customeraffiliate` (
  `idCustomerAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `idCustomer` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCustomerAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customeraffiliate`
--

LOCK TABLES `customeraffiliate` WRITE;
/*!40000 ALTER TABLE `customeraffiliate` DISABLE KEYS */;
INSERT INTO `customeraffiliate` VALUES (1,29,12),(2,29,13),(3,31,9),(4,32,9),(5,33,9),(6,33,12),(7,35,9),(8,35,9),(9,35,12),(10,34,9),(13,5,9),(14,36,9),(33,39,26),(34,39,27),(35,39,15),(36,39,14),(37,39,16),(38,39,28),(39,39,17),(40,39,18),(41,39,24),(42,39,13),(43,39,12),(44,39,25),(45,39,29),(46,39,9),(47,40,26),(48,40,27),(49,40,15),(61,38,9),(62,38,29),(67,37,30),(68,37,31),(69,37,29),(70,37,9),(71,37,25);
/*!40000 ALTER TABLE `customeraffiliate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customeritems`
--

DROP TABLE IF EXISTS `customeritems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `customeritems` (
  `idCustomerItems` int(11) NOT NULL AUTO_INCREMENT,
  `idCustomer` int(11) DEFAULT NULL,
  `idItem` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCustomerItems`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customeritems`
--

LOCK TABLES `customeritems` WRITE;
/*!40000 ALTER TABLE `customeritems` DISABLE KEYS */;
INSERT INTO `customeritems` VALUES (22,39,8),(23,39,9),(24,40,0),(37,38,8),(38,38,7),(41,37,7),(42,37,9);
/*!40000 ALTER TABLE `customeritems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defaultaccounts`
--

DROP TABLE IF EXISTS `defaultaccounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `defaultaccounts` (
  `idDefaultAcc` int(11) NOT NULL AUTO_INCREMENT,
  `idAffiliate` int(11) DEFAULT NULL,
  `accountingSched` int(1) DEFAULT NULL COMMENT '1 - Calendar\n2 - Fiscal',
  `month` int(2) DEFAULT NULL COMMENT '1 - January\n2 - February\n3 - March\n4 - April\n5 - May\n6 - June\n7 - July\n8 - August\n9 - September\n10-October\n11 - November\n12 - December',
  `type` int(1) DEFAULT NULL COMMENT '1 - Accounting\n2 - Inventory\n3 - Payroll',
  `debitRec` int(11) DEFAULT NULL,
  `creditPay` int(11) DEFAULT NULL,
  `accRec` int(11) DEFAULT NULL,
  `accPay` int(11) DEFAULT NULL,
  `debitMemo` int(11) DEFAULT NULL,
  `creditMemo` int(11) DEFAULT NULL,
  `inputTax` int(11) DEFAULT NULL,
  `outputTax` int(11) DEFAULT NULL,
  `salesAccount` int(11) DEFAULT NULL,
  `salesDiscount` int(11) DEFAULT NULL,
  `otherIncome` int(11) DEFAULT NULL,
  `retainedEarnings` int(11) DEFAULT NULL,
  `incomeTaxProvision` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDefaultAcc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defaultaccounts`
--

LOCK TABLES `defaultaccounts` WRITE;
/*!40000 ALTER TABLE `defaultaccounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `defaultaccounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defaultentry`
--

DROP TABLE IF EXISTS `defaultentry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `defaultentry` (
  `idDefaultEntry` int(11) NOT NULL AUTO_INCREMENT,
  `purpose` char(250) DEFAULT NULL,
  `idModule` int(11) DEFAULT NULL,
  `idReference` int(11) DEFAULT NULL,
  `remarks` text,
  PRIMARY KEY (`idDefaultEntry`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defaultentry`
--

LOCK TABLES `defaultentry` WRITE;
/*!40000 ALTER TABLE `defaultentry` DISABLE KEYS */;
/*!40000 ALTER TABLE `defaultentry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defaultentryaffiliate`
--

DROP TABLE IF EXISTS `defaultentryaffiliate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `defaultentryaffiliate` (
  `idDefaultAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `idDefaultEntry` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDefaultAffiliate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defaultentryaffiliate`
--

LOCK TABLES `defaultentryaffiliate` WRITE;
/*!40000 ALTER TABLE `defaultentryaffiliate` DISABLE KEYS */;
/*!40000 ALTER TABLE `defaultentryaffiliate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defaultentryposting`
--

DROP TABLE IF EXISTS `defaultentryposting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `defaultentryposting` (
  `idDefaultPosting` int(11) NOT NULL AUTO_INCREMENT,
  `idDefaultEntry` int(11) DEFAULT NULL,
  `idCoa` int(11) DEFAULT NULL,
  `debit` decimal(18,2) DEFAULT '0.00',
  `credit` decimal(18,2) DEFAULT '0.00',
  PRIMARY KEY (`idDefaultPosting`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defaultentryposting`
--

LOCK TABLES `defaultentryposting` WRITE;
/*!40000 ALTER TABLE `defaultentryposting` DISABLE KEYS */;
/*!40000 ALTER TABLE `defaultentryposting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disbursementhistory`
--

DROP TABLE IF EXISTS `disbursementhistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `disbursementhistory` (
  `idDisbursementHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idReference` int(11) DEFAULT NULL,
  `idReferenceSeries` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `idCostCenter` int(11) DEFAULT NULL,
  `idLocation` int(11) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `pType` int(1) DEFAULT NULL COMMENT '1 - Supplier\n2 - Customer',
  `pCode` int(11) DEFAULT NULL,
  `remarks` text,
  `fref` int(11) DEFAULT NULL,
  `frefNum` int(11) DEFAULT NULL,
  `paid` decimal(18,2) DEFAULT '0.00',
  `balance` decimal(18,2) DEFAULT '0.00',
  `hasJournal` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `notedBy` int(11) DEFAULT NULL,
  `doneBy` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '1' COMMENT '1 - Pending\n2 - Approved\n3 - Cancelled',
  PRIMARY KEY (`idDisbursementHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disbursementhistory`
--

LOCK TABLES `disbursementhistory` WRITE;
/*!40000 ALTER TABLE `disbursementhistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `disbursementhistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disbursements`
--

DROP TABLE IF EXISTS `disbursements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `disbursements` (
  `idDisbursement` int(11) NOT NULL AUTO_INCREMENT,
  `idReference` int(11) DEFAULT NULL,
  `idReferenceSeries` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `idCostCenter` int(11) DEFAULT NULL,
  `idLocation` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `pType` int(1) DEFAULT NULL COMMENT '1 - Supplier\n2 - Customer',
  `pCode` int(11) DEFAULT NULL,
  `remarks` text,
  `fref` int(11) DEFAULT NULL,
  `frefnum` int(11) DEFAULT NULL,
  `paid` decimal(18,2) DEFAULT '0.00',
  `balance` decimal(18,2) DEFAULT '0.00',
  `hasJournal` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `notedBy` int(11) DEFAULT NULL,
  `doneBy` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '1' COMMENT '1 - Pending\n2 - Approved\n3 - Cancelled',
  PRIMARY KEY (`idDisbursement`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disbursements`
--

LOCK TABLES `disbursements` WRITE;
/*!40000 ALTER TABLE `disbursements` DISABLE KEYS */;
/*!40000 ALTER TABLE `disbursements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empbenefits`
--

DROP TABLE IF EXISTS `empbenefits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `empbenefits` (
  `idEmpBenefits` int(11) NOT NULL AUTO_INCREMENT,
  `idEmployee` int(11) DEFAULT NULL,
  `description` char(50) DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT '0.00',
  `schedule` int(1) DEFAULT NULL COMMENT '1 - Daily\n2 - Monthly (1st Half)\n3 - Monthly (2nd Half)\n4 - Semi-Monthly',
  PRIMARY KEY (`idEmpBenefits`)
) ENGINE=InnoDB AUTO_INCREMENT=217 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empbenefits`
--

LOCK TABLES `empbenefits` WRITE;
/*!40000 ALTER TABLE `empbenefits` DISABLE KEYS */;
INSERT INTO `empbenefits` VALUES (140,68,'benefit 1',500.00,1),(183,66,'test b',500.00,1),(184,66,'test c',55000.00,3),(187,64,'test B',505.00,1),(188,64,'test C',55000.00,2),(197,63,'test 1',500.00,1),(198,63,'test 2',55000.00,2),(201,67,'benefits 1',500.00,1),(202,67,'benefits 2',55000.00,2),(209,59,'test',500.00,1),(210,59,'test 2',55000.00,2),(211,59,'test 3',5000.00,4),(212,97,'TEST',500.00,2),(215,65,'benefit 1',500.00,1),(216,65,'benefit 2',55000.00,3);
/*!40000 ALTER TABLE `empbenefits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empcontribution`
--

DROP TABLE IF EXISTS `empcontribution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `empcontribution` (
  `idEmpContribution` int(11) NOT NULL AUTO_INCREMENT,
  `idEmployee` int(11) DEFAULT NULL,
  `idcontribution` int(11) DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT NULL,
  `effectivityDate` date DEFAULT NULL,
  `idCoa` int(11) DEFAULT NULL,
  PRIMARY KEY (`idEmpContribution`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empcontribution`
--

LOCK TABLES `empcontribution` WRITE;
/*!40000 ALTER TABLE `empcontribution` DISABLE KEYS */;
INSERT INTO `empcontribution` VALUES (1,63,2,500.00,'2019-11-18',4101),(2,64,2,300.00,'2019-11-18',4103),(3,64,4,300.00,'2019-11-18',4102),(4,65,0,100.00,'2019-11-18',4101),(5,65,0,200.00,'2019-11-18',4102),(6,65,0,300.00,'2019-11-18',4103),(7,66,2,500.00,'2019-11-19',4101),(8,66,1,505.00,'2019-11-19',4102),(15,59,2,505.00,'2019-11-20',4101),(16,59,4,605.00,'2019-11-20',4102),(18,59,1,755.00,'2019-11-20',4103),(21,67,2,500.00,'2019-11-21',4101),(22,67,1,505.00,'2019-11-21',4102),(23,64,1,400.00,'2019-11-21',4101),(26,0,2,500.00,'2019-11-22',4101),(27,0,2,500.00,'2019-11-22',4101),(28,0,1,1000.00,'2019-11-12',4102),(29,0,2,100.00,'2019-12-17',4103);
/*!40000 ALTER TABLE `empcontribution` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empcontributionhistory`
--

DROP TABLE IF EXISTS `empcontributionhistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `empcontributionhistory` (
  `idEmpContributionHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idEmployee` int(11) DEFAULT NULL,
  `idcontribution` int(1) DEFAULT NULL COMMENT '1 - SSS\\n2 - Philhealth\\n3 - Pag-ibig Fund\\n4 - Withholding Tax',
  `amount` decimal(18,2) DEFAULT NULL,
  `effectivityDate` date DEFAULT NULL,
  PRIMARY KEY (`idEmpContributionHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empcontributionhistory`
--

LOCK TABLES `empcontributionhistory` WRITE;
/*!40000 ALTER TABLE `empcontributionhistory` DISABLE KEYS */;
INSERT INTO `empcontributionhistory` VALUES (1,64,2,200.00,'2019-11-18'),(2,64,4,300.00,'2019-11-18'),(3,64,1,400.00,'2019-11-21'),(4,64,2,300.00,'2019-11-18'),(5,59,1,755.00,'2019-11-20'),(6,68,2,500.00,'2019-11-22'),(7,68,2,500.00,'2019-11-22'),(8,97,1,1000.00,'2019-11-12'),(9,100,2,100.00,'2019-12-17');
/*!40000 ALTER TABLE `empcontributionhistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `employee` (
  `idEmployee` int(11) NOT NULL AUTO_INCREMENT,
  `idNumber` int(11) DEFAULT NULL,
  `name` char(50) DEFAULT NULL,
  `address` text,
  `contactNumber` bigint(8) DEFAULT NULL,
  `email` char(50) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `status` int(1) DEFAULT '1' COMMENT '1 - Active\n2 - Inactive',
  `idAffiliate` int(11) DEFAULT NULL,
  `idLocation` int(11) DEFAULT NULL,
  `user` int(1) DEFAULT NULL COMMENT '1 - True\n2 - False',
  PRIMARY KEY (`idEmployee`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee`
--

LOCK TABLES `employee` WRITE;
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
INSERT INTO `employee` VALUES (1,1,'Jon Snow','Cagayan de Oro City, Misamis Oriental',912345678,'jonknowsnothing@yopmail.com','1981-08-05',1,1,1,1),(59,555,'mark','mark',1231,'mark@gmail.com','1981-07-05',0,NULL,NULL,1),(63,777,'marco','marco',2323,'777@gmai.com','2019-11-18',0,NULL,NULL,1),(64,999,'Sample User','Test Address',123,'nine@gmail.com','1985-11-04',0,NULL,NULL,0),(65,888,'test user','user',123,'user@yahoomail.com','1995-11-05',0,NULL,NULL,1),(66,333,'Aubrey','test Address',1231231,'aubrey@gmail.com','2005-08-01',1,NULL,NULL,1),(67,222,'tuna tuna','test address',123,'tuna@gmail.com','1995-11-01',0,NULL,NULL,1),(68,111,'one','one address',111,'one@gmail.com','1985-11-04',0,NULL,NULL,1),(90,123456,'rgyrt','fghg',35454,'sfdgfd@sfg.gfj','2019-11-27',1,NULL,NULL,1),(91,123,'dsfgvdf','gdf',34535,'gdf@dhg.ujkl','2019-11-27',1,NULL,NULL,1),(92,213345,'tgdg','dfgdfg',2147483647,'dfgfdg@dfhfg.bfgt','1995-11-06',1,NULL,NULL,1),(93,12345,'sdgdf','gdfg',23534,'sdfsd@jdfhksjd.com','2019-11-27',1,NULL,NULL,1),(95,554455,'test marco','fdsfs',3434,'123@yahoo.com','2019-11-27',0,NULL,NULL,0),(96,443344,'test','erere',3434,'2323@gmail.com','2019-11-27',0,NULL,NULL,0),(97,2019,'Sam Paul','CDO',2147483647,'sampaul@yopmail.com','1986-11-03',0,NULL,NULL,0),(98,88888,'kulot','kulot',12345,'kulot@gmail.com','1998-09-06',0,NULL,NULL,1),(99,124,'Marie Danilene','Cagayan de Oro',122,'dan@dispostable.com','1996-01-21',0,NULL,NULL,1),(100,123456789,'Dulcy','CDO',139829485,'dulcy@yopmail.com','1997-12-05',0,NULL,NULL,1),(101,2147483647,'Hazel','El Salvador',945454545,'hazel@yopmail.com','1998-11-06',0,NULL,NULL,1),(102,11111,'System Administrator','address',11111,'11111@gmail.com','2019-12-06',0,NULL,NULL,1),(104,819,'Timon Cantu','Culpa explicabo Acc',875,'mywo@dispostable.com','1975-01-08',0,NULL,NULL,1);
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employeeaffiliate`
--

DROP TABLE IF EXISTS `employeeaffiliate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `employeeaffiliate` (
  `idEmpAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `idEmployee` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `selected` int(1) DEFAULT NULL,
  PRIMARY KEY (`idEmpAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=541 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employeeaffiliate`
--

LOCK TABLES `employeeaffiliate` WRITE;
/*!40000 ALTER TABLE `employeeaffiliate` DISABLE KEYS */;
INSERT INTO `employeeaffiliate` VALUES (96,68,27,1),(97,68,15,1),(143,90,26,1),(144,90,27,1),(145,90,15,1),(146,90,14,1),(147,90,16,1),(148,90,17,1),(149,90,18,1),(150,90,24,1),(151,90,13,1),(152,90,12,1),(153,90,25,1),(154,91,26,1),(155,91,27,1),(156,91,15,1),(157,91,14,1),(158,91,16,1),(159,91,17,1),(160,91,18,1),(161,91,24,1),(162,91,13,1),(163,91,12,1),(164,91,25,1),(165,92,26,1),(166,92,27,1),(167,93,26,1),(168,93,27,1),(211,96,15,1),(216,95,26,1),(218,66,27,1),(220,64,9,1),(262,1,9,1),(263,1,12,1),(264,1,13,1),(265,1,14,1),(266,1,15,1),(273,63,12,1),(274,63,9,1),(290,100,26,1),(291,100,27,1),(292,100,15,1),(293,100,14,1),(294,100,16,1),(295,100,17,1),(296,100,18,1),(297,100,24,1),(298,100,13,1),(299,100,12,1),(300,100,25,1),(301,100,9,1),(302,101,25,1),(304,67,14,1),(305,102,26,1),(306,102,27,1),(307,102,15,1),(308,102,14,1),(309,102,16,1),(310,102,17,1),(311,102,18,1),(312,102,24,1),(313,102,13,1),(314,102,12,1),(315,102,25,1),(316,102,9,1),(407,104,30,1),(408,104,31,1),(461,98,12,1),(462,98,9,1),(463,98,13,1),(464,98,26,1),(465,98,29,1),(466,59,9,1),(467,59,12,1),(468,59,13,1),(469,59,14,1),(470,59,15,1),(471,59,25,1),(472,59,16,1),(473,59,17,1),(474,59,18,1),(475,59,24,1),(476,59,26,1),(477,59,27,1),(478,59,28,1),(479,59,29,1),(480,59,30,1),(481,59,31,1),(482,59,32,1),(483,59,33,1),(484,97,26,1),(485,97,27,1),(486,97,15,1),(487,97,14,1),(488,97,9,1),(489,97,12,1),(490,97,13,1),(491,97,16,1),(492,97,17,1),(493,97,18,1),(494,97,24,1),(495,97,25,1),(496,97,28,1),(497,97,29,1),(498,97,30,1),(499,97,31,1),(500,97,32,1),(501,97,33,1),(520,65,9,1),(521,65,12,1),(522,65,13,1),(523,65,14,1),(524,65,15,1),(525,65,16,1),(526,65,17,1),(527,65,18,1),(528,65,24,1),(529,65,25,1),(530,65,26,1),(531,65,27,1),(532,65,28,1),(533,65,29,1),(534,65,30,1),(535,65,31,1),(536,65,32,1),(537,65,33,1),(538,99,30,1),(539,99,31,1),(540,99,25,1);
/*!40000 ALTER TABLE `employeeaffiliate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employeeclass`
--

DROP TABLE IF EXISTS `employeeclass`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `employeeclass` (
  `idEmpClass` int(11) NOT NULL AUTO_INCREMENT,
  `empClassName` char(20) DEFAULT NULL,
  PRIMARY KEY (`idEmpClass`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employeeclass`
--

LOCK TABLES `employeeclass` WRITE;
/*!40000 ALTER TABLE `employeeclass` DISABLE KEYS */;
INSERT INTO `employeeclass` VALUES (25,'Probationary'),(26,'Executive Staff'),(27,'Senior Staff'),(28,'Staff'),(29,'sdasdd'),(30,'test');
/*!40000 ALTER TABLE `employeeclass` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employeehistory`
--

DROP TABLE IF EXISTS `employeehistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `employeehistory` (
  `idEmpHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idEmployment` int(11) DEFAULT NULL,
  `idEmployee` int(11) DEFAULT NULL,
  `dateEmployed` date DEFAULT NULL,
  `dateEffective` date DEFAULT NULL,
  `endOfContract` date DEFAULT NULL,
  `classification` int(11) DEFAULT NULL,
  `monthRate` decimal(18,2) DEFAULT '0.00',
  PRIMARY KEY (`idEmpHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employeehistory`
--

LOCK TABLES `employeehistory` WRITE;
/*!40000 ALTER TABLE `employeehistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `employeehistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employment`
--

DROP TABLE IF EXISTS `employment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `employment` (
  `idEmployment` int(11) NOT NULL AUTO_INCREMENT,
  `idEmployee` int(11) DEFAULT NULL,
  `dateEmployed` date DEFAULT NULL,
  `dateEffective` date DEFAULT NULL,
  `endOfContract` date DEFAULT NULL,
  `classification` int(11) DEFAULT NULL,
  `monthRate` decimal(18,2) DEFAULT '0.00',
  PRIMARY KEY (`idEmployment`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employment`
--

LOCK TABLES `employment` WRITE;
/*!40000 ALTER TABLE `employment` DISABLE KEYS */;
INSERT INTO `employment` VALUES (1,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(5,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(6,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(7,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(8,66,'2019-11-06','2019-11-12','2019-11-19',27,55000.00),(9,67,'2019-11-01','2019-11-07','2019-11-21',26,25000.00),(10,68,'2019-11-01','2019-11-13','2019-11-22',25,12000.00),(32,90,'2019-11-27','2019-11-27','2019-11-27',27,4521.00),(33,91,'2019-11-27','2019-11-27','2019-11-27',25,1000.00),(34,92,'2019-11-27','2019-11-27','2031-11-27',26,1500.00),(35,93,'2019-11-27','2019-11-27','2024-11-22',26,12234.00),(36,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(37,96,'2019-11-27','2019-11-27','2019-11-27',26,1.00),(38,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(39,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(40,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(41,100,'2019-12-03','2019-12-03','2019-12-03',25,1500.00),(42,101,'2019-12-05','2019-12-05','2019-12-05',26,1500.00),(43,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(45,104,'2019-12-17','2019-12-23','2020-02-21',26,12.00);
/*!40000 ALTER TABLE `employment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employmenthistorydate`
--

DROP TABLE IF EXISTS `employmenthistorydate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `employmenthistorydate` (
  `idEmpHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idEmployee` int(11) DEFAULT NULL,
  `dateEmployed` date DEFAULT NULL,
  `dateEffective` date DEFAULT NULL,
  `endOfContract` date DEFAULT NULL,
  `classification` int(11) DEFAULT NULL,
  `monthRate` decimal(18,2) DEFAULT '0.00',
  PRIMARY KEY (`idEmpHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employmenthistorydate`
--

LOCK TABLES `employmenthistorydate` WRITE;
/*!40000 ALTER TABLE `employmenthistorydate` DISABLE KEYS */;
INSERT INTO `employmenthistorydate` VALUES (1,59,'2019-11-01','2019-11-06','2019-11-15',26,5500.00),(2,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(3,59,'2019-11-01','2019-11-07','2019-11-16',25,5550.00),(4,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(5,67,'2019-11-01','2019-11-07','2019-11-21',26,25000.00),(10,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(11,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(15,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(16,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(17,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(18,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(19,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(20,68,'2019-11-01','2019-11-13','2019-11-22',25,12000.00),(21,68,'2019-11-01','2019-11-13','2019-11-22',25,12000.00),(28,68,'2019-11-01','2019-11-13','2019-11-22',25,12000.00),(42,90,'2019-11-27','2019-11-27','2019-11-27',27,4521.00),(43,91,'2019-11-27','2019-11-27','2019-11-27',25,1000.00),(44,92,'2019-11-27','2019-11-27','2031-11-27',26,1500.00),(45,93,'2019-11-27','2019-11-27','2024-11-22',26,12234.00),(53,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(54,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(55,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(56,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(57,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(58,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(59,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(60,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(61,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(62,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(63,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(64,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(65,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(66,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(67,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(68,96,'2019-11-27','2019-11-27','2019-11-27',26,1.00),(69,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(70,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(71,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(83,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(84,96,'2019-11-27','2019-11-27','2019-11-27',26,1.00),(85,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(86,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(87,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(88,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(89,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(90,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(91,66,'2019-11-06','2019-11-12','2019-11-19',27,55000.00),(92,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(93,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(100,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(101,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(102,1,'2019-12-02','2019-12-02','2019-12-02',26,1500.00),(103,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(104,1,'2019-12-02','2019-12-02','2019-12-02',27,5000.00),(105,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(106,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(107,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(108,1,'2019-12-02','2019-12-02','2019-12-02',26,1500.00),(109,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(110,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(111,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(112,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(113,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(114,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(115,99,'2019-12-03','2019-12-02','2020-08-21',26,1000.00),(116,100,'2019-12-03','2019-12-03','2019-12-03',25,1500.00),(117,101,'2019-12-05','2019-12-05','2019-12-05',26,1500.00),(118,67,'2019-11-01','2019-11-07','2019-11-21',26,25000.00),(119,67,'2019-11-01','2019-11-07','2019-11-21',26,25000.00),(120,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(121,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(122,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(123,103,'2019-01-01','2019-08-20','2020-09-30',28,5.00),(124,99,'2019-12-03','2019-12-02','2020-08-21',26,1000.00),(125,99,'2019-12-03','2019-12-02','2020-08-21',26,1000.00),(126,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(127,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(128,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(129,104,'2019-12-17','2019-12-23','2020-02-21',26,12.00),(130,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(131,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(132,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(133,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(134,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(135,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(136,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(137,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(138,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(139,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(140,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(141,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00);
/*!40000 ALTER TABLE `employmenthistorydate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employmenthistoryposition`
--

DROP TABLE IF EXISTS `employmenthistoryposition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `employmenthistoryposition` (
  `idEmpHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idEmployee` int(11) DEFAULT NULL,
  `dateEmployed` date DEFAULT NULL,
  `dateEffective` date DEFAULT NULL,
  `endOfContract` date DEFAULT NULL,
  `classification` int(11) DEFAULT NULL,
  `monthRate` decimal(18,2) DEFAULT '0.00',
  PRIMARY KEY (`idEmpHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employmenthistoryposition`
--

LOCK TABLES `employmenthistoryposition` WRITE;
/*!40000 ALTER TABLE `employmenthistoryposition` DISABLE KEYS */;
INSERT INTO `employmenthistoryposition` VALUES (1,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(2,67,'2019-11-01','2019-11-07','2019-11-21',26,25000.00),(7,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(8,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(12,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(13,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(14,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(15,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(16,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(17,68,'2019-11-01','2019-11-13','2019-11-22',25,12000.00),(18,68,'2019-11-01','2019-11-13','2019-11-22',25,12000.00),(25,68,'2019-11-01','2019-11-13','2019-11-22',25,12000.00),(39,90,'2019-11-27','2019-11-27','2019-11-27',27,4521.00),(40,91,'2019-11-27','2019-11-27','2019-11-27',25,1000.00),(41,92,'2019-11-27','2019-11-27','2031-11-27',26,1500.00),(42,93,'2019-11-27','2019-11-27','2024-11-22',26,12234.00),(50,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(51,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(52,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(53,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(54,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(55,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(56,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(57,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(58,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(59,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(60,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(61,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(62,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(63,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(64,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(65,96,'2019-11-27','2019-11-27','2019-11-27',26,1.00),(66,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(67,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(68,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(80,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(81,96,'2019-11-27','2019-11-27','2019-11-27',26,1.00),(82,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(83,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(84,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(85,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(86,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(87,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(88,66,'2019-11-06','2019-11-12','2019-11-19',27,55000.00),(89,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(90,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(97,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(98,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(99,1,'2019-12-02','2019-12-02','2019-12-02',26,1500.00),(100,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(101,1,'2019-12-02','2019-12-02','2019-12-02',27,5000.00),(102,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(103,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(104,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(105,1,'2019-12-02','2019-12-02','2019-12-02',26,1500.00),(106,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(107,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(108,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(109,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(110,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(111,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(112,99,'2019-12-03','2019-12-02','2020-08-21',26,1000.00),(113,100,'2019-12-03','2019-12-03','2019-12-03',25,1500.00),(114,101,'2019-12-05','2019-12-05','2019-12-05',26,1500.00),(115,67,'2019-11-01','2019-11-07','2019-11-21',26,25000.00),(116,67,'2019-11-01','2019-11-07','2019-11-21',26,25000.00),(117,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(118,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(119,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(120,103,'2019-01-01','2019-08-20','2020-09-30',28,5.00),(121,99,'2019-12-03','2019-12-02','2020-08-21',26,1000.00),(122,99,'2019-12-03','2019-12-02','2020-08-21',26,1000.00),(123,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(124,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(125,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(126,104,'2019-12-17','2019-12-23','2020-02-21',26,12.00),(127,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(128,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(129,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(130,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(131,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(132,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(133,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(134,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(135,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(136,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(137,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(138,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00);
/*!40000 ALTER TABLE `employmenthistoryposition` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eu`
--

DROP TABLE IF EXISTS `eu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `eu` (
  `idEu` int(11) NOT NULL AUTO_INCREMENT,
  `idEmployee` int(11) DEFAULT NULL,
  `username` char(150) DEFAULT NULL,
  `userType` int(1) DEFAULT NULL COMMENT '1 - Administrator\n2 - Supervisor\n3 - User',
  `password` char(100) DEFAULT NULL,
  PRIMARY KEY (`idEu`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eu`
--

LOCK TABLES `eu` WRITE;
/*!40000 ALTER TABLE `eu` DISABLE KEYS */;
INSERT INTO `eu` VALUES (1,59,'mark',1,'ea82410c7a9991816b5eeeebe195e20a'),(8,66,'aubie',2,'d41d8cd98f00b204e9800998ecf8427e'),(9,67,'tuna',2,'2bf93a8a979420ff77b32fab0751cad2'),(10,68,'one',1,'098f6bcd4621d373cade4e832627b4f6'),(21,90,'test123',1,'123456'),(22,91,'test123',1,'123456'),(23,92,'qwerty',1,'123456'),(24,93,'qwerty',1,'123456'),(26,65,'888',1,'098f6bcd4621d373cade4e832627b4f6'),(28,1,'jonsnow',2,'d41d8cd98f00b204e9800998ecf8427e'),(29,63,'marco',1,'f5888d0bb58d611107e11f7cbc41c97a'),(30,98,'kulot',1,'45d1e4e173173efabc43111920a21fd2'),(31,99,'dan',1,'0f281d173f0fdfdccccd7e5b8edc21f1'),(32,100,'dulcy',1,'e10adc3949ba59abbe56e057f20f883e'),(33,101,'hazel',1,'16b9652df79d0e4784bdbf478c9f4fee'),(34,102,'sysadmin',1,'21232f297a57a5a743894a0e4a801fc3'),(36,104,'marie',1,'108f280224d356e3a2537b56152e0b13');
/*!40000 ALTER TABLE `eu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gl`
--

DROP TABLE IF EXISTS `gl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `gl` (
  `idGl` int(11) NOT NULL AUTO_INCREMENT,
  `idCoa` int(11) DEFAULT NULL,
  `idInvoice` int(11) DEFAULT NULL,
  `glYear` int(4) DEFAULT NULL,
  `glAmount` decimal(18,2) DEFAULT '0.00',
  `idAffiliate` int(11) DEFAULT NULL,
  `month` int(2) DEFAULT NULL COMMENT '1 - January\n2 - February\n3 - March\n4 - April\n5 - May\n6 - June\n7 - July\n8 - August\n9 - September\n10 - October\n11 - November\n12 - December',
  PRIMARY KEY (`idGl`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gl`
--

LOCK TABLES `gl` WRITE;
/*!40000 ALTER TABLE `gl` DISABLE KEYS */;
/*!40000 ALTER TABLE `gl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `idcontribution`
--

DROP TABLE IF EXISTS `idcontribution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `idcontribution` (
  `idEmpContribution` int(11) NOT NULL AUTO_INCREMENT,
  `idEmployee` int(11) DEFAULT NULL,
  `contribution` int(3) DEFAULT NULL COMMENT '1 - SSS\\n2 - Philhealth\\n3 - Pag-ibig Fund\\n4 - Withholding Tax',
  `amount` decimal(18,2) DEFAULT '0.00',
  `effectivityDate` date DEFAULT NULL,
  `idCoa` int(11) DEFAULT NULL COMMENT 'idCoa',
  PRIMARY KEY (`idEmpContribution`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `idcontribution`
--

LOCK TABLES `idcontribution` WRITE;
/*!40000 ALTER TABLE `idcontribution` DISABLE KEYS */;
INSERT INTO `idcontribution` VALUES (1,59,0,500.00,'2019-11-15',4101),(2,59,0,505.00,'2019-11-15',4102);
/*!40000 ALTER TABLE `idcontribution` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invadjustment`
--

DROP TABLE IF EXISTS `invadjustment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `invadjustment` (
  `idInvAdjustment` int(11) NOT NULL AUTO_INCREMENT,
  `idAffiliate` int(11) DEFAULT NULL,
  `idLocation` int(11) DEFAULT NULL,
  `idReference` int(11) DEFAULT NULL,
  `idRefernceSeries` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `remarks` text,
  `idItem` int(11) DEFAULT NULL,
  `idItemClass` int(11) DEFAULT NULL,
  `qtyBal` int(11) DEFAULT NULL,
  `qtyActual` int(11) DEFAULT NULL,
  `cost` decimal(18,2) DEFAULT NULL,
  `short` int(11) DEFAULT NULL,
  `over` int(11) DEFAULT NULL,
  `notedBy` int(11) DEFAULT NULL,
  `doneBy` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL COMMENT '1 - Pending\n2 - Approved\n3 - Cancelled',
  PRIMARY KEY (`idInvAdjustment`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invadjustment`
--

LOCK TABLES `invadjustment` WRITE;
/*!40000 ALTER TABLE `invadjustment` DISABLE KEYS */;
/*!40000 ALTER TABLE `invadjustment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invadjustmenthistory`
--

DROP TABLE IF EXISTS `invadjustmenthistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `invadjustmenthistory` (
  `idInvAdjustmentHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idInvAdjustment` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `idLocation` int(11) DEFAULT NULL,
  `idReference` int(11) DEFAULT NULL,
  `idRefernceSeries` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `remarks` text,
  `idItem` int(11) DEFAULT NULL,
  `idItemClass` int(11) DEFAULT NULL,
  `qtyBal` int(11) DEFAULT NULL,
  `qtyActual` int(11) DEFAULT NULL,
  `cost` decimal(18,2) DEFAULT '0.00',
  `short` int(11) DEFAULT NULL,
  `over` int(11) DEFAULT NULL,
  `notedBy` int(11) DEFAULT NULL,
  `doneBy` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '1' COMMENT '1 - Pending\n2 - Approved\n3 - Cancelled',
  PRIMARY KEY (`idInvAdjustmentHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invadjustmenthistory`
--

LOCK TABLES `invadjustmenthistory` WRITE;
/*!40000 ALTER TABLE `invadjustmenthistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `invadjustmenthistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `invoices` (
  `idInvoice` int(11) NOT NULL AUTO_INCREMENT,
  `idAffiliate` int(11) DEFAULT NULL,
  `idReference` int(11) DEFAULT NULL,
  `referenceNum` int(255) DEFAULT NULL,
  `idModule` int(11) DEFAULT NULL,
  `idCostCenter` int(11) DEFAULT NULL,
  `idLocation` int(11) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `time` time DEFAULT NULL,
  `pType` int(1) DEFAULT NULL COMMENT '1 - Customer\n2 - Supplier\n3 - Location',
  `pCode` int(11) DEFAULT NULL,
  `payMode` int(1) DEFAULT NULL COMMENT '1 - Cash\n2 - Charge',
  `amount` decimal(18,2) DEFAULT '0.00',
  `bal` decimal(18,2) DEFAULT '0.00',
  `balLeft` decimal(18,2) DEFAULT '0.00',
  `downPayment` decimal(18,2) DEFAULT '0.00',
  `discount` decimal(18,2) DEFAULT '0.00',
  `discountRate` decimal(18,2) DEFAULT '0.00',
  `deliveryReceiptTag` varchar(255) DEFAULT NULL,
  `deliveryReceipt` int(1) DEFAULT NULL,
  `cancelTag` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `remarks` text,
  `dueDate` date DEFAULT NULL,
  `terms` int(1) DEFAULT NULL COMMENT '1 - 30 Days\n2 - 60 Days\n3 - 90 Days\n4 - 120 Days',
  `vatType` int(1) DEFAULT NULL COMMENT '1 - Inclusive\n2 - Exclusive',
  `rbyCode` int(11) DEFAULT NULL,
  `checkDm` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `dateModified` datetime DEFAULT NULL,
  `transferredBy` int(11) DEFAULT NULL,
  `hasJournal` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `fident` int(11) DEFAULT NULL,
  `preparedBy` int(11) DEFAULT NULL,
  `transactionDate` timestamp NULL DEFAULT NULL,
  `pickupDate` timestamp NULL DEFAULT NULL,
  `notedby` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `idReferenceSeries` int(11) DEFAULT NULL,
  `orderedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`idInvoice`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoiceshistory`
--

DROP TABLE IF EXISTS `invoiceshistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `invoiceshistory` (
  `idInvoiceHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idInvoice` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `idReference` int(11) DEFAULT NULL,
  `idReferenceSeries` int(11) DEFAULT NULL,
  `idModule` int(11) DEFAULT NULL,
  `idCostCenter` int(11) DEFAULT NULL,
  `idLocation` int(11) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `pType` int(1) DEFAULT NULL COMMENT '1 - Customer\n2 - Supplier\n3 - Location',
  `pCode` int(11) DEFAULT NULL,
  `payMode` int(1) DEFAULT NULL COMMENT '1 - Cash\n2 - Charge',
  `amount` decimal(18,2) DEFAULT '0.00',
  `bal` decimal(18,2) DEFAULT '0.00',
  `balLeft` decimal(18,2) DEFAULT '0.00',
  `downPayment` decimal(18,2) DEFAULT '0.00',
  `discount` decimal(18,2) DEFAULT '0.00',
  `discountRate` decimal(18,2) DEFAULT '0.00',
  `cancelTag` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `remarks` text,
  `dueDate` date DEFAULT NULL,
  `terms` int(1) DEFAULT NULL COMMENT '1 - 30 Days\n2 - 60 Days\n3 - 90 Days\n4 - 120 Days',
  `vatType` int(1) DEFAULT NULL COMMENT '1 - Inclusive\n2 - Exclusive',
  `rbyCode` int(11) DEFAULT NULL,
  `checkDm` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `dateModified` datetime DEFAULT NULL,
  `transferredBy` int(11) DEFAULT NULL,
  `hasJournal` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `fident` int(11) DEFAULT NULL,
  PRIMARY KEY (`idInvoiceHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoiceshistory`
--

LOCK TABLES `invoiceshistory` WRITE;
/*!40000 ALTER TABLE `invoiceshistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoiceshistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `item` (
  `idItem` int(11) NOT NULL AUTO_INCREMENT,
  `barcode` char(20) DEFAULT NULL,
  `itemName` char(50) DEFAULT NULL,
  `idItemClass` int(11) DEFAULT NULL,
  `idUnit` int(11) DEFAULT NULL,
  `itemPrice` decimal(18,2) DEFAULT '0.00',
  `reorderLevel` int(11) DEFAULT NULL,
  `dateModified` datetime DEFAULT NULL,
  `releaseWithoutQty` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `salesGlAcc` int(11) DEFAULT NULL,
  `inventoryGlAcc` int(11) DEFAULT NULL,
  `costofsalesGlAcc` int(11) DEFAULT NULL,
  `effectivityDate` date DEFAULT NULL,
  PRIMARY KEY (`idItem`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item`
--

LOCK TABLES `item` WRITE;
/*!40000 ALTER TABLE `item` DISABLE KEYS */;
INSERT INTO `item` VALUES (7,'001','Red Wine',7,4,10.00,0,NULL,0,1105002,4105,4101,'2019-12-12'),(8,'002','Blue Wine',2,3,0.00,-98,NULL,1,NULL,NULL,NULL,'2019-12-06'),(9,'0003','Banana Bread',6,1,890.00,100,NULL,0,NULL,NULL,NULL,'2019-12-06'),(10,'01212','Item  1',1,3,10.00,10,NULL,0,NULL,NULL,NULL,'2019-12-13'),(11,'003','Jacket',5,1,589.50,-5,NULL,0,NULL,NULL,NULL,'2019-12-13'),(12,'004','Karsones',6,1,1569.21,50,NULL,0,NULL,NULL,NULL,'2019-12-13'),(13,'005','Short',6,3,569.00,900,NULL,0,NULL,NULL,NULL,'2019-12-13');
/*!40000 ALTER TABLE `item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `itemaffiliate`
--

DROP TABLE IF EXISTS `itemaffiliate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `itemaffiliate` (
  `idItemAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `idItem` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `selected` int(1) DEFAULT NULL,
  PRIMARY KEY (`idItemAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itemaffiliate`
--

LOCK TABLES `itemaffiliate` WRITE;
/*!40000 ALTER TABLE `itemaffiliate` DISABLE KEYS */;
INSERT INTO `itemaffiliate` VALUES (1,1,9,NULL),(2,2,26,1),(3,3,27,1),(4,3,15,1),(5,3,14,1),(6,4,27,1),(7,4,15,1),(8,4,14,1),(9,5,27,1),(10,5,15,1),(19,6,26,1),(20,6,9,1),(21,6,12,1),(26,8,26,1),(57,9,26,1),(58,9,27,1),(59,9,15,1),(60,9,14,1),(61,9,16,1),(62,9,17,1),(63,9,18,1),(64,9,24,1),(65,9,13,1),(66,9,12,1),(67,9,25,1),(68,9,9,1),(69,9,30,1),(70,9,31,1),(71,7,13,1),(72,7,12,1),(73,7,25,1),(74,7,9,1),(75,7,30,1),(76,7,31,1),(77,11,26,1),(78,12,26,1),(79,12,27,1),(80,12,15,1),(81,12,14,1),(82,12,16,1),(83,12,28,1),(84,12,17,1),(85,12,18,1),(86,12,24,1),(87,12,32,1),(88,12,13,1),(89,12,12,1),(90,12,25,1),(91,12,33,1),(92,12,30,1),(93,12,31,1),(94,12,29,1),(95,12,9,1),(96,13,26,1),(97,13,27,1),(98,13,15,1),(99,13,14,1),(100,13,16,1),(101,13,28,1),(102,13,17,1),(103,13,18,1),(104,13,24,1),(105,13,32,1),(106,13,13,1),(107,13,12,1),(108,13,25,1),(109,13,33,1),(110,13,30,1),(111,13,31,1),(112,13,29,1),(113,13,9,1),(114,10,30,1),(115,10,31,1),(116,10,25,1);
/*!40000 ALTER TABLE `itemaffiliate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `itemclassification`
--

DROP TABLE IF EXISTS `itemclassification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `itemclassification` (
  `idItemClass` int(11) NOT NULL AUTO_INCREMENT,
  `classCode` int(11) DEFAULT NULL,
  `className` char(20) DEFAULT NULL,
  PRIMARY KEY (`idItemClass`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itemclassification`
--

LOCK TABLES `itemclassification` WRITE;
/*!40000 ALTER TABLE `itemclassification` DISABLE KEYS */;
INSERT INTO `itemclassification` VALUES (1,2,'Beverages'),(2,3,'Kitchen Utensils'),(5,6,'Construction'),(6,7,'Forest'),(7,7,'Liquor');
/*!40000 ALTER TABLE `itemclassification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `itempricehistory`
--

DROP TABLE IF EXISTS `itempricehistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `itempricehistory` (
  `idPrice` int(11) NOT NULL AUTO_INCREMENT,
  `idItem` varchar(45) DEFAULT NULL,
  `itemPrice` decimal(18,2) DEFAULT '0.00',
  `effectivityDate` date DEFAULT NULL,
  PRIMARY KEY (`idPrice`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itempricehistory`
--

LOCK TABLES `itempricehistory` WRITE;
/*!40000 ALTER TABLE `itempricehistory` DISABLE KEYS */;
INSERT INTO `itempricehistory` VALUES (1,'7',100.00,'2019-10-20'),(2,'7',10.00,'2019-01-13'),(3,'7',140.00,'2019-12-10'),(5,'9',10.00,'2019-12-09'),(6,NULL,0.00,NULL),(7,NULL,0.00,NULL),(8,NULL,0.00,NULL),(9,NULL,0.00,NULL),(10,'7',1.00,'2019-12-11'),(11,'7',2.00,'2019-12-12'),(12,'7',3.00,'2019-12-13'),(13,'8',1.00,'2019-12-11'),(14,'8',2.00,'2019-12-12'),(15,'8',3.00,'2019-12-13');
/*!40000 ALTER TABLE `itempricehistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `location` (
  `idLocation` int(11) NOT NULL AUTO_INCREMENT,
  `locationCode` int(11) DEFAULT NULL,
  `locationName` char(50) DEFAULT NULL,
  PRIMARY KEY (`idLocation`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `location`
--

LOCK TABLES `location` WRITE;
/*!40000 ALTER TABLE `location` DISABLE KEYS */;
INSERT INTO `location` VALUES (3,3,'Villanueva'),(4,4,'Cagayan de Oro'),(5,6,'El Salvador City'),(6,6,'sdfsdf');
/*!40000 ALTER TABLE `location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `logs` (
  `idLog` int(11) NOT NULL AUTO_INCREMENT,
  `idAffiliate` int(11) DEFAULT NULL,
  `idLocation` int(11) DEFAULT NULL,
  `dateLog` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `idEu` int(11) DEFAULT NULL,
  `actionLogDescription` text,
  `ref` char(10) DEFAULT NULL,
  `refNum` int(11) DEFAULT NULL,
  PRIMARY KEY (`idLog`)
) ENGINE=InnoDB AUTO_INCREMENT=342 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
INSERT INTO `logs` VALUES (1,NULL,NULL,'2019-11-22','11:47:27',1,'Added new employee, one.',NULL,NULL),(2,NULL,NULL,'2019-11-22','11:57:09',1,'Modified the employee, one, for one with usertype Supervisor',NULL,NULL),(3,NULL,NULL,'2019-11-25','10:47:10',1,'Added new classification, sdasdd',NULL,NULL),(4,NULL,NULL,'2019-11-25','11:18:33',1,'Modified the module access for the user account, one of one, with usertype Administrator.',NULL,NULL),(5,NULL,NULL,'2019-11-25','11:18:48',1,'Modified the employee, one, for one with usertype Supervisor',NULL,NULL),(6,NULL,NULL,'2019-11-25','11:22:05',1,'Modified the module access for the user account, mark of mark, with usertype Administrator.',NULL,NULL),(7,NULL,NULL,'2019-11-25',NULL,1,'Username has logged out of the system.',NULL,NULL),(8,NULL,NULL,'2019-11-25',NULL,1,'Username has logged out of the system.',NULL,NULL),(9,NULL,NULL,'2019-11-25',NULL,1,'Username has logged out of the system.',NULL,NULL),(10,NULL,NULL,'2019-11-25',NULL,1,'Username has logged out of the system.',NULL,NULL),(11,NULL,NULL,'2019-11-25',NULL,1,'Username has logged out of the system.',NULL,NULL),(12,NULL,NULL,'2019-11-26',NULL,1,'Username has logged out of the system.',NULL,NULL),(13,NULL,NULL,'2019-11-26',NULL,1,'Username has logged out of the system.',NULL,NULL),(14,NULL,NULL,'2019-11-27',NULL,1,'Username has logged out of the system.',NULL,NULL),(15,NULL,NULL,'2019-11-27',NULL,1,'Username has logged out of the system.',NULL,NULL),(16,NULL,NULL,'2019-11-27',NULL,1,'Username has logged out of the system.',NULL,NULL),(17,NULL,NULL,'2019-11-27',NULL,1,'Username has logged out of the system.',NULL,NULL),(18,NULL,NULL,'2019-11-27','14:26:54',1,'Added new employee, test123, for rgyrt with usertype Supervisor',NULL,NULL),(19,NULL,NULL,'2019-11-27','14:29:07',1,'Added new employee, test123, for dsfgvdf with usertype Supervisor',NULL,NULL),(20,NULL,NULL,'2019-11-27','14:39:54',1,'Added new employee, qwerty, for tgdg with usertype Supervisor',NULL,NULL),(21,NULL,NULL,'2019-11-27','14:59:26',1,'Added new employee, qwerty, for sdgdf with usertype Supervisor',NULL,NULL),(22,NULL,NULL,'2019-11-27',NULL,1,'Username has logged out of the system.',NULL,NULL),(23,NULL,NULL,'2019-11-27',NULL,1,'Username has logged out of the system.',NULL,NULL),(24,NULL,NULL,'2019-11-27','18:54:04',1,'Modified the employee, Sample User.',NULL,NULL),(25,NULL,NULL,'2019-11-27','18:59:21',1,'Modified the employee, Sample User.',NULL,NULL),(26,NULL,NULL,'2019-11-27','19:02:48',1,'Modified the employee, Sample User.',NULL,NULL),(27,NULL,NULL,'2019-11-27','19:09:53',1,'Modified the employee, Sample User.',NULL,NULL),(28,NULL,NULL,'2019-11-27','19:11:22',1,'Modified the employee, Sample User.',NULL,NULL),(29,NULL,NULL,'2019-11-27','19:13:30',1,'Modified the employee, Sample User.',NULL,NULL),(30,NULL,NULL,'2019-11-27','19:14:36',1,'Modified the employee, marco, for marco with usertype Supervisor',NULL,NULL),(31,NULL,NULL,'2019-11-27','19:17:50',1,'Modified the employee, mark, for mark with usertype Supervisor',NULL,NULL),(32,NULL,NULL,'2019-11-27','19:24:22',1,'Modified the employee, mark, for mark with usertype Supervisor',NULL,NULL),(33,NULL,NULL,'2019-11-27','19:25:16',1,'Modified the employee, mark, for mark with usertype Supervisor',NULL,NULL),(34,NULL,NULL,'2019-11-27','19:25:28',1,'Modified the employee, mark, for mark with usertype Supervisor',NULL,NULL),(35,NULL,NULL,'2019-11-27','19:30:19',1,'Modified the employee, test user.',NULL,NULL),(36,NULL,NULL,'2019-11-27','19:33:33',1,'Modified the employee, test user.',NULL,NULL),(37,NULL,NULL,'2019-11-27','19:49:23',1,'Modified the employee, test user.',NULL,NULL),(38,NULL,NULL,'2019-11-27','20:05:48',1,'Added new employee, test marco.',NULL,NULL),(39,NULL,NULL,'2019-11-27','20:13:05',1,'Added new employee, test, for test with usertype Supervisor',NULL,NULL),(40,NULL,NULL,'2019-11-27','20:14:20',1,'Modified the employee, test user.',NULL,NULL),(41,NULL,NULL,'2019-11-27','20:17:28',1,'Modified the employee, test user.',NULL,NULL),(42,NULL,NULL,'2019-11-27','20:18:23',1,'Modified the employee, test user.',NULL,NULL),(43,NULL,NULL,'2019-11-27','20:32:33',1,'Modified the employee, user, for test user with usertype User',NULL,NULL),(44,NULL,NULL,'2019-11-27','20:33:13',1,'Modified the employee, test.',NULL,NULL),(45,NULL,NULL,'2019-11-27','20:35:35',1,'Modified the employee, 5544, for test marco with usertype Supervisor',NULL,NULL),(46,NULL,NULL,'2019-11-27','20:39:44',1,'Modified the employee, test, for test marco with usertype Supervisor',NULL,NULL),(47,NULL,NULL,'2019-11-27','20:41:00',1,'Modified the employee, test marco.',NULL,NULL),(48,NULL,NULL,'2019-11-27','20:41:39',1,'Modified the employee, test, for test marco with usertype Supervisor',NULL,NULL),(49,NULL,NULL,'2019-11-27','20:42:35',1,'Modified the employee, test marco.',NULL,NULL),(50,NULL,NULL,'2019-11-28',NULL,1,'Username has logged out of the system.',NULL,NULL),(51,NULL,NULL,'2019-11-28','10:17:51',1,'Modified the employee, 888, for test user with usertype Supervisor',NULL,NULL),(52,NULL,NULL,'2019-11-28','10:46:41',1,'Modified the employee, aubie, for Aubrey with usertype Supervisor',NULL,NULL),(53,NULL,NULL,'2019-11-28','11:06:28',1,'Modified the employee, 888, for test user with usertype User',NULL,NULL),(54,NULL,NULL,'2019-11-28','11:08:10',1,'Modified the employee, Sample User.',NULL,NULL),(55,NULL,NULL,'2019-11-28',NULL,1,'Username has logged out of the system.',NULL,NULL),(56,NULL,NULL,'2019-11-28','11:27:17',1,'Added new contribution, pag bubuntis',NULL,NULL),(57,NULL,NULL,'2019-11-28','11:28:12',1,'Deleted the contribution, pag bubuntis',NULL,NULL),(58,NULL,NULL,'2019-11-28',NULL,1,'Username has logged out of the system.',NULL,NULL),(59,NULL,NULL,'2019-11-28','11:39:26',59,'Added new contribution, pagbubuntis',NULL,NULL),(60,NULL,NULL,'2019-11-28',NULL,1,'Username has logged out of the system.',NULL,NULL),(61,NULL,NULL,'2019-11-28',NULL,59,'Username has logged out of the system.',NULL,NULL),(62,NULL,NULL,'2019-11-28',NULL,59,'Username has logged out of the system.',NULL,NULL),(63,NULL,NULL,'2019-11-28','14:45:55',1,'Added new classification, test',NULL,NULL),(64,NULL,NULL,'2019-11-28',NULL,59,'Username has logged out of the system.',NULL,NULL),(65,NULL,NULL,'2019-11-28','16:34:28',1,'Added new employee, sampaul, for Sam Paul with usertype Supervisor',NULL,NULL),(66,NULL,NULL,'2019-11-28','16:44:10',1,'Modified the employee, Sam Paul.',NULL,NULL),(67,NULL,NULL,'2019-11-28',NULL,1,'Username has logged out of the system.',NULL,NULL),(68,NULL,NULL,'2019-11-28',NULL,1,'mark has logged out of the system.',NULL,NULL),(69,NULL,NULL,'2019-11-28',NULL,1,'mark has logged out of the system.',NULL,NULL),(70,NULL,NULL,'2019-11-28',NULL,1,'mark has logged out of the system.',NULL,NULL),(71,NULL,NULL,'2019-11-28',NULL,1,'mark has logged out of the system.',NULL,NULL),(72,NULL,NULL,'2019-11-28',NULL,1,'mark has logged out of the system.',NULL,NULL),(73,NULL,NULL,'2019-11-28',NULL,1,'mark has logged out of the system.',NULL,NULL),(74,NULL,NULL,'2019-11-28',NULL,1,'mark has logged out of the system.',NULL,NULL),(75,NULL,NULL,'2019-11-28',NULL,1,'mark has logged out of the system.',NULL,NULL),(76,NULL,NULL,'2019-11-28',NULL,1,'mark has logged out of the system.',NULL,NULL),(77,NULL,NULL,'2019-11-28',NULL,1,'mark has logged out of the system.',NULL,NULL),(78,NULL,NULL,'2019-11-28',NULL,1,'mark has logged out of the system.',NULL,NULL),(79,NULL,NULL,'2019-11-28',NULL,1,'mark has logged out of the system.',NULL,NULL),(80,NULL,NULL,'2019-11-28',NULL,1,'mark has logged out of the system.',NULL,NULL),(81,NULL,NULL,'2019-11-28',NULL,1,'mark has logged out of the system.',NULL,NULL),(82,NULL,NULL,'2019-11-28','20:45:25',1,'Modified the module access for the user account, one of one, with usertype Administrator.',NULL,NULL),(83,NULL,NULL,'2019-11-28',NULL,1,'mark has logged out of the system.',NULL,NULL),(84,NULL,NULL,'2019-11-28',NULL,1,'mark has logged out of the system.',NULL,NULL),(85,NULL,NULL,'2019-11-28','20:48:46',1,'Modified the module access for the user account, one of one, with usertype Administrator.',NULL,NULL),(86,NULL,NULL,'2019-11-28','20:48:53',1,'Modified the module access for the user account, one of one, with usertype Administrator.',NULL,NULL),(87,NULL,NULL,'2019-11-28',NULL,1,'mark has logged out of the system.',NULL,NULL),(88,NULL,NULL,'2019-11-28',NULL,10,'one has logged out of the system.',NULL,NULL),(89,NULL,NULL,'2019-11-28',NULL,1,'mark has logged out of the system.',NULL,NULL),(90,NULL,NULL,'2019-11-28',NULL,1,'mark has logged out of the system.',NULL,NULL),(91,NULL,NULL,'2019-11-28',NULL,10,'one has logged out of the system.',NULL,NULL),(92,NULL,NULL,'2019-12-02',NULL,1,'mark has logged out of the system.',NULL,NULL),(93,NULL,NULL,'2019-12-02',NULL,1,'mark has logged out of the system.',NULL,NULL),(94,NULL,NULL,'2019-12-02',NULL,1,'mark has logged out of the system.',NULL,NULL),(95,NULL,NULL,'2019-12-02',NULL,1,'mark has logged out of the system.',NULL,NULL),(96,NULL,NULL,'2019-12-02','13:25:37',1,'Modified the employee, jonsnow, for Jon Snow with usertype Supervisor',NULL,NULL),(97,NULL,NULL,'2019-12-02','13:27:55',1,'Modified the employee, jonsnow, for Jon Snow with usertype Supervisor',NULL,NULL),(98,NULL,NULL,'2019-12-02','13:31:05',1,'Modified the employee, jonsnow, for Jon Snow with usertype Supervisor',NULL,NULL),(99,NULL,NULL,'2019-12-02','13:31:26',1,'Modified the employee, jonsnow, for Jon Snow with usertype Supervisor',NULL,NULL),(100,NULL,NULL,'2019-12-02','13:39:02',1,'Modified the employee, jonsnow, for Jon Snow with usertype Supervisor',NULL,NULL),(101,NULL,NULL,'2019-12-02','13:41:29',1,'Modified the employee, jonsnow, for Jon Snow with usertype Supervisor',NULL,NULL),(102,NULL,NULL,'2019-12-02','13:42:04',1,'Modified the employee, jonsnow, for Jon Snow with usertype Supervisor',NULL,NULL),(103,NULL,NULL,'2019-12-02','15:06:55',1,'Modified the employee, marco.',NULL,NULL),(104,NULL,NULL,'2019-12-02','15:07:23',1,'Modified the employee, marco, for marco with usertype Supervisor',NULL,NULL),(105,NULL,NULL,'2019-12-02','15:08:11',1,'Modified the module access for the user account, marco of marco, with usertype Supervisor.',NULL,NULL),(106,NULL,NULL,'2019-12-02','15:08:28',1,'Modified the employee, marco, for marco with usertype Supervisor',NULL,NULL),(107,NULL,NULL,'2019-12-02','15:09:44',1,'Modified the employee, marco, for marco with usertype Supervisor',NULL,NULL),(108,NULL,NULL,'2019-12-02','15:10:03',1,'Modified the module access for the user account, marco of marco, with usertype Administrator.',NULL,NULL),(109,NULL,NULL,'2019-12-02',NULL,29,'marco has logged out of the system.',NULL,NULL),(110,NULL,NULL,'2019-12-02',NULL,29,'marco has logged out of the system.',NULL,NULL),(111,NULL,NULL,'2019-12-02',NULL,1,'Username has logged out of the system.',NULL,NULL),(112,NULL,NULL,'2019-12-02','15:13:07',1,'Added new employee, kulot, for kulot with usertype Supervisor',NULL,NULL),(113,NULL,NULL,'2019-12-02','15:13:52',1,'Modified the module access for the user account, kulot of kulot, with usertype Administrator.',NULL,NULL),(114,NULL,NULL,'2019-12-02','15:20:13',30,'Modified the module access for the user account, kulot of kulot, with usertype Administrator.',NULL,NULL),(115,NULL,NULL,'2019-12-02','15:20:23',30,'Modified the module access for the user account, kulot of kulot, with usertype Administrator.',NULL,NULL),(116,NULL,NULL,'2019-12-02',NULL,1,'mark has logged out of the system.',NULL,NULL),(117,NULL,NULL,'2019-12-02','15:33:03',1,'Modified the module access for the user account, marco of marco, with usertype Administrator.',NULL,NULL),(118,NULL,NULL,'2019-12-02','15:34:01',1,'Modified the module access for the user account, marco of marco, with usertype Administrator.',NULL,NULL),(119,NULL,NULL,'2019-12-02',NULL,1,'mark has logged out of the system.',NULL,NULL),(120,NULL,NULL,'2019-12-02',NULL,29,'marco has logged out of the system.',NULL,NULL),(121,NULL,NULL,'2019-12-02','15:59:19',30,'Modified the employee, kulot, for kulot with usertype Supervisor',NULL,NULL),(122,NULL,NULL,'2019-12-02',NULL,30,'kulot has logged out of the system.',NULL,NULL),(123,NULL,NULL,'2019-12-03',NULL,30,'kulot has logged out of the system.',NULL,NULL),(124,NULL,NULL,'2019-12-03','07:25:59',30,'Modified the module access for the user account, kulot of kulot, with usertype Administrator.',NULL,NULL),(125,NULL,NULL,'2019-12-03','07:36:14',30,'Modified the module access for the user account, kulot of kulot, with usertype Administrator.',NULL,NULL),(126,NULL,NULL,'2019-12-03','07:36:43',30,'Modified the module access for the user account, kulot of kulot, with usertype Administrator.',NULL,NULL),(127,NULL,NULL,'2019-12-03','09:27:45',30,'Modified the module access for the user account, kulot of kulot, with usertype Administrator.',NULL,NULL),(128,NULL,NULL,'2019-12-03','09:28:49',30,'Modified the module access for the user account, kulot of kulot, with usertype Administrator.',NULL,NULL),(129,NULL,NULL,'2019-12-03','09:31:33',30,'Modified the module access for the user account, kulot of kulot, with usertype Administrator.',NULL,NULL),(130,NULL,NULL,'2019-12-03','10:26:07',30,'Modified the module access for the user account, kulot of kulot, with usertype Administrator.',NULL,NULL),(131,NULL,NULL,'2019-12-03','11:19:21',1,'Modified the module access for the user account, mark of mark, with usertype Administrator.',NULL,NULL),(132,NULL,NULL,'2019-12-03','11:19:33',1,'Modified the module access for the user account, mark of mark, with usertype Administrator.',NULL,NULL),(133,NULL,NULL,'2019-12-03','11:19:39',1,'Modified the module access for the user account, mark of mark, with usertype Administrator.',NULL,NULL),(134,NULL,NULL,'2019-12-03',NULL,1,'mark has logged out of the system.',NULL,NULL),(135,NULL,NULL,'2019-12-03',NULL,1,'mark has logged out of the system.',NULL,NULL),(136,NULL,NULL,'2019-12-03',NULL,1,'mark has logged out of the system.',NULL,NULL),(137,NULL,NULL,'2019-12-03',NULL,1,'mark has logged out of the system.',NULL,NULL),(138,NULL,NULL,'2019-12-03',NULL,1,'mark has logged out of the system.',NULL,NULL),(139,NULL,NULL,'2019-12-03',NULL,1,'mark has logged out of the system.',NULL,NULL),(140,NULL,NULL,'2019-12-03',NULL,1,'mark has logged out of the system.',NULL,NULL),(141,NULL,NULL,'2019-12-03',NULL,1,'mark has logged out of the system.',NULL,NULL),(142,NULL,NULL,'2019-12-03','11:28:37',1,'Added new employee, dan, for Marie Danilene with usertype Supervisor',NULL,NULL),(143,NULL,NULL,'2019-12-03',NULL,1,'mark has logged out of the system.',NULL,NULL),(144,NULL,NULL,'2019-12-03','11:29:34',1,'Modified the module access for the user account, dan of Marie Danilene, with usertype Administrator.',NULL,NULL),(145,NULL,NULL,'2019-12-03','11:29:41',1,'Modified the module access for the user account, dan of Marie Danilene, with usertype Administrator.',NULL,NULL),(146,NULL,NULL,'2019-12-03',NULL,1,'mark has logged out of the system.',NULL,NULL),(147,NULL,NULL,'2019-12-03',NULL,31,'dan has logged out of the system.',NULL,NULL),(148,NULL,NULL,'2019-12-03','11:35:14',1,'Modified the module access for the user account, dan of Marie Danilene, with usertype Administrator.',NULL,NULL),(149,NULL,NULL,'2019-12-03',NULL,1,'mark has logged out of the system.',NULL,NULL),(150,NULL,NULL,'2019-12-03','11:42:24',1,'Modified the module access for the user account, marco of marco, with usertype Administrator.',NULL,NULL),(151,NULL,NULL,'2019-12-03',NULL,29,'marco has logged out of the system.',NULL,NULL),(152,NULL,NULL,'2019-12-03',NULL,29,'marco has logged out of the system.',NULL,NULL),(153,NULL,NULL,'2019-12-03',NULL,29,'marco has logged out of the system.',NULL,NULL),(154,NULL,NULL,'2019-12-03',NULL,1,'mark has logged out of the system.',NULL,NULL),(155,NULL,NULL,'2019-12-03','11:45:44',31,'Modified the module access for the user account, mark of mark, with usertype Administrator.',NULL,NULL),(156,NULL,NULL,'2019-12-03',NULL,29,'marco has logged out of the system.',NULL,NULL),(157,NULL,NULL,'2019-12-03','11:56:59',29,'Modified the module access for the user account, marco of marco, with usertype Administrator.',NULL,NULL),(158,NULL,NULL,'2019-12-03','12:01:25',29,'Modified the module access for the user account, marco of marco, with usertype Administrator.',NULL,NULL),(159,NULL,NULL,'2019-12-03','13:21:59',29,'Modified the module access for the user account, marco of marco, with usertype Administrator.',NULL,NULL),(160,NULL,NULL,'2019-12-03',NULL,1,'mark has logged out of the system.',NULL,NULL),(161,NULL,NULL,'2019-12-03',NULL,29,'marco has logged out of the system.',NULL,NULL),(162,NULL,NULL,'2019-12-03',NULL,29,'marco has logged out of the system.',NULL,NULL),(163,NULL,NULL,'2019-12-03','14:38:40',29,'Modified the module access for the user account, marco of marco, with usertype Administrator.',NULL,NULL),(164,NULL,NULL,'2019-12-03','14:39:22',29,'Modified the module access for the user account, marco of marco, with usertype Administrator.',NULL,NULL),(165,NULL,NULL,'2019-12-03',NULL,29,'marco has logged out of the system.',NULL,NULL),(166,NULL,NULL,'2019-12-03','14:41:25',29,'Modified the module access for the user account, marco of marco, with usertype Administrator.',NULL,NULL),(167,NULL,NULL,'2019-12-03','14:41:40',29,'Modified the module access for the user account, marco of marco, with usertype Administrator.',NULL,NULL),(168,NULL,NULL,'2019-12-03',NULL,29,'marco has logged out of the system.',NULL,NULL),(169,NULL,NULL,'2019-12-03','14:42:05',29,'Modified the module access for the user account, marco of marco, with usertype Administrator.',NULL,NULL),(170,NULL,NULL,'2019-12-03',NULL,29,'marco has logged out of the system.',NULL,NULL),(171,NULL,NULL,'2019-12-03',NULL,29,'marco has logged out of the system.',NULL,NULL),(172,NULL,NULL,'2019-12-03','15:12:06',1,'Modified the module access for the user account, mark of mark, with usertype Administrator.',NULL,NULL),(173,NULL,NULL,'2019-12-03',NULL,1,'mark has logged out of the system.',NULL,NULL),(174,NULL,NULL,'2019-12-03','17:01:32',1,'Added new employee, dulcy, for Dulcy with usertype Supervisor',NULL,NULL),(175,NULL,NULL,'2019-12-03','17:02:42',1,'Modified the module access for the user account, dulcy of Dulcy, with usertype Administrator.',NULL,NULL),(176,NULL,NULL,'2019-12-03','17:02:44',1,'Modified the module access for the user account, dulcy of Dulcy, with usertype Administrator.',NULL,NULL),(177,NULL,NULL,'2019-12-03',NULL,32,'dulcy has logged out of the system.',NULL,NULL),(178,NULL,NULL,'2019-12-04','10:00:28',30,'Modified the module access for the user account, kulot of kulot, with usertype Administrator.',NULL,NULL),(179,NULL,NULL,'2019-12-04','10:13:27',30,'Modified the module access for the user account, kulot of kulot, with usertype Administrator.',NULL,NULL),(180,NULL,NULL,'2019-12-04','13:54:26',30,'Modified the module access for the user account, kulot of kulot, with usertype Administrator.',NULL,NULL),(181,NULL,NULL,'2019-12-04','16:32:53',30,'Modified the module access for the user account, kulot of kulot, with usertype Administrator.',NULL,NULL),(182,NULL,NULL,'2019-12-04','18:11:37',1,'Added a new supplier, Sharrah',NULL,NULL),(183,NULL,NULL,'2019-12-04','18:34:58',1,'Added a new supplier, aubie',NULL,NULL),(184,NULL,NULL,'2019-12-04','21:09:07',1,'Edited the supplier details of, Sharrah',NULL,NULL),(185,NULL,NULL,'2019-12-04','21:09:22',1,'Deleted the supplier Sharrah',NULL,NULL),(186,NULL,NULL,'2019-12-05',NULL,1,'mark has logged out of the system.',NULL,NULL),(187,NULL,NULL,'2019-12-05',NULL,1,'mark has logged out of the system.',NULL,NULL),(188,NULL,NULL,'2019-12-05','11:02:32',1,'Modified the module access for the user account, mark of mark, with usertype Administrator.',NULL,NULL),(189,NULL,NULL,'2019-12-05',NULL,1,'mark has logged out of the system.',NULL,NULL),(190,NULL,NULL,'2019-12-05',NULL,1,'mark has logged out of the system.',NULL,NULL),(191,NULL,NULL,'2019-12-05',NULL,1,'mark has logged out of the system.',NULL,NULL),(192,NULL,NULL,'2019-12-05','11:37:24',1,'Modified the module access for the user account, mark of mark, with usertype Administrator.',NULL,NULL),(193,NULL,NULL,'2019-12-05',NULL,1,'mark has logged out of the system.',NULL,NULL),(194,NULL,NULL,'2019-12-05',NULL,1,'mark has logged out of the system.',NULL,NULL),(195,NULL,NULL,'2019-12-05',NULL,1,'mark has logged out of the system.',NULL,NULL),(196,NULL,NULL,'2019-12-05','13:41:55',1,'Added a new supplier, sam paul',NULL,NULL),(197,NULL,NULL,'2019-12-05','13:44:22',1,'Edited the supplier details of, sam paul',NULL,NULL),(198,NULL,NULL,'2019-12-05','14:20:01',30,'Modified the module access for the user account, kulot of kulot, with usertype Administrator.',NULL,NULL),(199,NULL,NULL,'2019-12-05','16:25:11',30,'Added new employee, hazel, for Hazel with usertype Supervisor',NULL,NULL),(200,NULL,NULL,'2019-12-05','16:32:44',1,'Added a new supplier, ',NULL,NULL),(201,NULL,NULL,'2019-12-05','16:33:53',1,'Added a new supplier, ',NULL,NULL),(202,NULL,NULL,'2019-12-05','16:38:15',1,'added a new Item, samep2',NULL,NULL),(203,NULL,NULL,'2019-12-05','16:38:48',1,'added a new Item, sample3',NULL,NULL),(204,NULL,NULL,'2019-12-05','17:16:12',1,'added a new Item, sample4',NULL,NULL),(205,NULL,NULL,'2019-12-05','17:54:42',1,'edited an item details, sample4',NULL,NULL),(206,NULL,NULL,'2019-12-05','17:54:53',1,'edited an item details, sample4',NULL,NULL),(207,NULL,NULL,'2019-12-05','17:55:09',1,'edited an item details, sample4',NULL,NULL),(208,NULL,NULL,'2019-12-05','18:11:32',1,'deleted an item sample',NULL,NULL),(209,NULL,NULL,'2019-12-05','18:31:36',1,'edited an item details, sample4',NULL,NULL),(210,NULL,NULL,'2019-12-06','08:14:50',30,'Modified the module access for the user account, kulot of kulot, with usertype Administrator.',NULL,NULL),(211,NULL,NULL,'2019-12-06','08:36:21',1,'deleted an item sample3',NULL,NULL),(212,NULL,NULL,'2019-12-06','08:36:26',1,'deleted an item sample4',NULL,NULL),(213,NULL,NULL,'2019-12-06','08:36:30',1,'deleted an item samep2',NULL,NULL),(214,NULL,NULL,'2019-12-06','08:36:34',1,'deleted an item sample2',NULL,NULL),(215,NULL,NULL,'2019-12-06','08:36:38',1,'deleted an item itemName',NULL,NULL),(216,NULL,NULL,'2019-12-06','08:49:32',1,'added a new Item, Red Wine',NULL,NULL),(217,NULL,NULL,'2019-12-06','08:50:32',1,'Modified the employee, tuna, for tuna tuna with usertype Supervisor',NULL,NULL),(218,NULL,NULL,'2019-12-06','08:51:05',1,'Modified the module access for the user account, tuna of tuna tuna, with usertype Supervisor.',NULL,NULL),(219,NULL,NULL,'2019-12-06','08:51:23',1,'Modified the employee, tuna, for tuna tuna with usertype Supervisor',NULL,NULL),(220,NULL,NULL,'2019-12-06','08:58:37',1,'added a new Item, Blue Wine',NULL,NULL),(221,NULL,NULL,'2019-12-06','09:01:34',1,'added a new Item, Banana Bread',NULL,NULL),(222,NULL,NULL,'2019-12-06','09:01:59',1,'edited an item details, Banana Bread',NULL,NULL),(223,NULL,NULL,'2019-12-06','09:32:05',1,'Modified the module access for the user account, mark of mark, with usertype Administrator.',NULL,NULL),(224,NULL,NULL,'2019-12-06','09:33:10',1,'Modified the module access for the user account, mark of mark, with usertype Administrator.',NULL,NULL),(225,NULL,NULL,'2019-12-06','14:46:05',1,'Added a new supplier, another supplier',NULL,NULL),(226,NULL,NULL,'2019-12-06','15:22:43',1,'Edited the supplier details of, another supplier',NULL,NULL),(227,NULL,NULL,'2019-12-06',NULL,1,'mark has logged out of the system.',NULL,NULL),(228,NULL,NULL,'2019-12-06','19:53:08',1,'Added new employee, sysadmin, for System Administrator with usertype Supervisor',NULL,NULL),(229,NULL,NULL,'2019-12-06',NULL,1,'mark has logged out of the system.',NULL,NULL),(230,NULL,NULL,'2019-12-06','19:53:31',1,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',NULL,NULL),(231,NULL,NULL,'2019-12-06','19:53:35',1,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',NULL,NULL),(232,NULL,NULL,'2019-12-06','19:53:44',1,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',NULL,NULL),(233,NULL,NULL,'2019-12-06','19:53:47',1,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',NULL,NULL),(234,NULL,NULL,'2019-12-06',NULL,1,'mark has logged out of the system.',NULL,NULL),(235,NULL,NULL,'2019-12-06',NULL,34,'sysadmin has logged out of the system.',NULL,NULL),(236,NULL,NULL,'2019-12-09',NULL,1,'mark has logged out of the system.',NULL,NULL),(237,NULL,NULL,'2019-12-09',NULL,31,'dan has logged out of the system.',NULL,NULL),(238,NULL,NULL,'2019-12-09','15:16:55',1,'Modified the employee, mark, for mark with usertype Supervisor',NULL,NULL),(239,NULL,NULL,'2019-12-09',NULL,31,'dan has logged out of the system.',NULL,NULL),(240,NULL,NULL,'2019-12-09',NULL,1,'mark has logged out of the system.',NULL,NULL),(241,NULL,NULL,'2019-12-09','16:14:14',1,'Modified the employee, mark, for mark with usertype Supervisor',NULL,NULL),(242,NULL,NULL,'2019-12-09',NULL,1,'mark has logged out of the system.',NULL,NULL),(243,NULL,NULL,'2019-12-09','16:36:36',1,'Modified the module access for the user account, mark of mark, with usertype Administrator.',NULL,NULL),(244,NULL,NULL,'2019-12-09','16:36:39',1,'Modified the module access for the user account, mark of mark, with usertype Administrator.',NULL,NULL),(245,NULL,NULL,'2019-12-09',NULL,1,'mark has logged out of the system.',NULL,NULL),(246,NULL,NULL,'2019-12-09',NULL,1,'mark has logged out of the system.',NULL,NULL),(247,NULL,NULL,'2019-12-09',NULL,1,'mark has logged out of the system.',NULL,NULL),(248,NULL,NULL,'2019-12-09',NULL,1,'mark has logged out of the system.',NULL,NULL),(249,NULL,NULL,'2019-12-09',NULL,1,'mark has logged out of the system.',NULL,NULL),(250,NULL,NULL,'2019-12-09',NULL,1,'mark has logged out of the system.',NULL,NULL),(251,NULL,NULL,'2019-12-09',NULL,1,'mark has logged out of the system.',NULL,NULL),(252,NULL,NULL,'2019-12-09',NULL,1,'mark has logged out of the system.',NULL,NULL),(253,NULL,NULL,'2019-12-10','09:09:29',30,'Modified the module access for the user account, kulot of kulot, with usertype Administrator.',NULL,NULL),(254,NULL,NULL,'2019-12-10','11:53:49',1,'edited an item details, Red Wine',NULL,NULL),(255,NULL,NULL,'2019-12-10','13:34:30',1,'deleted an item ',NULL,NULL),(256,NULL,NULL,'2019-12-10','13:34:36',1,'deleted an item ',NULL,NULL),(257,NULL,NULL,'2019-12-11',NULL,30,'kulot has logged out of the system.',NULL,NULL),(258,NULL,NULL,'2019-12-11',NULL,1,'mark has logged out of the system.',NULL,NULL),(259,NULL,NULL,'2019-12-11',NULL,1,'mark has logged out of the system.',NULL,NULL),(260,NULL,NULL,'2019-12-11',NULL,1,'mark has logged out of the system.',NULL,NULL),(261,NULL,NULL,'2019-12-11',NULL,1,'mark has logged out of the system.',NULL,NULL),(262,NULL,NULL,'2019-12-11','19:46:27',1,'deleted an item ',NULL,NULL),(263,NULL,NULL,'2019-12-12','10:19:22',1,'Modified the module access for the user account, mark of mark, with usertype Administrator.',NULL,NULL),(264,NULL,NULL,'2019-12-12',NULL,1,'mark has logged out of the system.',NULL,NULL),(265,NULL,NULL,'2019-12-12','10:41:48',1,'Added new employee, dan, for Raymond Gillespie with usertype Supervisor',NULL,NULL),(266,NULL,NULL,'2019-12-12','10:42:22',1,'Deleted the user account of, Raymond Gillespie with username: dan.',NULL,NULL),(267,NULL,NULL,'2019-12-12','10:42:30',1,'Modified the module access for the user account, dan of Marie Danilene, with usertype Administrator.',NULL,NULL),(268,NULL,NULL,'2019-12-12','10:42:35',1,'Modified the module access for the user account, dan of Marie Danilene, with usertype Administrator.',NULL,NULL),(269,NULL,NULL,'2019-12-12','10:42:44',1,'Modified the module access for the user account, dan of Marie Danilene, with usertype Administrator.',NULL,NULL),(270,NULL,NULL,'2019-12-12','10:42:48',1,'Modified the module access for the user account, dan of Marie Danilene, with usertype Administrator.',NULL,NULL),(271,NULL,NULL,'2019-12-12',NULL,1,'mark has logged out of the system.',NULL,NULL),(272,NULL,NULL,'2019-12-12','10:45:15',31,'Modified the employee, dan, for Marie Danilene with usertype Supervisor',NULL,NULL),(273,NULL,NULL,'2019-12-12',NULL,31,'dan has logged out of the system.',NULL,NULL),(274,NULL,NULL,'2019-12-12','11:05:45',31,'Modified the employee, dan, for Marie Danilene with usertype Supervisor',NULL,NULL),(275,NULL,NULL,'2019-12-12','11:07:20',30,'Modified the module access for the user account, kulot of kulot, with usertype Administrator.',NULL,NULL),(276,NULL,NULL,'2019-12-12','11:07:36',30,'Modified the employee, kulot, for kulot with usertype Supervisor',NULL,NULL),(277,NULL,NULL,'2019-12-12','11:08:00',31,'Modified the employee, dan, for Marie Danilene with usertype Supervisor',NULL,NULL),(278,NULL,NULL,'2019-12-12','11:08:12',31,'Modified the employee, dan, for Marie Danilene with usertype Supervisor',NULL,NULL),(279,NULL,NULL,'2019-12-12',NULL,31,'dan has logged out of the system.',NULL,NULL),(280,NULL,NULL,'2019-12-12',NULL,30,'kulot has logged out of the system.',NULL,NULL),(281,NULL,NULL,'2019-12-12','11:11:28',31,'Added new employee, marie, for Timon Cantu with usertype Supervisor',NULL,NULL),(282,NULL,NULL,'2019-12-12',NULL,31,'dan has logged out of the system.',NULL,NULL),(283,NULL,NULL,'2019-12-12','11:12:10',31,'Modified the module access for the user account, marie of Timon Cantu, with usertype Administrator.',NULL,NULL),(284,NULL,NULL,'2019-12-12','11:12:21',31,'Modified the module access for the user account, marie of Timon Cantu, with usertype Administrator.',NULL,NULL),(285,NULL,NULL,'2019-12-12','11:12:24',31,'Modified the module access for the user account, marie of Timon Cantu, with usertype Administrator.',NULL,NULL),(286,NULL,NULL,'2019-12-12',NULL,31,'dan has logged out of the system.',NULL,NULL),(287,NULL,NULL,'2019-12-12','11:13:46',36,'Modified the employee, dan, for Marie Danilene with usertype Supervisor',NULL,NULL),(288,NULL,NULL,'2019-12-12','11:13:59',36,'Modified the employee, dan, for Marie Danilene with usertype Supervisor',NULL,NULL),(289,NULL,NULL,'2019-12-12','11:14:09',36,'Modified the employee, dan, for Marie Danilene with usertype Supervisor',NULL,NULL),(290,NULL,NULL,'2019-12-12','11:14:31',36,'Modified the employee, dan, for Marie Danilene with usertype Supervisor',NULL,NULL),(291,NULL,NULL,'2019-12-12',NULL,36,'marie has logged out of the system.',NULL,NULL),(292,NULL,NULL,'2019-12-12','11:15:18',30,'Modified the employee, kulot, for kulot with usertype Supervisor',NULL,NULL),(293,NULL,NULL,'2019-12-12',NULL,30,'kulot has logged out of the system.',NULL,NULL),(294,NULL,NULL,'2019-12-12','11:23:52',30,'Modified the employee, kulot, for kulot with usertype Supervisor',NULL,NULL),(295,NULL,NULL,'2019-12-12','11:35:38',1,'Modified the module access for the user account, dan of Marie Danilene, with usertype Administrator.',NULL,NULL),(296,NULL,NULL,'2019-12-13','06:25:38',31,'added a new Item, Item  1',NULL,NULL),(297,NULL,NULL,'2019-12-13','06:26:05',31,'edited an item details, Banana Bread',NULL,NULL),(298,NULL,NULL,'2019-12-13','06:28:16',31,'edited an item details, Red Wine',NULL,NULL),(299,NULL,NULL,'2019-12-13',NULL,1,'mark has logged out of the system.',NULL,NULL),(300,NULL,NULL,'2019-12-13','13:40:01',30,'Modified the employee, kulot, for kulot with usertype Supervisor',NULL,NULL),(301,NULL,NULL,'2019-12-13','14:40:25',1,'Modified the employee, mark, for mark with usertype Supervisor',NULL,NULL),(302,NULL,NULL,'2019-12-13','14:40:56',1,'Modified the employee, Sam Paul.',NULL,NULL),(303,NULL,NULL,'2019-12-13','14:41:43',1,'Modified the employee, 888, for test user with usertype User',NULL,NULL),(304,NULL,NULL,'2019-12-13',NULL,1,'mark has logged out of the system.',NULL,NULL),(305,NULL,NULL,'2019-12-13','14:51:02',1,'Modified the employee, 888, for test user with usertype Supervisor',NULL,NULL),(306,NULL,NULL,'2019-12-13',NULL,1,'mark has logged out of the system.',NULL,NULL),(307,NULL,NULL,'2019-12-13',NULL,1,'mark has logged out of the system.',NULL,NULL),(308,NULL,NULL,'2019-12-13','15:49:03',1,'added a new Item, Jacket',NULL,NULL),(309,NULL,NULL,'2019-12-13','15:51:06',1,'added a new Item, Karsones',NULL,NULL),(310,NULL,NULL,'2019-12-13','15:52:04',1,'added a new Item, Short',NULL,NULL),(311,NULL,NULL,'2019-12-17','11:24:21',31,'Modified the employee, dan, for Marie Danilene with usertype Supervisor',NULL,NULL),(312,NULL,NULL,'2019-12-17',NULL,31,'dan has logged out of the system.',NULL,NULL),(313,NULL,NULL,'2019-12-17','11:31:00',31,'edited an item details, Item  1',NULL,NULL),(314,NULL,NULL,'2019-12-17',NULL,1,'mark has logged out of the system.',NULL,NULL),(315,NULL,NULL,'2019-12-17',NULL,29,'marco has logged out of the system.',NULL,NULL),(316,NULL,NULL,'2019-12-17','21:42:29',1,'Modified the module access for the user account, marco of marco, with usertype Administrator.',NULL,NULL),(317,NULL,NULL,'2019-12-17',NULL,1,'mark has logged out of the system.',NULL,NULL),(318,NULL,NULL,'2019-12-17',NULL,29,'marco has logged out of the system.',NULL,NULL),(319,NULL,NULL,'2019-12-18',NULL,31,'dan has logged out of the system.',NULL,NULL),(320,NULL,NULL,'2019-12-18',NULL,31,'dan has logged out of the system.',NULL,NULL),(321,NULL,NULL,'2019-12-18',NULL,31,'dan has logged out of the system.',NULL,NULL),(322,NULL,NULL,'2019-12-18',NULL,31,'dan has logged out of the system.',NULL,NULL),(323,NULL,NULL,'2019-12-18','14:30:48',30,'Added a new supplier, Wallace Schroeder',NULL,NULL),(324,NULL,NULL,'2019-12-18',NULL,1,'mark has logged out of the system.',NULL,NULL),(325,NULL,NULL,'2019-12-18',NULL,29,'marco has logged out of the system.',NULL,NULL),(326,NULL,NULL,'2019-12-19',NULL,31,'dan has logged out of the system.',NULL,NULL),(327,NULL,NULL,'2019-12-19',NULL,1,'mark has logged out of the system.',NULL,NULL),(328,NULL,NULL,'2019-12-19',NULL,31,'dan has logged out of the system.',NULL,NULL),(329,NULL,NULL,'2019-12-19',NULL,31,'dan has logged out of the system.',NULL,NULL),(330,NULL,NULL,'2019-12-20',NULL,30,'kulot has logged out of the system.',NULL,NULL),(331,NULL,NULL,'2019-12-23',NULL,NULL,'Chart of Accounts : System Administrator modified account code : .',NULL,NULL),(332,NULL,NULL,'2019-12-23',NULL,NULL,'Chart of Accounts : System Administrator modified account code : .',NULL,NULL),(333,NULL,NULL,'2019-12-23',NULL,NULL,'Chart of Accounts : System Administrator modified account code : .',NULL,NULL),(334,NULL,NULL,'2019-12-23',NULL,NULL,'Chart of Accounts : System Administrator modified account code : 1103001.',NULL,NULL),(335,NULL,NULL,'2019-12-23',NULL,NULL,'Chart of Accounts : System Administrator modified account code : 1103001.',NULL,NULL),(336,NULL,NULL,'2019-12-23',NULL,NULL,'Chart of Accounts : System Administrator modified account code : 1103001.',NULL,NULL),(337,NULL,NULL,'2019-12-23',NULL,NULL,'Chart of Accounts : System Administrator modified account code : 1103001.',NULL,NULL),(338,NULL,NULL,'2019-12-23',NULL,NULL,'Chart of Accounts : System Administrator modified account code : 1103002.',NULL,NULL),(339,NULL,NULL,'2019-12-23',NULL,NULL,'Chart of Accounts : System Administrator added new account code : 2202000.',NULL,NULL),(340,NULL,NULL,'2019-12-26',NULL,NULL,'Chart of Accounts : System Administrator added new account code : 2202001.',NULL,NULL),(341,NULL,NULL,'2019-12-26',NULL,NULL,'Chart of Accounts : System Administrator modified account code : 2202001.',NULL,NULL);
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `module`
--

DROP TABLE IF EXISTS `module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `module` (
  `idModule` int(11) NOT NULL AUTO_INCREMENT,
  `moduleType` int(1) DEFAULT NULL COMMENT '1 - Dashboard\n2 - Inventory\n3 - Accounting\n4 - General Reports\n5 - Admin',
  `moduleSub` int(1) DEFAULT '0' COMMENT 'Other Menu:\n0 - Transaction\n1 - Reports\n2 - Settings\n3 - Modules\n\nInventory:\n0 - Purchase Order\n1 - Receiving\n2 - Releasing\n3 - Inventory\n4 - Settings',
  `sorter` int(100) DEFAULT '0',
  `moduleName` char(100) DEFAULT NULL,
  `moduleLink` char(100) DEFAULT NULL,
  `moduleArchive` int(1) DEFAULT '0',
  `isTransaction` int(1) DEFAULT '1',
  PRIMARY KEY (`idModule`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `module`
--

LOCK TABLES `module` WRITE;
/*!40000 ALTER TABLE `module` DISABLE KEYS */;
INSERT INTO `module` VALUES (1,0,3,0,'Dashboard','dashboard/Dashboard.js',0,1),(2,1,0,0,'Purchase Order','inventory/Purchaseorder.js',0,0),(3,5,2,4,'User Settings','admin/Usersettings.js',0,1),(4,5,2,1,'Affiliate Settings','admin/Affiliatesettings.js',0,1),(5,5,2,2,'Cost Center Settings','admin/Costcentersettings.js',0,1),(6,5,2,3,'Employee Classification Settings','admin/Empclassificationsettings.js',0,1),(7,5,2,6,'Backup and Restore','admin/Bandr.js',0,1),(8,4,3,4,'Reference Settings','generalsettings/Referencesettings.js',0,1),(9,5,2,5,'User Action Logs','admin/Userlog.js',0,1),(10,4,3,5,'Bank Settings','generalsettings/Banksettings.js',0,1),(11,4,3,1,'Customer Setting','generalsettings/customer.js',0,1),(12,4,3,2,'Supplier Settings','generalsettings/Supplier.js',0,1),(13,4,3,3,'Location Settings','generalsettings/Locationsettings.js',0,1),(14,1,4,0,'Classification Settings','inventory/Classificationsettings.js',0,1),(15,1,4,0,'Unit Settings','inventory/Unitsettings.js',0,1),(16,1,4,0,'Item Settings','inventory/Item.js',0,1),(17,1,2,0,'Sales Order','inventory/Salesorder.js',0,0),(18,1,2,0,'Sales','inventory/Sales.js',0,0),(19,2,2,0,'Chart of Accounts','accounting/Chartofaccounts.js',0,0);
/*!40000 ALTER TABLE `module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `po`
--

DROP TABLE IF EXISTS `po`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `po` (
  `idPo` int(11) NOT NULL AUTO_INCREMENT,
  `idReference` int(11) DEFAULT NULL,
  `idReferenceSeries` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `idCostCenter` int(11) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `idSupplier` int(11) DEFAULT NULL,
  `dueDate` date DEFAULT NULL,
  `remarks` text,
  `hasJournal` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `preparedBy` int(11) DEFAULT NULL,
  `notedBy` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '1' COMMENT '1 - Pending\n2 - Approved\n3 - Cancelled',
  `idItem` int(11) DEFAULT NULL,
  `idItemClass` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT '0',
  `qtyLeft` int(11) DEFAULT '0',
  `cost` decimal(18,2) DEFAULT '0.00',
  `idLocation` int(11) DEFAULT NULL,
  `idInvoice` int(11) DEFAULT NULL,
  `referenceNum` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `po`
--

LOCK TABLES `po` WRITE;
/*!40000 ALTER TABLE `po` DISABLE KEYS */;
/*!40000 ALTER TABLE `po` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `postdated`
--

DROP TABLE IF EXISTS `postdated`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `postdated` (
  `idPostdated` int(11) NOT NULL AUTO_INCREMENT,
  `paymentMethod` int(1) DEFAULT NULL COMMENT '1 - Cash\n2 - Cheque\n3 - Terms',
  `idBankAccount` int(11) DEFAULT NULL,
  `chequeNo` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT '0.00',
  `idDisbursement` int(11) DEFAULT NULL,
  `idInvoice` int(11) DEFAULT NULL,
  `idBankRecon` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL COMMENT '1 - Ourstanding\n2 - Cleared\n3 - Canceled\n4 - Bounced',
  `statusDate` date DEFAULT NULL,
  `remarks` char(250) DEFAULT NULL,
  `depositBankAccountId` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPostdated`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `postdated`
--

LOCK TABLES `postdated` WRITE;
/*!40000 ALTER TABLE `postdated` DISABLE KEYS */;
/*!40000 ALTER TABLE `postdated` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `postdatedhistory`
--

DROP TABLE IF EXISTS `postdatedhistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `postdatedhistory` (
  `idPosdatedHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idPostdated` int(11) DEFAULT NULL,
  `paymentMethod` int(1) DEFAULT NULL COMMENT '1 - Cash\n2 - Cheque\n3 - Terms',
  `idBankAccount` int(11) DEFAULT NULL,
  `chequeNo` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT '0.00',
  `idDisbursement` int(11) DEFAULT NULL,
  `idInvoice` int(11) DEFAULT NULL,
  `idBankRecon` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL COMMENT '1 - Ourstanding\n2 - Cleared\n3 - Canceled\n4 - Bounced',
  `statusDate` date DEFAULT NULL,
  `remarks` char(250) DEFAULT NULL,
  `depositBankAccountId` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPosdatedHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `postdatedhistory`
--

LOCK TABLES `postdatedhistory` WRITE;
/*!40000 ALTER TABLE `postdatedhistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `postdatedhistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posting`
--

DROP TABLE IF EXISTS `posting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `posting` (
  `idPosting` int(11) NOT NULL AUTO_INCREMENT,
  `idInvoice` int(11) DEFAULT NULL,
  `idBankRecon` int(11) DEFAULT NULL,
  `idClosing` int(11) DEFAULT NULL,
  `idCoa` int(11) DEFAULT NULL,
  `idCostCenter` int(11) DEFAULT NULL,
  `idPo` int(11) DEFAULT NULL,
  `idReceiving` int(11) DEFAULT NULL,
  `idInvAdjustment` int(11) DEFAULT NULL,
  `idDisbursement` int(11) DEFAULT NULL,
  `idBeginningBalances` int(11) DEFAULT NULL,
  `idReceipts` int(11) DEFAULT NULL,
  `idReleasing` int(11) DEFAULT NULL,
  `idSo` int(11) DEFAULT NULL,
  `explanation` text,
  `debit` decimal(18,2) DEFAULT '0.00',
  `credit` decimal(18,2) DEFAULT '0.00',
  PRIMARY KEY (`idPosting`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posting`
--

LOCK TABLES `posting` WRITE;
/*!40000 ALTER TABLE `posting` DISABLE KEYS */;
/*!40000 ALTER TABLE `posting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `postinghistory`
--

DROP TABLE IF EXISTS `postinghistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `postinghistory` (
  `idPostingHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idPosting` int(11) DEFAULT NULL,
  `idInvoice` int(11) DEFAULT NULL,
  `idBankRecon` int(11) DEFAULT NULL,
  `idClosing` int(11) DEFAULT NULL,
  `idCoa` int(11) DEFAULT NULL,
  `idCostCenter` int(11) DEFAULT NULL,
  `idPo` int(11) DEFAULT NULL,
  `idReceiving` int(11) DEFAULT NULL,
  `idInvAdjustment` int(11) DEFAULT NULL,
  `idDisbursement` int(11) DEFAULT NULL,
  `idBeginningBalances` int(11) DEFAULT NULL,
  `idReceipts` int(11) DEFAULT NULL,
  `idReleasing` int(11) DEFAULT NULL,
  `idSo` int(11) DEFAULT NULL,
  `explanation` text,
  `debit` decimal(18,2) DEFAULT '0.00',
  `credit` decimal(18,2) DEFAULT '0.00',
  PRIMARY KEY (`idPostingHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `postinghistory`
--

LOCK TABLES `postinghistory` WRITE;
/*!40000 ALTER TABLE `postinghistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `postinghistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `receipts`
--

DROP TABLE IF EXISTS `receipts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `receipts` (
  `idReceipts` int(11) NOT NULL AUTO_INCREMENT,
  `idReference` int(11) DEFAULT NULL,
  `idReferenceSeries` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `idLocation` int(11) DEFAULT NULL,
  `idCostCenter` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `idCustomer` int(11) DEFAULT NULL,
  `remarks` text,
  `fref` int(11) DEFAULT NULL,
  `frefnum` int(11) DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT '0.00',
  `hasJournal` int(1) DEFAULT '0' COMMENT '1 - True\n0 - False',
  `notedBy` int(11) DEFAULT NULL,
  `doneBy` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '1' COMMENT '1 - Pending\n2 - Approve\n3 - Cancelled',
  PRIMARY KEY (`idReceipts`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receipts`
--

LOCK TABLES `receipts` WRITE;
/*!40000 ALTER TABLE `receipts` DISABLE KEYS */;
/*!40000 ALTER TABLE `receipts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `receiving`
--

DROP TABLE IF EXISTS `receiving`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `receiving` (
  `idReceiving` int(11) NOT NULL AUTO_INCREMENT,
  `idAffiliate` int(11) DEFAULT NULL,
  `idCostCenter` int(11) DEFAULT NULL,
  `idReference` int(11) DEFAULT NULL,
  `idReferenceSeries` int(11) DEFAULT NULL,
  `idLocation` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `idItem` int(11) DEFAULT NULL,
  `idiItemClass` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `qtyLeft` int(11) DEFAULT NULL,
  `fident` int(11) DEFAULT NULL,
  `refNum` int(11) DEFAULT NULL,
  `refSeries` int(11) DEFAULT NULL,
  `cost` decimal(18,2) DEFAULT '0.00',
  `price` decimal(18,2) DEFAULT '0.00',
  `hasJournal` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `notedBy` int(11) DEFAULT NULL,
  `receivedBy` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '1' COMMENT '1 - Pending\n2 - Approved\n3 - Cancelled',
  `expiryDate` date DEFAULT NULL,
  `idSupplier` int(11) DEFAULT NULL,
  `idInvoice` int(11) DEFAULT NULL,
  PRIMARY KEY (`idReceiving`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receiving`
--

LOCK TABLES `receiving` WRITE;
/*!40000 ALTER TABLE `receiving` DISABLE KEYS */;
/*!40000 ALTER TABLE `receiving` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reference`
--

DROP TABLE IF EXISTS `reference`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `reference` (
  `idReference` int(11) NOT NULL AUTO_INCREMENT,
  `code` char(5) DEFAULT NULL,
  `name` char(50) DEFAULT NULL,
  `idModule` int(11) DEFAULT NULL,
  `isDefault` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  PRIMARY KEY (`idReference`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reference`
--

LOCK TABLES `reference` WRITE;
/*!40000 ALTER TABLE `reference` DISABLE KEYS */;
INSERT INTO `reference` VALUES (21,'CV','CHECK VOUCHER',2,0),(22,'OR','OFFICIAL RECEIPT',2,0),(24,'PO','PO',2,0),(26,'SO','Sample',17,0),(27,'test','test',17,0),(29,'SO1','Sales Order',17,0),(30,'SOR','Required Cost',17,0),(31,'SON','Non Required Cost',17,0),(32,'SOR1','Cost 1 Required',17,0),(33,'SOR2','Cost 2 Required',17,0),(37,'CPO1','Non-required CC',2,0),(38,'NPO2','Required CC',2,0),(44,'PO1','Purchase Order',2,0),(45,'RS','REFERENCE SETTINGS',18,0),(46,'SOM','main',17,0);
/*!40000 ALTER TABLE `reference` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `referenceaffiliate`
--

DROP TABLE IF EXISTS `referenceaffiliate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `referenceaffiliate` (
  `idRefAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `idReference` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  PRIMARY KEY (`idRefAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `referenceaffiliate`
--

LOCK TABLES `referenceaffiliate` WRITE;
/*!40000 ALTER TABLE `referenceaffiliate` DISABLE KEYS */;
INSERT INTO `referenceaffiliate` VALUES (51,23,12),(52,23,13),(53,21,12),(54,22,13),(55,22,15),(56,22,9),(57,22,17),(58,24,9),(59,24,12),(60,24,13),(61,24,14),(62,24,15),(63,24,16),(64,24,17),(65,24,18),(66,24,24),(67,24,25),(68,24,26),(69,24,27),(70,25,9),(71,25,12),(72,25,13),(73,25,14),(74,25,15),(75,25,16),(76,25,17),(77,25,18),(78,25,24),(79,25,25),(80,25,26),(81,25,27),(82,25,28),(83,25,29),(84,26,9),(85,26,12),(86,26,13),(87,26,14),(88,26,15),(89,26,16),(90,26,17),(91,26,18),(92,26,24),(93,26,25),(94,26,26),(95,26,27),(96,26,28),(97,26,29),(98,29,9),(99,29,12),(100,29,13),(101,29,14),(102,29,15),(103,29,16),(104,29,17),(105,29,18),(106,29,24),(107,29,25),(108,29,26),(109,29,27),(110,29,28),(111,29,29),(112,30,31),(113,31,30),(114,32,31),(115,32,30),(116,33,31),(117,36,9),(118,36,12),(119,37,32),(121,38,33),(125,42,9),(126,43,9),(127,44,12),(128,45,9),(129,45,12),(130,45,13),(131,45,14),(132,45,15),(133,45,16),(134,45,17),(135,45,18),(136,45,24),(137,45,25),(138,45,26),(139,45,27),(140,45,28),(141,45,29),(142,45,30),(143,45,31),(144,45,32),(145,45,33),(146,46,25);
/*!40000 ALTER TABLE `referenceaffiliate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `referenceseries`
--

DROP TABLE IF EXISTS `referenceseries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `referenceseries` (
  `idReferenceSeries` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `idCostCenter` int(11) DEFAULT NULL,
  `idModule` int(11) DEFAULT NULL,
  `idReference` int(11) DEFAULT NULL,
  `seriesFrom` int(11) DEFAULT NULL,
  `seriesTo` int(11) DEFAULT NULL,
  PRIMARY KEY (`idReferenceSeries`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `referenceseries`
--

LOCK TABLES `referenceseries` WRITE;
/*!40000 ALTER TABLE `referenceseries` DISABLE KEYS */;
INSERT INTO `referenceseries` VALUES (1,'2019-12-06',12,27,2,21,1,100),(3,'2019-12-09',9,3,2,22,1,1000),(4,'2019-12-10',25,3,2,24,2000,3000),(5,'2019-12-12',9,2,17,29,100,1000),(6,'2019-12-12',9,NULL,17,26,102,1020),(7,'2019-12-12',9,10,17,29,1010,1540),(8,'2019-12-12',9,NULL,17,29,500,5522),(9,'2019-12-12',9,4,18,29,500,5522),(10,'2019-12-12',9,NULL,18,29,1,12),(11,'2019-12-12',31,37,17,30,1000,2000),(12,'2019-12-12',30,38,17,31,1000,3000),(13,'2019-12-12',30,41,17,32,1,2),(14,'2019-12-12',31,42,17,32,10,45),(15,'2019-12-12',31,42,17,33,12,123),(16,'2019-12-12',31,42,17,32,101,215),(17,'2019-12-12',31,41,17,32,101,215),(18,'2019-12-13',12,26,2,44,1,499),(19,'2019-12-13',12,26,2,44,500,999),(20,'2019-12-13',9,11,2,45,50,100),(21,'2019-12-13',9,11,2,45,80,100),(22,'2019-12-13',12,26,2,44,1000,1001),(23,'2019-12-17',30,38,17,32,1000,1001),(24,'2019-12-17',25,NULL,17,46,100,1010),(25,'2019-12-17',25,43,17,29,6000,6100);
/*!40000 ALTER TABLE `referenceseries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `releasing`
--

DROP TABLE IF EXISTS `releasing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `releasing` (
  `idReleasing` int(11) NOT NULL AUTO_INCREMENT,
  `idReference` int(11) DEFAULT NULL,
  `idReferenceSeries` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `idLocation` int(11) DEFAULT NULL,
  `idCostCenter` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `idItem` int(11) DEFAULT NULL,
  `idItemClass` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `qtyLeft` int(11) DEFAULT NULL,
  `fident` int(11) DEFAULT NULL,
  `fref` int(11) DEFAULT NULL,
  `frefnum` int(11) DEFAULT NULL,
  `cost` decimal(18,2) DEFAULT NULL,
  `price` decimal(18,2) DEFAULT NULL,
  `discount` decimal(18,2) DEFAULT NULL,
  `hasJournal` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `notedBy` int(11) DEFAULT NULL,
  `preparedBy` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '1' COMMENT '1 - Pending\n2 - Approved\n3 - Cancelled',
  PRIMARY KEY (`idReleasing`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `releasing`
--

LOCK TABLES `releasing` WRITE;
/*!40000 ALTER TABLE `releasing` DISABLE KEYS */;
/*!40000 ALTER TABLE `releasing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `so`
--

DROP TABLE IF EXISTS `so`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `so` (
  `idSo` int(11) NOT NULL AUTO_INCREMENT,
  `idAffiliate` int(11) DEFAULT NULL,
  `idReference` int(11) DEFAULT NULL,
  `idReferenceSeries` int(11) DEFAULT NULL,
  `idCostcCenter` int(11) DEFAULT NULL,
  `idLocation` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `idCustomer` int(11) DEFAULT NULL,
  `remarks` text,
  `idItem` int(11) DEFAULT NULL,
  `idItemClass` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `qtyLeft` int(11) DEFAULT NULL,
  `cost` decimal(18,2) DEFAULT '0.00',
  `idInvoice` int(11) DEFAULT NULL,
  `hasJournal` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `notedBy` int(11) DEFAULT NULL,
  `preparedBy` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '1' COMMENT '1 - Pending\n2 - Approved\n3 - Cancelled',
  PRIMARY KEY (`idSo`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `so`
--

LOCK TABLES `so` WRITE;
/*!40000 ALTER TABLE `so` DISABLE KEYS */;
INSERT INTO `so` VALUES (54,NULL,NULL,NULL,NULL,NULL,'2019-12-18',NULL,NULL,9,NULL,1,1,10.00,30,0,NULL,NULL,1),(55,NULL,NULL,NULL,NULL,NULL,'2019-12-18',NULL,NULL,7,NULL,1,1,3.00,30,0,NULL,NULL,1),(56,NULL,NULL,NULL,NULL,NULL,'2019-12-18',NULL,NULL,9,NULL,50,50,10.00,31,0,NULL,NULL,1),(57,NULL,NULL,NULL,NULL,NULL,'2019-12-18',NULL,NULL,9,NULL,123,123,13.00,25,0,NULL,NULL,1),(58,NULL,NULL,NULL,NULL,NULL,'2019-12-18',NULL,NULL,7,NULL,12,12,30.00,25,0,NULL,NULL,1),(60,NULL,NULL,NULL,NULL,NULL,'2019-12-19',NULL,NULL,9,NULL,1,1,10.00,32,0,NULL,NULL,1),(61,NULL,NULL,NULL,NULL,NULL,'2019-12-19',NULL,NULL,7,NULL,1,1,3.00,32,0,NULL,NULL,1);
/*!40000 ALTER TABLE `so` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supplier`
--

DROP TABLE IF EXISTS `supplier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `supplier` (
  `idSupplier` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(50) DEFAULT NULL,
  `email` char(50) DEFAULT NULL,
  `contactNumber` bigint(11) DEFAULT NULL,
  `address` text,
  `tin` bigint(11) DEFAULT NULL,
  `paymentMethod` int(1) DEFAULT NULL COMMENT '1 - Cash\n2 - Charge',
  `terms` int(1) DEFAULT NULL COMMENT '1 - 30 Days\n2 - 60 Days\n3 - 90 Days\n4 - 120 Days',
  `withCreditLimit` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `creditLimit` decimal(18,2) DEFAULT '0.00',
  `withVat` int(1) DEFAULT '0' COMMENT '0 - False\\n1 - True',
  `vatType` int(1) DEFAULT NULL COMMENT '1 - Inclusive\n2 - Exclusive',
  `vatPercent` decimal(18,2) DEFAULT '0.00',
  `discount` decimal(18,2) DEFAULT '0.00',
  `withholdingTax` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `withholdingTaxRate` decimal(18,2) DEFAULT '0.00',
  `expenseGlAcc` int(11) DEFAULT NULL,
  `discountGlAcc` int(11) DEFAULT NULL,
  PRIMARY KEY (`idSupplier`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supplier`
--

LOCK TABLES `supplier` WRITE;
/*!40000 ALTER TABLE `supplier` DISABLE KEYS */;
INSERT INTO `supplier` VALUES (1,'mark',NULL,1231,'12312312',123211,0,NULL,0,0.00,0,NULL,0.00,NULL,0,0.00,NULL,NULL),(2,'marco','marco@gmail.com',123123,'address',123,0,0,1,111.00,1,0,11.00,11.00,1,11.00,4101,4102),(4,'aubie','aubie@yahoo.com',1231,'address',123,1,2,0,0.00,0,NULL,0.00,NULL,0,0.00,NULL,NULL),(5,'sam paul','sampaul@yopmail.com',2147483647,'opol',2147483647,0,0,1,5000.00,1,1,12.00,10.00,0,0.00,NULL,NULL),(9,'another supplier','another@gamil.com',3413,'sdlfjsd fsdlkf lsdkj',12312,1,3,1,22.00,1,1,22.00,232.00,1,34.00,4101,4102),(10,'Wallace Schroeder','qoxeh@mailinator.com',705,'Occaecat neque dolor',0,0,NULL,0,0.00,0,NULL,0.00,0.00,0,0.00,4101,4102);
/*!40000 ALTER TABLE `supplier` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supplieraffiliate`
--

DROP TABLE IF EXISTS `supplieraffiliate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `supplieraffiliate` (
  `idSupplierAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `idSupplier` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `selected` int(1) DEFAULT NULL,
  PRIMARY KEY (`idSupplierAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supplieraffiliate`
--

LOCK TABLES `supplieraffiliate` WRITE;
/*!40000 ALTER TABLE `supplieraffiliate` DISABLE KEYS */;
INSERT INTO `supplieraffiliate` VALUES (1,1,27,1),(2,2,26,1),(3,2,27,1),(6,4,26,1),(7,4,27,1),(8,4,15,1),(9,3,27,1),(10,3,15,1),(13,5,26,1),(14,5,27,1),(22,9,26,1),(23,10,13,1),(24,10,12,1);
/*!40000 ALTER TABLE `supplieraffiliate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supplieritems`
--

DROP TABLE IF EXISTS `supplieritems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `supplieritems` (
  `idSupplierItems` int(11) NOT NULL AUTO_INCREMENT,
  `idSupplier` int(11) DEFAULT NULL,
  `idItem` int(11) DEFAULT NULL,
  PRIMARY KEY (`idSupplierItems`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supplieritems`
--

LOCK TABLES `supplieritems` WRITE;
/*!40000 ALTER TABLE `supplieritems` DISABLE KEYS */;
INSERT INTO `supplieritems` VALUES (1,1,231),(2,2,3),(3,3,4),(4,4,1231),(5,5,0),(9,9,9),(10,9,8),(11,9,9);
/*!40000 ALTER TABLE `supplieritems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unadjusted`
--

DROP TABLE IF EXISTS `unadjusted`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `unadjusted` (
  `idUnadjusted` int(11) NOT NULL AUTO_INCREMENT,
  `idBankRecon` int(11) DEFAULT NULL,
  `idPostdated` int(11) DEFAULT NULL,
  `unadjustedTag` int(1) DEFAULT NULL COMMENT '1 - Add\n2 - Less',
  PRIMARY KEY (`idUnadjusted`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unadjusted`
--

LOCK TABLES `unadjusted` WRITE;
/*!40000 ALTER TABLE `unadjusted` DISABLE KEYS */;
/*!40000 ALTER TABLE `unadjusted` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unadjustedhistory`
--

DROP TABLE IF EXISTS `unadjustedhistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `unadjustedhistory` (
  `idUnadjustedHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idUnadjusted` int(11) DEFAULT NULL,
  `idBankRecon` int(11) DEFAULT NULL,
  `idPostdated` int(11) DEFAULT NULL,
  `unadjustedTag` int(1) DEFAULT NULL COMMENT '1 - Add\n2 - Less',
  PRIMARY KEY (`idUnadjustedHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unadjustedhistory`
--

LOCK TABLES `unadjustedhistory` WRITE;
/*!40000 ALTER TABLE `unadjustedhistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `unadjustedhistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unchecks`
--

DROP TABLE IF EXISTS `unchecks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `unchecks` (
  `idUnchecks` int(11) NOT NULL AUTO_INCREMENT,
  `idBankRecon` int(11) DEFAULT NULL,
  `idPostdated` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL COMMENT '1 - Outstanding\n2 - Cleared\n3 - Cancelled\n4 - Bounced',
  `uncheckTag` int(1) DEFAULT NULL COMMENT '1 - Add\n2 - Less',
  PRIMARY KEY (`idUnchecks`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unchecks`
--

LOCK TABLES `unchecks` WRITE;
/*!40000 ALTER TABLE `unchecks` DISABLE KEYS */;
/*!40000 ALTER TABLE `unchecks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unit`
--

DROP TABLE IF EXISTS `unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `unit` (
  `idUnit` int(11) NOT NULL AUTO_INCREMENT,
  `unitCode` char(20) DEFAULT NULL,
  `unitName` char(20) DEFAULT NULL,
  PRIMARY KEY (`idUnit`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unit`
--

LOCK TABLES `unit` WRITE;
/*!40000 ALTER TABLE `unit` DISABLE KEYS */;
INSERT INTO `unit` VALUES (1,'ft','feet'),(3,'2','ml'),(4,'4','l'),(5,'5','g'),(6,'6','kg'),(7,'589641','cm'),(8,'9dsfd','sdfdf'),(11,'123456','here'),(12,'1234567','7 ni'),(13,'sm','small');
/*!40000 ALTER TABLE `unit` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-12-26 13:14:58
