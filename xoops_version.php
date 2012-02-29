<?php
/**
 * Description:
 * XOOPS模組基本內容設定
 * @author          Martin Ku
 * @package         xoops
 * @version         2012/02/28 Add mod7:mailLink.php.
 */
$modversion['name'] = '新人名單模組';
$modversion['version'] = '4.1';
$modversion['description'] = '火把行道會新人名單模組';
$modversion['credits'] = 'Torch Church';
$modversion['author'] = 'Torch Church';
$modversion['official'] = 0;
$modversion['image'] = 'images/logo.jpg';
$modversion['dirname'] = 'torch_newmember';

# Database things
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables']['0'] = 'torch_specialty_lists';
$modversion['tables']['1'] = 'torch_service_lists';
$modversion['tables']['2'] = 'torch_group_lists';
$modversion['tables']['3'] = 'torch_member_information';
$modversion['tables']['4'] = 'torch_member_service';
$modversion['tables']['5'] = 'torch_member_specialty';
$modversion['tables']['6'] = 'torch_pastoral_records';
$modversion['tables']['7'] = 'torch_code';
$modversion['tables']['8'] = 'torch_system_variable';

# Insert data into table
$modversion['onInstall'] = 'function/install_funcs.php';
# Delete tables that cannot delete due to key constraint
$modversion['onUninstall'] = 'function/uninstall_funcs.php';

# Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

# Templates
$modversion['templates'][1]['file'] = 'addMemberForm.html';
$modversion['templates'][1]['description'] = 'The template using for adding memberdata';
$modversion['templates'][2]['file'] = 'contactForm.html';
$modversion['templates'][2]['description'] = 'The template using for adding and editing contact records';
$modversion['templates'][3]['file'] = 'detailForm.html';
$modversion['templates'][3]['description'] = 'The template using for editing memberdata';
$modversion['templates'][4]['file'] = 'queryForm.html';
$modversion['templates'][4]['description'] = 'The template using for querying memberdata';
$modversion['templates'][5]['file'] = 'tableResult.html';
$modversion['templates'][5]['description'] = 'The template using for query Result';

# Search
//$modversion['hasSearch'] = 0;

# Main Menu
$modversion['hasMain'] = 1;

if(isset($_SESSION['mod1'])){
  if($_SESSION['mod1']==true){
    $modversion['sub'][0]['name'] = '建檔';
    $modversion['sub'][0]['url'] = 'addMember.php';
  }
  if($_SESSION['mod2']==true || $_SESSION['mod3']==true){
    if($_SESSION['mod3']==true){ $modversion['sub'][1]['name'] = '查詢修改'; }
    else { $modversion['sub'][1]['name'] = '查詢'; }
    $modversion['sub'][1]['url'] = 'query.php';
  }
  if($_SESSION['mod4']==true){
    $modversion['sub'][2]['name'] = '批次匯入';
    $modversion['sub'][2]['url'] = 'importCSV.php';
  }
  if($_SESSION['mod5']==true){
    $modversion['sub'][3]['name'] = '查詢刪除';
    $modversion['sub'][3]['url'] = 'query.php?queryType=delete';
  }
  if($_SESSION['mod6']==true){
    $modversion['sub'][4]['name'] = '地址輸出';
    $modversion['sub'][4]['url'] = 'query.php?queryType=address';
  }
  if($_SESSION['mod7']==true){
    $modversion['sub'][5]['name'] = '小組新人';
    $modversion['sub'][5]['url'] = 'mailLink.php';
  }
}
?>
