<?php
/* 
 * 程式用途：作為[建檔]頁面
 * 歷史：
 *    2012/1/25 Martin Ku, change jqueryui css file to 'jquery-ui-1.8.16.custom.torch.css' for jGrowl.
 */
include '../../mainfile.php';

if($xoopsUser){
  if(!$_SESSION['mod1']){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}
else{ 
  redirect_header(XOOPS_URL, 3, _MD_NOT_LOGIN);
}

function getCode($code_kind_name){
  global $xoopsDB;
  $sql = "SELECT CODE_ID, CODE_NAME FROM ".$xoopsDB->prefix('torch_code')." WHERE CODE_KIND_NAME='$code_kind_name'";
  $result = $xoopsDB->query($sql);
  $list = array();
  while( $row = $xoopsDB->fetchrow($result) ){
    $list[$row[0]] = $row[1];
  }
  return $list;
}

//use module template
$xoopsOption['template_main'] = 'addMemberForm.html';
$xoopsOption['xoops_module_header'] = 
  "<link rel='stylesheet' type='text/css' href='css/validationEngine.jquery.css'>
  <link rel='stylesheet' type='text/css' href='css/redmond/jquery-ui-1.8.16.custom.torch.css'>
  <link rel='stylesheet' type='text/css' href='css/torchStyle.css'>
  <script type='text/javascript' src='js/jquery-1.7.1.min.js'></script>
  <script type='text/javascript' src='js/jquery-ui-1.8.17.custom.min.js'></script>
  <script type='text/javascript' src='js/jquery.ui.datepicker-zh-TW.js'></script>
  <script type='text/javascript' src='js/validation/jquery.validationEngine.js'></script>
  <script type='text/javascript' src='js/validation/jquery.validationEngine-zh_TW.js'></script>
  <script type='text/javascript' src='js/selectboxes.js'></script>
  <script type='text/javascript' src='js/memberForm.js'></script>";
include XOOPS_ROOT_PATH.'/header.php';

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

$xoopsTpl->assign('ageArr', $ageArr);
$xoopsTpl->assign('groupArr', $groupArr);
$xoopsTpl->assign('countryArr', $countryArr);

# Assign content into template
$xoopsTpl->assign('action', 'addMemberProcess.php');
$xoopsTpl->assign('tableTitle', '建立新人資料');
$xoopsTpl->assign('birthYear', '0');
$xoopsTpl->assign('buttonType', 'submit');
$xoopsTpl->assign('submitName', '儲存');

include XOOPS_ROOT_PATH.'/footer.php';
?>

