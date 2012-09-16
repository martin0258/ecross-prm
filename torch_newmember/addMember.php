<?php
/** 
 * Description:
 * [建檔]的主頁面。新增一筆新人資料。
 * @author Martin Ku
 * @package page
 */
include '../../mainfile.php';
require_once('function/funcs.php');

# 檢查權限及是否登入
if($xoopsUser){
  if(!$_SESSION['mod1']){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}
else{ redirect_header(XOOPS_URL, 3, _MD_NOT_LOGIN); }

//use module template
$xoopsOption['template_main'] = 'addMemberForm.html';
$xoopsOption['xoops_module_header'] = 
  "<link rel='stylesheet' type='text/css' href='css/validationEngine.jquery.css'>
  <link rel='stylesheet' type='text/css' href='css/redmond/jquery-ui-1.8.18.custom.css'>
  <link rel='stylesheet' type='text/css' href='css/torchStyle.css'>
  <script type='text/javascript' src='js/jquery-1.7.1.min.js'></script>
  <script type='text/javascript' src='js/jquery-ui-1.8.18.custom.min.js'></script>
  <script type='text/javascript' src='js/jquery.ui.datepicker-zh-TW.js'></script>
  <script type='text/javascript' src='js/validation/jquery.validationEngine.js'></script>
  <script type='text/javascript' src='js/validation/jquery.validationEngine-zh_TW.js'></script>
  <script type='text/javascript' src='js/selectboxes.js'></script>
  <script type='text/javascript' src='js/memberForm.js'></script>";
include XOOPS_ROOT_PATH.'/header.php';

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

