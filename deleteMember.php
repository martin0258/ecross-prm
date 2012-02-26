<?php
include '../../mainfile.php';
if($xoopsUser){
  if(!$_SESSION['mod5']){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}
else redirect_header(XOOPS_URL, 3, _MD_NOT_LOGIN);

$total_result = true;
$memberIDarr = $_POST['memberId'];     //get the checkbox array
foreach ($memberIDarr as $memberID) {
  //有照片的話刪除
  $sql = 
    "Select PictureSavingPath From ".$xoopsDB->prefix("torch_memberinformation").
    " Where MemberID=$memberID";
  $result = $xoopsDB->query($sql);
  $path = mysql_result($result, 0);
  if($path!=null && file_exists($path))unlink($path);

  //刪除訪談記錄
  $sql = 
    "Delete From ".$xoopsDB->prefix("torch_pastoralrecords").
    " Where MemberInformation_MemberID=$memberID";
  $delete_care_result = $xoopsDB->query($sql);

  //刪除會員資料
  $sql = "Delete From ".$xoopsDB->prefix("torch_memberinformation")." Where MemberID=$memberID";
  $delete_info_result = $xoopsDB->query($sql);

  $total_result &= $delete_care_result && $delete_info_result;
}
if($total_result)redirect_header("query.php?queryType=delete", 3, "刪除資料成功");
else redirect_header("query.php?queryType=delete", 3, "刪除資料失敗，請聯絡程式人員");
?>
