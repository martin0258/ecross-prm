<?php
/** 
 * Description:
 * 新增一筆訪談紀錄的頁面
 * @author Martin Ku
 * @package page
 */
include '../../mainfile.php';
if($xoopsUser){
  if((!$_SESSION['mod2'] && !$_SESSION['mod3']) || !isset($_POST['memberID'])){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}
else{ redirect_header(XOOPS_URL, 3, _MD_NOT_LOGIN); }

//use module template
$xoopsOption['template_main'] = "contactForm.html";
$xoopsOption['xoops_module_header'] = 
  "<link type='text/css' href='css/redmond/jquery-ui-1.8.18.custom.css' rel='stylesheet' />	  
  <link rel=stylesheet type='text/css' href='css/torchStyle.css'>
  <script type='text/javascript' src='js/jquery-1.7.1.min.js'></script>
  <script type='text/javascript' src='js/jquery-ui-1.8.18.custom.min.js'></script>
  <script type='text/javascript' src='js/jquery.ui.datepicker-zh-TW.js'></script>
  <script type='text/javascript' src='js/torch.js'></script>
  <script type='text/javascript' src='js/formFocus.js'></script>
  <script type='text/javascript'>
    $(document).ready(function(){
      $(':text').addClass('text');
      $('.datepicker').datepicker({
        showAnim: 'show',
        dateFormat: 'yy-mm-dd',
        showOn: 'both',
        buttonImageOnly: false
      }); 
      $('#date').datepicker('setDate', new Date());
    });
  </script>";
include XOOPS_ROOT_PATH.'/header.php';

$uname = $xoopsUser->uname();
$memberID = $_POST['memberID'];
$sql = 
  "Select CASE WHEN ChineseName != '' THEN ChineseName ELSE EnglishName END AS NAME FROM "
  .$xoopsDB->prefix("torch_member_information")." Where MemberID = '$memberID'";
$result = $xoopsDB->query($sql);
$row = $xoopsDB->fetchrow($result);
$memberName = $row[0];

$formAction = 'addContactProcess.php';
$tableTitle = '新增訪談紀錄';
$xoopsTpl->assign('action', $formAction);
$xoopsTpl->assign('tableTitle', $tableTitle);
$xoopsTpl->assign('memberID', $memberID);
$xoopsTpl->assign('memberName', $memberName);
$xoopsTpl->assign('carer', $uname);

include XOOPS_ROOT_PATH.'/footer.php';
?>	
