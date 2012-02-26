<?php
include '../../mainfile.php';
if($xoopsUser){
  if((!$_SESSION['mod2'] && !$_SESSION['mod3']) || !isset($_POST['memberID'])){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}
else redirect_header(XOOPS_URL, 3, _MD_NOT_LOGIN);

$memberID = $_POST['memberID'];
$RecordTime = $_POST['date'];
$Carer = $_POST['carer'];
$recentSituation = $_POST['recentSituation'];

$sql = "Insert Into ".$xoopsDB->prefix("torch_pastoralrecords")." (RecordTime,MemberInformation_MemberID,Carer,RecentSituation) 
  VALUES('$RecordTime','$memberID','$Carer','$recentSituation')";
$result = $xoopsDB->query($sql);

if($result)redirect_header("contactList.php", 3, "更新成功");
else redirect_header("contactList.php", 3, "新建資料失敗，請聯絡程式人員");
?>		
