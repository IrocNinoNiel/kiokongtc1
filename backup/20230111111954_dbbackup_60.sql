-- DB BACK UP Created on: 01/11/2023 11:19:54

DROP TABLE IF EXISTS `accountbegbal`;:||:Separator:||:


CREATE TABLE `accountbegbal` (
  `idAccBegBal` int NOT NULL AUTO_INCREMENT,
  `idAffiliate` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `idLocation` int DEFAULT NULL,
  `dateModified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idAccBegBal`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `accountbegbal` WRITE;:||:Separator:||:
 INSERT INTO `accountbegbal` VALUES(1,12,'2021-08-18',null,'2021-09-13 15:39:19'),(2,2,'2018-03-05',null,'2021-12-21 11:37:13');:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `accountbegbalhistory`;:||:Separator:||:


CREATE TABLE `accountbegbalhistory` (
  `idAccBegBalHistory` int NOT NULL AUTO_INCREMENT,
  `idAccBegBal` int DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `idLocation` int DEFAULT NULL,
  PRIMARY KEY (`idAccBegBalHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `accountbegbalhistory` WRITE;:||:Separator:||:
 INSERT INTO `accountbegbalhistory` VALUES(1,1,12,'2021-08-18',null),(2,2,2,'2018-03-05',null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `activity`;:||:Separator:||:


CREATE TABLE `activity` (
  `idActivity` int NOT NULL AUTO_INCREMENT,
  `activityName` char(50) NOT NULL,
  PRIMARY KEY (`idActivity`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `activity` WRITE;:||:Separator:||:
 INSERT INTO `activity` VALUES(1,'Other General Requirements'),(2,'New Activity'),(3,'Sample Activity'),(4,'New Test');:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `adjusted`;:||:Separator:||:


CREATE TABLE `adjusted` (
  `idAdjusted` int NOT NULL AUTO_INCREMENT,
  `idBankRecon` int DEFAULT NULL,
  `description` char(250) DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT '0.00',
  `adjustedTag` int DEFAULT '0' COMMENT '1 - Add\n2 - Less',
  PRIMARY KEY (`idAdjusted`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `adjusted` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `adjustedhistory`;:||:Separator:||:


CREATE TABLE `adjustedhistory` (
  `idAdjustedHistory` int NOT NULL AUTO_INCREMENT,
  `idAdjusted` int DEFAULT NULL,
  `idBankRecon` int DEFAULT NULL,
  `idBankReconHistory` int DEFAULT NULL,
  `description` char(250) DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT '0.00',
  `adjustedTag` int DEFAULT '0' COMMENT '1 - Add\n2 - Less',
  PRIMARY KEY (`idAdjustedHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `adjustedhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `affiliate`;:||:Separator:||:


CREATE TABLE `affiliate` (
  `idAffiliate` int NOT NULL AUTO_INCREMENT,
  `affiliateName` text,
  `tagLine` text,
  `address` text,
  `contactPerson` char(50) DEFAULT NULL,
  `contactNumber` char(20) DEFAULT NULL,
  `email` char(50) DEFAULT NULL,
  `tin` char(20) DEFAULT NULL,
  `vatPercent` decimal(18,2) DEFAULT '0.00',
  `vatType` int DEFAULT '1' COMMENT '1 - Inclusive\n2 - Exclusive',
  `checkedBy` text,
  `reviewedBy` text,
  `approvedBy1` int DEFAULT NULL,
  `approvedBy2` int DEFAULT NULL,
  `accSchedule` text COMMENT '1 - Calendar\\\\n2 - Fiscal',
  `month` int DEFAULT '1' COMMENT '1 - January\n2 - February\n3 - March\n4 - April\n5 - May\n6 - June\n7 - July\n8 - August\n9 - September\n10 - October\n11 - November\n12 - December',
  `remarks` text,
  `refTag` int DEFAULT NULL,
  `logo` text,
  `status` int DEFAULT '0' COMMENT '1 - Active\n2 - Inactive',
  `mainTag` int DEFAULT '0',
  `location` int DEFAULT NULL,
  `dateStart` date DEFAULT NULL,
  `archived` int DEFAULT '0',
  `sk` text,
  PRIMARY KEY (`idAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `affiliate` WRITE;:||:Separator:||:
 INSERT INTO `affiliate` VALUES(1,'TEST AFFILIATE',null,null,null,null,null,null,0.00,1,null,null,null,null,null,1,null,null,null,1,0,null,null,1,null),(2,'3a7bfef1ccbf32eddc5f26989d8026858485be070cb3b028bfa2d29048e9b2210940ffa989ae4d3cac9b0b40352fab897752737936db162a18cd89b7c283b55cASgyF53m+JJntJwRhOsy2pN3zqr7R6fGzEq9vVmN+eag9pgijKH8/2/EEAEVvMaD','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,102,null,'1f8fa9ff72e9e5f82657ef366d80972056123a64967013085d4da4b6dcd95e358672da4817a5432fcbb9eea34513772ec59c58779656b3b231d365cb70aefe08cvn13mzUpNhebZ9xxY2nhgdGaIwVucFKQARbNVOzQk4=',4,null,0,null,1,1,null,'2018-03-05',0,192514200167),(3,'678fe40fbb859ea6212d764b48f86009d75840e926028ece5e1fc5ac7b4545797e7864d9b436dd3324fb127a0eb84628117752c05507e9b23c7f39d1055248b3T0anCGQuOYbL4MPsSvXk8OD4Avsa+9VsbDJjlVi+F0c=',null,null,null,null,null,3242534534,0.00,0,null,null,null,null,'483dc437aaa78718e54f4784c04d1f03baa1ee2e2cae2fd75e42d3309563529edd7e2ebe9342923a909977865dd3b24456d033db77d241c98639d2058a24c8feOE6Iq3Uk0o0gA8yOxfaUUsk57RN95NjVXcBHxaEcxHk=',4,null,0,null,1,0,null,'2020-04-22',1,200519200124),(4,'e130f3be78af5b49f263a5c334ccc1228e746216e6cf89a2fd61c4e194bbb49f1ee798227202fa5c5454b5b8ed365d06f4db167aa1898b72f34afd7b7cab03f6o/Yl93+P727oR9cFwQYWYgI82Q+/HHCNnILJ5yugSEk=','Saepe nemo dolores e','Impedit consectetur','Natus',8278569425,'zytuqitase@mailinator.net','Voluptatum ipsam cil',0.00,0,null,null,null,null,'63d1dd1f91cd5c4cf5392060f023cc4bad7223853f8109a942802b9570b3f9252423b8962b3fb8ce1d12c9e2a9d1ecd07f0cc1d7d8e940f29251a6a361b9a66fsfIHpVhmesJydmvwu1kvVUT1lKkuwZrkxUgvNumCVz7qmnjI4KYjyhP3YDfG0uQW',4,'Aspernatur nesciunt',0,null,1,0,null,'2001-07-05',0,30109181565),(5,'29924b152aea23221410f71bb36400e8ca44f607b7d82dfc8018945a53c612e97862353fe8ac15691df2930e650650556d0eb57c3f7d0ce4413c3cdc49effcb2RRwcxMpLUOuLttsW7hxkdVVL19nDEFvUslywLZbz04g=',null,null,null,null,null,123456789,0.00,0,null,null,null,null,'5c940de9d51f3f62b4a4c1a28c8e965e906e5f260e4fced4b18dcb1f73d0e39a7641af8a09b3fa2cb802fc85a00f71ab9fb39cce87266aae41749ef3aaaccbb1WRhzTtq5HjaG4IbMnUnaYRtCAnO/FXlcwTvD8EbqH0s=',6,null,0,null,1,0,null,'2020-06-05',0,200519200079),(6,'1aea11f3d1eb8af2c6b64af20ab3866bb938488e5675b02793f796c777d7275565a18b12add84dc389b930d22ce5a0deb2d234b4795174098b059d353afc9a2bAtdjL5/TPUIVVa/MUHlUDRP/PCDxLkbwh1fRInkUW3sv1eJzwVFyTihda67p6NxG',null,null,null,null,null,12345,0.00,0,null,null,null,null,'7a97a0e9a9c7d88f621964f97e3298196a27d60e57ddfe229037a39bc9ec6c9d300b1c0e6054bb7d9ba5577dda3ff04f496674048bc7d6b77eaf4e1002b02841LLQlz+A9MZ67LLmCS7dUY+hjF9kzEgA3WOwUVDdIZD4=',7,null,0,null,1,0,null,'2020-07-03',0,190113161277),(7,'4231e3b790f2427dd3c7cf24767d58747c24ab2815d8f1a4c97aa793d32eb631ea992e39893dfda3dcd18bf8b36e16c763f9e3ed127d4653697e7dfa703290b6sYyJFi1VFudqInr96k/5ruxSUkx1bWkzznSYDpke7f6zoNF3mS6oKhKgFEApEZlQ',null,null,null,null,null,123232,0.00,0,null,null,null,null,'39f990a7beba55ce2af2fd52bdbf2b685bd03e8e5f4b4d3afe4b967f8da12dab94894dbac6d9f997d66650e1b61c69829c66aab174bd3795b4f519bdce8437e8axEzuVTdrzegYtPAyeNqe8s2FOgx4tNRcJjOz0D2rnY=',7,null,0,null,1,0,null,'2020-07-03',1,190113161283),(8,'bef5b99e74e2b94e21709295de49ca29413a03d0d97ed0550aacd9578790eccb166d61217dd7fad36d1f1bfca4fc00c4161ab52ba0a0529b9db4e19392589cdci13ZIG0RtBryyNxj+ELt2CNVdT9GdiyJ5OcwPb9hNaCioumoef5JngNOBzTTcaMk','This is a tag line','this is an address','Contact my manager',9264148700,'affiliate@gmail.com',19299066,0.00,1,null,null,null,null,'717ba08d19bdacfce0405418fe4e57db51d6f4224cef77932f998488db56f981d2df8407da29f24b14b1ff7af09a547ef101756c279adecbf55b914a97267a06VfSQ4eJKW46RGEqpbF3fbxicX1xxvAOLDwLHmHWuyAI=',7,null,0,null,2,0,null,'2020-07-03',0,200809190040),(9,'48f439a95e2d985eb66e06e0df1a2afa23af1df34e9146368ff8a91defbe48912855ad7d1da0a958a0712cca2819dbea72d353ed30bf53bafde6d65c39d6ca362wM+73jFc3giDFhIRlLx+NrJvfvu3PMDSBMDviMX8RGD6c5pOBBZyO2LkTq0Ff1E',null,'Balangay 6, Poblacion, Quezon, Bukidnon','Michelle Emnace',9351338826,null,268024933003,0.00,0,'465381afa08340f458c4e096a4abe6a6a58fa6082ac31e74fee3f30e5231f609e0741b846c4446432bbfa2452b0aa7846903da99344e8cd5f1b11723177cfba1fZdHBbsGjA+D4OED7JrYh8GHr+4T79Ic9WyARWRvUpc=','2e9c9fff3bd8ca5b038f516e65ab7ab8322c7520a3d711da0ea1ea3f419ff354e515c77dda3e4aed2dba91533370f0bd9e4cd8efb0f6862b589dde8f18a25eads6T0duilWJyMcnFg2Ka+ejuUjgFXmwz8hk7JeEeP4WGeOC9VpkYCLYltU4onQ/Gk',null,null,'359d939ecc9f097504c63575f2ffd262454436dbf43bd167ca2bcc43ba354ad8417a727426dd1443543e44e1247955bfafd4f81b48eb30101d0b98055dee3ca2yJzBZtQeh2Wn6uekmzyBIeRKst24U0T/9I5kil3XFxw=',7,null,0,null,1,0,null,'2020-07-05',1,130114210562),(10,'a63f087451e885c27a5b23a153647bb9dccde93414bb7870b65ae34a217742514f456a470cab7db675c3c989289cca3308859db6da243e03e6bd85a37b6b7655qaiHqMG8QPO5hWUi9g1phh2dAaeiA7n+9ik6h+/3nzP9cF/qPI9295IXC/dDy2mOe40B9wHZ0DWjaTt3B5yUHRu5JZTQCue2CXUJkzhSAgw=',null,'Balangay 3, Poblacion, Quezon, Bukidnon','Russel Ca??ete',9750640023,null,466480615,0.00,0,'40b630eecd676fc576f55fa77829c7fa0ca94e0bea5ce62adcc34dcff548d814d1e0d57f93523f2a110b77368c54d06d8175dab1b6c8a0416fcbc24abebaafd34ArgKRY5KeJVx/1wQjnKBKNjS/vruhn4ZjEjXA+1i/Y=','a553709dfb719181f7a4d5177b72ed6482faece032c7d01a1fb7999e137c0247afd3c98759f70dee99ba1cce33933a7a729f4a93d108e8f5963b41e6789822acvnh6YzQRu1XMgDhIVHgryo8t+ag+X4pn2zSOKfFF+V0=',null,null,'2bb4ddf7c9b77fd02afdfa1a4fefaa53c1540c8a9ff8347b409c00468c4417782b16083ecfc79cad97647608ddc0526ebc4ff43784f780b7c3cbdd3024a21cb6IjA2TeF3EfhLXyIfEM2676avXjqbUC8WpgThrMgBpyM=',7,null,0,'Design 3.png',1,0,null,'2020-07-17',1,110915111559),(11,'e6b91b8ccad9efedf02c1ba8d8af9f6334fc85a3a277e334de0ae2424c9ce814688676129bea9e4cc91e1915c0488238963ade8552560e96d870c52959bf066fc4HCSPVJrpk4GqSeiHu2zoIoATXUrUtvq1TmtwYB5ZgaDoMLH9PXTSHRHIedBu5RpVDY+4At9kS9oh7DiL9SNA==',null,'Balangay 3, Poblacion, Quezon, Bukidnon','Katereen Berly N. Quimod',9176307772,'kfb.kiokongent@yahoo.com.ph',268024933,0.00,0,'f1075d7717a02c0c3452470b96d1e3c3a01437932f4774e4bf88cded43fd76d6977269a1830b14e5503565bb1579623a6228b0fa9af21692f26c3eab5d43d5a1oiFK/DQyvO1Tnw6ZrBfSkSKKazfC8WplWWm5UtDxo3XN5OWpZnlwlovnfOSwoNQn','b5a08fbc684bb160ce690a7aacfb01aa44fb731601a6eebfdc28110e0cde292b667a873a0e39ef28a70293dfc22cc01b11b2be171b455f87163c2a89d5a440accrGW2quS5mr5FkB4LUsP1n9orCEgIsirLm/GfSfyHe23uTVvx508dvOkFWqj40yI',null,null,'7182609bb90cc8e49f1d2f44a391f40d6b1da89b2a84e046febe649e5afa859d7be55d5b256aea813e53433c7fd76044762bd45b3c7468f761c71ad578181524gZHHSioXu4O8sLWud6PS0Vm1qJneb5Ho4tuBqZtu8zo=',7,null,0,'Trucking Logo.png',1,0,null,'2008-12-11',1,110915111574),(12,'575d5e2f2634384c35c9984cd0990962d0e7b9e9e796a82b7295af31f68ca12995364a5f42709b9c34622bdb93f39438bf8820e97fa5e3491f75b0c9f1d8f48fAh9INxexQkqRdPheJ74K4rFKXXGDORNzpAicPb9fAZc=','made for QA regression testing','Sample 123, Cagayan de Oro City','John Doe',9123456789,null,111222333,0.00,0,null,null,null,null,'e351a262de2f0327e51b953e1c5db71a6fb0bb1c41e7afaa0b66e21a1f6e9c258a530cd37c1fae760de9ac37a7decdcef3d01852f8a35a7382a19f390d1347e9rXr8NFJK2KEGxt/PYHEeMMp71Gs64j4IRP+5ajUQecM=',8,null,1,null,1,0,null,'2021-08-18',0,80003151386),(13,'b1971df69f5c98f6c214c97411ad246a4ed2caafe605f902619a342fbc3a9982b6c3e3bf737ac289737e69e55d6ba1e331188991325b237055991df23dd2e967CmHk/2oY4DmkA7nZVdqCkKI3cYOqXUrFJcAU6gP80UbdCdVQ/FK/r3ci7Hk51Reu',null,null,null,null,null,123456,0.00,0,null,null,null,null,'5abfce8cabceca1307c0a29b146a97ba8325c0df5d9138121ebecd455b3722a01e9227bc9f560c044454b236af0b42888c8608aa1adabe5d1ecb16179cc8c34a7m9RxBeKinZUl8QJI77bg0ekdYqUwsK8PsuZMx5E4Lo=',9,null,0,null,1,0,null,'2021-09-20',0,130111130199),(14,'5977a736a10531525c1df0cfe0c3b90a970e29aa5c0bfda18bee63aec9c0ef0c9efde6937b109aa6e362e3a9f7da9f46e530c59fc52d55096e114e7a036c556842m7AnjJM/zgXb/EKVIF0kNFP156FsQ+pc/SYb8Xsu4=',null,null,null,null,null,789465,0.00,0,null,null,null,null,'399d0c6cecf453b2ea10429ed0211e79863a20177e4c7e6d11e5cde7068f0d9b26845e588762b8357e64e29d9614ce219913b646b736f05473ba93c66e914f17bszmcFeDAbUTpUZpo3Mk0JUjFfuclbIOca4m7IsUn00=',9,null,0,null,1,0,null,'2021-09-20',0,140523000189),(15,'93e23f9e9e47866d6711b09b7ef30dcac88caaee14168e5604a7e6072bb226b8b87702eda100f056f001d79fa4896fba5b9f15994d83aa7db87127c01dcc2b83EYu/+J1XudJkuAzpy+tVvyRDhdDGox9CDz/1xTv84jC6+5aX9LFVmcIboDqJGL5i',null,null,null,null,null,987654321,0.00,0,null,null,null,null,'4ec91334a3f78e71ed7630ec4278a241ba7061f970affb0c32ab0deda9cc6468a4818cb2ee7457ae758c63bd5273c982cea976d32dc456995b7e1f3d65a7f1f57dyi7rLrUOg5hw9jmElQj591a3r9NzYiBrKXxbaopm4=',5,null,0,'test-2.jpg',1,0,null,'2021-12-21',0,200519200095);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `affiliateapprover`;:||:Separator:||:


CREATE TABLE `affiliateapprover` (
  `idAffiliateApprover` int NOT NULL AUTO_INCREMENT,
  `idAffiliate` int NOT NULL,
  `idEmployee` int NOT NULL,
  `dateEffectivity` datetime DEFAULT NULL,
  PRIMARY KEY (`idAffiliateApprover`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `affiliateapprover` WRITE;:||:Separator:||:
 INSERT INTO `affiliateapprover` VALUES(1,15,141,'2021-12-21 00:00:00'),(2,8,141,'2021-12-21 00:00:00'),(3,2,141,'2022-03-31 00:00:00');:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `affiliatehistory`;:||:Separator:||:


CREATE TABLE `affiliatehistory` (
  `idAffiliateHistory` int NOT NULL AUTO_INCREMENT,
  `idAffiliate` int DEFAULT NULL,
  `affiliateName` text,
  `tagLine` text,
  `address` text,
  `contactPerson` char(50) DEFAULT NULL,
  `contactNumber` char(20) DEFAULT NULL,
  `email` char(50) DEFAULT NULL,
  `tin` char(20) DEFAULT NULL,
  `vatPercent` decimal(18,2) DEFAULT '0.00',
  `vatType` int DEFAULT '1',
  `checkedBy` char(50) DEFAULT NULL,
  `reviewedBy` char(50) DEFAULT NULL,
  `approvedBy1` int DEFAULT NULL,
  `approvedBy2` int DEFAULT NULL,
  `accSchedule` text,
  `month` int DEFAULT '1',
  `remarks` text,
  `refTag` int DEFAULT NULL,
  `logo` text,
  `status` int DEFAULT '0',
  `mainTag` int DEFAULT '0',
  `location` int DEFAULT NULL,
  `dateStart` date DEFAULT NULL,
  PRIMARY KEY (`idAffiliateHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `affiliatehistory` WRITE;:||:Separator:||:
 INSERT INTO `affiliatehistory` VALUES(1,2,'c4840320feb519f875126dfbedbef5f2f24842504a76a7b7608ce7f10941878a6708e148574579efc3f8d91c5bed2c2970b16802829decf6bfe704875c093e97nPTkXXz6r/hw95C4c/yj54o+UepUC4NYLqFhDoh3oIflonAuPZOY9H+iF8wO5WJk','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'304e3472907e826d12215c995b020adec9c9812c284996465e906ac51479aaa484b5b13b6d79392d61bc378a4477d377b77f25d4a0e9ac1d3bf58173205f8e9fmvCTPBpm+w6sqdo3LLzfQiXNWG6AWHpJOa9aMdfgZLs=',4,null,0,null,1,1,null,'2018-03-05'),(2,2,'a8f78cf68bcfc6be8745be1ea4e32cf74d0e49ee12c953eab615e6a9b58a6d3c0700b34fcb690a3e47fdf44b74333d723c6c31eca3a99bd3b3c5413dcea26a9bhSCrvwUHOSLqOtCXijqyqgprk6SWBqCSJPqRSMWD9C0+fCTFayT3HPTL1zQJnkCk','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,101,null,'231f3ae4ed001d4c2dc2b1a26cbeab48d923efcb24d1ac8853f310dd402cf3d9137cd4ae5ba701c65d64d70e9d05ae54a013368a874eb1c1579377670bd7c2cbD8mfA6f9pA6OrBJbzOZyOYCgUyvWv48Nfg9M8E+IuzY=',4,null,0,null,1,1,null,'2018-03-05'),(3,2,'64d5e0f3d0023ecdbe491c228b56c6eb3e3f8d7788ba48c0b15314a48da6245ef45cd59a29e2001931faea1f2e5f3652e75ab755a9a15d6472f42cebb328096dnbDE/yJc9NeWR/LFF1ONPY1vsJxjkJ3LC/1Zo7/Z6i9LANK2FAfBYYQdH3sWRHuP','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,105,null,'7553e9e8214f51f93073f443d4bb29271bdaedb178a216f7144b18a818f7012828ea5e9dd6fe2fa7011b620c231a244b21ebbfd02be72fdc573292931940c36cz4Sd0T6svIQM4dZI8PI2Xr1KJq/5DH/+RD+Rxbkanis=',4,null,0,null,1,1,null,'2018-03-05'),(4,2,'96d58052b74ac3b675ef8898b5f07bfc93214e84b288249893360de009260c12e7a72b28d81053df8a89ae3b414502d959ac5e3943f8b2e0b2e767828b01f8122l4SXbAzjatfkiOQkCrpndUrhgUDjy9oEV6GVN2emVoG67+mHC/xIUpZY/NBLmBm','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,102,null,'d756a794ad03967b144eed538a2d2e4a6ef03afb38921f6684639badc34357b05fba6a9642bc1b1f18e25f0daa9f976354abb6a5fa67c17a25a27efc0614e66fOFy7AuLVhR71+JgsyIQjq3x5HSeTIqKACTkWgQQ4bGs=',4,null,0,null,1,1,null,'2018-03-05'),(5,2,'0d7675f9ef62bbf63275e47919355aa79180f6b0a5215076c7f4851b1abcf752e5b41b5e49a3f8595d6282c2ce5738f4925a9afc0025f621d67334798d6580fejRNfE37zKsdylyOG4qjsd5y50AltkYgqWTP+5sfLk3jT0mIHAjUlkUswYX45THwZ','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'60f601d8a9ae3529b1e77b9b9e7d7d1e8856592eebfd8782f51d016bd2c24e72a795820458c1cf713a52d03190dafec33764a45b184489db2cff8195bade8e273vDTEnARSDBfHYd1opFlnGM1JD33TUd8jKptQ+OCpgM=',4,null,0,null,1,1,null,'2018-03-05'),(6,2,'4f7104870cbed9b9ae52798c5426cf2da98a07bf38e40288b2aa764da42773ef48c5d63e043d9cfb985a20a22fe65b6bc18f2cb68de4c1fcbcc7e31c3e6c27643gkN7TcwHoVoqJyqiS9iqyo0ZhF9BDjgFotfL58SI+lgGKAylrRBDPnT8zsemnal','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'f6f00d462a89147e64f970683b3f6b7b43d006725837e9e461f6fe0cefc0b96181dc21de92f1e66459f983eac512d60cde51ca1d17ee6559f0ee3aa7c4ecde82iY3agibJdBd/34ZvgFCmvYu4cbXelStUGrFwgH/nVUU=',4,null,0,null,1,1,null,'2018-03-05'),(7,2,'8e914636a165697fc082a1828db7822aca5c57b1a86369e8c1575a21993de8f6577bc3b1c1fdd67f2d78c0d3e8f3ff67255d7645fcc6ef1b47dec6f50077e864YE4HsxW2ULM6eTyesD/rOym+RKiyLnxjD/UNkxe4RZUBXDq8kZEasJ8Re61rEjJo','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'f1cae5e092a67f9b8369941e9796980c9e2541b4361db964fd29130a2f2154b02cad3b5d87aff891ffaa1539a98cfbc9c63ecd5acd64b2e5bfd28a93ce56baa9z43xae6ndzSABXbCyWTsdj7FN/Wink731+aNFnfoaLo=',4,null,0,null,1,1,null,'2018-03-05'),(8,2,'c288bfc10f2b5f143fd05b7effc5931f2b181fb828a8bf4adc0e383657fc8d88bb029fc677065b2f47bdb6653e0693f23e3fdca8cb44f6347555f140be7a468ea7xna5EUWWBj0mtmReUZ+7wSBKrfJdx2sEWqPLmma/aDdRAf+VXSonCGMjj9TMrx','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'35fa06ddb7f09c80376e231b4b41e1498d883b73df1ffb74ff163db7c96db11b5c3e7eefa4286f25b51afbfe655c1ec7b02d9e50237509075d915cd832afd258BNCnB63HrLPuepIn46ld8zTiYZm+pdrdRxvA0PXNUZ4=',4,null,0,null,1,1,null,'2018-03-05'),(9,8,'6b0781f129b1d706fc9b6a00bc7d2d00d7c4723fb0cf84479354f3344771535b200f2595ce5a1e0328c424364bc22d03baa020e83dfc546f26a932d00476728cQnH9e+g2c0BsfvUfRHS1809M+lH4bRSGA4RyVQzz3Hk=','This is a tag line','this is an address','Contact my manager',9264148700,'affiliate@gmail.com',19299066,0.00,1,null,null,null,null,'d4ba16409797dacfa027d1f3ced5b67bd7fc6f60664cecb77221f5b2aaa2ba9cf5bd027aff44d1690e9379ac2b1e1b0353ee98ebb1f65f11a55000ffc6fe6eberLvrwgTMHXIpVAkoTb71DsoOcou8FuU1ivvhTnpw93M=',7,null,0,null,1,0,null,'2020-07-03'),(10,8,'7bd15d6cbb0a678ec37be542ccf7aaaf37c958b3a056fceea7268a97e8b8b347ba7727487f88c10486122f75932ef51fe5073127e9bc373fdc952cb2735f3820enqBvuUnYSZ5/8lds+vsONb64s50Fb01OoEW8td6jd0=','This is a tag line','this is an address','Contact my manager',9264148700,'affiliate@gmail.com',19299066,0.00,1,null,null,null,null,'0b5c09460e209b9e6a1d7f52934878a7b6d25610f97ee8b91a675a5d66771b2e225b83c75ba9bc539a94d2c9fb7547c217f35512ebac72771f628426b2e2dacfE5tOjPj7a4Jl72OCRUZUW5Qo+hTSjKq9ZvDUI1pEdfc=',7,null,0,null,1,0,null,'2020-07-03'),(11,2,'d693c011ad43a46d3144ff5931142f81f405a3af5d7286749e4dd782fbdc3c8d151e7babd5af41f7eb2e31ce548ad87a3e365656cf721e72437de98586337496mDs0F9d0GKwWMZEKjMEKB4aokiH4YKrHdgc8U01yFbFHkOa+HUfkAae8UISLBFdM','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'e94645381013bb13bd0985588dbbb3366b09ee76eb43abaff4bcf860af633e84cde6bf822983c4ffd8de012af1573869e13f5627bb7c60ed5da22434d4f42ecbrgQUKTOR4zyhSyGkFfhGYeQm2mHhbfoM7D3OM46gDCs=',4,null,0,null,1,1,null,'2018-03-05'),(12,8,'a8961cb8d924ac49e0368608e77c984be84e4281cf048622eaf606270a857c82d797c4e17103f5cf3344cadba087327d21da0f512768be98a0ed2b25a36676feWvucWxyf0hKgbNmHIjbilaCkxKFgVLI3akr/EwmHicA=','This is a tag line','this is an address','Contact my manager',9264148700,'affiliate@gmail.com',19299066,0.00,1,null,null,null,null,'d34d2abcba76a99da706eda968a2e3e9ec5084e2c48741ac7d3f9ec1506e32a00579db13da6e9a0096d901836a7fe7ca74b662d7a19152b8d0da45dd0e2e5abd6RwgmZFxSZh7mCw6i3LYN94ZKXPRvUrUTjRo6uxTyZI=',7,null,0,null,1,0,null,'2020-07-03'),(13,6,'23cdaf6c7f754709e4c698a5e441632f69f59c40987a84262d89776a6f0f22806a90d780b002cf14bd1b228b49740eba0c87c5af9475994b9018719ae23abd84H9BSnhd4SkafY5MiC9RHSsrulMJa1IlEJHZfeH+I9ERacjD9nO9bwF8GFmBfefsV',null,null,null,null,null,12345,0.00,0,null,null,null,null,'ea3938530ee96dcc6bce34de564a45ebafc3481086f54bcc2629411c416dd968f9a20c9cd64477d7617faf043b0182095356ce49e69b759d2dc6dd9b2239cca7ac8MfBp1ZbbesGr2YLPL65LOntg2QigfaLXuzwid6AY=',7,null,0,null,1,0,null,'2020-07-03'),(14,6,'1aea11f3d1eb8af2c6b64af20ab3866bb938488e5675b02793f796c777d7275565a18b12add84dc389b930d22ce5a0deb2d234b4795174098b059d353afc9a2bAtdjL5/TPUIVVa/MUHlUDRP/PCDxLkbwh1fRInkUW3sv1eJzwVFyTihda67p6NxG',null,null,null,null,null,12345,0.00,0,null,null,null,null,'7a97a0e9a9c7d88f621964f97e3298196a27d60e57ddfe229037a39bc9ec6c9d300b1c0e6054bb7d9ba5577dda3ff04f496674048bc7d6b77eaf4e1002b02841LLQlz+A9MZ67LLmCS7dUY+hjF9kzEgA3WOwUVDdIZD4=',7,null,0,null,1,0,null,'2020-07-03'),(15,11,'e6b91b8ccad9efedf02c1ba8d8af9f6334fc85a3a277e334de0ae2424c9ce814688676129bea9e4cc91e1915c0488238963ade8552560e96d870c52959bf066fc4HCSPVJrpk4GqSeiHu2zoIoATXUrUtvq1TmtwYB5ZgaDoMLH9PXTSHRHIedBu5RpVDY+4At9kS9oh7DiL9SNA==',null,'Balangay 3, Poblacion, Quezon, Bukidnon','Katereen Berly N. Quimod',9176307772,'kfb.kiokongent@yahoo.com.ph',268024933,0.00,0,'f1075d7717a02c0c3452470b96d1e3c3a01437932f4774e4bf','b5a08fbc684bb160ce690a7aacfb01aa44fb731601a6eebfdc',null,null,'7182609bb90cc8e49f1d2f44a391f40d6b1da89b2a84e046febe649e5afa859d7be55d5b256aea813e53433c7fd76044762bd45b3c7468f761c71ad578181524gZHHSioXu4O8sLWud6PS0Vm1qJneb5Ho4tuBqZtu8zo=',7,null,0,'Trucking Logo.png',1,0,null,'2008-12-11'),(16,12,'da02aaea41469fa17d8cdd70c405e07817ab2fa34f899df416ee0f732bed6991e2a2b5f79b5ee74f507181179b3268ccb0836cef1baa908a6098e3a4469f90cf6cX8YqULpr43rMqJFoCRCH6G6I3+vCVoEFQ+m/ulAic=','made for QA regression testing','Sample 123, Cagayan de Oro City','John Doe',9123456789,null,111222333,0.00,0,null,null,null,null,'6fd4f8be8b30ea49ed612e24dbe71b290c16d66730e4f027d85c9f11177280c73db37c086d523f5a48437cc19f6dfdd26522e075779cb09b7c0c21c1895cb84fWELxgJsqM9T8mtqPDkbl1x9MQ+RhHi5lvwHh/cQzAjk=',8,null,1,null,2,0,null,'2021-08-18'),(17,12,'df672a82206368f3eb5b9e8b0ea079e46b7a382c184c27f69946514de0e402567a5822c417e77272829831732e7333eb2ad9f64ccc5e60cff8e197bb0d87f459l0Er48OoNLRqDHkjJTyn6iFn1P/dXuGs1mY++2ePjvU=','made for QA regression testing','Sample 123, Cagayan de Oro City','John Doe',9123456789,null,111222333,0.00,0,null,null,null,null,'a174b83407ea63aee42405bac4f3df01bc2717e2b4064e9cfc476ab337dc220280af6c30707a635b3d0a249fdb2169cf4e39128497ce7a63716fff90cd1450c8VY0J2L02K60KegxBXuQTFURNibwidGK6+oiTX2rokjU=',8,null,1,null,1,0,null,'2021-08-18'),(18,12,'a5a4a2a74465718d3e6543c46b69cdb566ea84ffb87c1ff886666802bf633129a8a2b9f5b910977c358bc60a0569127153514cf5a9cc35710cb97091c1dcc646m7mfpJGuI+c6xld3PQz+2DwEUsfAdwliMZlm9pAvESQ=','made for QA regression testing','Sample 123, Cagayan de Oro City','John Doe',9123456789,null,111222333,0.00,0,null,null,null,null,'9d486bbac3a73a4a74d8ca333ad646d9f590a207e883d40b4916e3faf1f022e58571f269bbabca3a2685bcd99992ddb971995c8daf459f2aa94ba904479c20fb9aSjZulMxxELwSkpjVOBbcGJO3WbRTQA0aXWtpcZQ/Q=',8,null,1,null,2,0,null,'2021-08-18'),(19,5,'a3eb65df16adc260f430132a0868ecc99ecab1a0138947f1daef11c96f61aa3afc328b46ab9617a74bcbb05e7eeff5ffb42df3ed3cb3e7fe6d72013f1db72942UUntgcbnLmIOnATDbxnTTvdbfY4Bf+kLlAyOZJ21vOQ=',null,null,null,null,null,123456789,0.00,0,null,null,null,null,'6360eb1704fd04c709a9a604535f9b40a97d0f139dfe86ad2a28cb3c2f257ca49f03535eb7f8e694010f3dffe88de9f6b43fb46606b50bbfe2d9e58051dec1c9GGYYdiEp37of0UKQZ3x1Y8dPkR9J+oeI+NjqBgjVLH4=',6,null,0,'test.png',1,0,null,'2020-06-05'),(20,2,'72944508b920c9ce98a2b72d7c46c43933e0c2332a476a69b50262c68d71a990196e220122f39dc4da1db3399dcdff379987581d18ad990f2aa237aeeafcbad6ApAte+HFEDc3bD7Rynl2LoBTrTQM6nNTv9HwX257djtIMQuAcwajD2Gz1WBwo8m/','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'e7c445029269c14faa2ca27376f5ba52473543659e83186b5ec64798da5e09d2f6ef2ac4adfd2445077872af557d7dda9efb4a97fe187291c57f388e8aaa68dbndW74dkXuwnaJNtNN+wD+jUudbUwROFwC0soqXU9Lec=',4,null,0,'test.png',1,1,null,'2018-03-05'),(21,2,'5b7945f3a923a9810cae48e42e82ec679c50c1aa04a14f1f85064d88996958abf5288f06c6571aa7d3f6711ed8529ceb1976951c0667dfed60e86ee02ff43eb01ZvwfLl4yEiX9jTiuY5MHJvaNIhbM1cVmT8vNPw/CcEI3d7hyMqfFhpfdWLzyRzM','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'471b85f830f6d73d2935af9263934dfb243bd89e1833614214295c703c4cbbf4ae83db41977f393e840566fad030a28b4de17cc0dfd99fb71169e1ba02f6dd63Ke06dBRxyBXuvcrhqCDkqif4oEoQCF9hEk7yCNq2cqU=',4,null,0,null,1,1,null,'2018-03-05'),(22,2,'ee3bc2eba9178339118576c0ad9cb8505ae0431d6d30a95f601b009c0d8423380d3bfcaf1c3e9d8896252e362fcccb234dc28daa64e2ad5710494877ad3fbd54CiNMdlMFA7J7HUPz8JNL1ouk3NjjD6Aj5aVqyFRn6wtYO6jpbjQ5jlj/CbCHQqCf','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'11aead4daa7ab975a9a6ca14a11e7f3524c0f02ba19a8f09da096669219c8dd4ac5d2f93befc5aeb20317bfcd0e7ca39cca886e8ced18a5ca1f13543e2ef0710gCZlOprnXzBZCrV1n5qUWAxeSK08a2hNHNVNIFhW8TA=',4,null,0,'Sync_Logo.png',1,1,null,'2018-03-05'),(23,2,'f206de42c923ed0e2128c38efa979d3bce3947f390a3c97c13ff3a451426a13594d93d0559617473711be664280ad11cbe3e09c9196b04d281b0de0de06dcecbyTxR+opqTAnFaYt1cN3hL3cLhJyNW/LmvJQ4Py+kJJtqDOOgVDPAhmKYIELenecY','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'93656eed7d21cd182266f884588998ebbe7a9833a5d1547b306fa6ed714b3c253182c657f27191a8c81ef45a2d85a9df5f05722099bf027fcf47ee93f06195326VNsQd/ke+HoS/ZjObeN/LC2C91rUyIIbTFkDHkFOYI=',4,null,0,null,1,1,null,'2018-03-05'),(24,2,'dbea81ec66742b0e6cef83e9f47f2c702e54c1a5e903810e4c75525643c026479068c6097c7ff0e015f1968c73541264c170ba34115e835f84c332dcf8a959a88wJwqZWMjovRwEwldAggZykeIwsqTzIbvQmBQR39yIuIxKpU66uo8+j//XtfANzq','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'ffc12c20db592b7c1d8324dbcb777e9fd536f5d487ab3546d3dd957384f6a78829f339c0eba58c6767df809fd400facb0de31ac6e7fb920ae6cf28c0488b77cfqanpJnwVwoh2SDEaCvtkYRzkQWR/sQ80nTPjpf+jGwc=',4,null,0,'Sync_Logo.png',1,1,null,'2018-03-05'),(25,2,'f4610f0c2019a5258bbc8b122c5a05019f4e8e88dc2abd442bef72db60603c6bb3efb7499240fb82eb2770c22499b649836d83297a88664de9881f0634745cd4EcNKpaoAUWuWwBftZXfRxK98X10fh/cMYZBFzpQBOyUPFPMAHtkb8Q8wTO1/cTl1','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'47daf4a83e292a3fbf7b5ecf053496871a52d454b502890ae4696bbf550aa1fa1a9ea191b84546a1085bdf01440e3e1526ab17014a6cdc4bfea204a161f7a4c98WBPhQSBPRup0rSpVASj2QIFWVzlBJtyuj/bgevjQ5M=',4,null,0,null,1,1,null,'2018-03-05'),(26,2,'fd4839546086b37d0c068a913e1e9bf45a92dbc12ea208f0a77d3b7f923e77b9f78f33f89e8a878717f87c4f2490d8ba5b24fbd9f257a15513f61ed96618fca29PC0HkkxyAxoD2HnIj6baAoH4/P5Ivn8gBvYOZTN0Ds5XX33tGTSfs0I1lZKgwLv','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'3b57ddc319e4a19d170edea09243199ff3087cb7fd1212d52956711a8b5040e410e1a82197aeef69dc8df8f36cb7c474135d6e8cdae1c21a70fbfb334a1cd44b8mQRMTNxXNjbd1zi3371Zb+GbpOG4GAqg4Ud3r/JGMs=',4,null,0,'Sync_Logo.png',1,1,null,'2018-03-05'),(27,2,'f81d3f6ea33e8392e0571113a25b815cf74a128dcc2b7a0c1fb0e7b13caa2103bc58c04d4b056728042e656f1b25c5a0917ea8275378575b2a2ffe5a2f1850e4wUZablx7N5A05xm5S+WFMsH6TzdAQMpI9xCoLCu3tbPjSDd2/IKTnEvS8qxr2IJS','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'20fd00ccb04ffa05211cad0be5d6a080d72dc7729534525a4e045af6e9a562cb2931862a14151d0f86bc10a6e5848e6bc66d3d9e59cfb1c2c58a8529a260ac44UYDMvgyTNK9/yUoeqoDNI8VAsV9FK95Jxt4ouykZjrQ=',4,null,0,null,1,1,null,'2018-03-05'),(28,2,'2a87e17c413358f2293c64f6b075b439aae879a78334146d126b3feeba704a0ccb5725e4680afd0cc0fd76fb3308862c54295c2d02cc7845b88fbb357899b64dPgJeVF4UnoWcXGnK4HJK0vAF49NmftLJD+L28nTleTWOxMEi2nIT32yCIXj7ljow','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'4aeefd62914203a9947323b98830719e92b9a3b1d8bbd593377e5aa5f026bbfa7a0b2a31e357912fd2fc89bd6a0d2c3da83368f521be1c2dfda2c9a35636ef7fa5tTvTbl49TdO+Op2qLkOKn/XQxMBdvbGDwgeoI6zV0=',4,null,0,'Sync_Logo.png',1,1,null,'2018-03-05'),(29,2,'6a7ea3fe6828fe75d626b669a63949929362f2e6cfc61da0694ad9c670f7284387b7cb3ec2855154463337f7c4de4194e799ba4639480d89f12cfc510aadca9aKXrpi5S6MKfQrnolBfJ7GQUqiYEA16W8qlXiESJPLJX1ofq25MWma89g4Hj7A61f','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'a0734863a08aceb678d2730d3a90eeb548202ed37bf831c428678c59514e7b01e3fccc0e5ea057ab4e46bd1e3b95ad4b62b976e3ef830207355bfa1aac2febe1Z26jvwX2Q2VS+qb0VlA7B2xYsm5soMFz6iDafR/0QXY=',4,null,0,null,1,1,null,'2018-03-05'),(30,2,'b4d75c92537352f128597419614db9e38f580ba0a8fd8a0e8cf1edac00e48a74679690e5324d7e14238b4986cb6bc277e288853df7a81676d5dba309436af6851uLQlcI313iKnBNGvm6xEVPTfSWmsnWHWDng5CcDXFdWUSWyqJhtY111OosfFTsP','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'15c4649d78fab40a1860fbe9a1a57166c446de5636ccc3a916915e2609fe4efd394522bf66dad7295f20039efe98b49835185387be9ac022759da75381b7d7338cUyajhXFVGt2ddlX00e/x5n9xc4iF1vVlu7vf79mdg=',4,null,0,'Sync_Logo.png',1,1,null,'2018-03-05'),(31,2,'67b649489bc089a9c3d71f8f066a7bb84a56f581b47d98a29629ee032051333bf54abbeb724215d25be6f17ee0854148b2b239422ff63d0cffb1f245ad5c3d07xd6OUYG0+6N48p3N9cBI33pICPlLcTQvNHIHisG2p4gaXPRv7jn6/aGbawGjN54B','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'cc5001cf2f7f4b53c7b5da97834724f650567aee0d0d76658c8946d57bc4e59f651831e578903ab329e99d046ed1c7d0e621b56953bc9753219270b683cbf0afA2NPlivs/kPt5sf3aX8a85/g1yoFQVFe73sPP+G54VQ=',4,null,0,null,1,1,null,'2018-03-05'),(32,2,'585e5d1d98f78ff8b0f6aa51aad601ec871fba2925c8a76b869c627f1e8602256173396941cc5c4399d2fc8b62d678cfdad0f5b5ff2bfd39606a0e90a79eed587AZHpjSKVquGhuF/cRONAF71QyK365/AZpziu5Pi+OYibyZiY8gFjPYr68iRzbhC','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'c0bd857912a2b979556b3ca3176bfa5e6f8062da1e49b953728da2e9b0fce7f197c7e9bfc03e4e3462eacffe3dbf8c3fc92f3e2a198214ad50e651645dc8c3baoNbhLMD2cAi2uEcudD9iYPW4mW0ka5D0cg91Tlwaktg=',4,null,0,'Sync_Logo.png',1,1,null,'2018-03-05'),(33,5,'29924b152aea23221410f71bb36400e8ca44f607b7d82dfc8018945a53c612e97862353fe8ac15691df2930e650650556d0eb57c3f7d0ce4413c3cdc49effcb2RRwcxMpLUOuLttsW7hxkdVVL19nDEFvUslywLZbz04g=',null,null,null,null,null,123456789,0.00,0,null,null,null,null,'5c940de9d51f3f62b4a4c1a28c8e965e906e5f260e4fced4b18dcb1f73d0e39a7641af8a09b3fa2cb802fc85a00f71ab9fb39cce87266aae41749ef3aaaccbb1WRhzTtq5HjaG4IbMnUnaYRtCAnO/FXlcwTvD8EbqH0s=',6,null,0,null,1,0,null,'2020-06-05'),(34,2,'039714ec2c61b426795d810e9cb99dc9a0bf8fffcbeb683ad1ae64b7cf5e5e31399bd3f29897168f15f4b03a16ef3525a6bb5e4a0c5752066537e7a7265fae9cnQA+VFZbtvZv6ycrT89+3SNUptcJDOZVqyzS9tNMbOmk04yb1ObwgyxwjLuW1CWq','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'b1d0798f1c529a756c3a5e6d325790dcfb6783438b86b040a4d52a0c10a87668bfe5a9c20df49f8c0f8795c6e30afd2b673b646a6d8900e4b0cf4210cd8e96eeSjbqBUJspkeE/u6UVyUpwwsCYFMvBv6yiJI7p5YwXqw=',4,null,0,null,1,1,null,'2018-03-05'),(35,8,'a2a7d81a506d869bfe5dc50988794a076e87460a4f9c01a2c4a6a856bc01ad74cdfd1ca1a2c188a14ab502755a1a58acd579bce2a85e41c0f5ff14e52d356d246Q4eGmQuhzYv7nFuaqJV3k+8yvR97zth7i9qWUYu8SYqzbaKqfj3yn5VXvcW8PVO','This is a tag line','this is an address','Contact my manager',9264148700,'affiliate@gmail.com',19299066,0.00,1,null,null,null,null,'ba71f99785cdc4f9fbc6bed4628a8a0c0dde8888a1a0c523efa5d5bef6e39da22915f9314e4b3d2dfebfd6edb65fdb0b2e21454d20fe2bccea7db287c252c83aLqrNJE6xjcNgDDhpBJjR86N44uXisSCh1+n+jPcheEg=',7,null,0,null,1,0,null,'2020-07-03'),(36,12,'03110db9b606f24b120c31436bfa8e3a44261f7b31f71c749d0d6f529927337e5ccf2000bf4d58955b56341cc9db888b6f9a7dac49f0e0446c3142a3c33be092G9O0AFAv+EvcWaKqUmr3dG0OPn1eLcTO8Qk5IbPLGv8=','made for QA regression testing','Sample 123, Cagayan de Oro City','John Doe',9123456789,null,111222333,0.00,0,null,null,null,null,'ba3e12aff1cde0ae28b8888cebc167c2aed2b4310406307aed4ae35cc7238079766f9395e63072b5d96786880efbf4d86405f75fb9a1a1f874bc0c61684d6987z04irfOTOywvjRWNPdswhHEQf3b/zKOX5BwtIl8N9vg=',8,null,1,null,2,0,null,'2021-08-18'),(37,12,'575d5e2f2634384c35c9984cd0990962d0e7b9e9e796a82b7295af31f68ca12995364a5f42709b9c34622bdb93f39438bf8820e97fa5e3491f75b0c9f1d8f48fAh9INxexQkqRdPheJ74K4rFKXXGDORNzpAicPb9fAZc=','made for QA regression testing','Sample 123, Cagayan de Oro City','John Doe',9123456789,null,111222333,0.00,0,null,null,null,null,'e351a262de2f0327e51b953e1c5db71a6fb0bb1c41e7afaa0b66e21a1f6e9c258a530cd37c1fae760de9ac37a7decdcef3d01852f8a35a7382a19f390d1347e9rXr8NFJK2KEGxt/PYHEeMMp71Gs64j4IRP+5ajUQecM=',8,null,1,null,1,0,null,'2021-08-18'),(38,2,'dae04af7d58e93094df99516bda63552eea19ce46cdeef20612653a84e7c47cc028b47a336e2eacf236425494afd90fdb9458e0964ac8c325d461d9814ba8739/mb9mSdDt9jLS8ZVDfcZt+2viUwhiX2k+60xCRvCGZkNNVYq47/bTvfPoqozk1nK','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'2c47a1b441803f784d197d4cb7b74bdf336dbc9c9512d84bfcd4fcca2bbba42a2d177cff13f17c74104529d6f6d630e07fe0d7e67fe9c642702af16ce6497e5fkrabZlmoaACYFBwVGYluXY+rzSXuVXgwUeaQqi9rfss=',4,null,0,'Sync_Logo.png',1,1,null,'2018-03-05'),(39,2,'a7658274ec4bf2a34dc82a83c7d6acb2fd8ba0a6e14d8554f431a76a669d63b51992b986c5cb06c71b864b629040b2920e3d005dd82f9dc9b5afb4513ab34d08cTdcssAtblKmdZtxG5s6x2PbRcyUzEX11UdHwnR3XkwRhPdPJfGcfFO61ArQs9tP','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'49970de0aee9dcae08ed120aa98714d86911fcbde3a6317bdeac19def4bf2b89e5e0e9dc9050b77b39f60c6d6865a19dfc82781dddd08e037f3c73ec027b68f5wzGnVo6ivsXwBC8Rv6m6+qYkLwja6eOFMMhWpmnfu14=',4,null,0,'syntactics-brand.png',1,1,null,'2018-03-05'),(40,2,'984790a8f485cc3761e95e2c9e92b2a170a297ee49fd68b3a258df720a86c706867bc2ecd0610a5e1002700eb4853bd44817146e61f3f921fc1d1b7533eb1c72ymGjO/tfc/M/9BP28dr8BSzQIHqE1mVcm8sK0xLE1yPUDCNqHeuErjGFeiHXZdUt','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'a6124ef4c6c3f222269f4698a88799242afbf21a6c2a8d04c6fcc35e6019114e41ce78cc0e3575d4064925ec73d5746dafd447b6d4310233517f910093d0dd9crNF1cYL+KgjZj0l2z4Nuz5iPhjDzj4s7fWDudOZ9kJ8=',4,null,0,'syntactics-brand.png',1,1,null,'2018-03-05'),(41,8,'bef5b99e74e2b94e21709295de49ca29413a03d0d97ed0550aacd9578790eccb166d61217dd7fad36d1f1bfca4fc00c4161ab52ba0a0529b9db4e19392589cdci13ZIG0RtBryyNxj+ELt2CNVdT9GdiyJ5OcwPb9hNaCioumoef5JngNOBzTTcaMk','This is a tag line','this is an address','Contact my manager',9264148700,'affiliate@gmail.com',19299066,0.00,1,null,null,null,null,'717ba08d19bdacfce0405418fe4e57db51d6f4224cef77932f998488db56f981d2df8407da29f24b14b1ff7af09a547ef101756c279adecbf55b914a97267a06VfSQ4eJKW46RGEqpbF3fbxicX1xxvAOLDwLHmHWuyAI=',7,null,0,null,2,0,null,'2020-07-03'),(42,2,'3a7bfef1ccbf32eddc5f26989d8026858485be070cb3b028bfa2d29048e9b2210940ffa989ae4d3cac9b0b40352fab897752737936db162a18cd89b7c283b55cASgyF53m+JJntJwRhOsy2pN3zqr7R6fGzEq9vVmN+eag9pgijKH8/2/EEAEVvMaD','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'1f8fa9ff72e9e5f82657ef366d80972056123a64967013085d4da4b6dcd95e358672da4817a5432fcbb9eea34513772ec59c58779656b3b231d365cb70aefe08cvn13mzUpNhebZ9xxY2nhgdGaIwVucFKQARbNVOzQk4=',4,null,0,null,1,1,null,'2018-03-05');:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `alocations`;:||:Separator:||:


CREATE TABLE `alocations` (
  `idAlocations` int NOT NULL AUTO_INCREMENT,
  `idEu` int DEFAULT NULL,
  `idLocation` int DEFAULT NULL,
  PRIMARY KEY (`idAlocations`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `alocations` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `amodule`;:||:Separator:||:


CREATE TABLE `amodule` (
  `idAmodule` int NOT NULL AUTO_INCREMENT,
  `idModule` int DEFAULT NULL,
  `idEu` int DEFAULT NULL,
  `moduleType` int DEFAULT '0',
  `canSave` int DEFAULT '0' COMMENT '0 - False\n1 - True',
  `canEdit` int DEFAULT '0' COMMENT '0 - False\n1 - True',
  `canDelete` int DEFAULT '0' COMMENT '0 - False\n1 - True',
  `canPrint` int DEFAULT '0' COMMENT '0 - False\n1 - True',
  `canCancel` int DEFAULT '0' COMMENT '0 - False\n1 - True',
  `canConfirm` int DEFAULT '0' COMMENT '0 - False\n1 - True',
  PRIMARY KEY (`idAmodule`)
) ENGINE=InnoDB AUTO_INCREMENT=6610 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `amodule` WRITE;:||:Separator:||:
 INSERT INTO `amodule` VALUES(6396,1,60,0,1,1,1,1,0,0),(6397,2,60,4,1,1,1,1,0,0),(6398,3,60,8,1,1,1,1,0,0),(6399,4,60,8,1,1,1,1,0,0),(6400,5,60,8,1,1,1,1,0,0),(6401,6,60,8,1,1,1,1,0,0),(6402,7,60,8,1,1,1,1,0,0),(6403,8,60,7,1,1,1,1,0,0),(6404,9,60,8,1,1,1,1,0,0),(6405,10,60,7,1,1,1,1,0,0),(6406,11,60,7,1,1,1,1,0,0),(6407,12,60,7,1,1,1,1,0,0),(6408,14,60,4,1,1,1,1,0,0),(6409,15,60,4,1,1,1,1,0,0),(6410,16,60,4,1,1,1,1,0,0),(6411,17,60,4,1,1,1,1,0,0),(6412,18,60,4,1,1,1,1,0,0),(6413,19,60,5,1,1,1,1,0,0),(6414,20,60,5,1,1,1,1,0,0),(6415,21,60,4,1,1,1,1,0,0),(6416,22,60,4,1,1,1,1,0,0),(6417,23,60,4,1,1,1,1,0,0),(6418,24,60,4,1,1,1,1,0,0),(6419,25,60,4,1,1,1,1,0,0),(6420,26,60,4,1,1,1,1,0,0),(6421,27,60,4,1,1,1,1,0,0),(6422,28,60,5,1,1,1,1,0,0),(6423,29,60,4,1,1,1,1,0,0),(6424,30,60,4,1,1,1,1,0,0),(6425,33,60,4,1,1,1,1,0,0),(6426,34,60,4,1,1,1,1,0,0),(6427,35,60,5,1,1,1,1,0,0),(6428,36,60,5,1,1,1,1,0,0),(6429,37,60,5,1,1,1,1,0,0),(6430,38,60,5,1,1,1,1,0,0),(6431,39,60,4,1,1,1,1,0,0),(6432,40,60,5,1,1,1,1,0,0),(6433,41,60,4,1,1,1,1,0,0),(6434,42,60,5,1,1,1,1,0,0),(6435,43,60,4,1,1,1,1,0,0),(6436,44,60,5,1,1,1,1,0,0),(6437,45,60,5,1,1,1,1,0,0),(6438,46,60,4,1,1,1,1,0,0),(6439,47,60,4,1,1,1,1,0,0),(6440,48,60,5,1,1,1,1,0,0),(6441,49,60,4,1,1,1,1,0,0),(6442,50,60,6,1,1,1,1,0,0),(6443,51,60,4,1,1,1,1,0,0),(6444,52,60,4,1,1,1,1,0,0),(6445,53,60,4,1,1,1,1,0,0),(6446,54,60,4,1,1,1,1,0,0),(6447,55,60,6,1,1,1,1,0,0),(6448,56,60,6,1,1,1,1,0,0),(6449,57,60,5,1,1,1,1,0,0),(6450,58,60,5,1,1,1,1,0,0),(6451,59,60,4,1,1,1,1,0,0),(6452,60,60,6,1,1,1,1,0,0),(6453,61,60,4,1,1,1,1,0,0),(6454,62,60,5,1,1,1,1,0,0),(6455,63,60,5,1,1,1,1,0,0),(6456,64,60,6,1,1,1,1,0,0),(6457,65,60,6,1,1,1,1,0,0),(6458,66,60,5,1,1,1,1,0,0),(6459,67,60,4,1,1,1,1,0,0),(6460,68,60,5,1,1,1,1,0,0),(6461,69,60,5,1,1,1,1,0,0),(6462,70,60,2,1,1,1,1,0,0),(6463,71,60,2,1,1,1,1,0,0),(6464,72,60,2,1,1,1,1,0,0),(6465,73,60,2,1,1,1,1,0,0),(6466,74,60,2,1,1,1,1,0,0),(6467,75,60,2,1,1,1,1,0,0),(6468,76,60,7,1,1,1,1,0,0),(6469,77,60,2,1,1,1,1,0,0),(6470,78,60,2,1,1,1,1,1,0),(6471,79,60,2,1,1,1,1,1,0),(6472,80,60,2,1,1,1,1,1,0),(6473,81,60,2,1,1,1,1,1,0),(6474,82,60,2,1,1,1,1,1,0),(6475,83,60,1,1,1,1,1,1,0),(6476,84,60,1,1,1,1,1,1,0),(6477,85,60,1,1,1,1,1,1,0),(6478,86,60,1,1,1,1,1,1,0),(6479,87,60,1,1,1,1,1,1,0),(6480,2,61,4,1,0,0,1,0,0),(6481,3,61,8,1,0,0,1,0,0),(6482,4,61,8,1,0,0,1,0,0),(6483,5,61,8,1,0,0,1,0,0),(6484,6,61,8,1,0,0,1,0,0),(6485,7,61,8,1,0,0,1,0,0),(6486,8,61,7,1,0,0,1,0,0),(6487,9,61,8,1,0,0,1,0,0),(6488,10,61,7,1,0,0,1,0,0),(6489,11,61,7,1,0,0,1,0,0),(6490,12,61,7,1,0,0,1,0,0),(6491,14,61,4,1,0,0,1,0,0),(6492,15,61,4,1,0,0,1,0,0),(6493,16,61,4,1,0,0,1,0,0),(6494,17,61,4,1,0,0,1,0,0),(6495,18,61,4,1,0,0,1,0,0),(6496,21,61,4,1,0,0,1,0,0),(6497,22,61,4,1,0,0,1,0,0),(6498,23,61,4,1,0,0,1,0,0),(6499,24,61,4,1,0,0,1,0,0),(6500,25,61,4,1,0,0,1,0,0),(6501,26,61,4,1,0,0,1,0,0),(6502,27,61,4,1,0,0,1,0,0),(6503,29,61,4,1,0,0,1,0,0),(6504,30,61,4,1,0,0,1,0,0),(6505,33,61,4,1,0,0,1,0,0),(6506,34,61,4,1,0,0,1,0,0),(6507,39,61,4,1,0,0,1,0,0),(6508,41,61,4,1,0,0,1,0,0),(6509,43,61,4,1,0,0,1,0,0),(6510,46,61,4,1,0,0,1,0,0),(6511,47,61,4,1,0,0,1,0,0),(6512,49,61,4,1,0,0,1,0,0),(6513,50,61,6,1,0,0,1,0,0),(6514,51,61,4,1,0,0,1,0,0),(6515,52,61,4,1,0,0,1,0,0),(6516,53,61,4,1,0,0,1,0,0),(6517,54,61,4,1,0,0,1,0,0),(6518,55,61,6,1,0,0,1,0,0),(6519,56,61,6,1,0,0,1,0,0),(6520,59,61,4,1,0,0,1,0,0),(6521,60,61,6,1,0,0,1,0,0),(6522,61,61,4,1,0,0,1,0,0),(6523,64,61,6,1,0,0,1,0,0),(6524,65,61,6,1,0,0,1,0,0),(6525,67,61,4,1,0,0,1,0,0),(6526,70,61,2,1,0,0,1,0,0),(6527,71,61,2,1,0,0,1,0,0),(6528,72,61,2,1,0,0,1,0,0),(6529,73,61,2,1,0,0,1,0,0),(6530,74,61,2,1,0,0,1,0,0),(6531,75,61,2,1,0,0,1,0,0),(6532,76,61,7,1,0,0,1,0,0),(6533,77,61,2,1,0,0,1,0,0),(6534,78,61,2,1,0,0,1,0,0),(6535,79,61,2,1,0,0,1,0,0),(6536,80,61,2,1,0,0,1,0,0),(6537,81,61,2,1,0,0,1,0,0),(6538,82,61,2,1,0,0,1,0,0),(6591,87,61,1,1,0,0,1,0,0),(6592,86,61,1,1,1,1,1,1,0),(6593,84,61,1,1,1,1,1,1,0),(6594,85,61,1,1,1,1,1,1,0),(6595,83,61,1,1,0,0,1,0,0),(6596,88,60,3,1,1,1,1,0,0),(6597,88,61,3,1,1,1,1,0,0),(6600,89,60,3,1,1,1,1,0,0),(6601,89,61,3,1,1,1,1,0,0),(6602,90,60,3,1,1,1,1,0,0),(6603,90,61,3,1,1,1,1,0,0),(6604,91,60,3,1,1,1,1,0,0),(6605,91,61,3,1,1,1,1,0,0),(6606,92,60,3,1,1,1,1,0,0),(6607,92,61,3,1,1,1,1,0,0),(6608,93,60,3,1,1,1,1,0,0),(6609,93,61,3,1,1,1,1,0,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `autobackup`;:||:Separator:||:


CREATE TABLE `autobackup` (
  `idAB` int NOT NULL AUTO_INCREMENT,
  `abType` int DEFAULT '3' COMMENT '1 - Daily\n2 - Weekly\n3 - Monthly',
  `abWeek` int DEFAULT '1' COMMENT '1 - Week 1\n2 - Week 2\n3 - Week 3\n4 - Week 4',
  `abDay` int DEFAULT '1' COMMENT '1 - Sunday\n2 - Monday\n3 - Tuesday\n4 - Wednesday\n5 - Thursday\n6 - Friday\n7 - Saturday',
  `abTime` time DEFAULT NULL,
  `latestBackupDate` date DEFAULT NULL,
  PRIMARY KEY (`idAB`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `autobackup` WRITE;:||:Separator:||:
 INSERT INTO `autobackup` VALUES(1,1,null,null,'13:00:00',null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `backuphistory`;:||:Separator:||:


CREATE TABLE `backuphistory` (
  `idBHistory` int NOT NULL AUTO_INCREMENT,
  `bhDate` date DEFAULT NULL,
  `bhTime` time DEFAULT NULL,
  `bhFile` text,
  `bhUser` int DEFAULT NULL,
  PRIMARY KEY (`idBHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `backuphistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `bank`;:||:Separator:||:


CREATE TABLE `bank` (
  `idBank` int NOT NULL AUTO_INCREMENT,
  `bankName` text,
  `archived` int DEFAULT '0',
  `sk` text,
  PRIMARY KEY (`idBank`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `bank` WRITE;:||:Separator:||:
 INSERT INTO `bank` VALUES(1,'5ffa82191b7a6b510d485d69bc00f83ea035bb4e11fb7bf5819e43515b83da99b9b61f8f6cb2c33c6fcd7daf627ccf531722798aa4fc9253f1d3c88e271ec1f6OIvMQSrvqAUnrXkwHIzlePBllhlRID7TrL73eJGt7zs=',0,120114040299),(2,'55d9c6d89998daf4df724cbf52ef0c9db179739e54a814e594324c2f9a8e317857f5a47f9fcc6e983df182fbf3508aeacc4f7f60ad32b4a60b9cea89bec839e4p8pl6EqwZxdpp3zM5wbMXMtiqupd05DWBsnAr9b7P9k=',0,20415010176),(3,'88be81e7cf0da7489d3b62f4292dc3c7354eb862e97dfaa1fcf8ff577cea1c44f44902a5da7fe27740d6d737317cc69a0969241be944a005c49686c44106ed76ypiXZQETcSIF0/QQ5KEcOBHfB7I7ujR/10maiwgYROI=',1,21609010123),(4,'aca6c27de18bcd348cc57b8b47471ed7d7f515ef585406203a9d7c7dc1d4b2f26529749f38ba9bc5f7854c4bf0b58c1aa652d2c7c9c4ffe098bddd29040ac750zBtgzUM3kmW99lbzbTZc9a3ZyLNI5wf9/O+2I4OD78s=',0,21609010172),(5,'2cb86cafcbd4bb40d06368d14d6e61dfa8f57732baf74a930e8d5813e59427d5ebcc699cb71128b4e24da05b67a8eeff03aab7356a2863834601c66b3066efbdA20VwHyN07bx3nhdS2wIXQ2VuyDm3qetcWQGwfXw35I=',0,210316020162);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `bankaccount`;:||:Separator:||:


CREATE TABLE `bankaccount` (
  `idBankAccount` int NOT NULL AUTO_INCREMENT,
  `idAffiliate` int DEFAULT NULL,
  `idBank` int DEFAULT NULL,
  `bankAccount` text,
  `bankAccountNumber` text,
  `begBal` decimal(18,2) DEFAULT '0.00',
  `idCoa` int DEFAULT NULL,
  `remarks` text,
  `archived` int DEFAULT '0',
  `sk` text,
  PRIMARY KEY (`idBankAccount`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `bankaccount` WRITE;:||:Separator:||:
 INSERT INTO `bankaccount` VALUES(1,4,1,'8c62497d023e9248d8aa2413684ddf1402aa9a0091ff1eea53073f1eb42af68f4a80846f0384a6cf258e397b0827ee218deead801c4fc950627486e67934b0855xB6KeJ2TlEdlmmHqXLRy/hXNtCqKRq+DAQcISU0meD9x95ZJqZhIDBtEUOyqgYw','3ce2298c7c973afb189efb30622dcd5fab16448a320bd2dc4822834dd53cf10129f8773a190569ad6f93a07a219386641177f18e899adcbcd567d8c89b1814172+OKR13idO91JQDyhCI+IpRAiZaOSRwGQkgvHbKtCic=',0.00,1102000,null,0,'02160900-16'),(2,2,1,'f441aefb219b749e5a540cfa09a9708e98b4855a304002d361d0cf9af6b81f6620ffc3bd86076228b668ab168beae893b58b18499bb8859adbfe57132128bd53RUkrLHfxXJAZdcYxwg5ibnoFHGUDUdVCxtFuhtDO75lg3nO99pzMLTdy5v0FQ9pQ','08ef95f20ff38d0bb9c613e91a5dd5d9588a346bff29f2f1db8f0cd44079cf1cc6e1826bac0ae1d0c951d1dbf3a8b495f75b7ea26e133de3af2a8fa41d3305f4B4+s3c+OoZqA4NZsf0bPzbAUKTtsNm5g10mCnbODoBU=',0.00,1102001,null,0,'02160900-99'),(3,12,2,'258567b5a547232a0869874893d74c71de79fa70d2072c41278f2592f6aee0205ba25469cce90741da6567c61079030943e7d7d8fbb296d9e73d529b7cae98cflcIIHlNxf6OYM70pVJAYMX4LJFtgFSeq7PhMmw3yBIs=','ecb16d04ba92be6f770beae4532857072e77123fcf64c5361a19bad183f822d4636e88ebf4ccae2b9a1812f849cf114dbac08975fe73dc99c1612dd6729f8f8cs/p45yAjz3ABP6BiCCWDng2/5WC2dzY3eutfsA/KDEU=',500000.00,1102002,null,1,80003151385),(4,12,2,'01e8cac36b419a2d08b42cabdb044093dee1958c27fdca03634c597e6f3ed0c2023eb8a16de547c0f5818ed70fa0bca9bbc2dd3a0c3be178d225c7aadfc1dc6bAfeg3W4UgC9DTLyYAUyTH/bLo4H7UopGFd90oBfdabU=','3b3c934f939e834b04d74a13dfc1cbd705cbcdd2ac0175a4303e0fc5ef0da1b5ee22fc8af536099e2a7e14292f2de363108245572fc71280f9c168de3c278741gTNAxAoyJVRgF8vanWpGWiMaL5NKgNd/eyklocIc+Ag=',1000000.00,1102001,null,0,060112190599),(5,2,5,'e1cf5cdac67abc96c8ae5cee8aae6f77e04d5b3547dd9fe4a17a3caed984907b1b76fd31013516eba57942e54be543c0ecfe412b63de47fccc218c8eee8f88e3cOj+RrD3Xy732Js/Uy9wJkWZF16nFkAHViL/mu8GpjkvUwW27VarKRcQ/14fEA9B','5c54825ba69c1a6d4acf14c63838f289fa66e6d11da890b1a447df900019730bed03f65b8ea26a915a57ccd5c07330c3711405140adf08a899243b818390a2b59YPstVDOLM2o9xIu5tQycR+dobF1OVZ26llkuZ9XEvM=',1000000.00,1102000,null,0,140523002144),(6,2,5,'a3b21ddb570e5e2ac7162d9a721c98cc9919e269914118df2f8727674c034334c72f948adceb6a80f3219ef355115728fc0370713ac00a369d9bd96d3cc9bffaOiSK0VCWzWw4geux/hZTHKOxH2jM/d4XekMfIvhiasI=','1fc42e85f5b34db0b014f153ad81b6aaeebfd5e1b0bbfd2e2d7698fc76941dce9536308f2841fe57391e93a114b991b6a62b2a4e6bbfed3d420642fa5762d1e53eKepaFWiavfe7tPRdh694+QOhiILXuI/6spbXVwLkk=',0.00,1102000,null,0,120120051998);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `bankaccounthistory`;:||:Separator:||:


CREATE TABLE `bankaccounthistory` (
  `idBankAccountHistory` int NOT NULL AUTO_INCREMENT,
  `idBankAccount` int DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  `idBank` int DEFAULT NULL,
  `bankAccount` text,
  `bankAccountNumber` text,
  `begBal` decimal(18,2) DEFAULT '0.00',
  `idCoa` int DEFAULT NULL,
  `remarks` text,
  PRIMARY KEY (`idBankAccountHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `bankaccounthistory` WRITE;:||:Separator:||:
 INSERT INTO `bankaccounthistory` VALUES(1,1,4,1,'8c62497d023e9248d8aa2413684ddf1402aa9a0091ff1eea53073f1eb42af68f4a80846f0384a6cf258e397b0827ee218deead801c4fc950627486e67934b0855xB6KeJ2TlEdlmmHqXLRy/hXNtCqKRq+DAQcISU0meD9x95ZJqZhIDBtEUOyqgYw','3ce2298c7c973afb189efb30622dcd5fab16448a320bd2dc4822834dd53cf10129f8773a190569ad6f93a07a219386641177f18e899adcbcd567d8c89b1814172+OKR13idO91JQDyhCI+IpRAiZaOSRwGQkgvHbKtCic=',0.00,1102000,null),(2,2,2,1,'f441aefb219b749e5a540cfa09a9708e98b4855a304002d361d0cf9af6b81f6620ffc3bd86076228b668ab168beae893b58b18499bb8859adbfe57132128bd53RUkrLHfxXJAZdcYxwg5ibnoFHGUDUdVCxtFuhtDO75lg3nO99pzMLTdy5v0FQ9pQ','08ef95f20ff38d0bb9c613e91a5dd5d9588a346bff29f2f1db8f0cd44079cf1cc6e1826bac0ae1d0c951d1dbf3a8b495f75b7ea26e133de3af2a8fa41d3305f4B4+s3c+OoZqA4NZsf0bPzbAUKTtsNm5g10mCnbODoBU=',0.00,1102001,null),(3,3,12,2,'258567b5a547232a0869874893d74c71de79fa70d2072c41278f2592f6aee0205ba25469cce90741da6567c61079030943e7d7d8fbb296d9e73d529b7cae98cflcIIHlNxf6OYM70pVJAYMX4LJFtgFSeq7PhMmw3yBIs=','ecb16d04ba92be6f770beae4532857072e77123fcf64c5361a19bad183f822d4636e88ebf4ccae2b9a1812f849cf114dbac08975fe73dc99c1612dd6729f8f8cs/p45yAjz3ABP6BiCCWDng2/5WC2dzY3eutfsA/KDEU=',500000.00,1102002,null),(4,4,12,2,'3d22b650c34cb44a16e1812d06f4a5d89998341b9c73d4dd4a9af05c3f3bfe0f94cc1c18adb3db4fe0fde57ea0faa0b527ddedcb9a136204d1dacbcc00939ad3C80NpmiVuybonwdgmIstAmtrhOzG73YkRKZMBiZPlGLk6Dh1aOXrQuXOkS1Ah0JT','51ffac4ef5cce93e62a96c9f4b7f814ea8d149b415fb47e9c38efe39f16981facb7a8a65a97c83d0f19a3432b9624d340bd20b69f936bf7dc6245bf73133360cFRVjGroQcX7a5DIM9S8jYRFZ53XiRDKJSQvxge1d7pE=',1000000.00,1102002,null),(5,5,2,5,'e1cf5cdac67abc96c8ae5cee8aae6f77e04d5b3547dd9fe4a17a3caed984907b1b76fd31013516eba57942e54be543c0ecfe412b63de47fccc218c8eee8f88e3cOj+RrD3Xy732Js/Uy9wJkWZF16nFkAHViL/mu8GpjkvUwW27VarKRcQ/14fEA9B','5c54825ba69c1a6d4acf14c63838f289fa66e6d11da890b1a447df900019730bed03f65b8ea26a915a57ccd5c07330c3711405140adf08a899243b818390a2b59YPstVDOLM2o9xIu5tQycR+dobF1OVZ26llkuZ9XEvM=',1000000.00,1102000,null),(6,6,2,5,'a3b21ddb570e5e2ac7162d9a721c98cc9919e269914118df2f8727674c034334c72f948adceb6a80f3219ef355115728fc0370713ac00a369d9bd96d3cc9bffaOiSK0VCWzWw4geux/hZTHKOxH2jM/d4XekMfIvhiasI=','1fc42e85f5b34db0b014f153ad81b6aaeebfd5e1b0bbfd2e2d7698fc76941dce9536308f2841fe57391e93a114b991b6a62b2a4e6bbfed3d420642fa5762d1e53eKepaFWiavfe7tPRdh694+QOhiILXuI/6spbXVwLkk=',0.00,1102000,null),(7,4,12,2,'01e8cac36b419a2d08b42cabdb044093dee1958c27fdca03634c597e6f3ed0c2023eb8a16de547c0f5818ed70fa0bca9bbc2dd3a0c3be178d225c7aadfc1dc6bAfeg3W4UgC9DTLyYAUyTH/bLo4H7UopGFd90oBfdabU=','3b3c934f939e834b04d74a13dfc1cbd705cbcdd2ac0175a4303e0fc5ef0da1b5ee22fc8af536099e2a7e14292f2de363108245572fc71280f9c168de3c278741gTNAxAoyJVRgF8vanWpGWiMaL5NKgNd/eyklocIc+Ag=',1000000.00,1102001,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `bankhistory`;:||:Separator:||:


CREATE TABLE `bankhistory` (
  `idBankHistory` int NOT NULL AUTO_INCREMENT,
  `idBank` int DEFAULT NULL,
  `bankName` text,
  `bankhistorycol` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idBankHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `bankhistory` WRITE;:||:Separator:||:
 INSERT INTO `bankhistory` VALUES(1,2,'55d9c6d89998daf4df724cbf52ef0c9db179739e54a814e594324c2f9a8e317857f5a47f9fcc6e983df182fbf3508aeacc4f7f60ad32b4a60b9cea89bec839e4p8pl6EqwZxdpp3zM5wbMXMtiqupd05DWBsnAr9b7P9k=',null),(2,1,'7628f88334ac90b14b959a23cfff3b95e66df858e183409f7b4ebe0c13ee1af02ad3fea3801af390b07609dfa96b6b634ca56da5754929f777d12dc12fc5b3a9tcFlnCA3FEoe08lF3BJKyFQtUfsCWssqIE6PRL9xkvA=',null),(3,1,'5ffa82191b7a6b510d485d69bc00f83ea035bb4e11fb7bf5819e43515b83da99b9b61f8f6cb2c33c6fcd7daf627ccf531722798aa4fc9253f1d3c88e271ec1f6OIvMQSrvqAUnrXkwHIzlePBllhlRID7TrL73eJGt7zs=',null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `bankrecon`;:||:Separator:||:


CREATE TABLE `bankrecon` (
  `idBankRecon` int NOT NULL AUTO_INCREMENT,
  `idReference` int DEFAULT NULL,
  `idReferenceSeries` int DEFAULT NULL,
  `reconDate` datetime DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  `reconMonth` int DEFAULT NULL COMMENT '1 - January\n2 - February\n3 - March\n4 - April\n5 - May\n6 - June\n7 - July\n8 - August\n9 - September\n10 - October\n11 - November\n12 - December',
  `reconYear` int DEFAULT NULL,
  `idBank` int DEFAULT NULL,
  `idBankAccount` int DEFAULT NULL,
  `description` char(250) DEFAULT NULL,
  `remark` char(250) DEFAULT NULL,
  `adjustedBankBal` decimal(18,2) DEFAULT '0.00',
  `adjustedBookBal` decimal(18,2) DEFAULT '0.00',
  `dateModified` datetime DEFAULT NULL,
  `idCostCenter` int DEFAULT NULL,
  `unAdjustedBankBalance` decimal(18,2) DEFAULT '0.00',
  `unAdjustedBookBalance` decimal(18,2) DEFAULT '0.00',
  `bankBalNextMonth` decimal(18,2) DEFAULT '0.00',
  `hasJournal` int DEFAULT '0' COMMENT '0 - False\n1 - True',
  `preparedBy` int DEFAULT NULL,
  `notedBy` int DEFAULT NULL,
  `status` int DEFAULT '1' COMMENT '1 - Pending\n2 - Approved\n3 - Cancelled',
  `archived` int DEFAULT '0',
  `referenceNum` int DEFAULT NULL,
  `cancelTag` int DEFAULT '0',
  `cancelledBy` int DEFAULT NULL,
  PRIMARY KEY (`idBankRecon`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `bankrecon` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `bankreconadjustment`;:||:Separator:||:


CREATE TABLE `bankreconadjustment` (
  `idBankReconAdjusted` int NOT NULL AUTO_INCREMENT,
  `idBankRecon` int DEFAULT NULL,
  `description` text,
  `amount` double DEFAULT NULL,
  `date` date DEFAULT NULL,
  `datestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idBankReconAdjusted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;:||:Separator:||:


LOCK TABLES `bankreconadjustment` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `bankreconadjustmenthistory`;:||:Separator:||:


CREATE TABLE `bankreconadjustmenthistory` (
  `idHistory` int NOT NULL AUTO_INCREMENT,
  `idBankReconAdjusted` int DEFAULT NULL,
  `idBankRecon` int DEFAULT NULL,
  `idBankReconHistory` int DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL,
  `amount` varchar(45) DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;:||:Separator:||:


LOCK TABLES `bankreconadjustmenthistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `bankreconhistory`;:||:Separator:||:


CREATE TABLE `bankreconhistory` (
  `idBankReconHistory` int NOT NULL AUTO_INCREMENT,
  `idBankRecon` int DEFAULT NULL,
  `idReference` int DEFAULT NULL,
  `idReferenceSeries` int DEFAULT NULL,
  `reconDate` datetime DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  `reconMonth` int DEFAULT '1' COMMENT '1 - January\n2 - February\n3 - March\n4 - April\n5 - May\n6 - June\n7 - July\n8 - August\n9 - September\n10 - October\n11 - November\n12 - December',
  `idBank` int DEFAULT NULL,
  `idBankAccount` int DEFAULT NULL,
  `description` text,
  `remark` text,
  `adjustedBankBal` decimal(18,2) DEFAULT '0.00',
  `adjustedBookBal` decimal(18,2) DEFAULT '0.00',
  `dateModified` datetime DEFAULT NULL,
  `idCostCenter` int DEFAULT NULL,
  `unAdjustedBankBalance` decimal(18,2) DEFAULT '0.00',
  `unAdjustedBookBalance` decimal(18,2) DEFAULT '0.00',
  `reconYear` int DEFAULT NULL,
  `bankBalNextMonth` decimal(18,2) DEFAULT '0.00',
  `preparedBy` int DEFAULT NULL,
  `notedBy` int DEFAULT NULL,
  `status` int DEFAULT '1' COMMENT '1 - Pending\n2 - Approved\n3 - Cancelled',
  `referenceNum` int DEFAULT NULL,
  PRIMARY KEY (`idBankReconHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `bankreconhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `begbal`;:||:Separator:||:


CREATE TABLE `begbal` (
  `idBegBal` int NOT NULL AUTO_INCREMENT,
  `idAccBegBal` int DEFAULT NULL,
  `idCoa` int DEFAULT NULL,
  `begBal` decimal(18,2) DEFAULT '0.00',
  PRIMARY KEY (`idBegBal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `begbal` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `begbalhistory`;:||:Separator:||:


CREATE TABLE `begbalhistory` (
  `idBegBalHistory` int NOT NULL AUTO_INCREMENT,
  `idBegBal` int DEFAULT NULL,
  `idCoa` int DEFAULT NULL,
  `begBal` decimal(18,2) DEFAULT '0.00',
  PRIMARY KEY (`idBegBalHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `begbalhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `coa`;:||:Separator:||:


CREATE TABLE `coa` (
  `idCoa` int NOT NULL AUTO_INCREMENT,
  `accountType` int DEFAULT NULL COMMENT '1 - header | 2 - subsidiary',
  `acod_c15` char(15) DEFAULT NULL,
  `aname_c30` char(100) DEFAULT NULL,
  `mocod_c1` int(1) unsigned zerofill DEFAULT '0' COMMENT '1 - Assets | 2 - Liabilities | 3 - Capital | 4 -Revenue | 5 -Expenses',
  `chcod_c1` int(1) unsigned zerofill DEFAULT '0',
  `accod_c2` int(2) unsigned zerofill DEFAULT '00',
  `sucod_c3` int(3) unsigned zerofill DEFAULT '000',
  `norm_c2` char(2) DEFAULT NULL,
  `accID` int DEFAULT '0',
  `dateModified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `recordedBy` int DEFAULT NULL,
  `cashflow_classification` int DEFAULT '0',
  `archived` int DEFAULT '0',
  PRIMARY KEY (`idCoa`)
) ENGINE=MyISAM AUTO_INCREMENT=5102002 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `coa` WRITE;:||:Separator:||:
 INSERT INTO `coa` VALUES(1101000,1,1101000,'Accounts Receivable',1,1,01,000,'DR',3,'2020-04-22 21:47:58',34,null,0),(4101000,1,4101000,'Revenue Account',4,1,01,000,'CR',1,'2020-04-22 21:08:07',34,null,0),(2101000,1,2101000,'Accounts Payable',2,1,01,000,'CR',12,'2020-04-22 21:47:42',34,null,0),(3101000,1,3101000,'Retained Earnings',3,1,01,000,'CR',26,'2020-04-22 21:47:35',34,null,0),(1102000,1,1102000,'Cash in Bank',1,1,02,000,'DR',2,'2020-06-12 18:44:52',34,null,0),(1103000,1,1103000,'Inventory Account',1,1,03,000,'DR',5,'2020-06-12 18:45:08',34,null,0),(2102000,1,2102000,'Goods Receipt Clearing',2,1,02,000,'CR',17,'2020-06-12 18:45:16',34,null,0),(5101000,1,5101000,'Expense Account',5,1,01,000,'DR',17,'2020-04-23 21:24:57',34,null,0),(4102000,1,4102000,'Sales',4,1,02,000,'CR',14,'2020-04-23 21:27:11',34,null,0),(4102001,2,4102001,'Sales Discount',4,1,02,001,'DR',1,'2020-04-23 21:28:10',34,null,1),(1102001,2,1102001,'Cash In Bank - BPI',1,1,02,001,'DR',2,'2020-04-23 21:28:44',34,null,0),(1101001,2,1101001,'Accounts Receivable - Customer 1',1,1,01,001,'DR',3,'2020-06-12 18:44:43',34,null,0),(5102000,1,5102000,'Business Travel and Transportation',5,1,02,000,'DR',23,'2020-04-23 21:32:12',34,null,0),(5102001,2,5102001,'Employee Expenses',5,1,02,001,'DR',23,'2020-04-23 21:32:40',34,null,0),(1104000,1,1104000,'Cash in Bank',1,1,04,000,'DR',2,'2020-09-02 23:09:29',34,0,0),(4102002,2,4102002,'Sales Discount from Sample',4,1,02,002,'DR',1,'2020-09-02 23:09:29',34,0,0),(1105000,1,1105000,'Accounts Receivable',1,1,05,000,'DR',3,'2020-09-02 23:09:58',34,0,0),(1106000,1,1106000,'Cash in Bank',1,1,06,000,'DR',2,'2020-09-02 23:09:58',34,0,0),(4102003,2,4102003,'Sales Discount',4,1,02,003,'DR',1,'2020-09-02 23:09:58',34,0,0),(1107000,1,1107000,'Accounts Receivable',1,1,07,000,'DR',3,'2020-09-03 00:09:21',34,0,0),(1108000,1,1108000,'Cash in Bank',1,1,08,000,'DR',2,'2020-09-03 00:09:21',34,0,0),(4102004,2,4102004,'Sales Discount',4,1,02,004,'DR',1,'2020-09-03 00:09:21',34,0,0),(1109000,1,1109000,'Accounts Receivable',1,1,09,000,'DR',3,'2020-09-03 00:09:52',34,0,0),(1110000,1,1110000,'Cash in Bank',1,1,10,000,'DR',2,'2020-09-03 00:09:52',34,0,0),(4102005,2,4102005,'Sales Discount',4,1,02,005,'DR',1,'2020-09-03 00:09:52',34,0,0),(1111000,1,1111000,'Accounts Receivable',1,1,11,000,'DR',3,'2020-09-03 00:09:20',34,0,0),(1112000,1,1112000,'Cash in Bank',1,1,12,000,'DR',2,'2020-09-03 00:09:20',34,0,0),(4102006,2,4102006,'Sales Discount',4,1,02,006,'DR',1,'2020-09-03 00:09:20',34,0,0),(2301000,1,2301000,'Sample Lianbility',2,3,01,000,'CR',1,'2020-09-03 00:09:20',34,0,0),(1113000,1,1113000,'Accounts Receivable from Dulcy',1,1,13,000,'DR',3,'2020-09-03 01:09:06',34,0,0),(1114000,1,1114000,'Cash in Bank from Dulcy',1,1,14,000,'DR',2,'2020-09-03 01:09:06',34,0,0),(4102007,2,4102007,'Sales Discount from Dulcy',4,1,02,007,'DR',1,'2020-09-03 01:09:06',34,0,0),(2302000,1,2302000,'Sample Liability',2,3,02,000,'CR',1,'2020-09-03 01:09:06',34,0,0),(2302001,2,2302001,'Liability 1',2,3,02,001,'CR',1,'2020-09-03 01:09:06',34,0,0),(1115000,1,1115000,'Cash in Bank from Hazel',1,1,15,000,'DR',2,'2020-09-03 02:09:00',34,0,0),(1116000,1,1116000,'Accounts Receivable from Hazel',1,1,16,000,'DR',3,'2020-09-03 02:09:32',34,0,0),(1102002,2,1102002,'Cash in Bank - BDO',1,1,02,002,'DR',26,'2021-09-13 14:53:55',34,1,0),(1117000,1,1117000,'Cash in Bank - Hannah',1,1,17,000,'DR',8,'2021-09-13 14:55:27',34,null,1),(4103000,1,4103000,'Sample Revenue Header',4,1,03,000,'CR',14,'2021-12-21 11:37:46',60,null,0),(4103001,2,4103001,'Sample Subsidiary for Revenue',4,1,03,001,'CR',15,'2021-12-21 11:38:52',60,null,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `coaaffiliate`;:||:Separator:||:


CREATE TABLE `coaaffiliate` (
  `idCoaAffiliate` int NOT NULL AUTO_INCREMENT,
  `idCoa` int DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  PRIMARY KEY (`idCoaAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `coaaffiliate` WRITE;:||:Separator:||:
 INSERT INTO `coaaffiliate` VALUES(1,4101000,2),(2,3101000,2),(3,2101000,2),(5,1101000,2),(8,5101000,2),(9,4102000,2),(11,4102001,2),(12,1102001,2),(14,5102000,2),(15,5102001,2),(16,1101001,2),(17,1101001,5),(18,1101001,4),(19,1102000,2),(20,1102000,5),(21,1102000,4),(22,1103000,2),(23,1103000,5),(24,1103000,4),(25,2102000,2),(26,2102000,5),(27,2102000,4),(28,1102002,12),(31,1117000,2),(32,1117000,12),(33,4103000,6),(34,4103000,5),(35,4103000,12),(36,4103000,14),(37,4103000,15),(38,4103000,2),(39,4103000,13),(40,4103000,8),(41,4103000,4),(42,4103001,6),(43,4103001,5),(44,4103001,12),(45,4103001,14),(46,4103001,15),(47,4103001,2),(48,4103001,13),(49,4103001,8),(50,4103001,4);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `coaaffiliatehistory`;:||:Separator:||:


CREATE TABLE `coaaffiliatehistory` (
  `idCoaAffiliate` int NOT NULL AUTO_INCREMENT,
  `idCoa` int DEFAULT NULL,
  `idCoaHistory` int DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  PRIMARY KEY (`idCoaAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `coaaffiliatehistory` WRITE;:||:Separator:||:
 INSERT INTO `coaaffiliatehistory` VALUES(1,4101000,6,2),(2,3101000,7,2),(3,2101000,8,2),(4,1102000,9,2),(5,1101000,10,2),(6,1103000,11,2),(7,2102000,12,2),(8,5101000,13,2),(9,4102000,14,2),(10,4103000,15,2),(11,4102001,16,2),(12,1102001,17,2),(13,1101001,18,2),(14,5102000,19,2),(15,5102001,20,2),(16,1101001,21,2),(17,1101001,21,5),(18,1101001,21,4),(19,1102000,22,2),(20,1102000,22,5),(21,1102000,22,4),(22,1103000,23,2),(23,1103000,23,5),(24,1103000,23,4),(25,2102000,24,2),(26,2102000,24,5),(27,2102000,24,4),(28,1102002,25,12),(29,1117000,26,12),(30,1117000,26,2),(31,1117000,27,2),(32,1117000,27,12),(33,4103000,28,6),(34,4103000,28,5),(35,4103000,28,12),(36,4103000,28,14),(37,4103000,28,15),(38,4103000,28,2),(39,4103000,28,13),(40,4103000,28,8),(41,4103000,28,4),(42,4103001,29,6),(43,4103001,29,5),(44,4103001,29,12),(45,4103001,29,14),(46,4103001,29,15),(47,4103001,29,2),(48,4103001,29,13),(49,4103001,29,8),(50,4103001,29,4);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `coahistory`;:||:Separator:||:


CREATE TABLE `coahistory` (
  `idCoaHistory` int NOT NULL AUTO_INCREMENT,
  `idCoa` int DEFAULT NULL,
  `accountType` int DEFAULT NULL,
  `acod_c15` char(15) DEFAULT NULL,
  `aname_c30` char(50) DEFAULT NULL,
  `mocod_c1` int DEFAULT NULL,
  `chcod_c1` int DEFAULT NULL,
  `accod_c2` int DEFAULT NULL,
  `sucod_c3` int DEFAULT NULL,
  `norm_c2` int DEFAULT NULL,
  `accID` int DEFAULT NULL,
  `recordedBy` int DEFAULT NULL,
  `cashflow_classification` int DEFAULT NULL,
  `dateModified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idCoaHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `coahistory` WRITE;:||:Separator:||:
 INSERT INTO `coahistory` VALUES(1,1101000,1,1101000,'Accounts Receivable',1,1,1,0,1,3,34,null,null),(2,4101000,1,4101000,'Revenue Account',4,1,1,0,2,1,34,null,null),(3,2101000,1,2101000,'Accounts Payable',2,1,1,0,2,12,34,null,null),(4,3101000,1,3101000,'Retained Earnings',3,1,1,0,2,26,34,null,null),(5,1102000,1,1102000,'Cash in Bank',1,1,2,0,1,2,34,null,null),(6,4101000,1,4101000,'Revenue Account',4,1,1,0,2,1,34,null,'2020-04-21 15:59:23'),(7,3101000,1,3101000,'Retained Earnings',3,1,1,0,2,26,34,null,'2020-04-21 16:00:27'),(8,2101000,1,2101000,'Accounts Payable',2,1,1,0,2,12,34,null,'2020-04-21 15:59:43'),(9,1102000,1,1102000,'Cash in Bank',1,1,2,0,1,2,34,null,'2020-04-21 16:00:58'),(10,1101000,1,1101000,'Accounts Receivable',1,1,1,0,1,3,34,null,'2020-04-21 15:57:06'),(11,1103000,1,1103000,'Inventory Account',1,1,3,0,1,5,34,null,null),(12,2102000,1,2102000,'Goods Receipt Clearing',2,1,2,0,2,17,34,null,null),(13,5101000,1,5101000,'Expense Account',5,1,1,0,1,17,34,null,null),(14,4102000,1,4102000,'Sales',4,1,2,0,2,14,34,null,null),(15,4103000,1,4103000,'Sales Discount',4,1,3,0,1,1,34,null,null),(16,4103000,2,4102001,'Sales Discount',4,1,2,1,1,1,34,null,'2020-04-23 21:27:49'),(17,1102001,2,1102001,'Cash In Bank - BPI',1,1,2,1,1,2,34,null,null),(18,1101001,2,1101001,'Accounts Receivable - Customer 1',1,1,1,1,1,3,34,null,null),(19,5102000,1,5102000,'Business Travel and Transportation',5,1,2,0,1,23,34,null,null),(20,5102001,2,5102001,'Employee Expenses',5,1,2,1,1,23,34,null,null),(21,1101001,2,1101001,'Accounts Receivable - Customer 1',1,1,1,1,1,3,34,null,'2020-04-23 21:29:15'),(22,1102000,1,1102000,'Cash in Bank',1,1,2,0,1,2,34,null,'2020-04-22 21:47:49'),(23,1103000,1,1103000,'Inventory Account',1,1,3,0,1,5,34,null,'2020-04-23 21:20:11'),(24,2102000,1,2102000,'Goods Receipt Clearing',2,1,2,0,2,17,34,null,'2020-04-23 21:24:22'),(25,1102002,2,1102002,'Cash in Bank - BDO',1,1,2,2,1,26,34,1,null),(26,1117000,1,1117000,'Sample HA',1,1,17,0,1,8,34,null,null),(27,1117000,1,1117000,'Cash in Bank - Hannah',1,1,17,0,1,8,34,null,'2021-09-13 14:54:54'),(28,4103000,1,4103000,'Sample Revenue Header',4,1,3,0,2,14,60,null,null),(29,4103001,2,4103001,'Sample Subsidiary for Revenue',4,1,3,1,2,15,60,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `consactivitydetails`;:||:Separator:||:


CREATE TABLE `consactivitydetails` (
  `idConsActivityDetails` int NOT NULL AUTO_INCREMENT,
  `subActivityType` decimal(10,0) DEFAULT NULL,
  `idConsSubActivity` int DEFAULT NULL,
  `ocm` decimal(10,0) DEFAULT NULL,
  `contractorsProfit` decimal(10,0) DEFAULT NULL,
  `vat` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`idConsActivityDetails`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `consactivitydetails` WRITE;:||:Separator:||:
 INSERT INTO `consactivitydetails` VALUES(2,null,2,0,0,0),(7,null,7,0,0,0),(15,null,15,0,0,0),(17,null,17,0,0,0),(19,null,19,0,0,0),(21,null,21,0,0,0),(22,null,22,0,0,0),(24,null,24,0,0,0),(25,null,25,0,0,0),(26,null,26,0,0,0),(27,null,27,0,0,0),(28,null,28,0,0,0),(29,null,29,0,0,0),(32,null,32,1,2,4),(33,null,33,0,0,0),(34,null,34,1,2,3),(35,null,35,1,2,4),(40,null,40,1,2,3),(41,null,41,0,0,0),(42,null,42,0,0,0),(43,null,43,12,8,6),(44,null,44,0,0,0),(45,null,45,10,10,12),(46,null,46,0,0,0),(47,null,47,0,0,0),(48,null,48,10,0,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `consactivitydetailsequip`;:||:Separator:||:


CREATE TABLE `consactivitydetailsequip` (
  `idConsActivityDetailsEquip` int NOT NULL AUTO_INCREMENT,
  `idConsActivityDetails` int DEFAULT NULL,
  `idTruck` int DEFAULT NULL,
  `qty` decimal(10,6) DEFAULT NULL,
  `unitCost` decimal(10,6) DEFAULT NULL,
  PRIMARY KEY (`idConsActivityDetailsEquip`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `consactivitydetailsequip` WRITE;:||:Separator:||:
 INSERT INTO `consactivitydetailsequip` VALUES(21,34,5,1.000000,123.000000),(22,35,5,1.000000,0.000000),(28,40,2,1.000000,2.000000),(30,45,1,150.000000,1500.000000),(31,45,6,100.000000,750.000000),(32,45,2,100.000000,1500.000000),(33,43,6,1.000000,50.000000),(34,47,5,50.000000,1000.000000),(36,48,1,500.000000,1000.000000),(37,48,2,50.000000,500.000000);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `consactivitydetailsequipreleased`;:||:Separator:||:


CREATE TABLE `consactivitydetailsequipreleased` (
  `idConsActivityDetailsEquipReleased` int NOT NULL AUTO_INCREMENT,
  `idConsActivityDetailsEquip` int NOT NULL,
  `idProjectAccomplishment` int DEFAULT NULL,
  `releasedQty` decimal(10,0) DEFAULT NULL,
  `releasedCost` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`idConsActivityDetailsEquipReleased`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `consactivitydetailsequipreleased` WRITE;:||:Separator:||:
 INSERT INTO `consactivitydetailsequipreleased` VALUES(6,23,7,2,2),(8,23,8,4,2),(9,23,6,5,2),(10,24,9,7,2),(11,24,10,8,2),(13,24,11,2,2),(20,24,12,2,2),(23,25,13,0,2),(24,25,13,5,2),(25,25,13,0,2),(26,25,14,5,2),(27,25,14,0,2),(28,25,14,5,2),(29,25,14,0,2),(30,25,14,5,2),(31,25,15,1,2);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `consactivitydetailsitem`;:||:Separator:||:


CREATE TABLE `consactivitydetailsitem` (
  `idConsActivityDetailsItem` int NOT NULL AUTO_INCREMENT,
  `idConsActivityDetails` int DEFAULT NULL,
  `idItem` int DEFAULT NULL,
  `qty` decimal(10,6) DEFAULT NULL,
  `unitCost` decimal(10,6) DEFAULT NULL,
  `remainingBalance` decimal(10,6) DEFAULT NULL,
  PRIMARY KEY (`idConsActivityDetailsItem`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `consactivitydetailsitem` WRITE;:||:Separator:||:
 INSERT INTO `consactivitydetailsitem` VALUES(40,34,14,1.000000,30.000000,null),(41,35,4,1.000000,150.000000,null),(61,40,9,1.000000,123.000000,null),(62,40,10,2.000000,123.000000,null),(63,40,11,3.000000,123.000000,null),(64,40,3,4.000000,189.000000,null),(65,41,14,1.000000,30.000000,null),(67,42,14,1.000000,30.000000,1.000000),(73,45,13,1500.000000,24.500000,1500.000000),(74,45,14,150.000000,30.000000,150.000000),(75,45,9,500.000000,123.000000,500.000000),(76,45,11,500.000000,123.000000,500.000000),(77,45,4,500.000000,150.000000,500.000000),(83,43,13,1.000000,24.500000,1.000000),(84,44,13,10.000000,24.500000,10.000000),(85,44,10,100.000000,123.000000,100.000000),(86,44,9,50.000000,123.000000,50.000000),(87,44,11,50.000000,123.000000,50.000000),(88,46,3,1000.000000,189.000000,1000.000000),(89,46,22,500.000000,12.000000,500.000000),(90,46,9,500.000000,123.000000,500.000000),(91,46,11,500.000000,123.000000,500.000000),(92,46,14,1000.000000,30.000000,1000.000000),(93,47,14,15.000000,30.000000,15.000000),(94,47,4,100.000000,150.000000,100.000000),(95,47,22,50.000000,12.000000,50.000000),(99,48,15,1500.000000,150.000000,1500.000000),(100,48,14,500.000000,30.000000,500.000000),(101,48,13,100.000000,24.500000,100.000000);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `consactivitydetailsitemreleased`;:||:Separator:||:


CREATE TABLE `consactivitydetailsitemreleased` (
  `idConsActivityDetailsItemReleased` int NOT NULL AUTO_INCREMENT,
  `idProjectAccomplishment` int DEFAULT NULL,
  `idConsActivityDetailsItem` int NOT NULL,
  `releasedQty` decimal(10,0) DEFAULT NULL,
  `releasedCost` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`idConsActivityDetailsItemReleased`)
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `consactivitydetailsitemreleased` WRITE;:||:Separator:||:
 INSERT INTO `consactivitydetailsitemreleased` VALUES(19,7,42,2,123),(20,7,43,2,123),(21,7,44,2,123),(25,8,42,4,123),(26,8,43,4,123),(27,8,44,4,123),(28,6,42,5,123),(29,6,43,5,123),(30,6,44,5,123),(31,9,45,1,123),(32,9,46,0,123),(33,9,45,6,123),(34,9,46,0,123),(35,9,45,7,123),(36,9,46,7,123),(37,9,47,7,123),(38,9,48,7,189),(44,10,45,123,123),(49,11,45,1,123),(50,11,46,2,123),(51,11,45,2,123),(52,11,46,2,123),(53,11,47,2,123),(54,11,48,2,189),(76,12,45,5,123),(77,12,46,5,123),(78,12,48,2,189),(87,13,49,1,123),(88,13,50,4,123),(89,13,51,5,123),(90,13,52,21,189),(91,13,49,3,123),(92,13,50,4,123),(93,13,51,5,123),(94,13,52,21,189),(95,13,49,1,123),(96,13,50,4,123),(97,13,51,5,123),(98,13,52,21,189),(99,14,49,3,123),(100,14,50,3,123),(101,14,51,4,123),(102,14,52,2,189),(103,14,49,1,123),(104,14,50,3,123),(105,14,51,5,123),(106,14,52,21,189),(107,14,49,3,123),(108,14,50,3,123),(109,14,51,5,123),(110,14,52,21,189),(111,14,49,1,123),(112,14,50,3,123),(113,14,51,5,123),(114,14,52,21,189),(115,14,49,3,123),(116,14,50,3,123),(117,14,51,4,123),(118,14,52,2,189),(119,15,49,1,123),(120,15,50,2,123),(121,15,51,3,123),(122,15,52,1,189),(127,17,65,1,30),(129,19,66,2,30);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `consactivitydetailslabor`;:||:Separator:||:


CREATE TABLE `consactivitydetailslabor` (
  `idConsActivityDetailsLabor` int NOT NULL AUTO_INCREMENT,
  `idConsActivityDetails` int DEFAULT NULL,
  `idEmployee` int DEFAULT NULL,
  `qty` decimal(20,6) DEFAULT NULL,
  `unitCost` decimal(20,6) DEFAULT NULL,
  PRIMARY KEY (`idConsActivityDetailsLabor`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `consactivitydetailslabor` WRITE;:||:Separator:||:
 INSERT INTO `consactivitydetailslabor` VALUES(24,34,141,1.000000,575.080000),(25,35,141,1.000000,575.080000),(31,40,141,1.000000,575.080000),(33,45,141,100.000000,null),(34,45,144,150.000000,null),(35,45,142,150.000000,null),(36,45,147,100.000000,null),(38,43,147,1.000000,null),(39,46,141,10.000000,null),(40,47,144,10.000000,null),(41,47,147,15.000000,null),(42,47,141,20.000000,null),(44,48,141,50.000000,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `consactivitydetailslaborreleased`;:||:Separator:||:


CREATE TABLE `consactivitydetailslaborreleased` (
  `idConsActivityDetailsLaborReleased` int NOT NULL AUTO_INCREMENT,
  `idConsActivityDetailsLabor` int NOT NULL,
  `idProjectAccomplishment` int NOT NULL,
  `releasedQty` decimal(10,0) DEFAULT NULL,
  `releasedCost` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`idConsActivityDetailsLaborReleased`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `consactivitydetailslaborreleased` WRITE;:||:Separator:||:
 INSERT INTO `consactivitydetailslaborreleased` VALUES(7,26,7,2,5751),(9,26,8,4,5751),(10,26,6,5,5751),(11,27,9,7,5751),(12,27,10,8,5751),(14,27,11,2,5751),(21,27,12,2,5751),(24,28,13,0,5751),(25,28,13,6,5751),(26,28,13,0,5751),(27,28,14,6,5751),(28,28,14,0,5751),(29,28,14,6,5751),(30,28,14,0,5751),(31,28,14,6,5751),(32,28,15,1,5751);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `consprojectaccomplishment`;:||:Separator:||:


CREATE TABLE `consprojectaccomplishment` (
  `idConsprojectAccomplishment` int NOT NULL AUTO_INCREMENT,
  `idConstructionProject` int NOT NULL,
  `idInvoice` int NOT NULL,
  `idDeliveryTicket` int NOT NULL,
  `idRental` int NOT NULL,
  `idDisbursement` int NOT NULL,
  `dateFrom` date DEFAULT NULL,
  `dateTo` date DEFAULT NULL,
  PRIMARY KEY (`idConsprojectAccomplishment`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `consprojectaccomplishment` WRITE;:||:Separator:||:
 INSERT INTO `consprojectaccomplishment` VALUES(4,13,207,0,0,0,'2022-08-01','2022-10-10'),(5,14,208,0,0,0,'2022-09-10','2022-10-10'),(6,13,209,0,0,0,'2022-09-10','2022-10-10');:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `conssubactivity`;:||:Separator:||:


CREATE TABLE `conssubactivity` (
  `idConsSubActivity` int NOT NULL AUTO_INCREMENT,
  `idActivity` int DEFAULT NULL,
  `subActivityName` char(50) DEFAULT NULL,
  `idConstructionProject` int DEFAULT NULL,
  PRIMARY KEY (`idConsSubActivity`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `conssubactivity` WRITE;:||:Separator:||:
 INSERT INTO `conssubactivity` VALUES(2,1,'Billboard',5),(7,1,'Construction test',3),(15,1,'Test Ativity 2',4),(17,1,'Test Ativity 2',4),(19,1,'Test',2),(21,1,'Test Ativity 2',4),(22,1,'Test Ativity 2',4),(24,1,'Test',5),(25,1,'Test Ativity 2',4),(26,1,'Test Ativity 2',4),(27,1,'Test Ativity 2',4),(28,1,'Test Ativity 2',4),(29,2,'Test Activity',4),(32,1,'Billboard',1),(33,2,'Test',1),(34,1,'Billboard',5),(35,1,'Test',5),(40,2,'Activity Name',6),(41,1,'asd',7),(42,2,123,10),(43,2,'Sub Activity Name',11),(44,2,'Test',12),(45,2,'SP-sub',13),(46,3,'Sub-activity',14),(47,2,'Test',16),(48,4,'Sub-activity Updated',17);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `constructionproject`;:||:Separator:||:


CREATE TABLE `constructionproject` (
  `idConstructionProject` int NOT NULL AUTO_INCREMENT,
  `projectID` int DEFAULT NULL,
  `manualIDConstructionProject` int DEFAULT NULL,
  `projectName` varchar(255) DEFAULT NULL,
  `contractDuration` int DEFAULT '0',
  `contractAmount` decimal(10,0) DEFAULT '0',
  `dateAwarded` date DEFAULT NULL,
  `dateStart` date DEFAULT NULL,
  `dateCompleted` date DEFAULT NULL,
  `timeExtension` time DEFAULT NULL,
  `revisedContractAmount` decimal(10,0) DEFAULT NULL,
  `licenseName` varchar(50) DEFAULT NULL,
  `licenseType` int DEFAULT NULL COMMENT '1 = In Royalty, 2 = Out Royalty, 3 = In and Out Royalty, 4 = Admin',
  `licenseNumber` varchar(50) DEFAULT NULL,
  `royaltyPercentage` int DEFAULT '0',
  `remarks` varchar(255) DEFAULT NULL,
  `status` int DEFAULT NULL COMMENT '1 = Suspended, 2 = Ongoing, 3 = Complete',
  `statusType` int DEFAULT NULL COMMENT '1 = Slippage, 2 = Advance, 3 = On-Time',
  `warrantyDateFrom` date DEFAULT NULL,
  `warrantyDateTo` date DEFAULT NULL,
  `isManual` int DEFAULT NULL,
  `idInvoice` int DEFAULT NULL,
  `idContract` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idConstructionProject`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `constructionproject` WRITE;:||:Separator:||:
 INSERT INTO `constructionproject` VALUES(1,null,null,'Project Setting Name',123,0,'2022-04-26','2022-04-26','2022-04-26','00:00:00',0,123,1,123,123,123,2,2,'2022-03-26','2022-04-26',0,155,null),(2,null,null,'Sample Project Updated',1,0,'2022-04-27','2022-04-27','2022-04-27','00:00:00',0,'My name test',4,123123123,0,'this is a remarks, updated',2,1,'2022-03-27','2022-04-27',0,156,null),(3,null,null,'Test Project',3,0,'2022-03-01','2022-04-18','2022-04-04','00:00:00',0,'Tester',1,273617532312,10,'This is just a test',3,3,'2022-03-28','2022-04-28',0,157,null),(4,null,null,'New Test Project',3,0,'2022-01-17','2022-03-14','2022-03-16','00:00:00',0,'Tester',3,123132312,10,'This is a test',2,2,'2022-04-10','2022-05-10',0,158,null),(5,null,null,'QA Project',5,0,'2022-01-03','2022-03-07','2022-08-15','00:00:00',0,'Tester',4,21312312312,0,'This is a test',2,2,'2022-04-12','2022-05-12',0,160,null),(6,null,null,'Project Settings',23,0,'2022-05-12','2022-05-12','2022-05-12','00:00:00',0,213,1,123,23,'Test',2,1,'2022-04-12','2022-05-12',0,165,null),(7,null,null,123,123,0,'2022-05-17','2022-05-17','2022-05-17','00:00:00',0,123,1,123,3,123,1,1,'2022-04-17','2022-05-17',0,177,null),(8,null,null,213,213,0,'2022-05-17','2022-05-17','2022-05-17','00:00:00',0,213,2,213,213,123,2,1,'2022-04-17','2022-05-17',0,179,null),(9,null,null,213,213,0,'2022-05-17','2022-05-17','2022-05-17','00:00:00',0,213,1,213,123,123,1,1,'2022-04-17','2022-05-17',0,180,null),(10,null,null,'Test Project',123,0,'2022-05-18','2022-05-18','2022-05-18','00:00:00',0,123,3,123,123,123,2,1,'2022-04-18','2022-05-18',0,182,null),(11,null,null,'Project Name',100,0,'2022-06-08','2022-06-08','2022-06-08','00:00:00',0,123,4,123,0,'Project remarks',2,2,'2022-05-08','2022-06-08',0,194,null),(12,null,null,'Project 02',2,0,'2022-07-11','2022-08-17','2024-08-17','00:00:00',0,'XYZ',1,5464768,10,'This is a test',2,3,'2022-07-17','2022-08-17',0,196,null),(13,null,null,'Sample Project',2,0,'2021-10-01','2021-12-01','2023-06-30','00:00:00',0,'ABC',2,15458487,10,'This is a test',2,2,'2022-07-17','2023-06-30',0,198,null),(14,null,null,'Old Project',1,0,'2021-04-14','2022-05-01','2022-05-06','00:00:00',0,'ABC',2,154848,5,'This is a test',3,3,'2022-04-14','2022-06-17',0,200,null),(15,null,null,'Test Project with Contract ID',123,0,'2022-08-18','2022-08-18','2022-08-18','00:00:00',0,213,1,213,123,123,1,1,'2022-07-18','2022-08-18',0,201,234),(16,null,null,'Latest Project',10000,0,'2021-08-19','2021-08-25','2022-08-19','00:00:00',0,'Test',2,15646974,10,'This is a test',3,2,'2022-07-19','2022-08-19',0,202,1245465),(17,null,1000,'Construction Project - Test',1000,0,'2022-01-24','2022-04-04','2023-12-29','90:00:00',1000000,'Test',1,1234,15,'This is a test construction project, updated',2,1,'2022-09-10','2022-10-10',1,206,1202174);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `constructionprojectdeduction`;:||:Separator:||:


CREATE TABLE `constructionprojectdeduction` (
  `idConstructionProjectDeduction` int NOT NULL AUTO_INCREMENT,
  `description` varchar(255) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `idConstructionProject` int DEFAULT NULL,
  PRIMARY KEY (`idConstructionProjectDeduction`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `constructionprojectdeduction` WRITE;:||:Separator:||:
 INSERT INTO `constructionprojectdeduction` VALUES(2,'Test Deduction',10000,3),(7,'test',1,1),(10,'Deduction 1',15000,13),(11,'Deduction 2',15000,13),(12,'Deduction 1',5000,12),(13,'Deduction 2',5000,12);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `constructionprojectlocation`;:||:Separator:||:


CREATE TABLE `constructionprojectlocation` (
  `idConstructionProjectLocation` int NOT NULL AUTO_INCREMENT,
  `idLocation` int DEFAULT '0',
  `idConstructionProject` int DEFAULT '0',
  PRIMARY KEY (`idConstructionProjectLocation`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `constructionprojectlocation` WRITE;:||:Separator:||:
 INSERT INTO `constructionprojectlocation` VALUES(11,2,3),(12,4,3),(13,3,3),(35,2,2),(36,4,2),(37,3,2),(41,2,4),(44,3,1),(45,2,5),(46,3,5),(52,3,6),(53,4,6),(54,3,7),(55,3,8),(56,3,9),(58,3,10),(61,2,13),(63,3,11),(64,2,12),(65,2,14),(67,3,15),(68,2,16),(70,2,17);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `constructionprojectteam`;:||:Separator:||:


CREATE TABLE `constructionprojectteam` (
  `idConstructionProjectTeam` int NOT NULL AUTO_INCREMENT,
  `idEmployee` int DEFAULT NULL,
  `idConstructionProject` int DEFAULT NULL,
  PRIMARY KEY (`idConstructionProjectTeam`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `constructionprojectteam` WRITE;:||:Separator:||:
 INSERT INTO `constructionprojectteam` VALUES(2,0,3),(9,141,2),(13,141,4),(14,145,4),(17,145,1),(21,141,13),(22,147,13),(23,144,13),(24,142,13),(25,0,11),(26,141,12),(27,142,12),(28,148,12),(29,148,14);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `constructionprojectvat`;:||:Separator:||:


CREATE TABLE `constructionprojectvat` (
  `idConstructionProjectVAT` int NOT NULL AUTO_INCREMENT,
  `vatType` int DEFAULT NULL COMMENT '1 = Inclusive, 2 = Exclusive',
  `vatName` varchar(255) DEFAULT NULL,
  `vatPercent` double DEFAULT NULL,
  `idConstructionProject` int DEFAULT NULL,
  PRIMARY KEY (`idConstructionProjectVAT`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `constructionprojectvat` WRITE;:||:Separator:||:
 INSERT INTO `constructionprojectvat` VALUES(2,1,'VAT Inclusive',12,3),(11,1,1,12,1),(12,2,2,32,1),(14,1,'Sample VAT',12,13),(15,1,'Sample VAT',12,12);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `constructionreleasing`;:||:Separator:||:


CREATE TABLE `constructionreleasing` (
  `idConstructionReleasing` int NOT NULL AUTO_INCREMENT,
  `qty` int DEFAULT NULL,
  `unitCost` decimal(10,6) DEFAULT NULL,
  `idInvoice` int DEFAULT NULL,
  `fIdent` int DEFAULT NULL,
  `fIDModule` int DEFAULT NULL,
  `remainingBalance` decimal(10,6) DEFAULT NULL,
  `type` int DEFAULT NULL COMMENT '1-item\r\n2-labor\r\n3-equipment',
  `idConsprojectAccomplishment` int DEFAULT NULL,
  PRIMARY KEY (`idConstructionReleasing`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `constructionreleasing` WRITE;:||:Separator:||:
 INSERT INTO `constructionreleasing` VALUES(1,1,24.500000,195,68,84,null,1,1),(2,1,575.080000,195,32,84,null,2,1),(3,1,50.000000,195,29,84,null,3,1),(8,10,24.500000,197,69,84,null,1,2),(9,100,123.000000,197,70,84,null,1,2),(10,50,123.000000,197,71,84,null,1,2),(11,50,123.000000,197,72,84,null,1,2),(48,0,24.500000,207,73,84,null,1,4),(49,0,30.000000,207,74,84,null,1,4),(50,0,123.000000,207,75,84,null,1,4),(51,0,123.000000,207,76,84,null,1,4),(52,0,150.000000,207,77,84,null,1,4),(53,0,5750.798722,207,33,84,null,2,4),(54,0,575.079872,207,36,84,null,2,4),(55,0,383.386581,207,34,84,null,2,4),(56,0,575.079872,207,35,84,null,2,4),(57,0,1500.000000,207,30,84,null,3,4),(58,0,750.000000,207,31,84,null,3,4),(59,0,1500.000000,207,32,84,null,3,4),(60,1500,24.500000,199,73,84,null,1,3),(61,150,30.000000,199,74,84,null,1,3),(62,450,123.000000,199,75,84,null,1,3),(63,250,123.000000,199,76,84,null,1,3),(64,250,150.000000,199,77,84,null,1,3),(65,20,5750.798722,199,33,84,null,2,3),(66,20,575.079872,199,36,84,null,2,3),(67,50,383.386581,199,34,84,null,2,3),(68,50,575.079872,199,35,84,null,2,3),(69,50,1500.000000,199,30,84,null,3,3),(70,50,750.000000,199,31,84,null,3,3),(71,50,1500.000000,199,32,84,null,3,3),(72,0,189.000000,208,88,84,null,1,5),(73,0,12.000000,208,89,84,null,1,5),(74,0,123.000000,208,90,84,null,1,5),(75,0,123.000000,208,91,84,null,1,5),(76,0,30.000000,208,92,84,null,1,5),(77,0,5750.798722,208,39,84,null,2,5),(78,0,24.500000,209,73,84,null,1,6),(79,0,30.000000,209,74,84,null,1,6),(80,0,123.000000,209,75,84,null,1,6),(81,0,123.000000,209,76,84,null,1,6),(82,0,150.000000,209,77,84,null,1,6),(83,0,5750.798722,209,33,84,null,2,6),(84,0,575.079872,209,36,84,null,2,6),(85,0,383.386581,209,34,84,null,2,6),(86,0,575.079872,209,35,84,null,2,6),(87,0,1500.000000,209,30,84,null,3,6),(88,0,750.000000,209,31,84,null,3,6),(89,0,1500.000000,209,32,84,null,3,6);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `contribution`;:||:Separator:||:


CREATE TABLE `contribution` (
  `idContribution` int NOT NULL AUTO_INCREMENT,
  `contributionName` text,
  PRIMARY KEY (`idContribution`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `contribution` WRITE;:||:Separator:||:
 INSERT INTO `contribution` VALUES(1,'SSS'),(2,'Sample Contribution'),(3,'Pag-ibig');:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `contributionhistory`;:||:Separator:||:


CREATE TABLE `contributionhistory` (
  `idContributionHistory` int NOT NULL,
  `idContribution` int DEFAULT NULL,
  `contributionName` text,
  PRIMARY KEY (`idContributionHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `contributionhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `costcenter`;:||:Separator:||:


CREATE TABLE `costcenter` (
  `idCostCenter` int NOT NULL AUTO_INCREMENT,
  `costCenterName` text,
  `remarks` text,
  `status` int DEFAULT '1' COMMENT '1 - Active\n2 - Inactive',
  `archived` int DEFAULT '0',
  `sk` text,
  PRIMARY KEY (`idCostCenter`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `costcenter` WRITE;:||:Separator:||:
 INSERT INTO `costcenter` VALUES(1,'d98a3361f394add155c2fa5cf85eae5e850a11f9d3a51e9fcfb1c0c7c0978330f3f46844e7d2fcafb84c2f3454de4756789512668028a86ea46578dfb034d57eNdQG2iFiqq75cT6AqU6tQ8wbNmiGPvzJf72I/YZtqH8=',null,2,1,200519200114),(2,'e66281a2c4a1ba5e0db342f8e3bb901db9ac810dbd379550b2608caa193d2b03e782e3505eee07ad5e3e116172e19d00a87421b93d31900ec2516f1fe216b5eewoCAimg5CCQdsuYR1fp75sfd9E8VGy1ia5l1iuEEJ/FBhoUDxbeDEe/PTatf2Dw2',null,1,0,200519200015),(3,'a27405fc90b78c6e2b12e9e439db8e93c1692a9066976f1af123d7b5277a4120869786a7901c45503bbe356fbe56a1c3d6effb92189db7149f7633b4828a33a1WuYZIMsbdkmJw5br8PSc+yNwkRcfVpLpsWngXeK/0pY=',null,1,0,151605180166),(4,'3e370f5204eb0f3055bd6ea868e19f4fbc554e07909066cd97dfd625625bebd060545b0b5bc8b3fbab3a977453f7917df4d7f3af84c15241267d47b7216ca48c0Ja1oDveY66D3neqT9KUWk2/tAUV1xfogIKWquWKt2c=',null,1,0,190113161283),(5,'f8f9029e7f1dcdc323cffdc4d52db939e0edff11f8bfd976bbcecea470075fec328145fba9a2bbcc8392d37faa1bc4129fba9432252d5f8d8b1220900cef86dfe/Gpy+dK6tp2yVlHiLUPTzc0UtVauwUV9QPs5VQq3A3vcBBgkZOttI/2KQUoIAff',null,1,0,192514200169),(6,'6e20274058d6655c61947a180d21970d4ade532e883379feead2f46c41c94fa93973414f8da881ea6d2a1720dd8f0a87596340175fcc656bef0007336c5c3615y+LR+Vi+q0SdHa7Mx/tkTkHD5ec6B8H91LBfrwq/xv0=',null,1,1,80019211612),(7,'8e629762ebd0a284f8adb7c94949ae13049e8a9b5ea9689788a74af8232c83bdea23b3e78a60960fd53811da778dcbd2e92c68b7ab8327a75a95e6336f5be8c30WS6cjXXvSmyebF5ppg80NIuO5bNRcd1oSiD9kCA4EE=',null,1,0,80007181593),(8,'12bbc5d480acdd34806256284106151dc964fe4ab56b104621d402bd2b43ad49c65acbe5b6dc34f5af7022970b5dcc5e63a715f32f46096960087d2f06cf0b74xYYYCoUqoYkC/Z6k7qkQlXERKIEVke4xcNMxAYkRExw=',null,1,0,140523000399);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `costcenteraffiliate`;:||:Separator:||:


CREATE TABLE `costcenteraffiliate` (
  `idCostCenterAffiliate` int NOT NULL AUTO_INCREMENT,
  `idCostCenter` int DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  PRIMARY KEY (`idCostCenterAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `costcenteraffiliate` WRITE;:||:Separator:||:
 INSERT INTO `costcenteraffiliate` VALUES(1,1,4),(2,2,2),(3,3,2),(4,4,2),(5,5,2),(6,6,12),(7,7,12),(8,7,12),(9,7,12),(10,5,2),(11,4,2),(12,8,6),(13,8,5),(14,8,12),(15,8,14),(16,8,13),(17,8,8),(18,8,2),(19,8,15),(20,8,4);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `costcenteraffiliatehistory`;:||:Separator:||:


CREATE TABLE `costcenteraffiliatehistory` (
  `idCostCenterAffiliateHistory` int NOT NULL AUTO_INCREMENT,
  `idCostCenterAffiliate` int DEFAULT NULL,
  `idCostCenter` int DEFAULT NULL,
  `idCostCenterHistory` int DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  PRIMARY KEY (`idCostCenterAffiliateHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `costcenteraffiliatehistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `costcenterhistory`;:||:Separator:||:


CREATE TABLE `costcenterhistory` (
  `idCostCenterHistory` int NOT NULL AUTO_INCREMENT,
  `idCostCenter` int DEFAULT NULL,
  `costCenterName` text,
  `remarks` text,
  `status` int DEFAULT NULL,
  PRIMARY KEY (`idCostCenterHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `costcenterhistory` WRITE;:||:Separator:||:
 INSERT INTO `costcenterhistory` VALUES(1,7,'0be5afb10a7f70de51826f1e5421fcfb2d8af12aadc4d29347e2a50a20aea43310fe7d0be5cc8f523434f3790ad78a3c218184a5990050e97c71c3088bbb3232QjpPRJB/ts5glzuQ7pxMIInI31dcZkoMl/4tz2ABltY=',null,1),(2,7,'8e629762ebd0a284f8adb7c94949ae13049e8a9b5ea9689788a74af8232c83bdea23b3e78a60960fd53811da778dcbd2e92c68b7ab8327a75a95e6336f5be8c30WS6cjXXvSmyebF5ppg80NIuO5bNRcd1oSiD9kCA4EE=',null,1),(3,5,'f8f9029e7f1dcdc323cffdc4d52db939e0edff11f8bfd976bbcecea470075fec328145fba9a2bbcc8392d37faa1bc4129fba9432252d5f8d8b1220900cef86dfe/Gpy+dK6tp2yVlHiLUPTzc0UtVauwUV9QPs5VQq3A3vcBBgkZOttI/2KQUoIAff',null,1),(4,4,'3e370f5204eb0f3055bd6ea868e19f4fbc554e07909066cd97dfd625625bebd060545b0b5bc8b3fbab3a977453f7917df4d7f3af84c15241267d47b7216ca48c0Ja1oDveY66D3neqT9KUWk2/tAUV1xfogIKWquWKt2c=',null,1);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `custcontactperson`;:||:Separator:||:


CREATE TABLE `custcontactperson` (
  `idCustCP` int NOT NULL AUTO_INCREMENT,
  `idCustomer` int DEFAULT NULL,
  `contactPersonName` text,
  `department` char(20) DEFAULT NULL,
  `main` int DEFAULT NULL,
  `sk` text,
  `contactNumber` text,
  PRIMARY KEY (`idCustCP`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `custcontactperson` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `custcontactpersonhistory`;:||:Separator:||:


CREATE TABLE `custcontactpersonhistory` (
  `idCustCPHistory` int NOT NULL AUTO_INCREMENT,
  `idCustomer` int DEFAULT NULL,
  `contactPersonName` text,
  `department` char(20) DEFAULT NULL,
  `main` int DEFAULT NULL,
  `idCustomerHistory` int DEFAULT NULL,
  `contactNumber` text,
  PRIMARY KEY (`idCustCPHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `custcontactpersonhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `customer`;:||:Separator:||:


CREATE TABLE `customer` (
  `idCustomer` int NOT NULL AUTO_INCREMENT,
  `name` text,
  `email` text,
  `contactNumber` text,
  `address` text,
  `tin` text,
  `paymentMethod` int DEFAULT NULL COMMENT '1 - Cash\n2 - Charge',
  `terms` int DEFAULT NULL COMMENT '1 - 30 Days\n2 - 60 Days\n3 - 90 Dyas\n4 - 120 Days',
  `withCreditLimit` int DEFAULT '0' COMMENT '"0 - False\n1 - True"',
  `creditLimit` decimal(18,2) DEFAULT '0.00',
  `withVAT` int DEFAULT '0' COMMENT '0 - False\n1 - True',
  `vatType` int DEFAULT NULL COMMENT '1 - Inclusive\n2 - Exclusive',
  `vatPercent` decimal(18,2) DEFAULT '0.00',
  `penalty` decimal(18,2) DEFAULT '0.00',
  `discount` decimal(18,2) DEFAULT '0.00',
  `withHoldingTax` int DEFAULT '0' COMMENT '0 - False\n1 - True',
  `withHoldingTaxRate` decimal(18,2) DEFAULT '0.00',
  `salesGLAcc` int DEFAULT NULL,
  `discountGLAcc` int DEFAULT NULL,
  `archived` int DEFAULT '0',
  `sk` text,
  PRIMARY KEY (`idCustomer`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `customer` WRITE;:||:Separator:||:
 INSERT INTO `customer` VALUES(1,'659f49276b7619cf4d1099cc5a2f24d0acd4f79e68c518744a7479d556ae6a80aab4c1661f6e742bdb8b3c28ed586ad778ba577545ba3a87345c53433588b682JDoYvx4wbKaTpI58cpMppHDDYEpldoG+e/wot+SRTu8=',null,'01e5fde15988a10c07de9c70b4c821ff7611307039d9194a924eb665b41a0dc50ae4b01db2ab4f39a5cf09d952a0dcbd7e3096f60f9e17bfd939c2763811de1fVOSvJa3XldlaYQha091Jaye1NkaSJrnW2libKE7mw2M=','272a52c0e624215651cc9d62002115a7a278d6e100b3c5db5208cdaa5bc606ce0ec954ad492ce82f1ac0b7bef0906b220f79fb4586ac3b21bceaae4d0257c31eU4cvnhHDQRWbvSzkD0QYNfWcEpcE+W7hnSeA7wlAAic=','66ebc338c06fbda354fe2cde83f3dff9da666607dea8317b13b96a22101c51e67a60dbe5f9ddbe6f98cf46fd76da4f543500a03e8a506469028cd4398827f31dkYWLycGgCeOIAIhu1KzA/f97C+6r6kr5gyDLaJ6Hn64=',2,1,0,0.00,0,null,0.00,0.00,0.00,0,0.00,null,null,0,130118110135),(2,'18e7524f657d6f4baa1b207281f86a0d61a1aa3fff751242633a20912ab023ed4e55344061687822d77d72e7a82b1d1cbe26bc65c7238a07fdbfc007f53833cerdiFNhWolTF/DkFt2KVfi9eEOUPK0pGRTHrhr89cefc=',null,'e56dcc5e3bc8d859634e902e6a5efa5fbeec577b11c00dd2eb0e29cdc5f4f1b48f626d0ce455bacd417a2a3241d2a1c808a712bd8e67ab7aa139f64f59f690bdVwlTyhHwg+9Ki6inIl2Pn27Ixtypp6ArZfW7A8rjLtA=','c0d96c4741af65ff1e283b92c481d231444526a210ee3d68e0390b4c529ca5f68979336f0cf31108cd557bf28afc622d1587f249d5f9421b6c1e4a31c4ca61e6Y306DXGZLzXVn4sERTAkMTnET4hrsK/wzt3oHAdlW+pAjnvt/adImHjwdqKNpfPF','3043bbebe6761be0079675975459418bfdea4425c7f51a2767f48797ef786db93e2c12af182ecdcc9fa3e53a778d7f65db591d800de8aa5c48fa49866471286donP2sWNB5FzfHrT6oNtCKykNDxaQf9YnmGlIhVt70J4=',1,null,1,10000.00,0,null,0.00,0.00,0.00,0,0.00,null,null,0,30818091987),(3,'fcca9ff4d84afeba86c71b70bfbf764311e53e63db0c432a565ecd66f6cee077a74b383c0adc46a5988543497f3fce3caff2eb02f2fda4cf886b95c5606e4d0fFmOKT8y6the8ko/V5M5edLodYIGNmZjbMFzg0Z1yt8g=',null,'4a8af4711d8fb719f984c7924251822a4a216ebbf1b42ee4bdfbdc083c4f8ad3b68dd2f94242271b8956759a32c98e3078604299979f2a49d89161bae2f841fbIvf9i44D0M0+BFrT91JATRy/SetwC/t8yuQFCbQ8BU4=','1dae9307688779c5e59320a1d768a886f635a702309481b6feb940599313e89a3b3c9ef5286a27bcb1f0f5a9ca580e844c96a5e9851a1fe07329bb651f0d7771T9FvskPa+MtKsblH3CAHyu9CzIil00rom7AWoFdf8WgcwmVd7pFvsAN4hDU5esb+','1665869ae307a93768349e4d0838035a0e27b497075b1671db678253cfcc33045e3b33db4fc3000b2355366866130cc0273d7d7302d4072befa23cad77d7ca5bCToRg9g0s9AzxMqpXnvc3IuevY/csS94ujrH5kgbVjI=',1,null,0,0.00,1,1,12.00,0.00,0.00,0,0.00,null,null,0,130111130124),(4,'32cac3ad0cc91d835e7dd6d346dcf51304d9318663784c783f03473837c69cfb52255791893702a04352bd4c92453756a11a10155338a06b04ae2603f692869bfQLxsJJ6XHgJMXhwxRWckPcYDX/WS6zZg1jBkqHWfdk=',null,'952148a59169e3f8ea3e543c5fd407ae498b4998e15e3356914e19d2f298683bdedc93a3455ed1b1ce9766cafae789892c736feb1bd4d166b54d6f217c3e996dO5MYLJC4ZHUDMJ21A18/gsJdTa6jyZZyWK0OnpvcCPg=','65a38b67f3054ded11848ea4d63d7941a0a443f8dda20279e3bfb2656e789b0dae108ed1183c96bb545f6d1db3c6c06829f41bfe15624e84bb59d29bb85e0aaecfHa+Tv6ki3Eosl/ndJ5JEb+bKFIgOy6hpN+KweWB0A=','07f22ac0e61a894d67ba3a2a21d0bb4447241415e5b1d97416361b0ac9c9bd96edf9a7d7b5549b95a9a0174162958579d0bb9720dfff92829401def9b7ba1e8eIDcj+snDbqtqn5ri9Zz4OL+zRPLOYx1QcONb3N9mUN0=',1,null,0,0.00,1,2,12.00,0.00,0.00,0,0.00,null,null,0,101508140026),(5,'58aaf3a3e3ff5e28529d341f3ee77e6e4509c343518d67df52b9cd0b29c952f70bb6c3d5196668c32e7c8c3f38793e1a0e4de7b592f4c6413fbc6d51b30fd566v+buOllUY13qWjcRgSaly/hNs12FBV3+J9/N1y9uVAuvQjA6IT1zFgqVLYdhiRuo',null,'36cf6e1b17ac1ca79ec52b910ff4ad2655bc19363edf78497b30f47c96d9aa43928777cfa61394b3fadd7d6e391535682f82ccb6a49e946f414d757d06df3cebah2exseRerZ1LiNMZgZcvk4Pjy+YnpPHIo/Cs7JPr/4=','1ff6307172bf580853e1db261066a7e2651bb923130c6d7ced8186125320d59e45553bf87413d2e006eec90ce278ecf67c56b4fc21f0749dcf3b5ec463a3b75fbzDZenqXnESXbj6L3DO5B5PYYGL2wmVt0bPRz2KPlZw=','bc00594964166675880ad2e525a8d6cce5cad22fa8616fed05f532d91a196e382090efbca61a52aac20393d45db00ccb0c9946b89bed1358d2201666365e7d62w4/2ZcLtl6pNqTXRvHmImUFdZu7jSkF1ipIdkKKuCZ8=',1,null,0,0.00,0,null,0.00,10.00,0.00,0,0.00,null,null,0,32119201586),(6,'ca58cf5e0700e233592b6fa83dca256cf8373cccf3097447e1bc577b1dcb9446e0abb598ac630bbe57229d35f5f4e6663c5991dcb487c6dea64a23f89d978e15LiqcW+Zjicu3BUsXyLw8gTF1z9LadsDEazC+BpMl+lA=',null,'ecd3f11d1235504674020970387f13cb03ba4f77588c3029dc5131475e1be41074590e479611d7c9843a24d027461ce26071f62d408a1978e2e56f4985a45db8L01Aqd9UQ1QYwnIz3GraEHu1f75W8WweyeIIaw8hlzc=','59569f0177f52be2e37c1198056d4b751435940fabfe2ae3a0646c427bc0f9f5d0371a3335a27a23573108b2347566448353296609740ca6ea6bc7bbbf70e93eMxYD5hCq+VQOUn10P427O3iV8v1B5gF4wc64tDA2Vdk=','c94470cce17a85b1daf3e5df1212a88413aa6f826fd27cc834e0121a8a4f580bbb2c21fa2c58f108c33d85272b36ac42ecc809be79a97e83aa3eb2752a947fbdWqICHqzC4LTgy/uaeFGEzHKDLGvmpHDhmMPM/MGGDVc=',1,null,0,0.00,0,null,0.00,0.00,10.00,0,0.00,null,null,0,102114090221),(7,'d482f9d16fb2707de49593e639742798de766e8eb372800da90c8e18533f2b5bba5c11c9bca433005e69f309e5de64588efcbfbf34feacb90ac3b4966e21a7c4X8+2UhqZcz+QVOMWKW0+1M6zpuRjEXUS6UnZlBiXNrQ=','2071fa77d39c5399a29cfd0b32e50b800c083c9b2860f79060b533f3944b78d9dbf6c3ccf84e5e71024e17ec48e2597395ef7320b1d1a936e69c89b354401f4dAOt7SYeG03SgSdsmV4O6xrY2yyujrfTzAdP/5wrXSKw=','cbcbf73a615f15f0bf6e4cb263592d75c80bdbb2b3e971b43f3339ba491982ee2ff98127dd9321d7dfbb34e18c0bff2129619c90a6743f3c652fdc5d18ec0ca9tjA9eH08KKN7ZpLPw0x89T/hnFiFOsw+way1NN18ucU=','2729adbe9e598a44442056a56aa9cca54ee96db802038cb60f3862a21555cd13c9db82eaf5c75dffcad345529f8a1321ebcdc311905dab164f21781bf1136404dBgm8fSGGp3wIelImHIN7xW9spPHpjnOLBC+qmaa+Dh1GkYnMMaGwhuFVNdJrHyQ5lLoh26+MKtjyYg1HfDSkg==','058bdd84f35035e54081fd7a46ae9bb222a05bb7ce5b19114910f8e741afbd41da5434f330f57ddb817bfed4a0dee77a4fd4be4e52c75a787ca9f2524eb00341Q1LWxOhakxhdt/ywULiWi9HmejeEL7QGFGk9q3/yMvo=',2,20,1,10000000.00,1,1,12.00,0.00,0.00,0,0.00,1101000,null,0,110304030168),(8,'8843d0044d599124f1a3470b75f6bb1eed6ff31a60ddb50c075687fe12d7a58648a5fb84d1914c7aa4421881be55e1e1de55c0fe31b3a7949e7f2344ec06e963/oARreONWneMaA4BfEUVX8jDXFazr4vcWaFBAQsF7mA=',null,'1af99d03cc0310acdeb7e85c6e346b84dd6eb3b5a6c09083db575aba2f10d00607ced1b28ab85822c14b333a4467df4977f58eabeeaef1916f9e96274f2cd32bzpDFRsVYq2D5eG9Nww7blz1Hn4Yy4e20CUZmRDi7430=','a910c281deb9a2620fd5c24f4de298d88e140195b53533100cd4b135a7b1d26d0551f93e98ba4e87ee5400a209c62a8d6fd683b7facbf120bd98229f799a4893rDibNqNhEtoDm2+DNTLt2tOfb6vNrnnmWkoL/xnuaQ9QZAJ7a5eSVRajhI5JE6ykS7uLBzCP8MmeTyrOoHBSiw==',0,1,null,1,5000.00,1,1,12.00,0.00,0.00,1,0.00,null,null,0,190113161212),(9,'00b38508390ad673802288ca3496a69cf2f77e931b7734651e19af391bb3606274d275ab9baf26ced432c74b7773ed5ac3c821130f94b028f4938c3433131b64FNYt9sQqnsldm7N+wez4Hx6IJl4rMrpHoZIp7NYkh50=',null,'3a604c0bcaa3a49d2d0494151b42d5846f046967013c236b39d4c6c281520999ccc0e861570f95f9ed68bdb8ed21b25ec743dd2767d9d46f2156fdbbc2c41911Xffw16UJDB9owfr9EUMGXiT7uv51y/DBtBZrexxBRLM=','000d4cc106b7adc263ee937a4e9a1be50e82fb130fd6657792ce68e5868ceebc66f0ffa06df3dae3aad7c883c1de3fb9cb5edf4d11304450e1486597b5f81f77Sh4L0XknEFKX0rkNbGyys5IuZoRoiJAoyY9cWDjUKznfSrnX3HfAp21Z8+bx5pJV','f7f3d9817bc04abbc835dcba38f245455e3ed1cda9997c9dbacf312de835357a1f93001005f6be9b629b53465a7b05730626f09d9ab4ad354785853d0ca76e74UvI4kw6oAWbl3jOjb5LZoHmJm1D4ZH6c9H937tGarro=',1,null,1,100000.00,1,1,12.00,10.00,0.00,0,0.00,null,null,1,110013011885),(10,'96f099d9c2988b8781a0a29f2c3faa487dac51fcbcda1f18fd2a7af4e3b52653f2ba2fac57382912d95e987bcc62e789922a400df8791300150eb0caa26bf06cjvIz7/l+292tTpX1Y8FoyJCGX3RGFrvQx7jvIXN0ovM=','e5947f01caed49d7adc10226a7b5e20a309239e05a629a57d9c04ec15406b44d35ec24e3bc4c96e12082679cc88c68e89b3d0ffb14618baad37f71bb92f8f788vUS8MYKJTrzx6Ulxp4BGbmuPcsTL7NsFer6zmTKLzxBmj3vPDBK5ocD9U6TqtD2K','2245d1b3cece4fb43e090ee94dc92eae73ee8fd033df84418969e18c949d64c80fc6b388a98ca55824a6de0274625b5ce0b9d546089f22a35018f8ee42727c17KX6GdCzGyNThSG7tgYKWBv0N7qcM7UZl6YtPRB5NiKE=','38da5c54a16702139c7d95e4173517d49e5df08fd2f76380277b680d23cc40d1dd4453daeef51fe70f8b693d275ca4684f37def5be16aa4f0c97ff5dfc10a36aba7Q1QVVjORxNemX3reYHpvqQr5e/w6gCeN215Gk63g=','bff60a8324e3d94c89f80bc92a76567a3754aaaf299e130491381bbb8d2cfbe39fc9cfc1c3af9e11de59f9ab5ed7f21a4c5c49ffe18652a9582964944901e701lCB1o8WRbC+lNLQi4DKhmtMW/CKFb3BVDhdtnRJuKK0=',1,null,1,100000.00,0,null,0.00,0.00,0.00,0,0.00,null,null,0,110013011859),(11,'74757ab60e59b0a71bc05d0d437f8ae7310c27f261bb710f120952fcd1ab59122e6080af326d0b0082535ee4481bae924d708f7181ff0e0a077ecbc1b8adeb4cGzwfgTBQgw1brToW0p3HAn7/+Qi/3TOCTUd5skWJOQM=',null,'7e0707630d2f6c698be0c48b164d73ff695dd9efff91c556481050f052176be863d73b5e46b051d43ea54863bb585516d2448f69dcb53dbca2a8dae620820f22ryiV/KKf+5kQGSt4Z7qeE/O7PV2cU6GnzC8rT7HrkZs=','09b81e296a1e78d062be6150796e2a6ed234f0d89157296f1e6a7f28572c95944ad2630fd75f5a66ebc7e9c6d79b18b2b3f736992c79bd3a524a0d3ce6ca7f0eoC3oSywcKynTCZx51atoT1k9oXVezwXUKnKpV6OKjo0Bj5NNjiYTF1M2FaYGYGUj','ec5bab644e82885219d0bd765b0646f66e70c4b867d57b34a48af7b6e767943795d80ccf20d2929d25f52d950efe06d06808946eddfa7adacd425fe36bf880e9JA/ZGnG9TXf9bv1mqhOHMLEWXhKN4P+ej5Pt/HQ94AQ=',1,null,0,0.00,0,null,0.00,0.00,0.00,0,0.00,null,null,0,170100032158),(12,'1419423964003d34c0ea60ebdfe50887590aac69bb6b30a97e7978f8ff35716ff05e4ddf8d7fe51a0baf72217d41ef3ec78871a4832f41e30c2c9827d219a8b43T28DDSTgq5J4sPdrLash+NdN4UZt5ZLrz68qfoCkk0=','3b7d5f40ef0a2649174bb529147648ae8d98e9d0c6361a83110ee23cd7844eaf80393fad1b018d1dc3ba0abc2a376ff460783c64875aa356e9ac6a509e06a262BTugakgLApMS6VRs35EhGub2dOu8dV7o7WOX0YHxK2fdqNaXzbZwki4PVtowy2hq','9f9ab8ab168154184a9bc060d4c35fc411eea925afac2aeabfc58c65eedcaef9cf13fc158cc9b6f7c0ae158d5aa55c177e5edaebd25870241794f0d90889b1da4hx5Fyu/uzuC7w1SSdcz/HpO8v5n5i3mJnpBQi90cZg=','1a351364e598bdf559299e60a9e02c6f1244b87bebd8cd659309d214f146f5ad2b622777c29f3270cbd9321af34a261acef003288d17191f706b5d1edd916709lb8v7YLqjriyj5mTImcqmp+Ub2C2I0+GvreoQ4l4pXw=','c1e082c5d6e21ede2d6488564563379ddda4658fbd17ac580183aac28d946f189e004bcf8214588fc5964b9796743a66b5dcd910ff3b870611ac3cd6f33def42HW4h+VquRUPJAyPISjiNX2Ask6t6Yld/josRkyJuUag=',1,null,0,0.00,0,null,0.00,0.00,0.00,0,0.00,null,null,0,140523000332);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `customeraffiliate`;:||:Separator:||:


CREATE TABLE `customeraffiliate` (
  `idCustomerAffiliate` int NOT NULL AUTO_INCREMENT,
  `idCustomer` int DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  PRIMARY KEY (`idCustomerAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `customeraffiliate` WRITE;:||:Separator:||:
 INSERT INTO `customeraffiliate` VALUES(13,5,2),(14,5,5),(15,5,4),(19,7,2),(20,8,6),(21,8,2),(23,9,12),(24,10,12),(25,10,2),(26,1,2),(27,1,5),(28,1,4),(29,2,2),(30,2,5),(31,2,4),(32,3,2),(33,3,5),(34,3,4),(35,4,2),(36,4,5),(37,4,4),(38,6,2),(39,6,5),(40,6,4),(41,11,2),(44,12,14),(45,12,2);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `customeraffiliatehistory`;:||:Separator:||:


CREATE TABLE `customeraffiliatehistory` (
  `idCustomerAffiliateHistory` int NOT NULL AUTO_INCREMENT,
  `idCustomerAffiliate` int DEFAULT NULL,
  `idCustomerHistory` int DEFAULT NULL,
  `idCustomer` int DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  PRIMARY KEY (`idCustomerAffiliateHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `customeraffiliatehistory` WRITE;:||:Separator:||:
 INSERT INTO `customeraffiliatehistory` VALUES(1,1,1,1,2),(2,2,1,1,5),(3,3,1,1,4),(4,4,2,2,2),(5,5,2,2,5),(6,6,2,2,4),(7,7,3,3,2),(8,8,3,3,5),(9,9,3,3,4),(10,10,4,4,2),(11,11,4,4,5),(12,12,4,4,4),(13,13,5,5,2),(14,14,5,5,5),(15,15,5,5,4),(16,16,6,6,2),(17,17,6,6,5),(18,18,6,6,4),(19,19,7,7,2),(20,20,8,8,6),(21,21,8,8,2),(22,22,9,9,12),(23,23,10,9,12),(24,24,11,10,12),(25,25,11,10,2),(26,26,12,1,2),(27,27,12,1,5),(28,28,12,1,4),(29,29,13,2,2),(30,30,13,2,5),(31,31,13,2,4),(32,32,14,3,2),(33,33,14,3,5),(34,34,14,3,4),(35,35,15,4,2),(36,36,15,4,5),(37,37,15,4,4),(38,38,16,6,2),(39,39,16,6,5),(40,40,16,6,4),(41,41,17,11,2),(42,42,18,12,14),(43,43,18,12,2),(44,44,19,12,14),(45,45,19,12,2);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `customerhistory`;:||:Separator:||:


CREATE TABLE `customerhistory` (
  `idCustomerHistory` int NOT NULL AUTO_INCREMENT,
  `idCustomer` varchar(45) DEFAULT NULL,
  `name` text,
  `email` text,
  `contactNumber` text,
  `address` text,
  `tin` text,
  `paymentMethod` int DEFAULT NULL,
  `terms` int DEFAULT NULL,
  `withCreditLimit` int DEFAULT NULL,
  `creditLimit` decimal(18,2) DEFAULT '0.00',
  `withVat` int DEFAULT NULL,
  `vatType` int DEFAULT NULL,
  `vatPercent` decimal(18,2) DEFAULT '0.00',
  `penalty` decimal(18,2) DEFAULT '0.00',
  `discount` decimal(18,2) DEFAULT '0.00',
  `withHoldingTax` int DEFAULT NULL,
  `withHoldingTaxRate` decimal(18,2) DEFAULT '0.00',
  `salesGLAcc` int DEFAULT NULL,
  `discountGLAcc` int DEFAULT NULL,
  PRIMARY KEY (`idCustomerHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `customerhistory` WRITE;:||:Separator:||:
 INSERT INTO `customerhistory` VALUES(1,1,'637116c176bcfb31ecf94e9174e861dee7d9ddb83800862007be7fbf26dfa808412b56b7b64c1e5675cc0880edbffc2a130883fa4b2343ebdc2542964fa101f4c+jR0/rnNff2AUSXjNEjflHomw8+QAAxIM4KipAgwjc=',null,'427c574437699d900a9df04aacf1721e08d6cc1f21c2cbe328ea0c893bfcdecf7de6b4a05a9e58f1d990da1c6a68f8ef160d17dc859c1cdb8d585587eb02eeechveHYO7+JjavL0BNHi16WdYIsO0qHZ7QGUkz3bQDGTI=','0f8489a4ff754ddfd6aa2ee428de50c777299abcf5f9540dc68278ad170418e1011514d5fcc02812eed2c848c61e2847449ebfa5d68a9dff34cc83b77d059410kli1d1G99iSSLPj5/Rp/XUP6uqSTJ8TxIJW3sML8bH0=','252e59f1f3bf9720056e1ff39942dbd73edc067f508f93c73a8fadd37672565f6396155fd2bdc7d877bb146732ea3e4c2401c2a91caeddd741bf9202e07e5939LUqo7yW755GbtLccKvlgL/a9X1R7Ia7AVve+oIbiHlo=',2,1,0,0.00,null,null,0.00,0.00,0.00,0,0.00,1101001,4102001),(2,2,'d635cfd59d01904b30a8116319b0626e1d602f574ea668e4c09131db32715d245257486e838b19300ffedae3437cbaf574b34565a39bc321d350858dd233863eAhWJi6pGOvSOUAWWbhLJKbJAjChH3BkxGWo4hcudeJvlVZ5BMI2+0KjVWkLOjGDP',null,'a3673e3821cc34e0dccec20f2b8cf76fd51581f943cf61eeac28dab9d275b3e2124f57d849aab1142f65c46a1ca6b774dee9ad1f59193ab0d8148256f9776456avLXMoiBNXGp321/dYfhbWRNzsFt3vVk/ytrVOO6qvE=','228da1027341f15bd7a17c793bbb7ca533c70d79d08505ce7cce9d0a640038f2342b8e4513e9cc1dc6ab9329fd50aa06240d277b02087c0e21790b5e6e61fae0wgl5EghFMwKLpbNUv0jGvwEP9qasrJdaX1JJx+/1XFo=','95f5973cdf29327da1788d22f0d3c22e11431ac3586d90a91213090502986a6cd625adb22aaf28c99460274a7f75f96cf27188d01bcf71f25305af2014c45da8bsesYlMwp0A7sioaeG6fpxypOpDPZLV8jnxGgaHY2ug=',1,null,1,10000.00,null,null,0.00,0.00,0.00,0,0.00,1101001,4102001),(3,3,'57a74afc2a31eab24a17286468c8657ae90c33d584f24f5be58f12eddb42b845acc97ed8f8e66028f61a570dd70c9e163b31cd7da15339bbe43bba81acbce4bbHvn5SKKXQy3t4XoRhrpeV35KgRpM2lrlj8YI8XnAkYx6Sl0bci+RFBVEo+TvbU3O',null,'b4d4eb97e90a0f2da046f0b5c274085d6151df40d2a5221563a8e5ed7f55fcea26a5ab5af3c9c25ff953dfc2fc276c260ca0163beaf233058b5bc4e6f88d46fbHDWuPJxb0+XNjq9hfaCAgHXqBSYdFDISQOsqsDAdiX4=','5d4fb924a8c60f9c26e080248a41b2e2e3952743fb8458cb0aa233f0650d69a98afe3cdcd3d94722879aae198bcc5cc5270e77364c1d6b10db5adf3576ce8c5dww4a41BplB9ARjVNZJ6D71kWJt0P+2aNAJaB/EYQTdU=','888748f669bd9eda08484e981cb1623e2013c3ceef16be6aa7f2c0f7f0418d0edbf31f70f9ca31a8dea04e2c3feb4da175c702e6f01dade311c8a584dc385cdfSUHPfKQeyFhXosrxTcfjxfCo1Tk/UZ/Avfl0l210GlU=',1,null,0,0.00,null,1,12.00,0.00,0.00,0,0.00,1101001,4102001),(4,4,'6f83f9dff87d9a2f3370cd923aea8c7bae4f75659804300901ada37b8384c1988273fc7a56b7a7bf75c9a73b9860beb35587b74f318198d5bb28af75363ef750YjB1iflA3qjbXEWwKghl5gX7AHxxG6cCcRebYeWFxG1MHH2T4Jpq4jF5tQMXzeqa',null,'e7e44ee38c10ff31c8c09adca565e4cf6c09738f18a7bb6d56dcf5394cd0d3f2640baa018b95670c254a6820c7f9909c55a4c5a2ca6c71a5d1fb8ef58f1fda11CI+SCmaVdK1iGXa8lbpG+YeTqJ+weqS18exPgckmois=','5d61697302b06be578e6949e546155da4ed14d76ef6dfa16b31167fc9398833ae05e9ad83d130502934ed27d094c581b828b14e4089c2ad4d9159b710107e339jmK0dlQjkYP5RpPMI3sGgWyFpWk812rckoU0mVjudsY=','71a6ec6e1e9119f4d900f9e0543bdade75aca901fd5c9dfc1daef17c08c5dab27e6ef1af6afeed43d0baedbbd8f724a16338cba691b38fa54a123fd851d208b5xk7Pj4aOFk5XGJPwY9KdbURiPljU0VzMdJFUa83yOzk=',1,null,0,0.00,null,2,12.00,0.00,0.00,0,0.00,1101001,4102001),(5,5,'58aaf3a3e3ff5e28529d341f3ee77e6e4509c343518d67df52b9cd0b29c952f70bb6c3d5196668c32e7c8c3f38793e1a0e4de7b592f4c6413fbc6d51b30fd566v+buOllUY13qWjcRgSaly/hNs12FBV3+J9/N1y9uVAuvQjA6IT1zFgqVLYdhiRuo',null,'36cf6e1b17ac1ca79ec52b910ff4ad2655bc19363edf78497b30f47c96d9aa43928777cfa61394b3fadd7d6e391535682f82ccb6a49e946f414d757d06df3cebah2exseRerZ1LiNMZgZcvk4Pjy+YnpPHIo/Cs7JPr/4=','1ff6307172bf580853e1db261066a7e2651bb923130c6d7ced8186125320d59e45553bf87413d2e006eec90ce278ecf67c56b4fc21f0749dcf3b5ec463a3b75fbzDZenqXnESXbj6L3DO5B5PYYGL2wmVt0bPRz2KPlZw=','bc00594964166675880ad2e525a8d6cce5cad22fa8616fed05f532d91a196e382090efbca61a52aac20393d45db00ccb0c9946b89bed1358d2201666365e7d62w4/2ZcLtl6pNqTXRvHmImUFdZu7jSkF1ipIdkKKuCZ8=',1,null,0,0.00,null,null,0.00,10.00,0.00,0,0.00,null,null),(6,6,'d2d580c9ee8f8245534b7a5cb2850f63525169f5a55c9ec2549b18798d6b7248228f21c9690e3a84d93cdd974b0bf76c9d764209876f8ead4e951cdd583a101dig8qNviOaNRudCnFEBmdtlAPvjbLpEbMnFlvnqAdakYV0R+8c2ieV8UlGTVUdkyI',null,'0a511a4062c52e353eca7256b348c1cc97f71da670a852b97b31f879ee3511d79bb1ab0069d1301dc2aea46f80ebfe0880afd3d8bb6e8af89a8f14ab477388b3fsfTacaCG9UrzsRjLKMVdJxD+2DQTEzZznl6olPL2eM=','26781ecd2a6aba354f54b43c89480e3e454fd800d705751ae9abd46a71639f7fa6b313f941ebad7ccf42e3d37df53dc229458b1d61504549a718a67d672bec593uZWqw/ormA2yrVIRRloN+r8wfxoHa3TJfWIhGQOgVQ=','c91ee7a449e7ff774024de1f4104d485107a393d3ddbecc5918345f58953f980b745d84bbaa02ad80ff9b77920c50feaf4c48bcc9b5303e50df5a4d199a5e61aXQFwbSPFSst4fz+Im7rB+hmD8zNKYgnn0Rg6NeJGz9s=',1,null,0,0.00,null,null,0.00,0.00,10.00,0,0.00,1101001,4102001),(7,7,'d482f9d16fb2707de49593e639742798de766e8eb372800da90c8e18533f2b5bba5c11c9bca433005e69f309e5de64588efcbfbf34feacb90ac3b4966e21a7c4X8+2UhqZcz+QVOMWKW0+1M6zpuRjEXUS6UnZlBiXNrQ=','2071fa77d39c5399a29cfd0b32e50b800c083c9b2860f79060b533f3944b78d9dbf6c3ccf84e5e71024e17ec48e2597395ef7320b1d1a936e69c89b354401f4dAOt7SYeG03SgSdsmV4O6xrY2yyujrfTzAdP/5wrXSKw=','cbcbf73a615f15f0bf6e4cb263592d75c80bdbb2b3e971b43f3339ba491982ee2ff98127dd9321d7dfbb34e18c0bff2129619c90a6743f3c652fdc5d18ec0ca9tjA9eH08KKN7ZpLPw0x89T/hnFiFOsw+way1NN18ucU=','2729adbe9e598a44442056a56aa9cca54ee96db802038cb60f3862a21555cd13c9db82eaf5c75dffcad345529f8a1321ebcdc311905dab164f21781bf1136404dBgm8fSGGp3wIelImHIN7xW9spPHpjnOLBC+qmaa+Dh1GkYnMMaGwhuFVNdJrHyQ5lLoh26+MKtjyYg1HfDSkg==','058bdd84f35035e54081fd7a46ae9bb222a05bb7ce5b19114910f8e741afbd41da5434f330f57ddb817bfed4a0dee77a4fd4be4e52c75a787ca9f2524eb00341Q1LWxOhakxhdt/ywULiWi9HmejeEL7QGFGk9q3/yMvo=',2,20,1,10000000.00,null,1,12.00,0.00,0.00,0,0.00,1101000,null),(8,8,'8843d0044d599124f1a3470b75f6bb1eed6ff31a60ddb50c075687fe12d7a58648a5fb84d1914c7aa4421881be55e1e1de55c0fe31b3a7949e7f2344ec06e963/oARreONWneMaA4BfEUVX8jDXFazr4vcWaFBAQsF7mA=',null,'1af99d03cc0310acdeb7e85c6e346b84dd6eb3b5a6c09083db575aba2f10d00607ced1b28ab85822c14b333a4467df4977f58eabeeaef1916f9e96274f2cd32bzpDFRsVYq2D5eG9Nww7blz1Hn4Yy4e20CUZmRDi7430=','a910c281deb9a2620fd5c24f4de298d88e140195b53533100cd4b135a7b1d26d0551f93e98ba4e87ee5400a209c62a8d6fd683b7facbf120bd98229f799a4893rDibNqNhEtoDm2+DNTLt2tOfb6vNrnnmWkoL/xnuaQ9QZAJ7a5eSVRajhI5JE6ykS7uLBzCP8MmeTyrOoHBSiw==',0,1,null,1,5000.00,null,1,12.00,0.00,0.00,1,0.00,null,null),(9,9,'e4d22cec2c47ec72d22c33d750fcd634d8e20a4823cf632033a1995c735ce9ac5ede5dd7fa90d92681792a084b24d54a781d7dfa6c1efc23fdc5702f44780b11qYZHA3ay54VoYGEnhrgUpOF+u2C6sLkq5Cs8bEv8SMg=',null,'c88b3d81ec09a8564cfe3251add9c801a8e78f1c0963a41a41042138eff67b710a6c8ceb67237810165505b95f738f81f0cc68ea7b3f8790c2eea2e5ad3a3215dnFXwCHntGPh28CONvfKiuXwXIrJL13o1qIIOGnyulE=','244a5c4d1905690a6672f5b63657d42adc152305f897550cfd9f3d35becf8e1643547d87ddcf93bf602fca68602e9bb276a74bef78cf42127eb5a7dd484c17b6YpVwk4QWNoXZmoYFVmnTkNTyyIQcNsySY4+asVC4GnlTAL9KZ4fwiXtJ1+T5Honk','06913dca843d4f01388b3f4e6e9d944f9e9d974ff58e1dfe9c28f9fbd4c580d6e738fd7850be03a47fc90cb5fdf3f62e5bdf46d3d46543ba2061346f90a4dd1eaHXNqYNhDus0Cr+XZvxaCth2XkCqgCXDzS72Faoer0o=',1,null,0,0.00,null,null,0.00,0.00,0.00,0,0.00,null,null),(10,9,'00b38508390ad673802288ca3496a69cf2f77e931b7734651e19af391bb3606274d275ab9baf26ced432c74b7773ed5ac3c821130f94b028f4938c3433131b64FNYt9sQqnsldm7N+wez4Hx6IJl4rMrpHoZIp7NYkh50=',null,'3a604c0bcaa3a49d2d0494151b42d5846f046967013c236b39d4c6c281520999ccc0e861570f95f9ed68bdb8ed21b25ec743dd2767d9d46f2156fdbbc2c41911Xffw16UJDB9owfr9EUMGXiT7uv51y/DBtBZrexxBRLM=','000d4cc106b7adc263ee937a4e9a1be50e82fb130fd6657792ce68e5868ceebc66f0ffa06df3dae3aad7c883c1de3fb9cb5edf4d11304450e1486597b5f81f77Sh4L0XknEFKX0rkNbGyys5IuZoRoiJAoyY9cWDjUKznfSrnX3HfAp21Z8+bx5pJV','f7f3d9817bc04abbc835dcba38f245455e3ed1cda9997c9dbacf312de835357a1f93001005f6be9b629b53465a7b05730626f09d9ab4ad354785853d0ca76e74UvI4kw6oAWbl3jOjb5LZoHmJm1D4ZH6c9H937tGarro=',1,null,1,100000.00,null,1,12.00,10.00,0.00,0,0.00,null,null),(11,10,'96f099d9c2988b8781a0a29f2c3faa487dac51fcbcda1f18fd2a7af4e3b52653f2ba2fac57382912d95e987bcc62e789922a400df8791300150eb0caa26bf06cjvIz7/l+292tTpX1Y8FoyJCGX3RGFrvQx7jvIXN0ovM=','e5947f01caed49d7adc10226a7b5e20a309239e05a629a57d9c04ec15406b44d35ec24e3bc4c96e12082679cc88c68e89b3d0ffb14618baad37f71bb92f8f788vUS8MYKJTrzx6Ulxp4BGbmuPcsTL7NsFer6zmTKLzxBmj3vPDBK5ocD9U6TqtD2K','2245d1b3cece4fb43e090ee94dc92eae73ee8fd033df84418969e18c949d64c80fc6b388a98ca55824a6de0274625b5ce0b9d546089f22a35018f8ee42727c17KX6GdCzGyNThSG7tgYKWBv0N7qcM7UZl6YtPRB5NiKE=','38da5c54a16702139c7d95e4173517d49e5df08fd2f76380277b680d23cc40d1dd4453daeef51fe70f8b693d275ca4684f37def5be16aa4f0c97ff5dfc10a36aba7Q1QVVjORxNemX3reYHpvqQr5e/w6gCeN215Gk63g=','bff60a8324e3d94c89f80bc92a76567a3754aaaf299e130491381bbb8d2cfbe39fc9cfc1c3af9e11de59f9ab5ed7f21a4c5c49ffe18652a9582964944901e701lCB1o8WRbC+lNLQi4DKhmtMW/CKFb3BVDhdtnRJuKK0=',1,null,1,100000.00,null,null,0.00,0.00,0.00,0,0.00,null,null),(12,1,'659f49276b7619cf4d1099cc5a2f24d0acd4f79e68c518744a7479d556ae6a80aab4c1661f6e742bdb8b3c28ed586ad778ba577545ba3a87345c53433588b682JDoYvx4wbKaTpI58cpMppHDDYEpldoG+e/wot+SRTu8=',null,'01e5fde15988a10c07de9c70b4c821ff7611307039d9194a924eb665b41a0dc50ae4b01db2ab4f39a5cf09d952a0dcbd7e3096f60f9e17bfd939c2763811de1fVOSvJa3XldlaYQha091Jaye1NkaSJrnW2libKE7mw2M=','272a52c0e624215651cc9d62002115a7a278d6e100b3c5db5208cdaa5bc606ce0ec954ad492ce82f1ac0b7bef0906b220f79fb4586ac3b21bceaae4d0257c31eU4cvnhHDQRWbvSzkD0QYNfWcEpcE+W7hnSeA7wlAAic=','66ebc338c06fbda354fe2cde83f3dff9da666607dea8317b13b96a22101c51e67a60dbe5f9ddbe6f98cf46fd76da4f543500a03e8a506469028cd4398827f31dkYWLycGgCeOIAIhu1KzA/f97C+6r6kr5gyDLaJ6Hn64=',2,null,0,0.00,null,null,0.00,0.00,0.00,0,0.00,null,null),(13,2,'18e7524f657d6f4baa1b207281f86a0d61a1aa3fff751242633a20912ab023ed4e55344061687822d77d72e7a82b1d1cbe26bc65c7238a07fdbfc007f53833cerdiFNhWolTF/DkFt2KVfi9eEOUPK0pGRTHrhr89cefc=',null,'e56dcc5e3bc8d859634e902e6a5efa5fbeec577b11c00dd2eb0e29cdc5f4f1b48f626d0ce455bacd417a2a3241d2a1c808a712bd8e67ab7aa139f64f59f690bdVwlTyhHwg+9Ki6inIl2Pn27Ixtypp6ArZfW7A8rjLtA=','c0d96c4741af65ff1e283b92c481d231444526a210ee3d68e0390b4c529ca5f68979336f0cf31108cd557bf28afc622d1587f249d5f9421b6c1e4a31c4ca61e6Y306DXGZLzXVn4sERTAkMTnET4hrsK/wzt3oHAdlW+pAjnvt/adImHjwdqKNpfPF','3043bbebe6761be0079675975459418bfdea4425c7f51a2767f48797ef786db93e2c12af182ecdcc9fa3e53a778d7f65db591d800de8aa5c48fa49866471286donP2sWNB5FzfHrT6oNtCKykNDxaQf9YnmGlIhVt70J4=',1,null,1,10000.00,null,null,0.00,0.00,0.00,0,0.00,null,null),(14,3,'fcca9ff4d84afeba86c71b70bfbf764311e53e63db0c432a565ecd66f6cee077a74b383c0adc46a5988543497f3fce3caff2eb02f2fda4cf886b95c5606e4d0fFmOKT8y6the8ko/V5M5edLodYIGNmZjbMFzg0Z1yt8g=',null,'4a8af4711d8fb719f984c7924251822a4a216ebbf1b42ee4bdfbdc083c4f8ad3b68dd2f94242271b8956759a32c98e3078604299979f2a49d89161bae2f841fbIvf9i44D0M0+BFrT91JATRy/SetwC/t8yuQFCbQ8BU4=','1dae9307688779c5e59320a1d768a886f635a702309481b6feb940599313e89a3b3c9ef5286a27bcb1f0f5a9ca580e844c96a5e9851a1fe07329bb651f0d7771T9FvskPa+MtKsblH3CAHyu9CzIil00rom7AWoFdf8WgcwmVd7pFvsAN4hDU5esb+','1665869ae307a93768349e4d0838035a0e27b497075b1671db678253cfcc33045e3b33db4fc3000b2355366866130cc0273d7d7302d4072befa23cad77d7ca5bCToRg9g0s9AzxMqpXnvc3IuevY/csS94ujrH5kgbVjI=',1,null,0,0.00,null,1,12.00,0.00,0.00,0,0.00,null,null),(15,4,'32cac3ad0cc91d835e7dd6d346dcf51304d9318663784c783f03473837c69cfb52255791893702a04352bd4c92453756a11a10155338a06b04ae2603f692869bfQLxsJJ6XHgJMXhwxRWckPcYDX/WS6zZg1jBkqHWfdk=',null,'952148a59169e3f8ea3e543c5fd407ae498b4998e15e3356914e19d2f298683bdedc93a3455ed1b1ce9766cafae789892c736feb1bd4d166b54d6f217c3e996dO5MYLJC4ZHUDMJ21A18/gsJdTa6jyZZyWK0OnpvcCPg=','65a38b67f3054ded11848ea4d63d7941a0a443f8dda20279e3bfb2656e789b0dae108ed1183c96bb545f6d1db3c6c06829f41bfe15624e84bb59d29bb85e0aaecfHa+Tv6ki3Eosl/ndJ5JEb+bKFIgOy6hpN+KweWB0A=','07f22ac0e61a894d67ba3a2a21d0bb4447241415e5b1d97416361b0ac9c9bd96edf9a7d7b5549b95a9a0174162958579d0bb9720dfff92829401def9b7ba1e8eIDcj+snDbqtqn5ri9Zz4OL+zRPLOYx1QcONb3N9mUN0=',1,null,0,0.00,null,2,12.00,0.00,0.00,0,0.00,null,null),(16,6,'ca58cf5e0700e233592b6fa83dca256cf8373cccf3097447e1bc577b1dcb9446e0abb598ac630bbe57229d35f5f4e6663c5991dcb487c6dea64a23f89d978e15LiqcW+Zjicu3BUsXyLw8gTF1z9LadsDEazC+BpMl+lA=',null,'ecd3f11d1235504674020970387f13cb03ba4f77588c3029dc5131475e1be41074590e479611d7c9843a24d027461ce26071f62d408a1978e2e56f4985a45db8L01Aqd9UQ1QYwnIz3GraEHu1f75W8WweyeIIaw8hlzc=','59569f0177f52be2e37c1198056d4b751435940fabfe2ae3a0646c427bc0f9f5d0371a3335a27a23573108b2347566448353296609740ca6ea6bc7bbbf70e93eMxYD5hCq+VQOUn10P427O3iV8v1B5gF4wc64tDA2Vdk=','c94470cce17a85b1daf3e5df1212a88413aa6f826fd27cc834e0121a8a4f580bbb2c21fa2c58f108c33d85272b36ac42ecc809be79a97e83aa3eb2752a947fbdWqICHqzC4LTgy/uaeFGEzHKDLGvmpHDhmMPM/MGGDVc=',1,null,0,0.00,null,null,0.00,0.00,10.00,0,0.00,null,null),(17,11,'74757ab60e59b0a71bc05d0d437f8ae7310c27f261bb710f120952fcd1ab59122e6080af326d0b0082535ee4481bae924d708f7181ff0e0a077ecbc1b8adeb4cGzwfgTBQgw1brToW0p3HAn7/+Qi/3TOCTUd5skWJOQM=',null,'7e0707630d2f6c698be0c48b164d73ff695dd9efff91c556481050f052176be863d73b5e46b051d43ea54863bb585516d2448f69dcb53dbca2a8dae620820f22ryiV/KKf+5kQGSt4Z7qeE/O7PV2cU6GnzC8rT7HrkZs=','09b81e296a1e78d062be6150796e2a6ed234f0d89157296f1e6a7f28572c95944ad2630fd75f5a66ebc7e9c6d79b18b2b3f736992c79bd3a524a0d3ce6ca7f0eoC3oSywcKynTCZx51atoT1k9oXVezwXUKnKpV6OKjo0Bj5NNjiYTF1M2FaYGYGUj','ec5bab644e82885219d0bd765b0646f66e70c4b867d57b34a48af7b6e767943795d80ccf20d2929d25f52d950efe06d06808946eddfa7adacd425fe36bf880e9JA/ZGnG9TXf9bv1mqhOHMLEWXhKN4P+ej5Pt/HQ94AQ=',1,null,0,0.00,null,null,0.00,0.00,0.00,0,0.00,null,null),(18,12,'91b5b0b2ce7f12a95aa94bcd7f8270c6c7ebbf239dce10fe0860f8f1a4077af640ac50b71b41035dcde7575474c21f1f0b8af60c072f056e40ef8fcb94531183fzLHOLf9KEYJVi6Odgm/rXkWElkwwZTdOPRsDcV7rWg=','b5acbddf2b23b8e205870a441afb219190a84da363b87eec50452d43f34ce92f7cb56e7a32b107ebef94945fe4f80b716e18cd1954c2f0a42c3792edafbb41840X/i6vWvjlFVygH6iDoHotyhcIbHRa900F7e7cqbBw5smvYIW9ViuNZLQKrUAalV','61b6c799c00820b915a0f66ea0d3f6bf9f59295f71321aacfdc40dffc39fa434010403d36d2f1741a41207470978677f572e229fe7181e0cec9fc75038cb2a11weLPhAMNc85JpT3nIYYSv/t8Tehb5Nr7jcsai0I+emI=','d67f46d0092b618237497e09065ca2d3ce1914248ae004b64360972efeb2614515df459327199fc2a893eb993acf40edb3dbc547cd2fe134b09af1a246e7d183bmmnW+84debvUhJuoiXxBL3OoREODZJQViNloe8JQLk=','8de33a417034084ccbede9356b6a3e074a2eef5ddeb3e2ad07f32bc79eb747fd1c8e36889663c42c3a8eb41f403ec76278887105253121beb488b3d582c80f9d/GhfZCEZlFREUPZ3BwdJOm/y6EeV8fhy0t/n9Hf38zA=',1,null,0,0.00,null,null,0.00,0.00,0.00,0,0.00,null,null),(19,12,'1419423964003d34c0ea60ebdfe50887590aac69bb6b30a97e7978f8ff35716ff05e4ddf8d7fe51a0baf72217d41ef3ec78871a4832f41e30c2c9827d219a8b43T28DDSTgq5J4sPdrLash+NdN4UZt5ZLrz68qfoCkk0=','3b7d5f40ef0a2649174bb529147648ae8d98e9d0c6361a83110ee23cd7844eaf80393fad1b018d1dc3ba0abc2a376ff460783c64875aa356e9ac6a509e06a262BTugakgLApMS6VRs35EhGub2dOu8dV7o7WOX0YHxK2fdqNaXzbZwki4PVtowy2hq','9f9ab8ab168154184a9bc060d4c35fc411eea925afac2aeabfc58c65eedcaef9cf13fc158cc9b6f7c0ae158d5aa55c177e5edaebd25870241794f0d90889b1da4hx5Fyu/uzuC7w1SSdcz/HpO8v5n5i3mJnpBQi90cZg=','1a351364e598bdf559299e60a9e02c6f1244b87bebd8cd659309d214f146f5ad2b622777c29f3270cbd9321af34a261acef003288d17191f706b5d1edd916709lb8v7YLqjriyj5mTImcqmp+Ub2C2I0+GvreoQ4l4pXw=','c1e082c5d6e21ede2d6488564563379ddda4658fbd17ac580183aac28d946f189e004bcf8214588fc5964b9796743a66b5dcd910ff3b870611ac3cd6f33def42HW4h+VquRUPJAyPISjiNX2Ask6t6Yld/josRkyJuUag=',1,null,0,0.00,null,null,0.00,0.00,0.00,0,0.00,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `customeritems`;:||:Separator:||:


CREATE TABLE `customeritems` (
  `idCustomerItems` int NOT NULL AUTO_INCREMENT,
  `idCustomer` int DEFAULT NULL,
  `idItem` int DEFAULT NULL,
  PRIMARY KEY (`idCustomerItems`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `customeritems` WRITE;:||:Separator:||:
 INSERT INTO `customeritems` VALUES(1,8,4),(2,8,1),(3,8,2),(4,12,4);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `customeritemshistory`;:||:Separator:||:


CREATE TABLE `customeritemshistory` (
  `idCustomerItemsHistory` int NOT NULL AUTO_INCREMENT,
  `idCustomerHistory` int DEFAULT NULL,
  `idCustomer` int DEFAULT NULL,
  `idItem` int DEFAULT NULL,
  PRIMARY KEY (`idCustomerItemsHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `customeritemshistory` WRITE;:||:Separator:||:
 INSERT INTO `customeritemshistory` VALUES(1,8,8,4),(2,8,8,1),(3,8,8,2),(4,19,12,4);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `defaultaccounts`;:||:Separator:||:


CREATE TABLE `defaultaccounts` (
  `idDefaultAcc` int NOT NULL AUTO_INCREMENT,
  `idAffiliate` int DEFAULT NULL,
  `debitRec` int DEFAULT NULL,
  `creditPay` int DEFAULT NULL,
  `accRec` int DEFAULT NULL,
  `accPay` int DEFAULT NULL,
  `debitMemo` int DEFAULT NULL,
  `creditMemo` int DEFAULT NULL,
  `inputTax` int DEFAULT NULL,
  `outputTax` int DEFAULT NULL,
  `salesAccount` int DEFAULT NULL,
  `salesDiscount` int DEFAULT NULL,
  `otherIncome` int DEFAULT NULL,
  `retainedEarnings` int DEFAULT NULL,
  `incomeTaxProvision` int DEFAULT NULL,
  `cashEquivalents` int DEFAULT NULL,
  PRIMARY KEY (`idDefaultAcc`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultaccounts` WRITE;:||:Separator:||:
 INSERT INTO `defaultaccounts` VALUES(1,2,null,null,null,null,null,null,null,null,null,null,null,null,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `defaultaccountshistory`;:||:Separator:||:


CREATE TABLE `defaultaccountshistory` (
  `idDefaultAccHistory` int NOT NULL AUTO_INCREMENT,
  `idDefaultAcc` int DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  `debitRec` int DEFAULT NULL,
  `creditPay` int DEFAULT NULL,
  `accRec` int DEFAULT NULL,
  `accPay` int DEFAULT NULL,
  `debitMemo` int DEFAULT NULL,
  `creditMemo` int DEFAULT NULL,
  `inputTax` int DEFAULT NULL,
  `outputTax` int DEFAULT NULL,
  `salesAccount` int DEFAULT NULL,
  `salesDiscount` int DEFAULT NULL,
  `otherIncome` int DEFAULT NULL,
  `retainedEarnings` int DEFAULT NULL,
  `incomeTaxProvision` int DEFAULT NULL,
  `cashEquivalents` int DEFAULT NULL,
  PRIMARY KEY (`idDefaultAccHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultaccountshistory` WRITE;:||:Separator:||:
 INSERT INTO `defaultaccountshistory` VALUES(1,1,2,0,0,0,0,0,0,0,0,0,0,0,0,0,0),(2,1,2,null,null,null,null,null,null,null,null,null,null,null,null,null,null),(3,1,2,null,null,null,null,null,null,null,null,null,null,null,null,null,null),(4,1,2,null,null,null,null,null,null,null,null,null,null,null,null,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `defaultentry`;:||:Separator:||:


CREATE TABLE `defaultentry` (
  `idDefaultEntry` int NOT NULL AUTO_INCREMENT,
  `purpose` char(250) DEFAULT NULL,
  `idModule` int DEFAULT NULL,
  `idReference` int DEFAULT NULL,
  `remarks` text,
  `archived` int DEFAULT '0',
  PRIMARY KEY (`idDefaultEntry`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultentry` WRITE;:||:Separator:||:
 INSERT INTO `defaultentry` VALUES(1,'Sample JE',25,11,null,1),(2,'Sample',48,2,null,1),(3,'For testing',57,16,null,1);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `defaultentryaffiliate`;:||:Separator:||:


CREATE TABLE `defaultentryaffiliate` (
  `idDefaultAffiliate` int NOT NULL AUTO_INCREMENT,
  `idDefaultEntry` int DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  PRIMARY KEY (`idDefaultAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultentryaffiliate` WRITE;:||:Separator:||:
 INSERT INTO `defaultentryaffiliate` VALUES(1,1,6),(2,1,2),(3,1,5),(5,3,2),(6,2,2);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `defaultentryaffiliatehistory`;:||:Separator:||:


CREATE TABLE `defaultentryaffiliatehistory` (
  `idDefaultEntryAffiliateHistory` int NOT NULL AUTO_INCREMENT,
  `idDefaultEntryAffiliate` int DEFAULT NULL,
  `idDefaultEntryHistory` int DEFAULT NULL,
  `idDefaultEntry` int DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  PRIMARY KEY (`idDefaultEntryAffiliateHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultentryaffiliatehistory` WRITE;:||:Separator:||:
 INSERT INTO `defaultentryaffiliatehistory` VALUES(1,null,1,1,6),(2,null,1,1,2),(3,null,1,1,5),(4,null,2,2,2),(5,null,3,3,2),(6,null,4,2,2);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `defaultentryhistory`;:||:Separator:||:


CREATE TABLE `defaultentryhistory` (
  `idDefaultEntryHistory` int NOT NULL AUTO_INCREMENT,
  `idDefaultEntry` int DEFAULT NULL,
  `purpose` char(250) DEFAULT NULL,
  `idModule` int DEFAULT NULL,
  `idReference` int DEFAULT NULL,
  `remarks` text,
  PRIMARY KEY (`idDefaultEntryHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultentryhistory` WRITE;:||:Separator:||:
 INSERT INTO `defaultentryhistory` VALUES(1,1,'Sample JE',25,11,null),(2,2,'Sample',48,24,null),(3,3,'For testing',57,16,null),(4,2,'Sample',48,2,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `defaultentryposting`;:||:Separator:||:


CREATE TABLE `defaultentryposting` (
  `idDefaultPosting` int NOT NULL AUTO_INCREMENT,
  `idDefaultEntry` int DEFAULT NULL,
  `idCoa` int DEFAULT NULL,
  `debit` decimal(18,2) DEFAULT '0.00',
  `credit` decimal(18,2) DEFAULT '0.00',
  PRIMARY KEY (`idDefaultPosting`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultentryposting` WRITE;:||:Separator:||:
 INSERT INTO `defaultentryposting` VALUES(1,1,2101000,0.00,0.00),(2,1,1102001,0.00,0.00),(5,3,2101000,10000.00,0.00),(6,3,1102000,0.00,10000.00),(7,2,5101000,1500.00,0.00),(8,2,5102000,0.00,1500.00);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `defaultentrypostinghistory`;:||:Separator:||:


CREATE TABLE `defaultentrypostinghistory` (
  `idDefaultEntryPostingHistory` int NOT NULL AUTO_INCREMENT,
  `idDefaultEntryPosting` int DEFAULT NULL,
  `idDefaultEntryHistory` int DEFAULT NULL,
  `idCoa` int DEFAULT NULL,
  `debit` decimal(18,2) DEFAULT '0.00',
  `credit` decimal(18,2) DEFAULT '0.00',
  PRIMARY KEY (`idDefaultEntryPostingHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultentrypostinghistory` WRITE;:||:Separator:||:
 INSERT INTO `defaultentrypostinghistory` VALUES(1,null,1,2101000,0.00,0.00),(2,null,1,1102001,0.00,0.00),(3,null,2,5101000,1000.00,0.00),(4,null,2,5102000,0.00,1000.00),(5,null,3,2101000,10000.00,0.00),(6,null,3,1102000,0.00,10000.00),(7,null,4,5101000,1500.00,0.00),(8,null,4,5102000,0.00,1500.00);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `deliveryticket`;:||:Separator:||:


CREATE TABLE `deliveryticket` (
  `idDeliveryTicket` int NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `idProject` int DEFAULT '0',
  `idTruckType` int DEFAULT '0',
  `remarks` varchar(255) DEFAULT NULL,
  `deliveryTicketType` int DEFAULT NULL COMMENT '1 = Per Load,  2 = Per Day',
  `isConstruction` int DEFAULT NULL COMMENT '0 = Truck, 1 = Construction',
  `odometer` float DEFAULT NULL,
  `idInvoice` int DEFAULT NULL,
  `idDriver` int DEFAULT NULL,
  `idTruckProfile` int DEFAULT NULL,
  PRIMARY KEY (`idDeliveryTicket`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1 COMMENT='phase 2';:||:Separator:||:


LOCK TABLES `deliveryticket` WRITE;:||:Separator:||:
 INSERT INTO `deliveryticket` VALUES(10,null,2,null,null,2,0,10000,45,null,null),(11,null,5,1,'Sample Update',1,0,1000,46,null,null),(12,null,5,6,null,1,0,0,47,null,null),(13,null,null,5,'Sample ticket using a deleted Project',1,0,100000,48,null,null),(19,null,5,1,'Do velit fuga Solu',1,0,100,80,null,null),(20,null,6,7,'Sed molestiae aut qu',1,1,120,81,142,15),(21,null,1,7,'Libero et quod occae',1,1,320,82,142,16),(22,null,3,1,'Nobis omnis quos nul',1,1,230,83,142,17),(26,null,1,2,'New Delivery Ticket',2,0,0,92,142,17),(27,null,1,1,null,1,0,0,116,142,17),(30,null,6,1,null,2,0,0,119,142,7),(31,null,6,7,null,1,0,500,120,142,17),(32,null,16,6,'This is a test ticket, updated',1,1,0,203,142,9);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `deliveryticketactivity`;:||:Separator:||:


CREATE TABLE `deliveryticketactivity` (
  `idDeliveryTicketActivity` int NOT NULL AUTO_INCREMENT,
  `activityName` varchar(50) DEFAULT NULL,
  `area` varchar(50) DEFAULT NULL,
  `noOfLoads` double DEFAULT NULL,
  `noOfDays` double DEFAULT NULL,
  `fuelConsumed` double DEFAULT NULL,
  `lubricant` char(50) DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `idInvoice` int DEFAULT NULL,
  `idDeliveryTicket` int DEFAULT NULL,
  `total` double NOT NULL DEFAULT '0',
  `idLocation` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`idDeliveryTicketActivity`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1 COMMENT='Phase 2';:||:Separator:||:


LOCK TABLES `deliveryticketactivity` WRITE;:||:Separator:||:
 INSERT INTO `deliveryticketactivity` VALUES(1,'Sint ut ut enim quae','Exercitationem qui s',2,null,50,'Officiis asperiores',45,null,null,1,90,0),(2,'Cupiditate adipisici','Facere officiis itaq',1,null,20,'Sapiente corporis in',35,null,null,1,35,0),(3,'Obcaecati ullamco ul','Aliquip recusandae ',null,5,5,'Eos laborum Mollit',60,'Assumenda ullam aut ',null,8,300,0),(12,'Sample','Sesame Street',5,null,45,1,2000,null,null,11,10000,0),(16,'Goods delivery','Iligan',null,3,100,2,1300,null,null,10,3900,0),(18,'Sample','Sample Street',1,null,20,null,5000,null,null,13,5000,0),(19,'Delivery of Goods','Sesame Street, CDO',1,null,25,1,1000,null,null,12,1000,0),(20,'Non et qui voluptati',null,15,null,50,'Dolore autem suscipi',145,null,null,19,2175,2),(21,'Ut ut commodi dolore',null,12,null,30,'Maiores odio exercit',65,null,null,20,780,2),(22,'Eveniet aperiam aut',null,12,null,65,'Ullam dignissimos ve',45,null,null,21,540,2),(24,'Labore nisi aliquip ',null,12,null,56,'Eos in consequatur',120,null,null,22,1440,4),(25,'Sample Activity',null,null,1,100,1,15000,null,null,26,15000,2),(26,'Sample',null,1,null,2,null,10,null,null,27,10,4),(28,null,null,null,5,500,null,5000,null,null,30,25000,0),(29,'Sample',null,10,null,100,null,5000,null,null,31,50000,2),(31,'Sample Activity ',null,10,null,1500,'Sample',500,null,null,32,5000,4),(32,'Another activity',null,5,null,500,null,500,null,null,32,2500,2);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `disbursements`;:||:Separator:||:


CREATE TABLE `disbursements` (
  `idDisbursement` int NOT NULL AUTO_INCREMENT,
  `idInvoice` int DEFAULT NULL,
  `paid` decimal(18,2) DEFAULT '0.00',
  `ewtRate` decimal(18,2) DEFAULT '0.00',
  `ewtAmount` decimal(18,2) DEFAULT '0.00',
  `balance` decimal(18,2) DEFAULT '0.00',
  `fIDModule` int DEFAULT NULL,
  `fIdent` int DEFAULT NULL,
  PRIMARY KEY (`idDisbursement`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `disbursements` WRITE;:||:Separator:||:
 INSERT INTO `disbursements` VALUES(1,84,150.00,0.00,0.00,0.00,0,73),(2,84,2000.00,0.00,0.00,450.00,0,72),(3,84,2000.00,0.00,0.00,450.00,0,59),(4,84,400.00,0.00,0.00,22000.00,0,56),(5,85,450.00,0.00,0.00,0.00,0,72),(6,85,450.00,0.00,0.00,0.00,0,59),(7,63,3000.00,0.00,0.00,1342.50,0,57);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `disbursementshistory`;:||:Separator:||:


CREATE TABLE `disbursementshistory` (
  `idDisbursementHistory` int NOT NULL AUTO_INCREMENT,
  `idInvoiceHistory` int DEFAULT NULL,
  `idInvoice` int DEFAULT NULL,
  `paid` decimal(18,2) DEFAULT '0.00',
  `balance` decimal(18,2) DEFAULT '0.00',
  `fIDModule` int DEFAULT NULL,
  `fIdent` int DEFAULT NULL,
  PRIMARY KEY (`idDisbursementHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `disbursementshistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `empbenefits`;:||:Separator:||:


CREATE TABLE `empbenefits` (
  `idEmpBenefits` int NOT NULL AUTO_INCREMENT,
  `idEmployee` int DEFAULT NULL,
  `description` text,
  `amount` text,
  `schedule` int DEFAULT NULL COMMENT '1 - Daily\n2 - Monthly (1st Half)\n3 - Monthly (2nd Half)\n4 - Semi-Monthly',
  `archived` int DEFAULT '0',
  PRIMARY KEY (`idEmpBenefits`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `empbenefits` WRITE;:||:Separator:||:
 INSERT INTO `empbenefits` VALUES(9,141,'0a14e0d7cfd0029147f514dda54ae24efacf99cf750f126d26dfe3bf66d0b486b470040eb4cf6dac3c88efe331cab6740dddb3c0fa7e1971f867e33e5836382c1XCC5n4qUqOEzZW2RN0PKdOwmmomqsV4AmY6MR2UGabZqALvZ+DtwUbjJiVZEZqI','de2c4348c84235b40dce4f923a1bbc2a0c671abc7b6276e6afdee2ffb7711d1c68b6b75bac4a839ab46b495f75ea5806cac4a8ae69b00a3a635bcd2e480f3eeb/qNy5JZ9E371VNbFrbm8oXEsl45lmxVnPeKnBCqVcus=',2,0),(10,141,'83c3c7ded07f9bd0a253d14cb37e7eae510240b720916bb2195a54ab8a754fc513ec35e24b96fc90b77f24ca7260464e430c6d43c79454b4ac67b913e427606e8bpDkt/afjpWzFVrVEYbiCWCVCKlMm2tIkRmdw4NRT0AKo4/1ldDGjy8GdulDDFa','e79fdec0f872ab6e9f8eb454c9d9f2f419d8704b95baccbf931a20ee71da4cbac8b007787246090032405c67a36ec101fdf50abed98d59bb743ad12e30f3f510f/MEAZKnmFRk4ptWLXDn9qwWhEn6PK1NmregBvTfnbQ=',3,0),(12,145,'011f8b44ce16ee4e0b21071968baee687a6352610cf06d1487de0c81cca8250663ed399fd31fa69671ec696a9fcc3f360289042165cd889d5c42b5f29a5f583bTykQ2GqIe8Cd3BXb6BxcrVubTwTn9Wmoz2Q86qsbe24=','043176d4c4e28fccbe5588e48f9dbf2e97ff36eb4836b058b7411c642660529da565b26497fcb1e6dcf3107757fa28cff0e1449682ae44b34abdb1c2b9ae4762ga3bWrkXvF4ZvVvzA5wotlpJxx9cXfspYthiz4lJIeE=',3,1);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `empbenefitshistory`;:||:Separator:||:


CREATE TABLE `empbenefitshistory` (
  `idEmpBenefitsHistory` int NOT NULL AUTO_INCREMENT,
  `idEmpBenefits` int DEFAULT NULL,
  `idEmployeeHistory` int DEFAULT NULL,
  `idEmployee` int DEFAULT NULL,
  `description` text,
  `amount` text,
  `schedule` int DEFAULT NULL,
  PRIMARY KEY (`idEmpBenefitsHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `empbenefitshistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `empcontribution`;:||:Separator:||:


CREATE TABLE `empcontribution` (
  `idEmpContribution` int NOT NULL AUTO_INCREMENT,
  `idEmployee` int DEFAULT NULL,
  `idcontribution` int DEFAULT NULL,
  `amount` text,
  `effectivityDate` date DEFAULT NULL,
  `idCoa` int DEFAULT NULL,
  `archived` int DEFAULT '0',
  PRIMARY KEY (`idEmpContribution`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `empcontribution` WRITE;:||:Separator:||:
 INSERT INTO `empcontribution` VALUES(1,0,3,'4f18c21bb0942ae4ed6666bf8feaa554c020153f01058aedad2a84a3cff2fbe8bfcc86a9320e9cc5442d4da7b90b7c7f7b27d06c6fedcf5b0c42d5a88277b86cBOvNZXaCKjtKZpzahFIoluBxB+fgctj5yWaayYjQLc8=','2022-03-07',null,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `empcontributionhistory`;:||:Separator:||:


CREATE TABLE `empcontributionhistory` (
  `idEmpContributionHistory` int NOT NULL AUTO_INCREMENT,
  `idEmployee` int DEFAULT NULL,
  `idcontribution` int DEFAULT NULL COMMENT '1 - SSS\\n2 - Philhealth\\n3 - Pag-ibig Fund\\n4 - Withholding Tax',
  `amount` text,
  `effectivityDate` date DEFAULT NULL,
  PRIMARY KEY (`idEmpContributionHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `empcontributionhistory` WRITE;:||:Separator:||:
 INSERT INTO `empcontributionhistory` VALUES(3,141,3,'774e110cf001cbfc3dd6c4519131c34e25c8237c293782d8cb0c054afe21f7970540135dcbcf0c343dd3e94e0635cb90d1a1e5de04b75326d65302c1ad9b8d3ajrunJm4qngs/Wc5A84t2YDxevbQqvdmUhwlORlCO7po=','2010-02-01'),(4,145,3,'4f18c21bb0942ae4ed6666bf8feaa554c020153f01058aedad2a84a3cff2fbe8bfcc86a9320e9cc5442d4da7b90b7c7f7b27d06c6fedcf5b0c42d5a88277b86cBOvNZXaCKjtKZpzahFIoluBxB+fgctj5yWaayYjQLc8=','2022-03-07');:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `employee`;:||:Separator:||:


CREATE TABLE `employee` (
  `idEmployee` int NOT NULL AUTO_INCREMENT,
  `idNumber` int DEFAULT NULL,
  `name` text,
  `address` text,
  `contactNumber` text,
  `email` text,
  `birthdate` text,
  `status` int DEFAULT '1' COMMENT '1 - Active\n2 - Inactive',
  `user` int DEFAULT NULL COMMENT '1 - True\n2 - False',
  `archived` int DEFAULT '0',
  `sk` text,
  PRIMARY KEY (`idEmployee`)
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `employee` WRITE;:||:Separator:||:
 INSERT INTO `employee` VALUES(141,2147483647,'630ef3effa619504f6ca2c36013c6add9937a7358fa97e15b9f02d46f3a7831bf86600877aeec9cdefa358498eb6a713f70482d6f04dd6edaf1eb85eaf08a7003oLotcmj7n4hEK/r48/GB+y+7PvKaMUKYBmUOpXq+OlR+3jbzSjqPvcDM+PomUdr','602f2a59aa642c95ff3c99d6c3c2bfdd1c9571174ebc0c48223fc6f834737c3493ede606e72f1c7f8193425ad9eb30bfa2302fbeb89b79546fe73eac7d3da19dGPYu98PDQvlcqiC/r5v+sA05f3K4wESrL8+fb+4RuD1zSPwTf5peIlodhENf3lY9','05f78fecf807637c4820e3a2ba67e96c7e8695e81d366571b28cad03baa2ead17c47a17daeab89682c7baec855ac117303f867a771213916753ec17d99fae4bbBOKQc0oP7FDykJjG5ax8gY8LCQVegKXr2lwDmSHZ4Z8=','17527f03870876bd48eb2a90e3eed08070c81dae0cd75676e4253db6a12891579a7e7977246cdeb4fcfbc6b862ff35d2968c041d84907ca61c438f2ec5ec2e744kdgfG862GDaU5Xf47sKekLHf+TEbkhkiVgIGm5R/eWjo6AiKQIcy5xRqO/2p6+N','4853968acbb9e09d51aafe0d1f8a0dcea26d028d1a8a5472ab2c266d81e534251bd0f78ceb6de77fad3d00a95f2f19abb910fb6a7ffa23b0dfdfffaad26b23c64vsyaNLuSrAsjfYFX8V+USJesAP/lc38W+9cfS60FnQ=',0,1,0,130112121571),(142,2147483647,'fe572ae0123c82f0a12eb0a8b640557175b2bcae444e181453d8fb3c45fb58a46839e83d555ac3d726a8b8794d9e2ef15a86bac77aa1143d8f83022cee2fc5bbvnU3o7maWp4RgGoSxznDokrGs1ECEq9kS0EhGlpXeE0=','98db85bfcb516da5bb9f49c0c09d831ea6d32f6089aea2e92d7dc4746361ae012816339ba811fd254c9e4846cd0c09f14de560a6ddd1a4450496b1793b076f3bDrDTxJmtMxLobLVEIuDT2V+mD6eRw3m+6E49hSkhtKxa75SR7PNJ1PyynaoVuZ4T','6c3796fdbb4560c0d0ae8f5ec660e714010e1f7d1e1b2a7863905b48b34d03e8a97826910142ca34b709cd7ea40055b810ecfd2e6c2109905a568b8f12dca4b6BBMGPHzuRN4e9RLk4bYxIX6NjzdZlaxUFZD2kizHOVQ=','304b97c1089e9f8ef02044f19f1086dbbb3d91276e054bfe86c25a9f04389b3cc913b8ac4e23b8555d31599be7a49bf19e102231177596c604d431b75cb0c300PSqE9hR2/Gw0EEq+fW1ZYk/1S2wwQFKvJFgH2ErJFvKonU+R0HqHSDcsuu3wBFaI','412b76e8070d9912745dfbe85c06663a4d3c9cbba241d440704bc0c0d6d8860e93154ff0d32996b5c1031f98d972b5c292201f4cf6afd52333a7a0b8da660a12xmbGG7no7UKM8CLObTcm1giG2on7SUkTaB9v3n8/MyQ=',0,0,0,140512120523),(143,2147483647,'94dcfb50f42cb367cd02072f69de28a0ef7ab540cb63b4701fbea65dfc5da482ca1e2f3dcf4ff91d131ba20150fa42fc8d2c59dad9c2aab25cf60e1ff3d08bbdDGq4jC8wPlX4fI/hW7OnBhWnePebCNXUd8Lz0SWy8TE=','e91f8c269de32e5b12770565ba100e1b1ef551faf3d289a471d7203b5ec7efd8491f0a06e3eadbb0dd6e04dc4275edcca6f1c003aa8ce5376edc79a462ead0b3ujQmSOLbFuHztfwadyag0k3np4P/xRCXyVbNT76F1UeC6GuvOT159FKawcWZxxMi','68b1bf53a1cc6bd790577c8ef04c26b6425f90f1627605a57f2d2889e109b4f4db0b964df177673adc2e767fb79fbb5d03b6a7fac462a19b8af06aba1b327bb1WE+XzuQZDAeX0RwjYLSJiGlD+owkx3c5MqQ6Bm4XDyk=','7c090c3da2f5e76980fedc0f038e7a84a889dca3adf7fe7cb3b258e43bea2a44d2a63cec4b52e77cbb462497dcf7c2e8d91fe9492d01903268b768d642b7ee630viUurNol6FVfY9EwKL0rE8bdk6j523QRX15Rwov0pk2vYm7C6RMJ/31aFbZ7OS/','a5884362a5bcf886117cca04d07be40372fe3ac15f0ee622502506b536a6148d472a38f78dcaef137eea5047e8c891a0af092132cb60212f663e1928b6ebc99biLsY8EkSZP5LOBL0q6AmBs6NkksojZrBH+H6e0+UICw=',0,0,1,110514140564),(144,1,'9428dd5beefb3368b0f584f5be2e184628237c597dccb65c27f267e369264241523b637c3063480e2bba12b976cf1fdcdd64a920af3725c154c556704bad6a80hDLEgM2WVWTS3K/5p5bP18WI9oO1z5WejvNXUQRWQvU=','adbce1e29bc6775f78cf7ae69db473fe4aa7e804e52e16e24dc62e2551cbd2e30565b31b519f160d640c2acaf1622dbe64c0c7f926e90339faf4e5d63e6998955/i/9BRJe6w1inYZwbBScq2H+DUA+OrcoAm3bCsZ8HI=','da4ba5b3910118d59c12c369b0fc70ecad979c34f7487e9cd22706afabc9cbc6d9353a726e3214770f105f972f5de3441b460385fd36481a2b6fe86d79ddbfd1UzD5/rSjDrm8kzDKNymsiL/QOplXO5TjP9X3dBhm6tU=','b3446209e645cdff1ac2b9862e5cdc12edeea6d2c487f5c4d4d1729456efcc9b382ebc17436a0622dfc0eed1212867d332e5c778cc87c3491ea7b1caf6d0cf51dCSHy8SqgmKZzGylBdS0TMq0FDc9vV1EkUE51SCJNINf01bR28vqMrZFkZ2P3dIS','48ce662d240a25b48ed26af0d2cc9df918eb62b8627ec03391cc4a212c9cb0e4187d430b2b013ee5365a947a0721b524b8698559b4d1636b98af803e8253ccadRYsEeLKkbWpxI36BxVG9TpcBW11w59sKGE5PkfQxoUg=',0,1,0,131514090382),(145,98293123,'0f52d53737f0f78c7340e8239b8c8849af26087ffd5a32e3e438c89f8e801080b3a6a9589a25819e330c26386a4d0aa94d450fda83106334c52196f486471a20C6zCjsJpxr0/8iwaN2LM5AzwYQtokqJUBIk0vmYLvBA=','ae46589078285f7d42981cc04d2dc9f74fc85049d11f82dcf6ffe53f3c5ed2dd33bdb0d98502279aaf41a3cf1ae3867245ecfec136f8781f5839dfebbc0da3e5q8UbPLH2jZZI0LZObX1qFMU8jGrAjAOOc41jt+SDscujhhPB030SUuRRLX/zItYM','1aebc03f5d98eed3efeae52fd6da562713870302074bba2c02f2ccd111cab3bb1748cee4f442d71976b42277b474b8fdd3f8b554f6b807029b51176525a5959f9/2pGsSJ/sYNttQTxHifdbq7Dryohkf1cJOJKh+wSqg=','18451245208bee0c618f91f12037eb16802251bdedc58ec0f1a9bb262cef0d1510a34704b1b80d6f731320baf40188e07d7d00497f23c90c42e437b151ae1e52ts4LjHNW2LqRwaSYVf87XWUbz2O0zvWytC0sHkMGIN1uDqYyeKEWRAp4ORxnG/7K','276f9ccfdf383187ee1d23d669b0b6b6fcb8a160c4878bfcc2a1467ca4ab5ccf5ffb6aaa5e2ff3eec9be9c3fe662234daff8440de87f454a72458b3938cd854024VgCxq9JbGeasnWOeDAklWVKhxygwwuikR181kPTGg=',0,0,1,101505000194),(146,20200001,'a7b7a50d9577519d0f272d52e251cc4ed7e3796c31749ed62813851019f0d0f278ea79caf50ce9e54f00fd176ad898796bab5869c33835f500791caecaf4d164hq+xyAPWifcYKx3KKoQ7+h7pLDAlX1IvxrpMMwASeM8=','74ac002eab657829427cba84ea25ebc63bf5f1e5551f684c1750b9e559149efe6b4f0b135d42544a81fc967020c59543bb9468c292fa0d3b2c0787773a697a65b50um3LADcZSi+QBkVX1Q/gmLZkgm2G0SDDFDjuJrkQ=','e3076d03566590570afc6d18838cbe4d30f2c761ac3c44a76a7c974e9765c422e8c86a85fce5cd69d1f16c633f32c605ffcb94fc0818a7b6fe01287a0c55719eqmts03ZA8ARdeE6/zMd5He6AmQw9g2kh1WxiSaz0k0o=','f5d04314bb2afe44a7ec5adb43569d45bea72495bd499eb8589691db01909163b85e8dbe129989a414646ba044db70ed0e23dc38768855d0be4f0636143d00f4FrsIsfG5FuapfKWocxjanXoQhIvJvwwz0wpHmvuGfGY=','1d31cad0cc058e80d28780eb39fecb01491827d7bb26c360cf61bfad4bd7989a8576c03624a1236a9a0baece23a6f5209931d776937453f2a6e8f473db02033fkEXNlTbsBftyjptw1Cdku38lwFkUyUZV5VcYypeBmsc=',0,0,1,130118110027),(147,10013,'7d4fc8805184132273668ae9102eb3ec1a436f273ff84c6342db18cd3a4aa1186c480effcac16e32340227c43bc7b3463266c2dfee6b1261c7e4429ddf1f1709njodI+MjKNPlUmrZ8hFbs/O0QZ3vfuQVV9SxwUdDmkk=','28e95a5e135f5f94a35240f3e4052a694ef5e31d76cef791b1cc54f71017a98b4cc903c4293f477bd34e3700a5cb970cb303667e98d079d7db136bb4044d4dbbtPdMLITA6+4+XE0N6sMcW0S8/y2mkJ3fRgjvSK5/mO8=','424ede938096483553bdb784a36fb38a689153fd3da62f8acc35becf38185cf95f18cf4c69f94d7cd702c89d61f424eee05a0db6accc637b58d8a20298d210ecEClxk4yT7hAJmzeX+Sf1LTZxwU9VbDYdpwqWhy8ya/s=','86ba07d087c7aef2ad537fe9b7d84f20d3deaf2dc320866cc1c2d66f7223a40df9f7ae9e40e8b3f9d6e812809be3b58955c6e24b3ebba426564ba17c23230ae1ScVJac/uAw+KPsNW/1iumZ3Zb1FD3VNKY+wWUSVRxzZ93TrV4jxbK5XEUC3RDS+J','181182187270722a9d575a7e9a22c1c2e33fd933826ab2adc1416db1bce2cae68115cb26b5481287dd2df1f213f28238876fb577c5bfd27738f995112ee848b4A/CANL+/7Ge0PFnPfueq9nvBuQGqhD0sTzRHpT5sFBE=',0,0,0,011209191983),(148,1001,'5afee355036972b0297a1f2500b11f4433dbd14128a4aca2cbd1ad1d3f64193c64ee065513375c0003496577fdc7b45563e58c9a97588b324fed57eefc5f7321LLBfTFa731SAN6BE+OKThG+hyXFSgvctYAZmFa3x5O4=','593f9c55ad48e554360477238c755ee6cbabea0739dcd87aec4182aab3c89f32dbeba8a10f66edda2c3e1e67d08d2e5a06890515fac4f48f499c0ce623bab0c3eiwXr+VnKxASixeLujB8aoTYfK8e9phXmMCxAHZ8Phc=','fbf22555f8e29f10b4d0040b0e4bab84e394249f5713e05f28dbe465d9d926ff6faf27a09241f6caabb35480379005d740b8b3609ff2c98531b231741a6e343b6WY4V33BqDRyHNMrLmd22V0FrOHi0BY4GdUVDzL3hrA=','90adf647a52167ee689baf5002b5a30e3c4be3acf81acaa8ac65786ba9a224e1f0b5dd9018965013c1bb5864f45d0fe765f0e62035447e9cbb07dead2fb176e4U74Yh3TTX/WR4iBvVDyZ1SKKSu4Y30cxlbWYpW2nYjkU89t0Iqho3h1KOz74BGRZ','2dfb6f0a11277b22971e515febd27bb1ebe8f5e0d04c8a762d6a3fc162a5ed99f0b16304b3c9d65fd4f847e5623dc2b18bc85eb7ff3b6c10be0e7bb43338a59aEQeE+T/cUhwrc1ktL4a9nmC6OSuIEwgMvo8Ypp0i96A=',0,0,0,130911050084);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `employeeaffiliate`;:||:Separator:||:


CREATE TABLE `employeeaffiliate` (
  `idEmpAffiliate` int NOT NULL AUTO_INCREMENT,
  `idEmployee` int DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  `selected` int DEFAULT NULL,
  `archived` int DEFAULT '0',
  PRIMARY KEY (`idEmpAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=241 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `employeeaffiliate` WRITE;:||:Separator:||:
 INSERT INTO `employeeaffiliate` VALUES(216,142,2,1,0),(217,143,2,1,1),(224,141,2,1,0),(225,141,12,1,0),(227,145,2,1,1),(228,146,6,1,1),(229,146,5,1,1),(230,146,2,1,1),(231,146,12,1,1),(232,146,14,1,1),(233,146,15,1,1),(234,146,13,1,1),(235,146,8,1,1),(236,146,4,1,1),(237,147,2,1,0),(238,144,12,1,0),(239,144,2,1,0),(240,148,2,1,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `employeeclass`;:||:Separator:||:


CREATE TABLE `employeeclass` (
  `idEmpClass` int NOT NULL AUTO_INCREMENT,
  `empClassName` char(20) DEFAULT NULL,
  `archived` int DEFAULT '0',
  PRIMARY KEY (`idEmpClass`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `employeeclass` WRITE;:||:Separator:||:
 INSERT INTO `employeeclass` VALUES(25,'Probationary',0),(26,'Executive Staff',0),(27,'Senior Staff',0),(31,'Full-time',0),(33,'sample1',1),(34,'sample 2',1),(35,'sample',1),(36,'Temporary',0),(37,'Part-time',0),(38,'cszxc',1),(39,'On-call',0),(40,'Outsider',0),(41,'Test Employee Classi',0),(42,'Driver',0),(43,'Grocer',1),(44,'Grocer',0),(45,'Approver',1),(46,'Contractor',0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `employeehistory`;:||:Separator:||:


CREATE TABLE `employeehistory` (
  `idEmpHistory` int NOT NULL AUTO_INCREMENT,
  `idEmployment` int DEFAULT NULL,
  `idEmployee` int DEFAULT NULL,
  `dateEmployed` text,
  `dateEffective` text,
  `endOfContract` text,
  `classification` int DEFAULT NULL,
  `monthRate` text,
  PRIMARY KEY (`idEmpHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `employeehistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `employment`;:||:Separator:||:


CREATE TABLE `employment` (
  `idEmployment` int NOT NULL AUTO_INCREMENT,
  `idEmployee` int DEFAULT NULL,
  `dateEmployed` text,
  `dateEffective` text,
  `endOfContract` text,
  `classification` int DEFAULT NULL,
  `monthRate` text,
  `archived` int DEFAULT '0',
  PRIMARY KEY (`idEmployment`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `employment` WRITE;:||:Separator:||:
 INSERT INTO `employment` VALUES(82,141,'03264e2c588cd00e7a3e33fa8542dd90558fa1560891c0e50539c417c778a48d2fcc9b10e5064e233b76aec4ef9661e53d9d503dc39c82117dbdf966854d5960+9QRmiw/Agcw58cInBuI0LQqW6cxb/5FZgBNJqN1eN4=','72e46402c6107c2f0988f16f61a6cb2bedd258a49b90a218630cdfa0a93d55769483d25f12f4dbed23cdaaa2db2dc40831bf93e8ed77d5a351a5517656abd9f8hIz8jjRo2jt9EyXge+mrTkg73OQn1Ui9VyIIOI6bW0E=','b2c5fd3ae6e89e9d7a72f5fd7321532da79292f3ab608f5b39f6b0c529ad78e75478c1fd250afac63a650b815fc0e9104ad6fdb791f623ed5696e6f2ca97737cEVKB3oroIE2b9rU7xN1Bn08VCmrNyIlQ+UXUh6xYykQ=',26,'e2029d14a7c3b109421932eea6bc2f73694e6a9dec7e81018a7615a838205a133308c01b80b6d6b2c716263186f6bc6fb1f1a58982f4730378ff53383e081302oevZqp+Q7LuXfSE4qABIygYdj9WtPtOOnOgVE4ATRO0=',0),(83,142,'b367f6bcaadec84202a0331f75801d4b096f5798b15a5b53d7eb327f99402f1da26cc3d18fa59fc16587d16531c6fbaea165c555c27a2e99d8c10a4ff392d5f6g/ORU2vOmTLazb90v/eNfT7vSfe57RnLvlRtTdHBrJQ=','433871b4d55b054e717beff991a0a0775a3fd3b527a0842850c75762ac7a3dc0442199dd85e7098cb53cae68eb7b33da093b2f8b3ff74952b41b9851d65cb0a453hFIgI0sGELPtrK1ep1alaxJ8A3mBq40icvm1HPL2M=','6a88ea905375a806f672b4abdbe9fa998a3ff01914bcf73b0189a579c4744b159286d3648d02e82931b4429b07facfb022292576daad444c92ccbca9aef1dafd7LCLNkEzMSDPABibGfdJUcB9O1koRowgbKW4XK1JbXY=',42,'399a4e8caba1ddeb074080a662c4a2db03bbd118f77a843192e4064439294dc865fb33ca99f5bf80ff8aba914dc5e16ed4ba02ee21dcdb65357a18c529c1302asFoGltHP/wR6MkI3QJHylDwZXTSCyEpEztSUts1Zzjw=',0),(84,143,'ff734dd32f94ab834ac1187f1c48f35f14feaa34d4ab9e28cd426c6c5a8bf01d0fb835f39253e7c4e382bf1a3d8cb62fe64c7750fadf682cbc1368783393aa0ctkwy5KOXZsjEOpxpL4ANLPMishJyJ7h7urnLpagtNhA=','92ec359a6aa0f3b6069b213000c3df0a28d698701e5f53d5c01171770afb47060124574d8d82f4ca6cb903f7d36ce5113f81f98cbf92f8607887d02e7b9ee07f2rM0+sakvC/UnEAbkRRsh2W5vRZwYfqNBlX2mKbmkpE=','dd9afee080a1c77d65f8daca1b76016482f6651db5927a088323028a4225d93e03a6be1de3b8a1595aabe065018de69daab308deff6f22e7447a863c1875fa0cylBkrAfFnOLquRakluOcmAu8Onp0NVEF1DYYYyDEXDc=',42,'a686f2f82f4076eb4fed39523fe628a3e1c6c8f95f15976b274464152153b694b897ef25e960d3cb4c8e2c4c63ba42be5cd53b2c20de73689a2a1bc8dee56f92lSkvGGrySzkB3IYw6ZUcMzib8QYSE15UcFFLyugCYVc=',1),(85,144,'0abc057e182f681cb11da70cc3ec64f2b7ed18512799ff8a4acf6bed63fc4d13850e240c24bad0051a6f94f83f8f27c4aabdb48c1562123ddafb8412780ba0a8IFoHEsXfU1eqSJ54WYEnu6da3XzrlZUAkP/CvlXkLYY=','9807559b90225b81f02dd1c3e86b87e943efef2213809c629fa16e187b872ea347a1e9faab9cc0bb4d8566be44424a85f9e30a535b40bb36cbc8c767dfd2664dSB1nosWPwDLmon8p8fFbt02iddK6hxJRDfxKq6yiCss=','364ba2b4da1160a87c67364e3d16858036e8df67fdfaafb88d8db60994db6e908c11b2d7189559cad77c9587d4331ad0b8635108623628ae67565b9d4c208242jGTox6zq43X7MXfNLDpZJ50lBr6MpjocCtCxSKxKyMA=',31,'8bc041fd84ece3a817ea3214c8e68036258290211437dc2fae3d16c5ef80002d4694a73a0667a4ad05c618435f42c7af53d260c0497ee3ad1d95eec6776b3b06G5fdV/DYi4EMH427cmw/XyNJSWLfDV6yQ1jBbfhsY0A=',0),(86,145,'b500c3fae72d26a900a08a78a726c528c390c6b18c8201a4b7c792c6632d0f2ad3a34d3ef34469143ba3304d5f5881970132ba5ed15b3fb0ebf22d92bab027723xn73F68Ro+DO8IYmmK5iZMg0lUvv6ONBUaig+d6zlw=','4887a1a45559adfc025ac78c173671141150265ead47e6028c24a3f63b015c03847cd90b4184af90fa66268e06e19792724778bf625d9e819d7bba7f4ca57e51tpycCcBJJqgc3nsKjb/TNGoDg6JJch8UU+RTpjtSHJ0=','4b68bbd8a0da250c711c67a6edd78ddb2e46ae11057695ccd0218df201e5baece499b3ae3d453c4eb35d9e7217ee9ee5d7843c08e242959b161b0d1d822f4f3biN5utYO/GVXn4gkCsPPlWDoCj5qA6PKK7iC4XnCv0pc=',46,'89134fedf81d5b7b60e62b78fc6903210f055f8e6b5ff518ce74669f842e6c65e4fbb42ca1456623a8c8fe221f52a4ed77d20680f5e50f6552e7d1b0069d0352ll7bQNvhxcS1cliaHU9MiPXhoMKAmGcOXeqEH+rgpac=',1),(87,146,'aa01f0786cd307873fb0158ac5afaa80b2812cfb8de17a5ca472681cb78fd71bc9a63c3ab816cc04f865da729293f38120db36d2d784286da52f311da99a03f2+NLzQo/f53f8Q7OcI5nsz7V8SQVIp4X40FJ3GUPBC1o=','0e687326be8a1134711a43cd26e55db031d8f932b3b0d5298478a7d4e754022fd2dfc6ac50ba26ffd2e53dc48a0c1d8e6fd6e2b455ea32c2af60c851d041633ep8CxkwbgGHjafU8m1+P1E/5CM0LvsPP7C9zJNtUAGJU=','6781eafef27867cfb885263caa552692855996257bfab78ce45978826afac8219004b36b1352bada88aff692f8c84d90a7a4826e4d8a7c9e476d8168e5dbe83f+U9sU8lFuOXJ1IlPN+1NDqKm/bOEo8iYfERao+rwPVc=',45,'79c380e71b9d0371c9085c0d452561b2c642dc6d59ffa0a56c54983a4dacfa05f8c161545dad1cc724d44faa630ccce3457a2b9a3d4a294dfeb82e0b8d79622aHuETTKxlzr9pCG3+iJPzUwnmh1mWq+j13T5TpxBsgSY=',1),(88,147,'cc0c7b01d5844359f101c87572eee3f4525e92a51ad97fb90d508ecb7f9c1d43c65082282951c851bdbfc53d86ffd8e46fb344894e6f879a9b2f289730e16829jZhFE7VmAcvphR4B6xCK9TijsY/it4S3f3GAEyXo+Zg=','48761734689b1b6519f644e4c4f34dd5da62aff9c07418d96e061ee7d8c2f35e9ad6809598ed3f5df07d2d2723fb34985f942e7b0b65bc4b16adc5a193276faendtVc2rdUCofJyMrz/qKoWRUxLKGZZsXgWOUdNpWSqM=','a1cb379158d88c2aaec38257116e16ac0bda8017b0205f733611aca74e9444e7e5e5b5c854fd52c80fa82c99de6f759849818b3d78f4c3da48ed195760c157e4CYmwwOhyO7a974fuYynV7U4vR00OWB2IhW82j/nn8hQ=',45,'f4489becfd338fc2debd4b068baa83dd58f3da432f24b00684dc23ba0a1d70e35addf919e8f815654c421b4a3418e7eca0a925221b76c61bb23e098f8204ee15tXRDYOT0VXGoT0dmZ3wUxuplubATdevlGYQER5wAsMs=',0),(89,148,'cc3e9fd8b70b09ae701042248c92ed2f8a177e094c0098329769a8fa6e27f47bf67b98c17c1db9c047e58e1a40150893e606947ac9bc77fc40773907a41a9aa9q7MDqc5q9z2obXPwXEc0aPHWr9i9hUaJnyedp8aqmtc=','2435a587a70c40e8475447fd28e7e03a4f7ba8a13c7ab1276b5f7a9256e189492f16926f6c8130342aa4df232fda4ba2970ca37bc312faa5b48b653bdf2778a3HpradwJJYPLNGF6YKwSdZgEXgAaxGx+LLlH2YAB3488=','9ed125fa16c6887d03319dbcd438bc6fe3daa0759d56c56f228320fa18d3562659934ad3a5cb50e7a360f8682c0e01b48c14037ddc2117b4a3f20f2b120bbc96wqgx8YNFlPuzk7XJ5y+S+YH75JuYAT9Fb/MpGKThQro=',46,'637567bb2571f588b4432bdfaa8b4601d3b505314f820019353195746ef4e7b9b44ea063157f81e3c3c29477747675c3377df340ebea99a052c55ac29b90c72480uTB1DOqnFpW/Qc2J118jMdkHnrBy3h61xw0IfUh3w=',0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `employmenthistorydate`;:||:Separator:||:


CREATE TABLE `employmenthistorydate` (
  `idEmpHistory` int NOT NULL AUTO_INCREMENT,
  `idEmployee` int DEFAULT NULL,
  `dateEmployed` text,
  `dateEffective` text,
  `endOfContract` text,
  `classification` int DEFAULT NULL,
  `monthRate` text,
  PRIMARY KEY (`idEmpHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=269 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `employmenthistorydate` WRITE;:||:Separator:||:
 INSERT INTO `employmenthistorydate` VALUES(256,141,'66be1448795060c315748793f77429c3808fbc7a8c3c5fad480fcbed79c109323ce17383b36c76a0aab60ee5f5f9ade4aed59aead527c7bdd6bf5b05bc831ad299DeXCC+wmLmulddH2K5ui6IggIOPDzcTfozW8hxPWw=','479ccd874690169e288dacf2982087d73e5f3ecf64c4654e9218c098863dc41f98cb2310104a7a1a6baf5c05459769b5d28536a06e003153a0eb5f012314bfb0VYqHS98wKESE6Gs0gHFZDG5NpgMbM0K9iuXpueqDg50=','92828a34d6046b12dcaacb37e15c5fcbcae432216084c3655134022d76d8a29a90629195a1d65ecca5dfdc5e10613788ae775dc110b287b64f4fa6692f9a3178CX2c6BJOS23F8BgHHq/eJaE4T5ChhjU6BcOk1280JBk=',26,'c2ad0c2e22d50e4728eedf0217dd53b8ae05a2e9fba06c08a2e08aef8a9e99c14e72c98ad6b0d25571dbba17cbf6dee88ab42634c359347c3d66210d0eb51b35VgfrugIL1lSvkt+1zKnm0h/kJ6BW4YkZUpItcU+9NS4='),(257,142,'b367f6bcaadec84202a0331f75801d4b096f5798b15a5b53d7eb327f99402f1da26cc3d18fa59fc16587d16531c6fbaea165c555c27a2e99d8c10a4ff392d5f6g/ORU2vOmTLazb90v/eNfT7vSfe57RnLvlRtTdHBrJQ=','433871b4d55b054e717beff991a0a0775a3fd3b527a0842850c75762ac7a3dc0442199dd85e7098cb53cae68eb7b33da093b2f8b3ff74952b41b9851d65cb0a453hFIgI0sGELPtrK1ep1alaxJ8A3mBq40icvm1HPL2M=','6a88ea905375a806f672b4abdbe9fa998a3ff01914bcf73b0189a579c4744b159286d3648d02e82931b4429b07facfb022292576daad444c92ccbca9aef1dafd7LCLNkEzMSDPABibGfdJUcB9O1koRowgbKW4XK1JbXY=',42,'399a4e8caba1ddeb074080a662c4a2db03bbd118f77a843192e4064439294dc865fb33ca99f5bf80ff8aba914dc5e16ed4ba02ee21dcdb65357a18c529c1302asFoGltHP/wR6MkI3QJHylDwZXTSCyEpEztSUts1Zzjw='),(258,143,'ff734dd32f94ab834ac1187f1c48f35f14feaa34d4ab9e28cd426c6c5a8bf01d0fb835f39253e7c4e382bf1a3d8cb62fe64c7750fadf682cbc1368783393aa0ctkwy5KOXZsjEOpxpL4ANLPMishJyJ7h7urnLpagtNhA=','92ec359a6aa0f3b6069b213000c3df0a28d698701e5f53d5c01171770afb47060124574d8d82f4ca6cb903f7d36ce5113f81f98cbf92f8607887d02e7b9ee07f2rM0+sakvC/UnEAbkRRsh2W5vRZwYfqNBlX2mKbmkpE=','dd9afee080a1c77d65f8daca1b76016482f6651db5927a088323028a4225d93e03a6be1de3b8a1595aabe065018de69daab308deff6f22e7447a863c1875fa0cylBkrAfFnOLquRakluOcmAu8Onp0NVEF1DYYYyDEXDc=',42,'a686f2f82f4076eb4fed39523fe628a3e1c6c8f95f15976b274464152153b694b897ef25e960d3cb4c8e2c4c63ba42be5cd53b2c20de73689a2a1bc8dee56f92lSkvGGrySzkB3IYw6ZUcMzib8QYSE15UcFFLyugCYVc='),(259,144,'c4fc4dc7d5a5947b7fe00d94d57651b27b3b3a7c14d756b10191da80c67d81e669fcfe431508e79ae892806e60e2dec0eec78554e43d55582009e1edcd08d81eD94OzoKN1uaTcnkWmiaNno0F9vw+70FUuQ4I817xXU8=','68bc96b5f43ad2de293a974adeb6492fe17121d85e3a6d6986bc9c5ead117af15a14d8148f0c1bf76096ce88e6f37ba9a385c5682d144b7a2b6e3fefdae57c6b9bp9umoGKgv3WozV29b6eEbLC1L7lu1BUQH1B00E8Fw=','b9854c9a3f28d680b9b963e5be1627e7a86530f27324d3076540bacac23d2775beefb3d7f1e24f8eac7cc3bb20bbacbba5215ce9d04ddbbf35f6d1ed5708c65aqAgm/E4mhZ8ZYcgvcq3dTQUWo7mjKpHEZNRz878oxOY=',31,'36877e6cb9c6c83faff5bf42b34aea15cf75fa6866f518903d3324d1905ba4274ea4f9c5438d6de5db9d79f8a8ea276bd1dcacc21a67ad8ddedf18f655c11d87B1OlZF3OH9yB6xZeVR7I5SScnM9xeSS6RNKDV/ZqzXQ='),(260,144,'18f835b6e74dcf28479c0e0a46ed6e61e8c458da814f5dbc39c4b655d34dde9ee8830c909dcea4172bb656433b87e5e3f128e4d5df676605de01f11656981b63tfnV3AOHHnXhrVe4uABFDMP0uMKbYZfb5EzIkpcogrM=','c7dca788aed0a9f3d1e5ba8ec8b5023e538d488c8314355608cc3f40becd553b52988c711437be35f78704c7b76a23a0c5dbe7f0c93fa06013071bbfc60e77631O2AgsYQY7OsEwHo3GnntmzY6FUA74H8C/B5L/nqwWE=','9285f9101e3a4323dd0571cc03a71ddb58db70ddaa8f16e573c080974fe9f6e58eb4b8798bb9c9b631120866a85a17d603a4c05a5afc30d1ac07c42dbcea01a8bi0I025N09o1sZWsXORk1VyXZwufYqL0S+YnVE18Kns=',31,'787131ce1e94d44d2a2d7615bee11db742ae0a8c9f879c04abf6bfcdce2233e0f2d4654d3c3d6a0c40b0744d70706ff5f5f67958515b679e9cff39a28c2dbb25QTeBzGV54cXHuzrLSSQmV/w619+2Iy/agU1uufojmrg='),(261,144,'24679caf0bfd4b3eee8bd7fe4b92b0fbbe242a9cfabf846da4787b6452821527e78979faa3d2f574dd150df0d10e79d1d19891c64238b1545685045d47b3d5fdJEdH/3jSXAdeBO143umjn03nVROVjVr8AL0du4mAuFM=','d8979338e478439c8c7bd30d2f6d6bfd707bf4f2b46c58d903cff300b634cd8141379c0d439d4b53a17bda6e50e432b641037e9bd2833128ee7f09ff398ac263rqFfiLxRpJ5jE1XVgPgaZ06Ld3iYzQ7up5Qk+n9eNOc=','bbe60fb41253f5be87324d945a4189be37181896961298a59ad5ed3726a4386efc00906ac6671129fe31422a15c7e74dd23eecf8f70ae29979ff28b5a6fd1696g8Tz6kg7DhpdXr9CIBzpg2/dyo1kwWeDa/9J0jshsqk=',31,'9bde5dc8245ca3372b7ee2d76b88226f889db173ae380d488157fe7f5da6f7af9dcc4450c8d019952248d4949a0b52b6877a45beb45f6efa7117c1135844e10cvBFA2gebKHzLsCWdV0YF4cF/5zX+J7wrzwTW+bN+Db0='),(262,141,'03264e2c588cd00e7a3e33fa8542dd90558fa1560891c0e50539c417c778a48d2fcc9b10e5064e233b76aec4ef9661e53d9d503dc39c82117dbdf966854d5960+9QRmiw/Agcw58cInBuI0LQqW6cxb/5FZgBNJqN1eN4=','72e46402c6107c2f0988f16f61a6cb2bedd258a49b90a218630cdfa0a93d55769483d25f12f4dbed23cdaaa2db2dc40831bf93e8ed77d5a351a5517656abd9f8hIz8jjRo2jt9EyXge+mrTkg73OQn1Ui9VyIIOI6bW0E=','b2c5fd3ae6e89e9d7a72f5fd7321532da79292f3ab608f5b39f6b0c529ad78e75478c1fd250afac63a650b815fc0e9104ad6fdb791f623ed5696e6f2ca97737cEVKB3oroIE2b9rU7xN1Bn08VCmrNyIlQ+UXUh6xYykQ=',26,'e2029d14a7c3b109421932eea6bc2f73694e6a9dec7e81018a7615a838205a133308c01b80b6d6b2c716263186f6bc6fb1f1a58982f4730378ff53383e081302oevZqp+Q7LuXfSE4qABIygYdj9WtPtOOnOgVE4ATRO0='),(263,145,'d7b25161f1d1749b6532aa2416e38f5bd1e79b4d73215c4589c9392d8ca3a086e196e74dc79d336c7124a10f1b1594ee9fef0b4d9204b592ef384fcbc4b683d7riSeQBCORK45AHgeRf4trCTgAkjJYpKkqLCl17nqNZU=','589a40cfd77a910c7505f2420a41a8e44bb50af4a498e4255849f94c5315984ae6ee9d43415b5ae3063c2d78f7bd5687f3005f2d5c52f996059d9016da35dc7eEzMHZuOHmklCYfEHbgetBZE+0EsdiivT1v8de+wvGoY=','b1a8e5bada408ac321efc4d5e6edf68e9caebb632799ef0b9937de0923cddba7b878221ad835f4245a5e2c763aabd4d64c97dbae8847d1a4a9916c833be6cabbGc6RltUP808QhF8kr/CEzfmMt2XXFvgwoEj3e7XNt5A=',31,'b0291ec71cf2c1c85c9cccd8c343d2a0bc0ed8841272f73cc275f3e84ce177db5e1b6ee34e4022bf126ba6c5e4b968fe75bfefc0a46f487d84d6389140acaccclU3RAuQRviaXm5Q5qZnipxrb1oQpetkQbSP9h7b6N6M='),(264,145,'b500c3fae72d26a900a08a78a726c528c390c6b18c8201a4b7c792c6632d0f2ad3a34d3ef34469143ba3304d5f5881970132ba5ed15b3fb0ebf22d92bab027723xn73F68Ro+DO8IYmmK5iZMg0lUvv6ONBUaig+d6zlw=','4887a1a45559adfc025ac78c173671141150265ead47e6028c24a3f63b015c03847cd90b4184af90fa66268e06e19792724778bf625d9e819d7bba7f4ca57e51tpycCcBJJqgc3nsKjb/TNGoDg6JJch8UU+RTpjtSHJ0=','4b68bbd8a0da250c711c67a6edd78ddb2e46ae11057695ccd0218df201e5baece499b3ae3d453c4eb35d9e7217ee9ee5d7843c08e242959b161b0d1d822f4f3biN5utYO/GVXn4gkCsPPlWDoCj5qA6PKK7iC4XnCv0pc=',46,'89134fedf81d5b7b60e62b78fc6903210f055f8e6b5ff518ce74669f842e6c65e4fbb42ca1456623a8c8fe221f52a4ed77d20680f5e50f6552e7d1b0069d0352ll7bQNvhxcS1cliaHU9MiPXhoMKAmGcOXeqEH+rgpac='),(265,146,'aa01f0786cd307873fb0158ac5afaa80b2812cfb8de17a5ca472681cb78fd71bc9a63c3ab816cc04f865da729293f38120db36d2d784286da52f311da99a03f2+NLzQo/f53f8Q7OcI5nsz7V8SQVIp4X40FJ3GUPBC1o=','0e687326be8a1134711a43cd26e55db031d8f932b3b0d5298478a7d4e754022fd2dfc6ac50ba26ffd2e53dc48a0c1d8e6fd6e2b455ea32c2af60c851d041633ep8CxkwbgGHjafU8m1+P1E/5CM0LvsPP7C9zJNtUAGJU=','6781eafef27867cfb885263caa552692855996257bfab78ce45978826afac8219004b36b1352bada88aff692f8c84d90a7a4826e4d8a7c9e476d8168e5dbe83f+U9sU8lFuOXJ1IlPN+1NDqKm/bOEo8iYfERao+rwPVc=',45,'79c380e71b9d0371c9085c0d452561b2c642dc6d59ffa0a56c54983a4dacfa05f8c161545dad1cc724d44faa630ccce3457a2b9a3d4a294dfeb82e0b8d79622aHuETTKxlzr9pCG3+iJPzUwnmh1mWq+j13T5TpxBsgSY='),(266,147,'cc0c7b01d5844359f101c87572eee3f4525e92a51ad97fb90d508ecb7f9c1d43c65082282951c851bdbfc53d86ffd8e46fb344894e6f879a9b2f289730e16829jZhFE7VmAcvphR4B6xCK9TijsY/it4S3f3GAEyXo+Zg=','48761734689b1b6519f644e4c4f34dd5da62aff9c07418d96e061ee7d8c2f35e9ad6809598ed3f5df07d2d2723fb34985f942e7b0b65bc4b16adc5a193276faendtVc2rdUCofJyMrz/qKoWRUxLKGZZsXgWOUdNpWSqM=','a1cb379158d88c2aaec38257116e16ac0bda8017b0205f733611aca74e9444e7e5e5b5c854fd52c80fa82c99de6f759849818b3d78f4c3da48ed195760c157e4CYmwwOhyO7a974fuYynV7U4vR00OWB2IhW82j/nn8hQ=',45,'f4489becfd338fc2debd4b068baa83dd58f3da432f24b00684dc23ba0a1d70e35addf919e8f815654c421b4a3418e7eca0a925221b76c61bb23e098f8204ee15tXRDYOT0VXGoT0dmZ3wUxuplubATdevlGYQER5wAsMs='),(267,144,'0abc057e182f681cb11da70cc3ec64f2b7ed18512799ff8a4acf6bed63fc4d13850e240c24bad0051a6f94f83f8f27c4aabdb48c1562123ddafb8412780ba0a8IFoHEsXfU1eqSJ54WYEnu6da3XzrlZUAkP/CvlXkLYY=','9807559b90225b81f02dd1c3e86b87e943efef2213809c629fa16e187b872ea347a1e9faab9cc0bb4d8566be44424a85f9e30a535b40bb36cbc8c767dfd2664dSB1nosWPwDLmon8p8fFbt02iddK6hxJRDfxKq6yiCss=','364ba2b4da1160a87c67364e3d16858036e8df67fdfaafb88d8db60994db6e908c11b2d7189559cad77c9587d4331ad0b8635108623628ae67565b9d4c208242jGTox6zq43X7MXfNLDpZJ50lBr6MpjocCtCxSKxKyMA=',31,'8bc041fd84ece3a817ea3214c8e68036258290211437dc2fae3d16c5ef80002d4694a73a0667a4ad05c618435f42c7af53d260c0497ee3ad1d95eec6776b3b06G5fdV/DYi4EMH427cmw/XyNJSWLfDV6yQ1jBbfhsY0A='),(268,148,'cc3e9fd8b70b09ae701042248c92ed2f8a177e094c0098329769a8fa6e27f47bf67b98c17c1db9c047e58e1a40150893e606947ac9bc77fc40773907a41a9aa9q7MDqc5q9z2obXPwXEc0aPHWr9i9hUaJnyedp8aqmtc=','2435a587a70c40e8475447fd28e7e03a4f7ba8a13c7ab1276b5f7a9256e189492f16926f6c8130342aa4df232fda4ba2970ca37bc312faa5b48b653bdf2778a3HpradwJJYPLNGF6YKwSdZgEXgAaxGx+LLlH2YAB3488=','9ed125fa16c6887d03319dbcd438bc6fe3daa0759d56c56f228320fa18d3562659934ad3a5cb50e7a360f8682c0e01b48c14037ddc2117b4a3f20f2b120bbc96wqgx8YNFlPuzk7XJ5y+S+YH75JuYAT9Fb/MpGKThQro=',46,'637567bb2571f588b4432bdfaa8b4601d3b505314f820019353195746ef4e7b9b44ea063157f81e3c3c29477747675c3377df340ebea99a052c55ac29b90c72480uTB1DOqnFpW/Qc2J118jMdkHnrBy3h61xw0IfUh3w=');:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `employmenthistoryposition`;:||:Separator:||:


CREATE TABLE `employmenthistoryposition` (
  `idEmpHistory` int NOT NULL AUTO_INCREMENT,
  `idEmployee` int DEFAULT NULL,
  `dateEmployed` text,
  `dateEffective` text,
  `endOfContract` text,
  `classification` int DEFAULT NULL,
  `monthRate` text,
  PRIMARY KEY (`idEmpHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=266 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `employmenthistoryposition` WRITE;:||:Separator:||:
 INSERT INTO `employmenthistoryposition` VALUES(253,141,'66be1448795060c315748793f77429c3808fbc7a8c3c5fad480fcbed79c109323ce17383b36c76a0aab60ee5f5f9ade4aed59aead527c7bdd6bf5b05bc831ad299DeXCC+wmLmulddH2K5ui6IggIOPDzcTfozW8hxPWw=','479ccd874690169e288dacf2982087d73e5f3ecf64c4654e9218c098863dc41f98cb2310104a7a1a6baf5c05459769b5d28536a06e003153a0eb5f012314bfb0VYqHS98wKESE6Gs0gHFZDG5NpgMbM0K9iuXpueqDg50=','92828a34d6046b12dcaacb37e15c5fcbcae432216084c3655134022d76d8a29a90629195a1d65ecca5dfdc5e10613788ae775dc110b287b64f4fa6692f9a3178CX2c6BJOS23F8BgHHq/eJaE4T5ChhjU6BcOk1280JBk=',26,'c2ad0c2e22d50e4728eedf0217dd53b8ae05a2e9fba06c08a2e08aef8a9e99c14e72c98ad6b0d25571dbba17cbf6dee88ab42634c359347c3d66210d0eb51b35VgfrugIL1lSvkt+1zKnm0h/kJ6BW4YkZUpItcU+9NS4='),(254,142,'b367f6bcaadec84202a0331f75801d4b096f5798b15a5b53d7eb327f99402f1da26cc3d18fa59fc16587d16531c6fbaea165c555c27a2e99d8c10a4ff392d5f6g/ORU2vOmTLazb90v/eNfT7vSfe57RnLvlRtTdHBrJQ=','433871b4d55b054e717beff991a0a0775a3fd3b527a0842850c75762ac7a3dc0442199dd85e7098cb53cae68eb7b33da093b2f8b3ff74952b41b9851d65cb0a453hFIgI0sGELPtrK1ep1alaxJ8A3mBq40icvm1HPL2M=','6a88ea905375a806f672b4abdbe9fa998a3ff01914bcf73b0189a579c4744b159286d3648d02e82931b4429b07facfb022292576daad444c92ccbca9aef1dafd7LCLNkEzMSDPABibGfdJUcB9O1koRowgbKW4XK1JbXY=',42,'399a4e8caba1ddeb074080a662c4a2db03bbd118f77a843192e4064439294dc865fb33ca99f5bf80ff8aba914dc5e16ed4ba02ee21dcdb65357a18c529c1302asFoGltHP/wR6MkI3QJHylDwZXTSCyEpEztSUts1Zzjw='),(255,143,'ff734dd32f94ab834ac1187f1c48f35f14feaa34d4ab9e28cd426c6c5a8bf01d0fb835f39253e7c4e382bf1a3d8cb62fe64c7750fadf682cbc1368783393aa0ctkwy5KOXZsjEOpxpL4ANLPMishJyJ7h7urnLpagtNhA=','92ec359a6aa0f3b6069b213000c3df0a28d698701e5f53d5c01171770afb47060124574d8d82f4ca6cb903f7d36ce5113f81f98cbf92f8607887d02e7b9ee07f2rM0+sakvC/UnEAbkRRsh2W5vRZwYfqNBlX2mKbmkpE=','dd9afee080a1c77d65f8daca1b76016482f6651db5927a088323028a4225d93e03a6be1de3b8a1595aabe065018de69daab308deff6f22e7447a863c1875fa0cylBkrAfFnOLquRakluOcmAu8Onp0NVEF1DYYYyDEXDc=',42,'a686f2f82f4076eb4fed39523fe628a3e1c6c8f95f15976b274464152153b694b897ef25e960d3cb4c8e2c4c63ba42be5cd53b2c20de73689a2a1bc8dee56f92lSkvGGrySzkB3IYw6ZUcMzib8QYSE15UcFFLyugCYVc='),(256,144,'c4fc4dc7d5a5947b7fe00d94d57651b27b3b3a7c14d756b10191da80c67d81e669fcfe431508e79ae892806e60e2dec0eec78554e43d55582009e1edcd08d81eD94OzoKN1uaTcnkWmiaNno0F9vw+70FUuQ4I817xXU8=','68bc96b5f43ad2de293a974adeb6492fe17121d85e3a6d6986bc9c5ead117af15a14d8148f0c1bf76096ce88e6f37ba9a385c5682d144b7a2b6e3fefdae57c6b9bp9umoGKgv3WozV29b6eEbLC1L7lu1BUQH1B00E8Fw=','b9854c9a3f28d680b9b963e5be1627e7a86530f27324d3076540bacac23d2775beefb3d7f1e24f8eac7cc3bb20bbacbba5215ce9d04ddbbf35f6d1ed5708c65aqAgm/E4mhZ8ZYcgvcq3dTQUWo7mjKpHEZNRz878oxOY=',31,'36877e6cb9c6c83faff5bf42b34aea15cf75fa6866f518903d3324d1905ba4274ea4f9c5438d6de5db9d79f8a8ea276bd1dcacc21a67ad8ddedf18f655c11d87B1OlZF3OH9yB6xZeVR7I5SScnM9xeSS6RNKDV/ZqzXQ='),(257,144,'18f835b6e74dcf28479c0e0a46ed6e61e8c458da814f5dbc39c4b655d34dde9ee8830c909dcea4172bb656433b87e5e3f128e4d5df676605de01f11656981b63tfnV3AOHHnXhrVe4uABFDMP0uMKbYZfb5EzIkpcogrM=','c7dca788aed0a9f3d1e5ba8ec8b5023e538d488c8314355608cc3f40becd553b52988c711437be35f78704c7b76a23a0c5dbe7f0c93fa06013071bbfc60e77631O2AgsYQY7OsEwHo3GnntmzY6FUA74H8C/B5L/nqwWE=','9285f9101e3a4323dd0571cc03a71ddb58db70ddaa8f16e573c080974fe9f6e58eb4b8798bb9c9b631120866a85a17d603a4c05a5afc30d1ac07c42dbcea01a8bi0I025N09o1sZWsXORk1VyXZwufYqL0S+YnVE18Kns=',31,'787131ce1e94d44d2a2d7615bee11db742ae0a8c9f879c04abf6bfcdce2233e0f2d4654d3c3d6a0c40b0744d70706ff5f5f67958515b679e9cff39a28c2dbb25QTeBzGV54cXHuzrLSSQmV/w619+2Iy/agU1uufojmrg='),(258,144,'24679caf0bfd4b3eee8bd7fe4b92b0fbbe242a9cfabf846da4787b6452821527e78979faa3d2f574dd150df0d10e79d1d19891c64238b1545685045d47b3d5fdJEdH/3jSXAdeBO143umjn03nVROVjVr8AL0du4mAuFM=','d8979338e478439c8c7bd30d2f6d6bfd707bf4f2b46c58d903cff300b634cd8141379c0d439d4b53a17bda6e50e432b641037e9bd2833128ee7f09ff398ac263rqFfiLxRpJ5jE1XVgPgaZ06Ld3iYzQ7up5Qk+n9eNOc=','bbe60fb41253f5be87324d945a4189be37181896961298a59ad5ed3726a4386efc00906ac6671129fe31422a15c7e74dd23eecf8f70ae29979ff28b5a6fd1696g8Tz6kg7DhpdXr9CIBzpg2/dyo1kwWeDa/9J0jshsqk=',31,'9bde5dc8245ca3372b7ee2d76b88226f889db173ae380d488157fe7f5da6f7af9dcc4450c8d019952248d4949a0b52b6877a45beb45f6efa7117c1135844e10cvBFA2gebKHzLsCWdV0YF4cF/5zX+J7wrzwTW+bN+Db0='),(259,141,'03264e2c588cd00e7a3e33fa8542dd90558fa1560891c0e50539c417c778a48d2fcc9b10e5064e233b76aec4ef9661e53d9d503dc39c82117dbdf966854d5960+9QRmiw/Agcw58cInBuI0LQqW6cxb/5FZgBNJqN1eN4=','72e46402c6107c2f0988f16f61a6cb2bedd258a49b90a218630cdfa0a93d55769483d25f12f4dbed23cdaaa2db2dc40831bf93e8ed77d5a351a5517656abd9f8hIz8jjRo2jt9EyXge+mrTkg73OQn1Ui9VyIIOI6bW0E=','b2c5fd3ae6e89e9d7a72f5fd7321532da79292f3ab608f5b39f6b0c529ad78e75478c1fd250afac63a650b815fc0e9104ad6fdb791f623ed5696e6f2ca97737cEVKB3oroIE2b9rU7xN1Bn08VCmrNyIlQ+UXUh6xYykQ=',26,'e2029d14a7c3b109421932eea6bc2f73694e6a9dec7e81018a7615a838205a133308c01b80b6d6b2c716263186f6bc6fb1f1a58982f4730378ff53383e081302oevZqp+Q7LuXfSE4qABIygYdj9WtPtOOnOgVE4ATRO0='),(260,145,'d7b25161f1d1749b6532aa2416e38f5bd1e79b4d73215c4589c9392d8ca3a086e196e74dc79d336c7124a10f1b1594ee9fef0b4d9204b592ef384fcbc4b683d7riSeQBCORK45AHgeRf4trCTgAkjJYpKkqLCl17nqNZU=','589a40cfd77a910c7505f2420a41a8e44bb50af4a498e4255849f94c5315984ae6ee9d43415b5ae3063c2d78f7bd5687f3005f2d5c52f996059d9016da35dc7eEzMHZuOHmklCYfEHbgetBZE+0EsdiivT1v8de+wvGoY=','b1a8e5bada408ac321efc4d5e6edf68e9caebb632799ef0b9937de0923cddba7b878221ad835f4245a5e2c763aabd4d64c97dbae8847d1a4a9916c833be6cabbGc6RltUP808QhF8kr/CEzfmMt2XXFvgwoEj3e7XNt5A=',31,'b0291ec71cf2c1c85c9cccd8c343d2a0bc0ed8841272f73cc275f3e84ce177db5e1b6ee34e4022bf126ba6c5e4b968fe75bfefc0a46f487d84d6389140acaccclU3RAuQRviaXm5Q5qZnipxrb1oQpetkQbSP9h7b6N6M='),(261,145,'b500c3fae72d26a900a08a78a726c528c390c6b18c8201a4b7c792c6632d0f2ad3a34d3ef34469143ba3304d5f5881970132ba5ed15b3fb0ebf22d92bab027723xn73F68Ro+DO8IYmmK5iZMg0lUvv6ONBUaig+d6zlw=','4887a1a45559adfc025ac78c173671141150265ead47e6028c24a3f63b015c03847cd90b4184af90fa66268e06e19792724778bf625d9e819d7bba7f4ca57e51tpycCcBJJqgc3nsKjb/TNGoDg6JJch8UU+RTpjtSHJ0=','4b68bbd8a0da250c711c67a6edd78ddb2e46ae11057695ccd0218df201e5baece499b3ae3d453c4eb35d9e7217ee9ee5d7843c08e242959b161b0d1d822f4f3biN5utYO/GVXn4gkCsPPlWDoCj5qA6PKK7iC4XnCv0pc=',46,'89134fedf81d5b7b60e62b78fc6903210f055f8e6b5ff518ce74669f842e6c65e4fbb42ca1456623a8c8fe221f52a4ed77d20680f5e50f6552e7d1b0069d0352ll7bQNvhxcS1cliaHU9MiPXhoMKAmGcOXeqEH+rgpac='),(262,146,'aa01f0786cd307873fb0158ac5afaa80b2812cfb8de17a5ca472681cb78fd71bc9a63c3ab816cc04f865da729293f38120db36d2d784286da52f311da99a03f2+NLzQo/f53f8Q7OcI5nsz7V8SQVIp4X40FJ3GUPBC1o=','0e687326be8a1134711a43cd26e55db031d8f932b3b0d5298478a7d4e754022fd2dfc6ac50ba26ffd2e53dc48a0c1d8e6fd6e2b455ea32c2af60c851d041633ep8CxkwbgGHjafU8m1+P1E/5CM0LvsPP7C9zJNtUAGJU=','6781eafef27867cfb885263caa552692855996257bfab78ce45978826afac8219004b36b1352bada88aff692f8c84d90a7a4826e4d8a7c9e476d8168e5dbe83f+U9sU8lFuOXJ1IlPN+1NDqKm/bOEo8iYfERao+rwPVc=',45,'79c380e71b9d0371c9085c0d452561b2c642dc6d59ffa0a56c54983a4dacfa05f8c161545dad1cc724d44faa630ccce3457a2b9a3d4a294dfeb82e0b8d79622aHuETTKxlzr9pCG3+iJPzUwnmh1mWq+j13T5TpxBsgSY='),(263,147,'cc0c7b01d5844359f101c87572eee3f4525e92a51ad97fb90d508ecb7f9c1d43c65082282951c851bdbfc53d86ffd8e46fb344894e6f879a9b2f289730e16829jZhFE7VmAcvphR4B6xCK9TijsY/it4S3f3GAEyXo+Zg=','48761734689b1b6519f644e4c4f34dd5da62aff9c07418d96e061ee7d8c2f35e9ad6809598ed3f5df07d2d2723fb34985f942e7b0b65bc4b16adc5a193276faendtVc2rdUCofJyMrz/qKoWRUxLKGZZsXgWOUdNpWSqM=','a1cb379158d88c2aaec38257116e16ac0bda8017b0205f733611aca74e9444e7e5e5b5c854fd52c80fa82c99de6f759849818b3d78f4c3da48ed195760c157e4CYmwwOhyO7a974fuYynV7U4vR00OWB2IhW82j/nn8hQ=',45,'f4489becfd338fc2debd4b068baa83dd58f3da432f24b00684dc23ba0a1d70e35addf919e8f815654c421b4a3418e7eca0a925221b76c61bb23e098f8204ee15tXRDYOT0VXGoT0dmZ3wUxuplubATdevlGYQER5wAsMs='),(264,144,'0abc057e182f681cb11da70cc3ec64f2b7ed18512799ff8a4acf6bed63fc4d13850e240c24bad0051a6f94f83f8f27c4aabdb48c1562123ddafb8412780ba0a8IFoHEsXfU1eqSJ54WYEnu6da3XzrlZUAkP/CvlXkLYY=','9807559b90225b81f02dd1c3e86b87e943efef2213809c629fa16e187b872ea347a1e9faab9cc0bb4d8566be44424a85f9e30a535b40bb36cbc8c767dfd2664dSB1nosWPwDLmon8p8fFbt02iddK6hxJRDfxKq6yiCss=','364ba2b4da1160a87c67364e3d16858036e8df67fdfaafb88d8db60994db6e908c11b2d7189559cad77c9587d4331ad0b8635108623628ae67565b9d4c208242jGTox6zq43X7MXfNLDpZJ50lBr6MpjocCtCxSKxKyMA=',31,'8bc041fd84ece3a817ea3214c8e68036258290211437dc2fae3d16c5ef80002d4694a73a0667a4ad05c618435f42c7af53d260c0497ee3ad1d95eec6776b3b06G5fdV/DYi4EMH427cmw/XyNJSWLfDV6yQ1jBbfhsY0A='),(265,148,'cc3e9fd8b70b09ae701042248c92ed2f8a177e094c0098329769a8fa6e27f47bf67b98c17c1db9c047e58e1a40150893e606947ac9bc77fc40773907a41a9aa9q7MDqc5q9z2obXPwXEc0aPHWr9i9hUaJnyedp8aqmtc=','2435a587a70c40e8475447fd28e7e03a4f7ba8a13c7ab1276b5f7a9256e189492f16926f6c8130342aa4df232fda4ba2970ca37bc312faa5b48b653bdf2778a3HpradwJJYPLNGF6YKwSdZgEXgAaxGx+LLlH2YAB3488=','9ed125fa16c6887d03319dbcd438bc6fe3daa0759d56c56f228320fa18d3562659934ad3a5cb50e7a360f8682c0e01b48c14037ddc2117b4a3f20f2b120bbc96wqgx8YNFlPuzk7XJ5y+S+YH75JuYAT9Fb/MpGKThQro=',46,'637567bb2571f588b4432bdfaa8b4601d3b505314f820019353195746ef4e7b9b44ea063157f81e3c3c29477747675c3377df340ebea99a052c55ac29b90c72480uTB1DOqnFpW/Qc2J118jMdkHnrBy3h61xw0IfUh3w=');:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `eu`;:||:Separator:||:


CREATE TABLE `eu` (
  `idEu` int NOT NULL AUTO_INCREMENT,
  `idEmployee` int DEFAULT NULL,
  `username` char(150) DEFAULT NULL,
  `userType` int DEFAULT NULL COMMENT '1 - Administrator\n2 - Supervisor\n3 - User',
  `password` char(100) DEFAULT NULL,
  `archived` int DEFAULT '0',
  PRIMARY KEY (`idEu`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `eu` WRITE;:||:Separator:||:
 INSERT INTO `eu` VALUES(60,141,'superddt',1,'402e7a087747cb56c718bde84651f96a',0),(61,144,'mg_staff',3,'5df8c1c796facb114133aac32f25fe40',0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `gl`;:||:Separator:||:


CREATE TABLE `gl` (
  `idGl` int NOT NULL AUTO_INCREMENT,
  `idCoa` int DEFAULT NULL,
  `idInvoice` int DEFAULT NULL,
  `glYear` int DEFAULT NULL,
  `glAmount` decimal(18,2) DEFAULT '0.00',
  `idAffiliate` int DEFAULT NULL,
  `month` int DEFAULT NULL COMMENT '1 - January\n2 - February\n3 - March\n4 - April\n5 - May\n6 - June\n7 - July\n8 - August\n9 - September\n10 - October\n11 - November\n12 - December',
  `debit` decimal(18,2) DEFAULT '0.00',
  `credit` decimal(18,2) DEFAULT '0.00',
  PRIMARY KEY (`idGl`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `gl` WRITE;:||:Separator:||:
 INSERT INTO `gl` VALUES(1,1101000,58,2018,1000.00,2,3,1000.00,0.00),(2,5102001,58,2018,-1000.00,2,3,0.00,1000.00);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `glhistory`;:||:Separator:||:


CREATE TABLE `glhistory` (
  `idGlHistory` int NOT NULL AUTO_INCREMENT,
  `idGl` int DEFAULT NULL,
  `idInvoice` int DEFAULT NULL,
  `glYear` int DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  `month` int DEFAULT NULL,
  PRIMARY KEY (`idGlHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `glhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `idcontribution`;:||:Separator:||:


CREATE TABLE `idcontribution` (
  `idEmpContribution` int NOT NULL AUTO_INCREMENT,
  `idEmployee` int DEFAULT NULL,
  `contribution` int DEFAULT NULL COMMENT '1 - SSS\\n2 - Philhealth\\n3 - Pag-ibig Fund\\n4 - Withholding Tax',
  `amount` decimal(18,2) DEFAULT '0.00',
  `effectivityDate` date DEFAULT NULL,
  `idCoa` int DEFAULT NULL COMMENT 'idCoa',
  PRIMARY KEY (`idEmpContribution`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `idcontribution` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `invadjustment`;:||:Separator:||:


CREATE TABLE `invadjustment` (
  `idInvAdjustment` int NOT NULL AUTO_INCREMENT,
  `idItem` int DEFAULT NULL,
  `qtyBal` int DEFAULT NULL,
  `qtyActual` int DEFAULT NULL,
  `cost` decimal(18,2) DEFAULT NULL,
  `short` int DEFAULT NULL,
  `over` int DEFAULT NULL,
  `idInvoice` int DEFAULT NULL,
  `expiryDate` date DEFAULT NULL,
  PRIMARY KEY (`idInvAdjustment`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `invadjustment` WRITE;:||:Separator:||:
 INSERT INTO `invadjustment` VALUES(1,1,1700,1800,100.00,1699,0,24,'0000-00-00'),(2,2,0,2,0.00,0,1,26,'0000-00-00'),(3,4,30,50,46.00,0,20,135,'0000-00-00'),(4,3,0,10,100.00,0,10,136,'0000-00-00'),(5,14,500,1,30.00,499,0,142,'2023-05-26'),(6,4,180,1,150.00,179,0,146,'2023-11-13'),(7,3,10,0,100.00,10,0,147,'0000-00-00'),(8,20,0,100,0.00,0,100,183,'0000-00-00'),(9,18,0,5,500.00,0,5,192,'0000-00-00');:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `invadjustmenthistory`;:||:Separator:||:


CREATE TABLE `invadjustmenthistory` (
  `idInvAdjustmentHistory` int NOT NULL AUTO_INCREMENT,
  `idInvAdjustment` int DEFAULT NULL,
  `idItem` int DEFAULT NULL,
  `idItemClass` int DEFAULT NULL,
  `qtyBal` int DEFAULT NULL,
  `qtyActual` int DEFAULT NULL,
  `cost` decimal(18,2) DEFAULT '0.00',
  `short` int DEFAULT NULL,
  `over` int DEFAULT NULL,
  `idInvoicesHistory` int DEFAULT NULL,
  `idInvoice` int DEFAULT NULL,
  `expiryDate` date DEFAULT NULL,
  PRIMARY KEY (`idInvAdjustmentHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `invadjustmenthistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `invoices`;:||:Separator:||:


CREATE TABLE `invoices` (
  `idInvoice` int NOT NULL AUTO_INCREMENT,
  `idAffiliate` int DEFAULT NULL,
  `idReference` int DEFAULT NULL,
  `referenceNum` int DEFAULT NULL,
  `idModule` int DEFAULT NULL,
  `idCostCenter` int DEFAULT NULL,
  `idLocation` int DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `time` time DEFAULT NULL,
  `pType` int DEFAULT NULL COMMENT '1 - Customer\n2 - Supplier\n3 - Location',
  `pCode` int DEFAULT NULL,
  `payMode` int DEFAULT NULL COMMENT '1 - Cash\n2 - Charge',
  `amount` decimal(18,2) DEFAULT '0.00',
  `bal` decimal(18,2) DEFAULT '0.00',
  `balLeft` decimal(18,2) DEFAULT '0.00',
  `downPayment` decimal(18,2) DEFAULT '0.00',
  `discount` decimal(18,2) DEFAULT '0.00',
  `discountRate` decimal(18,2) DEFAULT '0.00',
  `deliveryReceiptTag` int DEFAULT NULL,
  `deliveryReceipt` varchar(255) DEFAULT NULL,
  `cancelTag` int DEFAULT '0' COMMENT '0 - False\n1 - True',
  `remarks` text,
  `dueDate` date DEFAULT NULL,
  `terms` int DEFAULT NULL COMMENT '1 - 30 Days\n2 - 60 Days\n3 - 90 Days\n4 - 120 Days',
  `vatType` int DEFAULT NULL COMMENT '1 - Inclusive\n2 - Exclusive',
  `rbyCode` int DEFAULT NULL,
  `checkDm` int DEFAULT '0' COMMENT '0 - False\n1 - True',
  `dateModified` datetime DEFAULT NULL,
  `transferredBy` int DEFAULT NULL,
  `hasJournal` int DEFAULT '0' COMMENT '0 - False\n1 - True',
  `fident` int DEFAULT NULL,
  `preparedBy` int DEFAULT NULL,
  `pickupDate` timestamp NULL DEFAULT NULL,
  `notedby` int DEFAULT NULL,
  `status` int DEFAULT NULL,
  `otherTag` int DEFAULT NULL,
  `description` text,
  `month` int DEFAULT NULL,
  `year` int DEFAULT NULL,
  `archived` int DEFAULT '0' COMMENT '0 is Active\n1 is Archived',
  `idReferenceSeries` int DEFAULT NULL,
  `cancelledBy` int NOT NULL DEFAULT '0',
  `vatPercent` decimal(18,2) DEFAULT '0.00',
  `vatAmount` decimal(18,2) DEFAULT '0.00',
  `ewtRate` decimal(18,2) DEFAULT '0.00',
  `ewtAmount` decimal(18,2) DEFAULT '0.00',
  `penaltyRate` decimal(18,2) DEFAULT '0.00',
  `penaltyAmount` decimal(18,2) DEFAULT '0.00',
  `idDriver` int DEFAULT NULL,
  `plateNumber` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idInvoice`)
) ENGINE=InnoDB AUTO_INCREMENT=210 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `invoices` WRITE;:||:Separator:||:
 INSERT INTO `invoices` VALUES(1,2,29,1,70,null,null,'2021-11-29 11:26:48','11:26:48',1,0,null,125.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-11-29 11:28:09',null,0,null,null,null,null,2,null,null,null,null,0,52,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(8,2,29,2,70,null,null,'2021-11-29 11:48:24','11:48:24',1,0,null,300.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-11-29 11:49:13',null,0,null,null,null,null,2,null,null,null,null,0,52,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(9,2,9,10,2,null,null,'2021-12-01 08:07:05',null,2,5,null,294.00,294.00,294.00,0.00,0.00,0.00,null,null,0,'Porro aut qui assume','2021-12-01',null,null,null,0,null,null,1,null,60,null,null,2,null,null,null,null,0,21,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(11,2,31,1,71,null,null,'2021-12-09 14:00:16','02:00:16',2,10,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-09 14:15:34',null,1,null,null,null,null,2,null,null,null,null,0,54,0,0.00,0.00,0.00,0.00,0.00,0.00,143,'HJA-1234'),(12,2,31,2,71,null,null,'2021-12-09 14:00:16','02:00:16',2,5,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-09 14:17:52',null,1,null,null,null,null,2,null,null,null,null,1,54,0,0.00,0.00,0.00,0.00,0.00,0.00,143,'HJA-1234'),(13,2,31,3,71,null,null,'2021-12-13 08:21:32','08:21:32',2,10,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-13 08:40:27',null,1,null,null,null,null,2,null,null,null,null,1,54,0,0.00,0.00,0.00,0.00,0.00,0.00,142,'HJA1234'),(14,2,31,4,71,null,null,'2021-12-13 09:46:01','09:46:01',1,1,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-13 09:48:16',null,1,null,null,null,null,2,null,null,null,null,1,54,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(15,2,17,100,58,null,null,'2021-12-14 07:46:00',null,1,2,1,1678.00,1678.00,1678.00,0.00,0.00,0.00,null,null,0,null,'2021-12-14',null,null,null,0,null,null,0,null,60,null,null,2,null,null,null,null,0,44,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(16,2,16,1000,57,null,null,'2021-12-14 10:25:00',null,2,10,1,5450.00,5450.00,5450.00,0.00,0.00,0.00,null,null,0,100,'2021-12-14',null,null,null,0,null,null,0,null,60,null,null,2,null,null,null,null,0,43,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(17,2,16,1001,57,null,null,'2021-12-14 10:44:00',null,2,10,1,264.60,264.60,264.60,0.00,0.00,0.00,null,null,0,100,'2021-12-14',null,null,null,0,null,null,0,67,60,null,null,2,null,null,null,null,0,43,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(18,2,16,1002,57,null,null,'2021-12-14 10:46:00',null,2,6,1,264.60,264.60,264.60,0.00,0.00,0.00,null,null,0,'In sapiente reiciend','2021-12-14',null,null,null,0,null,null,0,67,60,null,null,2,null,null,null,null,0,43,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(19,2,17,101,58,null,null,'2021-12-14 10:47:00',null,1,6,1,1300.00,1300.00,1300.00,0.00,0.00,0.00,null,null,0,'Temporibus nisi mole','2021-12-14',null,null,null,0,null,null,0,null,60,null,null,2,null,null,null,null,0,44,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(20,2,17,102,58,null,null,'2021-12-14 10:49:00',null,1,5,1,100.00,100.00,100.00,0.00,0.00,0.00,null,null,0,'Nihil modi sit ea a','2021-12-14',null,null,null,0,null,null,0,null,60,null,null,2,null,null,null,null,0,44,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(29,2,31,5,71,null,null,'2021-12-14 15:34:00','03:34:00',2,5,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-15 08:09:30',null,1,null,null,null,null,2,null,null,null,null,1,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(30,2,31,6,71,null,null,'2021-12-14 15:39:53','03:39:53',2,7,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-14 15:40:49',null,1,null,null,null,null,2,null,null,null,null,1,54,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(31,2,31,7,71,null,null,'2021-12-15 08:29:58','08:29:58',2,5,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-15 08:31:16',null,1,null,null,null,null,2,null,null,null,null,1,54,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(32,2,31,8,71,null,null,'2021-12-15 08:29:00','08:29:00',1,2,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-15 08:37:59',null,1,null,null,null,null,2,null,null,null,null,1,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(33,2,31,8,71,null,null,'2021-12-15 08:40:12','08:40:12',2,2,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-15 08:41:36',null,1,null,null,null,null,2,null,null,null,null,1,54,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(34,2,31,9,71,null,null,'2021-12-15 08:54:42','08:54:42',1,4,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-15 08:56:08',null,1,null,null,null,null,2,null,null,null,null,1,54,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(35,2,31,10,71,null,null,'2021-12-15 09:00:27','09:00:27',1,7,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-15 09:02:12',null,1,null,null,null,null,2,null,null,null,null,1,54,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(36,2,31,11,71,null,null,'2021-12-15 09:24:00','09:24:00',1,6,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-10-10 10:37:06',null,1,null,null,null,null,2,null,null,null,null,1,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(37,2,31,12,71,null,null,'2021-12-15 10:16:24','10:16:24',1,2,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-15 10:17:10',null,1,null,null,null,null,2,null,null,null,null,0,54,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(38,2,31,13,71,null,null,'2021-12-15 10:21:04','10:21:04',2,7,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-15 10:22:54',null,1,null,null,null,null,2,null,null,null,null,0,54,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(43,2,31,14,71,null,null,'2021-12-15 10:21:04','10:21:04',1,10,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-15 10:31:01',null,1,null,null,null,null,2,null,null,null,null,0,54,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(44,2,31,15,71,null,null,'2021-12-15 14:59:00','02:59:00',2,5,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-15 16:17:17',null,1,null,null,null,null,2,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(45,2,29,1,70,5,null,'2021-12-15 15:42:00','03:42:00',1,4,null,3900.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-20 12:00:20',null,0,null,null,null,null,2,null,null,null,null,1,0,0,0.00,0.00,0.00,0.00,0.00,0.00,143,8),(46,2,29,2,70,3,null,'2021-12-15 15:42:00','03:42:00',1,8,null,10000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-15 15:56:03',null,0,null,null,null,null,2,null,null,null,null,1,0,0,0.00,0.00,0.00,0.00,0.00,0.00,143,1),(47,2,29,3,70,null,null,'2021-12-15 15:42:00','03:42:00',1,10,null,1000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-21 08:27:09',null,0,null,null,null,null,2,null,null,null,null,1,0,0,0.00,0.00,0.00,0.00,0.00,0.00,142,10),(48,2,29,4,70,null,null,'2021-12-15 15:42:00','03:42:00',1,8,null,5000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-21 08:26:46',null,0,null,null,null,null,2,null,null,null,null,1,0,0,0.00,0.00,0.00,0.00,0.00,0.00,142,14),(49,2,31,15,71,null,null,'2021-12-15 16:09:55','04:09:55',1,8,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-15 16:16:24',null,1,null,null,null,null,2,null,null,null,null,0,54,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(52,2,31,16,71,null,null,'2021-12-15 16:59:00','04:59:00',1,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-20 14:27:44',null,1,null,null,null,null,2,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(55,2,31,16,71,null,null,'2021-12-21 08:34:52','08:34:52',1,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-21 08:35:54',null,1,null,null,null,null,2,null,null,null,null,0,54,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(56,2,9,11,2,null,null,'2021-12-21 11:02:29',null,2,11,null,6050.00,6050.00,6050.00,0.00,0.00,0.00,null,null,0,null,'2022-01-05',null,null,null,0,null,null,1,null,60,null,null,2,null,null,null,null,0,21,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(57,2,11,1,25,null,null,'2021-12-21 11:09:50',null,2,11,2,4342.50,4342.50,1342.50,0.00,482.50,10.00,null,null,0,null,'2022-01-05',15,0,null,0,null,null,1,56,60,null,null,2,null,null,null,null,1,20,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(58,2,1,1,35,null,null,'2021-12-21 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,'Sample closing for March 2018',null,null,null,null,0,'2021-12-21 11:58:35',null,0,null,null,null,null,2,null,null,3,2018,0,2,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(62,2,16,1003,57,null,null,'2021-12-21 13:21:00',null,2,5,1,264.60,264.60,264.60,0.00,0.00,0.00,null,null,0,1000,'2021-12-21',null,null,null,0,null,null,1,67,60,null,null,2,null,null,null,null,0,43,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(63,2,21,234,45,null,3,'2021-12-21 13:25:00','00:20:21',2,11,null,3000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,0,null,60,null,null,2,0,null,null,null,0,35,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(64,2,13,1,17,null,3,'2021-12-21 13:41:00',null,1,12,null,2450.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,0,null,60,'2021-12-21 13:41:00',null,2,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(65,2,12,1,18,null,null,'2021-12-21 13:41:00',null,1,12,1,2450.00,2450.00,2450.00,0.00,0.00,0.00,1,1234567,0,null,'2021-12-21',null,0,null,0,null,null,0,64,60,null,null,2,null,null,null,null,1,30,0,0.00,0.00,0.00,0.00,0.00,0.00,142,1234531),(66,2,17,103,58,null,null,'2021-12-21 13:40:00',null,1,12,1,2450.00,2450.00,2450.00,0.00,0.00,0.00,null,null,0,null,'2021-12-21',null,null,null,0,null,null,0,65,60,null,null,2,null,null,null,null,0,44,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(67,2,11,2,25,null,null,'2021-12-21 14:07:00',null,2,5,1,8478.00,8478.00,8478.00,0.00,942.00,10.00,null,null,0,'Sample update','2021-12-21',null,0,null,0,null,null,1,9,60,null,null,2,null,null,null,null,1,20,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(68,2,10,1,29,null,null,'2021-12-21 14:18:00',null,2,11,null,3300.00,3300.00,3300.00,0.00,0.00,0.00,null,null,0,'Sample update',null,null,null,null,0,null,null,1,57,60,null,null,null,null,null,null,null,0,28,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(69,2,9,12,2,null,null,'2021-12-21 14:26:16',null,2,10,null,5450.00,5450.00,5450.00,0.00,0.00,0.00,null,null,0,null,'2021-12-21',null,null,null,0,null,null,1,null,60,null,null,2,null,null,null,null,0,21,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(70,2,11,3,25,null,null,'2021-12-21 14:16:29',null,2,10,1,5450.00,5450.00,5450.00,0.00,0.00,0.00,null,null,0,null,'2022-01-05',null,0,null,0,null,null,1,69,60,null,null,2,null,null,null,null,0,20,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(71,2,10,2,29,null,null,'2021-12-21 14:28:00',null,2,10,null,2670.00,2670.00,2670.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,1,70,60,null,null,null,null,null,null,null,0,28,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(72,2,13,2,17,null,3,'2021-12-21 14:42:00',null,1,10,null,32000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,0,null,60,'2021-12-21 14:42:00',null,2,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(73,2,13,3,17,null,3,'2021-12-21 14:42:00',null,1,4,null,20000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,0,null,60,'2021-12-21 14:42:00',null,2,null,null,null,null,1,29,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(74,2,12,2,18,null,null,'2021-12-21 14:44:00',null,1,10,1,22000.00,22000.00,22000.00,0.00,0.00,0.00,0,null,0,null,'2021-12-21',null,0,null,0,null,null,0,72,60,null,null,2,null,null,null,null,0,30,0,0.00,0.00,0.00,0.00,0.00,0.00,143,'NEW 1234'),(75,2,14,1,21,null,null,'2021-12-21 14:46:00',null,1,10,null,2500.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,0,74,60,null,null,2,null,null,null,null,0,40,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(76,2,8,1,22,null,3,'2021-12-22 08:45:00',null,null,null,null,300.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,0,null,60,null,null,2,null,null,null,null,0,27,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(77,12,15,1,43,null,null,'2022-02-28 10:53:00',null,3,2,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,0,0,null,60,null,null,2,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(78,12,15,1,43,null,null,'2021-12-22 09:13:00',null,3,2,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,'Sample stock transfer to H company',null,null,null,null,0,null,0,0,null,60,null,null,2,null,null,null,null,0,41,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(80,2,29,3,70,null,null,'2021-12-22 10:29:16','10:29:16',1,2,null,2175.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-22 10:34:44',null,0,null,null,null,null,2,null,null,null,null,1,52,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(81,2,29,4,70,null,null,'2021-12-22 10:39:54','10:39:54',1,1,null,780.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-22 10:40:36',null,0,null,null,null,null,2,null,null,null,null,1,52,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(82,2,29,5,70,null,null,'2021-12-22 10:43:10','10:43:10',1,3,null,540.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-22 10:43:48',null,0,null,null,null,null,2,null,null,null,null,1,52,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(83,2,29,6,70,null,null,'2021-12-22 10:46:00','10:46:00',1,4,null,1440.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2021-12-28 13:20:44',null,0,null,null,null,null,2,null,null,null,null,1,52,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(84,2,2,1,48,3,null,'2021-12-23 09:51:00',null,2,11,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-03-04 10:32:19',null,0,68,60,null,null,2,0,null,null,null,0,22,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(88,2,31,17,71,null,null,'2021-12-28 13:29:00','01:29:00',1,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-02-21 08:49:52',null,1,null,null,null,null,2,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(89,2,33,1,78,5,null,'2022-01-03 10:56:00','10:56:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-11 09:19:43',null,0,null,null,null,null,null,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(90,0,33,2,78,null,null,'2022-01-04 09:52:00','09:52:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-04 10:29:28',null,0,null,null,null,null,null,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(91,0,33,2,78,null,null,'2022-01-04 09:52:00','09:52:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-04 10:37:09',null,0,null,null,null,null,null,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(92,2,29,7,70,null,null,'2022-01-04 11:18:38','11:18:38',1,0,null,15000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-04 11:20:07',null,0,null,null,null,null,2,null,null,null,null,1,52,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(93,2,33,1,78,5,null,'2022-01-13 10:58:55','10:58:55',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-13 10:59:33',null,0,null,null,null,null,null,null,null,null,null,0,57,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(94,0,33,2,78,5,null,'2022-01-13 10:58:55','10:58:55',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-13 10:59:51',null,0,null,null,null,null,null,null,null,null,null,0,57,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(95,2,33,3,78,5,null,'2022-01-13 11:01:44','11:01:44',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-13 11:01:55',null,0,null,null,null,null,null,null,null,null,null,0,57,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(96,2,33,4,78,5,null,'2022-01-13 11:08:39','11:08:39',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-13 11:09:16',null,0,null,null,null,null,null,null,null,null,null,0,57,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(97,2,33,5,78,5,null,'2022-01-13 11:17:18','11:17:18',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-13 11:17:48',null,0,null,null,null,null,null,null,null,null,null,0,57,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(98,0,33,6,78,null,null,'2022-01-13 11:30:52','11:30:52',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-13 11:36:22',null,0,null,null,null,null,null,null,null,null,null,0,57,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(99,2,33,0,78,null,null,'2022-01-13 11:30:00','11:30:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-13 11:44:06',null,0,null,null,null,null,null,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(100,2,33,0,78,null,null,'2022-01-13 11:55:00','11:55:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-10-10 11:31:22',null,0,null,null,null,null,null,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(101,2,33,0,78,5,null,'2022-01-14 14:19:00','02:19:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-24 09:26:29',null,0,null,null,null,null,null,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(102,2,33,9,78,5,null,'2022-01-17 08:43:12','08:43:12',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-17 08:43:32',null,0,null,null,null,null,null,null,null,null,null,0,57,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(103,0,33,10,78,5,null,'2022-01-17 08:43:12','08:43:12',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-17 08:44:04',null,0,null,null,null,null,null,null,null,null,null,0,57,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(104,2,33,11,78,5,null,'2022-01-17 08:44:13','08:44:13',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-17 08:44:24',null,0,null,null,null,null,null,null,null,null,null,0,57,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(105,0,33,12,78,null,null,'2022-01-17 09:44:30','09:44:30',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-17 09:49:27',null,0,null,null,null,null,null,null,null,null,null,0,57,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(106,0,33,0,78,null,null,'2022-01-17 09:44:00','09:44:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-17 09:54:41',null,0,null,null,null,null,null,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(108,2,33,13,78,null,null,'2022-01-17 09:58:46','09:58:46',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-17 10:04:22',null,0,null,null,null,null,null,null,null,null,null,0,57,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(109,0,33,14,78,null,null,'2022-01-24 09:22:57','09:22:57',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-24 09:42:12',null,0,null,null,null,null,null,null,null,null,null,0,57,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(110,2,33,15,78,5,null,'2022-01-24 20:41:59','08:41:59',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-24 20:42:15',null,0,null,null,null,null,null,null,null,null,null,0,57,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(111,0,33,16,78,5,null,'2022-01-24 20:41:59','08:41:59',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-24 20:42:40',null,0,null,null,null,null,null,null,null,null,null,0,57,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(112,2,33,17,78,5,null,'2022-01-24 20:45:03','08:45:03',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-24 20:45:27',null,0,null,null,null,null,null,null,null,null,null,0,57,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(113,2,33,18,78,5,null,'2022-01-26 13:31:02','01:31:02',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-26 13:31:31',null,0,null,null,null,null,null,null,null,null,null,0,57,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(114,2,33,19,78,5,null,'2022-01-27 08:47:10','08:47:10',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-27 08:47:57',null,0,null,null,null,null,null,null,null,null,null,0,57,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(115,2,33,20,78,null,null,'2022-01-27 10:49:52','10:49:52',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-01-27 10:52:12',null,0,null,null,null,null,null,null,null,null,null,0,57,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(116,2,29,8,70,null,null,'2022-02-10 14:44:04','02:44:04',1,0,null,10.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-02-10 14:55:33',null,0,null,null,null,null,2,null,null,null,null,1,52,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(119,2,29,1,70,5,null,'2022-02-21 08:43:00','08:43:00',1,3,null,25000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-02-21 11:52:17',null,0,null,null,null,null,2,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(120,2,29,9,70,null,null,'2022-02-21 11:49:00','11:49:00',1,1,null,50000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-02-23 09:07:44',null,0,null,null,null,null,2,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(121,2,34,1,25,5,null,'2022-02-24 11:03:00',null,2,11,1,0.00,0.00,0.00,0.00,0.00,10.00,null,null,0,100,'2022-02-25',123,0,null,0,null,null,1,null,60,null,null,2,null,null,null,null,1,58,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(122,2,35,1,43,5,null,'2022-03-03 09:53:00',null,3,12,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,0,0,null,60,null,null,2,null,null,null,null,1,59,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(123,12,35,1,43,5,null,'2022-03-03 09:53:00',null,3,2,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,0,0,null,60,null,null,2,null,null,null,null,1,59,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(124,2,35,2,43,5,null,'2022-03-03 10:06:00',null,3,12,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,0,0,null,60,null,null,2,null,null,null,null,1,59,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(125,12,35,2,43,5,null,'2022-03-03 10:06:00',null,3,2,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,0,0,null,60,null,null,2,null,null,null,null,1,59,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(126,2,35,3,43,5,null,'2022-03-03 10:10:00',null,3,12,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,0,0,null,60,null,null,2,null,null,null,null,1,59,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(127,12,35,3,43,5,null,'2022-03-03 10:10:00',null,3,2,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,0,0,null,60,null,null,2,null,null,null,null,1,59,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(128,2,35,4,43,5,null,'2022-03-03 10:10:00',null,3,12,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,0,0,null,60,null,null,2,null,null,null,null,1,59,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(129,12,35,4,43,5,null,'2022-03-03 10:10:00',null,3,2,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,0,0,null,60,null,null,2,null,null,null,null,1,59,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(130,2,35,5,43,5,null,'2022-03-03 10:13:00',null,3,12,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,0,0,null,60,null,null,2,null,null,null,null,1,59,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(131,12,35,5,43,5,null,'2022-03-03 10:13:00',null,3,2,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,0,0,null,60,null,null,2,null,null,null,null,1,59,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(132,2,35,6,43,5,null,'2022-03-03 10:18:00',null,3,12,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,345,null,null,null,null,0,null,0,0,null,60,null,null,2,null,null,null,null,1,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(133,12,35,6,43,5,null,'2022-03-03 10:15:00',null,3,2,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,0,0,null,60,null,null,2,null,null,null,null,1,59,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(134,2,5,1,28,null,3,'2022-03-03 10:23:00','00:20:22',1,4,null,10021.00,0.00,0.00,0.00,0.00,0.00,null,null,0,'THIS IS A TEST',null,null,null,null,0,null,null,0,null,60,null,null,2,1,null,null,null,0,31,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(135,2,7,1,23,null,null,'2022-03-03 10:25:16',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,'THIS IS A TEST',null,null,null,null,0,'2022-03-03 10:27:09',null,0,null,60,null,null,1,null,null,null,null,0,19,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(136,2,7,2,23,null,null,'2022-03-03 10:25:16',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,'THIS IS A TEST',null,null,null,null,0,'2022-03-03 10:28:59',null,0,null,60,null,null,2,null,null,null,null,0,19,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(137,2,11,4,25,null,null,'2022-03-03 10:50:00',null,2,12,1,44850.00,44850.00,44850.00,0.00,0.00,0.00,null,null,0,null,'2022-03-03',null,0,null,0,null,null,1,null,60,null,null,2,null,null,null,null,0,20,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(138,2,35,7,43,5,null,'2022-03-03 10:58:00',null,3,12,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,1234,null,null,null,null,0,null,60,0,null,60,null,null,2,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(139,12,35,7,43,5,null,'2022-03-03 10:52:00',null,3,2,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,123,null,null,null,null,0,null,60,0,null,60,null,null,2,null,null,null,null,0,59,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(140,2,13,4,17,null,3,'2022-03-03 10:54:00',null,1,10,null,1745.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,0,null,60,'2022-03-03 10:54:00',null,2,null,null,null,null,0,29,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(141,2,12,3,18,null,null,'2022-03-03 10:54:00',null,1,10,1,1745.00,1745.00,1745.00,0.00,0.00,0.00,0,null,0,null,'2022-03-03',null,0,null,0,null,null,0,140,60,null,null,2,null,null,null,null,1,30,0,0.00,0.00,0.00,0.00,0.00,0.00,142,'ABC-1234'),(142,2,7,3,23,null,null,'2022-03-03 13:09:43',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-03-03 13:10:37',null,0,null,60,null,null,2,null,null,null,null,0,19,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(143,2,2,1001,48,5,null,'2022-03-04 08:27:55',null,0,0,null,123.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-03-04 10:31:54',null,0,null,60,null,null,2,0,'test',null,null,0,45,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(144,2,13,1000,17,5,3,'2022-03-10 08:21:00',null,1,2,null,1000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,0,null,60,'2022-03-10 08:21:00',null,2,null,null,null,null,0,47,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(145,2,13,1001,17,5,3,'2022-03-10 09:16:00',null,1,2,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,0,null,60,'2022-03-10 09:16:00',null,2,null,null,null,null,0,47,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(146,2,7,4,23,null,null,'2022-03-10 14:11:24',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-03-10 14:11:45',null,0,null,60,null,null,3,null,null,null,null,0,19,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(147,2,7,5,23,null,null,'2022-03-14 14:34:26',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-03-14 14:36:45',null,0,null,60,null,null,1,null,null,null,null,0,19,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(148,2,35,8,43,null,null,'2022-03-14 14:38:00',null,3,12,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,60,0,null,60,null,null,2,null,null,null,null,0,59,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(149,12,35,8,43,null,null,'2022-03-14 14:38:00',null,3,2,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,60,0,null,60,null,null,2,null,null,null,null,0,59,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(150,2,11,5,25,null,null,'2022-03-14 14:48:56',null,2,10,1,15000.00,15000.00,15000.00,0.00,0.00,0.00,null,null,0,null,'2022-03-14',null,0,null,0,null,null,1,null,60,null,null,2,null,null,null,null,1,20,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(151,2,12,1,18,5,null,'2022-03-15 14:05:00',null,1,12,1,500.00,500.00,500.00,0.00,0.00,0.00,0,null,0,null,'2022-03-15',null,0,null,0,null,null,0,null,60,null,null,2,null,null,null,null,0,48,0,0.00,0.00,0.00,0.00,0.00,0.00,142,434345345),(152,2,12,2,18,5,null,'2022-03-15 14:20:00',null,1,2,1,500.00,500.00,500.00,0.00,0.00,0.00,0,null,0,null,'2022-03-15',null,0,null,0,null,null,0,null,60,null,null,2,null,null,null,null,0,48,0,0.00,0.00,0.00,0.00,0.00,0.00,143,12321),(154,2,36,1,83,5,null,'2022-04-26 14:40:36','02:40:36',null,null,null,123.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-04-26 14:44:19',null,0,null,null,null,null,2,null,null,null,null,1,60,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(155,2,36,2,83,5,null,'2022-04-26 14:46:00','02:46:00',null,null,null,120.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-12 13:58:28',null,0,null,null,null,null,2,null,null,null,null,1,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(156,2,36,2,83,null,null,'2022-04-27 13:55:00','01:55:00',null,null,null,1000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-11 16:15:01',null,0,null,null,null,null,2,null,null,null,null,1,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(157,2,36,3,83,null,null,'2022-04-28 14:44:00','02:44:00',null,null,null,10000000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-04-28 14:58:19',null,0,null,null,null,null,2,null,null,null,null,1,60,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(158,2,36,4,83,null,null,'2022-05-10 13:34:00','01:34:00',null,null,null,10000000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-12 13:51:36',null,0,null,null,null,null,2,null,null,null,null,1,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(159,2,37,1,84,5,null,'2022-05-11 15:08:00','03:08:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-11 16:14:28',null,0,null,null,null,null,null,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(160,2,36,4,83,null,null,'2022-05-12 08:16:00','08:16:00',null,null,null,500000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-12 14:30:54',null,0,null,null,null,null,2,null,null,null,null,1,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(161,2,37,1,84,5,null,'2022-05-12 13:49:59','01:49:59',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-12 13:56:16',null,0,null,null,null,null,null,null,null,null,null,0,61,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(162,2,37,2,84,5,null,'2022-05-12 13:57:53','01:57:53',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-12 13:59:24',null,0,null,null,null,null,null,null,null,null,null,0,61,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(163,2,37,3,84,5,null,'2022-05-12 14:21:46','02:21:46',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-12 14:21:53',null,0,null,null,null,null,null,null,null,null,null,0,61,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(164,2,37,4,84,5,null,'2022-05-12 14:21:46','02:21:46',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-12 14:22:35',null,0,null,null,null,null,null,null,null,null,null,0,61,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(165,2,36,4,83,5,null,'2022-05-12 14:31:00','02:31:00',null,null,null,50000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-17 15:10:36',null,0,null,null,null,null,2,null,null,null,null,1,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(166,2,37,5,84,5,null,'2022-05-12 14:33:00','02:33:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-12 14:35:55',null,0,null,null,null,null,null,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(167,2,37,6,84,5,null,'2022-05-12 14:34:11','02:34:11',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-12 14:34:26',null,0,null,null,null,null,null,null,null,null,null,0,61,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(168,2,37,7,84,5,null,'2022-05-12 14:34:00','02:34:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-12 14:35:17',null,0,null,null,null,null,null,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(169,2,37,7,84,5,null,'2022-05-12 14:36:30','02:36:30',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-12 14:37:07',null,0,null,null,null,null,null,null,null,null,null,0,61,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(170,2,37,8,84,5,null,'2022-05-12 14:37:00','02:37:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-12 15:09:03',null,0,null,null,null,null,null,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(171,2,37,8,84,5,null,'2022-05-12 15:08:00','03:08:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-12 15:10:50',null,0,null,null,null,null,null,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(172,2,37,8,84,5,null,'2022-05-12 15:08:00','03:08:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-12 15:35:01',null,0,null,null,null,null,null,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(173,2,37,8,84,5,null,'2022-05-12 15:38:00','03:38:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-12 15:49:36',null,0,null,null,null,null,null,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(174,2,37,9,84,5,null,'2022-05-12 15:48:00','03:48:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-12 15:49:58',null,0,null,null,null,null,null,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(175,2,37,8,84,5,null,'2022-05-12 16:22:05','04:22:05',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-12 16:22:52',null,0,null,null,null,null,null,null,null,null,null,0,61,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(176,2,37,9,84,5,null,'2022-05-17 15:00:00','03:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-17 15:44:54',null,0,null,null,null,null,null,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(177,2,36,4,83,5,null,'2022-05-17 15:43:44','03:43:44',null,null,null,123.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-17 15:44:12',null,0,null,null,null,null,2,null,null,null,null,1,60,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(178,2,37,9,84,5,null,'2022-05-17 15:43:47','03:43:47',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-17 15:45:09',null,0,null,null,null,null,null,null,null,null,null,0,61,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(179,2,36,5,83,5,null,'2022-05-17 15:58:27','03:58:27',null,null,null,213.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-17 15:59:17',null,0,null,null,null,null,2,null,null,null,null,1,60,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(180,2,36,6,83,5,null,'2022-05-17 15:58:27','03:58:27',null,null,null,213.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-17 15:59:35',null,0,null,null,null,null,2,null,null,null,null,1,60,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(181,2,37,10,84,5,null,'2022-05-17 15:58:28','03:58:28',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-17 15:59:43',null,0,null,null,null,null,null,null,null,null,null,0,61,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(182,2,36,7,83,5,null,'2022-05-18 14:07:00','02:07:00',null,null,null,123.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-26 13:40:48',null,0,null,null,null,null,2,null,null,null,null,1,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(183,2,7,6,23,null,null,'2022-05-19 07:32:16',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-19 07:33:39',null,0,null,60,null,null,1,null,null,null,null,0,19,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(190,2,1,2,35,null,null,'2022-05-19 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,'add',null,null,null,null,0,'2022-05-19 10:44:57',null,0,null,null,null,null,2,null,'add',4,2018,0,2,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(191,2,37,11,84,5,null,'2022-05-19 14:53:00','02:53:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-19 16:57:53',null,0,null,null,null,null,null,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(192,2,7,7,23,null,null,'2022-05-20 14:48:43',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,'This is a test adjustment 05-20-2022',null,null,null,null,0,'2022-05-20 14:49:59',null,0,null,60,null,null,1,null,null,null,null,0,19,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(193,2,1,3,35,null,null,'2022-05-20 00:00:00',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-05-20 15:00:31',null,0,null,null,null,null,2,null,'Test',5,2018,0,2,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(194,2,36,7,83,5,null,'2022-06-08 14:27:00','02:27:00',null,null,null,50000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-08-18 12:59:12',null,0,null,null,null,null,2,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(195,2,37,11,84,5,null,'2022-06-08 14:32:37','02:32:37',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-06-08 14:32:52',null,0,null,null,null,null,null,null,null,null,null,0,61,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(196,2,36,8,83,null,null,'2022-08-17 14:17:00','02:17:00',null,null,null,30000000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-08-18 12:59:33',null,0,null,null,null,null,2,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(197,2,37,12,84,null,null,'2022-08-17 14:17:00','02:17:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-08-17 14:29:11',null,0,null,null,null,null,null,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(198,2,36,9,83,null,null,'2022-08-17 14:30:03','02:30:03',null,null,null,5000000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-08-17 14:42:35',null,0,null,null,null,null,2,null,null,null,null,0,60,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(199,2,37,12,84,null,null,'2022-08-17 14:44:00','02:44:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-10-10 14:08:22',null,0,null,null,null,null,null,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(200,2,36,10,83,null,null,'2022-08-17 15:31:00','03:31:00',null,null,null,500000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-08-18 13:03:21',null,0,null,null,null,null,2,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(201,2,36,10,83,5,null,'2022-08-18 13:57:00','01:57:00',null,null,null,123.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-08-18 13:59:19',null,0,null,null,null,null,2,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(202,2,36,10,83,null,null,'2022-08-19 13:11:19','01:11:19',null,null,null,1500000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-08-19 13:14:16',null,0,null,null,null,null,2,null,null,null,null,0,60,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(203,2,29,9,70,2,null,'2022-10-10 10:03:00','10:03:00',1,4,null,7500.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-10-10 10:13:27',null,0,null,null,null,null,2,null,null,null,null,1,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(204,2,31,17,71,null,null,'2022-10-09 10:00:00','10:00:00',2,10,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-10-10 10:55:30',null,1,null,null,null,null,2,null,null,null,null,0,54,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(205,2,33,21,78,null,null,'2022-10-09 11:30:00','11:30:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-10-10 11:20:52',null,0,null,null,null,null,null,null,null,null,null,0,57,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(206,2,36,11,83,null,null,'2022-10-10 13:11:00','01:11:00',null,null,null,15000000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-10-10 13:42:43',null,0,null,null,null,null,2,null,null,null,null,1,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(207,2,37,12,84,null,null,'2022-10-10 13:53:57','01:53:57',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-10-10 14:00:51',null,0,null,null,null,null,null,null,null,null,null,0,61,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(208,2,37,13,84,null,null,'2022-10-10 14:16:31','02:16:31',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-10-10 14:16:38',null,0,null,null,null,null,null,null,null,null,null,0,61,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(209,2,37,14,84,null,null,'2022-10-10 14:16:31','02:16:31',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2022-10-10 14:16:43',null,0,null,null,null,null,null,null,null,null,null,0,61,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `invoiceshistory`;:||:Separator:||:


CREATE TABLE `invoiceshistory` (
  `idInvoiceHistory` int NOT NULL AUTO_INCREMENT,
  `idInvoice` int DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  `idReference` int DEFAULT NULL,
  `idModule` int DEFAULT NULL,
  `idCostCenter` int DEFAULT NULL,
  `idLocation` int DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `pType` int DEFAULT NULL COMMENT '1 - Customer\n2 - Supplier\n3 - Location',
  `pCode` int DEFAULT NULL,
  `payMode` int DEFAULT NULL COMMENT '1 - Cash\n2 - Charge',
  `amount` decimal(18,2) DEFAULT '0.00',
  `bal` decimal(18,2) DEFAULT '0.00',
  `balLeft` decimal(18,2) DEFAULT '0.00',
  `downPayment` decimal(18,2) DEFAULT '0.00',
  `discount` decimal(18,2) DEFAULT '0.00',
  `discountRate` decimal(18,2) DEFAULT '0.00',
  `cancelTag` int DEFAULT '0' COMMENT '0 - False\n1 - True',
  `remarks` text,
  `dueDate` date DEFAULT NULL,
  `terms` int DEFAULT NULL COMMENT '1 - 30 Days\n2 - 60 Days\n3 - 90 Days\n4 - 120 Days',
  `vatType` int DEFAULT NULL COMMENT '1 - Inclusive\n2 - Exclusive',
  `rbyCode` int DEFAULT NULL,
  `checkDm` int DEFAULT '0' COMMENT '0 - False\n1 - True',
  `dateModified` datetime DEFAULT NULL,
  `transferredBy` int DEFAULT NULL,
  `hasJournal` int DEFAULT '0' COMMENT '0 - False\n1 - True',
  `fident` int DEFAULT NULL,
  `preparedBy` int DEFAULT NULL,
  `pickupDate` timestamp NULL DEFAULT NULL,
  `notedby` int DEFAULT NULL,
  `status` int DEFAULT NULL,
  `otherTag` int DEFAULT NULL,
  `referenceNum` varchar(255) DEFAULT NULL,
  `idReferenceSeries` int DEFAULT NULL,
  `deliveryReceiptTag` int DEFAULT NULL,
  `deliveryReceipt` varchar(255) DEFAULT NULL,
  `cancelledBy` int DEFAULT '0',
  `vatPercent` decimal(18,2) DEFAULT '0.00',
  `vatAmount` decimal(18,2) DEFAULT '0.00',
  `ewtRate` decimal(18,2) DEFAULT '0.00',
  `ewtAmount` decimal(18,2) DEFAULT '0.00',
  `penaltyRate` decimal(18,2) DEFAULT '0.00',
  `penaltyAmount` decimal(18,2) DEFAULT '0.00',
  `idDriver` int DEFAULT NULL,
  `plateNumber` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idInvoiceHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `invoiceshistory` WRITE;:||:Separator:||:
 INSERT INTO `invoiceshistory` VALUES(1,15,2,17,58,null,null,'2021-12-14 07:46:00',1,2,1,1678.00,1678.00,1678.00,0.00,0.00,0.00,0,null,'2021-12-14',null,null,null,0,null,null,0,null,60,null,null,2,null,100,44,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(2,16,2,16,57,null,null,'2021-12-14 10:25:00',2,10,1,1500.00,1500.00,1500.00,0.00,0.00,0.00,0,null,'2021-12-14',null,null,null,0,null,null,0,null,60,null,null,2,null,1000,43,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(3,17,2,16,57,null,null,'2021-12-14 10:44:00',2,10,1,1500.00,1500.00,1500.00,0.00,0.00,0.00,0,null,'2021-12-14',null,null,null,0,null,null,0,null,60,null,null,2,null,1001,43,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(4,18,2,16,57,null,null,'2021-12-14 10:46:00',2,6,1,1345.00,1345.00,1345.00,0.00,0.00,0.00,0,'In sapiente reiciend','2021-12-14',null,null,null,0,null,null,0,null,60,null,null,2,null,1002,43,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(5,19,2,17,58,null,null,'2021-12-14 10:47:00',1,6,1,1300.00,1300.00,1300.00,0.00,0.00,0.00,0,'Temporibus nisi mole','2021-12-14',null,null,null,0,null,null,0,null,60,null,null,2,null,101,44,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(6,20,2,17,58,null,null,'2021-12-14 10:49:00',1,5,1,100.00,100.00,100.00,0.00,0.00,0.00,0,'Nihil modi sit ea a','2021-12-14',null,null,null,0,null,null,0,null,60,null,null,2,null,102,44,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(7,29,2,31,71,null,null,'2021-12-14 15:34:00',2,5,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-15 07:48:07',null,1,null,null,null,null,2,null,5,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(8,29,2,31,71,null,null,'2021-12-14 15:34:00',2,5,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-15 07:49:44',null,1,null,null,null,null,2,null,5,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(13,29,2,31,71,null,null,'2021-12-14 15:34:00',2,5,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-15 08:06:50',null,1,null,null,null,null,2,null,5,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(14,29,2,31,71,null,null,'2021-12-14 15:34:00',2,5,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-15 08:09:30',null,1,null,null,null,null,2,null,5,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(16,32,2,31,71,null,null,'2021-12-15 08:29:00',1,2,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-15 08:37:29',null,1,null,null,null,null,2,null,8,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(17,32,2,31,71,null,null,'2021-12-15 08:29:00',1,2,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-15 08:37:59',null,1,null,null,null,null,2,null,8,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(18,44,2,31,71,null,null,'2021-12-15 14:59:00',1,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-15 15:01:40',null,1,null,null,null,null,2,null,15,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(19,44,2,31,71,null,null,'2021-12-15 14:59:00',2,4,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-15 15:02:17',null,1,null,null,null,null,2,null,15,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(20,44,2,31,71,null,null,'2021-12-15 14:59:00',2,5,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-15 15:02:28',null,1,null,null,null,null,2,null,15,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(21,46,2,29,70,3,null,'2021-12-15 15:42:00',1,8,null,7500.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-15 15:55:37',null,0,null,null,null,null,2,null,2,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,143,1),(22,46,2,29,70,3,null,'2021-12-15 15:42:00',1,8,null,10000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-15 15:56:03',null,0,null,null,null,null,2,null,2,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,143,1),(23,44,2,31,71,null,null,'2021-12-15 14:59:00',2,5,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-15 16:17:17',null,1,null,null,null,null,2,null,15,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(24,36,2,31,71,null,null,'2021-12-15 09:24:00',1,6,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-15 16:17:51',null,1,null,null,null,null,2,null,11,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(25,52,2,31,71,null,null,'2021-12-15 16:59:00',1,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-15 17:02:04',null,1,null,null,null,null,2,null,16,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(26,52,2,31,71,null,null,'2021-12-15 16:59:00',1,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-15 17:02:41',null,1,null,null,null,null,2,null,16,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(27,47,2,29,70,null,null,'2021-12-15 15:42:00',1,10,null,1000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-20 11:59:47',null,0,null,null,null,null,2,null,3,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,142,9),(28,48,2,29,70,null,null,'2021-12-15 15:42:00',1,8,null,5000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-20 12:00:07',null,0,null,null,null,null,2,null,4,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,142,8),(29,45,2,29,70,5,null,'2021-12-15 15:42:00',1,4,null,3900.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-20 12:00:20',null,0,null,null,null,null,2,null,1,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,143,8),(30,52,2,31,71,null,null,'2021-12-15 16:59:00',1,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-20 14:25:31',null,1,null,null,null,null,2,null,16,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(31,52,2,31,71,null,null,'2021-12-15 16:59:00',1,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-20 14:26:33',null,1,null,null,null,null,2,null,16,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(32,52,2,31,71,null,null,'2021-12-15 16:59:00',1,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-20 14:27:17',null,1,null,null,null,null,2,null,16,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(33,52,2,31,71,null,null,'2021-12-15 16:59:00',1,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-20 14:27:44',null,1,null,null,null,null,2,null,16,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(34,48,2,29,70,null,null,'2021-12-15 15:42:00',1,8,null,5000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-20 14:39:44',null,0,null,null,null,null,2,null,4,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,142,8),(35,48,2,29,70,null,null,'2021-12-15 15:42:00',1,8,null,5000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-21 08:26:46',null,0,null,null,null,null,2,null,4,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,142,14),(36,47,2,29,70,null,null,'2021-12-15 15:42:00',1,10,null,1000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-21 08:27:09',null,0,null,null,null,null,2,null,3,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,142,10),(37,58,2,1,35,null,null,'2021-12-21 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,'Sample closing for March 2018',null,null,null,null,0,null,null,0,null,null,null,null,2,null,1,2,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(41,62,2,16,57,null,null,'2021-12-21 13:21:00',2,11,1,4342.50,4342.50,4342.50,0.00,0.00,0.00,0,null,'2021-12-21',null,null,null,0,null,null,1,57,60,null,null,2,null,1003,43,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(42,64,2,13,17,null,3,'2021-12-21 13:41:00',1,12,null,2450.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,60,'2021-12-21 13:41:00',null,2,null,1,29,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(43,65,2,12,18,null,null,'2021-12-21 13:41:00',1,12,1,2450.00,2450.00,2450.00,0.00,0.00,0.00,0,null,'2021-12-21',null,0,null,0,null,null,0,64,60,null,null,2,null,1,30,1,1234567,0,0.00,0.00,0.00,0.00,0.00,0.00,142,1234531),(44,66,2,17,58,null,null,'2021-12-21 13:40:00',1,12,1,2450.00,2450.00,2450.00,0.00,0.00,0.00,0,null,'2021-12-21',null,null,null,0,null,null,0,65,60,null,null,2,null,103,44,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(45,1,2,11,25,null,null,'2021-12-21 14:07:00',2,5,1,485.10,485.10,485.10,0.00,53.90,10.00,0,null,'2021-12-21',null,0,null,0,null,null,1,9,60,null,null,2,null,2,20,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(46,1,2,11,25,null,null,'2021-12-21 14:07:00',2,5,1,264.60,264.60,264.60,0.00,29.40,10.00,0,'Sample update','2021-12-21',null,0,null,0,null,null,1,9,60,null,null,2,null,2,20,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(47,72,2,13,17,null,3,'2021-12-21 14:42:00',1,10,null,17000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,60,'2021-12-21 14:42:00',null,2,null,2,29,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(48,73,2,13,17,null,3,'2021-12-21 14:42:00',1,4,null,20000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,60,'2021-12-21 14:42:00',null,2,null,3,29,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(49,72,2,13,17,null,3,'2021-12-21 14:42:00',1,10,null,32000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,60,'2021-12-21 14:42:00',null,2,null,2,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(50,74,2,12,18,null,null,'2021-12-21 14:44:00',1,10,1,22000.00,22000.00,22000.00,0.00,0.00,0.00,0,null,'2021-12-21',null,0,null,0,null,null,0,72,60,null,null,2,null,2,30,0,null,0,0.00,0.00,0.00,0.00,0.00,0.00,143,'NEW 1234'),(51,75,2,14,21,null,null,'2021-12-21 14:46:00',1,10,null,2500.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,74,60,null,null,2,null,1,40,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(52,76,2,8,22,null,3,'2021-12-22 08:45:00',null,null,null,300.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,60,null,null,2,null,1,27,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(53,84,2,2,48,null,null,'2021-12-23 09:51:59',2,11,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,68,null,null,null,null,1,1,22,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(54,84,2,2,48,3,null,'2021-12-23 09:51:00',2,11,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-23 09:52:54',null,0,null,null,null,null,null,0,1,22,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(55,83,2,29,70,null,null,'2021-12-22 10:46:00',1,4,null,1440.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2021-12-28 13:20:44',null,0,null,null,null,null,2,null,6,52,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(56,90,0,33,78,null,null,'2022-01-04 09:52:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-01-04 10:29:03',null,0,null,null,null,null,null,null,2,57,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(57,90,0,33,78,null,null,'2022-01-04 09:52:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-01-04 10:29:28',null,0,null,null,null,null,null,null,2,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(58,91,0,33,78,null,null,'2022-01-04 09:52:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-01-04 10:37:09',null,0,null,null,null,null,null,null,2,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(59,89,2,33,78,5,null,'2022-01-03 10:56:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-01-11 09:19:43',null,0,null,null,null,null,null,null,1,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(60,99,0,33,78,null,null,'2022-01-13 11:30:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-01-13 11:39:59',null,0,null,null,null,null,null,null,'TM-7',0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(61,99,2,33,78,null,null,'2022-01-13 11:30:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-01-13 11:44:06',null,0,null,null,null,null,null,null,'TM-0',0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(62,101,2,33,78,5,null,'2022-01-14 14:19:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-01-17 09:44:51',null,0,null,null,null,null,null,null,'TM-8',0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(63,106,0,33,78,null,null,'2022-01-17 09:44:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-01-17 09:54:41',null,0,null,null,null,null,null,null,'TM-13',0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(64,101,2,33,78,5,null,'2022-01-14 14:19:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-01-24 09:26:29',null,0,null,null,null,null,null,null,'TM-0',0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(65,16,2,16,57,null,null,'2021-12-14 10:25:00',2,10,1,4342.50,4342.50,4342.50,0.00,0.00,0.00,0,null,'2021-12-14',null,null,null,0,null,null,0,null,60,null,null,2,null,1000,43,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(66,16,2,16,57,null,null,'2021-12-14 10:25:00',2,10,1,5450.00,5450.00,5450.00,0.00,0.00,0.00,0,100,'2021-12-14',null,null,null,0,null,null,0,null,60,null,null,2,null,1000,43,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(67,18,2,16,57,null,null,'2021-12-14 10:46:00',2,6,1,264.60,264.60,264.60,0.00,0.00,0.00,0,'In sapiente reiciend','2021-12-14',null,null,null,0,null,null,0,67,60,null,null,2,null,1002,43,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(68,62,2,16,57,null,null,'2021-12-21 13:21:00',2,5,1,264.60,264.60,264.60,0.00,0.00,0.00,0,null,'2021-12-21',null,null,null,0,null,null,1,67,60,null,null,2,null,1003,43,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(69,62,2,16,57,null,null,'2021-12-21 13:21:00',2,5,1,264.60,264.60,264.60,0.00,0.00,0.00,0,1000,'2021-12-21',null,null,null,0,null,null,1,67,60,null,null,2,null,1003,43,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(70,17,2,16,57,null,null,'2021-12-14 10:44:00',2,10,1,264.60,264.60,264.60,0.00,0.00,0.00,0,100,'2021-12-14',null,null,null,0,null,null,0,67,60,null,null,2,null,1001,43,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(71,119,2,29,70,5,null,'2022-02-21 08:43:00',1,3,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-02-21 08:46:13',null,0,null,null,null,null,2,null,1,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(72,119,2,29,70,5,null,'2022-02-21 08:43:00',1,3,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-02-21 08:46:29',null,0,null,null,null,null,2,null,1,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(73,88,2,31,71,null,null,'2021-12-28 13:29:00',1,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-02-21 08:49:52',null,1,null,null,null,null,2,null,17,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(74,119,2,29,70,5,null,'2022-02-21 08:43:00',1,3,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-02-21 09:41:13',null,0,null,null,null,null,2,null,1,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(75,119,2,29,70,5,null,'2022-02-21 08:43:00',1,3,null,25000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-02-21 11:52:17',null,0,null,null,null,null,2,null,1,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(76,120,2,29,70,null,null,'2022-02-21 11:49:00',1,1,null,50000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-02-23 09:07:44',null,0,null,null,null,null,2,null,9,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(77,1,2,34,25,5,null,'2022-02-24 11:03:00',2,11,2,0.00,0.00,0.00,0.00,0.00,10.00,0,null,'2022-02-25',1,0,null,0,null,null,1,null,60,null,null,2,null,1,58,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(78,1,2,34,25,5,null,'2022-02-24 11:03:00',2,11,2,0.00,0.00,0.00,0.00,0.00,10.00,0,null,'2022-06-27',123,0,null,0,null,null,1,null,60,null,null,2,null,1,58,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(79,1,2,34,25,5,null,'2022-02-24 11:03:00',2,11,1,0.00,0.00,0.00,0.00,0.00,10.00,0,100,'2022-02-25',null,0,null,0,null,null,1,null,60,null,null,2,null,1,58,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(80,64,2,13,17,null,3,'2021-12-21 13:41:00',1,12,null,2450.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,60,'2021-12-21 13:41:00',null,2,null,1,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(81,1,2,11,25,null,null,'2022-03-03 10:50:00',2,12,1,44850.00,44850.00,44850.00,0.00,0.00,0.00,0,null,'2022-03-03',null,0,null,0,null,null,1,null,60,null,null,2,null,4,20,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(82,140,2,13,17,null,3,'2022-03-03 10:54:00',1,10,null,1745.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,60,'2022-03-03 10:54:00',null,2,null,4,29,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(83,141,2,12,18,null,null,'2022-03-03 10:54:00',1,10,1,1745.00,1745.00,1745.00,0.00,0.00,0.00,0,null,'2022-03-03',null,0,null,0,null,null,0,140,60,null,null,2,null,3,30,0,null,0,0.00,0.00,0.00,0.00,0.00,0.00,142,'ABC-1234'),(84,143,2,2,48,5,null,'2022-03-04 08:27:55',0,0,null,123.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,null,0,1001,45,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(85,144,2,13,17,5,3,'2022-03-10 08:21:00',1,2,null,1000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,60,'2022-03-10 08:21:00',null,2,null,1000,47,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(86,145,2,13,17,5,3,'2022-03-10 09:16:00',1,2,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,60,'2022-03-10 09:16:00',null,2,null,1001,47,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(87,151,2,12,18,5,null,'2022-03-15 14:05:00',1,12,1,500.00,500.00,500.00,0.00,0.00,0.00,0,null,'2022-03-15',null,0,null,0,null,null,0,null,60,null,null,2,null,1,48,0,null,0,0.00,0.00,0.00,0.00,0.00,0.00,142,434345345),(88,152,2,12,18,5,null,'2022-03-15 14:20:00',1,2,1,500.00,500.00,500.00,0.00,0.00,0.00,0,null,'2022-03-15',null,0,null,0,null,null,0,null,60,null,null,2,null,2,48,0,null,0,0.00,0.00,0.00,0.00,0.00,0.00,143,12321),(89,155,2,36,83,5,null,'2022-04-26 14:46:00',null,null,null,120.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-04-26 15:12:46',null,0,null,null,null,null,2,null,2,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(90,155,2,36,83,5,null,'2022-04-26 14:46:00',null,null,null,120.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-04-26 15:13:05',null,0,null,null,null,null,2,null,2,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(91,157,2,36,83,null,null,'2022-04-28 14:44:00',null,null,null,10000000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-04-28 14:58:19',null,0,null,null,null,null,2,null,3,60,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(92,156,2,36,83,null,null,'2022-04-27 13:55:00',null,null,null,1000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-04-28 15:01:51',null,0,null,null,null,null,2,null,2,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(93,156,2,36,83,null,null,'2022-04-27 13:55:00',null,null,null,1000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-04-28 15:05:01',null,0,null,null,null,null,2,null,2,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(94,156,2,36,83,null,null,'2022-04-27 13:55:00',null,null,null,1000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-04-28 15:05:13',null,0,null,null,null,null,2,null,2,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(95,156,2,36,83,null,null,'2022-04-27 13:55:00',null,null,null,1000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-04-29 13:50:22',null,0,null,null,null,null,2,null,2,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(96,156,2,36,83,null,null,'2022-04-27 13:55:00',null,null,null,1000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-04-29 13:52:44',null,0,null,null,null,null,2,null,2,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(97,156,2,36,83,null,null,'2022-04-27 13:55:00',null,null,null,1000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-04-29 14:10:33',null,0,null,null,null,null,2,null,2,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(98,155,2,36,83,5,null,'2022-04-26 14:46:00',null,null,null,120.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-05 15:30:16',null,0,null,null,null,null,2,null,2,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(99,159,2,37,84,5,null,'2022-05-11 15:08:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-11 15:10:18',null,0,null,null,null,null,null,null,1,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(100,159,2,37,84,5,null,'2022-05-11 15:08:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-11 15:16:31',null,0,null,null,null,null,null,null,1,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(101,159,2,37,84,5,null,'2022-05-11 15:08:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-11 15:24:15',null,0,null,null,null,null,null,null,1,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(102,159,2,37,84,5,null,'2022-05-11 15:08:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-11 15:24:33',null,0,null,null,null,null,null,null,1,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(103,158,2,36,83,null,null,'2022-05-10 13:34:00',null,null,null,10000000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-11 16:13:07',null,0,null,null,null,null,2,null,4,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(104,159,2,37,84,5,null,'2022-05-11 15:08:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-11 16:14:28',null,0,null,null,null,null,null,null,1,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(105,156,2,36,83,null,null,'2022-04-27 13:55:00',null,null,null,1000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-11 16:15:01',null,0,null,null,null,null,2,null,2,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(106,155,2,36,83,5,null,'2022-04-26 14:46:00',null,null,null,120.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-11 16:18:38',null,0,null,null,null,null,2,null,2,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(107,158,2,36,83,null,null,'2022-05-10 13:34:00',null,null,null,10000000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-12 08:18:08',null,0,null,null,null,null,2,null,4,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(108,158,2,36,83,null,null,'2022-05-10 13:34:00',null,null,null,10000000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-12 13:51:36',null,0,null,null,null,null,2,null,4,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(109,155,2,36,83,5,null,'2022-04-26 14:46:00',null,null,null,120.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-12 13:55:52',null,0,null,null,null,null,2,null,2,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(110,155,2,36,83,5,null,'2022-04-26 14:46:00',null,null,null,120.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-12 13:57:44',null,0,null,null,null,null,2,null,2,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(111,155,2,36,83,5,null,'2022-04-26 14:46:00',null,null,null,120.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-12 13:58:28',null,0,null,null,null,null,2,null,2,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(112,160,2,36,83,null,null,'2022-05-12 08:16:00',null,null,null,500000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-12 14:30:54',null,0,null,null,null,null,2,null,4,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(113,168,2,37,84,5,null,'2022-05-12 14:34:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-12 14:35:17',null,0,null,null,null,null,null,null,7,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(114,166,2,37,84,5,null,'2022-05-12 14:33:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-12 14:35:55',null,0,null,null,null,null,null,null,5,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(115,165,2,36,83,5,null,'2022-05-12 14:31:00',null,null,null,50000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-12 14:36:22',null,0,null,null,null,null,2,null,4,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(116,170,2,37,84,5,null,'2022-05-12 14:37:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-12 14:38:28',null,0,null,null,null,null,null,null,8,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(117,170,2,37,84,5,null,'2022-05-12 14:37:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-12 15:09:03',null,0,null,null,null,null,null,null,8,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(118,171,2,37,84,5,null,'2022-05-12 15:08:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-12 15:10:50',null,0,null,null,null,null,null,null,8,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(119,172,2,37,84,5,null,'2022-05-12 15:08:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-12 15:11:35',null,0,null,null,null,null,null,null,8,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(120,172,2,37,84,5,null,'2022-05-12 15:08:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-12 15:12:11',null,0,null,null,null,null,null,null,8,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(121,172,2,37,84,5,null,'2022-05-12 15:08:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-12 15:12:27',null,0,null,null,null,null,null,null,8,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(122,172,2,37,84,5,null,'2022-05-12 15:08:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-12 15:12:43',null,0,null,null,null,null,null,null,8,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(123,172,2,37,84,5,null,'2022-05-12 15:08:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-12 15:34:52',null,0,null,null,null,null,null,null,8,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(124,172,2,37,84,5,null,'2022-05-12 15:08:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-12 15:35:01',null,0,null,null,null,null,null,null,8,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(125,165,2,36,83,5,null,'2022-05-12 14:31:00',null,null,null,50000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-12 15:36:13',null,0,null,null,null,null,2,null,4,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(126,173,2,37,84,5,null,'2022-05-12 15:38:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-12 15:49:36',null,0,null,null,null,null,null,null,8,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(127,174,2,37,84,5,null,'2022-05-12 15:48:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-12 15:49:58',null,0,null,null,null,null,null,null,9,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(128,165,2,36,83,5,null,'2022-05-12 14:31:00',null,null,null,50000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-17 14:41:11',null,0,null,null,null,null,2,null,4,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(129,165,2,36,83,5,null,'2022-05-12 14:31:00',null,null,null,50000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-17 14:51:35',null,0,null,null,null,null,2,null,4,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(130,165,2,36,83,5,null,'2022-05-12 14:31:00',null,null,null,50000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-17 15:10:36',null,0,null,null,null,null,2,null,4,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(131,176,2,37,84,5,null,'2022-05-17 15:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-17 15:44:54',null,0,null,null,null,null,null,null,9,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(138,190,2,1,35,null,null,'2022-05-19 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,2,null,2,2,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(139,190,2,1,35,null,null,'2022-05-19 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,'add',null,null,null,null,0,'2022-05-19 10:44:43',null,0,null,null,null,null,2,null,2,2,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(140,191,2,37,84,5,null,'2022-05-19 14:53:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-19 16:57:53',null,0,null,null,null,null,null,null,11,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(141,1,2,11,25,null,null,'2021-12-21 14:07:00',2,5,1,15106.50,15106.50,15106.50,0.00,1678.50,10.00,0,'Sample update','2021-12-21',null,0,null,0,null,null,1,null,60,null,null,2,null,2,20,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(142,1,2,11,25,null,null,'2021-12-21 14:07:00',2,5,1,15106.50,15106.50,15106.50,0.00,1678.50,10.00,0,'Sample update','2021-12-21',null,0,null,0,null,null,1,null,60,null,null,2,null,2,20,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(143,1,2,11,25,null,null,'2021-12-21 14:07:00',2,5,1,8478.00,8478.00,8478.00,0.00,942.00,10.00,0,'Sample update','2021-12-21',null,0,null,0,null,null,1,9,60,null,null,2,null,2,20,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(144,193,2,1,35,null,null,'2022-05-20 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,1,null,3,2,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(145,193,2,1,35,null,null,'2022-05-20 00:00:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-20 15:00:24',null,0,null,null,null,null,2,null,3,2,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(146,182,2,36,83,5,null,'2022-05-18 14:07:00',null,null,null,123.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-05-26 13:40:48',null,0,null,null,null,null,2,null,7,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(147,197,2,37,84,null,null,'2022-08-17 14:17:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-08-17 14:29:11',null,0,null,null,null,null,null,null,12,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(148,199,2,37,84,null,null,'2022-08-17 14:44:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-08-17 15:05:39',null,0,null,null,null,null,null,null,12,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(149,199,2,37,84,null,null,'2022-08-17 14:44:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-08-17 15:30:40',null,0,null,null,null,null,null,null,12,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(150,194,2,36,83,5,null,'2022-06-08 14:27:00',null,null,null,50000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-08-18 12:59:12',null,0,null,null,null,null,2,null,7,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(151,196,2,36,83,null,null,'2022-08-17 14:17:00',null,null,null,30000000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-08-18 12:59:33',null,0,null,null,null,null,2,null,8,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(152,200,2,36,83,null,null,'2022-08-17 15:31:00',null,null,null,500000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-08-18 13:03:21',null,0,null,null,null,null,2,null,10,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(153,201,2,36,83,5,null,'2022-08-18 13:57:00',null,null,null,123.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-08-18 13:59:19',null,0,null,null,null,null,2,null,10,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(154,203,2,29,70,2,null,'2022-10-10 10:03:00',1,4,null,7500.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-10-10 10:13:27',null,0,null,null,null,null,2,null,9,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(155,36,2,31,71,null,null,'2021-12-15 09:24:00',1,6,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-10-10 10:37:06',null,1,null,null,null,null,2,null,11,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(156,100,2,33,78,null,null,'2022-01-13 11:55:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-10-10 11:31:22',null,0,null,null,null,null,null,null,'TM-7',0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(157,206,2,36,83,null,null,'2022-10-10 13:11:00',null,null,null,15000000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-10-10 13:42:43',null,0,null,null,null,null,2,null,11,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(158,199,2,37,84,null,null,'2022-08-17 14:44:00',null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2022-10-10 14:08:22',null,0,null,null,null,null,null,null,12,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `item`;:||:Separator:||:


CREATE TABLE `item` (
  `idItem` int NOT NULL AUTO_INCREMENT,
  `barcode` char(20) DEFAULT NULL,
  `itemName` text,
  `idItemClass` int DEFAULT NULL,
  `idUnit` int DEFAULT NULL,
  `itemPrice` text,
  `reorderLevel` int DEFAULT NULL,
  `dateModified` datetime DEFAULT NULL,
  `releaseWithoutQty` int DEFAULT '0' COMMENT '0 - False\n1 - True',
  `salesGlAcc` int DEFAULT NULL,
  `inventoryGlAcc` int DEFAULT NULL,
  `costofsalesGlAcc` int DEFAULT NULL,
  `effectivityDate` date DEFAULT NULL,
  `archived` int DEFAULT '0',
  `sk` text,
  PRIMARY KEY (`idItem`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `item` WRITE;:||:Separator:||:
 INSERT INTO `item` VALUES(1,3,'bdd8e89fa76182172f3d10493b6deef7fa5e9d080eaaeea0b1563a239b1e4c38045f2c0d033f8fd08ad14db172744c2cc414ab49ab8097b2b477d58b204a423eTADJB9Or31eE1030pu3vegaNaM5LAWbTD46VGwv5QJc=',1,7,'621fb946e926af2503d14d5cdeaa805f89fd5527249d7256ef11b69835efbd3e2e3b5fe8b1c9805811cac476593502f2aaf7d77d4f89ca9c180370649526fa3eWDettsI2YO1dkRXWrj/XCnZL954QwwqNQ9mZktYOG6A=',10,null,0,null,null,null,'2020-06-12',1,92005130013),(2,2,'0becfcbd7e872538009100e81028923928ad5d1c14bc0ebe34d50145361e8c42a1b9ded8fcfb4a2d89aa76497d1090cb4550cde088cc1854f5b061e65125593fbdjB46h7YrxD3nSA82BTFvWSCQ8Mkx91u6YhCqMmmuM=',1,1,'b00407a43fe8a42324121b18056eb87b1a1710ae628ff607570ec48159f94d738484b4a3756b2b7bd5912ffbc6c31bbe6d49f889b16e236202b942c3abbbbc0a83lXIkvGLX+2IXjgo70LjI5oWXId/nNCTFixNV5GyqM=',0,null,0,null,null,null,'2021-08-18',1,92005181379),(3,'newt','daa2e4989815f8566e218c82d921b2e37f3d59088bce653e1d43a68f6e27a4d2adfa763334e00f65abc0927caeb82e770906c162f6c93da68d4c28cc78b80bebmjPsIS3dyxLG05+5Ll0KlIpXuwsJUt0gM3+hL5DGL/8=',1,2,'b885890347c413963b5aae119439888674d344ba1a456df1b4718d976f8577a5bf66402347f9fb127b1e6a8f3921ee6a12997071e6bd1691a563bd186aa4c0c61zzG+mlkzRByQPG1UgE+g/6PR5SURBDI9BOyfu332ro=',100,null,0,null,null,null,'2020-06-12',0,140523000984),(4,3,'66cc84fdbef93d1ef063bfa29f5b51f81edc8954f9b2529a05af2366e7e48ad3770d8b872e44775c05e409bbcec5d7b64e274925083bd58a1b1d3e5a62fa703eWKNMeNcxK7B5vuHFAiwvp5NZRJ0GKGAg6HHSk9D08OE=',6,3,'a0a3c66afb528c9fb504f7ae60312bb81d7bc67f1e84bba11f202614ab8c42860f53b9b0362c70df30a4988beaa2098504b2d6da9154e20b52019e1a6eaa0b0fV0Ud5GG14l6pdUOxgCdmdDoYfl8FHtOI/74wJ+BFZ60=',5,null,1,null,null,null,'2020-09-07',0,190113161224),(5,1023,'b43d6a86f00b34615d792995eab05e2ec4c29e6602112e26c943ac413a109d60e4f9e331293039f314e1075366e8453d08bb69b9077c5d6b252c24b1c04acf7f6q2A3Zuv+t21XlUF3nXqN3Yr1b+r0wF/66fWx/D4m1u91HktL+kZ+KiFFIzak1Ya',7,6,'86279977dc9ff5f49390d144894cb3d3ceac6580cceeb58d4f47927cf9fa05799e44239672434647258f1d4c7711d43ea8b5d1670a85e4fb360497aed4fef673/0qxCfspa1MQcC+/3fLt3rrqOPiizhRn4NdVY177I9Q=',1000,null,0,null,null,null,'2021-08-18',1,31201191985),(6,'SAMPITMCODE','a1b3ba40e432141d2dd66aeaf1e89cdda71407211770c0e6d479037fcc742a8b28d6e9b033277e85ad008df71b0ea618f8045f83b5fd7a8f6972200a9fc7100cD/HIGrHYd/GxmBbVTIt8UfhTvlU6XY9oqMDNwwtvQzk=',6,null,null,null,null,0,null,null,null,null,1,190113161269),(7,123,'353269dbdfce589122e7fb57af657bc0dc1fd6f8770874d6a7d5befcbf55fd4f134eda3677e27ddfa76cfd55010b14000bd9f4a20df4c4ec267e5d42cb0496f95c64X5rPyj6z4OHUrzu0yIjbavBsT6YyFutzwLE/rS0=',10,null,null,null,null,0,null,null,null,null,0,200519200169),(8,'qwerty','7fb300c5189429e0a5a82658c116a49a1c4e502f42aca48ee9d9942c0e429baae8cc57003c6f2126aa3c6c234d9984fde7515db7c6dab9f767565ee98c66e07aIZlgho+7ok0g1CjTbonqPsaHlGvGFkGtxwOv5hcUhXs=',11,null,null,null,null,0,null,null,null,null,1,200519200138),(9,'1stITEM','f1d1562a10426bdb2dc0b06d66f74a97a36f74d299f45c247c19031fcf72baa18efa4ff92b37c90333154bdbb5c910b091662af7259786b4b20fb0c0ac016f89NDbq2vpC90J2U7f6mmuBFiXaEViUWd9iueUp9aAAoG8=',15,7,'7a32e80c2d75c631bc958821ba8f6adfb27d359db8647e0c83bbf33e9a6a66f6e14f757a318e08eb238377f207a4e99e85f6531c3ce855c39d1ce432b6d7876bzcX0+URvq8W5oHoZTjzqvtD6zG1P/h36jydLEkJ+1Jc=',12,null,0,null,null,null,'2020-07-07',0,60918192091),(10,'2stITEM','88e8c8f93f0bfc25ea3554a5b04d7ded1be1457c37b7803f84be08c11530a4943678bc0847eb57154031f1d5eea0b4a13b241f81d542788aed3730e0b2fca51asDfgoc7HtXyp2dpGgQPvNIY/I2QN1O2RIqvTzAYmv0M=',15,7,'3d9df4d82dc8448b2ba455379067c2fe480adabbaa0c796a9de424d28267bc2b6de1eb93fe5dec0c24e6efc10f38839c760454958f6772ac795524d6c417e3afLzQPZ8za3aGzCHGccjOCaqRNj9QlKKx1fa6XlqKUG2k=',12,null,0,null,null,null,'2020-07-07',0,190503151457),(11,'3stITEM','f74a16f9807ad1da5596eee097f534ee16a5abe22eba77ba7b31768ed5100ad56ff86689fbfbd4b311dca233077d578384eaf2ab9effa5b9a71623973d8c218aY/STwNyVqZaemVRSFkkXbpvOYVI8I4f9RbbF3Cs7WJQ=',15,7,'8a605dc8e042b85bdba670f54c2699c368bf08a979bfd44dd7f754b2769678c1668090d8fffea0d6d10e735c773901d5131c6fe3a4865d82a611bfd56594f50d/EDbpufF28KdUpaNrEaLc3rlB+zsMT0MutB8kJ1Q7EE=',12,null,0,null,null,null,'2020-07-07',0,200809180476),(12,'4stITEM','7f35ccce874428dd25256bc443021a026c93069c83e7c0f82b3663fa2cf38c9ee58c64f725698b5eb39123936b8a9bef400e6fd2adac6fd28c240e35db3bd693qYIv/xX8zfY/RTNnG3gp1F3U8LdvCDdEoDC90ZS/vFk=',15,7,'137afa69a30f5150a0dcbd8e0690a1ab7017b8ff45816113ef645747de2c7f064ce5743356ffc044e99561aad65237bf46dd1df13bfab6df2c27318c5933ada939in/K6MA+a8WPSkbuhd4uRKziuO2Lrk2H8zxE6EQk4=',12,null,0,null,null,null,'2020-07-07',0,61521182061),(13,1,'5eaaf95b49cd8e00604ce59f8a3aeb8eb90fae485376c271d7583a77cd48cd90570f3ec48ece5bbfbaf74157ccb37de122bf6c9ff64dd7fdd70faea4ea3efec9ndFa6yJdpWwF3dyztAMRavrwI6e0Mu/Zfb5CpOvE2P8=',2,1,'dbba4621d473fa7e27084cc4ac9d39ffc15be1b404979f502de651d4e8dedd097373b00e33567afe99f9ef8cb0840022d2683266af5e0832ad345fc21829fa08bmhXuYCBVQvApgUki/9hkbSOFEjUc6Wl/67XLM7CRm4=',100,null,0,null,null,null,'2021-08-18',0,40512001338),(14,2,'48eedc8545bd433af7dc40b4f50d91abe23627a65882079508a7262b220c5b5509d0db3622fcb9134c1de6c3d14beab1f44b0cf75198f6713bc6a33ed24e3076Nqk3JpVKILVWTKGuJhRJaHOGi9kDYyeaPg5F9B6bnNc=',2,1,'9dd538965f3be18412d65fdd9008f1ff6e6242137e19c389f49210f799ef46417e3ae65fce432df4b5abb8a2f40ef7e2840d2793f44816b21cbe16434061e03eVkPIPXoo/VUIR25h/VlCtqIUb3FAuf0mWCnNyIe6oo8=',50,null,0,null,null,null,'2021-08-18',0,140519030162),(15,4,'353732da36a3e9819bd72bebc6189a72dcf93c2891b8594b94fbc995ee13b1093c8f0950306885ec2e42ae4b950efd21f899dd2b9139d4ef310f43c0cc22c45eAizm8GibaT3VZIz5KgAHj0B9/sRrLOrgrktf8j78h8w=',1,7,'33668f68b66d2e8d5bb47f0c753f16d9d4c342596d06f2c17576130ade096c717df5154f3a69f10834cfa78be064ede1a04ecc7b7f98323fd66a26a496a591b3Nsp5n9BUsTIYEr4eE1Pk3jdDTh2IE43iGKs+TMcvZ7g=',0,null,1,null,null,null,'2021-08-30',0,190113162097),(16,123456,'2faafc47a214ff9ba2baa70d1a13c1a9ac0cbec0da11012ef63c18c2d75a297cfea4b27f94705580f50443ba0324e9b6848749e3c9f3c4fd1b39a47ceac4bef63Qo4dsBMotu58/0EDRIsD019lXGBQxXpDLwOGeOjocsDK2qozF5HLbt+Tijx5BY/',10,7,0,0,null,0,null,null,null,'2022-03-03',1,42116120941),(17,321,'60e33ca5521614b54b2a57438c0acc19e655fda4624e2e7244205160781c9a2dd7174c3f9e4d3923b20f9ecb328f7c89c11eaaaa97882bebce58899429cc981bfjKYNwsWK86HnYXkI6D/fqo+dKAIOcJQ2R3bXON0h8Q=',3,1,0,0,null,0,null,null,null,'2022-03-10',1,92005132165),(18,'TESTDEL','ac362429646fbd19341c479baa87858c51cfcf91e1a48ffea540ddc58c7454f10f3f9fe871c29c179eb52be65a2896c6d0ba655978967ece5942f6bb5f8cc37bXaJjeAgQ53RB+msBQFrzRyPQ+DXfVpd5V62wB6QGSXE=',2,3,0,0,null,0,null,null,null,'2022-03-10',1,200519200036),(19,'Test Sell','b7a94f3af282230f87eb62c7d8155ada1b95326dc9e8560e7455654fcbd028964ebc1124d6cc126a3f6a0c0487c881c6b62e0fe03a23be6b438dd34583013079qQ3al6YTt05eV3zeaTv7atlFfohyAarnXjcm0amU2rA=',3,2,0,0,null,1,null,null,null,'2022-03-10',0,200519200095),(20,10023098123,'8f31cbc42f132b438d0d6018d6e8501fcf47dfeaa881f609f068548ef676780ed556211ddd50e11abd7a390464e814a9a980d65f54cefc258012ca3f51231141A7dzic3HFc2tLumvvmNcaU53PvicRo3glshzgxXwbxs=',6,9,'6754566d1ea2298f11f54207edcb6c53e0d6bbc8809087f070fe934bd2967e014146a8d581d2e301ada121c633b610f81036970def4f67630c23d887da8290b1PHfvTwNig2cefes04tzRnSaHmwvTMClmJArRaVQgPSs=',0,null,0,null,null,null,'2022-03-14',1,201500020550),(21,233423423423423,'df6c0ac5d4e61a01cdc5c6d606f1a519c51987fc2c1fa4745648cf6c339679fbfbd4c0735e99cafe5b582b4bfd21fe9cfe38aaf43ed7dc52eabd3a465701caabab7tYQ+nHOoi0jnq+sQpEwZAUHEV50iIYXCrUTKiWoA=',4,2,0,0,null,0,null,null,null,'2022-03-14',0,40512052059),(22,321123,'d6dbcf9c7e305704db81805730dd22e9919f6189694b10d9f6fc0b1c6bb26516a676ab907976b08a8096143d5c075aafe0ae394ac12b8130bd468347781365c8lJaGXL7grtRzh/dsXSs8hnwG6Kj4fW+kYZSfIwGslTs=',1,7,'b08d3890faf01c4fe46fdf25df706ee2e3427d83a979a48b4c07f6aaa9163dfab85de98bad41e2f976df42b45a246596a63324fb847f7c5256c999f8a5afe68eCXZGozy9EdJYqckoW89vsgya/b5WjkJHDtsb6lLJBkc=',100,null,0,null,null,null,'2022-05-13',0,140523000962),(23,98789,'e9597a40e579beb5ed22919eff45b2ba9e279f24c886700976aedfdf34ac8355563b8b43f0e4c21d77c21328b25ffc382d9fdc6d1a5720f6992a6ebd87dfee13dcNOYldqpziPyaa1Y/eqcgnQ/njIdv5n/zo4w8sTdIs=',1,7,'e2c155c0e1cf381e1f037fa892bcabc56b5dd4e08436df9d40e1f423d8e3e713704b52c836b053aee97e9a80b6331dd1e5e0bdd5aaf9e2fbf7d4e340f1ca8e12fJ+uSnMm5UkM1VHi74Ek+84rI0gjO8LPbOdGDo5XTjY=',100,null,0,null,null,null,'2022-05-13',1,200519202114),(24,1987654321,'36dc5ff417dbbcbd270d419896331a35ba1eb497921172535d556f31839ab2e48a7799ac4b98666f7f83944a624d496ec52e2e4a5188a897a4c1adde39681dd450zifMtLfaLzfH2cmB9r/bMK5O+wUglU3OtT54AiF4c=',2,2,0,0,null,0,null,null,null,'2022-05-20',1,201500020533),(25,12312312312,'11ffb1d4f41df12a3685a5f8f5cc94d94d9995c56a238000f2fa45d66f7b17d1b4a849670d2192e8a342312a739a0f2e2df4d9cee04e1e4d80b8386916546b56HUr5SqbirsPTOmoljRiaCwqoCbFLuPyYnomTCj1Cmw0=',2,2,0,10,null,0,null,null,null,'2022-05-26',1,040512052036),(26,08042022,'8c9fadbcc3bcc0873109f5573d5d3240eee015e8750c0533260f31513827eaeb06f93112078c34c938c4be92a51fed4e2cc87313f4a810f7a5d4b60a935a5729HbDnpGG80ZSQ2rpVoetGyfE91U+qhAJFmYDTV2TVopahzBuYGugI9TCAo4ze/arC',6,3,'b4c87bb57e38e1addc032d2b0f5bed250d2167912719394ccc87d697172866a849e222e4e710758db9938ef2a0355353e5cb6b40ce8f88d44d86d4c9c239b1e227QDPFNvaIVRoOeF4MKRh521XkiizQm/85bAGkvYIZs=',0,null,0,null,null,null,'2022-08-04',1,140523000920);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `itemaffiliate`;:||:Separator:||:


CREATE TABLE `itemaffiliate` (
  `idItemAffiliate` int NOT NULL AUTO_INCREMENT,
  `idItem` int DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  `selected` int DEFAULT NULL,
  PRIMARY KEY (`idItemAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `itemaffiliate` WRITE;:||:Separator:||:
 INSERT INTO `itemaffiliate` VALUES(13,3,2,1),(14,3,4,1),(15,3,5,1),(27,5,2,1),(28,5,4,1),(29,2,2,1),(30,2,4,1),(31,2,5,1),(36,1,2,1),(37,1,4,1),(38,1,5,1),(41,4,2,1),(42,4,5,1),(43,4,6,1),(46,15,5,1),(47,16,2,1),(48,17,2,1),(49,18,2,1),(50,19,2,1),(51,20,2,1),(52,21,2,1),(53,24,2,1),(54,25,2,1),(55,26,2,1),(56,13,2,1),(57,13,12,1),(58,14,2,1),(59,14,12,1);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `itemaffiliatehistory`;:||:Separator:||:


CREATE TABLE `itemaffiliatehistory` (
  `idItemAffiliateHistory` int NOT NULL AUTO_INCREMENT,
  `idItemHistory` int DEFAULT NULL,
  `idItem` int DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  `selected` int DEFAULT NULL,
  PRIMARY KEY (`idItemAffiliateHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `itemaffiliatehistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `itemclassification`;:||:Separator:||:


CREATE TABLE `itemclassification` (
  `idItemClass` int NOT NULL AUTO_INCREMENT,
  `classCode` int DEFAULT NULL,
  `className` char(20) DEFAULT NULL,
  `archived` int DEFAULT '0',
  PRIMARY KEY (`idItemClass`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `itemclassification` WRITE;:||:Separator:||:
 INSERT INTO `itemclassification` VALUES(1,1,'Tools',0),(2,2,'Beverage',0),(3,3,'BOLT',0),(4,4,'FILTER',0),(5,5,'FUSO FIGHTER',0),(6,6,'Sample Class',0),(7,7,'Classiefied',0),(8,8,'Class 1 Samp',0),(9,9,'Class 2 Samp',0),(10,10,'test',0),(11,11,'testtest',0),(12,12,'QWERTY',0),(13,13,'ASD',0),(14,14,'tyr',0),(15,15,'Numbered Item',0),(16,16,'Test Class',1),(17,17,'Test Classification',0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `itemclassificationhistory`;:||:Separator:||:


CREATE TABLE `itemclassificationhistory` (
  `idClassificationHistory` int NOT NULL AUTO_INCREMENT,
  `idItemClass` int DEFAULT NULL,
  `classCode` int DEFAULT NULL,
  `className` char(20) DEFAULT NULL,
  PRIMARY KEY (`idClassificationHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `itemclassificationhistory` WRITE;:||:Separator:||:
 INSERT INTO `itemclassificationhistory` VALUES(1,16,16,'Test Class');:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `itemhistory`;:||:Separator:||:


CREATE TABLE `itemhistory` (
  `idItemHistory` int NOT NULL AUTO_INCREMENT,
  `idItem` int DEFAULT NULL,
  `barcode` char(20) DEFAULT NULL,
  `itemName` text,
  `idItemClass` int DEFAULT NULL,
  `idUnit` int DEFAULT NULL,
  `itemPrice` text,
  `reorderLevel` int DEFAULT NULL,
  `dateModified` datetime DEFAULT NULL,
  `releaseWithoutQty` int DEFAULT '0',
  `salesGlAcc` int DEFAULT NULL,
  `inventoryGlAcc` int DEFAULT NULL,
  `costofsalesGlAcc` int DEFAULT NULL,
  `effectivityDate` date DEFAULT NULL,
  PRIMARY KEY (`idItemHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `itemhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `itempricehistory`;:||:Separator:||:


CREATE TABLE `itempricehistory` (
  `idPrice` int NOT NULL AUTO_INCREMENT,
  `idItem` varchar(45) DEFAULT NULL,
  `itemPrice` text,
  `effectivityDate` date DEFAULT NULL,
  PRIMARY KEY (`idPrice`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `itempricehistory` WRITE;:||:Separator:||:
 INSERT INTO `itempricehistory` VALUES(1,1,0,'2020-06-12'),(2,2,0,'2020-06-12'),(3,2,'0069011692e8b585e6db221a3f223d72bda95463a7a08b47a6a97134ecbd6ef1b6b2f666af0349e6cc11d10eb3194d4b3aa0ad21de943a3f4e8c3f12923bf3fcUbZq/CN+x/Prn/qbSVharCn4Ey4OpxCyOQdGADaTKs0=','2020-06-12'),(4,1,'7bce0bd9a1fcaa5ce78a35aa81e7dabdad9457be11863c0073a1cd2874d68ebe2641b73ec763502f0645706c11e291e2aa82cc42d2fe54619c8bfa69870e9e18lvinWnRX1xL5pYzE/lU79DXQQyUHTgAw0AUTujCzBYQ=','2020-06-12'),(5,3,'b885890347c413963b5aae119439888674d344ba1a456df1b4718d976f8577a5bf66402347f9fb127b1e6a8f3921ee6a12997071e6bd1691a563bd186aa4c0c61zzG+mlkzRByQPG1UgE+g/6PR5SURBDI9BOyfu332ro=','2020-06-12'),(6,2,'a1656fdc7c93886d6d7dc4b7fbfb102a4a7ce000a21f7ae5910f0e95b499b4f0f5a693af28434c0aefe7ce973fb2cab8266f8bf99f3b68d9a3cabbbf2c423e6bB372+12mVgWjDNwBwFIKWXm/g//RPWPDLhSPl/g7PEM=','2020-06-12'),(7,4,'a4381bb2d34e8c1ecdfeab62d4ff50ce55a16ba721542ba44517fcec4a6e665a884fe2c148372f305c1d1bbab4822e0333beb531c3e4b04d750373e831fdf4f4j6VhTxWYLSrUyTRxKDShl6x02euiUABOMn2lpA/Rqd4=','2020-07-04'),(8,4,'5caad0c9a39cd0437bcf62d1cac3c9868bd7e16345cb4e3ef810a203ec2b8ceba02560d8463f4f3db4d96cf71caef625289a0d22e8808326e9781fe296b78381hAY0/4ChGC1O8SRwWA9ngJkMI7G3J+q6fcP7qEuegS4=','2020-09-07'),(9,5,'f9891c49c1f15d71fa4a318f3b301d743a7aeb6561cb004c35cd70d60421ae90c10578cca8a8cf1bc98c9fc963bea3ded60a46c2bc1cd317be4ee700938067a9S8D2OmSFqDNVy/arNN9HsFN5P8R1n105amd4p69OSms=','2020-07-04'),(10,5,'e68775be5c051db1e8f192dce3c2c0b568f903915c14531e277561d0f68aa1235c6ca8d6bd8cf8fb963c2bdb97b9642f56e0114daf081b4b86b2e5950105e130aPPgQCCFZekavgh73GomxyanIhOpXH8Uduqxzz2xu8w=','2020-07-04'),(11,5,'86279977dc9ff5f49390d144894cb3d3ceac6580cceeb58d4f47927cf9fa05799e44239672434647258f1d4c7711d43ea8b5d1670a85e4fb360497aed4fef673/0qxCfspa1MQcC+/3fLt3rrqOPiizhRn4NdVY177I9Q=','2021-08-18'),(12,2,'b00407a43fe8a42324121b18056eb87b1a1710ae628ff607570ec48159f94d738484b4a3756b2b7bd5912ffbc6c31bbe6d49f889b16e236202b942c3abbbbc0a83lXIkvGLX+2IXjgo70LjI5oWXId/nNCTFixNV5GyqM=','2021-08-18'),(13,13,'ede6824e6e62a76fd4779d5caf99ee8681093ed313e9e6aa94f054933ea17df2ed228885d6ba217c642e221e7af611857c01085329cb4a4524cae68cb3b6caabkn5yDPQ0Q+/p36F2imFaWjJ2WNjFpHTy/1B85RD+dqg=','2021-08-18'),(14,14,'d0a4280ebf4d8cfac90eb4fd489d2c243a7fa7f044c8f92dc98c5114ad035d4e4e6dee6e459a09869b7060f348ed3001face070b6b8979a7bf0848fe63f789efGdHH4fc9642xRxX66ETKSxL19CkanLRe1XWFDXsb8zk=','2021-08-18'),(15,1,'621fb946e926af2503d14d5cdeaa805f89fd5527249d7256ef11b69835efbd3e2e3b5fe8b1c9805811cac476593502f2aaf7d77d4f89ca9c180370649526fa3eWDettsI2YO1dkRXWrj/XCnZL954QwwqNQ9mZktYOG6A=','2020-06-12'),(16,4,'7aaab1304a10b91875b5131acf1f445aa25ee1e6cbacb18e02c39fd59e811fe99b274de2ac8e16d00f279a7d60ac44d788251cf3df0f3a3ce4b49f3932cfeff3bNC5DyCT6RKGxMVipVAN4NGGJry2DXwQ7+IPedKlWII=','2020-09-07'),(17,4,'a0a3c66afb528c9fb504f7ae60312bb81d7bc67f1e84bba11f202614ab8c42860f53b9b0362c70df30a4988beaa2098504b2d6da9154e20b52019e1a6eaa0b0fV0Ud5GG14l6pdUOxgCdmdDoYfl8FHtOI/74wJ+BFZ60=','2020-09-07'),(18,15,'3b4415333b5b2b4af8f5e5b00e1f5b518e82ad1b97901c14fbeedec81b7b77f1e79542b593fbf663a01ffbf132c50f70af4412f6cdb249669c698efd852fb561lAMSOrlvveTIeRDlwTcaK52q1O8lbam3U41/2FU0nJ8=','2021-08-19'),(19,15,'dae2da84b9b12a7700369796e420d6dbdae04e786c8db589a822b6a6fb2cd4ae77e7fcfa0c9dbc1a37e804c9ea02819963b922c1a57f52eb34febf0500e85a6cMLsAsvAjMRBC7Wsq5G0P1aCQz5BiyxUAniM5oPQpAL8=','2021-08-30'),(20,15,'33668f68b66d2e8d5bb47f0c753f16d9d4c342596d06f2c17576130ade096c717df5154f3a69f10834cfa78be064ede1a04ecc7b7f98323fd66a26a496a591b3Nsp5n9BUsTIYEr4eE1Pk3jdDTh2IE43iGKs+TMcvZ7g=','2021-08-30'),(21,16,0,'2022-03-03'),(22,17,0,'2022-03-10'),(23,18,0,'2022-03-10'),(24,19,0,'2022-03-10'),(25,20,'6754566d1ea2298f11f54207edcb6c53e0d6bbc8809087f070fe934bd2967e014146a8d581d2e301ada121c633b610f81036970def4f67630c23d887da8290b1PHfvTwNig2cefes04tzRnSaHmwvTMClmJArRaVQgPSs=','2022-03-14'),(26,21,0,'2022-03-14'),(27,24,0,'2022-05-20'),(28,25,0,'2022-05-26'),(29,26,'b4c87bb57e38e1addc032d2b0f5bed250d2167912719394ccc87d697172866a849e222e4e710758db9938ef2a0355353e5cb6b40ce8f88d44d86d4c9c239b1e227QDPFNvaIVRoOeF4MKRh521XkiizQm/85bAGkvYIZs=','2022-08-04'),(30,13,'dbba4621d473fa7e27084cc4ac9d39ffc15be1b404979f502de651d4e8dedd097373b00e33567afe99f9ef8cb0840022d2683266af5e0832ad345fc21829fa08bmhXuYCBVQvApgUki/9hkbSOFEjUc6Wl/67XLM7CRm4=','2021-08-18'),(31,14,'9dd538965f3be18412d65fdd9008f1ff6e6242137e19c389f49210f799ef46417e3ae65fce432df4b5abb8a2f40ef7e2840d2793f44816b21cbe16434061e03eVkPIPXoo/VUIR25h/VlCtqIUb3FAuf0mWCnNyIe6oo8=','2021-08-18');:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `location`;:||:Separator:||:


CREATE TABLE `location` (
  `idLocation` int NOT NULL AUTO_INCREMENT,
  `locationCode` int DEFAULT NULL,
  `locationName` char(50) DEFAULT NULL,
  PRIMARY KEY (`idLocation`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `location` WRITE;:||:Separator:||:
 INSERT INTO `location` VALUES(2,2,'CDO'),(3,3,'Opol'),(4,4,'El Salvador');:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `locationhistory`;:||:Separator:||:


CREATE TABLE `locationhistory` (
  `idLocationHistory` int NOT NULL AUTO_INCREMENT,
  `idLocation` int DEFAULT NULL,
  `locationCode` int DEFAULT NULL,
  `locationName` char(50) DEFAULT NULL,
  PRIMARY KEY (`idLocationHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `locationhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `logs`;:||:Separator:||:


CREATE TABLE `logs` (
  `idLog` int NOT NULL AUTO_INCREMENT,
  `idAffiliate` int DEFAULT NULL,
  `idLocation` int DEFAULT NULL,
  `dateLog` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `idEu` int DEFAULT NULL,
  `actionLogDescription` text,
  `idReference` int DEFAULT NULL,
  `referenceNum` int DEFAULT NULL,
  `idModule` int DEFAULT NULL,
  PRIMARY KEY (`idLog`)
) ENGINE=InnoDB AUTO_INCREMENT=988 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `logs` WRITE;:||:Separator:||:
 INSERT INTO `logs` VALUES(1,null,null,'2021-11-24','10:24:21',60,'superddt has logged out of the system.',null,null,null),(2,null,null,'2021-11-24','15:50:24',60,'superddt has logged out of the system.',null,null,null),(3,null,null,'2021-11-26','08:35:02',60,'superddt has logged out of the system.',null,null,null),(4,null,null,'2021-11-26','08:53:47',60,'superddt edited the truck type details.',null,null,null),(5,null,null,'2021-11-26','08:53:56',60,'superddt edited the truck type details.',null,null,null),(6,null,null,'2021-11-26','08:54:12',60,'superddt edited the truck type details.',null,null,null),(7,null,null,'2021-11-26','09:01:12',60,'superddt added a new truck type.',null,null,null),(8,null,null,'2021-11-26','09:01:33',60,'superddt added a new truck type.',null,null,null),(9,null,null,'2021-11-26','09:01:42',60,'superddt added a new truck type.',null,null,null),(10,null,null,'2021-11-26','09:02:50',60,'superddt edited the truck type details.',null,null,null),(11,null,null,'2021-11-26','09:03:00',60,'superddt deleted a truck type',null,null,null),(12,null,null,'2021-11-26','09:03:18',60,'superddt edited the truck type details.',null,null,null),(13,null,null,'2021-11-26','09:13:56',60,'superddt added a new initial reference TRT',null,null,null),(14,null,null,'2021-11-26','09:19:31',60,'superddt added a new series reference DT',null,null,null),(15,null,null,'2021-11-26','09:20:59',60,'superddt added a new customer QA Customer',null,null,null),(16,null,null,'2021-11-26','09:37:46',60,'superddt edited the truck type details.',null,null,null),(17,null,null,'2021-11-26','10:12:28',60,'superddt has logged out of the system.',null,null,null),(18,2,null,'2021-12-01','08:07:40',60,' added a new transaction.',9,10,2),(19,null,null,'2021-12-02','10:05:55',60,'superddt edited the truck type details.',null,null,null),(20,null,null,'2021-12-02','10:08:09',60,'superddt deleted a truck type',null,null,null),(21,null,null,'2021-12-02','10:20:13',60,'superddt has logged out of the system.',null,null,null),(22,null,null,'2021-12-02','10:23:22',60,'superddt edited the truck type details.',null,null,null),(23,null,null,'2021-12-02','10:23:25',60,'superddt deleted a truck type',null,null,null),(24,null,null,'2021-12-03','08:11:52',60,'superddt has logged out of the system.',null,null,null),(25,2,null,'2021-12-09','10:37:52',60,'Generates Receivable Schedule Report',null,null,null),(26,2,null,'2021-12-09','11:34:21',60,'Modified the module access for the user account, superddt of Mallory Figueroa, with usertype Administrator.',null,null,null),(27,2,null,'2021-12-09','11:35:17',60,'Modified the module access for the user account, superddt of Mallory Figueroa, with usertype Administrator.',null,null,null),(28,null,null,'2021-12-09','13:45:43',60,'superddt added a new initial reference RHE',null,null,null),(29,null,null,'2021-12-09','13:47:07',60,'superddt added a new series reference RHE',null,null,null),(30,2,null,'2021-12-09','13:53:48',60,'Added new employee, Nelle Buckley.',null,null,null),(31,2,null,'2021-12-09','13:59:03',60,'Added new employee, Kennedy Patel.',null,null,null),(32,null,null,'2021-12-13','08:22:00',60,' removed the transaction.',null,null,71),(33,null,null,'2021-12-13','08:40:33',60,'  Rental of Heavy Equipment List: superddt printed a PDF report. transaction.',null,null,71),(34,null,null,'2021-12-13','08:41:35',60,'  Rental of Heavy Equipment List: superddt printed a PDF report. transaction.',null,null,71),(35,null,null,'2021-12-13','08:46:44',60,'  Rental of Heavy Equipment List: superddt printed a PDF report. transaction.',null,null,71),(36,2,null,'2021-12-14','07:51:43',60,'Vouchers Receivable:  added a new Vouchers Receivable Transaction.',17,100,58),(37,2,null,'2021-12-14','10:25:50',60,'Vouchers Payable :  added a new Vouchers Payable Transaction.',16,1000,57),(38,2,null,'2021-12-14','10:45:06',60,'Vouchers Payable :  added a new Vouchers Payable Transaction.',16,1001,57),(39,2,null,'2021-12-14','10:46:28',60,'Vouchers Payable :  added a new Vouchers Payable Transaction.',16,1002,57),(40,2,null,'2021-12-14','10:47:21',60,'Vouchers Receivable:  added a new Vouchers Receivable Transaction.',17,101,58),(41,2,null,'2021-12-14','10:49:29',60,'Vouchers Receivable:  added a new Vouchers Receivable Transaction.',17,102,58),(42,null,null,'2021-12-15','08:14:38',60,' removed the transaction.',null,null,71),(43,null,null,'2021-12-15','08:14:41',60,' removed the transaction.',null,null,71),(44,null,null,'2021-12-15','08:14:47',60,' removed the transaction.',null,null,71),(45,null,null,'2021-12-15','08:14:53',60,' removed the transaction.',null,null,71),(46,null,null,'2021-12-15','08:28:10',60,' removed the transaction.',null,null,71),(47,null,null,'2021-12-15','08:31:24',60,' removed the transaction.',null,null,71),(48,null,null,'2021-12-15','08:52:14',60,' removed the transaction.',null,null,71),(49,null,null,'2021-12-15','08:52:17',60,' removed the transaction.',null,null,71),(50,null,null,'2021-12-15','08:59:47',60,' removed the transaction.',null,null,71),(51,null,null,'2021-12-15','12:42:17',60,'  Rental of Heavy Equipment List: superddt printed a PDF report. transaction.',null,null,71),(52,null,null,'2021-12-15','12:43:06',60,'  Rental of Heavy Equipment List: superddt printed a PDF report. transaction.',null,null,71),(53,null,null,'2021-12-15','12:47:51',60,'superddt has logged out of the system.',null,null,null),(54,null,null,'2021-12-15','12:50:01',60,'superddt has logged out of the system.',null,null,null),(55,null,null,'2021-12-15','12:50:53',60,'Mallory Figueroa  Rental of Heavy Equipment List: superddt printed a PDF report. transaction.',null,null,71),(56,null,null,'2021-12-15','12:51:58',60,'superddt has logged out of the system.',null,null,null),(57,null,null,'2021-12-15','12:53:10',60,'superddt has logged out of the system.',null,null,null),(58,null,null,'2021-12-15','12:54:38',60,'Mallory Figueroa  Rental of Heavy Equipment List: superddt printed a PDF report. transaction.',null,null,71),(59,null,null,'2021-12-15','12:56:15',60,'Mallory Figueroa  Rental of Heavy Equipment List: superddt printed a PDF report. transaction.',null,null,71),(60,null,null,'2021-12-15','14:59:16',60,'superddt has logged out of the system.',null,null,null),(61,2,null,'2021-12-15','15:12:11',60,'Modified the module access for the user account, superddt of Mallory Figueroa, with usertype Administrator.',null,null,null),(62,null,null,'2021-12-15','15:13:14',60,'superddt edited the truck project details.',null,null,null),(63,null,null,'2021-12-15','15:22:48',60,'superddt has logged out of the system.',null,null,null),(64,null,null,'2021-12-15','15:30:52',60,'superddt edited the truck project details.',null,null,null),(65,null,null,'2021-12-15','15:34:10',60,'superddt edited the truck type details.',null,null,null),(66,null,null,'2021-12-15','15:42:35',60,'superddt edited the truck project details.',null,null,null),(67,null,null,'2021-12-15','15:44:31',60,'superddt deleted a truck project',null,null,null),(68,null,null,'2021-12-15','15:44:38',60,'superddt deleted a truck project',null,null,null),(69,null,null,'2021-12-15','15:47:21',60,'superddt edited the truck project details.',null,null,null),(70,null,null,'2021-12-15','15:49:46',60,'superddt deleted a truck project',null,null,null),(71,null,null,'2021-12-15','15:51:28',60,'superddt edited the truck project details.',null,null,null),(72,null,null,'2021-12-15','16:10:24',60,'Mallory Figueroa removed the transaction.',null,null,70),(73,null,null,'2021-12-15','16:17:05',60,'Mallory Figueroa removed the transaction.',null,null,71),(74,null,null,'2021-12-15','16:26:16',60,'superddt deleted a truck type',null,null,null),(75,null,null,'2021-12-15','16:35:25',60,'superddt edited the truck type details.',null,null,null),(76,null,null,'2021-12-15','16:40:30',60,'superddt edited the truck type details.',null,null,null),(77,2,null,'2021-12-15','16:51:43',60,'Exported the generated Truck Profile Report (PDF)',null,null,null),(78,2,null,'2021-12-15','16:52:17',60,'Exported the generated Truck Type Report (Excel).',null,null,null),(79,null,null,'2021-12-15','16:52:37',60,'superddt added a new truck type.',null,null,null),(80,null,null,'2021-12-15','16:56:03',60,'superddt added a new truck type.',null,null,null),(81,null,null,'2021-12-15','16:58:50',60,'superddt deleted a truck profile',null,null,null),(82,null,null,'2021-12-15','17:03:07',60,'superddt deleted a truck profile',null,null,null),(83,null,null,'2021-12-16','08:03:26',60,'superddt edited the truck type details.',null,null,null),(84,2,null,'2021-12-16','10:02:06',60,'Exported the generated Truck Type Report (PDF)',null,null,null),(85,null,null,'2021-12-16','12:38:07',60,'superddt edited the truck type details.',null,null,null),(86,null,null,'2021-12-16','17:43:44',60,'superddt edited the truck type details.',null,null,null),(87,null,null,'2021-12-16','17:44:08',60,'superddt edited the truck type details.',null,null,null),(88,null,null,'2021-12-16','17:44:21',60,'superddt deleted a truck profile',null,null,null),(89,null,null,'2021-12-16','17:44:23',60,'superddt deleted a truck profile',null,null,null),(90,null,null,'2021-12-16','17:44:25',60,'superddt deleted a truck profile',null,null,null),(91,null,null,'2021-12-16','17:44:27',60,'superddt deleted a truck profile',null,null,null),(92,null,null,'2021-12-16','17:44:28',60,'superddt deleted a truck profile',null,null,null),(93,null,null,'2021-12-16','18:01:29',60,'superddt edited the truck type details.',null,null,null),(94,null,null,'2021-12-18','13:17:00',60,'superddt has logged out of the system.',null,null,null),(95,null,null,'2021-12-20','11:13:13',60,'superddt added a new truck project.',null,null,null),(96,null,null,'2021-12-20','11:14:00',60,'superddt edited the truck project details.',null,null,null),(97,null,null,'2021-12-20','11:15:44',60,'superddt deleted a truck project',null,null,null),(98,null,null,'2021-12-20','11:16:24',60,'superddt edited the truck project details.',null,null,null),(99,null,null,'2021-12-20','11:16:31',60,'superddt deleted a truck project',null,null,null),(100,2,null,'2021-12-20','11:17:10',60,'Exported the generated Truck Project Report (PDF)',null,null,null),(101,2,null,'2021-12-20','11:17:59',60,'Exported the generated Truck Project Report (Excel).',null,null,null),(102,null,null,'2021-12-20','13:09:46',60,'Mallory Figueroa added a new truck profile.',null,null,74),(103,null,null,'2021-12-20','14:20:05',60,'Mallory Figueroa added a new truck profile.',null,null,74),(104,null,null,'2021-12-20','14:21:14',60,'Mallory Figueroa modified the truck profile.',null,null,74),(105,null,null,'2021-12-20','14:21:57',60,'Mallory Figueroa modified the truck profile.',null,null,74),(106,null,null,'2021-12-20','14:24:42',60,'Mallory Figueroa modified the truck profile.',null,null,74),(107,null,null,'2021-12-20','14:25:51',60,'Mallory Figueroa removed the truck profile.',null,null,74),(108,null,null,'2021-12-20','14:26:34',60,'Mallory Figueroa removed the truck profile.',null,null,74),(109,null,null,'2021-12-20','14:26:45',60,'Mallory Figueroa added a new truck profile.',null,null,74),(110,null,null,'2021-12-20','14:27:05',60,'Mallory Figueroa modified the truck profile.',null,null,74),(111,null,null,'2021-12-20','14:27:11',60,'Mallory Figueroa removed the truck profile.',null,null,74),(112,null,null,'2021-12-20','14:36:34',60,'Mallory Figueroa modified the truck profile.',null,null,74),(113,null,null,'2021-12-20','14:39:00',60,'Mallory Figueroa removed the truck profile.',null,null,74),(114,null,null,'2021-12-20','14:42:55',60,'Mallory Figueroa added a new truck profile.',null,null,74),(115,null,null,'2021-12-20','14:43:59',60,'Mallory Figueroa removed the truck type.',null,null,72),(116,null,null,'2021-12-20','14:44:09',60,'Mallory Figueroa added a new truck type.',null,null,72),(117,null,null,'2021-12-20','14:44:14',60,'Mallory Figueroa removed the truck type.',null,null,72),(118,null,null,'2021-12-20','14:44:59',60,'Mallory Figueroa modified the truck profile.',null,null,74),(119,null,null,'2021-12-20','14:47:49',60,'Mallory Figueroa modified the truck profile.',null,null,74),(120,null,null,'2021-12-20','14:52:37',60,'Mallory Figueroa removed the truck profile.',null,null,74),(121,null,null,'2021-12-20','14:57:03',60,'Mallory Figueroa modified the truck profile.',null,null,74),(122,null,null,'2021-12-20','15:10:25',60,'Mallory Figueroa added a new truck type.',null,null,72),(123,null,null,'2021-12-20','15:12:46',60,'Mallory Figueroa added a new truck profile.',null,null,74),(124,null,null,'2021-12-20','15:13:32',60,'Mallory Figueroa removed the truck type.',null,null,72),(125,null,null,'2021-12-20','15:13:47',60,'Mallory Figueroa removed the truck type.',null,null,72),(126,null,null,'2021-12-20','15:14:22',60,'Mallory Figueroa added a new truck type.',null,null,72),(127,null,null,'2021-12-21','08:12:28',60,'superddt deleted a truck project',null,null,null),(128,null,null,'2021-12-21','08:21:00',60,'Mallory Figueroa modified the truck profile.',null,null,74),(129,null,null,'2021-12-21','08:28:27',60,'Mallory Figueroa removed the truck profile.',null,null,74),(130,null,null,'2021-12-21','08:31:47',60,'Mallory Figueroa added a new truck profile.',null,null,74),(131,null,null,'2021-12-21','08:34:20',60,'Mallory Figueroa added a new truck profile.',null,null,74),(132,null,null,'2021-12-21','08:38:08',60,'Mallory Figueroa modified the truck profile.',null,null,74),(133,null,null,'2021-12-21','08:38:26',60,'Mallory Figueroa modified the truck profile.',null,null,74),(134,2,null,'2021-12-21','09:54:26',60,'Added new affiliate, Test Affiliate - newest',null,null,null),(135,2,null,'2021-12-21','09:58:32',60,'Modified the affiliate, This is an affiliate',null,null,null),(136,2,null,'2021-12-21','10:05:27',60,'Added new employee, Monica Bing.',null,null,null),(137,2,null,'2021-12-21','10:17:50',60,'Deleted the user account of, Kennedy Patel.',null,null,null),(138,null,null,'2021-12-21','10:19:57',60,'Mallory Figueroa added a new truck profile.',null,null,74),(139,null,null,'2021-12-21','10:20:05',60,'Mallory Figueroa removed the truck profile.',null,null,74),(140,2,null,'2021-12-21','10:26:31',60,'Modified the employee, Monica Bing.',null,null,null),(141,2,null,'2021-12-21','10:26:43',60,'Modified the employee, Monica Bing.',null,null,null),(142,null,null,'2021-12-21','10:28:33',60,'Mallory Figueroa modified the truck profile.',null,null,74),(143,null,null,'2021-12-21','10:29:33',60,'Mallory Figueroa modified the truck profile.',null,null,74),(144,null,null,'2021-12-21','10:38:49',60,'Added new classification, Approver',null,null,null),(145,null,null,'2021-12-21','10:39:33',60,'Modified the classification, Test Employee Classification',null,null,null),(146,null,null,'2021-12-21','10:39:38',60,'Deleted the classificatin, Approver',null,null,null),(147,null,null,'2021-12-21','10:55:27',60,'superddt added a new customer New Customer',null,null,null),(148,null,null,'2021-12-21','10:55:41',60,'superddt edited the customer details of New Customer',null,null,null),(149,2,null,'2021-12-21','10:58:00',60,'Added a new supplier, New Supplier',null,null,null),(150,null,null,'2021-12-21','10:58:45',60,'superddt added a new initial reference NWDT',null,null,null),(151,null,null,'2021-12-21','10:59:11',60,'superddt added a new series reference NWDT',null,null,null),(152,null,null,'2021-12-21','10:59:40',60,'superddt edited the bank UCPB',null,null,null),(153,null,null,'2021-12-21','10:59:47',60,'superddt added a new bank Landbank of Ph',null,null,null),(154,2,null,'2021-12-21','11:01:45',60,'Generates payable schedule report',null,null,null),(155,2,null,'2021-12-21','11:01:46',60,'Generates payable schedule report',null,null,null),(156,2,null,'2021-12-21','11:01:52',60,'Generates payable schedule report',null,null,null),(157,2,null,'2021-12-21','11:01:56',60,'Generates Receivable Schedule Report',null,null,null),(158,2,null,'2021-12-21','11:03:25',60,'Mallory Figueroa added a new transaction.',9,11,2),(159,2,null,'2021-12-21','11:04:13',60,'Generates cheque summary report.',null,null,null),(160,2,null,'2021-12-21','11:04:14',60,'Generates cheque summary report.',null,null,null),(161,2,null,'2021-12-21','11:04:15',60,'Generates cheque summary report.',null,null,null),(162,2,null,'2021-12-21','11:04:20',60,'Generates cheque summary report.',null,null,null),(163,2,null,'2021-12-21','11:04:30',60,'Generates cheque summary report.',null,null,null),(164,2,null,'2021-12-21','11:04:30',60,'Generates cheque summary report.',null,null,null),(165,2,null,'2021-12-21','11:04:31',60,'Generates cheque summary report.',null,null,null),(166,2,null,'2021-12-21','11:04:31',60,'Generates cheque summary report.',null,null,null),(167,2,null,'2021-12-21','11:04:31',60,'Generates cheque summary report.',null,null,null),(168,2,null,'2021-12-21','11:04:31',60,'Generates cheque summary report.',null,null,null),(169,2,null,'2021-12-21','11:04:31',60,'Generates cheque summary report.',null,null,null),(170,2,null,'2021-12-21','11:04:52',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(171,2,null,'2021-12-21','11:04:57',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(172,2,null,'2021-12-21','11:05:21',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(173,2,null,'2021-12-21','11:07:42',60,'Generates cheque summary report.',null,null,null),(174,2,null,'2021-12-21','11:09:22',60,'Generates payable schedule report',null,null,null),(175,2,null,'2021-12-21','11:09:40',60,'Generates Purchase Order Monitoring',null,null,30),(176,2,null,'2021-12-21','11:09:44',60,'Generates Purchase Order Monitoring',null,null,30),(177,2,null,'2021-12-21','11:09:44',60,'Generates Purchase Order Monitoring',null,null,30),(178,2,null,'2021-12-21','11:09:45',60,'Generates Purchase Order Monitoring',null,null,30),(179,2,null,'2021-12-21','11:09:45',60,'Generates Purchase Order Monitoring',null,null,30),(180,2,null,'2021-12-21','11:09:45',60,'Generates Purchase Order Monitoring',null,null,30),(181,2,null,'2021-12-21','11:09:45',60,'Generates Purchase Order Monitoring',null,null,30),(182,2,null,'2021-12-21','11:10:33',60,'Mallory Figueroa added a new receiving transaction.',11,1,2),(183,2,null,'2021-12-21','11:10:37',60,'Generates payable schedule report',null,null,null),(184,2,null,'2021-12-21','11:11:30',60,'Generates payable schedule report',null,null,null),(185,2,null,'2021-12-21','11:11:43',60,'Generates Receivable Schedule Report',null,null,null),(186,2,null,'2021-12-21','11:11:53',60,'Exported the generated Receivable Schedule Report (PDF)',null,null,null),(187,2,null,'2021-12-21','11:12:09',60,'Exported the generated Receivable Schedule Report (Excel)',null,null,null),(188,2,null,'2021-12-21','11:12:31',60,'Generates Receivable Schedule Report',null,null,null),(189,2,null,'2021-12-21','11:12:35',60,'Generates payable schedule report',null,null,null),(190,2,null,'2021-12-21','11:12:37',60,'Exported the generated payable schedule report (PDF)',null,null,null),(191,2,null,'2021-12-21','11:12:47',60,'Exported the generated payable schedule report (Excel)',null,null,null),(192,2,null,'2021-12-21','11:12:54',60,'Generates Aging of Receivables.',null,null,null),(193,2,null,'2021-12-21','11:12:56',60,'Generates Aging of Receivables.',null,null,null),(194,2,null,'2021-12-21','11:13:08',60,'Generates Aging of Receivables.',null,null,null),(195,2,null,'2021-12-21','11:13:18',60,'Generates Aging of Receivables.',null,null,null),(196,2,null,'2021-12-21','11:13:20',60,'Generates Aging of Receivables.',null,null,null),(197,2,null,'2021-12-21','11:13:27',60,'Generates Aging of Receivables.',null,null,null),(198,2,null,'2021-12-21','11:13:32',60,'Generates Aging of Receivables.',null,null,null),(199,2,null,'2021-12-21','11:13:35',60,'Generates Aging of Receivables.',null,null,null),(200,2,null,'2021-12-21','11:14:09',60,'Exported the Generated Aging of Receivables (PDF).',null,null,null),(201,2,null,'2021-12-21','11:15:08',60,'Generates Aging of Payables.',null,null,null),(202,2,null,'2021-12-21','11:15:12',60,'Generates Aging of Payables.',null,null,null),(203,2,null,'2021-12-21','11:15:13',60,'Exported the generated Aging of Payables (PDF).',null,null,null),(204,2,null,'2021-12-21','11:15:31',60,'Exported the generated Aging of Payables (Excel).',null,null,null),(205,2,null,'2021-12-21','11:16:22',60,'Exported the Generated Aging of Receivables (PDF).',null,null,null),(206,2,null,'2021-12-21','11:16:54',60,'Exported the Generated Aging of Receivables (Excel).',null,null,null),(207,2,null,'2021-12-21','11:17:18',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(208,2,null,'2021-12-21','11:17:24',60,'Generates cheque summary report.',null,null,null),(209,2,null,'2021-12-21','11:18:04',60,'Bank Account Settings : Mallory Figueroa added a new bank account for Cash in Bank',null,null,null),(210,2,null,'2021-12-21','11:18:58',60,'Bank Account Settings : Mallory Figueroa added a new bank account for Cash in Bank',null,null,null),(211,null,null,'2021-12-21','11:23:45',60,'Accouting Defaults : Mallory Figueroa added new default journal entry for the purpose of For testing',null,null,null),(212,null,null,'2021-12-21','11:23:53',60,'Accounting Defaults: Mallory Figueroa printed a PDF report',null,null,null),(213,null,null,'2021-12-21','11:25:17',60,'Accouting Defaults : Mallory Figueroa modified default journal entry for the purpose of Sample',null,null,null),(214,null,null,'2021-12-21','11:25:31',60,'Accouting Defaults : Mallory Figueroa deleted default journal entry with purpose : For testing',null,null,null),(215,null,null,'2021-12-21','11:26:46',60,'Accouting Defaults : Mallory Figueroa deleted default journal entry with purpose : For testing',null,null,null),(216,null,null,'2021-12-21','11:27:19',60,'Accouting Defaults : Mallory Figueroa deleted default journal entry with purpose : For testing',null,null,null),(217,null,null,'2021-12-21','11:27:26',60,'Accouting Defaults : Mallory Figueroa deleted default journal entry with purpose : For testing',null,null,null),(218,null,null,'2021-12-21','11:32:16',60,'Accouting Defaults : Mallory Figueroa  modified default account setting for affiliate : Syntactics, Inc.',null,null,null),(219,null,null,'2021-12-21','11:32:21',60,'Accouting Defaults : Mallory Figueroa  modified default account setting for affiliate : Syntactics, Inc.',null,null,null),(220,null,null,'2021-12-21','11:33:05',60,'Accouting Defaults : Mallory Figueroa  modified default account setting for affiliate : Syntactics, Inc.',null,null,null),(221,null,null,'2021-12-21','11:34:17',60,'Accouting Defaults : Mallory Figueroa  modified default account setting for affiliate : Syntactics, Inc.',null,null,null),(222,null,null,'2021-12-21','11:37:13',60,'Mallory Figueroa added a beginning balance.',null,null,null),(223,null,null,'2021-12-21','11:37:46',60,'Chart of Accounts : Mallory Figueroa added new account code : 4103000.',null,null,null),(224,null,null,'2021-12-21','11:38:52',60,'Chart of Accounts : Mallory Figueroa added new account code : 4103001.',null,null,null),(225,2,null,'2021-12-21','11:55:00',60,'Mallory Figueroa exported the generated Journalized Transaction Summary Report (PDF).',null,null,null),(226,2,null,'2021-12-21','11:55:40',60,'Mallory Figueroa exported the generated Journalized Transaction Summary Report (Excel).',null,null,null),(227,2,null,'2021-12-21','11:58:35',60,'Mallory Figueroa Closed the month March year 2018.',1,1,35),(228,2,null,'2021-12-21','13:06:23',60,'Generates payable schedule report',null,null,null),(229,2,null,'2021-12-21','13:20:20',60,'Vouchers Receivable: Mallory Figueroa printed an PDF report.',null,null,null),(230,2,null,'2021-12-21','13:24:15',60,'Vouchers Payable : Mallory Figueroa added a new Vouchers Payable Transaction.',16,1003,57),(231,2,null,'2021-12-21','13:24:40',60,'Generates payable schedule report',null,null,null),(232,2,null,'2021-12-21','13:25:45',60,'Generates payable schedule report',null,null,null),(233,2,null,'2021-12-21','13:26:37',60,'superddt added a new disbursement transaction.',null,null,null),(234,2,null,'2021-12-21','13:26:44',60,'Generates payable schedule report',null,null,null),(235,2,null,'2021-12-21','13:26:58',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(236,2,null,'2021-12-21','13:27:02',60,'Generates cheque summary report.',null,null,null),(237,2,null,'2021-12-21','13:28:22',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(238,2,null,'2021-12-21','13:28:27',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(239,2,null,'2021-12-21','13:32:50',60,'Generates cheque summary report.',null,null,null),(240,2,null,'2021-12-21','13:34:41',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(241,2,null,'2021-12-21','13:36:09',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(242,2,null,'2021-12-21','13:39:28',60,'Generates cheque summary report.',null,null,null),(243,2,null,'2021-12-21','13:42:20',60,'Sales Order : Mallory Figueroa added a new Sales Order transaction',13,1,17),(244,2,null,'2021-12-21','13:43:00',60,'Sales : Mallory Figueroa added a new Sales Order transaction ',12,1,18),(245,2,null,'2021-12-21','13:43:51',60,'Vouchers Receivable: Mallory Figueroa added a new Vouchers Receivable Transaction.',17,103,58),(246,2,null,'2021-12-21','13:44:01',60,'Generates Receivable Transactions Report.',null,null,null),(247,2,null,'2021-12-21','14:04:41',60,'Generates Purchase Order Monitoring',null,null,30),(248,2,null,'2021-12-21','14:04:50',60,'Generates Purchase Order Monitoring',null,null,30),(249,2,null,'2021-12-21','14:05:00',60,'Generates Purchase Order Monitoring',null,null,30),(250,2,null,'2021-12-21','14:05:06',60,'Generates Purchase Order Monitoring',null,null,30),(251,2,null,'2021-12-21','14:05:16',60,'Generates Purchase Order Monitoring',null,null,30),(252,2,null,'2021-12-21','14:05:17',60,'Generates Purchase Order Monitoring',null,null,30),(253,2,null,'2021-12-21','14:05:27',60,'Generates Purchase Order Monitoring',null,null,30),(254,2,null,'2021-12-21','14:05:27',60,'Generates Purchase Order Monitoring',null,null,30),(255,2,null,'2021-12-21','14:05:27',60,'Generates Purchase Order Monitoring',null,null,30),(256,2,null,'2021-12-21','14:05:27',60,'Generates Purchase Order Monitoring',null,null,30),(257,2,null,'2021-12-21','14:05:27',60,'Generates Purchase Order Monitoring',null,null,30),(258,2,null,'2021-12-21','14:05:27',60,'Generates Purchase Order Monitoring',null,null,30),(259,2,null,'2021-12-21','14:05:44',60,'Exported the generated Purchase Order',null,null,30),(260,2,null,'2021-12-21','14:06:35',60,'Generates Purchase Order Monitoring',null,null,30),(261,2,null,'2021-12-21','14:06:38',60,'Exported the generated Purchase Order',null,null,30),(262,2,null,'2021-12-21','14:07:13',60,'Generates Purchase Order Monitoring',null,null,30),(263,2,null,'2021-12-21','14:10:16',60,'Mallory Figueroa added a new receiving transaction.',11,2,2),(264,2,null,'2021-12-21','14:18:32',60,'Mallory Figueroa added a new purchase return transaction.',10,1,29),(265,2,null,'2021-12-21','14:18:47',60,'Mallory Figueroa edited a transaction.',10,1,29),(266,2,null,'2021-12-21','14:26:47',60,'Mallory Figueroa added a new transaction.',9,12,2),(267,2,null,'2021-12-21','14:27:59',60,'Mallory Figueroa added a new receiving transaction.',11,3,2),(268,2,null,'2021-12-21','14:28:27',60,'Mallory Figueroa added a new purchase return transaction.',10,2,29),(269,2,null,'2021-12-21','14:29:39',60,'Mallory Figueroa edited a transaction.',10,2,29),(270,2,null,'2021-12-21','14:36:47',60,'Exported the generated Payable Transactions Report (PDF).',null,null,null),(271,2,null,'2021-12-21','14:36:57',60,'Generates Receiving Summary Report',null,0,34),(272,2,null,'2021-12-21','14:37:04',60,'Exported the generated Receiving summary Report',null,0,34),(273,2,null,'2021-12-21','14:37:13',60,'Generates Receiving Summary Report',null,9,34),(274,2,null,'2021-12-21','14:37:20',60,'Generates Receiving Summary Report',null,10,34),(275,2,null,'2021-12-21','14:37:24',60,'Generates Receiving Summary Report',null,11,34),(276,2,null,'2021-12-21','14:37:29',60,'Generates Receiving Summary Report',null,12,34),(277,2,null,'2021-12-21','14:37:32',60,'Generates Receiving Summary Report',null,13,34),(278,2,null,'2021-12-21','14:37:37',60,'Generates Receiving Summary Report',null,29,34),(279,2,null,'2021-12-21','14:37:40',60,'Generates Receiving Summary Report',null,21,34),(280,2,null,'2021-12-21','14:37:44',60,'Generates Receiving Summary Report',null,31,34),(281,2,null,'2021-12-21','14:37:50',60,'Generates Receiving Summary Report',null,0,34),(282,2,null,'2021-12-21','14:37:56',60,'Generates Receiving Summary Report',null,0,34),(283,2,null,'2021-12-21','14:38:00',60,'Generates Receiving Summary Report',null,0,34),(284,2,null,'2021-12-21','14:38:13',60,'Generates Receiving Summary Report',null,0,34),(285,2,null,'2021-12-21','14:38:20',60,'Generates Receiving Summary Report',null,0,34),(286,null,null,'2021-12-21','14:38:33',60,'Generates Purchase return summary report',null,0,39),(287,2,null,'2021-12-21','14:41:20',60,'Generates Purchase return summary report',null,0,39),(288,null,null,'2021-12-21','14:41:23',60,'Generates Purchase return summary report',null,0,39),(289,null,null,'2021-12-21','14:41:32',60,'Generates Purchase return summary report',null,0,39),(290,null,null,'2021-12-21','14:41:38',60,'Generates Purchase return summary report',null,0,39),(291,null,null,'2021-12-21','14:41:42',60,'Exported the generated Purchase Return Summary Report',null,0,39),(292,2,null,'2021-12-21','14:42:09',60,'Generates Expiry Monitoring.',null,null,null),(293,2,null,'2021-12-21','14:42:13',60,'Exported the generated Expiry Monitoring(PDF).',null,null,null),(294,2,null,'2021-12-21','14:43:30',60,'Sales Order : Mallory Figueroa added a new Sales Order transaction',13,2,17),(295,2,null,'2021-12-21','14:43:54',60,'Sales Order : Mallory Figueroa added a new Sales Order transaction',13,3,17),(296,2,null,'2021-12-21','14:44:18',60,'Sales Order : Mallory Figueroa edited a transaction',13,2,17),(297,2,null,'2021-12-21','14:44:27',60,'Sales Order : Mallory Figueroa deleted a transaction',13,3,17),(298,2,null,'2021-12-21','14:45:59',60,'Sales : Mallory Figueroa added a new Sales Order transaction ',12,2,18),(299,2,null,'2021-12-21','14:47:07',60,'Sales Return : Mallory Figueroa added a new Sales Return transaction',14,1,21),(300,null,null,'2021-12-21','14:47:58',60,'Generates Releasing Summary Report.',null,null,null),(301,null,null,'2021-12-21','14:48:15',60,'Generates Releasing Summary Report.',null,null,null),(302,null,null,'2021-12-21','14:48:28',60,'Generates Releasing Summary Report.',null,null,null),(303,null,null,'2021-12-21','14:48:35',60,'Generates Releasing Summary Report.',null,null,null),(304,null,null,'2021-12-21','14:48:40',60,'Generates Releasing Summary Report.',null,null,null),(305,null,null,'2021-12-21','14:48:45',60,'Generates Releasing Summary Report.',null,null,null),(306,null,null,'2021-12-21','14:48:53',60,'Generates Releasing Summary Report.',null,null,null),(307,null,null,'2021-12-21','14:48:57',60,'Generates Releasing Summary Report.',null,null,null),(308,null,null,'2021-12-21','14:51:12',60,'Generates Releasing Summary Report.',null,null,null),(309,null,null,'2021-12-21','14:51:20',60,'Generates Releasing Summary Report.',null,null,null),(310,null,null,'2021-12-21','14:51:25',60,'Generates Releasing Summary Report.',null,null,null),(311,null,null,'2021-12-21','14:51:32',60,'Generates Releasing Summary Report.',null,null,null),(312,null,null,'2021-12-21','14:52:11',60,'Generates Releasing Summary Report.',null,null,null),(313,null,null,'2021-12-21','14:52:14',60,'Generates Releasing Summary Report.',null,null,null),(314,null,null,'2021-12-21','14:52:20',60,'Generates Releasing Summary Report.',null,null,null),(315,null,null,'2021-12-21','14:52:25',60,'Generates Releasing Summary Report.',null,null,null),(316,null,null,'2021-12-21','14:52:32',60,'Generates Releasing Summary Report.',null,null,null),(317,null,null,'2021-12-21','14:52:33',60,'Exported the generated Releasing Summary Report (PDF).',null,null,null),(318,2,null,'2021-12-21','14:52:49',60,'Mallory Figueroa Generates Sales Summary Report',null,null,null),(319,2,null,'2021-12-21','14:52:53',60,'Mallory Figueroa Exported the generated Sales Summary Report (PDF)',null,null,null),(320,2,null,'2021-12-21','14:53:15',60,'Mallory Figueroa Generates Sales Summary Report',null,null,null),(321,2,null,'2021-12-21','14:53:24',60,'Mallory Figueroa Generates Sales Summary Report',null,null,null),(322,2,null,'2021-12-21','14:53:30',60,'Mallory Figueroa Generates Sales Summary Report',null,null,null),(323,2,null,'2021-12-21','14:53:34',60,'Mallory Figueroa Generates Sales Summary Report',null,null,null),(324,2,null,'2021-12-21','14:53:39',60,'Mallory Figueroa Generates Sales Summary Report',null,null,null),(325,2,null,'2021-12-21','14:53:48',60,'Mallory Figueroa Generates Sales Summary Report',null,null,null),(326,2,null,'2021-12-21','14:53:50',60,'Mallory Figueroa Generates Sales Summary Report',null,null,null),(327,2,null,'2021-12-21','14:53:55',60,'Mallory Figueroa Generates Sales Summary Report',null,null,null),(328,2,null,'2021-12-21','14:57:02',60,'Mallory Figueroa Generates Sales Summary Report',null,null,null),(329,2,null,'2021-12-21','14:57:36',60,'Mallory Figueroa Generates Sales Summary Report',null,null,null),(330,2,null,'2021-12-21','14:59:03',60,'superddtexport pdf',null,null,null),(331,2,null,'2021-12-21','14:59:33',60,'superddtexport pdf',null,null,null),(332,2,null,'2021-12-21','15:00:18',60,'superddtexport pdf',null,null,null),(333,2,null,'2021-12-21','15:00:32',60,'Generates Receivable Transactions Report.',null,null,null),(334,2,null,'2021-12-21','15:00:35',60,'Exported the generated Receivable Transactions Report (PDF).',null,null,null),(335,2,null,'2021-12-21','15:00:51',60,'Generates Receivable Transactions Report.',null,null,null),(336,2,null,'2021-12-21','15:00:57',60,'Generates Receivable Transactions Report.',null,null,null),(337,2,null,'2021-12-21','15:01:05',60,'Generates Receivable Balance.',null,null,null),(338,2,null,'2021-12-21','15:01:11',60,'Exported the generated Receivable Balance(PDF).',null,null,null),(339,2,null,'2021-12-21','15:01:24',60,'Generates Receivable Ledger.',null,null,null),(340,2,null,'2021-12-21','15:03:41',60,'Exported the generated Receivable SOA(PDF) and sent as email.',null,null,null),(341,2,null,'2021-12-21','15:03:45',60,'Exported the generated Receivable SOA(PDF).',null,null,null),(342,2,null,'2021-12-21','15:03:54',60,'Exported the generated Receivable SOA(Excel).',null,null,null),(343,2,null,'2021-12-21','15:04:21',60,'Exported the generated Receivable SOA(PDF) and sent as email.',null,null,null),(344,2,null,'2021-12-21','15:05:12',60,'Exported the generated Receivable SOA(PDF) and sent as email.',null,null,null),(345,2,null,'2021-12-21','15:05:17',60,'Exported the generated Receivable SOA(PDF) and sent as email.',null,null,null),(346,2,null,'2021-12-21','15:07:01',60,'Generates Receivable Ledger.',null,null,null),(347,2,null,'2021-12-21','15:07:09',60,'Generates Receivable Ledger.',null,null,null),(348,2,null,'2021-12-21','15:07:21',60,'Generates Receivable Ledger.',null,null,null),(349,2,null,'2021-12-21','15:07:24',60,'Exported the generated Receivable Ledger(PDF).',null,null,null),(350,2,null,'2021-12-21','15:08:25',60,'Exported the generated Receivable SOA(PDF) and sent as email.',null,null,null),(351,2,null,'2021-12-21','15:10:43',60,'Generates Itemized Profit and Loss Report.',null,null,null),(352,2,null,'2021-12-21','15:10:53',60,'Generates Itemized Profit and Loss Report.',null,null,null),(353,2,null,'2021-12-21','15:14:23',60,'Generates Itemized Profit and Loss Report.',null,null,null),(354,2,null,'2021-12-21','15:14:27',60,'Generates Itemized Profit and Loss Report.',null,null,null),(355,2,null,'2021-12-21','15:14:30',60,'Generates Itemized Profit and Loss Report.',null,null,null),(356,2,null,'2021-12-21','15:14:34',60,'Generates Itemized Profit and Loss Report.',null,null,null),(357,2,null,'2021-12-21','15:14:36',60,'Exported the generated Itemized Profit and Loss Report(PDF)',null,null,null),(358,2,null,'2021-12-21','15:15:23',60,'Generates Itemized Profit and Loss Report.',null,null,null),(359,2,null,'2021-12-21','15:15:28',60,'Cancelled Transactions : Mallory Figueroa generates Cancelled Transactions Report',null,null,null),(360,2,null,'2021-12-21','15:15:52',60,'Generates Inventory Balances.',null,null,null),(361,2,null,'2021-12-21','15:15:56',60,'Generates Adjustment Summary Report.',null,null,null),(362,2,null,'2021-12-21','15:15:59',60,'Generates Adjustment Summary Report.',null,null,null),(363,2,null,'2021-12-22','08:24:32',60,'Generates Inventory Balances.',null,null,null),(364,2,null,'2021-12-22','08:41:39',60,'Modified the employee, superddt, for Mallory Figueroa with usertype Administrator',null,null,null),(365,2,null,'2021-12-22','08:52:56',60,'Generates Inventory Balances.',null,null,null),(366,2,null,'2021-12-22','09:02:34',60,'Generates Inventory Balances.',null,null,null),(367,2,null,'2021-12-22','09:06:32',60,'Generates Inventory Ledger Report.',null,null,null),(368,2,null,'2021-12-22','09:06:38',60,'Generates Inventory Ledger Report.',null,null,null),(369,2,null,'2021-12-22','09:06:49',60,'Generates Inventory Ledger Report.',null,null,null),(370,null,null,'2021-12-22','09:06:57',60,'Inventory Conversion: Mallory Figueroa printed a PDF report',null,null,null),(371,null,null,'2021-12-22','09:09:53',60,'Inventory Conversion: superddt printed a Excel report',null,null,null),(372,null,null,'2021-12-22','09:10:11',60,'Inventory Conversion: superddt printed a Excel report',null,null,null),(373,null,null,'2021-12-22','09:14:03',60,'superddt transferred stocks to H Company',null,null,null),(374,null,null,'2021-12-22','09:14:08',60,'Stock Transfer: Mallory Figueroa printed a PDF report',null,null,null),(375,null,null,'2021-12-22','09:14:55',60,'Stock Transfer: superddt printed a Excel report',null,null,null),(376,null,null,'2021-12-22','10:36:18',60,'Mallory Figueroa removed the transaction.',null,null,70),(377,null,null,'2021-12-22','10:36:20',60,'Mallory Figueroa removed the transaction.',null,null,70),(378,null,null,'2021-12-22','10:36:23',60,'Mallory Figueroa removed the transaction.',null,null,70),(379,null,null,'2021-12-22','10:36:26',60,'Mallory Figueroa removed the transaction.',null,null,70),(380,null,null,'2021-12-22','10:43:58',60,'Mallory Figueroa removed the transaction.',null,null,70),(381,2,null,'2021-12-22','10:47:31',60,'Exported the generated delivery ticket monitoring (PDF)',null,null,null),(382,2,null,'2021-12-22','10:59:35',60,'Exported the generated delivery ticket monitoring (PDF)',null,null,null),(383,2,null,'2021-12-23','09:39:20',60,'Generates Inventory Balances.',null,null,null),(384,2,null,'2021-12-23','09:39:26',60,'Exported the generated Inventory Balances Report (EXCEL).',null,null,null),(385,2,null,'2021-12-23','09:47:37',60,'superddtexport excel',null,null,null),(386,2,null,'2021-12-23','09:48:16',60,'superddtexport pdf',null,null,null),(387,2,null,'2021-12-23','09:48:27',60,'superddtexport excel',null,null,null),(388,null,null,'2021-12-23','09:49:41',60,'Inventory Conversion: superddt printed a Excel report',null,null,null),(389,2,null,'2021-12-23','09:52:54',60,'Mallory Figueroa added a new adjustment Transaction.',2,1,48),(390,null,null,'2021-12-23','09:53:03',60,'Accounting Adjustment: Mallory Figueroa printed a PDF report',null,null,null),(391,2,null,'2021-12-23','09:55:24',60,'Mallory Figueroa edited a transaction.',2,1,48),(392,null,null,'2021-12-23','09:55:32',60,'Accounting Adjustment: Mallory Figueroa printed a PDF report',null,null,null),(393,null,null,'2021-12-23','10:17:56',60,'superddt has logged out of the system.',null,null,null),(394,null,null,'2021-12-28','13:21:03',60,'Mallory Figueroa removed the transaction.',null,null,70),(395,null,null,'2022-01-03','10:54:55',60,'superddt added a new initial reference TM',null,null,null),(396,null,null,'2022-01-03','11:04:14',60,'superddt added a new series reference TM',null,null,null),(397,2,null,'2022-01-03','11:05:07',60,'superddt added a new truck maintenance.',null,null,78),(398,2,null,'2022-01-04','10:14:21',60,'superddt added a new truck maintenance.',null,null,78),(399,2,null,'2022-01-04','10:29:03',60,'superddt edited the truck maintenance details.',null,null,78),(400,2,null,'2022-01-04','10:29:28',60,'superddt edited the truck maintenance details.',null,null,78),(401,2,null,'2022-01-04','10:32:04',60,'superddt added a new truck maintenance.',null,null,78),(402,2,null,'2022-01-04','10:37:10',60,'superddt edited the truck maintenance details.',null,null,78),(403,2,null,'2022-01-04','10:43:44',60,'Exported the generated Truck Maintenance Report (PDF).',null,null,78),(404,2,null,'2022-01-04','10:54:48',60,'Exported the generated Truck Maintenance Report (Excel).',null,null,78),(405,2,null,'2022-01-04','11:17:03',60,'Exported the generated Logsheet Monitoring Report (PDF)',null,null,79),(406,2,null,'2022-01-04','11:17:10',60,'Exported the generated Logsheet Monitoring Report (Excel)',null,null,79),(407,2,null,'2022-01-04','11:31:54',60,'Exported the generated delivery ticket monitoring (PDF)',null,null,null),(408,2,null,'2022-01-04','11:32:02',60,'Exported the generated delivery ticket monitoring (Excel)',null,null,null),(409,12,null,'2022-01-04','13:38:18',60,'Exported the generated Truck Monitoring Report (PDF)',null,null,80),(410,2,null,'2022-01-04','13:38:23',60,'Exported the generated Truck Monitoring Report (Excel).',null,null,80),(411,null,null,'2022-01-06','10:11:18',60,'superddt has logged out of the system.',null,null,null),(412,null,null,'2022-01-06','10:43:47',60,'superddt has logged out of the system.',null,null,null),(413,null,null,'2022-01-06','17:24:59',60,'superddt has logged out of the system.',null,null,null),(414,2,null,'2022-01-11','09:18:30',60,'superddt edited the truck maintenance details.',null,null,78),(415,2,null,'2022-01-11','09:19:03',60,'Exported the generated Truck Maintenance Report (PDF).',null,null,78),(416,2,null,'2022-01-13','10:57:39',60,'superddt deleted a truck maintenance.',null,null,78),(417,2,null,'2022-01-13','10:57:44',60,'superddt deleted a truck maintenance.',null,null,78),(418,2,null,'2022-01-13','10:58:10',60,'superddt added a new truck maintenance.',null,null,78),(419,2,null,'2022-01-13','10:58:27',60,'superddt added a new truck maintenance.',null,null,78),(420,2,null,'2022-01-13','10:58:57',60,'superddt deleted a truck maintenance.',null,null,78),(421,2,null,'2022-01-13','10:59:01',60,'superddt deleted a truck maintenance.',null,null,78),(422,2,null,'2022-01-13','11:00:32',60,'superddt added a new truck maintenance.',null,null,78),(423,2,null,'2022-01-13','11:07:26',60,'superddt deleted a truck maintenance.',null,null,78),(424,2,null,'2022-01-13','11:07:30',60,'superddt deleted a truck maintenance.',null,null,78),(425,2,null,'2022-01-13','11:07:53',60,'superddt added a new truck maintenance.',null,null,78),(426,2,null,'2022-01-13','11:09:56',60,'superddt deleted a truck maintenance.',null,null,78),(427,2,null,'2022-01-13','11:16:25',60,'superddt added a new truck maintenance.',null,null,78),(428,2,null,'2022-01-13','11:36:23',60,'superddt added a new truck maintenance.',null,null,78),(429,2,null,'2022-01-13','11:38:41',60,'superddt added a new truck maintenance.',null,null,78),(430,2,null,'2022-01-13','11:40:00',60,'superddt edited the truck maintenance details.',null,null,78),(431,2,null,'2022-01-13','11:44:06',60,'superddt edited the truck maintenance details.',null,null,78),(432,2,null,'2022-01-13','11:45:09',60,'superddt deleted a truck maintenance.',null,null,78),(433,2,null,'2022-01-13','11:45:42',60,'Exported the generated Truck Maintenance Report (PDF).',null,null,78),(434,2,null,'2022-01-13','11:47:27',60,'Exported the generated Truck Maintenance Report (Excel).',null,null,78),(435,2,null,'2022-01-13','11:58:07',60,'superddt deleted a truck maintenance.',null,null,78),(436,2,null,'2022-01-13','11:58:52',60,'superddt added a new truck maintenance.',null,null,78),(437,2,null,'2022-01-13','11:59:53',60,'Exported the generated Truck Maintenance Report (PDF).',null,null,78),(438,2,null,'2022-01-14','14:18:48',60,'superddt deleted a truck maintenance.',null,null,78),(439,2,null,'2022-01-14','14:19:27',60,'superddt added a new truck maintenance.',null,null,78),(440,2,null,'2022-01-17','08:43:34',60,'superddt added a new truck maintenance.',null,null,78),(441,2,null,'2022-01-17','08:43:51',60,'superddt deleted a truck maintenance.',null,null,78),(442,2,null,'2022-01-17','08:44:07',60,'superddt added a new truck maintenance.',null,null,78),(443,2,null,'2022-01-17','08:44:26',60,'superddt added a new truck maintenance.',null,null,78),(444,2,null,'2022-01-17','09:44:52',60,'superddt edited the truck maintenance details.',null,null,78),(445,2,null,'2022-01-17','09:49:27',60,'superddt added a new truck maintenance.',null,null,78),(446,2,null,'2022-01-17','09:53:04',60,'superddt deleted a truck maintenance.',null,null,78),(447,2,null,'2022-01-17','09:53:09',60,'superddt deleted a truck maintenance.',null,null,78),(448,2,null,'2022-01-17','09:54:17',60,'superddt added a new truck maintenance.',null,null,78),(449,2,null,'2022-01-17','09:54:42',60,'superddt edited the truck maintenance details.',null,null,78),(450,2,null,'2022-01-17','10:04:22',60,'superddt added a new truck maintenance.',null,null,78),(451,2,null,'2022-01-17','11:44:38',60,'Exported the generated Truck Maintenance Report (PDF).',null,null,78),(452,2,null,'2022-01-24','09:26:29',60,'superddt edited the truck maintenance details.',null,null,78),(453,2,null,'2022-01-24','09:42:13',60,'superddt added a new truck maintenance.',null,null,78),(454,2,null,'2022-01-24','20:42:16',60,'superddt added a new truck maintenance.',null,null,78),(455,2,null,'2022-01-24','20:42:41',60,'superddt added a new truck maintenance.',null,null,78),(456,2,null,'2022-01-24','20:42:54',60,'superddt deleted a truck maintenance.',null,null,78),(457,2,null,'2022-01-24','20:45:28',60,'superddt added a new truck maintenance.',null,null,78),(458,2,null,'2022-01-25','13:12:14',60,'Exported the generated Driver Performance Summary Report (PDF)',null,null,82),(459,2,null,'2022-01-26','13:31:32',60,'superddt added a new truck maintenance.',null,null,78),(460,2,null,'2022-01-27','08:47:58',60,'superddt added a new truck maintenance.',null,null,78),(461,2,null,'2022-01-27','10:52:12',60,'superddt added a new truck maintenance.',null,null,78),(462,2,null,'2022-01-31','08:56:05',60,'Generates Receivable Schedule Report',null,null,null),(463,null,null,'2022-02-02','09:57:18',60,'superddt has logged out of the system.',null,null,null),(464,null,null,'2022-02-10','17:01:00',60,'superddt has logged out of the system.',null,null,null),(465,2,null,'2022-02-14','14:15:27',60,'Generates Inventory Balances.',null,null,null),(466,2,null,'2022-02-14','14:16:11',60,'Generates Inventory Ledger Report.',null,null,null),(467,2,null,'2022-02-14','14:16:18',60,'Generates Inventory Ledger Report.',null,null,null),(468,2,null,'2022-02-14','14:16:24',60,'Generates Inventory Ledger Report.',null,null,null),(469,2,null,'2022-02-14','14:16:26',60,'Generates Inventory Ledger Report.',null,null,null),(470,2,null,'2022-02-15','14:28:55',60,'Vouchers Payable : Mallory Figueroa edited a transaction.',16,1000,57),(471,2,null,'2022-02-15','15:43:17',60,'Vouchers Payable : Mallory Figueroa edited a transaction.',16,1000,57),(472,2,null,'2022-02-15','15:49:43',60,'Vouchers Payable : Mallory Figueroa edited a transaction.',16,1002,57),(473,2,null,'2022-02-15','15:50:11',60,'Vouchers Payable : Mallory Figueroa edited a transaction.',16,1003,57),(474,2,null,'2022-02-16','08:21:59',60,'Vouchers Payable : Mallory Figueroa edited a transaction.',16,1003,57),(475,2,null,'2022-02-16','08:22:17',60,'Vouchers Payable : Mallory Figueroa edited a transaction.',16,1001,57),(476,null,null,'2022-02-18','10:00:53',60,'Mallory Figueroa removed the truck profile.',null,null,74),(477,null,null,'2022-02-21','08:17:01',60,'Mallory Figueroa added a new truck profile.',null,null,74),(478,null,null,'2022-02-21','08:17:39',60,'Mallory Figueroa removed the truck profile.',null,null,74),(479,null,null,'2022-02-21','08:43:18',60,'Mallory Figueroa removed the transaction.',null,null,70),(480,null,null,'2022-02-21','08:43:19',60,'Mallory Figueroa removed the transaction.',null,null,70),(481,null,null,'2022-02-21','08:43:20',60,'Mallory Figueroa removed the transaction.',null,null,70),(482,null,null,'2022-02-21','11:55:50',60,'superddt has logged out of the system.',null,null,null),(483,2,null,'2022-02-21','11:57:39',60,'Exported the generated Driver Performance Summary Report (PDF)',null,null,82),(484,null,null,'2022-02-21','11:58:00',60,'superddt has logged out of the system.',null,null,null),(485,2,null,'2022-02-23','09:08:02',60,'Exported the generated delivery ticket monitoring (PDF)',null,null,null),(486,2,null,'2022-02-23','09:26:17',60,'Exported the generated Fuel Monitoring Report (PDF)',null,null,81),(487,2,null,'2022-02-23','09:26:25',60,'Exported the generated Fuel Monitoring Report (Excel).',null,null,81),(488,2,null,'2022-02-23','09:27:33',60,'Exported the generated Fuel Monitoring Report (PDF)',null,null,81),(489,null,null,'2022-02-23','09:30:08',60,'superddt has logged out of the system.',null,null,null),(490,null,null,'2022-02-24','08:53:24',60,'Generates Releasing Summary Report.',null,null,null),(491,null,null,'2022-02-24','08:53:25',60,'Generates Releasing Summary Report.',null,null,null),(492,null,null,'2022-02-24','08:53:26',60,'Generates Releasing Summary Report.',null,null,null),(493,null,null,'2022-02-24','08:53:26',60,'Generates Releasing Summary Report.',null,null,null),(494,null,null,'2022-02-24','08:53:26',60,'Generates Releasing Summary Report.',null,null,null),(495,null,null,'2022-02-24','08:53:26',60,'Generates Releasing Summary Report.',null,null,null),(496,2,null,'2022-02-24','09:15:55',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(497,null,null,'2022-02-24','09:55:45',60,'superddt has logged out of the system.',null,null,null),(498,null,null,'2022-02-24','11:05:52',60,'superddt added a new initial reference RR2',null,null,null),(499,null,null,'2022-02-24','11:06:09',60,'superddt added a new series reference RR2',null,null,null),(500,2,null,'2022-02-24','11:06:41',60,'Mallory Figueroa added a new receiving transaction.',34,1,2),(501,2,null,'2022-02-24','11:06:51',60,'Mallory Figueroa edited a transaction.',34,1,2),(502,2,null,'2022-02-24','11:07:05',60,'Mallory Figueroa edited a transaction.',34,1,2),(503,2,null,'2022-02-24','11:07:28',60,'Mallory Figueroa edited a transaction.',34,1,2),(504,null,null,'2022-02-24','11:10:29',60,'Generates Releasing Summary Report.',null,null,null),(505,null,null,'2022-02-24','11:10:32',60,'Generates Releasing Summary Report.',null,null,null),(506,null,null,'2022-02-24','11:10:35',60,'Generates Releasing Summary Report.',null,null,null),(507,null,null,'2022-02-24','11:10:39',60,'Generates Releasing Summary Report.',null,null,null),(508,null,null,'2022-02-24','11:10:42',60,'Generates Releasing Summary Report.',null,null,null),(509,null,null,'2022-02-24','11:10:46',60,'Generates Releasing Summary Report.',null,null,null),(510,null,null,'2022-02-24','11:11:01',60,'Generates Releasing Summary Report.',null,null,null),(511,null,null,'2022-02-28','09:38:15',60,'Generates Releasing Summary Report.',null,null,null),(512,null,null,'2022-02-28','09:38:20',60,'Generates Releasing Summary Report.',null,null,null),(513,null,null,'2022-02-28','09:38:24',60,'Generates Releasing Summary Report.',null,null,null),(514,null,null,'2022-02-28','10:05:57',60,'Generates Releasing Summary Report.',null,null,null),(515,2,null,'2022-02-28','10:06:25',60,'Sales Order : Mallory Figueroa edited a transaction',13,1,17),(516,null,null,'2022-02-28','10:54:11',60,'superddt edited a transaction',null,null,null),(517,2,null,'2022-02-28','11:17:05',60,'Generates Adjustment Summary Report.',null,null,null),(518,2,null,'2022-02-28','11:17:49',60,'Generates Adjustment Summary Report.',null,null,null),(519,2,null,'2022-02-28','11:17:49',60,'Generates Adjustment Summary Report.',null,null,null),(520,2,null,'2022-02-28','11:17:50',60,'Generates Adjustment Summary Report.',null,null,null),(521,2,null,'2022-02-28','11:17:50',60,'Generates Adjustment Summary Report.',null,null,null),(522,2,null,'2022-02-28','11:17:50',60,'Generates Adjustment Summary Report.',null,null,null),(523,2,null,'2022-02-28','11:17:57',60,'Generates Adjustment Summary Report.',null,null,null),(524,2,null,'2022-02-28','11:20:28',60,'Generates Adjustment Summary Report.',null,null,null),(525,2,null,'2022-02-28','11:20:30',60,'Generates Adjustment Summary Report.',null,null,null),(526,2,null,'2022-02-28','12:31:59',60,'Generates Adjustment Summary Report.',null,null,null),(527,2,null,'2022-02-28','12:31:59',60,'Generates Adjustment Summary Report.',null,null,null),(528,2,null,'2022-02-28','12:31:59',60,'Generates Adjustment Summary Report.',null,null,null),(529,2,null,'2022-02-28','12:32:00',60,'Generates Adjustment Summary Report.',null,null,null),(530,2,null,'2022-02-28','12:32:08',60,'Generates Adjustment Summary Report.',null,null,null),(531,2,null,'2022-02-28','12:32:14',60,'Generates Adjustment Summary Report.',null,null,null),(532,2,null,'2022-02-28','12:32:17',60,'Generates Adjustment Summary Report.',null,null,null),(533,2,null,'2022-02-28','12:32:20',60,'Generates Adjustment Summary Report.',null,null,null),(534,2,null,'2022-02-28','12:32:41',60,'Generates Adjustment Summary Report.',null,null,null),(535,2,null,'2022-02-28','12:32:49',60,'Generates Adjustment Summary Report.',null,null,null),(536,2,null,'2022-02-28','12:32:50',60,'Generates Adjustment Summary Report.',null,null,null),(537,2,null,'2022-02-28','12:32:50',60,'Generates Adjustment Summary Report.',null,null,null),(538,2,null,'2022-02-28','12:32:50',60,'Generates Adjustment Summary Report.',null,null,null),(539,2,null,'2022-02-28','12:32:51',60,'Generates Adjustment Summary Report.',null,null,null),(540,2,null,'2022-02-28','12:32:51',60,'Generates Adjustment Summary Report.',null,null,null),(541,2,null,'2022-02-28','12:33:12',60,'Generates Adjustment Summary Report.',null,null,null),(542,2,null,'2022-02-28','13:51:58',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(543,2,null,'2022-02-28','13:51:58',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(544,2,null,'2022-02-28','13:51:59',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(545,2,null,'2022-02-28','13:51:59',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(546,2,null,'2022-02-28','13:52:01',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(547,2,null,'2022-02-28','13:52:01',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(548,2,null,'2022-02-28','13:52:01',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(549,2,null,'2022-02-28','13:52:02',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(550,2,null,'2022-02-28','13:52:02',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(551,2,null,'2022-02-28','13:52:02',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(552,2,null,'2022-02-28','13:52:02',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(553,2,null,'2022-02-28','13:52:02',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(554,2,null,'2022-02-28','13:52:03',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(555,null,null,'2022-03-01','13:07:13',60,'superddt has logged out of the system.',null,null,null),(556,null,null,'2022-03-03','09:42:14',60,'Generates Releasing Summary Report.',null,null,null),(557,null,null,'2022-03-03','09:42:19',60,'Generates Releasing Summary Report.',null,null,null),(558,null,null,'2022-03-03','09:42:26',60,'Generates Releasing Summary Report.',null,null,null),(559,null,null,'2022-03-03','09:42:30',60,'Generates Releasing Summary Report.',null,null,null),(560,null,null,'2022-03-03','09:54:27',60,'superddt added a new initial reference ST1',null,null,null),(561,null,null,'2022-03-03','10:01:54',60,'superddt added a new series reference ST1',null,null,null),(562,null,null,'2022-03-03','10:02:29',60,'superddt transferred stocks to H Company',null,null,null),(563,null,null,'2022-03-03','10:07:22',60,'superddt transferred stocks to H Company',null,null,null),(564,null,null,'2022-03-03','10:10:48',60,'superddt transferred stocks to H Company',null,null,null),(565,null,null,'2022-03-03','10:11:31',60,'superddt transferred stocks to H Company',null,null,null),(566,null,null,'2022-03-03','10:13:49',60,'superddt transferred stocks to H Company',null,null,null),(567,null,null,'2022-03-03','10:16:05',60,'superddt deleted a transaction',null,null,null),(568,null,null,'2022-03-03','10:16:06',60,'superddt deleted a transaction',null,null,null),(569,null,null,'2022-03-03','10:16:07',60,'superddt deleted a transaction',null,null,null),(570,null,null,'2022-03-03','10:16:08',60,'superddt deleted a transaction',null,null,null),(571,null,null,'2022-03-03','10:16:09',60,'superddt deleted a transaction',null,null,null),(572,null,null,'2022-03-03','10:16:25',60,'superddt transferred stocks to H Company',null,null,null),(573,null,null,'2022-03-03','10:19:17',60,'superddt edited a transaction',null,null,null),(574,null,null,'2022-03-03','10:19:28',60,'superddt edited a transaction',null,null,null),(575,null,null,'2022-03-03','10:21:34',60,'Deleted a file: 20200421201342_dbbackup_34.sql',null,null,7),(576,2,null,'2022-03-03','10:24:33',60,'superddt added a new cash Receipt transaction',null,null,null),(577,2,null,'2022-03-03','10:27:09',60,'Mallory Figueroa added a new adjustment transaction.',7,1,23),(578,2,null,'2022-03-03','10:28:59',60,'Mallory Figueroa added a new adjustment transaction.',7,2,23),(579,2,null,'2022-03-03','10:30:08',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(580,2,null,'2022-03-03','10:30:18',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(581,2,null,'2022-03-03','10:32:47',60,'Added a new supplier, QA Supplier',null,null,null),(582,2,null,'2022-03-03','10:36:48',60,'Generates Receivable Ledger.',null,null,null),(583,2,null,'2022-03-03','10:36:53',60,'Generates Receivable Balance.',null,null,null),(584,2,null,'2022-03-03','10:36:57',60,'Generates Receivable Ledger.',null,null,null),(585,2,null,'2022-03-03','10:37:03',60,'Generates Receivable Ledger.',null,null,null),(586,2,null,'2022-03-03','10:40:24',60,'Generates Adjustment Summary Report.',null,null,null),(587,2,null,'2022-03-03','10:40:29',60,'Generates Adjustment Summary Report.',null,null,null),(588,2,null,'2022-03-03','10:40:40',60,'Generates Adjustment Summary Report.',null,null,null),(589,2,null,'2022-03-03','10:40:43',60,'Generates Adjustment Summary Report.',null,null,null),(590,2,null,'2022-03-03','10:40:52',60,'Generates Adjustment Summary Report.',null,null,null),(591,null,null,'2022-03-03','10:49:14',60,'Generates Releasing Summary Report.',null,null,null),(592,null,null,'2022-03-03','10:49:26',60,'Generates Releasing Summary Report.',null,null,null),(593,null,null,'2022-03-03','10:49:29',60,'Generates Releasing Summary Report.',null,null,null),(594,null,null,'2022-03-03','10:49:31',60,'Generates Releasing Summary Report.',null,null,null),(595,null,null,'2022-03-03','10:49:35',60,'Generates Releasing Summary Report.',null,null,null),(596,null,null,'2022-03-03','10:49:40',60,'Generates Releasing Summary Report.',null,null,null),(597,2,null,'2022-03-03','10:52:06',60,'Mallory Figueroa added a new receiving transaction.',11,4,2),(598,2,null,'2022-03-03','10:52:20',60,'Mallory Figueroa edited a transaction.',11,4,2),(599,null,null,'2022-03-03','10:53:16',60,'superddt deleted a transaction',null,null,null),(600,null,null,'2022-03-03','10:53:20',60,'Generates Releasing Summary Report.',null,null,null),(601,null,null,'2022-03-03','10:53:34',60,'superddt transferred stocks to H Company',null,null,null),(602,null,null,'2022-03-03','10:53:36',60,'Generates Releasing Summary Report.',null,null,null),(603,null,null,'2022-03-03','10:53:40',60,'Generates Releasing Summary Report.',null,null,null),(604,null,null,'2022-03-03','10:53:48',60,'superddt edited a transaction',null,null,null),(605,2,null,'2022-03-03','10:54:45',60,'Sales Order : Mallory Figueroa added a new Sales Order transaction',13,4,17),(606,2,null,'2022-03-03','10:55:39',60,'Sales : Mallory Figueroa added a new Sales Order transaction ',12,3,18),(607,null,null,'2022-03-03','10:55:43',60,'Generates Releasing Summary Report.',null,null,null),(608,null,null,'2022-03-03','10:55:44',60,'Generates Releasing Summary Report.',null,null,null),(609,null,null,'2022-03-03','10:55:53',60,'Generates Releasing Summary Report.',null,null,null),(610,null,null,'2022-03-03','10:57:51',60,'Generates Releasing Summary Report.',null,null,null),(611,null,null,'2022-03-03','10:58:02',60,'Generates Releasing Summary Report.',null,null,null),(612,null,null,'2022-03-03','10:58:05',60,'Generates Releasing Summary Report.',null,null,null),(613,null,null,'2022-03-03','10:58:08',60,'Generates Releasing Summary Report.',null,null,null),(614,null,null,'2022-03-03','10:58:11',60,'Generates Releasing Summary Report.',null,null,null),(615,null,null,'2022-03-03','10:58:14',60,'Generates Releasing Summary Report.',null,null,null),(616,null,null,'2022-03-03','10:59:21',60,'superddt edited a transaction',null,null,null),(617,2,null,'2022-03-03','11:01:55',60,'added a new Item, Duplicated item code',null,null,null),(618,null,null,'2022-03-03','11:02:17',60,'superddt has logged out of the system.',null,null,null),(619,2,null,'2022-03-03','13:10:37',60,'Mallory Figueroa added a new adjustment transaction.',7,3,23),(620,2,null,'2022-03-04','08:28:41',60,'Mallory Figueroa added a new adjustment Transaction.',2,1001,48),(621,12,null,'2022-03-04','08:29:19',60,'superddt has changed affiliate.',null,null,null),(622,2,null,'2022-03-04','08:29:24',60,'superddt has changed affiliate.',null,null,null),(623,2,null,'2022-03-04','08:30:04',60,'Modified the affiliate, Syntactics, Inc.',null,null,null),(624,null,null,'2022-03-04','10:31:55',60,'Mallory Figueroa confirmed a Vouchers Payable Transaction.',null,null,null),(625,2,null,'2022-03-04','10:32:01',60,'Mallory Figueroa confirmed an adjustment transaction.',null,null,null),(626,2,null,'2022-03-04','10:32:15',60,'Mallory Figueroa confirmed an adjustment transaction.',null,null,null),(627,null,null,'2022-03-04','10:32:19',60,'Mallory Figueroa confirmed a Vouchers Payable Transaction.',null,null,null),(628,2,null,'2022-03-10','08:21:47',60,'added a new Item, ITEMUSED',null,null,null),(629,2,null,'2022-03-10','08:22:26',60,'Sales Order : Mallory Figueroa added a new Sales Order transaction',13,1000,17),(630,2,null,'2022-03-10','08:22:41',60,'deleted an item ITEMUSED',null,null,null),(631,2,null,'2022-03-10','09:15:45',60,'deleted an item false',null,null,null),(632,2,null,'2022-03-10','09:16:08',60,'added a new Item, Test Delete',null,null,null),(633,2,null,'2022-03-10','09:17:04',60,'Sales Order : Mallory Figueroa added a new Sales Order transaction',13,1001,17),(634,2,null,'2022-03-10','09:17:12',60,'deleted an item Test Delete',null,null,null),(635,2,null,'2022-03-10','09:19:05',60,'added a new Item, Test SEll',null,null,null),(636,null,null,'2022-03-10','09:57:06',60,'Restored a file: 20220310095639_dbbackup_60.sql',null,null,7),(637,2,null,'2022-03-10','14:11:45',60,'Mallory Figueroa added a new adjustment transaction.',7,4,23),(638,2,null,'2022-03-10','14:12:03',60,'Mallory Figueroa cancelled a transaction.',null,null,null),(639,null,null,'2022-03-14','14:32:13',60,'Restored a file: 20220314143110_dbbackup_60.sql',null,null,7),(640,2,null,'2022-03-14','14:33:21',60,'Generates Inventory Balances.',null,null,null),(641,2,null,'2022-03-14','14:34:44',60,'Generates Inventory Balances.',null,null,null),(642,2,null,'2022-03-14','14:35:25',60,'Generates Inventory Balances.',null,null,null),(643,2,null,'2022-03-14','14:36:45',60,'Mallory Figueroa added a new adjustment transaction.',7,5,23),(644,2,null,'2022-03-14','14:36:49',60,'Generates Inventory Balances.',null,null,null),(645,2,null,'2022-03-14','14:36:49',60,'Generates Inventory Balances.',null,null,null),(646,2,null,'2022-03-14','14:36:51',60,'Generates Inventory Balances.',null,null,null),(647,2,null,'2022-03-14','14:37:27',60,'Generates Inventory Balances.',null,null,null),(648,2,null,'2022-03-14','14:37:57',60,'Generates Inventory Balances.',null,null,null),(649,null,null,'2022-03-14','14:39:00',60,'superddt transferred stocks to H Company',null,null,null),(650,2,null,'2022-03-14','14:39:07',60,'Generates Inventory Balances.',null,null,null),(651,2,null,'2022-03-14','14:39:08',60,'Generates Inventory Balances.',null,null,null),(652,2,null,'2022-03-14','14:39:09',60,'Generates Inventory Balances.',null,null,null),(653,2,null,'2022-03-14','14:43:17',60,'deleted an item Nescafe Coffee',null,null,null),(654,2,null,'2022-03-14','14:43:22',60,'deleted an item Del Monte Pineapple Juice',null,null,null),(655,2,null,'2022-03-14','14:43:50',60,'added a new Item, To be deleted',null,null,null),(656,2,null,'2022-03-14','14:44:09',60,'deleted an item To be deleted',null,null,null),(657,2,null,'2022-03-14','14:44:23',60,'deleted an item To be deleted',null,null,null),(658,2,null,'2022-03-14','14:45:20',60,'deleted an item To be deleted',null,null,null),(659,2,null,'2022-03-14','14:49:54',60,'Mallory Figueroa added a new receiving transaction.',11,5,2),(660,2,null,'2022-03-14','14:57:57',60,'Added new employee, Joe Alywn.',null,null,null),(661,null,null,'2022-03-14','15:05:23',60,'superddt has logged out of the system.',null,null,null),(662,2,null,'2022-03-14','15:27:32',60,'deleted an item To be deleted',null,null,null),(663,2,null,'2022-03-14','15:37:04',60,'added a new Item, delete ni sya',null,null,null),(664,2,null,'2022-03-14','15:37:09',60,'deleted an item delete ni sya',null,null,null),(665,2,null,'2022-03-15','14:05:14',60,'Sales : Mallory Figueroa deleted a transaction',12,3,18),(666,2,null,'2022-03-15','14:05:19',60,'Sales : Mallory Figueroa deleted a transaction',12,1,18),(667,2,null,'2022-03-15','14:05:59',60,'Sales : Mallory Figueroa added a new Sales Order transaction ',12,1,18),(668,2,null,'2022-03-15','14:21:05',60,'Sales : Mallory Figueroa added a new Sales Order transaction ',12,2,18),(669,null,null,'2022-03-31','08:59:38',60,'superddt has logged out of the system.',null,null,null),(670,null,null,'2022-04-18','13:59:11',60,'superddt has logged out of the system.',null,null,null),(671,null,null,'2022-04-26','14:40:35',60,'superddt added a new initial reference CON',null,null,null),(672,null,null,'2022-04-26','14:40:57',60,'superddt added a new series reference CON',null,null,null),(673,null,null,'2022-04-26','14:41:43',60,'superddt added a new activity name Other General Requirements',null,null,null),(674,null,null,'2022-04-26','15:12:20',60,'Mallory Figueroa removed the transaction.',null,null,83),(675,null,null,'2022-04-26','15:23:03',60,'Mallory Figueroa  Construction Project List: superddt printed a PDF report. transaction.',null,null,83),(676,null,null,'2022-04-26','15:23:15',60,'Mallory Figueroa  Construction Project List: superddt printed a PDF report. transaction.',null,null,83),(677,null,null,'2022-04-26','15:23:30',60,'Mallory Figueroa  Construction Project List: superddt printed a PDF report. transaction.',null,null,83),(678,2,null,'2022-04-28','14:35:36',60,'Exported the generated Fuel Monitoring Report (PDF)',null,null,81),(679,2,null,'2022-04-28','14:36:03',60,'Exported the generated Fuel Monitoring Report (Excel).',null,null,81),(680,null,null,'2022-04-28','15:00:31',60,'superddt has logged out of the system.',null,null,null),(681,null,null,'2022-04-28','15:05:25',60,'Mallory Figueroa removed the transaction.',null,null,83),(682,null,null,'2022-04-28','15:06:14',60,'superddt has logged out of the system.',null,null,null),(683,2,null,'2022-04-29','13:45:42',60,'Exported the generated Fuel Monitoring Report (Excel).',null,null,81),(684,null,null,'2022-04-29','14:22:58',60,'Mallory Figueroa  Construction Project List: superddt printed a PDF report. transaction.',null,null,81),(685,null,null,'2022-04-29','14:24:19',60,'Mallory Figueroa  Construction Project List: superddt printed a PDF report. transaction.',null,null,81),(686,null,null,'2022-04-29','14:25:09',60,'Mallory Figueroa  Construction Project List: superddt printed a PDF report. transaction.',null,null,81),(687,2,null,'2022-05-10','13:32:15',60,'Exported the generated Fuel Monitoring Report (Excel).',null,null,81),(688,null,null,'2022-05-10','13:37:19',60,'superddt added a new activity name New Activity',null,null,null),(689,null,null,'2022-05-10','13:48:11',60,'Mallory Figueroa  Construction Project List: superddt printed a PDF report. transaction.',null,null,81),(690,null,null,'2022-05-10','13:59:55',60,'superddt has logged out of the system.',null,null,null),(691,null,null,'2022-05-11','13:27:43',60,'Mallory Figueroa added a new transaction.',null,null,81),(692,null,null,'2022-05-11','13:29:30',60,'Mallory Figueroa added a new transaction.',null,null,81),(693,null,null,'2022-05-11','13:34:52',60,'Mallory Figueroa  Construction Project List: superddt printed a PDF report. transaction.',null,null,81),(694,null,null,'2022-05-11','15:08:09',60,'superddt added a new initial reference PA',null,null,null),(695,null,null,'2022-05-11','15:08:26',60,'superddt added a new series reference PA',null,null,null),(696,2,null,'2022-05-11','15:08:47',60,'superddt added a new project accomplishment.',null,null,84),(697,2,null,'2022-05-11','15:08:56',60,'superddt edited the project accomplishment details.',null,null,84),(698,2,null,'2022-05-11','15:08:59',60,'Exported the generated Project Accomplishment Report (PDF)',null,null,84),(699,2,null,'2022-05-11','15:09:05',60,'Exported the generated Project Accomplishment Report (Excel).',null,null,84),(700,2,null,'2022-05-11','15:11:11',60,'Exported the generated Project Accomplishment Report (Excel).',null,null,84),(701,2,null,'2022-05-11','15:11:41',60,'Exported the generated Project Accomplishment Report (PDF)',null,null,84),(702,2,null,'2022-05-11','15:15:09',60,'superddt edited the project accomplishment details.',null,null,84),(703,2,null,'2022-05-11','15:22:53',60,'superddt edited the project accomplishment details.',null,null,84),(704,2,null,'2022-05-11','15:23:11',60,'superddt edited the project accomplishment details.',null,null,84),(705,2,null,'2022-05-11','15:24:11',60,'Exported the generated Project Accomplishment Report (PDF)',null,null,84),(706,null,null,'2022-05-11','16:11:05',60,'Added new classification, Contractor',null,null,null),(707,2,null,'2022-05-11','16:11:23',60,'Modified the employee, Joe Alywn.',null,null,null),(708,2,null,'2022-05-11','16:13:07',60,'superddt edited the project accomplishment details.',null,null,84),(709,null,null,'2022-05-12','08:16:29',60,'Mallory Figueroa added a new transaction.',null,null,81),(710,2,null,'2022-05-12','13:54:53',60,'superddt added a new project accomplishment.',null,null,84),(711,2,null,'2022-05-12','13:58:01',60,'superddt added a new project accomplishment.',null,null,84),(712,null,null,'2022-05-12','14:18:35',60,'Mallory Figueroa removed the transaction.',null,null,81),(713,2,null,'2022-05-12','14:20:30',60,'superddt added a new project accomplishment.',null,null,84),(714,2,null,'2022-05-12','14:21:12',60,'superddt added a new project accomplishment.',null,null,84),(715,null,null,'2022-05-12','14:30:22',60,'Mallory Figueroa removed the transaction.',null,null,81),(716,null,null,'2022-05-12','14:30:23',60,'Mallory Figueroa removed the transaction.',null,null,81),(717,null,null,'2022-05-12','14:30:24',60,'Mallory Figueroa removed the transaction.',null,null,81),(718,2,null,'2022-05-12','14:32:26',60,'superddt added a new project accomplishment.',null,null,84),(719,2,null,'2022-05-12','14:33:03',60,'superddt added a new project accomplishment.',null,null,84),(720,2,null,'2022-05-12','14:33:36',60,'superddt added a new project accomplishment.',null,null,84),(721,2,null,'2022-05-12','14:33:54',60,'superddt edited the project accomplishment details.',null,null,84),(722,2,null,'2022-05-12','14:34:32',60,'superddt edited the project accomplishment details.',null,null,84),(723,2,null,'2022-05-12','14:35:44',60,'superddt added a new project accomplishment.',null,null,84),(724,2,null,'2022-05-12','14:36:30',60,'superddt added a new project accomplishment.',null,null,84),(725,2,null,'2022-05-12','14:37:05',60,'superddt edited the project accomplishment details.',null,null,84),(726,2,null,'2022-05-12','15:07:40',60,'superddt edited the project accomplishment details.',null,null,84),(727,2,null,'2022-05-12','15:08:00',60,'superddt added a new project accomplishment.',null,null,84),(728,2,null,'2022-05-12','15:09:27',60,'superddt edited the project accomplishment details.',null,null,84),(729,2,null,'2022-05-12','15:09:56',60,'superddt added a new project accomplishment.',null,null,84),(730,2,null,'2022-05-12','15:10:12',60,'superddt edited the project accomplishment details.',null,null,84),(731,2,null,'2022-05-12','15:10:48',60,'superddt edited the project accomplishment details.',null,null,84),(732,2,null,'2022-05-12','15:11:04',60,'superddt edited the project accomplishment details.',null,null,84),(733,2,null,'2022-05-12','15:11:20',60,'superddt edited the project accomplishment details.',null,null,84),(734,2,null,'2022-05-12','15:33:30',60,'superddt edited the project accomplishment details.',null,null,84),(735,2,null,'2022-05-12','15:33:38',60,'superddt edited the project accomplishment details.',null,null,84),(736,2,null,'2022-05-12','15:37:31',60,'superddt added a new project accomplishment.',null,null,84),(737,2,null,'2022-05-12','15:47:10',60,'superddt added a new project accomplishment.',null,null,84),(738,2,null,'2022-05-12','15:48:13',60,'superddt edited the project accomplishment details.',null,null,84),(739,2,null,'2022-05-12','15:48:35',60,'superddt edited the project accomplishment details.',null,null,84),(740,2,null,'2022-05-12','16:21:29',60,'superddt added a new project accomplishment.',null,null,84),(741,null,null,'2022-05-12','17:27:10',60,'superddt has logged out of the system.',null,null,null),(742,2,null,'2022-05-17','15:00:14',60,'superddt added a new project accomplishment.',null,null,84),(743,null,null,'2022-05-17','15:09:23',60,'Mallory Figueroa removed the transaction.',null,null,81),(744,null,null,'2022-05-17','15:09:46',60,'Mallory Figueroa removed the transaction.',null,null,81),(745,null,null,'2022-05-17','15:09:47',60,'Mallory Figueroa removed the transaction.',null,null,81),(746,null,null,'2022-05-17','15:09:48',60,'Mallory Figueroa removed the transaction.',null,null,81),(747,null,null,'2022-05-17','15:09:49',60,'Mallory Figueroa removed the transaction.',null,null,81),(748,null,null,'2022-05-17','15:09:50',60,'Mallory Figueroa removed the transaction.',null,null,81),(749,2,null,'2022-05-17','15:43:24',60,'superddt edited the project accomplishment details.',null,null,84),(750,2,null,'2022-05-17','15:43:39',60,'superddt added a new project accomplishment.',null,null,84),(751,null,null,'2022-05-17','15:44:36',60,'Mallory Figueroa removed the transaction.',null,null,81),(752,2,null,'2022-05-17','15:58:13',60,'superddt added a new project accomplishment.',null,null,84),(753,null,null,'2022-05-17','15:58:23',60,'Mallory Figueroa removed the transaction.',null,null,81),(754,null,null,'2022-05-17','15:58:37',60,'Mallory Figueroa removed the transaction.',null,null,81),(755,2,null,'2022-05-18','14:06:35',60,'Exported the generated Project Monitoring Report (PDF)',null,null,85),(756,2,null,'2022-05-18','14:06:42',60,'Exported the generated Project Monitoring Report (Excel).',null,null,85),(757,2,null,'2022-05-18','14:07:16',60,'Exported the generated Project Monitoring Report (PDF)',null,null,85),(758,2,null,'2022-05-18','14:07:45',60,'Exported the generated Project Monitoring Report (Excel).',null,null,85),(759,2,null,'2022-05-18','14:12:14',60,'Exported the generated Project Monitoring Report (Excel).',null,null,85),(760,2,null,'2022-05-18','14:49:07',60,'Generates Inventory Balances.',null,null,null),(761,2,null,'2022-05-18','14:50:24',60,'Generates Inventory Balances.',null,null,null),(762,2,null,'2022-05-18','14:50:30',60,'Generates Inventory Balances.',null,null,null),(763,2,null,'2022-05-19','07:33:39',60,'Mallory Figueroa added a new adjustment transaction.',7,6,23),(764,null,null,'2022-05-19','07:36:14',60,'Generates Purchase return summary report',null,10,39),(765,2,null,'2022-05-19','08:26:27',60,'Mallory Figueroa deleted a transaction.',34,1,2),(766,2,null,'2022-05-19','08:26:37',60,'Mallory Figueroa deleted a transaction.',11,5,2),(767,null,null,'2022-05-19','08:39:38',60,'Restored a file: 20220519083846_dbbackup_60.sql',null,null,7),(768,2,null,'2022-05-19','10:44:43',60,'Mallory Figueroa Closed the month April year 2018.',1,2,35),(769,2,null,'2022-05-19','10:44:57',60,'Mallory Figueroa edited a transaction.',1,2,35),(770,2,null,'2022-05-19','11:09:55',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(771,2,null,'2022-05-19','11:09:59',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(772,null,null,'2022-05-19','11:42:45',60,'Accounting Defaults : Mallory Figueroa deleted default journal entry with purpose : Sample',null,null,null),(773,2,null,'2022-05-19','12:36:27',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(774,2,null,'2022-05-19','13:04:58',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(775,2,null,'2022-05-19','13:05:14',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(776,null,null,'2022-05-19','13:28:59',60,'Generates Releasing Summary Report.',null,null,null),(777,null,null,'2022-05-19','13:29:05',60,'Generates Releasing Summary Report.',null,null,null),(778,null,null,'2022-05-19','13:29:12',60,'Generates Releasing Summary Report.',null,null,null),(779,null,null,'2022-05-19','13:29:22',60,'Generates Releasing Summary Report.',null,null,null),(780,null,null,'2022-05-19','13:29:30',60,'Generates Releasing Summary Report.',null,null,null),(781,null,null,'2022-05-19','13:29:45',60,'Generates Releasing Summary Report.',null,null,null),(782,null,null,'2022-05-19','13:29:47',60,'Generates Releasing Summary Report.',null,null,null),(783,null,null,'2022-05-19','13:30:03',60,'Generates Releasing Summary Report.',null,null,null),(784,null,null,'2022-05-19','13:30:10',60,'Generates Releasing Summary Report.',null,null,null),(785,null,null,'2022-05-19','13:30:14',60,'Generates Releasing Summary Report.',null,null,null),(786,null,null,'2022-05-19','13:30:35',60,'Generates Releasing Summary Report.',null,null,null),(787,null,null,'2022-05-19','13:30:44',60,'Generates Releasing Summary Report.',null,null,null),(788,null,null,'2022-05-19','13:33:11',60,'Generates Releasing Summary Report.',null,null,null),(789,null,null,'2022-05-19','13:33:17',60,'Generates Releasing Summary Report.',null,null,null),(790,null,null,'2022-05-19','13:57:46',60,'Generates Releasing Summary Report.',null,null,null),(791,2,null,'2022-05-19','13:58:09',60,'Generates Receivable Schedule Report',null,null,null),(792,2,null,'2022-05-19','13:58:10',60,'Generates Receivable Schedule Report',null,null,null),(793,null,null,'2022-05-19','14:40:35',60,'superddt has logged out of the system.',null,null,null),(794,null,null,'2022-05-19','14:40:46',60,'superddt has logged out of the system.',null,null,null),(795,2,null,'2022-05-19','14:52:12',60,'superddt added a new project accomplishment.',null,null,84),(796,2,null,'2022-05-19','16:56:22',60,'superddt edited the project accomplishment details.',null,null,84),(797,null,null,'2022-05-20','12:42:36',60,'Stock Transfer: Mallory Figueroa printed a PDF report',null,null,null),(798,null,null,'2022-05-20','12:43:57',60,'Stock Transfer: superddt printed a Excel report',null,null,null),(799,null,null,'2022-05-20','12:43:58',60,'Stock Transfer: Mallory Figueroa printed a PDF report',null,null,null),(800,null,null,'2022-05-20','12:57:03',60,'Stock Transfer: Mallory Figueroa printed a PDF report',null,null,null),(801,null,null,'2022-05-20','12:57:05',60,'Stock Transfer: superddt printed a Excel report',null,null,null),(802,null,null,'2022-05-20','12:58:19',60,'Stock Transfer: Mallory Figueroa printed a PDF report',null,null,null),(803,null,null,'2022-05-20','12:58:22',60,'Stock Transfer: superddt printed a Excel report',null,null,null),(804,2,null,'2022-05-20','13:27:41',60,'Added new employee, Mark Lambino.',null,null,null),(805,2,null,'2022-05-20','13:27:48',60,'Deleted the user account of, Mark Lambino.',null,null,null),(806,null,null,'2022-05-20','13:30:09',60,'Inventory Conversion: Mallory Figueroa printed a PDF report',null,null,null),(807,null,null,'2022-05-20','13:30:15',60,'Inventory Conversion: superddt printed a Excel report',null,null,null),(808,2,null,'2022-05-20','13:31:54',60,'Bank Account Settings : Mallory Figueroa edited the bank account of Cash In Bank - BPI',null,null,null),(809,null,null,'2022-05-20','13:32:39',60,'Accounting Adjustment: Mallory Figueroa printed a PDF report',null,null,null),(810,null,null,'2022-05-20','13:33:00',60,'Accounting Adjustment: superddt printed a Excel report',null,null,null),(811,2,null,'2022-05-20','14:49:59',60,'Mallory Figueroa added a new adjustment transaction.',7,7,23),(812,null,null,'2022-05-20','14:51:58',60,'Generates Purchase return summary report',null,0,39),(813,null,null,'2022-05-20','14:52:03',60,'Generates Purchase return summary report',null,0,39),(814,null,null,'2022-05-20','14:52:05',60,'Exported the generated Purchase Return Summary Report',null,0,39),(815,2,null,'2022-05-20','14:54:01',60,'Mallory Figueroa edited a transaction.',11,2,2),(816,2,null,'2022-05-20','14:54:15',60,'Mallory Figueroa edited a transaction.',11,2,2),(817,2,null,'2022-05-20','14:55:06',60,'Mallory Figueroa edited a transaction.',11,2,2),(818,2,null,'2022-05-20','14:56:10',60,'Mallory Figueroa deleted a transaction.',11,1,2),(819,2,null,'2022-05-20','14:56:15',60,'Mallory Figueroa deleted a transaction.',11,2,2),(820,2,null,'2022-05-20','14:58:18',60,'Added new employee, Alisson Tester.',null,null,null),(821,2,null,'2022-05-20','15:00:24',60,'Mallory Figueroa Closed the month May year 2018.',1,3,35),(822,2,null,'2022-05-20','15:00:31',60,'Mallory Figueroa edited a transaction.',1,3,35),(823,null,null,'2022-05-20','15:01:36',60,'Accounting Defaults : Mallory Figueroa deleted default journal entry with purpose : Sample JE',null,null,null),(824,2,null,'2022-05-20','15:02:23',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(825,2,null,'2022-05-20','15:02:27',60,'Cheque Monitoring : Mallory Figueroa Generates Cheque Monitoring Report',null,null,null),(826,null,null,'2022-05-20','15:03:35',60,'Generates Releasing Summary Report.',null,null,null),(827,null,null,'2022-05-20','15:03:40',60,'Generates Releasing Summary Report.',null,null,null),(828,null,null,'2022-05-20','15:04:00',60,'Generates Releasing Summary Report.',null,null,null),(829,null,null,'2022-05-20','15:04:05',60,'Generates Releasing Summary Report.',null,null,null),(830,2,null,'2022-05-20','15:04:45',60,'Mallory Figueroa Generates Sales Summary Report',null,null,null),(831,2,null,'2022-05-20','15:04:54',60,'Mallory Figueroa Generates Sales Summary Report',null,null,null),(832,2,null,'2022-05-20','15:05:07',60,'Mallory Figueroa Generates Sales Summary Report',null,null,null),(833,2,null,'2022-05-20','15:05:13',60,'Mallory Figueroa Generates Sales Summary Report',null,null,null),(834,2,null,'2022-05-20','15:08:39',60,'Deleted the user account of, Joe Alywn.',null,null,null),(835,null,null,'2022-05-20','15:10:44',60,'Stock Transfer: superddt printed a Excel report',null,null,null),(836,null,null,'2022-05-20','15:10:56',60,'Stock Transfer: Mallory Figueroa printed a PDF report',null,null,null),(837,null,null,'2022-05-20','15:12:19',60,'Inventory Conversion: Mallory Figueroa printed a PDF report',null,null,null),(838,null,null,'2022-05-20','15:12:34',60,'Inventory Conversion: superddt printed a Excel report',null,null,null),(839,null,null,'2022-05-20','15:13:55',60,'Accounting Adjustment: superddt printed a Excel report',null,null,null),(840,null,null,'2022-05-20','15:13:58',60,'Accounting Adjustment: Mallory Figueroa printed a PDF report',null,null,null),(841,null,null,'2022-05-20','15:16:08',60,'superddt has logged out of the system.',null,null,null),(842,2,null,'2022-05-20','15:18:40',60,'Generates Inventory Balances.',null,null,null),(843,2,null,'2022-05-20','15:19:41',60,'Generates Inventory Balances.',null,null,null),(844,null,null,'2022-05-20','15:22:26',60,'superddt has logged out of the system.',null,null,null),(845,2,null,'2022-05-20','15:23:21',60,'deleted an item Del Monte Pineapple Juice',null,null,null),(846,2,null,'2022-05-20','15:23:43',60,'added a new Item, To be deleted',null,null,null),(847,2,null,'2022-05-20','15:24:13',60,'deleted an item To be deleted',null,null,null),(848,2,null,'2022-05-20','15:24:19',60,'deleted an item To be deleted',null,null,null),(849,null,null,'2022-05-20','15:24:32',60,'superddt has logged out of the system.',null,null,null),(850,2,null,'2022-05-23','14:55:35',60,'Ledger (PDF)',null,null,86),(851,2,null,'2022-05-23','14:56:01',60,'Ledger (PDF)',null,null,86),(852,2,null,'2022-05-23','14:56:09',60,'Exported the generated Project Monitoring Report (PDF)',null,null,85),(853,null,null,'2022-05-26','13:42:15',60,'Mallory Figueroa removed the transaction.',null,null,81),(854,2,null,'2022-05-26','13:57:51',60,'deleted an item To be deleted',null,null,null),(855,2,null,'2022-05-26','13:57:54',60,'deleted an item SampTestAff',null,null,null),(856,2,null,'2022-05-26','13:57:58',60,'deleted an item Third Item',null,null,null),(857,2,null,'2022-05-26','13:58:01',60,'deleted an item test123',null,null,null),(858,2,null,'2022-05-26','13:58:20',60,'added a new Item, Delete ni sya',null,null,null),(859,2,null,'2022-05-26','13:58:35',60,'deleted an item Delete ni sya',null,null,null),(860,2,null,'2022-05-26','14:02:57',60,'deleted an item To be deleted',null,null,null),(861,2,null,'2022-05-26','14:08:46',60,'deleted an item Delete ni sya',null,null,null),(862,2,null,'2022-05-26','14:08:52',60,'deleted an item To be deleted',null,null,null),(863,2,null,'2022-05-26','14:08:59',60,'deleted an item Test Delete',null,null,null),(864,2,null,'2022-05-26','14:09:03',60,'deleted an item To be deleted',null,null,null),(865,2,null,'2022-05-26','14:09:08',60,'deleted an item Nescafe Coffee',null,null,null),(866,2,null,'2022-05-26','14:09:13',60,'deleted an item TestUpload',null,null,null),(867,2,null,'2022-05-26','14:09:15',60,'deleted an item test123',null,null,null),(868,2,null,'2022-05-26','14:09:17',60,'deleted an item false',null,null,null),(869,2,null,'2022-05-26','14:09:21',60,'deleted an item Del Monte Pineapple Juice',null,null,null),(870,2,null,'2022-06-08','14:32:29',60,'superddt added a new project accomplishment.',null,null,84),(871,2,null,'2022-08-04','13:02:30',60,'deleted an item Nescafe Coffee',null,null,null),(872,2,null,'2022-08-04','13:03:30',60,'added a new Item, New Item - to be deleted',null,null,null),(873,2,null,'2022-08-04','13:03:38',60,'deleted an item New Item - to be deleted',null,null,null),(874,null,null,'2022-08-04','13:08:59',60,'superddt has logged out of the system.',null,null,null),(875,null,null,'2022-08-05','10:26:16',60,'superddt has logged out of the system.',null,null,null),(876,2,null,'2022-08-17','13:43:51',60,'Modified the employee, mg_staff, for Monica Bing with usertype User',null,null,null),(877,2,null,'2022-08-17','13:44:29',60,'Modified the module access for the user account, mg_staff of Monica Bing, with usertype User.',null,null,null),(878,2,null,'2022-08-17','13:45:23',60,'Modified the module access for the user account, mg_staff of Monica Bing, with usertype User.',null,null,null),(879,2,null,'2022-08-17','13:46:17',60,'Modified the module access for the user account, mg_staff of Monica Bing, with usertype User.',null,null,null),(880,null,null,'2022-08-17','13:56:51',60,'superddt added a new initial reference TEST',null,null,null),(881,null,null,'2022-08-17','13:57:43',60,'superddt edited the details of initial reference TEST',null,null,null),(882,2,null,'2022-08-17','14:12:00',60,'edited an item details, Product 01',null,null,null),(883,2,null,'2022-08-17','14:12:11',60,'edited an item details, Product 02',null,null,null),(884,null,null,'2022-08-17','14:14:53',60,'superddt added a new series reference PA',null,null,null),(885,2,null,'2022-08-17','14:20:31',61,'mg_staff added a new project accomplishment.',null,null,84),(886,2,null,'2022-08-17','14:26:14',60,'Modified the module access for the user account, mg_staff of Monica Bing, with usertype User.',null,null,null),(887,2,null,'2022-08-17','14:27:11',61,'mg_staff edited the project accomplishment details.',null,null,84),(888,2,null,'2022-08-17','14:42:28',60,'Added new employee, Mike Doe.',null,null,null),(889,2,null,'2022-08-17','14:48:31',61,'mg_staff added a new project accomplishment.',null,null,84),(890,2,null,'2022-08-17','14:50:23',61,'Exported the generated Project Accomplishment Report (PDF)',null,null,84),(891,2,null,'2022-08-17','14:50:36',61,'Exported the generated Project Accomplishment Report (Excel).',null,null,84),(892,2,null,'2022-08-17','14:52:50',60,'Modified the module access for the user account, mg_staff of Monica Bing, with usertype User.',null,null,null),(893,2,null,'2022-08-17','14:52:59',60,'Modified the module access for the user account, mg_staff of Monica Bing, with usertype User.',null,null,null),(894,2,null,'2022-08-17','14:53:09',60,'Modified the module access for the user account, mg_staff of Monica Bing, with usertype User.',null,null,null),(895,2,null,'2022-08-17','15:03:39',61,'mg_staff edited the project accomplishment details.',null,null,84),(896,2,null,'2022-08-17','15:15:22',60,'Modified the module access for the user account, mg_staff of Monica Bing, with usertype User.',null,null,null),(897,null,null,'2022-08-17','15:15:25',60,'superddt has logged out of the system.',null,null,null),(898,2,null,'2022-08-17','15:16:37',61,'Exported the generated Project Monitoring Report (PDF)',null,null,85),(899,2,null,'2022-08-17','15:17:50',61,'Exported the generated Project Monitoring Report (Excel).',null,null,85),(900,2,null,'2022-08-17','15:22:57',61,'Exported the generated Project Monitoring Report (PDF)',null,null,85),(901,2,null,'2022-08-17','15:23:06',61,'Exported the generated Project Monitoring Report (PDF)',null,null,85),(902,2,null,'2022-08-17','15:27:24',60,'Modified the module access for the user account, mg_staff of Monica Bing, with usertype User.',null,null,null),(903,2,null,'2022-08-17','15:27:34',60,'Modified the module access for the user account, mg_staff of Monica Bing, with usertype User.',null,null,null),(904,2,null,'2022-08-17','15:28:40',61,'mg_staff edited the project accomplishment details.',null,null,84),(905,null,null,'2022-08-17','15:31:36',61,'mg_staff added a new activity name Sample Activity',null,null,null),(906,2,null,'2022-08-17','15:33:33',61,'Ledger (PDF)',null,null,86),(907,2,null,'2022-08-17','15:34:09',60,'Modified the module access for the user account, mg_staff of Monica Bing, with usertype User.',null,null,null),(908,2,null,'2022-08-17','15:34:30',61,'Ledger (Excel).',null,null,86),(909,null,null,'2022-08-17','15:37:25',60,'superddt has logged out of the system.',null,null,null),(910,2,null,'2022-08-17','15:38:10',61,'Ledger (PDF)',null,null,86),(911,2,null,'2022-08-17','15:38:13',61,'Ledger (Excel).',null,null,86),(912,2,null,'2022-08-17','15:40:45',61,'Exported the generated Budget vs. Actual Report (PDF)',null,null,87),(913,2,null,'2022-08-17','15:41:00',61,'Exported the generated Budget vs. Actual Report (EXCEL)',null,null,87),(914,2,null,'2022-08-18','06:07:08',60,'Generates payable schedule report',null,null,null),(915,2,null,'2022-08-18','06:07:18',60,'Generates payable schedule report',null,null,null),(916,2,null,'2022-08-18','06:20:38',60,'Exported the generated Budget vs. Actual Report (PDF)',null,null,87),(917,2,null,'2022-08-18','06:20:44',60,'Exported the generated Budget vs. Actual Report (EXCEL)',null,null,87),(918,2,null,'2022-08-18','06:22:39',60,'Exported the generated Budget vs. Actual Report (EXCEL)',null,null,87),(919,2,null,'2022-08-18','06:27:55',60,'Exported the generated Budget vs. Actual Report (EXCEL)',null,null,87),(920,2,null,'2022-08-18','06:32:56',60,'Exported the generated Budget vs. Actual Report (EXCEL)',null,null,87),(921,2,null,'2022-08-18','06:33:29',60,'Exported the generated Budget vs. Actual Report (EXCEL)',null,null,87),(922,2,null,'2022-08-18','06:36:16',60,'Exported the generated Budget vs. Actual Report (EXCEL)',null,null,87),(923,2,null,'2022-08-18','06:36:58',60,'Exported the generated Budget vs. Actual Report (PDF)',null,null,87),(924,2,null,'2022-08-18','06:37:30',60,'Exported the generated Budget vs. Actual Report (EXCEL)',null,null,87),(925,2,null,'2022-08-18','06:38:02',60,'Exported the generated Budget vs. Actual Report (EXCEL)',null,null,87),(926,2,null,'2022-08-19','13:01:45',60,'Exported the generated Budget vs. Actual Report (EXCEL)',null,null,87),(927,2,null,'2022-08-19','13:04:53',60,'Exported the generated Budget vs. Actual Report (EXCEL)',null,null,87),(928,2,null,'2022-08-19','13:05:35',60,'Ledger (Excel).',null,null,86),(929,null,null,'2022-08-19','13:22:28',60,'superddt has logged out of the system.',null,null,null),(930,null,null,'2022-08-22','09:03:30',60,'superddt has logged out of the system.',null,null,null),(931,null,null,'2022-08-22','09:06:15',60,'superddt has logged out of the system.',null,null,null),(932,null,null,'2022-08-31','10:48:35',60,'superddt has logged out of the system.',null,null,null),(933,null,null,'2022-09-15','14:53:54',60,'superddt has logged out of the system.',null,null,null),(934,null,null,'2022-10-06','14:18:46',60,'superddt has logged out of the system.',null,null,null),(935,null,null,'2022-10-10','08:46:21',60,'superddt has logged out of the system.',null,null,null),(936,2,null,'2022-10-10','08:51:08',60,'superddt added a new truck type.',null,null,72),(937,null,null,'2022-10-10','08:53:06',60,'superddt has logged out of the system.',null,null,null),(938,2,null,'2022-10-10','08:55:52',60,'superddt added a new truck type.',null,null,72),(939,2,null,'2022-10-10','09:03:04',60,'superddt edited the truck type details.',null,null,72),(940,2,null,'2022-10-10','09:03:26',60,'superddt deleted a truck type.',null,null,72),(941,null,null,'2022-10-10','09:36:47',60,'Mallory Figueroa added a new truck profile.',null,null,74),(942,null,null,'2022-10-10','09:38:40',60,'Mallory Figueroa modified the truck profile.',null,null,74),(943,null,null,'2022-10-10','09:38:58',60,'Mallory Figueroa removed the truck profile.',null,null,74),(944,null,null,'2022-10-10','09:50:24',60,'superddt edited the truck project details.',null,null,null),(945,null,null,'2022-10-10','09:51:03',60,'superddt added a new truck project.',null,null,null),(946,null,null,'2022-10-10','09:51:33',60,'superddt deleted a truck project',null,null,null),(947,2,null,'2022-10-10','09:58:45',60,'Exported the generated Truck Project Report (PDF)',null,null,null),(948,2,null,'2022-10-10','09:59:08',60,'Exported the generated Truck Project Report (Excel).',null,null,null),(949,null,null,'2022-10-10','10:13:12',60,'Mallory Figueroa removed the transaction.',null,null,70),(950,null,null,'2022-10-10','10:54:48',60,'Mallory Figueroa removed the transaction.',null,null,71),(951,null,null,'2022-10-10','11:06:19',60,'Mallory Figueroa modified the truck profile.',null,null,74),(952,2,null,'2022-10-10','11:19:46',60,'superddt added a new truck maintenance.',null,null,78),(953,2,null,'2022-10-10','11:22:11',60,'superddt deleted a truck maintenance.',null,null,78),(954,2,null,'2022-10-10','11:30:15',60,'superddt edited the truck maintenance details.',null,null,78),(955,2,null,'2022-10-10','11:51:17',60,'Exported the generated Truck Maintenance Report (PDF).',null,null,78),(956,2,null,'2022-10-10','11:51:37',60,'Exported the generated Truck Maintenance Report (Excel).',null,null,78),(957,2,null,'2022-10-10','11:58:32',60,'Exported the generated Logsheet Monitoring Report (PDF)',null,null,79),(958,2,null,'2022-10-10','11:59:02',60,'Exported the generated Logsheet Monitoring Report (Excel)',null,null,79),(959,2,null,'2022-10-10','12:34:39',60,'Exported the generated Driver Performance Summary Report (PDF)',null,null,82),(960,2,null,'2022-10-10','12:34:54',60,'Exported the generated Driver Performance Summary Report (Excel).',null,null,82),(961,2,null,'2022-10-10','12:41:18',60,'Exported the generated Fuel Monitoring Report (PDF)',null,null,81),(962,2,null,'2022-10-10','12:41:40',60,'Exported the generated Fuel Monitoring Report (Excel).',null,null,81),(963,2,null,'2022-10-10','12:45:50',60,'Exported the generated delivery ticket monitoring (PDF)',null,null,null),(964,2,null,'2022-10-10','12:46:06',60,'Exported the generated delivery ticket monitoring (Excel)',null,null,null),(965,null,null,'2022-10-10','13:28:54',60,'superddt added a new activity name New Test',null,null,null),(966,null,null,'2022-10-10','13:42:07',60,'Mallory Figueroa removed the transaction.',null,null,81),(967,null,null,'2022-10-10','13:49:00',60,'Mallory Figueroa  Construction Project List: superddt printed a PDF report. transaction.',null,null,81),(968,null,null,'2022-10-10','13:49:18',60,'Mallory Figueroa  Construction Project List: superddt printed a PDF report. transaction.',null,null,81),(969,2,null,'2022-10-10','13:59:45',60,'superddt added a new project accomplishment.',null,null,84),(970,2,null,'2022-10-10','14:07:15',60,'superddt edited the project accomplishment details.',null,null,84),(971,2,null,'2022-10-10','14:15:31',60,'superddt added a new project accomplishment.',null,null,84),(972,2,null,'2022-10-10','14:15:36',60,'superddt added a new project accomplishment.',null,null,84),(973,2,null,'2022-10-10','14:17:06',60,'Exported the generated Project Accomplishment Report (PDF)',null,null,84),(974,2,null,'2022-10-10','14:17:25',60,'Exported the generated Project Accomplishment Report (Excel).',null,null,84),(975,2,null,'2022-10-10','14:21:20',60,'Exported the generated Project Monitoring Report (PDF)',null,null,85),(976,2,null,'2022-10-10','14:21:49',60,'Exported the generated Project Monitoring Report (Excel).',null,null,85),(977,2,null,'2022-10-10','14:30:22',60,'Ledger (PDF)',null,null,86),(978,2,null,'2022-10-10','14:31:18',60,'Ledger (PDF)',null,null,86),(979,2,null,'2022-10-10','14:32:01',60,'Ledger (Excel).',null,null,86),(980,2,null,'2022-10-10','14:39:46',60,'Exported the generated Budget vs. Actual Report (PDF)',null,null,87),(981,2,null,'2022-10-10','14:40:04',60,'Exported the generated Budget vs. Actual Report (EXCEL)',null,null,87),(982,null,null,'2022-10-10','15:05:09',60,'superddt has logged out of the system.',null,null,null),(983,null,null,'2023-01-09','13:23:57',60,'superddt has logged out of the system.',null,null,null),(984,2,null,'2023-01-10','08:21:11',60,'Exported the generated Project Accomplishment Report (PDF)',null,null,84),(985,2,null,'2023-01-10','11:27:24',60,'Generates Expiry Monitoring.',null,null,null),(986,2,null,'2023-01-10','11:37:45',60,'Generates Expiry Monitoring.',null,null,null),(987,2,null,'2023-01-11','08:00:30',60,'Generates Expiry Monitoring.',null,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `module`;:||:Separator:||:


CREATE TABLE `module` (
  `idModule` int NOT NULL AUTO_INCREMENT,
  `moduleType` int DEFAULT NULL COMMENT '1 - Dashboard\\n2 - Inventory\\n3 - Accounting\\n4 - General Reports\\n5 - Admin\\n6 - Trucking',
  `moduleSub` int DEFAULT '0' COMMENT 'Other Menu:\n0 - Transaction\n1 - Reports\n2 - Settings\n3 - Modules\n\nInventory:\n0 - Purchase Order\n1 - Receiving\n2 - Releasing\n3 - Inventory\n4 - Settings',
  `sorter` int DEFAULT '0',
  `moduleName` char(100) DEFAULT NULL,
  `moduleLink` char(100) DEFAULT NULL,
  `moduleArchive` int DEFAULT '0',
  `isTransaction` int DEFAULT '1',
  PRIMARY KEY (`idModule`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `module` WRITE;:||:Separator:||:
 INSERT INTO `module` VALUES(1,0,3,0,'Dashboard','dashboard/Dashboard.js',0,1),(2,4,0,1,'Purchase Order','inventory/Purchaseorder.js',0,0),(3,8,2,4,'Employee Profile','admin/Usersettings.js',0,1),(4,8,2,1,'Affiliate Settings','admin/Affiliatesettings.js',0,1),(5,8,2,2,'Cost Center Settings','admin/Costcentersettings.js',0,1),(6,8,2,3,'Employee Classification Settings','admin/Empclassificationsettings.js',0,1),(7,8,2,6,'Backup and Restore','admin/Bandr.js',0,1),(8,7,3,3,'Reference Settings','generalsettings/Referencesettings.js',0,1),(9,8,2,5,'User Action Logs','admin/Userlog.js',0,1),(10,7,3,4,'Bank Settings','generalsettings/Banksettings.js',0,1),(11,7,3,1,'Customer Settings','generalsettings/customer.js',0,1),(12,7,3,2,'Supplier Settings','generalsettings/Supplier.js',0,1),(14,4,4,2,'Classification Settings','inventory/Classificationsettings.js',0,1),(15,4,4,3,'Unit Settings','inventory/Unitsettings.js',0,1),(16,4,3,8,'Item Settings','inventory/Item.js',0,1),(17,4,2,1,'Sales Order','inventory/Salesorder.js',0,0),(18,4,2,2,'Delivery','inventory/Sales.js',0,0),(19,5,2,1,'Chart of Accounts','accounting/Chartofaccounts.js',0,1),(20,5,2,3,'Accounting Defaults','accounting/Accountingdefaults.js',0,1),(21,4,2,3,'Sales Return','inventory/Salesreturn.js',0,0),(22,4,3,1,'Inventory Conversion','inventory/Inventoryconversion.js',0,0),(23,4,3,2,'Inventory Adjustment','inventory/Adjustments.js',0,0),(24,4,2,5,'Sales Summary','inventory/Salessummary.js',0,1),(25,4,1,1,'Receiving','inventory/Receiving.js',0,0),(26,4,2,6,'Sales Return Summary','inventory/Salesreturnsummary.js',0,1),(27,4,2,7,'SO Monitoring','inventory/Salesordermonitoring.js',0,1),(28,5,0,4,'Cash Receipts','accounting/Cashreceipts.js',0,0),(29,4,1,2,'Purchase Return','inventory/Purchasereturn.js',0,0),(30,4,0,2,'PO Monitoring','inventory/Pomonitoring.js',0,1),(33,4,1,3,'Payable Balance and Ledger','inventory/Payablebalanceledger.js',0,1),(34,4,1,5,'Receiving Summary','inventory/Receivingsummary.js',0,1),(35,5,0,8,'Closing Journal Entry','accounting/Closingentry.js',0,0),(36,5,1,6,'Collection Summary','accounting/Collectionsummary.js',0,1),(37,5,1,7,'Disbursement Summary','accounting/Disbursementsummary.js',0,1),(38,5,1,4,'Financial Report','accounting/Financialreport.js',0,1),(39,4,1,6,'Purchase Return Summary','inventory/Purchasereturnsummary.js',0,1),(40,5,1,2,'General and Subsidiary Ledger','accounting/Generalsubsidiaryledger.js',0,1),(41,4,1,7,'Expiry Monitoring','inventory/Expirymonitoring.js',0,1),(42,5,2,2,'Chart of Accounts Beginning Balance','accounting/Coabegbalance.js',0,1),(43,4,3,3,'Stock Transfer','inventory/Stocktransfer.js',0,0),(44,5,0,7,'Bank Reconciliation','accounting/Bankrecon.js',0,0),(45,5,0,5,'Disbursements','accounting/Disbursements.js',0,0),(46,4,2,9,'Receivable Balances, Ledger and SOA','inventory/Receivablebalanceledger.js',0,1),(47,4,2,10,'Itemized Profit and Loss','inventory/Itemizedprofitloss.js',0,1),(48,5,0,3,'Accounting Adjustment','accounting/Adjustmentsacc.js',0,0),(49,4,1,4,'Payable Transactions','inventory/Payabletransaction.js',0,1),(50,6,1,1,'Payable Schedule','generalreports/Payableschedule.js',0,1),(51,4,3,7,'Adjustment Summary','inventory/Adjustmentsummary.js',0,1),(52,4,2,4,'Releasing Summary','inventory/Releasingsummary.js',0,1),(53,4,3,6,'Conversion Summary','inventory/Conversionsummary.js',0,1),(54,4,2,8,'Receivable Transactions','inventory/Receivabletransaction.js',0,1),(55,6,1,4,'Aging of Payables','generalreports/Agingofpayables.js',0,1),(56,6,1,3,'Aging of Receivables','generalreports/Agingofreceivables.js',0,1),(57,5,0,2,'Vouchers Payable','accounting/Voucherspayable.js',0,0),(58,5,0,1,'Vouchers Receivable','accounting/Vouchersreceivable.js',0,0),(59,4,3,5,'Inventory Ledger','inventory/Inventoryledger.js',0,1),(60,6,1,2,'Schedule of Receivable','generalreports/Scheduleofreceivable.js',0,1),(61,4,3,4,'Inventory Balances','inventory/Inventorybalances.js',0,1),(62,5,0,6,'Beginning Balance','accounting/Beginningbalance.js',0,0),(63,5,2,4,'Bank Account Settings','accounting/Bankaccountsettings.js',0,1),(64,6,1,5,'Cheque Monitoring','generalreports/Chequemonitoring.js',0,1),(65,6,1,6,'Cheque Reports','generalreports/Chequereports.js',0,1),(66,5,1,3,'Accounting Adjustment Summary','accounting/Adjustmentsaccsummary.js',0,1),(67,4,2,11,'Cancelled Transactions','inventory/Cancelledtransactions.js',0,1),(68,5,1,5,'No JE Report','accounting/Nojereport.js',0,1),(69,5,1,1,'Journalized Transaction Summary','accounting/Journalizedtransactionsummary.js',0,1),(70,2,0,1,'Delivery Ticket','trucking/Deliveryticket.js',0,0),(71,2,0,2,'Rental of Heavy Equipment','trucking/Rentalofheavyequipment.js',0,0),(72,2,2,2,'Truck Type','trucking/Trucktype.js',0,1),(73,2,2,4,'Truck Project','trucking/Truckproject.js',0,1),(74,2,2,1,'Truck Profile','trucking/Truckprofile.js',0,1),(75,2,2,3,'Tire Profile','trucking/Tireprofile.js',0,1),(76,7,3,1,'Location Settings','generalsettings/Locationsettings.js',0,1),(77,2,1,4,'Delivery Ticket Monitoring','trucking/Deliveryticketmonitoring.js',0,1),(78,2,0,3,'Truck Maintenance','trucking/Truckmaintenance.js',0,0),(79,2,1,5,'Logsheet','trucking/Truckinglogs.js',0,1),(80,2,1,1,'Truck Monitoring','trucking/Truckmonitoring.js',0,1),(81,2,1,3,'Fuel Monitoring','trucking/Fuelmonitoring.js',0,1),(82,2,1,2,'Driver Performance Summary','trucking/Driverperformancesummary.js',0,1),(83,1,0,1,'Project Settings','construction/Constructionproject.js',0,0),(84,1,0,2,'Project Accomplishment','construction/Projectaccomplishment.js',0,0),(85,1,1,1,'Project Monitoring','construction/Projectmonitoring.js',0,1),(86,1,1,2,'Material Monitoring','construction/Materialmonitoring.js',0,1),(87,1,1,3,'Budget vs. Actual','construction/Budgetvsactual.js',0,1),(88,3,0,1,'Project Accomplishement Report','payroll/Projectaccomplishementreport.js',0,0),(89,3,0,2,'Attendance','payroll/Attendance.js',0,0),(90,3,0,1,'Cash Advance','payroll/Cashadvance.js',0,0),(91,3,0,3,'Payroll','payroll/Payroll.js',0,0),(92,3,1,1,'Logs Summary','payroll/Logssummary.js',0,0),(93,1,1,4,'Project Accomplishment Summary','construction/Projectaccomplishmentsummary.js',0,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `po`;:||:Separator:||:


CREATE TABLE `po` (
  `idPo` int NOT NULL AUTO_INCREMENT,
  `idItem` int DEFAULT NULL,
  `idItemClass` int DEFAULT NULL,
  `qty` int DEFAULT '0',
  `qtyLeft` int DEFAULT '0',
  `cost` decimal(18,2) DEFAULT '0.00',
  `idInvoice` int DEFAULT NULL,
  PRIMARY KEY (`idPo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `po` WRITE;:||:Separator:||:
 INSERT INTO `po` VALUES(1,13,2,12,0,24.50,9),(2,13,2,100,50,24.50,56),(3,14,2,20,0,30.00,56),(4,4,6,20,0,150.00,56),(5,13,2,100,0,24.50,69),(6,14,2,100,0,30.00,69);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `pohistory`;:||:Separator:||:


CREATE TABLE `pohistory` (
  `idPoHistory` int NOT NULL AUTO_INCREMENT,
  `idPo` int DEFAULT NULL,
  `idItem` int DEFAULT NULL,
  `idItemClass` varchar(45) DEFAULT NULL,
  `qty` int DEFAULT '0',
  `qtyLeft` int DEFAULT '0',
  `cost` decimal(18,2) DEFAULT '0.00',
  `idInvoice` int DEFAULT NULL,
  `idInvoiceHistory` int DEFAULT NULL,
  PRIMARY KEY (`idPoHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `pohistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `postdated`;:||:Separator:||:


CREATE TABLE `postdated` (
  `idPostdated` int NOT NULL AUTO_INCREMENT,
  `paymentMethod` int DEFAULT NULL COMMENT '1 - Cash\n2 - Cheque\n3 - Terms',
  `idBankAccount` int DEFAULT NULL,
  `chequeNo` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT '0.00',
  `idInvoice` int DEFAULT NULL,
  `idBankRecon` int DEFAULT NULL,
  `status` int DEFAULT NULL COMMENT '1 - Ourstanding\n2 - Cleared\n3 - Canceled\n4 - Bounced',
  `statusDate` date DEFAULT NULL,
  `remarks` char(250) DEFAULT NULL,
  `depositBankAccountId` int DEFAULT NULL,
  PRIMARY KEY (`idPostdated`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `postdated` WRITE;:||:Separator:||:
 INSERT INTO `postdated` VALUES(2,0,1,123456,'2020-06-11',3000.00,25,null,1,null,null,null),(3,2,1,123456,'2020-07-12',150.00,43,null,1,null,null,null),(4,1,1,0,'0000-00-00',150.00,43,null,1,null,null,null),(6,0,2,123456,'2020-07-02',200.00,44,null,1,null,null,null),(8,0,2,1121231321,'2021-09-14',1050.00,78,null,1,null,null,null),(10,1,1,0,'0000-00-00',5545.50,79,null,1,null,null,null),(11,0,1,123456,'2021-09-15',20000.00,79,null,1,null,null,null),(12,1,2,1213123,'2021-09-14',24000.00,82,null,1,null,null,null),(13,2,1,93176321,'2021-09-22',100700.00,83,null,1,null,null,null),(14,0,1,0,null,4550.00,84,null,null,null,null,null),(15,0,1,0,'0000-00-00',900.00,85,null,null,null,null,null),(16,2,5,123456789,'2021-12-21',3000.00,63,null,null,null,null,null),(17,2,5,812372131,'2022-03-07',5021.00,134,null,1,null,null,null),(18,1,2,0,'2022-03-03',5000.00,134,null,1,null,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `postdatedhistory`;:||:Separator:||:


CREATE TABLE `postdatedhistory` (
  `idPosdatedHistory` int NOT NULL AUTO_INCREMENT,
  `idPostdated` int DEFAULT NULL,
  `paymentMethod` int DEFAULT NULL COMMENT '1 - Cash\n2 - Cheque\n3 - Terms',
  `idBankAccount` int DEFAULT NULL,
  `chequeNo` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT '0.00',
  `idInvoice` int DEFAULT NULL,
  `idBankRecon` int DEFAULT NULL,
  `status` int DEFAULT NULL COMMENT '1 - Ourstanding\n2 - Cleared\n3 - Canceled\n4 - Bounced',
  `statusDate` date DEFAULT NULL,
  `remarks` char(250) DEFAULT NULL,
  `depositBankAccountId` int DEFAULT NULL,
  PRIMARY KEY (`idPosdatedHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `postdatedhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `posting`;:||:Separator:||:


CREATE TABLE `posting` (
  `idPosting` int NOT NULL AUTO_INCREMENT,
  `idInvoice` int DEFAULT NULL,
  `idBankRecon` int DEFAULT NULL,
  `explanation` text,
  `debit` decimal(18,2) DEFAULT '0.00',
  `credit` decimal(18,2) DEFAULT '0.00',
  `idCoa` int DEFAULT NULL,
  `idAccBegBal` int DEFAULT NULL,
  `idCostCenter` int DEFAULT NULL,
  PRIMARY KEY (`idPosting`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `posting` WRITE;:||:Separator:||:
 INSERT INTO `posting` VALUES(1,24,null,null,0.00,100.00,1103000,null,null),(2,24,null,null,100.00,0.00,2102000,null,null),(3,26,null,null,1.00,0.00,5101000,null,null),(4,26,null,null,0.00,1.00,2102000,null,null),(5,30,null,null,3243.00,0.00,1102000,null,0),(6,30,null,null,0.00,3243.00,1103000,null,0),(11,null,null,null,100000.00,100000.00,1117000,1,null),(12,77,null,'Sample only',1000.00,0.00,4102000,null,5),(13,77,null,'Sample adjustment only',0.00,1000.00,2101000,null,5),(14,32,null,null,23423.00,0.00,1102000,null,0),(15,32,null,null,0.00,23423.00,2101000,null,0),(16,45,null,null,1000.00,0.00,1101000,null,0),(17,45,null,null,0.00,1000.00,1101001,null,0),(18,78,null,null,0.00,0.00,0,null,0),(19,78,null,null,0.00,0.00,0,null,0),(20,43,null,null,0.00,1500.00,2101000,null,0),(21,43,null,null,1500.00,0.00,1101000,null,0),(22,null,null,null,1000.00,0.00,1101000,2,null),(23,null,null,null,0.00,1000.00,5102001,2,null),(24,58,null,null,0.00,-1000.00,5102001,null,null),(29,84,null,null,1000.00,0.00,1103000,null,0),(30,84,null,null,0.00,1000.00,1102001,null,0),(33,62,null,null,0.00,4000.00,1102001,null,0),(34,62,null,null,4000.00,0.00,2101000,null,0),(35,135,null,'Adjusment',920.00,0.00,1103000,null,null),(36,135,null,null,0.00,920.00,4102000,null,null),(37,136,null,'adjustment',1000.00,0.00,1103000,null,null),(38,136,null,null,0.00,1000.00,5101000,null,null),(39,142,null,null,100.00,0.00,5102001,null,null),(40,142,null,null,0.00,100.00,2102000,null,null),(41,143,null,null,100.00,0.00,1101000,null,0),(42,143,null,null,0.00,100.00,1117000,null,0),(43,146,null,null,100.00,0.00,5101000,null,null),(44,146,null,null,0.00,100.00,1101000,null,null),(45,147,null,null,0.00,1000.00,1103000,null,null),(46,147,null,null,1000.00,0.00,4102000,null,null),(47,183,null,null,1000.00,0.00,2101000,null,null),(48,183,null,null,0.00,1000.00,4102000,null,null),(49,192,null,null,2000.00,0.00,1103000,null,null),(50,192,null,null,0.00,2000.00,4102000,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `postinghistory`;:||:Separator:||:


CREATE TABLE `postinghistory` (
  `idPostingHistory` int NOT NULL AUTO_INCREMENT,
  `idPosting` int DEFAULT NULL,
  `idInvoice` int DEFAULT NULL,
  `explanation` text,
  `debit` decimal(18,2) DEFAULT '0.00',
  `credit` decimal(18,2) DEFAULT '0.00',
  `idCoa` int DEFAULT NULL,
  `idAccBegBal` int DEFAULT NULL,
  `idCostCenter` int DEFAULT NULL,
  `idInvoiceHistory` int DEFAULT NULL,
  `idBankRecon` int DEFAULT NULL,
  `idBankReconHistory` int DEFAULT NULL,
  PRIMARY KEY (`idPostingHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `postinghistory` WRITE;:||:Separator:||:
 INSERT INTO `postinghistory` VALUES(1,null,30,null,3243.00,0.00,1102000,null,0,14,null,null),(2,null,30,null,0.00,3243.00,1103000,null,0,14,null,null),(3,null,32,null,23423.00,0.00,1102000,null,0,16,null,null),(4,null,32,null,0.00,23423.00,2101000,null,0,16,null,null),(5,null,45,null,1000.00,0.00,1101000,null,0,21,null,null),(6,null,45,null,0.00,1000.00,1101001,null,0,21,null,null),(7,null,null,null,100000.00,100000.00,1117000,1,null,null,null,null),(8,null,77,'Sample only',1000.00,0.00,4102000,null,5,45,null,null),(9,null,77,'Sample adjustment only',0.00,1000.00,2101000,null,5,45,null,null),(10,null,32,null,23423.00,0.00,1102000,null,0,46,null,null),(11,null,32,null,0.00,23423.00,2101000,null,0,46,null,null),(12,null,45,null,1000.00,0.00,1101000,null,0,47,null,null),(13,null,45,null,0.00,1000.00,1101001,null,0,47,null,null),(14,null,null,null,1000.00,0.00,1101000,2,null,null,null,null),(15,null,null,null,0.00,1000.00,5102001,2,null,null,null,null),(16,null,58,null,0.00,-1000.00,5102001,null,null,null,null,null),(17,25,62,null,0.00,4000.00,1102001,null,0,null,null,null),(18,26,62,null,4000.00,0.00,2101000,null,0,null,null,null),(19,null,84,null,1000.00,0.00,1103000,null,0,53,null,null),(20,null,84,null,0.00,1000.00,1102001,null,0,53,null,null),(21,null,84,null,1000.00,0.00,1103000,null,0,54,null,null),(22,null,84,null,0.00,1000.00,1102001,null,0,54,null,null),(23,31,62,null,0.00,4000.00,1102001,null,0,null,null,null),(24,32,62,null,4000.00,0.00,2101000,null,0,null,null,null),(25,33,62,null,0.00,4000.00,1102001,null,0,null,null,null),(26,34,62,null,4000.00,0.00,2101000,null,0,null,null,null),(27,null,143,null,100.00,0.00,1101000,null,0,84,null,null),(28,null,143,null,0.00,100.00,1117000,null,0,84,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `receipts`;:||:Separator:||:


CREATE TABLE `receipts` (
  `idReceipts` int NOT NULL AUTO_INCREMENT,
  `idInvoice` int DEFAULT NULL,
  `idCustomer` int DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT '0.00',
  `ewtRate` decimal(18,2) DEFAULT '0.00',
  `ewtAmount` decimal(18,2) DEFAULT '0.00',
  `penaltyRate` decimal(18,2) DEFAULT '0.00',
  `penaltyAmount` decimal(18,2) DEFAULT '0.00',
  `fIDModule` int DEFAULT NULL,
  `fident` int DEFAULT NULL,
  `idBankRecon` int DEFAULT NULL,
  PRIMARY KEY (`idReceipts`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `receipts` WRITE;:||:Separator:||:
 INSERT INTO `receipts` VALUES(2,25,3,3000.00,0.00,0.00,0.00,0.00,0,16,null),(3,43,8,300.00,0.00,0.00,0.00,0.00,18,42,null),(5,44,8,200.00,0.00,0.00,0.00,0.00,0,42,null),(7,78,10,1050.00,0.00,0.00,0.00,0.00,0,75,null),(9,79,8,25545.50,0.00,0.00,0.00,0.00,0,41,null),(10,82,8,24000.00,0.00,0.00,0.00,0.00,18,81,null),(11,83,8,100700.00,0.00,0.00,0.00,0.00,18,81,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `receiptshistory`;:||:Separator:||:


CREATE TABLE `receiptshistory` (
  `idReceiptsHistory` int NOT NULL AUTO_INCREMENT,
  `idReceipts` int DEFAULT NULL,
  `idCustomer` int DEFAULT NULL,
  `remarks` text,
  `amount` decimal(18,2) DEFAULT '0.00',
  `idInvoice` int DEFAULT NULL,
  `fIDModule` int DEFAULT NULL,
  `fident` int DEFAULT NULL,
  `idInvoiceHistory` int DEFAULT NULL,
  PRIMARY KEY (`idReceiptsHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `receiptshistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `receiving`;:||:Separator:||:


CREATE TABLE `receiving` (
  `idReceiving` int NOT NULL AUTO_INCREMENT,
  `idItem` int DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `qtyLeft` int DEFAULT NULL,
  `idInvoice` int DEFAULT NULL,
  `cost` decimal(18,2) DEFAULT '0.00',
  `price` decimal(18,2) DEFAULT '0.00',
  `expiryDate` date DEFAULT NULL,
  `fIDModule` int DEFAULT NULL,
  `fident` int DEFAULT NULL,
  `lotNumber` text,
  PRIMARY KEY (`idReceiving`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `receiving` WRITE;:||:Separator:||:
 INSERT INTO `receiving` VALUES(1,1,200,0,6,100.00,0.00,'0000-00-00',25,1,null),(2,2,150,145,6,130.56,0.00,'0000-00-00',25,1,null),(3,1,132,37,7,156.50,0.00,'0000-00-00',25,2,null),(4,2,150,150,7,145.00,0.00,'0000-00-00',25,2,null),(5,1,1502,1452,8,231.59,0.00,'0000-00-00',25,3,null),(6,2,148,146,8,150.00,0.00,'0000-00-00',25,3,null),(7,1,150,150,9,100.00,0.00,'0000-00-00',25,1,null),(8,2,150,150,9,130.56,0.00,'0000-00-00',25,1,null),(9,1,10,10,21,100.00,250.00,null,null,6,null),(10,2,10,null,23,130.56,0.00,null,null,null,null),(11,2,1,1,26,0.00,0.00,'0000-00-00',null,null,null),(12,2,1,0,27,200.00,0.00,'0000-00-00',25,null,null),(13,2,10,10,28,0.00,1000.00,'0000-00-00',null,null,null),(14,1,10,0,34,250.00,0.00,'0000-00-00',25,33,null),(15,1,500,398,36,132.00,0.00,null,25,35,null),(16,2,300,300,36,245.00,0.00,'0000-00-00',25,35,null),(19,1,1000,1000,38,130.00,0.00,'0000-00-00',25,35,null),(20,2,900,900,38,250.00,0.00,'0000-00-00',25,35,null),(23,3,20,0,37,0.00,3780.00,null,null,null,null),(24,4,30,30,37,0.00,3000.00,null,null,null,null),(25,1,10,10,47,250.00,0.00,'0000-00-00',25,46,0),(26,2,0,0,47,300.00,0.00,'0000-00-00',25,46,0),(27,1,3,1,49,250.00,0.00,'0000-00-00',25,48,0),(28,5,1,0,49,1500.00,0.00,'0000-00-00',25,48,0),(29,2,2,1,49,300.00,0.00,'0000-00-00',25,48,0),(39,0,0,0,56,0.00,0.00,'0000-00-00',25,null,0),(42,4,50,0,56,150.00,0.00,'0000-00-00',25,null,0),(43,3,50,0,56,189.00,0.00,'0000-00-00',25,null,0),(44,13,50,0,56,24.50,0.00,'0000-00-00',25,null,0),(46,14,100,0,56,30.00,0.00,'0000-00-00',25,null,0),(47,13,100,0,59,24.50,0.00,'2021-10-24',25,null,0),(49,1,50,50,63,132.00,250.00,null,null,13,null),(50,1,2,2,64,132.00,250.00,null,null,14,null),(52,14,10,0,65,0.00,300.00,'2021-10-15',null,null,null),(56,0,100,null,0,0.00,0.00,null,null,null,null),(57,0,100,null,0,24.50,0.00,null,null,null,null),(58,0,20,null,0,132.00,0.00,null,null,null,null),(59,13,100,null,69,24.50,0.00,null,null,null,null),(60,2,2000,null,69,300.00,0.00,null,null,null,null),(61,3,2000,null,69,189.00,0.00,null,null,null,null),(62,0,100,null,0,24.50,0.00,null,null,null,null),(63,0,2000,null,0,300.00,0.00,null,null,null,null),(64,0,300,null,0,0.00,0.00,null,null,null,null),(65,13,10,null,71,24.50,0.00,null,null,null,null),(66,13,100,0,72,24.50,0.00,'2024-08-27',25,null,0),(67,14,5,0,73,30.00,0.00,'2023-08-27',25,null,0),(68,14,10,0,76,0.00,30.00,null,null,49,null),(75,13,100,30,70,24.50,0.00,'0000-00-00',25,69,0),(76,14,100,0,70,30.00,0.00,'0000-00-00',25,69,0),(77,1,25,25,75,231.59,100.00,null,null,7,null),(78,15,2,2,76,0.00,300.00,'0000-00-00',null,null,null),(79,13,5,null,78,24.50,0.00,null,null,null,null),(80,4,5,null,78,150.00,0.00,null,null,null,null),(97,0,5,null,0,24.50,0.00,null,null,null,null),(98,0,5,null,0,0.00,0.00,null,null,null,null),(99,13,1,null,123,24.50,0.00,null,null,null,null),(100,4,1,null,125,150.00,0.00,null,null,null,null),(101,4,1,null,127,150.00,0.00,null,null,null,null),(102,13,1,null,129,24.50,0.00,null,null,null,null),(103,13,1,null,131,24.50,0.00,null,null,null,null),(104,13,1,null,133,24.50,0.00,null,null,null,null),(107,13,2,null,132,24.50,0.00,null,null,null,null),(108,2,1,null,132,300.00,0.00,null,null,null,null),(109,4,20,20,135,46.00,0.00,'0000-00-00',null,null,null),(110,3,10,10,136,100.00,0.00,'0000-00-00',null,null,null),(114,14,500,500,137,30.00,0.00,'2023-05-26',25,null,0),(115,4,150,150,137,150.00,0.00,'2023-11-13',25,null,0),(116,13,300,300,137,24.50,0.00,'2023-02-28',25,null,0),(117,13,1,null,139,24.50,0.00,null,null,null,null),(120,13,1,null,138,24.50,0.00,null,null,null,null),(121,13,1,null,138,24.50,0.00,null,null,null,null),(122,14,10,null,138,30.00,0.00,null,null,null,null),(123,3,100,null,149,100.00,0.00,null,null,null,null),(125,20,100,100,183,0.00,0.00,'0000-00-00',null,null,null),(126,18,5,5,192,500.00,0.00,'0000-00-00',null,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `receivinghistory`;:||:Separator:||:


CREATE TABLE `receivinghistory` (
  `idReceivingHistory` int NOT NULL AUTO_INCREMENT,
  `idReceiving` int DEFAULT NULL,
  `idItem` int DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `qtyLeft` int DEFAULT NULL,
  `idInvoice` int DEFAULT NULL,
  `price` decimal(18,2) DEFAULT '0.00',
  `cost` decimal(18,2) DEFAULT '0.00',
  `expiryDate` date DEFAULT NULL,
  `fIDModule` int DEFAULT NULL,
  `fident` int DEFAULT NULL,
  `lotNumber` text,
  PRIMARY KEY (`idReceivingHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `receivinghistory` WRITE;:||:Separator:||:
 INSERT INTO `receivinghistory` VALUES(1,null,14,0,0,121,0.00,30.00,'0000-00-00',25,null,0),(2,null,4,0,0,121,0.00,150.00,'0000-00-00',25,null,0),(3,null,13,0,0,121,0.00,24.50,'0000-00-00',25,null,0),(4,null,5,0,0,121,0.00,2000.00,'0000-00-00',25,null,0),(5,null,14,0,0,121,0.00,30.00,'0000-00-00',25,null,0),(6,null,4,0,0,121,0.00,150.00,'0000-00-00',25,null,0),(7,null,13,0,0,121,0.00,24.50,'0000-00-00',25,null,0),(8,null,5,0,0,121,0.00,2000.00,'0000-00-00',25,null,0),(9,null,14,0,0,121,0.00,30.00,'0000-00-00',25,null,0),(10,null,4,0,0,121,0.00,150.00,'0000-00-00',25,null,0),(11,null,13,0,0,121,0.00,24.50,'0000-00-00',25,null,0),(12,null,5,0,0,121,0.00,2000.00,'0000-00-00',25,null,0),(13,null,14,500,500,137,0.00,30.00,'2023-05-26',25,null,0),(14,null,4,150,150,137,0.00,150.00,'2023-11-13',25,null,0),(15,null,13,300,300,137,0.00,24.50,'2023-02-28',25,null,0),(16,null,1,50,50,67,0.00,132.00,null,25,null,0),(17,null,14,20,20,67,0.00,0.00,null,25,null,0),(18,null,13,30,30,67,0.00,24.50,null,25,null,0),(19,null,3,50,50,67,0.00,189.00,'0000-00-00',25,null,0),(20,null,1,50,50,67,0.00,132.00,null,25,null,0),(21,null,14,20,20,67,0.00,0.00,null,25,null,0),(22,null,13,30,30,67,0.00,24.50,null,25,null,0),(23,null,3,50,50,67,0.00,189.00,'0000-00-00',25,null,0),(24,null,1,30,30,67,0.00,132.00,null,25,null,0),(25,null,14,20,20,67,0.00,0.00,null,25,null,0),(26,null,13,30,30,67,0.00,24.50,null,25,null,0),(27,null,3,25,25,67,0.00,189.00,'0000-00-00',25,null,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `reference`;:||:Separator:||:


CREATE TABLE `reference` (
  `idReference` int NOT NULL AUTO_INCREMENT,
  `code` char(5) DEFAULT NULL,
  `name` char(50) DEFAULT NULL,
  `idModule` int DEFAULT NULL,
  `isDefault` int DEFAULT '0' COMMENT '0 - False\n1 - True',
  `archived` int DEFAULT '0',
  PRIMARY KEY (`idReference`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `reference` WRITE;:||:Separator:||:
 INSERT INTO `reference` VALUES(1,'CE','Closing Entry',35,1,0),(2,'AAD','Accounting Adjustment',48,0,0),(3,'BR','Bank Reconciliation',44,0,0),(4,'BB','Beginning Balance',62,0,0),(5,'CR','Cash Receipt',28,0,0),(6,'DR','Disbursement',45,0,0),(7,'IAD','Inventory Adjustment',23,0,0),(8,'IC','Inventory Conversion',22,0,0),(9,'PO','Purchase Order',2,0,0),(10,'PR','Purchase Return',29,0,0),(11,'RR','Receiving',25,0,0),(12,'SA','Sales',18,0,0),(13,'SO','Sales Order',17,0,0),(14,'SR','Sales Return',21,0,0),(15,'ST','Stock Transfer',43,0,0),(16,'VP','Vouchers Payable',57,0,0),(17,'VR','Vouchers Receivable',58,0,0),(18,'InvAd','Inv Adjustment',23,0,0),(19,'PO1','Purchase Order 1',2,0,0),(20,'RR1','Receiving 1',25,0,0),(21,'OR','Test OR',45,0,0),(22,'HKM','HKM Purchase Order',2,0,0),(23,'SAOR','Sample Receivable',58,0,0),(28,'AAD1','Account Adjust',48,0,0),(29,'DT','Delivery Ticket',70,0,0),(30,'TRT','Truck Type',72,0,0),(31,'RHE','Rental of Heavy Equipment',71,0,0),(32,'NWDT','New Delivery Ticket',70,0,0),(33,'TM','Truck Maintenance',78,0,0),(34,'RR2','Receiving 2',25,0,0),(35,'ST1','Stock Transfer 1',43,0,0),(36,'CON','Construction Project',83,0,0),(37,'PA','Project Accomplishment',84,0,0),(38,'TEST','Ttest',48,0,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `referenceaffiliate`;:||:Separator:||:


CREATE TABLE `referenceaffiliate` (
  `idRefAffiliate` int NOT NULL AUTO_INCREMENT,
  `idReference` int DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  PRIMARY KEY (`idRefAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=162 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `referenceaffiliate` WRITE;:||:Separator:||:
 INSERT INTO `referenceaffiliate` VALUES(1,2,2),(2,2,5),(3,2,4),(4,3,2),(5,3,5),(6,3,4),(7,4,2),(8,4,5),(9,4,4),(10,5,2),(11,5,5),(12,5,4),(13,6,2),(14,6,5),(15,6,4),(16,7,2),(17,7,5),(18,7,4),(19,8,2),(20,8,5),(21,8,4),(22,9,2),(23,9,5),(24,9,4),(25,10,2),(26,10,5),(27,10,4),(28,11,2),(29,11,5),(30,11,4),(31,12,2),(32,12,5),(33,12,4),(34,13,2),(35,13,5),(36,13,4),(37,14,2),(38,14,5),(39,14,4),(40,15,2),(41,15,5),(42,15,4),(43,16,2),(44,16,5),(45,16,4),(46,17,2),(47,17,5),(48,17,4),(52,18,2),(53,18,5),(54,18,4),(55,19,2),(56,19,6),(57,19,5),(66,20,6),(67,20,9),(68,20,10),(69,20,8),(70,20,2),(71,20,5),(72,20,4),(73,20,11),(74,21,2),(79,22,12),(86,23,2),(132,28,2),(133,28,6),(134,28,5),(135,28,12),(136,28,14),(137,28,8),(138,28,13),(139,28,4),(140,29,2),(142,30,2),(143,31,2),(144,32,12),(145,32,2),(146,33,2),(147,34,2),(148,35,12),(149,35,2),(150,36,2),(151,37,2),(157,38,2),(158,38,4),(161,0,2);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `referenceaffiliatehistory`;:||:Separator:||:


CREATE TABLE `referenceaffiliatehistory` (
  `idRefAffiliateHistory` int NOT NULL AUTO_INCREMENT,
  `idRefAffiliate` int DEFAULT NULL,
  `idReference` int DEFAULT NULL,
  `idReferenceHistory` int DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  PRIMARY KEY (`idRefAffiliateHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `referenceaffiliatehistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `referencehistory`;:||:Separator:||:


CREATE TABLE `referencehistory` (
  `idReferenceHistory` int NOT NULL AUTO_INCREMENT,
  `idReference` int DEFAULT NULL,
  `code` char(5) DEFAULT NULL,
  `name` char(50) DEFAULT NULL,
  `idModule` int DEFAULT NULL,
  `isDefault` int DEFAULT '0',
  PRIMARY KEY (`idReferenceHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `referencehistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `referenceseries`;:||:Separator:||:


CREATE TABLE `referenceseries` (
  `idReferenceSeries` int NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  `idCostCenter` int DEFAULT NULL,
  `idModule` int DEFAULT NULL,
  `idReference` int DEFAULT NULL,
  `seriesFrom` int DEFAULT NULL,
  `seriesTo` int DEFAULT NULL,
  `archived` int DEFAULT '0',
  PRIMARY KEY (`idReferenceSeries`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `referenceseries` WRITE;:||:Separator:||:
 INSERT INTO `referenceseries` VALUES(1,'2010-01-01',1,null,35,1,1,99999,0),(2,'2010-01-01',2,null,35,1,1,99999,0),(3,'2020-06-12',4,null,48,2,1,5,0),(4,'2020-06-12',4,null,44,3,1,5,0),(5,'2020-06-12',4,null,62,4,1,5,0),(6,'2020-06-12',4,null,28,5,1,5,0),(7,'2020-06-12',4,null,45,6,1,5,0),(8,'2020-06-12',4,null,23,7,1,5,0),(9,'2020-06-12',4,null,22,8,1,5,0),(10,'2020-06-12',4,null,2,9,1,5,0),(11,'2020-06-12',4,null,29,10,1,5,0),(12,'2020-06-12',4,null,25,11,1,5,0),(13,'2020-06-12',4,null,18,12,1,5,0),(14,'2020-06-12',4,null,17,13,1,5,0),(15,'2020-06-12',4,null,21,14,1,5,0),(16,'2020-06-12',4,null,43,15,1,5,0),(17,'2020-06-12',4,null,57,16,1,5,0),(18,'2020-06-12',4,null,58,17,1,5,0),(19,'2020-06-12',2,null,23,7,1,1000,0),(20,'2020-06-12',2,null,25,11,1,1000,0),(21,'2020-06-13',2,null,2,9,10,20,0),(22,'2020-06-17',2,null,48,2,1,100,0),(23,'2020-07-03',6,null,35,1,1,999999,0),(24,'2020-07-03',7,null,35,1,1,999999,0),(25,'2020-07-03',8,null,35,1,1,999999,0),(26,'2020-06-01',2,null,2,19,101,200,0),(27,'2020-07-04',2,null,22,8,1,100,0),(28,'2020-07-04',2,null,29,10,1,100,0),(29,'2020-07-04',2,null,17,13,1,100,0),(30,'2020-07-04',2,null,18,12,1,100,0),(31,'2020-07-04',2,null,28,5,1,100,0),(32,'2020-07-05',9,null,35,1,1,999999,0),(33,'2020-07-17',10,null,35,1,1,999999,0),(34,'2020-07-17',11,null,35,1,1,999999,0),(35,'2021-03-17',2,null,45,21,234,300,0),(36,'2021-08-11',5,null,23,7,1,100,0),(37,'2021-08-18',12,null,35,1,1,999999,0),(38,'2021-08-19',12,7,2,22,1,150,1),(39,'2021-08-18',2,5,2,9,1,100,0),(40,'2021-08-18',2,null,21,14,1,100,0),(41,'2021-08-19',2,null,43,15,1,100,0),(42,'2021-08-19',5,null,43,15,1,1000,0),(43,'2021-09-13',2,5,57,16,1000,1999,0),(44,'2021-09-13',2,5,58,17,100,999,0),(45,'2021-09-14',2,5,48,2,1001,1999,0),(46,'2021-09-14',2,5,28,5,10,100,0),(47,'2021-09-14',2,5,17,13,1000,2000,0),(48,'2021-09-14',2,5,18,12,1,100,0),(49,'2021-09-14',2,5,45,6,1,100,0),(50,'2021-09-20',13,null,35,1,1,999999,0),(51,'2021-09-20',14,null,35,1,1,999999,0),(52,'2021-11-17',2,null,70,29,1,50,0),(53,'2021-11-26',2,5,70,29,1,100,0),(54,'2021-12-09',2,null,71,31,1,50,0),(55,'2021-12-21',15,null,35,1,1,999999,0),(56,'2021-12-21',2,null,70,32,100,999,0),(57,'2022-01-03',2,5,78,33,1,100,0),(58,'2022-02-24',2,5,25,34,1,1000,0),(59,'2022-03-03',2,5,43,35,1,10000,0),(60,'2022-04-26',2,5,83,36,1,10000,0),(61,'2022-05-11',2,5,84,37,1,10000,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `referenceserieshistory`;:||:Separator:||:


CREATE TABLE `referenceserieshistory` (
  `idReferenceSeriesHistory` int NOT NULL AUTO_INCREMENT,
  `idReferenceSeries` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  `idCostCenter` int DEFAULT NULL,
  `idModule` int DEFAULT NULL,
  `idReference` int DEFAULT NULL,
  `idReferenceHistory` int DEFAULT NULL,
  `seriesFrom` int DEFAULT NULL,
  `seriesTo` int DEFAULT NULL,
  PRIMARY KEY (`idReferenceSeriesHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `referenceserieshistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `releasing`;:||:Separator:||:


CREATE TABLE `releasing` (
  `idReleasing` int NOT NULL AUTO_INCREMENT,
  `idItem` int DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `qtyLeft` int DEFAULT NULL,
  `cost` decimal(18,2) DEFAULT NULL,
  `price` decimal(18,2) DEFAULT NULL,
  `idInvoice` int DEFAULT NULL,
  `fIDModule` int DEFAULT NULL,
  `fIdent` int DEFAULT NULL,
  `lotNumber` varchar(255) DEFAULT NULL,
  `expiration` date DEFAULT NULL,
  PRIMARY KEY (`idReleasing`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `releasing` WRITE;:||:Separator:||:
 INSERT INTO `releasing` VALUES(1,13,50,50,24.50,24.50,65,null,69,null,'0000-00-00'),(2,13,0,0,24.50,null,68,null,0,null,null),(3,14,10,10,30.00,null,68,null,0,null,null),(4,4,20,20,150.00,null,68,null,0,null,null),(5,13,60,60,24.50,null,71,null,0,null,null),(6,14,40,40,30.00,null,71,null,0,null,null),(7,1,50,25,231.59,100.00,74,null,5,null,'0000-00-00'),(8,14,10,10,30.00,30.00,74,null,70,null,'0000-00-00'),(9,14,100,100,30.00,30.00,74,null,76,null,'0000-00-00'),(10,13,10,10,24.50,245.00,76,null,75,null,null),(13,0,5,null,24.50,null,77,null,75,null,null),(14,0,5,null,0.00,null,77,null,24,null,null),(15,13,1,null,24.50,null,122,null,95,null,null),(16,4,1,null,150.00,null,124,null,94,null,null),(17,4,1,null,150.00,null,126,null,100,null,null),(18,13,1,null,24.50,null,128,null,99,null,null),(19,13,1,null,24.50,null,130,null,102,null,null),(25,13,2,null,24.50,null,132,null,75,null,null),(26,2,1,null,300.00,null,132,null,29,null,null),(30,13,10,10,24.50,24.50,141,null,75,1001,'0000-00-00'),(31,13,1,null,24.50,null,138,null,116,null,null),(32,13,1,null,24.50,null,138,null,116,null,null),(33,14,10,null,30.00,null,138,null,114,null,null),(34,3,100,null,100.00,null,148,null,110,null,null),(35,2,1,1,150.00,500.00,151,null,6,null,'0000-00-00'),(36,2,1,1,150.00,500.00,152,null,6,null,'0000-00-00');:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `releasinghistory`;:||:Separator:||:


CREATE TABLE `releasinghistory` (
  `idReleasingHistory` int NOT NULL AUTO_INCREMENT,
  `idReleasing` int DEFAULT NULL,
  `idItem` int DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `qtyLeft` int DEFAULT NULL,
  `cost` decimal(18,2) DEFAULT '0.00',
  `price` decimal(18,2) DEFAULT '0.00',
  `idInvoice` int DEFAULT NULL,
  `idInvoiceHistory` int DEFAULT NULL,
  `fIDModule` int DEFAULT NULL,
  `fIdent` int DEFAULT NULL,
  PRIMARY KEY (`idReleasingHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `releasinghistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `rental`;:||:Separator:||:


CREATE TABLE `rental` (
  `idRental` int NOT NULL AUTO_INCREMENT,
  `idProject` int DEFAULT NULL,
  `isConstruction` int DEFAULT NULL,
  `striker` int DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `idEmployee` int DEFAULT NULL,
  `dateFrom` date DEFAULT NULL,
  `dateTo` date DEFAULT NULL,
  `idTruckType` int DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `rateType` int DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `totalRate` double DEFAULT NULL,
  `hours` double DEFAULT NULL,
  `trip` double DEFAULT NULL,
  `kilometer` double DEFAULT NULL,
  `mileage` double DEFAULT NULL,
  `fuelLevel` double DEFAULT NULL,
  `fuelUsage` double DEFAULT NULL,
  `status` int DEFAULT NULL,
  `returnDate` date DEFAULT NULL,
  `returnMileage` double DEFAULT NULL,
  `returnFuelLevel` double DEFAULT NULL,
  `penalty` varchar(255) DEFAULT NULL,
  `idInvoice` int DEFAULT NULL,
  `idDriver` int DEFAULT NULL,
  `idTruckProfile` int DEFAULT NULL COMMENT 'Reference for plate number',
  PRIMARY KEY (`idRental`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1 COMMENT='phase 2';:||:Separator:||:


LOCK TABLES `rental` WRITE;:||:Separator:||:
 INSERT INTO `rental` VALUES(1,2,1,1,'Saepe nobis consecte',null,'2021-11-09','2021-12-10',6,'Et error quo fugit ',2,5,7000,null,1400,null,120,100,100,1,null,null,null,null,null,null,null),(2,3,1,1,'Earum dolor a volupt',null,'2021-11-09','2021-12-09',1,'Harum nihil consequa',1,8,12000,1500,null,null,120,100,100,1,null,null,null,null,12,null,null),(3,1,1,1,'Veniam aliqua Vel ',null,'2021-11-13','2021-12-13',6,'Quasi dolores delect',1,4,6000,1500,null,null,100,120,123,1,null,null,null,null,13,null,null),(4,2,1,0,'Id quasi et volupta',null,'2021-11-13','2021-12-13',1,null,2,2,3200,null,1600,null,100,100,0,1,null,null,null,null,14,142,null),(13,2,1,1,'Soluta libero aut ni',null,'2021-11-15','2021-12-15',6,null,2,2,3000,null,2,null,100,100,100,1,null,null,null,null,29,143,null),(14,1,1,1,'Mollit et velit bla',null,'2021-11-14','2021-12-14',5,null,2,5,5200,null,1040,null,100,100,100,1,null,null,null,null,30,143,null),(15,1,1,1,'Tempora dolor ut ips',null,'2021-11-15','2021-12-15',1,null,2,2,2800,null,1400,null,100,100,100,1,null,null,null,null,31,142,null),(16,2,1,0,'Delectus rerum sit ',null,'2021-11-15','2021-12-15',5,null,2,2,5200,null,2,null,100,100,0,1,null,null,null,null,32,142,null),(17,3,1,1,'Et voluptate dolorem',null,'2021-11-15','2021-11-30',6,null,3,34,17000,null,null,500,100,120,130,2,'2021-12-01',20000120,0,'Ea accusantium solut',33,143,null),(18,1,1,0,'Dolorum aspernatur v',null,'2021-11-15','2021-11-30',4,null,2,2,29780,null,14890,null,100,100,0,2,'2021-12-01',20000130,20000123,'Molestiae reprehende',34,142,null),(19,2,1,0,'Labore tenetur nemo ',null,'2021-11-15','2021-11-30',6,null,2,3,3900,null,1300,null,100,120,0,2,'2021-12-01',150,150,'Ut eligendi autem in',35,143,null),(20,3,1,0,'Sequi est illo nost - updated',null,'2021-11-01','2021-11-05',1,null,2,2,7500,null,2,null,100,100,0,1,null,null,null,null,36,142,2),(21,3,1,0,'Voluptas labore fugi',null,'2021-11-15','2021-11-30',6,null,2,3,3000,null,1000,null,100,100,0,2,'2021-12-01',100,100,'Asperiores mollit et',37,143,null),(22,3,1,1,'Sed similique quos n',null,'2021-11-15','2021-12-01',6,null,2,4,24216,null,6054,null,120,2340,4353,1,null,null,null,null,38,143,null),(27,2,1,0,'Tempore soluta opti',null,'2021-11-15','2021-12-15',6,null,1,10,2500,250,null,null,100,100,0,1,null,null,null,null,43,143,null),(28,2,1,1,'Nostrud sunt obcaec',null,'2021-11-15','2021-12-15',5,null,2,2,2800,null,2,null,100,100,0,1,null,null,null,null,44,143,null),(29,5,0,0,'Sample from QA',null,'2021-11-15','2021-12-15',4,null,1,24,12000,500,null,null,100000,1000,0,2,'2021-12-15',200000,500,null,49,143,1),(30,5,0,0,'Sample',null,'2021-12-13','2021-12-15',1,null,2,2,144000,null,2,null,100000000,10000,0,1,null,null,null,null,52,143,13),(31,5,0,0,'This is a test project for TEST 0987 truck profile',null,'2021-12-21','2021-12-21',6,null,1,24,36000,1500,null,null,100000,1000,0,1,null,null,null,null,55,143,17),(32,6,0,0,'New Sample Rental',null,'2021-12-29','2021-12-30',7,null,1,1,24000,24,null,null,100000,19990,0,1,null,null,null,null,88,142,null),(33,1,0,1,'This is a test rental transaction',null,'2022-10-09','2022-10-10',1,null,1,24,12240,510,null,null,18000,1000,500,2,'2022-10-10',20000,1000,'none',204,142,2);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `rentaldeduction`;:||:Separator:||:


CREATE TABLE `rentaldeduction` (
  `idRentalDeduction` int NOT NULL AUTO_INCREMENT,
  `idRental` int DEFAULT NULL,
  `fident` int DEFAULT NULL COMMENT 'This is the idInvoice of the selected reference from Vouchers Payable and Vouchers Receivable',
  `idItem` int DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `price` double DEFAULT NULL,
  `amount` double DEFAULT NULL,
  PRIMARY KEY (`idRentalDeduction`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1 COMMENT='Phase 2';:||:Separator:||:


LOCK TABLES `rentaldeduction` WRITE;:||:Separator:||:
 INSERT INTO `rentaldeduction` VALUES(4,14,19,0,'This item is a Vouchers Receivable transaction.',0,0,1300),(5,14,20,0,'This item is a Vouchers Receivable transaction.',0,0,100),(12,13,null,0,'This item is a Vouchers Receivable transaction.',0,0,100),(13,13,null,0,'This item is a Vouchers Payable transaction.',0,0,1500),(14,13,null,9,null,1,123,123),(15,13,null,10,null,1,123,123),(16,13,null,11,null,2,123,246),(17,15,15,0,'This item is a Vouchers Receivable transaction.',0,0,1678),(18,15,19,0,'This item is a Vouchers Receivable transaction.',0,0,1300),(19,15,20,0,'This item is a Vouchers Receivable transaction.',0,0,100),(25,16,15,0,'This item is a Vouchers Receivable transaction.',0,0,1678),(26,16,20,0,'This item is a Vouchers Receivable transaction.',0,0,100),(27,18,16,0,'This item is a Vouchers Payable transaction.',0,0,1500),(29,21,0,14,null,5,30,150),(30,22,0,10,null,13,123,1599),(35,29,15,0,'This item is a Vouchers Receivable transaction.',0,10,1678),(36,28,18,0,'This item is a Vouchers Payable transaction.',0,0,1345),(38,32,17,0,'This item is a Vouchers Payable transaction.',1,1,1),(39,20,16,0,'This item is a Vouchers Payable transaction.',0,0,1500),(40,20,66,0,'This item is a Vouchers Receivable transaction.',0,0,2450);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `so`;:||:Separator:||:


CREATE TABLE `so` (
  `idSo` int NOT NULL AUTO_INCREMENT,
  `idItem` int DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `qtyLeft` int DEFAULT NULL,
  `cost` decimal(18,2) DEFAULT '0.00',
  `idInvoice` int DEFAULT NULL,
  `fIDModule` int DEFAULT NULL,
  `fIdent` int DEFAULT NULL,
  PRIMARY KEY (`idSo`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `so` WRITE;:||:Separator:||:
 INSERT INTO `so` VALUES(4,5,10,10,2000.00,73,null,null),(5,5,1,0,2000.00,72,null,null),(6,14,500,0,30.00,72,null,null),(7,1,100,50,150.00,72,null,null),(8,13,100,100,24.50,64,null,null),(9,13,10,0,24.50,140,null,null),(10,14,50,0,30.00,140,null,null),(11,17,100,100,10.00,144,null,null),(12,18,1,1,0.00,145,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `sohistory`;:||:Separator:||:


CREATE TABLE `sohistory` (
  `idSoHistory` int NOT NULL AUTO_INCREMENT,
  `idSo` int DEFAULT NULL,
  `idItem` int DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `qtyLeft` int DEFAULT NULL,
  `cost` decimal(18,2) DEFAULT '0.00',
  `idInvoice` int DEFAULT NULL,
  `idInvoiceHistory` int DEFAULT NULL,
  `fIDModule` int DEFAULT NULL,
  `fIdent` int DEFAULT NULL,
  PRIMARY KEY (`idSoHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `sohistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `stocktransfer`;:||:Separator:||:


CREATE TABLE `stocktransfer` (
  `idstockTransfer` int NOT NULL AUTO_INCREMENT,
  `idInvoice` int DEFAULT NULL,
  `idItem` int DEFAULT NULL,
  `qtyTransferred` int DEFAULT NULL,
  `qtyReceived` int DEFAULT NULL,
  PRIMARY KEY (`idstockTransfer`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `stocktransfer` WRITE;:||:Separator:||:
 INSERT INTO `stocktransfer` VALUES(1,22,2,10,0),(5,66,0,100,0),(6,66,0,100,0),(7,66,0,20,0),(11,68,0,100,0),(12,68,0,2000,0),(13,68,0,300,0),(14,70,13,10,0),(17,77,0,5,0),(18,77,0,5,0),(19,122,13,1,0),(20,124,4,1,0),(21,126,4,1,0),(22,128,13,1,0),(23,130,13,1,0),(29,132,13,2,0),(30,132,2,1,0),(34,138,13,1,0),(35,138,13,1,0),(36,138,14,10,0),(37,148,3,100,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `supplier`;:||:Separator:||:


CREATE TABLE `supplier` (
  `idSupplier` int NOT NULL AUTO_INCREMENT,
  `name` text,
  `email` text,
  `contactNumber` text,
  `address` text,
  `tin` text,
  `paymentMethod` int DEFAULT NULL COMMENT '1 - Cash\n2 - Charge',
  `terms` int DEFAULT NULL COMMENT '1 - 30 Days\n2 - 60 Days\n3 - 90 Days\n4 - 120 Days',
  `withCreditLimit` int DEFAULT '0' COMMENT '0 - False\n1 - True',
  `creditLimit` decimal(18,2) DEFAULT '0.00',
  `withVat` int DEFAULT '0' COMMENT '0 - False\\n1 - True',
  `vatType` int DEFAULT NULL COMMENT '1 - Inclusive\n2 - Exclusive',
  `vatPercent` decimal(18,2) DEFAULT '0.00',
  `discount` decimal(18,2) DEFAULT '0.00',
  `withholdingTax` int DEFAULT '0' COMMENT '0 - False\n1 - True',
  `withholdingTaxRate` decimal(18,2) DEFAULT '0.00',
  `expenseGlAcc` int DEFAULT NULL,
  `discountGlAcc` int DEFAULT NULL,
  `archived` int DEFAULT '0',
  `sk` text,
  PRIMARY KEY (`idSupplier`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `supplier` WRITE;:||:Separator:||:
 INSERT INTO `supplier` VALUES(1,'46536e37b70d39da23680f11fd0734fd817d371eedbc6b5cc176af5a6daa7ee167307332d9e3450da73b5800e8fbac52c997615fe8465297ae5828ea1fd784052DwL11NWo35sPjCnHDpjWqq6+v1ukZhXYmOiKRBE2jU=','89d8806c03987488f4784fb83387216bdf7d5885a8d6900644eb0263181427918aa55093002158de4755638bc2f4306530c49813f51696824b27b0966dcba31f33pDlRlw3F8+jCQXUNr4u75CR4pJaBNL/OdBhLN31QAcQa4e7m248eXIVWUZTPc/','f06e3cfd01c7237c8e5a5fe9e51e8c460b63040cbcadbf3c401abc25b46d2120648e0f4b5f4aea4d0a57ccf43fb4011868703f6ec6351f31622a4ce4457d9dadyfRlG7a2E2P3hKkUOOxjgWrasUtx0Vu20SG5U+tdefM=','93f8812d6442e44a6c375d5cf38be4b8301390ff8e237653b3994bc5447977a307d9becdcfd15e4b2b90ebb25fc1ad60598095fed46e99c11b467998a68eba29rmfu99crwuimKvL/WXL83l6DElMyuRd8giS2ws161JE=','9786a35009e26536c51921154ec6467023246c912b8fc30479ad49153a782f1597910a15db0344bfa5e2f9e37eb7f7e4ca0a05d40450ab1d212cef46efad025aYJr1jAuQS2lv7Kn6HvC+HOYL5J988dq9PRvDVSIUhn4=',2,10,0,0.00,0,null,0.00,0.00,0,0.00,null,null,0,192116161232),(2,'5340abcade0288f5449eb3950a7e00f291bc1e7972df63b9eb5f739cb7480ad66abc4e88ea377971fdfd08bde4362aa2838c8bcc72a04b2cf35b3d040bd9d6cdQQFfbQq0Y8SF5m5bdAzESTD+MdRNX9UkVcz5ukuAI+S4BfuY6xR91Da6A2xJkoWq','acfdfe1a579c93b5e988ed39509280a18b820170d476f38d6de7f4e09d1124b248a89b7be040863b1ab828d2d4f26b6af454cf5d8b9f2854071d8d09c3f8b1fd5CQbal307Nx/kFn6HzzAQhVVa/hwPQXpM6J3xFKS/LEPT7hiGWyRtpuO/utkkjtx','3c90e4c335d794f445a97ccf9af6c8a12f3ecbf263aa04c3a2b552b9284c22309266a4d53d2b7ca2d445b17b310c8dfc6e3353cbb0c201cbbfb86ae1c118d2ee5qw/swhzk3pAjLa7mFPkknKyduT0Iw0T9O771674efA=','7637821d5fb949b39554ac4afa6ae0f93941dfa2c21f729a6ca9ddbd89e395dfd388d481a7f184746dd57a22cf7af5d173ac8e4e80d96d8c60b1281ef39020a2E4aQF7TYwcuKOAXv09bNzOrdQIxjamXUG7S0CqJG08w=','1f61861c984108b0ea742879deb6ec99b01d60c3eb69660f6a9ad72261995b87f57d6f6867139c5d439b6e5704cf7889f85316d61578ab7d905ee0d42c2df3f2yWKu9AHMQgLgkcaepvylhwrwDrpZhokK17fuRFCbTNE=',1,null,1,10000.00,0,null,0.00,0.00,0,0.00,5101000,null,0,192116161210),(3,'daf871165a8d3cc2ac7ec318f576e0338cb7e0aada3e53799d32ec5bef07977d5b82ab4c697d21d9935ae9e014515971e1387b5ec76f1981186486a1ba3a4542bZ9m53BRhwlukNSvgRoe4Sd4bNlTDTadlSXbifj5X8XoWG4ijgn8WQDJLdC9U3JM','1cd4806e3c05b469450893a7c35bf42705168a744dc136f40e426ad9de8278b75f70887bb902bec4073023182308ac73caaec3aedefecaafe282ab940da2b4ad+4yPiiIOkyHNYqVwDdubKssCIzNTsgDYEpo70I16E7QExkD14CUtybxuQqL7p8hs','48ad4572f7ee710bde0ea8c65b4d996c784fd6844823fdd09f85aacdfe2314176f9cf2c561434923d83159e497d7f0323330ae863f734e0c43e7eff525a0130aLqnhjt3KkbQ2pp5kdmzw6+PuxK/2vN2lm9DG0DUKPkM=','50c445a409e1f743f41f5f05c5edb9d308d9954a4801cb8dec6c39e382c8db7d3c9526d9c356a15d431b451f1489f0801a61cf8d90aa2a7f420cc5b6e0bc867agEC3LZOOrkoJw5DYTTQcf9N9TMmNX8AUZTyeYGPT/kw=','393af625441a8ee98ecb10b5c1b5a274c64d0f7867d2e6d56210979cb0d93b59bfc9fd726d99848e3f6b0e8d8d2edf7300e9cdae703c89faab87c91857ed270c3st9du5OAPfV/bXl3JouP2Czpw3cEqgBBEQWZ34Fdc4=',1,null,0,0.00,1,1,12.00,0.00,0,0.00,null,null,0,192116161250),(4,'30bf667f37286b88fbc98a7f1a9dd5c2b396ed9465d0d109b93d5ea6decd7ce9f1e0c25a590bfcffdecadaee533db18cf45d12df42ef8562288e49ad1c71d4378GE3IcC5PrLTlVfcjdnzaPv5mPwj3FMrjVvCiFdOodQTsbMyGrJQTuBhub8I2p/5','108a8eab6350a4744159c3dc82f35e652ee1e594d57fc70e2232be437542fb303eb159e8b1f72824812c6aa5be0f8edbd15628331f870d7d06b0ab2c9551880f0TapqprU2TU6FmQ54w2jwRPSYW74+kkniYRWyOq0JGqzSu4qYiuxJ60DixXkPExy','e2e4ac65f37630217bb96dff43610d1edbba68c356af5d326005b886122647eec5a5d439d96a0a4fa7f140c0c7a783a57c8ef9d329f445f1345d1cf8414ee64becUi7HqXtWOA1qQMj5iyTAlBfGuTQwoLGA7oagHiTxc=','2ae5f2f787b0979a6244dcb3ef5fabecbbe0bfa5e4d36c13515f222c1712f133e815a40dda4847441b85c3648fdafed797773e842f645d12f3d98ea7d789d7369I5Q+dzrxQ4bMaImuqA6oC7+We3VJ0kADJvn6M2yMeE=','7a0b439b06f6753098de7123f98c871803ca1c1e488c7f92740bd67bcb42fcb7e92dbbf29a803703237207eaf15c337e12d28184efa1da2f30d0f937fdedf2ddyMJyUAfIPKj0gW2eAEvNkojbD8mhPuQczthVNCV8h6Y=',1,null,0,0.00,1,1,10.00,0.00,0,0.00,5101000,null,0,192116161217),(5,'c6c0a35070a421618b10feeb0149e2b550dd401303f70e1f2ed7f691857ca59b365d0941bed6fa246c8a2b6803fd3cee357f4b9a2e5a19da6d13e1578da564a2GQJqwom3sgAIYaVSGUc94TGx29Or8yLkIZu0N+Nk6p0N6jw3irm2nm9liKGVzuxx','85f5fc3deb3bd9ec0c591327eb5db72ebf7ff0ae5d916dd0f95b8ebb34b17f22380fda34d82a88c31c2c729de50f0a561fbec21e3fbb88a89343e1be4cbb69b4/bx4y0eM6n44hITUpXAFAmFnkxCV/MlK7/8kdsvrWTibKzKU61DzhRzxqMfAdsw7','d8652ee242480eb5f4cde9dcbc6339dc874c42bb9ccad4e05afc329af66578054556243ada6b0e422085d2000b324096d8dc3d4eb4c60a6a8144ba5cb184e992pAnSxook0T7y3oI/KHoTXNthx/+hwZS4P7TQOQiTgdA=','6bbfeb6c3373a5019e566ff3b055a20ac410069699cc2416e2ea6783ab6761dd671e2a177301843e766fe1c02613f4d3db563bac06b2dcc2689345774ad1bfdewTKjVEjLqttiQIybhdoOdPzC+rkqR8pG9NdMPkfpSiA=','7fbfe40e08a69112b59f06d932d8cb118ddbb036d8609beb6ae0aedc37ab124f3e6f34cdb1b5fec8594579be3972d79badea23305c2df3076e4e349a3e7cdf7eWttvG6DpF6DTuWe3dAX4X6Qrg9SDJMNErKe4bOQqHcs=',1,null,0,0.00,0,null,0.00,10.00,0,0.00,5101000,null,0,192116161290),(6,'5f4f7fee212074a60e55230b09076dab3decc7fd138fd793c4260807faf34ceff7059219e4604f7e7ee575e0d3c6003281b49e042a6be6bb87c0eb7ce53a9d90Hr6pju5GAOKfxB7jY2velSELYj4Ch5CKSqYD8VhP9zPSfHSYj1lGDJScDJjRIgAD','a86f6704d4bb20c81cf560a41e38e2b8dc472c66972870ef262040ef04fa5fbf378e99d286a238fff46652409c3eadcd39ffc3118b8a23b0f9a29ce42baad836GBXGfHV+FeeZqgg7aY8PnzVDmnV7/rZ/WUHg1hLKshXe9cRIfqQkw/QEE18QFwlE','d6d4134f299dda10abc40b35fc37598bc38d44e83cb85e00a5c54638daef9b27d6ff88736bee671271e6ddfa25ab4d5dec5dc7e8652e1dbcbfe9e80deda8d081+8crf3+5/AXz/zXcePMZSu5vyB3aGrfrfzsvJjg5nEE=','5a3a78e8b047f2b7df9d16bc8d4c5366aa4649ccaff13413c2e95eb052df4fc627d904c549d8c4dd7aaa94cf4340dd8d61a39da4c433ec45f400aeb222f9da9d59x/4iGurRDhZKCv1NBHEuZbTlpEP5DdLiocfu7JqqY=','f78faa7b62b2dc99dbe2f877d474c57889208c10dcfd1cf7bc97987ba523e276efbe4cd63c894f895b0db2c33bebbc309b31034e8e77bdc514e6c5e63d3a45e6ycPLMfU+eB5Ab6R3AGWLXMECAQhbfeUWhKzux3UhLmQ=',1,null,0,0.00,0,null,0.00,0.00,1,10.00,5101000,null,0,192116161274),(7,'37386671e8ad9295ee18300e56a150187c50c7cdba7b885ad476f1cef37bfc7f77727102bb5e459d7f88da2c2b07743109e5b6d19ab9a76528443d8e8152f6f0bCX+ZZHsQcOMEDfxcmQZqkjdXoLEm7GpgiddY2UdqoU=','a5ef186fe5ca6adaa3c63f5460157fcf8b5265336d1e3bcc626b287bf80fd75b9d249b730ad15b6d1e0c08e61189368c3dde277c63a237649064680fe122f1f9JkgrEeIUZON8clZIukcqncCAhuApEu7OQOd7A9tllcmduLVfWD26w+j6mNL788Xh',0,'b9a943ea77203399ad9aaad1c8d0cd86af310454398cd917c80c7e47c508482abc973403d3c27846e0bccccdd12299026a9982c64a64d02651e7f584c35917d2zMRcpVmVkv5X1nyKCOoqkLsGmpuWs3LtOuiLNFrhRUo=',0,1,null,0,0.00,0,null,0.00,0.00,0,0.00,null,null,0,190113161217),(8,'4c10ced41a0ff8da7a4220eed7968f9aea1fc45cb9b17f41ec4ac78a6c19ecc334e4e2f59c300deb18c8f2d499907b482e1cb26725df0fa0cdb03d5043151cd8X1Fadzz11kOV7BR6MtAIDETHMf0CvoWumqPjdw2Zj4hJbA2Ls0IIwUPUW2aUgIuNkDbLJcTNQBS3JWf2T2gBlVUbKGsxmFB/PKgka5d9j0M=','ba55348cf2a2b61dc594e7e636133d23da95d0e800175727a0ed0524cb3ef1d37bd9cd611c52e5a4ccdb09a18f6901bb5750c0132136ba414628c8d0d74d2344yAT+qXILkwc0orAtPvr8cHmE1iFVs4sesNU+FgTPUVPj3HBG79J471cCsbvdu1T0','fbf6e2a8a0a6614cf55d01954e0b2190ad09ad612fb9fd7b0957c037db552e0a7ef869630f28522b735042eed15ccf1cc2684d0482c96d30e5b7e9966e6ff264/QZJHzDPlxHrYaSM+i43FkT3gey9qUsz5PkolaShxsk=','07cf26069233973674d1b32eff3a0b905cb8f402938389caae37b343f768d69665eca8391d32f5bcb52269b2529bfbe82e466a232ce1c344653d18d0ff059574h/ws7Y1/StI9+Y3uWmQlzEc/OqO/79hud1npYNu78CTRuHqC7edSfAqFw1id2mdZL3M7Q4CMH9f9eA/QVQOS7g==','5e2a3c49c1de08354948e8dcd588a3ff3af04dc74d7f048bdea0687dd9d43b81eed015458a07e2e1a95d698086f2b71dd22b79374103172b2ee7387a597cd74ciNlqtu3tqzW5Z2s56pcM9gVyVvI4eUElXs0VNP3z1xU=',2,45,0,0.00,1,1,12.00,1.00,1,1.00,null,null,0,40122011560),(9,'623f6d604ec3decc9b617de2519e12cd92fb5d4a7aac145c355762986b0e1954792bb8e2c9f5623e4ae28720290080439669ad49131816e7ce5a76401e9d8cf3XTXQr4A4/ShgZ1Q+Vc98mzsEe+sZ6CO2HisdIB/mpZY=','23cccc2d9c52e45e93beac721d50d45d20381f7f0ba2433ae6e3222a0f2c7fff109182722f175ee72ad05adc85d68bc9c18bfefa8030fcf199348a512dc10575APD8feuMPw1Hun8ilHSDvyFFf+gsvCUpEjs8JcOPEX0FhLf7DORTGKyMVMr7HnXt','ad3f8ca6429f4350cf2f996cb19a7b7be7412b87babef417cca13e5a528e5364a03552ccb325034f4abaf1e1e3673af64459b9da54f478015adec07f61a67182tpblqEBTx8y4FkHHQWGfC8AiI6xIUkijDBMwjgvW5zA=','2ae161101aaa58bcfe9b172fbdf11b575f657a520885fb999a68ebf1780fcb65344a86863761c5fcd34766df4b51a6c59f35e4d071d62740173f8db13be9ac45DzJ+uwftiPoh0jGnu9RO0QKTG54l9tUBm/oLg4qE02fLlsFp5Qauj5y4RWN11AD5','f262db1c601054d4cb6ac0e24c29caaf4852ebf13bd0f7596fbc372e860df21f7bae13e00ae90a01e3ddcdc7164698624c7e2629b32a4739283ae65feace3d692GhDqM4mANp8HNoqoqspYQ9oAyzEtmRvWTJnNPsMoVs=',1,null,1,50000.00,0,null,0.00,0.00,0,0.00,null,null,1,130019211642),(10,'43317a2ee20cbb22f3dd119b647da24c85836f795c19efca937857a48612c1bdb18b080624788462efc4c512bf3aeb475ddeb8d20aeafed311002c406667c503vRVpOKRqZvCycFsJXpfSwbWxweCHH2Fygk6otlT+k4k=','3c7bcfd69ac1cf70c97e019af34a9c2cb4e9125c818c0a2fa9123262e4e1a46eddabc8920673e98de87905b9c1d237e7bc917b6c90e79bd84ff05b8e1f62c6500ATdEBzG7uXtgEY2Vuv46vxbA+AKyNcTB3lKX5F4zgj/wVCXPUdA3wYDuu2YqkPn','4683ba9c4a0cf8824ff5dde20857f4920e5c929c80bf3eefee6a59911a0d1fd204f3907d3ecdc76588d651f3aa3649075df8bfc1d5aff9e2b3f895a7c1c67a99Ot+nb4s/HMLbwW3LnF68w+ExOQyK4hCAJAcwnfwzxUQ=','7c7569ba4aa95e53874ac353485a3c59d67a1ae91b55f8933e706599b043d59c306ec60ba390ab79fd069017d8ca35fa8169cc4f52aed88b233e4ddb729dc55cpZM6aBVKomZUiyGWdyNgKMGIv0tTNmiftyRS0v17kErbEg2OjUZJLnleIqKV3/wm','e2ae0d6de58fd11240ca71160a3141cac6778359645ba60d4ad2bf6107a537b4791adbadd883075d8051d1c93c128f0912fd4ae9c86e3a3fa8d83ff777fb2a71feLrJWYYAckOBmFIF/r8b0rn3y66U5hIk1IJAzaOwfg=',1,null,1,50000.00,0,null,0.00,0.00,0,0.00,null,null,0,130019211657),(11,'0d4cb0e492ac774f02fbc052a78715a6e9a7869e89da39838eb8102ae191d7f0e1b09e020881463584e6f5cc1d76c754198b6e65dbf48f1d3a5324767edeaa4778DxnN3sgn2xAI1BhELIlr2gvpo3B9Ala1HjeScdy3o=','b9879c36df93cc93c9a1ff880becedf52e8f4cfb34f9c86bd31189faac1752b18041153d01756e2d9927163bdc7a87f15baf95739943d7942508b3fdcafea620FeSQRB95GzN1WspFwKjnMC+6FeKCqOLU3obr75Viiif6Vy9CGIIdptp8IMOrMdMP','7233e7b8262d7d3fe3ecfc2e8e77c65214d327274ceac3db41bfdf4a27bbb4a9462c39dc5d5d112f22238a10c9a402f25dfede7d8a01c3841952d550fff1be758Zbsr5USi22zqb/67j14u7hGhM/504L2lKd8rRlgBbA=','78161da22c90ac1dcd4e06a9638ece9d926632d0df57ee83bd14c0e7db7920876fa52c100e8838a27dac0e8855a5f52dee73311d42798d6d67f68c28acfc552eCWFw6d/U/PZ+35hhE/VJP+m9IGS1iC1+NdV5cHL3NIqP62+VR3KrXNLqDp1bl9Jn','03246648441719616788c56f9d70171c962846555a41dc30832418dd3ad04f1ce66bf47d62118e10924a01938a1dd0e20363bb0c803d1f819398905cc8335436pRmmZkiefTToJFjYqpERPayMNazJgYny6WM5NFa2TQc=',2,15,1,15000.00,0,null,0.00,10.00,0,0.00,null,null,0,140523001925),(12,'3d3da6e4ed66f9a9d66198e46949b7580943db20a934a4b0ca00ebb0035e269662efa0820f3a85f94962f1691d5db7e680c0159e524ea4225ac7014a89bbce53hskpsahYWpy0egVhGWSxIpbeeLxEGxzOLPpvve/Z8f4=','ca3ad52f008eaeaafed3cdc3c42d626e47f6656d61ae9d58c63de65ace164d74dbfc340902405a8ad35c7f15f3aa1d82840ff2942cbd7083a8a3f08cf243e7d7IWIa+hJjRNxV7Yv7HdWrqB/FUOQsSTjvImqdXoUbyXEFYEpQCSO2So2DpTKHoJBX','c00aba85c5985e077aea0b8e591099e4c8c0bcfd0e4e1095e4598481beb19c58b4a98e88ae374af658b651f60811d8d9a20e780118f528fb868838f8c58ea148B4kjIc2SsHLcS1o+72+Xvi8iqRJjztjKQecw2L5CD6I=','952eca6f51f146dcf5ee285cc9b453dae91bfdce8b42db5367e65d62c2339640f4087e6992b889b0446e568d0210719ca56ec8cc5722a3be7d1d67c0723ba657BwYArwrrpk9Q4noYOx/PwcVS9smiAZx0msaSI257qhcL69kVtUumF5t0aldG+oeJ','1e72e01f6e8be1f49812f19306315c30ff6d57560940ec6959fe42010418c2832b4be28228d8dd5faf060602629f828420562920541d60ee4110f69c93aa33d5OjTxTt1AInMQe47WO6J1ilvhE6jFbyPI6V4QN18f5gc=',1,null,0,0.00,0,null,0.00,0.00,0,0.00,null,null,0,170100192187);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `supplieraffiliate`;:||:Separator:||:


CREATE TABLE `supplieraffiliate` (
  `idSupplierAffiliate` int NOT NULL AUTO_INCREMENT,
  `idSupplier` int DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  `selected` int DEFAULT NULL,
  PRIMARY KEY (`idSupplierAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `supplieraffiliate` WRITE;:||:Separator:||:
 INSERT INTO `supplieraffiliate` VALUES(4,2,2,1),(5,2,5,1),(6,2,4,1),(10,4,2,1),(11,4,5,1),(12,4,4,1),(13,5,2,1),(14,5,5,1),(15,5,4,1),(16,6,2,1),(17,6,5,1),(18,6,4,1),(19,1,2,1),(20,1,5,1),(21,1,4,1),(22,7,6,1),(23,7,8,1),(24,7,2,1),(25,7,5,1),(26,7,4,1),(27,8,9,1),(28,3,2,1),(29,3,5,1),(30,3,4,1),(33,9,12,1),(34,9,2,1),(35,10,12,1),(36,10,2,1),(37,11,6,1),(38,11,12,1),(39,11,2,1),(40,12,12,1),(41,12,2,1);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `supplieraffiliatehistory`;:||:Separator:||:


CREATE TABLE `supplieraffiliatehistory` (
  `idSupplierAffiliateHistory` int NOT NULL AUTO_INCREMENT,
  `idSupplierAffiliate` int DEFAULT NULL,
  `idSupplier` int DEFAULT NULL,
  `idSupplierHistory` int DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  `selected` int DEFAULT NULL,
  PRIMARY KEY (`idSupplierAffiliateHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `supplieraffiliatehistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `supplierhistory`;:||:Separator:||:


CREATE TABLE `supplierhistory` (
  `idSupplierHistory` int NOT NULL AUTO_INCREMENT,
  `idSupplier` int DEFAULT NULL,
  `name` char(50) DEFAULT NULL,
  `email` char(50) DEFAULT NULL,
  `contactNumber` char(20) DEFAULT NULL,
  `address` text,
  `tin` char(11) DEFAULT NULL,
  `paymentMethod` int DEFAULT NULL,
  `terms` int DEFAULT NULL,
  `withCreditLimit` int DEFAULT '0',
  `creditLimit` decimal(18,2) DEFAULT '0.00',
  `withVat` int DEFAULT '0',
  `vatType` int DEFAULT NULL,
  `varPercent` decimal(18,2) DEFAULT '0.00',
  `discount` decimal(18,2) DEFAULT '0.00',
  `withholdingTax` int DEFAULT '0',
  `withholdingTaxRate` decimal(18,2) DEFAULT '0.00',
  `expenseGlAcc` int DEFAULT NULL,
  `dicountGlAcc` int DEFAULT NULL,
  PRIMARY KEY (`idSupplierHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `supplierhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `supplieritems`;:||:Separator:||:


CREATE TABLE `supplieritems` (
  `idSupplierItems` int NOT NULL AUTO_INCREMENT,
  `idSupplier` int DEFAULT NULL,
  `idItem` int DEFAULT NULL,
  PRIMARY KEY (`idSupplierItems`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `supplieritems` WRITE;:||:Separator:||:
 INSERT INTO `supplieritems` VALUES(1,7,4),(2,7,0),(3,11,13),(4,11,5),(5,11,4),(6,11,14),(7,12,14),(8,12,4),(9,12,13);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `supplieritemshistory`;:||:Separator:||:


CREATE TABLE `supplieritemshistory` (
  `idSupplierItemsHistory` int NOT NULL AUTO_INCREMENT,
  `idSupplierItems` int DEFAULT NULL,
  `idSupplier` int DEFAULT NULL,
  `idSupplierHistory` int DEFAULT NULL,
  `idItem` int DEFAULT NULL,
  PRIMARY KEY (`idSupplierItemsHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `supplieritemshistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `tireadditional`;:||:Separator:||:


CREATE TABLE `tireadditional` (
  `idTireAdditional` int NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `recapValue` char(20) NOT NULL,
  `idTireProfile` int NOT NULL,
  PRIMARY KEY (`idTireAdditional`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `tireadditional` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `tireprofile`;:||:Separator:||:


CREATE TABLE `tireprofile` (
  `idTireProfile` int NOT NULL AUTO_INCREMENT,
  `idInvoice` int DEFAULT NULL,
  `serialNumber` int NOT NULL,
  `idTruckProfile` int NOT NULL,
  `idAxle` int DEFAULT NULL,
  `brandName` char(20) DEFAULT NULL,
  `tireSize` char(20) DEFAULT NULL,
  `dateAcquired` date DEFAULT NULL,
  `remarks` varchar(50) DEFAULT NULL,
  `archived` int DEFAULT '0',
  PRIMARY KEY (`idTireProfile`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `tireprofile` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `truckalbum`;:||:Separator:||:


CREATE TABLE `truckalbum` (
  `idPhoto` int NOT NULL AUTO_INCREMENT,
  `idTruckProfile` int DEFAULT NULL,
  `filename` varchar(50) DEFAULT NULL,
  `temp` int DEFAULT '0',
  `idEu` int DEFAULT NULL,
  `position` int DEFAULT NULL,
  `truckalbumcol` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idPhoto`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `truckalbum` WRITE;:||:Separator:||:
 INSERT INTO `truckalbum` VALUES(6,4,'images.png',0,60,0,null),(7,4,'images.png',0,60,0,null),(10,8,'images.png',0,60,0,null),(17,11,'468821940.png',0,60,0,null),(18,11,'162837973.png',0,60,0,null),(19,12,'837992954.png',0,60,0,null),(20,12,'993418463.png',0,60,0,null),(21,9,'648728374.jpg',0,60,0,null),(22,9,'981291329.png',0,60,0,null),(23,9,'363163818.jpg',0,60,0,null),(24,10,'110753181.jpg',0,60,0,null),(25,10,'193238832.png',0,60,0,null),(26,9,'309743984.jpg',0,60,0,null),(27,10,'841311219.jpg',0,60,0,null),(28,10,'669986347.png',0,60,0,null),(29,14,'990933122.png',0,60,0,null),(30,14,'563479921.jpg',0,60,0,null),(31,14,'970605690.jpg',0,60,0,null),(32,10,'959435671.jpg',0,60,0,null),(33,10,'270249074.jpg',0,60,0,null),(34,10,'364681160.jpg',0,60,0,null),(35,15,'823039151.jpg',0,60,0,null),(36,15,'835657551.jpg',0,60,0,null),(37,15,'850851652.jpg',0,60,0,null),(38,19,'948819842.jpg',0,60,0,null),(39,19,'894169988.jpg',0,60,0,null),(40,19,'694140802.jpg',0,60,0,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `truckmaintenance`;:||:Separator:||:


CREATE TABLE `truckmaintenance` (
  `idTruckMaintenance` int NOT NULL AUTO_INCREMENT,
  `idTruckProfile` int DEFAULT NULL,
  `idInvoice` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`idTruckMaintenance`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `truckmaintenance` WRITE;:||:Separator:||:
 INSERT INTO `truckmaintenance` VALUES(11,16,100,'2022-10-10','This is updated'),(14,14,103,'2022-01-17',12312),(17,16,106,'2022-01-17','Sample new truck maintenance, check if duplicated after edit'),(18,16,108,'2022-01-17',null),(19,17,109,'2022-01-24','01-24-2022 New truck maintenance record'),(20,14,110,'2022-01-24',null),(21,14,111,'2022-01-24',null),(22,14,112,'2022-01-24',null),(23,17,113,'2022-01-26',123),(24,17,114,'2022-01-27',null),(26,9,205,'2022-10-10',null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `truckmaintenancefiltersparts`;:||:Separator:||:


CREATE TABLE `truckmaintenancefiltersparts` (
  `idTruckMaintenanceFilters` int NOT NULL AUTO_INCREMENT,
  `idTruckMaintenance` int DEFAULT NULL,
  `filterPartsType` varchar(50) DEFAULT NULL,
  `filterPartName` varchar(50) DEFAULT NULL,
  `dateInstalled` date DEFAULT NULL,
  `mileage` decimal(10,0) DEFAULT NULL,
  `dueDate` date DEFAULT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  `damage` int DEFAULT NULL,
  PRIMARY KEY (`idTruckMaintenanceFilters`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `truckmaintenancefiltersparts` WRITE;:||:Separator:||:
 INSERT INTO `truckmaintenancefiltersparts` VALUES(19,18,0,'oilFilter','2022-01-17',0,'2022-01-17','Sample oil filter',0),(20,18,0,'fuelFilter','2022-01-17',0,'2022-01-17','New fuel filter',0),(21,18,1,'breakePad','2022-01-17',0,'2022-01-17','Sample brake pad',0),(25,19,0,'Oil Filter','2021-10-18',500,'2022-02-08','Sample oil filter change',0),(26,19,0,'Fuel Filter','2021-04-12',100,'2021-07-16','Fuel filter sample replacement record, damaged',1),(27,19,0,'Fuel Filter','2021-07-16',200,'2021-10-25','Fuel filter changed, maintenance',0),(28,19,0,'Fuel Separator Filter','2021-04-07',200,'2021-09-21','New fuel separator installed',0),(29,19,0,'Fuel Separator Filter','2021-09-27',400,'2022-01-24','Fuel separatory changed',1),(30,19,1,'Brake Pad','2021-11-29',500,'2022-02-28','Installed new brake pads',0),(31,19,1,'Battery','2020-02-18',100,'2022-01-03','Battey replacement record',0),(32,19,1,'Battery','2022-01-10',500,'2024-01-22','Damaged battery',1),(34,26,0,'Fuel Filter','2022-08-08',50000,'2022-10-15','This should be replaced before Oct. 15, 2022',1),(35,26,1,'Brake Pad','2022-09-05',0,'2022-12-13','This should be replaced before Dec. 13, 2022',0),(36,11,0,'Oil Filter','2022-08-08',0,'2022-10-10','This is a test',1),(37,11,0,'Oil Filter','2022-10-10',5000,'2022-10-10','This is a test',0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `truckmaintenanceothers`;:||:Separator:||:


CREATE TABLE `truckmaintenanceothers` (
  `idTruckMaintenanceOthers` int NOT NULL AUTO_INCREMENT,
  `idTruckMaintenance` int DEFAULT NULL,
  `maintenanceType` char(50) DEFAULT NULL,
  `description` char(50) DEFAULT NULL,
  `dateChangeInstalled` date DEFAULT NULL,
  `mileage` float DEFAULT NULL,
  `remarks` varchar(200) DEFAULT NULL,
  `damage` int DEFAULT NULL,
  PRIMARY KEY (`idTruckMaintenanceOthers`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `truckmaintenanceothers` WRITE;:||:Separator:||:
 INSERT INTO `truckmaintenanceothers` VALUES(5,18,'Tire replacement',null,'2021-12-07',500,'Sample maintenance',1),(7,19,'Battery replacement',null,'2022-01-17',500,'Changed damaged battery',1),(8,26,null,null,'2022-10-10',0,null,0),(9,26,null,null,'2022-10-10',0,null,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `truckmaintenancetires`;:||:Separator:||:


CREATE TABLE `truckmaintenancetires` (
  `idTruckMaintenanceTires` int NOT NULL AUTO_INCREMENT,
  `idTruckMaintenance` int DEFAULT NULL,
  `idTireProfile` int DEFAULT NULL,
  `mileage` float DEFAULT NULL,
  `thickness` float DEFAULT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  `damage` int DEFAULT '0',
  `original` char(50) DEFAULT NULL,
  `recap` float DEFAULT NULL,
  `number` int DEFAULT NULL,
  `serialNumber` int DEFAULT NULL,
  `dateInstalled` date DEFAULT NULL,
  PRIMARY KEY (`idTruckMaintenanceTires`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `truckmaintenancetires` WRITE;:||:Separator:||:
 INSERT INTO `truckmaintenancetires` VALUES(10,17,null,0,0,null,0,1,1,1000,101212121,'2021-12-13'),(11,18,null,0,500,null,0,1,1,121312312,21312312,'2022-01-18'),(13,19,null,500,123,'Tire 1',0,1,120,1001,1234567890,'2021-11-15'),(14,19,null,500,123,'Tire 2',0,2,123,1002,0,'2022-01-24'),(15,26,null,null,null,null,0,null,null,null,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `truckparts`;:||:Separator:||:


CREATE TABLE `truckparts` (
  `idTruckPart` int NOT NULL AUTO_INCREMENT,
  `truckPartName` char(50) NOT NULL,
  `dueDate` date NOT NULL,
  `dateInstalled` date NOT NULL,
  `idTruckProfile` int NOT NULL,
  PRIMARY KEY (`idTruckPart`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `truckparts` WRITE;:||:Separator:||:
 INSERT INTO `truckparts` VALUES(4,'Grease','2021-12-23','2021-12-24',11),(5,'Water Coolant','2021-12-30','2021-12-31',11),(6,'Grease','2021-12-21','2021-12-22',12),(15,'Sample part - check date','0000-00-00','0000-00-00',10),(16,'sample part - 2','2021-12-20','2021-12-13',10),(17,'Sample Part','2021-12-22','2021-12-06',16),(18,'Sample part','2022-02-15','2021-12-21',17),(20,'Sample part with date','2021-12-21','2021-12-21',15),(21,'Test','2021-12-21','2021-12-21',18),(24,'Sample Part 1','2021-10-20','2021-11-05',14),(25,'Sample Part 2 - without dates','2021-12-21','2021-12-21',14),(27,'Sample Part','2022-10-26','2022-09-05',19),(28,'Sample Part 2','2022-10-10','2022-10-10',19),(29,'Sample Part 1','2021-12-21','2021-12-20',9);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `truckprofile`;:||:Separator:||:


CREATE TABLE `truckprofile` (
  `idTruckProfile` int NOT NULL AUTO_INCREMENT,
  `plateNumber` char(20) NOT NULL,
  `idTruckType` int NOT NULL,
  `axle` int DEFAULT NULL,
  `color` char(20) DEFAULT NULL,
  `dateAcquired` date DEFAULT NULL,
  `dateDeployment` date DEFAULT NULL,
  `status` int DEFAULT NULL,
  `capacity` decimal(10,0) DEFAULT NULL,
  `currentmileage` decimal(10,0) DEFAULT NULL,
  `totalWorkingHours` decimal(10,0) DEFAULT NULL,
  `model` char(1) DEFAULT NULL,
  `make` char(1) DEFAULT NULL,
  `inactive` int DEFAULT NULL,
  `truckFront` text,
  `truckBack` text,
  `truckOR` text,
  `truckCR` text,
  `truckLTFRB` text,
  `insurance` date DEFAULT NULL,
  `registration` date DEFAULT NULL,
  `archived` int DEFAULT '0',
  PRIMARY KEY (`idTruckProfile`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `truckprofile` WRITE;:||:Separator:||:
 INSERT INTO `truckprofile` VALUES(1,'Test123',2,0,'#FFFFFF','2021-12-15','2021-12-15',1,0,0,0,null,null,0,'Screenshot_3.png',null,null,null,null,null,null,1),(2,'ABC 1234',1,0,'#FF6600','2021-08-08','2021-12-15',1,0,0,0,null,null,0,null,null,null,null,null,null,null,1),(3,'ABD 1234',2,0,'#FF6600','2021-12-15','2021-12-20',1,0,0,0,null,null,0,null,null,null,null,null,null,null,1),(4,'LTO123',2,0,'#FFFFFF','2021-12-16','2021-12-16',1,100,100,100,null,null,0,'images.png','images.png',null,null,null,null,null,1),(5,'LTO123test',1,0,'#FFFFFF','2021-12-16','2021-12-16',1,0,0,0,null,null,0,null,null,null,null,null,null,null,1),(6,'LTO123123123',1,0,'#FFFFFF','2021-12-17','2021-12-18',2,123,123,123,1,1,0,null,null,null,null,null,null,null,1),(7,1232312312,1,0,'#FFFFFF','2021-12-16','2021-12-16',1,123,123,123,1,1,0,null,null,null,null,null,null,null,1),(8,123123,5,0,'#FFFFFF','2021-12-16','2021-12-16',1,0,0,0,null,null,0,'123.png',null,null,null,null,null,null,1),(9,'RTV 1234',6,500,'#FF0000','2021-10-18','2021-11-08',2,5000,58700,720,'N','N',0,'502915583.png','771890637.jpg','323008859.png','333827982.png','507735258.png',null,null,1),(10,'NEW 1234',2,0,'#FF0000','2021-12-06','2021-12-20',1,5,100000,720,null,null,0,'264739492.png','777658934.png','243298387.png','364464267.jpg','276200996.png',null,null,1),(11,'TESTLTO123',2,10,'#008000','2021-12-21','2021-12-22',2,100,100,100,1,1,0,'486678330.png','235422073.png','843501214.png',null,null,null,null,1),(12,'LTO123',2,100,'#808000','2021-12-20','2021-12-22',2,100,100,100,1,1,0,'326904076.png','592453733.png',null,null,null,null,null,1),(13,'TestThis',5,0,'#FFFFFF','2021-12-20','2021-12-20',1,0,0,0,null,null,0,null,null,null,null,null,null,null,1),(14,'TEST 1234',5,1000,'#FFFFFF','2021-10-11','2021-12-20',2,1000,1000000,720,'S','S',0,'509753242.png','333568961.png','167790488.png','593875024.png','995821481.jpg',null,null,0),(15,'LPT 1234',7,0,'#FFFFFF','2021-11-15','2021-11-22',1,10000,500000,720,null,null,0,'293743356.png','864793491.png','920349446.png','941433845.png','180136151.jpg',null,null,1),(16,'SAM 1234',6,0,'#FF9900','2021-11-01','2021-11-08',2,5,5000,10,null,null,0,null,null,null,null,null,null,null,0),(17,'TEST 0987',2,0,'#FFFFFF','2021-12-21','2021-12-21',1,0,0,0,null,null,0,null,null,null,null,null,null,null,0),(18,'TestEmptyDate',2,0,'#FFFFFF','2021-12-21','2021-12-21',1,0,0,0,null,null,0,null,null,null,null,null,null,null,1);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `truckproject`;:||:Separator:||:


CREATE TABLE `truckproject` (
  `idTruckProject` int NOT NULL AUTO_INCREMENT,
  `idManual` int DEFAULT NULL,
  `projectName` varchar(50) NOT NULL,
  `idCustomer` int DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `status` int DEFAULT '0',
  `archived` int DEFAULT '0',
  `isManual` int DEFAULT '0',
  PRIMARY KEY (`idTruckProject`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `truckproject` WRITE;:||:Separator:||:
 INSERT INTO `truckproject` VALUES(1,null,'Test',2,'test',0,0,0),(2,null,'Project Sample',4,null,0,1,0),(3,null,'Sample Project to delete',4,null,0,1,0),(4,null,'Sample Project',null,null,0,1,0),(5,null,'Test Projects',10,null,0,0,0),(6,1,'Project w/ Manual ID',4,null,0,0,1),(7,2,'Project w/ Manual ID - 2',10,null,0,1,1),(8,1001,'Sample Project - updated',4,'This is a sample project, updated',0,1,1);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `truckprojectaffiliate`;:||:Separator:||:


CREATE TABLE `truckprojectaffiliate` (
  `idTruckProjectAffiliate` int NOT NULL AUTO_INCREMENT,
  `idTruckProject` int DEFAULT NULL,
  `idAffiliate` int DEFAULT NULL,
  PRIMARY KEY (`idTruckProjectAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `truckprojectaffiliate` WRITE;:||:Separator:||:
 INSERT INTO `truckprojectaffiliate` VALUES(4,1,5),(5,2,2),(6,3,2),(7,4,2),(9,5,2),(10,6,2),(11,7,2),(13,8,2),(14,8,14);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `truckregistrationhistory`;:||:Separator:||:


CREATE TABLE `truckregistrationhistory` (
  `idTruckRegistrationHistory` int NOT NULL AUTO_INCREMENT,
  `registrationCode` char(50) NOT NULL,
  `date` date NOT NULL,
  `idTruckProfile` int NOT NULL,
  PRIMARY KEY (`idTruckRegistrationHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `truckregistrationhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `trucktype`;:||:Separator:||:


CREATE TABLE `trucktype` (
  `idTruckType` int NOT NULL,
  `truckType` char(20) NOT NULL,
  PRIMARY KEY (`idTruckType`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `trucktype` WRITE;:||:Separator:||:
 INSERT INTO `trucktype` VALUES(1,'Dump Truck'),(2,'Tow Truck'),(5,'Semi-trailer Truck'),(6,'Pickup Truck'),(7,'10-wheeler'),(9,'Flatbed truck');:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `unadjusted`;:||:Separator:||:


CREATE TABLE `unadjusted` (
  `idUnadjusted` int NOT NULL AUTO_INCREMENT,
  `idBankRecon` int DEFAULT NULL,
  `idPostdated` int DEFAULT NULL,
  `unadjustedTag` int DEFAULT NULL COMMENT '1 - Add\n2 - Less',
  PRIMARY KEY (`idUnadjusted`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `unadjusted` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `unadjustedhistory`;:||:Separator:||:


CREATE TABLE `unadjustedhistory` (
  `idUnadjustedHistory` int NOT NULL AUTO_INCREMENT,
  `idUnadjusted` int DEFAULT NULL,
  `idBankRecon` int DEFAULT NULL,
  `idPostdated` int DEFAULT NULL,
  `unadjustedTag` int DEFAULT NULL COMMENT '1 - Add\n2 - Less',
  PRIMARY KEY (`idUnadjustedHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `unadjustedhistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `unchecks`;:||:Separator:||:


CREATE TABLE `unchecks` (
  `idUnchecks` int NOT NULL AUTO_INCREMENT,
  `idBankRecon` int DEFAULT NULL,
  `idPostdated` int DEFAULT NULL,
  `status` int DEFAULT NULL COMMENT '1 - Outstanding\n2 - Cleared\n3 - Cancelled\n4 - Bounced',
  `uncheckTag` int DEFAULT NULL COMMENT '1 - Add\n2 - Less',
  PRIMARY KEY (`idUnchecks`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `unchecks` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `uncheckshistory`;:||:Separator:||:


CREATE TABLE `uncheckshistory` (
  `idUnchecksHistory` int NOT NULL AUTO_INCREMENT,
  `idBankRecon` int DEFAULT NULL,
  `idBankReconHistory` int DEFAULT NULL,
  `idPostDated` int DEFAULT NULL,
  `idPostDatedHistory` int DEFAULT NULL,
  `status` int DEFAULT '0',
  `uncheckTag` int DEFAULT '0',
  PRIMARY KEY (`idUnchecksHistory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `uncheckshistory` WRITE;:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `unit`;:||:Separator:||:


CREATE TABLE `unit` (
  `idUnit` int NOT NULL AUTO_INCREMENT,
  `unitCode` char(20) DEFAULT NULL,
  `unitName` char(20) DEFAULT NULL,
  `archived` int DEFAULT '0',
  PRIMARY KEY (`idUnit`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `unit` WRITE;:||:Separator:||:
 INSERT INTO `unit` VALUES(1,'ml','Milliliter',0),(2,'m','Meter',0),(3,'l','Liter',0),(4,'Pc','Pc',1),(5,'kl','Kilos',1),(6,'ciu','classified item\'s un',0),(7,'unit','unit',0),(8,'mg','Milligram',0),(9,'g','Grams',1),(10,'g','Grams',0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
