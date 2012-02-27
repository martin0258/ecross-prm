<?php
/** 
 * Description:
 * This file is the main page of the admin section.
 * @author Martin Ku
 * @package page
 */

/**
 * There is an uncaught reference error:
 * activateMenu("nav") is not defined */
include '../../../include/cp_header.php';
include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
include_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';
$module_id = $xoopsModule->getVar('mid');   //what does this line do?

$item_list = array(
  '1' => '建檔',
  '2' => '查詢',
  '3' => '查詢修改',
  '4' => '批次匯入',
  '5' => '查詢刪除',
  '6' => '地址輸出'
);

# 群組權限設定表單
//title_of_form：子群組網頁顯示名稱
$title_of_form = '新人名單模組權限設定';
//$perm_name子群組名稱（會寫入group_permission的 gperm_name欄位）
$perm_name = 'torch_1';
//新增一個xoops群組權限設定表單
$forma = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc);
//將子功能陣列加入表單物件
foreach ($item_list as $item_id => $item_name) {
  $forma->addItem($item_id, $item_name);
}

$sql = "SELECT tg.GroupLeaderMail FROM ".$xoopsDB->prefix('torch_GroupLists').
  " tg INNER JOIN " .$xoopsDB->prefix('torch_SystemVariable').
  " ts ON ts.Value = tg.GroupID AND ts.VariableName='TestGroupID'";
$result = $xoopsDB->query($sql);
$testOn = (mysql_num_rows($result)>0);
$email = ($testOn) ? mysql_result($result, 0) : '';
# 建立虛擬小組長，測試Email通知功能
$formAddEmail = new XoopsThemeForm('小組長通知功能測試', 'form1', 'addTestEmail.php');
$Tray1 = new XoopsFormElementTray('Email', '', 'name', true);
$Tray1->addElement(new XoopsFormText('', 'email', 30, 70, $email),true);
$Tray1->addElement(new XoopsFormLabel('','虛擬小組的分類是「測試」'));
$formAddEmail->addElement($Tray1);
$Tray2 = new XoopsFormElementTray('', '', 'name', true);
$Tray2->addElement(new XoopsFormButton('', 'submitButton', '確定', 'submit'));
if($testOn){
  $Tray2->addElement(new XoopsFormButton('', 'closeButton', '關閉測試', 'button', 'closeButton'));
  $Tray2->addElement(new XoopsFormHidden('closeFlag', '0', 'closeFlag'));
}
$formAddEmail->addElement($Tray2);

xoops_cp_header();
echo $forma->render();
echo '<BR>';
echo $formAddEmail->render();
xoops_cp_footer();
?>
<script type='text/javascript'>
$(document).ready(function(){
  //We use the jquery library included by xoops
  $('#closeButton').click(function(){
    $('#closeFlag').val('true');
    $('#form1').submit();
  });
});
</script>
