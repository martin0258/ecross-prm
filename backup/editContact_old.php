<?php
include '../../mainfile.php';
if($xoopsUser){
  if(!$_SESSION['mod2'] && !$_SESSION['mod3']){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}
else redirect_header(XOOPS_URL, 3, "尚未登入");
include XOOPS_ROOT_PATH.'/header.php';
include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");

$serial = $_POST['serial'];	
$sql =
  "SELECT COALESCE(tm.ChineseName, tm.EnglishName) AS NAME, tp.RecordTime, tp.Carer, tp.RecentSituation ".
  "FROM ".$xoopsDB->prefix("torch_pastoralrecords").
  " tp INNER JOIN ".$xoopsDB->prefix('torch_memberinformation').
  " tm ON tp.MemberInformation_MemberID = tm.MemberID WHERE tp.RecordSerial = '$serial'";
$result = $xoopsDB->query($sql);
$row = $xoopsDB->fetchrow($result);
$form = new XoopsThemeForm("編輯訪談紀錄", 'form', 'editContactProcess.php', 'post', true);
$form->addElement(new XoopsFormHidden('serial', $serial));
$form->addElement(new XoopsFormLabel("新人姓名", $row[0]));
$form->addElement(new XoopsFormTextDateSelect('訪談時間', 'startDate', 15, $row[1]));
$form->addElement(new XoopsFormLabel("訪談者：", $row[2])); 
$form->addElement(new XoopsFormTextArea("訪談內容：", "recentSituation", "$row[3]",5, 50, ''));
$form->addElement(new XoopsFormButton('', 'submit_name', "送出", 'submit'));
$form->display();

include XOOPS_ROOT_PATH.'/footer.php';
?>	
