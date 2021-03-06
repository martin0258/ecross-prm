<?php
/** 
 * Description:
 * [查看新人]的主頁面。編輯完新人資料後會導向此頁。
 * @author Martin Ku
 * @package page
 */
include '../../mainfile.php';
require_once('function/funcs.php');
if($xoopsUser){
  if(!$_SESSION['mod2'] && !$_SESSION['mod3']){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}
else { redirect_header(XOOPS_URL, 3, _MD_NOT_LOGIN); }
$buttonType = ($_SESSION['mod3']==true) ? 'submit' : 'hidden';

# Use module template
$xoopsOption['template_main'] = "detailForm.html";
$xoopsOption['xoops_module_header'] = 
  "<link rel=stylesheet type='text/css' href='css/torchStyle.css'>
  <script type='text/javascript' src='js/selectboxes.js'></script>
  <script type='text/javascript' src='js/cascadingDropdown.js'></script>
  <script type='text/javascript' src='js/torch.js'></script>
  <script type='text/javascript' src='js/selectDate.js'></script>
  <script type='text/javascript' src='js/readOnly.js'></script>
  <script type='text/javascript'>
  $(document).ready(function() {
    //We use the jquery library included by xoops
    $('#group_select1').trigger('change');
    $('#address_select1').trigger('change');
    readOnly();
  });
  </script>";
include XOOPS_ROOT_PATH.'/header.php';

# Get Member Data from DB
if(!isset($_POST['memberID']) && !isset($_SESSION['editMemberID'])){
  redirect_header(XOOPS_URL, 3, _NOPERM);
}
$memberID = isset($_POST['memberID']) ? $_POST['memberID'] : $_SESSION['editMemberID'];
$sql = 
  "SELECT tg.GroupCategory, tm.GroupLists_GroupID, FirstVisitDate, Source, ChineseName, EnglishName, Sex,
  Birthday, AgeIntervalCode, Marriage, ComeWithSpouse, SpouseName, HomePhoneNumber, CellPhoneNumber,
  CompanyPhoneNumber, MailingAddress_Nationality, MailingAddress_Country_Code, MailingAddress_Township_Code,
  MailingAddress_Detail, Email, Job, SchoolYear, BeliefStatus, BeliefStatus_Memo, Introducer,
  IntroducerPhoneNumber, Introducer_FamilyName, Introducer_FamilyPhone, HowToKnowTorch, HowToKnowTorch_Memo,
  LikeTorchReason, LikeTorchReasonMemo, TorchImprovement, TorchImprovementMemo, NeedService, Intercession,
  Note, PictureSavingPath FROM ".$xoopsDB->prefix('torch_member_information').
  " tm LEFT OUTER JOIN ".$xoopsDB->prefix('torch_group_lists').
  " tg ON tm.GroupLists_GroupID = tg.GroupID WHERE tm.MemberID = '$memberID'";
$result = $xoopsDB->query($sql);
$row = $xoopsDB->fetchrow($result);

# Get the Group Category
$sqlGroup = "SELECT DISTINCT(GroupCategory) FROM ".$xoopsDB->prefix('torch_group_lists');
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
$xoopsTpl->assign('action', 'editMember.php');
$xoopsTpl->assign('tableTitle', '新人詳細資料(唯讀模式)');
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
$xoopsTpl->assign('buttonType', $buttonType);
$xoopsTpl->assign('submitName', '編輯');

include XOOPS_ROOT_PATH.'/footer.php';
?>
