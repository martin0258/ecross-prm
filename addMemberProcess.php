<?php
/** 
 * Description:
 * [建檔]的後端處理頁。ISNERT一筆新人資料。
 * @author Martin Ku
 * @package backend
 */
include '../../mainfile.php';
include './function/funcs.php';

# 檢查權限及是否登入
if($xoopsUser){
  if(!$_SESSION['mod1']){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}else{ redirect_header(XOOPS_URL, 3, _MD_NOT_LOGIN); }
//名單日期
date_default_timezone_set('Asia/Taipei');
$FirstVisitDate = $_POST['date']=='' ? date("Y-m-d") : $_POST['date'];

//性質、姓名、性別
$source = $_POST['source']; 
$memName = $_POST['memName'];
$memSex = $_POST['memSex'];    

//判斷名字是中文還是英文
if (mb_strlen($memName,"Big5") == strlen($memName)){
  $memEnglishName = $memName;
  $memChineseName = NULL;
}else{
  $memEnglishName = NULL;
  $memChineseName = $memName;
}

//生日
$bir_year = $_POST['bir_year'];
$bir_month = $_POST['bir_month'];
$bir_day = $_POST['bir_day'];
if($bir_year==NULL)$bir_year = date("Y");
if($bir_month==NULL)$bir_month = "1";
if($bir_day==NULL)$bir_day = "1";
$memBirth = "$bir_year-$bir_month-$bir_day";

//年齡、婚姻狀況、配偶姓名、手機、住家電話、公司電話
$memAge = $_POST['memAge'];
$memMarry = $_POST['memMarry'];
$memComeWithSpouse = ($_POST['memComeWithSpouse']=="") ? 'N' : $_POST['memComeWithSpouse'];
$memSpouseName = $_POST['memSpouseName'];
$memHome = $_POST['memHome'];
$memCell = $_POST['memCell'];
$memCompanyPhoneNumber = $_POST['memOffice'];

//住址(國家、縣市、鄉鎮市區、明細、郵遞區號)
$memCountryCode = $_POST['memAddressCountry'];
$memAddZipcode = $_POST['memAddressZipCode'];
$memTownCity = "";
$memCountry = "";
if($memCountryCode != ""){
  //從代碼取得縣市
  $sql = "SELECT CODE_NAME FROM ".$xoopsDB->prefix("torch_code")." WHERE CODE_ID IN('$memCountryCode')";
  $result = $xoopsDB->query($sql);
  $memCountry = mysql_result($result, 0);
}
if($memAddZipcode != ""){
  //從郵遞區號取得鄉鎮市區
  $sql = "SELECT CODE_NAME FROM ".$xoopsDB->prefix("torch_code")." WHERE CODE_ID IN('$memAddZipcode')";
  $result = $xoopsDB->query($sql);
  $memTownCity = mysql_result($result, 0);
}
$memNation = $_POST['memAddressNation'];
$memAddressDetail = $_POST['memAddressDetail'];

// 電子郵件、工作、學校、身分、身分(其他)
$memEmail = $_POST['memEmail'];
$memJob = $_POST['memJob'];
$memSchool = $_POST['memSchool'];
$memType = $_POST['memType'];
$memStatusMemo = $_POST['memStatusMemo'];

//如何得知火把
$IntermediumCode = $_POST['howToKnowTorch'];
$howToKnowTorch = "";
for($i=0; $i<count($IntermediumCode)-1; $i++){
  $howToKnowTorch .= ($IntermediumCode[$i] . ",");
}
$howToKnowTorch .= $IntermediumCode[$i];
//朋友、家人姓名電話
$memInviteFriend = $_POST['memInviteFriend'];
$memInviteTelFriend = $_POST['memInviteTelFriend'];
$memInviteFamily = $_POST['memInviteFamily'];
$memInviteTelFamily = $_POST['memInviteTelFamily'];
$howToKnowTorchMemo = $_POST['howToKnowTorchMemo'];

//喜歡火把的原因
$ReasonCode = $_POST['likeReason'];
$likeTorchReason = "";
for($i=0; $i<count($ReasonCode)-1; $i++){
  $likeTorchReason .= ($ReasonCode[$i] . ",");
}
$likeTorchReason .= $ReasonCode[$i];
$likeMemo = $_POST['likeMemo'];

//火把可改進的
$ReasonCode = $_POST['improve'];
$torchImprovement = "";
for($i=0; $i<count($ReasonCode)-1; $i++){
  $torchImprovement .= ($ReasonCode[$i] . ",");
}
$torchImprovement .= $ReasonCode[$i];
$improveMemo = $_POST['improveMemo'];

//新朋友需要的服務
$ReasonCode = $_POST['service'];
$needService = "";
for($i=0; $i<count($ReasonCode)-1; $i++){
  $needService .= ($ReasonCode[$i] . ",");
}
$needService .= $ReasonCode[$i];
$intercession = $_POST['intercession'];

//其他疑問/建議
$memNote = $_POST['note'];

//小組編號
$groupID = $groupID_Temp = "NULL";
if(isset($_POST['group_select2']) && $_POST['group_select2'] != ''){
  $groupID = "'".$_POST['group_select2']."'";
}

//開始匯入
$uname = $xoopsUser->uname();
$sql = 
  "INSERT INTO ".$xoopsDB->prefix("torch_memberinformation")."(FirstVisitDate, 
  ChineseName, EnglishName, Email, Birthday, Sex, CellPhoneNumber, MailingAddress_ZipCode, 
  MailingAddress_Nationality, MailingAddress_Country, MailingAddress_Township, MailingAddress_Detail, 
  Marriage, Source, BeliefStatus, HomePhoneNumber, Introducer, IntroducerPhoneNumber, Job, Note,
  GroupLists_GroupID, GroupID_TEMP, SpouseName, HowToKnowTorch, HowToKnowTorch_Memo,
  Introducer_FamilyName, Introducer_FamilyPhone, LikeTorchReason, TorchImprovement, 
  NeedService, Intercession, BeliefStatus_Memo, MailingAddress_Country_Code, 
  MailingAddress_Township_Code, Create_Timestamp, Create_ID, LikeTorchReasonMemo, 
  TorchImprovementMemo, ComeWithSpouse, SchoolYear, CompanyPhoneNumber, AgeIntervalCode) Values 
  ('$FirstVisitDate', '$memChineseName', '$memEnglishName', '$memEmail', '$memBirth',
  '$memSex', '$memCell', '$memAddZipcode','$memNation', '$memCountry', '$memTownCity',
  '$memAddressDetail', '$memMarry', '$source', '$memType', '$memHome', '$memInviteFriend',
  '$memInviteTelFriend', '$memJob', '$memNote', $groupID, $groupID_Temp, '$memSpouseName', '$howToKnowTorch',
  '$howToKnowTorchMemo', '$memInviteFamily', '$memInviteTelFamily', '$likeTorchReason',
  '$torchImprovement', '$needService', '$intercession', '$memStatusMemo', '$memCountryCode',
  '$memAddZipcode', NOW(), '$uname', '$likeMemo', '$improveMemo', '$memComeWithSpouse', 
  '$memSchool', '$memCompanyPhoneNumber', '$memAge')";
$result = $xoopsDB->query($sql);
$newID = mysql_insert_id();

if($groupID != "NULL"){
  //紀錄加入小組的新人
  $fp = fopen(getSysVar('logFilePath'), "a+");
  fwrite($fp, date("Y-m-d H:i:s").": Insert $newID into $groupID \n");
  fclose($fp);
}

if($result==true) redirect_header('addMember.php', 3, '建檔成功');
else redirect_header('addMember.php', 3, '建檔失敗，請聯絡程式人員');
?>
