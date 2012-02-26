<?php
include '../../mainfile.php';
include XOOPS_ROOT_PATH.'/header.php';
include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");

$uname = $xoopsUser->uname();
$memberID = $_POST['memberID'];
$sql = 
  "Select CASE WHEN ChineseName != '' THEN ChineseName ELSE EnglishName END AS NAME FROM "
  .$xoopsDB->prefix("torch_memberinformation")." Where MemberID = '$memberID'";
$result = $xoopsDB->query($sql);
$row = $xoopsDB->fetchrow($result);
$today=getdate();

$form = new XoopsThemeForm("新增訪談紀錄", 'form', "addContactProcess.php", 'post', true);
$form->addElement(new XoopsFormHidden('memberID', $memberID));
$form->addElement(new XoopsFormLabel("新人姓名", $row[0]));
$form->addElement(new XoopsFormTextDateSelect('訪談時間', 'startDate', 15, $today));

//取出所有同工姓名當作訪談者候選
$Carer = new XoopsFormSelect("訪談者", "Carer", $uname ); //預設帶入訪談者的編號
$module_handler = & xoops_gethandler('member');
$users = $module_handler->getUsers();
foreach($users as $user){
  if($user->getVar('loginname'))$Carer->addOption($user->getVar('loginname'), $user->getVar('loginname'));
  else $Carer->addOption($user->getVar('uname'), $user->getVar('uname'));
}
$form->addElement($Carer);
$form->addElement(new XoopsFormTextArea("訪談內容：", "recentSituation", "",5, 50, ''));
$form->addElement(new XoopsFormButton('', 'submit_name', "儲存", 'submit'));
$form->display();

include XOOPS_ROOT_PATH.'/footer.php';
?>	
