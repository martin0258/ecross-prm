<?php
/* 
 * Description:
 * 作為[查詢]、[查詢修改]、[查詢刪除]、[地址輸出]的輸入搜尋條件頁面
 * @author Martin Ku
 * @package page
 */
include '../../mainfile.php';

# 檢查權限及是否登入
if($xoopsUser){
  if(!$_SESSION['mod2'] && !$_SESSION['mod3']){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}
else{ redirect_header(XOOPS_URL, 3, _MD_NOT_LOGIN); }

//Decide which type of query and check if the user have its authority
$queryType = (isset($_GET['queryType'])) ? $_GET['queryType'] : 'query';
if($queryType == 'delete'){
  $formname = '查詢刪除';
  $action = 'queryResultDelete.php';
  if(!$_SESSION['mod5']){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}else if($queryType == 'address'){
  $formname = '查詢輸出';
  $action = 'queryResultAddress.php';
  if(!$_SESSION['mod6']){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}else{
  $formname = '查詢新人';
  $action = 'queryResult.php';
  if(!$_SESSION['mod2'] && !$_SESSION['mod3']){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}

//use module template
$xoopsOption['template_main'] = 'queryForm.html';
$xoopsOption['xoops_module_header'] = 
  "<link type='text/css' rel='stylesheet' href='css/redmond/jquery-ui-1.8.18.custom.css' />	  
  <link type='text/css' rel='stylesheet' href='css/torchStyle.css' />
  <script type='text/javascript' src='js/jquery-1.7.1.min.js'></script>
  <script type='text/javascript' src='js/jquery.ba-hashchange.js'></script>
  <script type='text/javascript' src='js/jquery-ui-1.8.18.custom.min.js'></script>
  <script type='text/javascript' src='js/jquery.ui.datepicker-zh-TW.js'></script>
  <script type='text/javascript' src='js/selectboxes.js'></script>
  <script type='text/javascript' src='js/cascadingDropdown.js'></script>
  <script type='text/javascript' src='js/torch.js'></script>
  <script type='text/javascript' src='js/formFocus.js'></script>
  <script type='text/javascript' src='js/queryForm.js'></script>";
include XOOPS_ROOT_PATH.'/header.php';

# Get the Group Category
$sqlGroup = "SELECT DISTINCT(GroupCategory) FROM ".$xoopsDB->prefix('torch_group_lists');
$result = $xoopsDB->query($sqlGroup);
$groupArr = array();
while( $tempRow = $xoopsDB->fetchrow($result) ){
  $groupArr[$tempRow[0]] = $tempRow[0];
}
$groupArr['暫無小組'] = '暫無小組';
$xoopsTpl->assign('groupArr', $groupArr);

# Assign content into template
$xoopsTpl->assign('action', $action);
$xoopsTpl->assign('tableTitle', $formname);
$xoopsTpl->assign('buttonType', 'submit');
$xoopsTpl->assign('submitName', '查詢');

include XOOPS_ROOT_PATH.'/footer.php';
?>
