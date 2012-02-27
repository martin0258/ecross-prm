<?php
/** 
 * Description:
 * 新人名單模組首頁
 * @author Martin Ku
 * @package page
 */
include 'include/encrypt.php';
$letAnonymousPass = false;
if(isset($_GET['l'])){
  $list_encrypt = str_replace(" ", "+", $_GET['l']);
  $list_decrypt = authcode($list_encrypt, 'DECODE');
  if($list_decrypt!=NULL){
    //解密成功
    $list = '';
    $IDlist = explode('j', $list_decrypt);
    foreach( $IDlist as $memberID ){ $list .= "'$memberID',"; }
    $letAnonymousPass = true;
  }
}

include '../../mainfile.php';

if($letAnonymousPass){
  $_SESSION['newMemberList'] = substr($list, 0, strlen($list)-1);
}
//error_log( print_r($_SESSION, true));

$_SESSION['mod1'] = false;
$_SESSION['mod2'] = false;
$_SESSION['mod3'] = false;
$_SESSION['mod4'] = false;
$_SESSION['mod5'] = false;
$_SESSION['mod6'] = false;
$gperm_itemid = array
  (
    "建檔"=>"1",
    "查詢"=>"2",
    "查詢修改"=>"3",
    "批次匯入"=>"4",
    "查詢刪除"=>"5",
    "地址輸出"=>"6",
  );
if($xoopsUser){
  //取得使用者所屬的群組，是個陣列
  $groups_id_arr =& $xoopsUser->getGroups();
  $gperm_name="torch_1";
  $gperm_modid=$xoopsModule->mid();
  //利用include/functions.php的xoops_gethandler建立$gperm_handler物件
  $gperm_handler =&xoops_gethandler('groupperm');
  foreach($groups_id_arr as $gperm_groupid){
    foreach($gperm_itemid as $itemid){
      $CR=$gperm_handler->checkRight($gperm_name, $itemid, $gperm_groupid, $gperm_modid);
      if($CR){
        $_SESSION["mod$itemid"] = true;
      }
    }
  }
}
else {
  redirect_header(XOOPS_URL, 3, _MD_NOT_LOGIN);
}

if(isset($_SESSION['newMemberList'])){
  header("Location:queryResult.php?l=1");
}else{
  header("Location:query.php");
}

?>

