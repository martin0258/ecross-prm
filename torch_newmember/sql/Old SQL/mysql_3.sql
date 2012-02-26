-- phpMyAdmin SQL Dump
-- version 2.10.3
-- http://www.phpmyadmin.net
-- 
-- 主機: localhost
-- 建立日期: Mar 13, 2010, 09:43 AM
-- 伺服器版本: 5.0.51
-- PHP 版本: 5.2.6

-- 
-- 資料庫: `torch_`
-- 

-- --------------------------------------------------------
CREATE TABLE torch_SpecialtyLists (
  SpecialtyID INTEGER UNSIGNED  NOT NULL AUTO_INCREMENT COMMENT '專長編號',
  SpecialtyItem VARCHAR(20)  NOT NULL  COMMENT '專長項目',
PRIMARY KEY(SpecialtyID))ENGINE = MyISAM;

CREATE TABLE torch_ServiceLists (
  ServiceID INTEGER UNSIGNED  NOT NULL   AUTO_INCREMENT COMMENT '事工編號',
  ServiceItem VARCHAR(20)  NOT NULL COMMENT '事工項目',
PRIMARY KEY(ServiceID))ENGINE = MyISAM;

-- torch_AccountLists 暫不使用
CREATE TABLE torch_AccoutLists (
  IDNumber VARCHAR(20)  NOT NULL COMMENT '身份證字號',
  Email VARCHAR(30)  NOT NULL COMMENT 'E-mail',
  Passward VARCHAR(16)  NOT NULL COMMENT '密碼',
  IsIDVerified ENUM('是','否')  NULL DEFAULT '否' COMMENT '是否開通',
PRIMARY KEY(IDNumber))ENGINE = MyISAM;

CREATE TABLE torch_GroupLists (
  GroupID INTEGER(10) UNSIGNED  NOT NULL   AUTO_INCREMENT  COMMENT '小組編號',
  GroupLeaderName VARCHAR(20)  NULL  COMMENT '小組長姓名',
  GroupName VARCHAR(20)  NULL COMMENT '小組名稱',
  GroupCategory ENUM('國中','高中','大專','社青','成人')  NOT NULL COMMENT '小組類別',
  GroupLeaderMail VARCHAR(40)  NULL COMMENT '小組長 E-mail',
PRIMARY KEY(GroupID))ENGINE = MyISAM;


CREATE TABLE torch_MemberInformation (
  MemberID INTEGER(10) UNSIGNED  NOT NULL   AUTO_INCREMENT COMMENT '會友編號',
  IDNumber VARCHAR(20)  NULL  COMMENT '身分證字號',
  GroupLists_GroupID INTEGER(10) UNSIGNED  NULL  COMMENT '小組編號',
  ChineseName VARCHAR(20)  NULL COMMENT '中文姓名',
  EnglishName VARCHAR(45)  NULL COMMENT '英文姓名',
  Introducer VARCHAR(45)  NULL COMMENT '邀請人',
  IntroducerPhoneNumber VARCHAR(20)  NULL COMMENT '邀請人電話',
  Sex ENUM('男','女','不詳')  NOT NULL COMMENT '性別',
  Birthday DATE  NULL COMMENT '生日',
  Marriage ENUM('單身','已婚','其他', '不詳')  NOT NULL COMMENT '婚姻狀況',
  CellPhoneNumber VARCHAR(20)  NULL COMMENT '手機',
  HomePhoneNumber VARCHAR(20)  NULL COMMENT '家用電話',
  MailingAddress_ZipCode VARCHAR(10)  NULL COMMENT '郵遞區號',
  MailingAddress_Nationality VARCHAR(20)  NULL COMMENT '通訊國家',
  MailingAddress_Country VARCHAR(6)  NULL COMMENT '通訊地址 - 縣市',
  MailingAddress_Township VARCHAR(10)  NULL COMMENT '通訊地址 - 鄉鎮市區',
  MailingAddress_Detail VARCHAR(116)  NULL COMMENT '通訊地址 - 明細',
  Email VARCHAR(40)  NULL COMMENT 'E-mail',
  IM VARCHAR(40)  NULL COMMENT '即時通訊',
  FirstVisitDate DATE  NULL COMMENT '第一次來教會日期',
  Source ENUM('其他','主日','特會')  NOT NULL COMMENT '名單來源',
  BeliefStatus ENUM('訪客','慕道友','基督徒')  NOT NULL COMMENT '身份別 (信仰狀況)',
  BelongedChurch VARCHAR(20)  NULL COMMENT '所屬教會',
  BaptismDate DATE  NULL COMMENT '受洗日期',
  PictureSavingPath VARCHAR(255)  NULL COMMENT '照片儲存路徑',
  AuthorityStatus VARCHAR(10)  NULL COMMENT '權限身份',
  Job VARCHAR(255)  NULL COMMENT '工作/學校',
  Stability ENUM('穩定','不穩定','訪客/外教會','不會再來','其他')  Not NULL COMMENT '是否穩定聚會',
  Note TEXT  NULL COMMENT '備註',
  GroupID_TEMP INTEGER(10) UNSIGNED  NULL  COMMENT '小組編號 (暫存)',
PRIMARY KEY(MemberID)  ,
INDEX MemberInformation_FKIndex1(GroupLists_GroupID),
  FOREIGN KEY(GroupLists_GroupID)
    REFERENCES torch_GroupLists(GroupID)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
)ENGINE = MyISAM;

CREATE TABLE torch_MemberTempGroup (
  MemberInformation_MemberID INTEGER(10) UNSIGNED  NOT NULL COMMENT '會友編號',
  GroupLists_GroupID INTEGER(10)  NULL  COMMENT '小組編號',
INDEX MemberContact_FKIndex1(MemberInformation_MemberID),
  FOREIGN KEY(MemberInformation_MemberID)
    REFERENCES torch_MemberInformation(MemberID)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
)ENGINE = MyISAM;

CREATE TABLE torch_MemberContact (
  MemberInformation_MemberID INTEGER(10) UNSIGNED  NOT NULL COMMENT '會友編號',
  ContactNumber INTEGER UNSIGNED  NOT NULL COMMENT '訪談次數',
INDEX MemberContact_FKIndex1(MemberInformation_MemberID),
  FOREIGN KEY(MemberInformation_MemberID)
    REFERENCES torch_MemberInformation(MemberID)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
)ENGINE = MyISAM;
	  
CREATE TABLE torch_MemberService (
  MemberInformation_MemberID INTEGER(10) UNSIGNED  NOT NULL COMMENT '會友編號',
  ServiceLists_ServiceID INTEGER UNSIGNED  NOT NULL COMMENT '事工編號',
INDEX MemberService_FKIndex1(MemberInformation_MemberID),
  FOREIGN KEY(MemberInformation_MemberID)
    REFERENCES torch_MemberInformation(MemberID)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(ServiceLists_ServiceID)
    REFERENCES torch_ServiceLists(ServiceID)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
)ENGINE = MyISAM;

CREATE TABLE torch_MemberSpecialty (
  MemberInformation_MemberID INTEGER(10) UNSIGNED  NOT NULL COMMENT '會友編號',
  SpecialtyLists_SpecialtyID INTEGER UNSIGNED  NOT NULL  COMMENT '專長編號',
INDEX MemberSpecialty_FKIndex2(MemberInformation_MemberID),
  FOREIGN KEY(SpecialtyLists_SpecialtyID)
    REFERENCES torch_SpecialtyLists(SpecialtyID)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(MemberInformation_MemberID)
    REFERENCES torch_MemberInformation(MemberID)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
)ENGINE = MyISAM;

CREATE TABLE torch_PastoralRecords (
  RecordSerial int  NOT NULL   AUTO_INCREMENT COMMENT '訪談序號',
  RecordTime DATETIME  NOT NULL COMMENT '日期+時間',
  MemberInformation_MemberID INTEGER(10) UNSIGNED  NOT NULL COMMENT '會友編號',
  Carer VARCHAR(20) NULL COMMENT '聯繫人員',
  RecentSituation TEXT  NOT NULL COMMENT '最近狀況',
PRIMARY KEY(RecordSerial)  ,
INDEX PastoralRecords_FKIndex1(MemberInformation_MemberID),
  FOREIGN KEY(MemberInformation_MemberID)
    REFERENCES torch_MemberInformation(MemberID)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
)ENGINE = MyISAM;
  
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '吳永成' , '迦勒小組', '社青', 'danielwu@via.com.tw');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '蘇文奕' , '真男人小組', '社青', 'eanwen@yahoo.com.tw');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '王銘潔' , '火戰車小組', '社青', 'wmj07@yahoo.com.tw');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '李冠利' , '火把霹靂火小組', '社青', 'guanliyuli@gmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '周祐平' , '火力旺小組', '社青', 'heymanchou@gmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '林殷立' , '電火球小組', '社青', 'ff6393@gmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '劉小芸' , '火把嬌娃小組', '社青', 'liuanna0205@gmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '史凱菱' , '火把向日葵小組', '社青', 'dinoks421@hotmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '徐蕙華' , '火把火花小組', '社青', 'kisshelen_531@yahoo.com.tw');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '黃心苓' , '火把LAVA小組', '社青', 'erin527hsl@gmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '王心怡' , '火把神蹟小組', '社青', 'maiiwang651001@gmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '徐禕祺' , '烈火瑞瑪小組', '社青', 'sharon.hsu@wpi-group.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '柯綉玲' , '火把奇異火小組 ', '社青', 'vitake_0123@yahoo.com.tw');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '廖方瑜' , '火寶貝小組', '社青', 'stella81@mail.7-11.com.tw');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '楊秀琴' , '火熱小組', '社青', 'naome220@yahoo.com.tw');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '葉怡妏' , '火把ZAIA小組', '社青', 'wenwen0907@gmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '蘇凱恩' , '火藥小組', '社青', 'sukaien@gmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '劉秋良' , '火太陽小組', '社青', 'lcl9889@yahoo.com.tw');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '劉翠琴' , '火柱小組', '社青', 'eliutpe@yahoo.com.tw');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '戴承儀' , '火力國中小組', '國中', 'diana-lgl@yahoo.com.tw');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '辛杰恩' , '火種國中小組', '國中', 'peter1212128@hotmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '蔡惠宇' , '火柴小組', '國中', 'sabrina_elsa12520@hotmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '江韶恩' , '火把高中小組', '高中', 'skiang22@gmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '王子銘' , '鮮焱小組', '高中', 'tayalgary@gmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '李睿哲' , '動力火小組', '大專', 'abrahamlee007@hotmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '王子銘' , '火大小組', '大專', 'tayalgary@gmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '黃欣蓉' , '火山口小組', '大專', 'sebrinahuang61579@gmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '尚至義' , '疾風烈火小組', '大專', 'secrecy1024@gmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '葉晏翠' , '火星人小組', '大專', 'jesus_islove@hotmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '卡伊‧馬賴' , 'J-Power小組', '大專', 'haleluyadavid@hotmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '賴春華' , '信望愛小組', '成人', 'rebecca99530@hotmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '吳永成、林美玲' , '但以理小組', '成人', 'danielwu@via.com.tw');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '林志勤、賴春華' , '活力成人小組', '成人', 'bowbeilin@gmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '鍾蘭英' , '閃亮小組', '成人', ' ling4045@gmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '陳竹平、陳惠貞' , '週日成人小組', '成人', 'chen_johnson1@yahoo.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '張光中、葛璐嘉' , '火光小組', '成人', 'ch.smallj@msa.hinet.net');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '劉精中、侯麗婷' , '火焰小組', '成人', 'liu@astrocorp.com.tw');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '詹富閔、葉香麟' , '火把愛家倍小組', '成人', 'inspykimo@yahoo.com.tw');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '李冠利、王心怡' , '甜蜜蜜小組', '成人', 'guanliyuli@gmail.com');
INSERT INTO `torch_grouplists` (`GroupID` ,`GroupLeaderName` ,`GroupName` ,`GroupCategory` ,`GroupLeaderMail`) VALUES (NULL , '郭曾麗霞' , '哈拿小組', '成人', 'hsia2933@gmail.com');
