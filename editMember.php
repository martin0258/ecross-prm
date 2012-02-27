<?php
/** 
 * Description:
 * 儲存一筆編輯完成的訪談紀錄
 * @author Martin Ku
 * @package page
 */
include '../../mainfile.php';
require_once 'function/funcs.php';  

//檢查權限及是否登入
if($xoopsUser){
  if(!$_SESSION['mod3']){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}
else{ redirect_header(XOOPS_URL, 3, _MD_NOT_LOGIN); }

# Use module template
$xoopsOption['template_main'] = "detailForm.html";
$xoopsOption['xoops_module_header'] = 
  "<link rel='stylesheet' type='text/css' href='css/validationEngine.jquery.css'>
  <link rel='stylesheet' type='text/css' href='css/redmond/jquery-ui-1.8.18.custom.css'>	  
  <link rel='stylesheet' type='text/css' href='css/torchStyle.css'>
  <script type='text/javascript' src='js/jquery-1.7.1.min.js'></script>
  <script type='text/javascript' src='js/jquery-ui-1.8.18.custom.min.js'></script>
  <script type='text/javascript' src='js/jquery.ui.datepicker-zh-TW.js'></script>
  <script type='text/javascript' src='js/validation/jquery.validationEngine-zh_TW.js'></script>
  <script type='text/javascript' src='js/validation/jquery.validationEngine.js'></script>
  <script type='text/javascript' src='js/selectboxes.js'></script>
  <script type='text/javascript' src='js/memberForm.js'></script>";
include XOOPS_ROOT_PATH.'/header.php';

# Get Member Data from DB
if(!isset($_POST['memberID'])){
  redirect_header(XOOPS_URL, 3, _NOPERM);
}else{
  $memberID = $_POST['memberID'];
}
$_SESSION['editMemberID'] = $memberID;
$sql = 
  "SELECT tg.GroupCategory, tm.GroupLists_GroupID, FirstVisitDate, Source, ChineseName, EnglishName, 
  Sex, Birthday, AgeIntervalCode, Marriage, ComeWithSpouse, SpouseName, HomePhoneNumber, CellPhoneNumber,
  CompanyPhoneNumber, MailingAddress_Nationality, MailingAddress_Country_Code, 
  MailingAddress_Township_Code, MailingAddress_Detail, Email, Job, SchoolYear, BeliefStatus,
  BeliefStatus_Memo, Introducer, IntroducerPhoneNumber, Introducer_FamilyName, Introducer_FamilyPhone,
  HowToKnowTorch, HowToKnowTorch_Memo, LikeTorchReason, LikeTorchReasonMemo, TorchImprovement,
  TorchImprovementMemo, NeedService, Intercession, Note, PictureSavingPath
  FROM ".$xoopsDB->prefix('torch_memberinformation')." tm LEFT OUTER JOIN ".$xoopsDB->prefix('torch_grouplists').
  " tg ON tm.GroupLists_GroupID = tg.GroupID WHERE tm.MemberID = '$memberID'";
$result = $xoopsDB->query($sql);
$row = $xoopsDB->fetchrow($result);

# Get the Group Category
$sqlGroup = "SELECT DISTINCT(GroupCategory) FROM ".$xoopsDB->prefix('torch_GroupLists');
$result = $xoopsDB->query($sqlGroup);
$groupArr = array();
while( $tempRow = $xoopsDB->fetchrow($result) ){
  $groupArr[$tempRow[0]] = $tempRow[0];
}

# Get the age interval, Country Dropdownlist Detail
$ageArr = getCode('年齡區間');
$countryArr = getCode('郵遞區號-縣市');
$xoopsTpl->assign('groupArr', $groupArr);
$xoopsTpl->assign('ageArr', $ageArr);
$xoopsTpl->assign('countryArr', $countryArr);

# Get the codes of checkbox arrays
$intermediumArr = getChecked($row[28], '得知火把');
$likeReasonArr = getChecked($row[30], '喜歡原因、待改進原因');
$improveArr = getChecked($row[32],  '喜歡原因、待改進原因');
$serviceArr = getChecked($row[34], '提供服務');
$xoopsTpl->assign('intermediumArr', $intermediumArr);
$xoopsTpl->assign('likeReasonArr', $likeReasonArr);
$xoopsTpl->assign('improveArr', $improveArr);
$xoopsTpl->assign('serviceArr', $serviceArr);

# Assign content into template
$birthdate = explode('-', $row[7]);
$xoopsTpl->assign('action', 'editMemberProcess.php');
$xoopsTpl->assign('tableTitle', '編輯新人資料');
$xoopsTpl->assign('memberID', $memberID);
$xoopsTpl->assign('groupCtg', $row[0]);
$xoopsTpl->assign('groupID', $row[1]);
$xoopsTpl->assign('visitDate', $row[2]);
$xoopsTpl->assign('source', $row[3]);
$xoopsTpl->assign('cname', $row[4]);
$xoopsTpl->assign('ename', $row[5]);
$xoopsTpl->assign('sex', $row[6]);
$xoopsTpl->assign('birthYear', $birthdate[0]);
$xoopsTpl->assign('birthMonth', $birthdate[1]);
$xoopsTpl->assign('birthDay', $birthdate[2]);
$xoopsTpl->assign('memAge', $row[8]);
$xoopsTpl->assign('memMarry', $row[9]);
$xoopsTpl->assign('memComeWithSpouse', $row[10]);
$xoopsTpl->assign('memSpouseName', $row[11]);
$xoopsTpl->assign('memHome', $row[12]);
$xoopsTpl->assign('memCell', $row[13]);
$xoopsTpl->assign('memOffice', $row[14]);
$xoopsTpl->assign('memNation', $row[15]);
$xoopsTpl->assign('memAddressCountry', $row[16]);
$xoopsTpl->assign('zipCode', $row[17]);
$xoopsTpl->assign('memAddressDetail', $row[18]);
$xoopsTpl->assign('memEmail', $row[19]);
$xoopsTpl->assign('memJob', $row[20]);
$xoopsTpl->assign('memSchool', $row[21]);
$xoopsTpl->assign('memType', $row[22]);
$xoopsTpl->assign('memStatusMemo', $row[23]);
$xoopsTpl->assign('memInviteFriend', $row[24]);
$xoopsTpl->assign('memInviteTelFriend', $row[25]);
$xoopsTpl->assign('memInviteFamily', $row[26]);
$xoopsTpl->assign('memInviteTelFamily', $row[27]);
$xoopsTpl->assign('howToKnowTorchMemo', $row[29]);
$xoopsTpl->assign('likeMemo', $row[31]);
$xoopsTpl->assign('improveMemo', $row[33]);
$xoopsTpl->assign('intercession', $row[35]);
$xoopsTpl->assign('note', $row[36]);
$xoopsTpl->assign('picturePath', file_exists($row[37]) ? $row[37] : 'images/nobody.jpg');
$xoopsTpl->assign('buttonType', 'submit');
$xoopsTpl->assign('submitName', '儲存');

include XOOPS_ROOT_PATH.'/footer.php';
?>
