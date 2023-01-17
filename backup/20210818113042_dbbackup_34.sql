-- DB BACK UP Created on: 08/18/2021 11:30:42

DROP TABLE IF EXISTS `accountbegbal`;:||:Separator:||:


CREATE TABLE `accountbegbal` (
  `idAccBegBal` int(11) NOT NULL AUTO_INCREMENT,
  `idAffiliate` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `idLocation` int(11) DEFAULT NULL,
  `dateModified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idAccBegBal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `accountbegbal` WRITE;:||:Separator:||:


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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `accountbegbalhistory` WRITE;:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `affiliate` WRITE;:||:Separator:||:
 INSERT INTO `affiliate` VALUES(1,'TEST AFFILIATE',null,null,null,null,null,null,0.00,1,null,null,null,null,null,1,null,null,null,1,0,null,null,1,null),(2,'d693c011ad43a46d3144ff5931142f81f405a3af5d7286749e4dd782fbdc3c8d151e7babd5af41f7eb2e31ce548ad87a3e365656cf721e72437de98586337496mDs0F9d0GKwWMZEKjMEKB4aokiH4YKrHdgc8U01yFbFHkOa+HUfkAae8UISLBFdM','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,102,null,'e94645381013bb13bd0985588dbbb3366b09ee76eb43abaff4bcf860af633e84cde6bf822983c4ffd8de012af1573869e13f5627bb7c60ed5da22434d4f42ecbrgQUKTOR4zyhSyGkFfhGYeQm2mHhbfoM7D3OM46gDCs=',4,null,0,null,1,1,null,'2018-03-05',0,192514200159),(3,'678fe40fbb859ea6212d764b48f86009d75840e926028ece5e1fc5ac7b4545797e7864d9b436dd3324fb127a0eb84628117752c05507e9b23c7f39d1055248b3T0anCGQuOYbL4MPsSvXk8OD4Avsa+9VsbDJjlVi+F0c=',null,null,null,null,null,3242534534,0.00,0,null,null,null,null,'483dc437aaa78718e54f4784c04d1f03baa1ee2e2cae2fd75e42d3309563529edd7e2ebe9342923a909977865dd3b24456d033db77d241c98639d2058a24c8feOE6Iq3Uk0o0gA8yOxfaUUsk57RN95NjVXcBHxaEcxHk=',4,null,0,null,1,0,null,'2020-04-22',1,200519200124),(4,'e130f3be78af5b49f263a5c334ccc1228e746216e6cf89a2fd61c4e194bbb49f1ee798227202fa5c5454b5b8ed365d06f4db167aa1898b72f34afd7b7cab03f6o/Yl93+P727oR9cFwQYWYgI82Q+/HHCNnILJ5yugSEk=','Saepe nemo dolores e','Impedit consectetur','Natus',8278569425,'zytuqitase@mailinator.net','Voluptatum ipsam cil',0.00,0,null,null,null,null,'63d1dd1f91cd5c4cf5392060f023cc4bad7223853f8109a942802b9570b3f9252423b8962b3fb8ce1d12c9e2a9d1ecd07f0cc1d7d8e940f29251a6a361b9a66fsfIHpVhmesJydmvwu1kvVUT1lKkuwZrkxUgvNumCVz7qmnjI4KYjyhP3YDfG0uQW',4,'Aspernatur nesciunt',0,null,1,0,null,'2001-07-05',0,030109181565),(5,'d84097f88022aabad6296e54de7e25bf6a0d75db252b5df5a064a8cfbc2f78e85979ed0e1d99e4cad6700ed56f2073b455783f11a1f1a03e63db9df58daae121a5idc3sNOZjWicrVCol5rGzxLe8Q/kOqIu4ApopITzM=',null,null,null,null,null,123456789,0.00,0,null,null,null,null,'fd84e63e2e9335b73c6f3d1a507e8a4216c2aa415ff4cf6258e0515e799bb5e23f48d2dc5f2efc38720c5b28505c66727307c89a3af2e226e5f2231c3f5dd886CGk0qCrQ72+nf/qHn3f/V8jOec7LyyagxVFh3zHV5Bc=',6,null,0,null,1,0,null,'2020-06-05',0,200519200097),(6,'1aea11f3d1eb8af2c6b64af20ab3866bb938488e5675b02793f796c777d7275565a18b12add84dc389b930d22ce5a0deb2d234b4795174098b059d353afc9a2bAtdjL5/TPUIVVa/MUHlUDRP/PCDxLkbwh1fRInkUW3sv1eJzwVFyTihda67p6NxG',null,null,null,null,null,12345,0.00,0,null,null,null,null,'7a97a0e9a9c7d88f621964f97e3298196a27d60e57ddfe229037a39bc9ec6c9d300b1c0e6054bb7d9ba5577dda3ff04f496674048bc7d6b77eaf4e1002b02841LLQlz+A9MZ67LLmCS7dUY+hjF9kzEgA3WOwUVDdIZD4=',7,null,0,null,1,0,null,'2020-07-03',0,190113161277),(7,'4231e3b790f2427dd3c7cf24767d58747c24ab2815d8f1a4c97aa793d32eb631ea992e39893dfda3dcd18bf8b36e16c763f9e3ed127d4653697e7dfa703290b6sYyJFi1VFudqInr96k/5ruxSUkx1bWkzznSYDpke7f6zoNF3mS6oKhKgFEApEZlQ',null,null,null,null,null,123232,0.00,0,null,null,null,null,'39f990a7beba55ce2af2fd52bdbf2b685bd03e8e5f4b4d3afe4b967f8da12dab94894dbac6d9f997d66650e1b61c69829c66aab174bd3795b4f519bdce8437e8axEzuVTdrzegYtPAyeNqe8s2FOgx4tNRcJjOz0D2rnY=',7,null,0,null,1,0,null,'2020-07-03',1,190113161283),(8,'a8961cb8d924ac49e0368608e77c984be84e4281cf048622eaf606270a857c82d797c4e17103f5cf3344cadba087327d21da0f512768be98a0ed2b25a36676feWvucWxyf0hKgbNmHIjbilaCkxKFgVLI3akr/EwmHicA=','This is a tag line','this is an address','Contact my manager',09264148700,'affiliate@gmail.com',19299066,0.00,1,null,null,null,null,'d34d2abcba76a99da706eda968a2e3e9ec5084e2c48741ac7d3f9ec1506e32a00579db13da6e9a0096d901836a7fe7ca74b662d7a19152b8d0da45dd0e2e5abd6RwgmZFxSZh7mCw6i3LYN94ZKXPRvUrUTjRo6uxTyZI=',7,null,0,null,1,0,null,'2020-07-03',0,030415000154),(9,'48f439a95e2d985eb66e06e0df1a2afa23af1df34e9146368ff8a91defbe48912855ad7d1da0a958a0712cca2819dbea72d353ed30bf53bafde6d65c39d6ca362wM+73jFc3giDFhIRlLx+NrJvfvu3PMDSBMDviMX8RGD6c5pOBBZyO2LkTq0Ff1E',null,'Balangay 6, Poblacion, Quezon, Bukidnon','Michelle Emnace',09351338826,null,268024933003,0.00,0,'465381afa08340f458c4e096a4abe6a6a58fa6082ac31e74fee3f30e5231f609e0741b846c4446432bbfa2452b0aa7846903da99344e8cd5f1b11723177cfba1fZdHBbsGjA+D4OED7JrYh8GHr+4T79Ic9WyARWRvUpc=','2e9c9fff3bd8ca5b038f516e65ab7ab8322c7520a3d711da0ea1ea3f419ff354e515c77dda3e4aed2dba91533370f0bd9e4cd8efb0f6862b589dde8f18a25eads6T0duilWJyMcnFg2Ka+ejuUjgFXmwz8hk7JeEeP4WGeOC9VpkYCLYltU4onQ/Gk',null,null,'359d939ecc9f097504c63575f2ffd262454436dbf43bd167ca2bcc43ba354ad8417a727426dd1443543e44e1247955bfafd4f81b48eb30101d0b98055dee3ca2yJzBZtQeh2Wn6uekmzyBIeRKst24U0T/9I5kil3XFxw=',7,null,0,null,1,0,null,'2020-07-05',1,130114210562),(10,'a63f087451e885c27a5b23a153647bb9dccde93414bb7870b65ae34a217742514f456a470cab7db675c3c989289cca3308859db6da243e03e6bd85a37b6b7655qaiHqMG8QPO5hWUi9g1phh2dAaeiA7n+9ik6h+/3nzP9cF/qPI9295IXC/dDy2mOe40B9wHZ0DWjaTt3B5yUHRu5JZTQCue2CXUJkzhSAgw=',null,'Balangay 3, Poblacion, Quezon, Bukidnon','Russel Cañete',09750640023,null,466480615,0.00,0,'40b630eecd676fc576f55fa77829c7fa0ca94e0bea5ce62adcc34dcff548d814d1e0d57f93523f2a110b77368c54d06d8175dab1b6c8a0416fcbc24abebaafd34ArgKRY5KeJVx/1wQjnKBKNjS/vruhn4ZjEjXA+1i/Y=','a553709dfb719181f7a4d5177b72ed6482faece032c7d01a1fb7999e137c0247afd3c98759f70dee99ba1cce33933a7a729f4a93d108e8f5963b41e6789822acvnh6YzQRu1XMgDhIVHgryo8t+ag+X4pn2zSOKfFF+V0=',null,null,'2bb4ddf7c9b77fd02afdfa1a4fefaa53c1540c8a9ff8347b409c00468c4417782b16083ecfc79cad97647608ddc0526ebc4ff43784f780b7c3cbdd3024a21cb6IjA2TeF3EfhLXyIfEM2676avXjqbUC8WpgThrMgBpyM=',7,null,0,'Design 3.png',1,0,null,'2020-07-17',1,110915111559),(11,'e6b91b8ccad9efedf02c1ba8d8af9f6334fc85a3a277e334de0ae2424c9ce814688676129bea9e4cc91e1915c0488238963ade8552560e96d870c52959bf066fc4HCSPVJrpk4GqSeiHu2zoIoATXUrUtvq1TmtwYB5ZgaDoMLH9PXTSHRHIedBu5RpVDY+4At9kS9oh7DiL9SNA==',null,'Balangay 3, Poblacion, Quezon, Bukidnon','Katereen Berly N. Quimod',09176307772,'kfb.kiokongent@yahoo.com.ph',268024933,0.00,0,'f1075d7717a02c0c3452470b96d1e3c3a01437932f4774e4bf88cded43fd76d6977269a1830b14e5503565bb1579623a6228b0fa9af21692f26c3eab5d43d5a1oiFK/DQyvO1Tnw6ZrBfSkSKKazfC8WplWWm5UtDxo3XN5OWpZnlwlovnfOSwoNQn','b5a08fbc684bb160ce690a7aacfb01aa44fb731601a6eebfdc28110e0cde292b667a873a0e39ef28a70293dfc22cc01b11b2be171b455f87163c2a89d5a440accrGW2quS5mr5FkB4LUsP1n9orCEgIsirLm/GfSfyHe23uTVvx508dvOkFWqj40yI',null,null,'7182609bb90cc8e49f1d2f44a391f40d6b1da89b2a84e046febe649e5afa859d7be55d5b256aea813e53433c7fd76044762bd45b3c7468f761c71ad578181524gZHHSioXu4O8sLWud6PS0Vm1qJneb5Ho4tuBqZtu8zo=',7,null,0,'Trucking Logo.png',1,0,null,'2008-12-11',1,110915111574),(12,'a5a4a2a74465718d3e6543c46b69cdb566ea84ffb87c1ff886666802bf633129a8a2b9f5b910977c358bc60a0569127153514cf5a9cc35710cb97091c1dcc646m7mfpJGuI+c6xld3PQz+2DwEUsfAdwliMZlm9pAvESQ=','made for QA regression testing','Sample 123, Cagayan de Oro City','John Doe',09123456789,null,000111222333,0.00,0,null,null,null,null,'9d486bbac3a73a4a74d8ca333ad646d9f590a207e883d40b4916e3faf1f022e58571f269bbabca3a2685bcd99992ddb971995c8daf459f2aa94ba904479c20fb9aSjZulMxxELwSkpjVOBbcGJO3WbRTQA0aXWtpcZQ/Q=',8,null,1,null,2,0,null,'2021-08-18',0,080003151319);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `affiliateapprover`;:||:Separator:||:


CREATE TABLE `affiliateapprover` (
  `idAffiliateApprover` int(11) NOT NULL AUTO_INCREMENT,
  `idAffiliate` int(11) NOT NULL,
  `idEmployee` int(11) NOT NULL,
  `dateEffectivity` datetime DEFAULT NULL,
  PRIMARY KEY (`idAffiliateApprover`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `affiliateapprover` WRITE;:||:Separator:||:
 INSERT INTO `affiliateapprover` VALUES(6,2,102,'2018-03-05 00:00:00'),(7,2,124,'2020-07-03 00:00:00'),(8,8,102,'2020-07-03 00:00:00'),(9,8,124,'2020-07-03 00:00:00'),(10,6,102,'2020-07-03 00:00:00'),(11,9,127,'2020-07-05 00:00:00'),(12,10,127,'2020-07-17 00:00:00'),(14,11,127,'2020-07-17 00:00:00'),(20,12,126,'2021-08-19 00:00:00'),(21,12,125,'2021-08-18 00:00:00');:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `affiliatehistory` WRITE;:||:Separator:||:
 INSERT INTO `affiliatehistory` VALUES(1,2,'c4840320feb519f875126dfbedbef5f2f24842504a76a7b7608ce7f10941878a6708e148574579efc3f8d91c5bed2c2970b16802829decf6bfe704875c093e97nPTkXXz6r/hw95C4c/yj54o+UepUC4NYLqFhDoh3oIflonAuPZOY9H+iF8wO5WJk','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'304e3472907e826d12215c995b020adec9c9812c284996465e906ac51479aaa484b5b13b6d79392d61bc378a4477d377b77f25d4a0e9ac1d3bf58173205f8e9fmvCTPBpm+w6sqdo3LLzfQiXNWG6AWHpJOa9aMdfgZLs=',4,null,0,null,1,1,null,'2018-03-05'),(2,2,'a8f78cf68bcfc6be8745be1ea4e32cf74d0e49ee12c953eab615e6a9b58a6d3c0700b34fcb690a3e47fdf44b74333d723c6c31eca3a99bd3b3c5413dcea26a9bhSCrvwUHOSLqOtCXijqyqgprk6SWBqCSJPqRSMWD9C0+fCTFayT3HPTL1zQJnkCk','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,101,null,'231f3ae4ed001d4c2dc2b1a26cbeab48d923efcb24d1ac8853f310dd402cf3d9137cd4ae5ba701c65d64d70e9d05ae54a013368a874eb1c1579377670bd7c2cbD8mfA6f9pA6OrBJbzOZyOYCgUyvWv48Nfg9M8E+IuzY=',4,null,0,null,1,1,null,'2018-03-05'),(3,2,'64d5e0f3d0023ecdbe491c228b56c6eb3e3f8d7788ba48c0b15314a48da6245ef45cd59a29e2001931faea1f2e5f3652e75ab755a9a15d6472f42cebb328096dnbDE/yJc9NeWR/LFF1ONPY1vsJxjkJ3LC/1Zo7/Z6i9LANK2FAfBYYQdH3sWRHuP','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,105,null,'7553e9e8214f51f93073f443d4bb29271bdaedb178a216f7144b18a818f7012828ea5e9dd6fe2fa7011b620c231a244b21ebbfd02be72fdc573292931940c36cz4Sd0T6svIQM4dZI8PI2Xr1KJq/5DH/+RD+Rxbkanis=',4,null,0,null,1,1,null,'2018-03-05'),(4,2,'96d58052b74ac3b675ef8898b5f07bfc93214e84b288249893360de009260c12e7a72b28d81053df8a89ae3b414502d959ac5e3943f8b2e0b2e767828b01f8122l4SXbAzjatfkiOQkCrpndUrhgUDjy9oEV6GVN2emVoG67+mHC/xIUpZY/NBLmBm','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,102,null,'d756a794ad03967b144eed538a2d2e4a6ef03afb38921f6684639badc34357b05fba6a9642bc1b1f18e25f0daa9f976354abb6a5fa67c17a25a27efc0614e66fOFy7AuLVhR71+JgsyIQjq3x5HSeTIqKACTkWgQQ4bGs=',4,null,0,null,1,1,null,'2018-03-05'),(5,2,'0d7675f9ef62bbf63275e47919355aa79180f6b0a5215076c7f4851b1abcf752e5b41b5e49a3f8595d6282c2ce5738f4925a9afc0025f621d67334798d6580fejRNfE37zKsdylyOG4qjsd5y50AltkYgqWTP+5sfLk3jT0mIHAjUlkUswYX45THwZ','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'60f601d8a9ae3529b1e77b9b9e7d7d1e8856592eebfd8782f51d016bd2c24e72a795820458c1cf713a52d03190dafec33764a45b184489db2cff8195bade8e273vDTEnARSDBfHYd1opFlnGM1JD33TUd8jKptQ+OCpgM=',4,null,0,null,1,1,null,'2018-03-05'),(6,2,'4f7104870cbed9b9ae52798c5426cf2da98a07bf38e40288b2aa764da42773ef48c5d63e043d9cfb985a20a22fe65b6bc18f2cb68de4c1fcbcc7e31c3e6c27643gkN7TcwHoVoqJyqiS9iqyo0ZhF9BDjgFotfL58SI+lgGKAylrRBDPnT8zsemnal','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'f6f00d462a89147e64f970683b3f6b7b43d006725837e9e461f6fe0cefc0b96181dc21de92f1e66459f983eac512d60cde51ca1d17ee6559f0ee3aa7c4ecde82iY3agibJdBd/34ZvgFCmvYu4cbXelStUGrFwgH/nVUU=',4,null,0,null,1,1,null,'2018-03-05'),(7,2,'8e914636a165697fc082a1828db7822aca5c57b1a86369e8c1575a21993de8f6577bc3b1c1fdd67f2d78c0d3e8f3ff67255d7645fcc6ef1b47dec6f50077e864YE4HsxW2ULM6eTyesD/rOym+RKiyLnxjD/UNkxe4RZUBXDq8kZEasJ8Re61rEjJo','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'f1cae5e092a67f9b8369941e9796980c9e2541b4361db964fd29130a2f2154b02cad3b5d87aff891ffaa1539a98cfbc9c63ecd5acd64b2e5bfd28a93ce56baa9z43xae6ndzSABXbCyWTsdj7FN/Wink731+aNFnfoaLo=',4,null,0,null,1,1,null,'2018-03-05'),(8,2,'c288bfc10f2b5f143fd05b7effc5931f2b181fb828a8bf4adc0e383657fc8d88bb029fc677065b2f47bdb6653e0693f23e3fdca8cb44f6347555f140be7a468ea7xna5EUWWBj0mtmReUZ+7wSBKrfJdx2sEWqPLmma/aDdRAf+VXSonCGMjj9TMrx','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'35fa06ddb7f09c80376e231b4b41e1498d883b73df1ffb74ff163db7c96db11b5c3e7eefa4286f25b51afbfe655c1ec7b02d9e50237509075d915cd832afd258BNCnB63HrLPuepIn46ld8zTiYZm+pdrdRxvA0PXNUZ4=',4,null,0,null,1,1,null,'2018-03-05'),(9,8,'6b0781f129b1d706fc9b6a00bc7d2d00d7c4723fb0cf84479354f3344771535b200f2595ce5a1e0328c424364bc22d03baa020e83dfc546f26a932d00476728cQnH9e+g2c0BsfvUfRHS1809M+lH4bRSGA4RyVQzz3Hk=','This is a tag line','this is an address','Contact my manager',09264148700,'affiliate@gmail.com',19299066,0.00,1,null,null,null,null,'d4ba16409797dacfa027d1f3ced5b67bd7fc6f60664cecb77221f5b2aaa2ba9cf5bd027aff44d1690e9379ac2b1e1b0353ee98ebb1f65f11a55000ffc6fe6eberLvrwgTMHXIpVAkoTb71DsoOcou8FuU1ivvhTnpw93M=',7,null,0,null,1,0,null,'2020-07-03'),(10,8,'7bd15d6cbb0a678ec37be542ccf7aaaf37c958b3a056fceea7268a97e8b8b347ba7727487f88c10486122f75932ef51fe5073127e9bc373fdc952cb2735f3820enqBvuUnYSZ5/8lds+vsONb64s50Fb01OoEW8td6jd0=','This is a tag line','this is an address','Contact my manager',09264148700,'affiliate@gmail.com',19299066,0.00,1,null,null,null,null,'0b5c09460e209b9e6a1d7f52934878a7b6d25610f97ee8b91a675a5d66771b2e225b83c75ba9bc539a94d2c9fb7547c217f35512ebac72771f628426b2e2dacfE5tOjPj7a4Jl72OCRUZUW5Qo+hTSjKq9ZvDUI1pEdfc=',7,null,0,null,1,0,null,'2020-07-03'),(11,2,'d693c011ad43a46d3144ff5931142f81f405a3af5d7286749e4dd782fbdc3c8d151e7babd5af41f7eb2e31ce548ad87a3e365656cf721e72437de98586337496mDs0F9d0GKwWMZEKjMEKB4aokiH4YKrHdgc8U01yFbFHkOa+HUfkAae8UISLBFdM','Empowering your Business through IT Solutions.',null,null,null,null,123456789,0.00,0,null,null,null,null,'e94645381013bb13bd0985588dbbb3366b09ee76eb43abaff4bcf860af633e84cde6bf822983c4ffd8de012af1573869e13f5627bb7c60ed5da22434d4f42ecbrgQUKTOR4zyhSyGkFfhGYeQm2mHhbfoM7D3OM46gDCs=',4,null,0,null,1,1,null,'2018-03-05'),(12,8,'a8961cb8d924ac49e0368608e77c984be84e4281cf048622eaf606270a857c82d797c4e17103f5cf3344cadba087327d21da0f512768be98a0ed2b25a36676feWvucWxyf0hKgbNmHIjbilaCkxKFgVLI3akr/EwmHicA=','This is a tag line','this is an address','Contact my manager',09264148700,'affiliate@gmail.com',19299066,0.00,1,null,null,null,null,'d34d2abcba76a99da706eda968a2e3e9ec5084e2c48741ac7d3f9ec1506e32a00579db13da6e9a0096d901836a7fe7ca74b662d7a19152b8d0da45dd0e2e5abd6RwgmZFxSZh7mCw6i3LYN94ZKXPRvUrUTjRo6uxTyZI=',7,null,0,null,1,0,null,'2020-07-03'),(13,6,'23cdaf6c7f754709e4c698a5e441632f69f59c40987a84262d89776a6f0f22806a90d780b002cf14bd1b228b49740eba0c87c5af9475994b9018719ae23abd84H9BSnhd4SkafY5MiC9RHSsrulMJa1IlEJHZfeH+I9ERacjD9nO9bwF8GFmBfefsV',null,null,null,null,null,12345,0.00,0,null,null,null,null,'ea3938530ee96dcc6bce34de564a45ebafc3481086f54bcc2629411c416dd968f9a20c9cd64477d7617faf043b0182095356ce49e69b759d2dc6dd9b2239cca7ac8MfBp1ZbbesGr2YLPL65LOntg2QigfaLXuzwid6AY=',7,null,0,null,1,0,null,'2020-07-03'),(14,6,'1aea11f3d1eb8af2c6b64af20ab3866bb938488e5675b02793f796c777d7275565a18b12add84dc389b930d22ce5a0deb2d234b4795174098b059d353afc9a2bAtdjL5/TPUIVVa/MUHlUDRP/PCDxLkbwh1fRInkUW3sv1eJzwVFyTihda67p6NxG',null,null,null,null,null,12345,0.00,0,null,null,null,null,'7a97a0e9a9c7d88f621964f97e3298196a27d60e57ddfe229037a39bc9ec6c9d300b1c0e6054bb7d9ba5577dda3ff04f496674048bc7d6b77eaf4e1002b02841LLQlz+A9MZ67LLmCS7dUY+hjF9kzEgA3WOwUVDdIZD4=',7,null,0,null,1,0,null,'2020-07-03'),(15,11,'e6b91b8ccad9efedf02c1ba8d8af9f6334fc85a3a277e334de0ae2424c9ce814688676129bea9e4cc91e1915c0488238963ade8552560e96d870c52959bf066fc4HCSPVJrpk4GqSeiHu2zoIoATXUrUtvq1TmtwYB5ZgaDoMLH9PXTSHRHIedBu5RpVDY+4At9kS9oh7DiL9SNA==',null,'Balangay 3, Poblacion, Quezon, Bukidnon','Katereen Berly N. Quimod',09176307772,'kfb.kiokongent@yahoo.com.ph',268024933,0.00,0,'f1075d7717a02c0c3452470b96d1e3c3a01437932f4774e4bf','b5a08fbc684bb160ce690a7aacfb01aa44fb731601a6eebfdc',null,null,'7182609bb90cc8e49f1d2f44a391f40d6b1da89b2a84e046febe649e5afa859d7be55d5b256aea813e53433c7fd76044762bd45b3c7468f761c71ad578181524gZHHSioXu4O8sLWud6PS0Vm1qJneb5Ho4tuBqZtu8zo=',7,null,0,'Trucking Logo.png',1,0,null,'2008-12-11'),(16,12,'da02aaea41469fa17d8cdd70c405e07817ab2fa34f899df416ee0f732bed6991e2a2b5f79b5ee74f507181179b3268ccb0836cef1baa908a6098e3a4469f90cf6cX8YqULpr43rMqJFoCRCH6G6I3+vCVoEFQ+m/ulAic=','made for QA regression testing','Sample 123, Cagayan de Oro City','John Doe',09123456789,null,000111222333,0.00,0,null,null,null,null,'6fd4f8be8b30ea49ed612e24dbe71b290c16d66730e4f027d85c9f11177280c73db37c086d523f5a48437cc19f6dfdd26522e075779cb09b7c0c21c1895cb84fWELxgJsqM9T8mtqPDkbl1x9MQ+RhHi5lvwHh/cQzAjk=',8,null,1,null,2,0,null,'2021-08-18'),(17,12,'df672a82206368f3eb5b9e8b0ea079e46b7a382c184c27f69946514de0e402567a5822c417e77272829831732e7333eb2ad9f64ccc5e60cff8e197bb0d87f459l0Er48OoNLRqDHkjJTyn6iFn1P/dXuGs1mY++2ePjvU=','made for QA regression testing','Sample 123, Cagayan de Oro City','John Doe',09123456789,null,000111222333,0.00,0,null,null,null,null,'a174b83407ea63aee42405bac4f3df01bc2717e2b4064e9cfc476ab337dc220280af6c30707a635b3d0a249fdb2169cf4e39128497ce7a63716fff90cd1450c8VY0J2L02K60KegxBXuQTFURNibwidGK6+oiTX2rokjU=',8,null,1,null,1,0,null,'2021-08-18'),(18,12,'a5a4a2a74465718d3e6543c46b69cdb566ea84ffb87c1ff886666802bf633129a8a2b9f5b910977c358bc60a0569127153514cf5a9cc35710cb97091c1dcc646m7mfpJGuI+c6xld3PQz+2DwEUsfAdwliMZlm9pAvESQ=','made for QA regression testing','Sample 123, Cagayan de Oro City','John Doe',09123456789,null,000111222333,0.00,0,null,null,null,null,'9d486bbac3a73a4a74d8ca333ad646d9f590a207e883d40b4916e3faf1f022e58571f269bbabca3a2685bcd99992ddb971995c8daf459f2aa94ba904479c20fb9aSjZulMxxELwSkpjVOBbcGJO3WbRTQA0aXWtpcZQ/Q=',8,null,1,null,2,0,null,'2021-08-18');:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=5173 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `amodule` WRITE;:||:Separator:||:
 INSERT INTO `amodule` VALUES(30,1,59,0,1,1,1,1,0,0),(31,2,59,0,1,1,1,1,0,0),(32,3,59,5,1,1,1,1,0,0),(33,4,59,5,1,1,1,1,0,0),(34,5,59,5,1,1,1,1,0,0),(35,6,59,0,1,1,1,1,0,0),(36,7,59,5,1,1,1,1,0,0),(37,8,59,4,1,1,1,1,0,0),(38,9,59,5,1,1,1,1,0,0),(39,4,10,5,1,1,1,1,0,0),(40,7,10,5,1,1,1,1,0,0),(41,5,10,5,1,1,1,1,0,0),(42,6,10,5,1,1,1,1,0,0),(43,9,10,5,1,1,1,1,0,0),(44,3,10,5,1,1,1,1,0,0),(45,2,10,1,1,1,1,1,0,0),(46,8,10,4,1,1,1,1,0,0),(82,1,30,0,0,0,0,0,0,0),(118,8,29,4,1,1,1,1,0,0),(129,4,32,5,1,1,1,1,0,0),(130,7,32,5,1,1,1,1,0,0),(131,5,32,5,1,1,1,1,0,0),(132,6,32,5,1,1,1,1,0,0),(133,9,32,5,1,1,1,1,0,0),(134,3,32,5,1,1,1,1,0,0),(155,10,30,4,1,1,1,1,0,0),(156,11,30,4,1,1,1,1,0,0),(157,13,30,4,1,1,1,1,0,0),(158,8,30,4,1,1,1,1,0,0),(159,12,30,4,1,1,1,1,0,0),(164,14,9,1,1,1,1,1,0,0),(165,16,9,1,1,1,1,1,0,0),(166,2,9,1,1,1,1,1,0,0),(167,15,9,1,1,1,1,1,0,0),(173,10,1,4,1,1,1,1,0,0),(174,11,1,4,1,1,1,1,0,0),(175,13,1,4,1,1,1,1,0,0),(176,8,1,4,1,1,1,1,0,0),(177,12,1,4,1,1,1,1,0,0),(222,1,31,0,1,1,1,1,0,0),(228,4,31,5,1,1,1,1,0,0),(229,7,31,5,1,1,1,1,0,0),(230,5,31,5,1,1,1,1,0,0),(231,6,31,5,1,1,1,1,0,0),(232,9,31,5,1,1,1,1,0,0),(233,3,31,5,1,1,1,1,0,0),(240,14,36,1,1,1,1,1,0,0),(241,16,36,1,1,1,1,1,0,0),(242,2,36,1,1,1,1,1,0,0),(243,18,36,1,1,1,1,1,0,0),(244,17,36,1,1,1,1,1,0,0),(245,15,36,1,1,1,1,1,0,0),(246,10,36,4,1,1,1,1,0,0),(247,11,36,4,1,1,1,1,0,0),(248,13,36,4,1,1,1,1,0,0),(249,8,36,4,1,1,1,1,0,0),(250,12,36,4,1,1,1,1,0,0),(251,4,36,5,1,1,1,1,0,0),(252,7,36,5,1,1,1,1,0,0),(253,5,36,5,1,1,1,1,0,0),(254,6,36,5,1,1,1,1,0,0),(255,9,36,5,1,1,1,1,0,0),(256,3,36,5,1,1,1,1,0,0),(257,10,31,4,1,1,1,1,0,0),(258,11,31,4,1,1,1,1,0,0),(259,13,31,4,1,1,1,1,0,0),(260,8,31,4,1,1,1,1,0,0),(261,12,31,4,1,1,1,1,0,0),(262,18,29,1,1,1,1,1,0,0),(614,20,30,2,1,1,1,1,0,0),(615,32,30,2,1,1,1,1,0,0),(616,44,30,2,1,1,1,1,0,0),(617,28,30,2,1,1,1,1,0,0),(618,31,30,2,1,1,1,1,0,0),(619,19,30,2,1,1,1,1,0,0),(620,42,30,2,1,1,1,1,0,0),(621,35,30,2,1,1,1,1,0,0),(622,36,30,2,1,1,1,1,0,0),(623,37,30,2,1,1,1,1,0,0),(624,38,30,2,1,1,1,1,0,0),(625,40,30,2,1,1,1,1,0,0),(626,4,30,5,1,1,1,1,0,0),(627,7,30,5,1,1,1,1,0,0),(628,5,30,5,1,1,1,1,0,0),(629,6,30,5,1,1,1,1,0,0),(630,9,30,5,1,1,1,1,0,0),(631,3,30,5,1,1,1,1,0,0),(729,23,31,1,1,1,1,1,0,0),(730,14,31,1,1,1,1,1,0,0),(731,41,31,1,1,1,1,1,0,0),(732,22,31,1,1,1,1,1,0,0),(733,16,31,1,1,1,1,1,0,0),(734,33,31,1,1,1,1,1,0,0),(735,30,31,1,1,1,1,1,0,0),(736,2,31,1,1,1,1,1,0,0),(737,29,31,1,1,1,1,1,0,0),(738,39,31,1,1,1,1,1,0,0),(739,25,31,1,1,1,1,1,0,0),(740,34,31,1,1,1,1,1,0,0),(741,18,31,1,1,1,1,1,0,0),(742,17,31,1,1,1,1,1,0,0),(743,27,31,1,1,1,1,1,0,0),(744,21,31,1,1,1,1,1,0,0),(745,26,31,1,1,1,1,1,0,0),(746,24,31,1,1,1,1,1,0,0),(747,43,31,1,1,1,1,1,0,0),(748,15,31,1,1,1,1,1,0,0),(761,32,31,2,1,1,1,1,0,0),(762,20,31,2,1,1,1,1,0,0),(763,44,31,2,1,1,1,1,0,0),(764,28,31,2,1,1,1,1,0,0),(765,19,31,2,1,1,1,1,0,0),(766,31,31,2,1,1,1,1,0,0),(767,42,31,2,1,1,1,1,0,0),(768,35,31,2,1,1,1,1,0,0),(769,36,31,2,1,1,1,1,0,0),(770,37,31,2,1,1,1,1,0,0),(771,38,31,2,1,1,1,1,0,0),(772,40,31,2,1,1,1,1,0,0),(793,1,37,0,1,1,1,1,0,0),(807,10,37,4,1,1,1,1,0,0),(808,11,37,4,1,1,1,1,0,0),(809,8,37,4,1,1,1,1,0,0),(810,12,37,4,1,1,1,1,0,0),(811,4,37,5,1,1,1,1,0,0),(812,7,37,5,1,1,1,1,0,0),(813,5,37,5,1,1,1,1,0,0),(814,6,37,5,1,1,1,1,0,0),(815,9,37,5,1,1,1,1,0,0),(816,3,37,5,1,1,1,1,0,0),(906,1,1,0,1,1,1,1,0,0),(948,18,38,1,1,1,1,1,0,0),(949,17,38,1,1,1,1,1,0,0),(950,21,38,1,1,1,1,1,0,0),(951,26,38,1,1,1,1,1,0,0),(952,24,38,1,1,1,1,1,0,0),(953,27,38,1,1,1,1,1,0,0),(1157,51,40,1,1,1,1,1,0,0),(1158,14,40,1,1,1,1,1,0,0),(1159,53,40,1,1,1,1,1,0,0),(1160,41,40,1,1,1,1,1,0,0),(1161,23,40,1,1,1,1,1,0,0),(1162,22,40,1,1,1,1,1,0,0),(1163,16,40,1,1,1,1,1,0,0),(1164,47,40,1,1,1,1,1,0,0),(1165,33,40,1,1,1,1,1,0,0),(1166,49,40,1,1,1,1,1,0,0),(1167,30,40,1,1,1,1,1,0,0),(1168,2,40,1,1,1,1,1,0,0),(1169,29,40,1,1,1,1,1,0,0),(1170,39,40,1,1,1,1,1,0,0),(1171,46,40,1,1,1,1,1,0,0),(1172,54,40,1,1,1,1,1,0,0),(1173,25,40,1,1,1,1,1,0,0),(1174,34,40,1,1,1,1,1,0,0),(1175,52,40,1,1,1,1,1,0,0),(1176,18,40,1,1,1,1,1,0,0),(1177,17,40,1,1,1,1,1,0,0),(1178,21,40,1,1,1,1,1,0,0),(1179,26,40,1,1,1,1,1,0,0),(1180,24,40,1,1,1,1,1,0,0),(1181,27,40,1,1,1,1,1,0,0),(1182,43,40,1,1,1,1,1,0,0),(1183,15,40,1,1,1,1,1,0,0),(1184,48,40,2,1,1,1,1,0,0),(1185,20,40,2,1,1,1,1,0,0),(1186,44,40,2,1,1,1,1,0,0),(1187,28,40,2,1,1,1,1,0,0),(1188,19,40,2,1,1,1,1,0,0),(1189,42,40,2,1,1,1,1,0,0),(1190,35,40,2,1,1,1,1,0,0),(1191,36,40,2,1,1,1,1,0,0),(1192,37,40,2,1,1,1,1,0,0),(1193,45,40,2,1,1,1,1,0,0),(1194,38,40,2,1,1,1,1,0,0),(1195,40,40,2,1,1,1,1,0,0),(1196,57,40,2,1,1,1,1,0,0),(1197,55,40,3,1,1,1,1,0,0),(1198,56,40,3,1,1,1,1,0,0),(1199,50,40,3,1,1,1,1,0,0),(1200,10,40,4,1,1,1,1,0,0),(1201,11,40,4,1,1,1,1,0,0),(1202,8,40,4,1,1,1,1,0,0),(1203,12,40,4,1,1,1,1,0,0),(1204,4,40,5,1,1,1,1,0,0),(1205,7,40,5,1,1,1,1,0,0),(1206,5,40,5,1,1,1,1,0,0),(1207,6,40,5,1,1,1,1,0,0),(1208,9,40,5,1,1,1,1,0,0),(1209,3,40,5,1,1,1,1,0,0),(1211,1,40,0,1,1,1,1,0,0),(1212,51,21,1,1,1,1,1,0,0),(1213,14,21,1,1,1,1,1,0,0),(1214,53,21,1,1,1,1,1,0,0),(1215,41,21,1,1,1,1,1,0,0),(1216,23,21,1,1,1,1,1,0,0),(1217,22,21,1,1,1,1,1,0,0),(1218,16,21,1,1,1,1,1,0,0),(1219,47,21,1,1,1,1,1,0,0),(1220,33,21,1,1,1,1,1,0,0),(1221,49,21,1,1,1,1,1,0,0),(1222,30,21,1,1,1,1,1,0,0),(1223,2,21,1,1,1,1,1,0,0),(1224,29,21,1,1,1,1,1,0,0),(1225,39,21,1,1,1,1,1,0,0),(1226,46,21,1,1,1,1,1,0,0),(1227,54,21,1,1,1,1,1,0,0),(1228,25,21,1,1,1,1,1,0,0),(1229,34,21,1,1,1,1,1,0,0),(1230,52,21,1,1,1,1,1,0,0),(1231,18,21,1,1,1,1,1,0,0),(1232,17,21,1,1,1,1,1,0,0),(1233,21,21,1,1,1,1,1,0,0),(1234,26,21,1,1,1,1,1,0,0),(1235,24,21,1,1,1,1,1,0,0),(1236,27,21,1,1,1,1,1,0,0),(1237,43,21,1,1,1,1,1,0,0),(1238,15,21,1,1,1,1,1,0,0),(1239,51,24,1,1,1,1,1,0,0),(1240,14,24,1,1,1,1,1,0,0),(1241,53,24,1,1,1,1,1,0,0),(1242,41,24,1,1,1,1,1,0,0),(1243,23,24,1,1,1,1,1,0,0),(1244,22,24,1,1,1,1,1,0,0),(1245,16,24,1,1,1,1,1,0,0),(1246,47,24,1,1,1,1,1,0,0),(1247,33,24,1,1,1,1,1,0,0),(1248,49,24,1,1,1,1,1,0,0),(1249,30,24,1,1,1,1,1,0,0),(1250,2,24,1,1,1,1,1,0,0),(1251,29,24,1,1,1,1,1,0,0),(1252,39,24,1,1,1,1,1,0,0),(1253,46,24,1,1,1,1,1,0,0),(1254,54,24,1,1,1,1,1,0,0),(1255,25,24,1,1,1,1,1,0,0),(1256,34,24,1,1,1,1,1,0,0),(1257,52,24,1,1,1,1,1,0,0),(1258,18,24,1,1,1,1,1,0,0),(1259,17,24,1,1,1,1,1,0,0),(1260,21,24,1,1,1,1,1,0,0),(1261,26,24,1,1,1,1,1,0,0),(1262,24,24,1,1,1,1,1,0,0),(1263,27,24,1,1,1,1,1,0,0),(1264,43,24,1,1,1,1,1,0,0),(1265,15,24,1,1,1,1,1,0,0),(1266,51,1,1,1,1,1,1,0,0),(1267,14,1,1,1,1,1,1,0,0),(1268,53,1,1,1,1,1,1,0,0),(1269,41,1,1,1,1,1,1,0,0),(1270,23,1,1,1,1,1,1,0,0),(1271,22,1,1,1,1,1,1,0,0),(1272,16,1,1,1,1,1,1,0,0),(1273,47,1,1,1,1,1,1,0,0),(1274,33,1,1,1,1,1,1,0,0),(1275,49,1,1,1,1,1,1,0,0),(1276,30,1,1,1,1,1,1,0,0),(1277,2,1,1,1,1,1,1,0,0),(1278,2,1,1,1,1,1,1,0,0),(1279,29,1,1,1,1,1,1,0,0),(1280,39,1,1,1,1,1,1,0,0),(1281,46,1,1,1,1,1,1,0,0),(1282,54,1,1,1,1,1,1,0,0),(1283,25,1,1,1,1,1,1,0,0),(1284,34,1,1,1,1,1,1,0,0),(1285,52,1,1,1,1,1,1,0,0),(1286,18,1,1,1,1,1,1,0,0),(1287,17,1,1,1,1,1,1,0,0),(1288,21,1,1,1,1,1,1,0,0),(1289,26,1,1,1,1,1,1,0,0),(1290,24,1,1,1,1,1,1,0,0),(1291,27,1,1,1,1,1,1,0,0),(1292,43,1,1,1,1,1,1,0,0),(1293,15,1,1,1,1,1,1,0,0),(1294,48,1,2,1,1,1,1,0,0),(1295,20,1,2,1,1,1,1,0,0),(1296,44,1,2,1,1,1,1,0,0),(1297,28,1,2,1,1,1,1,0,0),(1298,19,1,2,1,1,1,1,0,0),(1299,42,1,2,1,1,1,1,0,0),(1300,35,1,2,1,1,1,1,0,0),(1301,36,1,2,1,1,1,1,0,0),(1302,37,1,2,1,1,1,1,0,0),(1303,45,1,2,1,1,1,1,0,0),(1304,38,1,2,1,1,1,1,0,0),(1305,40,1,2,1,1,1,1,0,0),(1306,57,1,2,1,1,1,1,0,0),(1307,55,1,3,1,1,1,1,0,0),(1308,56,1,3,1,1,1,1,0,0),(1309,50,1,3,1,1,1,1,0,0),(1310,4,1,5,1,1,1,1,0,0),(1311,7,1,5,1,1,1,1,0,0),(1312,5,1,5,1,1,1,1,0,0),(1313,6,1,5,1,1,1,1,0,0),(1314,9,1,5,1,1,1,1,0,0),(1315,3,1,5,1,1,1,1,0,0),(1501,51,41,1,1,1,1,1,0,0),(1502,14,41,1,1,1,1,1,0,0),(1503,53,41,1,1,1,1,1,0,0),(1504,41,41,1,1,1,1,1,0,0),(1505,23,41,1,1,1,1,1,0,0),(1506,61,41,1,1,1,1,1,0,0),(1507,22,41,1,1,1,1,1,0,0),(1508,59,41,1,1,1,1,1,0,0),(1509,16,41,1,1,1,1,1,0,0),(1510,47,41,1,1,1,1,1,0,0),(1511,33,41,1,1,1,1,1,0,0),(1512,49,41,1,1,1,1,1,0,0),(1513,30,41,1,1,1,1,1,0,0),(1514,2,41,1,1,1,1,1,0,0),(1515,29,41,1,1,1,1,1,0,0),(1516,39,41,1,1,1,1,1,0,0),(1517,46,41,1,1,1,1,1,0,0),(1518,54,41,1,1,1,1,1,0,0),(1519,25,41,1,1,1,1,1,0,0),(1520,34,41,1,1,1,1,1,0,0),(1521,52,41,1,1,1,1,1,0,0),(1522,18,41,1,1,1,1,1,0,0),(1523,17,41,1,1,1,1,1,0,0),(1524,21,41,1,1,1,1,1,0,0),(1525,26,41,1,1,1,1,1,0,0),(1526,24,41,1,1,1,1,1,0,0),(1527,27,41,1,1,1,1,1,0,0),(1528,43,41,1,1,1,1,1,0,0),(1529,15,41,1,1,1,1,1,0,0),(1530,1,41,0,1,1,1,1,0,0),(1531,48,41,2,1,1,1,1,0,0),(1532,66,41,2,1,1,1,1,0,0),(1533,20,41,2,1,1,1,1,0,0),(1534,63,41,2,1,1,1,1,0,0),(1535,44,41,2,1,1,1,1,0,0),(1536,62,41,2,1,1,1,1,0,0),(1537,28,41,2,1,1,1,1,0,0),(1538,19,41,2,1,1,1,1,0,0),(1539,42,41,2,1,1,1,1,0,0),(1540,35,41,2,1,1,1,1,0,0),(1541,36,41,2,1,1,1,1,0,0),(1542,37,41,2,1,1,1,1,0,0),(1543,45,41,2,1,1,1,1,0,0),(1544,38,41,2,1,1,1,1,0,0),(1545,40,41,2,1,1,1,1,0,0),(1546,57,41,2,1,1,1,1,0,0),(1547,58,41,2,1,1,1,1,0,0),(1548,55,41,3,1,1,1,1,0,0),(1549,56,41,3,1,1,1,1,0,0),(1550,64,41,3,1,1,1,1,0,0),(1551,65,41,3,1,1,1,1,0,0),(1552,50,41,3,1,1,1,1,0,0),(1553,60,41,3,1,1,1,1,0,0),(1554,10,41,4,1,1,1,1,0,0),(1555,11,41,4,1,1,1,1,0,0),(1556,8,41,4,1,1,1,1,0,0),(1557,12,41,4,1,1,1,1,0,0),(1558,4,41,5,1,1,1,1,0,0),(1559,7,41,5,1,1,1,1,0,0),(1560,5,41,5,1,1,1,1,0,0),(1561,6,41,5,1,1,1,1,0,0),(1562,9,41,5,1,1,1,1,0,0),(1563,3,41,5,1,1,1,1,0,0),(1624,51,37,1,1,1,1,1,0,0),(1625,67,37,1,1,1,1,1,0,0),(1626,14,37,1,1,1,1,1,0,0),(1627,53,37,1,1,1,1,1,0,0),(1628,41,37,1,1,1,1,1,0,0),(1629,23,37,1,1,1,1,1,0,0),(1630,61,37,1,1,1,1,1,0,0),(1631,22,37,1,1,1,1,1,0,0),(1632,59,37,1,1,1,1,1,0,0),(1633,16,37,1,1,1,1,1,0,0),(1634,47,37,1,1,1,1,1,0,0),(1635,33,37,1,1,1,1,1,0,0),(1636,49,37,1,1,1,1,1,0,0),(1637,30,37,1,1,1,1,1,0,0),(1638,2,37,1,1,1,1,1,0,0),(1639,29,37,1,1,1,1,1,0,0),(1640,39,37,1,1,1,1,1,0,0),(1641,46,37,1,1,1,1,1,0,0),(1642,54,37,1,1,1,1,1,0,0),(1643,25,37,1,1,1,1,1,0,0),(1644,34,37,1,1,1,1,1,0,0),(1645,52,37,1,1,1,1,1,0,0),(1646,18,37,1,1,1,1,1,0,0),(1647,17,37,1,1,1,1,1,0,0),(1648,21,37,1,1,1,1,1,0,0),(1649,26,37,1,1,1,1,1,0,0),(1650,24,37,1,1,1,1,1,0,0),(1651,27,37,1,1,1,1,1,0,0),(1652,43,37,1,1,1,1,1,0,0),(1653,15,37,1,1,1,1,1,0,0),(1654,55,37,3,1,1,1,1,0,0),(1655,56,37,3,1,1,1,1,0,0),(1656,64,37,3,1,1,1,1,0,0),(1657,65,37,3,1,1,1,1,0,0),(1658,50,37,3,1,1,1,1,0,0),(1659,60,37,3,1,1,1,1,0,0),(1690,1,42,0,1,1,1,1,0,0),(1691,48,42,2,1,1,1,1,0,0),(1692,66,42,2,1,1,1,1,0,0),(1693,20,42,2,1,1,1,1,0,0),(1694,63,42,2,1,1,1,1,0,0),(1695,44,42,2,1,1,1,1,0,0),(1696,62,42,2,1,1,1,1,0,0),(1697,28,42,2,1,1,1,1,0,0),(1698,19,42,2,1,1,1,1,0,0),(1699,42,42,2,1,1,1,1,0,0),(1700,35,42,2,1,1,1,1,0,0),(1701,36,42,2,1,1,1,1,0,0),(1702,37,42,2,1,1,1,1,0,0),(1703,45,42,2,1,1,1,1,0,0),(1704,38,42,2,1,1,1,1,0,0),(1705,40,42,2,1,1,1,1,0,0),(1706,57,42,2,1,1,1,1,0,0),(1707,58,42,2,1,1,1,1,0,0),(1708,55,42,3,1,1,1,1,0,0),(1709,56,42,3,1,1,1,1,0,0),(1710,64,42,3,1,1,1,1,0,0),(1711,65,42,3,1,1,1,1,0,0),(1712,50,42,3,1,1,1,1,0,0),(1713,60,42,3,1,1,1,1,0,0),(1718,51,43,1,1,1,1,1,0,0),(1719,67,43,1,1,1,1,1,0,0),(1720,14,43,1,1,1,1,1,0,0),(1721,53,43,1,1,1,1,1,0,0),(1722,41,43,1,1,1,1,1,0,0),(1723,23,43,1,1,1,1,1,0,0),(1724,61,43,1,1,1,1,1,0,0),(1725,22,43,1,1,1,1,1,0,0),(1726,59,43,1,1,1,1,1,0,0),(1727,16,43,1,1,1,1,1,0,0),(1728,47,43,1,1,1,1,1,0,0),(1729,33,43,1,1,1,1,1,0,0),(1730,49,43,1,1,1,1,1,0,0),(1731,30,43,1,1,1,1,1,0,0),(1732,2,43,1,1,1,1,1,0,0),(1733,29,43,1,1,1,1,1,0,0),(1734,39,43,1,1,1,1,1,0,0),(1735,46,43,1,1,1,1,1,0,0),(1736,54,43,1,1,1,1,1,0,0),(1737,25,43,1,1,1,1,1,0,0),(1738,34,43,1,1,1,1,1,0,0),(1739,52,43,1,1,1,1,1,0,0),(1740,18,43,1,1,1,1,1,0,0),(1741,17,43,1,1,1,1,1,0,0),(1742,21,43,1,1,1,1,1,0,0),(1743,26,43,1,1,1,1,1,0,0),(1744,24,43,1,1,1,1,1,0,0),(1745,27,43,1,1,1,1,1,0,0),(1746,43,43,1,1,1,1,1,0,0),(1747,15,43,1,1,1,1,1,0,0),(1748,4,44,5,1,1,1,1,0,0),(1749,7,44,5,1,1,1,1,0,0),(1750,5,44,5,1,1,1,1,0,0),(1751,6,44,5,1,1,1,1,0,0),(1752,3,44,5,1,1,1,1,0,0),(1753,9,44,5,1,1,1,1,0,0),(1754,55,30,3,1,1,1,1,0,0),(1755,56,30,3,1,1,1,1,0,0),(1756,64,30,3,1,1,1,1,0,0),(1757,65,30,3,1,1,1,1,0,0),(1758,50,30,3,1,1,1,1,0,0),(1759,60,30,3,1,1,1,1,0,0),(1760,51,30,1,1,1,1,1,0,0),(1761,67,30,1,1,1,1,1,0,0),(1762,14,30,1,1,1,1,1,0,0),(1763,53,30,1,1,1,1,1,0,0),(1764,41,30,1,1,1,1,1,0,0),(1765,23,30,1,1,1,1,1,0,0),(1766,61,30,1,1,1,1,1,0,0),(1767,22,30,1,1,1,1,1,0,0),(1768,59,30,1,1,1,1,1,0,0),(1769,16,30,1,1,1,1,1,0,0),(1770,47,30,1,1,1,1,1,0,0),(1771,33,30,1,1,1,1,1,0,0),(1772,49,30,1,1,1,1,1,0,0),(1773,30,30,1,1,1,1,1,0,0),(1774,2,30,1,1,1,1,1,0,0),(1775,29,30,1,1,1,1,1,0,0),(1776,39,30,1,1,1,1,1,0,0),(1777,46,30,1,1,1,1,1,0,0),(1778,54,30,1,1,1,1,1,0,0),(1779,25,30,1,1,1,1,1,0,0),(1780,34,30,1,1,1,1,1,0,0),(1781,52,30,1,1,1,1,1,0,0),(1782,18,30,1,1,1,1,1,0,0),(1783,17,30,1,1,1,1,1,0,0),(1784,21,30,1,1,1,1,1,0,0),(1785,26,30,1,1,1,1,1,0,0),(1786,24,30,1,1,1,1,1,0,0),(1787,27,30,1,1,1,1,1,0,0),(1788,43,30,1,1,1,1,1,0,0),(1789,15,30,1,1,1,1,1,0,0),(1839,1,33,0,1,1,1,1,0,0),(1840,48,33,2,1,1,1,1,0,0),(1841,66,33,2,1,1,1,1,0,0),(1842,20,33,2,1,1,1,1,0,0),(1843,63,33,2,1,1,1,1,0,0),(1844,44,33,2,1,1,1,1,0,0),(1845,62,33,2,1,1,1,1,0,0),(1846,28,33,2,1,1,1,1,0,0),(1847,19,33,2,1,1,1,1,0,0),(1848,42,33,2,1,1,1,1,0,0),(1849,35,33,2,1,1,1,1,0,0),(1850,36,33,2,1,1,1,1,0,0),(1851,37,33,2,1,1,1,1,0,0),(1852,45,33,2,1,1,1,1,0,0),(1853,38,33,2,1,1,1,1,0,0),(1854,40,33,2,1,1,1,1,0,0),(1855,69,33,2,1,1,1,1,0,0),(1856,68,33,2,1,1,1,1,0,0),(1857,57,33,2,1,1,1,1,0,0),(1858,58,33,2,1,1,1,1,0,0),(1859,55,33,3,1,1,1,1,0,0),(1860,56,33,3,1,1,1,1,0,0),(1861,64,33,3,1,1,1,1,0,0),(1862,65,33,3,1,1,1,1,0,0),(1863,50,33,3,1,1,1,1,0,0),(1864,60,33,3,1,1,1,1,0,0),(1865,10,33,4,1,1,1,1,0,0),(1866,11,33,4,1,1,1,1,0,0),(1867,8,33,4,1,1,1,1,0,0),(1868,12,33,4,1,1,1,1,0,0),(1869,4,33,5,1,1,1,1,0,0),(1870,7,33,5,1,1,1,1,0,0),(1871,5,33,5,1,1,1,1,0,0),(1872,6,33,5,1,1,1,1,0,0),(1873,3,33,5,1,1,1,1,0,0),(1874,9,33,5,1,1,1,1,0,0),(1875,48,37,2,1,1,1,1,0,0),(1876,66,37,2,1,1,1,1,0,0),(1877,20,37,2,1,1,1,1,0,0),(1878,63,37,2,1,1,1,1,0,0),(1879,44,37,2,1,1,1,1,0,0),(1880,62,37,2,1,1,1,1,0,0),(1881,28,37,2,1,1,1,1,0,0),(1882,19,37,2,1,1,1,1,0,0),(1883,42,37,2,1,1,1,1,0,0),(1884,35,37,2,1,1,1,1,0,0),(1885,36,37,2,1,1,1,1,0,0),(1886,37,37,2,1,1,1,1,0,0),(1887,45,37,2,1,1,1,1,0,0),(1888,38,37,2,1,1,1,1,0,0),(1889,40,37,2,1,1,1,1,0,0),(1890,69,37,2,1,1,1,1,0,0),(1891,68,37,2,1,1,1,1,0,0),(1892,57,37,2,1,1,1,1,0,0),(1893,58,37,2,1,1,1,1,0,0),(1897,10,42,4,1,1,1,1,0,0),(1898,8,42,4,1,1,1,1,0,0),(1928,51,42,1,1,1,1,1,0,0),(1929,67,42,1,1,1,1,1,0,0),(1930,14,42,1,1,1,1,1,0,0),(1931,53,42,1,1,1,1,1,0,0),(1932,41,42,1,1,1,1,1,0,0),(1933,23,42,1,1,1,1,1,0,0),(1934,61,42,1,1,1,1,1,0,0),(1935,22,42,1,1,1,1,1,0,0),(1936,59,42,1,1,1,1,1,0,0),(1937,16,42,1,1,1,1,1,0,0),(1938,47,42,1,1,1,1,1,0,0),(1939,33,42,1,1,1,1,1,0,0),(1940,49,42,1,1,1,1,1,0,0),(1941,30,42,1,1,1,1,1,0,0),(1942,2,42,1,1,1,1,1,0,0),(1943,29,42,1,1,1,1,1,0,0),(1944,39,42,1,1,1,1,1,0,0),(1945,46,42,1,1,1,1,1,0,0),(1946,54,42,1,1,1,1,1,0,0),(1947,25,42,1,1,1,1,1,0,0),(1948,34,42,1,1,1,1,1,0,0),(1949,52,42,1,1,1,1,1,0,0),(1950,18,42,1,1,1,1,1,0,0),(1951,17,42,1,1,1,1,1,0,0),(1952,21,42,1,1,1,1,1,0,0),(1953,26,42,1,1,1,1,1,0,0),(1954,24,42,1,1,1,1,1,0,0),(1955,27,42,1,1,1,1,1,0,0),(1956,43,42,1,1,1,1,1,0,0),(1957,15,42,1,1,1,1,1,0,0),(2075,51,33,1,1,1,1,1,0,0),(2076,67,33,1,1,1,1,1,0,0),(2077,14,33,1,1,1,1,1,0,0),(2078,53,33,1,1,1,1,1,0,0),(2079,41,33,1,1,1,1,1,0,0),(2080,23,33,1,1,1,1,1,0,0),(2081,61,33,1,1,1,1,1,0,0),(2082,22,33,1,1,1,1,1,0,0),(2083,59,33,1,1,1,1,1,0,0),(2084,16,33,1,1,1,1,1,0,0),(2085,47,33,1,1,1,1,1,0,0),(2086,33,33,1,1,1,1,1,0,0),(2087,49,33,1,1,1,1,1,0,0),(2088,30,33,1,1,1,1,1,0,0),(2089,2,33,1,1,1,1,1,0,0),(2090,29,33,1,1,1,1,1,0,0),(2091,39,33,1,1,1,1,1,0,0),(2092,46,33,1,1,1,1,1,0,0),(2093,54,33,1,1,1,1,1,0,0),(2094,25,33,1,1,1,1,1,0,0),(2095,34,33,1,1,1,1,1,0,0),(2096,52,33,1,1,1,1,1,0,0),(2097,18,33,1,1,1,1,1,0,0),(2098,17,33,1,1,1,1,1,0,0),(2099,21,33,1,1,1,1,1,0,0),(2100,26,33,1,1,1,1,1,0,0),(2101,24,33,1,1,1,1,1,0,0),(2102,27,33,1,1,1,1,1,0,0),(2103,43,33,1,1,1,1,1,0,0),(2104,15,33,1,1,1,1,1,0,0),(2164,2,45,1,1,0,0,1,0,0),(2165,8,45,4,1,0,0,1,0,0),(2166,10,45,4,1,0,0,1,0,0),(2167,11,45,4,1,0,0,1,0,0),(2168,12,45,4,1,0,0,1,0,0),(2169,14,45,1,1,0,0,1,0,0),(2170,15,45,1,1,0,0,1,0,0),(2171,16,45,1,1,0,0,1,0,0),(2172,17,45,1,1,0,0,1,0,0),(2173,18,45,1,1,0,0,1,0,0),(2174,19,45,2,1,0,0,1,0,0),(2175,20,45,2,1,0,0,1,0,0),(2176,21,45,1,1,0,0,1,0,0),(2177,22,45,1,1,0,0,1,0,0),(2178,23,45,1,1,0,0,1,0,0),(2179,24,45,1,1,0,0,1,0,0),(2180,25,45,1,1,0,0,1,0,0),(2181,26,45,1,1,0,0,1,0,0),(2182,27,45,1,1,0,0,1,0,0),(2183,28,45,2,1,0,0,1,0,0),(2184,29,45,1,1,0,0,1,0,0),(2185,30,45,1,1,0,0,1,0,0),(2186,33,45,1,1,0,0,1,0,0),(2187,34,45,1,1,0,0,1,0,0),(2188,35,45,2,1,0,0,1,0,0),(2189,36,45,2,1,0,0,1,0,0),(2190,37,45,2,1,0,0,1,0,0),(2191,38,45,2,1,0,0,1,0,0),(2192,39,45,1,1,0,0,1,0,0),(2193,40,45,2,1,0,0,1,0,0),(2194,41,45,1,1,0,0,1,0,0),(2195,42,45,2,1,0,0,1,0,0),(2196,43,45,1,1,0,0,1,0,0),(2197,44,45,2,1,0,0,1,0,0),(2198,45,45,2,1,0,0,1,0,0),(2199,46,45,1,1,0,0,1,0,0),(2200,47,45,1,1,0,0,1,0,0),(2201,48,45,2,1,0,0,1,0,0),(2202,49,45,1,1,0,0,1,0,0),(2203,50,45,3,1,0,0,1,0,0),(2204,51,45,1,1,0,0,1,0,0),(2205,52,45,1,1,0,0,1,0,0),(2206,53,45,1,1,0,0,1,0,0),(2207,54,45,1,1,0,0,1,0,0),(2208,55,45,3,1,0,0,1,0,0),(2209,56,45,3,1,0,0,1,0,0),(2210,57,45,2,1,0,0,1,0,0),(2211,58,45,2,1,0,0,1,0,0),(2212,59,45,1,1,0,0,1,0,0),(2213,60,45,3,1,0,0,1,0,0),(2214,61,45,1,1,0,0,1,0,0),(2215,62,45,2,1,0,0,1,0,0),(2216,63,45,2,1,0,0,1,0,0),(2217,64,45,3,1,0,0,1,0,0),(2218,65,45,3,1,0,0,1,0,0),(2219,66,45,2,1,0,0,1,0,0),(2220,67,45,1,1,0,0,1,0,0),(2221,68,45,2,1,0,0,1,0,0),(2222,69,45,2,1,0,0,1,0,0),(2223,1,46,0,1,1,1,1,0,0),(2224,2,46,1,1,1,1,1,0,0),(2225,3,46,5,1,1,1,1,0,0),(2226,4,46,5,1,1,1,1,0,0),(2227,5,46,5,1,1,1,1,0,0),(2228,6,46,5,1,1,1,1,0,0),(2229,7,46,5,1,1,1,1,0,0),(2230,8,46,4,1,1,1,1,0,0),(2231,9,46,5,1,1,1,1,0,0),(2232,10,46,4,1,1,1,1,0,0),(2233,11,46,4,1,1,1,1,0,0),(2234,12,46,4,1,1,1,1,0,0),(2235,14,46,1,1,1,1,1,0,0),(2236,15,46,1,1,1,1,1,0,0),(2237,16,46,1,1,1,1,1,0,0),(2238,17,46,1,1,1,1,1,0,0),(2239,18,46,1,1,1,1,1,0,0),(2240,19,46,2,1,1,1,1,0,0),(2241,20,46,2,1,1,1,1,0,0),(2242,21,46,1,1,1,1,1,0,0),(2243,22,46,1,1,1,1,1,0,0),(2244,23,46,1,1,1,1,1,0,0),(2245,24,46,1,1,1,1,1,0,0),(2246,25,46,1,1,1,1,1,0,0),(2247,26,46,1,1,1,1,1,0,0),(2248,27,46,1,1,1,1,1,0,0),(2249,28,46,2,1,1,1,1,0,0),(2250,29,46,1,1,1,1,1,0,0),(2251,30,46,1,1,1,1,1,0,0),(2252,33,46,1,1,1,1,1,0,0),(2253,34,46,1,1,1,1,1,0,0),(2254,35,46,2,1,1,1,1,0,0),(2255,36,46,2,1,1,1,1,0,0),(2256,37,46,2,1,1,1,1,0,0),(2257,38,46,2,1,1,1,1,0,0),(2258,39,46,1,1,1,1,1,0,0),(2259,40,46,2,1,1,1,1,0,0),(2260,41,46,1,1,1,1,1,0,0),(2261,42,46,2,1,1,1,1,0,0),(2262,43,46,1,1,1,1,1,0,0),(2263,44,46,2,1,1,1,1,0,0),(2264,45,46,2,1,1,1,1,0,0),(2265,46,46,1,1,1,1,1,0,0),(2266,47,46,1,1,1,1,1,0,0),(2267,48,46,2,1,1,1,1,0,0),(2268,49,46,1,1,1,1,1,0,0),(2269,50,46,3,1,1,1,1,0,0),(2270,51,46,1,1,1,1,1,0,0),(2271,52,46,1,1,1,1,1,0,0),(2272,53,46,1,1,1,1,1,0,0),(2273,54,46,1,1,1,1,1,0,0),(2274,55,46,3,1,1,1,1,0,0),(2275,56,46,3,1,1,1,1,0,0),(2276,57,46,2,1,1,1,1,0,0),(2277,58,46,2,1,1,1,1,0,0),(2278,59,46,1,1,1,1,1,0,0),(2279,60,46,3,1,1,1,1,0,0),(2280,61,46,1,1,1,1,1,0,0),(2281,62,46,2,1,1,1,1,0,0),(2282,63,46,2,1,1,1,1,0,0),(2283,64,46,3,1,1,1,1,0,0),(2284,65,46,3,1,1,1,1,0,0),(2285,66,46,2,1,1,1,1,0,0),(2286,67,46,1,1,1,1,1,0,0),(2287,68,46,2,1,1,1,1,0,0),(2288,69,46,2,1,1,1,1,0,0),(2289,2,47,1,1,0,0,1,0,0),(2290,8,47,4,1,0,0,1,0,0),(2291,10,47,4,1,0,0,1,0,0),(2292,11,47,4,1,0,0,1,0,0),(2293,12,47,4,1,0,0,1,0,0),(2294,14,47,1,1,0,0,1,0,0),(2295,15,47,1,1,0,0,1,0,0),(2296,16,47,1,1,0,0,1,0,0),(2297,17,47,1,1,0,0,1,0,0),(2298,18,47,1,1,0,0,1,0,0),(2299,19,47,2,1,0,0,1,0,0),(2300,20,47,2,1,0,0,1,0,0),(2301,21,47,1,1,0,0,1,0,0),(2302,22,47,1,1,0,0,1,0,0),(2303,23,47,1,1,0,0,1,0,0),(2304,24,47,1,1,0,0,1,0,0),(2305,25,47,1,1,0,0,1,0,0),(2306,26,47,1,1,0,0,1,0,0),(2307,27,47,1,1,0,0,1,0,0),(2308,28,47,2,1,0,0,1,0,0),(2309,29,47,1,1,0,0,1,0,0),(2310,30,47,1,1,0,0,1,0,0),(2311,33,47,1,1,0,0,1,0,0),(2312,34,47,1,1,0,0,1,0,0),(2313,35,47,2,1,0,0,1,0,0),(2314,36,47,2,1,0,0,1,0,0),(2315,37,47,2,1,0,0,1,0,0),(2316,38,47,2,1,0,0,1,0,0),(2317,39,47,1,1,0,0,1,0,0),(2318,40,47,2,1,0,0,1,0,0),(2319,41,47,1,1,0,0,1,0,0),(2320,42,47,2,1,0,0,1,0,0),(2321,43,47,1,1,0,0,1,0,0),(2322,44,47,2,1,0,0,1,0,0),(2323,45,47,2,1,0,0,1,0,0),(2324,46,47,1,1,0,0,1,0,0),(2325,47,47,1,1,0,0,1,0,0),(2326,48,47,2,1,0,0,1,0,0),(2327,49,47,1,1,0,0,1,0,0),(2328,50,47,3,1,0,0,1,0,0),(2329,51,47,1,1,0,0,1,0,0),(2330,52,47,1,1,0,0,1,0,0),(2331,53,47,1,1,0,0,1,0,0),(2332,54,47,1,1,0,0,1,0,0),(2333,55,47,3,1,0,0,1,0,0),(2334,56,47,3,1,0,0,1,0,0),(2335,57,47,2,1,0,0,1,0,0),(2336,58,47,2,1,0,0,1,0,0),(2337,59,47,1,1,0,0,1,0,0),(2338,60,47,3,1,0,0,1,0,0),(2339,61,47,1,1,0,0,1,0,0),(2340,62,47,2,1,0,0,1,0,0),(2341,63,47,2,1,0,0,1,0,0),(2342,64,47,3,1,0,0,1,0,0),(2343,65,47,3,1,0,0,1,0,0),(2344,66,47,2,1,0,0,1,0,0),(2345,67,47,1,1,0,0,1,0,0),(2346,68,47,2,1,0,0,1,0,0),(2347,69,47,2,1,0,0,1,0,0),(2348,1,48,0,1,1,1,1,0,0),(2349,2,48,1,1,1,1,1,0,0),(2350,3,48,5,1,1,1,1,0,0),(2351,4,48,5,1,1,1,1,0,0),(2352,5,48,5,1,1,1,1,0,0),(2353,6,48,5,1,1,1,1,0,0),(2354,7,48,5,1,1,1,1,0,0),(2355,8,48,4,1,1,1,1,0,0),(2356,9,48,5,1,1,1,1,0,0),(2357,10,48,4,1,1,1,1,0,0),(2358,11,48,4,1,1,1,1,0,0),(2359,12,48,4,1,1,1,1,0,0),(2360,14,48,1,1,1,1,1,0,0),(2361,15,48,1,1,1,1,1,0,0),(2362,16,48,1,1,1,1,1,0,0),(2363,17,48,1,1,1,1,1,0,0),(2364,18,48,1,1,1,1,1,0,0),(2365,19,48,2,1,1,1,1,0,0),(2366,20,48,2,1,1,1,1,0,0),(2367,21,48,1,1,1,1,1,0,0),(2368,22,48,1,1,1,1,1,0,0),(2369,23,48,1,1,1,1,1,0,0),(2370,24,48,1,1,1,1,1,0,0),(2371,25,48,1,1,1,1,1,0,0),(2372,26,48,1,1,1,1,1,0,0),(2373,27,48,1,1,1,1,1,0,0),(2374,28,48,2,1,1,1,1,0,0),(2375,29,48,1,1,1,1,1,0,0),(2376,30,48,1,1,1,1,1,0,0),(2377,33,48,1,1,1,1,1,0,0),(2378,34,48,1,1,1,1,1,0,0),(2379,35,48,2,1,1,1,1,0,0),(2380,36,48,2,1,1,1,1,0,0),(2381,37,48,2,1,1,1,1,0,0),(2382,38,48,2,1,1,1,1,0,0),(2383,39,48,1,1,1,1,1,0,0),(2384,40,48,2,1,1,1,1,0,0),(2385,41,48,1,1,1,1,1,0,0),(2386,42,48,2,1,1,1,1,0,0),(2387,43,48,1,1,1,1,1,0,0),(2388,44,48,2,1,1,1,1,0,0),(2389,45,48,2,1,1,1,1,0,0),(2390,46,48,1,1,1,1,1,0,0),(2391,47,48,1,1,1,1,1,0,0),(2392,48,48,2,1,1,1,1,0,0),(2393,49,48,1,1,1,1,1,0,0),(2394,50,48,3,1,1,1,1,0,0),(2395,51,48,1,1,1,1,1,0,0),(2396,52,48,1,1,1,1,1,0,0),(2397,53,48,1,1,1,1,1,0,0),(2398,54,48,1,1,1,1,1,0,0),(2399,55,48,3,1,1,1,1,0,0),(2400,56,48,3,1,1,1,1,0,0),(2401,57,48,2,1,1,1,1,0,0),(2402,58,48,2,1,1,1,1,0,0),(2403,59,48,1,1,1,1,1,0,0),(2404,60,48,3,1,1,1,1,0,0),(2405,61,48,1,1,1,1,1,0,0),(2406,62,48,2,1,1,1,1,0,0),(2407,63,48,2,1,1,1,1,0,0),(2408,64,48,3,1,1,1,1,0,0),(2409,65,48,3,1,1,1,1,0,0),(2410,66,48,2,1,1,1,1,0,0),(2411,67,48,1,1,1,1,1,0,0),(2412,68,48,2,1,1,1,1,0,0),(2413,69,48,2,1,1,1,1,0,0),(3695,1,34,0,1,1,1,1,0,0),(3697,3,34,5,1,1,1,1,0,0),(3698,4,34,5,1,1,1,1,0,0),(3699,5,34,5,1,1,1,1,0,0),(3700,6,34,5,1,1,1,1,0,0),(3701,7,34,5,1,1,1,1,0,0),(3702,8,34,4,1,1,1,1,0,0),(3703,9,34,5,1,1,1,1,0,0),(3704,10,34,4,1,1,1,1,0,0),(3705,11,34,4,1,1,1,1,0,0),(3706,12,34,4,1,1,1,1,0,0),(3712,19,34,2,1,1,1,1,0,0),(3713,20,34,2,1,1,1,1,0,0),(3721,28,34,2,1,1,1,1,0,0),(3726,35,34,2,1,1,1,1,0,0),(3727,36,34,2,1,1,1,1,0,0),(3728,37,34,2,1,1,1,1,0,0),(3729,38,34,2,1,1,1,1,0,0),(3731,40,34,2,1,1,1,1,0,0),(3733,42,34,2,1,1,1,1,0,0),(3735,44,34,2,1,1,1,1,0,0),(3736,45,34,2,1,1,1,1,0,0),(3739,48,34,2,1,1,1,1,0,0),(3741,50,34,3,1,1,1,1,0,0),(3746,55,34,3,1,1,1,1,0,0),(3747,56,34,3,1,1,1,1,0,0),(3748,57,34,2,1,1,1,1,0,0),(3749,58,34,2,1,1,1,1,0,0),(3751,60,34,3,1,1,1,1,0,0),(3753,62,34,2,1,1,1,1,0,0),(3754,63,34,2,1,1,1,1,0,0),(3755,64,34,3,1,1,1,1,0,0),(3756,65,34,3,1,1,1,1,0,0),(3757,66,34,2,1,1,1,1,0,0),(3759,68,34,2,1,1,1,1,0,0),(3760,69,34,2,1,1,1,1,0,0),(3851,51,34,1,1,1,1,1,0,0),(3852,67,34,1,1,1,1,1,0,0),(3853,14,34,1,1,1,1,1,0,0),(3854,53,34,1,1,1,1,1,0,0),(3855,18,34,1,1,1,1,1,0,0),(3856,41,34,1,1,1,1,1,0,0),(3857,23,34,1,1,1,1,1,0,0),(3858,61,34,1,1,1,1,1,0,0),(3859,22,34,1,1,1,1,1,0,0),(3860,59,34,1,1,1,1,1,0,0),(3861,16,34,1,1,1,1,1,0,0),(3862,47,34,1,1,1,1,1,0,0),(3863,33,34,1,1,1,1,1,0,0),(3864,49,34,1,1,1,1,1,0,0),(3865,30,34,1,1,1,1,1,0,0),(3866,2,34,1,1,1,1,1,1,0),(3867,29,34,1,1,1,1,1,0,0),(3868,39,34,1,1,1,1,1,0,0),(3869,46,34,1,1,1,1,1,0,0),(3870,54,34,1,1,1,1,1,0,0),(3871,25,34,1,1,1,1,1,0,0),(3872,34,34,1,1,1,1,1,0,0),(3873,52,34,1,1,1,1,1,0,0),(3874,17,34,1,1,1,1,1,0,0),(3875,21,34,1,1,1,1,1,0,0),(3876,26,34,1,1,1,1,1,0,0),(3877,24,34,1,1,1,1,1,0,0),(3878,27,34,1,1,1,1,1,0,0),(3879,43,34,1,1,1,1,1,0,0),(3880,15,34,1,1,1,1,1,0,0),(4006,1,51,0,1,1,1,1,0,0),(4007,2,51,1,1,1,1,1,0,0),(4008,3,51,5,1,1,1,1,0,0),(4009,4,51,5,1,1,1,1,0,0),(4010,5,51,5,1,1,1,1,0,0),(4011,6,51,5,1,1,1,1,0,0),(4012,7,51,5,1,1,1,1,0,0),(4013,8,51,4,1,1,1,1,0,0),(4014,9,51,5,1,1,1,1,0,0),(4015,10,51,4,1,1,1,1,0,0),(4016,11,51,4,1,1,1,1,0,0),(4017,12,51,4,1,1,1,1,0,0),(4018,14,51,1,1,1,1,1,0,0),(4019,15,51,1,1,1,1,1,0,0),(4020,16,51,1,1,1,1,1,0,0),(4021,17,51,1,1,1,1,1,0,0),(4022,18,51,1,1,1,1,1,0,0),(4023,19,51,2,1,1,1,1,0,0),(4024,20,51,2,1,1,1,1,0,0),(4025,21,51,1,1,1,1,1,0,0),(4026,22,51,1,1,1,1,1,0,0),(4027,23,51,1,1,1,1,1,0,0),(4028,24,51,1,1,1,1,1,0,0),(4029,25,51,1,1,1,1,1,0,0),(4030,26,51,1,1,1,1,1,0,0),(4031,27,51,1,1,1,1,1,0,0),(4032,28,51,2,1,1,1,1,0,0),(4033,29,51,1,1,1,1,1,0,0),(4034,30,51,1,1,1,1,1,0,0),(4035,33,51,1,1,1,1,1,0,0),(4036,34,51,1,1,1,1,1,0,0),(4037,35,51,2,1,1,1,1,0,0),(4038,36,51,2,1,1,1,1,0,0),(4039,37,51,2,1,1,1,1,0,0),(4040,38,51,2,1,1,1,1,0,0),(4041,39,51,1,1,1,1,1,0,0),(4042,40,51,2,1,1,1,1,0,0),(4043,41,51,1,1,1,1,1,0,0),(4044,42,51,2,1,1,1,1,0,0),(4045,43,51,1,1,1,1,1,0,0),(4046,44,51,2,1,1,1,1,0,0),(4047,45,51,2,1,1,1,1,0,0),(4048,46,51,1,1,1,1,1,0,0),(4049,47,51,1,1,1,1,1,0,0),(4050,48,51,2,1,1,1,1,0,0),(4051,49,51,1,1,1,1,1,0,0),(4052,50,51,3,1,1,1,1,0,0),(4053,51,51,1,1,1,1,1,0,0),(4054,52,51,1,1,1,1,1,0,0),(4055,53,51,1,1,1,1,1,0,0),(4056,54,51,1,1,1,1,1,0,0),(4057,55,51,3,1,1,1,1,0,0),(4058,56,51,3,1,1,1,1,0,0),(4059,57,51,2,1,1,1,1,0,0),(4060,58,51,2,1,1,1,1,0,0),(4061,59,51,1,1,1,1,1,0,0),(4062,60,51,3,1,1,1,1,0,0),(4063,61,51,1,1,1,1,1,0,0),(4064,62,51,2,1,1,1,1,0,0),(4065,63,51,2,1,1,1,1,0,0),(4066,64,51,3,1,1,1,1,0,0),(4067,65,51,3,1,1,1,1,0,0),(4068,66,51,2,1,1,1,1,0,0),(4069,67,51,1,1,1,1,1,0,0),(4070,68,51,2,1,1,1,1,0,0),(4071,69,51,2,1,1,1,1,0,0),(4072,2,52,1,1,0,0,1,0,0),(4073,8,52,4,1,0,0,1,0,0),(4074,10,52,4,1,0,0,1,0,0),(4075,11,52,4,1,0,0,1,0,0),(4076,12,52,4,1,0,0,1,0,0),(4077,14,52,1,1,0,0,1,0,0),(4078,15,52,1,1,0,0,1,0,0),(4079,16,52,1,1,0,0,1,0,0),(4080,17,52,1,1,0,0,1,0,0),(4081,18,52,1,1,0,0,1,0,0),(4082,19,52,2,1,0,0,1,0,0),(4083,20,52,2,1,0,0,1,0,0),(4084,21,52,1,1,0,0,1,0,0),(4085,22,52,1,1,0,0,1,0,0),(4086,23,52,1,1,0,0,1,0,0),(4087,24,52,1,1,0,0,1,0,0),(4088,25,52,1,1,0,0,1,0,0),(4089,26,52,1,1,0,0,1,0,0),(4090,27,52,1,1,0,0,1,0,0),(4091,28,52,2,1,0,0,1,0,0),(4092,29,52,1,1,0,0,1,0,0),(4093,30,52,1,1,0,0,1,0,0),(4094,33,52,1,1,0,0,1,0,0),(4095,34,52,1,1,0,0,1,0,0),(4096,35,52,2,1,0,0,1,0,0),(4097,36,52,2,1,0,0,1,0,0),(4098,37,52,2,1,0,0,1,0,0),(4099,38,52,2,1,0,0,1,0,0),(4100,39,52,1,1,0,0,1,0,0),(4101,40,52,2,1,0,0,1,0,0),(4102,41,52,1,1,0,0,1,0,0),(4103,42,52,2,1,0,0,1,0,0),(4104,43,52,1,1,0,0,1,0,0),(4105,44,52,2,1,0,0,1,0,0),(4106,45,52,2,1,0,0,1,0,0),(4107,46,52,1,1,0,0,1,0,0),(4108,47,52,1,1,0,0,1,0,0),(4109,48,52,2,1,0,0,1,0,0),(4110,49,52,1,1,0,0,1,0,0),(4111,50,52,3,1,0,0,1,0,0),(4112,51,52,1,1,0,0,1,0,0),(4113,52,52,1,1,0,0,1,0,0),(4114,53,52,1,1,0,0,1,0,0),(4115,54,52,1,1,0,0,1,0,0),(4116,55,52,3,1,0,0,1,0,0),(4117,56,52,3,1,0,0,1,0,0),(4118,57,52,2,1,0,0,1,0,0),(4119,58,52,2,1,0,0,1,0,0),(4120,59,52,1,1,0,0,1,0,0),(4121,60,52,3,1,0,0,1,0,0),(4122,61,52,1,1,0,0,1,0,0),(4123,62,52,2,1,0,0,1,0,0),(4124,63,52,2,1,0,0,1,0,0),(4125,64,52,3,1,0,0,1,0,0),(4126,65,52,3,1,0,0,1,0,0),(4127,66,52,2,1,0,0,1,0,0),(4128,67,52,1,1,0,0,1,0,0),(4129,68,52,2,1,0,0,1,0,0),(4130,69,52,2,1,0,0,1,0,0),(4131,2,53,1,1,0,0,1,0,0),(4132,8,53,4,1,0,0,1,0,0),(4133,10,53,4,1,0,0,1,0,0),(4134,11,53,4,1,0,0,1,0,0),(4135,12,53,4,1,0,0,1,0,0),(4136,14,53,1,1,0,0,1,0,0),(4137,15,53,1,1,0,0,1,0,0),(4138,16,53,1,1,0,0,1,0,0),(4139,17,53,1,1,0,0,1,0,0),(4140,18,53,1,1,0,0,1,0,0),(4141,19,53,2,1,0,0,1,0,0),(4142,20,53,2,1,0,0,1,0,0),(4143,21,53,1,1,0,0,1,0,0),(4144,22,53,1,1,0,0,1,0,0),(4145,23,53,1,1,0,0,1,0,0),(4146,24,53,1,1,0,0,1,0,0),(4147,25,53,1,1,0,0,1,0,0),(4148,26,53,1,1,0,0,1,0,0),(4149,27,53,1,1,0,0,1,0,0),(4150,28,53,2,1,0,0,1,0,0),(4151,29,53,1,1,0,0,1,0,0),(4152,30,53,1,1,0,0,1,0,0),(4153,33,53,1,1,0,0,1,0,0),(4154,34,53,1,1,0,0,1,0,0),(4155,35,53,2,1,0,0,1,0,0),(4156,36,53,2,1,0,0,1,0,0),(4157,37,53,2,1,0,0,1,0,0),(4158,38,53,2,1,0,0,1,0,0),(4159,39,53,1,1,0,0,1,0,0),(4160,40,53,2,1,0,0,1,0,0),(4161,41,53,1,1,0,0,1,0,0),(4162,42,53,2,1,0,0,1,0,0),(4163,43,53,1,1,0,0,1,0,0),(4164,44,53,2,1,0,0,1,0,0),(4165,45,53,2,1,0,0,1,0,0),(4166,46,53,1,1,0,0,1,0,0),(4167,47,53,1,1,0,0,1,0,0),(4168,48,53,2,1,0,0,1,0,0),(4169,49,53,1,1,0,0,1,0,0),(4170,50,53,3,1,0,0,1,0,0),(4171,51,53,1,1,0,0,1,0,0),(4172,52,53,1,1,0,0,1,0,0),(4173,53,53,1,1,0,0,1,0,0),(4174,54,53,1,1,0,0,1,0,0),(4175,55,53,3,1,0,0,1,0,0),(4176,56,53,3,1,0,0,1,0,0),(4177,57,53,2,1,0,0,1,0,0),(4178,58,53,2,1,0,0,1,0,0),(4179,59,53,1,1,0,0,1,0,0),(4180,60,53,3,1,0,0,1,0,0),(4181,61,53,1,1,0,0,1,0,0),(4182,62,53,2,1,0,0,1,0,0),(4183,63,53,2,1,0,0,1,0,0),(4184,64,53,3,1,0,0,1,0,0),(4185,65,53,3,1,0,0,1,0,0),(4186,66,53,2,1,0,0,1,0,0),(4187,67,53,1,1,0,0,1,0,0),(4188,68,53,2,1,0,0,1,0,0),(4189,69,53,2,1,0,0,1,0,0),(4190,2,54,1,1,0,0,1,0,0),(4191,8,54,4,1,0,0,1,0,0),(4192,10,54,4,1,0,0,1,0,0),(4193,11,54,4,1,0,0,1,0,0),(4194,12,54,4,1,0,0,1,0,0),(4195,14,54,1,1,0,0,1,0,0),(4196,15,54,1,1,0,0,1,0,0),(4197,16,54,1,1,0,0,1,0,0),(4198,17,54,1,1,0,0,1,0,0),(4199,18,54,1,1,0,0,1,0,0),(4200,19,54,2,1,0,0,1,0,0),(4201,20,54,2,1,0,0,1,0,0),(4202,21,54,1,1,0,0,1,0,0),(4203,22,54,1,1,0,0,1,0,0),(4204,23,54,1,1,0,0,1,0,0),(4205,24,54,1,1,0,0,1,0,0),(4206,25,54,1,1,0,0,1,0,0),(4207,26,54,1,1,0,0,1,0,0),(4208,27,54,1,1,0,0,1,0,0),(4209,28,54,2,1,0,0,1,0,0),(4210,29,54,1,1,0,0,1,0,0),(4211,30,54,1,1,0,0,1,0,0),(4212,33,54,1,1,0,0,1,0,0),(4213,34,54,1,1,0,0,1,0,0),(4214,35,54,2,1,0,0,1,0,0),(4215,36,54,2,1,0,0,1,0,0),(4216,37,54,2,1,0,0,1,0,0),(4217,38,54,2,1,0,0,1,0,0),(4218,39,54,1,1,0,0,1,0,0),(4219,40,54,2,1,0,0,1,0,0),(4220,41,54,1,1,0,0,1,0,0),(4221,42,54,2,1,0,0,1,0,0),(4222,43,54,1,1,0,0,1,0,0),(4223,44,54,2,1,0,0,1,0,0),(4224,45,54,2,1,0,0,1,0,0),(4225,46,54,1,1,0,0,1,0,0),(4226,47,54,1,1,0,0,1,0,0),(4227,48,54,2,1,0,0,1,0,0),(4228,49,54,1,1,0,0,1,0,0),(4229,50,54,3,1,0,0,1,0,0),(4230,51,54,1,1,0,0,1,0,0),(4231,52,54,1,1,0,0,1,0,0),(4232,53,54,1,1,0,0,1,0,0),(4233,54,54,1,1,0,0,1,0,0),(4234,55,54,3,1,0,0,1,0,0),(4235,56,54,3,1,0,0,1,0,0),(4236,57,54,2,1,0,0,1,0,0),(4237,58,54,2,1,0,0,1,0,0),(4238,59,54,1,1,0,0,1,0,0),(4239,60,54,3,1,0,0,1,0,0),(4240,61,54,1,1,0,0,1,0,0),(4241,62,54,2,1,0,0,1,0,0),(4242,63,54,2,1,0,0,1,0,0),(4243,64,54,3,1,0,0,1,0,0),(4244,65,54,3,1,0,0,1,0,0),(4245,66,54,2,1,0,0,1,0,0),(4246,67,54,1,1,0,0,1,0,0),(4247,68,54,2,1,0,0,1,0,0),(4248,69,54,2,1,0,0,1,0,0),(4249,2,55,1,1,0,0,1,0,0),(4250,8,55,4,1,0,0,1,0,0),(4251,10,55,4,1,0,0,1,0,0),(4252,11,55,4,1,0,0,1,0,0),(4253,12,55,4,1,0,0,1,0,0),(4254,14,55,1,1,0,0,1,0,0),(4255,15,55,1,1,0,0,1,0,0),(4256,16,55,1,1,0,0,1,0,0),(4257,17,55,1,1,0,0,1,0,0),(4258,18,55,1,1,0,0,1,0,0),(4259,19,55,2,1,0,0,1,0,0),(4260,20,55,2,1,0,0,1,0,0),(4261,21,55,1,1,0,0,1,0,0),(4262,22,55,1,1,0,0,1,0,0),(4263,23,55,1,1,0,0,1,0,0),(4264,24,55,1,1,0,0,1,0,0),(4265,25,55,1,1,0,0,1,0,0),(4266,26,55,1,1,0,0,1,0,0),(4267,27,55,1,1,0,0,1,0,0),(4268,28,55,2,1,0,0,1,0,0),(4269,29,55,1,1,0,0,1,0,0),(4270,30,55,1,1,0,0,1,0,0),(4271,33,55,1,1,0,0,1,0,0),(4272,34,55,1,1,0,0,1,0,0),(4273,35,55,2,1,0,0,1,0,0),(4274,36,55,2,1,0,0,1,0,0),(4275,37,55,2,1,0,0,1,0,0),(4276,38,55,2,1,0,0,1,0,0),(4277,39,55,1,1,0,0,1,0,0),(4278,40,55,2,1,0,0,1,0,0),(4279,41,55,1,1,0,0,1,0,0),(4280,42,55,2,1,0,0,1,0,0),(4281,43,55,1,1,0,0,1,0,0),(4282,44,55,2,1,0,0,1,0,0),(4283,45,55,2,1,0,0,1,0,0),(4284,46,55,1,1,0,0,1,0,0),(4285,47,55,1,1,0,0,1,0,0),(4286,48,55,2,1,0,0,1,0,0),(4287,49,55,1,1,0,0,1,0,0),(4288,50,55,3,1,0,0,1,0,0),(4289,51,55,1,1,0,0,1,0,0),(4290,52,55,1,1,0,0,1,0,0),(4291,53,55,1,1,0,0,1,0,0),(4292,54,55,1,1,0,0,1,0,0),(4293,55,55,3,1,0,0,1,0,0),(4294,56,55,3,1,0,0,1,0,0),(4295,57,55,2,1,0,0,1,0,0),(4296,58,55,2,1,0,0,1,0,0),(4297,59,55,1,1,0,0,1,0,0),(4298,60,55,3,1,0,0,1,0,0),(4299,61,55,1,1,0,0,1,0,0),(4300,62,55,2,1,0,0,1,0,0),(4301,63,55,2,1,0,0,1,0,0),(4302,64,55,3,1,0,0,1,0,0),(4303,65,55,3,1,0,0,1,0,0),(4304,66,55,2,1,0,0,1,0,0),(4305,67,55,1,1,0,0,1,0,0),(4306,68,55,2,1,0,0,1,0,0),(4307,69,55,2,1,0,0,1,0,0),(4308,2,56,1,1,0,0,1,0,0),(4309,8,56,4,1,0,0,1,0,0),(4310,10,56,4,1,0,0,1,0,0),(4311,11,56,4,1,0,0,1,0,0),(4312,12,56,4,1,0,0,1,0,0),(4313,14,56,1,1,0,0,1,0,0),(4314,15,56,1,1,0,0,1,0,0),(4315,16,56,1,1,0,0,1,0,0),(4316,17,56,1,1,0,0,1,0,0),(4317,18,56,1,1,0,0,1,0,0),(4318,19,56,2,1,0,0,1,0,0),(4319,20,56,2,1,0,0,1,0,0),(4320,21,56,1,1,0,0,1,0,0),(4321,22,56,1,1,0,0,1,0,0),(4322,23,56,1,1,0,0,1,0,0),(4323,24,56,1,1,0,0,1,0,0),(4324,25,56,1,1,0,0,1,0,0),(4325,26,56,1,1,0,0,1,0,0),(4326,27,56,1,1,0,0,1,0,0),(4327,28,56,2,1,0,0,1,0,0),(4328,29,56,1,1,0,0,1,0,0),(4329,30,56,1,1,0,0,1,0,0),(4330,33,56,1,1,0,0,1,0,0),(4331,34,56,1,1,0,0,1,0,0),(4332,35,56,2,1,0,0,1,0,0),(4333,36,56,2,1,0,0,1,0,0),(4334,37,56,2,1,0,0,1,0,0),(4335,38,56,2,1,0,0,1,0,0),(4336,39,56,1,1,0,0,1,0,0),(4337,40,56,2,1,0,0,1,0,0),(4338,41,56,1,1,0,0,1,0,0),(4339,42,56,2,1,0,0,1,0,0),(4340,43,56,1,1,0,0,1,0,0),(4341,44,56,2,1,0,0,1,0,0),(4342,45,56,2,1,0,0,1,0,0),(4343,46,56,1,1,0,0,1,0,0),(4344,47,56,1,1,0,0,1,0,0),(4345,48,56,2,1,0,0,1,0,0),(4346,49,56,1,1,0,0,1,0,0),(4347,50,56,3,1,0,0,1,0,0),(4348,51,56,1,1,0,0,1,0,0),(4349,52,56,1,1,0,0,1,0,0),(4350,53,56,1,1,0,0,1,0,0),(4351,54,56,1,1,0,0,1,0,0),(4352,55,56,3,1,0,0,1,0,0),(4353,56,56,3,1,0,0,1,0,0),(4354,57,56,2,1,0,0,1,0,0),(4355,58,56,2,1,0,0,1,0,0),(4356,59,56,1,1,0,0,1,0,0),(4357,60,56,3,1,0,0,1,0,0),(4358,61,56,1,1,0,0,1,0,0),(4359,62,56,2,1,0,0,1,0,0),(4360,63,56,2,1,0,0,1,0,0),(4361,64,56,3,1,0,0,1,0,0),(4362,65,56,3,1,0,0,1,0,0),(4363,66,56,2,1,0,0,1,0,0),(4364,67,56,1,1,0,0,1,0,0),(4365,68,56,2,1,0,0,1,0,0),(4366,69,56,2,1,0,0,1,0,0),(4427,2,49,1,1,0,0,1,0,0),(4428,8,49,4,1,0,0,1,0,0),(4429,10,49,4,1,0,0,1,0,0),(4430,11,49,4,1,0,0,1,0,0),(4431,12,49,4,1,0,0,1,0,0),(4432,14,49,1,1,0,0,1,0,0),(4433,15,49,1,1,0,0,1,0,0),(4434,16,49,1,1,0,0,1,0,0),(4435,17,49,1,1,0,0,1,0,0),(4436,18,49,1,1,0,0,1,0,0),(4437,19,49,2,1,0,0,1,0,0),(4438,20,49,2,1,0,0,1,0,0),(4439,21,49,1,1,0,0,1,0,0),(4440,22,49,1,1,0,0,1,0,0),(4441,23,49,1,1,0,0,1,0,0),(4442,24,49,1,1,0,0,1,0,0),(4443,25,49,1,1,0,0,1,0,0),(4444,26,49,1,1,0,0,1,0,0),(4445,27,49,1,1,0,0,1,0,0),(4446,28,49,2,1,0,0,1,0,0),(4447,29,49,1,1,0,0,1,0,0),(4448,30,49,1,1,0,0,1,0,0),(4449,33,49,1,1,0,0,1,0,0),(4450,34,49,1,1,0,0,1,0,0),(4451,35,49,2,1,0,0,1,0,0),(4452,36,49,2,1,0,0,1,0,0),(4453,37,49,2,1,0,0,1,0,0),(4454,38,49,2,1,0,0,1,0,0),(4455,39,49,1,1,0,0,1,0,0),(4456,40,49,2,1,0,0,1,0,0),(4457,41,49,1,1,0,0,1,0,0),(4458,42,49,2,1,0,0,1,0,0),(4459,43,49,1,1,0,0,1,0,0),(4460,44,49,2,1,0,0,1,0,0),(4461,45,49,2,1,0,0,1,0,0),(4462,46,49,1,1,0,0,1,0,0),(4463,47,49,1,1,0,0,1,0,0),(4464,48,49,2,1,0,0,1,0,0),(4465,49,49,1,1,0,0,1,0,0),(4466,50,49,3,1,0,0,1,0,0),(4467,51,49,1,1,0,0,1,0,0),(4468,52,49,1,1,0,0,1,0,0),(4469,53,49,1,1,0,0,1,0,0),(4470,54,49,1,1,0,0,1,0,0),(4471,55,49,3,1,0,0,1,0,0),(4472,56,49,3,1,0,0,1,0,0),(4473,57,49,2,1,0,0,1,0,0),(4474,58,49,2,1,0,0,1,0,0),(4475,59,49,1,1,0,0,1,0,0),(4476,60,49,3,1,0,0,1,0,0),(4477,61,49,1,1,0,0,1,0,0),(4478,62,49,2,1,0,0,1,0,0),(4479,63,49,2,1,0,0,1,0,0),(4480,64,49,3,1,0,0,1,0,0),(4481,65,49,3,1,0,0,1,0,0),(4482,66,49,2,1,0,0,1,0,0),(4483,67,49,1,1,0,0,1,0,0),(4484,68,49,2,1,0,0,1,0,0),(4485,69,49,2,1,0,0,1,0,0),(4882,1,50,0,1,1,1,1,0,0),(4883,2,50,1,1,1,1,1,0,0),(4889,8,50,4,1,1,1,1,0,0),(4891,10,50,4,1,1,1,1,0,0),(4892,11,50,4,1,1,1,1,0,0),(4893,12,50,4,1,1,1,1,0,0),(4894,14,50,1,1,1,1,1,0,0),(4895,15,50,1,1,1,1,1,0,0),(4896,16,50,1,1,1,1,1,0,0),(4897,17,50,1,1,1,1,1,0,0),(4898,18,50,1,1,1,1,1,0,0),(4899,19,50,2,1,1,1,1,0,0),(4900,20,50,2,1,1,1,1,0,0),(4901,21,50,1,1,1,1,1,0,0),(4902,22,50,1,1,1,1,1,0,0),(4903,23,50,1,1,1,1,1,0,0),(4904,24,50,1,1,1,1,1,0,0),(4905,25,50,1,1,1,1,1,0,0),(4906,26,50,1,1,1,1,1,0,0),(4907,27,50,1,1,1,1,1,0,0),(4908,28,50,2,1,1,1,1,0,0),(4909,29,50,1,1,1,1,1,0,0),(4910,30,50,1,1,1,1,1,0,0),(4911,33,50,1,1,1,1,1,0,0),(4912,34,50,1,1,1,1,1,0,0),(4913,35,50,2,1,1,1,1,0,0),(4914,36,50,2,1,1,1,1,0,0),(4915,37,50,2,1,1,1,1,0,0),(4916,38,50,2,1,1,1,1,0,0),(4917,39,50,1,1,1,1,1,0,0),(4918,40,50,2,1,1,1,1,0,0),(4919,41,50,1,1,1,1,1,0,0),(4920,42,50,2,1,1,1,1,0,0),(4921,43,50,1,1,1,1,1,0,0),(4922,44,50,2,1,1,1,1,0,0),(4923,45,50,2,1,1,1,1,0,0),(4924,46,50,1,1,1,1,1,0,0),(4925,47,50,1,1,1,1,1,0,0),(4926,48,50,2,1,1,1,1,0,0),(4927,49,50,1,1,1,1,1,0,0),(4928,50,50,3,1,1,1,1,0,0),(4929,51,50,1,1,1,1,1,0,0),(4930,52,50,1,1,1,1,1,0,0),(4931,53,50,1,1,1,1,1,0,0),(4932,54,50,1,1,1,1,1,0,0),(4933,55,50,3,1,1,1,1,0,0),(4934,56,50,3,1,1,1,1,0,0),(4935,57,50,2,1,1,1,1,0,0),(4936,58,50,2,1,1,1,1,0,0),(4937,59,50,1,1,1,1,1,0,0),(4938,60,50,3,1,1,1,1,0,0),(4939,61,50,1,1,1,1,1,0,0),(4940,62,50,2,1,1,1,1,0,0),(4941,63,50,2,1,1,1,1,0,0),(4942,64,50,3,1,1,1,1,0,0),(4943,65,50,3,1,1,1,1,0,0),(4944,66,50,2,1,1,1,1,0,0),(4945,67,50,1,1,1,1,1,0,0),(4946,68,50,2,1,1,1,1,0,0),(4947,69,50,2,1,1,1,1,0,0),(5014,1,57,0,1,1,1,1,0,0),(5015,2,57,1,1,1,1,1,0,0),(5016,3,57,5,1,1,1,1,0,0),(5017,4,57,5,1,1,1,1,0,0),(5018,5,57,5,1,1,1,1,0,0),(5019,6,57,5,1,1,1,1,0,0),(5020,7,57,5,1,1,1,1,0,0),(5021,8,57,4,1,1,1,1,0,0),(5022,9,57,5,1,1,1,1,0,0),(5023,10,57,4,1,1,1,1,0,0),(5024,11,57,4,1,1,1,1,0,0),(5025,12,57,4,1,1,1,1,0,0),(5026,14,57,1,1,1,1,1,0,0),(5027,15,57,1,1,1,1,1,0,0),(5028,16,57,1,1,1,1,1,0,0),(5029,17,57,1,1,1,1,1,0,0),(5030,18,57,1,1,1,1,1,0,0),(5031,19,57,2,1,1,1,1,0,0),(5032,20,57,2,1,1,1,1,0,0),(5033,21,57,1,1,1,1,1,0,0),(5034,22,57,1,1,1,1,1,0,0),(5035,23,57,1,1,1,1,1,0,0),(5036,24,57,1,1,1,1,1,0,0),(5037,25,57,1,1,1,1,1,0,0),(5038,26,57,1,1,1,1,1,0,0),(5039,27,57,1,1,1,1,1,0,0),(5040,28,57,2,1,1,1,1,0,0),(5041,29,57,1,1,1,1,1,0,0),(5042,30,57,1,1,1,1,1,0,0),(5043,33,57,1,1,1,1,1,0,0),(5044,34,57,1,1,1,1,1,0,0),(5045,35,57,2,1,1,1,1,0,0),(5046,36,57,2,1,1,1,1,0,0),(5047,37,57,2,1,1,1,1,0,0),(5048,38,57,2,1,1,1,1,0,0),(5049,39,57,1,1,1,1,1,0,0),(5050,40,57,2,1,1,1,1,0,0),(5051,41,57,1,1,1,1,1,0,0),(5052,42,57,2,1,1,1,1,0,0),(5053,43,57,1,1,1,1,1,0,0),(5054,44,57,2,1,1,1,1,0,0),(5055,45,57,2,1,1,1,1,0,0),(5056,46,57,1,1,1,1,1,0,0),(5057,47,57,1,1,1,1,1,0,0),(5058,48,57,2,1,1,1,1,0,0),(5059,49,57,1,1,1,1,1,0,0),(5060,50,57,3,1,1,1,1,0,0),(5061,51,57,1,1,1,1,1,0,0),(5062,52,57,1,1,1,1,1,0,0),(5063,53,57,1,1,1,1,1,0,0),(5064,54,57,1,1,1,1,1,0,0),(5065,55,57,3,1,1,1,1,0,0),(5066,56,57,3,1,1,1,1,0,0),(5067,57,57,2,1,1,1,1,0,0),(5068,58,57,2,1,1,1,1,0,0),(5069,59,57,1,1,1,1,1,0,0),(5070,60,57,3,1,1,1,1,0,0),(5071,61,57,1,1,1,1,1,0,0),(5072,62,57,2,1,1,1,1,0,0),(5073,63,57,2,1,1,1,1,0,0),(5074,64,57,3,1,1,1,1,0,0),(5075,65,57,3,1,1,1,1,0,0),(5076,66,57,2,1,1,1,1,0,0),(5077,67,57,1,1,1,1,1,0,0),(5078,68,57,2,1,1,1,1,0,0),(5079,69,57,2,1,1,1,1,0,0),(5080,5,50,5,1,1,1,1,0,0),(5081,6,50,5,1,1,1,1,0,0),(5082,3,50,5,1,1,1,1,0,0),(5083,9,50,5,1,1,1,1,0,0),(5085,8,58,4,1,0,0,1,0,0),(5086,10,58,4,1,0,0,1,0,0),(5087,11,58,4,1,0,0,1,0,0),(5088,12,58,4,1,0,0,1,0,0),(5094,19,58,2,1,0,0,1,0,0),(5095,20,58,2,1,0,0,1,0,0),(5103,28,58,2,1,0,0,1,0,0),(5108,35,58,2,1,0,0,1,0,0),(5109,36,58,2,1,0,0,1,0,0),(5110,37,58,2,1,0,0,1,0,0),(5111,38,58,2,1,0,0,1,0,0),(5113,40,58,2,1,0,0,1,0,0),(5115,42,58,2,1,0,0,1,0,0),(5117,44,58,2,1,0,0,1,0,0),(5118,45,58,2,1,0,0,1,0,0),(5121,48,58,2,1,0,0,1,0,0),(5123,50,58,3,1,0,0,1,0,0),(5128,55,58,3,1,0,0,1,0,0),(5129,56,58,3,1,0,0,1,0,0),(5130,57,58,2,1,0,0,1,0,0),(5131,58,58,2,1,0,0,1,0,0),(5133,60,58,3,1,0,0,1,0,0),(5135,62,58,2,1,0,0,1,0,0),(5136,63,58,2,1,0,0,1,0,0),(5137,64,58,3,1,0,0,1,0,0),(5138,65,58,3,1,0,0,1,0,0),(5139,66,58,2,1,0,0,1,0,0),(5141,68,58,2,1,0,0,1,0,0),(5142,69,58,2,1,0,0,1,0,0),(5143,15,58,1,1,0,0,1,0,0),(5144,43,58,1,1,0,0,1,0,0),(5145,27,58,1,1,0,0,1,0,0),(5146,24,58,1,1,0,0,1,0,0),(5147,26,58,1,1,0,0,1,0,0),(5148,21,58,1,1,0,0,1,0,0),(5149,17,58,1,1,0,0,1,0,0),(5150,52,58,1,1,0,0,1,0,0),(5151,34,58,1,1,0,0,1,0,0),(5152,25,58,1,1,1,0,1,0,0),(5153,54,58,1,1,0,0,1,0,0),(5154,46,58,1,1,0,0,1,0,0),(5155,39,58,1,1,0,0,1,0,0),(5156,29,58,1,1,0,0,1,0,0),(5157,2,58,1,1,0,0,1,0,0),(5158,30,58,1,1,0,0,1,0,0),(5159,49,58,1,1,0,0,1,0,0),(5160,33,58,1,1,0,0,1,0,0),(5161,47,58,1,1,0,0,1,0,0),(5162,16,58,1,1,0,0,1,0,0),(5163,59,58,1,1,0,0,1,0,0),(5164,22,58,1,1,0,0,1,0,0),(5165,61,58,1,1,0,0,1,0,0),(5166,23,58,1,1,0,0,1,0,0),(5167,41,58,1,1,0,0,1,0,0),(5168,18,58,1,1,0,0,1,0,0),(5169,53,58,1,1,0,0,1,0,0),(5170,14,58,1,1,0,0,1,0,0),(5171,67,58,1,1,0,0,1,0,0),(5172,51,58,1,1,0,0,1,0,0);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `autobackup` WRITE;:||:Separator:||:
 INSERT INTO `autobackup` VALUES(1,2,null,6,'13:00:00',null);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `bank` WRITE;:||:Separator:||:
 INSERT INTO `bank` VALUES(1,'c1a9e6d92d0c269e1a295f10d2773873f252ac0dc7e1a4c516befbba450b4af720492e8b8fd9e20cadd135689345c0259ce48b87cddf15c54a5feff6961314e1d5AHP4GDAyKB3a8k+pZJQ+d/AzTCE8pKGWHmP+U/m8o=',0,021609010153),(2,'467df9ea47458368432284cdebdeca85629441bee824af069130eef9ad250668e5f78e956b17d5a95d0d7665efd174d240d50ca3c412f2b757d96d290ac16adfbZa9nl+9KL0etKo3DeEv6tgiyA+wjzA+uQg33SwI59w=',0,020415010116);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `bankaccount` WRITE;:||:Separator:||:
 INSERT INTO `bankaccount` VALUES(1,4,1,'8c62497d023e9248d8aa2413684ddf1402aa9a0091ff1eea53073f1eb42af68f4a80846f0384a6cf258e397b0827ee218deead801c4fc950627486e67934b0855xB6KeJ2TlEdlmmHqXLRy/hXNtCqKRq+DAQcISU0meD9x95ZJqZhIDBtEUOyqgYw','3ce2298c7c973afb189efb30622dcd5fab16448a320bd2dc4822834dd53cf10129f8773a190569ad6f93a07a219386641177f18e899adcbcd567d8c89b1814172+OKR13idO91JQDyhCI+IpRAiZaOSRwGQkgvHbKtCic=',0.00,1102000,null,0,'02160900-16'),(2,2,1,'f441aefb219b749e5a540cfa09a9708e98b4855a304002d361d0cf9af6b81f6620ffc3bd86076228b668ab168beae893b58b18499bb8859adbfe57132128bd53RUkrLHfxXJAZdcYxwg5ibnoFHGUDUdVCxtFuhtDO75lg3nO99pzMLTdy5v0FQ9pQ','08ef95f20ff38d0bb9c613e91a5dd5d9588a346bff29f2f1db8f0cd44079cf1cc6e1826bac0ae1d0c951d1dbf3a8b495f75b7ea26e133de3af2a8fa41d3305f4B4+s3c+OoZqA4NZsf0bPzbAUKTtsNm5g10mCnbODoBU=',0.00,1102001,null,0,'02160900-99');:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `bankaccounthistory` WRITE;:||:Separator:||:
 INSERT INTO `bankaccounthistory` VALUES(1,1,4,1,'8c62497d023e9248d8aa2413684ddf1402aa9a0091ff1eea53073f1eb42af68f4a80846f0384a6cf258e397b0827ee218deead801c4fc950627486e67934b0855xB6KeJ2TlEdlmmHqXLRy/hXNtCqKRq+DAQcISU0meD9x95ZJqZhIDBtEUOyqgYw','3ce2298c7c973afb189efb30622dcd5fab16448a320bd2dc4822834dd53cf10129f8773a190569ad6f93a07a219386641177f18e899adcbcd567d8c89b1814172+OKR13idO91JQDyhCI+IpRAiZaOSRwGQkgvHbKtCic=',0.00,1102000,null),(2,2,2,1,'f441aefb219b749e5a540cfa09a9708e98b4855a304002d361d0cf9af6b81f6620ffc3bd86076228b668ab168beae893b58b18499bb8859adbfe57132128bd53RUkrLHfxXJAZdcYxwg5ibnoFHGUDUdVCxtFuhtDO75lg3nO99pzMLTdy5v0FQ9pQ','08ef95f20ff38d0bb9c613e91a5dd5d9588a346bff29f2f1db8f0cd44079cf1cc6e1826bac0ae1d0c951d1dbf3a8b495f75b7ea26e133de3af2a8fa41d3305f4B4+s3c+OoZqA4NZsf0bPzbAUKTtsNm5g10mCnbODoBU=',0.00,1102001,null);:||:Separator:||:


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
 INSERT INTO `coa` VALUES(1101000,1,1101000,'Accounts Receivable',1,1,01,000,'DR',3,'2020-04-22 21:47:58',34,null,0),(4101000,1,4101000,'Revenue Account',4,1,01,000,'CR',1,'2020-04-22 21:08:07',34,null,0),(2101000,1,2101000,'Accounts Payable',2,1,01,000,'CR',12,'2020-04-22 21:47:42',34,null,0),(3101000,1,3101000,'Retained Earnings',3,1,01,000,'CR',26,'2020-04-22 21:47:35',34,null,0),(1102000,1,1102000,'Cash in Bank',1,1,02,000,'DR',2,'2020-06-12 18:44:52',34,null,0),(1103000,1,1103000,'Inventory Account',1,1,03,000,'DR',5,'2020-06-12 18:45:08',34,null,0),(2102000,1,2102000,'Goods Receipt Clearing',2,1,02,000,'CR',17,'2020-06-12 18:45:16',34,null,0),(5101000,1,5101000,'Expense Account',5,1,01,000,'DR',17,'2020-04-23 21:24:57',34,null,0),(4102000,1,4102000,'Sales',4,1,02,000,'CR',14,'2020-04-23 21:27:11',34,null,0),(4102001,2,4102001,'Sales Discount',4,1,02,001,'DR',1,'2020-04-23 21:28:10',34,null,1),(1102001,2,1102001,'Cash In Bank - BPI',1,1,02,001,'DR',2,'2020-04-23 21:28:44',34,null,0),(1101001,2,1101001,'Accounts Receivable - Customer 1',1,1,01,001,'DR',3,'2020-06-12 18:44:43',34,null,0),(5102000,1,5102000,'Business Travel and Transportation',5,1,02,000,'DR',23,'2020-04-23 21:32:12',34,null,0),(5102001,2,5102001,'Employee Expenses',5,1,02,001,'DR',23,'2020-04-23 21:32:40',34,null,0),(1104000,1,1104000,'Cash in Bank',1,1,04,000,'DR',2,'2020-09-02 23:09:29',34,0,0),(4102002,2,4102002,'Sales Discount from Sample',4,1,02,002,'DR',1,'2020-09-02 23:09:29',34,0,0),(1105000,1,1105000,'Accounts Receivable',1,1,05,000,'DR',3,'2020-09-02 23:09:58',34,0,0),(1106000,1,1106000,'Cash in Bank',1,1,06,000,'DR',2,'2020-09-02 23:09:58',34,0,0),(4102003,2,4102003,'Sales Discount',4,1,02,003,'DR',1,'2020-09-02 23:09:58',34,0,0),(1107000,1,1107000,'Accounts Receivable',1,1,07,000,'DR',3,'2020-09-03 00:09:21',34,0,0),(1108000,1,1108000,'Cash in Bank',1,1,08,000,'DR',2,'2020-09-03 00:09:21',34,0,0),(4102004,2,4102004,'Sales Discount',4,1,02,004,'DR',1,'2020-09-03 00:09:21',34,0,0),(1109000,1,1109000,'Accounts Receivable',1,1,09,000,'DR',3,'2020-09-03 00:09:52',34,0,0),(1110000,1,1110000,'Cash in Bank',1,1,10,000,'DR',2,'2020-09-03 00:09:52',34,0,0),(4102005,2,4102005,'Sales Discount',4,1,02,005,'DR',1,'2020-09-03 00:09:52',34,0,0),(1111000,1,1111000,'Accounts Receivable',1,1,11,000,'DR',3,'2020-09-03 00:09:20',34,0,0),(1112000,1,1112000,'Cash in Bank',1,1,12,000,'DR',2,'2020-09-03 00:09:20',34,0,0),(4102006,2,4102006,'Sales Discount',4,1,02,006,'DR',1,'2020-09-03 00:09:20',34,0,0),(2301000,1,2301000,'Sample Lianbility',2,3,01,000,'CR',1,'2020-09-03 00:09:20',34,0,0),(1113000,1,1113000,'Accounts Receivable from Dulcy',1,1,13,000,'DR',3,'2020-09-03 01:09:06',34,0,0),(1114000,1,1114000,'Cash in Bank from Dulcy',1,1,14,000,'DR',2,'2020-09-03 01:09:06',34,0,0),(4102007,2,4102007,'Sales Discount from Dulcy',4,1,02,007,'DR',1,'2020-09-03 01:09:06',34,0,0),(2302000,1,2302000,'Sample Liability',2,3,02,000,'CR',1,'2020-09-03 01:09:06',34,0,0),(2302001,2,2302001,'Liability 1',2,3,02,001,'CR',1,'2020-09-03 01:09:06',34,0,0),(1115000,1,1115000,'Cash in Bank from Hazel',1,1,15,000,'DR',2,'2020-09-03 02:09:00',34,0,0),(1116000,1,1116000,'Accounts Receivable from Hazel',1,1,16,000,'DR',3,'2020-09-03 02:09:32',34,0,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `coaaffiliate`;:||:Separator:||:


CREATE TABLE `coaaffiliate` (
  `idCoaAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `idCoa` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCoaAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `coaaffiliate` WRITE;:||:Separator:||:
 INSERT INTO `coaaffiliate` VALUES(1,4101000,2),(2,3101000,2),(3,2101000,2),(5,1101000,2),(8,5101000,2),(9,4102000,2),(11,4102001,2),(12,1102001,2),(14,5102000,2),(15,5102001,2),(16,1101001,2),(17,1101001,5),(18,1101001,4),(19,1102000,2),(20,1102000,5),(21,1102000,4),(22,1103000,2),(23,1103000,5),(24,1103000,4),(25,2102000,2),(26,2102000,5),(27,2102000,4);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `coaaffiliatehistory` WRITE;:||:Separator:||:
 INSERT INTO `coaaffiliatehistory` VALUES(1,4101000,6,2),(2,3101000,7,2),(3,2101000,8,2),(4,1102000,9,2),(5,1101000,10,2),(6,1103000,11,2),(7,2102000,12,2),(8,5101000,13,2),(9,4102000,14,2),(10,4103000,15,2),(11,4102001,16,2),(12,1102001,17,2),(13,1101001,18,2),(14,5102000,19,2),(15,5102001,20,2),(16,1101001,21,2),(17,1101001,21,5),(18,1101001,21,4),(19,1102000,22,2),(20,1102000,22,5),(21,1102000,22,4),(22,1103000,23,2),(23,1103000,23,5),(24,1103000,23,4),(25,2102000,24,2),(26,2102000,24,5),(27,2102000,24,4);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `coahistory` WRITE;:||:Separator:||:
 INSERT INTO `coahistory` VALUES(1,1101000,1,1101000,'Accounts Receivable',1,1,1,0,1,3,34,null,null),(2,4101000,1,4101000,'Revenue Account',4,1,1,0,2,1,34,null,null),(3,2101000,1,2101000,'Accounts Payable',2,1,1,0,2,12,34,null,null),(4,3101000,1,3101000,'Retained Earnings',3,1,1,0,2,26,34,null,null),(5,1102000,1,1102000,'Cash in Bank',1,1,2,0,1,2,34,null,null),(6,4101000,1,4101000,'Revenue Account',4,1,1,0,2,1,34,null,'2020-04-21 15:59:23'),(7,3101000,1,3101000,'Retained Earnings',3,1,1,0,2,26,34,null,'2020-04-21 16:00:27'),(8,2101000,1,2101000,'Accounts Payable',2,1,1,0,2,12,34,null,'2020-04-21 15:59:43'),(9,1102000,1,1102000,'Cash in Bank',1,1,2,0,1,2,34,null,'2020-04-21 16:00:58'),(10,1101000,1,1101000,'Accounts Receivable',1,1,1,0,1,3,34,null,'2020-04-21 15:57:06'),(11,1103000,1,1103000,'Inventory Account',1,1,3,0,1,5,34,null,null),(12,2102000,1,2102000,'Goods Receipt Clearing',2,1,2,0,2,17,34,null,null),(13,5101000,1,5101000,'Expense Account',5,1,1,0,1,17,34,null,null),(14,4102000,1,4102000,'Sales',4,1,2,0,2,14,34,null,null),(15,4103000,1,4103000,'Sales Discount',4,1,3,0,1,1,34,null,null),(16,4103000,2,4102001,'Sales Discount',4,1,2,1,1,1,34,null,'2020-04-23 21:27:49'),(17,1102001,2,1102001,'Cash In Bank - BPI',1,1,2,1,1,2,34,null,null),(18,1101001,2,1101001,'Accounts Receivable - Customer 1',1,1,1,1,1,3,34,null,null),(19,5102000,1,5102000,'Business Travel and Transportation',5,1,2,0,1,23,34,null,null),(20,5102001,2,5102001,'Employee Expenses',5,1,2,1,1,23,34,null,null),(21,1101001,2,1101001,'Accounts Receivable - Customer 1',1,1,1,1,1,3,34,null,'2020-04-23 21:29:15'),(22,1102000,1,1102000,'Cash in Bank',1,1,2,0,1,2,34,null,'2020-04-22 21:47:49'),(23,1103000,1,1103000,'Inventory Account',1,1,3,0,1,5,34,null,'2020-04-23 21:20:11'),(24,2102000,1,2102000,'Goods Receipt Clearing',2,1,2,0,2,17,34,null,'2020-04-23 21:24:22');:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `contribution`;:||:Separator:||:


CREATE TABLE `contribution` (
  `idContribution` int(11) NOT NULL AUTO_INCREMENT,
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `costcenter` WRITE;:||:Separator:||:
 INSERT INTO `costcenter` VALUES(1,'d98a3361f394add155c2fa5cf85eae5e850a11f9d3a51e9fcfb1c0c7c0978330f3f46844e7d2fcafb84c2f3454de4756789512668028a86ea46578dfb034d57eNdQG2iFiqq75cT6AqU6tQ8wbNmiGPvzJf72I/YZtqH8=',null,2,1,200519200114),(2,'e66281a2c4a1ba5e0db342f8e3bb901db9ac810dbd379550b2608caa193d2b03e782e3505eee07ad5e3e116172e19d00a87421b93d31900ec2516f1fe216b5eewoCAimg5CCQdsuYR1fp75sfd9E8VGy1ia5l1iuEEJ/FBhoUDxbeDEe/PTatf2Dw2',null,1,0,200519200015),(3,'a27405fc90b78c6e2b12e9e439db8e93c1692a9066976f1af123d7b5277a4120869786a7901c45503bbe356fbe56a1c3d6effb92189db7149f7633b4828a33a1WuYZIMsbdkmJw5br8PSc+yNwkRcfVpLpsWngXeK/0pY=',null,1,0,151605180166),(4,'d7764a809058b6bd9d0346850343348a09798b75b95346d6daa448a464c595f4698f3741c252477e4f3879a63eb2306093515b8d0168e526402e733a935b288aqqsHSG4QL4KibQwWQLC8h4ITuVzp5k4ppE7tRpyVrko=',null,1,0,010303152160),(5,'aacffca60bc934fb5a6e8107bc04e47205e27f5ae4d4f7263a98de4840b9068a0ce3b4891cd4d070ebc50dfd6ea9af4aa777b331a8cdecc7d8de55d71a1fcb5c7I8PT6Ac2m+nBuNjgbsJJekzpCgi9/sGSrH+bfypXWg=',null,1,0,010413091443),(6,'6e20274058d6655c61947a180d21970d4ade532e883379feead2f46c41c94fa93973414f8da881ea6d2a1720dd8f0a87596340175fcc656bef0007336c5c3615y+LR+Vi+q0SdHa7Mx/tkTkHD5ec6B8H91LBfrwq/xv0=',null,1,1,080019211612),(7,'8e629762ebd0a284f8adb7c94949ae13049e8a9b5ea9689788a74af8232c83bdea23b3e78a60960fd53811da778dcbd2e92c68b7ab8327a75a95e6336f5be8c30WS6cjXXvSmyebF5ppg80NIuO5bNRcd1oSiD9kCA4EE=',null,1,0,080007181593);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `costcenteraffiliate`;:||:Separator:||:


CREATE TABLE `costcenteraffiliate` (
  `idCostCenterAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `idCostCenter` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCostCenterAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `costcenteraffiliate` WRITE;:||:Separator:||:
 INSERT INTO `costcenteraffiliate` VALUES(1,1,4),(2,2,2),(3,3,2),(4,4,2),(5,5,2),(6,6,12),(7,7,12),(8,7,12),(9,7,12);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `costcenterhistory` WRITE;:||:Separator:||:
 INSERT INTO `costcenterhistory` VALUES(1,7,'0be5afb10a7f70de51826f1e5421fcfb2d8af12aadc4d29347e2a50a20aea43310fe7d0be5cc8f523434f3790ad78a3c218184a5990050e97c71c3088bbb3232QjpPRJB/ts5glzuQ7pxMIInI31dcZkoMl/4tz2ABltY=',null,1),(2,7,'8e629762ebd0a284f8adb7c94949ae13049e8a9b5ea9689788a74af8232c83bdea23b3e78a60960fd53811da778dcbd2e92c68b7ab8327a75a95e6336f5be8c30WS6cjXXvSmyebF5ppg80NIuO5bNRcd1oSiD9kCA4EE=',null,1);:||:Separator:||:


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
  `contactNumber` text,
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
  `contactNumber` text,
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `customer` WRITE;:||:Separator:||:
 INSERT INTO `customer` VALUES(1,'637116c176bcfb31ecf94e9174e861dee7d9ddb83800862007be7fbf26dfa808412b56b7b64c1e5675cc0880edbffc2a130883fa4b2343ebdc2542964fa101f4c+jR0/rnNff2AUSXjNEjflHomw8+QAAxIM4KipAgwjc=',null,'427c574437699d900a9df04aacf1721e08d6cc1f21c2cbe328ea0c893bfcdecf7de6b4a05a9e58f1d990da1c6a68f8ef160d17dc859c1cdb8d585587eb02eeechveHYO7+JjavL0BNHi16WdYIsO0qHZ7QGUkz3bQDGTI=','0f8489a4ff754ddfd6aa2ee428de50c777299abcf5f9540dc68278ad170418e1011514d5fcc02812eed2c848c61e2847449ebfa5d68a9dff34cc83b77d059410kli1d1G99iSSLPj5/Rp/XUP6uqSTJ8TxIJW3sML8bH0=','252e59f1f3bf9720056e1ff39942dbd73edc067f508f93c73a8fadd37672565f6396155fd2bdc7d877bb146732ea3e4c2401c2a91caeddd741bf9202e07e5939LUqo7yW755GbtLccKvlgL/a9X1R7Ia7AVve+oIbiHlo=',2,1,0,0.00,0,null,0.00,0.00,0.00,0,0.00,1101001,4102001,0,032119201527),(2,'d635cfd59d01904b30a8116319b0626e1d602f574ea668e4c09131db32715d245257486e838b19300ffedae3437cbaf574b34565a39bc321d350858dd233863eAhWJi6pGOvSOUAWWbhLJKbJAjChH3BkxGWo4hcudeJvlVZ5BMI2+0KjVWkLOjGDP',null,'a3673e3821cc34e0dccec20f2b8cf76fd51581f943cf61eeac28dab9d275b3e2124f57d849aab1142f65c46a1ca6b774dee9ad1f59193ab0d8148256f9776456avLXMoiBNXGp321/dYfhbWRNzsFt3vVk/ytrVOO6qvE=','228da1027341f15bd7a17c793bbb7ca533c70d79d08505ce7cce9d0a640038f2342b8e4513e9cc1dc6ab9329fd50aa06240d277b02087c0e21790b5e6e61fae0wgl5EghFMwKLpbNUv0jGvwEP9qasrJdaX1JJx+/1XFo=','95f5973cdf29327da1788d22f0d3c22e11431ac3586d90a91213090502986a6cd625adb22aaf28c99460274a7f75f96cf27188d01bcf71f25305af2014c45da8bsesYlMwp0A7sioaeG6fpxypOpDPZLV8jnxGgaHY2ug=',1,null,1,10000.00,0,null,0.00,0.00,0.00,0,0.00,1101001,4102001,0,032119201551),(3,'57a74afc2a31eab24a17286468c8657ae90c33d584f24f5be58f12eddb42b845acc97ed8f8e66028f61a570dd70c9e163b31cd7da15339bbe43bba81acbce4bbHvn5SKKXQy3t4XoRhrpeV35KgRpM2lrlj8YI8XnAkYx6Sl0bci+RFBVEo+TvbU3O',null,'b4d4eb97e90a0f2da046f0b5c274085d6151df40d2a5221563a8e5ed7f55fcea26a5ab5af3c9c25ff953dfc2fc276c260ca0163beaf233058b5bc4e6f88d46fbHDWuPJxb0+XNjq9hfaCAgHXqBSYdFDISQOsqsDAdiX4=','5d4fb924a8c60f9c26e080248a41b2e2e3952743fb8458cb0aa233f0650d69a98afe3cdcd3d94722879aae198bcc5cc5270e77364c1d6b10db5adf3576ce8c5dww4a41BplB9ARjVNZJ6D71kWJt0P+2aNAJaB/EYQTdU=','888748f669bd9eda08484e981cb1623e2013c3ceef16be6aa7f2c0f7f0418d0edbf31f70f9ca31a8dea04e2c3feb4da175c702e6f01dade311c8a584dc385cdfSUHPfKQeyFhXosrxTcfjxfCo1Tk/UZ/Avfl0l210GlU=',1,null,0,0.00,1,1,12.00,0.00,0.00,0,0.00,1101001,4102001,0,032119201557),(4,'6f83f9dff87d9a2f3370cd923aea8c7bae4f75659804300901ada37b8384c1988273fc7a56b7a7bf75c9a73b9860beb35587b74f318198d5bb28af75363ef750YjB1iflA3qjbXEWwKghl5gX7AHxxG6cCcRebYeWFxG1MHH2T4Jpq4jF5tQMXzeqa',null,'e7e44ee38c10ff31c8c09adca565e4cf6c09738f18a7bb6d56dcf5394cd0d3f2640baa018b95670c254a6820c7f9909c55a4c5a2ca6c71a5d1fb8ef58f1fda11CI+SCmaVdK1iGXa8lbpG+YeTqJ+weqS18exPgckmois=','5d61697302b06be578e6949e546155da4ed14d76ef6dfa16b31167fc9398833ae05e9ad83d130502934ed27d094c581b828b14e4089c2ad4d9159b710107e339jmK0dlQjkYP5RpPMI3sGgWyFpWk812rckoU0mVjudsY=','71a6ec6e1e9119f4d900f9e0543bdade75aca901fd5c9dfc1daef17c08c5dab27e6ef1af6afeed43d0baedbbd8f724a16338cba691b38fa54a123fd851d208b5xk7Pj4aOFk5XGJPwY9KdbURiPljU0VzMdJFUa83yOzk=',1,null,0,0.00,1,2,12.00,0.00,0.00,0,0.00,1101001,4102001,0,032119201527),(5,'58aaf3a3e3ff5e28529d341f3ee77e6e4509c343518d67df52b9cd0b29c952f70bb6c3d5196668c32e7c8c3f38793e1a0e4de7b592f4c6413fbc6d51b30fd566v+buOllUY13qWjcRgSaly/hNs12FBV3+J9/N1y9uVAuvQjA6IT1zFgqVLYdhiRuo',null,'36cf6e1b17ac1ca79ec52b910ff4ad2655bc19363edf78497b30f47c96d9aa43928777cfa61394b3fadd7d6e391535682f82ccb6a49e946f414d757d06df3cebah2exseRerZ1LiNMZgZcvk4Pjy+YnpPHIo/Cs7JPr/4=','1ff6307172bf580853e1db261066a7e2651bb923130c6d7ced8186125320d59e45553bf87413d2e006eec90ce278ecf67c56b4fc21f0749dcf3b5ec463a3b75fbzDZenqXnESXbj6L3DO5B5PYYGL2wmVt0bPRz2KPlZw=','bc00594964166675880ad2e525a8d6cce5cad22fa8616fed05f532d91a196e382090efbca61a52aac20393d45db00ccb0c9946b89bed1358d2201666365e7d62w4/2ZcLtl6pNqTXRvHmImUFdZu7jSkF1ipIdkKKuCZ8=',1,null,0,0.00,0,null,0.00,10.00,0.00,0,0.00,null,null,0,032119201586),(6,'d2d580c9ee8f8245534b7a5cb2850f63525169f5a55c9ec2549b18798d6b7248228f21c9690e3a84d93cdd974b0bf76c9d764209876f8ead4e951cdd583a101dig8qNviOaNRudCnFEBmdtlAPvjbLpEbMnFlvnqAdakYV0R+8c2ieV8UlGTVUdkyI',null,'0a511a4062c52e353eca7256b348c1cc97f71da670a852b97b31f879ee3511d79bb1ab0069d1301dc2aea46f80ebfe0880afd3d8bb6e8af89a8f14ab477388b3fsfTacaCG9UrzsRjLKMVdJxD+2DQTEzZznl6olPL2eM=','26781ecd2a6aba354f54b43c89480e3e454fd800d705751ae9abd46a71639f7fa6b313f941ebad7ccf42e3d37df53dc229458b1d61504549a718a67d672bec593uZWqw/ormA2yrVIRRloN+r8wfxoHa3TJfWIhGQOgVQ=','c91ee7a449e7ff774024de1f4104d485107a393d3ddbecc5918345f58953f980b745d84bbaa02ad80ff9b77920c50feaf4c48bcc9b5303e50df5a4d199a5e61aXQFwbSPFSst4fz+Im7rB+hmD8zNKYgnn0Rg6NeJGz9s=',1,null,0,0.00,0,null,0.00,0.00,10.00,0,0.00,1101001,4102001,0,032119201512),(7,'d482f9d16fb2707de49593e639742798de766e8eb372800da90c8e18533f2b5bba5c11c9bca433005e69f309e5de64588efcbfbf34feacb90ac3b4966e21a7c4X8+2UhqZcz+QVOMWKW0+1M6zpuRjEXUS6UnZlBiXNrQ=','2071fa77d39c5399a29cfd0b32e50b800c083c9b2860f79060b533f3944b78d9dbf6c3ccf84e5e71024e17ec48e2597395ef7320b1d1a936e69c89b354401f4dAOt7SYeG03SgSdsmV4O6xrY2yyujrfTzAdP/5wrXSKw=','cbcbf73a615f15f0bf6e4cb263592d75c80bdbb2b3e971b43f3339ba491982ee2ff98127dd9321d7dfbb34e18c0bff2129619c90a6743f3c652fdc5d18ec0ca9tjA9eH08KKN7ZpLPw0x89T/hnFiFOsw+way1NN18ucU=','2729adbe9e598a44442056a56aa9cca54ee96db802038cb60f3862a21555cd13c9db82eaf5c75dffcad345529f8a1321ebcdc311905dab164f21781bf1136404dBgm8fSGGp3wIelImHIN7xW9spPHpjnOLBC+qmaa+Dh1GkYnMMaGwhuFVNdJrHyQ5lLoh26+MKtjyYg1HfDSkg==','058bdd84f35035e54081fd7a46ae9bb222a05bb7ce5b19114910f8e741afbd41da5434f330f57ddb817bfed4a0dee77a4fd4be4e52c75a787ca9f2524eb00341Q1LWxOhakxhdt/ywULiWi9HmejeEL7QGFGk9q3/yMvo=',2,20,1,10000000.00,1,1,12.00,0.00,0.00,0,0.00,1101000,null,0,110304030168),(8,'8843d0044d599124f1a3470b75f6bb1eed6ff31a60ddb50c075687fe12d7a58648a5fb84d1914c7aa4421881be55e1e1de55c0fe31b3a7949e7f2344ec06e963/oARreONWneMaA4BfEUVX8jDXFazr4vcWaFBAQsF7mA=',null,'1af99d03cc0310acdeb7e85c6e346b84dd6eb3b5a6c09083db575aba2f10d00607ced1b28ab85822c14b333a4467df4977f58eabeeaef1916f9e96274f2cd32bzpDFRsVYq2D5eG9Nww7blz1Hn4Yy4e20CUZmRDi7430=','a910c281deb9a2620fd5c24f4de298d88e140195b53533100cd4b135a7b1d26d0551f93e98ba4e87ee5400a209c62a8d6fd683b7facbf120bd98229f799a4893rDibNqNhEtoDm2+DNTLt2tOfb6vNrnnmWkoL/xnuaQ9QZAJ7a5eSVRajhI5JE6ykS7uLBzCP8MmeTyrOoHBSiw==',0,1,null,1,5000.00,1,1,12.00,0.00,0.00,1,0.00,null,null,0,190113161212);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `customeraffiliate`;:||:Separator:||:


CREATE TABLE `customeraffiliate` (
  `idCustomerAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `idCustomer` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCustomerAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `customeraffiliate` WRITE;:||:Separator:||:
 INSERT INTO `customeraffiliate` VALUES(1,1,2),(2,1,5),(3,1,4),(4,2,2),(5,2,5),(6,2,4),(7,3,2),(8,3,5),(9,3,4),(10,4,2),(11,4,5),(12,4,4),(13,5,2),(14,5,5),(15,5,4),(16,6,2),(17,6,5),(18,6,4),(19,7,2),(20,8,6),(21,8,2);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `customeraffiliatehistory` WRITE;:||:Separator:||:
 INSERT INTO `customeraffiliatehistory` VALUES(1,1,1,1,2),(2,2,1,1,5),(3,3,1,1,4),(4,4,2,2,2),(5,5,2,2,5),(6,6,2,2,4),(7,7,3,3,2),(8,8,3,3,5),(9,9,3,3,4),(10,10,4,4,2),(11,11,4,4,5),(12,12,4,4,4),(13,13,5,5,2),(14,14,5,5,5),(15,15,5,5,4),(16,16,6,6,2),(17,17,6,6,5),(18,18,6,6,4),(19,19,7,7,2),(20,20,8,8,6),(21,21,8,8,2);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `customerhistory` WRITE;:||:Separator:||:
 INSERT INTO `customerhistory` VALUES(1,1,'637116c176bcfb31ecf94e9174e861dee7d9ddb83800862007be7fbf26dfa808412b56b7b64c1e5675cc0880edbffc2a130883fa4b2343ebdc2542964fa101f4c+jR0/rnNff2AUSXjNEjflHomw8+QAAxIM4KipAgwjc=',null,'427c574437699d900a9df04aacf1721e08d6cc1f21c2cbe328ea0c893bfcdecf7de6b4a05a9e58f1d990da1c6a68f8ef160d17dc859c1cdb8d585587eb02eeechveHYO7+JjavL0BNHi16WdYIsO0qHZ7QGUkz3bQDGTI=','0f8489a4ff754ddfd6aa2ee428de50c777299abcf5f9540dc68278ad170418e1011514d5fcc02812eed2c848c61e2847449ebfa5d68a9dff34cc83b77d059410kli1d1G99iSSLPj5/Rp/XUP6uqSTJ8TxIJW3sML8bH0=','252e59f1f3bf9720056e1ff39942dbd73edc067f508f93c73a8fadd37672565f6396155fd2bdc7d877bb146732ea3e4c2401c2a91caeddd741bf9202e07e5939LUqo7yW755GbtLccKvlgL/a9X1R7Ia7AVve+oIbiHlo=',2,1,0,0.00,null,null,0.00,0.00,0.00,0,0.00,1101001,4102001),(2,2,'d635cfd59d01904b30a8116319b0626e1d602f574ea668e4c09131db32715d245257486e838b19300ffedae3437cbaf574b34565a39bc321d350858dd233863eAhWJi6pGOvSOUAWWbhLJKbJAjChH3BkxGWo4hcudeJvlVZ5BMI2+0KjVWkLOjGDP',null,'a3673e3821cc34e0dccec20f2b8cf76fd51581f943cf61eeac28dab9d275b3e2124f57d849aab1142f65c46a1ca6b774dee9ad1f59193ab0d8148256f9776456avLXMoiBNXGp321/dYfhbWRNzsFt3vVk/ytrVOO6qvE=','228da1027341f15bd7a17c793bbb7ca533c70d79d08505ce7cce9d0a640038f2342b8e4513e9cc1dc6ab9329fd50aa06240d277b02087c0e21790b5e6e61fae0wgl5EghFMwKLpbNUv0jGvwEP9qasrJdaX1JJx+/1XFo=','95f5973cdf29327da1788d22f0d3c22e11431ac3586d90a91213090502986a6cd625adb22aaf28c99460274a7f75f96cf27188d01bcf71f25305af2014c45da8bsesYlMwp0A7sioaeG6fpxypOpDPZLV8jnxGgaHY2ug=',1,null,1,10000.00,null,null,0.00,0.00,0.00,0,0.00,1101001,4102001),(3,3,'57a74afc2a31eab24a17286468c8657ae90c33d584f24f5be58f12eddb42b845acc97ed8f8e66028f61a570dd70c9e163b31cd7da15339bbe43bba81acbce4bbHvn5SKKXQy3t4XoRhrpeV35KgRpM2lrlj8YI8XnAkYx6Sl0bci+RFBVEo+TvbU3O',null,'b4d4eb97e90a0f2da046f0b5c274085d6151df40d2a5221563a8e5ed7f55fcea26a5ab5af3c9c25ff953dfc2fc276c260ca0163beaf233058b5bc4e6f88d46fbHDWuPJxb0+XNjq9hfaCAgHXqBSYdFDISQOsqsDAdiX4=','5d4fb924a8c60f9c26e080248a41b2e2e3952743fb8458cb0aa233f0650d69a98afe3cdcd3d94722879aae198bcc5cc5270e77364c1d6b10db5adf3576ce8c5dww4a41BplB9ARjVNZJ6D71kWJt0P+2aNAJaB/EYQTdU=','888748f669bd9eda08484e981cb1623e2013c3ceef16be6aa7f2c0f7f0418d0edbf31f70f9ca31a8dea04e2c3feb4da175c702e6f01dade311c8a584dc385cdfSUHPfKQeyFhXosrxTcfjxfCo1Tk/UZ/Avfl0l210GlU=',1,null,0,0.00,null,1,12.00,0.00,0.00,0,0.00,1101001,4102001),(4,4,'6f83f9dff87d9a2f3370cd923aea8c7bae4f75659804300901ada37b8384c1988273fc7a56b7a7bf75c9a73b9860beb35587b74f318198d5bb28af75363ef750YjB1iflA3qjbXEWwKghl5gX7AHxxG6cCcRebYeWFxG1MHH2T4Jpq4jF5tQMXzeqa',null,'e7e44ee38c10ff31c8c09adca565e4cf6c09738f18a7bb6d56dcf5394cd0d3f2640baa018b95670c254a6820c7f9909c55a4c5a2ca6c71a5d1fb8ef58f1fda11CI+SCmaVdK1iGXa8lbpG+YeTqJ+weqS18exPgckmois=','5d61697302b06be578e6949e546155da4ed14d76ef6dfa16b31167fc9398833ae05e9ad83d130502934ed27d094c581b828b14e4089c2ad4d9159b710107e339jmK0dlQjkYP5RpPMI3sGgWyFpWk812rckoU0mVjudsY=','71a6ec6e1e9119f4d900f9e0543bdade75aca901fd5c9dfc1daef17c08c5dab27e6ef1af6afeed43d0baedbbd8f724a16338cba691b38fa54a123fd851d208b5xk7Pj4aOFk5XGJPwY9KdbURiPljU0VzMdJFUa83yOzk=',1,null,0,0.00,null,2,12.00,0.00,0.00,0,0.00,1101001,4102001),(5,5,'58aaf3a3e3ff5e28529d341f3ee77e6e4509c343518d67df52b9cd0b29c952f70bb6c3d5196668c32e7c8c3f38793e1a0e4de7b592f4c6413fbc6d51b30fd566v+buOllUY13qWjcRgSaly/hNs12FBV3+J9/N1y9uVAuvQjA6IT1zFgqVLYdhiRuo',null,'36cf6e1b17ac1ca79ec52b910ff4ad2655bc19363edf78497b30f47c96d9aa43928777cfa61394b3fadd7d6e391535682f82ccb6a49e946f414d757d06df3cebah2exseRerZ1LiNMZgZcvk4Pjy+YnpPHIo/Cs7JPr/4=','1ff6307172bf580853e1db261066a7e2651bb923130c6d7ced8186125320d59e45553bf87413d2e006eec90ce278ecf67c56b4fc21f0749dcf3b5ec463a3b75fbzDZenqXnESXbj6L3DO5B5PYYGL2wmVt0bPRz2KPlZw=','bc00594964166675880ad2e525a8d6cce5cad22fa8616fed05f532d91a196e382090efbca61a52aac20393d45db00ccb0c9946b89bed1358d2201666365e7d62w4/2ZcLtl6pNqTXRvHmImUFdZu7jSkF1ipIdkKKuCZ8=',1,null,0,0.00,null,null,0.00,10.00,0.00,0,0.00,null,null),(6,6,'d2d580c9ee8f8245534b7a5cb2850f63525169f5a55c9ec2549b18798d6b7248228f21c9690e3a84d93cdd974b0bf76c9d764209876f8ead4e951cdd583a101dig8qNviOaNRudCnFEBmdtlAPvjbLpEbMnFlvnqAdakYV0R+8c2ieV8UlGTVUdkyI',null,'0a511a4062c52e353eca7256b348c1cc97f71da670a852b97b31f879ee3511d79bb1ab0069d1301dc2aea46f80ebfe0880afd3d8bb6e8af89a8f14ab477388b3fsfTacaCG9UrzsRjLKMVdJxD+2DQTEzZznl6olPL2eM=','26781ecd2a6aba354f54b43c89480e3e454fd800d705751ae9abd46a71639f7fa6b313f941ebad7ccf42e3d37df53dc229458b1d61504549a718a67d672bec593uZWqw/ormA2yrVIRRloN+r8wfxoHa3TJfWIhGQOgVQ=','c91ee7a449e7ff774024de1f4104d485107a393d3ddbecc5918345f58953f980b745d84bbaa02ad80ff9b77920c50feaf4c48bcc9b5303e50df5a4d199a5e61aXQFwbSPFSst4fz+Im7rB+hmD8zNKYgnn0Rg6NeJGz9s=',1,null,0,0.00,null,null,0.00,0.00,10.00,0,0.00,1101001,4102001),(7,7,'d482f9d16fb2707de49593e639742798de766e8eb372800da90c8e18533f2b5bba5c11c9bca433005e69f309e5de64588efcbfbf34feacb90ac3b4966e21a7c4X8+2UhqZcz+QVOMWKW0+1M6zpuRjEXUS6UnZlBiXNrQ=','2071fa77d39c5399a29cfd0b32e50b800c083c9b2860f79060b533f3944b78d9dbf6c3ccf84e5e71024e17ec48e2597395ef7320b1d1a936e69c89b354401f4dAOt7SYeG03SgSdsmV4O6xrY2yyujrfTzAdP/5wrXSKw=','cbcbf73a615f15f0bf6e4cb263592d75c80bdbb2b3e971b43f3339ba491982ee2ff98127dd9321d7dfbb34e18c0bff2129619c90a6743f3c652fdc5d18ec0ca9tjA9eH08KKN7ZpLPw0x89T/hnFiFOsw+way1NN18ucU=','2729adbe9e598a44442056a56aa9cca54ee96db802038cb60f3862a21555cd13c9db82eaf5c75dffcad345529f8a1321ebcdc311905dab164f21781bf1136404dBgm8fSGGp3wIelImHIN7xW9spPHpjnOLBC+qmaa+Dh1GkYnMMaGwhuFVNdJrHyQ5lLoh26+MKtjyYg1HfDSkg==','058bdd84f35035e54081fd7a46ae9bb222a05bb7ce5b19114910f8e741afbd41da5434f330f57ddb817bfed4a0dee77a4fd4be4e52c75a787ca9f2524eb00341Q1LWxOhakxhdt/ywULiWi9HmejeEL7QGFGk9q3/yMvo=',2,20,1,10000000.00,null,1,12.00,0.00,0.00,0,0.00,1101000,null),(8,8,'8843d0044d599124f1a3470b75f6bb1eed6ff31a60ddb50c075687fe12d7a58648a5fb84d1914c7aa4421881be55e1e1de55c0fe31b3a7949e7f2344ec06e963/oARreONWneMaA4BfEUVX8jDXFazr4vcWaFBAQsF7mA=',null,'1af99d03cc0310acdeb7e85c6e346b84dd6eb3b5a6c09083db575aba2f10d00607ced1b28ab85822c14b333a4467df4977f58eabeeaef1916f9e96274f2cd32bzpDFRsVYq2D5eG9Nww7blz1Hn4Yy4e20CUZmRDi7430=','a910c281deb9a2620fd5c24f4de298d88e140195b53533100cd4b135a7b1d26d0551f93e98ba4e87ee5400a209c62a8d6fd683b7facbf120bd98229f799a4893rDibNqNhEtoDm2+DNTLt2tOfb6vNrnnmWkoL/xnuaQ9QZAJ7a5eSVRajhI5JE6ykS7uLBzCP8MmeTyrOoHBSiw==',0,1,null,1,5000.00,null,1,12.00,0.00,0.00,1,0.00,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `customeritems`;:||:Separator:||:


CREATE TABLE `customeritems` (
  `idCustomerItems` int(11) NOT NULL AUTO_INCREMENT,
  `idCustomer` int(11) DEFAULT NULL,
  `idItem` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCustomerItems`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `customeritems` WRITE;:||:Separator:||:
 INSERT INTO `customeritems` VALUES(1,8,4),(2,8,1),(3,8,2);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `customeritemshistory` WRITE;:||:Separator:||:
 INSERT INTO `customeritemshistory` VALUES(1,8,8,4),(2,8,8,1),(3,8,8,2);:||:Separator:||:


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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultaccounts` WRITE;:||:Separator:||:


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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultaccountshistory` WRITE;:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultentry` WRITE;:||:Separator:||:
 INSERT INTO `defaultentry` VALUES(1,'Sample JE',25,11,null,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `defaultentryaffiliate`;:||:Separator:||:


CREATE TABLE `defaultentryaffiliate` (
  `idDefaultAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `idDefaultEntry` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDefaultAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultentryaffiliate` WRITE;:||:Separator:||:
 INSERT INTO `defaultentryaffiliate` VALUES(1,1,6),(2,1,2),(3,1,5);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultentryaffiliatehistory` WRITE;:||:Separator:||:
 INSERT INTO `defaultentryaffiliatehistory` VALUES(1,null,1,1,6),(2,null,1,1,2),(3,null,1,1,5);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultentryhistory` WRITE;:||:Separator:||:
 INSERT INTO `defaultentryhistory` VALUES(1,1,'Sample JE',25,11,null);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultentryposting` WRITE;:||:Separator:||:
 INSERT INTO `defaultentryposting` VALUES(1,1,2101000,0.00,0.00),(2,1,1102001,0.00,0.00);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `defaultentrypostinghistory` WRITE;:||:Separator:||:
 INSERT INTO `defaultentrypostinghistory` VALUES(1,null,1,2101000,0.00,0.00),(2,null,1,1102001,0.00,0.00);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `disbursements`;:||:Separator:||:


CREATE TABLE `disbursements` (
  `idDisbursement` int(11) NOT NULL AUTO_INCREMENT,
  `idInvoice` int(11) DEFAULT NULL,
  `paid` decimal(18,2) DEFAULT '0.00',
  `ewtRate` decimal(18,2) DEFAULT '0.00',
  `ewtAmount` decimal(18,2) DEFAULT '0.00',
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `empbenefits` WRITE;:||:Separator:||:
 INSERT INTO `empbenefits` VALUES(1,119,'2194f557bced2679ad189a8daab5e5be68bb468309a4ffd90700d18ffc7eadc84619f70c9f9b61338da8ef442e61fed5b6232d6927b6fba10e0991a5aa457f83KIPS4OEdwG3cuR+8NNs2KjKU35b1wfFxmP0iNrWCBuk=','8deb44bd845313408aab5083c5042ef92e3865f2db0a259665694d5612dd904da395c80b9f0a2d3a79a2b92c036ce93326c710f9f81d3c9269a438b04d7b0e3doWHBQhBqwB+0okz3iplRGCnCDxjNL4I4tNZgjRj6Fgg=',4,1),(2,123,'ce0684d9f3b135f8e52981fe0b2a672650cbaac2a5c18205079ed073427a4f7108f691fe207311aa5761307758bc0c5126b24454760511e84cb73cb48c278d42dEtMd1zK6TRoYYzlWMfffNiEX9JOpAVVH07oOQFNU1c=','2a8fcf5832aa99dc43e32da04f7c228973fe7280e96ae5264595acefce823d37a1252a876b523e199ba979edb7a286a3beae812f7d5ecf8b08d8fd2f64835748QEsidSCcxqd1lFOO2i/FrOE0cP/Dw6heeZZGQWLKY9Q=',2,1),(6,134,'946a5cc0150ddcec6016d024456be30a14ba0ab0e5bfb23e66911393cf7bdd8a8821a935dc87cb6186f7c1681e91ae424a3095d8f3a7663268aa8bc025615eefBPPn79lhV/NDn0laBilP7gqZRBC9rZhb23lYa6i8Dg0=','8348393638d36eeab4f7990bed06e7339cc475f5d8ee2e3134434a0192b572b4aa33eda202aa2b916f006b9c2d196a0b48642aaf61c4a5e0a2eed4f4cad2f783If8Z4Sch5vKWeP4eTBHrWR2gxvi/YJyWO1a3yudDFHY=',4,0);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `empcontribution` WRITE;:||:Separator:||:
 INSERT INTO `empcontribution` VALUES(1,0,1,'6f1ae808c239bb87587a800b5d592020aee29f27a1d8faecf706e9e3c6985a1f6943ec5036ae6a647f54bf389d8334edd47240bcd49379be0e00289af6ba9e70pHzvzkOCbMyCoCJhKXI4lNZDBjB82SxdXTUq5LlotkQ=','2020-05-01',0,0),(2,0,2,'7b4216fa7e72bb481d6af86515446d908fa06a0f16dd2ad697016cd5bde5029fdfee1d2702dce3f90b00852dcd3f9cc0f4d0df24a8b8305da77799ce9e8822e7BWorzyFvjP4h9kj2a6Wq+laZjEMR7NLI/WvauZGDEnk=','2019-06-02',5102000,0);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `empcontributionhistory` WRITE;:||:Separator:||:
 INSERT INTO `empcontributionhistory` VALUES(1,119,1,'6f1ae808c239bb87587a800b5d592020aee29f27a1d8faecf706e9e3c6985a1f6943ec5036ae6a647f54bf389d8334edd47240bcd49379be0e00289af6ba9e70pHzvzkOCbMyCoCJhKXI4lNZDBjB82SxdXTUq5LlotkQ=','2020-05-01'),(2,123,2,'7b4216fa7e72bb481d6af86515446d908fa06a0f16dd2ad697016cd5bde5029fdfee1d2702dce3f90b00852dcd3f9cc0f4d0df24a8b8305da77799ce9e8822e7BWorzyFvjP4h9kj2a6Wq+laZjEMR7NLI/WvauZGDEnk=','2019-06-02');:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `employee` WRITE;:||:Separator:||:
 INSERT INTO `employee` VALUES(1,1,'Jon Snow','Cagayan de Oro City, Misamis Oriental',912345678,'jonknowsnothing@yopmail.com','1981-08-05',1,1,1,null),(59,555,'mark','mark',1231,'mark@gmail.com','1981-07-05',0,1,1,null),(63,777,'marco','marco',2323,'777@gmai.com','2019-11-18',0,1,1,null),(64,999,'Sample User','Test Address',123,'nine@gmail.com','1985-11-04',0,0,1,null),(65,888,'test user','user',123,'user@yahoomail.com','1995-11-05',0,1,1,null),(66,333,'Aubrey','test Address',1231231,'aubrey@gmail.com','2005-08-01',1,1,1,null),(67,222,'tuna tuna','test address',123,'tuna@gmail.com','1995-11-01',0,1,1,null),(68,111,'one','one address',111,'one@gmail.com','1985-11-04',0,1,1,null),(90,123456,'rgyrt','fghg',35454,'sfdgfd@sfg.gfj','2019-11-27',0,1,1,null),(91,123,'dsfgvdf','gdf',34535,'gdf@dhg.ujkl','2019-11-27',1,1,1,null),(92,213345,'tgdg','dfgdfg',2147483647,'dfgfdg@dfhfg.bfgt','1995-11-06',1,1,1,null),(93,12345,'sdgdf','gdfg',23534,'sdfsd@jdfhksjd.com','2019-11-27',1,1,1,null),(95,554455,'test marco','fdsfs',3434,'123@yahoo.com','2019-11-27',0,0,1,null),(96,443344,'test','erere',3434,'2323@gmail.com','2019-11-27',0,0,1,null),(97,2019,'Sam Paul','CDO',2147483647,'sampaul@yopmail.com','1986-11-03',0,1,1,null),(98,88888,'6a86d1a3815d4fb3815270308f63a0c5766f072935a3bcde39ab882b5a03281391f6d61abb82a1ff9b2e02b1a89c22ef85aca9d5f74192e31e86da5d359d2fa5gI7BjCCY1N9ZYNZIk+NW15Sd3V2x96tQv6y1PeU3WxY=','78d0ce81f59c19c1c46e27c0785bc789baecb80b3321da21b87aec93a56f65f6eafb036ed856e945efaf5ceb663100f9a22a76e7903da7b1499d8385e91ff303ojPrJeJnCfIBofEZGBVclQ7nNSxWWKqlMbuhDkQ8p/I=','601a11ee359a030dcf25d52f6c6fb0508c84e3c3f75f5cad10847438d8c4ec56d46b9893a83e977161eb5a11c67e93c87f02d4ee0958941b008ec4cb278c0511lWGLXQtliaFw2ukSWLEgxG4kBnMI6fB96wjukwtVcdI=','cbc1546e3395883a786bf96a172ceb8f1d7bb0a9b97e81993ad2d0f5078276fc80b5ab66138422a698db6dabe68db99f8a527970c7b1911f4d9b3b0f802bbd38+Lkx4Xk7e5vy3fr5o+nr9vniK6HvJVg0/z9P8f3AXc8=','87fa747c9ffd26a3e34aa30f95aae624cf4066048e84626d94126ceef314bf15870bfb4baa0d3a3c0e8b45e0f387196d1ffacfe7b8f35450a743743ccdd25072mCEDbiHWaVGOLT1STa+spkNRNgIc4mEk3yINCSnUUsw=',0,1,1,112112152042),(99,124,'Marie Danilene','Cagayan de Oro',122,'dan@dispostable.com','1996-01-21',0,1,1,null),(100,123456789,'Dulcy','CDO',139829485,'dulcy@yopmail.com','1997-12-05',0,1,1,null),(101,2147483647,'728ca771c05fd87f532de95711c023a4e7520d6950ea556df81202b9121591314cf3f84ceac5906f21b9111fe3242a1dca3e000383789c9122f0ef002757fce6jDge2fMFUacyZcTxw9r4uwHAf0SU2mcBari6rzuvrI0=','5d10cd152ff8f1ff4034fae39592d2ccb3587f4b6ae0b3137d8789433ffb10e353fc6e22794ef0aa9176fe6bb1f3a4a5f6e4623a481dbca3bd4e10a0d15b40deyOrfumyogNiKxgH5MfhHErthkUhiuMYJ+inhcJt7XGw=','00511aa86961ce6e32f1ebbba1d744ac0313a484f508bea6e83198c7f24e3ed48e1493229e6ce448debd322b318a30ade89d18d48c978e1078280d45f5e8cc4c5bgzfVJbTd3AzEPZivw4+nT3O1vlJrLt/Wr9hZXoO90=','91d959cde11451324b52d2309bdcb424ecd8aff449578481be14854901f163bd015d52c6cce19b5773f399db07afc446d20786e97a3a24b185b12f2383c175cfa4brEUk9xfWD+xy271HZO8AQ1I/pyl0tBTL8/cJrg3/iJiZy+63NHH0l0vPoHd7e','c0e10ca68e5a072097da60fda70415d7976dc5a08e135f27ad5cc46d1eb51eb329673f21abdfdf02302d8a1bf830e79ecaa407ee9d4ce0201f6fe06020273c25i0W1mEIlVx6o+6dBSzRlssbOI9unglaHtSBDrVFftE4=',0,1,1,080126051252),(102,11111,'973309a1c5fb6aa02073c8510279ef96c2b5552992f8673ca2c38b30c56f71436cd3d786898483a2f21d5cc83f8a8c647a084dbbd45a56880e211aceecf104efR0lUrhiRamh6a1i5nvtVTEd3QHqrYlcLUvGI9+Z4/Ioib1xwGyYW4pB1wx//+5D7','e59e522767849ca39643548aaf35a701af4b92e5f310f38fc4383aed74de2b155d083821dbe7f378f81e15890a59dc7b3b6cf38cca7cfff525d31f8ce92bbca3EuP/YUYAhe0+1D1ACze89EmtuMlMrkBiiIIzZlKeoCQ=','98bcdbc2f20643025414756080f37273e704db6d6edb8b7cde377004fce08bb5de21fb1dcd361773fe2e128d7a2b0700a97342a95bd3481ded37b1106f6e0efdXMYB75L/o6RHifKk+Ep7iBIdQvXIOLxB+VYUEgx1FN8=','53575107987c25298e445f3f0572fa0b24413937a2c4c04472760068a433ecfe6ea4c67a6409560876c6db2b6afe9bced94d05be261792a5c7273a95d8f8fe08bNcO8TonmdB4LtpBBhjeN/M8eV8ERvaO1jAFitU+/Dw=','7354caa3be68384a828708afe39125ab94a317c4875213640647c6e626678d5e793835ee95b009c24b681424ae8dc29213e4a2de81c203fe32c27b90ca2d36096nYiCtQ6SJkLRu5vlipOXcHxFVZdRcUeBdP2Fi6iQ80=',0,1,0,192519200578),(104,819,'Timon Cantu','Culpa explicabo Acc',875,'mywo@dispostable.com','1975-01-08',0,1,1,null),(105,258,'335891efac3ff74086fd394f3baf4a88df189a3510475460827c9ac683318da89a6888b176892a5cf02ab4ba7b4d9285b1e000b11c31db13f780f0bea6ff95f1GMIRLwBXXVVOVhNdjCOJJcIW6rV5zcU7FB2U9e2ut60=','7d099bc13441e988f7585bb0f6b74c30e16d8a80d1cdc79aae7f394ebb46e43e305863461814382c68a2ab28c26836ff7ac5cae1a5bd59d9956ad0ce303cb08fekNNMQMKuj1ahXaqaJuARM+mxk3/OF5nbrDxrOPP9eI9ZGu8pGve4bUtBjw45uZA','d355113a0e9fd3e7415aa3185e86402102efb9747a584868e72eb24b86d171f9683eab30fc25a56f51841b20c626da787e10e4a8d2db925aee366f6f1bd66241+WwiV9rV4DlA9LCXaKcYKtJziFs3xjG70lQjHDOneZA=','65b4de7425b6047104a74a2cb1ed8d78657f1a97615073225ee7951670e6c30907b58f84e15907ede5c3fd84a7379aebb894a2ac8de5639bc4ac770145b7688c47Xo8HYJ/Prs7psqACF1I6U8dk/RVoCyMahI5B7yQstkHrrurEFeb1IdxydbBGT3','08f1f93322a385fc968db5d8f429600f176e1931f80ff3946a494a96341e3c6b004dce544de59b3162f58828d477c08239a6c18da0978506eb4216fcaf5b7d80gluLJwGOcZfnBDzA+rQLZbt8eNpTNaltuy7E3hMf5Hs=',0,1,1,130901001398),(107,659,'Liberty Chandler','Cupidatat voluptas c',410,'tysowevifi@dispostable.com','2017-02-07',0,0,1,null),(108,708,'Connor Curry','Dolor est sit volup',328,'jyze@dispostable.com','1987-02-09',0,1,1,null),(109,454534,'erterfd','gefdf',34534534,'dffdv@jdnf.df','2020-02-24',0,0,1,null),(110,398,'Leila Francis','Dolor lorem corrupti',298,'fisohypu@mailinator.net','1983-01-26',0,1,1,null),(111,570,'Ahmed Lambert','Et hic aperiam at do',819,'hibyci@mailinator.com','1974-07-15',0,0,1,null),(112,411,'c90eb341acdc69c1b15399a718e4f361b0733e32476a83851b8b0079b6b583c2006e0e33094ab96ca1c24c28a7175e68410fe2bc375ed03a97dea61f5ecc5c1e1pIuX2FgdpSPL0KCaavQaM1Wn3iGLvczKV/qctxVMJ8=','00e89a41238013d06bbdc67a2ce75a92a7998eec372c40fb54115710828025c737738728be2107b4969d2ed9b941a54a09630365824d249e55a4fd095858a3f5IQERZYNjvvaMM9Edfjx2SMfaPL3pnKqrpbQ1gALHevMIKFM6FtFtcGGf2+0/oMr9','7bc3736ac3e316a09246621f6ce5153da0fbe5a8dec1b8ec465500b2c5ad2907a337e40a242398441fbea619c27b786fff33b310a3337704b7406405ad564ef6xrko2PYr80kDQ98waQx9ZKZMrHMJud+SJIiHmLJwhsc=','3cafe1dfb30800dcdac0caeaf92d9caafa5a56ae88ab91479ca200346e6ada36c89515552d25778a0a70d1355f91d4d63f97c1b83be663175778b93c422c5830iLag64t1gmcZIG7eLjrANxXAGOwEReF6npAZHagbyAaYMZtqgUolzalhvYIBIxgc','6241e400ba960588e3d8b756d537c6d8c27295717150eef9f85ec765eabc9752368005b7f8ce020a3a5b6357b26e0674b2926d1d19d713a27891ad5d6762f5f3nhcrCnnTjdFF2OZIAKilK8jlQYpQDvMxg2c5daglyEA=',0,0,1,190123250595),(113,86,'cfb79d2110f9a07a7ad36f5411252565eda068e9dcd81e8e0c670a175fc3c3394fdad8140609b79d024cd48ebae3a628afdc20666ede3c7dbfb6a4d3e8885aa5apN2+jCYIgBbicR8/qMRv078PEZhwLyVVQxx9PrOV7w=','dd077a9ebb8dbbec084f0c697352c002d525507a3165a5e94e49bfddc5e65dfdc26d25bcbd266af8b25a2ff3266514231c8fd3b3bfdcd33c1381a0a813a8fa19QAQ0MuJriJsxDHeuTurIxS/xuV+nmIYl2Fzb+3GvFz/FLbTgucBLHNeqWiOsLjL+','1fa73a08cbd64aaac42fd0072fa116dc33b0de62022a49a5ad7d13659f1bfba68879eab05118693c4b3977f024c42f2f294e0663346eeae069e57807dcd3c021CkBvxQGIJmef7GElOLyjnd5+OOBXMEcSYhpCzVBRqOo=','468a1f773c70305c6d1715dbacfe7b76825e0fe6088911cde947a6321ff74f7cad8933188dfbf105646059bd7182c3ab8af39e78fdc84a38d4f25c6774a63e0bijnU/5Ab3OdD8pQhTYdXZha8hn2WlJCjLxGoQQkc0FnSEIlRSSM/SXb4CzLSfZDx','0002fde3dc95e4454c4b6bd9cc02bf7e2ee99b4c2e0a905216c01042da324930508d8b6fae66451cfda78603dbef0ad69d7ba4c2afcf8c0eacd46332faac798aEtFdWt6kcprOx5MkQ0j6scMhxyOJV7e0kN7IKJVXebo=',0,0,1,122103090158),(114,116,'5f72a57a051e2f3f876f5dbf7d06de720bcd527db71254bc09c56e72703c8e02875bddfc60ccb46c16672a827d87d891399edc9994ff74ba79db1e8eb9e42b38Ckj22XLzF1fI1q4g1v1OkyGTemcPbdSbfXS2Td13OeM=','97190983e39747a5cb1f6b330f9d14467f51c1d55f9866616604914ce6a9e2a8560df91624df4d41d53d42b0f026f7a182112b15a6ffac967c503589b9a65fe1mNT1zKdatvQRPfKDrgnaTQyM+SeJawL88Ou4c+T/Te46eWyb2QZAbZbWbveUhLyf','e9f7985eadc725ec83437e17bc886645ab25ba71569439e44a4f7ea9bdef5a6bf65535be15b48f3abea4c65696c5332c1d6d42cf438980384917dac9794a05d5qNSbB5246cIBe03Lya0PuEngg3YzpVGTWZvYh2WuDKw=','d83045a6e82f3fe410467ec1380d2a0e669d422440f93ad9a6e2013d798d7be2469ccfaf5024edf5ecc223c6c76860985abab0e4da3af789b69a4dfc5939fc1ddvCQx9yPw9uADIziap7YYQHX85ofaiZW+EcuWsejw7ZelHWNMCFV9sPG4atQCYAI','c7f73787bc9631d81dcd7008a06461d8a3d319affb5cd7e3e2ecfe4e1ffa0ea4379322306baa3272784b0a17d97505a561a86cae2fd558ddd3bc4dce7a584881l7oUiqfEYUusEALNSuuN8Oug9+sUl51bfH/MVfl7dn0=',0,0,1,071801142021),(115,379,'866299fda6645ced7b82abab9f37a6122ecf35ad27d1c964e2326cefac334b2e49ea3681ef31727fcc33af48e116afe4b8230554c432dbc60a8400105c7fad4d3a1symIUN6FI2pw0Z6fpj2ju/mS9xEU0L3Y/F0l4qGU=','1b88fe4ff1526b9e0b113062fe4c461591eb55c71bba8321872d0e0265fd7f77c4c2f69dcefe24578c3f57b913f1a7ea00ab15b1e534b8ffdc07f683ec5135ecChTZAvTNhEHzsLxNaMYfQTxu/WZ9Ll7Txr6vAHn0Y8JzWCxlnuJWEAU59nRmYE3g','998ee4ec6029b6e5ae4c1e08d37d0a72e98f289a2ddd3c48cb88edc8e5c27b6fa145bc2cce7e6d5866000bd15629d43b35608c58344d356c21d0201e5e86977cf4c5L82E1e1D/LQCW0MS4auK1hYH3CR5XCILKjQnGKE=','2de5dfb74d27c65d5006eb6abedae75fe042381369a64c59cffd591eda298c583adf768a4fdc402b25b5d8224bd32c00dd9ac0494a7d122e4fcb4de9664a23496z1/ZN0F9yADNG69waOEll3dMhVXN0WLqTokrnmhUlIgM501JQbpeg+CZUTEfct8','ab2a65ba7435ab7add70c028c526e35dbf133576f64cdd7b909f88a6b2871d3fe18c6d0b62aa09b589693d4febb270359dfcff042fef5b0a057549e69e668367GgtVq743jx5Fh2x1eHIh0lEQbeJ3TAWySedJgnd9Dls=',0,1,1,172101130167),(116,532,'c29d7a06b2955adfdcf78531c0596a486432745b7d9a4b307ef5095dc78b4d3ebd660a4649b5bb6586e28c8f9d62b78e4cf8930aa596acf74e96e6dc70969217YG60sK+DfzyVCSnQxBGdTTjwjPpUiLnBP2WuJ+xoaD3zbP7ofpo96Inz8MN7fYqT','43047115b23804a28b1b33ad712471a8f66d64cb13c440e55d9395e6c46c314d22e368a18606eb3950f1302ba2910ac20a135bdee85653b2a4c712cde53cee2dAWzzD073OuD2sD/CWcbCba8qG1jDaScAkfZ5snqoi8Kqw3+kL0d0LeYR2p7ONJIn','9913fed74dc3402e82d97902922876a1cb2dd7944b15bf8e9fc3e4c6b49d106bf33d1560c3e5c4d11fb98e5fdacbda913b4610b3cf7d7a89daf421212dc83b01THyW1ef+v/Kh+nHY70JRp1mcP4dYeFLwSzH0WiGHyfQ=','8e3bf2608ed7d9c807cb74fa860d66c3e1af40325ec5bdb9e6f47f906a48603435ffc5aabd0aaebd7ab4aae27766a819c2af8fa0882ba5da80956acf98be610cFq0j3x03yiUcvRS/nVtRpygx/BpevcbEIdX81kw2+nXe4FWK5WdO+3hJh7zdtJWI','61863b85d3c186acfdf5e9371f48bf43050a92a6546b3e24c0f774dd36e17d3e9efaa769d1b4e93382c4ccc94b2efa8bd7ffa37a269929d1ca8a6d1c1952434cCff/8kAe6rbmDo69PZFBlGlBPYWUYyanRS6XhfCFgmY=',0,0,1,030109120913),(117,1000,'e1b3c4676815b9f9ec827827b6cf93b51bee0d862b1a41c70158543134cd0a8352e55a9310164cbdeedcf67b735d8041d6f27fc9a2a75ac8b118b33fc15c5a55i+IvtLdkGW8tRUq8iNblWQkCkqJOXNBWT/QYEvWpOe8=','5a1dfe08834e7aa487201e06b78f26490bb02fbb745288d197102218cd9a2adf01a51f6d94df00cc7fec908692fbdd214bc07e9e03ef40a0c261db705bcb4a90/UCWhs9e/G306Vk2mbfgHg6TkKCkK56CCQQHVUfHKmw=','cd9bb446123999b3c90464d05c9d67d5dac7e84dd9b25174999aa38b8a5099574a0a11cdb4cb029911b3ab6c39dda80cbaf12ba6b0fe5414cfe11e705b3409e3/ZqULtDuRm/qwFBHxCv8+chyWJOISq1ZFQEIb+STV9s=','a03a28594e58343989b6c382832d2da06202b9ed2939a76407a2681d2563f735946161c1592bc40a976ab645cb52b67abfc73b6ce32b0855706e73a4c8be7375a5PbvrYe4+uvBmd3UG3Lj/Ud19sXncaMlub02MZ4NBnO28oNz+nCepmzIgY4w+e9','8fb3688515927c219e53bbf4c90b972cf8d987d84a2f05b355a75d8b6bed82809c1b5ac83ddf59923faf291770723d0e039727c7d4f2d0d47b5d3e12279b99d8mK1Z5aTK3sYXmfTTJ2Yl360X8W/2MAs5bRWCOCjK1PU=',0,1,1,010413091465),(118,9,'bf8a44ba218ff50623eb4b96e7a0b19adecdcf4dda09cc0308870134a84c41aa55092ad9ac839b665bd37a5d373cd06e01432b5a321be14debef75de88cad716wfi2a9E1sYnOVylOzQR9Kd7MK7p1IMebQDp0s5SbQrQ=','e96d1d1aa6ce5de2988daca3cd30e98d508c871e700cdbcd4de3cbae291d3fc40ba8b4d6725435b172be6742d5d4b72701bbb2f14c1dc1f230c35f7195635151hgmIGl79tP1t6C9vZO3LrbBNC/XVd+fZ13CJXOAbYMg=','0fcf8fa135d15b8ff0f2109b2c569ddf8434bffee77f2423b8ba198aa30fe52fb310362fabce41467274302095e63bcf4d4c476189eddcecdf06fce0b6d66b58Uz0d/1JdTgfnFYBvfeC8OYwGe+HOaOlvNx+vkQfiVjI=','f47529140f854c13541249496d03e52501c432d511ac9b0bd9c6baaf4f3e9d9fa52d8a6ad6bc914b3f7cc6dbb1baf438872df70e48b2b7b09f4a55da489bac0f1ASP+LU54ZazkCvvsbyXXBbMO3vqcnVN3p/BAikPYAI=','ea9ff42ea2f7563c35fae6a816cb55aae2f4d5044de97c3a802b57e86fc40a699bc3f8c909187fb49f4fd9d30ee959439cf0e3fd44114b270d0b9f89b410eb3dlHEpL71wq5ct2g7ajXfH+Uigun1wJVm/hyiYhTkj5j8=',0,1,1,140523010194),(119,-8,'852bd09ec009e8d26d152d852f3a9f7af79b3af179f175fbbc6db867b0ee81419b35dfc15811d3d5a7fdc465f0e5bc1dc159fe9f6a191d011d4f5fe7ed8c6d7eAs7IrtI/hxH+w2/2eD+8OP/NdDsSfDsPU52xFRNx3kXaJiSroUmyB2W79yGvRiuF','29ef9a726a299d6945a5bdfc86d5b399787870fa83bc93e214030fb53a09d2a88b1bf812f8c010d572fc287decc87ffa051f6d206e2a06436b2a30b9cba97adewrwltpmoJCopknM1rao9H3y9gkDh5MFQu9L4q6CTQ64=','426dd44c3190758785cd9d7c0fbb0dd7c6c2e7bee89723491861a20ac6ec3a8de0081136da07f861a1b30b1fed3276e7c296915010dcc9eb2ebb113629a207638bKzvhLmnGo+4h2kG+mKvhqgbfliVmzgEFg2fsra8ok=','318bbf9fb94d4bf5ca3e78def975ec78c66c2f187b5869e75e7b823b35e535d7043d46c72f7c6cbaa4485062f15b9d551baaae075ecbf09cfe9ecbaaaf039614qxYqSYyAROsEAWTwF7zmDg+9X/l4uPHD+4DkPNfpTlQ=','9ed8d963e4df96da70f62ae58de965de187a8f2fb3d3ba82417bd5b0d6b14bc2b384cd99ebfd385b59f32f9dfcbdc5a04b1199569f8e8625ad6bc060f922f6b1FpP20L8kbGzSv2bGmIhuaGO7mqLl+jZrAt4m2kD/rNk=',0,0,1,011618091271),(120,2002001,'a04b275cf603a23adbcccfbf9b0941235629df4842e37fcac8c137d8f73be1e57d2740e9bafbc3da7d6fa96065151969fa3fa38a266bc1da46cbbb418279e24cMirCDHdjWQ4d9XwEOCLhPRMt9EzLxQ8zaky4cgg7VJWt2IEJl7Xyt96S+mHJhzEz','4e0a2d3adf0fedde1044abffd19fe18a792f39c1d5944ffa09970be7236a215d35308066533f7da5b3f0f5c3514823cb571726c80d380a69da26762c2c912e6f5gksCZoeDcqeYbNv4pT8o0fxP5KplJ1k6DrlqZZO6Y8=','e2561b629647cbef2d96ebec1f0a18bcc9a57e0235ec51ff5dbdc0efcb24c49820182ea41cae7f993f7ae44af20d9dbc49da515774d6f1c5aee0793f96bb3674OqXw5+nFVaLrn3DGULzVswDzQoIbYfyOPjCqQ0gofvs=','fcba85177aeeaef5bececbd58c76da887827ad4cae0ad729e092e6870d75e929917becb9d60d6e7f1b32508e7108d63c91078cec37957fcd519f3987d0baa09ah9JY0hNEvi4YVKls0voj3CP1PckX/y5wA0OGfc3+xD33IGTx3mm+do/+n1TVE5mc','3f1e8de98761a45f33b77b312dfaa8599997c61f2a34f3dedcf931181dd641976ed1267836e7867693c27f780ba8827ae24084cff66f82357ac807a24b27055cbn0iE+dWlrkUc4VvnBierQ6/caGixOrf5PQRvFtYQu0=',0,1,1,130118110057),(121,20020001,'d20c402bd18c40766b72a9078234468c3e0f68c5b14375175c019a27ebe591755b1833d3ad435d35d6f7771ee800084a330fec6ea442d985cfa038500ced878fvqv3cD8z0+F7VoCWwJ4d81lfuvz+W6HY5+YbwgowCu4QrNJ3t5i1mLfaLx8cWBkL','7a007f43fbe24efc5fc64e1d17d012ea9eb5db9a1bc953537913173dfc2591904f8dfe9847712f2243cf5636d496bc0ee90a2ed67e9b4323ddc3076fdeea7bb3ojQWImky0YNn2/IxKmMxN1+vvaU6ljvyi2MPoxWjPmPJ2rt5P9lsMmKZz8dtSPf2','ded7c5083eacbb96729fc9c20eb79297ff4f477c5f80af859e0dd78d8b6d5970ec835e5fe41158af2d46933cae6b97f8af37a5eb04c6d438f1065082a683e29dfTg+hwOGx94vO66A73g80ORt3g4h7iiNwWt06yDOE04=','db9abdd8972ab8e8cb3a9278d868fdaed414841a0d18fa44dcf6899c9ededfbe31910ccbf903b5babe4b9e4fff4116eeae71334ff66ac819398434270240cc5aqAZYllPQFzgtjlIs4LFiqsu12/ueYizrNykOa+vqzHk=','3694d05784e453695aa8f0448da735469940fd726080e2cdf3f60a0323d6030977f3d8fc17f99e0977567b8cb78df73b0a621c41eb032ab608fdd854ba9e5a99lTw3RqJ3FhPgT0DsbQ0gUKTGMeJzRT8j/Qs4feVs1DY=',0,1,1,130118110071),(122,132423,'9ff4f6a0b05c780c9aa4a882bbf056252e044932a950c0c2c0615c0a9f1834f71589f44b0c3b864ba47df69052accfa1e3c9ae79a9d37e5065da305bf26347f1osODNXfgFrjvaLfSN+p96PQHeYgu95Df8EWo+Rh4gng=','8eeb258ea865efee7c6fdbfe5d8a2c6be1099c2fb6647e07df44c7969d1947927963910a78b853f8691eb07aded65a92b9e691cd4ec37dbe619082a721346a07Dr80wH6VzD/ytZYRbS0e5FCdKckvMLcsD/srCvzAb7s=','c651f9ebfb455d78a5f1fdc5b800d1d7a555ed6f3634d6965eb0a65e072bb1f8afcc488c8789680522bddd28d4196ad667212ccad23141465bc8248985881ee0BvcvISOJfuTZHgErTzus1DhJ71njRFFM24yx8+ntA8s=','53426106664370e2c2eeb44adda0fd51ec082bed3fc91f05fcd9181cd96e770fe429d4911bd6690c7ada5968326142cd7bd12233ce70952dc899cc4a105af2adP2xKbEBTxk+YKtzXSoY5CIm3og2+MRCjN9aYbtRKTAs=','233fec74352968f481124effb75a9805eb7a87c82989054635307d7c3cacb270f613fb7825b84a8dbc22ed633531170aaefe5d921ea9cd65226c8501378235e8C+CWY9RDqtirBObBdndExU8kyIv5vfp6n3c37sAtqQI=',0,1,1,010401040621),(123,1234567,'3b04ee1f67fb6b0f3d2048cd066208c948d1fd8f4908497717e36abb9832bc98f31ad63a5735e74c8e8c294cf4d65fbe40f15751f75ebe0efe65b86a6959a024tkfpLiisQoIZKujsokVwuQzawfofLPyoEDC83nARoCs=','fe2186407e6f72a0229511980adc95ce5f292c31d09a4a19e2d81ca57d9d253ed4174591ed81088890b363aab96f52bdd1f8177a8839afff18932200dd5d96d55Iqr7Jnm0ZpezrG00HDH/8IP7dwzuvbUYVJXbs8bOVk=','88455cac6d2ab01acc506cba8bed003facae7b211945c9e5fd715eca3586fab898e928d12b5015b5a6361acd004aa6f4b20c302fd29e706ee40a27914c58d27eydT0cvXNuTxO2kEeaeuNn8jABvLejCQGHy57oEYGkG8=','b57bac9ab6623c8a76ed4894a9d52f010e0663cb0fb683eb15012fc89a60efb714a2f0d2c5b394e31620fa03914d6f3570c933d2d30c26eb8a431b932875e00baj8NEw5rWnNQBWJUqHAUL0sH2pNzx01BN+MD55S5EahbAO6BGJwNaWLirjFZ0jhk','1e7e437211f5ac502477f486fe527bfa92beaae9837e415afac4d8aeb63d0b19a9bf27da28a523ccd0b929c60019383a96bc5e35b007876f8452c3e6b46cf9cfWz7VQa637AxU6OroG5J1cYqMIeMoonJsPMXtAvCS/WM=',0,1,1,200519200086),(124,2147483647,'7e1af9bbb184d20fba6cf520868793dd3d13c5dbdeea6ec1cca73bc9c333941c587d97a939bdb3a842caf1b409e21994212c132906c87678bff31d83028776f5aLKVkQWw4SCwrp4ANzLAz5HonYdAEQo6rDicXlS9ZXY=','d7d62d1fa41eeb375ef0e15748292826e840e996532541ecc99dafa388094c7a7205759ddef2349fa09790d94c4d44f55275b5835b360dba572d0f810112ea2f//RbGZ2gGEDS6GhfCnQ/RLMpPNoOkteI5DW31WysEX8=','87a85cfc58e19c34af0342ff4736a8f13dd6319d800c2e177ca17e86b559f0b29fa7e1490133418fc1aac6fa0c4846703a04bb8f97d1fb30874ee779fce8d1aboO/2uJtwzYc0VtvXhozzB/kSMTq6nxzlkf8JjKXG67U=','467326292e05b83e7b884cb6cb2250b95678fd6842b961c1e341b3018b72ee43401bb492dccef32a7447221dd17a38c1c897adea738982e9540089102c773d61KUDFMujsQei2G/ydnJs4tYHV4I+MRZKroGJisZjTR8MI45PxU1ZjlQIPNgEBjCWv','f4e18e2f0755450ea48e312eac2fc9c0fe4264f8d6fa071b3ed7b73970718714c2972cd1eb027425ea123396d659903941d1d8e211a92c5a3246ae52033f4ea0yniqKuXeda7o/hcDxxGbCYbfaepMQkX8gycmrcHDb1w=',0,0,0,130111130186),(125,2,'ff3e351ab98563106af3be9446a8570b9742c09e30044f3b90cfcaf244fde547c146f07ec5e54b0116d511fb721994751e4a62a2ad302bfd9f4e7b0ebfad4026BEg6/Bpo+DlTeZ8aaOAREc/RH9wRkLkVEls4k0EHwtA=','3e371e02aca58be8304ddeb2d8db9694f4fc1e9aaa1951c6ccc36e7e2783d762262beca5e093694e3031d788906ef29ed12ec1aa02d091b70685aeec068ee195ikyRufXkUafKuKjBEgD2Fvq5D7dKqK/H6zPo1J8rZomiYzlTXdag7xbjbhAJxH6E','eb11e0badc3820daf5d59967972f422245562aeb82f7c1ddfcaebfb83ffdf2ded634278ff68c75a77e5adfa6f9984020c5a6b84070ce35ea37fcf973087739d6rOvOT2OQhB1SZX8UIgWJV6TMjoF/8eUYTNdonktQnf4=','1bf0cc39c56188a123898eadd17bbb3259a95944bd3af6b512363cb76768f686f046016a84d32e1307fe354711e11d0b041139b8c509cd299ef84d7b235b33b0c5mdmL6GMGaGz3cVBY2SW1yCTrVBH+O4Y/pC+rM+HT267oq2XqDU1z7UM1+Coql5','8abbe4755e6119541004d3e614296827e4813fb277ed0de0c3c5fe60ab55cc7d523c7b63fab57de210532c5021f2a8b45b154123758ead3cb313a2121a59d1efAUkwZrzEfcmZNOUAZWuORfKxB+Z9FMppURij5EVwNk8=',0,1,0,230914192021),(126,3,'366d91a126d6ebe25c35a3707311a46241fe8c8a87d50936e7e0aa28b95fe9134643dd674fc6714c479d60059cb8c0018e8aa5bb15519a47537d1db01e4bce03t9ouY6xMpZsWsy2XEbCLsJzOoMZX6aGzLeUniFeDAUWX8IawFL7dVawv03beEGyX','166deedfc94824d40cd90eedc37c905bf2e4560cc1560268bfc4fd82aa2bcdca26707b189fa3c4e506b1d4ea6bc2c531701674df1f4bb3c7a7a0b39bd2f9a447MBjWJouC4hob1Moqoman/tzKB4WDnRLKbQTgK0yRXv7SqkkTYKJ9M+TZkUTLRpE7VaUGXLlRppYoDE/S092nWw==','aacbd6a9ca24a1d692c4db8c79fb45eccb8735c7d251ebc81968848eca594e0e440e396a87cb3400f6c7447adae4d26862729fed7bd3ac0a5cd302adc52780486zZ5LLrvONz0P6Z/rU0e3lMu20GMcCwVUt/cuzz4tes=','a65b5875dc0b96318880966fe7097b4ec0543aa4b5721cec9373e7b5b940fda697925389f2cd27204020edad280d846eaeb36d85d75fa9aa9376f59a39b684b5UUsdIrX7dZdk0rakfuBeSrLcPKCJOsrnY780LgrVu/CCnjr2f3V8CADf3iscQx/5','4026dcc5608a3944140953a32ac7f0e806cff63d1b8b86f9751a98baca84c51ac67190f94623dea437ec44c1f43d9475c81b2d26584ad4b73f011ba28203af3awAIJP7oTe7xFMLE8tGrWDNiRfIf7y8Z0MZXaMfk6xcI=',0,1,0,110809140015),(127,4,'6fe5e378004d451c87ca0d2d60dc3d623f012d5f7d2a5acc61e6b160b9267a7910119d7d625b6e41d33930516717dbd183fb5d6ceb1535e4dd94bcf7375c5e41bWZ5rAMzkNH1qViws02Vf6WdZpARUGt3q/dmtFMlu3I=','7223b0850e879e0c96990b8df25dda550c110aa856dc5fa7cb9773119b673f313a158a5cfd1d35e148266a1bd85d2f4d7fc8177f286862c1483574e27ed3f66eJtPz/17PQfedj05NY6UpXAatuKCRX7CtV+YXymYdDp3qIQGsnWdjol+RVc8lOe4+','5a7537a7c8aad76b8fa24c744479e90243a525bcc88b6be5404edac9c87e7b398928f28e3320df4208468183c325ed36b156a2df1a68b9e4507078e1e7a9c63aPzgix5/G81lYvqND5hIDXzKxxKbes9ZHlllN5vSAsX4=','9abf840caab0774f14eb1f97ea55003132819647fe4086208725aade279c975373032842a7d731fca0896fc2150a49a508e00e180c4805f72bd1b8ff6343a265TJgDapFyrT5/XsU618FIOXZ09UJDO7ACedVSI+uf1Pw=','146e217e861d3470dd8e45d55c8150fe6c831036c534baada4fd6fc647af4039fd9f6a2520fa7ed913ae6d30408ff1969abf8e067575dbc6c07fe7199937da202TyfWTXY+Lq/NEWkN0bAAULhUdzW+fpI6r5c/1eh88g=',0,1,0,030818091992),(128,5,'b719430f3be9777eb07b51e89a4cc7fb1063e6a0603618b945f1f4f4b9ff935c246f897c2bbbb6dd9b047120f7f811ef9089e9739b7d19001dcb2dd52814fb83XIr1RKIiabKPA4kTUj7lSjwMBF7puDuBY8jlXrR/NL0=','61a5a0a819caab633ab4910eda402b65efc94a11b08faa5793517769434007ea2f514146cf5032303045d1ce30195d8eb064911e32ed62c71c2fc7d12e8c03aeXjO1I+05IIr/aKSJfsn0SImdAlAhPqUXIP00xauBF3ytONAB/05dlvvwtw8fTRmp','387f1cb51180745d82551261b19ebad26dbd170d64b6c58506bb60fa836e85a4f21bd0882e744a91dfeac9c29b038052ea59586286217f4e60fa58bbaf393d8btTIyfVuA0gIgfRu5zuuYOtGSqkKO98uW++T15ZR2GWc=','5b21e2d9a23b460580ac74fe738c5586fd66e6021cddfad5dadb07d99a2e5b6412fec941394fa48f027aa0ea10a07e99876c26a119dd06a32945c29bab001275eXm9MzS77zG15qiBdcXDcBpptdm7wzcX6L2pJZEE4zo=','dc1a0420a3990c86e79f31e8fb2d864cca83a5b63d90b61e79f29b285c1b924cea3d59511b55c495e2bdc7865d3d7284bbc75f509cfe0b1d755d3ed94bf2a5bbjMsxnZJWPnlskor2WMH3PhecR+lvAjU/aY5duqLwSyo=',0,1,0,182119190559),(129,6,'add2bacf82fb297236dbedc1e942bf0eac14739064ac96689d252a8aca369b2d6a0161719dbfd709add8fa18689a441ea2bbab1cebeef09b2c0219ff019fd1c3hdZyCXMm/+vIL4/aKhM+xXooy8XISU2/SU2Px/uv2rFDov5nJt9c7NUMY6Bzpggl','a79f09e11a3839ffe804b3fbe19f6c25836378bce7808f000a6f3cf53a975e2d5ea1952b3cf7792eb8bae17858e44258ec06a171786f3cede310e17c46fcc047PEGkoNZRPKW3vr7i3zJPgU1G5k3bx9PmPlBNcOq4uSeQkfRO8VqiBzYFA2/UyTqE','f67ac50c3ca752975bd080b9555dda12b6148393939668fdc72a98a0600745ebcb7aae303bfc1597016ef351831d71f32569210705bd2e8da76cf4c49a44a57cwXnxc34DAAA9QVYjJN3Gk6GEJwH3VIOXedrdn5vaTVA=','57a7e4c49ee21a74223eb5ac9f8179a9449a938833230beca93a2d03b41b8de02dd207e7b5be6d73360bcb3088af50f64ef554df3a100d2d50ca594b8e318496aSdzunKzWT9jpUDV+fm60mRXg1vdDvNCWlZoL8Du26Y=','b249b4acd19bda590724ee0c9408cf50081ca631ff15e605546300f56c61f64b239d167cd667cfd1c4e57d99e91bb6a4adbe2bccb5307251b4876c05e35532e2PgGLGycO+vgqwAo0L3n2/aBGw9yEcFCJBgzhl0JmKDI=',0,1,0,110120051838),(130,7,'e97c7e5fc975dee4650a61fb68ef3e8423ea8cd95cdac7fa7dbbcbfb6ae1438618890fe39cc5e9a8c3f8685f6a92062e687c8d38d74cdb2b00f6c9b39c5e7e1692NaPtVw8teiIeTx/k6KcCsl2PKp6hcUxwxYdB2IS9Q=','6e8db47face5caad877cbdce8379cd0e6d3c809d508259035f5eef81d029584b84e1036d2ff59a06908732f2e6a683c7468ef64c7c19b7141f7c87186485cd836cOh7bnrifWw85/pflcthpYbxNu4k7Z4KnMOH08aQXyDZCUPfTTra56jFwee+w6D','9d85fbad433bb07e500c58b5a16fc6f9c5e32b17d2fe35c4298e0a8639ef2d1a9aeec7c847e2b54cbde65c29458b74ab81ffb17bc34050c9b52060894ed83345UjOgUTvog/x1czTZjGyMir7SAXUT4GLL4wMMv4/lx4c=','f466edc29a820c893a5fc2229a485910d2dfc6ca6db71435a455e41558b7d43ed51cc46c4daad683aa9992f20237d6000d75c51b085155a8a15b33a17111243aBvR6KzuX7TlHYPDGNWzQ7OrfnYCVvgRIfkILdm3xswU=','903b5b12f1dcaa5c64548d355c2dcd5686a7049e809ffd60013a38d8eac4c18c578b25857bea53f441064551cae0c7d974d8df322482b42acfa73ffc00249a0doElkZuZ1SQlRICGTqDMPvs1Ai6E3Aa36sFsrlbAhzK4=',0,1,0,180507091432),(131,8,'5f4bf95de3d6175630c64f7c26c956dbbd35e49763ef31f125feb16c9b23dd18a188b8cddd4c19826309627c565a8e8f70970ea9b10052958ab9f2a1b49e6f1dWsbvUFTzobYsIT/Y4k9dyf+/a2R3mQJYXeIQ5myd+64=','537e6c097d9905427f06a519ba1c3da743804d15a607e3090959d83e196446df9d0ed5e9bb42a5f2a61e3122912df75b6b070a3d98e0d18f32a39a5a4b9038a42SM351xMPcaczxwulk+A5SYSDaplJouWiemIMFw4YdvKiu8xBF4xXlIuaNVe+wEB','ae88649f1cd1e87d88e8167f74823ad44ba8e501b17e43166a3865ad40cccaf2a19d8fad80880c44d2f917edbfffa676ec69ff3273bcea3504eaec3bbf8ea1a3Zt8T3SJ65Qx2OGt7z1cbT7xRQWq8MQvDf7TNZmtK3UI=','12793b4fc6b724696bb71ab27d0d2a0ba5e9f57e7d612a2b33fb8cc0d8e1faa26e614db1de19bcd730d457e2256f0ddbb0703a0f51d96d13e1dc9cba48d76727/bXhlnnRFPlIOIXeuA4xARhcZnxZmjhx/RpefdQBl3s=','73d1ba14e3cd7618a8973ccf4a3461c3e65b91be2bd23a6e18a5e164be0414c8dfd5f88e9192a96d2332495332ce909b493e2bf27055fdf2fb3ef4f79f6463a7ZQRA1kekOXuzALlt5Jqnp1idQqKgc1pdHiZN2f6vZbU=',0,1,0,130903080514),(132,10,'2b4fff06c8b3104a00d35785055bb1473a37c4d4ddd80ef8ac20d5ba01936262cf89a0e5fc13d48aa1754552312b9ea2451bebebf469f13e76a81d4d0e433f0fuWgW3uEr3y1PDO0mxQGubcuRqIKPBWjiLkMR1ksiAEI=','722d17a9f4d406d3d512f036d78a45c96a965e5e5b93cf19adc5a308df6f34d4e2a201f239d3e8f4b273143d1f7ceef31a458327b2eb2e006ef39d92ef6b4a495LD/cexVjuIxn8sIL3BUtljqakEcWqw9rqBEAmPBynJR2rhqy7tsOn7hkngW2QNP','014ce97923e1d241f470fec9c017ef9871bc3add3fc23bbf0ebcf3a85a9da7a76c0aa2afb6d229f48bdca4c17e80573ad2330b6b92cf09a8891e1d316fd0d7e3gtPSEtW4MPx9+QuTxAY+QqQGrWBacTHGFQJhTHd1Ah4=','1e2c957fe075e8561c57fc807c9ad25f55a266f4b25fe7861e141f10772621387f64c83c1c65f3614441ebd27e705b5b757e33f7087fde6cfe8e2087dd2dd855vEW7c/ueL/o5KZj9JzYxdgBCYYcbxhz0IGVER+xHJLw=','32a730da35edd00d1676e286b813f9eb6e76153690ed2adab40563b7085a1592889a6e30fb9e6ccbc10eff37538555d393745df2a88f42c4e0f99376b3c3a6c0Ijbte/UMNIhrgJtC6ERmT1q1Git73rw9HIueUnZFfOs=',0,1,1,010914090572),(133,12,'2ff8df190113b7db56a05893a84122a072d4c338c66ca33b2d4ad21d2e1cd76abfe9b83871b395c7a9ba7f7af33d3b95df0e00dbf64caa8c16061008d70ec4ceYbtPrPnNUInXjLVMFSUr9iIBOj7Zg/tGJ3yuU6lQnOg=','b7f4c837ab5f5445a439461e0b8ec5fd96fb57905043c1a71321564f2e42670c1e928e4f1fc40fa22736de7cea46766cb2981e4f6bf21e49890e347f1d431adfglt9wmGCRAQkLBn7VuacG8XKzvl8yrF+T2z55614h7k=','2852de75fbb99f0e7c7d32e26b0026b9c71fe08f77a1d62760af8d34e654d8fccadc20dd520ccb7de53aae35fa03fa354f9dd37a10827b46c27311515c500c2fBwxGW+ma6WxxGiIDxX2siIRXXxdx1UkPSSR8ugVwP9U=','1d1d615fe4bd6136788b5de31fda19bae05762b25de5263e69b31ca10d17137b51f9ffc885647026afef6f262e92d66e06152315d227d7f931ab3520a60f6422Vh9O8wQIGJ6s/KL1RMEmWjky+GUkWzYXDsEBvwu8LRAJ90jQ9/+qPHNGS5ew3jtB','b49198af6439111388ca2357787ca9148fe2166c6bc9de05ea62bd4cb3f01535ff9d20ade04e53a36dedf064e2b1456ac0f90ec8c45fb3eb5443f1f0592e2438/pnLaYocT7+BHNigIIa3l7I1GiIz+hYZB3IdDd9bc74=',1,1,1,190113161280),(134,101,'366a4cc60290beacf08c8ae450b86e068f8d267dce29e598cb4c43d858efd9578e0441e14de2ddc75e9b31195efb6314cabb918e9306f2dba6a850e1b4150ee7fckVlPrG/9aEBkQNzm+2pWfASfQa44UjIgETC1hp8OM=','ca8cc95a24c7b363fa80670f35c4015401ca8dbd659f2f0f7688acca0040ec85b3fa396e373715ec905f8460795a9a2012f4769d8a03a8cdfd99a17ab8d0a2c5BZ6bb/GyyuvzNXXNuTNQZHIIaLkP4vx2BShWeQ3A6yiBKh7RODKiwz0Bd8hfb2Dr','a953132824746ff48c05440be459b73e4113001136473f3ef40abb011dffd04b4f5e4a37ed598638a44a586a48d5af8d1a7a21f02671e6f790e412b8504977571iYOPIJAIjCBizj0cAEALlB4dmpmqIGqEa7UsNLxPgw=','88d26058e4df8aeb1265979e2a72c18d180b82ab11eb6c3299575f5aca4c72c95380944fc86fde63c65a933acec6e3dec2d56bf369c1dda08cab1ec454dd01f5JIUBrxGM/CD2z3keXvzBTh6qZIuo3w9iHlKVgoTuX5nnsK4sllAYcZ1JFMPkTwWK','2418232e098ad7d4c7e3d8c0e38f66e44d6379e9accbf77bab01e314bf2b901931fd40c6980f2d57e998233b0b47f2e061f08bb2f00af26856e110d3fc6562cfjSZPmrizGd0uXwdZXGeDYym+9o4fD/dkDPADp5pysxQ=',0,0,0,100114050061),(135,102,'0f216c8d205a378ea9e5b3a744220b3899cf34d8fad5179b795c81df89ff66c83bf6c1e72395b50fb3f75863c246cde27fb90bcce7fddf7da7ee953acc60aceaa1o7C32w1or9HWO0PHJp9bTkiFa2ATXO9tIy0a+rSlypojl7PDQKV47sqylgDVte','38bad86700d18c9f8c4935299e93f29dee213495e1fe34b10b686b6bc24983d66fa66221abd4720ce42a22d67a8a98f28faae02dff523b8a57d31711e2f09ae7EmmKY0RpoLHvZDwy0k0JMz6JpDBVyFcX4ZxH7UkvgZNAUPiPKJyG28ztnSjBSFfe','f6829d77220f703db8a30160fa2be58f5f34a9516d941ebe971e3c4162a0fb2885b4ae3c10977e960f5c8a6a286af5d42b9f0ddf0396dd086d4ce77ff8e2770aTIK8NzJsV050oaDELkB5Hee3wqDhZjgdb+HFcj5pNy7W0X3FzUyWow398YvDWRnB','ddf9e09fa4a3185743dcd2ff0dbf0c02ef95adcdb7c9e654333fd89d27c29eae54dcbd21913fee2490dc73f13dcf8f886e14cad82af688f212eee4098f594632OK37QefcahVzfmbl/hr10KuDi1W+148Gi7NR1xXKvue2TZV4Pa3CxkOGqpUfzlMg','bf9b70a02d1a0fd0bb068c1220cf185cd464af075ae5e480791fe9590d83a507994c74c15fb843eb7fa6bcc5659c63be0d1b94d4a860fbf9d982d9127445faeczhPwKcHBi8KV5o7jkGPAV0Ca380MDJPqUDZZZVicKZM=',0,1,0,011205240052);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `employeeaffiliate` WRITE;:||:Separator:||:
 INSERT INTO `employeeaffiliate` VALUES(5,101,2,1,1),(6,101,4,1,1),(7,105,2,1,1),(8,105,4,1,1),(11,120,2,1,1),(12,121,2,1,1),(13,122,2,1,1),(14,123,5,1,1),(15,124,2,1,0),(16,124,5,1,0),(17,124,4,1,0),(21,102,2,1,0),(22,102,5,1,0),(23,102,6,1,0),(39,127,6,1,0),(40,127,8,1,0),(41,127,2,1,0),(42,127,5,1,0),(43,127,4,1,0),(44,128,6,1,0),(45,128,8,1,0),(46,128,2,1,0),(47,128,5,1,0),(48,128,4,1,0),(49,129,6,1,0),(50,129,8,1,0),(51,129,2,1,0),(52,129,5,1,0),(53,129,4,1,0),(54,130,6,1,0),(55,130,8,1,0),(56,130,2,1,0),(57,130,5,1,0),(58,130,4,1,0),(59,131,6,1,0),(60,131,8,1,0),(61,131,2,1,0),(62,131,5,1,0),(63,131,4,1,0),(64,132,6,1,1),(65,132,8,1,1),(66,132,2,1,1),(67,132,5,1,1),(68,132,4,1,1),(69,125,6,1,0),(70,125,8,1,0),(71,125,2,1,0),(72,125,5,1,0),(73,125,4,1,0),(97,126,10,1,0),(98,126,9,1,0),(99,126,11,1,0),(116,133,6,1,1),(117,133,9,1,1),(118,133,10,1,1),(119,133,8,1,1),(120,133,2,1,1),(121,133,5,1,1),(122,133,4,1,1),(123,133,11,1,1),(126,135,12,1,0),(127,135,8,1,0),(128,135,2,1,0),(130,134,12,1,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `employeeclass`;:||:Separator:||:


CREATE TABLE `employeeclass` (
  `idEmpClass` int(11) NOT NULL AUTO_INCREMENT,
  `empClassName` char(20) DEFAULT NULL,
  `archived` int(1) DEFAULT '0',
  PRIMARY KEY (`idEmpClass`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `employeeclass` WRITE;:||:Separator:||:
 INSERT INTO `employeeclass` VALUES(25,'Probationary',0),(26,'Executive Staff',0),(27,'Senior Staff',0),(31,'Full-time',0),(33,'sample1',1),(34,'sample 2',1),(35,'sample',1),(36,'Temporary',0),(37,'Part-time',0),(38,'cszxc',1),(39,'On-call',0),(40,'Outsider',0),(41,'Test Employee Classi',0),(42,'Driver',0),(43,'Grocer',1),(44,'Grocer',0);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `employment` WRITE;:||:Separator:||:
 INSERT INTO `employment` VALUES(1,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00,1),(5,63,'2019-11-02','2019-11-06','2019-11-18',31,55000.00,1),(6,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00,1),(7,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00,1),(8,66,'2019-11-06','2019-11-12','2019-11-19',27,55000.00,1),(9,67,'2019-11-01','2019-11-07','2019-11-21',26,25000.00,1),(10,68,'2019-11-01','2019-11-13','2019-11-22',25,12000.00,1),(32,90,'2019-11-27','2019-11-27','2019-11-27',27,4521.00,1),(33,91,'2019-11-27','2019-11-27','2019-11-27',25,1000.00,1),(34,92,'2019-11-27','2019-11-27','2031-11-27',26,1500.00,1),(35,93,'2019-11-27','2019-11-27','2024-11-22',26,12234.00,1),(36,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00,1),(37,96,'2019-11-27','2019-11-27','2019-11-27',26,1.00,1),(38,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00,1),(39,98,'e5b5cc03d66736fe6ff77be6d96a1bf5570c248b6a5aa3b7ec0b9b61f73580e360d3fa8793391963e819de8ac87120dc1f5f88960c97197ad3429b8f20e42dbbNCF+tpg7fRKLcqaul65XQL5IzxM7ml3sR27/GIA2Q38=','1e57a784d33c6532346d6c6045ac4279c256581c28e656bff72ca8a37005ece89783836ff090acf6c68adbfb4d055d3431c5a21a35647ba47dfabf0242bea89bKS5LBSZIbFxzaICY9s7etcXJxtoM4Jv3BA6e1zKEg7s=','44935cfc8263e54d0d176d5d0155bf561e4eb0d9b33fd7a34ac3f14af72d02379dad0a0bb5380a4af716270d0ffbcdb0c2953c8a7a968abb3e95d10698ad76d5UQvYMIBSMG2SXCcW9McKLrNvFiLuqyX/CaliyrJxoRc=',26,'cfad804ecc2a21da175190a0ff8d73b9d465128a55fbb9c162f921ea8d1c4efb08ac36792be586e54b3b6ad06eb692be265928bbf6e84c368a1d360d4b232c8aPG1kDb9p4aiQjMU+vbJ7y/P23hhNUyaGoXSO0Exajs0=',1),(40,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00,1),(41,100,'2019-12-03','2019-12-03','2019-12-03',25,1500.00,1),(42,101,'98c3d3a48a2ebe0ffc6c84fac0fd8e00219150649f9c0f84dcafcecbafb1d1552815cd20796362296228337d1cea6ef46acf7facc41255441b8d185c09148f125bzV4jBHkgPT1Nkv0sVIvvz/rhU2TKobe6D7lIfYdA0=','0bac6a12b329c7d5cbdfdf09becb6111d259775636f1a299004ec4f4fd8eb6a82bbe13601666f13a63beaed56110203df758e4d67cb0df48ee240fa5ea8c022dpclNHw7z/nsmhfsZKB5oRSPMTbzx9sSeaii+heo7WVQ=','a88cdb76781d910b3351c4ceb89c2e1a9ec58da63102370fdc6b2a73519baade884f1f169b4a95915f463744362c3bdfc166b206627b92ddb5a8b2fb01b545e3CNtBNB4ULr6aRl8aFlVULI+TXtkR/nOM1ppv1DXtYGw=',26,'ecfb0f236ec280fbf9be8c8e82bc8d62c28722a29403f43050cf80cea6099b6d72df63a80a06fe875ef8e356b992567345be6a3e32110344b41340aefef93c9dErc54TKxXIJsMqiWnlg5MUKmcES6EO9eONW9F9Y9iJM=',1),(43,102,'8b7ed4db3910ba6b84b058cd1ece2ceb7dfdb800a5669e6eab4767cc97c38646e488c51b7e8a21c5ce5b1e3ad9079de821813410dad78f4e6b3b65918b714e9eDTQuhbTCr0lk7906xRubgqt6xhES5oGrs8RNg0h1dFo=','5afb6848b1cfc5f6bf42503aa90fa05049eae227ae72c67b3ad102e6441996aab242a3d76b8f1cb1b3da0eeebc2392447c2cae6bf7c4ff93fdfe641975cff034VXMZY2ymZX4d26DC2bKGoSHC0GCvlGJ4VhOIDFV0O20=','ad794e00a7e677277d1623b4c442310814936ab0e2687a67e74c89ad0b8f62f7d695ede9a0ac2f93b53f2feeede1020296d426736bfae24711908e1e29bb5eabbA6j+SbwQEFYsFYq6hFoDiReKe37ogDMVscnQOMSY9c=',26,'2b519f2403cec9f0e6717621fd0363cc07788b927d61348d12eabdc5e5bab4cfe5fcd84e654039d1ced9deb40893144a6ddd105405f9ee2be87fc88e89af1e14VeVjySyfZu/mnAkGnmzQZETP0KG92Nj+CJYAZTAGROo=',0),(45,104,'2019-12-17','2019-12-23','2020-02-21',26,12.00,1),(46,105,'dbd26d7f477b6e462c191168e4631a0605bf04125fbe5f064953aa91a80d264e2e0380da80a8b1964204195fe46adfde7f291966e62aeb75578534c21a150a42TEhktERbnUrwXwhc2R2SGpV6Ct1n5/rr1dhKyQ9DE0E=','2ba6a6505e6c776ee2580860ea3ab66c794301c4c1686d6250c5c9cecfc6d81ad6ae19a0e73491611c3f0d984a9f39abe1e529c18b912fc7d3c20f7271d4d600vlphHR8w7O7OpyikZjH2T1Gpq0LIj2c98ClALtTPalM=','63d4369ba0441c1892bbfb0ba7a7fb651f33bc00f27d8d487524be0b3983a9f1d2f97843a9e8d151bd3ae3db6128bc6942aeaf6db80cfe9d44a580eacca6168a0FzD6tbAFkVxyRPShuwl3Ov37C+/xo4tAXpx+TBzVqg=',26,'3e8137f081c4b466537eee09e01d264a06730cd234249a84e3edb7839b550ed87d185921ca228c23d3f4521399057bd9cf6bbda28c5723162dfedf1d480688dbXr7Jf3wJJ2IZM9y/C5NJFrA+WL9PTGiHrJ4smJtk9gI=',1),(48,107,'2020-02-18','2020-02-19','2020-03-07',37,1213.00,1),(49,108,'2020-02-18','2020-02-20','2020-02-28',36,123211.00,1),(50,109,'2020-02-24','2020-02-24','2020-02-24',37,100.00,1),(51,110,'2020-03-01','2001-07-08','2025-04-01',26,5000.00,1),(52,111,'2015-01-05','2015-01-12','2022-12-31',26,15000,1),(53,112,'033b4845595ccf5f8113e7016b60a743857a1c86d4053d8165e9671ffae61aac9293be745e5b9a7a856651b0d1d5ef4f05f57da1fba213766e6a8389b820510dslM7fxek2pJAdTM0ze5RBkegDZcihq9yvm2jkNr9K8c=','85cb0c13c0d72c48f683dc4c774eca37bb3339eb7056a851195f6bb0aad92841dcbef8203d8bb4079d3ac01b0e12ac1fa5a034961f25786cf6b7b64680503aaesSjxYIzrQgxWgZSBb8BD++c7+14ctOtJAGwXZ/eTIU0=','cf40e43566e91635b7e24e11d6182c69dacfd1688879074e30a55865e609d21066adc2645a2d37132ad433b7bb895c5cc7a0edfcb33ac863bbeefff5941dc49eBc1cMi8ih4ZZMPdfktEAfQx6SHYYm4txn+FzBzpMQEc=',26,'6e90d7f88c20a2d8582065b3f98d5c66352eed51bd03a3fae817d4e6b2fded1ee35e69e4c0b8db8c93f5d097fb794470619650bddd6dd16f2f0df8318af4e9c3GTI3Y1ZT5wqXAJ1Sr7aFbD5U2X/t23VMOsokg86gDJ4=',1),(54,113,'f3b03c4e5563d166c1fdea7ea371a38a5d460249dda1a824cbe59f3dc0e755005467a2f5f824198c84b253496fc9a4cded40077928d68f5f8212c4567bc32869oKSZh9+dY02chhh/V33XLd/Nu/lEJ/5po1xPQvqWomQ=','a43d0deece3a5ddb9a969412402baa4d41d079185a34d85e209952fce0c76f1515c4e9a76bb101a66c1fdaf773b7405392830f6730d20aca199be80e38670d59Kpth7OAclNRCBL0XCqKffu6o/poXRZa1kvEy40NRkzM=','c29dabf4cf41487dc67d6ed0d956c3fab1bd513fd9f4388f013a0fdfa30eccf3202b843ee02f00022e05deae40e82f226a161b95e3d109b2ad287028e6170dac3I0kJY0YY2onSvJpZo4e5iDQTIC8bV7lCYXVXdH0mls=',26,'9532d24332089667bf7f427134d4dee95000ae700706c2fd93bd7ddb989aa35678b6145cd56b0040194efd21e8ad90499d98b1398998b36a42e2f3b2a9a1b4a9T4D5rK81w8DM0+60zMy1qHcd+fm4REGpym/PRMLPzO8=',1),(55,114,'ee4c697a7fe1757f17e12eeeb76e6ed7a544cb5d77b9f01a0d65c890978724bfe9708ed6e3114509d090659c36041479a55514e49442b47f50f6c338e90a2df3q3gLDeu5m3qm9k5zoXPlrqSaQorxUU+sZBcZMirhDMM=','3ae75d3c3331a6f5f36f0bac34fe328eb1e6c13360670ab2b1b33c04b54f1e1e0abafefe1da777d1e94860d9deb2384e01e5e89060ff8097736498907ac847c7aAQLOwuIGWBudwF5EQ2BrNmDh+wGNMgNMSzx5zVThMM=','809c3045c9eb3f227809082f487899af70516b76e15079d3604506e0513c57567eeac5eab22fb7cf19904baf0c2c1a1fed1b0ef1fc5f41666cbb5be41964c13eTzfER2hBp1D7kTndwdMWdVaKWjz9Uhci8nzJbhDtHiE=',39,'28461050a96b08a3cb91a9b50d596084e96b6f4c7d38b12124837049bf94a455e84e21588b3260fee4b23edfba5bba453c17d1d986a642ce2bdd6043270402cfcOT1mxwyfax+pkGmkkhJGtXOX6C7Wm/VKQka4+uvT4c=',1),(56,115,'5c9db706e67f1ab48eff00dd39ea72cdf5c1fe5a690f9ec7085aaf19631153a4cf29edd173afd670896c94edd082fe8b77e55b2dac55ea2be0bbeb3530b97c6ezBc0raBqGzGclHry7l9lTrz6GYpS0lNADMQ39n3yVXg=','ce3ba63bbb2fdd94f0ec30af14acbe03c6d3fce65219ed1d0592032d3c8161901a5b79fae8facac77972dc5b1cb4ee18e6c9eb93e386c5cd638d27e2e34caeb0bxgM2PEAcCVL6jnddew/WHa8hHE/39PXBZcOQm6yB+g=','3e7357bb112c599eec878282e68f459dc7a2bd0adb3864b6a3176dd5cc4fdd2742d189e707b6b585c3be5489cd9163ea3b17e1ed71f30a3c82b287774957d5704BLZtFT0f1EpYJDeiHodmxm+2cUgi2OSlzvOtmpqN48=',26,'ffa24e8dab56f86a6deb0fedfdf1c049d144d76aa1a94e80f9b97acb185dd113102ba0970cc1525b99e4fb456fdd62c03eded84131d255ee61d25c7c0e82cb4f6egm+TsIZb5ne4lfzCelYzYI2xOkCzdvHKMQqQWwDA4=',1),(57,116,'2cf6377b4658d0da1c7feb221ef7ab76e139f5e4585479522a71b31167c693fc4a19f1637615b3448259f15b53a5fd2c42194c7f0c69735b4cfb3040d2555a730n39OPEsUB6OJX32EH1VmqpQwtPTYyXzcj0w1H7YrVM=','a298e314916696f2d860ef5d2da1afde5f1e100f978d8993ad5f08ff0304599620a8ab014ae5d7668f373b20b18c7bdbeab401604fa86eabe80fae6f8b02286dBYqUdOXT0ygF0A+YlTvV6k7wvH9YcOIyevD7KZpldMo=','6d3b239d49d39480270c819b13df246050e9fa37d6443d689b0553d4a0c3ef8458fbec9be4c9d578a1f660e68ed8d13022655a614c6d8acd79bee6d8fa1622f99H2Q3X08J1dWAmiRcwU0xJYzSjmWLHWt+mrNGEVnSOA=',27,'edf1560c6496c69cc36196068f27fe43c9143b2bc60bffde5d4ee9e1b77810fcffa198fc8f5e75681cfab1d75a574d12ebd59792a149f5f967ade2ef848234b5WU7umFtjUNFiP8UhwB37IV9e/0lA5ReTYnS9gahL6Rs=',1),(58,117,'15940c489c18e64f9e49ef458685a7223deded2bb6badec94a5718e5254a01bb303607931681f292cc4d570e3ffb064126f67af58bc5ad21da8793f60d3a97aakJHj1jXfy9DmfK0VVHPlUry3PZrZb0Go3MYJGtpfiMs=','d7b56f2fa1ace160c5f9d89b3ef3e8627f4751ca10c7b87647b7a4036dbfc150b46e7ae7cee6c6f3a73d47d605abd6016f0a842cdbd0dabb8ec12a9a2cf15df3zW8e3xnnb0Z0qejzh04MXQc3NUumRZ7TbBlOzeNcSo8=','ba5a6c5af39fff045387e6a844f610f258ca92f0c35cbbfeb20cdec71c199af165a289044b91b00208b59b858e17ba43ba2e1ab6ea276bcfc8c8b5ca3b603002tkaaBdpG6gaiZMXDbumVnRCxLta1oDOsd3ZQbxz0z/E=',26,'03b6d16132e92f4e800dcf448a39e30687d0f5d265c8ec6e9d49961b079b2383cf0ca939898c49e19a45c1516314365b703d523c6e3ece0bb94aa5d6d52f52c8SrvewytCb1XVQM4EljNoQ6E6LFIJAGoSbx5KZVvdGZw=',1),(59,118,'2ada00ba3c7beff6d5732f098d64195923a1911ba3b4a162c7ee967b34b3e1326112967dccbdf18d554da2dc07961751ec52e7a714657dc0fb8b278a356aeb31sYHrODjkqns7E/cOsnrF2ZhLZdknudEQcxMxwe41UpQ=','116adb008d16fbe04f8e8ce5ecce34f62650c17c45e36b1614471faaadb1a11faa1dca1ff3cd9059afb165229bea69e5a662eb7579391eda7c54ad4e56a25607qG9YbE852iAq0xrE1NYtUayp9bK1kiVI77nBh+ZymII=','600eac99fa5ed81d4da3b14ad117e2f898e93e0ce466d91fbff2867ae07f4d5b61abb23ed69f9bfe2f021b6db0049e40093aee8fc06a2dfdb4da5e3b329cc57fTVQTBPqotyDP9PSyAh4PXidpsTLaBmjKJXy9YpjdIjs=',26,'01d945b95bc7aa1c08e9700972e17f612ad52f3245adeb227d4306a520def0b95838366cd5edc7fae6290aa07a3bc51b4b453b5d8adde2d3a90e7825807cc2c6UK7Eyak2lTSneZWgtTpOtRejxFDnHyv9i2YePz4YcyI=',1),(60,119,'1a263c9fdd54d7efa678d65bf45212bcc22dc632528ad16d823b0dde40dbba57c0b044a48c33e45d9d5d606627daaded7c4ec8d13236cd9f372b9c9ee9081ecctDAGXLo5FXXA5V3qNqP47Yx4rdATF+EU8PMHkho1A7M=','6271f96fed5d8bd3588a06fb3db7c943d53ea8383dbdab8bdcf13370956ca7891090ca8caf9979799a14285fad572754be5752390e772e60eb9d16eaa239cc63JcxQtkA5tQzhnR7Dv0HgyW87/d1dVWm2UQRoGRcYNTE=','c6982be94a1db7a5a15289f89f2e4e18f221a477db9b629a6c7c3e9f13f9b8e651a6bdd218c804c9cfa5081d7720885c869997b936413aaafcbc60d0f5a9bc16E5/GjEVYG+myvsLWhY22ZenK05JISPT11AEcepbOf4E=',26,'d61f45f04fb83f0e2e637dbae2216aa39756fe3d19bc93a2d74cb44186040fad5dabf9f0d49fb261ab472dd06921f527d74fe3252d193c8b27fe52842f7bd09bOmCU4Ynby+TVGemXG96lJVtIRMzm2Lu/uMnbzubMSew=',1),(61,120,'2aaed4a1c2c920cac26fe49fe1902e1fd449d15d3629e571c100db94dfc7cd165abea86e2562886fe9a32eea74e66db48b6c7b5c11e0067124cd1e59e1aa9158FvhUOIWYlK3PCDiqVnO+EoL2EvTozXr32+JQxsgxRlI=','d0f2231abd05480f7a8fa29292dc15e2c66b720acc4e373866d95193f1f0d52afc4c2b8b15550d613039f0ead9211d3d4cae5e60869efcd86535377583708bcfhXcBhvE5CdKar5v1JjzJhVd+wzgthFBhCj99+vdIjgw=','1f24b768ac302baa0fa7d204de8b49d61f662898c9900968e36cb64fba25d9fce59a5250816bc7c9f48a66daa3899a30bccdb17cb93d1bb83706e13ff87a540c767vmu6V1OXsxaStUKQZiGWxU1OS9liL2FuS3E/XtIM=',26,'fad4f27818ad91d9b8705239ad0270aa65b65f111d5bb29b1826de150723767276f42c0d61a76774f164fc4ded3ae5fa4990f30cdcc9d50666e5fd49aadade1aQki9lBEOsc47Gfxr50N05VaTfV7Zvp0P77zLPZAd00E=',1),(62,121,'bba57382c5a38088d3ef2fd5d8707cc1ac8757aa4d467680dc231e74cf053d901fb1a9b489ce5dd50afd230828708c4081a6258d3ef2e323d5b9f804ba1c5eb3Hi57Z+zGWnEb9ZWPQGU441QrISyjvml+Ekw+QF7PqcM=','550e3d22ec0d142183340e51775583ba2f74d3cd4fa3abca5195aee034c9cee7ee56729f6ac64ffe9240dd99e0be53c2893b69894cf51a9c83227d41501a7e7bwKepduwIC2Rvox/ri2tA1UiMRZGCyxxCwHWsG5i3o8M=','8fa90cb6e4ae7f30156a4204562195ae8e0b3e89efbbd08ede3db50bb8dac9813f1953d8ad6be56ad1dd0bb26e690858b6c2019d55da230f7bf7476d5142c539AicWHvxhBrXKRrGgRbi0aKtVxt6Z/ow6EUL955BuVvw=',26,'af052dbc944c6b1edb53c3582ecda3ed4e289db85e1f3421f36b9b54d7693daff6ae8f3b7e8d0a3762f452323a0a310e31ee13657284bc0ce7dfef593455e7962RzuhsbdPIWOEMe6Y8ozWVffHzfo+rPv7ehRjJSVI/8=',1),(63,122,'fa5aafd798d21b9fb0f2c899dae243e28ef44e83f67a132fecbce3feab426647564230e1bf33a0d97ae3e973ed0bb508fb6d475b6ce2e3da5f0952e49b5082e9hnJ9iVKtOy85Zxcqd+INbSJHIzxPOJNCqkIYZKLlJjg=','c05751422ffa5fe10b4fdb03d92f71793c6154e59eaea6c500eac860625769e30b26c6a2069b8443c28d1fe4caf49d8baef20a07c3ad8a8ac048232cccac95a9QwzVdHcif/pYqF/YmHIHPUlBv/9RR+E0VMglPL296Ls=','5005bd3fb49b70b0d699b8dd054c9ee5b918d5cd0d6099b7d408791b5d4a03cd9922b5f41e8b4f8e6ab26f086a34d2378bc7a3e205729023f3cc16ee7d3300e64n4xpqiHqCjY2XXfKY+rmdYfu0o43OL/HkIdojIHhus=',26,'17226eb5cfbd5cea9d3657934146dc726f15c2d560fa0394c6c5a7bba10957bfb8281020dae70202dad33c55918114a73488572f0de0236c842715be4d08a87d/Utm92H1uz1FMJOsGTc2nqcG85WHQiPU8Be02NjOs+8=',1),(64,123,'fc03bd80d2e04792ed2d0bc7419cde9c1e345de19fae0d6379f19a35ede55b05620296fe539826e588ecd57791f1ea342335f758da635bf75971a9dc5ee02428S7BqQ6jQsfMNcpBvZKpkZ1B5FlFih3jQ08ufB8DKUE4=','36d4530878dbe0f022346738258ad4548532cf1aca8425f811f024631dff5d618fb9e0595fab1868f1d6b998ee57fe184b74dce5741d57e2413128b25561e28b5hqYzUf7LpQs+xTLZNMOyqecwgmQP64iuGQVWia2F0w=','b834fbd4444d8f9b28150b517beb3dbf96640500c5462cd6c6f54d4cb4e10c9ad58f392fdb4ae622c9960b46d2fa6a222a1776fc26e8ff0f3f985935dfa3be9039o0AAxLZFiJNM6z99isVOSx+fqjFODEZRhs8T4Evxk=',41,'ea14cd3492d0e10e12e65e411221eaa25bc8040fcc1f22f12498877326b800b1a69fb25f16b6ed1c23b839db0de0c8450974615a938e37698f270705773f7cc60/HsmiY/tIngGHRH+bsRhKBS1qihPxPQHjOYPI+/uA4=',1),(65,124,'7eab3408abe8bc19766871d3031275b71b29211ef6edc842c4a92b263984d0d64bb6d8c5e11432379191131f260a726953e0ddcaab1f9a6ef34810eb3085a7d8dX6n8ZaBbFoQELb6Fv7lipmpnq2hNi9Oh78K2hXfHXM=','15a07c50d6dfc698b58b173107d5817fa9c95fbdfc5dc445602d0155db21b8ed5a67e3cd9e20773983792cc35f89d0362df256f6bec44ecc013a6670bd4d071eQ6KP++yten2jM0sN9kshc263kC++m4SqQZ3IEJCIQ6s=','0d1b5f88adb8fa1dda1576e62ffd19fc611257b4ba23735f0698da3ec8142f3b90a202635f58487754aa6d2387e6418cd99cad8f6b53d6fcd2025325f6156b6bnk3jARACN6Ks0k8Ny0k+sRcYD1s72K3OP+0eg3HZCqw=',42,'b71a6b428e3bab14d18d1587d3125e012381bffddfe5ebf6da32a29ae8dc4c1e2bb8ce41d70d0093f2253d89bf1eddc3d222883937563b0ea007c21a9f1f55db0EVzm2/TGXX9Whqa20uMhgHKClb1Iv0NwYN41lZ8W9k=',0),(66,125,'1043c21075c38f586d65e97cc75a2e55ca27dc0b9a412361ef115cbc7209a4e7ea30289e4994aa601373f273aff67ad91b42737935355d88ef34297acfd61e152gU/Bh3KtY4mKf1d/F+BWpfGdys9Jh89oLmyQU+Lj2Q=','f3788029fc2b50d125ca8d94fe09742d064600eb6e2857cd482a0ec8b77c9d01c037ad9f5b8c546fd970794875b7c029ef841343cda4456da0a56ece68f1e7d2eKFnCZaPZ3wSVQMi7vjTISJ+1MH17ai7W+noFOkfUvg=','289e2c962323b6886db6f855bd7620c53c1ce4eb480028c8e506b3645fbf5cf13151d67bbec50fb257a18a9e906724b0aaffc2bb4b0815ac49a42a7a5ef24f22AKEkdsApQkM8VqwPbxLcFvT+krPvLsUokcXNX1A4xas=',31,'a200389c6bd7a07aec4ffeca286d77d76baeb33914b782e24b3e60644b66e0bd8ed6517c3ed66ed399f76a6d275bfdd658498978c5e96c4a9446ed88b52d9e66RaJWuRaXFuWopqZAKaGckhj/6mRsmNhrEP76uF6TH48=',0),(67,126,'245ac1c08fd5b6873f8e595ab19da32e39bfad56e7625f13521c046d91a0e81ec50dc5c1bc6c5cfa775f5b8bcddcf2dea994cea040250a37aa60cd2b3dc1c848UIV4PlWCyIJ1GE4r0pf4ijFxv9XsrPo8KUOh5XDZU3s=','9bd97b760e097578f9b626ec23fde6d68ff36397bd726369cc164b40b1d98fe6cb0f85dac2f971a10c40b237d2a8cf898cd8f21ed8fd9bcb25a15cf610a96f33BoyaPjHF5J/IpWOHfp7VUjuGwLYic1RS8yLT7p+5HRM=','e354e2908fff5c72a27247c74fb7f14ec1236ad748cb0ddff1970bc1d953f48ef5363303414b169e9c01af40a88b0d289973419ecd45d30266932d865b746445t4WPt2KV1+g/kJnMgZ2uWKo7ad5B+Fg4SK2Q7mO2/70=',31,'758912ee44cbeef9e567d0d1c45e8014c1b4938c6e7384695ed20f5e0e08444cd9de51ff6acd991a07c015b5cf20310f495674e1201d99406cd4fef691afeede/OfGiAcGOKCMkmjV56IvxFeK2JJuVLePbqN0/OH8MSI=',0),(68,127,'96d09640ab1d14c03262d829668ca6d90f7e58cb90a13332e3c187603cf303b59d607579b8e203fbdf6f8efeb6d9e512b12889b0afec6feb737f3b3b94405892+vVHSbDxSQY+MBX42kyq7P1WW9ej7LE0OmGNUUVl80o=','325508288bbb8a811a12e2d243b96e9db80b7294a194f672979db99fd1ea4a378e79a2dd8244fbdb57c727ba3201f6fadbd80b1bcb7c29f65d2e22f2bbf14b4bbYzWVh7IS2G16vCeqXNIrnP0hLVX1mdtTn+MypmUBl0=','f68866e1e609483e8a73fd094041fe7d31430dcad28a2b1e3515d618c1d516d5ac527344e53715b632054895e2c6266c7d2877c9fc6d012ee632475114dce3838XRYBIvl4oTjzKcBwJyZtlX6+CuG7LW2kpbB/pS/PYo=',31,'af32004f96b083944aeeb702141175460485bc163480e12598b88e6610eb9e31abc73338c5c755d65bff0b11225920d4de3605298f04fbba166d0c81387c4438GDGdzHODARYakoPDz8sOdKFF7h5UGUBONSbQ2kiKYac=',0),(69,128,'3b3756f39912348b4a20edb898cb7bdee1a81165018b43bc76d093537418e147a5f906ccd109a3067b523fe32f4afde12572b98eeb6738ce9af6123b7fc5d1ee4f0edOdoBhdlupPFnyVHGbzgUEmAPt8iOzRo8DE8wlo=','78b90a2e85a1a1eaf3fac3ab6d3f17d9172c2e4bff9abd23fa1d570134bf5c6eb710534f5256090ce984125d944929d5471a2c4153df527bd8c2c3c90f41fbe93YeSHBwBNO3WYee0eamdMHcjCeF8utK/teSu0f1Y4jM=','8fee154e3ccc6311d9c25342072b5add463cb6543182ba3c369ae493ae0de529638a59f2ea68305ebae3d4f71ee143e3693747d95eabaabf4b2f5575c36bb626PZr9sInhXef3H8/thO01K/B7WIn1C+NG/KQ3od6OJJ0=',31,'10b38a5d3d9b6561df4f804bd4168c7a04eecdf89cde41f67fd7fd9dce0e18920e1d61e4eb65b41c3756caa72f410d1701e4360664684649f1626f05cb58a95a5zQgxWSORQGmEyEpwrOgTokUjLfTm6EwWLu+ibmcLgk=',0),(70,129,'40b4dc004918eb89103a7346d07d94f0b8607faf4164e03f590e89763b625733cb3a818c6cf09d003520b27f1500f74bb2a3263912e36274b7068ea528e4ba33mAJTidTSIDPxWkQ6m6H0oGaAOsJFCdd707SKhkZE0Cc=','1142962fa79eeb8f8c58064f9dd1bb0eb6fea58c583f5f71d3114ae0bbcb41cb73a8869301d992a5496409beb3e10ee578d6ea10b814e85700013c21b48ba74eXe59hvql2Qb4MaAFv9qhmSkFNKdhh25UeCVPwHypoUk=','3e781a9068b0e8a95508bc2a888f23cd125496fa09fcc2188443d961c75ea0e4ae8720f9c39fb63224d17161ed5cc4d94e42b25cb03593ecaece640d04880531QjrdfGtXrgP11FQbhYjYkBQSDrx2MoYHs5V13OO1saY=',31,'7f423b642ec401936e2f38aab7ca73f39e378c0024b103af7ab16caf6cfd8707f88e1b62bf51b6fab3f777b078a31e345e61e6a0ab5246294a8ed1693ef89e82xm8VEzfT3Vk43/NYDBTd/gy4qhA5R8djBhZrxNnHa08=',0),(71,130,'6ebaf33ee9755ae7d7fd70078f28cab5256e207ab195fdec6ed08faaa2df773b61c71ebaf4d4490e47f62b4c498f9996eefb34d127410486dd1066e351ca4887P8DKE5ngtAJmG8jhSGN6tnYND1YZMS6mGWt0o3jJzDE=','bade29b051a38d936de32aa9070fba27fbb373e0197ae9acf7bd8d62f375302cdf8dc8352296e587e5d19c8eb31ca9d20fdf85ae9c108ca340de57869c10e9a078E8n92q/jcMgWBx3UTsnVtnUXKVpv4fuCwDZlyveJw=','f74ea3ba6b2b39b2fcb0bd8a1d306085e9b1a41ba21d7fd995f46a25813814a6718dd1f864e27fa3d07d14bdea97560bc4e6e063b262904e4bff2eb0195fc64543ecukRO/g0WuxeBeu3jmq1KZkO+EP9X/j6QcTFyhrI=',31,'7a88f48c08152d5d92b35626db1642fb5270f2c3aaff37648f858dc6cf0e6e96b537700176d3091118dc175db5b26f6406ff9601607eaabdf32a2eb81f4c90f6DRK5vsBe/suCXcTbNCJYxG9Ud8u19rFHya5KOptmIeY=',0),(72,131,'3f9a1e1621ca0c747a370dcf02e7bf54f37bb854c3f1c4a04c352127cc268a4f50e2152b06550e6361eb379f57e35b96d83980591ded82745ea1dd33b8df74f0ZbZQhjnyFs9YHszyZtNtwBd54WHzB8BN811z5wzQE84=','f29a3343d96d2221401b0c113fae9aeb64a9b5bf3577e9d57b10e35d980bddba78c72738f4aeddea37893720dcfae3927401ba694c7645e3c0c39c057015ff70C+ViIfVkgtwHGWxzVHI0+DR3OR5VtSqCjPjKFP/6O6w=','e302d51057c488f379e38f55ae4be2028f2dbe8ced55d757441caa40d21da9e262c57590b63c1087645554b8d8d57a696957f6459ad057f77a816a814c84c9679XC7OL80v2RSH6HLnEeXir5gNtett4/xgQuIYS1Agyo=',31,'b5b40b85e5ccf2804e5e3fde78fa12b75ac0d34a5dc242695ed2311639d71b48786a5f0d1adea502aa9308ea1185c2fa8d66f35ba5077bab6fbada5cca200b06Z8+zn/j7YVsfwhKY1GXtja3hjChl/TzRW3wRNeVjBlY=',0),(73,132,'1b7c6afb60e02f5c7cb6fc8dc32adc64623a0a926240d5cddfef0d17fcd0a4278bbd73cee1d765f245449c550dffa1dd593d3db76c76af49aa2a167e2885dd93oM4KLOUtLEKDzMqFZnBDjVfNPOfk4v14XFJUuMMiOrM=','b04c4da446952bd5a4bb61333b0537e07a2f0412989056c8820e85185bcf2893718b814ae20ba86522aeb59de0c4caf213c04dcd5476355821c777103838d1e5nK5008uFbM6a9pcbL4OYNHZE2nXNJy2VEVaAfiRTJxQ=','c7d35dad1fedc6eb80fc738c7061735f22ac6dc9a76edc66f6e6a6c43c4b2af8d7992324d4a354d0babe163f1303ae5d674cc3b64793c7956a73bc3315a2a1946pVknKEj4h+Iqy2+6W/iRMLvBG6Tc36p0j06uL8shZE=',31,'afe80365e791c1ad8922c1dac1296955afec3dec866a05a74332d8c19b0ef2bac2e0f4e3262d05e9d3ce1326608fe3a6f57405ea76feada79d1ecb90c6dd2b215Yh3sBAxDY5sRitZMKnMFBG+fgmNnx+AkA873G3i4+0=',1),(74,133,'723bfa30ecf8e647510c7b58748a8ff2c198864f55ee74a81a334beddb5978511618cf98e4a5c3f1a37e8136a0f5b68cb46e114b3845ff7a9d1e9a7f50f52b634AnGACzllzT1TiqN/bAsmfcm91da+yaaccYwqIl0SXA=','5f1dad0bc0d37a9df593664b9185d977cc06ab58b5978b70af33a05a084d53fd1ae6f08fb5d2d89453e4c72ee20ab218f7800a84441ed17d0765f8711cc2049f1MX1gY7qJkgfz60LHBSNXfEDpC7fb+rLPBL9FDoVq00=','92da8e52d47d2aab4b074933e2358ef5b69a9d483e607d761734a12bf4780019467d07e509d9ec79cc3e0f8efb580e8309c829e166fa965e0ec472fddf6c92d9A3FgsFBKoK1ow8rl2hekXDLNJrxbqAFHBYmXAKlkP0E=',42,'713da1bb7ddaa48e6274a76de12c7e74b2f3734d8e1333919e5ef89b51a8235471764419098bff4bfd93ea98bcfa52c20173af32a69e8ea72e0e2bd65b5338f61LnOMJ8Br+tDvSc1qraTenDosW9pV3eJv+cW1br809w=',1),(75,134,'e7bf126438c89627c674c64c6cf3f6577463c07ec2fe6d42a45f548852c4bab2d29f8a5a5aaf4735e5a4eaf30a9f5b1fc45666a5ec2c762fabe88ee33ef6c006pcSWR3iZ5x4cYXGmUzXegloFckD7wVGg2G1agR+xNvY=','5ae8ca36b14ef2b710ac320914b72a1b2ae17406066683ddac8fdd3dc369a95d71bc08da9a2904c1e9f61cc33c76a0503a4a2d2cee335765df482122ba197000WCkIh4hnzcA4db3nfyhE7DuPFnuBx2rRvHzJloS0xBY=','9cc862a523bafde090b6ed52e1e20f689e06d8390fcb5308cfe2039c8be18ceb49c36049ffcaba3293fbce1473f900502431fb4a14c947a7a88a9a2d782302197Yp6Go2z6lShoZUGojOsTgsK9WhkkYBLw4zfL4ajuxs=',43,'ead9459ba4beddad9d3300428134e6f303ffda40dd3d15c27ffa27b03ec6f45c0d38517ac12f0f6e2cf6e016ab9d0c929b3df5b41c679169c53b580b1d857cd16Uv/oFZ3MuQo1r32g0PJEvPGUwbLoLJjtTVQEv3xB20=',0),(76,135,'fae4a356b3f24efb7dece6d98edefabb2817b2d5ab98cfda913283a46c4653ac44c0c3ff41a620aff203661fffa436353522d226061b9afb7a8a4f2f574820cefxEaQ2X+6J4mQDsXwikLLCClhffE/F1c5jPBSLEXcFY=','9886de7a24962018ee0dbfc58c5f0d624475f27db154f6841618661a1c873261969c33497b5f789adfe345692164cd93ba276b860d2804459977b9ecdf9b2816+7soUKS/mATidJjhSfBb5wvaN8RLwqBLPFPatQUihz8=','f15a9a6c741c9cf466e62ca94400fcf88b620e2207b97b910d02af22b571a662f4d7104dbfef0cbc9f7433ccf12773394482bba83f9fcd144736f367e4b4dae18hMOYN7KGw1SRvFmYdjgWlD4dWHePuJNH2B4Wc9RUD0=',31,'e5999a9ef4285418bfe022aad6246705af121f459195a5d5dd4070d27be2b6ace5fd21fce37b4255ad175e7ed9bc944bb92cc09f13a2db1caa5e739d4f977a67KauCs79ww/eCahbXCaOfmcoz/fzQR959GVph+5BaXhU=',0);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=235 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `employmenthistorydate` WRITE;:||:Separator:||:
 INSERT INTO `employmenthistorydate` VALUES(1,59,'2019-11-01','2019-11-06','2019-11-15',26,5500.00),(2,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(3,59,'2019-11-01','2019-11-07','2019-11-16',25,5550.00),(4,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(5,67,'2019-11-01','2019-11-07','2019-11-21',26,25000.00),(10,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(11,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(15,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(16,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(17,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(18,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(19,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(20,68,'2019-11-01','2019-11-13','2019-11-22',25,12000.00),(21,68,'2019-11-01','2019-11-13','2019-11-22',25,12000.00),(28,68,'2019-11-01','2019-11-13','2019-11-22',25,12000.00),(42,90,'2019-11-27','2019-11-27','2019-11-27',27,4521.00),(43,91,'2019-11-27','2019-11-27','2019-11-27',25,1000.00),(44,92,'2019-11-27','2019-11-27','2031-11-27',26,1500.00),(45,93,'2019-11-27','2019-11-27','2024-11-22',26,12234.00),(53,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(54,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(55,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(56,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(57,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(58,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(59,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(60,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(61,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(62,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(63,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(64,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(65,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(66,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(67,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(68,96,'2019-11-27','2019-11-27','2019-11-27',26,1.00),(69,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(70,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(71,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(83,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(84,96,'2019-11-27','2019-11-27','2019-11-27',26,1.00),(85,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(86,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(87,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(88,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(89,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(90,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(91,66,'2019-11-06','2019-11-12','2019-11-19',27,55000.00),(92,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(93,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(100,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(101,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(102,1,'2019-12-02','2019-12-02','2019-12-02',26,1500.00),(103,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(104,1,'2019-12-02','2019-12-02','2019-12-02',27,5000.00),(105,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(106,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(107,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(108,1,'2019-12-02','2019-12-02','2019-12-02',26,1500.00),(109,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(110,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(111,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(112,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(113,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(114,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(115,99,'2019-12-03','2019-12-02','2020-08-21',26,1000.00),(116,100,'2019-12-03','2019-12-03','2019-12-03',25,1500.00),(117,101,'2019-12-05','2019-12-05','2019-12-05',26,1500.00),(118,67,'2019-11-01','2019-11-07','2019-11-21',26,25000.00),(119,67,'2019-11-01','2019-11-07','2019-11-21',26,25000.00),(120,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(121,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(122,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(123,103,'2019-01-01','2019-08-20','2020-09-30',28,5.00),(124,99,'2019-12-03','2019-12-02','2020-08-21',26,1000.00),(125,99,'2019-12-03','2019-12-02','2020-08-21',26,1000.00),(126,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(127,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(128,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(129,104,'2019-12-17','2019-12-23','2020-02-21',26,12.00),(130,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(131,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(132,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(133,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(134,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(135,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(136,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(137,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(138,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(139,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(140,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(141,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(142,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(143,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(144,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(145,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(146,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(147,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(148,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(149,105,'2020-02-11','2020-02-29','2020-02-29',0,6.00),(150,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(151,105,'2020-02-11','2020-02-29','2020-02-29',26,6.00),(152,106,'2020-02-13','2020-02-13','2025-02-13',28,5000.00),(153,106,'2020-02-13','2020-02-13','2025-02-13',28,5000.00),(154,63,'2019-11-02','2019-11-06','2019-11-18',25,55000.00),(155,63,'2019-11-02','2019-11-06','2019-11-18',29,55000.00),(156,63,'2019-11-02','2019-11-06','2019-11-18',31,55000.00),(157,107,'2020-02-18','2020-02-19','2020-03-07',37,1213.00),(158,107,'2020-02-18','2020-02-19','2020-03-07',37,1213.00),(159,107,'2020-02-18','2020-02-19','2020-03-07',37,1213.00),(160,107,'2020-02-18','2020-02-19','2020-03-07',37,1213.00),(161,107,'2020-02-18','2020-02-19','2020-03-07',37,1213.00),(162,108,'2020-02-18','2020-02-20','2020-02-28',36,123211.00),(163,109,'2020-02-17','2020-02-20','2020-02-22',37,1121.00),(164,109,'2020-02-17','2020-02-20','2020-02-22',37,1121.00),(165,106,'2020-02-13','2020-02-13','2025-02-13',37,5000.00),(166,108,'2020-02-18','2020-02-20','2020-02-28',36,123211.00),(167,109,'2020-02-24','2020-02-24','2020-02-24',37,100.00),(168,109,'2020-02-24','2020-02-24','2020-02-24',37,100.00),(169,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(170,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(171,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(172,90,'2019-11-27','2019-11-27','2019-11-27',27,4521.00),(173,110,'2020-03-01','2001-07-08','2025-04-01',26,5000.00),(174,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(175,105,'2020-02-11','2020-02-29','2020-02-29',26,6.00),(176,105,'2020-02-11','2020-02-29','2020-02-29',26,6.00),(177,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(178,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(179,111,'2015-01-05','2015-01-12','2022-12-31',26,15000.00),(180,112,'0000-00-00','0000-00-00','0000-00-00',26,9999999999999999.99),(181,113,'e4e2359cfd173a0afc96860e9696953d5083ab3de97b6c1a48d7716709247fa855951f4f97910dc32eed5aeb8308d398ba00b2fc2039b4a66d2566ca52ecee475gFONLlbh2h7Ka2gKk3NCF64f8fB3S5vWJ7JlwNHDMM=','5d9a4d4b3b9db1b904a22f38a16eac7b5f3034001ceb6aaa06abc257c8587cdb61be8c7f57db1bac3a5bb9b8c03c591544049f62d12f0d08f909a814c7a3ce9fRBOiHgFcF8f5gj7269zGyOWID5y+qLM8mVeVhZcLsGY=','1aed58f89092bca34acc0b8e3521d82984fe41e0c908fbf893c3a7b976946fa56cabda7a083f13bb9f3ceaed5300d4a885fb91f8e8709f436f146cd2dcbac1a3BusAaFyItoWGL2y7TrFTUcIUdsKax3R+y40ym46g1fs=',26,'cbff2fcc70d36e63c24084e2206019f94c29cab2d5d629ef0023272fc5f82b5eafbc22a1fe191bf949ff8e83976d413744e133a9ff7458fd6b32e524dd1c871a8xXdG71gKo7P0Zl1jopBR6zREInAUH3c4FRpTJ0TWqQ='),(182,114,'ee4c697a7fe1757f17e12eeeb76e6ed7a544cb5d77b9f01a0d65c890978724bfe9708ed6e3114509d090659c36041479a55514e49442b47f50f6c338e90a2df3q3gLDeu5m3qm9k5zoXPlrqSaQorxUU+sZBcZMirhDMM=','3ae75d3c3331a6f5f36f0bac34fe328eb1e6c13360670ab2b1b33c04b54f1e1e0abafefe1da777d1e94860d9deb2384e01e5e89060ff8097736498907ac847c7aAQLOwuIGWBudwF5EQ2BrNmDh+wGNMgNMSzx5zVThMM=','809c3045c9eb3f227809082f487899af70516b76e15079d3604506e0513c57567eeac5eab22fb7cf19904baf0c2c1a1fed1b0ef1fc5f41666cbb5be41964c13eTzfER2hBp1D7kTndwdMWdVaKWjz9Uhci8nzJbhDtHiE=',39,'28461050a96b08a3cb91a9b50d596084e96b6f4c7d38b12124837049bf94a455e84e21588b3260fee4b23edfba5bba453c17d1d986a642ce2bdd6043270402cfcOT1mxwyfax+pkGmkkhJGtXOX6C7Wm/VKQka4+uvT4c='),(183,115,'5c9db706e67f1ab48eff00dd39ea72cdf5c1fe5a690f9ec7085aaf19631153a4cf29edd173afd670896c94edd082fe8b77e55b2dac55ea2be0bbeb3530b97c6ezBc0raBqGzGclHry7l9lTrz6GYpS0lNADMQ39n3yVXg=','ce3ba63bbb2fdd94f0ec30af14acbe03c6d3fce65219ed1d0592032d3c8161901a5b79fae8facac77972dc5b1cb4ee18e6c9eb93e386c5cd638d27e2e34caeb0bxgM2PEAcCVL6jnddew/WHa8hHE/39PXBZcOQm6yB+g=','3e7357bb112c599eec878282e68f459dc7a2bd0adb3864b6a3176dd5cc4fdd2742d189e707b6b585c3be5489cd9163ea3b17e1ed71f30a3c82b287774957d5704BLZtFT0f1EpYJDeiHodmxm+2cUgi2OSlzvOtmpqN48=',26,'ffa24e8dab56f86a6deb0fedfdf1c049d144d76aa1a94e80f9b97acb185dd113102ba0970cc1525b99e4fb456fdd62c03eded84131d255ee61d25c7c0e82cb4f6egm+TsIZb5ne4lfzCelYzYI2xOkCzdvHKMQqQWwDA4='),(184,102,'7c359d9dafa37becc023494dad26eeee87b85bb210d5c49e5164a0675bdebe6d926113508282d951a12ffa983b63c2167f3998fdce3c267d4777524ff8120aa47RJPs4YYiY4Q8gvPljE/JH/Af7NUJKgDbq1RmbVrlZU=','8a3f3ab1a8961f86699946a08fe032fd3f856ff84d0c7e59075cb4ce768d6541defad35404f4ada387a5639e2b65386938a925e702a3aa132709d1f928f132f0s0uhBg8BCs5uz1wEhWHZHRHWRamBYb2VfHHyccsL5hg=','592f4e0c7edaa0cd8797eefe832ada78e402581d45c50139a80eac468f2afcc2d1bff66322bbc2db78f004468164415bad7263f1cdd63ae763779bb927991cdaKXU8C9Xh2g0ZRcRmwgAeeIxaQn2ZBQ1LFzBgO2q69TA=',26,'2907a24854a951774e33aed5e4d8d8cfc82114931e031e58699c018562aaba127ae68acff38ed2e3072fd6c6c94d9ae85b21858df22f214482196e7a4628d467AI+RsgUNwRgiJYpa57wdwXHSFzwwupOgWQSpZNHg+dw='),(185,116,'2cf6377b4658d0da1c7feb221ef7ab76e139f5e4585479522a71b31167c693fc4a19f1637615b3448259f15b53a5fd2c42194c7f0c69735b4cfb3040d2555a730n39OPEsUB6OJX32EH1VmqpQwtPTYyXzcj0w1H7YrVM=','a298e314916696f2d860ef5d2da1afde5f1e100f978d8993ad5f08ff0304599620a8ab014ae5d7668f373b20b18c7bdbeab401604fa86eabe80fae6f8b02286dBYqUdOXT0ygF0A+YlTvV6k7wvH9YcOIyevD7KZpldMo=','6d3b239d49d39480270c819b13df246050e9fa37d6443d689b0553d4a0c3ef8458fbec9be4c9d578a1f660e68ed8d13022655a614c6d8acd79bee6d8fa1622f99H2Q3X08J1dWAmiRcwU0xJYzSjmWLHWt+mrNGEVnSOA=',27,'edf1560c6496c69cc36196068f27fe43c9143b2bc60bffde5d4ee9e1b77810fcffa198fc8f5e75681cfab1d75a574d12ebd59792a149f5f967ade2ef848234b5WU7umFtjUNFiP8UhwB37IV9e/0lA5ReTYnS9gahL6Rs='),(186,102,'0838932e0a68cc11197cd5423e15438765df9ab065ba157a9dde0a260bfbc18acd19d2fecda1ca5b624e1b76d4b767d4f02dd75a40d90f652724b62a91d984c9/GWAq6pegb0UXk7Sz1yjzNhpwuB6xwyaS7zpkJ7oYfA=','b9a72379a0c92443e8a6eaea1022dc42ef9fd75f654d270d649c0155c89020770eb9fcb7a3b5cd9b997508e01abdb722d03d72a660df3e38cb33fc56783d898frkfgUsmtJvQ0CB1cEkYXEJcNtWz4nYNTYTfA0cdXD2M=','1004190c94640ca69b60b6dc1d26d1b745f9d1e8750d70f1f0108a4fbdcd541a638d580746b33e12fbda3f044457ec75829837a1d2eed1a1938dd87b7ebd3893RB8HAVlMM3o39Dge3XSm5q2AdeySvoZCenUj81V08o0=',26,'66863cd9688668aff5402c1ae00f8c90e3ce8719745ad06b2318a52ad11e0b9ef82f5d63514c1356826734892823749a7a54971c4720bb8354b5c454a742fe7fPnby1jyZc/iaYI4EOiStPd+siY4zTHMqKIbj7y7A6hg='),(187,117,'d703f670eef5aa5ceeda521bb5f801daef33de7632e702c67aa3e02e13e6478882cfd1b5c543de9da33b970358ab582b2b8de9c186c77545878f8d5d783b957f6CphJD2mICEXaFF24wXVnhX3fJdbIIgkoAHvoB28urg=','2ce9edc3d8baff49bff916c7cb82ae625c24b72631829c7131e430446ddff8b2eb4877a43623a5162a6a7523df75fda134658dbb49ae5be7606ceef39a63ca1b6qPezOquQFwYTDeP59cF1HzxC2Z8rx6ZqbtNJi6+WFQ=','adecca37d2e6892c12911090e326adab2714e26dfd45fef45e85a6a591bd0ad03bc53deb367d65c4cc1bad157a4aef43fba790703103eb59f445cf53fdb04ad0FvX5kGIf2cnbrwjqhCvdUk8JnLc9PcWZlKuggRLvbq8=',26,'1c9577481b348b6d6eb62d0eca644def06d642195159f350b7d55dd81a57828487061fd34acd6e3e964976c26b180772609463763dd8ecc9377b8fec6949621ansKQvfyYPIeukjlLzHv4ajehFNIjdKFGOB85u7WFhFE='),(188,98,'e5b5cc03d66736fe6ff77be6d96a1bf5570c248b6a5aa3b7ec0b9b61f73580e360d3fa8793391963e819de8ac87120dc1f5f88960c97197ad3429b8f20e42dbbNCF+tpg7fRKLcqaul65XQL5IzxM7ml3sR27/GIA2Q38=','1e57a784d33c6532346d6c6045ac4279c256581c28e656bff72ca8a37005ece89783836ff090acf6c68adbfb4d055d3431c5a21a35647ba47dfabf0242bea89bKS5LBSZIbFxzaICY9s7etcXJxtoM4Jv3BA6e1zKEg7s=','44935cfc8263e54d0d176d5d0155bf561e4eb0d9b33fd7a34ac3f14af72d02379dad0a0bb5380a4af716270d0ffbcdb0c2953c8a7a968abb3e95d10698ad76d5UQvYMIBSMG2SXCcW9McKLrNvFiLuqyX/CaliyrJxoRc=',26,'cfad804ecc2a21da175190a0ff8d73b9d465128a55fbb9c162f921ea8d1c4efb08ac36792be586e54b3b6ad06eb692be265928bbf6e84c368a1d360d4b232c8aPG1kDb9p4aiQjMU+vbJ7y/P23hhNUyaGoXSO0Exajs0='),(189,102,'2dc05f1be7b693b1e0ffe1c5f0144630875eacf95ea13c85430bfda53cb1dba66a8395cb1efa3b3ea72e62baaa082d938373d2e9e19e41448b42da9a1f27ea74gBNpu7l51nr/CN3uKG88OKkFrjWZAfrU13j+k9bODEs=','f26e84f90bb17ec7474a2d00892eb7a9d838e3f3f7a149d6c0f272320fc0d90b1d670ce927dc6da54c5082c8fda7c823592e4e754a5e04cc6e77e7d13148832fv7qFNbCClURzY7/9EbsdKuElFdZ2X8ERHbuF1OzfKQE=','7cb822458de05d42ebd6f3f122bddd67e3d8a36f5e0e7c5206df914210509efed4aa2041b339584e63b4bdd7c8326e45fd2f820888d6d301818ea82eea7950a8EL1tousm9B8FER6LCtWMNMNb0acdLJlnuBLAQba4iqA=',26,'e2fa71ba6c967f5c5ba6f7ec5fb39cfd320c78b86e8a8cfb770ccb73177633462f030d75965075372b40bf0a26fbd12c2781968cd4e6bce6d15edb7582076e17LxU+7lJyQzZuPQmYGuNnu9Aq4LEuQZ4hf4ZlRils9PU='),(190,113,'f3b03c4e5563d166c1fdea7ea371a38a5d460249dda1a824cbe59f3dc0e755005467a2f5f824198c84b253496fc9a4cded40077928d68f5f8212c4567bc32869oKSZh9+dY02chhh/V33XLd/Nu/lEJ/5po1xPQvqWomQ=','a43d0deece3a5ddb9a969412402baa4d41d079185a34d85e209952fce0c76f1515c4e9a76bb101a66c1fdaf773b7405392830f6730d20aca199be80e38670d59Kpth7OAclNRCBL0XCqKffu6o/poXRZa1kvEy40NRkzM=','c29dabf4cf41487dc67d6ed0d956c3fab1bd513fd9f4388f013a0fdfa30eccf3202b843ee02f00022e05deae40e82f226a161b95e3d109b2ad287028e6170dac3I0kJY0YY2onSvJpZo4e5iDQTIC8bV7lCYXVXdH0mls=',26,'9532d24332089667bf7f427134d4dee95000ae700706c2fd93bd7ddb989aa35678b6145cd56b0040194efd21e8ad90499d98b1398998b36a42e2f3b2a9a1b4a9T4D5rK81w8DM0+60zMy1qHcd+fm4REGpym/PRMLPzO8='),(191,102,'6dddb68493b95cef4e3f6d528e0e7447d1309e9ff5139be0ef653bc640e8bea0bf3bee5d4e8519dff88429e35dd7e62ffbd4f676e685f0274eb07c30be905a64+z0UUS9yCmzObn5fC+LJpcxk/DuJMmvxMrfI1EoUbYE=','174914ccf62484f35b689530cf046e845d3caabffa4f5a73e298c9a80965ec52cfb0bbe68860931686a115ee6f09e4773ce7636a9125aa595a56c94d7d1c551auVPnO08KTkWLUbY6ZEegLksFOyzN42pGYiyNbgDg0lo=','ab440c21a1545f1f31e140a634a9e7e8471d74802030c9a5768f468418ae0b3c97726db1ae603d63247c3c250070a978bf973d02777ab0902d366d86a886cb52FXIvA5YKTttn+LaMVS7mkllY3ELtfRhlydYtHuj9qQM=',26,'711887127688aea3af227d983a3918be4749546a761b930acfa75bf067c38c5cf3c7d85196e288809d46af38c4b72df5a861506ff49942193bb513ce99a26214cpDOJn9FoFlHuB3yCUnE055E0YTMg64GJgHlzRJlp+Y='),(192,118,'2ada00ba3c7beff6d5732f098d64195923a1911ba3b4a162c7ee967b34b3e1326112967dccbdf18d554da2dc07961751ec52e7a714657dc0fb8b278a356aeb31sYHrODjkqns7E/cOsnrF2ZhLZdknudEQcxMxwe41UpQ=','116adb008d16fbe04f8e8ce5ecce34f62650c17c45e36b1614471faaadb1a11faa1dca1ff3cd9059afb165229bea69e5a662eb7579391eda7c54ad4e56a25607qG9YbE852iAq0xrE1NYtUayp9bK1kiVI77nBh+ZymII=','600eac99fa5ed81d4da3b14ad117e2f898e93e0ce466d91fbff2867ae07f4d5b61abb23ed69f9bfe2f021b6db0049e40093aee8fc06a2dfdb4da5e3b329cc57fTVQTBPqotyDP9PSyAh4PXidpsTLaBmjKJXy9YpjdIjs=',26,'01d945b95bc7aa1c08e9700972e17f612ad52f3245adeb227d4306a520def0b95838366cd5edc7fae6290aa07a3bc51b4b453b5d8adde2d3a90e7825807cc2c6UK7Eyak2lTSneZWgtTpOtRejxFDnHyv9i2YePz4YcyI='),(193,117,'15940c489c18e64f9e49ef458685a7223deded2bb6badec94a5718e5254a01bb303607931681f292cc4d570e3ffb064126f67af58bc5ad21da8793f60d3a97aakJHj1jXfy9DmfK0VVHPlUry3PZrZb0Go3MYJGtpfiMs=','d7b56f2fa1ace160c5f9d89b3ef3e8627f4751ca10c7b87647b7a4036dbfc150b46e7ae7cee6c6f3a73d47d605abd6016f0a842cdbd0dabb8ec12a9a2cf15df3zW8e3xnnb0Z0qejzh04MXQc3NUumRZ7TbBlOzeNcSo8=','ba5a6c5af39fff045387e6a844f610f258ca92f0c35cbbfeb20cdec71c199af165a289044b91b00208b59b858e17ba43ba2e1ab6ea276bcfc8c8b5ca3b603002tkaaBdpG6gaiZMXDbumVnRCxLta1oDOsd3ZQbxz0z/E=',26,'03b6d16132e92f4e800dcf448a39e30687d0f5d265c8ec6e9d49961b079b2383cf0ca939898c49e19a45c1516314365b703d523c6e3ece0bb94aa5d6d52f52c8SrvewytCb1XVQM4EljNoQ6E6LFIJAGoSbx5KZVvdGZw='),(194,119,'1a263c9fdd54d7efa678d65bf45212bcc22dc632528ad16d823b0dde40dbba57c0b044a48c33e45d9d5d606627daaded7c4ec8d13236cd9f372b9c9ee9081ecctDAGXLo5FXXA5V3qNqP47Yx4rdATF+EU8PMHkho1A7M=','6271f96fed5d8bd3588a06fb3db7c943d53ea8383dbdab8bdcf13370956ca7891090ca8caf9979799a14285fad572754be5752390e772e60eb9d16eaa239cc63JcxQtkA5tQzhnR7Dv0HgyW87/d1dVWm2UQRoGRcYNTE=','c6982be94a1db7a5a15289f89f2e4e18f221a477db9b629a6c7c3e9f13f9b8e651a6bdd218c804c9cfa5081d7720885c869997b936413aaafcbc60d0f5a9bc16E5/GjEVYG+myvsLWhY22ZenK05JISPT11AEcepbOf4E=',26,'d61f45f04fb83f0e2e637dbae2216aa39756fe3d19bc93a2d74cb44186040fad5dabf9f0d49fb261ab472dd06921f527d74fe3252d193c8b27fe52842f7bd09bOmCU4Ynby+TVGemXG96lJVtIRMzm2Lu/uMnbzubMSew='),(195,102,'106357dc1c0e9675ddf18b944ac76697a169ef06164861fb7a9e54c5a516b6f34dc1618bb7bff790c04fe7e8535fb97635fa3a952e3ae456d6fee6183a7c55dbgbDnB7IFmqPHhbGMHuTna3gOJBimdNwK+AOwg7n157E=','cb849f13f89cab284ea652cfa7e77c0defb4b42588cc7fc2b800b062c1fb52053f0e6348e7c7b40523a3b1dcaf528f5cda0356d48399bac07e5b8ad1b77c79c8I3GxzyybvNIN61BxrAbm7DeaGcoMcdofqaAAJSQK1oE=','01cbce06ba5175fb4850c45c410ec07189cbef4cde32b9cd16934f1073e80660d33e9c8c4617bb273a95828d8033733884578d3242847b87494757c11d872ce2nZYJVdBRXX5TQ+IjR9mY66nxH4rFsl6Nj64kiVWqT+Q=',26,'647fc5cfd82e72a3937eea3297f78186c21896b3655ebbf39178898df009275f9933f8bee452dc830279c6dea17a15738adcfa540efee39efca0ec3dde4213c5XduHDBwTAvTMNAcZ7F5YZ88pHV6QhNxwbzxVXIjCFAo='),(196,102,'0f100561add0f99a80983465f8e4e3a08972e26480456abb152a850108278e9b8c678e2d0649ff42e1bef0139bcb047efad14f9b33fe931e60fad552800c498cWQEePyw5KnH4sRLP1Nmg6lE+R3fEOFtNnjxSwfI6Cs8=','dbfec3ec81b3fdd65dfba60ab84b2ac9bf7e98cbc03fd324cb54999d5263cdd118f556c38a84263915c9d465be365d65609654474b2cd4b0eb7a0baedf990bc2iRHlwClcMDiIlv3kPiHMLELctS08lKyH7kE0XHkDZJ0=','9b82bfc992157d229927e8bdeba78a003d81cc6cb89f7c5d56c3d0e57851e25f81e241ae6a53b149ed912ef088239dc121dd4668412d92c4a300f0904e43b9ccT+7jNRMk8pXeJjtMvz8yrWbv8HStNgHtYK26su6EI7Q=',26,'5a68d40f8828ce90ed96832360ed0067cdb4f39c7b26da78f15d5601d34897bc24c8df9719d2ddb69c1d1d269b86c063d8898168f6fbcfd29d2e2e2b5989ba39DOJ7LI7TlGGpbNxhEbf4D68Fr2o+DhOxSrMAuE8ys9Y='),(197,102,'68272cbd2253fb0923d59304fb82d63622de4f91db286229da625c44b040d22b806e01812cf9e28bc255e27fdea0404268e003ebfffe9d3234131c4e7b8b6e83Ekd6s39ngAk+TSGMabmy/18AbUll0GFVRaRiWjjcDsw=','a3e2fd34b9171067fc2f5d405bfd0687b96e955f395ac6a7e58bbd28c096abb544e814a2ac9db4011a1da4bbf8ad4fcbe976163aac79c05f3c4431caf1914252Xs57trsopbD4Z8zYWTX6UXWW33/7fn8Rzp/POSxKM54=','d11ea6771d430cb855d7f6617bf28e2db515294e7950d6cc4fd0668719493029ff4b8452fdc21f5f6fd455b83a3040d5c829093e6e55e4e0f776933a781b8c7dGEihuCAEJPRuKyICsBtfCAg311oi583l5eFSMFpOx9U=',26,'fde06c851fba7e3a51f1aebc6e721680d6a18f91a2aef1ee50db871171750b74050acc73454ec95c2ea7d994137ce1df42fa7d1e15c239dd0e1dad0c4ab0a215w6xL+TaUMAdTcvnkMN6RKatt0/6QJX4xX+kTjwuGmBI='),(198,101,'98c3d3a48a2ebe0ffc6c84fac0fd8e00219150649f9c0f84dcafcecbafb1d1552815cd20796362296228337d1cea6ef46acf7facc41255441b8d185c09148f125bzV4jBHkgPT1Nkv0sVIvvz/rhU2TKobe6D7lIfYdA0=','0bac6a12b329c7d5cbdfdf09becb6111d259775636f1a299004ec4f4fd8eb6a82bbe13601666f13a63beaed56110203df758e4d67cb0df48ee240fa5ea8c022dpclNHw7z/nsmhfsZKB5oRSPMTbzx9sSeaii+heo7WVQ=','a88cdb76781d910b3351c4ceb89c2e1a9ec58da63102370fdc6b2a73519baade884f1f169b4a95915f463744362c3bdfc166b206627b92ddb5a8b2fb01b545e3CNtBNB4ULr6aRl8aFlVULI+TXtkR/nOM1ppv1DXtYGw=',26,'ecfb0f236ec280fbf9be8c8e82bc8d62c28722a29403f43050cf80cea6099b6d72df63a80a06fe875ef8e356b992567345be6a3e32110344b41340aefef93c9dErc54TKxXIJsMqiWnlg5MUKmcES6EO9eONW9F9Y9iJM='),(199,105,'dbd26d7f477b6e462c191168e4631a0605bf04125fbe5f064953aa91a80d264e2e0380da80a8b1964204195fe46adfde7f291966e62aeb75578534c21a150a42TEhktERbnUrwXwhc2R2SGpV6Ct1n5/rr1dhKyQ9DE0E=','2ba6a6505e6c776ee2580860ea3ab66c794301c4c1686d6250c5c9cecfc6d81ad6ae19a0e73491611c3f0d984a9f39abe1e529c18b912fc7d3c20f7271d4d600vlphHR8w7O7OpyikZjH2T1Gpq0LIj2c98ClALtTPalM=','63d4369ba0441c1892bbfb0ba7a7fb651f33bc00f27d8d487524be0b3983a9f1d2f97843a9e8d151bd3ae3db6128bc6942aeaf6db80cfe9d44a580eacca6168a0FzD6tbAFkVxyRPShuwl3Ov37C+/xo4tAXpx+TBzVqg=',26,'3e8137f081c4b466537eee09e01d264a06730cd234249a84e3edb7839b550ed87d185921ca228c23d3f4521399057bd9cf6bbda28c5723162dfedf1d480688dbXr7Jf3wJJ2IZM9y/C5NJFrA+WL9PTGiHrJ4smJtk9gI='),(200,120,'34b78f4347d89bc5ac1389cfbc0a0c38b88cff132416a7133898a7192e37c30968c83a9529d51cbe1e11d069529df7801d021bebc1388862052aedaacb22b4e2G36RaaAN9mjkgp6UJAfd3buKK5ZDBvPIl3+YsR33U4M=','f8d52fd2317024d6081af18c7cdfae725cc7c97a4b1f3a990e257e425e7225239c2924b5cc6444bd71410739ab154f9b69e8c22fbc7518cf733472faabb2b3088p98UqfEK0WK7X/WP1sHQD2PEVK9J4FInlzp30Z63/Q=','4bd8b91fafcdb5c65a4ca6e89707d75e79bfbae737b096681610b74daf71bf9dbbcb5578a9809039419a92d69dc5ab91b9140db57e3c8ebe1e0e70ba28638840lZT4Y9lPgCcn6UMVTI2fqpoV5nJ642X7HKXp6OcOHNo=',26,'9e586db67261eab4be32894f843d8cd611412da3e85ad95d668761226e9188a49f181bd4f5ea120e83e341bc77a7a85e1dd26df5e64ffdb6999440bfd0bc172alcLfxymhjb6Mvny//IeAdYobgn2KoBcK8eKgmQTF0qQ='),(201,121,'962a530c30bc23c3e4faecb5d270d6d90ca295289d5187f926059be32c5ab5e651637403003da308b0b979e839e260dacb48592aee728598d3ff31e9dbbbc420ts7XNzzbaVNbtaiKONZd8a9835FaauBdcHLaOPDR+nI=','f136d1fdfb34bddeb5b2d44c2df2c933a87eaaf564be0c9e738e749239e3bbc2a9c35d4a87913460e4a8017e34bbae9cc6d3ef629cc013f2fb2a83b58066893eS9J3VwRiD6qf1K+5vdeXp+rVKT7BEtIZPWoKxK6NmH4=','edd38853917d8052c709ae14c97d9e0178ca73b307f8b0098247bf0f98f9e2e0b489ae08358a18b4f9f21b199ffc394d932f965b01e7d73eb3e4aacfc79c81aaghcXYhD+PO1EPff1RXqFHEqCb5ekB3cJ+LhC2GdgVWU=',26,'4c048f29c5d50a649fa57ae4a00925037f0e12e6411a17f557fd86493db9555e826a8e7573e90df50ddd18348bd60c0c575c58f6f857bcca835afe472f133068F3ILhIX+PjVVFw1oQ40/Hvm1MkcKRGceoDcVz2yy+Jk='),(202,120,'2aaed4a1c2c920cac26fe49fe1902e1fd449d15d3629e571c100db94dfc7cd165abea86e2562886fe9a32eea74e66db48b6c7b5c11e0067124cd1e59e1aa9158FvhUOIWYlK3PCDiqVnO+EoL2EvTozXr32+JQxsgxRlI=','d0f2231abd05480f7a8fa29292dc15e2c66b720acc4e373866d95193f1f0d52afc4c2b8b15550d613039f0ead9211d3d4cae5e60869efcd86535377583708bcfhXcBhvE5CdKar5v1JjzJhVd+wzgthFBhCj99+vdIjgw=','1f24b768ac302baa0fa7d204de8b49d61f662898c9900968e36cb64fba25d9fce59a5250816bc7c9f48a66daa3899a30bccdb17cb93d1bb83706e13ff87a540c767vmu6V1OXsxaStUKQZiGWxU1OS9liL2FuS3E/XtIM=',26,'fad4f27818ad91d9b8705239ad0270aa65b65f111d5bb29b1826de150723767276f42c0d61a76774f164fc4ded3ae5fa4990f30cdcc9d50666e5fd49aadade1aQki9lBEOsc47Gfxr50N05VaTfV7Zvp0P77zLPZAd00E='),(203,121,'bba57382c5a38088d3ef2fd5d8707cc1ac8757aa4d467680dc231e74cf053d901fb1a9b489ce5dd50afd230828708c4081a6258d3ef2e323d5b9f804ba1c5eb3Hi57Z+zGWnEb9ZWPQGU441QrISyjvml+Ekw+QF7PqcM=','550e3d22ec0d142183340e51775583ba2f74d3cd4fa3abca5195aee034c9cee7ee56729f6ac64ffe9240dd99e0be53c2893b69894cf51a9c83227d41501a7e7bwKepduwIC2Rvox/ri2tA1UiMRZGCyxxCwHWsG5i3o8M=','8fa90cb6e4ae7f30156a4204562195ae8e0b3e89efbbd08ede3db50bb8dac9813f1953d8ad6be56ad1dd0bb26e690858b6c2019d55da230f7bf7476d5142c539AicWHvxhBrXKRrGgRbi0aKtVxt6Z/ow6EUL955BuVvw=',26,'af052dbc944c6b1edb53c3582ecda3ed4e289db85e1f3421f36b9b54d7693daff6ae8f3b7e8d0a3762f452323a0a310e31ee13657284bc0ce7dfef593455e7962RzuhsbdPIWOEMe6Y8ozWVffHzfo+rPv7ehRjJSVI/8='),(204,122,'fa5aafd798d21b9fb0f2c899dae243e28ef44e83f67a132fecbce3feab426647564230e1bf33a0d97ae3e973ed0bb508fb6d475b6ce2e3da5f0952e49b5082e9hnJ9iVKtOy85Zxcqd+INbSJHIzxPOJNCqkIYZKLlJjg=','c05751422ffa5fe10b4fdb03d92f71793c6154e59eaea6c500eac860625769e30b26c6a2069b8443c28d1fe4caf49d8baef20a07c3ad8a8ac048232cccac95a9QwzVdHcif/pYqF/YmHIHPUlBv/9RR+E0VMglPL296Ls=','5005bd3fb49b70b0d699b8dd054c9ee5b918d5cd0d6099b7d408791b5d4a03cd9922b5f41e8b4f8e6ab26f086a34d2378bc7a3e205729023f3cc16ee7d3300e64n4xpqiHqCjY2XXfKY+rmdYfu0o43OL/HkIdojIHhus=',26,'17226eb5cfbd5cea9d3657934146dc726f15c2d560fa0394c6c5a7bba10957bfb8281020dae70202dad33c55918114a73488572f0de0236c842715be4d08a87d/Utm92H1uz1FMJOsGTc2nqcG85WHQiPU8Be02NjOs+8='),(205,123,'fc03bd80d2e04792ed2d0bc7419cde9c1e345de19fae0d6379f19a35ede55b05620296fe539826e588ecd57791f1ea342335f758da635bf75971a9dc5ee02428S7BqQ6jQsfMNcpBvZKpkZ1B5FlFih3jQ08ufB8DKUE4=','36d4530878dbe0f022346738258ad4548532cf1aca8425f811f024631dff5d618fb9e0595fab1868f1d6b998ee57fe184b74dce5741d57e2413128b25561e28b5hqYzUf7LpQs+xTLZNMOyqecwgmQP64iuGQVWia2F0w=','b834fbd4444d8f9b28150b517beb3dbf96640500c5462cd6c6f54d4cb4e10c9ad58f392fdb4ae622c9960b46d2fa6a222a1776fc26e8ff0f3f985935dfa3be9039o0AAxLZFiJNM6z99isVOSx+fqjFODEZRhs8T4Evxk=',41,'ea14cd3492d0e10e12e65e411221eaa25bc8040fcc1f22f12498877326b800b1a69fb25f16b6ed1c23b839db0de0c8450974615a938e37698f270705773f7cc60/HsmiY/tIngGHRH+bsRhKBS1qihPxPQHjOYPI+/uA4='),(206,124,'7eab3408abe8bc19766871d3031275b71b29211ef6edc842c4a92b263984d0d64bb6d8c5e11432379191131f260a726953e0ddcaab1f9a6ef34810eb3085a7d8dX6n8ZaBbFoQELb6Fv7lipmpnq2hNi9Oh78K2hXfHXM=','15a07c50d6dfc698b58b173107d5817fa9c95fbdfc5dc445602d0155db21b8ed5a67e3cd9e20773983792cc35f89d0362df256f6bec44ecc013a6670bd4d071eQ6KP++yten2jM0sN9kshc263kC++m4SqQZ3IEJCIQ6s=','0d1b5f88adb8fa1dda1576e62ffd19fc611257b4ba23735f0698da3ec8142f3b90a202635f58487754aa6d2387e6418cd99cad8f6b53d6fcd2025325f6156b6bnk3jARACN6Ks0k8Ny0k+sRcYD1s72K3OP+0eg3HZCqw=',42,'b71a6b428e3bab14d18d1587d3125e012381bffddfe5ebf6da32a29ae8dc4c1e2bb8ce41d70d0093f2253d89bf1eddc3d222883937563b0ea007c21a9f1f55db0EVzm2/TGXX9Whqa20uMhgHKClb1Iv0NwYN41lZ8W9k='),(207,102,'25c284b54d3e498dd1456f0b32ed761b99b0cd23312862fa6d122e73f0d5d2754a550d714408fe93d6d4bd5fdc9552bcc84e35c9a8db15765a9c46c35c0682de62F9uBA1UK/pBXrmePgDl+RIA093QvMDIMsUhBxsnIM=','c46dae9a7997ce4889856c2ce2ce1318825e2ea8f2f4d84c7a81a68a0733e492a2ef4034ef02c73c0edcd7951b6dba7c54027fcf3333ed93b5a60f65fae332ddMwB3xJlhoWcObJGuvggnOVuO9pS1+8vFWcTSzSstYTo=','bf1b035ee93bf29d3f24cf2e0d541ef1bc22db56f3c6252005fbfeed7d7672d7ab0a4a1018b16e873aac2154c3931d852b5ad2f267d678c0dccdc9ff8a3e0acbwVK1V4K+8iDC6R571GMM7R0811OLeZ1DGeTB8yVzKMo=',26,'8f6a5965db25fbfb7cbdd53cdee346a62592be086a5d48a758a7ce0ffbf5e9e9cba28b3ec023960679fd87a959716152302ae06c12b3de1fb0d813be2fe32d70bhZlCcJ3/xlLHGSXi3KHbjmO8l7Q4daajrFldNrL31k='),(208,102,'c7d560f4da5ed6ff0383a52f1c387af60ffd4e2001e8049ce79de26157306250b0764ce9c27d7d7aea67556495a76ec3df33ba653411329b81aec9bc4e9eefcfEmNPGgyCvdEOsTY0fY5kVPfk3OUr/fH3gt5tJ24yn88=','f9b7678dfff8f375a7de62c135eaa14c52f89689bd701ff450ee79e7726a038f2442b882bf4a13372c1e0c943d7b60138c7a918f678ab863816f3a625787a5ecYIh09fm28OenpAbD3pkiX4MlTQnUHm0IEKfCup+rH10=','2c476cb5967d67755821cc03146ab27d0d9efa31a526f65ef2973499c0d61cc6e9dfa8eaed40432662bf1e78d2a28034f6549fc079d38f53e615d18608d3787aweYHE1sCH9V4hivC+Dd5Ttn5kvxECaolpEfg5Y53piQ=',26,'0ee706544e1655005c29dfe94c086e5d3de0390687469578c26728c10bb88169b31b180fd58c9fbd71060aad59645c94ae83916507e3546d647770bf9f3e99b9m1AnQZXuL+/9s3lal2ZhKc+NuGF29OD2lF1V0PazM3o='),(209,102,'8b7ed4db3910ba6b84b058cd1ece2ceb7dfdb800a5669e6eab4767cc97c38646e488c51b7e8a21c5ce5b1e3ad9079de821813410dad78f4e6b3b65918b714e9eDTQuhbTCr0lk7906xRubgqt6xhES5oGrs8RNg0h1dFo=','5afb6848b1cfc5f6bf42503aa90fa05049eae227ae72c67b3ad102e6441996aab242a3d76b8f1cb1b3da0eeebc2392447c2cae6bf7c4ff93fdfe641975cff034VXMZY2ymZX4d26DC2bKGoSHC0GCvlGJ4VhOIDFV0O20=','ad794e00a7e677277d1623b4c442310814936ab0e2687a67e74c89ad0b8f62f7d695ede9a0ac2f93b53f2feeede1020296d426736bfae24711908e1e29bb5eabbA6j+SbwQEFYsFYq6hFoDiReKe37ogDMVscnQOMSY9c=',26,'2b519f2403cec9f0e6717621fd0363cc07788b927d61348d12eabdc5e5bab4cfe5fcd84e654039d1ced9deb40893144a6ddd105405f9ee2be87fc88e89af1e14VeVjySyfZu/mnAkGnmzQZETP0KG92Nj+CJYAZTAGROo='),(210,125,'161e2c9d2476db5a85ef82025e3e6ed7b06644e99912a5ad00136da90a1f7f0d99fa5641b8f6b13b48dff848c283e80123ba4a2b596f750ff0efb755ea513330/dzVN58C/TZbvY16BUCmm6UAA/oQ/zsMeq22+1SmpDE=','08154d4b87489f7f777713a00f28706d381206e84f61ad1cdef2c9047ec115faef483e2e661fdaa67f15ea4f62730783529230ade96dab3273c83ec332c7caefJWMyn6RpxLSko0sbjHB42WVtpLv2N0lp3tHuULqRyC4=','9137da463d3e4b5e4c7efe5f0324fb4afb3a3e63e302f52b8aea953b4432303640c58b14ab5d091ad8a2dc6ac31a15d56b064cf6943a437d19dd3998f017da07xzN1QR4WaP7eZObuwuwMGw/MNgdgWLjFdBjgSEuR4IM=',31,'e74ec64c90e16d4af401e85fa48a2f208096b6311e772ebf5224fce54789a8968b45a4ad07a7eb333e1dae92670621a2a013016f30a3584a5d2d5dd306357160UCr1/GD7hR7Pbu7tVxy1SY9pd1YEIi5t+0g9Seq6Qck='),(211,125,'7562628ba161feeb5c87b6217085076a9091829c1c131cd5e92f424d1a90d827d0376d1f75441291c112d18e6145f2dabeaf405b0c297ddff46cf0742dd9a368UC0HejDfPf5TAe1JvAWYvi/eKdv6CKYYC3Vazia1FXw=','d6137d9f8ba877a7144dd010a7f6f5216b287e3f913d541ac29de0138724f083de0e7c01ed893695d2aede9acdba068e2d89e238bf17d92cbe4b2654ecf3f13dx8jIG0KEME5oGrZm8UJ6G4DP1R/RsjNKgpsFcTA3Txg=','d9db57de48cd036a48a83076016450572de26d43b2b1a3389cfd3645ab1dd4c890e7dfd25281f46a65dc70e402d1534a04b45c525b9f9b9682f5a16b2f245344k+Mvkqd+XDC6EJky4mp6y4CRwqHmWw/De9SgT0p7TxE=',31,'5e689b167f8dd0b38cd51ce83d2f1b345d4d90f4a588ef964826e9b183099ba24fb859c74173944e3cd330a2a5c9f28a7959f698028f7a83e30eb07cbe767e12JakNJxkp0Lo7SlH/CXMJUQc/5+Qqebu98UqRCsDy4dA='),(212,126,'ef71ed9b71225a9b75b4e8f9ba5988ccd2b4aec653601ed00fc38c89a4e8e643266fc29442fe8a6de5aeab47f9f80272ac8cdc3cb6b7aafd1926708935fc9b71bHGhLjcseIovbAnA/8wTB+qlL5+DxDOYYRdX6fJMrqs=','113190773b0ab5b41cb8004750669136ed014fe4373fa6aba27a02f1f23bf9d8b048bb0500109419e5ac03ab7b3fd95af266ab6c58886c5acb0d2435af688843eoT1z5lFH8e6CU/gWzTZwCxFRt4QHn1cqV1J5EwD0e8=','79e6e6c99f50f93a02cc4ed6441ace98711fe8e57ae4fec861a4808f242098b48b9353e97f3abedc8eb438d29c16db0ccf0b5e530c0c1df835adc57dc334e6a6ttS9twQP9Xtj3GR5Hb1EthCX84kJTxgetHcoIOuvZfE=',31,'18b82e443301ade6c124ab7ed196711b1e19c932a08a09f949e5947367f9cc35c8280d52673c60e4789931e430cb1555ba4e82ca8c90bf4c31667c92d6807901kAHzKPYJwV+zAEhnfqerrLtdv3XgseT2AcNPbFtHWnU='),(213,127,'96d09640ab1d14c03262d829668ca6d90f7e58cb90a13332e3c187603cf303b59d607579b8e203fbdf6f8efeb6d9e512b12889b0afec6feb737f3b3b94405892+vVHSbDxSQY+MBX42kyq7P1WW9ej7LE0OmGNUUVl80o=','325508288bbb8a811a12e2d243b96e9db80b7294a194f672979db99fd1ea4a378e79a2dd8244fbdb57c727ba3201f6fadbd80b1bcb7c29f65d2e22f2bbf14b4bbYzWVh7IS2G16vCeqXNIrnP0hLVX1mdtTn+MypmUBl0=','f68866e1e609483e8a73fd094041fe7d31430dcad28a2b1e3515d618c1d516d5ac527344e53715b632054895e2c6266c7d2877c9fc6d012ee632475114dce3838XRYBIvl4oTjzKcBwJyZtlX6+CuG7LW2kpbB/pS/PYo=',31,'af32004f96b083944aeeb702141175460485bc163480e12598b88e6610eb9e31abc73338c5c755d65bff0b11225920d4de3605298f04fbba166d0c81387c4438GDGdzHODARYakoPDz8sOdKFF7h5UGUBONSbQ2kiKYac='),(214,128,'3b3756f39912348b4a20edb898cb7bdee1a81165018b43bc76d093537418e147a5f906ccd109a3067b523fe32f4afde12572b98eeb6738ce9af6123b7fc5d1ee4f0edOdoBhdlupPFnyVHGbzgUEmAPt8iOzRo8DE8wlo=','78b90a2e85a1a1eaf3fac3ab6d3f17d9172c2e4bff9abd23fa1d570134bf5c6eb710534f5256090ce984125d944929d5471a2c4153df527bd8c2c3c90f41fbe93YeSHBwBNO3WYee0eamdMHcjCeF8utK/teSu0f1Y4jM=','8fee154e3ccc6311d9c25342072b5add463cb6543182ba3c369ae493ae0de529638a59f2ea68305ebae3d4f71ee143e3693747d95eabaabf4b2f5575c36bb626PZr9sInhXef3H8/thO01K/B7WIn1C+NG/KQ3od6OJJ0=',31,'10b38a5d3d9b6561df4f804bd4168c7a04eecdf89cde41f67fd7fd9dce0e18920e1d61e4eb65b41c3756caa72f410d1701e4360664684649f1626f05cb58a95a5zQgxWSORQGmEyEpwrOgTokUjLfTm6EwWLu+ibmcLgk='),(215,129,'40b4dc004918eb89103a7346d07d94f0b8607faf4164e03f590e89763b625733cb3a818c6cf09d003520b27f1500f74bb2a3263912e36274b7068ea528e4ba33mAJTidTSIDPxWkQ6m6H0oGaAOsJFCdd707SKhkZE0Cc=','1142962fa79eeb8f8c58064f9dd1bb0eb6fea58c583f5f71d3114ae0bbcb41cb73a8869301d992a5496409beb3e10ee578d6ea10b814e85700013c21b48ba74eXe59hvql2Qb4MaAFv9qhmSkFNKdhh25UeCVPwHypoUk=','3e781a9068b0e8a95508bc2a888f23cd125496fa09fcc2188443d961c75ea0e4ae8720f9c39fb63224d17161ed5cc4d94e42b25cb03593ecaece640d04880531QjrdfGtXrgP11FQbhYjYkBQSDrx2MoYHs5V13OO1saY=',31,'7f423b642ec401936e2f38aab7ca73f39e378c0024b103af7ab16caf6cfd8707f88e1b62bf51b6fab3f777b078a31e345e61e6a0ab5246294a8ed1693ef89e82xm8VEzfT3Vk43/NYDBTd/gy4qhA5R8djBhZrxNnHa08='),(216,130,'6ebaf33ee9755ae7d7fd70078f28cab5256e207ab195fdec6ed08faaa2df773b61c71ebaf4d4490e47f62b4c498f9996eefb34d127410486dd1066e351ca4887P8DKE5ngtAJmG8jhSGN6tnYND1YZMS6mGWt0o3jJzDE=','bade29b051a38d936de32aa9070fba27fbb373e0197ae9acf7bd8d62f375302cdf8dc8352296e587e5d19c8eb31ca9d20fdf85ae9c108ca340de57869c10e9a078E8n92q/jcMgWBx3UTsnVtnUXKVpv4fuCwDZlyveJw=','f74ea3ba6b2b39b2fcb0bd8a1d306085e9b1a41ba21d7fd995f46a25813814a6718dd1f864e27fa3d07d14bdea97560bc4e6e063b262904e4bff2eb0195fc64543ecukRO/g0WuxeBeu3jmq1KZkO+EP9X/j6QcTFyhrI=',31,'7a88f48c08152d5d92b35626db1642fb5270f2c3aaff37648f858dc6cf0e6e96b537700176d3091118dc175db5b26f6406ff9601607eaabdf32a2eb81f4c90f6DRK5vsBe/suCXcTbNCJYxG9Ud8u19rFHya5KOptmIeY='),(217,131,'3f9a1e1621ca0c747a370dcf02e7bf54f37bb854c3f1c4a04c352127cc268a4f50e2152b06550e6361eb379f57e35b96d83980591ded82745ea1dd33b8df74f0ZbZQhjnyFs9YHszyZtNtwBd54WHzB8BN811z5wzQE84=','f29a3343d96d2221401b0c113fae9aeb64a9b5bf3577e9d57b10e35d980bddba78c72738f4aeddea37893720dcfae3927401ba694c7645e3c0c39c057015ff70C+ViIfVkgtwHGWxzVHI0+DR3OR5VtSqCjPjKFP/6O6w=','e302d51057c488f379e38f55ae4be2028f2dbe8ced55d757441caa40d21da9e262c57590b63c1087645554b8d8d57a696957f6459ad057f77a816a814c84c9679XC7OL80v2RSH6HLnEeXir5gNtett4/xgQuIYS1Agyo=',31,'b5b40b85e5ccf2804e5e3fde78fa12b75ac0d34a5dc242695ed2311639d71b48786a5f0d1adea502aa9308ea1185c2fa8d66f35ba5077bab6fbada5cca200b06Z8+zn/j7YVsfwhKY1GXtja3hjChl/TzRW3wRNeVjBlY='),(218,132,'1b7c6afb60e02f5c7cb6fc8dc32adc64623a0a926240d5cddfef0d17fcd0a4278bbd73cee1d765f245449c550dffa1dd593d3db76c76af49aa2a167e2885dd93oM4KLOUtLEKDzMqFZnBDjVfNPOfk4v14XFJUuMMiOrM=','b04c4da446952bd5a4bb61333b0537e07a2f0412989056c8820e85185bcf2893718b814ae20ba86522aeb59de0c4caf213c04dcd5476355821c777103838d1e5nK5008uFbM6a9pcbL4OYNHZE2nXNJy2VEVaAfiRTJxQ=','c7d35dad1fedc6eb80fc738c7061735f22ac6dc9a76edc66f6e6a6c43c4b2af8d7992324d4a354d0babe163f1303ae5d674cc3b64793c7956a73bc3315a2a1946pVknKEj4h+Iqy2+6W/iRMLvBG6Tc36p0j06uL8shZE=',31,'afe80365e791c1ad8922c1dac1296955afec3dec866a05a74332d8c19b0ef2bac2e0f4e3262d05e9d3ce1326608fe3a6f57405ea76feada79d1ecb90c6dd2b215Yh3sBAxDY5sRitZMKnMFBG+fgmNnx+AkA873G3i4+0='),(219,125,'1043c21075c38f586d65e97cc75a2e55ca27dc0b9a412361ef115cbc7209a4e7ea30289e4994aa601373f273aff67ad91b42737935355d88ef34297acfd61e152gU/Bh3KtY4mKf1d/F+BWpfGdys9Jh89oLmyQU+Lj2Q=','f3788029fc2b50d125ca8d94fe09742d064600eb6e2857cd482a0ec8b77c9d01c037ad9f5b8c546fd970794875b7c029ef841343cda4456da0a56ece68f1e7d2eKFnCZaPZ3wSVQMi7vjTISJ+1MH17ai7W+noFOkfUvg=','289e2c962323b6886db6f855bd7620c53c1ce4eb480028c8e506b3645fbf5cf13151d67bbec50fb257a18a9e906724b0aaffc2bb4b0815ac49a42a7a5ef24f22AKEkdsApQkM8VqwPbxLcFvT+krPvLsUokcXNX1A4xas=',31,'a200389c6bd7a07aec4ffeca286d77d76baeb33914b782e24b3e60644b66e0bd8ed6517c3ed66ed399f76a6d275bfdd658498978c5e96c4a9446ed88b52d9e66RaJWuRaXFuWopqZAKaGckhj/6mRsmNhrEP76uF6TH48='),(220,126,'0ef4f4e74e648f06fda52a308c8f79cff6cdb9a6d95f2f3a0ea3fc87aa1f07318645a497eb66675dac08887cd1173d0c194ce20655af95fde134a2e2566b74edgxM50A6PwdR5Z4DOkes1NWTSd4r1UtivLD/1a8omXKk=','bc29ccaa93722076f264da48c890569df76dbf3f64f0306e4ae0067f896c84eb2ba8f015b4b52ffda1fa3fc0725a69e5a3e4440ad12bec2a82038024db63970f2DddvZgNmqTRAwFf0Uq1mq624edsxKWXpNN2MQvU47Q=','4ef7a1d184bbeb67eda391375ff80d9cc56921ea2772764b999680ec5c3f00016022a2d2224e618001c246317650343bf7669624bd72bd894de032471a10ce69KSzRZ/oh9NX4KeSCxmRpVma/Dpwo8OIQRf4WOet4A5o=',31,'c1f32efb7297812a0a421b8f6f3b1881bc53198443ac04d4a0cdf5bf78de46f5e3617fae2b4b5619e90c323c070655bad8563238182aafe24ae313959c9498761ixPoH0h20R+WmVyEnX0za8MZQ3tFj6mv5nxUH1UYZo='),(221,126,'385ef919c442e38d5f6df7d4cc819362126c234f866faac50d915e3dde7f20b08b855688016c991b010b867fe3d7475d774fbc9934bae6a349687909f71c7484M8MdWwFZWg9RPdTOQVaPMMqc5BrCxzVn/4/1nedmQv0=','7a2530d8bcaf082120ffb8ebc552f5cfcd72461b05f3d31c6ad7ec1f6098601c407aacb30bc76fdbd7a6a88d3f8bf0d4bcab134d0c2fe4b9774ea729b42bfbce7AdnfZnkH0LDtQHuY0TXfeIrOBfylmFIhqYzV98FtvE=','3bd5892b3942a43a08727730a51b5ab07de7374414dfb830f3e4344bf56b3c70470d3feba8032ee80b27075347770bf0cf67ac39b17b0f83af14283cc7033ac88W8jikx6doI0d/G6eFYunBym5fPEkVqUmfYI/mbWX8w=',31,'dc13bf1c9fde8b241f19e2f003b9c271a23d210311838e81355c765cddf2209fe4ae0f888fe29216db440521106d9e706994eab07ff80cc3c4f32ce0b6566d8cO1+shUoGhdg8xp31Guh2ODzKEpVTxsWX5tDmm+EV0z4='),(222,126,'4144101c07b4724e3638e91eb928dcfe8b3092e6378ec1e3f1ce4ec4025b19a2c0db970eabd45913fb35433b5fbf436649864a9b38d57fbfe67a9e437ccdbc19yN1Ww80A0+ffokqQ6toauk/J3ZbuuKmETmB2UMdQBk8=','45dadabb06a49db1f563afc59a07168196344bb7242077a2a061023f760166ff22cf4ddf8485e6a53c06bf3392f40e52434b48fe10ca9ce2fa221366a68199daRw676vP2O3fI+XvlMeiP+y6XBI9jnSiHbCI6ziqJPBQ=','b5515f8dff761173ffb4466c4176fa3da20fed9cb1fb3954a41d757a5bcee95eb11cadc5afa3fcd246f4d2783d5e46a41957b86d4e22f061ed3c5887ac07b5d28PsnZT/y4pu78lNU7nR/1CKS++kNbdbAZofpQcbsTTA=',31,'18ef9c2c4cd696fa214129ca4689d022f220a61b9ae2d616d002e3599d078d77d7b298232858813cbc76b28b2d943e709fc6eed15a5629266e2a13f9487568d3NS9zZGLoEBmAQkv28JHnEnvllBkOZwjoeYTbVUGvDYY='),(223,126,'c4b1e2ae4928e0efa1bb7637b290c44ed99029950d4c0a9b8dd1c82a65db64351ca649439909196319a758fd502ee176b82ab13f99d47e7ac98dae36c02b645a0ZiQ876LPRRUKGaElBt2VtsiOE0QhPi9BcAgNrADiw4=','1114ee056551031b456ebfd70caec384014a1098c0134c18077c92d2e0bcf02ac81121b84e8d0dacf18ea3fead51e2dd7e347b4e5d690cdd595726f4ad8a3c84GgZ1tlfWXgX5Ro0jXKy9hMQ6WD4cCZ+ByZYFTbHunHo=','9bfac4bb1225e1a46d5893a922f15b388d666c362ffc2f283ed150eb0195ed542de018506de02da7a67c142fd3bf7c848a5d51ffd732548cda07cf6e3a36cf19Buys6HzwL5UMxxay07XP/3Loj9wtYyd+Lmm6raspP1I=',31,'25a86916ac5c6dee7d9df25e1d0a95b5faf96152233babeb50bac07ba3ad4a6d21f2f840a31df0a7451df5c9bc76acc7ff57d53864bb49b2941ffefd0208effcN9RCAZ9FtPqzOGKL/+RXsICn8L2s1ejy/lLpPcj5P60='),(224,126,'43117d9a691224797f65ff17034a0cc71dd41f8f88bf0144806321adec917d82290f9bed1143bcdba887be780f535d63eb3c196cd0cb136e839b674a1635c2e9ZctrI90q8vAA2yBIHrC0SV/H2331puEWS+E7FCrW9o8=','6aee27827b59bc72a9e595d515dad500b4362d262754e8887cad555ebc5c2841129f97f8f8d6811e0680b914409eafe8d1679f732155cc983f11ee0d10dfd879xN478yPtINyaMYp01NV1xYBwxSO28Dl7WertZfN4w+4=','f84199a1fee2248d5b0d0a42716c0894485d3d169b1296b953c7f2edb8cfe2269fa4869eb522be6a20d51d3a50d3175396b956e555c9535e37ac3e2f7123a5397Ae8B80bOnoCyWXYiFS2CdDG0yaax9VFoC9+Co3CqyU=',31,'a3a1099883018bfe95688a1a4011660426cd996e2f913f5d260ccb53fe5d92948d0b973ab8f1f33350d4ac95b89524369073c0ee9fa3b5b008a990dfdc78e460lGIJCywXPMCR2byeS++m8LXOgyroS9ROXc2GmJ8C3E4='),(225,126,'5b40efcc9f65d8c3f50883ca16e1a774faff395572613f2e45558fa9840cfe8c32b61326e661dfcff5f281c17919fc8f92320a2f188cb9ebfbf8ffa8aef1a0d5CKEIIf9HGN7wfe9ID95GKzC8xSwXLmityAijEySJX00=','3b20c4e71c65bbe747e157a5a356e46b7d54c2b4cf0c6775b06e76d28b03bc2620f2bea1933d1856fa8e51174e57ec62ebb05e3cb97cf6e28633e163fca2fbb3oANtXvfcpGnuf8V3ETS5ricLuZTr6/776NX4AuzZzQ8=','b6d16084aaf94ef7279fa2bfeb758fa6cd31ea922d1ebcedc61d5055a6dc7341988aa31d210ea34ff72102629a78b2b1fdd19960f1ab7a11d5bfb3b2bc2fc8f6JDrXu4J7+YHV1qC10+IYyNxd4JF/7dRW5OTVs4t7gC8=',31,'d75966eb609e434387fb042ff857383de9fe71907f2d728f8fa61da2f957c4637b4ef408db06d18e1a734d32301183609bf1bbae2f8583211e91ccdc9c408f176FZ9P2vq/jwYOzQ75vbFLjGDdqvMyTzBmDwPBVK5HiE='),(226,126,'245ac1c08fd5b6873f8e595ab19da32e39bfad56e7625f13521c046d91a0e81ec50dc5c1bc6c5cfa775f5b8bcddcf2dea994cea040250a37aa60cd2b3dc1c848UIV4PlWCyIJ1GE4r0pf4ijFxv9XsrPo8KUOh5XDZU3s=','9bd97b760e097578f9b626ec23fde6d68ff36397bd726369cc164b40b1d98fe6cb0f85dac2f971a10c40b237d2a8cf898cd8f21ed8fd9bcb25a15cf610a96f33BoyaPjHF5J/IpWOHfp7VUjuGwLYic1RS8yLT7p+5HRM=','e354e2908fff5c72a27247c74fb7f14ec1236ad748cb0ddff1970bc1d953f48ef5363303414b169e9c01af40a88b0d289973419ecd45d30266932d865b746445t4WPt2KV1+g/kJnMgZ2uWKo7ad5B+Fg4SK2Q7mO2/70=',31,'758912ee44cbeef9e567d0d1c45e8014c1b4938c6e7384695ed20f5e0e08444cd9de51ff6acd991a07c015b5cf20310f495674e1201d99406cd4fef691afeede/OfGiAcGOKCMkmjV56IvxFeK2JJuVLePbqN0/OH8MSI='),(227,133,'365d327d883cd3460b5d564c2b15c2113b90a37131db860233a7b30a4f11738aa417b4289daa479ac829f5c97a53a9b75349752e977567e976dc56870ea0b48fQQdCuveNcypEsCUIE88rlsXsECok86lATNApLED7JA4=','461b1c48d43e614d5ccd27b0cc395cfb53524ce92437c4c4a58846427b237a2ea8413694bd5f7645c7332f5f0bda0a918270c67dfbad4494d9ba581173b05e5cJACKINqLRcB7Rj7fisuZY+CTArX8P4JkAuym4ATxXjI=','3ccac7d6e400eef2f7adb5c158d2cbee1da149d69c0f1265efd93450d048c13d65f27ee57c6781bc339ba849e06d0c8890bf468bc22b1086cf4bc92208ab4c5cgiJfjDxhcj0gLmbdeYBeEj9zMlG2qYnA5q50JI+hZk8=',42,'fb2c46ed2809fdf3dc44eb4b48657c71a25faa2ecf1f0a6f6f7e95e6a5a209b11faa6b971b95f21381fb440f0f43c049340ab519d235e20cdbacffb14dc33974oDOwkXJgz2pVkv8/4ujjFUnxH2NXvsQklmeLagvCNVY='),(228,133,'50f82468b1dc30962046efdb17d7e1d35a776e71dcf11e87226cadb084f6cec8e6a7687cf67421eea1476d507d7e32fe2ee788592ad12a949687d73e521e5026bFWif09GjJMz1ax3vOdVnaOuAbpt/vPaAILBgnpKo4w=','29ec346ea933e47c1279284ebbb12d08fc49c239972716a20e5cf558d3354fe991cffe444f388549af0ace50324824a40f451b7e2890525edf16997859b0a62fOq37KbgsaQIOtWOOAtBo4ZNGWPWP87C7g5dUdnzPJiw=','1712270e79da376f02b50432790d69061b787d4fa80bf6cf2ba9febab4eaf582b770e723acb242fdd22e9ed4995b43615d5fe6120aaff48f3a1b5d43fd902bfeZmxq4F5ssCCHRMUBWCWdgouUJdZ8yrIrsqIaW00TA1s=',42,'bbe0ccfbe7c08c8017561f248c5e8da4800d6f65a769a6cdc4b05c4f48c1b1bf800ad1354e5c782cd9266ee745dbb46d080080a1ae922963b47f0d8fc776a5d68LVEmudVUMzGb3RcY++Lju+VEb8v1UEopRjAS+SyWf8='),(229,133,'723bfa30ecf8e647510c7b58748a8ff2c198864f55ee74a81a334beddb5978511618cf98e4a5c3f1a37e8136a0f5b68cb46e114b3845ff7a9d1e9a7f50f52b634AnGACzllzT1TiqN/bAsmfcm91da+yaaccYwqIl0SXA=','5f1dad0bc0d37a9df593664b9185d977cc06ab58b5978b70af33a05a084d53fd1ae6f08fb5d2d89453e4c72ee20ab218f7800a84441ed17d0765f8711cc2049f1MX1gY7qJkgfz60LHBSNXfEDpC7fb+rLPBL9FDoVq00=','92da8e52d47d2aab4b074933e2358ef5b69a9d483e607d761734a12bf4780019467d07e509d9ec79cc3e0f8efb580e8309c829e166fa965e0ec472fddf6c92d9A3FgsFBKoK1ow8rl2hekXDLNJrxbqAFHBYmXAKlkP0E=',42,'713da1bb7ddaa48e6274a76de12c7e74b2f3734d8e1333919e5ef89b51a8235471764419098bff4bfd93ea98bcfa52c20173af32a69e8ea72e0e2bd65b5338f61LnOMJ8Br+tDvSc1qraTenDosW9pV3eJv+cW1br809w='),(230,134,'f5521cfa5b1253279652ccf816c0b2d7325ecebba408dffde983e010da19e18c47547232b5877dddbe63b31f5f92ad09de0b78521056df2afd25a2bd90949b019pYXjOXq1n20CfAuKrp5eHvRHpT11pq5SH+4JRjbrhc=','80fd51d9e99d92624333da8ddd8eb7641f16af4b2c4f3b27b9ce592da70102a872fe3a8765c200708f7e8c9ff56f7248b415133d268352fdd7b438e15ff9583dC37IpmXcejdg73VIdPG3yUB6SS7yHEN1hQm3Oy/23jk=','f15b09d635c615d9043ae605ea76c099f636328985bf0ede183a549d118208d4ff4d4373eb3f81db23d5eb418c8cca8a0fe902c7eb5b12e244dc5979c47d1a00DxqoPlDvfbCGjycz6l5RSmpotpL3tSQg/ePPBA7De6E=',43,'a1a568975ef0fb1e5761836cfd1e30ccdbfa85bff8b17e3fd3402e1066a1170e953abe0709f5c15161655019ef5972452bc58476f5c55b394006d8d83b7a04b6aBY5cKv/okU73sjWgSa4jd2oXjbMfQ4/mlh+aqWS2kI='),(231,134,'4c62025a80e55d3337c06945684a2fe5695aae42f23cb9879d6349309bcdf34f4cf8f1ee02778edc6534a9a8019b378c3a2f919f6f453481e415ebf2c0d70ac3ozrNs2AKeETOapHMClyhlQGRbfRjJbU0Mpu7Z29LPh4=','20a279a86b8cbd1fada64de283cb693162bddfa838e46927fb45f5dde706104eb35f74217bf9c721b469ff16cfeacb8bd649c5a618fd46bcdc6134cd5b53e4ac6KDNbpzVDJPSwLv14uUOVbvqG46COvWYNPkZW8gY1bk=','dd1cb785b152937fba8f282a42fd94ba965f888e790e63edce1da39f498b28947c1607e476f6eb8e10df8be21ee9e97e4013a040024b92a8630647ab9b27db18XpKzjFF5WK3Jx+Hommgg5t9ba/+vXorxueTywBSiYUg=',43,'2b6423694b3a2620ade02ba497d068e83f66711cffdfe40555575a5c5da4a78fd556cbd6712ebebc1a46abb2276023158aeb698b95149cebe2c292060f8a271eL4LUGSVHgCyB8EkjdkjPGEksd/or/NR2xYGd9Ft9H18='),(232,135,'fae4a356b3f24efb7dece6d98edefabb2817b2d5ab98cfda913283a46c4653ac44c0c3ff41a620aff203661fffa436353522d226061b9afb7a8a4f2f574820cefxEaQ2X+6J4mQDsXwikLLCClhffE/F1c5jPBSLEXcFY=','9886de7a24962018ee0dbfc58c5f0d624475f27db154f6841618661a1c873261969c33497b5f789adfe345692164cd93ba276b860d2804459977b9ecdf9b2816+7soUKS/mATidJjhSfBb5wvaN8RLwqBLPFPatQUihz8=','f15a9a6c741c9cf466e62ca94400fcf88b620e2207b97b910d02af22b571a662f4d7104dbfef0cbc9f7433ccf12773394482bba83f9fcd144736f367e4b4dae18hMOYN7KGw1SRvFmYdjgWlD4dWHePuJNH2B4Wc9RUD0=',31,'e5999a9ef4285418bfe022aad6246705af121f459195a5d5dd4070d27be2b6ace5fd21fce37b4255ad175e7ed9bc944bb92cc09f13a2db1caa5e739d4f977a67KauCs79ww/eCahbXCaOfmcoz/fzQR959GVph+5BaXhU='),(233,134,'1b3cbd5c7fb93fb09f6db6708e26c975c01d65fb94968dc680d82b1e3284fc0e26673830f6c13328afb4d6ad0c4282b2d211384ea9b1c57ab242885373c7f037qngnVbgS8vSMDQH26OmNfQN6erGGBtA/u0J6ZohoxhI=','c926c14dceebc0fc2b6d22decc9ea4b360799b31f6993fd811ba9e8c143ef761c96e4702b0a0a792b9149a80dcde3e94ea142258a35566023e79261a1bcf2e0dvAT7cUUfi7dMlcxAuEqrtFQqt12gDmATB44g49p9K4E=','ebbd8b14b9e72b52bfb4e12a3dbc37174055a64a4043c60a2f71e37a46c4d2c9867288addbb7a5717ac7bbeb6e55be8c6d33ecb97bd9dd7fff0690a26f3042f9/YqnAKPthyTUC50/tXbAtphTrqwL/LGgDnMJzN/OY9o=',43,'83f3ad4531751b11931b906245475a796dc2fca25934c333816d81e8ad51627f96691f809f92f133be0ab5422ab1eb7e67f72568f943c81903420a45e28cd3e7sxwKQiT5ZUva/p0sc9KlMmVMEp2J1CXnQgao2CR+EnM='),(234,134,'e7bf126438c89627c674c64c6cf3f6577463c07ec2fe6d42a45f548852c4bab2d29f8a5a5aaf4735e5a4eaf30a9f5b1fc45666a5ec2c762fabe88ee33ef6c006pcSWR3iZ5x4cYXGmUzXegloFckD7wVGg2G1agR+xNvY=','5ae8ca36b14ef2b710ac320914b72a1b2ae17406066683ddac8fdd3dc369a95d71bc08da9a2904c1e9f61cc33c76a0503a4a2d2cee335765df482122ba197000WCkIh4hnzcA4db3nfyhE7DuPFnuBx2rRvHzJloS0xBY=','9cc862a523bafde090b6ed52e1e20f689e06d8390fcb5308cfe2039c8be18ceb49c36049ffcaba3293fbce1473f900502431fb4a14c947a7a88a9a2d782302197Yp6Go2z6lShoZUGojOsTgsK9WhkkYBLw4zfL4ajuxs=',43,'ead9459ba4beddad9d3300428134e6f303ffda40dd3d15c27ffa27b03ec6f45c0d38517ac12f0f6e2cf6e016ab9d0c929b3df5b41c679169c53b580b1d857cd16Uv/oFZ3MuQo1r32g0PJEvPGUwbLoLJjtTVQEv3xB20=');:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=232 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `employmenthistoryposition` WRITE;:||:Separator:||:
 INSERT INTO `employmenthistoryposition` VALUES(1,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(2,67,'2019-11-01','2019-11-07','2019-11-21',26,25000.00),(7,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(8,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(12,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(13,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(14,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(15,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(16,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(17,68,'2019-11-01','2019-11-13','2019-11-22',25,12000.00),(18,68,'2019-11-01','2019-11-13','2019-11-22',25,12000.00),(25,68,'2019-11-01','2019-11-13','2019-11-22',25,12000.00),(39,90,'2019-11-27','2019-11-27','2019-11-27',27,4521.00),(40,91,'2019-11-27','2019-11-27','2019-11-27',25,1000.00),(41,92,'2019-11-27','2019-11-27','2031-11-27',26,1500.00),(42,93,'2019-11-27','2019-11-27','2024-11-22',26,12234.00),(50,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(51,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(52,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(53,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(54,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(55,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(56,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(57,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(58,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(59,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(60,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(61,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(62,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(63,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(64,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(65,96,'2019-11-27','2019-11-27','2019-11-27',26,1.00),(66,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(67,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(68,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(80,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(81,96,'2019-11-27','2019-11-27','2019-11-27',26,1.00),(82,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(83,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(84,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(85,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(86,95,'2019-11-27','2019-11-27','2019-11-27',25,1.00),(87,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(88,66,'2019-11-06','2019-11-12','2019-11-19',27,55000.00),(89,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(90,64,'2019-11-08','2019-11-13','2019-11-18',25,22000.00),(97,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(98,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(99,1,'2019-12-02','2019-12-02','2019-12-02',26,1500.00),(100,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(101,1,'2019-12-02','2019-12-02','2019-12-02',27,5000.00),(102,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(103,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(104,1,'2019-12-02','2019-12-02','2019-12-02',26,5000.00),(105,1,'2019-12-02','2019-12-02','2019-12-02',26,1500.00),(106,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(107,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(108,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(109,63,'2019-11-02','2019-11-06','2019-11-18',28,55000.00),(110,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(111,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(112,99,'2019-12-03','2019-12-02','2020-08-21',26,1000.00),(113,100,'2019-12-03','2019-12-03','2019-12-03',25,1500.00),(114,101,'2019-12-05','2019-12-05','2019-12-05',26,1500.00),(115,67,'2019-11-01','2019-11-07','2019-11-21',26,25000.00),(116,67,'2019-11-01','2019-11-07','2019-11-21',26,25000.00),(117,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(118,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(119,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(120,103,'2019-01-01','2019-08-20','2020-09-30',28,5.00),(121,99,'2019-12-03','2019-12-02','2020-08-21',26,1000.00),(122,99,'2019-12-03','2019-12-02','2020-08-21',26,1000.00),(123,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(124,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(125,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(126,104,'2019-12-17','2019-12-23','2020-02-21',26,12.00),(127,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(128,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(129,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(130,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(131,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(132,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(133,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(134,59,'2019-11-01','2019-11-07','2019-11-16',26,5500.00),(135,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(136,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(137,65,'2019-11-08','2019-11-12','2019-11-18',26,55000.00),(138,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(139,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(140,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(141,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(142,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(143,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(144,99,'2019-12-01','2019-12-03','2020-08-21',26,1000.00),(145,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(146,105,'2020-02-11','2020-02-29','2020-02-29',0,6.00),(147,98,'2019-12-02','2019-12-02','2019-12-02',26,50.00),(148,105,'2020-02-11','2020-02-29','2020-02-29',26,6.00),(149,106,'2020-02-13','2020-02-13','2025-02-13',28,5000.00),(150,106,'2020-02-13','2020-02-13','2025-02-13',28,5000.00),(151,63,'2019-11-02','2019-11-06','2019-11-18',25,55000.00),(152,63,'2019-11-02','2019-11-06','2019-11-18',29,55000.00),(153,63,'2019-11-02','2019-11-06','2019-11-18',31,55000.00),(154,107,'2020-02-18','2020-02-19','2020-03-07',37,1213.00),(155,107,'2020-02-18','2020-02-19','2020-03-07',37,1213.00),(156,107,'2020-02-18','2020-02-19','2020-03-07',37,1213.00),(157,107,'2020-02-18','2020-02-19','2020-03-07',37,1213.00),(158,107,'2020-02-18','2020-02-19','2020-03-07',37,1213.00),(159,108,'2020-02-18','2020-02-20','2020-02-28',36,123211.00),(160,109,'2020-02-17','2020-02-20','2020-02-22',37,1121.00),(161,109,'2020-02-17','2020-02-20','2020-02-22',37,1121.00),(162,106,'2020-02-13','2020-02-13','2025-02-13',37,5000.00),(163,108,'2020-02-18','2020-02-20','2020-02-28',36,123211.00),(164,109,'2020-02-24','2020-02-24','2020-02-24',37,100.00),(165,109,'2020-02-24','2020-02-24','2020-02-24',37,100.00),(166,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(167,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(168,97,'2019-11-28','2019-11-29','2022-11-30',26,15000.00),(169,90,'2019-11-27','2019-11-27','2019-11-27',27,4521.00),(170,110,'2020-03-01','2001-07-08','2025-04-01',26,5000.00),(171,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(172,105,'2020-02-11','2020-02-29','2020-02-29',26,6.00),(173,105,'2020-02-11','2020-02-29','2020-02-29',26,6.00),(174,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(175,102,'2019-12-06','2019-12-06','2019-12-06',26,1.00),(176,111,'2015-01-05','2015-01-12','2022-12-31',26,15000.00),(177,112,'0000-00-00','0000-00-00','0000-00-00',26,9999999999999999.99),(178,113,'e4e2359cfd173a0afc96860e9696953d5083ab3de97b6c1a48d7716709247fa855951f4f97910dc32eed5aeb8308d398ba00b2fc2039b4a66d2566ca52ecee475gFONLlbh2h7Ka2gKk3NCF64f8fB3S5vWJ7JlwNHDMM=','5d9a4d4b3b9db1b904a22f38a16eac7b5f3034001ceb6aaa06abc257c8587cdb61be8c7f57db1bac3a5bb9b8c03c591544049f62d12f0d08f909a814c7a3ce9fRBOiHgFcF8f5gj7269zGyOWID5y+qLM8mVeVhZcLsGY=','1aed58f89092bca34acc0b8e3521d82984fe41e0c908fbf893c3a7b976946fa56cabda7a083f13bb9f3ceaed5300d4a885fb91f8e8709f436f146cd2dcbac1a3BusAaFyItoWGL2y7TrFTUcIUdsKax3R+y40ym46g1fs=',26,'cbff2fcc70d36e63c24084e2206019f94c29cab2d5d629ef0023272fc5f82b5eafbc22a1fe191bf949ff8e83976d413744e133a9ff7458fd6b32e524dd1c871a8xXdG71gKo7P0Zl1jopBR6zREInAUH3c4FRpTJ0TWqQ='),(179,114,'ee4c697a7fe1757f17e12eeeb76e6ed7a544cb5d77b9f01a0d65c890978724bfe9708ed6e3114509d090659c36041479a55514e49442b47f50f6c338e90a2df3q3gLDeu5m3qm9k5zoXPlrqSaQorxUU+sZBcZMirhDMM=','3ae75d3c3331a6f5f36f0bac34fe328eb1e6c13360670ab2b1b33c04b54f1e1e0abafefe1da777d1e94860d9deb2384e01e5e89060ff8097736498907ac847c7aAQLOwuIGWBudwF5EQ2BrNmDh+wGNMgNMSzx5zVThMM=','809c3045c9eb3f227809082f487899af70516b76e15079d3604506e0513c57567eeac5eab22fb7cf19904baf0c2c1a1fed1b0ef1fc5f41666cbb5be41964c13eTzfER2hBp1D7kTndwdMWdVaKWjz9Uhci8nzJbhDtHiE=',39,'28461050a96b08a3cb91a9b50d596084e96b6f4c7d38b12124837049bf94a455e84e21588b3260fee4b23edfba5bba453c17d1d986a642ce2bdd6043270402cfcOT1mxwyfax+pkGmkkhJGtXOX6C7Wm/VKQka4+uvT4c='),(180,115,'5c9db706e67f1ab48eff00dd39ea72cdf5c1fe5a690f9ec7085aaf19631153a4cf29edd173afd670896c94edd082fe8b77e55b2dac55ea2be0bbeb3530b97c6ezBc0raBqGzGclHry7l9lTrz6GYpS0lNADMQ39n3yVXg=','ce3ba63bbb2fdd94f0ec30af14acbe03c6d3fce65219ed1d0592032d3c8161901a5b79fae8facac77972dc5b1cb4ee18e6c9eb93e386c5cd638d27e2e34caeb0bxgM2PEAcCVL6jnddew/WHa8hHE/39PXBZcOQm6yB+g=','3e7357bb112c599eec878282e68f459dc7a2bd0adb3864b6a3176dd5cc4fdd2742d189e707b6b585c3be5489cd9163ea3b17e1ed71f30a3c82b287774957d5704BLZtFT0f1EpYJDeiHodmxm+2cUgi2OSlzvOtmpqN48=',26,'ffa24e8dab56f86a6deb0fedfdf1c049d144d76aa1a94e80f9b97acb185dd113102ba0970cc1525b99e4fb456fdd62c03eded84131d255ee61d25c7c0e82cb4f6egm+TsIZb5ne4lfzCelYzYI2xOkCzdvHKMQqQWwDA4='),(181,102,'7c359d9dafa37becc023494dad26eeee87b85bb210d5c49e5164a0675bdebe6d926113508282d951a12ffa983b63c2167f3998fdce3c267d4777524ff8120aa47RJPs4YYiY4Q8gvPljE/JH/Af7NUJKgDbq1RmbVrlZU=','8a3f3ab1a8961f86699946a08fe032fd3f856ff84d0c7e59075cb4ce768d6541defad35404f4ada387a5639e2b65386938a925e702a3aa132709d1f928f132f0s0uhBg8BCs5uz1wEhWHZHRHWRamBYb2VfHHyccsL5hg=','592f4e0c7edaa0cd8797eefe832ada78e402581d45c50139a80eac468f2afcc2d1bff66322bbc2db78f004468164415bad7263f1cdd63ae763779bb927991cdaKXU8C9Xh2g0ZRcRmwgAeeIxaQn2ZBQ1LFzBgO2q69TA=',26,'2907a24854a951774e33aed5e4d8d8cfc82114931e031e58699c018562aaba127ae68acff38ed2e3072fd6c6c94d9ae85b21858df22f214482196e7a4628d467AI+RsgUNwRgiJYpa57wdwXHSFzwwupOgWQSpZNHg+dw='),(182,116,'2cf6377b4658d0da1c7feb221ef7ab76e139f5e4585479522a71b31167c693fc4a19f1637615b3448259f15b53a5fd2c42194c7f0c69735b4cfb3040d2555a730n39OPEsUB6OJX32EH1VmqpQwtPTYyXzcj0w1H7YrVM=','a298e314916696f2d860ef5d2da1afde5f1e100f978d8993ad5f08ff0304599620a8ab014ae5d7668f373b20b18c7bdbeab401604fa86eabe80fae6f8b02286dBYqUdOXT0ygF0A+YlTvV6k7wvH9YcOIyevD7KZpldMo=','6d3b239d49d39480270c819b13df246050e9fa37d6443d689b0553d4a0c3ef8458fbec9be4c9d578a1f660e68ed8d13022655a614c6d8acd79bee6d8fa1622f99H2Q3X08J1dWAmiRcwU0xJYzSjmWLHWt+mrNGEVnSOA=',27,'edf1560c6496c69cc36196068f27fe43c9143b2bc60bffde5d4ee9e1b77810fcffa198fc8f5e75681cfab1d75a574d12ebd59792a149f5f967ade2ef848234b5WU7umFtjUNFiP8UhwB37IV9e/0lA5ReTYnS9gahL6Rs='),(183,102,'0838932e0a68cc11197cd5423e15438765df9ab065ba157a9dde0a260bfbc18acd19d2fecda1ca5b624e1b76d4b767d4f02dd75a40d90f652724b62a91d984c9/GWAq6pegb0UXk7Sz1yjzNhpwuB6xwyaS7zpkJ7oYfA=','b9a72379a0c92443e8a6eaea1022dc42ef9fd75f654d270d649c0155c89020770eb9fcb7a3b5cd9b997508e01abdb722d03d72a660df3e38cb33fc56783d898frkfgUsmtJvQ0CB1cEkYXEJcNtWz4nYNTYTfA0cdXD2M=','1004190c94640ca69b60b6dc1d26d1b745f9d1e8750d70f1f0108a4fbdcd541a638d580746b33e12fbda3f044457ec75829837a1d2eed1a1938dd87b7ebd3893RB8HAVlMM3o39Dge3XSm5q2AdeySvoZCenUj81V08o0=',26,'66863cd9688668aff5402c1ae00f8c90e3ce8719745ad06b2318a52ad11e0b9ef82f5d63514c1356826734892823749a7a54971c4720bb8354b5c454a742fe7fPnby1jyZc/iaYI4EOiStPd+siY4zTHMqKIbj7y7A6hg='),(184,117,'d703f670eef5aa5ceeda521bb5f801daef33de7632e702c67aa3e02e13e6478882cfd1b5c543de9da33b970358ab582b2b8de9c186c77545878f8d5d783b957f6CphJD2mICEXaFF24wXVnhX3fJdbIIgkoAHvoB28urg=','2ce9edc3d8baff49bff916c7cb82ae625c24b72631829c7131e430446ddff8b2eb4877a43623a5162a6a7523df75fda134658dbb49ae5be7606ceef39a63ca1b6qPezOquQFwYTDeP59cF1HzxC2Z8rx6ZqbtNJi6+WFQ=','adecca37d2e6892c12911090e326adab2714e26dfd45fef45e85a6a591bd0ad03bc53deb367d65c4cc1bad157a4aef43fba790703103eb59f445cf53fdb04ad0FvX5kGIf2cnbrwjqhCvdUk8JnLc9PcWZlKuggRLvbq8=',26,'1c9577481b348b6d6eb62d0eca644def06d642195159f350b7d55dd81a57828487061fd34acd6e3e964976c26b180772609463763dd8ecc9377b8fec6949621ansKQvfyYPIeukjlLzHv4ajehFNIjdKFGOB85u7WFhFE='),(185,98,'e5b5cc03d66736fe6ff77be6d96a1bf5570c248b6a5aa3b7ec0b9b61f73580e360d3fa8793391963e819de8ac87120dc1f5f88960c97197ad3429b8f20e42dbbNCF+tpg7fRKLcqaul65XQL5IzxM7ml3sR27/GIA2Q38=','1e57a784d33c6532346d6c6045ac4279c256581c28e656bff72ca8a37005ece89783836ff090acf6c68adbfb4d055d3431c5a21a35647ba47dfabf0242bea89bKS5LBSZIbFxzaICY9s7etcXJxtoM4Jv3BA6e1zKEg7s=','44935cfc8263e54d0d176d5d0155bf561e4eb0d9b33fd7a34ac3f14af72d02379dad0a0bb5380a4af716270d0ffbcdb0c2953c8a7a968abb3e95d10698ad76d5UQvYMIBSMG2SXCcW9McKLrNvFiLuqyX/CaliyrJxoRc=',26,'cfad804ecc2a21da175190a0ff8d73b9d465128a55fbb9c162f921ea8d1c4efb08ac36792be586e54b3b6ad06eb692be265928bbf6e84c368a1d360d4b232c8aPG1kDb9p4aiQjMU+vbJ7y/P23hhNUyaGoXSO0Exajs0='),(186,102,'2dc05f1be7b693b1e0ffe1c5f0144630875eacf95ea13c85430bfda53cb1dba66a8395cb1efa3b3ea72e62baaa082d938373d2e9e19e41448b42da9a1f27ea74gBNpu7l51nr/CN3uKG88OKkFrjWZAfrU13j+k9bODEs=','f26e84f90bb17ec7474a2d00892eb7a9d838e3f3f7a149d6c0f272320fc0d90b1d670ce927dc6da54c5082c8fda7c823592e4e754a5e04cc6e77e7d13148832fv7qFNbCClURzY7/9EbsdKuElFdZ2X8ERHbuF1OzfKQE=','7cb822458de05d42ebd6f3f122bddd67e3d8a36f5e0e7c5206df914210509efed4aa2041b339584e63b4bdd7c8326e45fd2f820888d6d301818ea82eea7950a8EL1tousm9B8FER6LCtWMNMNb0acdLJlnuBLAQba4iqA=',26,'e2fa71ba6c967f5c5ba6f7ec5fb39cfd320c78b86e8a8cfb770ccb73177633462f030d75965075372b40bf0a26fbd12c2781968cd4e6bce6d15edb7582076e17LxU+7lJyQzZuPQmYGuNnu9Aq4LEuQZ4hf4ZlRils9PU='),(187,113,'f3b03c4e5563d166c1fdea7ea371a38a5d460249dda1a824cbe59f3dc0e755005467a2f5f824198c84b253496fc9a4cded40077928d68f5f8212c4567bc32869oKSZh9+dY02chhh/V33XLd/Nu/lEJ/5po1xPQvqWomQ=','a43d0deece3a5ddb9a969412402baa4d41d079185a34d85e209952fce0c76f1515c4e9a76bb101a66c1fdaf773b7405392830f6730d20aca199be80e38670d59Kpth7OAclNRCBL0XCqKffu6o/poXRZa1kvEy40NRkzM=','c29dabf4cf41487dc67d6ed0d956c3fab1bd513fd9f4388f013a0fdfa30eccf3202b843ee02f00022e05deae40e82f226a161b95e3d109b2ad287028e6170dac3I0kJY0YY2onSvJpZo4e5iDQTIC8bV7lCYXVXdH0mls=',26,'9532d24332089667bf7f427134d4dee95000ae700706c2fd93bd7ddb989aa35678b6145cd56b0040194efd21e8ad90499d98b1398998b36a42e2f3b2a9a1b4a9T4D5rK81w8DM0+60zMy1qHcd+fm4REGpym/PRMLPzO8='),(188,102,'6dddb68493b95cef4e3f6d528e0e7447d1309e9ff5139be0ef653bc640e8bea0bf3bee5d4e8519dff88429e35dd7e62ffbd4f676e685f0274eb07c30be905a64+z0UUS9yCmzObn5fC+LJpcxk/DuJMmvxMrfI1EoUbYE=','174914ccf62484f35b689530cf046e845d3caabffa4f5a73e298c9a80965ec52cfb0bbe68860931686a115ee6f09e4773ce7636a9125aa595a56c94d7d1c551auVPnO08KTkWLUbY6ZEegLksFOyzN42pGYiyNbgDg0lo=','ab440c21a1545f1f31e140a634a9e7e8471d74802030c9a5768f468418ae0b3c97726db1ae603d63247c3c250070a978bf973d02777ab0902d366d86a886cb52FXIvA5YKTttn+LaMVS7mkllY3ELtfRhlydYtHuj9qQM=',26,'711887127688aea3af227d983a3918be4749546a761b930acfa75bf067c38c5cf3c7d85196e288809d46af38c4b72df5a861506ff49942193bb513ce99a26214cpDOJn9FoFlHuB3yCUnE055E0YTMg64GJgHlzRJlp+Y='),(189,118,'2ada00ba3c7beff6d5732f098d64195923a1911ba3b4a162c7ee967b34b3e1326112967dccbdf18d554da2dc07961751ec52e7a714657dc0fb8b278a356aeb31sYHrODjkqns7E/cOsnrF2ZhLZdknudEQcxMxwe41UpQ=','116adb008d16fbe04f8e8ce5ecce34f62650c17c45e36b1614471faaadb1a11faa1dca1ff3cd9059afb165229bea69e5a662eb7579391eda7c54ad4e56a25607qG9YbE852iAq0xrE1NYtUayp9bK1kiVI77nBh+ZymII=','600eac99fa5ed81d4da3b14ad117e2f898e93e0ce466d91fbff2867ae07f4d5b61abb23ed69f9bfe2f021b6db0049e40093aee8fc06a2dfdb4da5e3b329cc57fTVQTBPqotyDP9PSyAh4PXidpsTLaBmjKJXy9YpjdIjs=',26,'01d945b95bc7aa1c08e9700972e17f612ad52f3245adeb227d4306a520def0b95838366cd5edc7fae6290aa07a3bc51b4b453b5d8adde2d3a90e7825807cc2c6UK7Eyak2lTSneZWgtTpOtRejxFDnHyv9i2YePz4YcyI='),(190,117,'15940c489c18e64f9e49ef458685a7223deded2bb6badec94a5718e5254a01bb303607931681f292cc4d570e3ffb064126f67af58bc5ad21da8793f60d3a97aakJHj1jXfy9DmfK0VVHPlUry3PZrZb0Go3MYJGtpfiMs=','d7b56f2fa1ace160c5f9d89b3ef3e8627f4751ca10c7b87647b7a4036dbfc150b46e7ae7cee6c6f3a73d47d605abd6016f0a842cdbd0dabb8ec12a9a2cf15df3zW8e3xnnb0Z0qejzh04MXQc3NUumRZ7TbBlOzeNcSo8=','ba5a6c5af39fff045387e6a844f610f258ca92f0c35cbbfeb20cdec71c199af165a289044b91b00208b59b858e17ba43ba2e1ab6ea276bcfc8c8b5ca3b603002tkaaBdpG6gaiZMXDbumVnRCxLta1oDOsd3ZQbxz0z/E=',26,'03b6d16132e92f4e800dcf448a39e30687d0f5d265c8ec6e9d49961b079b2383cf0ca939898c49e19a45c1516314365b703d523c6e3ece0bb94aa5d6d52f52c8SrvewytCb1XVQM4EljNoQ6E6LFIJAGoSbx5KZVvdGZw='),(191,119,'1a263c9fdd54d7efa678d65bf45212bcc22dc632528ad16d823b0dde40dbba57c0b044a48c33e45d9d5d606627daaded7c4ec8d13236cd9f372b9c9ee9081ecctDAGXLo5FXXA5V3qNqP47Yx4rdATF+EU8PMHkho1A7M=','6271f96fed5d8bd3588a06fb3db7c943d53ea8383dbdab8bdcf13370956ca7891090ca8caf9979799a14285fad572754be5752390e772e60eb9d16eaa239cc63JcxQtkA5tQzhnR7Dv0HgyW87/d1dVWm2UQRoGRcYNTE=','c6982be94a1db7a5a15289f89f2e4e18f221a477db9b629a6c7c3e9f13f9b8e651a6bdd218c804c9cfa5081d7720885c869997b936413aaafcbc60d0f5a9bc16E5/GjEVYG+myvsLWhY22ZenK05JISPT11AEcepbOf4E=',26,'d61f45f04fb83f0e2e637dbae2216aa39756fe3d19bc93a2d74cb44186040fad5dabf9f0d49fb261ab472dd06921f527d74fe3252d193c8b27fe52842f7bd09bOmCU4Ynby+TVGemXG96lJVtIRMzm2Lu/uMnbzubMSew='),(192,102,'106357dc1c0e9675ddf18b944ac76697a169ef06164861fb7a9e54c5a516b6f34dc1618bb7bff790c04fe7e8535fb97635fa3a952e3ae456d6fee6183a7c55dbgbDnB7IFmqPHhbGMHuTna3gOJBimdNwK+AOwg7n157E=','cb849f13f89cab284ea652cfa7e77c0defb4b42588cc7fc2b800b062c1fb52053f0e6348e7c7b40523a3b1dcaf528f5cda0356d48399bac07e5b8ad1b77c79c8I3GxzyybvNIN61BxrAbm7DeaGcoMcdofqaAAJSQK1oE=','01cbce06ba5175fb4850c45c410ec07189cbef4cde32b9cd16934f1073e80660d33e9c8c4617bb273a95828d8033733884578d3242847b87494757c11d872ce2nZYJVdBRXX5TQ+IjR9mY66nxH4rFsl6Nj64kiVWqT+Q=',26,'647fc5cfd82e72a3937eea3297f78186c21896b3655ebbf39178898df009275f9933f8bee452dc830279c6dea17a15738adcfa540efee39efca0ec3dde4213c5XduHDBwTAvTMNAcZ7F5YZ88pHV6QhNxwbzxVXIjCFAo='),(193,102,'0f100561add0f99a80983465f8e4e3a08972e26480456abb152a850108278e9b8c678e2d0649ff42e1bef0139bcb047efad14f9b33fe931e60fad552800c498cWQEePyw5KnH4sRLP1Nmg6lE+R3fEOFtNnjxSwfI6Cs8=','dbfec3ec81b3fdd65dfba60ab84b2ac9bf7e98cbc03fd324cb54999d5263cdd118f556c38a84263915c9d465be365d65609654474b2cd4b0eb7a0baedf990bc2iRHlwClcMDiIlv3kPiHMLELctS08lKyH7kE0XHkDZJ0=','9b82bfc992157d229927e8bdeba78a003d81cc6cb89f7c5d56c3d0e57851e25f81e241ae6a53b149ed912ef088239dc121dd4668412d92c4a300f0904e43b9ccT+7jNRMk8pXeJjtMvz8yrWbv8HStNgHtYK26su6EI7Q=',26,'5a68d40f8828ce90ed96832360ed0067cdb4f39c7b26da78f15d5601d34897bc24c8df9719d2ddb69c1d1d269b86c063d8898168f6fbcfd29d2e2e2b5989ba39DOJ7LI7TlGGpbNxhEbf4D68Fr2o+DhOxSrMAuE8ys9Y='),(194,102,'68272cbd2253fb0923d59304fb82d63622de4f91db286229da625c44b040d22b806e01812cf9e28bc255e27fdea0404268e003ebfffe9d3234131c4e7b8b6e83Ekd6s39ngAk+TSGMabmy/18AbUll0GFVRaRiWjjcDsw=','a3e2fd34b9171067fc2f5d405bfd0687b96e955f395ac6a7e58bbd28c096abb544e814a2ac9db4011a1da4bbf8ad4fcbe976163aac79c05f3c4431caf1914252Xs57trsopbD4Z8zYWTX6UXWW33/7fn8Rzp/POSxKM54=','d11ea6771d430cb855d7f6617bf28e2db515294e7950d6cc4fd0668719493029ff4b8452fdc21f5f6fd455b83a3040d5c829093e6e55e4e0f776933a781b8c7dGEihuCAEJPRuKyICsBtfCAg311oi583l5eFSMFpOx9U=',26,'fde06c851fba7e3a51f1aebc6e721680d6a18f91a2aef1ee50db871171750b74050acc73454ec95c2ea7d994137ce1df42fa7d1e15c239dd0e1dad0c4ab0a215w6xL+TaUMAdTcvnkMN6RKatt0/6QJX4xX+kTjwuGmBI='),(195,101,'98c3d3a48a2ebe0ffc6c84fac0fd8e00219150649f9c0f84dcafcecbafb1d1552815cd20796362296228337d1cea6ef46acf7facc41255441b8d185c09148f125bzV4jBHkgPT1Nkv0sVIvvz/rhU2TKobe6D7lIfYdA0=','0bac6a12b329c7d5cbdfdf09becb6111d259775636f1a299004ec4f4fd8eb6a82bbe13601666f13a63beaed56110203df758e4d67cb0df48ee240fa5ea8c022dpclNHw7z/nsmhfsZKB5oRSPMTbzx9sSeaii+heo7WVQ=','a88cdb76781d910b3351c4ceb89c2e1a9ec58da63102370fdc6b2a73519baade884f1f169b4a95915f463744362c3bdfc166b206627b92ddb5a8b2fb01b545e3CNtBNB4ULr6aRl8aFlVULI+TXtkR/nOM1ppv1DXtYGw=',26,'ecfb0f236ec280fbf9be8c8e82bc8d62c28722a29403f43050cf80cea6099b6d72df63a80a06fe875ef8e356b992567345be6a3e32110344b41340aefef93c9dErc54TKxXIJsMqiWnlg5MUKmcES6EO9eONW9F9Y9iJM='),(196,105,'dbd26d7f477b6e462c191168e4631a0605bf04125fbe5f064953aa91a80d264e2e0380da80a8b1964204195fe46adfde7f291966e62aeb75578534c21a150a42TEhktERbnUrwXwhc2R2SGpV6Ct1n5/rr1dhKyQ9DE0E=','2ba6a6505e6c776ee2580860ea3ab66c794301c4c1686d6250c5c9cecfc6d81ad6ae19a0e73491611c3f0d984a9f39abe1e529c18b912fc7d3c20f7271d4d600vlphHR8w7O7OpyikZjH2T1Gpq0LIj2c98ClALtTPalM=','63d4369ba0441c1892bbfb0ba7a7fb651f33bc00f27d8d487524be0b3983a9f1d2f97843a9e8d151bd3ae3db6128bc6942aeaf6db80cfe9d44a580eacca6168a0FzD6tbAFkVxyRPShuwl3Ov37C+/xo4tAXpx+TBzVqg=',26,'3e8137f081c4b466537eee09e01d264a06730cd234249a84e3edb7839b550ed87d185921ca228c23d3f4521399057bd9cf6bbda28c5723162dfedf1d480688dbXr7Jf3wJJ2IZM9y/C5NJFrA+WL9PTGiHrJ4smJtk9gI='),(197,120,'34b78f4347d89bc5ac1389cfbc0a0c38b88cff132416a7133898a7192e37c30968c83a9529d51cbe1e11d069529df7801d021bebc1388862052aedaacb22b4e2G36RaaAN9mjkgp6UJAfd3buKK5ZDBvPIl3+YsR33U4M=','f8d52fd2317024d6081af18c7cdfae725cc7c97a4b1f3a990e257e425e7225239c2924b5cc6444bd71410739ab154f9b69e8c22fbc7518cf733472faabb2b3088p98UqfEK0WK7X/WP1sHQD2PEVK9J4FInlzp30Z63/Q=','4bd8b91fafcdb5c65a4ca6e89707d75e79bfbae737b096681610b74daf71bf9dbbcb5578a9809039419a92d69dc5ab91b9140db57e3c8ebe1e0e70ba28638840lZT4Y9lPgCcn6UMVTI2fqpoV5nJ642X7HKXp6OcOHNo=',26,'9e586db67261eab4be32894f843d8cd611412da3e85ad95d668761226e9188a49f181bd4f5ea120e83e341bc77a7a85e1dd26df5e64ffdb6999440bfd0bc172alcLfxymhjb6Mvny//IeAdYobgn2KoBcK8eKgmQTF0qQ='),(198,121,'962a530c30bc23c3e4faecb5d270d6d90ca295289d5187f926059be32c5ab5e651637403003da308b0b979e839e260dacb48592aee728598d3ff31e9dbbbc420ts7XNzzbaVNbtaiKONZd8a9835FaauBdcHLaOPDR+nI=','f136d1fdfb34bddeb5b2d44c2df2c933a87eaaf564be0c9e738e749239e3bbc2a9c35d4a87913460e4a8017e34bbae9cc6d3ef629cc013f2fb2a83b58066893eS9J3VwRiD6qf1K+5vdeXp+rVKT7BEtIZPWoKxK6NmH4=','edd38853917d8052c709ae14c97d9e0178ca73b307f8b0098247bf0f98f9e2e0b489ae08358a18b4f9f21b199ffc394d932f965b01e7d73eb3e4aacfc79c81aaghcXYhD+PO1EPff1RXqFHEqCb5ekB3cJ+LhC2GdgVWU=',26,'4c048f29c5d50a649fa57ae4a00925037f0e12e6411a17f557fd86493db9555e826a8e7573e90df50ddd18348bd60c0c575c58f6f857bcca835afe472f133068F3ILhIX+PjVVFw1oQ40/Hvm1MkcKRGceoDcVz2yy+Jk='),(199,120,'2aaed4a1c2c920cac26fe49fe1902e1fd449d15d3629e571c100db94dfc7cd165abea86e2562886fe9a32eea74e66db48b6c7b5c11e0067124cd1e59e1aa9158FvhUOIWYlK3PCDiqVnO+EoL2EvTozXr32+JQxsgxRlI=','d0f2231abd05480f7a8fa29292dc15e2c66b720acc4e373866d95193f1f0d52afc4c2b8b15550d613039f0ead9211d3d4cae5e60869efcd86535377583708bcfhXcBhvE5CdKar5v1JjzJhVd+wzgthFBhCj99+vdIjgw=','1f24b768ac302baa0fa7d204de8b49d61f662898c9900968e36cb64fba25d9fce59a5250816bc7c9f48a66daa3899a30bccdb17cb93d1bb83706e13ff87a540c767vmu6V1OXsxaStUKQZiGWxU1OS9liL2FuS3E/XtIM=',26,'fad4f27818ad91d9b8705239ad0270aa65b65f111d5bb29b1826de150723767276f42c0d61a76774f164fc4ded3ae5fa4990f30cdcc9d50666e5fd49aadade1aQki9lBEOsc47Gfxr50N05VaTfV7Zvp0P77zLPZAd00E='),(200,121,'bba57382c5a38088d3ef2fd5d8707cc1ac8757aa4d467680dc231e74cf053d901fb1a9b489ce5dd50afd230828708c4081a6258d3ef2e323d5b9f804ba1c5eb3Hi57Z+zGWnEb9ZWPQGU441QrISyjvml+Ekw+QF7PqcM=','550e3d22ec0d142183340e51775583ba2f74d3cd4fa3abca5195aee034c9cee7ee56729f6ac64ffe9240dd99e0be53c2893b69894cf51a9c83227d41501a7e7bwKepduwIC2Rvox/ri2tA1UiMRZGCyxxCwHWsG5i3o8M=','8fa90cb6e4ae7f30156a4204562195ae8e0b3e89efbbd08ede3db50bb8dac9813f1953d8ad6be56ad1dd0bb26e690858b6c2019d55da230f7bf7476d5142c539AicWHvxhBrXKRrGgRbi0aKtVxt6Z/ow6EUL955BuVvw=',26,'af052dbc944c6b1edb53c3582ecda3ed4e289db85e1f3421f36b9b54d7693daff6ae8f3b7e8d0a3762f452323a0a310e31ee13657284bc0ce7dfef593455e7962RzuhsbdPIWOEMe6Y8ozWVffHzfo+rPv7ehRjJSVI/8='),(201,122,'fa5aafd798d21b9fb0f2c899dae243e28ef44e83f67a132fecbce3feab426647564230e1bf33a0d97ae3e973ed0bb508fb6d475b6ce2e3da5f0952e49b5082e9hnJ9iVKtOy85Zxcqd+INbSJHIzxPOJNCqkIYZKLlJjg=','c05751422ffa5fe10b4fdb03d92f71793c6154e59eaea6c500eac860625769e30b26c6a2069b8443c28d1fe4caf49d8baef20a07c3ad8a8ac048232cccac95a9QwzVdHcif/pYqF/YmHIHPUlBv/9RR+E0VMglPL296Ls=','5005bd3fb49b70b0d699b8dd054c9ee5b918d5cd0d6099b7d408791b5d4a03cd9922b5f41e8b4f8e6ab26f086a34d2378bc7a3e205729023f3cc16ee7d3300e64n4xpqiHqCjY2XXfKY+rmdYfu0o43OL/HkIdojIHhus=',26,'17226eb5cfbd5cea9d3657934146dc726f15c2d560fa0394c6c5a7bba10957bfb8281020dae70202dad33c55918114a73488572f0de0236c842715be4d08a87d/Utm92H1uz1FMJOsGTc2nqcG85WHQiPU8Be02NjOs+8='),(202,123,'fc03bd80d2e04792ed2d0bc7419cde9c1e345de19fae0d6379f19a35ede55b05620296fe539826e588ecd57791f1ea342335f758da635bf75971a9dc5ee02428S7BqQ6jQsfMNcpBvZKpkZ1B5FlFih3jQ08ufB8DKUE4=','36d4530878dbe0f022346738258ad4548532cf1aca8425f811f024631dff5d618fb9e0595fab1868f1d6b998ee57fe184b74dce5741d57e2413128b25561e28b5hqYzUf7LpQs+xTLZNMOyqecwgmQP64iuGQVWia2F0w=','b834fbd4444d8f9b28150b517beb3dbf96640500c5462cd6c6f54d4cb4e10c9ad58f392fdb4ae622c9960b46d2fa6a222a1776fc26e8ff0f3f985935dfa3be9039o0AAxLZFiJNM6z99isVOSx+fqjFODEZRhs8T4Evxk=',41,'ea14cd3492d0e10e12e65e411221eaa25bc8040fcc1f22f12498877326b800b1a69fb25f16b6ed1c23b839db0de0c8450974615a938e37698f270705773f7cc60/HsmiY/tIngGHRH+bsRhKBS1qihPxPQHjOYPI+/uA4='),(203,124,'7eab3408abe8bc19766871d3031275b71b29211ef6edc842c4a92b263984d0d64bb6d8c5e11432379191131f260a726953e0ddcaab1f9a6ef34810eb3085a7d8dX6n8ZaBbFoQELb6Fv7lipmpnq2hNi9Oh78K2hXfHXM=','15a07c50d6dfc698b58b173107d5817fa9c95fbdfc5dc445602d0155db21b8ed5a67e3cd9e20773983792cc35f89d0362df256f6bec44ecc013a6670bd4d071eQ6KP++yten2jM0sN9kshc263kC++m4SqQZ3IEJCIQ6s=','0d1b5f88adb8fa1dda1576e62ffd19fc611257b4ba23735f0698da3ec8142f3b90a202635f58487754aa6d2387e6418cd99cad8f6b53d6fcd2025325f6156b6bnk3jARACN6Ks0k8Ny0k+sRcYD1s72K3OP+0eg3HZCqw=',42,'b71a6b428e3bab14d18d1587d3125e012381bffddfe5ebf6da32a29ae8dc4c1e2bb8ce41d70d0093f2253d89bf1eddc3d222883937563b0ea007c21a9f1f55db0EVzm2/TGXX9Whqa20uMhgHKClb1Iv0NwYN41lZ8W9k='),(204,102,'25c284b54d3e498dd1456f0b32ed761b99b0cd23312862fa6d122e73f0d5d2754a550d714408fe93d6d4bd5fdc9552bcc84e35c9a8db15765a9c46c35c0682de62F9uBA1UK/pBXrmePgDl+RIA093QvMDIMsUhBxsnIM=','c46dae9a7997ce4889856c2ce2ce1318825e2ea8f2f4d84c7a81a68a0733e492a2ef4034ef02c73c0edcd7951b6dba7c54027fcf3333ed93b5a60f65fae332ddMwB3xJlhoWcObJGuvggnOVuO9pS1+8vFWcTSzSstYTo=','bf1b035ee93bf29d3f24cf2e0d541ef1bc22db56f3c6252005fbfeed7d7672d7ab0a4a1018b16e873aac2154c3931d852b5ad2f267d678c0dccdc9ff8a3e0acbwVK1V4K+8iDC6R571GMM7R0811OLeZ1DGeTB8yVzKMo=',26,'8f6a5965db25fbfb7cbdd53cdee346a62592be086a5d48a758a7ce0ffbf5e9e9cba28b3ec023960679fd87a959716152302ae06c12b3de1fb0d813be2fe32d70bhZlCcJ3/xlLHGSXi3KHbjmO8l7Q4daajrFldNrL31k='),(205,102,'c7d560f4da5ed6ff0383a52f1c387af60ffd4e2001e8049ce79de26157306250b0764ce9c27d7d7aea67556495a76ec3df33ba653411329b81aec9bc4e9eefcfEmNPGgyCvdEOsTY0fY5kVPfk3OUr/fH3gt5tJ24yn88=','f9b7678dfff8f375a7de62c135eaa14c52f89689bd701ff450ee79e7726a038f2442b882bf4a13372c1e0c943d7b60138c7a918f678ab863816f3a625787a5ecYIh09fm28OenpAbD3pkiX4MlTQnUHm0IEKfCup+rH10=','2c476cb5967d67755821cc03146ab27d0d9efa31a526f65ef2973499c0d61cc6e9dfa8eaed40432662bf1e78d2a28034f6549fc079d38f53e615d18608d3787aweYHE1sCH9V4hivC+Dd5Ttn5kvxECaolpEfg5Y53piQ=',26,'0ee706544e1655005c29dfe94c086e5d3de0390687469578c26728c10bb88169b31b180fd58c9fbd71060aad59645c94ae83916507e3546d647770bf9f3e99b9m1AnQZXuL+/9s3lal2ZhKc+NuGF29OD2lF1V0PazM3o='),(206,102,'8b7ed4db3910ba6b84b058cd1ece2ceb7dfdb800a5669e6eab4767cc97c38646e488c51b7e8a21c5ce5b1e3ad9079de821813410dad78f4e6b3b65918b714e9eDTQuhbTCr0lk7906xRubgqt6xhES5oGrs8RNg0h1dFo=','5afb6848b1cfc5f6bf42503aa90fa05049eae227ae72c67b3ad102e6441996aab242a3d76b8f1cb1b3da0eeebc2392447c2cae6bf7c4ff93fdfe641975cff034VXMZY2ymZX4d26DC2bKGoSHC0GCvlGJ4VhOIDFV0O20=','ad794e00a7e677277d1623b4c442310814936ab0e2687a67e74c89ad0b8f62f7d695ede9a0ac2f93b53f2feeede1020296d426736bfae24711908e1e29bb5eabbA6j+SbwQEFYsFYq6hFoDiReKe37ogDMVscnQOMSY9c=',26,'2b519f2403cec9f0e6717621fd0363cc07788b927d61348d12eabdc5e5bab4cfe5fcd84e654039d1ced9deb40893144a6ddd105405f9ee2be87fc88e89af1e14VeVjySyfZu/mnAkGnmzQZETP0KG92Nj+CJYAZTAGROo='),(207,125,'161e2c9d2476db5a85ef82025e3e6ed7b06644e99912a5ad00136da90a1f7f0d99fa5641b8f6b13b48dff848c283e80123ba4a2b596f750ff0efb755ea513330/dzVN58C/TZbvY16BUCmm6UAA/oQ/zsMeq22+1SmpDE=','08154d4b87489f7f777713a00f28706d381206e84f61ad1cdef2c9047ec115faef483e2e661fdaa67f15ea4f62730783529230ade96dab3273c83ec332c7caefJWMyn6RpxLSko0sbjHB42WVtpLv2N0lp3tHuULqRyC4=','9137da463d3e4b5e4c7efe5f0324fb4afb3a3e63e302f52b8aea953b4432303640c58b14ab5d091ad8a2dc6ac31a15d56b064cf6943a437d19dd3998f017da07xzN1QR4WaP7eZObuwuwMGw/MNgdgWLjFdBjgSEuR4IM=',31,'e74ec64c90e16d4af401e85fa48a2f208096b6311e772ebf5224fce54789a8968b45a4ad07a7eb333e1dae92670621a2a013016f30a3584a5d2d5dd306357160UCr1/GD7hR7Pbu7tVxy1SY9pd1YEIi5t+0g9Seq6Qck='),(208,125,'7562628ba161feeb5c87b6217085076a9091829c1c131cd5e92f424d1a90d827d0376d1f75441291c112d18e6145f2dabeaf405b0c297ddff46cf0742dd9a368UC0HejDfPf5TAe1JvAWYvi/eKdv6CKYYC3Vazia1FXw=','d6137d9f8ba877a7144dd010a7f6f5216b287e3f913d541ac29de0138724f083de0e7c01ed893695d2aede9acdba068e2d89e238bf17d92cbe4b2654ecf3f13dx8jIG0KEME5oGrZm8UJ6G4DP1R/RsjNKgpsFcTA3Txg=','d9db57de48cd036a48a83076016450572de26d43b2b1a3389cfd3645ab1dd4c890e7dfd25281f46a65dc70e402d1534a04b45c525b9f9b9682f5a16b2f245344k+Mvkqd+XDC6EJky4mp6y4CRwqHmWw/De9SgT0p7TxE=',31,'5e689b167f8dd0b38cd51ce83d2f1b345d4d90f4a588ef964826e9b183099ba24fb859c74173944e3cd330a2a5c9f28a7959f698028f7a83e30eb07cbe767e12JakNJxkp0Lo7SlH/CXMJUQc/5+Qqebu98UqRCsDy4dA='),(209,126,'ef71ed9b71225a9b75b4e8f9ba5988ccd2b4aec653601ed00fc38c89a4e8e643266fc29442fe8a6de5aeab47f9f80272ac8cdc3cb6b7aafd1926708935fc9b71bHGhLjcseIovbAnA/8wTB+qlL5+DxDOYYRdX6fJMrqs=','113190773b0ab5b41cb8004750669136ed014fe4373fa6aba27a02f1f23bf9d8b048bb0500109419e5ac03ab7b3fd95af266ab6c58886c5acb0d2435af688843eoT1z5lFH8e6CU/gWzTZwCxFRt4QHn1cqV1J5EwD0e8=','79e6e6c99f50f93a02cc4ed6441ace98711fe8e57ae4fec861a4808f242098b48b9353e97f3abedc8eb438d29c16db0ccf0b5e530c0c1df835adc57dc334e6a6ttS9twQP9Xtj3GR5Hb1EthCX84kJTxgetHcoIOuvZfE=',31,'18b82e443301ade6c124ab7ed196711b1e19c932a08a09f949e5947367f9cc35c8280d52673c60e4789931e430cb1555ba4e82ca8c90bf4c31667c92d6807901kAHzKPYJwV+zAEhnfqerrLtdv3XgseT2AcNPbFtHWnU='),(210,127,'96d09640ab1d14c03262d829668ca6d90f7e58cb90a13332e3c187603cf303b59d607579b8e203fbdf6f8efeb6d9e512b12889b0afec6feb737f3b3b94405892+vVHSbDxSQY+MBX42kyq7P1WW9ej7LE0OmGNUUVl80o=','325508288bbb8a811a12e2d243b96e9db80b7294a194f672979db99fd1ea4a378e79a2dd8244fbdb57c727ba3201f6fadbd80b1bcb7c29f65d2e22f2bbf14b4bbYzWVh7IS2G16vCeqXNIrnP0hLVX1mdtTn+MypmUBl0=','f68866e1e609483e8a73fd094041fe7d31430dcad28a2b1e3515d618c1d516d5ac527344e53715b632054895e2c6266c7d2877c9fc6d012ee632475114dce3838XRYBIvl4oTjzKcBwJyZtlX6+CuG7LW2kpbB/pS/PYo=',31,'af32004f96b083944aeeb702141175460485bc163480e12598b88e6610eb9e31abc73338c5c755d65bff0b11225920d4de3605298f04fbba166d0c81387c4438GDGdzHODARYakoPDz8sOdKFF7h5UGUBONSbQ2kiKYac='),(211,128,'3b3756f39912348b4a20edb898cb7bdee1a81165018b43bc76d093537418e147a5f906ccd109a3067b523fe32f4afde12572b98eeb6738ce9af6123b7fc5d1ee4f0edOdoBhdlupPFnyVHGbzgUEmAPt8iOzRo8DE8wlo=','78b90a2e85a1a1eaf3fac3ab6d3f17d9172c2e4bff9abd23fa1d570134bf5c6eb710534f5256090ce984125d944929d5471a2c4153df527bd8c2c3c90f41fbe93YeSHBwBNO3WYee0eamdMHcjCeF8utK/teSu0f1Y4jM=','8fee154e3ccc6311d9c25342072b5add463cb6543182ba3c369ae493ae0de529638a59f2ea68305ebae3d4f71ee143e3693747d95eabaabf4b2f5575c36bb626PZr9sInhXef3H8/thO01K/B7WIn1C+NG/KQ3od6OJJ0=',31,'10b38a5d3d9b6561df4f804bd4168c7a04eecdf89cde41f67fd7fd9dce0e18920e1d61e4eb65b41c3756caa72f410d1701e4360664684649f1626f05cb58a95a5zQgxWSORQGmEyEpwrOgTokUjLfTm6EwWLu+ibmcLgk='),(212,129,'40b4dc004918eb89103a7346d07d94f0b8607faf4164e03f590e89763b625733cb3a818c6cf09d003520b27f1500f74bb2a3263912e36274b7068ea528e4ba33mAJTidTSIDPxWkQ6m6H0oGaAOsJFCdd707SKhkZE0Cc=','1142962fa79eeb8f8c58064f9dd1bb0eb6fea58c583f5f71d3114ae0bbcb41cb73a8869301d992a5496409beb3e10ee578d6ea10b814e85700013c21b48ba74eXe59hvql2Qb4MaAFv9qhmSkFNKdhh25UeCVPwHypoUk=','3e781a9068b0e8a95508bc2a888f23cd125496fa09fcc2188443d961c75ea0e4ae8720f9c39fb63224d17161ed5cc4d94e42b25cb03593ecaece640d04880531QjrdfGtXrgP11FQbhYjYkBQSDrx2MoYHs5V13OO1saY=',31,'7f423b642ec401936e2f38aab7ca73f39e378c0024b103af7ab16caf6cfd8707f88e1b62bf51b6fab3f777b078a31e345e61e6a0ab5246294a8ed1693ef89e82xm8VEzfT3Vk43/NYDBTd/gy4qhA5R8djBhZrxNnHa08='),(213,130,'6ebaf33ee9755ae7d7fd70078f28cab5256e207ab195fdec6ed08faaa2df773b61c71ebaf4d4490e47f62b4c498f9996eefb34d127410486dd1066e351ca4887P8DKE5ngtAJmG8jhSGN6tnYND1YZMS6mGWt0o3jJzDE=','bade29b051a38d936de32aa9070fba27fbb373e0197ae9acf7bd8d62f375302cdf8dc8352296e587e5d19c8eb31ca9d20fdf85ae9c108ca340de57869c10e9a078E8n92q/jcMgWBx3UTsnVtnUXKVpv4fuCwDZlyveJw=','f74ea3ba6b2b39b2fcb0bd8a1d306085e9b1a41ba21d7fd995f46a25813814a6718dd1f864e27fa3d07d14bdea97560bc4e6e063b262904e4bff2eb0195fc64543ecukRO/g0WuxeBeu3jmq1KZkO+EP9X/j6QcTFyhrI=',31,'7a88f48c08152d5d92b35626db1642fb5270f2c3aaff37648f858dc6cf0e6e96b537700176d3091118dc175db5b26f6406ff9601607eaabdf32a2eb81f4c90f6DRK5vsBe/suCXcTbNCJYxG9Ud8u19rFHya5KOptmIeY='),(214,131,'3f9a1e1621ca0c747a370dcf02e7bf54f37bb854c3f1c4a04c352127cc268a4f50e2152b06550e6361eb379f57e35b96d83980591ded82745ea1dd33b8df74f0ZbZQhjnyFs9YHszyZtNtwBd54WHzB8BN811z5wzQE84=','f29a3343d96d2221401b0c113fae9aeb64a9b5bf3577e9d57b10e35d980bddba78c72738f4aeddea37893720dcfae3927401ba694c7645e3c0c39c057015ff70C+ViIfVkgtwHGWxzVHI0+DR3OR5VtSqCjPjKFP/6O6w=','e302d51057c488f379e38f55ae4be2028f2dbe8ced55d757441caa40d21da9e262c57590b63c1087645554b8d8d57a696957f6459ad057f77a816a814c84c9679XC7OL80v2RSH6HLnEeXir5gNtett4/xgQuIYS1Agyo=',31,'b5b40b85e5ccf2804e5e3fde78fa12b75ac0d34a5dc242695ed2311639d71b48786a5f0d1adea502aa9308ea1185c2fa8d66f35ba5077bab6fbada5cca200b06Z8+zn/j7YVsfwhKY1GXtja3hjChl/TzRW3wRNeVjBlY='),(215,132,'1b7c6afb60e02f5c7cb6fc8dc32adc64623a0a926240d5cddfef0d17fcd0a4278bbd73cee1d765f245449c550dffa1dd593d3db76c76af49aa2a167e2885dd93oM4KLOUtLEKDzMqFZnBDjVfNPOfk4v14XFJUuMMiOrM=','b04c4da446952bd5a4bb61333b0537e07a2f0412989056c8820e85185bcf2893718b814ae20ba86522aeb59de0c4caf213c04dcd5476355821c777103838d1e5nK5008uFbM6a9pcbL4OYNHZE2nXNJy2VEVaAfiRTJxQ=','c7d35dad1fedc6eb80fc738c7061735f22ac6dc9a76edc66f6e6a6c43c4b2af8d7992324d4a354d0babe163f1303ae5d674cc3b64793c7956a73bc3315a2a1946pVknKEj4h+Iqy2+6W/iRMLvBG6Tc36p0j06uL8shZE=',31,'afe80365e791c1ad8922c1dac1296955afec3dec866a05a74332d8c19b0ef2bac2e0f4e3262d05e9d3ce1326608fe3a6f57405ea76feada79d1ecb90c6dd2b215Yh3sBAxDY5sRitZMKnMFBG+fgmNnx+AkA873G3i4+0='),(216,125,'1043c21075c38f586d65e97cc75a2e55ca27dc0b9a412361ef115cbc7209a4e7ea30289e4994aa601373f273aff67ad91b42737935355d88ef34297acfd61e152gU/Bh3KtY4mKf1d/F+BWpfGdys9Jh89oLmyQU+Lj2Q=','f3788029fc2b50d125ca8d94fe09742d064600eb6e2857cd482a0ec8b77c9d01c037ad9f5b8c546fd970794875b7c029ef841343cda4456da0a56ece68f1e7d2eKFnCZaPZ3wSVQMi7vjTISJ+1MH17ai7W+noFOkfUvg=','289e2c962323b6886db6f855bd7620c53c1ce4eb480028c8e506b3645fbf5cf13151d67bbec50fb257a18a9e906724b0aaffc2bb4b0815ac49a42a7a5ef24f22AKEkdsApQkM8VqwPbxLcFvT+krPvLsUokcXNX1A4xas=',31,'a200389c6bd7a07aec4ffeca286d77d76baeb33914b782e24b3e60644b66e0bd8ed6517c3ed66ed399f76a6d275bfdd658498978c5e96c4a9446ed88b52d9e66RaJWuRaXFuWopqZAKaGckhj/6mRsmNhrEP76uF6TH48='),(217,126,'0ef4f4e74e648f06fda52a308c8f79cff6cdb9a6d95f2f3a0ea3fc87aa1f07318645a497eb66675dac08887cd1173d0c194ce20655af95fde134a2e2566b74edgxM50A6PwdR5Z4DOkes1NWTSd4r1UtivLD/1a8omXKk=','bc29ccaa93722076f264da48c890569df76dbf3f64f0306e4ae0067f896c84eb2ba8f015b4b52ffda1fa3fc0725a69e5a3e4440ad12bec2a82038024db63970f2DddvZgNmqTRAwFf0Uq1mq624edsxKWXpNN2MQvU47Q=','4ef7a1d184bbeb67eda391375ff80d9cc56921ea2772764b999680ec5c3f00016022a2d2224e618001c246317650343bf7669624bd72bd894de032471a10ce69KSzRZ/oh9NX4KeSCxmRpVma/Dpwo8OIQRf4WOet4A5o=',31,'c1f32efb7297812a0a421b8f6f3b1881bc53198443ac04d4a0cdf5bf78de46f5e3617fae2b4b5619e90c323c070655bad8563238182aafe24ae313959c9498761ixPoH0h20R+WmVyEnX0za8MZQ3tFj6mv5nxUH1UYZo='),(218,126,'385ef919c442e38d5f6df7d4cc819362126c234f866faac50d915e3dde7f20b08b855688016c991b010b867fe3d7475d774fbc9934bae6a349687909f71c7484M8MdWwFZWg9RPdTOQVaPMMqc5BrCxzVn/4/1nedmQv0=','7a2530d8bcaf082120ffb8ebc552f5cfcd72461b05f3d31c6ad7ec1f6098601c407aacb30bc76fdbd7a6a88d3f8bf0d4bcab134d0c2fe4b9774ea729b42bfbce7AdnfZnkH0LDtQHuY0TXfeIrOBfylmFIhqYzV98FtvE=','3bd5892b3942a43a08727730a51b5ab07de7374414dfb830f3e4344bf56b3c70470d3feba8032ee80b27075347770bf0cf67ac39b17b0f83af14283cc7033ac88W8jikx6doI0d/G6eFYunBym5fPEkVqUmfYI/mbWX8w=',31,'dc13bf1c9fde8b241f19e2f003b9c271a23d210311838e81355c765cddf2209fe4ae0f888fe29216db440521106d9e706994eab07ff80cc3c4f32ce0b6566d8cO1+shUoGhdg8xp31Guh2ODzKEpVTxsWX5tDmm+EV0z4='),(219,126,'4144101c07b4724e3638e91eb928dcfe8b3092e6378ec1e3f1ce4ec4025b19a2c0db970eabd45913fb35433b5fbf436649864a9b38d57fbfe67a9e437ccdbc19yN1Ww80A0+ffokqQ6toauk/J3ZbuuKmETmB2UMdQBk8=','45dadabb06a49db1f563afc59a07168196344bb7242077a2a061023f760166ff22cf4ddf8485e6a53c06bf3392f40e52434b48fe10ca9ce2fa221366a68199daRw676vP2O3fI+XvlMeiP+y6XBI9jnSiHbCI6ziqJPBQ=','b5515f8dff761173ffb4466c4176fa3da20fed9cb1fb3954a41d757a5bcee95eb11cadc5afa3fcd246f4d2783d5e46a41957b86d4e22f061ed3c5887ac07b5d28PsnZT/y4pu78lNU7nR/1CKS++kNbdbAZofpQcbsTTA=',31,'18ef9c2c4cd696fa214129ca4689d022f220a61b9ae2d616d002e3599d078d77d7b298232858813cbc76b28b2d943e709fc6eed15a5629266e2a13f9487568d3NS9zZGLoEBmAQkv28JHnEnvllBkOZwjoeYTbVUGvDYY='),(220,126,'c4b1e2ae4928e0efa1bb7637b290c44ed99029950d4c0a9b8dd1c82a65db64351ca649439909196319a758fd502ee176b82ab13f99d47e7ac98dae36c02b645a0ZiQ876LPRRUKGaElBt2VtsiOE0QhPi9BcAgNrADiw4=','1114ee056551031b456ebfd70caec384014a1098c0134c18077c92d2e0bcf02ac81121b84e8d0dacf18ea3fead51e2dd7e347b4e5d690cdd595726f4ad8a3c84GgZ1tlfWXgX5Ro0jXKy9hMQ6WD4cCZ+ByZYFTbHunHo=','9bfac4bb1225e1a46d5893a922f15b388d666c362ffc2f283ed150eb0195ed542de018506de02da7a67c142fd3bf7c848a5d51ffd732548cda07cf6e3a36cf19Buys6HzwL5UMxxay07XP/3Loj9wtYyd+Lmm6raspP1I=',31,'25a86916ac5c6dee7d9df25e1d0a95b5faf96152233babeb50bac07ba3ad4a6d21f2f840a31df0a7451df5c9bc76acc7ff57d53864bb49b2941ffefd0208effcN9RCAZ9FtPqzOGKL/+RXsICn8L2s1ejy/lLpPcj5P60='),(221,126,'43117d9a691224797f65ff17034a0cc71dd41f8f88bf0144806321adec917d82290f9bed1143bcdba887be780f535d63eb3c196cd0cb136e839b674a1635c2e9ZctrI90q8vAA2yBIHrC0SV/H2331puEWS+E7FCrW9o8=','6aee27827b59bc72a9e595d515dad500b4362d262754e8887cad555ebc5c2841129f97f8f8d6811e0680b914409eafe8d1679f732155cc983f11ee0d10dfd879xN478yPtINyaMYp01NV1xYBwxSO28Dl7WertZfN4w+4=','f84199a1fee2248d5b0d0a42716c0894485d3d169b1296b953c7f2edb8cfe2269fa4869eb522be6a20d51d3a50d3175396b956e555c9535e37ac3e2f7123a5397Ae8B80bOnoCyWXYiFS2CdDG0yaax9VFoC9+Co3CqyU=',31,'a3a1099883018bfe95688a1a4011660426cd996e2f913f5d260ccb53fe5d92948d0b973ab8f1f33350d4ac95b89524369073c0ee9fa3b5b008a990dfdc78e460lGIJCywXPMCR2byeS++m8LXOgyroS9ROXc2GmJ8C3E4='),(222,126,'5b40efcc9f65d8c3f50883ca16e1a774faff395572613f2e45558fa9840cfe8c32b61326e661dfcff5f281c17919fc8f92320a2f188cb9ebfbf8ffa8aef1a0d5CKEIIf9HGN7wfe9ID95GKzC8xSwXLmityAijEySJX00=','3b20c4e71c65bbe747e157a5a356e46b7d54c2b4cf0c6775b06e76d28b03bc2620f2bea1933d1856fa8e51174e57ec62ebb05e3cb97cf6e28633e163fca2fbb3oANtXvfcpGnuf8V3ETS5ricLuZTr6/776NX4AuzZzQ8=','b6d16084aaf94ef7279fa2bfeb758fa6cd31ea922d1ebcedc61d5055a6dc7341988aa31d210ea34ff72102629a78b2b1fdd19960f1ab7a11d5bfb3b2bc2fc8f6JDrXu4J7+YHV1qC10+IYyNxd4JF/7dRW5OTVs4t7gC8=',31,'d75966eb609e434387fb042ff857383de9fe71907f2d728f8fa61da2f957c4637b4ef408db06d18e1a734d32301183609bf1bbae2f8583211e91ccdc9c408f176FZ9P2vq/jwYOzQ75vbFLjGDdqvMyTzBmDwPBVK5HiE='),(223,126,'245ac1c08fd5b6873f8e595ab19da32e39bfad56e7625f13521c046d91a0e81ec50dc5c1bc6c5cfa775f5b8bcddcf2dea994cea040250a37aa60cd2b3dc1c848UIV4PlWCyIJ1GE4r0pf4ijFxv9XsrPo8KUOh5XDZU3s=','9bd97b760e097578f9b626ec23fde6d68ff36397bd726369cc164b40b1d98fe6cb0f85dac2f971a10c40b237d2a8cf898cd8f21ed8fd9bcb25a15cf610a96f33BoyaPjHF5J/IpWOHfp7VUjuGwLYic1RS8yLT7p+5HRM=','e354e2908fff5c72a27247c74fb7f14ec1236ad748cb0ddff1970bc1d953f48ef5363303414b169e9c01af40a88b0d289973419ecd45d30266932d865b746445t4WPt2KV1+g/kJnMgZ2uWKo7ad5B+Fg4SK2Q7mO2/70=',31,'758912ee44cbeef9e567d0d1c45e8014c1b4938c6e7384695ed20f5e0e08444cd9de51ff6acd991a07c015b5cf20310f495674e1201d99406cd4fef691afeede/OfGiAcGOKCMkmjV56IvxFeK2JJuVLePbqN0/OH8MSI='),(224,133,'365d327d883cd3460b5d564c2b15c2113b90a37131db860233a7b30a4f11738aa417b4289daa479ac829f5c97a53a9b75349752e977567e976dc56870ea0b48fQQdCuveNcypEsCUIE88rlsXsECok86lATNApLED7JA4=','461b1c48d43e614d5ccd27b0cc395cfb53524ce92437c4c4a58846427b237a2ea8413694bd5f7645c7332f5f0bda0a918270c67dfbad4494d9ba581173b05e5cJACKINqLRcB7Rj7fisuZY+CTArX8P4JkAuym4ATxXjI=','3ccac7d6e400eef2f7adb5c158d2cbee1da149d69c0f1265efd93450d048c13d65f27ee57c6781bc339ba849e06d0c8890bf468bc22b1086cf4bc92208ab4c5cgiJfjDxhcj0gLmbdeYBeEj9zMlG2qYnA5q50JI+hZk8=',42,'fb2c46ed2809fdf3dc44eb4b48657c71a25faa2ecf1f0a6f6f7e95e6a5a209b11faa6b971b95f21381fb440f0f43c049340ab519d235e20cdbacffb14dc33974oDOwkXJgz2pVkv8/4ujjFUnxH2NXvsQklmeLagvCNVY='),(225,133,'50f82468b1dc30962046efdb17d7e1d35a776e71dcf11e87226cadb084f6cec8e6a7687cf67421eea1476d507d7e32fe2ee788592ad12a949687d73e521e5026bFWif09GjJMz1ax3vOdVnaOuAbpt/vPaAILBgnpKo4w=','29ec346ea933e47c1279284ebbb12d08fc49c239972716a20e5cf558d3354fe991cffe444f388549af0ace50324824a40f451b7e2890525edf16997859b0a62fOq37KbgsaQIOtWOOAtBo4ZNGWPWP87C7g5dUdnzPJiw=','1712270e79da376f02b50432790d69061b787d4fa80bf6cf2ba9febab4eaf582b770e723acb242fdd22e9ed4995b43615d5fe6120aaff48f3a1b5d43fd902bfeZmxq4F5ssCCHRMUBWCWdgouUJdZ8yrIrsqIaW00TA1s=',42,'bbe0ccfbe7c08c8017561f248c5e8da4800d6f65a769a6cdc4b05c4f48c1b1bf800ad1354e5c782cd9266ee745dbb46d080080a1ae922963b47f0d8fc776a5d68LVEmudVUMzGb3RcY++Lju+VEb8v1UEopRjAS+SyWf8='),(226,133,'723bfa30ecf8e647510c7b58748a8ff2c198864f55ee74a81a334beddb5978511618cf98e4a5c3f1a37e8136a0f5b68cb46e114b3845ff7a9d1e9a7f50f52b634AnGACzllzT1TiqN/bAsmfcm91da+yaaccYwqIl0SXA=','5f1dad0bc0d37a9df593664b9185d977cc06ab58b5978b70af33a05a084d53fd1ae6f08fb5d2d89453e4c72ee20ab218f7800a84441ed17d0765f8711cc2049f1MX1gY7qJkgfz60LHBSNXfEDpC7fb+rLPBL9FDoVq00=','92da8e52d47d2aab4b074933e2358ef5b69a9d483e607d761734a12bf4780019467d07e509d9ec79cc3e0f8efb580e8309c829e166fa965e0ec472fddf6c92d9A3FgsFBKoK1ow8rl2hekXDLNJrxbqAFHBYmXAKlkP0E=',42,'713da1bb7ddaa48e6274a76de12c7e74b2f3734d8e1333919e5ef89b51a8235471764419098bff4bfd93ea98bcfa52c20173af32a69e8ea72e0e2bd65b5338f61LnOMJ8Br+tDvSc1qraTenDosW9pV3eJv+cW1br809w='),(227,134,'f5521cfa5b1253279652ccf816c0b2d7325ecebba408dffde983e010da19e18c47547232b5877dddbe63b31f5f92ad09de0b78521056df2afd25a2bd90949b019pYXjOXq1n20CfAuKrp5eHvRHpT11pq5SH+4JRjbrhc=','80fd51d9e99d92624333da8ddd8eb7641f16af4b2c4f3b27b9ce592da70102a872fe3a8765c200708f7e8c9ff56f7248b415133d268352fdd7b438e15ff9583dC37IpmXcejdg73VIdPG3yUB6SS7yHEN1hQm3Oy/23jk=','f15b09d635c615d9043ae605ea76c099f636328985bf0ede183a549d118208d4ff4d4373eb3f81db23d5eb418c8cca8a0fe902c7eb5b12e244dc5979c47d1a00DxqoPlDvfbCGjycz6l5RSmpotpL3tSQg/ePPBA7De6E=',43,'a1a568975ef0fb1e5761836cfd1e30ccdbfa85bff8b17e3fd3402e1066a1170e953abe0709f5c15161655019ef5972452bc58476f5c55b394006d8d83b7a04b6aBY5cKv/okU73sjWgSa4jd2oXjbMfQ4/mlh+aqWS2kI='),(228,134,'4c62025a80e55d3337c06945684a2fe5695aae42f23cb9879d6349309bcdf34f4cf8f1ee02778edc6534a9a8019b378c3a2f919f6f453481e415ebf2c0d70ac3ozrNs2AKeETOapHMClyhlQGRbfRjJbU0Mpu7Z29LPh4=','20a279a86b8cbd1fada64de283cb693162bddfa838e46927fb45f5dde706104eb35f74217bf9c721b469ff16cfeacb8bd649c5a618fd46bcdc6134cd5b53e4ac6KDNbpzVDJPSwLv14uUOVbvqG46COvWYNPkZW8gY1bk=','dd1cb785b152937fba8f282a42fd94ba965f888e790e63edce1da39f498b28947c1607e476f6eb8e10df8be21ee9e97e4013a040024b92a8630647ab9b27db18XpKzjFF5WK3Jx+Hommgg5t9ba/+vXorxueTywBSiYUg=',43,'2b6423694b3a2620ade02ba497d068e83f66711cffdfe40555575a5c5da4a78fd556cbd6712ebebc1a46abb2276023158aeb698b95149cebe2c292060f8a271eL4LUGSVHgCyB8EkjdkjPGEksd/or/NR2xYGd9Ft9H18='),(229,135,'fae4a356b3f24efb7dece6d98edefabb2817b2d5ab98cfda913283a46c4653ac44c0c3ff41a620aff203661fffa436353522d226061b9afb7a8a4f2f574820cefxEaQ2X+6J4mQDsXwikLLCClhffE/F1c5jPBSLEXcFY=','9886de7a24962018ee0dbfc58c5f0d624475f27db154f6841618661a1c873261969c33497b5f789adfe345692164cd93ba276b860d2804459977b9ecdf9b2816+7soUKS/mATidJjhSfBb5wvaN8RLwqBLPFPatQUihz8=','f15a9a6c741c9cf466e62ca94400fcf88b620e2207b97b910d02af22b571a662f4d7104dbfef0cbc9f7433ccf12773394482bba83f9fcd144736f367e4b4dae18hMOYN7KGw1SRvFmYdjgWlD4dWHePuJNH2B4Wc9RUD0=',31,'e5999a9ef4285418bfe022aad6246705af121f459195a5d5dd4070d27be2b6ace5fd21fce37b4255ad175e7ed9bc944bb92cc09f13a2db1caa5e739d4f977a67KauCs79ww/eCahbXCaOfmcoz/fzQR959GVph+5BaXhU='),(230,134,'1b3cbd5c7fb93fb09f6db6708e26c975c01d65fb94968dc680d82b1e3284fc0e26673830f6c13328afb4d6ad0c4282b2d211384ea9b1c57ab242885373c7f037qngnVbgS8vSMDQH26OmNfQN6erGGBtA/u0J6ZohoxhI=','c926c14dceebc0fc2b6d22decc9ea4b360799b31f6993fd811ba9e8c143ef761c96e4702b0a0a792b9149a80dcde3e94ea142258a35566023e79261a1bcf2e0dvAT7cUUfi7dMlcxAuEqrtFQqt12gDmATB44g49p9K4E=','ebbd8b14b9e72b52bfb4e12a3dbc37174055a64a4043c60a2f71e37a46c4d2c9867288addbb7a5717ac7bbeb6e55be8c6d33ecb97bd9dd7fff0690a26f3042f9/YqnAKPthyTUC50/tXbAtphTrqwL/LGgDnMJzN/OY9o=',43,'83f3ad4531751b11931b906245475a796dc2fca25934c333816d81e8ad51627f96691f809f92f133be0ab5422ab1eb7e67f72568f943c81903420a45e28cd3e7sxwKQiT5ZUva/p0sc9KlMmVMEp2J1CXnQgao2CR+EnM='),(231,134,'e7bf126438c89627c674c64c6cf3f6577463c07ec2fe6d42a45f548852c4bab2d29f8a5a5aaf4735e5a4eaf30a9f5b1fc45666a5ec2c762fabe88ee33ef6c006pcSWR3iZ5x4cYXGmUzXegloFckD7wVGg2G1agR+xNvY=','5ae8ca36b14ef2b710ac320914b72a1b2ae17406066683ddac8fdd3dc369a95d71bc08da9a2904c1e9f61cc33c76a0503a4a2d2cee335765df482122ba197000WCkIh4hnzcA4db3nfyhE7DuPFnuBx2rRvHzJloS0xBY=','9cc862a523bafde090b6ed52e1e20f689e06d8390fcb5308cfe2039c8be18ceb49c36049ffcaba3293fbce1473f900502431fb4a14c947a7a88a9a2d782302197Yp6Go2z6lShoZUGojOsTgsK9WhkkYBLw4zfL4ajuxs=',43,'ead9459ba4beddad9d3300428134e6f303ffda40dd3d15c27ffa27b03ec6f45c0d38517ac12f0f6e2cf6e016ab9d0c929b3df5b41c679169c53b580b1d857cd16Uv/oFZ3MuQo1r32g0PJEvPGUwbLoLJjtTVQEv3xB20=');:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `eu` WRITE;:||:Separator:||:
 INSERT INTO `eu` VALUES(1,59,'mark',1,'ea82410c7a9991816b5eeeebe195e20a',1),(8,66,'aubie',2,'d41d8cd98f00b204e9800998ecf8427e',1),(9,67,'tuna',2,'2bf93a8a979420ff77b32fab0751cad2',1),(10,68,'one',1,'098f6bcd4621d373cade4e832627b4f6',1),(21,90,'test123',1,'e10adc3949ba59abbe56e057f20f883e',1),(22,91,'test123',1,123456,1),(23,92,'qwerty',1,123456,1),(24,93,'qwerty',1,'81dc9bdb52d04dc20036dbd8313ed055',1),(26,65,888,1,'098f6bcd4621d373cade4e832627b4f6',0),(28,1,'jonsnow',2,'5a665206e6374a1b3b95e05d1ae9ecd8',1),(29,63,'marco',3,'f5888d0bb58d611107e11f7cbc41c97a',1),(30,98,'kulot',1,'45d1e4e173173efabc43111920a21fd2',1),(31,99,'dan',1,'0f281d173f0fdfdccccd7e5b8edc21f1',1),(32,100,'dulcy',1,'e10adc3949ba59abbe56e057f20f883e',1),(33,101,'hazel',1,'16b9652df79d0e4784bdbf478c9f4fee',1),(34,102,'sysadmin',1,'21232f297a57a5a743894a0e4a801fc3',0),(36,104,'marie',1,'108f280224d356e3a2537b56152e0b13',1),(37,105,'mcmia',1,'b9499de432a9b63ae79da5fb3e95580f',1),(39,108,'sample',2,'4297f44b13955235245b2497399d7a93',1),(40,97,'sam123',1,'e10adc3949ba59abbe56e057f20f883e',1),(41,110,'leila',1,'754f9968bf5f5f68d7dea029889b7415',1),(42,115,'keith',3,'8dd9fa632ca161d0ca1929a4d99cbe77',1),(43,117,'pests',1,'dbb5eba8ef5cc7bde33928963b207f6e',1),(44,118,'NEWS',1,'508c75c8507a2ae5223dfd2faeb98122',1),(45,120,'makmak',3,'5c1f327ed0bd20a52a38cdb39e4d80fa',1),(46,121,'makmak',1,'5c1f327ed0bd20a52a38cdb39e4d80fa',1),(47,122,'rtyu',3,'e6724fc37c9e7c530980e81615029330',1),(48,123,'testuser',1,'e10adc3949ba59abbe56e057f20f883e',1),(49,125,'WT',3,'81dc9bdb52d04dc20036dbd8313ed055',0),(50,126,'khinmaster',1,'81dc9bdb52d04dc20036dbd8313ed055',0),(51,127,'CG',2,'81dc9bdb52d04dc20036dbd8313ed055',0),(52,128,'RC',3,'81dc9bdb52d04dc20036dbd8313ed055',0),(53,129,'KQ',3,'81dc9bdb52d04dc20036dbd8313ed055',0),(54,130,'RA',3,'81dc9bdb52d04dc20036dbd8313ed055',0),(55,131,'ME',3,'81dc9bdb52d04dc20036dbd8313ed055',0),(56,132,'AC',3,'81dc9bdb52d04dc20036dbd8313ed055',1),(57,133,'sample123',2,'e10adc3949ba59abbe56e057f20f883e',1),(58,135,'alexmartin.ballora',3,'b8269d9b34c6a9bf15763e01832e9185',0);:||:Separator:||:


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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `gl` WRITE;:||:Separator:||:


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
 INSERT INTO `invadjustment` VALUES(1,1,1700,1800,100.00,1699,0,24,'0000-00-00'),(2,2,0,2,0.00,0,1,26,'0000-00-00');:||:Separator:||:


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
  `vatPercent` decimal(18,2) DEFAULT '0.00',
  `vatAmount` decimal(18,2) DEFAULT '0.00',
  `ewtRate` decimal(18,2) DEFAULT '0.00',
  `ewtAmount` decimal(18,2) DEFAULT '0.00',
  `penaltyRate` decimal(18,2) DEFAULT '0.00',
  `penaltyAmount` decimal(18,2) DEFAULT '0.00',
  `idDriver` int(11) DEFAULT NULL,
  `plateNumber` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idInvoice`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `invoices` WRITE;:||:Separator:||:
 INSERT INTO `invoices` VALUES(1,4,9,1,2,null,null,'2020-06-12 18:10:11',null,2,3,null,139168.00,139168.00,139168.00,0.00,0.00,0.00,null,null,0,null,'2020-06-12',null,null,null,0,null,null,1,null,34,null,null,2,null,null,null,null,0,10,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(2,4,9,2,2,null,null,'2020-06-12 18:10:11',null,2,4,null,51328.50,51328.50,51328.50,0.00,0.00,0.00,null,null,0,null,'2020-06-12',null,null,null,0,null,null,1,null,34,null,null,2,null,null,null,null,0,10,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(3,4,9,3,2,null,null,'2020-06-12 18:10:11',null,2,2,null,554857.00,554857.00,554857.00,0.00,0.00,0.00,null,null,0,null,'2020-06-12',null,null,null,0,null,null,1,null,34,null,null,2,null,null,null,null,0,10,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(4,4,9,4,2,null,null,'2020-06-12 18:10:11',null,2,6,null,180000.00,180000.00,180000.00,0.00,0.00,0.00,null,null,0,null,'2020-06-12',null,null,null,0,null,null,1,null,34,null,null,2,null,null,null,null,0,10,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(5,4,9,5,2,null,null,'2020-06-12 18:10:11',null,2,6,null,7620000.00,7620000.00,7620000.00,0.00,0.00,0.00,null,null,0,null,'2020-06-12',null,null,null,0,null,null,1,null,34,null,null,2,null,null,null,null,0,10,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(6,4,11,1,25,null,null,'2020-06-12 18:14:29',null,2,3,1,39584.00,39584.00,39584.00,0.00,0.00,0.00,null,null,0,null,'2020-06-12',null,null,null,0,null,null,1,1,34,null,null,2,null,null,null,null,0,12,0,0.00,3958.40,0.00,0.00,0.00,0.00,null,null),(7,4,11,2,25,null,null,'2020-06-12 18:14:29',null,2,4,1,46648.80,46648.80,46648.80,0.00,0.00,0.00,null,null,0,null,'2020-06-12',null,null,null,0,null,null,1,2,34,null,null,2,null,null,null,null,0,12,0,0.00,4240.80,0.00,0.00,0.00,0.00,null,null),(8,4,11,3,25,null,null,'2020-06-12 18:14:29',null,2,2,1,370048.18,370048.18,370048.18,0.00,0.00,0.00,null,null,0,null,'2020-06-12',null,null,null,0,null,null,1,3,34,null,null,2,null,null,null,null,0,12,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(9,4,11,4,25,null,null,'2020-06-12 18:14:29',null,2,3,1,34584.00,34584.00,34584.00,0.00,0.00,0.00,null,null,0,null,'2020-06-12',null,null,null,0,null,null,1,1,34,null,null,2,null,null,null,null,0,12,0,0.00,3458.40,0.00,0.00,0.00,0.00,null,null),(10,4,10,1,29,null,null,'2020-06-12 18:20:33',null,2,3,null,1652.80,1652.80,1652.80,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,1,6,34,null,null,null,null,null,null,null,0,11,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(11,4,13,1,17,null,3,'2020-06-12 18:22:00',null,1,3,null,7500.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,0,null,34,'2020-06-12 18:22:00',null,2,null,null,null,null,0,14,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(12,4,13,2,17,null,3,'2020-06-12 18:22:00',null,1,5,null,15000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,0,null,34,'2020-06-12 18:22:00',null,2,null,null,null,null,0,14,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(13,4,13,3,17,null,3,'2020-06-12 18:22:00',null,1,1,null,325000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,0,null,34,'2020-06-12 18:22:00',null,2,null,null,null,null,0,14,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(14,4,13,4,17,null,3,'2020-06-12 18:22:00',null,1,4,null,70500.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,0,null,34,'2020-06-12 18:22:00',null,2,null,null,null,null,0,14,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(15,4,13,5,17,null,3,'2020-06-12 18:25:00',null,1,6,null,201000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,0,null,34,'2020-06-12 18:25:00',null,2,null,null,null,null,0,14,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(16,4,12,1,18,null,null,'2020-06-12 18:26:00',null,1,3,1,3000.00,3000.00,0.00,0.00,0.00,0.00,0,null,0,null,'2020-06-12',null,1,null,0,null,null,0,11,34,null,null,2,null,null,null,null,0,13,0,12.00,360.00,0.00,0.00,0.00,0.00,null,null),(17,4,12,2,18,null,null,'2020-06-12 18:26:00',null,1,5,1,7500.00,7500.00,7500.00,0.00,0.00,0.00,0,null,0,null,'2020-06-12',null,0,null,0,null,null,0,12,34,null,null,2,null,null,null,null,0,13,0,0.00,0.00,0.00,0.00,10.00,750.00,null,null),(18,4,12,3,18,null,null,'2020-06-12 18:26:00',null,1,3,1,2500.00,2500.00,2500.00,0.00,0.00,0.00,0,null,0,null,'2020-06-12',null,1,null,0,null,null,0,11,34,null,null,2,null,null,null,null,0,13,0,12.00,300.00,0.00,0.00,0.00,0.00,null,null),(19,4,12,4,18,null,null,'2020-06-12 18:26:00',null,1,4,1,70000.00,70000.00,70000.00,0.00,0.00,0.00,0,null,0,null,'2020-06-12',null,2,null,0,null,null,0,14,34,null,null,2,null,null,null,null,0,13,0,12.00,7500.00,0.00,0.00,0.00,0.00,null,null),(20,4,12,5,18,null,null,'2020-06-12 18:26:00',null,1,6,1,61500.00,61500.00,61500.00,0.00,0.00,0.00,0,null,0,null,'2020-06-12',null,0,null,0,null,null,0,15,34,null,null,2,null,null,null,null,0,13,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(21,4,14,1,21,null,null,'2020-06-12 18:32:00',null,1,4,null,2500.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,0,19,34,null,null,2,null,null,null,null,0,15,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(22,4,15,1,43,null,null,'2020-06-12 18:41:00',null,3,2,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,0,0,null,34,null,null,2,null,null,null,null,0,16,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(23,2,15,1,43,null,null,'2020-06-12 18:41:00',null,3,4,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,0,0,null,34,null,null,2,null,null,null,null,0,16,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(24,4,7,1,23,null,null,'2020-06-12 18:45:20',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-06-12 10:46:32',null,0,null,34,null,null,1,null,null,null,null,0,8,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(25,4,5,1,28,null,3,'2020-06-12 18:47:00','00:20:20',1,3,null,3000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,0,null,34,null,null,2,0,null,null,null,0,6,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(26,2,7,1,23,null,null,'2020-06-12 20:44:22',null,null,null,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-06-12 12:44:46',null,0,null,34,null,null,2,null,null,null,null,0,19,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(27,2,11,1,25,null,null,'2020-06-13 05:04:07',null,2,3,1,200.00,200.00,200.00,0.00,0.00,0.00,null,null,0,null,'2020-06-12',null,0,null,0,null,null,1,null,34,null,null,2,null,null,null,null,0,20,0,0.00,20.00,0.00,0.00,0.00,0.00,null,null),(28,4,8,1,22,null,3,'2020-06-17 17:37:00',null,null,null,null,1000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,0,null,34,null,null,2,null,null,null,null,0,9,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(29,4,17,1,58,null,null,'2020-06-17 17:39:00',null,1,2,1,2134.00,2134.00,2134.00,0.00,0.00,0.00,null,null,0,null,'2020-06-17',null,null,null,0,null,null,0,null,34,null,null,2,null,null,null,null,0,18,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(30,4,2,1,48,null,null,'2020-06-17 17:39:37',null,1,5,null,3243.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-06-17 09:40:13',null,0,null,34,null,null,1,0,null,null,null,0,3,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(31,4,4,1,62,null,null,'2020-06-17 17:40:00',null,1,1,null,24234.00,24234.00,24234.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-06-17 09:40:00',null,0,null,34,null,null,2,null,null,null,null,0,5,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(32,2,2,1,48,null,null,'2020-06-17 17:54:38',null,0,0,null,2343.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,'2020-06-17 09:55:43',null,0,null,34,null,null,1,0,null,null,null,0,22,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(33,2,9,10,2,null,null,'2020-06-18 18:01:51',null,2,5,null,2500.00,2500.00,2500.00,0.00,0.00,0.00,null,null,0,null,'2020-06-18',null,null,null,0,null,null,1,null,34,null,null,2,null,null,null,null,0,21,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(34,2,11,2,25,null,null,'2020-06-18 18:02:21',null,2,5,1,2250.00,2250.00,2250.00,0.00,250.00,10.00,null,null,0,null,'2020-06-18',null,0,null,0,null,null,1,33,34,null,null,2,null,null,null,null,0,20,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(35,2,19,101,2,null,null,'2020-06-01 18:56:02',null,2,4,null,380000.00,380000.00,380000.00,0.00,0.00,0.00,null,null,0,null,'2020-07-04',null,null,null,0,null,null,1,null,34,null,null,2,null,null,null,null,0,26,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(36,2,11,3,25,null,null,'2020-07-04 19:20:11',null,2,4,1,139500.00,139500.00,139500.00,0.00,0.00,0.00,null,null,0,null,'2020-07-04',null,1,null,0,null,null,1,35,34,null,null,2,null,null,null,null,0,20,0,10.00,13950.00,0.00,0.00,0.00,0.00,null,null),(37,2,8,1,22,null,3,'2020-08-27 19:11:00',null,null,null,null,1000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,1,null,34,null,null,2,null,null,null,null,0,0,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(38,2,11,4,25,null,null,'2020-07-04 19:20:11',null,2,4,1,355000.00,355000.00,355000.00,0.00,0.00,0.00,null,null,0,null,'2020-07-04',null,1,null,0,null,null,1,35,34,null,null,2,null,null,null,null,0,20,0,10.00,35500.00,0.00,0.00,0.00,0.00,null,null),(39,2,10,1,29,null,null,'2020-07-04 19:36:36',null,2,3,null,200.00,200.00,200.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,1,27,34,null,null,null,null,null,null,null,0,28,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(40,2,13,1,17,null,3,'2020-07-04 22:05:00',null,1,8,null,5280.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,0,null,34,'2020-07-04 22:05:00',null,2,null,null,null,null,0,29,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(41,2,12,1,18,null,null,'2020-07-04 22:40:00',null,1,8,1,25545.50,25545.50,25545.50,0.00,1344.50,5.00,0,null,0,null,'2020-07-04',null,1,null,0,null,null,0,null,34,null,null,2,null,null,null,null,0,30,0,12.00,3226.80,0.00,0.00,0.00,0.00,124,123456),(42,2,12,2,18,null,null,'2020-07-04 22:49:00',null,1,8,2,500.00,500.00,0.00,0.00,0.00,0.00,0,null,0,null,'2020-07-14',null,1,null,0,null,null,0,null,34,null,null,2,null,null,null,null,0,30,0,12.00,60.00,0.00,0.00,0.00,0.00,124,123),(43,2,5,1,28,null,3,'2020-07-05 00:36:00','00:20:20',1,8,null,0.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,0,null,34,null,null,2,0,null,null,null,0,31,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(44,2,5,2,28,null,3,'2020-08-27 19:00:00','00:20:20',1,8,null,200.00,0.00,0.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,0,null,34,null,null,2,0,null,null,null,0,31,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(45,2,2,2,48,null,null,'2020-07-16 19:55:03',null,2,8,null,1000.00,0.00,0.00,0.00,0.00,0.00,null,null,0,'this is a smple remarks',null,null,null,null,0,'2020-07-16 11:57:10',null,0,null,34,null,null,1,0,'This is a sample description',null,null,0,22,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(46,2,19,102,2,null,null,'2021-03-10 00:52:13',null,2,3,null,11000.00,11000.00,11000.00,0.00,0.00,0.00,null,null,0,null,'2021-03-09',null,null,null,0,null,null,1,null,34,null,null,2,null,null,null,null,0,26,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(47,2,11,5,25,null,null,'2021-03-10 00:51:14',null,2,3,1,2500.00,2500.00,2500.00,0.00,0.00,0.00,null,null,0,null,'2021-03-09',null,0,null,0,null,null,1,46,34,null,null,2,null,null,null,null,0,20,0,10.00,250.00,0.00,0.00,0.00,0.00,null,null),(48,2,9,11,2,null,null,'2021-08-11 11:26:38',null,2,3,null,2850.00,2850.00,2850.00,0.00,0.00,0.00,null,null,0,null,'2021-08-11',null,null,null,0,null,null,1,null,34,null,null,2,null,null,null,null,0,21,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(49,2,11,6,25,null,null,'2021-08-11 11:28:31',null,2,3,1,2065.00,2065.00,2065.00,500.00,285.00,10.00,null,null,0,null,'2021-08-11',null,1,null,0,null,null,1,48,34,null,null,2,null,null,null,null,0,20,0,12.00,342.00,0.00,0.00,0.00,0.00,null,null),(50,2,10,2,29,null,null,'2021-08-11 11:53:23',null,2,3,null,2300.00,2300.00,2300.00,0.00,0.00,0.00,null,null,0,null,null,null,null,null,0,null,null,1,49,34,null,null,null,null,null,null,null,0,28,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(51,2,19,103,2,null,null,'2021-08-18 09:37:19',null,2,4,null,2300.00,2300.00,2300.00,0.00,0.00,0.00,null,null,0,null,'2021-08-18',null,null,null,0,null,null,1,null,34,null,null,2,null,null,null,null,0,26,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null);:||:Separator:||:


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
  `vatPercent` decimal(18,2) DEFAULT '0.00',
  `vatAmount` decimal(18,2) DEFAULT '0.00',
  `ewtRate` decimal(18,2) DEFAULT '0.00',
  `ewtAmount` decimal(18,2) DEFAULT '0.00',
  `penaltyRate` decimal(18,2) DEFAULT '0.00',
  `penaltyAmount` decimal(18,2) DEFAULT '0.00',
  `idDriver` int(11) DEFAULT NULL,
  `plateNumber` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idInvoiceHistory`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `invoiceshistory` WRITE;:||:Separator:||:
 INSERT INTO `invoiceshistory` VALUES(1,11,4,13,17,null,3,'2020-06-12 18:22:00',1,3,null,7500.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,34,'2020-06-12 18:22:00',null,2,null,1,14,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(2,12,4,13,17,null,3,'2020-06-12 18:22:00',1,5,null,15000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,34,'2020-06-12 18:22:00',null,2,null,2,14,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(3,13,4,13,17,null,3,'2020-06-12 18:22:00',1,1,null,325000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,34,'2020-06-12 18:22:00',null,2,null,3,14,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(4,14,4,13,17,null,3,'2020-06-12 18:22:00',1,4,null,70500.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,34,'2020-06-12 18:22:00',null,2,null,4,14,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(5,15,4,13,17,null,3,'2020-06-12 18:25:00',1,6,null,201000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,34,'2020-06-12 18:25:00',null,2,null,5,14,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(6,16,4,12,18,null,null,'2020-06-12 18:26:00',1,3,1,3000.00,3000.00,3000.00,0.00,0.00,0.00,0,null,'2020-06-12',null,1,null,0,null,null,0,11,34,null,null,2,null,1,13,0,null,0,12.00,360.00,0.00,0.00,0.00,0.00,null,null),(7,17,4,12,18,null,null,'2020-06-12 18:26:00',1,5,1,7500.00,7500.00,7500.00,0.00,0.00,0.00,0,null,'2020-06-12',null,0,null,0,null,null,0,12,34,null,null,2,null,2,13,0,null,0,0.00,0.00,0.00,0.00,10.00,750.00,null,null),(8,18,4,12,18,null,null,'2020-06-12 18:26:00',1,3,1,2500.00,2500.00,2500.00,0.00,0.00,0.00,0,null,'2020-06-12',null,1,null,0,null,null,0,11,34,null,null,2,null,3,13,0,null,0,12.00,300.00,0.00,0.00,0.00,0.00,null,null),(9,19,4,12,18,null,null,'2020-06-12 18:26:00',1,4,1,70000.00,70000.00,70000.00,0.00,0.00,0.00,0,null,'2020-06-12',null,2,null,0,null,null,0,14,34,null,null,2,null,4,13,0,null,0,12.00,7500.00,0.00,0.00,0.00,0.00,null,null),(10,20,4,12,18,null,null,'2020-06-12 18:26:00',1,6,1,61500.00,61500.00,61500.00,0.00,0.00,0.00,0,null,'2020-06-12',null,0,null,0,null,null,0,15,34,null,null,2,null,5,13,0,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(11,21,4,14,21,null,null,'2020-06-12 18:32:00',1,4,null,2500.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,19,34,null,null,2,null,1,15,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(12,28,4,8,22,null,3,'2020-06-17 17:37:00',null,null,null,1000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,34,null,null,2,null,1,9,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(13,29,4,17,58,null,null,'2020-06-17 17:39:00',1,2,1,2134.00,2134.00,2134.00,0.00,0.00,0.00,0,null,'2020-06-17',null,null,null,0,null,null,0,null,34,null,null,2,null,1,18,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(14,30,4,2,48,null,null,'2020-06-17 17:39:37',1,5,null,3243.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,null,0,1,3,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(15,31,4,4,62,null,null,'2020-06-17 17:40:00',1,1,null,24234.00,24234.00,24234.00,0.00,0.00,0.00,0,null,null,null,null,null,0,'2020-06-17 09:40:00',null,0,null,34,null,null,2,null,1,5,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(16,32,2,2,48,null,null,'2020-06-17 17:54:38',0,0,null,2343.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,null,null,null,null,0,1,22,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(17,37,2,8,22,null,3,'2020-07-04 19:29:00',null,null,null,1000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,34,null,null,2,null,1,27,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(18,40,2,13,17,null,3,'2020-07-04 22:05:00',1,8,null,5280.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,34,'2020-07-04 22:05:00',null,2,null,1,29,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(19,41,2,12,18,null,null,'2020-07-04 22:40:00',1,8,1,25545.50,25545.50,25545.50,0.00,1344.50,5.00,0,null,'2020-07-04',null,1,null,0,null,null,0,null,34,null,null,2,null,1,30,0,null,0,12.00,3226.80,0.00,0.00,0.00,0.00,124,123456),(20,42,2,12,18,null,null,'2020-07-04 22:49:00',1,8,2,500.00,500.00,500.00,0.00,0.00,0.00,0,null,'2020-07-14',null,1,null,0,null,null,0,null,34,null,null,2,null,2,30,0,null,0,12.00,60.00,0.00,0.00,0.00,0.00,124,123),(21,45,2,2,48,null,null,'2020-07-16 19:55:03',2,8,null,1000.00,0.00,0.00,0.00,0.00,0.00,0,'this is a smple remarks',null,null,null,null,0,null,null,0,null,null,null,null,null,0,2,22,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(23,37,2,8,22,null,3,'2020-08-27 19:11:00',null,null,null,1000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,0,null,34,null,null,2,null,1,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null),(24,37,2,8,22,null,3,'2020-08-27 19:11:00',null,null,null,1000.00,0.00,0.00,0.00,0.00,0.00,0,null,null,null,null,null,0,null,null,1,null,34,null,null,2,null,1,0,null,null,0,0.00,0.00,0.00,0.00,0.00,0.00,null,null);:||:Separator:||:


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
 INSERT INTO `item` VALUES(1,1,'ff78608dad2507cd31a36ac340233fc89822291fde3e5aa6154786e35bb9af0cd356345b9966c0fac58b432f4ee2293965c725a80837a5c997852ae0f1e6c4f93EFr7qg/uf0R7rqIcvCjLaz5I5AAxI+OUzkCH2RlxPQ=',1,2,'7bce0bd9a1fcaa5ce78a35aa81e7dabdad9457be11863c0073a1cd2874d68ebe2641b73ec763502f0645706c11e291e2aa82cc42d2fe54619c8bfa69870e9e18lvinWnRX1xL5pYzE/lU79DXQQyUHTgAw0AUTujCzBYQ=',0,null,0,1101000,null,null,'2020-06-12',0,092005130013),(2,2,'0becfcbd7e872538009100e81028923928ad5d1c14bc0ebe34d50145361e8c42a1b9ded8fcfb4a2d89aa76497d1090cb4550cde088cc1854f5b061e65125593fbdjB46h7YrxD3nSA82BTFvWSCQ8Mkx91u6YhCqMmmuM=',1,1,'b00407a43fe8a42324121b18056eb87b1a1710ae628ff607570ec48159f94d738484b4a3756b2b7bd5912ffbc6c31bbe6d49f889b16e236202b942c3abbbbc0a83lXIkvGLX+2IXjgo70LjI5oWXId/nNCTFixNV5GyqM=',0,null,0,null,null,null,'2021-08-18',0,092005181379),(3,'newt','daa2e4989815f8566e218c82d921b2e37f3d59088bce653e1d43a68f6e27a4d2adfa763334e00f65abc0927caeb82e770906c162f6c93da68d4c28cc78b80bebmjPsIS3dyxLG05+5Ll0KlIpXuwsJUt0gM3+hL5DGL/8=',1,2,'b885890347c413963b5aae119439888674d344ba1a456df1b4718d976f8577a5bf66402347f9fb127b1e6a8f3921ee6a12997071e6bd1691a563bd186aa4c0c61zzG+mlkzRByQPG1UgE+g/6PR5SURBDI9BOyfu332ro=',100,null,0,null,null,null,'2020-06-12',0,140523000984),(4,'S1','82f97c56f18d4a74f6cebd98efc06db351d01e14879c6d248d3804ecf819a862719965c6e480e8ce0be180558af97f43319b324b5febbe27ea09ea5337dff401EcuZRHcN8luLiLK8i3ZudGGo8cpEewZZu2xSG5lnjHQ=',6,3,'5caad0c9a39cd0437bcf62d1cac3c9868bd7e16345cb4e3ef810a203ec2b8ceba02560d8463f4f3db4d96cf71caef625289a0d22e8808326e9781fe296b78381hAY0/4ChGC1O8SRwWA9ngJkMI7G3J+q6fcP7qEuegS4=',5,null,0,null,null,null,'2020-09-07',0,190113161224),(5,1023,'b43d6a86f00b34615d792995eab05e2ec4c29e6602112e26c943ac413a109d60e4f9e331293039f314e1075366e8453d08bb69b9077c5d6b252c24b1c04acf7f6q2A3Zuv+t21XlUF3nXqN3Yr1b+r0wF/66fWx/D4m1u91HktL+kZ+KiFFIzak1Ya',7,6,'86279977dc9ff5f49390d144894cb3d3ceac6580cceeb58d4f47927cf9fa05799e44239672434647258f1d4c7711d43ea8b5d1670a85e4fb360497aed4fef673/0qxCfspa1MQcC+/3fLt3rrqOPiizhRn4NdVY177I9Q=',1000,null,0,null,null,null,'2021-08-18',0,031201191985),(6,'SAMPITMCODE','a1b3ba40e432141d2dd66aeaf1e89cdda71407211770c0e6d479037fcc742a8b28d6e9b033277e85ad008df71b0ea618f8045f83b5fd7a8f6972200a9fc7100cD/HIGrHYd/GxmBbVTIt8UfhTvlU6XY9oqMDNwwtvQzk=',6,null,null,null,null,0,null,null,null,null,0,190113161269),(7,123,'353269dbdfce589122e7fb57af657bc0dc1fd6f8770874d6a7d5befcbf55fd4f134eda3677e27ddfa76cfd55010b14000bd9f4a20df4c4ec267e5d42cb0496f95c64X5rPyj6z4OHUrzu0yIjbavBsT6YyFutzwLE/rS0=',10,null,null,null,null,0,null,null,null,null,0,200519200169),(8,'qwerty','7fb300c5189429e0a5a82658c116a49a1c4e502f42aca48ee9d9942c0e429baae8cc57003c6f2126aa3c6c234d9984fde7515db7c6dab9f767565ee98c66e07aIZlgho+7ok0g1CjTbonqPsaHlGvGFkGtxwOv5hcUhXs=',11,null,null,null,null,0,null,null,null,null,0,200519200138),(9,'1stITEM','f1d1562a10426bdb2dc0b06d66f74a97a36f74d299f45c247c19031fcf72baa18efa4ff92b37c90333154bdbb5c910b091662af7259786b4b20fb0c0ac016f89NDbq2vpC90J2U7f6mmuBFiXaEViUWd9iueUp9aAAoG8=',15,7,'7a32e80c2d75c631bc958821ba8f6adfb27d359db8647e0c83bbf33e9a6a66f6e14f757a318e08eb238377f207a4e99e85f6531c3ce855c39d1ce432b6d7876bzcX0+URvq8W5oHoZTjzqvtD6zG1P/h36jydLEkJ+1Jc=',12,null,0,null,null,null,'2020-07-07',0,060918192091),(10,'2stITEM','88e8c8f93f0bfc25ea3554a5b04d7ded1be1457c37b7803f84be08c11530a4943678bc0847eb57154031f1d5eea0b4a13b241f81d542788aed3730e0b2fca51asDfgoc7HtXyp2dpGgQPvNIY/I2QN1O2RIqvTzAYmv0M=',15,7,'3d9df4d82dc8448b2ba455379067c2fe480adabbaa0c796a9de424d28267bc2b6de1eb93fe5dec0c24e6efc10f38839c760454958f6772ac795524d6c417e3afLzQPZ8za3aGzCHGccjOCaqRNj9QlKKx1fa6XlqKUG2k=',12,null,0,null,null,null,'2020-07-07',0,190503151457),(11,'3stITEM','f74a16f9807ad1da5596eee097f534ee16a5abe22eba77ba7b31768ed5100ad56ff86689fbfbd4b311dca233077d578384eaf2ab9effa5b9a71623973d8c218aY/STwNyVqZaemVRSFkkXbpvOYVI8I4f9RbbF3Cs7WJQ=',15,7,'8a605dc8e042b85bdba670f54c2699c368bf08a979bfd44dd7f754b2769678c1668090d8fffea0d6d10e735c773901d5131c6fe3a4865d82a611bfd56594f50d/EDbpufF28KdUpaNrEaLc3rlB+zsMT0MutB8kJ1Q7EE=',12,null,0,null,null,null,'2020-07-07',0,200809180476),(12,'4stITEM','7f35ccce874428dd25256bc443021a026c93069c83e7c0f82b3663fa2cf38c9ee58c64f725698b5eb39123936b8a9bef400e6fd2adac6fd28c240e35db3bd693qYIv/xX8zfY/RTNnG3gp1F3U8LdvCDdEoDC90ZS/vFk=',15,7,'137afa69a30f5150a0dcbd8e0690a1ab7017b8ff45816113ef645747de2c7f064ce5743356ffc044e99561aad65237bf46dd1df13bfab6df2c27318c5933ada939in/K6MA+a8WPSkbuhd4uRKziuO2Lrk2H8zxE6EQk4=',12,null,0,null,null,null,'2020-07-07',0,061521182061);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `itemaffiliate` WRITE;:||:Separator:||:
 INSERT INTO `itemaffiliate` VALUES(10,1,2,1),(11,1,4,1),(12,1,5,1),(13,3,2,1),(14,3,4,1),(15,3,5,1),(21,4,2,1),(22,4,6,1),(27,5,2,1),(28,5,4,1),(29,2,2,1),(30,2,4,1),(31,2,5,1);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `itemclassification` WRITE;:||:Separator:||:
 INSERT INTO `itemclassification` VALUES(1,1,'Tools',0),(2,2,'Beverage',0),(3,3,'BOLT',0),(4,4,'FILTER',0),(5,5,'FUSO FIGHTER',0),(6,6,'Sample Class',0),(7,7,'Classiefied',0),(8,8,'Class 1 Samp',0),(9,9,'Class 2 Samp',0),(10,10,'test',0),(11,11,'testtest',0),(12,12,'QWERTY',0),(13,13,'ASD',0),(14,14,'tyr',0),(15,15,'Numbered Item',0);:||:Separator:||:


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
 INSERT INTO `itempricehistory` VALUES(1,1,0,'2020-06-12'),(2,2,0,'2020-06-12'),(3,2,'0069011692e8b585e6db221a3f223d72bda95463a7a08b47a6a97134ecbd6ef1b6b2f666af0349e6cc11d10eb3194d4b3aa0ad21de943a3f4e8c3f12923bf3fcUbZq/CN+x/Prn/qbSVharCn4Ey4OpxCyOQdGADaTKs0=','2020-06-12'),(4,1,'7bce0bd9a1fcaa5ce78a35aa81e7dabdad9457be11863c0073a1cd2874d68ebe2641b73ec763502f0645706c11e291e2aa82cc42d2fe54619c8bfa69870e9e18lvinWnRX1xL5pYzE/lU79DXQQyUHTgAw0AUTujCzBYQ=','2020-06-12'),(5,3,'b885890347c413963b5aae119439888674d344ba1a456df1b4718d976f8577a5bf66402347f9fb127b1e6a8f3921ee6a12997071e6bd1691a563bd186aa4c0c61zzG+mlkzRByQPG1UgE+g/6PR5SURBDI9BOyfu332ro=','2020-06-12'),(6,2,'a1656fdc7c93886d6d7dc4b7fbfb102a4a7ce000a21f7ae5910f0e95b499b4f0f5a693af28434c0aefe7ce973fb2cab8266f8bf99f3b68d9a3cabbbf2c423e6bB372+12mVgWjDNwBwFIKWXm/g//RPWPDLhSPl/g7PEM=','2020-06-12'),(7,4,'a4381bb2d34e8c1ecdfeab62d4ff50ce55a16ba721542ba44517fcec4a6e665a884fe2c148372f305c1d1bbab4822e0333beb531c3e4b04d750373e831fdf4f4j6VhTxWYLSrUyTRxKDShl6x02euiUABOMn2lpA/Rqd4=','2020-07-04'),(8,4,'5caad0c9a39cd0437bcf62d1cac3c9868bd7e16345cb4e3ef810a203ec2b8ceba02560d8463f4f3db4d96cf71caef625289a0d22e8808326e9781fe296b78381hAY0/4ChGC1O8SRwWA9ngJkMI7G3J+q6fcP7qEuegS4=','2020-09-07'),(9,5,'f9891c49c1f15d71fa4a318f3b301d743a7aeb6561cb004c35cd70d60421ae90c10578cca8a8cf1bc98c9fc963bea3ded60a46c2bc1cd317be4ee700938067a9S8D2OmSFqDNVy/arNN9HsFN5P8R1n105amd4p69OSms=','2020-07-04'),(10,5,'e68775be5c051db1e8f192dce3c2c0b568f903915c14531e277561d0f68aa1235c6ca8d6bd8cf8fb963c2bdb97b9642f56e0114daf081b4b86b2e5950105e130aPPgQCCFZekavgh73GomxyanIhOpXH8Uduqxzz2xu8w=','2020-07-04'),(11,5,'86279977dc9ff5f49390d144894cb3d3ceac6580cceeb58d4f47927cf9fa05799e44239672434647258f1d4c7711d43ea8b5d1670a85e4fb360497aed4fef673/0qxCfspa1MQcC+/3fLt3rrqOPiizhRn4NdVY177I9Q=','2021-08-18'),(12,2,'b00407a43fe8a42324121b18056eb87b1a1710ae628ff607570ec48159f94d738484b4a3756b2b7bd5912ffbc6c31bbe6d49f889b16e236202b942c3abbbbc0a83lXIkvGLX+2IXjgo70LjI5oWXId/nNCTFixNV5GyqM=','2021-08-18');:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=703 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `logs` WRITE;:||:Separator:||:
 INSERT INTO `logs` VALUES(1,4,null,'2020-06-09','13:28:10',34,'sysadmin has changed affiliate.',null,null,null),(2,null,null,'2020-06-09','13:29:17',34,'sysadmin has logged out of the system.',null,null,null),(3,null,null,'2020-06-09','13:30:29',34,'sysadmin has logged out of the system.',null,null,null),(4,2,null,'2020-06-09','13:30:53',34,'Generates Receivable Schedule Report',null,null,null),(5,null,null,'2020-06-11','11:02:21',34,'sysadmin has logged out of the system.',null,null,null),(6,2,null,'2020-06-11','11:33:18',34,'Deleted the user account of, Mia Mcfarland with username: mcmia.',null,null,null),(7,2,null,'2020-06-11','11:34:12',34,'Deleted the user account of, Test Employee with username: testuser.',null,null,null),(8,2,null,'2020-06-11','11:34:35',34,'Deleted the user account of, Hazel with username: hazel.',null,null,null),(9,2,null,'2020-06-11','11:36:34',34,'Deleted the user account of, Quamar Keith with username: keith.',null,null,null),(10,2,null,'2020-06-11','11:37:09',34,'Deleted the user account of, adadf with username: rtyu.',null,null,null),(11,2,null,'2020-06-11','11:38:34',34,'Deleted the user account of, Mark Christian Lambino with username: makmak.',null,null,null),(12,2,null,'2020-06-11','11:38:39',34,'Deleted the user account of, Mark Christian Lambino with username: makmak.',null,null,null),(13,2,null,'2020-06-11','11:38:46',34,'Deleted the user account of, admin with username: pests.',null,null,null),(14,4,null,'2020-06-12','09:33:08',34,'Generates Receivable Schedule Report',null,null,null),(15,null,null,'2020-06-12','09:34:42',34,'sysadmin added a new customer Customer Charge',null,null,null),(16,null,null,'2020-06-12','09:35:24',34,'sysadmin added a new customer Customer With credit Limit',null,null,null),(17,null,null,'2020-06-12','09:36:05',34,'sysadmin added a new customer Customer VAT Inclusive',null,null,null),(18,null,null,'2020-06-12','09:36:41',34,'sysadmin added a new customer Customer VAT Exclusive',null,null,null),(19,null,null,'2020-06-12','09:37:07',34,'sysadmin added a new customer Customer Penalty',null,null,null),(20,null,null,'2020-06-12','09:37:43',34,'sysadmin added a new customer Customer Discount',null,null,null),(21,4,null,'2020-06-12','09:39:16',34,'Added a new supplier, Supplier charge',null,null,null),(22,4,null,'2020-06-12','09:41:08',34,'Added a new supplier, Supplier with credit limit',null,null,null),(23,4,null,'2020-06-12','09:42:51',34,'Added a new supplier, Supplier VAT Inclusive',null,null,null),(24,4,null,'2020-06-12','09:43:43',34,'Added a new supplier, Supplier VAT Exclusive',null,null,null),(25,4,null,'2020-06-12','09:45:13',34,'Added a new supplier, Supplier Discount',null,null,null),(26,4,null,'2020-06-12','09:45:55',34,'Added a new supplier, Supplier withholding tax',null,null,null),(27,null,null,'2020-06-12','09:46:25',34,'sysadmin added a new initial reference AAD',null,null,null),(28,null,null,'2020-06-12','09:46:42',34,'sysadmin added a new initial reference BR',null,null,null),(29,null,null,'2020-06-12','09:46:57',34,'sysadmin added a new initial reference BB',null,null,null),(30,null,null,'2020-06-12','09:47:17',34,'sysadmin added a new initial reference CR',null,null,null),(31,null,null,'2020-06-12','09:47:34',34,'sysadmin added a new initial reference DR',null,null,null),(32,null,null,'2020-06-12','09:47:52',34,'sysadmin added a new initial reference IAD',null,null,null),(33,null,null,'2020-06-12','09:54:30',34,'sysadmin added a new initial reference IC',null,null,null),(34,null,null,'2020-06-12','09:57:20',34,'sysadmin added a new initial reference PO',null,null,null),(35,null,null,'2020-06-12','09:57:33',34,'sysadmin added a new initial reference PR',null,null,null),(36,null,null,'2020-06-12','10:00:33',34,'sysadmin added a new initial reference RR',null,null,null),(37,null,null,'2020-06-12','10:01:04',34,'sysadmin added a new initial reference SA',null,null,null),(38,null,null,'2020-06-12','10:01:39',34,'sysadmin added a new initial reference SO',null,null,null),(39,null,null,'2020-06-12','10:01:51',34,'sysadmin added a new initial reference SR',null,null,null),(40,null,null,'2020-06-12','10:02:04',34,'sysadmin added a new initial reference ST',null,null,null),(41,null,null,'2020-06-12','10:02:23',34,'sysadmin added a new initial reference VP',null,null,null),(42,null,null,'2020-06-12','10:02:41',34,'sysadmin added a new initial reference VR',null,null,null),(43,null,null,'2020-06-12','10:02:59',34,'sysadmin added a new series reference AAD',null,null,null),(44,null,null,'2020-06-12','10:03:14',34,'sysadmin added a new series reference BR',null,null,null),(45,null,null,'2020-06-12','10:03:23',34,'sysadmin added a new series reference BB',null,null,null),(46,null,null,'2020-06-12','10:03:46',34,'sysadmin added a new series reference CR',null,null,null),(47,null,null,'2020-06-12','10:03:59',34,'sysadmin added a new series reference DR',null,null,null),(48,null,null,'2020-06-12','10:04:10',34,'sysadmin added a new series reference IAD',null,null,null),(49,null,null,'2020-06-12','10:04:21',34,'sysadmin added a new series reference IC',null,null,null),(50,null,null,'2020-06-12','10:04:33',34,'sysadmin added a new series reference PO',null,null,null),(51,null,null,'2020-06-12','10:04:44',34,'sysadmin added a new series reference PR',null,null,null),(52,null,null,'2020-06-12','10:05:24',34,'sysadmin added a new series reference RR',null,null,null),(53,null,null,'2020-06-12','10:05:35',34,'sysadmin added a new series reference SA',null,null,null),(54,null,null,'2020-06-12','10:05:46',34,'sysadmin added a new series reference SO',null,null,null),(55,null,null,'2020-06-12','10:05:57',34,'sysadmin added a new series reference SA',null,null,null),(56,null,null,'2020-06-12','10:06:09',34,'sysadmin added a new series reference SR',null,null,null),(57,null,null,'2020-06-12','10:06:26',34,'sysadmin added a new series reference ST',null,null,null),(58,null,null,'2020-06-12','10:07:22',34,'sysadmin added a new series reference VP',null,null,null),(59,null,null,'2020-06-12','10:07:35',34,'sysadmin added a new series reference VR',null,null,null),(60,null,null,'2020-06-12','10:07:44',34,'sysadmin edited the bank BPI',null,null,null),(61,null,null,'2020-06-12','10:08:17',34,'sysadmin added a new unit.',null,null,null),(62,null,null,'2020-06-12','10:08:27',34,'sysadmin added a new unit.',null,null,null),(63,null,null,'2020-06-12','10:08:33',34,'sysadmin added a new unit.',null,null,null),(64,null,null,'2020-06-12','10:08:42',34,'sysadmin added a new classification.',null,null,null),(65,null,null,'2020-06-12','10:08:48',34,'sysadmin added a new classification.',null,null,null),(66,4,null,'2020-06-12','10:09:21',34,'added a new Item, Item 1',null,null,null),(67,4,null,'2020-06-12','10:09:35',34,'added a new Item, Iterm 2',null,null,null),(68,4,null,'2020-06-12','10:09:58',34,'edited an item details, Iterm 2',null,null,null),(69,4,null,'2020-06-12','10:10:08',34,'edited an item details, Item 1',null,null,null),(70,4,null,'2020-06-12','10:11:21',34,'System Administrator added a new transaction.',9,1,2),(71,4,null,'2020-06-12','10:12:07',34,'System Administrator added a new transaction.',9,2,2),(72,4,null,'2020-06-12','10:13:02',34,'System Administrator added a new transaction.',9,3,2),(73,4,null,'2020-06-12','10:13:38',34,'System Administrator added a new transaction.',9,4,2),(74,4,null,'2020-06-12','10:14:17',34,'System Administrator added a new transaction.',9,5,2),(75,4,null,'2020-06-12','10:14:26',34,'Generates Purchase Order Monitoring',null,null,30),(76,4,null,'2020-06-12','10:15:06',34,'System Administrator added a new receiving transaction.',11,1,2),(77,4,null,'2020-06-12','10:15:15',34,'Generates Purchase Order Monitoring',null,null,30),(78,4,null,'2020-06-12','10:16:02',34,'System Administrator added a new receiving transaction.',11,2,2),(79,4,null,'2020-06-12','10:16:39',34,'System Administrator added a new receiving transaction.',11,3,2),(80,4,null,'2020-06-12','10:17:37',34,'Generates Purchase Order Monitoring',null,null,30),(81,4,null,'2020-06-12','10:18:05',34,'System Administrator added a new receiving transaction.',11,4,2),(82,4,null,'2020-06-12','10:18:14',34,'Generates Purchase Order Monitoring',null,null,30),(83,4,null,'2020-06-12','10:18:19',34,'Generates Purchase Order Monitoring',null,null,30),(84,4,null,'2020-06-12','10:18:19',34,'Generates Purchase Order Monitoring',null,null,30),(85,4,null,'2020-06-12','10:18:19',34,'Generates Purchase Order Monitoring',null,null,30),(86,4,null,'2020-06-12','10:18:19',34,'Generates Purchase Order Monitoring',null,null,30),(87,4,null,'2020-06-12','10:18:19',34,'Generates Purchase Order Monitoring',null,null,30),(88,4,null,'2020-06-12','10:18:19',34,'Generates Purchase Order Monitoring',null,null,30),(89,4,null,'2020-06-12','10:20:20',34,'Generates Receiving Summary Report',null,0,34),(90,null,null,'2020-06-12','10:20:34',34,'Generates Purchase return summary report',null,0,39),(91,4,null,'2020-06-12','10:20:52',34,'System Administrator added a new purchase return transaction.',10,1,29),(92,4,null,'2020-06-12','10:20:57',34,'Generates Purchase Order Monitoring',null,null,30),(93,null,null,'2020-06-12','10:21:20',34,'Generates Purchase return summary report',null,0,39),(94,null,null,'2020-06-12','10:21:21',34,'Generates Purchase return summary report',null,0,39),(95,null,null,'2020-06-12','10:21:22',34,'Generates Purchase return summary report',null,0,39),(96,null,null,'2020-06-12','10:21:23',34,'Generates Purchase return summary report',null,0,39),(97,null,null,'2020-06-12','10:21:23',34,'Generates Purchase return summary report',null,0,39),(98,null,null,'2020-06-12','10:21:23',34,'Generates Purchase return summary report',null,0,39),(99,null,null,'2020-06-12','10:21:23',34,'Generates Purchase return summary report',null,0,39),(100,null,null,'2020-06-12','10:21:23',34,'Generates Purchase return summary report',null,0,39),(101,null,null,'2020-06-12','10:21:23',34,'Generates Purchase return summary report',null,0,39),(102,null,null,'2020-06-12','10:21:23',34,'Generates Purchase return summary report',null,0,39),(103,null,null,'2020-06-12','10:21:24',34,'Generates Purchase return summary report',null,0,39),(104,null,null,'2020-06-12','10:21:24',34,'Generates Purchase return summary report',null,0,39),(105,null,null,'2020-06-12','10:21:24',34,'Generates Purchase return summary report',null,0,39),(106,null,null,'2020-06-12','10:21:24',34,'Generates Purchase return summary report',null,0,39),(107,null,null,'2020-06-12','10:21:24',34,'Generates Purchase return summary report',null,0,39),(108,null,null,'2020-06-12','10:21:26',34,'Generates Purchase return summary report',null,0,39),(109,null,null,'2020-06-12','10:21:50',34,'Generates Purchase return summary report',null,0,39),(110,4,null,'2020-06-12','10:22:12',34,'Generates Expiry Monitoring.',null,null,null),(111,4,null,'2020-06-12','10:23:03',34,'Sales Order : System Administrator added a new Sales Order transaction',13,1,17),(112,4,null,'2020-06-12','10:23:34',34,'Sales Order : System Administrator added a new Sales Order transaction',13,2,17),(113,4,null,'2020-06-12','10:24:09',34,'Sales Order : System Administrator added a new Sales Order transaction',13,3,17),(114,4,null,'2020-06-12','10:24:57',34,'Sales Order : System Administrator added a new Sales Order transaction',13,4,17),(115,4,null,'2020-06-12','10:26:27',34,'Sales Order : System Administrator added a new Sales Order transaction',13,5,17),(116,4,null,'2020-06-12','10:27:13',34,'Sales : System Administrator added a new Sales Order transaction ',12,1,18),(117,4,null,'2020-06-12','10:27:50',34,'Sales : System Administrator added a new Sales Order transaction ',12,2,18),(118,4,null,'2020-06-12','10:30:32',34,'Sales : System Administrator added a new Sales Order transaction ',12,3,18),(119,4,null,'2020-06-12','10:31:43',34,'Sales : System Administrator added a new Sales Order transaction ',12,4,18),(120,4,null,'2020-06-12','10:32:27',34,'Sales : System Administrator added a new Sales Order transaction ',12,5,18),(121,4,null,'2020-06-12','10:33:00',34,'Sales Return : System Administrator added a new Sales Return transaction',14,1,21),(122,null,null,'2020-06-12','10:33:05',34,'Generates Releasing Summary Report.',null,null,null),(123,4,null,'2020-06-12','10:33:24',34,'System Administrator Generates Sales Summary Report',null,null,null),(124,4,null,'2020-06-12','10:33:56',34,'Generates Receivable Transactions Report.',null,null,null),(125,4,null,'2020-06-12','10:34:02',34,'Generates Receivable Balance.',null,null,null),(126,4,null,'2020-06-12','10:34:07',34,'Generates Receivable Balance.',null,null,null),(127,4,null,'2020-06-12','10:34:08',34,'Generates Receivable Balance.',null,null,null),(128,4,null,'2020-06-12','10:34:08',34,'Generates Receivable Balance.',null,null,null),(129,4,null,'2020-06-12','10:34:08',34,'Generates Receivable Balance.',null,null,null),(130,4,null,'2020-06-12','10:34:08',34,'Generates Receivable Balance.',null,null,null),(131,4,null,'2020-06-12','10:34:08',34,'Generates Receivable Balance.',null,null,null),(132,4,null,'2020-06-12','10:34:16',34,'Generates Receivable Ledger.',null,null,null),(133,4,null,'2020-06-12','10:34:23',34,'Generates Receivable Ledger.',null,null,null),(134,4,null,'2020-06-12','10:34:30',34,'Generates Itemized Profit and Loss Report.',null,null,null),(135,4,null,'2020-06-12','10:34:42',34,'Cancelled Transactions : System Administrator generates Cancelled Transactions Report',null,null,null),(136,4,null,'2020-06-12','10:35:14',34,'Generates Inventory Balances.',null,null,null),(137,4,null,'2020-06-12','10:35:26',34,'Generates Inventory Ledger Report.',null,null,null),(138,4,null,'2020-06-12','10:35:38',34,'Generates Inventory Ledger Report.',null,null,null),(139,4,null,'2020-06-12','10:38:50',34,'Generates Conversions Summary Report.',null,null,null),(140,4,null,'2020-06-12','10:38:52',34,'Generates Conversions Summary Report.',null,null,null),(141,null,null,'2020-06-12','10:42:17',34,'sysadmin transferred stocks to Syntactics, Inc.',null,null,null),(142,null,null,'2020-06-12','10:44:43',34,'Chart of Accounts : System Administrator modified account code : 1101001.',null,null,null),(143,null,null,'2020-06-12','10:44:52',34,'Chart of Accounts : System Administrator modified account code : 1102000.',null,null,null),(144,null,null,'2020-06-12','10:45:08',34,'Chart of Accounts : System Administrator modified account code : 1103000.',null,null,null),(145,null,null,'2020-06-12','10:45:16',34,'Chart of Accounts : System Administrator modified account code : 2102000.',null,null,null),(146,4,null,'2020-06-12','10:46:32',34,'System Administrator added a new adjustment transaction.',7,1,23),(147,4,null,'2020-06-12','10:46:39',34,'Generates Adjustment Summary Report.',null,null,null),(148,4,null,'2020-06-12','10:46:40',34,'Generates Adjustment Summary Report.',null,null,null),(149,4,null,'2020-06-12','10:46:41',34,'Generates Adjustment Summary Report.',null,null,null),(150,4,null,'2020-06-12','10:46:42',34,'Generates Adjustment Summary Report.',null,null,null),(151,4,null,'2020-06-12','10:46:42',34,'Generates Adjustment Summary Report.',null,null,null),(152,4,null,'2020-06-12','10:46:42',34,'Generates Adjustment Summary Report.',null,null,null),(153,4,null,'2020-06-12','10:46:42',34,'Generates Adjustment Summary Report.',null,null,null),(154,4,null,'2020-06-12','10:46:42',34,'Generates Adjustment Summary Report.',null,null,null),(155,4,null,'2020-06-12','10:47:26',34,'Bank Account Settings : System Administrator added a new bank account for Cash in Bank',null,null,null),(156,2,null,'2020-06-12','10:50:46',34,'Generates Adjustment Summary Report.',null,null,null),(157,2,null,'2020-06-12','10:50:55',34,'Generates Adjustment Summary Report.',null,null,null),(158,4,null,'2020-06-12','10:51:41',34,'Generates Collection Summary Report.',null,null,null),(159,4,null,'2020-06-12','10:51:47',34,'Generates payable schedule report',null,null,null),(160,4,null,'2020-06-12','10:51:49',34,'Generates Receivable Schedule Report',null,null,null),(161,4,null,'2020-06-12','10:51:52',34,'Generates Aging of Receivables.',null,null,null),(162,4,null,'2020-06-12','10:51:54',34,'Generates Aging of Payables.',null,null,null),(163,4,null,'2020-06-12','10:52:33',34,'sysadmin added a new cash Receipt transaction',null,null,null),(164,4,null,'2020-06-12','10:52:39',34,'Cheque Monitoring : System Administrator Generates Cheque Monitoring Report',null,null,null),(165,4,null,'2020-06-12','10:52:41',34,'Cheque Monitoring : System Administrator Generates Cheque Monitoring Report',null,null,null),(166,4,null,'2020-06-12','10:52:41',34,'Cheque Monitoring : System Administrator Generates Cheque Monitoring Report',null,null,null),(167,4,null,'2020-06-12','10:52:42',34,'Cheque Monitoring : System Administrator Generates Cheque Monitoring Report',null,null,null),(168,4,null,'2020-06-12','10:52:42',34,'Cheque Monitoring : System Administrator Generates Cheque Monitoring Report',null,null,null),(169,4,null,'2020-06-12','10:52:43',34,'Cheque Monitoring : System Administrator Generates Cheque Monitoring Report',null,null,null),(170,4,null,'2020-06-12','10:52:43',34,'Cheque Monitoring : System Administrator Generates Cheque Monitoring Report',null,null,null),(171,4,null,'2020-06-12','10:52:43',34,'Cheque Monitoring : System Administrator Generates Cheque Monitoring Report',null,null,null),(172,4,null,'2020-06-12','10:52:43',34,'Cheque Monitoring : System Administrator Generates Cheque Monitoring Report',null,null,null),(173,4,null,'2020-06-12','10:52:43',34,'Cheque Monitoring : System Administrator Generates Cheque Monitoring Report',null,null,null),(174,4,null,'2020-06-12','10:52:43',34,'Cheque Monitoring : System Administrator Generates Cheque Monitoring Report',null,null,null),(175,4,null,'2020-06-12','10:52:44',34,'Cheque Monitoring : System Administrator Generates Cheque Monitoring Report',null,null,null),(176,4,null,'2020-06-12','10:52:44',34,'Cheque Monitoring : System Administrator Generates Cheque Monitoring Report',null,null,null),(177,4,null,'2020-06-12','10:52:44',34,'Cheque Monitoring : System Administrator Generates Cheque Monitoring Report',null,null,null),(178,4,null,'2020-06-12','10:52:44',34,'Cheque Monitoring : System Administrator Generates Cheque Monitoring Report',null,null,null),(179,4,null,'2020-06-12','10:52:44',34,'Cheque Monitoring : System Administrator Generates Cheque Monitoring Report',null,null,null),(180,4,null,'2020-06-12','10:53:05',34,'sysadmin edited a transaction',null,null,null),(181,4,null,'2020-06-12','10:53:11',34,'Cheque Monitoring : System Administrator Generates Cheque Monitoring Report',null,null,null),(182,4,null,'2020-06-12','10:53:30',34,'Generates cheque summary report.',null,null,null),(183,null,null,'2020-06-12','10:54:30',34,'sysadmin added a new initial reference InvAd',null,null,null),(184,null,null,'2020-06-12','10:55:08',34,'sysadmin edited the details of initial reference InvAd',null,null,null),(185,null,null,'2020-06-12','10:55:16',34,'sysadmin added a new series reference IAD',null,null,null),(186,2,null,'2020-06-12','11:00:55',34,'Generates Inventory Balances.',null,null,null),(187,2,null,'2020-06-12','11:07:13',34,'added a new Item, New Item',null,null,null),(188,null,null,'2020-06-12','11:07:42',34,'sysadmin added a new series reference RR',null,null,null),(189,2,null,'2020-06-12','11:11:37',34,'sysadmin has changed affiliate.',null,null,null),(190,2,null,'2020-06-12','11:36:14',34,'Generates Inventory Balances.',null,null,null),(191,2,null,'2020-06-12','12:25:25',34,'Generates Inventory Balances.',null,null,null),(192,2,null,'2020-06-12','12:25:33',34,'Generates Inventory Balances.',null,null,null),(193,2,null,'2020-06-12','12:44:46',34,'System Administrator added a new adjustment transaction.',7,1,23),(194,2,null,'2020-06-12','12:47:53',34,'Modified the affiliate, Syntactics, Inc.',null,null,null),(195,null,null,'2020-06-12','12:47:56',34,'sysadmin has logged out of the system.',null,null,null),(196,2,null,'2020-06-12','12:48:14',34,' confirmed an adjustment transaction.',null,null,null),(197,2,null,'2020-06-12','12:48:25',34,'Generates Adjustment Summary Report.',null,null,null),(198,2,null,'2020-06-12','21:02:08',34,'Generates Inventory Balances.',null,null,null),(199,4,null,'2020-06-12','21:02:27',34,'sysadmin has changed affiliate.',null,null,null),(200,4,null,'2020-06-12','21:03:14',34,'Generates Inventory Balances.',null,null,null),(201,4,null,'2020-06-12','21:03:35',34,'Generates Adjustment Summary Report.',null,null,null),(202,2,null,'2020-06-12','21:03:44',34,'sysadmin has changed affiliate.',null,null,null),(203,2,null,'2020-06-12','21:04:22',34,'System Administrator added a new receiving transaction.',11,1,2),(204,2,null,'2020-06-13','10:04:05',34,'edited an item details, Iterm 2',null,null,null),(205,2,null,'2020-06-13','10:09:08',34,'Generates Inventory Balances.',null,null,null),(206,null,null,'2020-06-13','10:12:25',34,'sysadmin added a new series reference PO',null,null,null),(207,2,null,'2020-06-13','10:25:34',34,'Generates Purchase Order Monitoring',null,null,30),(208,2,null,'2020-06-13','10:27:17',34,'Generates Purchase Order Monitoring',null,null,30),(209,2,null,'2020-06-13','10:27:17',34,'Generates Purchase Order Monitoring',null,null,30),(210,2,null,'2020-06-13','10:27:17',34,'Generates Purchase Order Monitoring',null,null,30),(211,2,null,'2020-06-13','10:27:17',34,'Generates Purchase Order Monitoring',null,null,30),(212,4,null,'2020-06-13','10:27:17',34,'Generates Purchase Order Monitoring',null,null,30),(213,4,null,'2020-06-13','10:27:17',34,'Generates Purchase Order Monitoring',null,null,30),(214,2,null,'2020-06-13','10:43:17',34,'Generates Receiving Summary Report',null,0,34),(215,null,null,'2020-06-13','10:43:57',34,'Generates Purchase return summary report',null,0,39),(216,2,null,'2020-06-13','10:44:39',34,'Generates Expiry Monitoring.',null,null,null),(217,2,null,'2020-06-13','10:44:58',34,'Generates Expiry Monitoring.',null,null,null),(218,2,null,'2020-06-13','10:45:22',34,'Generates Expiry Monitoring.',null,null,null),(219,null,null,'2020-06-13','10:57:41',34,'Generates Releasing Summary Report.',null,null,null),(220,2,null,'2020-06-13','10:58:46',34,' Generates Sales Summary Report',null,null,null),(221,2,null,'2020-06-13','11:01:54',34,'Generates Receivable Transactions Report.',null,null,null),(222,2,null,'2020-06-13','11:02:30',34,'Generates Receivable Balance.',null,null,null),(223,2,null,'2020-06-13','11:02:50',34,'Exported the generated Receivable SOA(PDF).',null,null,null),(224,2,null,'2020-06-13','11:03:27',34,'Generates Itemized Profit and Loss Report.',null,null,null),(225,2,null,'2020-06-13','11:04:11',34,'Cancelled Transactions :  generates Cancelled Transactions Report',null,null,null),(226,2,null,'2020-06-13','11:04:13',34,'Cancelled Transactions :  generates Cancelled Transactions Report',null,null,null),(227,2,null,'2020-06-13','11:04:21',34,'Cancelled Transactions :  generates Cancelled Transactions Report',null,null,null),(228,2,null,'2020-06-13','11:11:24',34,'Generates Inventory Balances.',null,null,null),(229,2,null,'2020-06-13','11:12:09',34,'Generates Inventory Ledger Report.',null,null,null),(230,2,null,'2020-06-13','11:12:51',34,'Generates Conversions Summary Report.',null,null,null),(231,2,null,'2020-06-13','11:12:52',34,'Generates Conversions Summary Report.',null,null,null),(232,2,null,'2020-06-13','11:12:55',34,'Generates Adjustment Summary Report.',null,null,null),(233,4,null,'2020-06-13','11:22:32',34,'sysadmin has changed affiliate.',null,null,null),(234,4,null,'2020-06-13','11:33:26',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(235,4,null,'2020-06-13','11:33:27',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(236,4,null,'2020-06-13','11:35:46',34,'Generates Collection Summary Report.',null,null,null),(237,4,null,'2020-06-13','11:36:19',34,'Generates payable schedule report',null,null,null),(238,4,null,'2020-06-13','11:36:41',34,'Generates Receivable Schedule Report',null,null,null),(239,4,null,'2020-06-13','11:36:54',34,'Generates Aging of Receivables.',null,null,null),(240,4,null,'2020-06-13','11:38:00',34,'Cheque Monitoring :  Generates Cheque Monitoring Report',null,null,null),(241,4,null,'2020-06-13','11:39:50',34,'Generates cheque summary report.',null,null,null),(242,null,null,'2020-06-16','15:47:24',34,'Added new classification, Driver',null,null,null),(243,2,null,'2020-06-16','15:48:35',34,'Added new employee, Makmak.',null,null,null),(244,4,null,'2020-06-17','09:36:56',34,'sysadmin has changed affiliate.',null,null,null),(245,4,null,'2020-06-17','09:39:20',34,'Vouchers Receivable:  added a new Vouchers Receivable Transaction.',17,1,58),(246,4,null,'2020-06-17','09:40:13',34,' added a new adjustment Transaction.',2,1,48),(247,4,null,'2020-06-17','09:40:43',34,'Beginning Balance :  added a new Beginnning Balance Transaction.',4,1,62),(248,2,null,'2020-06-17','09:54:15',34,'sysadmin has changed affiliate.',null,null,null),(249,null,null,'2020-06-17','09:55:11',34,'sysadmin added a new series reference AAD',null,null,null),(250,2,null,'2020-06-17','09:55:43',34,' added a new adjustment Transaction.',2,1,48),(251,2,null,'2020-06-18','10:01:09',34,'Modified the affiliate, Syntactics, Inc.',null,null,null),(252,2,null,'2020-06-18','10:02:19',34,' added a new transaction.',9,10,2),(253,2,null,'2020-06-18','10:19:56',34,' added a new receiving transaction.',11,2,2),(254,2,null,'2020-06-18','10:23:21',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(255,2,null,'2020-06-18','13:02:21',34,'Modified the affiliate, Syntactics, Inc.',null,null,null),(256,2,null,'2020-06-18','13:03:28',34,'Modified the affiliate, Syntactics, Inc.',null,null,null),(257,2,null,'2020-06-18','13:04:07',34,'Generates Inventory Balances.',null,null,null),(258,2,null,'2020-06-18','13:13:58',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(259,2,null,'2020-06-18','13:14:00',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(260,2,null,'2020-06-18','13:14:00',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(261,2,null,'2020-06-18','13:14:00',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(262,2,null,'2020-06-18','13:14:01',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(263,2,null,'2020-06-18','13:14:01',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(264,2,null,'2020-06-18','13:14:01',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(265,2,null,'2020-06-18','13:14:01',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(266,2,null,'2020-06-18','13:14:01',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(267,2,null,'2020-06-18','13:14:01',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(268,2,null,'2020-06-18','13:14:02',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(269,2,null,'2020-06-18','13:14:02',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(270,2,null,'2020-06-18','13:14:03',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(271,2,null,'2020-06-18','13:14:07',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(272,2,null,'2020-06-18','13:14:10',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(273,2,null,'2020-06-18','13:14:10',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(274,2,null,'2020-06-18','13:14:10',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(275,2,null,'2020-06-18','13:14:11',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(276,2,null,'2020-06-18','13:14:11',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(277,2,null,'2020-06-18','13:14:11',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(278,2,null,'2020-06-18','13:14:11',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(279,2,null,'2020-06-18','13:16:35',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(280,2,null,'2020-06-18','13:16:36',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(281,2,null,'2020-06-18','13:16:36',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(282,2,null,'2020-06-18','13:16:36',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(283,2,null,'2020-06-18','13:16:37',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(284,2,null,'2020-06-18','13:16:37',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(285,2,null,'2020-06-18','13:16:37',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(286,4,null,'2020-06-18','13:21:01',34,'sysadmin has changed affiliate.',null,null,null),(287,2,null,'2020-06-18','13:38:44',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(288,2,null,'2020-06-18','13:50:44',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(289,2,null,'2020-06-18','13:52:41',34,'Modified the affiliate, Syntactics, Inc.',null,null,null),(290,null,null,'2020-06-18','16:18:07',34,'Generates Purchase return summary report',null,0,39),(291,2,null,'2020-06-18','16:20:48',34,'Edited the supplier details of, Supplier charge',null,null,null),(292,2,null,'2020-06-18','16:39:51',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(293,2,null,'2020-06-18','16:40:10',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(294,2,null,'2020-06-18','16:40:11',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(295,2,null,'2020-06-18','16:40:11',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(296,2,null,'2020-06-18','16:40:11',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(297,2,null,'2020-06-18','16:40:11',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(298,2,null,'2020-06-18','16:40:11',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(299,2,null,'2020-06-18','16:40:12',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(300,2,null,'2020-06-18','16:40:12',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(301,2,null,'2020-06-18','16:40:12',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(302,2,null,'2020-06-18','16:40:12',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(303,2,null,'2020-06-18','16:53:14',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(304,2,null,'2020-06-18','16:53:34',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(305,2,null,'2020-06-18','16:59:34',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(306,2,null,'2020-06-18','17:00:42',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(307,2,null,'2020-06-18','17:04:27',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(308,2,null,'2020-06-18','17:04:29',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(309,2,null,'2020-06-18','17:04:30',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(310,2,null,'2020-06-18','17:04:30',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(311,2,null,'2020-06-18','17:04:30',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(312,2,null,'2020-06-18','17:04:30',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(313,2,null,'2020-06-18','17:04:31',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(314,2,null,'2020-06-18','17:04:31',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(315,2,null,'2020-06-18','17:04:31',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(316,2,null,'2020-06-18','17:04:31',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(317,2,null,'2020-06-18','17:04:31',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(318,null,null,'2020-06-19','17:12:41',34,'sysadmin has logged out of the system.',null,null,null),(319,null,null,'2020-06-29','10:04:10',34,'sysadmin has logged out of the system.',null,null,null),(320,null,null,'2020-06-29','10:04:31',34,'sysadmin has logged out of the system.',null,null,null),(321,4,null,'2020-07-01','15:43:00',34,'sysadmin has changed affiliate.',null,null,null),(322,null,null,'2020-07-03','15:19:33',34,'sysadmin has logged out of the system.',null,null,null),(323,null,null,'2020-07-03','15:20:45',34,'sysadmin has logged out of the system.',null,null,null),(324,4,null,'2020-07-03','15:47:33',34,'sysadmin has changed affiliate.',null,null,null),(325,2,null,'2020-07-03','15:47:46',34,'sysadmin has changed affiliate.',null,null,null),(326,2,null,'2020-07-03','15:48:34',34,'Modified the employee, sysadmin, for System Administrator with usertype Supervisor',null,null,null),(327,2,null,'2020-07-03','15:48:44',34,'Modified the employee, sysadmin, for System Administrator with usertype Supervisor',null,null,null),(328,5,null,'2020-07-03','15:48:47',34,'sysadmin has changed affiliate.',null,null,null),(329,5,null,'2020-07-03','15:51:35',34,'Deleted the affiliate, 4231e3b790f2427dd3c7cf24767d58747c24ab2815d8f1a4c97aa793d32eb631ea992e39893dfda3dcd18bf8b36e16c763f9e3ed127d4653697e7dfa703290b6sYyJFi1VFudqInr96k/5ruxSUkx1bWkzznSYDpke7f6zoNF3mS6oKhKgFEApEZlQ',null,null,null),(330,5,null,'2020-07-03','15:51:57',34,'Modified the employee, sysadmin, for System Administrator with usertype Supervisor',null,null,null),(331,6,null,'2020-07-03','15:52:02',34,'sysadmin has changed affiliate.',null,null,null),(332,2,null,'2020-07-03','15:57:11',34,'Modified the affiliate, CDO Affiliate',null,null,null),(333,2,null,'2020-07-03','15:58:11',34,'Modified the affiliate, Syntactics, Inc.',null,null,null),(334,2,null,'2020-07-03','15:59:06',34,'Modified the affiliate, CDO Affiliate',null,null,null),(335,2,null,'2020-07-03','16:22:09',34,'Modified the affiliate, Sample Affiliate',null,null,null),(336,2,null,'2020-07-04','09:52:38',34,'Generates Receivable Balance.',null,null,null),(337,2,null,'2020-07-04','09:54:25',34,'Generates cheque summary report.',null,null,null),(338,null,null,'2020-07-04','09:58:01',34,'sysadmin added a new customer KCDC',null,null,null),(339,null,null,'2020-07-04','10:00:21',34,'sysadmin added a new unit.',null,null,null),(340,null,null,'2020-07-04','10:17:42',34,'sysadmin added a new classification.',null,null,null),(341,null,null,'2020-07-04','10:17:51',34,'sysadmin added a new classification.',null,null,null),(342,null,null,'2020-07-04','10:18:07',34,'sysadmin added a new classification.',null,null,null),(343,null,null,'2020-07-04','10:25:14',34,'sysadmin added a new unit.',null,null,null),(344,null,null,'2020-07-04','10:26:37',34,'sysadmin edited unit details.',null,null,null),(345,null,null,'2020-07-04','10:26:45',34,'sysadmin deleted the unit.',null,null,null),(346,null,null,'2020-07-04','10:26:57',34,'sysadmin deleted the unit.',null,null,null),(347,null,null,'2020-07-04','10:29:29',34,'sysadmin added a new classification.',null,null,null),(348,2,null,'2020-07-04','10:38:41',34,'added a new Item, Sample Item',null,null,null),(349,2,null,'2020-07-04','10:40:08',34,'edited an item details, Sample Item',null,null,null),(350,null,null,'2020-07-04','10:48:53',34,'sysadmin added a new customer Sample Customer',null,null,null),(351,2,null,'2020-07-04','10:53:05',34,'Added a new supplier, Sample Supplier',null,null,null),(352,null,null,'2020-07-04','10:56:16',34,'sysadmin added a new initial reference PO1',null,null,null),(353,null,null,'2020-07-04','10:59:40',34,'sysadmin added a new series reference PO1',null,null,null),(354,2,null,'2020-07-04','11:08:08',34,' added a new transaction.',19,101,2),(355,2,null,'2020-07-04','11:09:45',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(356,2,null,'2020-07-04','11:09:53',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(357,2,null,'2020-07-04','11:09:55',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(358,2,null,'2020-07-04','11:09:55',34,'Modified the module access for the user account, sysadmin of System Administrator, with usertype Administrator.',null,null,null),(359,2,null,'2020-07-04','11:12:16',34,'Generates Purchase Order Monitoring',null,null,30),(360,2,null,'2020-07-04','11:13:41',34,'Generates Purchase Order Monitoring',null,null,30),(361,2,null,'2020-07-04','11:13:41',34,'Generates Purchase Order Monitoring',null,null,30),(362,2,null,'2020-07-04','11:13:41',34,'Generates Purchase Order Monitoring',null,null,30),(363,2,null,'2020-07-04','11:13:41',34,'Generates Purchase Order Monitoring',null,null,30),(364,2,null,'2020-07-04','11:13:41',34,'Generates Purchase Order Monitoring',null,null,30),(365,2,null,'2020-07-04','11:13:41',34,'Generates Purchase Order Monitoring',null,null,30),(366,2,null,'2020-07-04','11:13:44',34,'Generates Purchase Order Monitoring',null,null,30),(367,2,null,'2020-07-04','11:14:58',34,'Generates Purchase Order Monitoring',null,null,30),(368,2,null,'2020-07-04','11:14:58',34,'Generates Purchase Order Monitoring',null,null,30),(369,2,null,'2020-07-04','11:14:58',34,'Generates Purchase Order Monitoring',null,null,30),(370,2,null,'2020-07-04','11:14:58',34,'Generates Purchase Order Monitoring',null,null,30),(371,2,null,'2020-07-04','11:14:58',34,'Generates Purchase Order Monitoring',null,null,30),(372,2,null,'2020-07-04','11:14:58',34,'Generates Purchase Order Monitoring',null,null,30),(373,2,null,'2020-07-04','11:15:06',34,'Generates Purchase Order Monitoring',null,null,30),(374,2,null,'2020-07-04','11:15:06',34,'Generates Purchase Order Monitoring',null,null,30),(375,2,null,'2020-07-04','11:15:06',34,'Generates Purchase Order Monitoring',null,null,30),(376,2,null,'2020-07-04','11:15:06',34,'Generates Purchase Order Monitoring',null,null,30),(377,2,null,'2020-07-04','11:15:06',34,'Generates Purchase Order Monitoring',null,null,30),(378,2,null,'2020-07-04','11:15:06',34,'Generates Purchase Order Monitoring',null,null,30),(379,2,null,'2020-07-04','11:15:44',34,'Exported the generated Purchase Order',null,null,30),(380,2,null,'2020-07-04','11:15:58',34,'Exported the generated Purchase Order',null,null,30),(381,2,null,'2020-07-04','11:16:14',34,'Exported the generated Purchase Order',null,null,30),(382,2,null,'2020-07-04','11:24:29',34,' added a new receiving transaction.',11,3,2),(383,2,null,'2020-07-04','11:25:47',34,'Generates Purchase Order Monitoring',null,null,30),(384,2,null,'2020-07-04','11:26:26',34,'Generates Purchase Order Monitoring',null,null,30),(385,2,null,'2020-07-04','11:26:26',34,'Generates Purchase Order Monitoring',null,null,30),(386,2,null,'2020-07-04','11:26:26',34,'Generates Purchase Order Monitoring',null,null,30),(387,2,null,'2020-07-04','11:26:26',34,'Generates Purchase Order Monitoring',null,null,30),(388,2,null,'2020-07-04','11:26:26',34,'Generates Purchase Order Monitoring',null,null,30),(389,2,null,'2020-07-04','11:26:26',34,'Generates Purchase Order Monitoring',null,null,30),(390,2,null,'2020-07-04','11:26:27',34,'Generates Purchase Order Monitoring',null,null,30),(391,2,null,'2020-07-04','11:26:35',34,'Generates Purchase Order Monitoring',null,null,30),(392,null,null,'2020-07-04','11:28:29',34,'sysadmin added a new series reference IC',null,null,null),(393,2,null,'2020-07-04','11:30:07',34,'Generates Inventory Balances.',null,null,null),(394,2,null,'2020-07-04','11:30:15',34,'Generates Inventory Balances.',null,null,null),(395,2,null,'2020-07-04','11:32:43',34,' added a new receiving transaction.',11,4,2),(396,null,null,'2020-07-04','13:40:33',34,'sysadmin added a new classification.',null,null,null),(397,2,null,'2020-07-04','13:41:09',34,'added a new Item, Classified\'s Item',null,null,null),(398,null,null,'2020-07-04','13:41:21',34,'sysadmin deleted a classification',null,null,null),(399,null,null,'2020-07-04','13:41:52',34,'sysadmin added a new unit.',null,null,null),(400,2,null,'2020-07-04','13:42:14',34,'edited an item details, Classified\'s Item',null,null,null),(401,null,null,'2020-07-04','13:42:22',34,'sysadmin deleted the unit.',null,null,null),(402,null,null,'2020-07-04','13:53:30',34,'sysadmin added a new series reference PR',null,null,null),(403,2,null,'2020-07-04','13:54:58',34,' added a new purchase return transaction.',10,1,29),(404,2,null,'2020-07-04','14:00:25',34,'Generates Receiving Summary Report',null,0,34),(405,null,null,'2020-07-04','14:00:48',34,'Generates Purchase return summary report',null,0,39),(406,2,null,'2020-07-04','14:02:01',34,'Generates Expiry Monitoring.',null,null,null),(407,2,null,'2020-07-04','14:02:04',34,'Generates Expiry Monitoring.',null,null,null),(408,null,null,'2020-07-04','14:04:16',34,'sysadmin added a new series reference SO',null,null,null),(409,2,null,'2020-07-04','14:05:33',34,'Sales Order :  added a new Sales Order transaction',13,1,17),(410,6,null,'2020-07-04','14:24:44',34,'sysadmin has changed affiliate.',null,null,null),(411,2,null,'2020-07-04','14:25:32',34,'sysadmin has changed affiliate.',null,null,null),(412,null,null,'2020-07-04','14:39:00',34,'sysadmin added a new series reference SA',null,null,null),(413,2,null,'2020-07-04','14:45:17',34,'Sales :  added a new Sales Order transaction ',12,1,18),(414,2,null,'2020-07-04','14:48:19',34,'Sales :  added a new Sales Order transaction ',12,2,18),(415,null,null,'2020-07-04','14:51:53',34,'Generates Releasing Summary Report.',null,null,null),(416,2,null,'2020-07-04','14:53:45',34,' Generates Sales Summary Report',null,null,null),(417,2,null,'2020-07-04','14:56:23',34,'Generates Receivable Transactions Report.',null,null,null),(418,2,null,'2020-07-04','14:57:36',34,'Generates Receivable Balance.',null,null,null),(419,2,null,'2020-07-04','14:58:05',34,'Generates Receivable Ledger.',null,null,null),(420,2,null,'2020-07-04','14:58:58',34,'Exported the generated Receivable SOA(PDF).',null,null,null),(421,2,null,'2020-07-04','14:59:17',34,'Exported the generated Receivable SOA(Excel).',null,null,null),(422,2,null,'2020-07-04','14:59:39',34,'Exported the generated Receivable SOA(PDF) and sent as email.',null,null,null),(423,2,null,'2020-07-04','15:00:05',34,'Generates Itemized Profit and Loss Report.',null,null,null),(424,2,null,'2020-07-04','15:01:29',34,'Cancelled Transactions :  generates Cancelled Transactions Report',null,null,null),(425,2,null,'2020-07-04','15:01:52',34,'Cancelled Transactions :  generates Cancelled Transactions Report',null,null,null),(426,2,null,'2020-07-04','15:11:55',34,'Generates Inventory Balances.',null,null,null),(427,2,null,'2020-07-04','15:12:58',34,'Generates Inventory Ledger Report.',null,null,null),(428,2,null,'2020-07-04','15:13:07',34,'Generates Inventory Ledger Report.',null,null,null),(429,2,null,'2020-07-04','15:13:42',34,'Generates Conversions Summary Report.',null,null,null),(430,2,null,'2020-07-04','15:13:52',34,'Generates Adjustment Summary Report.',null,null,null),(431,2,null,'2020-07-04','15:22:26',34,'Added new employee, Winston Talaba.',null,null,null),(432,2,null,'2020-07-04','15:31:04',34,'Modified the employee, WT, for Winston Talaba with usertype User',null,null,null),(433,5,null,'2020-07-04','15:33:46',49,'WT has changed affiliate.',null,null,null),(434,2,null,'2020-07-04','15:34:13',34,'Generates Receivable Transactions Report.',null,null,null),(435,2,null,'2020-07-04','15:34:58',34,'Generates Receiving Summary Report',null,0,34),(436,2,null,'2020-07-04','15:35:17',34,'Added new employee, KR, for Khin Rulida with usertype Supervisor',null,null,null),(437,2,null,'2020-07-04','15:38:43',34,'Added new employee, CG, for Christine Galbo with usertype Supervisor',null,null,null),(438,2,null,'2020-07-04','15:40:52',34,'Added new employee, RC, for Russel Canete with usertype User',null,null,null),(439,2,null,'2020-07-04','15:42:59',34,'Added new employee, KQ, for Katereen Berly Quimod with usertype User',null,null,null),(440,2,null,'2020-07-04','15:45:54',34,'Added new employee, RA, for Regine Alcontin with usertype User',null,null,null),(441,null,null,'2020-07-04','15:48:50',50,'KR has logged out of the system.',null,null,null),(442,null,null,'2020-07-04','15:49:57',52,'RC has logged out of the system.',null,null,null),(443,2,null,'2020-07-04','15:56:09',34,'Added new employee, ME, for Michelle Emnace with usertype User',null,null,null),(444,2,null,'2020-07-04','15:58:06',34,'Added new employee, AC, for Ainie Casquejo with usertype User',null,null,null),(445,2,null,'2020-07-04','16:08:39',34,'Modified the module access for the user account, WT of Winston Talaba, with usertype User.',null,null,null),(446,2,null,'2020-07-04','16:08:54',34,'Modified the module access for the user account, WT of Winston Talaba, with usertype User.',null,null,null),(447,2,null,'2020-07-04','16:16:15',34,'Modified the employee, WT, for Winston Talaba with usertype User',null,null,null),(448,null,null,'2020-07-04','16:26:24',34,'Accouting Defaults :  added new default journal entry for the purpose of Sample JE',null,null,null),(449,null,null,'2020-07-04','16:30:37',34,'sysadmin edited the bank BDO',null,null,null),(450,null,null,'2020-07-04','16:39:29',34,'sysadmin added a new series reference CR',null,null,null),(451,2,null,'2020-07-04','16:39:38',34,'sysadmin added a new cash Receipt transaction',null,null,null),(452,5,null,'2020-07-04','16:43:18',34,'sysadmin has changed affiliate.',null,null,null),(453,2,null,'2020-07-04','16:44:03',34,'Bank Account Settings :  added a new bank account for Cash In Bank - BPI',null,null,null),(454,2,null,'2020-07-04','16:45:08',34,'sysadmin has changed affiliate.',null,null,null),(455,6,null,'2020-07-04','16:47:16',34,'sysadmin has changed affiliate.',null,null,null),(456,5,null,'2020-07-04','16:47:27',34,'sysadmin has changed affiliate.',null,null,null),(457,2,null,'2020-07-04','16:47:40',34,'sysadmin has changed affiliate.',null,null,null),(458,2,null,'2020-07-04','16:52:28',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(459,2,null,'2020-07-04','16:55:08',34,'Generates Collection Summary Report.',null,null,null),(460,2,null,'2020-07-04','16:56:45',34,'Generates payable schedule report',null,null,null),(461,2,null,'2020-07-04','16:57:26',34,'Generates Receivable Schedule Report',null,null,null),(462,2,null,'2020-07-04','16:57:39',34,'Generates Aging of Receivables.',null,null,null),(463,2,null,'2020-07-04','16:57:49',34,'Generates Aging of Payables.',null,null,null),(464,2,null,'2020-07-04','16:58:27',34,'Cheque Monitoring :  Generates Cheque Monitoring Report',null,null,null),(465,2,null,'2020-07-04','16:58:35',34,'Cheque Monitoring :  Generates Cheque Monitoring Report',null,null,null),(466,2,null,'2020-07-04','16:58:44',34,'Cheque Monitoring :  Generates Cheque Monitoring Report',null,null,null),(467,2,null,'2020-07-04','16:59:50',34,'sysadmin added a new cash Receipt transaction',null,null,null),(468,2,null,'2020-07-04','16:59:54',34,'Cheque Monitoring :  Generates Cheque Monitoring Report',null,null,null),(469,2,null,'2020-07-04','17:01:40',34,'Generates cheque summary report.',null,null,null),(470,4,null,'2020-07-15','11:43:50',50,'Added new affiliate, Manuel Auto Supply',null,null,null),(471,4,null,'2020-07-15','11:52:12',50,'Added a new supplier, Davao Diamond Industrial Supply-Steel Bars/Cement',null,null,null),(472,null,null,'2020-07-15','12:56:00',52,'RC has logged out of the system.',null,null,null),(473,2,null,'2020-07-16','11:57:10',34,' added a new adjustment Transaction.',2,2,48),(474,2,null,'2020-07-16','11:57:31',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(475,2,null,'2020-07-16','13:04:59',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(476,2,null,'2020-07-16','13:09:29',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(477,2,null,'2020-07-16','13:09:30',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(478,2,null,'2020-07-16','13:09:30',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(479,2,null,'2020-07-16','13:09:31',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(480,2,null,'2020-07-16','13:10:24',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(481,2,null,'2020-07-16','13:10:25',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(482,2,null,'2020-07-16','13:10:28',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(483,2,null,'2020-07-16','13:11:04',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(484,2,null,'2020-07-16','13:11:05',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(485,2,null,'2020-07-16','13:11:05',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(486,2,null,'2020-07-16','13:11:06',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(487,2,null,'2020-07-16','13:11:06',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(488,2,null,'2020-07-16','13:11:06',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(489,2,null,'2020-07-16','13:11:06',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(490,2,null,'2020-07-16','13:11:08',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(491,2,null,'2020-07-16','13:11:41',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(492,2,null,'2020-07-16','13:22:54',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(493,6,null,'2020-07-17','14:25:48',50,'Added new affiliate, Kiokong Construction and Development Corporation',null,null,null),(494,null,null,'2020-07-17','14:26:04',50,'KR has logged out of the system.',null,null,null),(495,6,null,'2020-07-17','14:31:35',50,'Added new affiliate, Kiokong Enterprises, Farm and Trucking Services',null,null,null),(496,6,null,'2020-07-17','14:41:16',50,'Modified the affiliate, Kiokong Enterprises, Farm and Trucking Services',null,null,null),(497,6,null,'2020-07-17','15:01:39',50,'Modified the employee, KR, for Khin Rulida with usertype Supervisor',null,null,null),(498,6,null,'2020-07-17','15:02:05',50,'Modified the employee, KR, for Khin Rulida with usertype Supervisor',null,null,null),(499,6,null,'2020-07-17','15:02:35',50,'Modified the employee, KR, for Khin Rulida with usertype Supervisor',null,null,null),(500,9,null,'2020-07-17','15:02:44',50,'KR has changed affiliate.',null,null,null),(501,10,null,'2020-07-17','15:02:50',50,'KR has changed affiliate.',null,null,null),(502,10,null,'2020-07-17','15:03:29',50,'Modified the employee, KR, for Khin B. Rulida with usertype Supervisor',null,null,null),(503,10,null,'2020-07-17','15:04:56',50,'Modified the employee, KR, for Khin Bacus Rulida with usertype Supervisor',null,null,null),(504,10,null,'2020-07-17','15:05:26',50,'Modified the employee, khinmaster, for Khin Bacus Rulida with usertype Supervisor',null,null,null),(505,10,null,'2020-07-17','15:07:56',50,'Modified the employee, khinmaster, for Khin Bacus Rulida with usertype Supervisor',null,null,null),(506,null,null,'2020-07-17','15:11:51',50,'KR has logged out of the system.',null,null,null),(507,null,null,'2020-08-27','10:59:44',34,'sysadmin has logged out of the system.',null,null,null),(508,2,null,'2020-08-27','11:01:34',34,'sysadmin edited a transaction',null,null,null),(509,5,null,'2020-08-27','11:08:51',34,'sysadmin has changed affiliate.',null,null,null),(510,2,null,'2020-08-27','11:09:25',34,'sysadmin has changed affiliate.',null,null,null),(511,2,null,'2020-08-27','11:24:10',34,'Generates Purchase Order Monitoring',null,null,30),(512,2,null,'2020-08-27','11:24:15',34,'Exported the generated Purchase Order',null,null,30),(513,2,null,'2020-08-27','11:24:29',34,'Generates Purchase Order Monitoring',null,null,30),(514,2,null,'2020-08-27','11:24:29',34,'Generates Purchase Order Monitoring',null,null,30),(515,2,null,'2020-08-27','11:24:29',34,'Generates Purchase Order Monitoring',null,null,30),(516,2,null,'2020-08-27','11:24:29',34,'Generates Purchase Order Monitoring',null,null,30),(517,2,null,'2020-08-27','11:24:29',34,'Generates Purchase Order Monitoring',null,null,30),(518,2,null,'2020-08-27','11:24:29',34,'Generates Purchase Order Monitoring',null,null,30),(519,2,null,'2020-08-27','11:24:31',34,'Generates Purchase Order Monitoring',null,null,30),(520,2,null,'2020-08-27','11:24:35',34,'Exported the generated Purchase Order',null,null,30),(521,null,null,'2020-08-27','11:26:34',34,'Generates Purchase return summary report',null,0,39),(522,null,null,'2020-08-27','11:26:35',34,'Generates Purchase return summary report',null,0,39),(523,null,null,'2020-08-27','11:26:46',34,'Generates Purchase return summary report',null,0,39),(524,null,null,'2020-08-27','11:26:48',34,'Exported the generated Purchase Return Summary Report',null,0,39),(525,2,null,'2020-08-27','11:27:05',34,'Generates Receiving Summary Report',null,0,34),(526,2,null,'2020-08-27','11:27:09',34,'Exported the generated Receiving summary Report',null,0,34),(527,null,null,'2020-08-27','11:27:33',34,'Inventory Conversion:  printed a PDF report',null,null,null),(528,2,null,'2020-08-27','13:35:27',34,' Generates Sales Summary Report',null,null,null),(529,2,null,'2020-08-27','13:35:37',34,' Generates Sales Summary Report',null,null,null),(530,2,null,'2020-08-27','13:35:39',34,' Exported the generated Sales Summary Report (PDF)',null,null,null),(531,null,null,'2020-08-27','14:08:13',34,'sysadmin has logged out of the system.',null,null,null),(532,2,null,'2020-09-01','16:35:24',34,' Exported the generated Cash Receipt Form (PDF)',null,null,null),(533,null,null,'2020-09-02','07:51:53',34,'sysadmin has logged out of the system.',null,null,null),(534,2,null,'2020-09-02','08:10:17',34,'Generates Purchase Order Monitoring',null,null,30),(535,2,null,'2020-09-02','08:10:19',34,'Exported the generated Purchase Order',null,null,30),(536,2,null,'2020-09-02','08:10:28',34,'Generates Purchase Order Monitoring',null,null,30),(537,2,null,'2020-09-02','08:10:28',34,'Generates Purchase Order Monitoring',null,null,30),(538,2,null,'2020-09-02','08:10:28',34,'Generates Purchase Order Monitoring',null,null,30),(539,2,null,'2020-09-02','08:10:29',34,'Generates Purchase Order Monitoring',null,null,30),(540,2,null,'2020-09-02','08:10:29',34,'Generates Purchase Order Monitoring',null,null,30),(541,2,null,'2020-09-02','08:10:29',34,'Generates Purchase Order Monitoring',null,null,30),(542,2,null,'2020-09-02','08:10:29',34,'Exported the generated Purchase Order',null,null,30),(543,null,null,'2020-09-02','09:17:31',34,'sysadmin added a new classification.',null,null,null),(544,null,null,'2020-09-02','09:17:31',34,'sysadmin added a new classification.',null,null,null),(545,null,null,'2020-09-02','09:30:27',34,'sysadmin added a new classification.',null,null,null),(546,null,null,'2020-09-02','09:30:27',34,'sysadmin added a new classification.',null,null,null),(547,null,null,'2020-09-02','09:30:27',34,'sysadmin added a new classification.',null,null,null),(548,null,null,'2020-09-02','15:13:13',34,'sysadmin has logged out of the system.',null,null,null),(549,2,null,'2020-09-08','13:23:57',34,'Generates Collection Summary Report.',null,null,null),(550,null,null,'2020-10-03','14:28:34',34,'sysadmin has logged out of the system.',null,null,null),(551,null,null,'2020-10-03','14:34:29',50,'khinmaster has logged out of the system.',null,null,null),(552,5,null,'2020-10-03','15:12:47',34,'Deleted the user account of, Ainie Casquejo with username: AC.',null,null,null),(553,6,null,'2020-10-29','14:26:56',34,'sysadmin has changed affiliate.',null,null,null),(554,5,null,'2020-10-29','14:27:27',34,'sysadmin has changed affiliate.',null,null,null),(555,null,null,'2020-10-29','19:32:40',34,'Chart of Accounts :  deleted account code : 4102001.',null,null,null),(556,null,null,'2020-10-29','22:58:17',34,'sysadmin has logged out of the system.',null,null,null),(557,2,null,'2021-03-06','13:01:41',34,'Added new employee, sample123.',null,null,null),(558,2,null,'2021-03-06','13:02:10',34,'Modified the employee, sample123, for sample123 with usertype Supervisor',null,null,null),(559,null,null,'2021-03-06','13:11:21',34,'sysadmin has logged out of the system.',null,null,null),(560,null,null,'2021-03-06','13:25:40',34,'Added new contribution, Pag-ibig',null,null,null),(561,2,null,'2021-03-06','13:27:55',34,'Modified the employee, sample123, for sample123 with usertype Supervisor',null,null,null),(562,2,null,'2021-03-06','13:29:43',34,'Modified the module access for the user account, khinmaster of Khin Bacus Rulida, with usertype Administrator.',null,null,null),(563,null,null,'2021-03-06','13:43:51',34,'sysadmin added a new initial reference RR1',null,null,null),(564,null,null,'2021-03-06','13:44:13',34,'sysadmin edited the details of initial reference RR1',null,null,null),(565,2,null,'2021-03-06','14:02:21',34,'Generates Purchase Order Monitoring',null,null,30),(566,2,null,'2021-03-06','14:03:40',34,'Generates Purchase Order Monitoring',null,null,30),(567,2,null,'2021-03-06','14:03:40',34,'Generates Purchase Order Monitoring',null,null,30),(568,2,null,'2021-03-06','14:03:40',34,'Generates Purchase Order Monitoring',null,null,30),(569,2,null,'2021-03-06','14:03:40',34,'Generates Purchase Order Monitoring',null,null,30),(570,2,null,'2021-03-06','14:03:41',34,'Generates Purchase Order Monitoring',null,null,30),(571,2,null,'2021-03-06','14:03:41',34,'Generates Purchase Order Monitoring',null,null,30),(572,2,null,'2021-03-06','14:03:52',34,'Generates Purchase Order Monitoring',null,null,30),(573,2,null,'2021-03-06','14:10:57',34,'Generates Receiving Summary Report',null,null,34),(574,2,null,'2021-03-06','14:11:49',34,'Generates Receiving Summary Report',null,0,34),(575,null,null,'2021-03-06','14:12:13',34,'Generates Purchase return summary report',null,0,39),(576,null,null,'2021-03-06','14:12:20',34,'Generates Purchase return summary report',null,0,39),(577,2,null,'2021-03-06','14:12:28',34,'Generates Expiry Monitoring.',null,null,null),(578,2,null,'2021-03-06','14:19:35',34,'Generates Inventory Balances.',null,null,null),(579,2,null,'2021-03-06','14:37:40',34,'Generates Aging of Receivables.',null,null,null),(580,2,null,'2021-03-06','14:37:43',34,'Generates Aging of Receivables.',null,null,null),(581,2,null,'2021-03-06','14:37:45',34,'Generates Aging of Receivables.',null,null,null),(582,2,null,'2021-03-09','14:57:04',34,'Deleted the affiliate, 48f439a95e2d985eb66e06e0df1a2afa23af1df34e9146368ff8a91defbe48912855ad7d1da0a958a0712cca2819dbea72d353ed30bf53bafde6d65c39d6ca362wM+73jFc3giDFhIRlLx+NrJvfvu3PMDSBMDviMX8RGD6c5pOBBZyO2LkTq0Ff1E',null,null,null),(583,2,null,'2021-03-09','14:57:10',34,'Deleted the affiliate, a63f087451e885c27a5b23a153647bb9dccde93414bb7870b65ae34a217742514f456a470cab7db675c3c989289cca3308859db6da243e03e6bd85a37b6b7655qaiHqMG8QPO5hWUi9g1phh2dAaeiA7n+9ik6h+/3nzP9cF/qPI9295IXC/dDy2mOe40B9wHZ0DWjaTt3B5yUHRu5JZTQCue2CXUJkzhSAgw=',null,null,null),(584,2,null,'2021-03-09','14:57:15',34,'Deleted the affiliate, e6b91b8ccad9efedf02c1ba8d8af9f6334fc85a3a277e334de0ae2424c9ce814688676129bea9e4cc91e1915c0488238963ade8552560e96d870c52959bf066fc4HCSPVJrpk4GqSeiHu2zoIoATXUrUtvq1TmtwYB5ZgaDoMLH9PXTSHRHIedBu5RpVDY+4At9kS9oh7DiL9SNA==',null,null,null),(585,null,null,'2021-03-09','14:57:31',34,'sysadmin has logged out of the system.',null,null,null),(586,null,null,'2021-03-09','15:03:31',34,'sysadmin has logged out of the system.',null,null,null),(587,null,null,'2021-03-09','15:03:32',34,'sysadmin has logged out of the system.',null,null,null),(588,null,null,'2021-03-09','15:04:41',34,'sysadmin has logged out of the system.',null,null,null),(589,null,null,'2021-03-09','15:09:54',34,'sysadmin has logged out of the system.',null,null,null),(590,2,null,'2021-03-09','16:49:22',34,'Generates Purchase Order Monitoring',null,null,30),(591,2,null,'2021-03-09','16:50:25',34,'Generates Purchase Order Monitoring',null,null,30),(592,2,null,'2021-03-09','16:50:25',34,'Generates Purchase Order Monitoring',null,null,30),(593,2,null,'2021-03-09','16:50:25',34,'Generates Purchase Order Monitoring',null,null,30),(594,2,null,'2021-03-09','16:50:25',34,'Generates Purchase Order Monitoring',null,null,30),(595,2,null,'2021-03-09','16:50:25',34,'Generates Purchase Order Monitoring',null,null,30),(596,2,null,'2021-03-09','16:50:26',34,'Generates Purchase Order Monitoring',null,null,30),(597,2,null,'2021-03-09','16:50:34',34,'Generates Purchase Order Monitoring',null,null,30),(598,2,null,'2021-03-09','16:52:39',34,' added a new transaction.',19,102,2),(599,2,null,'2021-03-09','16:53:26',34,' added a new receiving transaction.',11,5,2),(600,2,null,'2021-03-09','16:58:13',34,'Generates Receiving Summary Report',null,0,34),(601,2,null,'2021-03-09','16:58:28',34,'Generates Receiving Summary Report',null,0,34),(602,null,null,'2021-03-09','16:58:40',34,'Generates Purchase return summary report',null,0,39),(603,2,null,'2021-03-09','16:58:55',34,'Generates Expiry Monitoring.',null,null,null),(604,null,null,'2021-03-09','17:05:04',34,'Generates Releasing Summary Report.',null,null,null),(605,null,null,'2021-03-09','17:05:15',34,'Generates Releasing Summary Report.',null,null,null),(606,2,null,'2021-03-09','17:05:42',34,' Generates Sales Summary Report',null,null,null),(607,2,null,'2021-03-09','17:05:50',34,' Generates Sales Summary Report',null,null,null),(608,2,null,'2021-03-09','17:06:43',34,'Generates Receivable Transactions Report.',null,null,null),(609,2,null,'2021-03-09','17:06:50',34,'Generates Receivable Transactions Report.',null,null,null),(610,2,null,'2021-03-09','17:07:05',34,'Generates Receivable Balance.',null,null,null),(611,2,null,'2021-03-09','17:07:58',34,'Generates Itemized Profit and Loss Report.',null,null,null),(612,2,null,'2021-03-09','17:08:35',34,'Cancelled Transactions :  generates Cancelled Transactions Report',null,null,null),(613,2,null,'2021-03-09','17:12:13',34,'Generates Inventory Balances.',null,null,null),(614,2,null,'2021-03-09','17:12:28',34,'Generates Inventory Ledger Report.',null,null,null),(615,2,null,'2021-03-09','17:12:33',34,'Generates Inventory Ledger Report.',null,null,null),(616,2,null,'2021-03-09','17:12:38',34,'Generates Inventory Ledger Report.',null,null,null),(617,2,null,'2021-03-09','17:12:47',34,'Generates Inventory Ledger Report.',null,null,null),(618,2,null,'2021-03-09','17:27:46',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(619,2,null,'2021-03-09','17:31:46',34,'Generates payable schedule report',null,null,null),(620,2,null,'2021-03-09','17:32:02',34,'Generates Receivable Schedule Report',null,null,null),(621,2,null,'2021-03-09','17:32:14',34,'Generates Aging of Receivables.',null,null,null),(622,2,null,'2021-03-09','17:32:39',34,'Generates Aging of Payables.',null,null,null),(623,2,null,'2021-03-09','17:32:47',34,'Cheque Monitoring :  Generates Cheque Monitoring Report',null,null,null),(624,2,null,'2021-03-09','17:32:48',34,'Cheque Monitoring :  Generates Cheque Monitoring Report',null,null,null),(625,2,null,'2021-03-09','17:32:59',34,'Generates cheque summary report.',null,null,null),(626,null,null,'2021-03-17','12:59:12',34,'sysadmin added a new initial reference OR',null,null,null),(627,null,null,'2021-03-17','12:59:50',34,'sysadmin added a new series reference OR',null,null,null),(628,null,null,'2021-03-17','13:02:46',34,'sysadmin edited the details of series reference OR',null,null,null),(629,null,null,'2021-03-17','13:03:38',34,'sysadmin edited the details of series reference OR',null,null,null),(630,null,null,'2021-03-17','13:07:10',34,'sysadmin edited the details of series reference OR',null,null,null),(631,null,null,'2021-03-17','13:07:18',34,'sysadmin edited the details of series reference OR',null,null,null),(632,null,null,'2021-03-17','13:07:31',34,'sysadmin edited the details of series reference OR',null,null,null),(633,null,null,'2021-03-17','13:07:34',34,'sysadmin edited the details of series reference OR',null,null,null),(634,null,null,'2021-03-17','13:09:35',34,'sysadmin edited the details of series reference OR',null,null,null),(635,null,null,'2021-03-17','13:10:24',34,'sysadmin edited the details of series reference OR',null,null,null),(636,null,null,'2021-03-17','13:10:42',34,'sysadmin edited the details of series reference OR',null,null,null),(637,null,null,'2021-03-17','13:12:02',34,'sysadmin has logged out of the system.',null,null,null),(638,2,null,'2021-08-11','11:28:17',34,' added a new transaction.',9,11,2),(639,2,null,'2021-08-11','11:31:46',34,'Edited the supplier details of, Supplier VAT Inclusive',null,null,null),(640,2,null,'2021-08-11','11:33:05',34,' added a new receiving transaction.',11,6,2),(641,2,null,'2021-08-11','11:53:59',34,' added a new purchase return transaction.',10,2,29),(642,2,null,'2021-08-11','11:54:25',34,'Generates Inventory Balances.',null,null,null),(643,2,null,'2021-08-11','13:05:30',34,'Generates Inventory Balances.',null,null,null),(644,5,null,'2021-08-11','13:06:32',34,'sysadmin has changed affiliate.',null,null,null),(645,null,null,'2021-08-11','13:07:06',34,'sysadmin added a new series reference IAD',null,null,null),(646,5,null,'2021-08-11','16:19:32',34,'Generates Inventory Balances.',null,null,null),(647,5,null,'2021-08-11','16:19:38',34,'Generates Inventory Ledger Report.',null,null,null),(648,2,null,'2021-08-11','16:26:50',34,'sysadmin has changed affiliate.',null,null,null),(649,2,null,'2021-08-11','16:29:11',34,'Generate Accounting Adjustment Summary Report.',null,null,null),(650,2,null,'2021-08-11','16:31:41',34,'Generates Receiving Summary Report',null,0,34),(651,2,null,'2021-08-11','16:32:11',34,'Generates Expiry Monitoring.',null,null,null),(652,null,null,'2021-08-11','16:32:43',34,'Generates Releasing Summary Report.',null,null,null),(653,2,null,'2021-08-11','16:32:56',34,'Generates Receivable Transactions Report.',null,null,null),(654,2,null,'2021-08-11','16:32:58',34,'Generates Receivable Transactions Report.',null,null,null),(655,2,null,'2021-08-11','16:33:02',34,'Generates Receivable Transactions Report.',null,null,null),(656,2,null,'2021-08-11','16:33:32',34,'Generates Receivable Transactions Report.',null,null,null),(657,2,null,'2021-08-11','16:33:43',34,'Generates Itemized Profit and Loss Report.',null,null,null),(658,null,null,'2021-08-18','08:47:17',34,'sysadmin has logged out of the system.',null,null,null),(659,2,null,'2021-08-18','09:35:17',34,'edited an item details, Classified\'s Item',null,null,null),(660,2,null,'2021-08-18','09:36:11',34,'edited an item details, Iterm 2',null,null,null),(661,2,null,'2021-08-18','09:42:27',34,' added a new transaction.',19,103,2),(662,2,null,'2021-08-18','09:43:11',34,'Generates Purchase Order Monitoring',null,null,30),(663,2,null,'2021-08-18','09:45:57',34,'Generates Purchase Order Monitoring',null,null,30),(664,2,null,'2021-08-18','09:45:57',34,'Generates Purchase Order Monitoring',null,null,30),(665,2,null,'2021-08-18','09:45:57',34,'Generates Purchase Order Monitoring',null,null,30),(666,2,null,'2021-08-18','09:45:57',34,'Generates Purchase Order Monitoring',null,null,30),(667,2,null,'2021-08-18','09:45:57',34,'Generates Purchase Order Monitoring',null,null,30),(668,2,null,'2021-08-18','09:45:57',34,'Generates Purchase Order Monitoring',null,null,30),(669,2,null,'2021-08-18','09:46:09',34,'Generates Purchase Order Monitoring',null,null,30),(670,2,null,'2021-08-18','09:47:07',34,'Generates Purchase Order Monitoring',null,null,30),(671,2,null,'2021-08-18','09:47:07',34,'Generates Purchase Order Monitoring',null,null,30),(672,2,null,'2021-08-18','09:47:07',34,'Generates Purchase Order Monitoring',null,null,30),(673,2,null,'2021-08-18','09:47:07',34,'Generates Purchase Order Monitoring',null,null,30),(674,2,null,'2021-08-18','09:47:07',34,'Generates Purchase Order Monitoring',null,null,30),(675,2,null,'2021-08-18','09:47:08',34,'Generates Purchase Order Monitoring',null,null,30),(676,2,null,'2021-08-18','09:53:04',34,'Generates Receiving Summary Report',null,0,34),(677,2,null,'2021-08-18','09:53:15',34,'Generates Receiving Summary Report',null,0,34),(678,2,null,'2021-08-18','09:53:19',34,'Generates Receiving Summary Report',null,0,34),(679,null,null,'2021-08-18','09:53:23',34,'Generates Purchase return summary report',null,0,39),(680,2,null,'2021-08-18','09:53:31',34,'Generates Expiry Monitoring.',null,null,null),(681,2,null,'2021-08-18','09:53:33',34,'Generates Expiry Monitoring.',null,null,null),(682,2,null,'2021-08-18','09:53:45',34,'Generates Expiry Monitoring.',null,null,null),(683,2,null,'2021-08-18','09:54:04',34,'Generates Expiry Monitoring.',null,null,null),(684,2,null,'2021-08-18','09:54:11',34,'Generates Expiry Monitoring.',null,null,null),(685,5,null,'2021-08-18','10:24:23',34,'Added new affiliate, H Company',null,null,null),(686,5,null,'2021-08-18','10:28:05',34,'Modified the affiliate, H Company',null,null,null),(687,5,null,'2021-08-18','10:28:42',34,'Modified the affiliate, H Company',null,null,null),(688,5,null,'2021-08-18','10:33:04',34,'Deleted the affiliate, df672a82206368f3eb5b9e8b0ea079e46b7a382c184c27f69946514de0e402567a5822c417e77272829831732e7333eb2ad9f64ccc5e60cff8e197bb0d87f459l0Er48OoNLRqDHkjJTyn6iFn1P/dXuGs1mY++2ePjvU=',null,null,null),(689,5,null,'2021-08-18','10:33:29',34,'Deleted the affiliate, df672a82206368f3eb5b9e8b0ea079e46b7a382c184c27f69946514de0e402567a5822c417e77272829831732e7333eb2ad9f64ccc5e60cff8e197bb0d87f459l0Er48OoNLRqDHkjJTyn6iFn1P/dXuGs1mY++2ePjvU=',null,null,null),(690,5,null,'2021-08-18','10:37:51',34,'Modified the affiliate, H Company',null,null,null),(691,null,null,'2021-08-18','11:01:17',34,'Added new classification, Grocer',null,null,null),(692,null,null,'2021-08-18','11:01:48',34,'Modified the classification, Grocery Staff',null,null,null),(693,null,null,'2021-08-18','11:01:55',34,'Modified the classification, Grocer',null,null,null),(694,null,null,'2021-08-18','11:02:14',34,'Deleted the classificatin, Grocer',null,null,null),(695,null,null,'2021-08-18','11:03:00',34,'Added new classification, Grocer',null,null,null),(696,5,null,'2021-08-18','11:07:17',34,'Added new employee, Jane Cabo.',null,null,null),(697,5,null,'2021-08-18','11:12:43',34,'Modified the employee, Jane Cabo.',null,null,null),(698,5,null,'2021-08-18','11:16:24',34,'Added new employee, alexmartin.ballora, for Alex Martin Ballora with usertype User',null,null,null),(699,5,null,'2021-08-18','11:24:31',34,'Modified the employee, Jane Cabo.',null,null,null),(700,5,null,'2021-08-18','11:24:51',34,'Modified the employee, Jane Cabo.',null,null,null),(701,5,null,'2021-08-18','11:25:37',34,'Deleted the user account of, sample123 with username: sample123.',null,null,null),(702,5,null,'2021-08-18','11:27:23',34,'Modified the module access for the user account, alexmartin.ballora of Alex Martin Ballora, with usertype User.',null,null,null);:||:Separator:||:


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
 INSERT INTO `module` VALUES(1,0,3,0,'Dashboard','dashboard/Dashboard.js',0,1),(2,1,0,1,'Purchase Order','inventory/Purchaseorder.js',0,0),(3,5,2,4,'Employee Profile','admin/Usersettings.js',0,1),(4,5,2,1,'Affiliate Settings','admin/Affiliatesettings.js',0,1),(5,5,2,2,'Cost Center Settings','admin/Costcentersettings.js',0,1),(6,5,2,3,'Employee Classification Settings','admin/Empclassificationsettings.js',0,1),(7,5,2,6,'Backup and Restore','admin/Bandr.js',0,1),(8,4,3,3,'Reference Settings','generalsettings/Referencesettings.js',0,1),(9,5,2,5,'User Action Logs','admin/Userlog.js',0,1),(10,4,3,4,'Bank Settings','generalsettings/Banksettings.js',0,1),(11,4,3,1,'Customer Settings','generalsettings/customer.js',0,1),(12,4,3,2,'Supplier Settings','generalsettings/Supplier.js',0,1),(14,1,4,2,'Classification Settings','inventory/Classificationsettings.js',0,1),(15,1,4,3,'Unit Settings','inventory/Unitsettings.js',0,1),(16,1,4,1,'Item Settings','inventory/Item.js',0,1),(17,1,2,1,'Sales Order','inventory/Salesorder.js',0,0),(18,1,2,2,'Delivery','inventory/Sales.js',0,0),(19,2,2,1,'Chart of Accounts','accounting/Chartofaccounts.js',0,1),(20,2,2,3,'Accounting Defaults','accounting/Accountingdefaults.js',0,1),(21,1,2,3,'Sales Return','inventory/Salesreturn.js',0,0),(22,1,3,1,'Inventory Conversion','inventory/Inventoryconversion.js',0,0),(23,1,3,2,'Inventory Adjustment','inventory/Adjustments.js',0,0),(24,1,2,5,'Sales Summary','inventory/Salessummary.js',0,1),(25,1,1,1,'Receiving','inventory/Receiving.js',0,0),(26,1,2,6,'Sales Return Summary','inventory/Salesreturnsummary.js',0,1),(27,1,2,7,'SO Monitoring','inventory/Salesordermonitoring.js',0,1),(28,2,0,4,'Cash Receipts','accounting/Cashreceipts.js',0,0),(29,1,1,2,'Purchase Return','inventory/Purchasereturn.js',0,0),(30,1,0,2,'PO Monitoring','inventory/Pomonitoring.js',0,1),(33,1,1,3,'Payable Balance and Ledger','inventory/Payablebalanceledger.js',0,1),(34,1,1,5,'Receiving Summary','inventory/Receivingsummary.js',0,1),(35,2,0,8,'Closing Journal Entry','accounting/Closingentry.js',0,0),(36,2,1,6,'Collection Summary','accounting/Collectionsummary.js',0,1),(37,2,1,7,'Disbursement Summary','accounting/Disbursementsummary.js',0,1),(38,2,1,4,'Financial Report','accounting/Financialreport.js',0,1),(39,1,1,6,'Purchase Return Summary','inventory/Purchasereturnsummary.js',0,1),(40,2,1,2,'General and Subsidiary Ledger','accounting/Generalsubsidiaryledger.js',0,1),(41,1,1,7,'Expiry Monitoring','inventory/Expirymonitoring.js',0,1),(42,2,2,2,'Chart of Accounts Beginning Balance','accounting/Coabegbalance.js',0,1),(43,1,3,3,'Stock Transfer','inventory/Stocktransfer.js',0,0),(44,2,0,7,'Bank Reconciliation','accounting/Bankrecon.js',0,0),(45,2,0,5,'Disbursements','accounting/Disbursements.js',0,0),(46,1,2,9,'Receivable Balances, Ledger and SOA','inventory/Receivablebalanceledger.js',0,1),(47,1,2,10,'Itemized Profit and Loss','inventory/Itemizedprofitloss.js',0,1),(48,2,0,3,'Accounting Adjustment','accounting/Adjustmentsacc.js',0,0),(49,1,1,4,'Payable Transactions','inventory/Payabletransaction.js',0,1),(50,3,1,1,'Payable Schedule','generalreports/Payableschedule.js',0,1),(51,1,3,7,'Adjustment Summary','inventory/Adjustmentsummary.js',0,1),(52,1,2,4,'Releasing Summary','inventory/Releasingsummary.js',0,1),(53,1,3,6,'Conversion Summary','inventory/Conversionsummary.js',0,1),(54,1,2,8,'Receivable Transactions','inventory/Receivabletransaction.js',0,1),(55,3,1,4,'Aging of Payables','generalreports/Agingofpayables.js',0,1),(56,3,1,3,'Aging of Receivables','generalreports/Agingofreceivables.js',0,1),(57,2,0,2,'Vouchers Payable','accounting/Voucherspayable.js',0,0),(58,2,0,1,'Vouchers Receivable','accounting/Vouchersreceivable.js',0,0),(59,1,3,5,'Inventory Ledger','inventory/Inventoryledger.js',0,1),(60,3,1,2,'Schedule of Receivable','generalreports/Scheduleofreceivable.js',0,1),(61,1,3,4,'Inventory Balances','inventory/Inventorybalances.js',0,1),(62,2,0,6,'Beginning Balance','accounting/Beginningbalance.js',0,0),(63,2,2,4,'Bank Account Settings','accounting/Bankaccountsettings.js',0,1),(64,3,1,5,'Cheque Monitoring','generalreports/Chequemonitoring.js',0,1),(65,3,1,6,'Cheque Reports','generalreports/Chequereports.js',0,1),(66,2,1,3,'Accounting Adjustment Summary','accounting/Adjustmentsaccsummary.js',0,1),(67,1,2,11,'Cancelled Transactions','inventory/Cancelledtransactions.js',0,1),(68,2,1,5,'No JE Report','accounting/Nojereport.js',0,1),(69,2,1,1,'Journalized Transaction Summary','accounting/Journalizedtransactionsummary.js',0,1);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `po` WRITE;:||:Separator:||:
 INSERT INTO `po` VALUES(1,1,1,1000,650,100.00,1),(2,2,1,300,0,130.56,1),(3,2,1,150,0,145.00,2),(4,1,1,189,57,156.50,2),(5,1,1,2300,798,231.59,3),(6,2,1,148,0,150.00,3),(7,1,1,600,600,230.00,4),(8,2,1,300,300,140.00,4),(9,1,1,3000,3000,200.00,5),(10,2,1,45000,45000,156.00,5),(11,1,1,10,0,250.00,33),(12,1,1,1000,-500,130.00,35),(13,2,1,1000,-200,250.00,35),(14,1,1,20,10,250.00,46),(15,2,1,20,20,300.00,46),(16,5,7,1,0,1500.00,48),(17,2,1,2,0,300.00,48),(18,1,1,3,0,250.00,48),(19,1,1,10,10,230.00,51);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `postdated` WRITE;:||:Separator:||:
 INSERT INTO `postdated` VALUES(2,0,1,123456,'2020-06-11',3000.00,25,null,1,null,null,null),(3,2,1,123456,'2020-07-12',150.00,43,null,1,null,null,null),(4,1,1,0,'0000-00-00',150.00,43,null,1,null,null,null),(6,0,2,123456,'2020-07-02',200.00,44,null,1,null,null,null);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `posting` WRITE;:||:Separator:||:
 INSERT INTO `posting` VALUES(1,24,null,null,0.00,100.00,1103000,null,null),(2,24,null,null,100.00,0.00,2102000,null,null),(3,26,null,null,1.00,0.00,5101000,null,null),(4,26,null,null,0.00,1.00,2102000,null,null),(5,30,null,null,3243.00,0.00,1102000,null,0),(6,30,null,null,0.00,3243.00,1103000,null,0),(7,32,null,null,23423.00,0.00,1102000,null,0),(8,32,null,null,0.00,23423.00,2101000,null,0),(9,45,null,null,1000.00,0.00,1101000,null,0),(10,45,null,null,0.00,1000.00,1101001,null,0);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `postinghistory` WRITE;:||:Separator:||:
 INSERT INTO `postinghistory` VALUES(1,null,30,null,3243.00,0.00,1102000,null,0,14,null,null),(2,null,30,null,0.00,3243.00,1103000,null,0,14,null,null),(3,null,32,null,23423.00,0.00,1102000,null,0,16,null,null),(4,null,32,null,0.00,23423.00,2101000,null,0,16,null,null),(5,null,45,null,1000.00,0.00,1101000,null,0,21,null,null),(6,null,45,null,0.00,1000.00,1101001,null,0,21,null,null);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `receipts`;:||:Separator:||:


CREATE TABLE `receipts` (
  `idReceipts` int(11) NOT NULL AUTO_INCREMENT,
  `idInvoice` int(11) DEFAULT NULL,
  `idCustomer` int(11) DEFAULT NULL,
  `amount` decimal(18,2) DEFAULT '0.00',
  `ewtRate` decimal(18,2) DEFAULT '0.00',
  `ewtAmount` decimal(18,2) DEFAULT '0.00',
  `penaltyRate` decimal(18,2) DEFAULT '0.00',
  `penaltyAmount` decimal(18,2) DEFAULT '0.00',
  `fIDModule` int(11) DEFAULT NULL,
  `fident` int(11) DEFAULT NULL,
  `idBankRecon` int(11) DEFAULT NULL,
  PRIMARY KEY (`idReceipts`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `receipts` WRITE;:||:Separator:||:
 INSERT INTO `receipts` VALUES(2,25,3,3000.00,0.00,0.00,0.00,0.00,0,16,null),(3,43,8,300.00,0.00,0.00,0.00,0.00,18,42,null),(5,44,8,200.00,0.00,0.00,0.00,0.00,0,42,null);:||:Separator:||:


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
  `lotNumber` text,
  PRIMARY KEY (`idReceiving`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `receiving` WRITE;:||:Separator:||:
 INSERT INTO `receiving` VALUES(1,1,200,0,6,100.00,0.00,'0000-00-00',25,1,null),(2,2,150,145,6,130.56,0.00,'0000-00-00',25,1,null),(3,1,132,37,7,156.50,0.00,'0000-00-00',25,2,null),(4,2,150,150,7,145.00,0.00,'0000-00-00',25,2,null),(5,1,1502,1502,8,231.59,0.00,'0000-00-00',25,3,null),(6,2,148,148,8,150.00,0.00,'0000-00-00',25,3,null),(7,1,150,150,9,100.00,0.00,'0000-00-00',25,1,null),(8,2,150,150,9,130.56,0.00,'0000-00-00',25,1,null),(9,1,10,10,21,100.00,250.00,null,null,6,null),(10,2,10,null,23,130.56,0.00,null,null,null,null),(11,2,1,1,26,0.00,0.00,'0000-00-00',null,null,null),(12,2,1,0,27,200.00,0.00,'0000-00-00',25,null,null),(13,2,10,10,28,0.00,1000.00,'0000-00-00',null,null,null),(14,1,10,0,34,250.00,0.00,'0000-00-00',25,33,null),(15,1,500,398,36,132.00,0.00,null,25,35,null),(16,2,300,300,36,245.00,0.00,'0000-00-00',25,35,null),(19,1,1000,1000,38,130.00,0.00,'0000-00-00',25,35,null),(20,2,900,900,38,250.00,0.00,'0000-00-00',25,35,null),(23,3,20,20,37,0.00,3780.00,null,null,null,null),(24,4,30,30,37,0.00,3000.00,null,null,null,null),(25,1,10,10,47,250.00,0.00,'0000-00-00',25,46,0),(26,2,0,0,47,300.00,0.00,'0000-00-00',25,46,0),(27,1,3,1,49,250.00,0.00,'0000-00-00',25,48,0),(28,5,1,0,49,1500.00,0.00,'0000-00-00',25,48,0),(29,2,2,1,49,300.00,0.00,'0000-00-00',25,48,0);:||:Separator:||:


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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `receivinghistory` WRITE;:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `reference` WRITE;:||:Separator:||:
 INSERT INTO `reference` VALUES(1,'CE','Closing Entry',35,1,0),(2,'AAD','Accounting Adjustment',48,0,0),(3,'BR','Bank Reconciliation',44,0,0),(4,'BB','Beginning Balance',62,0,0),(5,'CR','Cash Receipt',28,0,0),(6,'DR','Disbursement',45,0,0),(7,'IAD','Inventory Adjustment',23,0,0),(8,'IC','Inventory Conversion',22,0,0),(9,'PO','Purchase Order',2,0,0),(10,'PR','Purchase Return',29,0,0),(11,'RR','Receiving',25,0,0),(12,'SA','Sales',18,0,0),(13,'SO','Sales Order',17,0,0),(14,'SR','Sales Return',21,0,0),(15,'ST','Stock Transfer',43,0,0),(16,'VP','Vouchers Payable',57,0,0),(17,'VR','Vouchers Receivable',58,0,0),(18,'InvAd','Inv Adjustment',23,0,0),(19,'PO1','Purchase Order 1',2,0,0),(20,'RR1','Receiving 1',25,0,0),(21,'OR','Test OR',45,0,0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
DROP TABLE IF EXISTS `referenceaffiliate`;:||:Separator:||:


CREATE TABLE `referenceaffiliate` (
  `idRefAffiliate` int(11) NOT NULL AUTO_INCREMENT,
  `idReference` int(11) DEFAULT NULL,
  `idAffiliate` int(11) DEFAULT NULL,
  PRIMARY KEY (`idRefAffiliate`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `referenceaffiliate` WRITE;:||:Separator:||:
 INSERT INTO `referenceaffiliate` VALUES(1,2,2),(2,2,5),(3,2,4),(4,3,2),(5,3,5),(6,3,4),(7,4,2),(8,4,5),(9,4,4),(10,5,2),(11,5,5),(12,5,4),(13,6,2),(14,6,5),(15,6,4),(16,7,2),(17,7,5),(18,7,4),(19,8,2),(20,8,5),(21,8,4),(22,9,2),(23,9,5),(24,9,4),(25,10,2),(26,10,5),(27,10,4),(28,11,2),(29,11,5),(30,11,4),(31,12,2),(32,12,5),(33,12,4),(34,13,2),(35,13,5),(36,13,4),(37,14,2),(38,14,5),(39,14,4),(40,15,2),(41,15,5),(42,15,4),(43,16,2),(44,16,5),(45,16,4),(46,17,2),(47,17,5),(48,17,4),(52,18,2),(53,18,5),(54,18,4),(55,19,2),(56,19,6),(57,19,5),(66,20,6),(67,20,9),(68,20,10),(69,20,8),(70,20,2),(71,20,5),(72,20,4),(73,20,11),(74,21,2);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `referenceseries` WRITE;:||:Separator:||:
 INSERT INTO `referenceseries` VALUES(1,'2010-01-01',1,null,35,1,1,99999,0),(2,'2010-01-01',2,null,35,1,1,99999,0),(3,'2020-06-12',4,null,48,2,1,5,0),(4,'2020-06-12',4,null,44,3,1,5,0),(5,'2020-06-12',4,null,62,4,1,5,0),(6,'2020-06-12',4,null,28,5,1,5,0),(7,'2020-06-12',4,null,45,6,1,5,0),(8,'2020-06-12',4,null,23,7,1,5,0),(9,'2020-06-12',4,null,22,8,1,5,0),(10,'2020-06-12',4,null,2,9,1,5,0),(11,'2020-06-12',4,null,29,10,1,5,0),(12,'2020-06-12',4,null,25,11,1,5,0),(13,'2020-06-12',4,null,18,12,1,5,0),(14,'2020-06-12',4,null,17,13,1,5,0),(15,'2020-06-12',4,null,21,14,1,5,0),(16,'2020-06-12',4,null,43,15,1,5,0),(17,'2020-06-12',4,null,57,16,1,5,0),(18,'2020-06-12',4,null,58,17,1,5,0),(19,'2020-06-12',2,null,23,7,1,1000,0),(20,'2020-06-12',2,null,25,11,1,1000,0),(21,'2020-06-13',2,null,2,9,10,20,0),(22,'2020-06-17',2,null,48,2,1,100,0),(23,'2020-07-03',6,null,35,1,1,999999,0),(24,'2020-07-03',7,null,35,1,1,999999,0),(25,'2020-07-03',8,null,35,1,1,999999,0),(26,'2020-06-01',2,null,2,19,101,200,0),(27,'2020-07-04',2,null,22,8,1,100,0),(28,'2020-07-04',2,null,29,10,1,100,0),(29,'2020-07-04',2,null,17,13,1,100,0),(30,'2020-07-04',2,null,18,12,1,100,0),(31,'2020-07-04',2,null,28,5,1,100,0),(32,'2020-07-05',9,null,35,1,1,999999,0),(33,'2020-07-17',10,null,35,1,1,999999,0),(34,'2020-07-17',11,null,35,1,1,999999,0),(35,'2021-03-17',2,null,45,21,234,300,0),(36,'2021-08-11',5,null,23,7,1,100,0),(37,'2021-08-18',12,null,35,1,1,999999,0);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `releasing` WRITE;:||:Separator:||:
 INSERT INTO `releasing` VALUES(1,1,10,10,100.00,null,10,null,1,null,null),(2,2,5,5,130.56,null,10,null,2,null,null),(3,1,12,12,100.00,250.00,16,null,1,null,'0000-00-00'),(4,1,6,6,100.00,250.00,17,null,1,null,'0000-00-00'),(5,1,10,10,100.00,250.00,18,null,1,null,'0000-00-00'),(6,1,130,120,100.00,250.00,19,null,1,null,'0000-00-00'),(7,1,32,32,100.00,250.00,20,null,1,null,'0000-00-00'),(8,1,94,94,156.50,250.00,20,null,3,null,'0000-00-00'),(9,2,10,null,130.56,null,22,null,8,null,null),(10,1,1,1,0.00,null,28,null,3,null,null),(12,2,1,1,200.00,null,39,null,12,null,null),(13,1,100,100,132.00,250.00,41,null,15,null,'0000-00-00'),(14,1,2,2,132.00,250.00,42,null,15,null,'0000-00-00'),(16,1,10,10,0.00,0.00,37,null,14,null,null),(17,1,2,2,250.00,null,50,null,27,null,null),(18,5,1,1,1500.00,null,50,null,28,null,null),(19,2,1,1,300.00,null,50,null,29,null,null);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `so` WRITE;:||:Separator:||:
 INSERT INTO `so` VALUES(1,1,30,8,250.00,11,null,null),(2,1,20,14,250.00,12,null,null),(3,2,50,20,200.00,12,null,null),(4,1,500,500,250.00,13,null,null),(5,2,1000,1000,200.00,13,null,null),(6,1,150,20,250.00,14,null,null),(7,2,165,15,200.00,14,null,null),(8,1,564,438,250.00,15,null,null),(9,2,300,150,200.00,15,null,null),(10,4,10,10,150.00,40,null,null),(11,3,20,20,189.00,40,null,null);:||:Separator:||:


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
 INSERT INTO `stocktransfer` VALUES(1,22,2,10,0);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `supplier` WRITE;:||:Separator:||:
 INSERT INTO `supplier` VALUES(1,'46536e37b70d39da23680f11fd0734fd817d371eedbc6b5cc176af5a6daa7ee167307332d9e3450da73b5800e8fbac52c997615fe8465297ae5828ea1fd784052DwL11NWo35sPjCnHDpjWqq6+v1ukZhXYmOiKRBE2jU=','89d8806c03987488f4784fb83387216bdf7d5885a8d6900644eb0263181427918aa55093002158de4755638bc2f4306530c49813f51696824b27b0966dcba31f33pDlRlw3F8+jCQXUNr4u75CR4pJaBNL/OdBhLN31QAcQa4e7m248eXIVWUZTPc/','f06e3cfd01c7237c8e5a5fe9e51e8c460b63040cbcadbf3c401abc25b46d2120648e0f4b5f4aea4d0a57ccf43fb4011868703f6ec6351f31622a4ce4457d9dadyfRlG7a2E2P3hKkUOOxjgWrasUtx0Vu20SG5U+tdefM=','93f8812d6442e44a6c375d5cf38be4b8301390ff8e237653b3994bc5447977a307d9becdcfd15e4b2b90ebb25fc1ad60598095fed46e99c11b467998a68eba29rmfu99crwuimKvL/WXL83l6DElMyuRd8giS2ws161JE=','9786a35009e26536c51921154ec6467023246c912b8fc30479ad49153a782f1597910a15db0344bfa5e2f9e37eb7f7e4ca0a05d40450ab1d212cef46efad025aYJr1jAuQS2lv7Kn6HvC+HOYL5J988dq9PRvDVSIUhn4=',2,10,0,0.00,0,null,0.00,0.00,0,0.00,null,null,0,192116161232),(2,'5340abcade0288f5449eb3950a7e00f291bc1e7972df63b9eb5f739cb7480ad66abc4e88ea377971fdfd08bde4362aa2838c8bcc72a04b2cf35b3d040bd9d6cdQQFfbQq0Y8SF5m5bdAzESTD+MdRNX9UkVcz5ukuAI+S4BfuY6xR91Da6A2xJkoWq','acfdfe1a579c93b5e988ed39509280a18b820170d476f38d6de7f4e09d1124b248a89b7be040863b1ab828d2d4f26b6af454cf5d8b9f2854071d8d09c3f8b1fd5CQbal307Nx/kFn6HzzAQhVVa/hwPQXpM6J3xFKS/LEPT7hiGWyRtpuO/utkkjtx','3c90e4c335d794f445a97ccf9af6c8a12f3ecbf263aa04c3a2b552b9284c22309266a4d53d2b7ca2d445b17b310c8dfc6e3353cbb0c201cbbfb86ae1c118d2ee5qw/swhzk3pAjLa7mFPkknKyduT0Iw0T9O771674efA=','7637821d5fb949b39554ac4afa6ae0f93941dfa2c21f729a6ca9ddbd89e395dfd388d481a7f184746dd57a22cf7af5d173ac8e4e80d96d8c60b1281ef39020a2E4aQF7TYwcuKOAXv09bNzOrdQIxjamXUG7S0CqJG08w=','1f61861c984108b0ea742879deb6ec99b01d60c3eb69660f6a9ad72261995b87f57d6f6867139c5d439b6e5704cf7889f85316d61578ab7d905ee0d42c2df3f2yWKu9AHMQgLgkcaepvylhwrwDrpZhokK17fuRFCbTNE=',1,null,1,10000.00,0,null,0.00,0.00,0,0.00,5101000,null,0,192116161210),(3,'daf871165a8d3cc2ac7ec318f576e0338cb7e0aada3e53799d32ec5bef07977d5b82ab4c697d21d9935ae9e014515971e1387b5ec76f1981186486a1ba3a4542bZ9m53BRhwlukNSvgRoe4Sd4bNlTDTadlSXbifj5X8XoWG4ijgn8WQDJLdC9U3JM','1cd4806e3c05b469450893a7c35bf42705168a744dc136f40e426ad9de8278b75f70887bb902bec4073023182308ac73caaec3aedefecaafe282ab940da2b4ad+4yPiiIOkyHNYqVwDdubKssCIzNTsgDYEpo70I16E7QExkD14CUtybxuQqL7p8hs','48ad4572f7ee710bde0ea8c65b4d996c784fd6844823fdd09f85aacdfe2314176f9cf2c561434923d83159e497d7f0323330ae863f734e0c43e7eff525a0130aLqnhjt3KkbQ2pp5kdmzw6+PuxK/2vN2lm9DG0DUKPkM=','50c445a409e1f743f41f5f05c5edb9d308d9954a4801cb8dec6c39e382c8db7d3c9526d9c356a15d431b451f1489f0801a61cf8d90aa2a7f420cc5b6e0bc867agEC3LZOOrkoJw5DYTTQcf9N9TMmNX8AUZTyeYGPT/kw=','393af625441a8ee98ecb10b5c1b5a274c64d0f7867d2e6d56210979cb0d93b59bfc9fd726d99848e3f6b0e8d8d2edf7300e9cdae703c89faab87c91857ed270c3st9du5OAPfV/bXl3JouP2Czpw3cEqgBBEQWZ34Fdc4=',1,null,0,0.00,1,1,12.00,0.00,0,0.00,null,null,0,192116161250),(4,'30bf667f37286b88fbc98a7f1a9dd5c2b396ed9465d0d109b93d5ea6decd7ce9f1e0c25a590bfcffdecadaee533db18cf45d12df42ef8562288e49ad1c71d4378GE3IcC5PrLTlVfcjdnzaPv5mPwj3FMrjVvCiFdOodQTsbMyGrJQTuBhub8I2p/5','108a8eab6350a4744159c3dc82f35e652ee1e594d57fc70e2232be437542fb303eb159e8b1f72824812c6aa5be0f8edbd15628331f870d7d06b0ab2c9551880f0TapqprU2TU6FmQ54w2jwRPSYW74+kkniYRWyOq0JGqzSu4qYiuxJ60DixXkPExy','e2e4ac65f37630217bb96dff43610d1edbba68c356af5d326005b886122647eec5a5d439d96a0a4fa7f140c0c7a783a57c8ef9d329f445f1345d1cf8414ee64becUi7HqXtWOA1qQMj5iyTAlBfGuTQwoLGA7oagHiTxc=','2ae5f2f787b0979a6244dcb3ef5fabecbbe0bfa5e4d36c13515f222c1712f133e815a40dda4847441b85c3648fdafed797773e842f645d12f3d98ea7d789d7369I5Q+dzrxQ4bMaImuqA6oC7+We3VJ0kADJvn6M2yMeE=','7a0b439b06f6753098de7123f98c871803ca1c1e488c7f92740bd67bcb42fcb7e92dbbf29a803703237207eaf15c337e12d28184efa1da2f30d0f937fdedf2ddyMJyUAfIPKj0gW2eAEvNkojbD8mhPuQczthVNCV8h6Y=',1,null,0,0.00,1,1,10.00,0.00,0,0.00,5101000,null,0,192116161217),(5,'c6c0a35070a421618b10feeb0149e2b550dd401303f70e1f2ed7f691857ca59b365d0941bed6fa246c8a2b6803fd3cee357f4b9a2e5a19da6d13e1578da564a2GQJqwom3sgAIYaVSGUc94TGx29Or8yLkIZu0N+Nk6p0N6jw3irm2nm9liKGVzuxx','85f5fc3deb3bd9ec0c591327eb5db72ebf7ff0ae5d916dd0f95b8ebb34b17f22380fda34d82a88c31c2c729de50f0a561fbec21e3fbb88a89343e1be4cbb69b4/bx4y0eM6n44hITUpXAFAmFnkxCV/MlK7/8kdsvrWTibKzKU61DzhRzxqMfAdsw7','d8652ee242480eb5f4cde9dcbc6339dc874c42bb9ccad4e05afc329af66578054556243ada6b0e422085d2000b324096d8dc3d4eb4c60a6a8144ba5cb184e992pAnSxook0T7y3oI/KHoTXNthx/+hwZS4P7TQOQiTgdA=','6bbfeb6c3373a5019e566ff3b055a20ac410069699cc2416e2ea6783ab6761dd671e2a177301843e766fe1c02613f4d3db563bac06b2dcc2689345774ad1bfdewTKjVEjLqttiQIybhdoOdPzC+rkqR8pG9NdMPkfpSiA=','7fbfe40e08a69112b59f06d932d8cb118ddbb036d8609beb6ae0aedc37ab124f3e6f34cdb1b5fec8594579be3972d79badea23305c2df3076e4e349a3e7cdf7eWttvG6DpF6DTuWe3dAX4X6Qrg9SDJMNErKe4bOQqHcs=',1,null,0,0.00,0,null,0.00,10.00,0,0.00,5101000,null,0,192116161290),(6,'5f4f7fee212074a60e55230b09076dab3decc7fd138fd793c4260807faf34ceff7059219e4604f7e7ee575e0d3c6003281b49e042a6be6bb87c0eb7ce53a9d90Hr6pju5GAOKfxB7jY2velSELYj4Ch5CKSqYD8VhP9zPSfHSYj1lGDJScDJjRIgAD','a86f6704d4bb20c81cf560a41e38e2b8dc472c66972870ef262040ef04fa5fbf378e99d286a238fff46652409c3eadcd39ffc3118b8a23b0f9a29ce42baad836GBXGfHV+FeeZqgg7aY8PnzVDmnV7/rZ/WUHg1hLKshXe9cRIfqQkw/QEE18QFwlE','d6d4134f299dda10abc40b35fc37598bc38d44e83cb85e00a5c54638daef9b27d6ff88736bee671271e6ddfa25ab4d5dec5dc7e8652e1dbcbfe9e80deda8d081+8crf3+5/AXz/zXcePMZSu5vyB3aGrfrfzsvJjg5nEE=','5a3a78e8b047f2b7df9d16bc8d4c5366aa4649ccaff13413c2e95eb052df4fc627d904c549d8c4dd7aaa94cf4340dd8d61a39da4c433ec45f400aeb222f9da9d59x/4iGurRDhZKCv1NBHEuZbTlpEP5DdLiocfu7JqqY=','f78faa7b62b2dc99dbe2f877d474c57889208c10dcfd1cf7bc97987ba523e276efbe4cd63c894f895b0db2c33bebbc309b31034e8e77bdc514e6c5e63d3a45e6ycPLMfU+eB5Ab6R3AGWLXMECAQhbfeUWhKzux3UhLmQ=',1,null,0,0.00,0,null,0.00,0.00,1,10.00,5101000,null,0,192116161274),(7,'37386671e8ad9295ee18300e56a150187c50c7cdba7b885ad476f1cef37bfc7f77727102bb5e459d7f88da2c2b07743109e5b6d19ab9a76528443d8e8152f6f0bCX+ZZHsQcOMEDfxcmQZqkjdXoLEm7GpgiddY2UdqoU=','a5ef186fe5ca6adaa3c63f5460157fcf8b5265336d1e3bcc626b287bf80fd75b9d249b730ad15b6d1e0c08e61189368c3dde277c63a237649064680fe122f1f9JkgrEeIUZON8clZIukcqncCAhuApEu7OQOd7A9tllcmduLVfWD26w+j6mNL788Xh',0,'b9a943ea77203399ad9aaad1c8d0cd86af310454398cd917c80c7e47c508482abc973403d3c27846e0bccccdd12299026a9982c64a64d02651e7f584c35917d2zMRcpVmVkv5X1nyKCOoqkLsGmpuWs3LtOuiLNFrhRUo=',0,1,null,0,0.00,0,null,0.00,0.00,0,0.00,null,null,0,190113161217),(8,'4c10ced41a0ff8da7a4220eed7968f9aea1fc45cb9b17f41ec4ac78a6c19ecc334e4e2f59c300deb18c8f2d499907b482e1cb26725df0fa0cdb03d5043151cd8X1Fadzz11kOV7BR6MtAIDETHMf0CvoWumqPjdw2Zj4hJbA2Ls0IIwUPUW2aUgIuNkDbLJcTNQBS3JWf2T2gBlVUbKGsxmFB/PKgka5d9j0M=','ba55348cf2a2b61dc594e7e636133d23da95d0e800175727a0ed0524cb3ef1d37bd9cd611c52e5a4ccdb09a18f6901bb5750c0132136ba414628c8d0d74d2344yAT+qXILkwc0orAtPvr8cHmE1iFVs4sesNU+FgTPUVPj3HBG79J471cCsbvdu1T0','fbf6e2a8a0a6614cf55d01954e0b2190ad09ad612fb9fd7b0957c037db552e0a7ef869630f28522b735042eed15ccf1cc2684d0482c96d30e5b7e9966e6ff264/QZJHzDPlxHrYaSM+i43FkT3gey9qUsz5PkolaShxsk=','07cf26069233973674d1b32eff3a0b905cb8f402938389caae37b343f768d69665eca8391d32f5bcb52269b2529bfbe82e466a232ce1c344653d18d0ff059574h/ws7Y1/StI9+Y3uWmQlzEc/OqO/79hud1npYNu78CTRuHqC7edSfAqFw1id2mdZL3M7Q4CMH9f9eA/QVQOS7g==','5e2a3c49c1de08354948e8dcd588a3ff3af04dc74d7f048bdea0687dd9d43b81eed015458a07e2e1a95d698086f2b71dd22b79374103172b2ee7387a597cd74ciNlqtu3tqzW5Z2s56pcM9gVyVvI4eUElXs0VNP3z1xU=',2,45,0,0.00,1,1,12.00,1.00,1,1.00,null,null,0,040122011560);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `supplieraffiliate` WRITE;:||:Separator:||:
 INSERT INTO `supplieraffiliate` VALUES(4,2,2,1),(5,2,5,1),(6,2,4,1),(10,4,2,1),(11,4,5,1),(12,4,4,1),(13,5,2,1),(14,5,5,1),(15,5,4,1),(16,6,2,1),(17,6,5,1),(18,6,4,1),(19,1,2,1),(20,1,5,1),(21,1,4,1),(22,7,6,1),(23,7,8,1),(24,7,2,1),(25,7,5,1),(26,7,4,1),(27,8,9,1),(28,3,2,1),(29,3,5,1),(30,3,4,1);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `supplieritems` WRITE;:||:Separator:||:
 INSERT INTO `supplieritems` VALUES(1,7,4),(2,7,0);:||:Separator:||:


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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;:||:Separator:||:


LOCK TABLES `unit` WRITE;:||:Separator:||:
 INSERT INTO `unit` VALUES(1,'ml','Milliliter',0),(2,'m','Meter',0),(3,'l','Liter',0),(4,'Pc','Pc',1),(5,'kl','Kilos',1),(6,'ciu','classified item\'s un',0),(7,'unit','unit',0);:||:Separator:||:


:||:Separator:||:
UNLOCK TABLES;
:||:Separator:||:
