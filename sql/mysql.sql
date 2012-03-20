-- 新人名單4.1資料庫DDL
-- 建立日期: 2011/9/24
-- 修改日期: 2012/03/01 Change table name to lower case
-- 作者: Torch
-- 
-- 資料庫: `torch_`
-- 
-- --------------------------------------------------------

CREATE TABLE torch_pastoral_area (
  PastoralAreaID INTEGER(10)   NOT NULL COMMENT '牧區編號',
  PastoralAreaName VARCHAR(20)  COMMENT '牧區名稱',
  LeaderID MEDIUMINT(8) COMMENT '牧區長ID'  ,
PRIMARY KEY(PastoralAreaID));

CREATE TABLE torch_specialty_lists (
  SpecialtyID INTEGER(10) UNSIGNED  NOT NULL AUTO_INCREMENT COMMENT '專長編號',
  SpecialtyItem VARCHAR(20)  NOT NULL  COMMENT '專長項目',
PRIMARY KEY(SpecialtyID)
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE torch_service_lists (
  ServiceID INTEGER(10) UNSIGNED  NOT NULL AUTO_INCREMENT COMMENT '事工編號',
  ServiceItem VARCHAR(20)  NOT NULL COMMENT '事工項目',
PRIMARY KEY(ServiceID)
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE torch_group_lists (
  GroupID INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '小組編號',
  GroupLeaderName VARCHAR(20)  NULL  COMMENT '小組長姓名',
  GroupName VARCHAR(20)  NULL COMMENT '小組名稱',
  GroupCategory VARCHAR(20)  NOT NULL COMMENT '牧區',
  GroupLeaderMail VARCHAR(40)  NULL COMMENT '小組長E-mail',
  ViceLeaderMail VARCHAR(120)  NULL COMMENT '預備領袖E-mail',
  Active_Flag tinyint(1) NOT NULL DEFAULT '1' COMMENT '小組存在與否',  
PRIMARY KEY(GroupID)
)ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE torch_member_information (
  MemberID INTEGER(10) UNSIGNED  NOT NULL AUTO_INCREMENT COMMENT '會友編號',
  IDNumber VARCHAR(20)  NULL  COMMENT '身分證字號',
  GroupLists_GroupID INTEGER(10) UNSIGNED NULL  COMMENT '小組編號',
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
  Source ENUM('其他','主日','特會', '禱告會')  NOT NULL COMMENT '名單來源',
  BeliefStatus ENUM('已有其他固定教會基督徒','無其他固定教會基督徒','未受洗非基督徒','其他', '訪客','慕道友','基督徒')  NOT NULL COMMENT '身份別 (信仰狀況)',
  BelongedChurch VARCHAR(20)  NULL COMMENT '所屬教會',
  BaptismDate DATE  NULL COMMENT '受洗日期',
  PictureSavingPath VARCHAR(255)  NULL COMMENT '照片儲存路徑',
  AuthorityStatus VARCHAR(10)  NULL COMMENT '權限身份',
  Job VARCHAR(255)  NULL COMMENT '工作/學校',
  Stability ENUM('穩定','不穩定','訪客/外教會','不會再來','其他')  Not NULL COMMENT '是否穩定聚會',
  Note TEXT  NULL COMMENT '備註',
  GroupID_TEMP INTEGER(10) UNSIGNED NULL  COMMENT '小組編號 (暫存)',
  SpouseName VARCHAR(20)  NULL   COMMENT '配偶姓名',
  HowToKnowTorch VARCHAR(50)  NULL   COMMENT '如何得知火把',
  HowToKnowTorch_Memo VARCHAR(300)  NULL   COMMENT '如何得知火把(其他)',
  Introducer_FamilyName VARCHAR(45)  NULL   COMMENT '邀請人(家人)姓名',
  Introducer_FamilyPhone VARCHAR(20)  NULL   COMMENT '邀請人(家人)電話',
  LikeTorchReason VARCHAR(50)  NULL   COMMENT '喜歡火把原因 ',
  TorchImprovement VARCHAR(50)  NULL   COMMENT '火把待改進',
  NeedService VARCHAR(50)  NULL   COMMENT '需要的服務',
  Intercession  VARCHAR(300)  NULL   COMMENT '代禱事項',
  BeliefStatus_Memo VARCHAR(20)  NULL   COMMENT '身份別(其他)',
  MailingAddress_Country_Code VARCHAR(10)  NULL   COMMENT '通訊地址 - 縣市(代碼)',
  MailingAddress_Township_Code VARCHAR(3)  NULL   COMMENT '通訊地址 - 鄉鎮市區(代碼)',
  Create_Timestamp DATETIME  NULL   COMMENT '建立時間',
  Update_Timestamp DATETIME  NULL   COMMENT '修改時間',
  Create_ID VARCHAR(45)  NULL   COMMENT '建立者',
  Update_ID VARCHAR(45)  NULL   COMMENT '修改者',
  LikeTorchReasonMemo VARCHAR(300)  NULL   COMMENT '喜歡火把原因(其他)',
  TorchImprovementMemo VARCHAR(300)  NULL   COMMENT '火把待改進(其他)',
  ComeWithSpouse ENUM('Y','N')  NULL   COMMENT '是否和配偶一起前來',
  SchoolYear VARCHAR(255)  NULL   COMMENT '學校年級',
  CompanyPhoneNumber VARCHAR(20)  NULL   COMMENT '公司電話',
  AgeIntervalCode VARCHAR(20)  NULL     COMMENT '年齡區間代碼',
  PRIMARY KEY(MemberID),
  INDEX MemberInformation_FKIndex1(GroupLists_GroupID),
  INDEX MemberInformation_FKIndex2(GroupID_TEMP),
  FOREIGN KEY(GroupLists_GroupID) 
  REFERENCES torch_group_lists(GroupID)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
  FOREIGN KEY(GroupID_TEMP)
  REFERENCES torch_group_lists(GroupID)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE torch_member_service (
  MemberInformation_MemberID INTEGER(10) UNSIGNED  NOT NULL COMMENT '會友編號',
  ServiceLists_ServiceID INTEGER(10) UNSIGNED  NOT NULL COMMENT '事工編號',
INDEX MemberService_FKIndex1(MemberInformation_MemberID),
  FOREIGN KEY(MemberInformation_MemberID)
    REFERENCES torch_member_information(MemberID)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(ServiceLists_ServiceID)
    REFERENCES torch_service_lists(ServiceID)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE torch_member_specialty (
  MemberInformation_MemberID INTEGER(10) UNSIGNED  NOT NULL COMMENT '會友編號',
  SpecialtyLists_SpecialtyID INTEGER(10) UNSIGNED  NOT NULL  COMMENT '專長編號',
INDEX MemberSpecialty_FKIndex2(MemberInformation_MemberID),
  FOREIGN KEY(SpecialtyLists_SpecialtyID)
    REFERENCES torch_specialty_lists(SpecialtyID)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(MemberInformation_MemberID)
    REFERENCES torch_member_information(MemberID)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE torch_pastoral_records (
  RecordSerial int  NOT NULL   AUTO_INCREMENT COMMENT '訪談序號',
  RecordTime DATETIME  NOT NULL COMMENT '日期+時間',
  MemberInformation_MemberID INTEGER(10) UNSIGNED  NOT NULL COMMENT '會友編號',
  Carer VARCHAR(20) NULL COMMENT '聯繫人員',
  RecentSituation TEXT  NOT NULL COMMENT '最近狀況',
PRIMARY KEY(RecordSerial)  ,
INDEX PastoralRecords_FKIndex1(MemberInformation_MemberID),
  FOREIGN KEY(MemberInformation_MemberID)
    REFERENCES torch_member_information(MemberID)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE torch_system_variable (
  VariableName VARCHAR(50)  NOT NULL COMMENT '變數名稱',
  Value VARCHAR(200)  NULL COMMENT '變數值',
PRIMARY KEY(VariableName)
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE torch_code (
  CODE_KIND VARCHAR(20)  NOT NULL COMMENT '編碼種類' ,
  CODE_ID VARCHAR(10)  NOT NULL COMMENT '編碼ID' ,
  CODE_KIND_NAME VARCHAR(50)  NULL COMMENT '編碼種類名稱' ,
  CODE_NAME VARCHAR(50)  NULL  COMMENT '編碼名稱'  ,
  PRIMARY KEY(CODE_KIND, CODE_ID)
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- Insert the codes will be used in program
INSERT INTO `torch_code` (`CODE_KIND`, `CODE_ID`, `CODE_KIND_NAME`, `CODE_NAME`) VALUES
('Intermedium', 'I1', '得知火把', '朋友'),
('Intermedium', 'I2', '得知火把', '家人'),
('Intermedium', 'I3', '得知火把', '傳播媒介'),
('Intermedium', 'I301', '得知火把', '火把網站'),
('Intermedium', 'I302', '得知火把', 'FACEBOOK'),
('Intermedium', 'I303', '得知火把', 'TORCH雜誌'),
('Intermedium', 'I304', '得知火把', 'DM'),
('Intermedium', 'I305', '得知火把', '電視媒體'),
('Intermedium', 'I4', '得知火把', '其他'),
('AgeInterval', 'A1', '年齡區間', '0~12'),
('AgeInterval', 'A2', '年齡區間', '13~17'),
('AgeInterval', 'A3', '年齡區間', '18~22'),
('AgeInterval', 'A4', '年齡區間', '23~30'),
('AgeInterval', 'A5', '年齡區間', '31~40'),
('AgeInterval', 'A6', '年齡區間', '41~50'),
('AgeInterval', 'A7', '年齡區間', '51~60'),
('AgeInterval', 'A8', '年齡區間', '60以上'),
('Reason', 'R1', '喜歡原因、待改進原因', '熱情招待'),
('Reason', 'R2', '喜歡原因、待改進原因', '餐點'),
('Reason', 'R3', '喜歡原因、待改進原因', '會場佈置'),
('Reason', 'R4', '喜歡原因、待改進原因', '唱詩氣氛'),
('Reason', 'R5', '喜歡原因、待改進原因', '節目呈現'),
('Reason', 'R6', '喜歡原因、待改進原因', '牧師信息'),
('Reason', 'R7', '喜歡原因、待改進原因', '禱告時刻'),
('Reason', 'R8', '喜歡原因、待改進原因', '其他'),
('Service', 'S1', '提供服務', '祝福禱告'),
('Service', 'S2', '提供服務', '參加小組'),
('Service', 'S3', '提供服務', '寄送電子報'),
('Service', 'S4', '提供服務', '至家裡或公司探訪新會友'),
('Service', 'S5', '提供服務', '代禱'),
('ZIP_Country', 'ZIP_TP', '郵遞區號-縣市', '臺北市'),
('ZIP_Country', 'ZIP_KL', '郵遞區號-縣市', '基隆市'),
('ZIP_Country', 'ZIP_SP', '郵遞區號-縣市', '新北市'),
('ZIP_Country', 'ZIP_EL', '郵遞區號-縣市', '宜蘭縣'),
('ZIP_Country', 'ZIP_SC', '郵遞區號-縣市', '新竹縣市'),
('ZIP_Country', 'ZIP_TY', '郵遞區號-縣市', '桃園縣'),
('ZIP_Country', 'ZIP_ML', '郵遞區號-縣市', '苗栗縣'),
('ZIP_Country', 'ZIP_TC', '郵遞區號-縣市', '臺中市'),
('ZIP_Country', 'ZIP_CH', '郵遞區號-縣市', '彰化縣'),
('ZIP_Country', 'ZIP_NT', '郵遞區號-縣市', '南投縣'),
('ZIP_Country', 'ZIP_CY', '郵遞區號-縣市', '嘉義縣市'),
('ZIP_Country', 'ZIP_YL', '郵遞區號-縣市', '雲林縣'),
('ZIP_Country', 'ZIP_TN', '郵遞區號-縣市', '臺南市'),
('ZIP_Country', 'ZIP_KS', '郵遞區號-縣市', '高雄市'),
('ZIP_Country', 'ZIP_NL', '郵遞區號-縣市', '南海諸島'),
('ZIP_Country', 'ZIP_PH', '郵遞區號-縣市', '澎湖縣'),
('ZIP_Country', 'ZIP_PD', '郵遞區號-縣市', '屏東縣'),
('ZIP_Country', 'ZIP_TD', '郵遞區號-縣市', '臺東縣'),
('ZIP_Country', 'ZIP_HL', '郵遞區號-縣市', '花蓮縣'),
('ZIP_Country', 'ZIP_KM', '郵遞區號-縣市', '金門縣'),
('ZIP_Country', 'ZIP_LC', '郵遞區號-縣市', '連江縣'),
('ZIP_TP', '100', '臺北市', '中正區'),
('ZIP_TP', '103', '臺北市', '大同區'),
('ZIP_TP', '104', '臺北市', '中山區'),
('ZIP_TP', '105', '臺北市', '松山區'),
('ZIP_TP', '106', '臺北市', '大安區'),
('ZIP_TP', '108', '臺北市', '萬華區'),
('ZIP_TP', '110', '臺北市', '信義區'),
('ZIP_TP', '111', '臺北市', '士林區'),
('ZIP_TP', '112', '臺北市', '北投區'),
('ZIP_TP', '114', '臺北市', '內湖區'),
('ZIP_TP', '115', '臺北市', '南港區'),
('ZIP_TP', '116', '臺北市', '文山區'),
('ZIP_KL', '200', '基隆市', '仁愛區'),
('ZIP_KL', '201', '基隆市', '信義區'),
('ZIP_KL', '202', '基隆市', '中正區'),
('ZIP_KL', '203', '基隆市', '中山區'),
('ZIP_KL', '204', '基隆市', '安樂區'),
('ZIP_KL', '205', '基隆市', '暖暖區'),
('ZIP_KL', '206', '基隆市', '七堵區'),
('ZIP_SP', '207', '新北市', '萬里區'),
('ZIP_SP', '208', '新北市', '金山區'),
('ZIP_SP', '220', '新北市', '板橋區'),
('ZIP_SP', '221', '新北市', '汐止區'),
('ZIP_SP', '222', '新北市', '深坑區'),
('ZIP_SP', '223', '新北市', '石碇區'),
('ZIP_SP', '224', '新北市', '瑞芳區'),
('ZIP_SP', '226', '新北市', '平溪區'),
('ZIP_SP', '227', '新北市', '雙溪區'),
('ZIP_SP', '228', '新北市', '貢寮區'),
('ZIP_SP', '231', '新北市', '新店區'),
('ZIP_SP', '232', '新北市', '坪林區'),
('ZIP_SP', '233', '新北市', '烏來區'),
('ZIP_SP', '234', '新北市', '永和區'),
('ZIP_SP', '235', '新北市', '中和區'),
('ZIP_SP', '236', '新北市', '土城區'),
('ZIP_SP', '237', '新北市', '三峽區'),
('ZIP_SP', '238', '新北市', '樹林區'),
('ZIP_SP', '239', '新北市', '鶯歌區'),
('ZIP_SP', '241', '新北市', '三重區'),
('ZIP_SP', '242', '新北市', '新莊區'),
('ZIP_SP', '243', '新北市', '泰山區'),
('ZIP_SP', '244', '新北市', '林口區'),
('ZIP_SP', '247', '新北市', '蘆洲區'),
('ZIP_SP', '248', '新北市', '五股區'),
('ZIP_SP', '249', '新北市', '八里區'),
('ZIP_SP', '251', '新北市', '淡水區'),
('ZIP_SP', '252', '新北市', '三芝區'),
('ZIP_SP', '253', '新北市', '石門區'),
('ZIP_EL', '260', '宜蘭縣', '宜   蘭'),
('ZIP_EL', '261', '宜蘭縣', '頭   城'),
('ZIP_EL', '262', '宜蘭縣', '礁   溪'),
('ZIP_EL', '263', '宜蘭縣', '壯   圍'),
('ZIP_EL', '264', '宜蘭縣', '員   山'),
('ZIP_EL', '265', '宜蘭縣', '羅   東'),
('ZIP_EL', '266', '宜蘭縣', '三   星'),
('ZIP_EL', '267', '宜蘭縣', '大   同'),
('ZIP_EL', '268', '宜蘭縣', '五   結'),
('ZIP_EL', '269', '宜蘭縣', '冬   山'),
('ZIP_EL', '270', '宜蘭縣', '蘇   澳'),
('ZIP_EL', '272', '宜蘭縣', '南   澳'),
('ZIP_EL', '290', '宜蘭縣', '釣魚台列嶼'),
('ZIP_SC', '300', '新竹縣市', '新竹市'),
('ZIP_SC', '302', '新竹縣市', '竹   北'),
('ZIP_SC', '303', '新竹縣市', '湖   口'),
('ZIP_SC', '304', '新竹縣市', '新   豐'),
('ZIP_SC', '305', '新竹縣市', '新   埔'),
('ZIP_SC', '306', '新竹縣市', '關   西'),
('ZIP_SC', '307', '新竹縣市', '芎   林'),
('ZIP_SC', '308', '新竹縣市', '寶   山'),
('ZIP_SC', '310', '新竹縣市', '竹   東'),
('ZIP_SC', '311', '新竹縣市', '五   峰'),
('ZIP_SC', '312', '新竹縣市', '橫   山'),
('ZIP_SC', '313', '新竹縣市', '尖   石'),
('ZIP_SC', '314', '新竹縣市', '北   埔'),
('ZIP_SC', '315', '新竹縣市', '峨   眉'),
('ZIP_TY', '320', '桃園縣', '中   壢'),
('ZIP_TY', '324', '桃園縣', '平   鎮'),
('ZIP_TY', '325', '桃園縣', '龍   潭'),
('ZIP_TY', '326', '桃園縣', '楊   梅'),
('ZIP_TY', '327', '桃園縣', '新   屋'),
('ZIP_TY', '328', '桃園縣', '觀   音'),
('ZIP_TY', '330', '桃園縣', '桃   園'),
('ZIP_TY', '333', '桃園縣', '龜   山'),
('ZIP_TY', '334', '桃園縣', '八   德'),
('ZIP_TY', '335', '桃園縣', '大   溪'),
('ZIP_TY', '336', '桃園縣', '復   興'),
('ZIP_TY', '337', '桃園縣', '大   園'),
('ZIP_TY', '338', '桃園縣', '蘆   竹'),
('ZIP_ML', '350', '苗栗縣', '竹   南'),
('ZIP_ML', '351', '苗栗縣', '頭   份'),
('ZIP_ML', '352', '苗栗縣', '三   灣'),
('ZIP_ML', '353', '苗栗縣', '南   庄'),
('ZIP_ML', '354', '苗栗縣', '獅   潭'),
('ZIP_ML', '356', '苗栗縣', '後   龍'),
('ZIP_ML', '357', '苗栗縣', '通   霄'),
('ZIP_ML', '358', '苗栗縣', '苑   裡'),
('ZIP_ML', '360', '苗栗縣', '苗   栗'),
('ZIP_ML', '361', '苗栗縣', '造   橋'),
('ZIP_ML', '362', '苗栗縣', '頭   屋'),
('ZIP_ML', '363', '苗栗縣', '公   館'),
('ZIP_ML', '364', '苗栗縣', '大   湖'),
('ZIP_ML', '365', '苗栗縣', '泰   安'),
('ZIP_ML', '366', '苗栗縣', '銅   鑼'),
('ZIP_ML', '367', '苗栗縣', '三   義'),
('ZIP_ML', '368', '苗栗縣', '西   湖'),
('ZIP_ML', '369', '苗栗縣', '卓   蘭'),
('ZIP_TC', '400', '臺中市', '中   區'),
('ZIP_TC', '401', '臺中市', '東   區'),
('ZIP_TC', '402', '臺中市', '南   區'),
('ZIP_TC', '403', '臺中市', '西   區'),
('ZIP_TC', '404', '臺中市', '北   區'),
('ZIP_TC', '406', '臺中市', '北屯區'),
('ZIP_TC', '407', '臺中市', '西屯區'),
('ZIP_TC', '408', '臺中市', '南屯區'),
('ZIP_TC', '411', '臺中市', '太平區'),
('ZIP_TC', '412', '臺中市', '大里區'),
('ZIP_TC', '413', '臺中市', '霧峰區'),
('ZIP_TC', '414', '臺中市', '烏日區'),
('ZIP_TC', '420', '臺中市', '豐原區'),
('ZIP_TC', '421', '臺中市', '后里區'),
('ZIP_TC', '422', '臺中市', '石岡區'),
('ZIP_TC', '423', '臺中市', '東勢區'),
('ZIP_TC', '424', '臺中市', '和平區'),
('ZIP_TC', '426', '臺中市', '新社區'),
('ZIP_TC', '427', '臺中市', '潭子區'),
('ZIP_TC', '428', '臺中市', '大雅區'),
('ZIP_TC', '429', '臺中市', '神岡區'),
('ZIP_TC', '432', '臺中市', '大肚區'),
('ZIP_TC', '433', '臺中市', '沙鹿區'),
('ZIP_TC', '434', '臺中市', '龍井區'),
('ZIP_TC', '435', '臺中市', '梧棲區'),
('ZIP_TC', '436', '臺中市', '清水區'),
('ZIP_TC', '437', '臺中市', '大甲區'),
('ZIP_TC', '438', '臺中市', '外埔區'),
('ZIP_TC', '439', '臺中市', '大安區'),
('ZIP_CH', '500', '彰化縣', '彰   化'),
('ZIP_CH', '502', '彰化縣', '芬   園'),
('ZIP_CH', '503', '彰化縣', '花   壇'),
('ZIP_CH', '504', '彰化縣', '秀   水'),
('ZIP_CH', '505', '彰化縣', '鹿   港'),
('ZIP_CH', '506', '彰化縣', '福   興'),
('ZIP_CH', '507', '彰化縣', '線   西'),
('ZIP_CH', '508', '彰化縣', '和   美'),
('ZIP_CH', '509', '彰化縣', '伸   港'),
('ZIP_CH', '510', '彰化縣', '員   林'),
('ZIP_CH', '511', '彰化縣', '社   頭'),
('ZIP_CH', '512', '彰化縣', '永   靖'),
('ZIP_CH', '513', '彰化縣', '埔   心'),
('ZIP_CH', '514', '彰化縣', '溪   湖'),
('ZIP_CH', '515', '彰化縣', '大   村'),
('ZIP_CH', '516', '彰化縣', '埔   鹽'),
('ZIP_CH', '520', '彰化縣', '田   中'),
('ZIP_CH', '521', '彰化縣', '北   斗'),
('ZIP_CH', '522', '彰化縣', '田   尾'),
('ZIP_CH', '523', '彰化縣', '埤   頭'),
('ZIP_CH', '524', '彰化縣', '溪   州'),
('ZIP_CH', '525', '彰化縣', '竹   塘'),
('ZIP_CH', '526', '彰化縣', '二   林'),
('ZIP_CH', '527', '彰化縣', '大   城'),
('ZIP_CH', '528', '彰化縣', '芳   苑'),
('ZIP_CH', '530', '彰化縣', '二   水'),
('ZIP_NT', '540', '南投縣', '南   投'),
('ZIP_NT', '541', '南投縣', '中   寮'),
('ZIP_NT', '542', '南投縣', '草   屯'),
('ZIP_NT', '544', '南投縣', '國   姓'),
('ZIP_NT', '545', '南投縣', '埔   里'),
('ZIP_NT', '546', '南投縣', '仁   愛'),
('ZIP_NT', '551', '南投縣', '名   間'),
('ZIP_NT', '552', '南投縣', '集   集'),
('ZIP_NT', '553', '南投縣', '水   里'),
('ZIP_NT', '555', '南投縣', '魚   池'),
('ZIP_NT', '556', '南投縣', '信   義'),
('ZIP_NT', '557', '南投縣', '竹   山'),
('ZIP_NT', '558', '南投縣', '鹿   谷'),
('ZIP_CY', '600', '嘉義縣市', '嘉義市'),
('ZIP_CY', '602', '嘉義縣市', '番   路'),
('ZIP_CY', '603', '嘉義縣市', '梅   山'),
('ZIP_CY', '604', '嘉義縣市', '竹   崎'),
('ZIP_CY', '605', '嘉義縣市', '阿里山'),
('ZIP_CY', '606', '嘉義縣市', '中   埔'),
('ZIP_CY', '607', '嘉義縣市', '大   埔'),
('ZIP_CY', '608', '嘉義縣市', '水   上'),
('ZIP_CY', '611', '嘉義縣市', '鹿   草'),
('ZIP_CY', '612', '嘉義縣市', '太   保'),
('ZIP_CY', '613', '嘉義縣市', '朴   子'),
('ZIP_CY', '614', '嘉義縣市', '東   石'),
('ZIP_CY', '615', '嘉義縣市', '六   腳'),
('ZIP_CY', '616', '嘉義縣市', '新   港'),
('ZIP_CY', '621', '嘉義縣市', '民   雄'),
('ZIP_CY', '622', '嘉義縣市', '大   林'),
('ZIP_CY', '623', '嘉義縣市', '溪   口'),
('ZIP_CY', '624', '嘉義縣市', '義   竹'),
('ZIP_CY', '625', '嘉義縣市', '布   袋'),
('ZIP_YL', '630', '雲林縣', '斗   南'),
('ZIP_YL', '631', '雲林縣', '大   埤'),
('ZIP_YL', '632', '雲林縣', '虎   尾'),
('ZIP_YL', '633', '雲林縣', '土   庫'),
('ZIP_YL', '634', '雲林縣', '褒   忠'),
('ZIP_YL', '635', '雲林縣', '東   勢'),
('ZIP_YL', '636', '雲林縣', '臺   西'),
('ZIP_YL', '637', '雲林縣', '崙   背'),
('ZIP_YL', '638', '雲林縣', '麥   寮'),
('ZIP_YL', '640', '雲林縣', '斗   六'),
('ZIP_YL', '643', '雲林縣', '林   內'),
('ZIP_YL', '646', '雲林縣', '古   坑'),
('ZIP_YL', '647', '雲林縣', '莿   桐'),
('ZIP_YL', '648', '雲林縣', '西   螺'),
('ZIP_YL', '649', '雲林縣', '二   崙'),
('ZIP_YL', '651', '雲林縣', '北   港'),
('ZIP_YL', '652', '雲林縣', '水   林'),
('ZIP_YL', '653', '雲林縣', '口   湖'),
('ZIP_YL', '654', '雲林縣', '四   湖'),
('ZIP_YL', '655', '雲林縣', '元   長'),
('ZIP_TN', '700', '臺南市', '中西 區'),
('ZIP_TN', '701', '臺南市', '東   區'),
('ZIP_TN', '702', '臺南市', '南   區'),
('ZIP_TN', '704', '臺南市', '北   區'),
('ZIP_TN', '708', '臺南市', '安平區'),
('ZIP_TN', '709', '臺南市', '安南區'),
('ZIP_TN', '710', '臺南市', '永康區'),
('ZIP_TN', '711', '臺南市', '歸仁區'),
('ZIP_TN', '712', '臺南市', '新化區'),
('ZIP_TN', '713', '臺南市', '左鎮區'),
('ZIP_TN', '714', '臺南市', '玉井區'),
('ZIP_TN', '715', '臺南市', '楠西區'),
('ZIP_TN', '716', '臺南市', '南化區'),
('ZIP_TN', '717', '臺南市', '仁德區'),
('ZIP_TN', '718', '臺南市', '關廟區'),
('ZIP_TN', '719', '臺南市', '龍崎區'),
('ZIP_TN', '720', '臺南市', '官田區'),
('ZIP_TN', '721', '臺南市', '麻豆區'),
('ZIP_TN', '722', '臺南市', '佳里區'),
('ZIP_TN', '723', '臺南市', '西港區'),
('ZIP_TN', '724', '臺南市', '七 股區'),
('ZIP_TN', '725', '臺南市', '將軍區'),
('ZIP_TN', '726', '臺南市', '學甲區'),
('ZIP_TN', '727', '臺南市', '北門區'),
('ZIP_TN', '730', '臺南市', '新營區'),
('ZIP_TN', '731', '臺南市', '後壁區'),
('ZIP_TN', '732', '臺南市', '白河區'),
('ZIP_TN', '733', '臺南市', '東山區'),
('ZIP_TN', '734', '臺南市', '六甲區'),
('ZIP_TN', '735', '臺南市', '下營區'),
('ZIP_TN', '736', '臺南市', '柳營區'),
('ZIP_TN', '737', '臺南市', '鹽水區'),
('ZIP_TN', '741', '臺南市', '善化區'),
('ZIP_TN', '742', '臺南市', '大內區'),
('ZIP_TN', '743', '臺南市', '山上區'),
('ZIP_TN', '744', '臺南市', '新市區'),
('ZIP_TN', '745', '臺南市', '安定區'),
('ZIP_KS', '800', '高雄市', '新興區'),
('ZIP_KS', '801', '高雄市', '前金區'),
('ZIP_KS', '802', '高雄市', '苓雅區'),
('ZIP_KS', '803', '高雄市', '鹽埕區'),
('ZIP_KS', '804', '高雄市', '鼓山區'),
('ZIP_KS', '805', '高雄市', '旗津區'),
('ZIP_KS', '806', '高雄市', '前鎮區'),
('ZIP_KS', '807', '高雄市', '三民區'),
('ZIP_KS', '811', '高雄市', '楠梓區'),
('ZIP_KS', '812', '高雄市', '小港區'),
('ZIP_KS', '813', '高雄市', '左營區'),
('ZIP_KS', '814', '高雄市', '仁武區'),
('ZIP_KS', '815', '高雄市', '大社區'),
('ZIP_KS', '820', '高雄市', '岡山區'),
('ZIP_KS', '821', '高雄市', '路竹區'),
('ZIP_KS', '822', '高雄市', '阿蓮區'),
('ZIP_KS', '823', '高雄市', '田寮區'),
('ZIP_KS', '824', '高雄市', '燕巢區'),
('ZIP_KS', '825', '高雄市', '橋頭區'),
('ZIP_KS', '826', '高雄市', '梓官區'),
('ZIP_KS', '827', '高雄市', '彌陀區'),
('ZIP_KS', '828', '高雄市', '永安區'),
('ZIP_KS', '829', '高雄市', '湖內區'),
('ZIP_KS', '830', '高雄市', '鳳山區'),
('ZIP_KS', '831', '高雄市', '大寮區'),
('ZIP_KS', '832', '高雄市', '林園區'),
('ZIP_KS', '833', '高雄市', '鳥松區'),
('ZIP_KS', '840', '高雄市', '大樹區'),
('ZIP_KS', '842', '高雄市', '旗山區'),
('ZIP_KS', '843', '高雄市', '美濃區'),
('ZIP_KS', '844', '高雄市', '六龜區'),
('ZIP_KS', '845', '高雄市', '內門區'),
('ZIP_KS', '846', '高雄市', '杉林區'),
('ZIP_KS', '847', '高雄市', '甲仙區'),
('ZIP_KS', '848', '高雄市', '桃源區'),
('ZIP_KS', '849', '高雄市', '那瑪夏區'),
('ZIP_KS', '851', '高雄市', '茂林區'),
('ZIP_KS', '852', '高雄市', '茄萣區'),
('ZIP_NL', '817', '南海諸島', '東    沙'),
('ZIP_NL', '819', '南海諸島', '南    沙'),
('ZIP_PH', '880', '澎湖縣', '馬   公'),
('ZIP_PH', '881', '澎湖縣', '西   嶼'),
('ZIP_PH', '882', '澎湖縣', '望   安'),
('ZIP_PH', '883', '澎湖縣', '七   美'),
('ZIP_PH', '884', '澎湖縣', '白   沙'),
('ZIP_PH', '885', '澎湖縣', '湖   西'),
('ZIP_PD', '900', '屏東縣', '屏   東'),
('ZIP_PD', '901', '屏東縣', '三地門'),
('ZIP_PD', '902', '屏東縣', '霧   臺'),
('ZIP_PD', '903', '屏東縣', '瑪   家'),
('ZIP_PD', '904', '屏東縣', '九   如'),
('ZIP_PD', '905', '屏東縣', '里   港'),
('ZIP_PD', '906', '屏東縣', '高   樹'),
('ZIP_PD', '907', '屏東縣', '盬   埔'),
('ZIP_PD', '908', '屏東縣', '長   治'),
('ZIP_PD', '909', '屏東縣', '麟   洛'),
('ZIP_PD', '911', '屏東縣', '竹   田'),
('ZIP_PD', '912', '屏東縣', '內   埔'),
('ZIP_PD', '913', '屏東縣', '萬   丹'),
('ZIP_PD', '920', '屏東縣', '潮   州'),
('ZIP_PD', '921', '屏東縣', '泰   武'),
('ZIP_PD', '922', '屏東縣', '來   義'),
('ZIP_PD', '923', '屏東縣', '萬   巒'),
('ZIP_PD', '924', '屏東縣', '崁   頂'),
('ZIP_PD', '925', '屏東縣', '新   埤'),
('ZIP_PD', '926', '屏東縣', '南   州'),
('ZIP_PD', '927', '屏東縣', '林   邊'),
('ZIP_PD', '928', '屏東縣', '東   港'),
('ZIP_PD', '929', '屏東縣', '琉   球'),
('ZIP_PD', '931', '屏東縣', '佳   冬'),
('ZIP_PD', '932', '屏東縣', '新   園'),
('ZIP_PD', '940', '屏東縣', '枋   寮'),
('ZIP_PD', '941', '屏東縣', '枋   山'),
('ZIP_PD', '942', '屏東縣', '春   日'),
('ZIP_PD', '943', '屏東縣', '獅   子'),
('ZIP_PD', '944', '屏東縣', '車   城'),
('ZIP_PD', '945', '屏東縣', '牡   丹'),
('ZIP_PD', '946', '屏東縣', '恆   春'),
('ZIP_PD', '947', '屏東縣', '滿   州'),
('ZIP_TD', '950', '臺東縣', '臺   東'),
('ZIP_TD', '951', '臺東縣', '綠   島'),
('ZIP_TD', '952', '臺東縣', '蘭   嶼'),
('ZIP_TD', '953', '臺東縣', '延   平'),
('ZIP_TD', '954', '臺東縣', '卑   南'),
('ZIP_TD', '955', '臺東縣', '鹿   野'),
('ZIP_TD', '956', '臺東縣', '關   山'),
('ZIP_TD', '957', '臺東縣', '海   端'),
('ZIP_TD', '958', '臺東縣', '池   上'),
('ZIP_TD', '959', '臺東縣', '東   河'),
('ZIP_TD', '961', '臺東縣', '成   功'),
('ZIP_TD', '962', '臺東縣', '長   濱'),
('ZIP_TD', '963', '臺東縣', '太麻里'),
('ZIP_TD', '964', '臺東縣', '金   峰'),
('ZIP_TD', '965', '臺東縣', '大   武'),
('ZIP_TD', '966', '臺東縣', '達   仁'),
('ZIP_HL', '970', '花蓮縣', '花   蓮'),
('ZIP_HL', '971', '花蓮縣', '新   城'),
('ZIP_HL', '972', '花蓮縣', '秀   林'),
('ZIP_HL', '973', '花蓮縣', '吉   安'),
('ZIP_HL', '974', '花蓮縣', '壽   豐'),
('ZIP_HL', '975', '花蓮縣', '鳳   林'),
('ZIP_HL', '976', '花蓮縣', '光   復'),
('ZIP_HL', '977', '花蓮縣', '豐   濱'),
('ZIP_HL', '978', '花蓮縣', '瑞   穗'),
('ZIP_HL', '979', '花蓮縣', '萬   榮'),
('ZIP_HL', '981', '花蓮縣', '玉   里'),
('ZIP_HL', '982', '花蓮縣', '卓   溪'),
('ZIP_HL', '983', '花蓮縣', '富   里'),
('ZIP_KM', '890', '金門縣', '金   沙'),
('ZIP_KM', '891', '金門縣', '金   湖'),
('ZIP_KM', '892', '金門縣', '金   寧'),
('ZIP_KM', '893', '金門縣', '金   城'),
('ZIP_KM', '894', '金門縣', '烈   嶼'),
('ZIP_KM', '896', '金門縣', '烏   坵'),
('ZIP_LC', '209', '連江縣', '南   竿'),
('ZIP_LC', '210', '連江縣', '北   竿'),
('ZIP_LC', '211', '連江縣', '莒   光'),
('ZIP_LC', '212', '連江縣', '東   引');
