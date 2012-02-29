<?php
/** 
 * Description:
 * 儲存一筆編輯完成的訪談紀錄
 * @author Martin Ku
 * @package backend
 */
include '../../mainfile.php';

//檢查權限及是否登入
if($xoopsUser){
  if((!$_SESSION['mod2'] && !$_SESSION['mod3']) || !isset($_POST['serial'])){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}
else{ 
  redirect_header(XOOPS_URL, 3, _MD_NOT_LOGIN);
}

$startDate = $_POST['date'];
$recentSituation = $_POST['recentSituation'];
$serial = $_POST['serial'];
$carer = $_POST['carer'];

$sql = 
  "Update ".$xoopsDB->prefix("torch_pastoral_records").
  " SET RecordTime = '$startDate', 
  RecentSituation = '$recentSituation',
  Carer = '$carer'
  Where RecordSerial = '$serial'";
$result = $xoopsDB->query($sql);
$message = $result ? '更新成功' : '更新失敗，請聯絡程式人員';

redirect_header('contactList.php', 3, $message);
?>		
