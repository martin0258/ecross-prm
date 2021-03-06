<?php
/** 
 * Description:
 * 檢查模組各項功能的權限，調整左邊的模組選單。
 * @author            Martin Ku
 * @package           page
 * @version           2012/02/27 File created.
 */
include dirname(dirname(dirname(dirname(__FILE__)))).'/mainfile.php';

$_SESSION['mod1'] = false;
$_SESSION['mod2'] = false;
$_SESSION['mod3'] = false;
$_SESSION['mod4'] = false;
$_SESSION['mod5'] = false;
$_SESSION['mod6'] = false;
$_SESSION['mod7'] = true;
$gperm_itemid = array
  (
    '建檔'=>'1',
    '查詢'=>'2',
    '查詢修改'=>'3',
    '批次匯入'=>'4',
    '查詢刪除'=>'5',
    '地址輸出'=>'6'
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
      if($CR){ $_SESSION["mod$itemid"] = true; }
    }
  }
}
?>
