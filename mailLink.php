<?php
/** 
 * Description:
 * 點新人通知郵件裡的連結會連到此頁，分析連結後，再轉到queryResult.php顯示小組新人。
 * @author          Martin Ku
 * @package         page
 * @version         2012/02/27 File created.
 */
require_once 'function/encrypt.php';
$letAnonymousPass = false;
if(isset($_GET['l'])){
  $list_encrypt = str_replace(" ", "+", $_GET['l']);
  $list_decrypt = authcode($list_encrypt, 'DECODE');
  if($list_decrypt!=NULL){
    //解密成功，設定$letAnonymousPass=true讓模組暫時可匿名使用(才會執行include後面的程式碼)
    $list = '';
    $IDlist = explode(',', $list_decrypt);
    foreach( $IDlist as $memberID ){ $list .= "'$memberID',"; }
    $list = substr($list, 0, strlen($list)-1);
    $letAnonymousPass = true;
  }
}

include_once 'include\checkPermission.php';
# 跑完一遍XOOPS的檢查後，SESSION會被清空
# 故需於此後設定，才會登入後轉向此頁(詳見checkLogin.php)。
if($letAnonymousPass){ 
  //解密成功才會有SESSION跟左邊的[小組新人]選項
  $_SESSION['newMemberList'] = $list;
  $_SESSION['mod7'] = true;
}else{ $_SESSION['mod7'] = isset($_SESSION['newMemberList']) ? true:false; }

if($xoopsUser){
  if(isset($_SESSION['newMemberList'])){
    redirect_header('queryResult.php?l=1', 3, '以下是本次小組新人<br>要記得聯絡喔:)'); 
  }else{ redirect_header('index.php', 3, _NOPERM); }
}
else { redirect_header(XOOPS_URL, 3, _MD_NOT_LOGIN); }
?>
