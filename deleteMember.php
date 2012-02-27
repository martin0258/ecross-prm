<?php
/** 
 * Description:
 * 刪除勾選的新人。[queryResultDelete.php]的[刪除新人]按鈕之後端處理頁。
 * @author Martin Ku
 * @package backend
 */
include '../../mainfile.php';

#檢查權限及是否登入
if($xoopsUser){
  if(!$_SESSION['mod5']){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }else{
    # 檢查是否有勾選新人，沒有的話重新導向至首頁
    if(count($_POST['memberId'])<=0){ redirect_header(XOOPS_URL, 3, _NOPERM); }
  }
}
else { redirect_header(XOOPS_URL, 3, _MD_NOT_LOGIN); }

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

$message = $total_result ? '刪除資料成功' : '刪除資料失敗，請聯絡程式人員';
redirect_header('query.php?queryType=delete', 3, $message);
?>
