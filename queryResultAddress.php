<?php
/* 
 * Description:
 * [地址輸出]的結果頁面
 * @author Martin Ku
 * @package page
 */
include '../../mainfile.php';

//檢查權限及是否登入
if($xoopsUser){
  if(!$_SESSION['mod6']){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}else{ redirect_header(XOOPS_URL, 3, _MD_NOT_LOGIN); }

//Use smarty template
$xoopsOption['template_main'] = "tableResult.html";
$xoopsOption['xoops_module_header'] = 
  "<link type='text/css' rel='stylesheet' href='css/redmond/jquery-ui-1.8.18.custom.css' />
   <link type='text/css' rel='stylesheet' href='css/torchStyle.css' />
   <script type='text/javascript' src='js/jquery-1.7.1.min.js'></script>
   <script type='text/javascript' src='js/jquery-ui-1.8.18.custom.min.js'></script>
   <script type='text/javascript' src='js/checkBox.js'></script>
   <script type='text/javascript' src='js/torch.js'></script>
   <script type='text/javascript'>
     $(document).ready(function(){
       $('.Button').button();
       //we must set this to let scroll bar to appear
       $('#xo-canvas-columns').css('table-layout', 'fixed');
     });
   </script>";
include XOOPS_ROOT_PATH.'/header.php';
include_once("class/pager.php");

$ifContact=$_POST['ifContact'];
$Name = $_POST['name'];
$StartDate = $_POST['startDate'];
$EndDate = $_POST['endDate'];
$CellGroupCtg = $_POST['group_select1'];
$CellGroup = $_POST['group_select2'];
$flag = 0;

if($ifContact != "")
{
  $contactSQL = " SELECT DISTINCT MemberInformation_MemberID FROM "
    .$xoopsDB->prefix('torch_pastoralrecords');
  $sqlstring = ($ifContact == 'no') ? " MemberID NOT IN($contactSQL) " : " MemberID IN($contactSQL) ";
  $flag=1;
}

if($Name != '')
{
  //判斷是中文還是英文
  if (mb_strlen($Name,"Big5") == strlen($Name)){
    $nameSql = " EnglishName like ";
  }else{
    $nameSql = " ChineseName like ";
  }
  if($flag == 1)
  {
    $sqlstring .= " and$nameSql'%$Name%'";
  }
  else
  {
    $sqlstring = "$nameSql'%$Name%'";
  }
  $flag = 1;
}

if($StartDate != '' or $EndDate != '')
{
  //有這個查詢條件的話
  if($flag == 1)
  {
    $sqlstring .= " and (FirstVisitDate = '$StartDate' or FirstVisitDate > '$StartDate') and (FirstVisitDate < '$EndDate' or FirstVisitDate = '$EndDate') ";
  }
  else
  {
    $sqlstring = " (FirstVisitDate = '$StartDate' or FirstVisitDate > '$StartDate') and (FirstVisitDate < '$EndDate' or FirstVisitDate = '$EndDate') ";
  }
  $flag = 1;
}
#小組條件搜尋
if($CellGroup!='')
{
  //搜尋特定一個小組
  $sqlstring .= ($flag==1) ? " AND GroupLists_GroupID=$CellGroup " : " GroupLists_GroupID=$CellGroup ";
  $flag = 1;
}else if($CellGroupCtg == '暫無小組'){
  //搜尋沒有小組的人
  $sqlstring .= ($flag==1) ? " AND GroupLists_GroupID IS NULL " : " GroupLists_GroupID IS NULL";
  $flag = 1;
}else if($CellGroupCtg != '' && $CellGroup==''){
  //搜尋一整個牧區
  $strGroupsSQL = "SELECT GroupID FROM ".$xoopsDB->prefix('torch_GroupLists')." WHERE GroupCategory='$CellGroupCtg'";
  $result = $xoopsDB->query($strGroupsSQL);
  $groupIDList = '';
  while( $row = $xoopsDB->fetchrow($result) ){
    $groupIDList .= ($row[0].',');
  }
  $groupIDList = substr($groupIDList, 0, strlen($groupIDList)-1);
  $sqlstring .= ($flag==1) ? " and GroupLists_GroupID IN ($groupIDList) " : " GroupLists_GroupID IN ($groupIDList) ";
  $flag = 1;
}

$sql =
  "Select CASE WHEN ChineseName != '' THEN ChineseName ELSE EnglishName END AS NAME,
  GroupLists_GroupID, CellPhoneNumber, HomePhoneNumber, Email,
  MailingAddress_ZipCode, CONCAT(MailingAddress_Country, MailingAddress_Township,
  MailingAddress_Detail) AS Address, MemberID, FirstVisitDate FROM "
  .$xoopsDB->prefix("torch_MemberInformation");
if($flag == 1)
{
  $sql .= " WHERE $sqlstring";
}
//根據到訪日期排序，從最新的資料開始顯示，根據小組作Group By
$sql .= " ORDER BY GroupLists_GroupID ASC, FirstVisitDate DESC";

//返回上一次查詢結果
if((isset($_GET['r']) || isset($_GET['page'])) && isset($_SESSION['sqlString'])){
  $sql = $_SESSION['sqlString'];
}

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
$_SESSION['sqlString'] = $sql;    //儲存此次查詢SQL

$links_per_page = 10;
$result_page = new pager();                          //new新的object
$result_page->set_statement($sql);                   //設定要查詢的sql指令
$result_page->set_num_rows($num_per_page);           //設定每頁幾筆資料
$result_page->set_link_per_page($links_per_page);    //設定一頁要有幾筆"XX頁"
$result = $result_page->get_page($page); 
$numOfPages = $result_page->how_many_pages();        //取得第幾頁,回傳值是執行完sql指令的資源識別字

if(mysql_num_rows($result)==0){
  echo _MD_NO_RESULT;
  $xoopsTpl->assign('hasResult', false);
}
else{
  $xoopsTpl->assign('hasResult', true);
  $tableTitle = '地址輸出';
  $formAction = 'exportCSV.php';
  $formOnsubmit = 'return countChecked("輸出");';
  $checkAll = "<input type='checkbox' name='checkAll' id='checkAll' onClick='checkAllAction();'>";
  $xoopsTpl->assign('formAction', $formAction);
  $xoopsTpl->assign('formOnsubmit', $formOnsubmit);
  $tableHead = array($checkAll, '姓名', '小組', '手機',
    '家裡電話', '電子郵件', '郵遞區號', '地址');
  $xoopsTpl->assign('tableTitle', $tableTitle);
  $xoopsTpl->assign('tableHead', $tableHead);

  $tableBody = array();
  for ($i = 0; $i < mysql_num_rows($result); $i++) {
    $row = $xoopsDB->fetchRow($result);
    $tableBody[$i] = array();

    //取得小組資訊
    $sql_group = "SELECT GroupLeaderName, GroupName FROM ".$xoopsDB->prefix("torch_grouplists")
      ." where GroupID = '$row[1]'";
    $return = $xoopsDB->query($sql_group);
    $row[1] = (mysql_num_rows($return)==0) ? 
      '暫無小組' : mysql_result($return, 0)."-".mysql_result($return, 0, 1); 

    $firstCell = "<input type='checkbox' name='memberId[]' value='$row[7]'>";
    array_push($tableBody[$i], $firstCell);
    for($j = 0 ; $j < 7 ; $j++){
      array_push($tableBody[$i], $row[$j]);
    }
  }
  $tableFoot = "<tfoot><tr><td colspan=9><input type='submit' value='輸出CSV'></td></tr></tfoot>";
  $xoopsTpl->assign('tableBody', $tableBody);
  $xoopsTpl->assign('tableFoot', $tableFoot);
  $xoopsTpl->assign('pageLinks', $result_page->get_links('queryResultAddress.php'));
  $xoopsTpl->assign('pages', range(1, $numOfPages));
  $xoopsTpl->assign('page', $page);
  $xoopsTpl->assign('rows_per_page_option', $rows_per_page);
  $xoopsTpl->assign('rows_per_page', $num_per_page);
}
include XOOPS_ROOT_PATH.'/footer.php';
?>
