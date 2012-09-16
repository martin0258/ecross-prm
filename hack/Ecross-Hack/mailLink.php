<?php
/** 
 * Description:
 * 點新人通知郵件裡的連結會連到此頁，分析連結後，再轉到queryResult.php顯示小組新人。
 * @author          Martin Ku
 * @package         page
 * @version         2012/02/27 File created.
 */

# 有query string的話嘗試解密，解密成功的話存SESSION
include '../mainfile.php';
require_once 'function/encrypt.php';
if(isset($_GET['l'])){
  $list_encrypt = str_replace(" ", "+", $_GET['l']);
  $list_decrypt = authcode($list_encrypt, 'DECODE');
  if($list_decrypt!=NULL){
    //解密成功
    $list = '';
    $IDlist = explode(',', $list_decrypt);
    foreach( $IDlist as $memberID ){ $list .= "'$memberID',"; }
    $list = substr($list, 0, strlen($list)-1);
    $_SESSION['newMemberList'] = $list;
  }
}

//error_log(print_r($_SESSION, true));
# 決定網頁去向
if(!$xoopsUser){  //尚未登入
  redirect_header(XOOPS_URL, 3, '你必須先登入，才能查看此頁面');
}else{
  redirect_header(XOOPS_URL.'/modules/torch_newmember/groupNewMember.php', 3, '');
}
?>
