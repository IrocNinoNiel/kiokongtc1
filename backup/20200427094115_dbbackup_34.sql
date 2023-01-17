-- DB BACK UP Created on: 04/27/2020 09:41:15

DROP TABLE IF EXISTS `accountbegbal`;:||:Separator:||:


CREATE TABLE `accountbegbal` (
  `idAccBegBal` int(11) NOT NULL AUTO_INCREMENT,
  `idAffiliate` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `idLocation` int(11) DEFAULT NULL,
  `dateModified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idAccBegBal`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `accountbegbal` WRITE;:||:Separator:||:
 INSERT INTO `accountbegbal` VALUES(1,2,'2018-03-05',null,'2020-04-25 09:41:11');:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `accountbegbalhistory`;:||:Separator:||:


CREATE TABLE `accountbegbalhistory` (
  `idAccBegBalHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idAccBegBal` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `idLocation` int(11) DEFAULT NULL,
  PRIMARY KEY (`idAccBegBalHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `accountbegbalhistory` WRITE;:||:Separator:||:
 INSERT INTO `accountbegbalhistory` VALUES(1,1,2,'2018-03-05',null),(2,1,2,'2018-03-05',null),(3,1,2,'2018-03-05',null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `adjusted`;:||:Separator:||:


CREATE TABLE `adjusted` (
  `idAdjusted` int(11) NOT NULL AUTO_INCREMENT,
  `idBankRecon` int(11) DEFAULT NULL,
  `description` char(250) DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT '0.00',
  `adjustedTag` int(1) DEFAULT '0' COMMENT '1 - Add\n2 - Less',
  PRIMARY KEY (`idAdjusted`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `adjusted` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `adjustedhistory`;:||:Separator:||:


CREATE TABLE `adjustedhistory` (
  `idAdjustedHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idAdjusted` int(11) DEFAULT NULL,
  `idBankRecon` int(11) DEFAULT NULL,
  `idBankReconHistory` int(11) DEFAULT NULL,
  `description` char(250) DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT '0.00',
  `adjustedTag` int(1) DEFAULT '0' COMMENT '1 - Add\n2 - Less',
  PRIMARY KEY (`idAdjustedHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `adjustedhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `affiliate`;:||:Separator:||:


CREATE TABLE `affiliate` (
  `idAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `affiliateName` text,
  `tagLine` text,
  `address` text,
  `contactPerson` char(50) DEFAULT NULL,
  `contactNumber` char(20) DEFAULT NULL,
  `email` char(50) DEFAULT NULL,
  `tin` char(20) DEFAULT NULL,
  `vatPercent` decimal(18,2) DEFAULT '0.00',
  `vatType` int(1) DEFAULT '1' COMMENT '1 - Inclusive\n2 - Exclusive',
  `checkedBy` text,
  `reviewedBy` text,
  `approvedBy1` int(11) DEFAULT NULL,
  `approvedBy2` int(11) DEFAULT NULL,
  `accSchedule` text COMMENT '1 - Calendar\\\\n2 - Fiscal',
  `month` int(2) DEFAULT '1' COMMENT '1 - January\n2 - February\n3 - March\n4 - April\n5 - May\n6 - June\n7 - July\n8 - August\n9 - September\n10 - October\n11 - November\n12 - December',
  `remarks` text,
  `refTag` int(1) DEFAULT NULL,
  `logo` text,
  `status` int(1) DEFAULT '0' COMMENT '1 - Active\n2 - Inactive',
  `mainTag` int(1) DEFAULT '0',
  `location` int(11) DEFAULT NULL,
  `dateStart` date DEFAULT NULL,
  `archived` int(1) DEFAULT '0',
  `sk` text,
  PRIMARY KEY (`idAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `affiliate` WRITE;:||:Separator:||:
 INSERT INTO `affiliate` VALUES(1,'TEST AFFILIATE',null,null,null,null,null,null,0.00,1,null,null,null,null,null,1,null,null,null,1,0,null,null,1,null),(2,'9fb35413720056f26ef5d6e701f0a8c5b5048ae97a8bf653830ac56a4c3dbd87028e9597a1cc209771612ae8fc23d90fdb44536832bfa9b9f07df24b841b075eDxiH0EgEaig1R1paOUcOp4dG5YvBociTP+hXd0tNgLczjMB1uIQVeGekmY24LW6/',null,null,null,null,null,123456789,0.00,0,null,null,null,null,'f22c9519d0d365b85e423c21b9a6fa19c0511924b6764177269444015b9d9d8198c0253cafc13afe3ea6c2e53f2edb911cde606f0af3ed878d59719b730e3de9MOVxFHSW6Rcqao042QetpqbCewz40dktAkRAI6iAq6M=',4,null,0,null,1,1,null,'2018-03-05',0,192514200156),(3,'678fe40fbb859ea6212d764b48f86009d75840e926028ece5e1fc5ac7b4545797e7864d9b436dd3324fb127a0eb84628117752c05507e9b23c7f39d1055248b3T0anCGQuOYbL4MPsSvXk8OD4Avsa+9VsbDJjlVi+F0c=',null,null,null,null,null,3242534534,0.00,0,null,null,null,null,'483dc437aaa78718e54f4784c04d1f03baa1ee2e2cae2fd75e42d3309563529edd7e2ebe9342923a909977865dd3b24456d033db77d241c98639d2058a24c8feOE6Iq3Uk0o0gA8yOxfaUUsk57RN95NjVXcBHxaEcxHk=',4,null,0,null,1,0,null,'2020-04-22',1,200519200124),(4,'e130f3be78af5b49f263a5c334ccc1228e746216e6cf89a2fd61c4e194bbb49f1ee798227202fa5c5454b5b8ed365d06f4db167aa1898b72f34afd7b7cab03f6o/Yl93+P727oR9cFwQYWYgI82Q+/HHCNnILJ5yugSEk=','Saepe nemo dolores e','Impedit consectetur','Natus',8278569425,'zytuqitase@mailinator.net','Voluptatum ipsam cil',0.00,0,null,null,null,null,'63d1dd1f91cd5c4cf5392060f023cc4bad7223853f8109a942802b9570b3f9252423b8962b3fb8ce1d12c9e2a9d1ecd07f0cc1d7d8e940f29251a6a361b9a66fsfIHpVhmesJydmvwu1kvVUT1lKkuwZrkxUgvNumCVz7qmnjI4KYjyhP3YDfG0uQW',4,'Aspernatur nesciunt',0,null,1,0,null,'2001-07-05',0,030109181565);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `affiliatehistory`;:||:Separator:||:


CREATE TABLE `affiliatehistory` (
  `idAffiliateHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idAffiliate` int(11) DEFAULT NULL,
  `affiliateName` text,
  `tagLine` text,
  `address` text,
  `contactPerson` char(50) DEFAULT NULL,
  `contactNumber` char(20) DEFAULT NULL,
  `email` char(50) DEFAULT NULL,
  `tin` char(20) DEFAULT NULL,
  `vatPercent` decimal(18,2) DEFAULT '0.00',
  `vatType` int(1) DEFAULT '1',
  `checkedBy` char(50) DEFAULT NULL,
  `reviewedBy` char(50) DEFAULT NULL,
  `approvedBy1` int(11) DEFAULT NULL,
  `approvedBy2` int(11) DEFAULT NULL,
  `accSchedule` text,
  `month` int(2) DEFAULT '1',
  `remarks` text,
  `refTag` int(1) DEFAULT NULL,
  `logo` text,
  `status` int(1) DEFAULT '0',
  `mainTag` int(1) DEFAULT '0',
  `location` int(11) DEFAULT NULL,
  `dateStart` date DEFAULT NULL,
  PRIMARY KEY (`idAffiliateHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `affiliatehistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `alocations`;:||:Separator:||:


CREATE TABLE `alocations` (
  `idAlocations` int(11) NOT NULL AUTO_INCREMENT,
  `idEu` int(11) DEFAULT NULL,
  `idLocation` int(11) DEFAULT NULL,
  PRIMARY KEY (`idAlocations`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `alocations` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `amodule`;:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=1809 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `amodule` WRITE;:||:Separator:||:
 INSERT INTO `amodule` VALUES(30,1,59,0,1,1,1,1,0,0),(31,2,59,0,1,1,1,1,0,0),(32,3,59,5,1,1,1,1,0,0),(33,4,59,5,1,1,1,1,0,0),(34,5,59,5,1,1,1,1,0,0),(35,6,59,0,1,1,1,1,0,0),(36,7,59,5,1,1,1,1,0,0),(37,8,59,4,1,1,1,1,0,0),(38,9,59,5,1,1,1,1,0,0),(39,4,10,5,1,1,1,1,0,0),(40,7,10,5,1,1,1,1,0,0),(41,5,10,5,1,1,1,1,0,0),(42,6,10,5,1,1,1,1,0,0),(43,9,10,5,1,1,1,1,0,0),(44,3,10,5,1,1,1,1,0,0),(45,2,10,1,1,1,1,1,0,0),(46,8,10,4,1,1,1,1,0,0),(82,1,30,0,0,0,0,0,0,0),(118,8,29,4,1,1,1,1,0,0),(129,4,32,5,1,1,1,1,0,0),(130,7,32,5,1,1,1,1,0,0),(131,5,32,5,1,1,1,1,0,0),(132,6,32,5,1,1,1,1,0,0),(133,9,32,5,1,1,1,1,0,0),(134,3,32,5,1,1,1,1,0,0),(155,10,30,4,1,1,1,1,0,0),(156,11,30,4,1,1,1,1,0,0),(157,13,30,4,1,1,1,1,0,0),(158,8,30,4,1,1,1,1,0,0),(159,12,30,4,1,1,1,1,0,0),(164,14,9,1,1,1,1,1,0,0),(165,16,9,1,1,1,1,1,0,0),(166,2,9,1,1,1,1,1,0,0),(167,15,9,1,1,1,1,1,0,0),(173,10,1,4,1,1,1,1,0,0),(174,11,1,4,1,1,1,1,0,0),(175,13,1,4,1,1,1,1,0,0),(176,8,1,4,1,1,1,1,0,0),(177,12,1,4,1,1,1,1,0,0),(182,1,34,0,1,1,1,1,0,0),(183,10,34,4,1,1,1,1,0,0),(184,11,34,4,1,1,1,1,0,0),(185,13,34,4,1,1,1,1,0,0),(186,8,34,4,1,1,1,1,0,0),(187,12,34,4,1,1,1,1,0,0),(188,4,34,5,1,1,1,1,0,0),(189,7,34,5,1,1,1,1,0,0),(190,5,34,5,1,1,1,1,0,0),(191,6,34,5,1,1,1,1,0,0),(192,9,34,5,1,1,1,1,0,0),(193,3,34,5,1,1,1,1,0,0),(222,1,31,0,1,1,1,1,0,0),(228,4,31,5,1,1,1,1,0,0),(229,7,31,5,1,1,1,1,0,0),(230,5,31,5,1,1,1,1,0,0),(231,6,31,5,1,1,1,1,0,0),(232,9,31,5,1,1,1,1,0,0),(233,3,31,5,1,1,1,1,0,0),(240,14,36,1,1,1,1,1,0,0),(241,16,36,1,1,1,1,1,0,0),(242,2,36,1,1,1,1,1,0,0),(243,18,36,1,1,1,1,1,0,0),(244,17,36,1,1,1,1,1,0,0),(245,15,36,1,1,1,1,1,0,0),(246,10,36,4,1,1,1,1,0,0),(247,11,36,4,1,1,1,1,0,0),(248,13,36,4,1,1,1,1,0,0),(249,8,36,4,1,1,1,1,0,0),(250,12,36,4,1,1,1,1,0,0),(251,4,36,5,1,1,1,1,0,0),(252,7,36,5,1,1,1,1,0,0),(253,5,36,5,1,1,1,1,0,0),(254,6,36,5,1,1,1,1,0,0),(255,9,36,5,1,1,1,1,0,0),(256,3,36,5,1,1,1,1,0,0),(257,10,31,4,1,1,1,1,0,0),(258,11,31,4,1,1,1,1,0,0),(259,13,31,4,1,1,1,1,0,0),(260,8,31,4,1,1,1,1,0,0),(261,12,31,4,1,1,1,1,0,0),(262,18,29,1,1,1,1,1,0,0),(614,20,30,2,1,1,1,1,0,0),(615,32,30,2,1,1,1,1,0,0),(616,44,30,2,1,1,1,1,0,0),(617,28,30,2,1,1,1,1,0,0),(618,31,30,2,1,1,1,1,0,0),(619,19,30,2,1,1,1,1,0,0),(620,42,30,2,1,1,1,1,0,0),(621,35,30,2,1,1,1,1,0,0),(622,36,30,2,1,1,1,1,0,0),(623,37,30,2,1,1,1,1,0,0),(624,38,30,2,1,1,1,1,0,0),(625,40,30,2,1,1,1,1,0,0),(626,4,30,5,1,1,1,1,0,0),(627,7,30,5,1,1,1,1,0,0),(628,5,30,5,1,1,1,1,0,0),(629,6,30,5,1,1,1,1,0,0),(630,9,30,5,1,1,1,1,0,0),(631,3,30,5,1,1,1,1,0,0),(729,23,31,1,1,1,1,1,0,0),(730,14,31,1,1,1,1,1,0,0),(731,41,31,1,1,1,1,1,0,0),(732,22,31,1,1,1,1,1,0,0),(733,16,31,1,1,1,1,1,0,0),(734,33,31,1,1,1,1,1,0,0),(735,30,31,1,1,1,1,1,0,0),(736,2,31,1,1,1,1,1,0,0),(737,29,31,1,1,1,1,1,0,0),(738,39,31,1,1,1,1,1,0,0),(739,25,31,1,1,1,1,1,0,0),(740,34,31,1,1,1,1,1,0,0),(741,18,31,1,1,1,1,1,0,0),(742,17,31,1,1,1,1,1,0,0),(743,27,31,1,1,1,1,1,0,0),(744,21,31,1,1,1,1,1,0,0),(745,26,31,1,1,1,1,1,0,0),(746,24,31,1,1,1,1,1,0,0),(747,43,31,1,1,1,1,1,0,0),(748,15,31,1,1,1,1,1,0,0),(761,32,31,2,1,1,1,1,0,0),(762,20,31,2,1,1,1,1,0,0),(763,44,31,2,1,1,1,1,0,0),(764,28,31,2,1,1,1,1,0,0),(765,19,31,2,1,1,1,1,0,0),(766,31,31,2,1,1,1,1,0,0),(767,42,31,2,1,1,1,1,0,0),(768,35,31,2,1,1,1,1,0,0),(769,36,31,2,1,1,1,1,0,0),(770,37,31,2,1,1,1,1,0,0),(771,38,31,2,1,1,1,1,0,0),(772,40,31,2,1,1,1,1,0,0),(793,1,37,0,1,1,1,1,0,0),(807,10,37,4,1,1,1,1,0,0),(808,11,37,4,1,1,1,1,0,0),(809,8,37,4,1,1,1,1,0,0),(810,12,37,4,1,1,1,1,0,0),(811,4,37,5,1,1,1,1,0,0),(812,7,37,5,1,1,1,1,0,0),(813,5,37,5,1,1,1,1,0,0),(814,6,37,5,1,1,1,1,0,0),(815,9,37,5,1,1,1,1,0,0),(816,3,37,5,1,1,1,1,0,0),(817,32,37,2,1,1,1,1,0,0),(818,20,37,2,1,1,1,1,0,0),(819,44,37,2,1,1,1,1,0,0),(820,28,37,2,1,1,1,1,0,0),(821,19,37,2,1,1,1,1,0,0),(822,31,37,2,1,1,1,1,0,0),(823,42,37,2,1,1,1,1,0,0),(824,35,37,2,1,1,1,1,0,0),(825,36,37,2,1,1,1,1,0,0),(826,37,37,2,1,1,1,1,0,0),(827,38,37,2,1,1,1,1,0,0),(828,40,37,2,1,1,1,1,0,0),(906,1,1,0,1,1,1,1,0,0),(948,18,38,1,1,1,1,1,0,0),(949,17,38,1,1,1,1,1,0,0),(950,21,38,1,1,1,1,1,0,0),(951,26,38,1,1,1,1,1,0,0),(952,24,38,1,1,1,1,1,0,0),(953,27,38,1,1,1,1,1,0,0),(1157,51,40,1,1,1,1,1,0,0),(1158,14,40,1,1,1,1,1,0,0),(1159,53,40,1,1,1,1,1,0,0),(1160,41,40,1,1,1,1,1,0,0),(1161,23,40,1,1,1,1,1,0,0),(1162,22,40,1,1,1,1,1,0,0),(1163,16,40,1,1,1,1,1,0,0),(1164,47,40,1,1,1,1,1,0,0),(1165,33,40,1,1,1,1,1,0,0),(1166,49,40,1,1,1,1,1,0,0),(1167,30,40,1,1,1,1,1,0,0),(1168,2,40,1,1,1,1,1,0,0),(1169,29,40,1,1,1,1,1,0,0),(1170,39,40,1,1,1,1,1,0,0),(1171,46,40,1,1,1,1,1,0,0),(1172,54,40,1,1,1,1,1,0,0),(1173,25,40,1,1,1,1,1,0,0),(1174,34,40,1,1,1,1,1,0,0),(1175,52,40,1,1,1,1,1,0,0),(1176,18,40,1,1,1,1,1,0,0),(1177,17,40,1,1,1,1,1,0,0),(1178,21,40,1,1,1,1,1,0,0),(1179,26,40,1,1,1,1,1,0,0),(1180,24,40,1,1,1,1,1,0,0),(1181,27,40,1,1,1,1,1,0,0),(1182,43,40,1,1,1,1,1,0,0),(1183,15,40,1,1,1,1,1,0,0),(1184,48,40,2,1,1,1,1,0,0),(1185,20,40,2,1,1,1,1,0,0),(1186,44,40,2,1,1,1,1,0,0),(1187,28,40,2,1,1,1,1,0,0),(1188,19,40,2,1,1,1,1,0,0),(1189,42,40,2,1,1,1,1,0,0),(1190,35,40,2,1,1,1,1,0,0),(1191,36,40,2,1,1,1,1,0,0),(1192,37,40,2,1,1,1,1,0,0),(1193,45,40,2,1,1,1,1,0,0),(1194,38,40,2,1,1,1,1,0,0),(1195,40,40,2,1,1,1,1,0,0),(1196,57,40,2,1,1,1,1,0,0),(1197,55,40,3,1,1,1,1,0,0),(1198,56,40,3,1,1,1,1,0,0),(1199,50,40,3,1,1,1,1,0,0),(1200,10,40,4,1,1,1,1,0,0),(1201,11,40,4,1,1,1,1,0,0),(1202,8,40,4,1,1,1,1,0,0),(1203,12,40,4,1,1,1,1,0,0),(1204,4,40,5,1,1,1,1,0,0),(1205,7,40,5,1,1,1,1,0,0),(1206,5,40,5,1,1,1,1,0,0),(1207,6,40,5,1,1,1,1,0,0),(1208,9,40,5,1,1,1,1,0,0),(1209,3,40,5,1,1,1,1,0,0),(1211,1,40,0,1,1,1,1,0,0),(1212,51,21,1,1,1,1,1,0,0),(1213,14,21,1,1,1,1,1,0,0),(1214,53,21,1,1,1,1,1,0,0),(1215,41,21,1,1,1,1,1,0,0),(1216,23,21,1,1,1,1,1,0,0),(1217,22,21,1,1,1,1,1,0,0),(1218,16,21,1,1,1,1,1,0,0),(1219,47,21,1,1,1,1,1,0,0),(1220,33,21,1,1,1,1,1,0,0),(1221,49,21,1,1,1,1,1,0,0),(1222,30,21,1,1,1,1,1,0,0),(1223,2,21,1,1,1,1,1,0,0),(1224,29,21,1,1,1,1,1,0,0),(1225,39,21,1,1,1,1,1,0,0),(1226,46,21,1,1,1,1,1,0,0),(1227,54,21,1,1,1,1,1,0,0),(1228,25,21,1,1,1,1,1,0,0),(1229,34,21,1,1,1,1,1,0,0),(1230,52,21,1,1,1,1,1,0,0),(1231,18,21,1,1,1,1,1,0,0),(1232,17,21,1,1,1,1,1,0,0),(1233,21,21,1,1,1,1,1,0,0),(1234,26,21,1,1,1,1,1,0,0),(1235,24,21,1,1,1,1,1,0,0),(1236,27,21,1,1,1,1,1,0,0),(1237,43,21,1,1,1,1,1,0,0),(1238,15,21,1,1,1,1,1,0,0),(1239,51,24,1,1,1,1,1,0,0),(1240,14,24,1,1,1,1,1,0,0),(1241,53,24,1,1,1,1,1,0,0),(1242,41,24,1,1,1,1,1,0,0),(1243,23,24,1,1,1,1,1,0,0),(1244,22,24,1,1,1,1,1,0,0),(1245,16,24,1,1,1,1,1,0,0),(1246,47,24,1,1,1,1,1,0,0),(1247,33,24,1,1,1,1,1,0,0),(1248,49,24,1,1,1,1,1,0,0),(1249,30,24,1,1,1,1,1,0,0),(1250,2,24,1,1,1,1,1,0,0),(1251,29,24,1,1,1,1,1,0,0),(1252,39,24,1,1,1,1,1,0,0),(1253,46,24,1,1,1,1,1,0,0),(1254,54,24,1,1,1,1,1,0,0),(1255,25,24,1,1,1,1,1,0,0),(1256,34,24,1,1,1,1,1,0,0),(1257,52,24,1,1,1,1,1,0,0),(1258,18,24,1,1,1,1,1,0,0),(1259,17,24,1,1,1,1,1,0,0),(1260,21,24,1,1,1,1,1,0,0),(1261,26,24,1,1,1,1,1,0,0),(1262,24,24,1,1,1,1,1,0,0),(1263,27,24,1,1,1,1,1,0,0),(1264,43,24,1,1,1,1,1,0,0),(1265,15,24,1,1,1,1,1,0,0),(1266,51,1,1,1,1,1,1,0,0),(1267,14,1,1,1,1,1,1,0,0),(1268,53,1,1,1,1,1,1,0,0),(1269,41,1,1,1,1,1,1,0,0),(1270,23,1,1,1,1,1,1,0,0),(1271,22,1,1,1,1,1,1,0,0),(1272,16,1,1,1,1,1,1,0,0),(1273,47,1,1,1,1,1,1,0,0),(1274,33,1,1,1,1,1,1,0,0),(1275,49,1,1,1,1,1,1,0,0),(1276,30,1,1,1,1,1,1,0,0),(1277,2,1,1,1,1,1,1,0,0),(1278,2,1,1,1,1,1,1,0,0),(1279,29,1,1,1,1,1,1,0,0),(1280,39,1,1,1,1,1,1,0,0),(1281,46,1,1,1,1,1,1,0,0),(1282,54,1,1,1,1,1,1,0,0),(1283,25,1,1,1,1,1,1,0,0),(1284,34,1,1,1,1,1,1,0,0),(1285,52,1,1,1,1,1,1,0,0),(1286,18,1,1,1,1,1,1,0,0),(1287,17,1,1,1,1,1,1,0,0),(1288,21,1,1,1,1,1,1,0,0),(1289,26,1,1,1,1,1,1,0,0),(1290,24,1,1,1,1,1,1,0,0),(1291,27,1,1,1,1,1,1,0,0),(1292,43,1,1,1,1,1,1,0,0),(1293,15,1,1,1,1,1,1,0,0),(1294,48,1,2,1,1,1,1,0,0),(1295,20,1,2,1,1,1,1,0,0),(1296,44,1,2,1,1,1,1,0,0),(1297,28,1,2,1,1,1,1,0,0),(1298,19,1,2,1,1,1,1,0,0),(1299,42,1,2,1,1,1,1,0,0),(1300,35,1,2,1,1,1,1,0,0),(1301,36,1,2,1,1,1,1,0,0),(1302,37,1,2,1,1,1,1,0,0),(1303,45,1,2,1,1,1,1,0,0),(1304,38,1,2,1,1,1,1,0,0),(1305,40,1,2,1,1,1,1,0,0),(1306,57,1,2,1,1,1,1,0,0),(1307,55,1,3,1,1,1,1,0,0),(1308,56,1,3,1,1,1,1,0,0),(1309,50,1,3,1,1,1,1,0,0),(1310,4,1,5,1,1,1,1,0,0),(1311,7,1,5,1,1,1,1,0,0),(1312,5,1,5,1,1,1,1,0,0),(1313,6,1,5,1,1,1,1,0,0),(1314,9,1,5,1,1,1,1,0,0),(1315,3,1,5,1,1,1,1,0,0),(1478,55,34,3,1,1,1,1,0,0),(1479,56,34,3,1,1,1,1,0,0),(1480,64,34,3,1,1,1,1,0,0),(1481,65,34,3,1,1,1,1,0,0),(1482,50,34,3,1,1,1,1,0,0),(1483,60,34,3,1,1,1,1,0,0),(1501,51,41,1,1,1,1,1,0,0),(1502,14,41,1,1,1,1,1,0,0),(1503,53,41,1,1,1,1,1,0,0),(1504,41,41,1,1,1,1,1,0,0),(1505,23,41,1,1,1,1,1,0,0),(1506,61,41,1,1,1,1,1,0,0),(1507,22,41,1,1,1,1,1,0,0),(1508,59,41,1,1,1,1,1,0,0),(1509,16,41,1,1,1,1,1,0,0),(1510,47,41,1,1,1,1,1,0,0),(1511,33,41,1,1,1,1,1,0,0),(1512,49,41,1,1,1,1,1,0,0),(1513,30,41,1,1,1,1,1,0,0),(1514,2,41,1,1,1,1,1,0,0),(1515,29,41,1,1,1,1,1,0,0),(1516,39,41,1,1,1,1,1,0,0),(1517,46,41,1,1,1,1,1,0,0),(1518,54,41,1,1,1,1,1,0,0),(1519,25,41,1,1,1,1,1,0,0),(1520,34,41,1,1,1,1,1,0,0),(1521,52,41,1,1,1,1,1,0,0),(1522,18,41,1,1,1,1,1,0,0),(1523,17,41,1,1,1,1,1,0,0),(1524,21,41,1,1,1,1,1,0,0),(1525,26,41,1,1,1,1,1,0,0),(1526,24,41,1,1,1,1,1,0,0),(1527,27,41,1,1,1,1,1,0,0),(1528,43,41,1,1,1,1,1,0,0),(1529,15,41,1,1,1,1,1,0,0),(1530,1,41,0,1,1,1,1,0,0),(1531,48,41,2,1,1,1,1,0,0),(1532,66,41,2,1,1,1,1,0,0),(1533,20,41,2,1,1,1,1,0,0),(1534,63,41,2,1,1,1,1,0,0),(1535,44,41,2,1,1,1,1,0,0),(1536,62,41,2,1,1,1,1,0,0),(1537,28,41,2,1,1,1,1,0,0),(1538,19,41,2,1,1,1,1,0,0),(1539,42,41,2,1,1,1,1,0,0),(1540,35,41,2,1,1,1,1,0,0),(1541,36,41,2,1,1,1,1,0,0),(1542,37,41,2,1,1,1,1,0,0),(1543,45,41,2,1,1,1,1,0,0),(1544,38,41,2,1,1,1,1,0,0),(1545,40,41,2,1,1,1,1,0,0),(1546,57,41,2,1,1,1,1,0,0),(1547,58,41,2,1,1,1,1,0,0),(1548,55,41,3,1,1,1,1,0,0),(1549,56,41,3,1,1,1,1,0,0),(1550,64,41,3,1,1,1,1,0,0),(1551,65,41,3,1,1,1,1,0,0),(1552,50,41,3,1,1,1,1,0,0),(1553,60,41,3,1,1,1,1,0,0),(1554,10,41,4,1,1,1,1,0,0),(1555,11,41,4,1,1,1,1,0,0),(1556,8,41,4,1,1,1,1,0,0),(1557,12,41,4,1,1,1,1,0,0),(1558,4,41,5,1,1,1,1,0,0),(1559,7,41,5,1,1,1,1,0,0),(1560,5,41,5,1,1,1,1,0,0),(1561,6,41,5,1,1,1,1,0,0),(1562,9,41,5,1,1,1,1,0,0),(1563,3,41,5,1,1,1,1,0,0),(1594,51,34,1,1,1,1,1,0,0),(1595,67,34,1,1,1,1,1,0,0),(1596,14,34,1,1,1,1,1,0,0),(1597,53,34,1,1,1,1,1,0,0),(1598,41,34,1,1,1,1,1,0,0),(1599,23,34,1,1,1,1,1,0,0),(1600,61,34,1,1,1,1,1,0,0),(1601,22,34,1,1,1,1,1,0,0),(1602,59,34,1,1,1,1,1,0,0),(1603,16,34,1,1,1,1,1,0,0),(1604,47,34,1,1,1,1,1,0,0),(1605,33,34,1,1,1,1,1,0,0),(1606,49,34,1,1,1,1,1,0,0),(1607,30,34,1,1,1,1,1,0,0),(1608,2,34,1,1,1,1,1,0,0),(1609,29,34,1,1,1,1,1,0,0),(1610,39,34,1,1,1,1,1,0,0),(1611,46,34,1,1,1,1,1,0,0),(1612,54,34,1,1,1,1,1,0,0),(1613,25,34,1,1,1,1,1,0,0),(1614,34,34,1,1,1,1,1,0,0),(1615,52,34,1,1,1,1,1,0,0),(1616,18,34,1,1,1,1,1,0,0),(1617,17,34,1,1,1,1,1,0,0),(1618,21,34,1,1,1,1,1,0,0),(1619,26,34,1,1,1,1,1,0,0),(1620,24,34,1,1,1,1,1,0,0),(1621,27,34,1,1,1,1,1,0,0),(1622,43,34,1,1,1,1,1,0,0),(1623,15,34,1,1,1,1,1,0,0),(1624,51,37,1,1,1,1,1,0,0),(1625,67,37,1,1,1,1,1,0,0),(1626,14,37,1,1,1,1,1,0,0),(1627,53,37,1,1,1,1,1,0,0),(1628,41,37,1,1,1,1,1,0,0),(1629,23,37,1,1,1,1,1,0,0),(1630,61,37,1,1,1,1,1,0,0),(1631,22,37,1,1,1,1,1,0,0),(1632,59,37,1,1,1,1,1,0,0),(1633,16,37,1,1,1,1,1,0,0),(1634,47,37,1,1,1,1,1,0,0),(1635,33,37,1,1,1,1,1,0,0),(1636,49,37,1,1,1,1,1,0,0),(1637,30,37,1,1,1,1,1,0,0),(1638,2,37,1,1,1,1,1,0,0),(1639,29,37,1,1,1,1,1,0,0),(1640,39,37,1,1,1,1,1,0,0),(1641,46,37,1,1,1,1,1,0,0),(1642,54,37,1,1,1,1,1,0,0),(1643,25,37,1,1,1,1,1,0,0),(1644,34,37,1,1,1,1,1,0,0),(1645,52,37,1,1,1,1,1,0,0),(1646,18,37,1,1,1,1,1,0,0),(1647,17,37,1,1,1,1,1,0,0),(1648,21,37,1,1,1,1,1,0,0),(1649,26,37,1,1,1,1,1,0,0),(1650,24,37,1,1,1,1,1,0,0),(1651,27,37,1,1,1,1,1,0,0),(1652,43,37,1,1,1,1,1,0,0),(1653,15,37,1,1,1,1,1,0,0),(1654,55,37,3,1,1,1,1,0,0),(1655,56,37,3,1,1,1,1,0,0),(1656,64,37,3,1,1,1,1,0,0),(1657,65,37,3,1,1,1,1,0,0),(1658,50,37,3,1,1,1,1,0,0),(1659,60,37,3,1,1,1,1,0,0),(1660,51,42,1,1,1,1,1,0,0),(1661,67,42,1,1,1,1,1,0,0),(1662,14,42,1,1,1,1,1,0,0),(1663,53,42,1,1,1,1,1,0,0),(1664,41,42,1,1,1,1,1,0,0),(1665,23,42,1,1,1,1,1,0,0),(1666,61,42,1,1,1,1,1,0,0),(1667,22,42,1,1,1,1,1,0,0),(1668,59,42,1,1,1,1,1,0,0),(1669,16,42,1,1,1,1,1,0,0),(1670,47,42,1,1,1,1,1,0,0),(1671,33,42,1,1,1,1,1,0,0),(1672,49,42,1,1,1,1,1,0,0),(1673,30,42,1,1,1,1,1,0,0),(1674,2,42,1,1,1,1,1,0,0),(1675,29,42,1,1,1,1,1,0,0),(1676,39,42,1,1,1,1,1,0,0),(1677,46,42,1,1,1,1,1,0,0),(1678,54,42,1,1,1,1,1,0,0),(1679,25,42,1,1,1,1,1,0,0),(1680,34,42,1,1,1,1,1,0,0),(1681,52,42,1,1,1,1,1,0,0),(1682,18,42,1,1,1,1,1,0,0),(1683,17,42,1,1,1,1,1,0,0),(1684,21,42,1,1,1,1,1,0,0),(1685,26,42,1,1,1,1,1,0,0),(1686,24,42,1,1,1,1,1,0,0),(1687,27,42,1,1,1,1,1,0,0),(1688,43,42,1,1,1,1,1,0,0),(1689,15,42,1,1,1,1,1,0,0),(1690,1,42,0,1,1,1,1,0,0),(1691,48,42,2,1,1,1,1,0,0),(1692,66,42,2,1,1,1,1,0,0),(1693,20,42,2,1,1,1,1,0,0),(1694,63,42,2,1,1,1,1,0,0),(1695,44,42,2,1,1,1,1,0,0),(1696,62,42,2,1,1,1,1,0,0),(1697,28,42,2,1,1,1,1,0,0),(1698,19,42,2,1,1,1,1,0,0),(1699,42,42,2,1,1,1,1,0,0),(1700,35,42,2,1,1,1,1,0,0),(1701,36,42,2,1,1,1,1,0,0),(1702,37,42,2,1,1,1,1,0,0),(1703,45,42,2,1,1,1,1,0,0),(1704,38,42,2,1,1,1,1,0,0),(1705,40,42,2,1,1,1,1,0,0),(1706,57,42,2,1,1,1,1,0,0),(1707,58,42,2,1,1,1,1,0,0),(1708,55,42,3,1,1,1,1,0,0),(1709,56,42,3,1,1,1,1,0,0),(1710,64,42,3,1,1,1,1,0,0),(1711,65,42,3,1,1,1,1,0,0),(1712,50,42,3,1,1,1,1,0,0),(1713,60,42,3,1,1,1,1,0,0),(1714,10,42,4,1,1,1,1,0,0),(1715,11,42,4,1,1,1,1,0,0),(1716,8,42,4,1,1,1,1,0,0),(1717,12,42,4,1,1,1,1,0,0),(1718,51,43,1,1,1,1,1,0,0),(1719,67,43,1,1,1,1,1,0,0),(1720,14,43,1,1,1,1,1,0,0),(1721,53,43,1,1,1,1,1,0,0),(1722,41,43,1,1,1,1,1,0,0),(1723,23,43,1,1,1,1,1,0,0),(1724,61,43,1,1,1,1,1,0,0),(1725,22,43,1,1,1,1,1,0,0),(1726,59,43,1,1,1,1,1,0,0),(1727,16,43,1,1,1,1,1,0,0),(1728,47,43,1,1,1,1,1,0,0),(1729,33,43,1,1,1,1,1,0,0),(1730,49,43,1,1,1,1,1,0,0),(1731,30,43,1,1,1,1,1,0,0),(1732,2,43,1,1,1,1,1,0,0),(1733,29,43,1,1,1,1,1,0,0),(1734,39,43,1,1,1,1,1,0,0),(1735,46,43,1,1,1,1,1,0,0),(1736,54,43,1,1,1,1,1,0,0),(1737,25,43,1,1,1,1,1,0,0),(1738,34,43,1,1,1,1,1,0,0),(1739,52,43,1,1,1,1,1,0,0),(1740,18,43,1,1,1,1,1,0,0),(1741,17,43,1,1,1,1,1,0,0),(1742,21,43,1,1,1,1,1,0,0),(1743,26,43,1,1,1,1,1,0,0),(1744,24,43,1,1,1,1,1,0,0),(1745,27,43,1,1,1,1,1,0,0),(1746,43,43,1,1,1,1,1,0,0),(1747,15,43,1,1,1,1,1,0,0),(1748,4,44,5,1,1,1,1,0,0),(1749,7,44,5,1,1,1,1,0,0),(1750,5,44,5,1,1,1,1,0,0),(1751,6,44,5,1,1,1,1,0,0),(1752,3,44,5,1,1,1,1,0,0),(1753,9,44,5,1,1,1,1,0,0),(1754,55,30,3,1,1,1,1,0,0),(1755,56,30,3,1,1,1,1,0,0),(1756,64,30,3,1,1,1,1,0,0),(1757,65,30,3,1,1,1,1,0,0),(1758,50,30,3,1,1,1,1,0,0),(1759,60,30,3,1,1,1,1,0,0),(1760,51,30,1,1,1,1,1,0,0),(1761,67,30,1,1,1,1,1,0,0),(1762,14,30,1,1,1,1,1,0,0),(1763,53,30,1,1,1,1,1,0,0),(1764,41,30,1,1,1,1,1,0,0),(1765,23,30,1,1,1,1,1,0,0),(1766,61,30,1,1,1,1,1,0,0),(1767,22,30,1,1,1,1,1,0,0),(1768,59,30,1,1,1,1,1,0,0),(1769,16,30,1,1,1,1,1,0,0),(1770,47,30,1,1,1,1,1,0,0),(1771,33,30,1,1,1,1,1,0,0),(1772,49,30,1,1,1,1,1,0,0),(1773,30,30,1,1,1,1,1,0,0),(1774,2,30,1,1,1,1,1,0,0),(1775,29,30,1,1,1,1,1,0,0),(1776,39,30,1,1,1,1,1,0,0),(1777,46,30,1,1,1,1,1,0,0),(1778,54,30,1,1,1,1,1,0,0),(1779,25,30,1,1,1,1,1,0,0),(1780,34,30,1,1,1,1,1,0,0),(1781,52,30,1,1,1,1,1,0,0),(1782,18,30,1,1,1,1,1,0,0),(1783,17,30,1,1,1,1,1,0,0),(1784,21,30,1,1,1,1,1,0,0),(1785,26,30,1,1,1,1,1,0,0),(1786,24,30,1,1,1,1,1,0,0),(1787,27,30,1,1,1,1,1,0,0),(1788,43,30,1,1,1,1,1,0,0),(1789,15,30,1,1,1,1,1,0,0),(1790,48,34,2,1,1,1,1,0,0),(1791,66,34,2,1,1,1,1,0,0),(1792,20,34,2,1,1,1,1,0,0),(1793,63,34,2,1,1,1,1,0,0),(1794,44,34,2,1,1,1,1,0,0),(1795,62,34,2,1,1,1,1,0,0),(1796,28,34,2,1,1,1,1,0,0),(1797,19,34,2,1,1,1,1,0,0),(1798,42,34,2,1,1,1,1,0,0),(1799,35,34,2,1,1,1,1,0,0),(1800,36,34,2,1,1,1,1,0,0),(1801,37,34,2,1,1,1,1,0,0),(1802,45,34,2,1,1,1,1,0,0),(1803,38,34,2,1,1,1,1,0,0),(1804,40,34,2,1,1,1,1,0,0),(1805,69,34,2,1,1,1,1,0,0),(1806,68,34,2,1,1,1,1,0,0),(1807,57,34,2,1,1,1,1,0,0),(1808,58,34,2,1,1,1,1,0,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `autobackup`;:||:Separator:||:


CREATE TABLE `autobackup` (
  `idAB` int(11) NOT NULL AUTO_INCREMENT,
  `abType` int(1) DEFAULT '3' COMMENT '1 - Daily\n2 - Weekly\n3 - Monthly',
  `abWeek` int(1) DEFAULT '1' COMMENT '1 - Week 1\n2 - Week 2\n3 - Week 3\n4 - Week 4',
  `abDay` int(1) DEFAULT '1' COMMENT '1 - Sunday\n2 - Monday\n3 - Tuesday\n4 - Wednesday\n5 - Thursday\n6 - Friday\n7 - Saturday',
  `abTime` time DEFAULT NULL,
  `latestBackupDate` date DEFAULT NULL,
  PRIMARY KEY (`idAB`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `autobackup` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `backuphistory`;:||:Separator:||:


CREATE TABLE `backuphistory` (
  `idBHistory` int(11) NOT NULL AUTO_INCREMENT,
  `bhDate` date DEFAULT NULL,
  `bhTime` time DEFAULT NULL,
  `bhFile` text,
  `bhUser` int(11) DEFAULT NULL,
  PRIMARY KEY (`idBHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `backuphistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `bank`;:||:Separator:||:


CREATE TABLE `bank` (
  `idBank` int(11) NOT NULL AUTO_INCREMENT,
  `bankName` text,
  `archived` int(1) DEFAULT '0',
  `sk` text,
  PRIMARY KEY (`idBank`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `bank` WRITE;:||:Separator:||:
 INSERT INTO `bank` VALUES(1,'2ad5345191c562bcf6266f9dbc55e098e2f18d12527c963a67a2e304cb9c84947472d479da82e6d0e1fe61c0c503d8564c616e4812dc656830ed77c4ed2e226dKoqMppO+ef8+xdcGd6xAiJuOPpY3vhCGGz0aubyELWA=',0,021609010186),(2,'c01fbd6be71da0f42333c5baedb64a64af4c663f862afc2ae8fb0f0e72e60bfd82ed3af1602f2be09f1d0a10ecb383615827d9eb348ddd781d2b5521c3a97363bYQQ8dOP8riPf7oMqKm1OPHMeyV/U93Rj42EofGT3Ec=',0,161402010151),(3,'cdb134fe195fb9b1e9262b098857cb306584db49cadd055846a94d40a1915df067f07475b00400198f496d7d96c6462d51c18f7a2a723927898d097f14097f03yRyBn+6CWQ9415REbNv4zSJmaz33tiM/ohQamIxRBAA=',0,120114040064),(4,'520b718d2f67b9dd2c7fb2121f9c701a4ece66985c73fcebc7312174b4ec650bcc20fe62e81b35153124b14b1b3ac7f81e7012ea6f49ff485e9ef9629bb2f6c95puRtxHRl2wKj9NYipBgkhXJavhFROBo465YTYBl7QE=',0,012102010152),(5,'71d218c07559c1e3d48e73b145ca2d8926d4dbceb1dae3f51b3f5f2dd4e1f5285d846089115201109d38990c53039d534b8c862b67202759e463914ffa8432e9F3HqX/BF1+iRNQjZit5RusRL9pUP+AHYBtZMBFActCQ=',0,030809140152);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `bankaccount`;:||:Separator:||:


CREATE TABLE `bankaccount` (
  `idBankAccount` int(11) NOT NULL AUTO_INCREMENT,
  `idAffiliate` int(11) DEFAULT NULL,
  `idBank` int(11) DEFAULT NULL,
  `bankAccount` text,
  `bankAccountNumber` text,
  `begBal` decimal(18,2) DEFAULT '0.00',
  `idCoa` int(11) DEFAULT NULL,
  `remarks` text,
  `archived` int(1) DEFAULT '0',
  `sk` text,
  PRIMARY KEY (`idBankAccount`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `bankaccount` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `bankaccounthistory`;:||:Separator:||:


CREATE TABLE `bankaccounthistory` (
  `idBankAccountHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idBankAccount` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `idBank` int(11) DEFAULT NULL,
  `bankAccount` text,
  `bankAccountNumber` text,
  `begBal` decimal(18,2) DEFAULT '0.00',
  `idCoa` int(11) DEFAULT NULL,
  `remarks` text,
  PRIMARY KEY (`idBankAccountHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `bankaccounthistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `bankhistory`;:||:Separator:||:


CREATE TABLE `bankhistory` (
  `idBankHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idBank` int(11) DEFAULT NULL,
  `bankName` text,
  `bankhistorycol` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idBankHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `bankhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `bankrecon`;:||:Separator:||:


CREATE TABLE `bankrecon` (
  `idBankRecon` int(11) NOT NULL AUTO_INCREMENT,
  `idReference` int(11) DEFAULT NULL,
  `idReferenceSeries` int(11) DEFAULT NULL,
  `reconDate` datetime DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `reconMonth` int(2) DEFAULT NULL COMMENT '1 - January\n2 - February\n3 - March\n4 - April\n5 - May\n6 - June\n7 - July\n8 - August\n9 - September\n10 - October\n11 - November\n12 - December',
  `reconYear` int(4) DEFAULT NULL,
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
  `bankBalNextMonth` decimal(18,2) DEFAULT '0.00',
  `hasJournal` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `preparedBy` int(11) DEFAULT NULL,
  `notedBy` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '1' COMMENT '1 - Pending\n2 - Approved\n3 - Cancelled',
  `archived` int(1) DEFAULT '0',
  `referenceNum` int(255) DEFAULT NULL,
  `cancelTag` int(1) DEFAULT '0',
  `cancelledBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`idBankRecon`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `bankrecon` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `bankreconadjustment`;:||:Separator:||:


CREATE TABLE `bankreconadjustment` (
  `idBankReconAdjusted` int(11) NOT NULL AUTO_INCREMENT,
  `idBankRecon` int(11) DEFAULT NULL,
  `description` text,
  `amount` double DEFAULT NULL,
  `date` date DEFAULT NULL,
  `datestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idBankReconAdjusted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;:||:Separator:||:


LOCK TABLES `bankreconadjustment` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `bankreconadjustmenthistory`;:||:Separator:||:


CREATE TABLE `bankreconadjustmenthistory` (
  `idHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idBankReconAdjusted` int(11) DEFAULT NULL,
  `idBankRecon` int(11) DEFAULT NULL,
  `idBankReconHistory` int(11) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;:||:Separator:||:


LOCK TABLES `bankreconadjustmenthistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `bankreconhistory`;:||:Separator:||:


CREATE TABLE `bankreconhistory` (
  `idBankReconHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idBankRecon` int(11) DEFAULT NULL,
  `idReference` int(11) DEFAULT NULL,
  `idReferenceSeries` int(11) DEFAULT NULL,
  `reconDate` datetime DEFAULT NULL,
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
  `referenceNum` int(255) DEFAULT NULL,
  PRIMARY KEY (`idBankReconHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `bankreconhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `begbal`;:||:Separator:||:


CREATE TABLE `begbal` (
  `idBegBal` int(11) NOT NULL AUTO_INCREMENT,
  `idAccBegBal` int(11) DEFAULT NULL,
  `idCoa` int(11) DEFAULT NULL,
  `begBal` decimal(18,2) DEFAULT '0.00',
  PRIMARY KEY (`idBegBal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `begbal` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `begbalhistory`;:||:Separator:||:


CREATE TABLE `begbalhistory` (
  `idBegBalHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idBegBal` int(11) DEFAULT NULL,
  `idCoa` int(11) DEFAULT NULL,
  `begBal` decimal(18,2) DEFAULT '0.00',
  PRIMARY KEY (`idBegBalHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `begbalhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `coa`;:||:Separator:||:


CREATE TABLE `coa` (
  `idCoa` int(11) NOT NULL AUTO_INCREMENT,
  `accountType` int(1) DEFAULT NULL COMMENT '1 - header | 2 - subsidiary',
  `acod_c15` char(15) DEFAULT NULL,
  `aname_c30` char(100) DEFAULT NULL,
  `mocod_c1` int(1) unsigned zerofill DEFAULT '0' COMMENT '1 - Assets | 2 - Liabilities | 3 - Capital | 4 -Revenue | 5 -Expenses',
  `chcod_c1` int(1) unsigned zerofill DEFAULT '0',
  `accod_c2` int(2) unsigned zerofill DEFAULT '00',
  `sucod_c3` int(3) unsigned zerofill DEFAULT '000',
  `norm_c2` char(2) DEFAULT NULL,
  `accID` int(2) DEFAULT '0',
  `dateModified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `recordedBy` int(11) DEFAULT NULL,
  `cashflow_classification` int(1) DEFAULT '0',
  `archived` int(1) DEFAULT '0',
  PRIMARY KEY (`idCoa`)
) ENGINE=MyISAM AUTO_INCREMENT=5102002 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `coa` WRITE;:||:Separator:||:
 INSERT INTO `coa` VALUES(1101000,1,1101000,'Accounts Receivable',1,1,01,000,'DR',3,'2020-04-22 13:47:58',34,null,0),(4101000,1,4101000,'Revenue Account',4,1,01,000,'CR',1,'2020-04-22 13:08:07',34,null,0),(2101000,1,2101000,'Accounts Payable',2,1,01,000,'CR',12,'2020-04-22 13:47:42',34,null,0),(3101000,1,3101000,'Retained Earnings',3,1,01,000,'CR',26,'2020-04-22 13:47:35',34,null,0),(1102000,1,1102000,'Cash in Bank',1,1,02,000,'DR',2,'2020-04-22 13:47:49',34,null,0),(1103000,1,1103000,'Inventory Account',1,1,03,000,'DR',5,'2020-04-23 13:20:11',34,null,0),(2102000,1,2102000,'Goods Receipt Clearing',2,1,02,000,'CR',17,'2020-04-23 13:24:22',34,null,0),(5101000,1,5101000,'Expense Account',5,1,01,000,'DR',17,'2020-04-23 13:24:57',34,null,0),(4102000,1,4102000,'Sales',4,1,02,000,'CR',14,'2020-04-23 13:27:11',34,null,0),(4102001,2,4102001,'Sales Discount',4,1,02,001,'DR',1,'2020-04-23 13:28:10',34,null,0),(1102001,2,1102001,'Cash In Bank - BPI',1,1,02,001,'DR',2,'2020-04-23 13:28:44',34,null,0),(1101001,2,1101001,'Accounts Receivable - Customer 1',1,1,01,001,'DR',3,'2020-04-23 13:29:15',34,null,0),(5102000,1,5102000,'Business Travel and Transportation',5,1,02,000,'DR',23,'2020-04-23 13:32:12',34,null,0),(5102001,2,5102001,'Employee Expenses',5,1,02,001,'DR',23,'2020-04-23 13:32:40',34,null,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `coaaffiliate`;:||:Separator:||:


CREATE TABLE `coaaffiliate` (
  `idCoaAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `idCoa` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCoaAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `coaaffiliate` WRITE;:||:Separator:||:
 INSERT INTO `coaaffiliate` VALUES(1,4101000,2),(2,3101000,2),(3,2101000,2),(4,1102000,2),(5,1101000,2),(6,1103000,2),(7,2102000,2),(8,5101000,2),(9,4102000,2),(11,4102001,2),(12,1102001,2),(13,1101001,2),(14,5102000,2),(15,5102001,2);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `coaaffiliatehistory`;:||:Separator:||:


CREATE TABLE `coaaffiliatehistory` (
  `idCoaAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `idCoa` int(11) DEFAULT NULL,
  `idCoaHistory` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCoaAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `coaaffiliatehistory` WRITE;:||:Separator:||:
 INSERT INTO `coaaffiliatehistory` VALUES(1,4101000,6,2),(2,3101000,7,2),(3,2101000,8,2),(4,1102000,9,2),(5,1101000,10,2),(6,1103000,11,2),(7,2102000,12,2),(8,5101000,13,2),(9,4102000,14,2),(10,4103000,15,2),(11,4102001,16,2),(12,1102001,17,2),(13,1101001,18,2),(14,5102000,19,2),(15,5102001,20,2);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `coahistory`;:||:Separator:||:


CREATE TABLE `coahistory` (
  `idCoaHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idCoa` int(11) DEFAULT NULL,
  `accountType` int(1) DEFAULT NULL,
  `acod_c15` char(15) DEFAULT NULL,
  `aname_c30` char(50) DEFAULT NULL,
  `mocod_c1` int(1) DEFAULT NULL,
  `chcod_c1` int(1) DEFAULT NULL,
  `accod_c2` int(2) DEFAULT NULL,
  `sucod_c3` int(3) DEFAULT NULL,
  `norm_c2` int(1) DEFAULT NULL,
  `accID` int(2) DEFAULT NULL,
  `recordedBy` int(11) DEFAULT NULL,
  `cashflow_classification` int(1) DEFAULT NULL,
  `dateModified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idCoaHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `coahistory` WRITE;:||:Separator:||:
 INSERT INTO `coahistory` VALUES(1,1101000,1,1101000,'Accounts Receivable',1,1,1,0,1,3,34,null,null),(2,4101000,1,4101000,'Revenue Account',4,1,1,0,2,1,34,null,null),(3,2101000,1,2101000,'Accounts Payable',2,1,1,0,2,12,34,null,null),(4,3101000,1,3101000,'Retained Earnings',3,1,1,0,2,26,34,null,null),(5,1102000,1,1102000,'Cash in Bank',1,1,2,0,1,2,34,null,null),(6,4101000,1,4101000,'Revenue Account',4,1,1,0,2,1,34,null,'2020-04-21 07:59:23'),(7,3101000,1,3101000,'Retained Earnings',3,1,1,0,2,26,34,null,'2020-04-21 08:00:27'),(8,2101000,1,2101000,'Accounts Payable',2,1,1,0,2,12,34,null,'2020-04-21 07:59:43'),(9,1102000,1,1102000,'Cash in Bank',1,1,2,0,1,2,34,null,'2020-04-21 08:00:58'),(10,1101000,1,1101000,'Accounts Receivable',1,1,1,0,1,3,34,null,'2020-04-21 07:57:06'),(11,1103000,1,1103000,'Inventory Account',1,1,3,0,1,5,34,null,null),(12,2102000,1,2102000,'Goods Receipt Clearing',2,1,2,0,2,17,34,null,null),(13,5101000,1,5101000,'Expense Account',5,1,1,0,1,17,34,null,null),(14,4102000,1,4102000,'Sales',4,1,2,0,2,14,34,null,null),(15,4103000,1,4103000,'Sales Discount',4,1,3,0,1,1,34,null,null),(16,4103000,2,4102001,'Sales Discount',4,1,2,1,1,1,34,null,'2020-04-23 13:27:49'),(17,1102001,2,1102001,'Cash In Bank - BPI',1,1,2,1,1,2,34,null,null),(18,1101001,2,1101001,'Accounts Receivable - Customer 1',1,1,1,1,1,3,34,null,null),(19,5102000,1,5102000,'Business Travel and Transportation',5,1,2,0,1,23,34,null,null),(20,5102001,2,5102001,'Employee Expenses',5,1,2,1,1,23,34,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `contribution`;:||:Separator:||:


CREATE TABLE `contribution` (
  `idContribution` int(11) NOT NULL AUTO_INCREMENT,
  `contributionName` text,
  PRIMARY KEY (`idContribution`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `contribution` WRITE;:||:Separator:||:
 INSERT INTO `contribution` VALUES(1,'SSS');:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `contributionhistory`;:||:Separator:||:


CREATE TABLE `contributionhistory` (
  `idContributionHistory` int(11) NOT NULL,
  `idContribution` int(11) DEFAULT NULL,
  `contributionName` text,
  PRIMARY KEY (`idContributionHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `contributionhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `costcenter`;:||:Separator:||:


CREATE TABLE `costcenter` (
  `idCostCenter` int(11) NOT NULL AUTO_INCREMENT,
  `costCenterName` text,
  `remarks` text,
  `status` int(1) DEFAULT '1' COMMENT '1 - Active\n2 - Inactive',
  `archived` int(1) DEFAULT '0',
  `sk` text,
  PRIMARY KEY (`idCostCenter`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `costcenter` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `costcenteraffiliate`;:||:Separator:||:


CREATE TABLE `costcenteraffiliate` (
  `idCostCenterAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `idCostCenter` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCostCenterAffiliate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `costcenteraffiliate` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `costcenteraffiliatehistory`;:||:Separator:||:


CREATE TABLE `costcenteraffiliatehistory` (
  `idCostCenterAffiliateHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idCostCenterAffiliate` int(11) DEFAULT NULL,
  `idCostCenter` int(11) DEFAULT NULL,
  `idCostCenterHistory` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCostCenterAffiliateHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `costcenteraffiliatehistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `costcenterhistory`;:||:Separator:||:


CREATE TABLE `costcenterhistory` (
  `idCostCenterHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idCostCenter` int(11) DEFAULT NULL,
  `costCenterName` text,
  `remarks` text,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`idCostCenterHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `costcenterhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `custcontactperson`;:||:Separator:||:


CREATE TABLE `custcontactperson` (
  `idCustCP` int(11) NOT NULL AUTO_INCREMENT,
  `idCustomer` int(11) DEFAULT NULL,
  `contactPersonName` text,
  `department` char(20) DEFAULT NULL,
  `main` int(1) DEFAULT NULL,
  `sk` text,
  PRIMARY KEY (`idCustCP`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `custcontactperson` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `custcontactpersonhistory`;:||:Separator:||:


CREATE TABLE `custcontactpersonhistory` (
  `idCustCPHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idCustomer` int(11) DEFAULT NULL,
  `contactPersonName` text,
  `department` char(20) DEFAULT NULL,
  `main` int(1) DEFAULT NULL,
  `idCustomerHistory` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCustCPHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `custcontactpersonhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `customer`;:||:Separator:||:


CREATE TABLE `customer` (
  `idCustomer` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `email` text,
  `contactNumber` text,
  `address` text,
  `tin` text,
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
  `archived` int(1) DEFAULT '0',
  `sk` text,
  PRIMARY KEY (`idCustomer`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `customer` WRITE;:||:Separator:||:
 INSERT INTO `customer` VALUES(1,'6e5c71b665ff06be5555939fb10b31000747fe62b852557feb689b8bff6e548162762d6ee0ff9d8086ebdc5f417eb1b1f73904c5436429cb7fcb38ba811b2497zieBEv1IQIzHSEnRYpPtgIXJ4DnuOBSz2+k62SkdG7WgOhiVD4pEhsrK1lxMqBAr6fST/Lz0mx7QYMvNyg+qQw==',null,'28ad610bdd8493f4bfdd14c04146a4cd56675db7f713f682a3eaccbc844c9c7d6e5a1c899979ade62907eb555ab1fee391fdf4c35080f5615b8298f4c56ef199GCg9p81Wx7DqLdfNHYFyMFXUlJvYjfL3NqlFKj8HmJY=','5d45a97be69c01e2872a13c7b577b28ee7fba2734cff792503e2efe24d43fd9c839ca9d25e55eaa0fbb7a61934bd5c1547a4cb07f94967fbe23ddd4f262fbb41oiCREdUx3qks9y9HNoh3nRSolHu3pkwEEHXcMUa9YcE=','2a576e108ba68424205e7d26b2f5c4374f455d5dc59818b08bc75641152129a687ce1a8f6afa6343eae0aad82511af1bcaa8d2ab45eb418fb86e01fc49d7d0454Qew0UolRa+7xt6fzbB/8sP08aL8DSYUi0dVtoXcJa8=',1,null,1,100000.00,0,null,0.00,5.00,10.00,0,0.00,null,null,0,200519200098),(2,'a8e4a9f1fc96f72a1a792ac3580399d8e92b0e9be02fae4db8382f535a087d2f1776fcb4368e7e5ee1a6341769643ef92688aa5aa1015e96828660d63da51f64dgkVOyuYKQPRhN4KCV7Br2gPibscku0JiqhenP6XM0Yp8CtagMjWIb7HOqJT9mF7',null,'eec5f84c188dc20bbc0bc42f9b9e323deb85b01dda7aeaf45f7728d7a2e690a6c8fb4ab7430948b51e2044195ad0c17f58b41eee582adc78facfa8e39abda80eOtiOnzYPeXVnf4gtVfZzqXvwzVuwwEq2nT9BiK2/PBg=','44e7dde63b7922fb4cca8c1bffbbf22f5d17320115d5fc1fcdac7007140864ba0fd1675b197d63285e24dcd9004ba7d89891d540c5b64d5d593d356a89acd4ff1pEjNTpDWpnaDIIEQQaPHmYrbH84j82FuQavYNczNV8=','4f554cbf9058383ac2f88be3c0420cb7868b443612ad9be2125bedbd76416862d1f0ab4f16341ff4ffc5e3ecdd4078f7cdb3722ae388b335c6964b6824e46ea5oPiEd0m40wmcG+QS+DnK2zwTEtkaboopODv0geQx60s=',1,null,0,0.00,0,null,0.00,0.00,0.00,0,0.00,null,null,0,'23/15000361'),(3,'d079a21156b0862603f028cbabd6db2092ff032d0fe79f0f70480368def11967964321d957df24a70efdd0a1010eea2934e780302f131b625e26844e32920bbezbycwA/3TvCWzDD8qBrDsdVjrBVNoY+GtjEIoimjGxQ=','eb7f99a7dbc1ff648678e68cc0cf3a5a49167e687c72f6711679236b0ecec5e7409b8f1fdb444248b3df31baec4858637572fa7196d7216ed9aa8e8d70a93cf6bCfqO8VJNqfWZ57TrIsaem+wbWZ1xxCTwSDUGn8tEos=','f8be7946629f36b9e96c330d74d410eff4c8f234b6f65626810aa9aabd3aef31b8952337c1144378100c84c347e2e21780bac889dd6822ce03fea5a9b28918f8bSuwYtCuhK2xcW7xLzsQbwYoEVRjEz0FOqAJVpFEBQo=','aeaac2a2aa7757d20557170ffd257c39b0a58b459526e64cb5a534ebaede082da670d3ca155ea166febce85b7153eaf2b56c9942860812c9b97332a4e09af5a3ofBZEKxkIsIjnLiHDOaXf1Ts/tO35MhOUAufq6c+TjA=','22ab7077190bb40db848b517b31a38b73589e07c3f966f9399f0b31325ceaa8380a8cf1af19df42fe21e60fc898a18aed6bbc355fe54a64749fbe7ef00f5f301WvuemkbSOgsW9m8HGReSPFntgKz9GNE68FTMgHkyqvM=',2,4,0,0.00,1,1,0.00,0.00,0.00,0,0.00,null,1101000,0,'12072100-82'),(4,'603e12a33a92d111c4ad44011805ec095334373f419b9fcf9942b21271ab77aef5006b7b3884e0972548d1de3ac2e7dac247e5ac808d61976227f7e78844a21eav9WDpDfNXe1O18n3jljGHJbp7Njlt2GoRMt+WPHGko=','44215a66166b3c64feaecefb2dd242ab4cfefccf9ed07b938037afbff0229686ef2a6a416deb90ca5581d78e995d9217a9ec0282ddc30edb7ffb6f7f6beb6d659iTwhIsw323abx/0ArTXWVHZuo++hpQZFvmfAyK1FXY=','178d252611428cee24f4ff4ad7caaece178eed4d6916952adbbfb54dada1e9f862db80377664e12ce1b970aca0e93b36d05533a85f60f1e0f1a338d13b1b2691VsG+XwYgPgDOwvM0ASWa0DWREd9KkT0t/GEf0yo2jDQ=','c8b3459e5f6146a12529f794ba1bcfdc2c655bdf83585de2344c776edff08249bc624fca639e44e5160af418bda7580b3b738d3d2af082dace1d40a2a6cbd933tHT/LPkqHsyPjY06cVFVWcFVS4me50keJ7iKfB9h7K4=','afaea19721cede18f2d49b0ac793abf5a28b1d812cc7049235448b52aec8beecb3b768cb7e4dd3920ff9b0b5f9bbf380544b686f81655b8f42633135720f271fhVqcxMTrFJCCw4zdQ6oUtNnCadDl3WH5BrRVzqPXupM=',2,3,0,0.00,1,1,12.00,0.00,0.00,0,0.00,null,1101000,0,041623080131);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `customeraffiliate`;:||:Separator:||:


CREATE TABLE `customeraffiliate` (
  `idCustomerAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `idCustomer` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCustomerAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `customeraffiliate` WRITE;:||:Separator:||:
 INSERT INTO `customeraffiliate` VALUES(1,1,2),(2,2,2),(5,3,2),(6,4,2);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `customeraffiliatehistory`;:||:Separator:||:


CREATE TABLE `customeraffiliatehistory` (
  `idCustomerAffiliateHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idCustomerAffiliate` int(11) DEFAULT NULL,
  `idCustomerHistory` int(11) DEFAULT NULL,
  `idCustomer` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCustomerAffiliateHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `customeraffiliatehistory` WRITE;:||:Separator:||:
 INSERT INTO `customeraffiliatehistory` VALUES(1,1,1,1,2),(2,2,2,2,2),(3,3,3,3,2),(4,4,4,4,2),(5,5,5,3,2),(6,6,6,4,2);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `customerhistory`;:||:Separator:||:


CREATE TABLE `customerhistory` (
  `idCustomerHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idCustomer` varchar(45) DEFAULT NULL,
  `name` text,
  `email` text,
  `contactNumber` text,
  `address` text,
  `tin` text,
  `paymentMethod` int(1) DEFAULT NULL,
  `terms` int(1) DEFAULT NULL,
  `withCreditLimit` int(1) DEFAULT NULL,
  `creditLimit` decimal(18,2) DEFAULT '0.00',
  `withVat` int(1) DEFAULT NULL,
  `vatType` int(1) DEFAULT NULL,
  `vatPercent` decimal(18,2) DEFAULT '0.00',
  `penalty` decimal(18,2) DEFAULT '0.00',
  `discount` decimal(18,2) DEFAULT '0.00',
  `withHoldingTax` int(1) DEFAULT NULL,
  `withHoldingTaxRate` decimal(18,2) DEFAULT '0.00',
  `salesGLAcc` int(11) DEFAULT NULL,
  `discountGLAcc` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCustomerHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `customerhistory` WRITE;:||:Separator:||:
 INSERT INTO `customerhistory` VALUES(1,1,'6e5c71b665ff06be5555939fb10b31000747fe62b852557feb689b8bff6e548162762d6ee0ff9d8086ebdc5f417eb1b1f73904c5436429cb7fcb38ba811b2497zieBEv1IQIzHSEnRYpPtgIXJ4DnuOBSz2+k62SkdG7WgOhiVD4pEhsrK1lxMqBAr6fST/Lz0mx7QYMvNyg+qQw==',null,'28ad610bdd8493f4bfdd14c04146a4cd56675db7f713f682a3eaccbc844c9c7d6e5a1c899979ade62907eb555ab1fee391fdf4c35080f5615b8298f4c56ef199GCg9p81Wx7DqLdfNHYFyMFXUlJvYjfL3NqlFKj8HmJY=','5d45a97be69c01e2872a13c7b577b28ee7fba2734cff792503e2efe24d43fd9c839ca9d25e55eaa0fbb7a61934bd5c1547a4cb07f94967fbe23ddd4f262fbb41oiCREdUx3qks9y9HNoh3nRSolHu3pkwEEHXcMUa9YcE=','2a576e108ba68424205e7d26b2f5c4374f455d5dc59818b08bc75641152129a687ce1a8f6afa6343eae0aad82511af1bcaa8d2ab45eb418fb86e01fc49d7d0454Qew0UolRa+7xt6fzbB/8sP08aL8DSYUi0dVtoXcJa8=',1,null,1,100000.00,null,null,0.00,5.00,10.00,0,0.00,null,null),(2,2,'a8e4a9f1fc96f72a1a792ac3580399d8e92b0e9be02fae4db8382f535a087d2f1776fcb4368e7e5ee1a6341769643ef92688aa5aa1015e96828660d63da51f64dgkVOyuYKQPRhN4KCV7Br2gPibscku0JiqhenP6XM0Yp8CtagMjWIb7HOqJT9mF7',null,'eec5f84c188dc20bbc0bc42f9b9e323deb85b01dda7aeaf45f7728d7a2e690a6c8fb4ab7430948b51e2044195ad0c17f58b41eee582adc78facfa8e39abda80eOtiOnzYPeXVnf4gtVfZzqXvwzVuwwEq2nT9BiK2/PBg=','44e7dde63b7922fb4cca8c1bffbbf22f5d17320115d5fc1fcdac7007140864ba0fd1675b197d63285e24dcd9004ba7d89891d540c5b64d5d593d356a89acd4ff1pEjNTpDWpnaDIIEQQaPHmYrbH84j82FuQavYNczNV8=','4f554cbf9058383ac2f88be3c0420cb7868b443612ad9be2125bedbd76416862d1f0ab4f16341ff4ffc5e3ecdd4078f7cdb3722ae388b335c6964b6824e46ea5oPiEd0m40wmcG+QS+DnK2zwTEtkaboopODv0geQx60s=',1,null,0,0.00,null,null,0.00,0.00,0.00,0,0.00,null,null),(3,3,'b14f3c9498b40fd52e46de66fe972af51a5cc2d3082009b71bf03111f48227bb0c162e461102da6448887dd513b89753d6271e659463be22ac883b3f787809bezdJ7Tqx8oJPyRa4Ha0UcjPMQsSostwPDv624N/z8MC4=','fe99ab5a025352841af3cd856560d08362afb0b2930a5c404d588d18fd0968c4b0da30f42d996dda6377c8ae0f0370b3a4dbd289de743d05c127b285459d94f7z0kF4s26023ZxzoItABmkuYoxKNe7oHVgnqB65vciEo=','86a0318b2e738c2cd5f2e89c3d4ffd04fe92d12fc64710baf9e13ef7d0aa9956f2a312cacfb5bd218b815d691508bd2a5d8b18b49d05b1ab74ad27822144d167lN9Oy2jQUwBoILz1OtcwSbOtYTB4l5KoFElWo+aD6yA=','547b6a34eea38184f1cf3d82158f376245fc755b4ebb6039d84e8677fdab3cf9ddb3498854a0d5d165620f16d462cf6f159a788f70cd31bf8b4c247359e8bc1bN2BgE90G6E8pHdkX1cf+JegWO5ZF5eGPmCUSMxG6JXY=','4bd5bce8950ce490b7b8ef086be5699ce58dfc2d05046021756730dcce00a0b36d06c6c7ac9d070d6a569828a8ce0375e9eb5a447f5ac1f8555431754bfde7f8S2GaUDDecpn+f7Ly5JbfLqBuL8VHKDco7NuAoeGLOu8=',2,4,0,0.00,null,1,0.00,0.00,10.00,0,0.00,null,null),(4,4,'e066b720c8dbf4e86e2133f2ab7c05829f6a320456d169ac0f48886db0e15fa9f609631ea044a7f63c97054fe00a37b73aca701263e3555d57ade326bdf7e8efy5K5JBuOeQDDXNpFZUSq5Pd1yy041JmdACecwE6RSRU=','4ff913eb3e4ef9679a02e7d2e8e4e3de7e5f1b3dda8da6a0bbcaa851c54c7b744532c25ee4e4b3b472b723b19a18747f047b2e74a5e17d7559b01057b6f93fe1BwNkZVEuhLOQsQV1Y4JgA2jr9B/N9F0EtK0XDBwXp4U=','3115fd5702a30ad04f8f8c1303547e803571c8361010a47501af84c30ac6a70b31850926a18887cef77abda124e9850c07fc6d7ebc083263864d02b54edbc9dcVl/Sbbl3/2ESWEVxihB/zJ1bb1nC6qlVvtmFvR5gdLw=','8dd2f3d0f765e65f08da0bd13d205e9bdfe7ccb68703c20becdd24663a7504083987fb3b025f460638377ef38f72a7689ef92a8c56ad5f8f5c350fa99b687b85qJ9dZRwNkhGX45RnYbIHJqCWiVpjeKAGDoj38er+07M=','58b6c9458ef81d43006f82b9aa2c4c589f392f7910603f5ace4a9c357e192c3d20df92de11e0d8d2ea114b11e458fcc4320f3097cacdcdb1a3dd5dae032315266vLmQwc/xMK1SOv8tHQo9yyd9HHt54VZgocZRMo/igs=',2,3,0,0.00,null,1,12.00,0.00,10.00,0,0.00,null,null),(5,3,'d079a21156b0862603f028cbabd6db2092ff032d0fe79f0f70480368def11967964321d957df24a70efdd0a1010eea2934e780302f131b625e26844e32920bbezbycwA/3TvCWzDD8qBrDsdVjrBVNoY+GtjEIoimjGxQ=','eb7f99a7dbc1ff648678e68cc0cf3a5a49167e687c72f6711679236b0ecec5e7409b8f1fdb444248b3df31baec4858637572fa7196d7216ed9aa8e8d70a93cf6bCfqO8VJNqfWZ57TrIsaem+wbWZ1xxCTwSDUGn8tEos=','f8be7946629f36b9e96c330d74d410eff4c8f234b6f65626810aa9aabd3aef31b8952337c1144378100c84c347e2e21780bac889dd6822ce03fea5a9b28918f8bSuwYtCuhK2xcW7xLzsQbwYoEVRjEz0FOqAJVpFEBQo=','aeaac2a2aa7757d20557170ffd257c39b0a58b459526e64cb5a534ebaede082da670d3ca155ea166febce85b7153eaf2b56c9942860812c9b97332a4e09af5a3ofBZEKxkIsIjnLiHDOaXf1Ts/tO35MhOUAufq6c+TjA=','22ab7077190bb40db848b517b31a38b73589e07c3f966f9399f0b31325ceaa8380a8cf1af19df42fe21e60fc898a18aed6bbc355fe54a64749fbe7ef00f5f301WvuemkbSOgsW9m8HGReSPFntgKz9GNE68FTMgHkyqvM=',2,4,0,0.00,null,1,0.00,0.00,0.00,0,0.00,null,1101000),(6,4,'603e12a33a92d111c4ad44011805ec095334373f419b9fcf9942b21271ab77aef5006b7b3884e0972548d1de3ac2e7dac247e5ac808d61976227f7e78844a21eav9WDpDfNXe1O18n3jljGHJbp7Njlt2GoRMt+WPHGko=','44215a66166b3c64feaecefb2dd242ab4cfefccf9ed07b938037afbff0229686ef2a6a416deb90ca5581d78e995d9217a9ec0282ddc30edb7ffb6f7f6beb6d659iTwhIsw323abx/0ArTXWVHZuo++hpQZFvmfAyK1FXY=','178d252611428cee24f4ff4ad7caaece178eed4d6916952adbbfb54dada1e9f862db80377664e12ce1b970aca0e93b36d05533a85f60f1e0f1a338d13b1b2691VsG+XwYgPgDOwvM0ASWa0DWREd9KkT0t/GEf0yo2jDQ=','c8b3459e5f6146a12529f794ba1bcfdc2c655bdf83585de2344c776edff08249bc624fca639e44e5160af418bda7580b3b738d3d2af082dace1d40a2a6cbd933tHT/LPkqHsyPjY06cVFVWcFVS4me50keJ7iKfB9h7K4=','afaea19721cede18f2d49b0ac793abf5a28b1d812cc7049235448b52aec8beecb3b768cb7e4dd3920ff9b0b5f9bbf380544b686f81655b8f42633135720f271fhVqcxMTrFJCCw4zdQ6oUtNnCadDl3WH5BrRVzqPXupM=',2,3,0,0.00,null,1,12.00,0.00,0.00,0,0.00,null,1101000);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `customeritems`;:||:Separator:||:


CREATE TABLE `customeritems` (
  `idCustomerItems` int(11) NOT NULL AUTO_INCREMENT,
  `idCustomer` int(11) DEFAULT NULL,
  `idItem` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCustomerItems`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `customeritems` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `customeritemshistory`;:||:Separator:||:


CREATE TABLE `customeritemshistory` (
  `idCustomerItemsHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idCustomerHistory` int(11) DEFAULT NULL,
  `idCustomer` int(11) DEFAULT NULL,
  `idItem` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCustomerItemsHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `customeritemshistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `defaultaccounts`;:||:Separator:||:


CREATE TABLE `defaultaccounts` (
  `idDefaultAcc` int(11) NOT NULL AUTO_INCREMENT,
  `idAffiliate` int(11) DEFAULT NULL,
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
  `cashEquivalents` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDefaultAcc`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultaccounts` WRITE;:||:Separator:||:
 INSERT INTO `defaultaccounts` VALUES(1,2,null,null,1101000,2101000,null,null,null,null,null,null,null,3101000,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `defaultaccountshistory`;:||:Separator:||:


CREATE TABLE `defaultaccountshistory` (
  `idDefaultAccHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idDefaultAcc` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
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
  `cashEquivalents` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDefaultAccHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultaccountshistory` WRITE;:||:Separator:||:
 INSERT INTO `defaultaccountshistory` VALUES(1,1,2,0,0,1101000,2101000,0,0,0,0,0,0,0,0,0,0),(2,1,2,null,null,1101000,2101000,null,null,null,null,null,null,null,3101000,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `defaultentry`;:||:Separator:||:


CREATE TABLE `defaultentry` (
  `idDefaultEntry` int(11) NOT NULL AUTO_INCREMENT,
  `purpose` char(250) DEFAULT NULL,
  `idModule` int(11) DEFAULT NULL,
  `idReference` int(11) DEFAULT NULL,
  `remarks` text,
  `archived` int(1) DEFAULT '0',
  PRIMARY KEY (`idDefaultEntry`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultentry` WRITE;:||:Separator:||:
 INSERT INTO `defaultentry` VALUES(1,'Sales',18,5,null,0),(2,'Payable',25,4,null,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `defaultentryaffiliate`;:||:Separator:||:


CREATE TABLE `defaultentryaffiliate` (
  `idDefaultAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `idDefaultEntry` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDefaultAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultentryaffiliate` WRITE;:||:Separator:||:
 INSERT INTO `defaultentryaffiliate` VALUES(1,1,2),(2,2,2);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `defaultentryaffiliatehistory`;:||:Separator:||:


CREATE TABLE `defaultentryaffiliatehistory` (
  `idDefaultEntryAffiliateHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idDefaultEntryAffiliate` int(11) DEFAULT NULL,
  `idDefaultEntryHistory` int(11) DEFAULT NULL,
  `idDefaultEntry` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDefaultEntryAffiliateHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultentryaffiliatehistory` WRITE;:||:Separator:||:
 INSERT INTO `defaultentryaffiliatehistory` VALUES(1,null,1,1,2),(2,null,2,2,2);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `defaultentryhistory`;:||:Separator:||:


CREATE TABLE `defaultentryhistory` (
  `idDefaultEntryHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idDefaultEntry` int(11) DEFAULT NULL,
  `purpose` char(250) DEFAULT NULL,
  `idModule` int(11) DEFAULT NULL,
  `idReference` int(11) DEFAULT NULL,
  `remarks` text,
  PRIMARY KEY (`idDefaultEntryHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultentryhistory` WRITE;:||:Separator:||:
 INSERT INTO `defaultentryhistory` VALUES(1,1,'Sales',18,5,null),(2,2,'Payable',25,4,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `defaultentryposting`;:||:Separator:||:


CREATE TABLE `defaultentryposting` (
  `idDefaultPosting` int(11) NOT NULL AUTO_INCREMENT,
  `idDefaultEntry` int(11) DEFAULT NULL,
  `idCoa` int(11) DEFAULT NULL,
  `debit` decimal(18,2) DEFAULT '0.00',
  `credit` decimal(18,2) DEFAULT '0.00',
  PRIMARY KEY (`idDefaultPosting`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultentryposting` WRITE;:||:Separator:||:
 INSERT INTO `defaultentryposting` VALUES(1,1,1101000,0.00,0.00),(2,1,4102000,0.00,0.00),(3,2,2101000,0.00,0.00),(4,2,1103000,0.00,0.00);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `defaultentrypostinghistory`;:||:Separator:||:


CREATE TABLE `defaultentrypostinghistory` (
  `idDefaultEntryPostingHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idDefaultEntryPosting` int(11) DEFAULT NULL,
  `idDefaultEntryHistory` int(11) DEFAULT NULL,
  `idCoa` int(11) DEFAULT NULL,
  `debit` decimal(18,2) DEFAULT '0.00',
  `credit` decimal(18,2) DEFAULT '0.00',
  PRIMARY KEY (`idDefaultEntryPostingHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultentrypostinghistory` WRITE;:||:Separator:||:
 INSERT INTO `defaultentrypostinghistory` VALUES(1,null,1,1101000,0.00,0.00),(2,null,1,4102000,0.00,0.00),(3,null,2,2101000,0.00,0.00),(4,null,2,1103000,0.00,0.00);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `disbursements`;:||:Separator:||:


CREATE TABLE `disbursements` (
  `idDisbursement` int(11) NOT NULL AUTO_INCREMENT,
  `idInvoice` int(11) DEFAULT NULL,
  `paid` decimal(18,2) DEFAULT '0.00',
  `balance` decimal(18,2) DEFAULT '0.00',
  `fIDModule` int(11) DEFAULT NULL,
  `fIdent` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDisbursement`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `disbursements` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `disbursementshistory`;:||:Separator:||:


CREATE TABLE `disbursementshistory` (
  `idDisbursementHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idInvoiceHistory` int(11) DEFAULT NULL,
  `idInvoice` int(11) DEFAULT NULL,
  `paid` decimal(18,2) DEFAULT '0.00',
  `balance` decimal(18,2) DEFAULT '0.00',
  `fIDModule` int(11) DEFAULT NULL,
  `fIdent` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDisbursementHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `disbursementshistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `empbenefits`;:||:Separator:||:


CREATE TABLE `empbenefits` (
  `idEmpBenefits` int(11) NOT NULL AUTO_INCREMENT,
  `idEmployee` int(11) DEFAULT NULL,
  `description` text,
  `amount` text,
  `schedule` int(1) DEFAULT NULL COMMENT '1 - Daily\n2 - Monthly (1st Half)\n3 - Monthly (2nd Half)\n4 - Semi-Monthly',
  `archived` int(1) DEFAULT '0',
  PRIMARY KEY (`idEmpBenefits`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `empbenefits` WRITE;:||:Separator:||:
 INSERT INTO `empbenefits` VALUES(1,119,'2194f557bced2679ad189a8daab5e5be68bb468309a4ffd90700d18ffc7eadc84619f70c9f9b61338da8ef442e61fed5b6232d6927b6fba10e0991a5aa457f83KIPS4OEdwG3cuR+8NNs2KjKU35b1wfFxmP0iNrWCBuk=','8deb44bd845313408aab5083c5042ef92e3865f2db0a259665694d5612dd904da395c80b9f0a2d3a79a2b92c036ce93326c710f9f81d3c9269a438b04d7b0e3doWHBQhBqwB+0okz3iplRGCnCDxjNL4I4tNZgjRj6Fgg=',4,1);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `empbenefitshistory`;:||:Separator:||:


CREATE TABLE `empbenefitshistory` (
  `idEmpBenefitsHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idEmpBenefits` int(11) DEFAULT NULL,
  `idEmployeeHistory` int(11) DEFAULT NULL,
  `idEmployee` int(11) DEFAULT NULL,
  `description` text,
  `amount` text,
  `schedule` int(11) DEFAULT NULL,
  PRIMARY KEY (`idEmpBenefitsHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `empbenefitshistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `empcontribution`;:||:Separator:||:


CREATE TABLE `empcontribution` (
  `idEmpContribution` int(11) NOT NULL AUTO_INCREMENT,
  `idEmployee` int(11) DEFAULT NULL,
  `idcontribution` int(11) DEFAULT NULL,
  `amount` text,
  `effectivityDate` date DEFAULT NULL,
  `idCoa` int(11) DEFAULT NULL,
  `archived` int(1) DEFAULT '0',
  PRIMARY KEY (`idEmpContribution`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `empcontribution` WRITE;:||:Separator:||:
 INSERT INTO `empcontribution` VALUES(1,0,1,'6f1ae808c239bb87587a800b5d592020aee29f27a1d8faecf706e9e3c6985a1f6943ec5036ae6a647f54bf389d8334edd47240bcd49379be0e00289af6ba9e70pHzvzkOCbMyCoCJhKXI4lNZDBjB82SxdXTUq5LlotkQ=','2020-05-01',0,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `empcontributionhistory`;:||:Separator:||:


CREATE TABLE `empcontributionhistory` (
  `idEmpContributionHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idEmployee` int(11) DEFAULT NULL,
  `idcontribution` int(1) DEFAULT NULL COMMENT '1 - SSS\\n2 - Philhealth\\n3 - Pag-ibig Fund\\n4 - Withholding Tax',
  `amount` text,
  `effectivityDate` date DEFAULT NULL,
  PRIMARY KEY (`idEmpContributionHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `empcontributionhistory` WRITE;:||:Separator:||:
 INSERT INTO `empcontributionhistory` VALUES(1,119,1,'6f1ae808c239bb87587a800b5d592020aee29f27a1d8faecf706e9e3c6985a1f6943ec5036ae6a647f54bf389d8334edd47240bcd49379be0e00289af6ba9e70pHzvzkOCbMyCoCJhKXI4lNZDBjB82SxdXTUq5LlotkQ=','2020-05-01');:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `employee`;:||:Separator:||:


CREATE TABLE `employee` (
  `idEmployee` int(11) NOT NULL AUTO_INCREMENT,
  `idNumber` int(11) DEFAULT NULL,
  `name` text,
  `address` text,
  `contactNumber` text,
  `email` text,
  `birthdate` text,
  `status` int(1) DEFAULT '1' COMMENT '1 - Active\n2 - Inactive',
  `user` int(1) DEFAULT NULL COMMENT '1 - True\n2 - False',
  `archived` int(1) DEFAULT '0',
  `sk` text,
  PRIMARY KEY (`idEmployee`)
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `employee` WRITE;:||:Separator:||:
 INSERT INTO `employee` VALUES(1,1,'Jon Snow','Cagayan de Oro City, Misamis Oriental',912345678,'jonknowsnothing@yopmail.com','1981-08-05',1,1,1,null),(59,555,'mark','mark',1231,'mark@gmail.com','1981-07-05',0,1,1,null),(63,777,'marco','marco',2323,'777@gmai.com','2019-11-18',0,1,1,null),(64,999,'Sample User','Test Address',123,'nine@gmail.com','1985-11-04',0,0,1,null),(65,888,'test user','user',123,'user@yahoomail.com','1995-11-05',0,1,1,null),(66,333,'Aubrey','test Address',1231231,'aubrey@gmail.com','2005-08-01',1,1,1,null),(67,222,'tuna tuna','test address',123,'tuna@gmail.com','1995-11-01',0,1,1,null),(68,111,'one','one address',111,'one@gmail.com','1985-11-04',0,1,1,null),(90,123456,'rgyrt','fghg',35454,'sfdgfd@sfg.gfj','2019-11-27',0,1,1,null),(91,123,'dsfgvdf','gdf',34535,'gdf@dhg.ujkl','2019-11-27',1,1,1,null),(92,213345,'tgdg','dfgdfg',2147483647,'dfgfdg@dfhfg.bfgt','1995-11-06',1,1,1,null),(93,12345,'sdgdf','gdfg',23534,'sdfsd@jdfhksjd.com','2019-11-27',1,1,1,null),(95,554455,'test marco','fdsfs',3434,'123@yahoo.com','2019-11-27',0,0,1,null),(96,443344,'test','erere',3434,'2323@gmail.com','2019-11-27',0,0,1,null),(97,2019,'Sam Paul','CDO',2147483647,'sampaul@yopmail.com','1986-11-03',0,1,1,null),(98,88888,'6a86d1a3815d4fb3815270308f63a0c5766f072935a3bcde39ab882b5a03281391f6d61abb82a1ff9b2e02b1a89c22ef85aca9d5f74192e31e86da5d359d2fa5gI7BjCCY1N9ZYNZIk+NW15Sd3V2x96tQv6y1PeU3WxY=','78d0ce81f59c19c1c46e27c0785bc789baecb80b3321da21b87aec93a56f65f6eafb036ed856e945efaf5ceb663100f9a22a76e7903da7b1499d8385e91ff303ojPrJeJnCfIBofEZGBVclQ7nNSxWWKqlMbuhDkQ8p/I=','601a11ee359a030dcf25d52f6c6fb0508c84e3c3f75f5cad10847438d8c4ec56d46b9893a83e977161eb5a11c67e93c87f02d4ee0958941b008ec4cb278c0511lWGLXQtliaFw2ukSWLEgxG4kBnMI6fB96wjukwtVcdI=','cbc1546e3395883a786bf96a172ceb8f1d7bb0a9b97e81993ad2d0f5078276fc80b5ab66138422a698db6dabe68db99f8a527970c7b1911f4d9b3b0f802bbd38+Lkx4Xk7e5vy3fr5o+nr9vniK6HvJVg0/z9P8f3AXc8=','87fa747c9ffd26a3e34aa30f95aae624cf4066048e84626d94126ceef314bf15870bfb4baa0d3a3c0e8b45e0f387196d1ffacfe7b8f35450a743743ccdd25072mCEDbiHWaVGOLT1STa+spkNRNgIc4mEk3yINCSnUUsw=',0,1,1,112112152042),(99,124,'Marie Danilene','Cagayan de Oro',122,'dan@dispostable.com','1996-01-21',0,1,1,null),(100,123456789,'Dulcy','CDO',139829485,'dulcy@yopmail.com','1997-12-05',0,1,1,null),(101,2147483647,'Hazel','El Salvador',945454545,'hazel@yopmail.com','1998-11-06',0,1,0,null),(102,11111,'630a2f216871747b96ca9fa87e62ef99069dfdecbcd2303a3eeb41721fdd54805419c8c56fb649e27e1e50d18b7f3e3b9c5b674c0884bedf2c4d186c8f3fef0dlogkslPLCXV5FR64RLShF46U8JoAxiC3zmKh8OkT5OlNrNTG3XZJm5NU4+wTKomT','a0ceab3e3c2029861f9af92e6adffc528f6a489ca7eb7a8d39d31cb881dd52997c3a26f510180ffc2c90f89282f3563a1e83d0a1d8f4a84f1d4da8a838d6556aVd9K8K+/34jSkKBAH+GSPfT4pGjyNp2PnJ25gz99snU=','f24887a4b998707766e1c49247daebbcb829ddf76b2c934c308646b222f4b9783fff820829e57353c5f35e89c64d5e506bb969116faa1dbf89452ce9d037efccNM8TiR17gL/QqVsSkNkonhzxa/mmAiM/K0wychcIXXU=','b6ae84ec00bd9f83c8afa47c2bb84abf8d42ae447841ad2dfb6d45cb4277dd6bf882af0d23029cb623f03f41bd54c5f596c413f239ad9d8aacc3340d85861041zYSdc2chZ3wzpPZGRAJ9CP2xzw0aDgyhlfYE3nmL2eA=','fd434ae6a6aca408b156ebc264d6bf309b44dcb4a18dc9020591f9f83622509cb0ebabf5ca3fee65d592adf7361606dc4a43b2585702373fc09a5021c304d531trtlAJ36Ft5FvM28YK3NM4/an7joD0dOGccXhPeg5Lg=',0,1,0,192519200589),(104,819,'Timon Cantu','Culpa explicabo Acc',875,'mywo@dispostable.com','1975-01-08',0,1,1,null),(105,258,'Mia Mcfarland','Ut iusto cumque eius',33,'biba@mailinator.net','1990-06-13',0,1,0,null),(107,659,'Liberty Chandler','Cupidatat voluptas c',410,'tysowevifi@dispostable.com','2017-02-07',0,0,1,null),(108,708,'Connor Curry','Dolor est sit volup',328,'jyze@dispostable.com','1987-02-09',0,1,1,null),(109,454534,'erterfd','gefdf',34534534,'dffdv@jdnf.df','2020-02-24',0,0,1,null),(110,398,'Leila Francis','Dolor lorem corrupti',298,'fisohypu@mailinator.net','1983-01-26',0,1,1,null),(111,570,'Ahmed Lambert','Et hic aperiam at do',819,'hibyci@mailinator.com','1974-07-15',0,0,1,null),(112,411,'c90eb341acdc69c1b15399a718e4f361b0733e32476a83851b8b0079b6b583c2006e0e33094ab96ca1c24c28a7175e68410fe2bc375ed03a97dea61f5ecc5c1e1pIuX2FgdpSPL0KCaavQaM1Wn3iGLvczKV/qctxVMJ8=','00e89a41238013d06bbdc67a2ce75a92a7998eec372c40fb54115710828025c737738728be2107b4969d2ed9b941a54a09630365824d249e55a4fd095858a3f5IQERZYNjvvaMM9Edfjx2SMfaPL3pnKqrpbQ1gALHevMIKFM6FtFtcGGf2+0/oMr9','7bc3736ac3e316a09246621f6ce5153da0fbe5a8dec1b8ec465500b2c5ad2907a337e40a242398441fbea619c27b786fff33b310a3337704b7406405ad564ef6xrko2PYr80kDQ98waQx9ZKZMrHMJud+SJIiHmLJwhsc=','3cafe1dfb30800dcdac0caeaf92d9caafa5a56ae88ab91479ca200346e6ada36c89515552d25778a0a70d1355f91d4d63f97c1b83be663175778b93c422c5830iLag64t1gmcZIG7eLjrANxXAGOwEReF6npAZHagbyAaYMZtqgUolzalhvYIBIxgc','6241e400ba960588e3d8b756d537c6d8c27295717150eef9f85ec765eabc9752368005b7f8ce020a3a5b6357b26e0674b2926d1d19d713a27891ad5d6762f5f3nhcrCnnTjdFF2OZIAKilK8jlQYpQDvMxg2c5daglyEA=',0,0,1,190123250595),(113,86,'cfb79d2110f9a07a7ad36f5411252565eda068e9dcd81e8e0c670a175fc3c3394fdad8140609b79d024cd48ebae3a628afdc20666ede3c7dbfb6a4d3e8885aa5apN2+jCYIgBbicR8/qMRv078PEZhwLyVVQxx9PrOV7w=','dd077a9ebb8dbbec084f0c697352c002d525507a3165a5e94e49bfddc5e65dfdc26d25bcbd266af8b25a2ff3266514231c8fd3b3bfdcd33c1381a0a813a8fa19QAQ0MuJriJsxDHeuTurIxS/xuV+nmIYl2Fzb+3GvFz/FLbTgucBLHNeqWiOsLjL+','1fa73a08cbd64aaac42fd0072fa116dc33b0de62022a49a5ad7d13659f1bfba68879eab05118693c4b3977f024c42f2f294e0663346eeae069e57807dcd3c021CkBvxQGIJmef7GElOLyjnd5+OOBXMEcSYhpCzVBRqOo=','468a1f773c70305c6d1715dbacfe7b76825e0fe6088911cde947a6321ff74f7cad8933188dfbf105646059bd7182c3ab8af39e78fdc84a38d4f25c6774a63e0bijnU/5Ab3OdD8pQhTYdXZha8hn2WlJCjLxGoQQkc0FnSEIlRSSM/SXb4CzLSfZDx','0002fde3dc95e4454c4b6bd9cc02bf7e2ee99b4c2e0a905216c01042da324930508d8b6fae66451cfda78603dbef0ad69d7ba4c2afcf8c0eacd46332faac798aEtFdWt6kcprOx5MkQ0j6scMhxyOJV7e0kN7IKJVXebo=',0,0,1,122103090158),(114,116,'5f72a57a051e2f3f876f5dbf7d06de720bcd527db71254bc09c56e72703c8e02875bddfc60ccb46c16672a827d87d891399edc9994ff74ba79db1e8eb9e42b38Ckj22XLzF1fI1q4g1v1OkyGTemcPbdSbfXS2Td13OeM=','97190983e39747a5cb1f6b330f9d14467f51c1d55f9866616604914ce6a9e2a8560df91624df4d41d53d42b0f026f7a182112b15a6ffac967c503589b9a65fe1mNT1zKdatvQRPfKDrgnaTQyM+SeJawL88Ou4c+T/Te46eWyb2QZAbZbWbveUhLyf','e9f7985eadc725ec83437e17bc886645ab25ba71569439e44a4f7ea9bdef5a6bf65535be15b48f3abea4c65696c5332c1d6d42cf438980384917dac9794a05d5qNSbB5246cIBe03Lya0PuEngg3YzpVGTWZvYh2WuDKw=','d83045a6e82f3fe410467ec1380d2a0e669d422440f93ad9a6e2013d798d7be2469ccfaf5024edf5ecc223c6c76860985abab0e4da3af789b69a4dfc5939fc1ddvCQx9yPw9uADIziap7YYQHX85ofaiZW+EcuWsejw7ZelHWNMCFV9sPG4atQCYAI','c7f73787bc9631d81dcd7008a06461d8a3d319affb5cd7e3e2ecfe4e1ffa0ea4379322306baa3272784b0a17d97505a561a86cae2fd558ddd3bc4dce7a584881l7oUiqfEYUusEALNSuuN8Oug9+sUl51bfH/MVfl7dn0=',0,0,1,071801142021),(115,379,'866299fda6645ced7b82abab9f37a6122ecf35ad27d1c964e2326cefac334b2e49ea3681ef31727fcc33af48e116afe4b8230554c432dbc60a8400105c7fad4d3a1symIUN6FI2pw0Z6fpj2ju/mS9xEU0L3Y/F0l4qGU=','1b88fe4ff1526b9e0b113062fe4c461591eb55c71bba8321872d0e0265fd7f77c4c2f69dcefe24578c3f57b913f1a7ea00ab15b1e534b8ffdc07f683ec5135ecChTZAvTNhEHzsLxNaMYfQTxu/WZ9Ll7Txr6vAHn0Y8JzWCxlnuJWEAU59nRmYE3g','998ee4ec6029b6e5ae4c1e08d37d0a72e98f289a2ddd3c48cb88edc8e5c27b6fa145bc2cce7e6d5866000bd15629d43b35608c58344d356c21d0201e5e86977cf4c5L82E1e1D/LQCW0MS4auK1hYH3CR5XCILKjQnGKE=','2de5dfb74d27c65d5006eb6abedae75fe042381369a64c59cffd591eda298c583adf768a4fdc402b25b5d8224bd32c00dd9ac0494a7d122e4fcb4de9664a23496z1/ZN0F9yADNG69waOEll3dMhVXN0WLqTokrnmhUlIgM501JQbpeg+CZUTEfct8','ab2a65ba7435ab7add70c028c526e35dbf133576f64cdd7b909f88a6b2871d3fe18c6d0b62aa09b589693d4febb270359dfcff042fef5b0a057549e69e668367GgtVq743jx5Fh2x1eHIh0lEQbeJ3TAWySedJgnd9Dls=',0,1,0,172101130167),(116,532,'c29d7a06b2955adfdcf78531c0596a486432745b7d9a4b307ef5095dc78b4d3ebd660a4649b5bb6586e28c8f9d62b78e4cf8930aa596acf74e96e6dc70969217YG60sK+DfzyVCSnQxBGdTTjwjPpUiLnBP2WuJ+xoaD3zbP7ofpo96Inz8MN7fYqT','43047115b23804a28b1b33ad712471a8f66d64cb13c440e55d9395e6c46c314d22e368a18606eb3950f1302ba2910ac20a135bdee85653b2a4c712cde53cee2dAWzzD073OuD2sD/CWcbCba8qG1jDaScAkfZ5snqoi8Kqw3+kL0d0LeYR2p7ONJIn','9913fed74dc3402e82d97902922876a1cb2dd7944b15bf8e9fc3e4c6b49d106bf33d1560c3e5c4d11fb98e5fdacbda913b4610b3cf7d7a89daf421212dc83b01THyW1ef+v/Kh+nHY70JRp1mcP4dYeFLwSzH0WiGHyfQ=','8e3bf2608ed7d9c807cb74fa860d66c3e1af40325ec5bdb9e6f47f906a48603435ffc5aabd0aaebd7ab4aae27766a819c2af8fa0882ba5da80956acf98be610cFq0j3x03yiUcvRS/nVtRpygx/BpevcbEIdX81kw2+nXe4FWK5WdO+3hJh7zdtJWI','61863b85d3c186acfdf5e9371f48bf43050a92a6546b3e24c0f774dd36e17d3e9efaa769d1b4e93382c4ccc94b2efa8bd7ffa37a269929d1ca8a6d1c1952434cCff/8kAe6rbmDo69PZFBlGlBPYWUYyanRS6XhfCFgmY=',0,0,1,030109120913),(117,1000,'e1b3c4676815b9f9ec827827b6cf93b51bee0d862b1a41c70158543134cd0a8352e55a9310164cbdeedcf67b735d8041d6f27fc9a2a75ac8b118b33fc15c5a55i+IvtLdkGW8tRUq8iNblWQkCkqJOXNBWT/QYEvWpOe8=','5a1dfe08834e7aa487201e06b78f26490bb02fbb745288d197102218cd9a2adf01a51f6d94df00cc7fec908692fbdd214bc07e9e03ef40a0c261db705bcb4a90/UCWhs9e/G306Vk2mbfgHg6TkKCkK56CCQQHVUfHKmw=','cd9bb446123999b3c90464d05c9d67d5dac7e84dd9b25174999aa38b8a5099574a0a11cdb4cb029911b3ab6c39dda80cbaf12ba6b0fe5414cfe11e705b3409e3/ZqULtDuRm/qwFBHxCv8+chyWJOISq1ZFQEIb+STV9s=','a03a28594e58343989b6c382832d2da06202b9ed2939a76407a2681d2563f735946161c1592bc40a976ab645cb52b67abfc73b6ce32b0855706e73a4c8be7375a5PbvrYe4+uvBmd3UG3Lj/Ud19sXncaMlub02MZ4NBnO28oNz+nCepmzIgY4w+e9','8fb3688515927c219e53bbf4c90b972cf8d987d84a2f05b355a75d8b6bed82809c1b5ac83ddf59923faf291770723d0e039727c7d4f2d0d47b5d3e12279b99d8mK1Z5aTK3sYXmfTTJ2Yl360X8W/2MAs5bRWCOCjK1PU=',0,1,0,010413091465),(118,9,'bf8a44ba218ff50623eb4b96e7a0b19adecdcf4dda09cc0308870134a84c41aa55092ad9ac839b665bd37a5d373cd06e01432b5a321be14debef75de88cad716wfi2a9E1sYnOVylOzQR9Kd7MK7p1IMebQDp0s5SbQrQ=','e96d1d1aa6ce5de2988daca3cd30e98d508c871e700cdbcd4de3cbae291d3fc40ba8b4d6725435b172be6742d5d4b72701bbb2f14c1dc1f230c35f7195635151hgmIGl79tP1t6C9vZO3LrbBNC/XVd+fZ13CJXOAbYMg=','0fcf8fa135d15b8ff0f2109b2c569ddf8434bffee77f2423b8ba198aa30fe52fb310362fabce41467274302095e63bcf4d4c476189eddcecdf06fce0b6d66b58Uz0d/1JdTgfnFYBvfeC8OYwGe+HOaOlvNx+vkQfiVjI=','f47529140f854c13541249496d03e52501c432d511ac9b0bd9c6baaf4f3e9d9fa52d8a6ad6bc914b3f7cc6dbb1baf438872df70e48b2b7b09f4a55da489bac0f1ASP+LU54ZazkCvvsbyXXBbMO3vqcnVN3p/BAikPYAI=','ea9ff42ea2f7563c35fae6a816cb55aae2f4d5044de97c3a802b57e86fc40a699bc3f8c909187fb49f4fd9d30ee959439cf0e3fd44114b270d0b9f89b410eb3dlHEpL71wq5ct2g7ajXfH+Uigun1wJVm/hyiYhTkj5j8=',0,1,1,140523010194),(119,-8,'852bd09ec009e8d26d152d852f3a9f7af79b3af179f175fbbc6db867b0ee81419b35dfc15811d3d5a7fdc465f0e5bc1dc159fe9f6a191d011d4f5fe7ed8c6d7eAs7IrtI/hxH+w2/2eD+8OP/NdDsSfDsPU52xFRNx3kXaJiSroUmyB2W79yGvRiuF','29ef9a726a299d6945a5bdfc86d5b399787870fa83bc93e214030fb53a09d2a88b1bf812f8c010d572fc287decc87ffa051f6d206e2a06436b2a30b9cba97adewrwltpmoJCopknM1rao9H3y9gkDh5MFQu9L4q6CTQ64=','426dd44c3190758785cd9d7c0fbb0dd7c6c2e7bee89723491861a20ac6ec3a8de0081136da07f861a1b30b1fed3276e7c296915010dcc9eb2ebb113629a207638bKzvhLmnGo+4h2kG+mKvhqgbfliVmzgEFg2fsra8ok=','318bbf9fb94d4bf5ca3e78def975ec78c66c2f187b5869e75e7b823b35e535d7043d46c72f7c6cbaa4485062f15b9d551baaae075ecbf09cfe9ecbaaaf039614qxYqSYyAROsEAWTwF7zmDg+9X/l4uPHD+4DkPNfpTlQ=','9ed8d963e4df96da70f62ae58de965de187a8f2fb3d3ba82417bd5b0d6b14bc2b384cd99ebfd385b59f32f9dfcbdc5a04b1199569f8e8625ad6bc060f922f6b1FpP20L8kbGzSv2bGmIhuaGO7mqLl+jZrAt4m2kD/rNk=',0,0,1,011618091271);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `employeeaffiliate`;:||:Separator:||:


CREATE TABLE `employeeaffiliate` (
  `idEmpAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `idEmployee` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `selected` int(1) DEFAULT NULL,
  `archived` int(1) DEFAULT '0',
  PRIMARY KEY (`idEmpAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `employeeaffiliate` WRITE;:||:Separator:||:
 INSERT INTO `employeeaffiliate` VALUES(3,102,2,1,0),(4,102,4,1,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `employeeclass`;:||:Separator:||:


CREATE TABLE `employeeclass` (
  `idEmpClass` int(11) NOT NULL AUTO_INCREMENT,
  `empClassName` char(20) DEFAULT NULL,
  `archived` int(1) DEFAULT '0',
  PRIMARY KEY (`idEmpClass`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `employeeclass` WRITE;:||:Separator:||:
 INSERT INTO `employeeclass` VALUES(25,'Probationary',0),(26,'Executive Staff',0),(27,'Senior Staff',0),(31,'Full-time',0),(33,'sample1',1),(34,'sample 2',1),(35,'sample',1),(36,'Temporary',0),(37,'Part-time',0),(38,'cszxc',1),(39,'On-call',0),(40,'Outsider',0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `employeehistory`;:||:Separator:||:


CREATE TABLE `employeehistory` (
  `idEmpHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idEmployment` int(11) DEFAULT NULL,
  `idEmployee` int(11) DEFAULT NULL,
  `dateEmployed` text,
  `dateEffective` text,
  `endOfContract` text,
  `classification` int(11) DEFAULT NULL,
  `monthRate` text,
  PRIMARY KEY (`idEmpHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `employeehistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `employment`;:||:Separator:||:


CREATE TABLE `employment` (
  `idEmployment` int(11) NOT NULL AUTO_INCREMENT,
  `idEmployee` int(11) DEFAULT NULL,
  `dateEmployed` text,
  `dateEffective` text,
  `endOfContract` text,
  `classification` int(11) DEFAULT NULL,
  `monthRate` text,
  `archived` int(1) DEFAULT '0',
  PRIMARY KEY (`idEmployment`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `employment` WRITE;:||:Separator:||:
 INSERT INTO `employment` VALUES(1,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00,1),(5,63,'2019-11-02','2019-11-06','2019-11-18',31,55000.00,1),(6,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00,1),(7,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00,1),(8,66,'2019-11-06','2019-11-12','2019-11-19',27,55000.00,1),(9,67,'2019-11-01','2019-11-07','2019-11-21',26,25000.00,1),(10,68,'2019-11-01','2019-11-13','2019-11-22',25,12000.00,1),(32,90,'2019-11-27','2019-11-27','2019-11-27',27,4521.00,1),(33,91,'2019-11-27','2019-11-27','2019-11-27',25,1000.00,1),(34,92,'2019-11-27','2019-11-27','2031-11-27',26,1500.00,1),(35,93,'2019-11-27','2019-11-27','2024-11-22',26,12234.00,1),(36,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00,1),(37,96,'2019-11-27','2019-11-27','2019-11-27',26,1.00,1),(38,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00,1),(39,98,'e5b5cc03d66736fe6ff77be6d96a1bf5570c248b6a5aa3b7ec0b9b61f73580e360d3fa8793391963e819de8ac87120dc1f5f88960c97197ad3429b8f20e42dbbNCF+tpg7fRKLcqaul65XQL5IzxM7ml3sR27/GIA2Q38=','1e57a784d33c6532346d6c6045ac4279c256581c28e656bff72ca8a37005ece89783836ff090acf6c68adbfb4d055d3431c5a21a35647ba47dfabf0242bea89bKS5LBSZIbFxzaICY9s7etcXJxtoM4Jv3BA6e1zKEg7s=','44935cfc8263e54d0d176d5d0155bf561e4eb0d9b33fd7a34ac3f14af72d02379dad0a0bb5380a4af716270d0ffbcdb0c2953c8a7a968abb3e95d10698ad76d5UQvYMIBSMG2SXCcW9McKLrNvFiLuqyX/CaliyrJxoRc=',26,'cfad804ecc2a21da175190a0ff8d73b9d465128a55fbb9c162f921ea8d1c4efb08ac36792be586e54b3b6ad06eb692be265928bbf6e84c368a1d360d4b232c8aPG1kDb9p4aiQjMU+vbJ7y/P23hhNUyaGoXSO0Exajs0=',1),(40,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00,1),(41,100,'2019-12-03','2019-12-03','2019-12-03',25,1500.00,1),(42,101,'2019-12-05','2019-12-05','2019-12-05',26,1500.00,0),(43,102,'68272cbd2253fb0923d59304fb82d63622de4f91db286229da625c44b040d22b806e01812cf9e28bc255e27fdea0404268e003ebfffe9d3234131c4e7b8b6e83Ekd6s39ngAk+TSGMabmy/18AbUll0GFVRaRiWjjcDsw=','a3e2fd34b9171067fc2f5d405bfd0687b96e955f395ac6a7e58bbd28c096abb544e814a2ac9db4011a1da4bbf8ad4fcbe976163aac79c05f3c4431caf1914252Xs57trsopbD4Z8zYWTX6UXWW33/7fn8Rzp/POSxKM54=','d11ea6771d430cb855d7f6617bf28e2db515294e7950d6cc4fd0668719493029ff4b8452fdc21f5f6fd455b83a3040d5c829093e6e55e4e0f776933a781b8c7dGEihuCAEJPRuKyICsBtfCAg311oi583l5eFSMFpOx9U=',26,'fde06c851fba7e3a51f1aebc6e721680d6a18f91a2aef1ee50db871171750b74050acc73454ec95c2ea7d994137ce1df42fa7d1e15c239dd0e1dad0c4ab0a215w6xL+TaUMAdTcvnkMN6RKatt0/6QJX4xX+kTjwuGmBI=',0),(45,104,'2019-12-17','2019-12-23','2020-02-21',26,12.00,1),(46,105,'2020-02-11','2020-02-29','2020-02-29',26,6.00,0),(48,107,'2020-02-18','2020-02-19','2020-03-07',37,1213.00,1),(49,108,'2020-02-18','2020-02-20','2020-02-28',36,123211.00,1),(50,109,'2020-02-24','2020-02-24','2020-02-24',37,100.00,1),(51,110,'2020-03-01','2001-07-08','2025-04-01',26,5000.00,1),(52,111,'2015-01-05','2015-01-12','2022-12-31',26,15000,1),(53,112,'033b4845595ccf5f8113e7016b60a743857a1c86d4053d8165e9671ffae61aac9293be745e5b9a7a856651b0d1d5ef4f05f57da1fba213766e6a8389b820510dslM7fxek2pJAdTM0ze5RBkegDZcihq9yvm2jkNr9K8c=','85cb0c13c0d72c48f683dc4c774eca37bb3339eb7056a851195f6bb0aad92841dcbef8203d8bb4079d3ac01b0e12ac1fa5a034961f25786cf6b7b64680503aaesSjxYIzrQgxWgZSBb8BD++c7+14ctOtJAGwXZ/eTIU0=','cf40e43566e91635b7e24e11d6182c69dacfd1688879074e30a55865e609d21066adc2645a2d37132ad433b7bb895c5cc7a0edfcb33ac863bbeefff5941dc49eBc1cMi8ih4ZZMPdfktEAfQx6SHYYm4txn+FzBzpMQEc=',26,'6e90d7f88c20a2d8582065b3f98d5c66352eed51bd03a3fae817d4e6b2fded1ee35e69e4c0b8db8c93f5d097fb794470619650bddd6dd16f2f0df8318af4e9c3GTI3Y1ZT5wqXAJ1Sr7aFbD5U2X/t23VMOsokg86gDJ4=',1),(54,113,'f3b03c4e5563d166c1fdea7ea371a38a5d460249dda1a824cbe59f3dc0e755005467a2f5f824198c84b253496fc9a4cded40077928d68f5f8212c4567bc32869oKSZh9+dY02chhh/V33XLd/Nu/lEJ/5po1xPQvqWomQ=','a43d0deece3a5ddb9a969412402baa4d41d079185a34d85e209952fce0c76f1515c4e9a76bb101a66c1fdaf773b7405392830f6730d20aca199be80e38670d59Kpth7OAclNRCBL0XCqKffu6o/poXRZa1kvEy40NRkzM=','c29dabf4cf41487dc67d6ed0d956c3fab1bd513fd9f4388f013a0fdfa30eccf3202b843ee02f00022e05deae40e82f226a161b95e3d109b2ad287028e6170dac3I0kJY0YY2onSvJpZo4e5iDQTIC8bV7lCYXVXdH0mls=',26,'9532d24332089667bf7f427134d4dee95000ae700706c2fd93bd7ddb989aa35678b6145cd56b0040194efd21e8ad90499d98b1398998b36a42e2f3b2a9a1b4a9T4D5rK81w8DM0+60zMy1qHcd+fm4REGpym/PRMLPzO8=',1),(55,114,'ee4c697a7fe1757f17e12eeeb76e6ed7a544cb5d77b9f01a0d65c890978724bfe9708ed6e3114509d090659c36041479a55514e49442b47f50f6c338e90a2df3q3gLDeu5m3qm9k5zoXPlrqSaQorxUU+sZBcZMirhDMM=','3ae75d3c3331a6f5f36f0bac34fe328eb1e6c13360670ab2b1b33c04b54f1e1e0abafefe1da777d1e94860d9deb2384e01e5e89060ff8097736498907ac847c7aAQLOwuIGWBudwF5EQ2BrNmDh+wGNMgNMSzx5zVThMM=','809c3045c9eb3f227809082f487899af70516b76e15079d3604506e0513c57567eeac5eab22fb7cf19904baf0c2c1a1fed1b0ef1fc5f41666cbb5be41964c13eTzfER2hBp1D7kTndwdMWdVaKWjz9Uhci8nzJbhDtHiE=',39,'28461050a96b08a3cb91a9b50d596084e96b6f4c7d38b12124837049bf94a455e84e21588b3260fee4b23edfba5bba453c17d1d986a642ce2bdd6043270402cfcOT1mxwyfax+pkGmkkhJGtXOX6C7Wm/VKQka4+uvT4c=',1),(56,115,'5c9db706e67f1ab48eff00dd39ea72cdf5c1fe5a690f9ec7085aaf19631153a4cf29edd173afd670896c94edd082fe8b77e55b2dac55ea2be0bbeb3530b97c6ezBc0raBqGzGclHry7l9lTrz6GYpS0lNADMQ39n3yVXg=','ce3ba63bbb2fdd94f0ec30af14acbe03c6d3fce65219ed1d0592032d3c8161901a5b79fae8facac77972dc5b1cb4ee18e6c9eb93e386c5cd638d27e2e34caeb0bxgM2PEAcCVL6jnddew/WHa8hHE/39PXBZcOQm6yB+g=','3e7357bb112c599eec878282e68f459dc7a2bd0adb3864b6a3176dd5cc4fdd2742d189e707b6b585c3be5489cd9163ea3b17e1ed71f30a3c82b287774957d5704BLZtFT0f1EpYJDeiHodmxm+2cUgi2OSlzvOtmpqN48=',26,'ffa24e8dab56f86a6deb0fedfdf1c049d144d76aa1a94e80f9b97acb185dd113102ba0970cc1525b99e4fb456fdd62c03eded84131d255ee61d25c7c0e82cb4f6egm+TsIZb5ne4lfzCelYzYI2xOkCzdvHKMQqQWwDA4=',0),(57,116,'2cf6377b4658d0da1c7feb221ef7ab76e139f5e4585479522a71b31167c693fc4a19f1637615b3448259f15b53a5fd2c42194c7f0c69735b4cfb3040d2555a730n39OPEsUB6OJX32EH1VmqpQwtPTYyXzcj0w1H7YrVM=','a298e314916696f2d860ef5d2da1afde5f1e100f978d8993ad5f08ff0304599620a8ab014ae5d7668f373b20b18c7bdbeab401604fa86eabe80fae6f8b02286dBYqUdOXT0ygF0A+YlTvV6k7wvH9YcOIyevD7KZpldMo=','6d3b239d49d39480270c819b13df246050e9fa37d6443d689b0553d4a0c3ef8458fbec9be4c9d578a1f660e68ed8d13022655a614c6d8acd79bee6d8fa1622f99H2Q3X08J1dWAmiRcwU0xJYzSjmWLHWt+mrNGEVnSOA=',27,'edf1560c6496c69cc36196068f27fe43c9143b2bc60bffde5d4ee9e1b77810fcffa198fc8f5e75681cfab1d75a574d12ebd59792a149f5f967ade2ef848234b5WU7umFtjUNFiP8UhwB37IV9e/0lA5ReTYnS9gahL6Rs=',1),(58,117,'15940c489c18e64f9e49ef458685a7223deded2bb6badec94a5718e5254a01bb303607931681f292cc4d570e3ffb064126f67af58bc5ad21da8793f60d3a97aakJHj1jXfy9DmfK0VVHPlUry3PZrZb0Go3MYJGtpfiMs=','d7b56f2fa1ace160c5f9d89b3ef3e8627f4751ca10c7b87647b7a4036dbfc150b46e7ae7cee6c6f3a73d47d605abd6016f0a842cdbd0dabb8ec12a9a2cf15df3zW8e3xnnb0Z0qejzh04MXQc3NUumRZ7TbBlOzeNcSo8=','ba5a6c5af39fff045387e6a844f610f258ca92f0c35cbbfeb20cdec71c199af165a289044b91b00208b59b858e17ba43ba2e1ab6ea276bcfc8c8b5ca3b603002tkaaBdpG6gaiZMXDbumVnRCxLta1oDOsd3ZQbxz0z/E=',26,'03b6d16132e92f4e800dcf448a39e30687d0f5d265c8ec6e9d49961b079b2383cf0ca939898c49e19a45c1516314365b703d523c6e3ece0bb94aa5d6d52f52c8SrvewytCb1XVQM4EljNoQ6E6LFIJAGoSbx5KZVvdGZw=',0),(59,118,'2ada00ba3c7beff6d5732f098d64195923a1911ba3b4a162c7ee967b34b3e1326112967dccbdf18d554da2dc07961751ec52e7a714657dc0fb8b278a356aeb31sYHrODjkqns7E/cOsnrF2ZhLZdknudEQcxMxwe41UpQ=','116adb008d16fbe04f8e8ce5ecce34f62650c17c45e36b1614471faaadb1a11faa1dca1ff3cd9059afb165229bea69e5a662eb7579391eda7c54ad4e56a25607qG9YbE852iAq0xrE1NYtUayp9bK1kiVI77nBh+ZymII=','600eac99fa5ed81d4da3b14ad117e2f898e93e0ce466d91fbff2867ae07f4d5b61abb23ed69f9bfe2f021b6db0049e40093aee8fc06a2dfdb4da5e3b329cc57fTVQTBPqotyDP9PSyAh4PXidpsTLaBmjKJXy9YpjdIjs=',26,'01d945b95bc7aa1c08e9700972e17f612ad52f3245adeb227d4306a520def0b95838366cd5edc7fae6290aa07a3bc51b4b453b5d8adde2d3a90e7825807cc2c6UK7Eyak2lTSneZWgtTpOtRejxFDnHyv9i2YePz4YcyI=',1),(60,119,'1a263c9fdd54d7efa678d65bf45212bcc22dc632528ad16d823b0dde40dbba57c0b044a48c33e45d9d5d606627daaded7c4ec8d13236cd9f372b9c9ee9081ecctDAGXLo5FXXA5V3qNqP47Yx4rdATF+EU8PMHkho1A7M=','6271f96fed5d8bd3588a06fb3db7c943d53ea8383dbdab8bdcf13370956ca7891090ca8caf9979799a14285fad572754be5752390e772e60eb9d16eaa239cc63JcxQtkA5tQzhnR7Dv0HgyW87/d1dVWm2UQRoGRcYNTE=','c6982be94a1db7a5a15289f89f2e4e18f221a477db9b629a6c7c3e9f13f9b8e651a6bdd218c804c9cfa5081d7720885c869997b936413aaafcbc60d0f5a9bc16E5/GjEVYG+myvsLWhY22ZenK05JISPT11AEcepbOf4E=',26,'d61f45f04fb83f0e2e637dbae2216aa39756fe3d19bc93a2d74cb44186040fad5dabf9f0d49fb261ab472dd06921f527d74fe3252d193c8b27fe52842f7bd09bOmCU4Ynby+TVGemXG96lJVtIRMzm2Lu/uMnbzubMSew=',1);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `employmenthistorydate`;:||:Separator:||:


CREATE TABLE `employmenthistorydate` (
  `idEmpHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idEmployee` int(11) DEFAULT NULL,
  `dateEmployed` text,
  `dateEffective` text,
  `endOfContract` text,
  `classification` int(11) DEFAULT NULL,
  `monthRate` text,
  PRIMARY KEY (`idEmpHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=198 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `employmenthistorydate` WRITE;:||:Separator:||:
 INSERT INTO `employmenthistorydate` VALUES(1,59,'2019-11-01','2019-11-06','2019-11-15',26,5500.00),(2,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(3,59,'2019-11-01','2019-11-07','2019-11-16',25,5550.00),(4,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(5,67,'2019-11-01','2019-11-07','2019-11-21',26,25000.00),(10,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(11,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(15,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(16,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(17,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(18,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(19,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(20,68,'2019-11-01','2019-11-13','2019-11-22',25,12000.00),(21,68,'2019-11-01','2019-11-13','2019-11-22',25,12000.00),(28,68,'2019-11-01','2019-11-13','2019-11-22',25,12000.00),(42,90,'2019-11-27','2019-11-27','2019-11-27',27,4521.00),(43,91,'2019-11-27','2019-11-27','2019-11-27',25,1000.00),(44,92,'2019-11-27','2019-11-27','2031-11-27',26,1500.00),(45,93,'2019-11-27','2019-11-27','2024-11-22',26,12234.00),(53,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(54,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(55,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(56,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(57,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(58,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(59,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(60,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(61,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(62,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(63,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(64,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(65,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(66,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(67,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(68,96,'2019-11-27','2019-11-27','2019-11-27',26,1.00),(69,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(70,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(71,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(83,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(84,96,'2019-11-27','2019-11-27','2019-11-27',26,1.00),(85,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(86,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(87,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(88,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(89,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(90,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(91,66,'2019-11-06','2019-11-12','2019-11-19',27,55000.00),(92,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(93,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(100,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(101,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(102,1,'2019-12-02','2019-12-02','2019-12-02',26,1500.00),(103,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(104,1,'2019-12-02','2019-12-02','2019-12-02',27,5000.00),(105,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(106,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(107,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(108,1,'2019-12-02','2019-12-02','2019-12-02',26,1500.00),(109,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(110,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(111,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(112,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(113,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(114,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(115,99,'2019-12-03','2019-12-02','2020-08-21',26,1000.00),(116,100,'2019-12-03','2019-12-03','2019-12-03',25,1500.00),(117,101,'2019-12-05','2019-12-05','2019-12-05',26,1500.00),(118,67,'2019-11-01','2019-11-07','2019-11-21',26,25000.00),(119,67,'2019-11-01','2019-11-07','2019-11-21',26,25000.00),(120,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(121,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(122,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(123,103,'2019-01-01','2019-08-20','2020-09-30',28,5.00),(124,99,'2019-12-03','2019-12-02','2020-08-21',26,1000.00),(125,99,'2019-12-03','2019-12-02','2020-08-21',26,1000.00),(126,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(127,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(128,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(129,104,'2019-12-17','2019-12-23','2020-02-21',26,12.00),(130,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(131,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(132,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(133,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(134,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(135,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(136,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(137,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(138,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(139,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(140,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(141,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(142,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(143,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(144,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(145,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(146,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(147,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(148,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(149,105,'2020-02-11','2020-02-29','2020-02-29',0,6.00),(150,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(151,105,'2020-02-11','2020-02-29','2020-02-29',26,6.00),(152,106,'2020-02-13','2020-02-13','2025-02-13',28,5000.00),(153,106,'2020-02-13','2020-02-13','2025-02-13',28,5000.00),(154,63,'2019-11-02','2019-11-06','2019-11-18',25,55000.00),(155,63,'2019-11-02','2019-11-06','2019-11-18',29,55000.00),(156,63,'2019-11-02','2019-11-06','2019-11-18',31,55000.00),(157,107,'2020-02-18','2020-02-19','2020-03-07',37,1213.00),(158,107,'2020-02-18','2020-02-19','2020-03-07',37,1213.00),(159,107,'2020-02-18','2020-02-19','2020-03-07',37,1213.00),(160,107,'2020-02-18','2020-02-19','2020-03-07',37,1213.00),(161,107,'2020-02-18','2020-02-19','2020-03-07',37,1213.00),(162,108,'2020-02-18','2020-02-20','2020-02-28',36,123211.00),(163,109,'2020-02-17','2020-02-20','2020-02-22',37,1121.00),(164,109,'2020-02-17','2020-02-20','2020-02-22',37,1121.00),(165,106,'2020-02-13','2020-02-13','2025-02-13',37,5000.00),(166,108,'2020-02-18','2020-02-20','2020-02-28',36,123211.00),(167,109,'2020-02-24','2020-02-24','2020-02-24',37,100.00),(168,109,'2020-02-24','2020-02-24','2020-02-24',37,100.00),(169,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(170,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(171,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(172,90,'2019-11-27','2019-11-27','2019-11-27',27,4521.00),(173,110,'2020-03-01','2001-07-08','2025-04-01',26,5000.00),(174,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(175,105,'2020-02-11','2020-02-29','2020-02-29',26,6.00),(176,105,'2020-02-11','2020-02-29','2020-02-29',26,6.00),(177,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(178,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(179,111,'2015-01-05','2015-01-12','2022-12-31',26,15000.00),(180,112,'0000-00-00','0000-00-00','0000-00-00',26,9999999999999999.99),(181,113,'e4e2359cfd173a0afc96860e9696953d5083ab3de97b6c1a48d7716709247fa855951f4f97910dc32eed5aeb8308d398ba00b2fc2039b4a66d2566ca52ecee475gFONLlbh2h7Ka2gKk3NCF64f8fB3S5vWJ7JlwNHDMM=','5d9a4d4b3b9db1b904a22f38a16eac7b5f3034001ceb6aaa06abc257c8587cdb61be8c7f57db1bac3a5bb9b8c03c591544049f62d12f0d08f909a814c7a3ce9fRBOiHgFcF8f5gj7269zGyOWID5y+qLM8mVeVhZcLsGY=','1aed58f89092bca34acc0b8e3521d82984fe41e0c908fbf893c3a7b976946fa56cabda7a083f13bb9f3ceaed5300d4a885fb91f8e8709f436f146cd2dcbac1a3BusAaFyItoWGL2y7TrFTUcIUdsKax3R+y40ym46g1fs=',26,'cbff2fcc70d36e63c24084e2206019f94c29cab2d5d629ef0023272fc5f82b5eafbc22a1fe191bf949ff8e83976d413744e133a9ff7458fd6b32e524dd1c871a8xXdG71gKo7P0Zl1jopBR6zREInAUH3c4FRpTJ0TWqQ='),(182,114,'ee4c697a7fe1757f17e12eeeb76e6ed7a544cb5d77b9f01a0d65c890978724bfe9708ed6e3114509d090659c36041479a55514e49442b47f50f6c338e90a2df3q3gLDeu5m3qm9k5zoXPlrqSaQorxUU+sZBcZMirhDMM=','3ae75d3c3331a6f5f36f0bac34fe328eb1e6c13360670ab2b1b33c04b54f1e1e0abafefe1da777d1e94860d9deb2384e01e5e89060ff8097736498907ac847c7aAQLOwuIGWBudwF5EQ2BrNmDh+wGNMgNMSzx5zVThMM=','809c3045c9eb3f227809082f487899af70516b76e15079d3604506e0513c57567eeac5eab22fb7cf19904baf0c2c1a1fed1b0ef1fc5f41666cbb5be41964c13eTzfER2hBp1D7kTndwdMWdVaKWjz9Uhci8nzJbhDtHiE=',39,'28461050a96b08a3cb91a9b50d596084e96b6f4c7d38b12124837049bf94a455e84e21588b3260fee4b23edfba5bba453c17d1d986a642ce2bdd6043270402cfcOT1mxwyfax+pkGmkkhJGtXOX6C7Wm/VKQka4+uvT4c='),(183,115,'5c9db706e67f1ab48eff00dd39ea72cdf5c1fe5a690f9ec7085aaf19631153a4cf29edd173afd670896c94edd082fe8b77e55b2dac55ea2be0bbeb3530b97c6ezBc0raBqGzGclHry7l9lTrz6GYpS0lNADMQ39n3yVXg=','ce3ba63bbb2fdd94f0ec30af14acbe03c6d3fce65219ed1d0592032d3c8161901a5b79fae8facac77972dc5b1cb4ee18e6c9eb93e386c5cd638d27e2e34caeb0bxgM2PEAcCVL6jnddew/WHa8hHE/39PXBZcOQm6yB+g=','3e7357bb112c599eec878282e68f459dc7a2bd0adb3864b6a3176dd5cc4fdd2742d189e707b6b585c3be5489cd9163ea3b17e1ed71f30a3c82b287774957d5704BLZtFT0f1EpYJDeiHodmxm+2cUgi2OSlzvOtmpqN48=',26,'ffa24e8dab56f86a6deb0fedfdf1c049d144d76aa1a94e80f9b97acb185dd113102ba0970cc1525b99e4fb456fdd62c03eded84131d255ee61d25c7c0e82cb4f6egm+TsIZb5ne4lfzCelYzYI2xOkCzdvHKMQqQWwDA4='),(184,102,'7c359d9dafa37becc023494dad26eeee87b85bb210d5c49e5164a0675bdebe6d926113508282d951a12ffa983b63c2167f3998fdce3c267d4777524ff8120aa47RJPs4YYiY4Q8gvPljE/JH/Af7NUJKgDbq1RmbVrlZU=','8a3f3ab1a8961f86699946a08fe032fd3f856ff84d0c7e59075cb4ce768d6541defad35404f4ada387a5639e2b65386938a925e702a3aa132709d1f928f132f0s0uhBg8BCs5uz1wEhWHZHRHWRamBYb2VfHHyccsL5hg=','592f4e0c7edaa0cd8797eefe832ada78e402581d45c50139a80eac468f2afcc2d1bff66322bbc2db78f004468164415bad7263f1cdd63ae763779bb927991cdaKXU8C9Xh2g0ZRcRmwgAeeIxaQn2ZBQ1LFzBgO2q69TA=',26,'2907a24854a951774e33aed5e4d8d8cfc82114931e031e58699c018562aaba127ae68acff38ed2e3072fd6c6c94d9ae85b21858df22f214482196e7a4628d467AI+RsgUNwRgiJYpa57wdwXHSFzwwupOgWQSpZNHg+dw='),(185,116,'2cf6377b4658d0da1c7feb221ef7ab76e139f5e4585479522a71b31167c693fc4a19f1637615b3448259f15b53a5fd2c42194c7f0c69735b4cfb3040d2555a730n39OPEsUB6OJX32EH1VmqpQwtPTYyXzcj0w1H7YrVM=','a298e314916696f2d860ef5d2da1afde5f1e100f978d8993ad5f08ff0304599620a8ab014ae5d7668f373b20b18c7bdbeab401604fa86eabe80fae6f8b02286dBYqUdOXT0ygF0A+YlTvV6k7wvH9YcOIyevD7KZpldMo=','6d3b239d49d39480270c819b13df246050e9fa37d6443d689b0553d4a0c3ef8458fbec9be4c9d578a1f660e68ed8d13022655a614c6d8acd79bee6d8fa1622f99H2Q3X08J1dWAmiRcwU0xJYzSjmWLHWt+mrNGEVnSOA=',27,'edf1560c6496c69cc36196068f27fe43c9143b2bc60bffde5d4ee9e1b77810fcffa198fc8f5e75681cfab1d75a574d12ebd59792a149f5f967ade2ef848234b5WU7umFtjUNFiP8UhwB37IV9e/0lA5ReTYnS9gahL6Rs='),(186,102,'0838932e0a68cc11197cd5423e15438765df9ab065ba157a9dde0a260bfbc18acd19d2fecda1ca5b624e1b76d4b767d4f02dd75a40d90f652724b62a91d984c9/GWAq6pegb0UXk7Sz1yjzNhpwuB6xwyaS7zpkJ7oYfA=','b9a72379a0c92443e8a6eaea1022dc42ef9fd75f654d270d649c0155c89020770eb9fcb7a3b5cd9b997508e01abdb722d03d72a660df3e38cb33fc56783d898frkfgUsmtJvQ0CB1cEkYXEJcNtWz4nYNTYTfA0cdXD2M=','1004190c94640ca69b60b6dc1d26d1b745f9d1e8750d70f1f0108a4fbdcd541a638d580746b33e12fbda3f044457ec75829837a1d2eed1a1938dd87b7ebd3893RB8HAVlMM3o39Dge3XSm5q2AdeySvoZCenUj81V08o0=',26,'66863cd9688668aff5402c1ae00f8c90e3ce8719745ad06b2318a52ad11e0b9ef82f5d63514c1356826734892823749a7a54971c4720bb8354b5c454a742fe7fPnby1jyZc/iaYI4EOiStPd+siY4zTHMqKIbj7y7A6hg='),(187,117,'d703f670eef5aa5ceeda521bb5f801daef33de7632e702c67aa3e02e13e6478882cfd1b5c543de9da33b970358ab582b2b8de9c186c77545878f8d5d783b957f6CphJD2mICEXaFF24wXVnhX3fJdbIIgkoAHvoB28urg=','2ce9edc3d8baff49bff916c7cb82ae625c24b72631829c7131e430446ddff8b2eb4877a43623a5162a6a7523df75fda134658dbb49ae5be7606ceef39a63ca1b6qPezOquQFwYTDeP59cF1HzxC2Z8rx6ZqbtNJi6+WFQ=','adecca37d2e6892c12911090e326adab2714e26dfd45fef45e85a6a591bd0ad03bc53deb367d65c4cc1bad157a4aef43fba790703103eb59f445cf53fdb04ad0FvX5kGIf2cnbrwjqhCvdUk8JnLc9PcWZlKuggRLvbq8=',26,'1c9577481b348b6d6eb62d0eca644def06d642195159f350b7d55dd81a57828487061fd34acd6e3e964976c26b180772609463763dd8ecc9377b8fec6949621ansKQvfyYPIeukjlLzHv4ajehFNIjdKFGOB85u7WFhFE='),(188,98,'e5b5cc03d66736fe6ff77be6d96a1bf5570c248b6a5aa3b7ec0b9b61f73580e360d3fa8793391963e819de8ac87120dc1f5f88960c97197ad3429b8f20e42dbbNCF+tpg7fRKLcqaul65XQL5IzxM7ml3sR27/GIA2Q38=','1e57a784d33c6532346d6c6045ac4279c256581c28e656bff72ca8a37005ece89783836ff090acf6c68adbfb4d055d3431c5a21a35647ba47dfabf0242bea89bKS5LBSZIbFxzaICY9s7etcXJxtoM4Jv3BA6e1zKEg7s=','44935cfc8263e54d0d176d5d0155bf561e4eb0d9b33fd7a34ac3f14af72d02379dad0a0bb5380a4af716270d0ffbcdb0c2953c8a7a968abb3e95d10698ad76d5UQvYMIBSMG2SXCcW9McKLrNvFiLuqyX/CaliyrJxoRc=',26,'cfad804ecc2a21da175190a0ff8d73b9d465128a55fbb9c162f921ea8d1c4efb08ac36792be586e54b3b6ad06eb692be265928bbf6e84c368a1d360d4b232c8aPG1kDb9p4aiQjMU+vbJ7y/P23hhNUyaGoXSO0Exajs0='),(189,102,'2dc05f1be7b693b1e0ffe1c5f0144630875eacf95ea13c85430bfda53cb1dba66a8395cb1efa3b3ea72e62baaa082d938373d2e9e19e41448b42da9a1f27ea74gBNpu7l51nr/CN3uKG88OKkFrjWZAfrU13j+k9bODEs=','f26e84f90bb17ec7474a2d00892eb7a9d838e3f3f7a149d6c0f272320fc0d90b1d670ce927dc6da54c5082c8fda7c823592e4e754a5e04cc6e77e7d13148832fv7qFNbCClURzY7/9EbsdKuElFdZ2X8ERHbuF1OzfKQE=','7cb822458de05d42ebd6f3f122bddd67e3d8a36f5e0e7c5206df914210509efed4aa2041b339584e63b4bdd7c8326e45fd2f820888d6d301818ea82eea7950a8EL1tousm9B8FER6LCtWMNMNb0acdLJlnuBLAQba4iqA=',26,'e2fa71ba6c967f5c5ba6f7ec5fb39cfd320c78b86e8a8cfb770ccb73177633462f030d75965075372b40bf0a26fbd12c2781968cd4e6bce6d15edb7582076e17LxU+7lJyQzZuPQmYGuNnu9Aq4LEuQZ4hf4ZlRils9PU='),(190,113,'f3b03c4e5563d166c1fdea7ea371a38a5d460249dda1a824cbe59f3dc0e755005467a2f5f824198c84b253496fc9a4cded40077928d68f5f8212c4567bc32869oKSZh9+dY02chhh/V33XLd/Nu/lEJ/5po1xPQvqWomQ=','a43d0deece3a5ddb9a969412402baa4d41d079185a34d85e209952fce0c76f1515c4e9a76bb101a66c1fdaf773b7405392830f6730d20aca199be80e38670d59Kpth7OAclNRCBL0XCqKffu6o/poXRZa1kvEy40NRkzM=','c29dabf4cf41487dc67d6ed0d956c3fab1bd513fd9f4388f013a0fdfa30eccf3202b843ee02f00022e05deae40e82f226a161b95e3d109b2ad287028e6170dac3I0kJY0YY2onSvJpZo4e5iDQTIC8bV7lCYXVXdH0mls=',26,'9532d24332089667bf7f427134d4dee95000ae700706c2fd93bd7ddb989aa35678b6145cd56b0040194efd21e8ad90499d98b1398998b36a42e2f3b2a9a1b4a9T4D5rK81w8DM0+60zMy1qHcd+fm4REGpym/PRMLPzO8='),(191,102,'6dddb68493b95cef4e3f6d528e0e7447d1309e9ff5139be0ef653bc640e8bea0bf3bee5d4e8519dff88429e35dd7e62ffbd4f676e685f0274eb07c30be905a64+z0UUS9yCmzObn5fC+LJpcxk/DuJMmvxMrfI1EoUbYE=','174914ccf62484f35b689530cf046e845d3caabffa4f5a73e298c9a80965ec52cfb0bbe68860931686a115ee6f09e4773ce7636a9125aa595a56c94d7d1c551auVPnO08KTkWLUbY6ZEegLksFOyzN42pGYiyNbgDg0lo=','ab440c21a1545f1f31e140a634a9e7e8471d74802030c9a5768f468418ae0b3c97726db1ae603d63247c3c250070a978bf973d02777ab0902d366d86a886cb52FXIvA5YKTttn+LaMVS7mkllY3ELtfRhlydYtHuj9qQM=',26,'711887127688aea3af227d983a3918be4749546a761b930acfa75bf067c38c5cf3c7d85196e288809d46af38c4b72df5a861506ff49942193bb513ce99a26214cpDOJn9FoFlHuB3yCUnE055E0YTMg64GJgHlzRJlp+Y='),(192,118,'2ada00ba3c7beff6d5732f098d64195923a1911ba3b4a162c7ee967b34b3e1326112967dccbdf18d554da2dc07961751ec52e7a714657dc0fb8b278a356aeb31sYHrODjkqns7E/cOsnrF2ZhLZdknudEQcxMxwe41UpQ=','116adb008d16fbe04f8e8ce5ecce34f62650c17c45e36b1614471faaadb1a11faa1dca1ff3cd9059afb165229bea69e5a662eb7579391eda7c54ad4e56a25607qG9YbE852iAq0xrE1NYtUayp9bK1kiVI77nBh+ZymII=','600eac99fa5ed81d4da3b14ad117e2f898e93e0ce466d91fbff2867ae07f4d5b61abb23ed69f9bfe2f021b6db0049e40093aee8fc06a2dfdb4da5e3b329cc57fTVQTBPqotyDP9PSyAh4PXidpsTLaBmjKJXy9YpjdIjs=',26,'01d945b95bc7aa1c08e9700972e17f612ad52f3245adeb227d4306a520def0b95838366cd5edc7fae6290aa07a3bc51b4b453b5d8adde2d3a90e7825807cc2c6UK7Eyak2lTSneZWgtTpOtRejxFDnHyv9i2YePz4YcyI='),(193,117,'15940c489c18e64f9e49ef458685a7223deded2bb6badec94a5718e5254a01bb303607931681f292cc4d570e3ffb064126f67af58bc5ad21da8793f60d3a97aakJHj1jXfy9DmfK0VVHPlUry3PZrZb0Go3MYJGtpfiMs=','d7b56f2fa1ace160c5f9d89b3ef3e8627f4751ca10c7b87647b7a4036dbfc150b46e7ae7cee6c6f3a73d47d605abd6016f0a842cdbd0dabb8ec12a9a2cf15df3zW8e3xnnb0Z0qejzh04MXQc3NUumRZ7TbBlOzeNcSo8=','ba5a6c5af39fff045387e6a844f610f258ca92f0c35cbbfeb20cdec71c199af165a289044b91b00208b59b858e17ba43ba2e1ab6ea276bcfc8c8b5ca3b603002tkaaBdpG6gaiZMXDbumVnRCxLta1oDOsd3ZQbxz0z/E=',26,'03b6d16132e92f4e800dcf448a39e30687d0f5d265c8ec6e9d49961b079b2383cf0ca939898c49e19a45c1516314365b703d523c6e3ece0bb94aa5d6d52f52c8SrvewytCb1XVQM4EljNoQ6E6LFIJAGoSbx5KZVvdGZw='),(194,119,'1a263c9fdd54d7efa678d65bf45212bcc22dc632528ad16d823b0dde40dbba57c0b044a48c33e45d9d5d606627daaded7c4ec8d13236cd9f372b9c9ee9081ecctDAGXLo5FXXA5V3qNqP47Yx4rdATF+EU8PMHkho1A7M=','6271f96fed5d8bd3588a06fb3db7c943d53ea8383dbdab8bdcf13370956ca7891090ca8caf9979799a14285fad572754be5752390e772e60eb9d16eaa239cc63JcxQtkA5tQzhnR7Dv0HgyW87/d1dVWm2UQRoGRcYNTE=','c6982be94a1db7a5a15289f89f2e4e18f221a477db9b629a6c7c3e9f13f9b8e651a6bdd218c804c9cfa5081d7720885c869997b936413aaafcbc60d0f5a9bc16E5/GjEVYG+myvsLWhY22ZenK05JISPT11AEcepbOf4E=',26,'d61f45f04fb83f0e2e637dbae2216aa39756fe3d19bc93a2d74cb44186040fad5dabf9f0d49fb261ab472dd06921f527d74fe3252d193c8b27fe52842f7bd09bOmCU4Ynby+TVGemXG96lJVtIRMzm2Lu/uMnbzubMSew='),(195,102,'106357dc1c0e9675ddf18b944ac76697a169ef06164861fb7a9e54c5a516b6f34dc1618bb7bff790c04fe7e8535fb97635fa3a952e3ae456d6fee6183a7c55dbgbDnB7IFmqPHhbGMHuTna3gOJBimdNwK+AOwg7n157E=','cb849f13f89cab284ea652cfa7e77c0defb4b42588cc7fc2b800b062c1fb52053f0e6348e7c7b40523a3b1dcaf528f5cda0356d48399bac07e5b8ad1b77c79c8I3GxzyybvNIN61BxrAbm7DeaGcoMcdofqaAAJSQK1oE=','01cbce06ba5175fb4850c45c410ec07189cbef4cde32b9cd16934f1073e80660d33e9c8c4617bb273a95828d8033733884578d3242847b87494757c11d872ce2nZYJVdBRXX5TQ+IjR9mY66nxH4rFsl6Nj64kiVWqT+Q=',26,'647fc5cfd82e72a3937eea3297f78186c21896b3655ebbf39178898df009275f9933f8bee452dc830279c6dea17a15738adcfa540efee39efca0ec3dde4213c5XduHDBwTAvTMNAcZ7F5YZ88pHV6QhNxwbzxVXIjCFAo='),(196,102,'0f100561add0f99a80983465f8e4e3a08972e26480456abb152a850108278e9b8c678e2d0649ff42e1bef0139bcb047efad14f9b33fe931e60fad552800c498cWQEePyw5KnH4sRLP1Nmg6lE+R3fEOFtNnjxSwfI6Cs8=','dbfec3ec81b3fdd65dfba60ab84b2ac9bf7e98cbc03fd324cb54999d5263cdd118f556c38a84263915c9d465be365d65609654474b2cd4b0eb7a0baedf990bc2iRHlwClcMDiIlv3kPiHMLELctS08lKyH7kE0XHkDZJ0=','9b82bfc992157d229927e8bdeba78a003d81cc6cb89f7c5d56c3d0e57851e25f81e241ae6a53b149ed912ef088239dc121dd4668412d92c4a300f0904e43b9ccT+7jNRMk8pXeJjtMvz8yrWbv8HStNgHtYK26su6EI7Q=',26,'5a68d40f8828ce90ed96832360ed0067cdb4f39c7b26da78f15d5601d34897bc24c8df9719d2ddb69c1d1d269b86c063d8898168f6fbcfd29d2e2e2b5989ba39DOJ7LI7TlGGpbNxhEbf4D68Fr2o+DhOxSrMAuE8ys9Y='),(197,102,'68272cbd2253fb0923d59304fb82d63622de4f91db286229da625c44b040d22b806e01812cf9e28bc255e27fdea0404268e003ebfffe9d3234131c4e7b8b6e83Ekd6s39ngAk+TSGMabmy/18AbUll0GFVRaRiWjjcDsw=','a3e2fd34b9171067fc2f5d405bfd0687b96e955f395ac6a7e58bbd28c096abb544e814a2ac9db4011a1da4bbf8ad4fcbe976163aac79c05f3c4431caf1914252Xs57trsopbD4Z8zYWTX6UXWW33/7fn8Rzp/POSxKM54=','d11ea6771d430cb855d7f6617bf28e2db515294e7950d6cc4fd0668719493029ff4b8452fdc21f5f6fd455b83a3040d5c829093e6e55e4e0f776933a781b8c7dGEihuCAEJPRuKyICsBtfCAg311oi583l5eFSMFpOx9U=',26,'fde06c851fba7e3a51f1aebc6e721680d6a18f91a2aef1ee50db871171750b74050acc73454ec95c2ea7d994137ce1df42fa7d1e15c239dd0e1dad0c4ab0a215w6xL+TaUMAdTcvnkMN6RKatt0/6QJX4xX+kTjwuGmBI=');:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `employmenthistoryposition`;:||:Separator:||:


CREATE TABLE `employmenthistoryposition` (
  `idEmpHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idEmployee` int(11) DEFAULT NULL,
  `dateEmployed` text,
  `dateEffective` text,
  `endOfContract` text,
  `classification` int(11) DEFAULT NULL,
  `monthRate` text,
  PRIMARY KEY (`idEmpHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=195 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `employmenthistoryposition` WRITE;:||:Separator:||:
 INSERT INTO `employmenthistoryposition` VALUES(1,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(2,67,'2019-11-01','2019-11-07','2019-11-21',26,25000.00),(7,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(8,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(12,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(13,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(14,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(15,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(16,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(17,68,'2019-11-01','2019-11-13','2019-11-22',25,12000.00),(18,68,'2019-11-01','2019-11-13','2019-11-22',25,12000.00),(25,68,'2019-11-01','2019-11-13','2019-11-22',25,12000.00),(39,90,'2019-11-27','2019-11-27','2019-11-27',27,4521.00),(40,91,'2019-11-27','2019-11-27','2019-11-27',25,1000.00),(41,92,'2019-11-27','2019-11-27','2031-11-27',26,1500.00),(42,93,'2019-11-27','2019-11-27','2024-11-22',26,12234.00),(50,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(51,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(52,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(53,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(54,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(55,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(56,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(57,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(58,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(59,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(60,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(61,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(62,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(63,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(64,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(65,96,'2019-11-27','2019-11-27','2019-11-27',26,1.00),(66,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(67,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(68,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(80,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(81,96,'2019-11-27','2019-11-27','2019-11-27',26,1.00),(82,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(83,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(84,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(85,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(86,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(87,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(88,66,'2019-11-06','2019-11-12','2019-11-19',27,55000.00),(89,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(90,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(97,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(98,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(99,1,'2019-12-02','2019-12-02','2019-12-02',26,1500.00),(100,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(101,1,'2019-12-02','2019-12-02','2019-12-02',27,5000.00),(102,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(103,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(104,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(105,1,'2019-12-02','2019-12-02','2019-12-02',26,1500.00),(106,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(107,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(108,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(109,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(110,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(111,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(112,99,'2019-12-03','2019-12-02','2020-08-21',26,1000.00),(113,100,'2019-12-03','2019-12-03','2019-12-03',25,1500.00),(114,101,'2019-12-05','2019-12-05','2019-12-05',26,1500.00),(115,67,'2019-11-01','2019-11-07','2019-11-21',26,25000.00),(116,67,'2019-11-01','2019-11-07','2019-11-21',26,25000.00),(117,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(118,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(119,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(120,103,'2019-01-01','2019-08-20','2020-09-30',28,5.00),(121,99,'2019-12-03','2019-12-02','2020-08-21',26,1000.00),(122,99,'2019-12-03','2019-12-02','2020-08-21',26,1000.00),(123,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(124,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(125,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(126,104,'2019-12-17','2019-12-23','2020-02-21',26,12.00),(127,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(128,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(129,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(130,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(131,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(132,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(133,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(134,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(135,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(136,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(137,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(138,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(139,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(140,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(141,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(142,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(143,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(144,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(145,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(146,105,'2020-02-11','2020-02-29','2020-02-29',0,6.00),(147,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(148,105,'2020-02-11','2020-02-29','2020-02-29',26,6.00),(149,106,'2020-02-13','2020-02-13','2025-02-13',28,5000.00),(150,106,'2020-02-13','2020-02-13','2025-02-13',28,5000.00),(151,63,'2019-11-02','2019-11-06','2019-11-18',25,55000.00),(152,63,'2019-11-02','2019-11-06','2019-11-18',29,55000.00),(153,63,'2019-11-02','2019-11-06','2019-11-18',31,55000.00),(154,107,'2020-02-18','2020-02-19','2020-03-07',37,1213.00),(155,107,'2020-02-18','2020-02-19','2020-03-07',37,1213.00),(156,107,'2020-02-18','2020-02-19','2020-03-07',37,1213.00),(157,107,'2020-02-18','2020-02-19','2020-03-07',37,1213.00),(158,107,'2020-02-18','2020-02-19','2020-03-07',37,1213.00),(159,108,'2020-02-18','2020-02-20','2020-02-28',36,123211.00),(160,109,'2020-02-17','2020-02-20','2020-02-22',37,1121.00),(161,109,'2020-02-17','2020-02-20','2020-02-22',37,1121.00),(162,106,'2020-02-13','2020-02-13','2025-02-13',37,5000.00),(163,108,'2020-02-18','2020-02-20','2020-02-28',36,123211.00),(164,109,'2020-02-24','2020-02-24','2020-02-24',37,100.00),(165,109,'2020-02-24','2020-02-24','2020-02-24',37,100.00),(166,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(167,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(168,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(169,90,'2019-11-27','2019-11-27','2019-11-27',27,4521.00),(170,110,'2020-03-01','2001-07-08','2025-04-01',26,5000.00),(171,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(172,105,'2020-02-11','2020-02-29','2020-02-29',26,6.00),(173,105,'2020-02-11','2020-02-29','2020-02-29',26,6.00),(174,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(175,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(176,111,'2015-01-05','2015-01-12','2022-12-31',26,15000.00),(177,112,'0000-00-00','0000-00-00','0000-00-00',26,9999999999999999.99),(178,113,'e4e2359cfd173a0afc96860e9696953d5083ab3de97b6c1a48d7716709247fa855951f4f97910dc32eed5aeb8308d398ba00b2fc2039b4a66d2566ca52ecee475gFONLlbh2h7Ka2gKk3NCF64f8fB3S5vWJ7JlwNHDMM=','5d9a4d4b3b9db1b904a22f38a16eac7b5f3034001ceb6aaa06abc257c8587cdb61be8c7f57db1bac3a5bb9b8c03c591544049f62d12f0d08f909a814c7a3ce9fRBOiHgFcF8f5gj7269zGyOWID5y+qLM8mVeVhZcLsGY=','1aed58f89092bca34acc0b8e3521d82984fe41e0c908fbf893c3a7b976946fa56cabda7a083f13bb9f3ceaed5300d4a885fb91f8e8709f436f146cd2dcbac1a3BusAaFyItoWGL2y7TrFTUcIUdsKax3R+y40ym46g1fs=',26,'cbff2fcc70d36e63c24084e2206019f94c29cab2d5d629ef0023272fc5f82b5eafbc22a1fe191bf949ff8e83976d413744e133a9ff7458fd6b32e524dd1c871a8xXdG71gKo7P0Zl1jopBR6zREInAUH3c4FRpTJ0TWqQ='),(179,114,'ee4c697a7fe1757f17e12eeeb76e6ed7a544cb5d77b9f01a0d65c890978724bfe9708ed6e3114509d090659c36041479a55514e49442b47f50f6c338e90a2df3q3gLDeu5m3qm9k5zoXPlrqSaQorxUU+sZBcZMirhDMM=','3ae75d3c3331a6f5f36f0bac34fe328eb1e6c13360670ab2b1b33c04b54f1e1e0abafefe1da777d1e94860d9deb2384e01e5e89060ff8097736498907ac847c7aAQLOwuIGWBudwF5EQ2BrNmDh+wGNMgNMSzx5zVThMM=','809c3045c9eb3f227809082f487899af70516b76e15079d3604506e0513c57567eeac5eab22fb7cf19904baf0c2c1a1fed1b0ef1fc5f41666cbb5be41964c13eTzfER2hBp1D7kTndwdMWdVaKWjz9Uhci8nzJbhDtHiE=',39,'28461050a96b08a3cb91a9b50d596084e96b6f4c7d38b12124837049bf94a455e84e21588b3260fee4b23edfba5bba453c17d1d986a642ce2bdd6043270402cfcOT1mxwyfax+pkGmkkhJGtXOX6C7Wm/VKQka4+uvT4c='),(180,115,'5c9db706e67f1ab48eff00dd39ea72cdf5c1fe5a690f9ec7085aaf19631153a4cf29edd173afd670896c94edd082fe8b77e55b2dac55ea2be0bbeb3530b97c6ezBc0raBqGzGclHry7l9lTrz6GYpS0lNADMQ39n3yVXg=','ce3ba63bbb2fdd94f0ec30af14acbe03c6d3fce65219ed1d0592032d3c8161901a5b79fae8facac77972dc5b1cb4ee18e6c9eb93e386c5cd638d27e2e34caeb0bxgM2PEAcCVL6jnddew/WHa8hHE/39PXBZcOQm6yB+g=','3e7357bb112c599eec878282e68f459dc7a2bd0adb3864b6a3176dd5cc4fdd2742d189e707b6b585c3be5489cd9163ea3b17e1ed71f30a3c82b287774957d5704BLZtFT0f1EpYJDeiHodmxm+2cUgi2OSlzvOtmpqN48=',26,'ffa24e8dab56f86a6deb0fedfdf1c049d144d76aa1a94e80f9b97acb185dd113102ba0970cc1525b99e4fb456fdd62c03eded84131d255ee61d25c7c0e82cb4f6egm+TsIZb5ne4lfzCelYzYI2xOkCzdvHKMQqQWwDA4='),(181,102,'7c359d9dafa37becc023494dad26eeee87b85bb210d5c49e5164a0675bdebe6d926113508282d951a12ffa983b63c2167f3998fdce3c267d4777524ff8120aa47RJPs4YYiY4Q8gvPljE/JH/Af7NUJKgDbq1RmbVrlZU=','8a3f3ab1a8961f86699946a08fe032fd3f856ff84d0c7e59075cb4ce768d6541defad35404f4ada387a5639e2b65386938a925e702a3aa132709d1f928f132f0s0uhBg8BCs5uz1wEhWHZHRHWRamBYb2VfHHyccsL5hg=','592f4e0c7edaa0cd8797eefe832ada78e402581d45c50139a80eac468f2afcc2d1bff66322bbc2db78f004468164415bad7263f1cdd63ae763779bb927991cdaKXU8C9Xh2g0ZRcRmwgAeeIxaQn2ZBQ1LFzBgO2q69TA=',26,'2907a24854a951774e33aed5e4d8d8cfc82114931e031e58699c018562aaba127ae68acff38ed2e3072fd6c6c94d9ae85b21858df22f214482196e7a4628d467AI+RsgUNwRgiJYpa57wdwXHSFzwwupOgWQSpZNHg+dw='),(182,116,'2cf6377b4658d0da1c7feb221ef7ab76e139f5e4585479522a71b31167c693fc4a19f1637615b3448259f15b53a5fd2c42194c7f0c69735b4cfb3040d2555a730n39OPEsUB6OJX32EH1VmqpQwtPTYyXzcj0w1H7YrVM=','a298e314916696f2d860ef5d2da1afde5f1e100f978d8993ad5f08ff0304599620a8ab014ae5d7668f373b20b18c7bdbeab401604fa86eabe80fae6f8b02286dBYqUdOXT0ygF0A+YlTvV6k7wvH9YcOIyevD7KZpldMo=','6d3b239d49d39480270c819b13df246050e9fa37d6443d689b0553d4a0c3ef8458fbec9be4c9d578a1f660e68ed8d13022655a614c6d8acd79bee6d8fa1622f99H2Q3X08J1dWAmiRcwU0xJYzSjmWLHWt+mrNGEVnSOA=',27,'edf1560c6496c69cc36196068f27fe43c9143b2bc60bffde5d4ee9e1b77810fcffa198fc8f5e75681cfab1d75a574d12ebd59792a149f5f967ade2ef848234b5WU7umFtjUNFiP8UhwB37IV9e/0lA5ReTYnS9gahL6Rs='),(183,102,'0838932e0a68cc11197cd5423e15438765df9ab065ba157a9dde0a260bfbc18acd19d2fecda1ca5b624e1b76d4b767d4f02dd75a40d90f652724b62a91d984c9/GWAq6pegb0UXk7Sz1yjzNhpwuB6xwyaS7zpkJ7oYfA=','b9a72379a0c92443e8a6eaea1022dc42ef9fd75f654d270d649c0155c89020770eb9fcb7a3b5cd9b997508e01abdb722d03d72a660df3e38cb33fc56783d898frkfgUsmtJvQ0CB1cEkYXEJcNtWz4nYNTYTfA0cdXD2M=','1004190c94640ca69b60b6dc1d26d1b745f9d1e8750d70f1f0108a4fbdcd541a638d580746b33e12fbda3f044457ec75829837a1d2eed1a1938dd87b7ebd3893RB8HAVlMM3o39Dge3XSm5q2AdeySvoZCenUj81V08o0=',26,'66863cd9688668aff5402c1ae00f8c90e3ce8719745ad06b2318a52ad11e0b9ef82f5d63514c1356826734892823749a7a54971c4720bb8354b5c454a742fe7fPnby1jyZc/iaYI4EOiStPd+siY4zTHMqKIbj7y7A6hg='),(184,117,'d703f670eef5aa5ceeda521bb5f801daef33de7632e702c67aa3e02e13e6478882cfd1b5c543de9da33b970358ab582b2b8de9c186c77545878f8d5d783b957f6CphJD2mICEXaFF24wXVnhX3fJdbIIgkoAHvoB28urg=','2ce9edc3d8baff49bff916c7cb82ae625c24b72631829c7131e430446ddff8b2eb4877a43623a5162a6a7523df75fda134658dbb49ae5be7606ceef39a63ca1b6qPezOquQFwYTDeP59cF1HzxC2Z8rx6ZqbtNJi6+WFQ=','adecca37d2e6892c12911090e326adab2714e26dfd45fef45e85a6a591bd0ad03bc53deb367d65c4cc1bad157a4aef43fba790703103eb59f445cf53fdb04ad0FvX5kGIf2cnbrwjqhCvdUk8JnLc9PcWZlKuggRLvbq8=',26,'1c9577481b348b6d6eb62d0eca644def06d642195159f350b7d55dd81a57828487061fd34acd6e3e964976c26b180772609463763dd8ecc9377b8fec6949621ansKQvfyYPIeukjlLzHv4ajehFNIjdKFGOB85u7WFhFE='),(185,98,'e5b5cc03d66736fe6ff77be6d96a1bf5570c248b6a5aa3b7ec0b9b61f73580e360d3fa8793391963e819de8ac87120dc1f5f88960c97197ad3429b8f20e42dbbNCF+tpg7fRKLcqaul65XQL5IzxM7ml3sR27/GIA2Q38=','1e57a784d33c6532346d6c6045ac4279c256581c28e656bff72ca8a37005ece89783836ff090acf6c68adbfb4d055d3431c5a21a35647ba47dfabf0242bea89bKS5LBSZIbFxzaICY9s7etcXJxtoM4Jv3BA6e1zKEg7s=','44935cfc8263e54d0d176d5d0155bf561e4eb0d9b33fd7a34ac3f14af72d02379dad0a0bb5380a4af716270d0ffbcdb0c2953c8a7a968abb3e95d10698ad76d5UQvYMIBSMG2SXCcW9McKLrNvFiLuqyX/CaliyrJxoRc=',26,'cfad804ecc2a21da175190a0ff8d73b9d465128a55fbb9c162f921ea8d1c4efb08ac36792be586e54b3b6ad06eb692be265928bbf6e84c368a1d360d4b232c8aPG1kDb9p4aiQjMU+vbJ7y/P23hhNUyaGoXSO0Exajs0='),(186,102,'2dc05f1be7b693b1e0ffe1c5f0144630875eacf95ea13c85430bfda53cb1dba66a8395cb1efa3b3ea72e62baaa082d938373d2e9e19e41448b42da9a1f27ea74gBNpu7l51nr/CN3uKG88OKkFrjWZAfrU13j+k9bODEs=','f26e84f90bb17ec7474a2d00892eb7a9d838e3f3f7a149d6c0f272320fc0d90b1d670ce927dc6da54c5082c8fda7c823592e4e754a5e04cc6e77e7d13148832fv7qFNbCClURzY7/9EbsdKuElFdZ2X8ERHbuF1OzfKQE=','7cb822458de05d42ebd6f3f122bddd67e3d8a36f5e0e7c5206df914210509efed4aa2041b339584e63b4bdd7c8326e45fd2f820888d6d301818ea82eea7950a8EL1tousm9B8FER6LCtWMNMNb0acdLJlnuBLAQba4iqA=',26,'e2fa71ba6c967f5c5ba6f7ec5fb39cfd320c78b86e8a8cfb770ccb73177633462f030d75965075372b40bf0a26fbd12c2781968cd4e6bce6d15edb7582076e17LxU+7lJyQzZuPQmYGuNnu9Aq4LEuQZ4hf4ZlRils9PU='),(187,113,'f3b03c4e5563d166c1fdea7ea371a38a5d460249dda1a824cbe59f3dc0e755005467a2f5f824198c84b253496fc9a4cded40077928d68f5f8212c4567bc32869oKSZh9+dY02chhh/V33XLd/Nu/lEJ/5po1xPQvqWomQ=','a43d0deece3a5ddb9a969412402baa4d41d079185a34d85e209952fce0c76f1515c4e9a76bb101a66c1fdaf773b7405392830f6730d20aca199be80e38670d59Kpth7OAclNRCBL0XCqKffu6o/poXRZa1kvEy40NRkzM=','c29dabf4cf41487dc67d6ed0d956c3fab1bd513fd9f4388f013a0fdfa30eccf3202b843ee02f00022e05deae40e82f226a161b95e3d109b2ad287028e6170dac3I0kJY0YY2onSvJpZo4e5iDQTIC8bV7lCYXVXdH0mls=',26,'9532d24332089667bf7f427134d4dee95000ae700706c2fd93bd7ddb989aa35678b6145cd56b0040194efd21e8ad90499d98b1398998b36a42e2f3b2a9a1b4a9T4D5rK81w8DM0+60zMy1qHcd+fm4REGpym/PRMLPzO8='),(188,102,'6dddb68493b95cef4e3f6d528e0e7447d1309e9ff5139be0ef653bc640e8bea0bf3bee5d4e8519dff88429e35dd7e62ffbd4f676e685f0274eb07c30be905a64+z0UUS9yCmzObn5fC+LJpcxk/DuJMmvxMrfI1EoUbYE=','174914ccf62484f35b689530cf046e845d3caabffa4f5a73e298c9a80965ec52cfb0bbe68860931686a115ee6f09e4773ce7636a9125aa595a56c94d7d1c551auVPnO08KTkWLUbY6ZEegLksFOyzN42pGYiyNbgDg0lo=','ab440c21a1545f1f31e140a634a9e7e8471d74802030c9a5768f468418ae0b3c97726db1ae603d63247c3c250070a978bf973d02777ab0902d366d86a886cb52FXIvA5YKTttn+LaMVS7mkllY3ELtfRhlydYtHuj9qQM=',26,'711887127688aea3af227d983a3918be4749546a761b930acfa75bf067c38c5cf3c7d85196e288809d46af38c4b72df5a861506ff49942193bb513ce99a26214cpDOJn9FoFlHuB3yCUnE055E0YTMg64GJgHlzRJlp+Y='),(189,118,'2ada00ba3c7beff6d5732f098d64195923a1911ba3b4a162c7ee967b34b3e1326112967dccbdf18d554da2dc07961751ec52e7a714657dc0fb8b278a356aeb31sYHrODjkqns7E/cOsnrF2ZhLZdknudEQcxMxwe41UpQ=','116adb008d16fbe04f8e8ce5ecce34f62650c17c45e36b1614471faaadb1a11faa1dca1ff3cd9059afb165229bea69e5a662eb7579391eda7c54ad4e56a25607qG9YbE852iAq0xrE1NYtUayp9bK1kiVI77nBh+ZymII=','600eac99fa5ed81d4da3b14ad117e2f898e93e0ce466d91fbff2867ae07f4d5b61abb23ed69f9bfe2f021b6db0049e40093aee8fc06a2dfdb4da5e3b329cc57fTVQTBPqotyDP9PSyAh4PXidpsTLaBmjKJXy9YpjdIjs=',26,'01d945b95bc7aa1c08e9700972e17f612ad52f3245adeb227d4306a520def0b95838366cd5edc7fae6290aa07a3bc51b4b453b5d8adde2d3a90e7825807cc2c6UK7Eyak2lTSneZWgtTpOtRejxFDnHyv9i2YePz4YcyI='),(190,117,'15940c489c18e64f9e49ef458685a7223deded2bb6badec94a5718e5254a01bb303607931681f292cc4d570e3ffb064126f67af58bc5ad21da8793f60d3a97aakJHj1jXfy9DmfK0VVHPlUry3PZrZb0Go3MYJGtpfiMs=','d7b56f2fa1ace160c5f9d89b3ef3e8627f4751ca10c7b87647b7a4036dbfc150b46e7ae7cee6c6f3a73d47d605abd6016f0a842cdbd0dabb8ec12a9a2cf15df3zW8e3xnnb0Z0qejzh04MXQc3NUumRZ7TbBlOzeNcSo8=','ba5a6c5af39fff045387e6a844f610f258ca92f0c35cbbfeb20cdec71c199af165a289044b91b00208b59b858e17ba43ba2e1ab6ea276bcfc8c8b5ca3b603002tkaaBdpG6gaiZMXDbumVnRCxLta1oDOsd3ZQbxz0z/E=',26,'03b6d16132e92f4e800dcf448a39e30687d0f5d265c8ec6e9d49961b079b2383cf0ca939898c49e19a45c1516314365b703d523c6e3ece0bb94aa5d6d52f52c8SrvewytCb1XVQM4EljNoQ6E6LFIJAGoSbx5KZVvdGZw='),(191,119,'1a263c9fdd54d7efa678d65bf45212bcc22dc632528ad16d823b0dde40dbba57c0b044a48c33e45d9d5d606627daaded7c4ec8d13236cd9f372b9c9ee9081ecctDAGXLo5FXXA5V3qNqP47Yx4rdATF+EU8PMHkho1A7M=','6271f96fed5d8bd3588a06fb3db7c943d53ea8383dbdab8bdcf13370956ca7891090ca8caf9979799a14285fad572754be5752390e772e60eb9d16eaa239cc63JcxQtkA5tQzhnR7Dv0HgyW87/d1dVWm2UQRoGRcYNTE=','c6982be94a1db7a5a15289f89f2e4e18f221a477db9b629a6c7c3e9f13f9b8e651a6bdd218c804c9cfa5081d7720885c869997b936413aaafcbc60d0f5a9bc16E5/GjEVYG+myvsLWhY22ZenK05JISPT11AEcepbOf4E=',26,'d61f45f04fb83f0e2e637dbae2216aa39756fe3d19bc93a2d74cb44186040fad5dabf9f0d49fb261ab472dd06921f527d74fe3252d193c8b27fe52842f7bd09bOmCU4Ynby+TVGemXG96lJVtIRMzm2Lu/uMnbzubMSew='),(192,102,'106357dc1c0e9675ddf18b944ac76697a169ef06164861fb7a9e54c5a516b6f34dc1618bb7bff790c04fe7e8535fb97635fa3a952e3ae456d6fee6183a7c55dbgbDnB7IFmqPHhbGMHuTna3gOJBimdNwK+AOwg7n157E=','cb849f13f89cab284ea652cfa7e77c0defb4b42588cc7fc2b800b062c1fb52053f0e6348e7c7b40523a3b1dcaf528f5cda0356d48399bac07e5b8ad1b77c79c8I3GxzyybvNIN61BxrAbm7DeaGcoMcdofqaAAJSQK1oE=','01cbce06ba5175fb4850c45c410ec07189cbef4cde32b9cd16934f1073e80660d33e9c8c4617bb273a95828d8033733884578d3242847b87494757c11d872ce2nZYJVdBRXX5TQ+IjR9mY66nxH4rFsl6Nj64kiVWqT+Q=',26,'647fc5cfd82e72a3937eea3297f78186c21896b3655ebbf39178898df009275f9933f8bee452dc830279c6dea17a15738adcfa540efee39efca0ec3dde4213c5XduHDBwTAvTMNAcZ7F5YZ88pHV6QhNxwbzxVXIjCFAo='),(193,102,'0f100561add0f99a80983465f8e4e3a08972e26480456abb152a850108278e9b8c678e2d0649ff42e1bef0139bcb047efad14f9b33fe931e60fad552800c498cWQEePyw5KnH4sRLP1Nmg6lE+R3fEOFtNnjxSwfI6Cs8=','dbfec3ec81b3fdd65dfba60ab84b2ac9bf7e98cbc03fd324cb54999d5263cdd118f556c38a84263915c9d465be365d65609654474b2cd4b0eb7a0baedf990bc2iRHlwClcMDiIlv3kPiHMLELctS08lKyH7kE0XHkDZJ0=','9b82bfc992157d229927e8bdeba78a003d81cc6cb89f7c5d56c3d0e57851e25f81e241ae6a53b149ed912ef088239dc121dd4668412d92c4a300f0904e43b9ccT+7jNRMk8pXeJjtMvz8yrWbv8HStNgHtYK26su6EI7Q=',26,'5a68d40f8828ce90ed96832360ed0067cdb4f39c7b26da78f15d5601d34897bc24c8df9719d2ddb69c1d1d269b86c063d8898168f6fbcfd29d2e2e2b5989ba39DOJ7LI7TlGGpbNxhEbf4D68Fr2o+DhOxSrMAuE8ys9Y='),(194,102,'68272cbd2253fb0923d59304fb82d63622de4f91db286229da625c44b040d22b806e01812cf9e28bc255e27fdea0404268e003ebfffe9d3234131c4e7b8b6e83Ekd6s39ngAk+TSGMabmy/18AbUll0GFVRaRiWjjcDsw=','a3e2fd34b9171067fc2f5d405bfd0687b96e955f395ac6a7e58bbd28c096abb544e814a2ac9db4011a1da4bbf8ad4fcbe976163aac79c05f3c4431caf1914252Xs57trsopbD4Z8zYWTX6UXWW33/7fn8Rzp/POSxKM54=','d11ea6771d430cb855d7f6617bf28e2db515294e7950d6cc4fd0668719493029ff4b8452fdc21f5f6fd455b83a3040d5c829093e6e55e4e0f776933a781b8c7dGEihuCAEJPRuKyICsBtfCAg311oi583l5eFSMFpOx9U=',26,'fde06c851fba7e3a51f1aebc6e721680d6a18f91a2aef1ee50db871171750b74050acc73454ec95c2ea7d994137ce1df42fa7d1e15c239dd0e1dad0c4ab0a215w6xL+TaUMAdTcvnkMN6RKatt0/6QJX4xX+kTjwuGmBI=');:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `eu`;:||:Separator:||:


CREATE TABLE `eu` (
  `idEu` int(11) NOT NULL AUTO_INCREMENT,
  `idEmployee` int(11) DEFAULT NULL,
  `username` char(150) DEFAULT NULL,
  `userType` int(1) DEFAULT NULL COMMENT '1 - Administrator\n2 - Supervisor\n3 - User',
  `password` char(100) DEFAULT NULL,
  `archived` int(1) DEFAULT '0',
  PRIMARY KEY (`idEu`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `eu` WRITE;:||:Separator:||:
 INSERT INTO `eu` VALUES(1,59,'mark',1,'ea82410c7a9991816b5eeeebe195e20a',1),(8,66,'aubie',2,'d41d8cd98f00b204e9800998ecf8427e',1),(9,67,'tuna',2,'2bf93a8a979420ff77b32fab0751cad2',1),(10,68,'one',1,'098f6bcd4621d373cade4e832627b4f6',1),(21,90,'test123',1,'e10adc3949ba59abbe56e057f20f883e',1),(22,91,'test123',1,123456,1),(23,92,'qwerty',1,123456,1),(24,93,'qwerty',1,'81dc9bdb52d04dc20036dbd8313ed055',1),(26,65,888,1,'098f6bcd4621d373cade4e832627b4f6',0),(28,1,'jonsnow',2,'5a665206e6374a1b3b95e05d1ae9ecd8',1),(29,63,'marco',3,'f5888d0bb58d611107e11f7cbc41c97a',1),(30,98,'kulot',1,'45d1e4e173173efabc43111920a21fd2',1),(31,99,'dan',1,'0f281d173f0fdfdccccd7e5b8edc21f1',1),(32,100,'dulcy',1,'e10adc3949ba59abbe56e057f20f883e',1),(33,101,'hazel',1,'16b9652df79d0e4784bdbf478c9f4fee',0),(34,102,'sysadmin',1,'21232f297a57a5a743894a0e4a801fc3',0),(36,104,'marie',1,'108f280224d356e3a2537b56152e0b13',1),(37,105,'mcmia',1,'b9499de432a9b63ae79da5fb3e95580f',0),(39,108,'sample',2,'4297f44b13955235245b2497399d7a93',1),(40,97,'sam123',1,'e10adc3949ba59abbe56e057f20f883e',1),(41,110,'leila',1,'754f9968bf5f5f68d7dea029889b7415',1),(42,115,'keith',3,'8dd9fa632ca161d0ca1929a4d99cbe77',0),(43,117,'pests',1,'dbb5eba8ef5cc7bde33928963b207f6e',0),(44,118,'NEWS',1,'508c75c8507a2ae5223dfd2faeb98122',1);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `gl`;:||:Separator:||:


CREATE TABLE `gl` (
  `idGl` int(11) NOT NULL AUTO_INCREMENT,
  `idCoa` int(11) DEFAULT NULL,
  `idInvoice` int(11) DEFAULT NULL,
  `glYear` int(4) DEFAULT NULL,
  `glAmount` decimal(18,2) DEFAULT '0.00',
  `idAffiliate` int(11) DEFAULT NULL,
  `month` int(2) DEFAULT NULL COMMENT '1 - January\n2 - February\n3 - March\n4 - April\n5 - May\n6 - June\n7 - July\n8 - August\n9 - September\n10 - October\n11 - November\n12 - December',
  `debit` decimal(18,2) DEFAULT '0.00',
  `credit` decimal(18,2) DEFAULT '0.00',
  PRIMARY KEY (`idGl`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `gl` WRITE;:||:Separator:||:
 INSERT INTO `gl` VALUES(1,1102001,19,2018,100000.00,2,3,100000.00,0.00),(2,2101000,19,2018,50000.00,2,3,0.00,50000.00),(3,4102000,19,2018,-60000.00,2,3,60000.00,0.00),(4,4102001,19,2018,-10000.00,2,3,0.00,10000.00),(5,5102001,19,2018,-100000.00,2,3,0.00,100000.00),(6,1102001,20,2018,100000.00,2,3,100000.00,0.00),(7,2101000,20,2018,50000.00,2,3,0.00,50000.00),(8,4102000,20,2018,-60000.00,2,3,60000.00,0.00),(9,4102001,20,2018,-10000.00,2,3,0.00,10000.00),(10,5102001,20,2018,-100000.00,2,3,0.00,100000.00),(11,1102001,21,2018,100000.00,2,3,100000.00,0.00),(12,2101000,21,2018,50000.00,2,3,0.00,50000.00),(13,4102000,21,2018,-60000.00,2,3,60000.00,0.00),(14,4102001,21,2018,-10000.00,2,3,0.00,10000.00),(15,5102001,21,2018,-100000.00,2,3,0.00,100000.00),(16,1102001,22,2018,100000.00,2,3,100000.00,0.00),(17,2101000,22,2018,50000.00,2,3,0.00,50000.00),(18,4102000,22,2018,-60000.00,2,3,60000.00,0.00),(19,4102001,22,2018,-10000.00,2,3,0.00,10000.00),(20,5102001,22,2018,-100000.00,2,3,0.00,100000.00),(21,1102001,23,2018,100000.00,2,3,100000.00,0.00),(22,2101000,23,2018,50000.00,2,3,0.00,50000.00),(23,4102000,23,2018,-60000.00,2,3,60000.00,0.00),(24,4102001,23,2018,-10000.00,2,3,0.00,10000.00),(25,5102001,23,2018,-100000.00,2,3,0.00,100000.00),(26,1102001,24,2018,100000.00,2,3,100000.00,0.00),(27,2101000,24,2018,50000.00,2,3,0.00,50000.00),(28,4102000,24,2018,-60000.00,2,3,60000.00,0.00),(29,4102001,24,2018,-10000.00,2,3,0.00,10000.00),(30,5102001,24,2018,-100000.00,2,3,0.00,100000.00),(37,1101000,30,2020,500.00,2,1,500.00,0.00),(38,1102000,30,2020,1000.00,2,1,1000.00,0.00),(39,1102001,30,2020,500.00,2,1,500.00,0.00),(40,2101000,30,2020,800.00,2,1,0.00,800.00),(41,3101000,30,2020,1200.00,2,1,0.00,1200.00),(42,4102000,30,2020,1000.00,2,1,0.00,1000.00),(43,5102001,30,2020,-200.00,2,1,0.00,200.00),(44,1101000,31,2020,500.00,2,1,500.00,0.00),(45,1102000,31,2020,1000.00,2,1,1000.00,0.00),(46,1102001,31,2020,500.00,2,1,500.00,0.00),(47,2101000,31,2020,800.00,2,1,0.00,800.00),(48,3101000,31,2020,1200.00,2,1,0.00,1200.00),(49,4102000,31,2020,1000.00,2,1,0.00,1000.00),(50,5102001,31,2020,-200.00,2,1,0.00,200.00),(51,1102001,29,2018,100000.00,2,3,100000.00,0.00),(52,2101000,29,2018,50000.00,2,3,0.00,50000.00),(53,3101000,29,2018,50000.00,2,3,0.00,50000.00),(54,4102000,29,2018,-60000.00,2,3,60000.00,0.00),(55,4102001,29,2018,-10000.00,2,3,0.00,10000.00),(56,5102001,29,2018,-100000.00,2,3,0.00,100000.00),(57,1101000,32,2020,500.00,2,1,500.00,0.00),(58,1102000,32,2020,1000.00,2,1,1000.00,0.00),(59,1102001,32,2020,500.00,2,1,500.00,0.00),(60,2101000,32,2020,800.00,2,1,0.00,800.00),(61,3101000,32,2020,1200.00,2,1,0.00,1200.00),(62,4102000,32,2020,1000.00,2,1,0.00,1000.00),(63,5102001,32,2020,-200.00,2,1,0.00,200.00),(64,1102001,33,2020,5000.00,2,4,5000.00,0.00),(65,1103000,33,2020,-673983.50,2,4,71500.00,745483.50),(66,2101000,33,2020,-673983.50,2,4,745483.50,71500.00),(67,3101000,33,2020,56200.00,2,4,0.00,56200.00),(68,4102000,33,2020,64000.00,2,4,-59000.00,5000.00),(69,4102001,33,2020,10000.00,2,4,0.00,-10000.00),(70,5102001,33,2020,100200.00,2,4,0.00,-100200.00),(71,1101000,35,2020,500.00,2,1,500.00,0.00),(72,1102000,35,2020,1000.00,2,1,1000.00,0.00),(73,1102001,35,2020,100500.00,2,1,500.00,0.00),(74,2101000,35,2020,50800.00,2,1,0.00,800.00),(75,3101000,35,2020,51200.00,2,1,0.00,1200.00),(76,4102000,35,2020,1000.00,2,1,0.00,1000.00),(77,5102001,35,2020,-200.00,2,1,0.00,200.00),(78,1102001,36,2018,100000.00,2,3,100000.00,0.00),(79,2101000,36,2018,50000.00,2,3,0.00,50000.00),(80,3101000,36,2018,50000.00,2,3,0.00,50000.00),(81,4101000,36,2018,100000.00,2,3,0.00,100000.00),(82,4102000,36,2018,60000.00,2,3,0.00,60000.00),(83,4102001,36,2018,10000.00,2,3,10000.00,0.00),(84,5102001,36,2018,100000.00,2,3,100000.00,0.00),(85,3101000,37,2018,50000.00,2,6,0.00,0.00),(86,1102001,38,2018,100000.00,2,3,100000.00,0.00),(87,2101000,38,2018,50000.00,2,3,0.00,50000.00),(88,3101000,38,2018,50000.00,2,3,0.00,50000.00),(89,4101000,38,2018,100000.00,2,3,0.00,100000.00),(90,4102000,38,2018,40000.00,2,3,0.00,40000.00),(91,4102001,38,2018,-10000.00,2,3,0.00,10000.00),(92,5102001,38,2018,100000.00,2,3,100000.00,0.00),(93,3101000,39,2018,100000.00,2,4,0.00,50000.00),(94,3101000,40,2018,50000.00,2,5,0.00,0.00),(95,1101000,41,2020,500.00,2,1,500.00,0.00),(96,1102000,41,2020,1000.00,2,1,1000.00,0.00),(97,1102001,41,2020,100500.00,2,1,500.00,0.00),(98,2101000,41,2020,50800.00,2,1,0.00,800.00),(99,3101000,41,2020,50000.00,2,1,0.00,0.00),(100,4102000,41,2020,1000.00,2,1,0.00,1000.00),(101,5102001,41,2020,-200.00,2,1,0.00,200.00),(102,1101000,51,2020,-1000.00,2,2,0.00,1000.00),(103,1102000,51,2020,1000.00,2,2,1000.00,0.00),(104,3101000,51,2020,50000.00,2,2,0.00,0.00),(105,3101000,52,2020,100000.00,2,3,0.00,50000.00),(106,1102001,53,2020,105000.00,2,4,5000.00,0.00),(107,1103000,53,2020,-673983.50,2,4,71500.00,745483.50),(108,2101000,53,2020,-623983.50,2,4,745483.50,71500.00),(109,3101000,53,2020,105000.00,2,4,0.00,55000.00),(110,4101000,53,2020,-100000.00,2,4,100000.00,0.00),(111,4102000,53,2020,-40000.00,2,4,45000.00,5000.00),(112,4102001,53,2020,10000.00,2,4,0.00,-10000.00),(113,5102001,53,2020,-100000.00,2,4,0.00,100000.00);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `glhistory`;:||:Separator:||:


CREATE TABLE `glhistory` (
  `idGlHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idGl` int(11) DEFAULT NULL,
  `idInvoice` int(11) DEFAULT NULL,
  `glYear` int(4) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `month` int(2) DEFAULT NULL,
  PRIMARY KEY (`idGlHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `glhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `idcontribution`;:||:Separator:||:


CREATE TABLE `idcontribution` (
  `idEmpContribution` int(11) NOT NULL AUTO_INCREMENT,
  `idEmployee` int(11) DEFAULT NULL,
  `contribution` int(3) DEFAULT NULL COMMENT '1 - SSS\\n2 - Philhealth\\n3 - Pag-ibig Fund\\n4 - Withholding Tax',
  `amount` decimal(18,2) DEFAULT '0.00',
  `effectivityDate` date DEFAULT NULL,
  `idCoa` int(11) DEFAULT NULL COMMENT 'idCoa',
  PRIMARY KEY (`idEmpContribution`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `idcontribution` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `invadjustment`;:||:Separator:||:


CREATE TABLE `invadjustment` (
  `idInvAdjustment` int(11) NOT NULL AUTO_INCREMENT,
  `idItem` int(11) DEFAULT NULL,
  `qtyBal` int(11) DEFAULT NULL,
  `qtyActual` int(11) DEFAULT NULL,
  `cost` decimal(18,2) DEFAULT NULL,
  `short` int(11) DEFAULT NULL,
  `over` int(11) DEFAULT NULL,
  `idInvoice` int(11) DEFAULT NULL,
  `expiryDate` date DEFAULT NULL,
  PRIMARY KEY (`idInvAdjustment`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `invadjustment` WRITE;:||:Separator:||:
 INSERT INTO `invadjustment` VALUES(1,1,6,1,50.00,5,0,6,'0000-00-00'),(2,10,150,160,1467.00,0,10,49,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `invadjustmenthistory`;:||:Separator:||:


CREATE TABLE `invadjustmenthistory` (
  `idInvAdjustmentHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idInvAdjustment` int(11) DEFAULT NULL,
  `idItem` int(11) DEFAULT NULL,
  `idItemClass` int(11) DEFAULT NULL,
  `qtyBal` int(11) DEFAULT NULL,
  `qtyActual` int(11) DEFAULT NULL,
  `cost` decimal(18,2) DEFAULT '0.00',
  `short` int(11) DEFAULT NULL,
  `over` int(11) DEFAULT NULL,
  `idInvoicesHistory` int(11) DEFAULT NULL,
  `idInvoice` int(11) DEFAULT NULL,
  `expiryDate` date DEFAULT NULL,
  PRIMARY KEY (`idInvAdjustmentHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `invadjustmenthistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `invoices`;:||:Separator:||:


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
  `deliveryReceiptTag` int(1) DEFAULT NULL,
  `deliveryReceipt` varchar(255) DEFAULT NULL,
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
  `pickupDate` timestamp NULL DEFAULT NULL,
  `notedby` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `otherTag` int(1) DEFAULT NULL,
  `description` text,
  `month` int(2) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  `archived` int(1) DEFAULT '0' COMMENT '0 is Active\n1 is Archived',
  `idReferenceSeries` int(11) DEFAULT NULL,
  `cancelledBy` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idInvoice`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `invoices` WRITE;:||:Separator:||:
 INSERT INTO `invoices` VALUES(3,null,2,1,48,null,null,'2020-03-06 16:32:13',null,1,4,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-23 16:33:43',null,0,null,34,null,null,1,0,'sample',null,null,0,3,0),(4,2,3,1,58,null,null,'2020-01-06 19:07:00',null,1,1,1,2000.00,2000.00,2000.00,0.00,0.00,0.00,null,null,0,null,'2020-01-06',null,null,null,0,null,null,1,null,34,null,null,2,null,null,null,null,0,4,0),(5,2,3,2,58,null,null,'2020-04-03 19:07:00',null,1,1,1,5000.00,5000.00,5000.00,0.00,0.00,0.00,null,null,0,null,'2020-04-03',null,null,null,0,null,null,1,null,34,null,null,2,null,null,null,null,0,4,0),(6,2,1,1,35,null,null,'2020-04-23 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-23 19:21:56',null,0,null,null,null,null,1,null,null,2,2020,1,1,0),(7,2,4,1,25,null,null,'2020-04-23 19:56:18',null,2,1,1,37500.00,37500.00,37500.00,0.00,0.00,0.00,null,null,0,null,'2020-04-23',null,null,null,0,null,null,1,null,34,null,null,2,null,null,null,null,0,5,0),(8,2,4,2,25,null,null,'2020-04-23 19:56:18',null,2,2,1,15000.00,15000.00,15000.00,0.00,0.00,0.00,null,null,0,null,'2020-04-23',null,null,null,0,null,null,1,null,34,null,null,2,null,null,null,null,0,5,0),(9,2,5,1,18,null,null,'2020-04-23 20:12:00',null,1,4,1,3750.00,3750.00,3750.00,0.00,0.00,0.00,0,null,0,null,'2020-04-23',null,0,null,0,null,null,0,null,34,null,null,2,null,null,null,null,0,6,0),(10,2,5,2,18,null,null,'2020-04-23 20:12:00',null,1,1,1,7500.00,7500.00,7500.00,0.00,0.00,0.00,0,null,0,null,'2020-04-23',null,null,null,0,null,null,0,null,34,null,null,2,null,null,null,null,0,6,0),(11,2,6,1,2,null,null,'2020-04-24 07:01:38',null,2,4,null,14670.00,14670.00,14670.00,0.00,0.00,0.00,null,null,0,null,'2020-04-24',null,null,null,0,null,null,1,null,34,null,null,2,null,null,null,null,0,7,0),(12,2,6,2,2,null,null,'2020-04-24 13:46:09',null,2,3,null,143000.00,143000.00,143000.00,0.00,0.00,0.00,null,null,0,null,'2020-04-24',null,null,null,0,null,null,1,null,34,null,null,2,null,null,null,null,0,7,0),(13,2,4,3,25,null,null,'2020-04-24 13:46:00',null,2,3,1,71500.00,71500.00,71500.00,0.00,0.00,0.00,null,null,0,null,'2020-04-24',null,null,null,0,null,null,1,12,34,null,null,2,null,null,null,null,0,5,0),(17,2,4,4,25,null,null,'2020-04-24 14:07:50',null,2,4,1,745483.50,745483.50,745483.50,0.00,0.00,0.00,null,null,0,null,'2020-04-24',null,null,null,0,null,null,1,null,34,null,null,2,null,null,null,null,0,5,0),(18,2,1,2,35,null,null,'2020-04-24 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-24 17:07:24',null,0,null,null,null,null,1,null,null,3,2018,1,1,0),(19,2,1,3,35,null,null,'2020-04-24 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-24 17:37:24',null,0,null,null,null,null,1,null,null,3,2018,1,1,0),(20,2,1,4,35,null,null,'2020-04-24 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-24 18:15:40',null,0,null,null,null,null,1,null,null,3,2018,1,1,0),(21,2,1,5,35,null,null,'2020-04-24 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-24 18:25:43',null,0,null,null,null,null,1,null,null,3,2018,1,1,0),(22,2,1,6,35,null,null,'2020-04-24 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-24 18:28:58',null,0,null,null,null,null,1,null,null,3,2018,1,1,0),(23,2,1,7,35,null,null,'2020-04-24 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-24 18:30:30',null,0,null,null,null,null,1,null,null,3,2018,1,1,0),(24,2,1,8,35,null,null,'2020-04-24 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-24 18:33:11',null,0,null,null,null,null,1,null,null,3,2018,1,1,0),(27,2,1,9,35,null,null,'2020-04-24 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-24 18:39:39',null,0,null,null,null,null,1,null,null,3,2018,1,1,0),(28,2,1,10,35,null,null,'2020-04-24 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-24 18:47:06',null,0,null,null,null,null,1,null,null,3,2018,1,1,0),(29,2,1,11,35,null,null,'2020-04-24 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-24 18:52:10',null,0,null,null,null,null,2,null,null,3,2018,1,1,0),(30,2,1,12,35,null,null,'2020-04-24 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-24 18:49:26',null,0,null,null,null,null,1,null,null,1,2020,1,1,0),(31,2,1,13,35,null,null,'2020-04-24 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-24 18:50:06',null,0,null,null,null,null,1,null,null,1,2020,1,1,0),(32,2,1,14,35,null,null,'2020-04-24 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-24 18:52:20',null,0,null,null,null,null,2,null,null,1,2020,1,1,0),(33,2,1,15,35,null,null,'2020-04-24 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-24 18:52:28',null,0,null,null,null,null,1,null,null,4,2020,1,1,0),(34,2,3,3,58,null,null,'2020-02-01 18:53:00',null,1,1,1,1000.00,1000.00,1000.00,0.00,0.00,0.00,null,null,0,null,'2020-02-01',null,null,null,0,null,null,1,null,34,null,null,2,null,null,null,null,0,4,0),(35,2,1,16,35,null,null,'2020-04-24 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-24 19:15:22',null,0,null,null,null,null,1,null,null,1,2020,1,1,0),(36,2,1,17,35,null,null,'2020-04-25 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-25 09:35:50',null,0,null,null,null,null,2,null,null,3,2018,1,1,0),(37,2,1,18,35,null,null,'2020-04-25 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-25 09:36:12',null,0,null,null,null,null,2,null,null,6,2018,1,1,0),(38,2,1,19,35,null,null,'2020-04-25 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-25 09:41:38',null,0,null,null,null,null,2,null,null,3,2018,0,1,0),(39,2,1,20,35,null,null,'2020-04-25 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-25 09:43:22',null,0,null,null,null,null,2,null,null,4,2018,0,1,0),(40,2,1,21,35,null,null,'2020-04-25 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-25 09:43:32',null,0,null,null,null,null,2,null,null,5,2018,0,1,0),(41,2,1,22,35,null,null,'2020-04-25 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-25 09:43:52',null,0,null,null,null,null,2,null,null,1,2020,0,1,0),(43,2,5,3,18,null,null,'2020-04-25 09:50:00',null,1,4,1,5000.00,5000.00,5000.00,0.00,0.00,0.00,0,null,0,null,'2020-04-25',null,null,null,0,null,null,0,null,34,null,null,2,null,null,null,null,0,6,0),(46,2,5,4,18,null,null,'2020-04-25 09:50:00',null,1,1,1,50.00,50.00,50.00,0.00,0.00,0.00,0,null,0,null,'2020-04-25',null,null,null,0,null,null,0,null,34,null,null,2,null,null,null,null,0,6,0),(47,2,8,1,43,null,null,'2020-04-25 10:00:00',null,3,4,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,0,0,null,34,null,null,2,null,null,null,null,0,9,0),(48,4,8,1,43,null,null,'2020-04-25 10:00:00',null,3,2,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,0,0,null,34,null,null,2,null,null,null,null,0,9,0),(49,2,9,1,23,null,null,'2020-04-25 10:06:51',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-25 10:14:26',null,0,null,34,null,null,1,null,null,null,null,0,10,0),(50,null,2,2,48,null,null,'2020-04-25 10:15:21',null,0,0,null,1000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-25 10:16:44',null,0,null,34,null,null,1,0,null,null,null,0,3,0),(51,2,1,23,35,null,null,'2020-04-25 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-25 11:58:27',null,0,null,null,null,null,2,null,null,2,2020,0,1,0),(52,2,1,24,35,null,null,'2020-04-25 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-25 11:58:34',null,0,null,null,null,null,2,null,null,3,2020,0,1,0),(53,2,1,25,35,null,null,'2020-04-25 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-04-25 11:58:43',null,0,null,null,null,null,2,null,null,4,2020,0,1,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `invoiceshistory`;:||:Separator:||:


CREATE TABLE `invoiceshistory` (
  `idInvoiceHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idInvoice` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `idReference` int(11) DEFAULT NULL,
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
  `preparedBy` int(11) DEFAULT NULL,
  `pickupDate` timestamp NULL DEFAULT NULL,
  `notedby` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `otherTag` int(1) DEFAULT NULL,
  `referenceNum` varchar(255) DEFAULT NULL,
  `idReferenceSeries` int(11) DEFAULT NULL,
  `deliveryReceiptTag` int(1) DEFAULT NULL,
  `deliveryReceipt` varchar(255) DEFAULT NULL,
  `cancelledBy` int(11) DEFAULT '0',
  PRIMARY KEY (`idInvoiceHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `invoiceshistory` WRITE;:||:Separator:||:
 INSERT INTO `invoiceshistory` VALUES(3,3,null,2,48,null,null,'2020-03-06 16:32:13',1,4,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,null,0,1,3,null,null,0),(4,4,2,3,58,null,null,'2020-03-06 19:07:00',1,1,1,2000.00,2000.00,2000.00,0.00,0.00,0.00,0,null,'2020-03-06',null,null,null,0,null,null,1,null,34,null,null,2,null,1,4,null,null,0),(5,5,2,3,58,null,null,'2020-04-03 19:07:00',1,1,1,5000.00,5000.00,5000.00,0.00,0.00,0.00,0,null,'2020-04-03',null,null,null,0,null,null,1,null,34,null,null,2,null,2,4,null,null,0),(6,4,2,3,58,null,null,'2020-02-06 19:07:00',1,1,1,2000.00,2000.00,2000.00,0.00,0.00,0.00,0,null,'2020-02-06',null,null,null,0,null,null,1,null,34,null,null,2,null,1,4,null,null,0),(7,4,2,3,58,null,null,'2020-01-06 19:07:00',1,1,1,2000.00,2000.00,2000.00,0.00,0.00,0.00,0,null,'2020-01-06',null,null,null,0,null,null,1,null,34,null,null,2,null,1,4,null,null,0),(8,6,2,1,35,null,null,'2020-04-23 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,1,null,1,1,null,null,0),(9,9,2,5,18,null,null,'2020-04-23 20:12:00',1,4,1,3750.00,3750.00,3750.00,0.00,0.00,0.00,0,null,'2020-04-23',null,0,null,0,null,null,0,null,34,null,null,2,null,1,6,0,null,0),(10,10,2,5,18,null,null,'2020-04-23 20:12:00',1,1,1,7500.00,7500.00,7500.00,0.00,0.00,0.00,0,null,'2020-04-23',null,null,null,0,null,null,0,null,34,null,null,2,null,2,6,0,null,0),(11,3,2,4,25,null,null,'2020-04-24 13:46:00',2,3,1,71500.00,71500.00,71500.00,0.00,0.00,0.00,0,null,'2020-04-24',null,null,null,0,null,null,1,12,34,null,null,2,null,3,5,null,null,0),(15,18,2,1,35,null,null,'2020-04-24 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,1,null,2,1,null,null,0),(16,19,2,1,35,null,null,'2020-04-24 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,1,null,3,1,null,null,0),(17,20,2,1,35,null,null,'2020-04-24 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,1,null,4,1,null,null,0),(18,21,2,1,35,null,null,'2020-04-24 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,1,null,5,1,null,null,0),(19,22,2,1,35,null,null,'2020-04-24 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,1,null,6,1,null,null,0),(20,23,2,1,35,null,null,'2020-04-24 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,1,null,7,1,null,null,0),(21,24,2,1,35,null,null,'2020-04-24 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,1,null,8,1,null,null,0),(24,27,2,1,35,null,null,'2020-04-24 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,1,null,9,1,null,null,0),(25,28,2,1,35,null,null,'2020-04-24 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,1,null,10,1,null,null,0),(26,29,2,1,35,null,null,'2020-04-24 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,1,null,11,1,null,null,0),(27,30,2,1,35,null,null,'2020-04-24 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,1,null,12,1,null,null,0),(28,31,2,1,35,null,null,'2020-04-24 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,1,null,13,1,null,null,0),(29,29,2,1,35,null,null,'2020-04-24 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2020-04-24 18:48:37',null,0,null,null,null,null,2,null,11,1,null,null,0),(30,32,2,1,35,null,null,'2020-04-24 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,2,null,14,1,null,null,0),(31,33,2,1,35,null,null,'2020-04-24 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,1,null,15,1,null,null,0),(32,34,2,3,58,null,null,'2020-02-01 18:53:00',1,1,1,1000.00,1000.00,1000.00,0.00,0.00,0.00,0,null,'2020-02-01',null,null,null,0,null,null,1,null,34,null,null,2,null,3,4,null,null,0),(33,35,2,1,35,null,null,'2020-04-24 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,1,null,16,1,null,null,0),(34,36,2,1,35,null,null,'2020-04-25 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,2,null,17,1,null,null,0),(35,37,2,1,35,null,null,'2020-04-25 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,2,null,18,1,null,null,0),(36,38,2,1,35,null,null,'2020-04-25 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,2,null,19,1,null,null,0),(37,39,2,1,35,null,null,'2020-04-25 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,2,null,20,1,null,null,0),(38,40,2,1,35,null,null,'2020-04-25 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,2,null,21,1,null,null,0),(39,41,2,1,35,null,null,'2020-04-25 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,2,null,22,1,null,null,0),(41,43,2,5,18,null,null,'2020-04-25 09:50:00',1,4,1,500.00,500.00,500.00,0.00,0.00,0.00,0,null,'2020-04-25',null,0,null,0,null,null,0,null,34,null,null,2,null,3,6,0,null,0),(44,43,2,5,18,null,null,'2020-04-25 09:50:00',1,4,1,5000.00,5000.00,5000.00,0.00,0.00,0.00,0,null,'2020-04-25',null,null,null,0,null,null,0,null,34,null,null,2,null,3,6,0,null,0),(45,46,2,5,18,null,null,'2020-04-25 09:50:00',1,1,1,50.00,50.00,50.00,0.00,0.00,0.00,0,null,'2020-04-25',null,null,null,0,null,null,0,null,34,null,null,2,null,4,6,0,null,0),(46,50,null,2,48,null,null,'2020-04-25 10:15:21',0,0,null,1000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,null,0,2,3,null,null,0),(47,51,2,1,35,null,null,'2020-04-25 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,2,null,23,1,null,null,0),(48,52,2,1,35,null,null,'2020-04-25 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,2,null,24,1,null,null,0),(49,53,2,1,35,null,null,'2020-04-25 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,2,null,25,1,null,null,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `item`;:||:Separator:||:


CREATE TABLE `item` (
  `idItem` int(11) NOT NULL AUTO_INCREMENT,
  `barcode` char(20) DEFAULT NULL,
  `itemName` text,
  `idItemClass` int(11) DEFAULT NULL,
  `idUnit` int(11) DEFAULT NULL,
  `itemPrice` text,
  `reorderLevel` int(11) DEFAULT NULL,
  `dateModified` datetime DEFAULT NULL,
  `releaseWithoutQty` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `salesGlAcc` int(11) DEFAULT NULL,
  `inventoryGlAcc` int(11) DEFAULT NULL,
  `costofsalesGlAcc` int(11) DEFAULT NULL,
  `effectivityDate` date DEFAULT NULL,
  `archived` int(1) DEFAULT '0',
  `sk` text,
  PRIMARY KEY (`idItem`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `item` WRITE;:||:Separator:||:
 INSERT INTO `item` VALUES(1,1,'dda74523efea88aa80a16a83dc56ff94a404655432e483e09907cf0e9329f644608275df52ffa48ab9c5c96c52b11a9f486f56b9774ff6304c115d98f90c34efc4M8xMwJL6zD9VuzBxClUAi9syQngHeqS3RM4lirAPw=',1,1,'49f872eb00e5b46a09c692e45e5bb94dc484c4828ea95e561e5626dfa7455d9cd80005228e03d96adf77cf87b8554fe4145f35f7f196c70e30681198dcbf8448paQneaJdDdYJ0E3Um8NZr5AXv8mzTOS9XseDu87oEKI=',0,null,0,null,null,null,'2020-04-22',0,092005130080),(2,2,'640010f4b6345a792afd31476cc8c9a52ab20c16870dce11a5640dfaacddddd77254e6c52d859231ff129e4b37106e71c7da5fe13b3bb3120b5ce77f831d004e5RdyWUNbKnQegeGl0UVot4oqE6sGjlL1TPONjxDUymw=',4,8,'585d9d2e7095a2c49b6bfd86861ca50e754f135ea307433483fe711d96de5b4ce96228d797a80f7090e843624da83b1dfb6622bf14d9254698ad7e2803435064SibW77/W2UlZY6u6fVQzefmiGO5H0DG3mHKUI9wdarU=',50,null,1,4101000,1101000,null,'2020-04-22',0,030513051432),(3,3,'d35f2dd3aec96535d6e9a0ce576cb132e778889b223d79a4791d253fd2701c8e81bb9dc2ad41ba4c9870813795e16655fabaa1e3c640cb8243af28c804a7a76c60/ACcuriPW+yu74LkvLw1eGBPT8eO7iw63qWJWV6Vk=',3,3,'20f5fb22d834a3f59c4afeeaf49d30c1461c47551b6d6cb17542661c0b74ad507e6ca496f486dc9fe99b3a9c64e2391d4df8d36eae1189f11142705e8fbab61ds8AdDtOa6yA7TSv+4IZwn+FIRZL8oApdFxN/AWhyoeo=',10,null,0,4101000,1101000,3101000,'2020-04-22',0,040122090577),(4,4,'30866b9fdf1d86ed3d036417d86e1833ac31dff74e58cdd90cdb48dcbf268b5c4b0f089fee72d17c3d4b19abe128917cc2fdd8315e73a2e7d9cc22cfaecffa89jqjLspnrOA/w4fbhXQb08QO6Xs7fE3dxhIMm6wQ6cNI=',4,7,'06e77f04a0150095c483648eb7a215c7bef5d46acebe57917c6efccc9e6f1f498d33fe25bbd5a31cbe5a8f8988fc410301e8b4c5a41a706abed3b1a4e6bb67ecYBg2V98AscfiB7ifz/YxNq8JV6HnSWwuIMm8yMCc0HU=',40,null,0,4101000,1101000,3101000,'2020-04-22',0,021201031168),(5,5,'6c97820107ef11d1dc06ebf233264331f42aa1295051a21c0f19beb3fd972068dbc4cb59f37e2c23990cf34764bba1537bcfc82aad6c6b901c07b8dbd295931fy5uXVqp+ME3Va3FVzIhKD3mJ5A8GyPzFCrbvbsuPubA=',4,1,'e674ceaddf56f7adfb27c3ed7f261f34db1fc9c9c73b9bf80018ed171a78ca687c0fd5e5beb47e77e1f687d0abe11dbb6bd8fa2c062b09aabc010b58ddf37babJf8y5a+A27PfHy7GcBKdK4RrZApj3YxVG+Hz0Iy4BgU=',40,null,0,4101000,1101000,3101000,'2020-04-22',0,200912051911),(6,6,'3324c9226a39c6aaab0ee498726a56dbb5143a482b419e2d033e4e488dc0018235b26b8ddaf21d4182cb5e1559ba641742420cf21e67a32f181c5f19374a1b65AJSOwj8eujDFUEuz+pYfPv0C1zjhtGAb6qzgJ69I7HFfPHK0yVp4hRGw62Q+uFYp',4,1,'d34b9b8727c8a57a69ca29e13ea62f72716c8eb16514b866989e4b554cd8473fd5462e010e0b6c0cb2ea4128c832b42287cb487f332b92abe3df23579b10c81ddq2PeNuH9uVBnKGthkJqQLlKDTR+2olb6WaG2DH+v2E=',40,null,0,4101000,1101000,3101000,'2020-04-22',0,200912051949),(7,7,'be93986548db8b4ac53d1f4233330ee3301115c83ae9b3f69d9a8f0ef9f49d432b0f5e34f0734f706a4e479fa7c154dad85fb9d862477071cbdb8b7e3c3bba84QaRhWXAsngHP1rAJKz85U5v/XtrYr4OsHkuTXDXSez8K6F1XVSBOHptR429RIHML',3,3,'ef5a77017a2d03e76300cace27fcc300b60dcebbe47d7442b913ae8993307be3e564cc266e0c34074864c2fff6fc273c16e4bc5fb918e3ad82ad4b15bb413394oGr59Lpt1dqQz9zX5IFEP6r5H5udFDuTA3Muz0Jz94c=',10,null,0,4101000,1101000,3101000,'2020-04-22',0,040122090594),(8,8,'5f1981bfa8119c0313ef46ae50c06425dd262befc2e47e6610ca519b1702fa80ac75e8d19b043725ed50a72aad67ee739a0acc52af107a152ac44767743e6e9a1ixWZJFeV1q570VjxuMuix555bR57cdTIDtnKueIddN8T0ug0rwTVGAvDfMhaIMa',3,3,'1ecdca2312fd0444ea7f5f1441cf3d2302d7b20cd424d01f06084319f9ceb27f9561d4cb8283aa40d526268746092c48403ff87e2fc550a9756d5972f9d88202+8/CZBmrV5X6ZY6Lv//ssNOn2EpGansZXI/TkAv43Oc=',10,null,0,null,null,null,'2020-04-22',0,040122090595),(9,9,'996f8bc74cb55ff900c724dd8ddd9016109580bb5d7df4943be75cef9a9030bbf90da5017bf3ef505087213c1773a6879934153ac2024024d4d7933358f2159c0BlP+2hWla4R10CQOrAaHl+EK9OR94SlrasCP0p2zD9m43tbVYYTsgkvQFQ25Tjx',3,3,'c844f3af07c74d3a6b80694b51be2834f2ab7d9aa628725311dd367775822bb220547fd98847595a8bba737d96e7927d2bc835da8e3b1821e8739cbbd9d94354lFv1ebaTEeiTja/3R6+sgtOmp6Q7Gka0zJP4jdpSo1I=',10,null,0,null,null,null,'2020-04-22',0,040122090513),(10,10,'68059dc986f4db2694b58c29b247c79bc0d41e866fb2168ec39db659c65b188115ad6605644c3aa76cb2472302ad44fbbe1453b527d9e7560f2653fccda99f9ccVz8GD25QUQK0Hh8YFLVtS/gwd9VnjGZNCPB2Uy1xqwAmEcDLYg+sORA3RzIXjTN',3,3,'e56312d51d6018076ff230e9f7a1834ca09af3bc7bbb3df9dcaa89be75168392b203d3ac15eff3b1c51bb76b5e5cbf3f03c81b030a8df82130d1194eb96bec75vG39IfDGzkWpdDPIdaMimtDkRgBm6n40Foh48DZJMq4=',10,null,0,null,null,null,'2020-04-22',0,040122090531),(11,11,'2dc116cf45f35f8463b785ab5fa8d66885bdd75e54105debe91051026882c9b12a343cbfda4c35a0fbdcf01cf93932c4df437003cbbe1a026cef3d0f70545cc8Zr340q0Yzv3tCiaanjj15ayP1Obf0nCkAaC3j/0YXb+ftwqQhB4An/BqxZ6THCsr',2,4,'73bdc1d336cacdba7e3deb40cbdb444d46c3e692d22a7601c56a6f67181ca3071c41d07924f11d8cfccf4c39d9d0142795eea7161c8a81fcbb1e46e9cc3c3b0a3t+d2jfB2d+WVnPtWXKAcFg7FCPlHgf2X6J9HZbC4pA=',10,null,0,null,null,null,'2020-04-22',0,190114040036),(12,12,'ff79c0008971ffbe1f8719e1b49cf02d41f3c7d7f3627d6a3a1cf79e2afdf5744a3f4ae8cf7ce3e150bb0fb3e5cca0b2b149807906b010d226a21c1f531031eash8QJShoFngkesN97QnLazKizBgaulggJtWYHi75wUdRsreqTE17r7aQnmQFkKId',4,1,'05c05eab8d0614b80d70a5a7411816b7beda3c2c26d6097eef42c6de499ebc5f9ce36bbfa661954f80bac52341d4ba30e32b9a124a7c4c51897a2a80224974d3NMY9mTcMz1omJo6FeksiNoAu3cLhEXnYbTeh1uTobuo=',10,null,0,null,null,null,'2020-04-22',0,091815140027);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `itemaffiliate`;:||:Separator:||:


CREATE TABLE `itemaffiliate` (
  `idItemAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `idItem` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `selected` int(1) DEFAULT NULL,
  PRIMARY KEY (`idItemAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `itemaffiliate` WRITE;:||:Separator:||:
 INSERT INTO `itemaffiliate` VALUES(1,1,2,1),(2,2,2,1),(3,3,1,1),(4,3,2,1),(5,3,3,1),(6,4,2,1),(7,5,2,1),(8,6,2,1),(9,7,2,1),(10,8,2,1),(11,9,2,1),(12,10,2,1),(13,11,2,1),(14,12,2,1);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `itemaffiliatehistory`;:||:Separator:||:


CREATE TABLE `itemaffiliatehistory` (
  `idItemAffiliateHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idItemHistory` int(11) DEFAULT NULL,
  `idItem` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `selected` int(1) DEFAULT NULL,
  PRIMARY KEY (`idItemAffiliateHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `itemaffiliatehistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `itemclassification`;:||:Separator:||:


CREATE TABLE `itemclassification` (
  `idItemClass` int(11) NOT NULL AUTO_INCREMENT,
  `classCode` int(11) DEFAULT NULL,
  `className` char(20) DEFAULT NULL,
  `archived` int(1) DEFAULT '0',
  PRIMARY KEY (`idItemClass`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `itemclassification` WRITE;:||:Separator:||:
 INSERT INTO `itemclassification` VALUES(1,1,'Food',0),(2,2,'Supplies',0),(3,3,'Chemicals',0),(4,4,'Construction Materia',0),(5,5,'Increase char limit',0),(6,6,'Rental',0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `itemclassificationhistory`;:||:Separator:||:


CREATE TABLE `itemclassificationhistory` (
  `idClassificationHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idItemClass` int(11) DEFAULT NULL,
  `classCode` int(11) DEFAULT NULL,
  `className` char(20) DEFAULT NULL,
  PRIMARY KEY (`idClassificationHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `itemclassificationhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `itemhistory`;:||:Separator:||:


CREATE TABLE `itemhistory` (
  `idItemHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idItem` int(11) DEFAULT NULL,
  `barcode` char(20) DEFAULT NULL,
  `itemName` text,
  `idItemClass` int(11) DEFAULT NULL,
  `idUnit` int(11) DEFAULT NULL,
  `itemPrice` text,
  `reorderLevel` int(11) DEFAULT NULL,
  `dateModified` datetime DEFAULT NULL,
  `releaseWithoutQty` int(1) DEFAULT '0',
  `salesGlAcc` int(11) DEFAULT NULL,
  `inventoryGlAcc` int(11) DEFAULT NULL,
  `costofsalesGlAcc` int(11) DEFAULT NULL,
  `effectivityDate` date DEFAULT NULL,
  PRIMARY KEY (`idItemHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `itemhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `itempricehistory`;:||:Separator:||:


CREATE TABLE `itempricehistory` (
  `idPrice` int(11) NOT NULL AUTO_INCREMENT,
  `idItem` varchar(45) DEFAULT NULL,
  `itemPrice` text,
  `effectivityDate` date DEFAULT NULL,
  PRIMARY KEY (`idPrice`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `itempricehistory` WRITE;:||:Separator:||:
 INSERT INTO `itempricehistory` VALUES(1,1,'49f872eb00e5b46a09c692e45e5bb94dc484c4828ea95e561e5626dfa7455d9cd80005228e03d96adf77cf87b8554fe4145f35f7f196c70e30681198dcbf8448paQneaJdDdYJ0E3Um8NZr5AXv8mzTOS9XseDu87oEKI=','2020-04-22'),(2,2,'585d9d2e7095a2c49b6bfd86861ca50e754f135ea307433483fe711d96de5b4ce96228d797a80f7090e843624da83b1dfb6622bf14d9254698ad7e2803435064SibW77/W2UlZY6u6fVQzefmiGO5H0DG3mHKUI9wdarU=','2020-04-22'),(3,3,'20f5fb22d834a3f59c4afeeaf49d30c1461c47551b6d6cb17542661c0b74ad507e6ca496f486dc9fe99b3a9c64e2391d4df8d36eae1189f11142705e8fbab61ds8AdDtOa6yA7TSv+4IZwn+FIRZL8oApdFxN/AWhyoeo=','2020-04-22'),(4,4,'06e77f04a0150095c483648eb7a215c7bef5d46acebe57917c6efccc9e6f1f498d33fe25bbd5a31cbe5a8f8988fc410301e8b4c5a41a706abed3b1a4e6bb67ecYBg2V98AscfiB7ifz/YxNq8JV6HnSWwuIMm8yMCc0HU=','2020-04-22'),(5,5,'e674ceaddf56f7adfb27c3ed7f261f34db1fc9c9c73b9bf80018ed171a78ca687c0fd5e5beb47e77e1f687d0abe11dbb6bd8fa2c062b09aabc010b58ddf37babJf8y5a+A27PfHy7GcBKdK4RrZApj3YxVG+Hz0Iy4BgU=','2020-04-22'),(6,6,'d34b9b8727c8a57a69ca29e13ea62f72716c8eb16514b866989e4b554cd8473fd5462e010e0b6c0cb2ea4128c832b42287cb487f332b92abe3df23579b10c81ddq2PeNuH9uVBnKGthkJqQLlKDTR+2olb6WaG2DH+v2E=','2020-04-22'),(7,7,'ef5a77017a2d03e76300cace27fcc300b60dcebbe47d7442b913ae8993307be3e564cc266e0c34074864c2fff6fc273c16e4bc5fb918e3ad82ad4b15bb413394oGr59Lpt1dqQz9zX5IFEP6r5H5udFDuTA3Muz0Jz94c=','2020-04-22'),(8,8,'1ecdca2312fd0444ea7f5f1441cf3d2302d7b20cd424d01f06084319f9ceb27f9561d4cb8283aa40d526268746092c48403ff87e2fc550a9756d5972f9d88202+8/CZBmrV5X6ZY6Lv//ssNOn2EpGansZXI/TkAv43Oc=','2020-04-22'),(9,9,'c844f3af07c74d3a6b80694b51be2834f2ab7d9aa628725311dd367775822bb220547fd98847595a8bba737d96e7927d2bc835da8e3b1821e8739cbbd9d94354lFv1ebaTEeiTja/3R6+sgtOmp6Q7Gka0zJP4jdpSo1I=','2020-04-22'),(10,10,'e56312d51d6018076ff230e9f7a1834ca09af3bc7bbb3df9dcaa89be75168392b203d3ac15eff3b1c51bb76b5e5cbf3f03c81b030a8df82130d1194eb96bec75vG39IfDGzkWpdDPIdaMimtDkRgBm6n40Foh48DZJMq4=','2020-04-22'),(11,11,'73bdc1d336cacdba7e3deb40cbdb444d46c3e692d22a7601c56a6f67181ca3071c41d07924f11d8cfccf4c39d9d0142795eea7161c8a81fcbb1e46e9cc3c3b0a3t+d2jfB2d+WVnPtWXKAcFg7FCPlHgf2X6J9HZbC4pA=','2020-04-22'),(12,12,'05c05eab8d0614b80d70a5a7411816b7beda3c2c26d6097eef42c6de499ebc5f9ce36bbfa661954f80bac52341d4ba30e32b9a124a7c4c51897a2a80224974d3NMY9mTcMz1omJo6FeksiNoAu3cLhEXnYbTeh1uTobuo=','2020-04-22');:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `location`;:||:Separator:||:


CREATE TABLE `location` (
  `idLocation` int(11) NOT NULL AUTO_INCREMENT,
  `locationCode` int(11) DEFAULT NULL,
  `locationName` char(50) DEFAULT NULL,
  PRIMARY KEY (`idLocation`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `location` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `locationhistory`;:||:Separator:||:


CREATE TABLE `locationhistory` (
  `idLocationHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idLocation` int(11) DEFAULT NULL,
  `locationCode` int(11) DEFAULT NULL,
  `locationName` char(50) DEFAULT NULL,
  PRIMARY KEY (`idLocationHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `locationhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `logs`;:||:Separator:||:


CREATE TABLE `logs` (
  `idLog` int(11) NOT NULL AUTO_INCREMENT,
  `idAffiliate` int(11) DEFAULT NULL,
  `idLocation` int(11) DEFAULT NULL,
  `dateLog` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `idEu` int(11) DEFAULT NULL,
  `actionLogDescription` text,
  `idReference` int(11) DEFAULT NULL,
  `referenceNum` int(11) DEFAULT NULL,
  `idModule` int(11) DEFAULT NULL,
  PRIMARY KEY (`idLog`)
) ENGINE=InnoDB AUTO_INCREMENT=269 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `logs` WRITE;:||:Separator:||:
 INSERT INTO `logs` VALUES(1,1,null,'2020-04-22','13:04:37',34,'Added new affiliate, Syntactics, Inc.',null,null,null),(2,1,null,'2020-04-22','13:04:51',34,'Deleted the user account of, April Twenty One.',null,null,null),(3,1,null,'2020-04-22','13:04:55',34,'Deleted the user account of, Grant Willis.',null,null,null),(4,1,null,'2020-04-22','13:04:58',34,'Deleted the user account of, Ahmed Lambert.',null,null,null),(5,1,null,'2020-04-22','13:05:04',34,'Deleted the user account of, Liberty Chandler.',null,null,null),(6,1,null,'2020-04-22','13:05:14',34,'Deleted the user account of, false with username: kulot.',null,null,null),(7,1,null,'2020-04-22','13:05:27',34,'Modified the employee, sysadmin, for System Administrator with usertype Supervisor',null,null,null),(8,null,null,'2020-04-22','13:07:50',34,'sysadmin added a new customer Test with Credit limit, penalty and Discount',null,null,null),(9,null,null,'2020-04-22','13:08:07',34,'Chart of Accounts : 4d54f3968e23e3a165c1a9036fffaa8050fa70ca0aa1080c58171b6ab33ae5a15d0793643dbfe08f74d93a16d71e28c01195e6e420e38f04741f585eaaf7d355w1qQ8HbCG0g/hvp2RruEv4yOpUVC/amH+1FU+YpwvbozIeHZa6Tge4UBhzJ4BvKH modified account code : 4101000.',null,null,null),(10,null,null,'2020-04-22','13:09:10',34,'sysadmin added a new customer w/o credit limit',null,null,null),(11,null,null,'2020-04-22','13:10:12',34,'Added a new supplier, 2b41b9841ab02ee227b955db8f04b9c4a78a5d0e8fa3e3cc1e13248c827b840ac54619bc0e1a3ec5b93dfd27eb8feaab306b85eb88e4305372c7c2f8d3c19f85MEfK80qe8yi9uZKxnKlEZXdIAgzCTBOXXvmFg/fWOlE=',null,null,null),(12,null,null,'2020-04-22','13:12:32',34,'Added a new supplier, 80ef4afff3b57e9f618ad3f6fa3f0bc9210ab05db31b4931eef6f7fdad46b8f0ea7dbfe6a6b37aa7a83628130ffd622b79393608efbd37e13adc3bbcd9746e2bJtj6VAKwJhDAMjEGg1lJNN6rMSHFuPzEIuHF/eyYh94=',null,null,null),(13,null,null,'2020-04-22','13:13:01',34,'sysadmin added a new initial reference PO',null,null,null),(14,null,null,'2020-04-22','13:14:28',34,'sysadmin deleted the series reference PO',null,null,null),(15,null,null,'2020-04-22','13:14:41',34,'sysadmin added a new series reference PO',null,null,null),(16,2,null,'2020-04-22','13:20:41',34,'Added new affiliate, test',null,null,null),(17,2,null,'2020-04-22','13:20:47',34,'Deleted the affiliate, ',null,null,null),(18,null,null,'2020-04-22','13:22:17',34,'sysadmin added a new unit.',null,null,null),(19,null,null,'2020-04-22','13:22:25',34,'sysadmin added a new classification.',null,null,null),(20,null,null,'2020-04-22','13:22:39',34,'sysadmin has logged out of the system.',null,null,null),(21,2,null,'2020-04-22','13:26:48',34,'added a new Item, dda74523efea88aa80a16a83dc56ff94a404655432e483e09907cf0e9329f644608275df52ffa48ab9c5c96c52b11a9f486f56b9774ff6304c115d98f90c34efc4M8xMwJL6zD9VuzBxClUAi9syQngHeqS3RM4lirAPw=',null,null,null),(22,null,null,'2020-04-22','13:28:07',34,'Edited the supplier details of, 8cd648a72cf621848722d77c482228017870755877729fdf83717308d1b2ac92b1a181c1e13fc193e3c8cab7ba63c3be824db4911dcd8977d353bd2b3a19b180x6706Y/espZ7kihVSTHwWg48cCntxmevU6BJB7qKWq8=',null,null,null),(23,null,null,'2020-04-22','13:31:47',34,'sysadmin added a new initial reference RE',null,null,null),(24,null,null,'2020-04-22','13:32:19',34,'sysadmin added a new series reference RE',null,null,null),(25,2,null,'2020-04-22','13:32:45',34,'4d54f3968e23e3a165c1a9036fffaa8050fa70ca0aa1080c58171b6ab33ae5a15d0793643dbfe08f74d93a16d71e28c01195e6e420e38f04741f585eaaf7d355w1qQ8HbCG0g/hvp2RruEv4yOpUVC/amH+1FU+YpwvbozIeHZa6Tge4UBhzJ4BvKH added a new receiving transaction.',2,1,2),(26,null,null,'2020-04-22','13:33:01',34,'Generates Inventory Ledger Report.',null,null,null),(27,null,null,'2020-04-22','13:33:07',34,'Generates Inventory Ledger Report.',null,null,null),(28,null,null,'2020-04-22','13:33:34',34,'sysadmin added a new initial reference PR',null,null,null),(29,null,null,'2020-04-22','13:36:59',34,'sysadmin added a new series reference PR',null,null,null),(30,2,null,'2020-04-22','13:37:30',34,'4d54f3968e23e3a165c1a9036fffaa8050fa70ca0aa1080c58171b6ab33ae5a15d0793643dbfe08f74d93a16d71e28c01195e6e420e38f04741f585eaaf7d355w1qQ8HbCG0g/hvp2RruEv4yOpUVC/amH+1FU+YpwvbozIeHZa6Tge4UBhzJ4BvKH added a new purchase return transaction.',3,1,29),(31,null,null,'2020-04-22','13:37:36',34,'Generates Inventory Ledger Report.',null,null,null),(32,null,null,'2020-04-22','13:38:08',34,'sysadmin added a new initial reference SO',null,null,null),(33,null,null,'2020-04-22','13:38:19',34,'sysadmin added a new series reference SO',null,null,null),(34,null,null,'2020-04-22','13:38:46',34,'Sales Order : 4d54f3968e23e3a165c1a9036fffaa8050fa70ca0aa1080c58171b6ab33ae5a15d0793643dbfe08f74d93a16d71e28c01195e6e420e38f04741f585eaaf7d355w1qQ8HbCG0g/hvp2RruEv4yOpUVC/amH+1FU+YpwvbozIeHZa6Tge4UBhzJ4BvKH added a new Sales Order transaction',4,1,17),(35,null,null,'2020-04-22','13:39:31',34,'sysadmin added a new initial reference SE',null,null,null),(36,null,null,'2020-04-22','13:39:41',34,'sysadmin added a new series reference SE',null,null,null),(37,null,null,'2020-04-22','13:40:08',34,'Sales : 4d54f3968e23e3a165c1a9036fffaa8050fa70ca0aa1080c58171b6ab33ae5a15d0793643dbfe08f74d93a16d71e28c01195e6e420e38f04741f585eaaf7d355w1qQ8HbCG0g/hvp2RruEv4yOpUVC/amH+1FU+YpwvbozIeHZa6Tge4UBhzJ4BvKH added a new Sales Order transaction ',5,1,18),(38,null,null,'2020-04-22','13:40:16',34,'Generates Inventory Ledger Report.',null,null,null),(39,null,null,'2020-04-22','13:41:18',34,'sysadmin added a new initial reference SR',null,null,null),(40,null,null,'2020-04-22','13:41:29',34,'sysadmin added a new series reference SR',null,null,null),(41,null,null,'2020-04-22','13:41:49',34,'Sales Return : 4d54f3968e23e3a165c1a9036fffaa8050fa70ca0aa1080c58171b6ab33ae5a15d0793643dbfe08f74d93a16d71e28c01195e6e420e38f04741f585eaaf7d355w1qQ8HbCG0g/hvp2RruEv4yOpUVC/amH+1FU+YpwvbozIeHZa6Tge4UBhzJ4BvKH added a new Sales Return transaction',6,1,21),(42,null,null,'2020-04-22','13:41:53',34,'Generates Inventory Ledger Report.',null,null,null),(43,null,null,'2020-04-22','13:42:49',34,'sysadmin added a new initial reference IC',null,null,null),(44,null,null,'2020-04-22','13:43:01',34,'sysadmin added a new series reference IC',null,null,null),(45,null,null,'2020-04-22','13:47:03',34,'sysadmin added a new initial reference I-AD',null,null,null),(46,null,null,'2020-04-22','13:47:17',34,'sysadmin added a new series reference I-AD',null,null,null),(47,null,null,'2020-04-22','13:47:35',34,'Chart of Accounts : 4d54f3968e23e3a165c1a9036fffaa8050fa70ca0aa1080c58171b6ab33ae5a15d0793643dbfe08f74d93a16d71e28c01195e6e420e38f04741f585eaaf7d355w1qQ8HbCG0g/hvp2RruEv4yOpUVC/amH+1FU+YpwvbozIeHZa6Tge4UBhzJ4BvKH modified account code : 3101000.',null,null,null),(48,null,null,'2020-04-22','13:47:42',34,'Chart of Accounts : 4d54f3968e23e3a165c1a9036fffaa8050fa70ca0aa1080c58171b6ab33ae5a15d0793643dbfe08f74d93a16d71e28c01195e6e420e38f04741f585eaaf7d355w1qQ8HbCG0g/hvp2RruEv4yOpUVC/amH+1FU+YpwvbozIeHZa6Tge4UBhzJ4BvKH modified account code : 2101000.',null,null,null),(49,null,null,'2020-04-22','13:47:49',34,'Chart of Accounts : 4d54f3968e23e3a165c1a9036fffaa8050fa70ca0aa1080c58171b6ab33ae5a15d0793643dbfe08f74d93a16d71e28c01195e6e420e38f04741f585eaaf7d355w1qQ8HbCG0g/hvp2RruEv4yOpUVC/amH+1FU+YpwvbozIeHZa6Tge4UBhzJ4BvKH modified account code : 1102000.',null,null,null),(50,null,null,'2020-04-22','13:47:58',34,'Chart of Accounts : 4d54f3968e23e3a165c1a9036fffaa8050fa70ca0aa1080c58171b6ab33ae5a15d0793643dbfe08f74d93a16d71e28c01195e6e420e38f04741f585eaaf7d355w1qQ8HbCG0g/hvp2RruEv4yOpUVC/amH+1FU+YpwvbozIeHZa6Tge4UBhzJ4BvKH modified account code : 1101000.',null,null,null),(51,2,null,'2020-04-22','13:48:43',34,'4d54f3968e23e3a165c1a9036fffaa8050fa70ca0aa1080c58171b6ab33ae5a15d0793643dbfe08f74d93a16d71e28c01195e6e420e38f04741f585eaaf7d355w1qQ8HbCG0g/hvp2RruEv4yOpUVC/amH+1FU+YpwvbozIeHZa6Tge4UBhzJ4BvKH added a new adjustment transaction.',8,1,23),(52,null,null,'2020-04-22','13:51:25',34,'sysadmin added a new initial reference VR',null,null,null),(53,null,null,'2020-04-22','13:51:52',34,'sysadmin added a new series reference VR',null,null,null),(54,null,null,'2020-04-22','13:52:27',34,'Vouchers Receivable : 4d54f3968e23e3a165c1a9036fffaa8050fa70ca0aa1080c58171b6ab33ae5a15d0793643dbfe08f74d93a16d71e28c01195e6e420e38f04741f585eaaf7d355w1qQ8HbCG0g/hvp2RruEv4yOpUVC/amH+1FU+YpwvbozIeHZa6Tge4UBhzJ4BvKH added a new Vouchers Receivable Transaction.',9,1,58),(55,null,null,'2020-04-22','13:53:32',34,'sysadmin added a new initial reference VP',null,null,null),(56,null,null,'2020-04-22','13:53:42',34,'sysadmin added a new series reference VP',null,null,null),(57,null,null,'2020-04-22','13:54:06',34,'Vouchers Payable : 4d54f3968e23e3a165c1a9036fffaa8050fa70ca0aa1080c58171b6ab33ae5a15d0793643dbfe08f74d93a16d71e28c01195e6e420e38f04741f585eaaf7d355w1qQ8HbCG0g/hvp2RruEv4yOpUVC/amH+1FU+YpwvbozIeHZa6Tge4UBhzJ4BvKH added a new Vouchers Payable Transaction.',10,1,57),(58,null,null,'2020-04-22','13:55:29',34,'sysadmin added a new initial reference A-AD',null,null,null),(59,null,null,'2020-04-22','13:56:10',34,'sysadmin added a new series reference A-AD',null,null,null),(60,null,null,'2020-04-22','13:57:43',34,'sysadmin added a new initial reference CR',null,null,null),(61,null,null,'2020-04-22','13:58:12',34,'sysadmin added a new series reference CR',null,null,null),(62,null,null,'2020-04-22','14:05:19',34,'sysadmin added a new initial reference BB',null,null,null),(63,null,null,'2020-04-22','14:05:32',34,'sysadmin added a new series reference BB',null,null,null),(64,null,null,'2020-04-22','14:05:55',34,'Beginning Balance : 4d54f3968e23e3a165c1a9036fffaa8050fa70ca0aa1080c58171b6ab33ae5a15d0793643dbfe08f74d93a16d71e28c01195e6e420e38f04741f585eaaf7d355w1qQ8HbCG0g/hvp2RruEv4yOpUVC/amH+1FU+YpwvbozIeHZa6Tge4UBhzJ4BvKH added a new Beginnning Balance Transaction.',13,1,62),(65,null,null,'2020-04-22','14:08:51',34,'Generates Collection Summary Report.',null,null,null),(66,null,null,'2020-04-22','14:09:49',34,'Sales Return : 4d54f3968e23e3a165c1a9036fffaa8050fa70ca0aa1080c58171b6ab33ae5a15d0793643dbfe08f74d93a16d71e28c01195e6e420e38f04741f585eaaf7d355w1qQ8HbCG0g/hvp2RruEv4yOpUVC/amH+1FU+YpwvbozIeHZa6Tge4UBhzJ4BvKH deleted a transaction',6,1,21),(67,null,null,'2020-04-22','14:10:00',34,'Sales : 4d54f3968e23e3a165c1a9036fffaa8050fa70ca0aa1080c58171b6ab33ae5a15d0793643dbfe08f74d93a16d71e28c01195e6e420e38f04741f585eaaf7d355w1qQ8HbCG0g/hvp2RruEv4yOpUVC/amH+1FU+YpwvbozIeHZa6Tge4UBhzJ4BvKH deleted a transaction',5,1,18),(68,2,null,'2020-04-22','14:11:13',34,'4d54f3968e23e3a165c1a9036fffaa8050fa70ca0aa1080c58171b6ab33ae5a15d0793643dbfe08f74d93a16d71e28c01195e6e420e38f04741f585eaaf7d355w1qQ8HbCG0g/hvp2RruEv4yOpUVC/amH+1FU+YpwvbozIeHZa6Tge4UBhzJ4BvKH cancelled a transaction.',3,1,29),(69,2,null,'2020-04-22','14:20:03',34,'Modified the module access for the user account, sysadmin of 45197fd27b55626c1637e403a8423338fde250e74db78c12647760ddf7d5098f96b30406b2981a49d51a5f69097f62f246e80b48a7a3431186c0c71b6a5ab0aaop2yS8OcjEqIaDOTebhkDfTmmeySay5FZcb7flxX7dQDo+SR+hbvjCVhtF5Llj5e, with usertype Administrator.',null,null,null),(70,null,null,'2020-04-22','14:25:57',34,'Generates Inventory Ledger Report.',null,null,null),(71,null,null,'2020-04-22','14:37:32',34,'sysadmin added a new customer LGU - Kiokong',null,null,null),(72,null,null,'2020-04-22','14:38:49',34,'sysadmin added a new customer DPWH',null,null,null),(73,null,null,'2020-04-22','14:40:06',34,'Generates Receivable Schedule Report',null,null,null),(74,null,null,'2020-04-22','14:40:24',34,'Added a new supplier, 8f72a77dffcc0b3e285a3acc760268316e3bfd14ec81e359949a30826d0450dd2c92ba3b4dd841515b9a742a6cb955b07013a5fba7b7af63abc31a896be4eac2q4UVyl0uC5Id46xPYJ0Og+bsTjpv/CTYc/EaK7l75E8=',null,null,null),(75,null,null,'2020-04-22','14:41:16',34,'Added a new supplier, ad09f5b27501a6c204d2875727609848106a744aea6dc14646ca03ca780fd9c5f1ed9d86cc475fd2cb94228993f2ff6baa44229b03b7e433cb22195a8a1ad5c131mrDEHb8YEPDCv9PEVFo8tQ5GVdysUgRI2+boPgkF0=',null,null,null),(76,null,null,'2020-04-22','14:42:17',34,'sysadmin edited the customer details of LGU - Kiokong',null,null,null),(77,null,null,'2020-04-22','14:42:55',34,'sysadmin edited the customer details of DPWH',null,null,null),(78,null,null,'2020-04-22','14:43:42',34,'sysadmin edited the bank BPI',null,null,null),(79,null,null,'2020-04-22','14:43:49',34,'sysadmin edited the bank PNB',null,null,null),(80,null,null,'2020-04-22','14:43:59',34,'sysadmin edited the bank Land Bank',null,null,null),(81,null,null,'2020-04-22','14:44:19',34,'sysadmin edited the bank AUB',null,null,null),(82,null,null,'2020-04-22','14:44:24',34,'sysadmin edited the bank China Bank',null,null,null),(83,null,null,'2020-04-22','14:45:31',34,'sysadmin added a new unit.',null,null,null),(84,null,null,'2020-04-22','14:45:37',34,'sysadmin added a new unit.',null,null,null),(85,null,null,'2020-04-22','14:45:46',34,'sysadmin added a new unit.',null,null,null),(86,null,null,'2020-04-22','14:46:08',34,'sysadmin added a new unit.',null,null,null),(87,null,null,'2020-04-22','14:48:07',34,'sysadmin added a new unit.',null,null,null),(88,null,null,'2020-04-22','14:48:16',34,'sysadmin added a new unit.',null,null,null),(89,null,null,'2020-04-22','14:48:22',34,'sysadmin added a new unit.',null,null,null),(90,null,null,'2020-04-22','14:48:31',34,'sysadmin added a new unit.',null,null,null),(91,null,null,'2020-04-22','14:49:07',34,'sysadmin added a new classification.',null,null,null),(92,null,null,'2020-04-22','14:49:21',34,'sysadmin added a new classification.',null,null,null),(93,null,null,'2020-04-22','14:50:17',34,'sysadmin added a new classification.',null,null,null),(94,null,null,'2020-04-22','14:50:43',34,'sysadmin added a new classification.',null,null,null),(95,null,null,'2020-04-22','14:50:55',34,'sysadmin added a new classification.',null,null,null),(96,2,null,'2020-04-22','14:58:07',34,'added a new Item, 640010f4b6345a792afd31476cc8c9a52ab20c16870dce11a5640dfaacddddd77254e6c52d859231ff129e4b37106e71c7da5fe13b3bb3120b5ce77f831d004e5RdyWUNbKnQegeGl0UVot4oqE6sGjlL1TPONjxDUymw=',null,null,null),(97,2,null,'2020-04-22','15:05:30',34,'added a new Item, d35f2dd3aec96535d6e9a0ce576cb132e778889b223d79a4791d253fd2701c8e81bb9dc2ad41ba4c9870813795e16655fabaa1e3c640cb8243af28c804a7a76c60/ACcuriPW+yu74LkvLw1eGBPT8eO7iw63qWJWV6Vk=',null,null,null),(98,2,null,'2020-04-22','15:08:10',34,'added a new Item, 30866b9fdf1d86ed3d036417d86e1833ac31dff74e58cdd90cdb48dcbf268b5c4b0f089fee72d17c3d4b19abe128917cc2fdd8315e73a2e7d9cc22cfaecffa89jqjLspnrOA/w4fbhXQb08QO6Xs7fE3dxhIMm6wQ6cNI=',null,null,null),(99,2,null,'2020-04-22','15:11:40',34,'added a new Item, 6c97820107ef11d1dc06ebf233264331f42aa1295051a21c0f19beb3fd972068dbc4cb59f37e2c23990cf34764bba1537bcfc82aad6c6b901c07b8dbd295931fy5uXVqp+ME3Va3FVzIhKD3mJ5A8GyPzFCrbvbsuPubA=',null,null,null),(100,2,null,'2020-04-22','15:14:35',34,'added a new Item, 3324c9226a39c6aaab0ee498726a56dbb5143a482b419e2d033e4e488dc0018235b26b8ddaf21d4182cb5e1559ba641742420cf21e67a32f181c5f19374a1b65AJSOwj8eujDFUEuz+pYfPv0C1zjhtGAb6qzgJ69I7HFfPHK0yVp4hRGw62Q+uFYp',null,null,null),(101,2,null,'2020-04-22','15:15:53',34,'added a new Item, be93986548db8b4ac53d1f4233330ee3301115c83ae9b3f69d9a8f0ef9f49d432b0f5e34f0734f706a4e479fa7c154dad85fb9d862477071cbdb8b7e3c3bba84QaRhWXAsngHP1rAJKz85U5v/XtrYr4OsHkuTXDXSez8K6F1XVSBOHptR429RIHML',null,null,null),(102,2,null,'2020-04-22','15:32:32',34,'added a new Item, 5f1981bfa8119c0313ef46ae50c06425dd262befc2e47e6610ca519b1702fa80ac75e8d19b043725ed50a72aad67ee739a0acc52af107a152ac44767743e6e9a1ixWZJFeV1q570VjxuMuix555bR57cdTIDtnKueIddN8T0ug0rwTVGAvDfMhaIMa',null,null,null),(103,2,null,'2020-04-22','15:34:07',34,'added a new Item, 996f8bc74cb55ff900c724dd8ddd9016109580bb5d7df4943be75cef9a9030bbf90da5017bf3ef505087213c1773a6879934153ac2024024d4d7933358f2159c0BlP+2hWla4R10CQOrAaHl+EK9OR94SlrasCP0p2zD9m43tbVYYTsgkvQFQ25Tjx',null,null,null),(104,2,null,'2020-04-22','15:35:16',34,'added a new Item, 68059dc986f4db2694b58c29b247c79bc0d41e866fb2168ec39db659c65b188115ad6605644c3aa76cb2472302ad44fbbe1453b527d9e7560f2653fccda99f9ccVz8GD25QUQK0Hh8YFLVtS/gwd9VnjGZNCPB2Uy1xqwAmEcDLYg+sORA3RzIXjTN',null,null,null),(105,2,null,'2020-04-22','15:36:49',34,'added a new Item, 2dc116cf45f35f8463b785ab5fa8d66885bdd75e54105debe91051026882c9b12a343cbfda4c35a0fbdcf01cf93932c4df437003cbbe1a026cef3d0f70545cc8Zr340q0Yzv3tCiaanjj15ayP1Obf0nCkAaC3j/0YXb+ftwqQhB4An/BqxZ6THCsr',null,null,null),(106,2,null,'2020-04-22','15:39:09',34,'added a new Item, ff79c0008971ffbe1f8719e1b49cf02d41f3c7d7f3627d6a3a1cf79e2afdf5744a3f4ae8cf7ce3e150bb0fb3e5cca0b2b149807906b010d226a21c1f531031eash8QJShoFngkesN97QnLazKizBgaulggJtWYHi75wUdRsreqTE17r7aQnmQFkKId',null,null,null),(107,null,null,'2020-04-22','18:35:28',34,'Generates Receivable Schedule Report',null,null,null),(108,null,null,'2020-04-22','18:35:29',34,'Generates Receivable Schedule Report',null,null,null),(109,null,null,'2020-04-22','18:35:29',34,'Generates Receivable Schedule Report',null,null,null),(110,null,null,'2020-04-22','18:35:30',34,'Generates Receivable Schedule Report',null,null,null),(111,null,null,'2020-04-22','18:35:30',34,'Generates Receivable Schedule Report',null,null,null),(112,null,null,'2020-04-22','18:35:30',34,'Generates Receivable Schedule Report',null,null,null),(113,null,null,'2020-04-22','18:35:33',34,'Generates payable schedule report',null,null,null),(114,null,null,'2020-04-22','18:35:33',34,'Generates payable schedule report',null,null,null),(115,null,null,'2020-04-22','18:35:33',34,'Generates payable schedule report',null,null,null),(116,null,null,'2020-04-22','18:35:33',34,'Generates payable schedule report',null,null,null),(117,null,null,'2020-04-22','18:35:33',34,'Generates payable schedule report',null,null,null),(118,null,null,'2020-04-22','18:35:34',34,'Generates payable schedule report',null,null,null),(119,null,null,'2020-04-22','18:35:34',34,'Generates payable schedule report',null,null,null),(120,null,null,'2020-04-22','18:53:49',34,'Accouting Defaults : 4d54f3968e23e3a165c1a9036fffaa8050fa70ca0aa1080c58171b6ab33ae5a15d0793643dbfe08f74d93a16d71e28c01195e6e420e38f04741f585eaaf7d355w1qQ8HbCG0g/hvp2RruEv4yOpUVC/amH+1FU+YpwvbozIeHZa6Tge4UBhzJ4BvKH  modified default account setting for affiliate : Syntactics, Inc.',null,null,null),(121,null,null,'2020-04-23','12:57:30',34,'sysadmin added a new initial reference ST',null,null,null),(122,null,null,'2020-04-23','12:57:41',34,'sysadmin added a new series reference ST',null,null,null),(123,null,null,'2020-04-23','13:20:11',34,'Chart of Accounts :  added new account code : 1103000.',null,null,null),(124,null,null,'2020-04-23','13:24:22',34,'Chart of Accounts :  added new account code : 2102000.',null,null,null),(125,null,null,'2020-04-23','13:24:57',34,'Chart of Accounts :  added new account code : 5101000.',null,null,null),(126,null,null,'2020-04-23','13:27:11',34,'Chart of Accounts :  added new account code : 4102000.',null,null,null),(127,null,null,'2020-04-23','13:27:49',34,'Chart of Accounts :  added new account code : 4103000.',null,null,null),(128,null,null,'2020-04-23','13:28:10',34,'Chart of Accounts :  modified account code : 4103000.',null,null,null),(129,null,null,'2020-04-23','13:28:44',34,'Chart of Accounts :  added new account code : 1102001.',null,null,null),(130,null,null,'2020-04-23','13:29:15',34,'Chart of Accounts :  added new account code : 1101001.',null,null,null),(131,null,null,'2020-04-23','13:32:12',34,'Chart of Accounts :  added new account code : 5102000.',null,null,null),(132,null,null,'2020-04-23','13:32:40',34,'Chart of Accounts :  added new account code : 5102001.',null,null,null),(133,null,null,'2020-04-23','13:34:27',34,' added a beginning balance.',null,null,null),(134,null,null,'2020-04-23','13:44:12',34,'Accouting Defaults :   modified default account setting for affiliate : Syntactics, Inc.',null,null,null),(135,2,null,'2020-04-23','13:45:40',34,'Added new affiliate, Cairo',null,null,null),(136,null,null,'2020-04-23','16:15:44',34,'sysadmin added a new initial reference JV',null,null,null),(137,null,null,'2020-04-23','16:16:03',34,'sysadmin added a new series reference JV',null,null,null),(138,null,null,'2020-04-23','16:21:25',34,'sysadmin edited the details of series reference JV',null,null,null),(139,null,null,'2020-04-23','16:33:43',34,' added a new adjustment Transaction.',2,1,48),(140,null,null,'2020-04-23','19:05:30',34,'sysadmin added a new initial reference VR',null,null,null),(141,null,null,'2020-04-23','19:05:46',34,'sysadmin added a new series reference VR',null,null,null),(142,null,null,'2020-04-23','19:06:34',34,'sysadmin edited the details of series reference VR',null,null,null),(143,null,null,'2020-04-23','19:06:48',34,'sysadmin edited the details of series reference VR',null,null,null),(144,null,null,'2020-04-23','19:11:14',34,'Vouchers Receivable :  added a new Vouchers Receivable Transaction.',3,1,58),(145,null,null,'2020-04-23','19:12:31',34,'Vouchers Receivable :  added a new Vouchers Receivable Transaction.',3,2,58),(146,null,null,'2020-04-23','19:13:45',34,'Vouchers Receivable :  edited a transaction.',3,1,58),(147,null,null,'2020-04-23','19:14:31',34,'Vouchers Receivable :  edited a transaction.',3,1,58),(148,2,null,'2020-04-23','19:21:56',34,' Closed the month February year 2020.',1,1,35),(149,2,null,'2020-04-23','19:22:07',34,' deleted a transaction.',null,null,35),(150,null,null,'2020-04-23','19:56:39',34,'sysadmin added a new initial reference RR',null,null,null),(151,null,null,'2020-04-23','19:56:54',34,'sysadmin added a new series reference RR',null,null,null),(152,2,null,'2020-04-23','19:57:30',34,' added a new receiving transaction.',4,1,2),(153,2,null,'2020-04-23','19:57:50',34,' added a new receiving transaction.',4,2,2),(154,null,null,'2020-04-23','19:58:03',34,'Generates Receivable Transactions Report.',null,null,null),(155,null,null,'2020-04-23','19:58:11',34,'Generates Receivable Balance.',null,null,null),(156,null,null,'2020-04-23','19:58:17',34,'Generates Receivable Balance.',null,null,null),(157,null,null,'2020-04-23','19:59:11',34,'Generates Receivable Transactions Report.',null,null,null),(158,null,null,'2020-04-23','19:59:59',34,'Generates Receivable Balance.',null,null,null),(159,null,null,'2020-04-23','20:04:35',34,'Generates Receivable Balance.',null,null,null),(160,null,null,'2020-04-23','20:06:32',34,'Generates Receivable Balance.',null,null,null),(161,null,null,'2020-04-23','20:13:24',34,'sysadmin added a new initial reference SI',null,null,null),(162,null,null,'2020-04-23','20:13:39',34,'sysadmin added a new series reference SI',null,null,null),(163,null,null,'2020-04-23','20:14:59',34,'Sales :  added a new Sales Order transaction ',5,1,18),(164,null,null,'2020-04-23','20:15:17',34,'Sales :  added a new Sales Order transaction ',5,2,18),(165,null,null,'2020-04-23','20:15:21',34,'Generates Receivable Balance.',null,null,null),(166,null,null,'2020-04-23','20:15:23',34,'Generates Receivable Balance.',null,null,null),(167,null,null,'2020-04-23','20:15:58',34,'Generates Receivable Balance.',null,null,null),(168,null,null,'2020-04-23','20:16:10',34,'Generates Receivable Transactions Report.',null,null,null),(169,null,null,'2020-04-23','20:18:53',34,'Generates Receivable Transactions Report.',null,null,null),(170,null,null,'2020-04-23','20:18:56',34,'Generates Receivable Balance.',null,null,null),(171,null,null,'2020-04-24','07:03:12',34,'sysadmin added a new initial reference PO',null,null,null),(172,null,null,'2020-04-24','07:03:30',34,'sysadmin added a new series reference PO',null,null,null),(173,2,null,'2020-04-24','07:03:59',34,' added a new transaction.',6,1,2),(174,null,null,'2020-04-24','13:04:02',34,'Generates Receivable Balance.',null,null,null),(175,2,null,'2020-04-24','13:47:21',34,' added a new transaction.',6,2,2),(176,2,null,'2020-04-24','13:47:26',34,'Generates Purchase Order Monitoring',null,null,30),(177,2,null,'2020-04-24','13:48:41',34,' added a new receiving transaction.',4,3,2),(178,null,null,'2020-04-24','13:48:50',34,'Generates Inventory Ledger Report.',null,null,null),(179,2,null,'2020-04-24','13:50:08',34,' edited a transaction.',4,3,2),(180,2,null,'2020-04-24','13:52:55',34,'Generates Purchase Order Monitoring',null,null,30),(181,null,null,'2020-04-24','13:58:46',34,'Accouting Defaults :  added new default journal entry for the purpose of Sales',null,null,null),(185,null,null,'2020-04-24','14:06:11',34,'Accouting Defaults :  added new default journal entry for the purpose of Payable',null,null,null),(186,2,null,'2020-04-24','14:11:12',34,' added a new receiving transaction.',4,4,2),(187,2,null,'2020-04-24','17:07:24',34,' Closed the month March year 2018.',1,2,35),(188,2,null,'2020-04-24','17:11:01',34,' deleted a transaction.',null,null,35),(189,2,null,'2020-04-24','17:37:25',34,' Closed the month March year 2018.',1,3,35),(190,2,null,'2020-04-24','17:52:23',34,' deleted a transaction.',null,null,35),(191,2,null,'2020-04-24','18:15:40',34,' Closed the month March year 2018.',1,4,35),(192,2,null,'2020-04-24','18:25:32',34,' deleted a transaction.',null,null,35),(193,2,null,'2020-04-24','18:28:34',34,' deleted a transaction.',null,null,35),(194,2,null,'2020-04-24','18:30:14',34,' deleted a transaction.',null,null,35),(195,2,null,'2020-04-24','18:32:59',34,' deleted a transaction.',null,null,35),(196,2,null,'2020-04-24','18:34:53',34,' deleted a transaction.',null,null,35),(197,2,null,'2020-04-24','18:46:54',34,' deleted a transaction.',null,null,35),(198,2,null,'2020-04-24','18:48:28',34,' deleted a transaction.',null,null,35),(199,2,null,'2020-04-24','18:48:37',34,' Closed the month March year 2018.',1,11,35),(200,2,null,'2020-04-24','18:49:26',34,' Closed the month January year 2020.',1,12,35),(201,2,null,'2020-04-24','18:49:59',34,' deleted a transaction.',null,null,35),(202,2,null,'2020-04-24','18:50:06',34,' Closed the month January year 2020.',1,13,35),(203,2,null,'2020-04-24','18:50:11',34,' deleted a transaction.',null,null,35),(204,2,null,'2020-04-24','18:52:10',34,' edited a transaction.',1,11,35),(205,2,null,'2020-04-24','18:52:20',34,' Closed the month January year 2020.',1,14,35),(206,2,null,'2020-04-24','18:52:28',34,' Closed the month April year 2020.',1,15,35),(207,2,null,'2020-04-24','18:52:49',34,' deleted a transaction.',null,null,35),(208,null,null,'2020-04-24','18:53:44',34,'Vouchers Receivable :  added a new Vouchers Receivable Transaction.',3,3,58),(209,2,null,'2020-04-24','19:13:47',34,' deleted a transaction.',null,null,35),(210,2,null,'2020-04-24','19:15:22',34,' Closed the month January year 2020.',1,16,35),(211,2,null,'2020-04-25','09:32:53',34,'Generates Purchase return summary report',null,0,39),(212,null,null,'2020-04-25','09:32:57',34,'Generates Releasing Summary Report.',null,null,null),(213,null,null,'2020-04-25','09:33:02',34,' Generates Sales Summary Report',null,null,null),(214,null,null,'2020-04-25','09:33:17',34,'Generates Receivable Transactions Report.',null,null,null),(215,null,null,'2020-04-25','09:33:21',34,'Generates Receivable Balance.',null,null,null),(216,null,null,'2020-04-25','09:33:24',34,'Generates Itemized Profit and Loss Report.',null,null,null),(217,null,null,'2020-04-25','09:33:27',34,'Cancelled Transactions :  generates Cancelled Transactions Report',null,null,null),(218,null,null,'2020-04-25','09:35:04',34,' edited the beginning balance.',null,null,null),(219,2,null,'2020-04-25','09:35:30',34,' deleted a transaction.',null,null,35),(220,2,null,'2020-04-25','09:35:35',34,' deleted a transaction.',null,null,35),(221,2,null,'2020-04-25','09:35:50',34,' Closed the month March year 2018.',1,17,35),(222,2,null,'2020-04-25','09:36:12',34,' Closed the month June year 2018.',1,18,35),(223,null,null,'2020-04-25','09:41:11',34,' edited the beginning balance.',null,null,null),(224,2,null,'2020-04-25','09:41:23',34,' deleted a transaction.',null,null,35),(225,2,null,'2020-04-25','09:41:28',34,' deleted a transaction.',null,null,35),(226,2,null,'2020-04-25','09:41:38',34,' Closed the month March year 2018.',1,19,35),(227,2,null,'2020-04-25','09:43:22',34,' Closed the month April year 2018.',1,20,35),(228,2,null,'2020-04-25','09:43:32',34,' Closed the month May year 2018.',1,21,35),(229,2,null,'2020-04-25','09:43:52',34,' Closed the month January year 2020.',1,22,35),(231,null,null,'2020-04-25','09:51:00',34,'sysadmin added a new initial reference IC',null,null,null),(232,null,null,'2020-04-25','09:51:20',34,'sysadmin added a new series reference IC',null,null,null),(233,null,null,'2020-04-25','09:51:31',34,'Sales :  added a new Sales Order transaction ',5,3,18),(236,null,null,'2020-04-25','09:55:47',34,'Sales :  edited a transaction ',5,3,18),(237,null,null,'2020-04-25','09:57:01',34,'Sales :  added a new Sales Order transaction ',5,4,18),(238,null,null,'2020-04-25','10:01:46',34,'sysadmin added a new initial reference ST',null,null,null),(239,null,null,'2020-04-25','10:02:03',34,'sysadmin added a new series reference ST',null,null,null),(240,2,null,'2020-04-25','10:03:24',34,'Modified the employee, sysadmin, for System Administrator with usertype Supervisor',null,null,null),(241,null,null,'2020-04-25','10:04:11',34,'sysadmin transferred stocks to Cairo',null,null,null),(242,null,null,'2020-04-25','10:06:54',34,'sysadmin added a new initial reference I-AD',null,null,null),(243,null,null,'2020-04-25','10:07:33',34,'sysadmin added a new series reference I-AD',null,null,null),(244,2,null,'2020-04-25','10:14:26',34,' added a new adjustment transaction.',9,1,23),(245,null,null,'2020-04-25','10:16:44',34,' added a new adjustment Transaction.',2,2,48),(246,null,null,'2020-04-25','10:31:33',34,'Generates Collection Summary Report.',null,null,null),(247,null,null,'2020-04-25','10:31:49',34,'Generates payable schedule report',null,null,null),(248,null,null,'2020-04-25','10:31:51',34,'Generates Receivable Schedule Report',null,null,null),(249,null,null,'2020-04-25','10:31:53',34,'Generates Aging of Receivables.',null,null,null),(250,null,null,'2020-04-25','10:31:56',34,'Generates Aging of Payables.',null,null,null),(251,null,null,'2020-04-25','10:35:43',34,'Generates Collection Summary Report.',null,null,null),(252,null,null,'2020-04-25','10:36:29',34,'Generates Adjustment Summary Report.',null,null,null),(253,null,null,'2020-04-25','10:36:30',34,'Generates Adjustment Summary Report.',null,null,null),(254,null,null,'2020-04-25','10:36:44',34,'Generates Conversions Summary Report.',null,null,null),(255,2,null,'2020-04-25','11:58:27',34,' Closed the month February year 2020.',1,23,35),(256,2,null,'2020-04-25','11:58:34',34,' Closed the month March year 2020.',1,24,35),(257,2,null,'2020-04-25','11:58:43',34,' Closed the month April year 2020.',1,25,35),(258,null,null,'2020-04-27','08:11:34',34,' Generates Sales Summary Report',null,null,null),(259,null,null,'2020-04-27','08:11:37',34,'Generates Releasing Summary Report.',null,null,null),(260,null,null,'2020-04-27','08:11:45',34,'Generates Receivable Balance.',null,null,null),(261,null,null,'2020-04-27','08:11:59',34,'Generates Inventory Balances.',null,null,null),(262,null,null,'2020-04-27','08:12:02',34,'Generates Conversions Summary Report.',null,null,null),(263,null,null,'2020-04-27','08:12:05',34,'Generates Adjustment Summary Report.',null,null,null),(264,null,null,'2020-04-27','08:12:14',34,'Generates Collection Summary Report.',null,null,null),(265,null,null,'2020-04-27','09:32:33',34,'Modified the classification, On-call',null,null,null),(266,null,null,'2020-04-27','09:34:37',34,'Modified the classification, Part-time',null,null,null),(267,null,null,'2020-04-27','09:34:54',34,'Modified the classification, Full-time',null,null,null),(268,null,null,'2020-04-27','09:35:16',34,'Modified the classification, Temporary',null,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `module`;:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `module` WRITE;:||:Separator:||:
 INSERT INTO `module` VALUES(1,0,3,0,'Dashboard','dashboard/Dashboard.js',0,1),(2,1,0,1,'Purchase Order','inventory/Purchaseorder.js',0,0),(3,5,2,4,'Employee Profile','admin/Usersettings.js',0,1),(4,5,2,1,'Affiliate Settings','admin/Affiliatesettings.js',0,1),(5,5,2,2,'Cost Center Settings','admin/Costcentersettings.js',0,1),(6,5,2,3,'Employee Classification Settings','admin/Empclassificationsettings.js',0,1),(7,5,2,6,'Backup and Restore','admin/Bandr.js',0,1),(8,4,3,3,'Reference Settings','generalsettings/Referencesettings.js',0,1),(9,5,2,5,'User Action Logs','admin/Userlog.js',0,1),(10,4,3,4,'Bank Settings','generalsettings/Banksettings.js',0,1),(11,4,3,1,'Customer Settings','generalsettings/customer.js',0,1),(12,4,3,2,'Supplier Settings','generalsettings/Supplier.js',0,1),(14,1,4,2,'Classification Settings','inventory/Classificationsettings.js',0,1),(15,1,4,3,'Unit Settings','inventory/Unitsettings.js',0,1),(16,1,4,1,'Item Settings','inventory/Item.js',0,1),(17,1,2,1,'Sales Order','inventory/Salesorder.js',0,0),(18,1,2,2,'Sales','inventory/Sales.js',0,0),(19,2,2,1,'Chart of Accounts','accounting/Chartofaccounts.js',0,1),(20,2,2,3,'Accounting Defaults','accounting/Accountingdefaults.js',0,1),(21,1,2,3,'Sales Return','inventory/Salesreturn.js',0,0),(22,1,3,1,'Inventory Conversion','inventory/Inventoryconversion.js',0,0),(23,1,3,2,'Inventory Adjustment','inventory/Adjustments.js',0,0),(24,1,2,5,'Sales Summary','inventory/Salessummary.js',0,1),(25,1,1,1,'Receiving','inventory/Receiving.js',0,0),(26,1,2,6,'Sales Return Summary','inventory/Salesreturnsummary.js',0,1),(27,1,2,7,'SO Monitoring','inventory/Salesordermonitoring.js',0,1),(28,2,0,4,'Cash Receipts','accounting/Cashreceipts.js',0,0),(29,1,1,2,'Purchase Return','inventory/Purchasereturn.js',0,0),(30,1,0,2,'PO Monitoring','inventory/Pomonitoring.js',0,1),(33,1,1,3,'Payable Balance and Ledger','inventory/Payablebalanceledger.js',0,1),(34,1,1,5,'Receiving Summary','inventory/Receivingsummary.js',0,1),(35,2,0,8,'Closing Journal Entry','accounting/Closingentry.js',0,0),(36,2,1,6,'Collection Summary','accounting/Collectionsummary.js',0,1),(37,2,1,7,'Disbursement Summary','accounting/Disbursementsummary.js',0,1),(38,2,1,4,'Financial Report','accounting/Financialreport.js',0,1),(39,1,1,6,'Purchase Return Summary','inventory/Purchasereturnsummary.js',0,1),(40,2,1,2,'General and Subsidiary Ledger','accounting/Generalsubsidiaryledger.js',0,1),(41,1,1,7,'Expiry Monitoring','inventory/Expirymonitoring.js',0,1),(42,2,2,2,'Chart of Accounts Beginning Balance','accounting/Coabegbalance.js',0,1),(43,1,3,3,'Stock Transfer','inventory/Stocktransfer.js',0,0),(44,2,0,7,'Bank Reconciliation','accounting/Bankrecon.js',0,0),(45,2,0,5,'Disbursements','accounting/Disbursements.js',0,0),(46,1,2,9,'Receivable Balances, Ledger and SOA','inventory/Receivablebalanceledger.js',0,1),(47,1,2,10,'Itemized Profit and Loss','inventory/Itemizedprofitloss.js',0,1),(48,2,0,3,'Accounting Adjustment','accounting/Adjustmentsacc.js',0,0),(49,1,1,4,'Payable Transactions','inventory/Payabletransaction.js',0,1),(50,3,1,1,'Payable Schedule','generalreports/Payableschedule.js',0,1),(51,1,3,7,'Adjustment Summary','inventory/Adjustmentsummary.js',0,1),(52,1,2,4,'Releasing Summary','inventory/Releasingsummary.js',0,1),(53,1,3,6,'Conversion Summary','inventory/Conversionsummary.js',0,1),(54,1,2,8,'Receivable Transactions','inventory/Receivabletransaction.js',0,1),(55,3,1,4,'Aging of Payables','generalreports/Agingofpayables.js',0,1),(56,3,1,3,'Aging of Receivables','generalreports/Agingofreceivables.js',0,1),(57,2,0,2,'Vouchers Payable','accounting/Voucherspayable.js',0,0),(58,2,0,1,'Vouchers Receivable','accounting/Vouchersreceivable.js',0,0),(59,1,3,5,'Inventory Ledger','inventory/Inventoryledger.js',0,1),(60,3,1,2,'Schedule of Receivable','generalreports/Scheduleofreceivable.js',0,1),(61,1,3,4,'Inventory Balances','inventory/Inventorybalances.js',0,1),(62,2,0,6,'Beginning Balance','accounting/Beginningbalance.js',0,0),(63,2,2,4,'Bank Account Settings','accounting/Bankaccountsettings.js',0,1),(64,3,1,5,'Cheque Monitoring','generalreports/Chequemonitoring.js',0,1),(65,3,1,6,'Cheque Reports','generalreports/Chequereports.js',0,1),(66,2,1,3,'Accounting Adjustment Summary','accounting/Adjustmentsaccsummary.js',0,1),(67,1,2,11,'Cancelled Transactions','inventory/Cancelledtransactions.js',0,1),(68,2,1,5,'No JE Report','accounting/Nojereport.js',0,1),(69,2,1,1,'Journalized Transaction Summary','accounting/Journalizedtransactionsummary.js',0,1);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `po`;:||:Separator:||:


CREATE TABLE `po` (
  `idPo` int(11) NOT NULL AUTO_INCREMENT,
  `idItem` int(11) DEFAULT NULL,
  `idItemClass` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT '0',
  `qtyLeft` int(11) DEFAULT '0',
  `cost` decimal(18,2) DEFAULT '0.00',
  `idInvoice` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `po` WRITE;:||:Separator:||:
 INSERT INTO `po` VALUES(1,10,3,10,10,1467.00,11),(2,4,4,100,50,1430.00,12);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `pohistory`;:||:Separator:||:


CREATE TABLE `pohistory` (
  `idPoHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idPo` int(11) DEFAULT NULL,
  `idItem` int(11) DEFAULT NULL,
  `idItemClass` varchar(45) DEFAULT NULL,
  `qty` int(11) DEFAULT '0',
  `qtyLeft` int(11) DEFAULT '0',
  `cost` decimal(18,2) DEFAULT '0.00',
  `idInvoice` int(11) DEFAULT NULL,
  `idInvoiceHistory` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPoHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `pohistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `postdated`;:||:Separator:||:


CREATE TABLE `postdated` (
  `idPostdated` int(11) NOT NULL AUTO_INCREMENT,
  `paymentMethod` int(1) DEFAULT NULL COMMENT '1 - Cash\n2 - Cheque\n3 - Terms',
  `idBankAccount` int(11) DEFAULT NULL,
  `chequeNo` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT '0.00',
  `idInvoice` int(11) DEFAULT NULL,
  `idBankRecon` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL COMMENT '1 - Ourstanding\n2 - Cleared\n3 - Canceled\n4 - Bounced',
  `statusDate` date DEFAULT NULL,
  `remarks` char(250) DEFAULT NULL,
  `depositBankAccountId` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPostdated`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `postdated` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `postdatedhistory`;:||:Separator:||:


CREATE TABLE `postdatedhistory` (
  `idPosdatedHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idPostdated` int(11) DEFAULT NULL,
  `paymentMethod` int(1) DEFAULT NULL COMMENT '1 - Cash\n2 - Cheque\n3 - Terms',
  `idBankAccount` int(11) DEFAULT NULL,
  `chequeNo` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT '0.00',
  `idInvoice` int(11) DEFAULT NULL,
  `idBankRecon` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL COMMENT '1 - Ourstanding\n2 - Cleared\n3 - Canceled\n4 - Bounced',
  `statusDate` date DEFAULT NULL,
  `remarks` char(250) DEFAULT NULL,
  `depositBankAccountId` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPosdatedHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `postdatedhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `posting`;:||:Separator:||:


CREATE TABLE `posting` (
  `idPosting` int(11) NOT NULL AUTO_INCREMENT,
  `idInvoice` int(11) DEFAULT NULL,
  `idBankRecon` int(11) DEFAULT NULL,
  `explanation` text,
  `debit` decimal(18,2) DEFAULT '0.00',
  `credit` decimal(18,2) DEFAULT '0.00',
  `idCoa` int(11) DEFAULT NULL,
  `idAccBegBal` int(11) DEFAULT NULL,
  `idCostCenter` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPosting`)
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `posting` WRITE;:||:Separator:||:
 INSERT INTO `posting` VALUES(6,3,null,null,1000.00,0.00,1102000,null,0),(7,3,null,null,500.00,0.00,1101000,null,0),(8,3,null,null,0.00,750.00,2101000,null,0),(9,3,null,null,0.00,250.00,5102001,null,0),(10,3,null,null,0.00,500.00,4102000,null,0),(17,5,null,null,5000.00,0.00,1102001,null,0),(18,5,null,null,0.00,5000.00,4102000,null,0),(25,4,null,null,1000.00,0.00,1102000,null,0),(26,4,null,null,500.00,0.00,1101000,null,0),(27,4,null,null,500.00,0.00,1102001,null,0),(28,4,null,null,0.00,800.00,2101000,null,0),(29,4,null,null,0.00,1000.00,4102000,null,0),(30,4,null,null,0.00,200.00,5102001,null,0),(31,6,null,null,0.00,0.00,3101000,null,null),(32,13,null,null,0.00,71500.00,2101000,null,0),(33,13,null,null,71500.00,0.00,1103000,null,0),(34,17,null,null,745483.50,0.00,2101000,null,0),(35,17,null,null,0.00,745483.50,1103000,null,0),(36,18,null,null,0.00,0.00,3101000,null,null),(37,19,null,null,-60000.00,0.00,4102000,null,null),(38,19,null,null,0.00,-10000.00,4102001,null,null),(39,19,null,null,0.00,-100000.00,5102001,null,null),(40,19,null,null,0.00,50000.00,3101000,null,null),(41,20,null,null,-60000.00,0.00,4102000,null,null),(42,20,null,null,0.00,-10000.00,4102001,null,null),(43,20,null,null,0.00,-100000.00,5102001,null,null),(44,20,null,null,0.00,50000.00,3101000,null,null),(45,21,null,null,-60000.00,0.00,4102000,null,null),(46,21,null,null,0.00,-10000.00,4102001,null,null),(47,21,null,null,0.00,-100000.00,5102001,null,null),(48,21,null,null,0.00,50000.00,3101000,null,null),(49,22,null,null,-60000.00,0.00,4102000,null,null),(50,22,null,null,0.00,-10000.00,4102001,null,null),(51,22,null,null,0.00,-100000.00,5102001,null,null),(52,22,null,null,0.00,50000.00,3101000,null,null),(53,23,null,null,-60000.00,0.00,4102000,null,null),(54,23,null,null,0.00,-10000.00,4102001,null,null),(55,23,null,null,0.00,-100000.00,5102001,null,null),(56,23,null,null,0.00,50000.00,3101000,null,null),(57,24,null,null,-60000.00,0.00,4102000,null,null),(58,24,null,null,0.00,-10000.00,4102001,null,null),(59,24,null,null,0.00,-100000.00,5102001,null,null),(60,24,null,null,0.00,50000.00,3101000,null,null),(69,27,null,null,-60000.00,0.00,4102000,null,null),(70,27,null,null,0.00,-10000.00,4102001,null,null),(71,27,null,null,0.00,-100000.00,5102001,null,null),(72,27,null,null,0.00,50000.00,3101000,null,null),(73,28,null,null,-60000.00,0.00,4102000,null,null),(74,28,null,null,0.00,-10000.00,4102001,null,null),(75,28,null,null,0.00,-100000.00,5102001,null,null),(76,28,null,null,0.00,50000.00,3101000,null,null),(81,30,null,null,1000.00,0.00,4102000,null,null),(82,30,null,null,0.00,-200.00,5102001,null,null),(83,30,null,null,0.00,1200.00,3101000,null,null),(84,31,null,null,1000.00,0.00,4102000,null,null),(85,31,null,null,0.00,-200.00,5102001,null,null),(86,31,null,null,0.00,1200.00,3101000,null,null),(87,29,null,null,-60000.00,0.00,4102000,null,null),(88,29,null,null,0.00,-10000.00,4102001,null,null),(89,29,null,null,0.00,-100000.00,5102001,null,null),(90,29,null,null,0.00,50000.00,3101000,null,null),(91,32,null,null,1000.00,0.00,4102000,null,null),(92,32,null,null,0.00,-200.00,5102001,null,null),(93,32,null,null,0.00,1200.00,3101000,null,null),(94,33,null,null,5000.00,0.00,4102000,null,null),(95,33,null,null,0.00,5000.00,3101000,null,null),(96,34,null,null,1000.00,0.00,1102000,null,0),(97,34,null,null,0.00,1000.00,1101000,null,0),(98,35,null,null,1000.00,0.00,4102000,null,null),(99,35,null,null,0.00,-200.00,5102001,null,null),(100,35,null,null,0.00,1200.00,3101000,null,null),(107,36,null,null,100000.00,0.00,4101000,null,null),(108,36,null,null,60000.00,0.00,4102000,null,null),(109,36,null,null,0.00,10000.00,4102001,null,null),(110,36,null,null,0.00,100000.00,5102001,null,null),(111,36,null,null,0.00,50000.00,3101000,null,null),(112,37,null,null,0.00,0.00,3101000,null,null),(113,null,null,null,100000.00,0.00,1102001,1,null),(114,null,null,null,0.00,50000.00,2101000,1,null),(115,null,null,null,0.00,100000.00,4101000,1,null),(116,null,null,null,0.00,40000.00,4102000,1,null),(117,null,null,null,0.00,10000.00,4102001,1,null),(118,null,null,null,100000.00,0.00,5102001,1,null),(119,38,null,null,100000.00,0.00,4101000,null,null),(120,38,null,null,40000.00,0.00,4102000,null,null),(121,38,null,null,0.00,-10000.00,4102001,null,null),(122,38,null,null,0.00,100000.00,5102001,null,null),(123,38,null,null,0.00,50000.00,3101000,null,null),(124,39,null,null,0.00,0.00,3101000,null,null),(125,40,null,null,0.00,0.00,3101000,null,null),(126,41,null,null,0.00,0.00,3101000,null,null),(127,49,null,null,10000.00,0.00,1103000,null,null),(128,49,null,null,0.00,10000.00,2101000,null,null),(129,50,null,null,1000.00,0.00,1102000,null,0),(130,50,null,null,0.00,1000.00,2101000,null,0),(131,51,null,null,0.00,0.00,3101000,null,null),(132,52,null,null,0.00,0.00,3101000,null,null),(133,53,null,null,5000.00,0.00,4102000,null,null),(134,53,null,null,0.00,5000.00,3101000,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `postinghistory`;:||:Separator:||:


CREATE TABLE `postinghistory` (
  `idPostingHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idPosting` int(11) DEFAULT NULL,
  `idInvoice` int(11) DEFAULT NULL,
  `explanation` text,
  `debit` decimal(18,2) DEFAULT '0.00',
  `credit` decimal(18,2) DEFAULT '0.00',
  `idCoa` int(11) DEFAULT NULL,
  `idAccBegBal` int(11) DEFAULT NULL,
  `idCostCenter` int(11) DEFAULT NULL,
  `idInvoiceHistory` int(11) DEFAULT NULL,
  `idBankRecon` int(11) DEFAULT NULL,
  `idBankReconHistory` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPostingHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `postinghistory` WRITE;:||:Separator:||:
 INSERT INTO `postinghistory` VALUES(1,null,null,null,100000.00,0.00,1102001,1,null,null,null,null),(2,null,null,null,0.00,50000.00,2101000,1,null,null,null,null),(3,null,null,null,60000.00,0.00,4102000,1,null,null,null,null),(4,null,null,null,0.00,10000.00,4102001,1,null,null,null,null),(5,null,null,null,0.00,100000.00,5102001,1,null,null,null,null),(6,null,3,null,1000.00,0.00,1102000,null,0,3,null,null),(7,null,3,null,500.00,0.00,1101000,null,0,3,null,null),(8,null,3,null,0.00,750.00,2101000,null,0,3,null,null),(9,null,3,null,0.00,250.00,5102001,null,0,3,null,null),(10,null,3,null,0.00,500.00,4102000,null,0,3,null,null),(11,11,4,null,1000.00,0.00,1102000,null,0,null,null,null),(12,12,4,null,500.00,0.00,1101000,null,0,null,null,null),(13,13,4,null,500.00,0.00,1102001,null,0,null,null,null),(14,14,4,null,0.00,800.00,2101000,null,0,null,null,null),(15,15,4,null,0.00,1000.00,4102000,null,0,null,null,null),(16,16,4,null,0.00,200.00,5102001,null,0,null,null,null),(17,17,5,null,5000.00,0.00,1102001,null,0,null,null,null),(18,18,5,null,0.00,5000.00,4102000,null,0,null,null,null),(19,19,4,null,1000.00,0.00,1102000,null,0,null,null,null),(20,20,4,null,500.00,0.00,1101000,null,0,null,null,null),(21,21,4,null,500.00,0.00,1102001,null,0,null,null,null),(22,22,4,null,0.00,800.00,2101000,null,0,null,null,null),(23,23,4,null,0.00,1000.00,4102000,null,0,null,null,null),(24,24,4,null,0.00,200.00,5102001,null,0,null,null,null),(25,25,4,null,1000.00,0.00,1102000,null,0,null,null,null),(26,26,4,null,500.00,0.00,1101000,null,0,null,null,null),(27,27,4,null,500.00,0.00,1102001,null,0,null,null,null),(28,28,4,null,0.00,800.00,2101000,null,0,null,null,null),(29,29,4,null,0.00,1000.00,4102000,null,0,null,null,null),(30,30,4,null,0.00,200.00,5102001,null,0,null,null,null),(31,null,6,null,0.00,0.00,3101000,null,null,null,null,null),(32,null,13,null,0.00,71500.00,2101000,null,0,null,null,null),(33,null,13,null,71500.00,0.00,1103000,null,0,null,null,null),(34,null,18,null,0.00,0.00,3101000,null,null,null,null,null),(35,null,19,null,-60000.00,0.00,4102000,null,null,null,null,null),(36,null,19,null,0.00,-10000.00,4102001,null,null,null,null,null),(37,null,19,null,0.00,-100000.00,5102001,null,null,null,null,null),(38,null,19,null,0.00,50000.00,3101000,null,null,null,null,null),(39,null,20,null,-60000.00,0.00,4102000,null,null,null,null,null),(40,null,20,null,0.00,-10000.00,4102001,null,null,null,null,null),(41,null,20,null,0.00,-100000.00,5102001,null,null,null,null,null),(42,null,20,null,0.00,50000.00,3101000,null,null,null,null,null),(43,null,21,null,-60000.00,0.00,4102000,null,null,null,null,null),(44,null,21,null,0.00,-10000.00,4102001,null,null,null,null,null),(45,null,21,null,0.00,-100000.00,5102001,null,null,null,null,null),(46,null,21,null,0.00,50000.00,3101000,null,null,null,null,null),(47,null,22,null,-60000.00,0.00,4102000,null,null,null,null,null),(48,null,22,null,0.00,-10000.00,4102001,null,null,null,null,null),(49,null,22,null,0.00,-100000.00,5102001,null,null,null,null,null),(50,null,22,null,0.00,50000.00,3101000,null,null,null,null,null),(51,null,23,null,-60000.00,0.00,4102000,null,null,null,null,null),(52,null,23,null,0.00,-10000.00,4102001,null,null,null,null,null),(53,null,23,null,0.00,-100000.00,5102001,null,null,null,null,null),(54,null,23,null,0.00,50000.00,3101000,null,null,null,null,null),(55,null,24,null,-60000.00,0.00,4102000,null,null,null,null,null),(56,null,24,null,0.00,-10000.00,4102001,null,null,null,null,null),(57,null,24,null,0.00,-100000.00,5102001,null,null,null,null,null),(58,null,24,null,0.00,50000.00,3101000,null,null,null,null,null),(67,null,27,null,-60000.00,0.00,4102000,null,null,null,null,null),(68,null,27,null,0.00,-10000.00,4102001,null,null,null,null,null),(69,null,27,null,0.00,-100000.00,5102001,null,null,null,null,null),(70,null,27,null,0.00,50000.00,3101000,null,null,null,null,null),(71,null,28,null,-60000.00,0.00,4102000,null,null,null,null,null),(72,null,28,null,0.00,-10000.00,4102001,null,null,null,null,null),(73,null,28,null,0.00,-100000.00,5102001,null,null,null,null,null),(74,null,28,null,0.00,50000.00,3101000,null,null,null,null,null),(75,null,29,null,-60000.00,0.00,4102000,null,null,null,null,null),(76,null,29,null,0.00,-10000.00,4102001,null,null,null,null,null),(77,null,29,null,0.00,-100000.00,5102001,null,null,null,null,null),(78,null,29,null,0.00,50000.00,3101000,null,null,null,null,null),(79,null,30,null,1000.00,0.00,4102000,null,null,null,null,null),(80,null,30,null,0.00,-200.00,5102001,null,null,null,null,null),(81,null,30,null,0.00,1200.00,3101000,null,null,null,null,null),(82,null,31,null,1000.00,0.00,4102000,null,null,null,null,null),(83,null,31,null,0.00,-200.00,5102001,null,null,null,null,null),(84,null,31,null,0.00,1200.00,3101000,null,null,null,null,null),(85,null,29,null,-60000.00,0.00,4102000,null,null,null,null,null),(86,null,29,null,0.00,-10000.00,4102001,null,null,null,null,null),(87,null,29,null,0.00,-100000.00,5102001,null,null,null,null,null),(88,null,29,null,0.00,50000.00,3101000,null,null,null,null,null),(89,null,32,null,1000.00,0.00,4102000,null,null,null,null,null),(90,null,32,null,0.00,-200.00,5102001,null,null,null,null,null),(91,null,32,null,0.00,1200.00,3101000,null,null,null,null,null),(92,null,33,null,5000.00,0.00,4102000,null,null,null,null,null),(93,null,33,null,0.00,5000.00,3101000,null,null,null,null,null),(94,96,34,null,1000.00,0.00,1102000,null,0,null,null,null),(95,97,34,null,0.00,1000.00,1101000,null,0,null,null,null),(96,null,35,null,1000.00,0.00,4102000,null,null,null,null,null),(97,null,35,null,0.00,-200.00,5102001,null,null,null,null,null),(98,null,35,null,0.00,1200.00,3101000,null,null,null,null,null),(99,null,null,null,100000.00,0.00,1102001,1,null,null,null,null),(100,null,null,null,0.00,50000.00,2101000,1,null,null,null,null),(101,null,null,null,0.00,100000.00,4101000,1,null,null,null,null),(102,null,null,null,0.00,60000.00,4102000,1,null,null,null,null),(103,null,null,null,10000.00,0.00,4102001,1,null,null,null,null),(104,null,null,null,100000.00,0.00,5102001,1,null,null,null,null),(105,null,36,null,100000.00,0.00,4101000,null,null,null,null,null),(106,null,36,null,60000.00,0.00,4102000,null,null,null,null,null),(107,null,36,null,0.00,10000.00,4102001,null,null,null,null,null),(108,null,36,null,0.00,100000.00,5102001,null,null,null,null,null),(109,null,36,null,0.00,50000.00,3101000,null,null,null,null,null),(110,null,37,null,0.00,0.00,3101000,null,null,null,null,null),(111,null,null,null,100000.00,0.00,1102001,1,null,null,null,null),(112,null,null,null,0.00,50000.00,2101000,1,null,null,null,null),(113,null,null,null,0.00,100000.00,4101000,1,null,null,null,null),(114,null,null,null,0.00,40000.00,4102000,1,null,null,null,null),(115,null,null,null,0.00,10000.00,4102001,1,null,null,null,null),(116,null,null,null,100000.00,0.00,5102001,1,null,null,null,null),(117,null,38,null,100000.00,0.00,4101000,null,null,null,null,null),(118,null,38,null,40000.00,0.00,4102000,null,null,null,null,null),(119,null,38,null,0.00,-10000.00,4102001,null,null,null,null,null),(120,null,38,null,0.00,100000.00,5102001,null,null,null,null,null),(121,null,38,null,0.00,50000.00,3101000,null,null,null,null,null),(122,null,39,null,0.00,0.00,3101000,null,null,null,null,null),(123,null,40,null,0.00,0.00,3101000,null,null,null,null,null),(124,null,41,null,0.00,0.00,3101000,null,null,null,null,null),(125,null,50,null,1000.00,0.00,1102000,null,0,46,null,null),(126,null,50,null,0.00,1000.00,2101000,null,0,46,null,null),(127,null,51,null,0.00,0.00,3101000,null,null,null,null,null),(128,null,52,null,0.00,0.00,3101000,null,null,null,null,null),(129,null,53,null,5000.00,0.00,4102000,null,null,null,null,null),(130,null,53,null,0.00,5000.00,3101000,null,null,null,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `receipts`;:||:Separator:||:


CREATE TABLE `receipts` (
  `idReceipts` int(11) NOT NULL AUTO_INCREMENT,
  `idInvoice` int(11) DEFAULT NULL,
  `idCustomer` int(11) DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT '0.00',
  `fIDModule` int(11) DEFAULT NULL,
  `fident` int(11) DEFAULT NULL,
  `idBankRecon` int(11) DEFAULT NULL,
  PRIMARY KEY (`idReceipts`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `receipts` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `receiptshistory`;:||:Separator:||:


CREATE TABLE `receiptshistory` (
  `idReceiptsHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idReceipts` int(11) DEFAULT NULL,
  `idCustomer` int(11) DEFAULT NULL,
  `remarks` text,
  `amount` decimal(18,2) DEFAULT '0.00',
  `idInvoice` int(11) DEFAULT NULL,
  `fIDModule` int(11) DEFAULT NULL,
  `fident` int(11) DEFAULT NULL,
  `idInvoiceHistory` int(11) DEFAULT NULL,
  PRIMARY KEY (`idReceiptsHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `receiptshistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `receiving`;:||:Separator:||:


CREATE TABLE `receiving` (
  `idReceiving` int(11) NOT NULL AUTO_INCREMENT,
  `idItem` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `qtyLeft` int(11) DEFAULT NULL,
  `idInvoice` int(11) DEFAULT NULL,
  `cost` decimal(18,2) DEFAULT '0.00',
  `price` decimal(18,2) DEFAULT '0.00',
  `expiryDate` date DEFAULT NULL,
  `fIDModule` int(11) DEFAULT NULL,
  `fident` int(11) DEFAULT NULL,
  PRIMARY KEY (`idReceiving`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `receiving` WRITE;:||:Separator:||:
 INSERT INTO `receiving` VALUES(1,2,150,135,7,250.00,0.00,'2024-01-01',25,null),(2,1,300,49,8,50.00,0.00,'0000-00-00',25,null),(4,4,50,50,13,1430.00,0.00,'0000-00-00',25,12),(5,2,150,150,17,250.00,0.00,'0000-00-00',25,null),(6,7,150,150,17,1467.89,0.00,'0000-00-00',25,null),(7,9,150,150,17,1467.00,0.00,'0000-00-00',25,null),(8,6,150,150,17,318.00,0.00,'0000-00-00',25,null),(9,10,150,150,17,1467.00,0.00,'0000-00-00',25,null),(10,0,10,null,48,0.00,0.00,null,null,null),(11,10,10,10,49,1467.00,0.00,null,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `receivinghistory`;:||:Separator:||:


CREATE TABLE `receivinghistory` (
  `idReceivingHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idReceiving` int(11) DEFAULT NULL,
  `idItem` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `qtyLeft` int(11) DEFAULT NULL,
  `idInvoice` int(11) DEFAULT NULL,
  `price` decimal(18,2) DEFAULT '0.00',
  `cost` decimal(18,2) DEFAULT '0.00',
  `expiryDate` date DEFAULT NULL,
  `fIDModule` int(11) DEFAULT NULL,
  `fident` int(11) DEFAULT NULL,
  PRIMARY KEY (`idReceivingHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `receivinghistory` WRITE;:||:Separator:||:
 INSERT INTO `receivinghistory` VALUES(1,null,4,50,50,13,0.00,1430.00,'0000-00-00',25,12);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `reference`;:||:Separator:||:


CREATE TABLE `reference` (
  `idReference` int(11) NOT NULL AUTO_INCREMENT,
  `code` char(5) DEFAULT NULL,
  `name` char(50) DEFAULT NULL,
  `idModule` int(11) DEFAULT NULL,
  `isDefault` int(1) DEFAULT '0' COMMENT '0 - False\n1 - True',
  `archived` int(1) DEFAULT '0',
  PRIMARY KEY (`idReference`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `reference` WRITE;:||:Separator:||:
 INSERT INTO `reference` VALUES(1,'CE','Closing Entry',35,1,0),(2,'JV','Journal Voucher',48,0,0),(3,'VR','Vouchers Receivable',58,0,0),(4,'RR','Receiving',25,0,0),(5,'SI','Sales Invoices',18,0,0),(6,'PO','Purchase Order',2,0,0),(7,'IC','Inventory Conversion',22,0,0),(8,'ST','Stock Transfer',43,0,0),(9,'I-AD','Inventory Adjustment',23,0,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `referenceaffiliate`;:||:Separator:||:


CREATE TABLE `referenceaffiliate` (
  `idRefAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `idReference` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  PRIMARY KEY (`idRefAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `referenceaffiliate` WRITE;:||:Separator:||:
 INSERT INTO `referenceaffiliate` VALUES(1,2,2),(2,2,4),(3,3,2),(4,3,4),(5,4,2),(6,4,4),(7,5,2),(8,5,4),(9,6,2),(10,7,2),(11,8,2),(12,9,2);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `referenceaffiliatehistory`;:||:Separator:||:


CREATE TABLE `referenceaffiliatehistory` (
  `idRefAffiliateHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idRefAffiliate` int(11) DEFAULT NULL,
  `idReference` int(11) DEFAULT NULL,
  `idReferenceHistory` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  PRIMARY KEY (`idRefAffiliateHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `referenceaffiliatehistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `referencehistory`;:||:Separator:||:


CREATE TABLE `referencehistory` (
  `idReferenceHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idReference` int(11) DEFAULT NULL,
  `code` char(5) DEFAULT NULL,
  `name` char(50) DEFAULT NULL,
  `idModule` int(11) DEFAULT NULL,
  `isDefault` int(1) DEFAULT '0',
  PRIMARY KEY (`idReferenceHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `referencehistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `referenceseries`;:||:Separator:||:


CREATE TABLE `referenceseries` (
  `idReferenceSeries` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `idCostCenter` int(11) DEFAULT NULL,
  `idModule` int(11) DEFAULT NULL,
  `idReference` int(11) DEFAULT NULL,
  `seriesFrom` int(11) DEFAULT NULL,
  `seriesTo` int(11) DEFAULT NULL,
  `archived` int(1) DEFAULT '0',
  PRIMARY KEY (`idReferenceSeries`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `referenceseries` WRITE;:||:Separator:||:
 INSERT INTO `referenceseries` VALUES(1,'2018-03-05',2,null,35,1,1,999999,0),(2,'2001-07-05',4,null,35,1,1,999999,0),(3,'2020-03-05',2,null,48,2,1,1000,0),(4,'2020-03-05',2,null,58,3,1,1000,0),(5,'2020-04-23',2,null,25,4,1,1000,0),(6,'2020-04-23',2,null,18,5,1,1000,0),(7,'2020-04-24',2,null,2,6,1,1000,0),(8,'2020-04-25',2,null,22,7,1,10,0),(9,'2020-04-25',2,null,43,8,1,10,0),(10,'2020-04-25',2,null,23,9,1,10,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `referenceserieshistory`;:||:Separator:||:


CREATE TABLE `referenceserieshistory` (
  `idReferenceSeriesHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idReferenceSeries` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `idCostCenter` int(11) DEFAULT NULL,
  `idModule` int(11) DEFAULT NULL,
  `idReference` int(11) DEFAULT NULL,
  `idReferenceHistory` int(11) DEFAULT NULL,
  `seriesFrom` int(11) DEFAULT NULL,
  `seriesTo` int(11) DEFAULT NULL,
  PRIMARY KEY (`idReferenceSeriesHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `referenceserieshistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `releasing`;:||:Separator:||:


CREATE TABLE `releasing` (
  `idReleasing` int(11) NOT NULL AUTO_INCREMENT,
  `idItem` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `qtyLeft` int(11) DEFAULT NULL,
  `cost` decimal(18,2) DEFAULT NULL,
  `price` decimal(18,2) DEFAULT NULL,
  `idInvoice` int(1) DEFAULT NULL,
  `fIDModule` int(11) DEFAULT NULL,
  `fIdent` int(11) DEFAULT NULL,
  `lotNumber` varchar(255) DEFAULT NULL,
  `expiration` date DEFAULT NULL,
  PRIMARY KEY (`idReleasing`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `releasing` WRITE;:||:Separator:||:
 INSERT INTO `releasing` VALUES(1,2,15,15,250.00,250.00,9,null,1,null,'0000-00-00'),(2,1,150,150,50.00,50.00,10,null,2,null,'0000-00-00'),(16,1,100,100,50.00,50.00,43,null,2,null,'0000-00-00'),(17,1,1,1,50.00,50.00,46,null,2,null,'0000-00-00'),(18,0,10,null,0.00,null,47,null,0,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `releasinghistory`;:||:Separator:||:


CREATE TABLE `releasinghistory` (
  `idReleasingHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idReleasing` int(11) DEFAULT NULL,
  `idItem` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `qtyLeft` int(11) DEFAULT NULL,
  `cost` decimal(18,2) DEFAULT '0.00',
  `price` decimal(18,2) DEFAULT '0.00',
  `idInvoice` int(11) DEFAULT NULL,
  `idInvoiceHistory` int(11) DEFAULT NULL,
  `fIDModule` int(11) DEFAULT NULL,
  `fIdent` int(11) DEFAULT NULL,
  PRIMARY KEY (`idReleasingHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `releasinghistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `so`;:||:Separator:||:


CREATE TABLE `so` (
  `idSo` int(11) NOT NULL AUTO_INCREMENT,
  `idItem` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `qtyLeft` int(11) DEFAULT NULL,
  `cost` decimal(18,2) DEFAULT '0.00',
  `idInvoice` int(11) DEFAULT NULL,
  `fIDModule` int(11) DEFAULT NULL,
  `fIdent` int(11) DEFAULT NULL,
  PRIMARY KEY (`idSo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `so` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `sohistory`;:||:Separator:||:


CREATE TABLE `sohistory` (
  `idSoHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idSo` int(11) DEFAULT NULL,
  `idItem` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `qtyLeft` int(11) DEFAULT NULL,
  `cost` decimal(18,2) DEFAULT '0.00',
  `idInvoice` int(11) DEFAULT NULL,
  `idInvoiceHistory` int(11) DEFAULT NULL,
  `fIDModule` int(11) DEFAULT NULL,
  `fIdent` int(11) DEFAULT NULL,
  PRIMARY KEY (`idSoHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `sohistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `stocktransfer`;:||:Separator:||:


CREATE TABLE `stocktransfer` (
  `idstockTransfer` int(11) NOT NULL AUTO_INCREMENT,
  `idInvoice` int(11) DEFAULT NULL,
  `idItem` int(11) DEFAULT NULL,
  `qtyTransferred` int(11) DEFAULT NULL,
  `qtyReceived` int(11) DEFAULT NULL,
  PRIMARY KEY (`idstockTransfer`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `stocktransfer` WRITE;:||:Separator:||:
 INSERT INTO `stocktransfer` VALUES(1,47,0,10,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `supplier`;:||:Separator:||:


CREATE TABLE `supplier` (
  `idSupplier` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `email` text,
  `contactNumber` text,
  `address` text,
  `tin` text,
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
  `archived` int(1) DEFAULT '0',
  `sk` text,
  PRIMARY KEY (`idSupplier`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `supplier` WRITE;:||:Separator:||:
 INSERT INTO `supplier` VALUES(1,'2b41b9841ab02ee227b955db8f04b9c4a78a5d0e8fa3e3cc1e13248c827b840ac54619bc0e1a3ec5b93dfd27eb8feaab306b85eb88e4305372c7c2f8d3c19f85MEfK80qe8yi9uZKxnKlEZXdIAgzCTBOXXvmFg/fWOlE=','64a02dfb2517539d5008336c5b30dc022e8afde5794b0ad65701359cadfc6852e72b546a73c9d0533035a1c4f61b0d3b8745641519177cc059f78926266c62641KlW0vbcjayLXm2DbcicPodIxQa1B5xHxTDt+fPYXPwB79KnWeU/xHh5FgNDPAgs','e91e60c3929a59f4e0ce038d13ff9d5edfd4d0a00f4a4b90b0c3094a4fe758e9f594c03ef5883cdc1eba2e89e8087741dc8f646b31e2d7b741898daec7030286Yqk9I5zmmz8Ie6vFSSUdGN32xMezf9sP5HwBHBcO8to=','ce00e0bf180e2943929bc80706f90dba73e4aff4fc077159ef3aff847a5374028982681bb3df704622f3bf98f4a09ca9fdbab8aac3816d939690b51f444210bcZDa1zjc4mIGO5Oh/zMYSgQj9oepl+vcDTYeQzula5t4=','d19bab839a460c58aef5a96207cd71d8d6bb16ac3bee1f70777a24385e29b680050caf655fceb8d2ed0ecee55d67ec4b693fa8885e1eeecc6dd7f8ee95544eb9T9Q/EOczGjh/LjZd+xFnRW2xYsugWCijT03GknNh+jM=',1,null,0,0.00,0,null,0.00,0.00,1,12.00,null,null,0,230920080047),(2,'8cd648a72cf621848722d77c482228017870755877729fdf83717308d1b2ac92b1a181c1e13fc193e3c8cab7ba63c3be824db4911dcd8977d353bd2b3a19b180x6706Y/espZ7kihVSTHwWg48cCntxmevU6BJB7qKWq8=','e7a6b304f8c9de002e69979ea723a9f5b8304bdcfd52d6d4ed5ab4f529a8b3eba3af39df4fc6de3d21124690c8a8dd52fa0ead8b1c47b68e82d387b6ae81ed3fnfeA9bDc5OLGhRXMx1vedkcuUEEvrsA3KOLSMxEFPELpaOWckYssjpKR3dLS6608','fc98fae06115132fdd5514a19a824bd21208e35ef75e615d6339356efd4bc454cd6628fdf03fbc80b4788a5e34cce0913859f1f2f2b70946848c50f183b27004hfkIRPZ2AdEH+lgtNdBxqyQp/N7fTjGicqcbU7CMtdI=','4f5c4ea97c5a21732846447ff8d22ef02c60ee05ee1ed5f4cef1acf8ba6c9c41df7c9ce03cb1894c8364e9c798d9c79de943160b9c7eb71ba1f4117f354d17fbkZLsUojXI53JxZSF+eaSQec5m+XqFJOdGMfmhTK2ng0=','9d13846ba83b5f46df9ab110d8063d9c6870c9b64f89b4373009090f52f0b505e991a5aabe010b6907c705a6a3fbb27b18c044bb14cec90a9ca0ac921a246810YykDNVT9EXqUrIT6a46UENxqqq58jzJZ+IoJ1XvJIf8=',1,null,0,0.00,0,null,0.00,0.00,0,0.00,null,null,0,'23/15000520'),(3,'8f72a77dffcc0b3e285a3acc760268316e3bfd14ec81e359949a30826d0450dd2c92ba3b4dd841515b9a742a6cb955b07013a5fba7b7af63abc31a896be4eac2q4UVyl0uC5Id46xPYJ0Og+bsTjpv/CTYc/EaK7l75E8=','2fb61075252b87520d11eac08b17745da7de7da5ca9069f9de041840667018e0f235a1c33d9d1f93d39444305648fa66a95092ca32614722668e77922d1ce28aE4nstnjRqLAwjuR+nPCufgk/9OBOwsGdtQgD2kfzyFQ=','4404a26a07fdd90f9bf219f18bc5f4f19f3f989d245b8d5cd167eca24bec14bce1c07540666da988b17228d63d89966b9f60399b2e0d38e1190f62cac2d1420dP/0Kq7OVRUBWXqHrgSdNRvMw7Cvcagm4JM3OsND4nbE=','ec74db2562b7a80821e38966dab83dc70e85d2f6100f088d5569ab2a4deb03305e0fc4c94d7cf57da91be6f1bfdfb7602371a96a3bb7bc2b53a5f0887b4f473eJXeGU1bkvgblb9AcC26dXm2wWR7Jyp6eU9BdGc7Woig=','f28a00f54737524e674da613c4c87e786ea306608a8231d93ee46d846644a29ca77df99034d55f6b1cf067579228a3db471c5919ca3cc85287cfb46e0d7725d6KuMAAyqWfkJZB/QHCkNim9UVOMiFRn81wUL8vvmWg9A=',1,null,0,0.00,1,0,12.00,0.00,0,0.00,2101000,null,0,081512030958),(4,'ad09f5b27501a6c204d2875727609848106a744aea6dc14646ca03ca780fd9c5f1ed9d86cc475fd2cb94228993f2ff6baa44229b03b7e433cb22195a8a1ad5c131mrDEHb8YEPDCv9PEVFo8tQ5GVdysUgRI2+boPgkF0=','7a80b8ee9b59ffd9225fb83d369b5051785f993aa2078d1b34cc84501b045f711ae1a916c2d3159b9f73961b131cdc108d85f1ffb3c9ec214eff6d1999b3c8fclF0G3S+SPP+hOwVlln9AJ082RLrbz+cWMZSTkAFPosT5M8iXPJvEcRUC1aKi6IS0','51d14ff20e44c0329b00d3a58221a5b725a3bf713b7444be75ee98d161131cb0b4fa5b15f098fe1cb3e490d52e4e1cc4bd1a79bb121c5d10dff76ff04735a29bwce5U3EEMesv0yCjPwlXS/F8DMIArhdur/vy10L189U=','d1f036a084a866f8c6e8ae11230510ffe6750fbc66d949cecb9cd2ba6e8ac4b393553ef5be0d2ff2bba6a6bcf7b19a8e11599e140502eebfa7d0e21c9e82cd56aNLmRL9wq6H9o/oSgKtIaohndhBDdz6fPBXL65QoBJ1PJWELSYE6c3jmLYsWQvO9','70f95786773ce657741839a87c389e821ee70edb4b165f53cfde0390f3c71f21395e7c9b7a3e4794c2a394c92f01cf5703d72289245dbb2431216eec4ce07743d3Fs4oDFWlUD/IQmcvONzZpridP8EKgj/y3PAcdwuy8=',1,null,0,0.00,1,0,12.00,5.00,0,0.00,2101000,null,0,030920090039);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `supplieraffiliate`;:||:Separator:||:


CREATE TABLE `supplieraffiliate` (
  `idSupplierAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `idSupplier` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `selected` int(1) DEFAULT NULL,
  PRIMARY KEY (`idSupplierAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `supplieraffiliate` WRITE;:||:Separator:||:
 INSERT INTO `supplieraffiliate` VALUES(1,1,2,1),(3,2,2,1),(4,3,2,1),(5,4,2,1);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `supplieraffiliatehistory`;:||:Separator:||:


CREATE TABLE `supplieraffiliatehistory` (
  `idSupplierAffiliateHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idSupplierAffiliate` int(11) DEFAULT NULL,
  `idSupplier` int(11) DEFAULT NULL,
  `idSupplierHistory` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  `selected` int(11) DEFAULT NULL,
  PRIMARY KEY (`idSupplierAffiliateHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `supplieraffiliatehistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `supplierhistory`;:||:Separator:||:


CREATE TABLE `supplierhistory` (
  `idSupplierHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idSupplier` int(11) DEFAULT NULL,
  `name` char(50) DEFAULT NULL,
  `email` char(50) DEFAULT NULL,
  `contactNumber` char(20) DEFAULT NULL,
  `address` text,
  `tin` char(11) DEFAULT NULL,
  `paymentMethod` int(1) DEFAULT NULL,
  `terms` int(1) DEFAULT NULL,
  `withCreditLimit` int(1) DEFAULT '0',
  `creditLimit` decimal(18,2) DEFAULT '0.00',
  `withVat` int(1) DEFAULT '0',
  `vatType` int(1) DEFAULT NULL,
  `varPercent` decimal(18,2) DEFAULT '0.00',
  `discount` decimal(18,2) DEFAULT '0.00',
  `withholdingTax` int(1) DEFAULT '0',
  `withholdingTaxRate` decimal(18,2) DEFAULT '0.00',
  `expenseGlAcc` int(11) DEFAULT NULL,
  `dicountGlAcc` int(11) DEFAULT NULL,
  PRIMARY KEY (`idSupplierHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `supplierhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `supplieritems`;:||:Separator:||:


CREATE TABLE `supplieritems` (
  `idSupplierItems` int(11) NOT NULL AUTO_INCREMENT,
  `idSupplier` int(11) DEFAULT NULL,
  `idItem` int(11) DEFAULT NULL,
  PRIMARY KEY (`idSupplierItems`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `supplieritems` WRITE;:||:Separator:||:
 INSERT INTO `supplieritems` VALUES(1,2,1);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `supplieritemshistory`;:||:Separator:||:


CREATE TABLE `supplieritemshistory` (
  `idSupplierItemsHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idSupplierItems` int(11) DEFAULT NULL,
  `idSupplier` int(11) DEFAULT NULL,
  `idSupplierHistory` int(11) DEFAULT NULL,
  `idItem` int(11) DEFAULT NULL,
  PRIMARY KEY (`idSupplierItemsHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `supplieritemshistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `unadjusted`;:||:Separator:||:


CREATE TABLE `unadjusted` (
  `idUnadjusted` int(11) NOT NULL AUTO_INCREMENT,
  `idBankRecon` int(11) DEFAULT NULL,
  `idPostdated` int(11) DEFAULT NULL,
  `unadjustedTag` int(1) DEFAULT NULL COMMENT '1 - Add\n2 - Less',
  PRIMARY KEY (`idUnadjusted`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `unadjusted` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `unadjustedhistory`;:||:Separator:||:


CREATE TABLE `unadjustedhistory` (
  `idUnadjustedHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idUnadjusted` int(11) DEFAULT NULL,
  `idBankRecon` int(11) DEFAULT NULL,
  `idPostdated` int(11) DEFAULT NULL,
  `unadjustedTag` int(1) DEFAULT NULL COMMENT '1 - Add\n2 - Less',
  PRIMARY KEY (`idUnadjustedHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `unadjustedhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `unchecks`;:||:Separator:||:


CREATE TABLE `unchecks` (
  `idUnchecks` int(11) NOT NULL AUTO_INCREMENT,
  `idBankRecon` int(11) DEFAULT NULL,
  `idPostdated` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL COMMENT '1 - Outstanding\n2 - Cleared\n3 - Cancelled\n4 - Bounced',
  `uncheckTag` int(1) DEFAULT NULL COMMENT '1 - Add\n2 - Less',
  PRIMARY KEY (`idUnchecks`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `unchecks` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `uncheckshistory`;:||:Separator:||:


CREATE TABLE `uncheckshistory` (
  `idUnchecksHistory` int(11) NOT NULL AUTO_INCREMENT,
  `idBankRecon` int(11) DEFAULT NULL,
  `idBankReconHistory` int(11) DEFAULT NULL,
  `idPostDated` int(11) DEFAULT NULL,
  `idPostDatedHistory` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT '0',
  `uncheckTag` int(1) DEFAULT '0',
  PRIMARY KEY (`idUnchecksHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `uncheckshistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `unit`;:||:Separator:||:


CREATE TABLE `unit` (
  `idUnit` int(11) NOT NULL AUTO_INCREMENT,
  `unitCode` char(20) DEFAULT NULL,
  `unitName` char(20) DEFAULT NULL,
  `archived` int(1) DEFAULT '0',
  PRIMARY KEY (`idUnit`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `unit` WRITE;:||:Separator:||:
 INSERT INTO `unit` VALUES(1,'pcs','Pieces',0),(2,'ml','millilitre',0),(3,'l','liter',0),(4,'pck','pack',0),(5,'kl','kilograms',0),(6,'g','grams',0),(7,'t','ton',0),(8,'s','sack',0),(9,'r','roll',0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
