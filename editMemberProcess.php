<?php
/** 
 * Description:
 * 儲存編輯完成的新人資料(包含上傳圖片)
 * @author Martin Ku
 * @package backend
 */
include '../../mainfile.php';
require_once 'include/imageResize.php';  

# 檢查權限及是否登入
if($xoopsUser){
  if(!$_SESSION['mod3']){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}
else{ redirect_header(XOOPS_URL, 3, _MD_NOT_LOGIN); }

//更改資料的新人編號
$memberID = $_POST['memberID'];

//小組編號
$groupID = "NULL";
if(isset($_POST['group_select2']) && $_POST['group_select2'] != ''){
  $groupID = "'".$_POST['group_select2']."'";
}

//名單日期
date_default_timezone_set('Asia/Taipei');
$FirstVisitDate = $_POST['date']=='' ? date("Y-m-d") : $_POST['date'];

//性質、姓名、性別
$source = $_POST['source'];
$memChineseName = $_POST['cname'];
$memEnglishName = $_POST['ename'];
$memSex = $_POST['memSex'];

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
$ReasonCode = $_POST['likeReason'];
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

$sql = "SELECT PictureSavingPath FROM ".$xoopsDB->prefix("torch_MemberInformation")." where MemberID = '$memberID'";
$result = $xoopsDB->query($sql);
$pre_Path = mysql_result($result, 0);
$message = '資料已更新';
$redirect_url = 'viewMember.php';
//處理更新圖片
if( is_uploaded_file($_FILES['imgURL']['tmp_name']) ){
  $DestDIR = "images/member";
  if( !is_dir($DestDIR) || !is_writeable($DestDIR) )
    die("目錄不存在或無法寫入");
  $File_Extension = explode(".", $_FILES['imgURL']['name']); 
  $File_Extension = $File_Extension[count($File_Extension)-1]; 
  $ServerFilename = date("YmdHis") . "_$memberID." . $File_Extension;
  if( move_uploaded_file( $_FILES['imgURL']['tmp_name'] , $DestDIR.'/'.$ServerFilename ) ){
    $message = '資料和照片已更新';
    $PictureSavingPath = $DestDIR.'/'.$ServerFilename;
    if($pre_Path!=NULL && file_exists($pre_Path))unlink($pre_Path);
  }
  //縮圖
  if(ImageResize($PictureSavingPath, $PictureSavingPath, 200, 100)==false){
    $message = "資料已更新，但照片格式可能有誤(建議:jpg)";
  }
}
else{
  //no picture submit
  $PictureSavingPath = $pre_Path;
}

# 特別處理ENUM的欄位，否則會UPDATE失敗
if($memType==''){
  $memType = '其他';
}
//開始更新
$uname = $xoopsUser->uname();
$sql = 
  "UPDATE ".$xoopsDB->prefix("torch_memberinformation").
  " SET FirstVisitDate = '$FirstVisitDate', 
  ChineseName = '$memChineseName',
  EnglishName = '$memEnglishName',
  Email = '$memEmail',
  Birthday = '$memBirth',
  Sex = '$memSex',
  CellPhoneNumber = '$memCell',
  MailingAddress_ZipCode = '$memAddZipcode',
  MailingAddress_Nationality = '$memNation',
  MailingAddress_Country = '$memCountry',
  MailingAddress_Township = '$memTownCity',
  MailingAddress_Detail = '$memAddressDetail',
  Marriage = '$memMarry',
  Source = '$source',
  BeliefStatus = '$memType',
  HomePhoneNumber = '$memHome',
  Introducer = '$memInviteFriend',
  IntroducerPhoneNumber = '$memInviteTelFriend',
  Job = '$memJob',
  Note = '$memNote',
  GroupLists_GroupID = $groupID,
  SpouseName = '$memSpouseName',
  HowToKnowTorch = '$howToKnowTorch',
  HowToKnowTorch_Memo = '$howToKnowTorchMemo',
  Introducer_FamilyName = '$memInviteFamily',
  Introducer_FamilyPhone = '$memInviteTelFamily',
  LikeTorchReason = '$likeTorchReason',
  TorchImprovement = '$torchImprovement',
  NeedService = '$needService',
  Intercession = '$intercession',
  BeliefStatus_Memo = '$memStatusMemo',
  MailingAddress_Country_Code = '$memCountryCode',
  MailingAddress_Township_Code = '$memAddZipcode',
  Update_Timestamp = NOW(),
  Update_ID = '$uname',
  LikeTorchReasonMemo = '$likeMemo',
  TorchImprovementMemo = '$improveMemo',
  ComeWithSpouse = '$memComeWithSpouse',
  SchoolYear = '$memSchool',
  CompanyPhoneNumber = '$memCompanyPhoneNumber',
  AgeIntervalCode = '$memAge',
  PictureSavingPath = '$PictureSavingPath'
  WHERE MemberID = '$memberID'";
$result = $xoopsDB->query($sql);
if($result!=true){
  $message = "更新失敗，請聯絡程式人員";
  //讀取前一頁透過連結過來的網址
  //$redirect_url = $_SERVER['HTTP_REFERER'];    
}
redirect_header($redirect_url, 3, $message);

include XOOPS_ROOT_PATH.'/footer.php';
?>
