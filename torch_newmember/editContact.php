<?php
include '../../mainfile.php';
if($xoopsUser){
  if((!$_SESSION['mod2'] && !$_SESSION['mod3']) || !isset($_POST['serial'])){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}
else redirect_header(XOOPS_URL, 3, _MD_NOT_LOGIN);

//use module template
$xoopsOption['template_main'] = "contactForm.html";
$xoopsOption['xoops_module_header'] = 
  "<link type='text/css' href='css/redmond/jquery-ui-1.8.16.custom.css' rel='stylesheet' />	  
  <link rel=stylesheet type='text/css' href='css/torchStyle.css'>
  <script type='text/javascript' src='js/jquery-1.7.1.min.js'></script>
  <script type='text/javascript' src='js/jquery-ui-1.8.17.custom.min.js'></script>
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
    });
  </script>";
include XOOPS_ROOT_PATH.'/header.php';

$serial = $_POST['serial'];	
$sql =
  "SELECT CASE WHEN tm.ChineseName != '' THEN tm.ChineseName ELSE tm.EnglishName END AS NAME,
  tp.RecordTime, tp.Carer, tp.RecentSituation ".
  "FROM ".$xoopsDB->prefix("torch_pastoralrecords").
  " tp INNER JOIN ".$xoopsDB->prefix('torch_memberinformation').
  " tm ON tp.MemberInformation_MemberID = tm.MemberID WHERE tp.RecordSerial = '$serial'";
$result = $xoopsDB->query($sql);
$row = $xoopsDB->fetchrow($result);
$memberName = $row[0];
$date = substr($row[1],0,10);
$carer = $row[2];
$recentSituation = $row[3];

$formAction = 'editContactProcess.php';
$tableTitle = '編輯訪談紀錄';
$xoopsTpl->assign('action', $formAction);
$xoopsTpl->assign('tableTitle', $tableTitle);
$xoopsTpl->assign('serial', $serial);
$xoopsTpl->assign('memberName', $memberName);
$xoopsTpl->assign('carer', $carer);
$xoopsTpl->assign('date', $date);
$xoopsTpl->assign('recentSituation', $recentSituation);

include XOOPS_ROOT_PATH.'/footer.php';
?>	
