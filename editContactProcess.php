<?php
include '../../mainfile.php';
if($xoopsUser){
  if((!$_SESSION['mod2'] && !$_SESSION['mod3']) || !isset($_POST['serial'])){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}
else redirect_header(XOOPS_URL, 3, _MD_NOT_LOGIN);

$startDate = $_POST['date'];
$recentSituation = $_POST['recentSituation'];
$serial = $_POST['serial'];
$carer = $_POST['carer'];

$sql = 
  "Update ".$xoopsDB->prefix("torch_pastoralrecords").
  " SET RecordTime = '$startDate', 
  RecentSituation = '$recentSituation',
  Carer = '$carer'
  Where RecordSerial = '$serial'";
$result = $xoopsDB->query($sql);

if($result==true)redirect_header("contactList.php", 3, "更新成功");
else redirect_header("contactList.php", 3, "更新失敗，請聯絡程式人員");
?>		
