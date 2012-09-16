<?php
/* 
 * Description:
 * 從查詢結果點[訪談]進入此頁面。顯示某一新人的訪談記錄列表。
 * @author Martin Ku
 * @package page
 */
include '../../mainfile.php';

# 檢查權限及是否登入
if($xoopsUser){
  if(!$_SESSION['mod2'] && !$_SESSION['mod3']){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}
else{ redirect_header(XOOPS_URL, 3, _MD_NOT_LOGIN); }
if(!isset($_POST['memberID']) && !isset($_SESSION['contactMemberID'])){
  redirect_header(XOOPS_URL, 3, _NOPERM);
}
$memberID = isset($_POST['memberID']) ? $_POST['memberID'] : $_SESSION['contactMemberID'];
$_SESSION['contactMemberID'] = $memberID;

//Use smarty template
$xoopsOption['template_main'] = 'tableResult.html';
$xoopsOption['xoops_module_header'] = 
  "<link type='text/css' rel='stylesheet' href='css/redmond/jquery-ui-1.8.18.custom.css' />
   <link type='text/css' rel='stylesheet' href='css/torchStyle.css' />
   <script type='text/javascript' src='js/jquery-1.7.1.min.js'></script>
   <script type='text/javascript' src='js/jquery-ui-1.8.18.custom.min.js'></script>
   <script type='text/javascript'>
     $(document).ready(function(){
       $('.Button').button();
       //we must set this to let scroll bar to appear
       $('#xo-canvas-columns').css('table-layout', 'fixed');
     });
   </script>";
include XOOPS_ROOT_PATH.'/header.php';
include_once("class/pager.php");

$sql = 
  "SELECT CASE WHEN tm.ChineseName != '' THEN tm.ChineseName ELSE tm.EnglishName END AS NAME, 
   tp.RecordTime FROM "
  .$xoopsDB->prefix("torch_member_information").
  " tm LEFT JOIN ".$xoopsDB->prefix('torch_pastoral_records').
  " tp ON tp.MemberInformation_MemberID = tm.MemberID WHERE MemberID = '$memberID'";
$result = $xoopsDB->query($sql);
$row = $xoopsDB->fetchRow($result);
$memberName = $row[0];

$contactButton = "<form method='post' action='addContact.php'>
    <input type='hidden' name='memberID' value='$memberID'>
    <input class='importantButton' type='submit' value='新增訪談'>
    <input type='button' value='回上次查詢結果' onClick=\"window.location='queryResult.php?r=1'\">
    </form>";

if($row[1]==NULL){
  //無訪談紀錄
  $tableTitle = "訪談新人:$memberName";
  $xoopsTpl->assign('tableTitle', $tableTitle);
  $xoopsTpl->assign('contactButton', $contactButton);
  $xoopsTpl->assign('hasResult', false);
  echo _MD_NO_PASTORAL_RECORD;
}
else{
  //取得一頁幾筆資料
  $rows_per_page = array(10, 20, 50);
  $num_per_page = 10;
  if(isset($_GET['num_row'])){
    if(isset($_SESSION['sqlString']))$sql = $_SESSION['sqlString'];
    $num_per_page = in_array($_GET['num_row'], $rows_per_page) ? $_GET['num_row'] : 10;
    $_SESSION['num_row'] = $num_per_page;
  }else if(isset($_SESSION['num_row'])){
    $num_per_page = $_SESSION['num_row'];
  }

  //取得顯示頁數
  if(isset($_GET['page'])){
    $page = is_numeric($_GET['page']) ? $_GET['page'] : 1;
  }
  else{
    $page = 1; //預設顯示第一頁
  }
  
  $sql = 
    "SELECT RecordTime, Carer, RecentSituation, RecordSerial FROM "
    .$xoopsDB->prefix("torch_pastoral_records").
    " Where MemberInformation_MemberID ='$memberID' ORDER BY RecordTime DESC";

  $links_per_page = 10;
  $result_page = new pager();                          //new新的object
  $result_page->set_statement($sql);                   //設定要查詢的sql指令
  $result_page->set_num_rows($num_per_page);           //設定每頁幾筆資料
  $result_page->set_link_per_page($links_per_page);    //設定一頁要有幾筆"XX頁"
  $result = $result_page->get_page($page); 
  $numOfPages = $result_page->how_many_pages();        //取得第幾頁,回傳值是執行完sql指令的資源識別字
  
  $xoopsTpl->assign('hasResult', true);
  $formAction = 'editContact.php';
  $xoopsTpl->assign('formAction', $formAction);
  $tableTitle = "訪談新人:$memberName";
  $tableHead = array('訪談時間', '訪談者', '訪談內容', '');
  $xoopsTpl->assign('tableTitle', $tableTitle);
  $xoopsTpl->assign('tableHead', $tableHead);

  $tableBody = array();
  for ($i = 0; $i < mysql_num_rows($result); $i++) {
    $row = $xoopsDB->fetchRow($result);
    $tableBody[$i] = array();
    
    //時間顯示長度調整，訪談紀錄只顯示前40個字
    $row[0] = substr($row[0],0,10);                 
    $row[2] = mb_strlen($row[2], 'utf-8')>40 ? mb_substr($row[2],0,40, 'utf-8').'......' : $row[2];
    for($j = 0 ; $j < 3 ; $j++){
      array_push($tableBody[$i], $row[$j]);
    }

    $buttonCell = 
      "<form method='post' action='editContact.php'>
       <input type='hidden' name='serial' value='$row[3]'>
       <input type='submit' value='編輯'></form>";
    array_push($tableBody[$i], $buttonCell);
  }

  $xoopsTpl->assign('tableBody', $tableBody);
  $xoopsTpl->assign('pageLinks', $result_page->get_links('contactList.php'));
  $xoopsTpl->assign('contactButton', $contactButton);
  $xoopsTpl->assign('pages', range(1, $numOfPages));
  $xoopsTpl->assign('page', $page);
  $xoopsTpl->assign('rows_per_page_option', $rows_per_page);
  $xoopsTpl->assign('rows_per_page', $num_per_page);
}

include XOOPS_ROOT_PATH.'/footer.php';
?>
