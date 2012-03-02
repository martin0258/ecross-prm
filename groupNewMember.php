<?php
/** 
 * Description:
 * 未來希望於此顯示小組新人列表。
 * 目前是透過 Ecross-Extra/mailLink.php 轉到這，再轉到queryResult.php顯示小組新人。
 *
 * @author          Martin Ku
 * @package         page
 * @version         2012/03/02 Simplify the usage.
 */

# 檢查模組各項功能權限
include_once 'include/checkPermission.php';

# 決定網頁去向
if(!$xoopsUser){  //尚未登入
  redirect_header(XOOPS_URL, 3, _MD_NOT_LOGIN);
}else{
  if(isset($_SESSION['newMemberList'])){
    redirect_header('queryResult.php?l=1', 3, '以下是本次小組新人<br>要記得聯絡喔:)'); 
  }else{ include_once 'include/groupNewMemberInfo.php'; }
}
?>
