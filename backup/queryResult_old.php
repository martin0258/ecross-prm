<?php
include '../../mainfile.php';
if($xoopsUser){
  if(!$_SESSION['mod2'] && !$_SESSION['mod3']){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}
else redirect_header(XOOPS_URL, 3, "尚未登入");
include XOOPS_ROOT_PATH.'/header.php';
require_once("include/pager.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel=stylesheet type="text/css" href="css/style.css">
<script type="text/javascript">
function submitForm(form, button){
  if(button.value=="訪談")
    form.action = "contactList.php";
  else if(button.value=="編輯")
    form.action = "editMember.php";
  else
    form.action = "viewMember.php";
  form.submit();
}

function gotoPage(page){
  location.href=('queryResult.php?page=' + page);
}
</script>
<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/numPerPage.js"></script>
</head>
<body>
<?php

$ifContact = $_POST['ifContact'];
$Name = $_POST['name'];
$StartDate = $_POST['startDate'];
$EndDate = $_POST['endDate'];
$CellGroupCtg = $_POST['cellgroupCtg'];
$CellGroup = $_POST['cellgroup'];
$flag = 0;

if($ifContact != '')
{
  $sqlstring = ($ifContact == 'no') ? ' ContactNumber = 0' : ' ContactNumber > 0';
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

if($CellGroup > 0 || ($CellGroupCtg == '暫無小組'))
{
  if($flag == 1)
  {
    $sqlstring .= " and GroupLists_GroupID  = $CellGroup";
  }
  else
  {
    $sqlstring = " GroupLists_GroupID  = $CellGroup";
  }
  $flag = 1;
}

$sql=
  "SELECT B.FirstVisitDate, B.ChineseName, B.EnglishName, B.GroupLists_GroupID,
  B.CellPhoneNumber, B.Email, CONCAT(B.MailingAddress_Country, B.MailingAddress_Township, 
  B.MailingAddress_Detail) AS Address, B.MemberID FROM ".$xoopsDB->prefix("torch_MemberInformation").
  " AS B LEFT JOIN ".$xoopsDB->prefix("torch_MemberContact")." AS A 
  ON A.MemberInformation_MemberID = B.MemberID";
if($flag == 1){
  $sql .= " WHERE $sqlstring";
}else if(isset($_SESSION['newMemberList']) && isset($_GET['l'])){
  //顯示通知信中提到的新人
  $sql .= " WHERE B.MemberID IN(" . $_SESSION['newMemberList'] . ")";
}
//從最新的資料開始顯示
$sql .= " ORDER BY FirstVisitDate DESC";

//返回上一次查詢結果
if(isset($_GET['r']) && isset($_SESSION['sqlString'])){
  $sql = $_SESSION['sqlString'];
}

//取得一頁幾筆
$num_per_page = 10;
if(isset($_GET['num_row'])){
  $sql = $_SESSION['sqlString'];
  $num_per_page = $_GET['num_row'];
  $_SESSION['num_row'] = $num_per_page;
}
if(isset($_SESSION['num_row']))$num_per_page = $_SESSION['num_row'];
//分頁設定，先取得?後的，代表第幾頁的變數
if(isset($_GET['page']))
  $page = $_GET['page'];
else{
  //預設一開始為第一頁
  $page = 1;
  if(isset($_SESSION['sqlString'])){
    unset($_SESSION['sqlString']);
  }
}
$a = new pager();                   //new新的object
$a->set_statement($sql);            //設定要查詢的sql指令
$a->set_num_rows($num_per_page);    //設定每頁幾筆資料
$a->set_link_per_page(10);          //設定一頁要有幾筆"XX頁"
$result = $a->get_page($page); 
$numOfPages = $a->how_many_pages(); //取得第幾頁,回傳值是執行完sql指令的資源識別字
if(mysql_num_rows($result)==0){
  echo "查不到任何資料，請試著更改查詢條件。";
}
else{
  //讓使用者決定一頁幾筆，用numPerPage.js處理
  echo "<form><select id='select_page'>";
  if($num_per_page==10)echo "<option value='10' selected='selected'>10筆</option>";
  else echo "<option value='10'>10筆</option>";
  if($num_per_page==20)echo "<option value='20' selected='selected'>20筆</option>";
  else echo "<option value='20'>20筆</option>";
  if($num_per_page==50)echo "<option value='50' selected='selected'>50筆</option>";
  else echo "<option value='50'>50筆</option>";
  echo "</select></form>";

  echo "<table class='qTable'><thead><tr>";
  //echo "<th align ='center'> 照片 </th>";
  echo "<th>第一次來教會日期</th>";
  echo "<th>中文姓名</th>";
  echo "<th>英文姓名</th>";
  echo "<th>小組</th>";
  echo "<th>手機號碼</th>";
  echo "<th>電子郵件</th>";
  echo "<th></th>";
  echo "</tr></thead><tbody>";

  //print 資料
  while($row = $xoopsDB->fetchRow($result)){
    echo "<tr>";

    //取得小組資訊
    $sql_group = "SELECT GroupLeaderName, GroupName FROM ".$xoopsDB->prefix("torch_grouplists")
      ." where GroupID = '$row[3]'";
    $return = $xoopsDB->query($sql_group);
    if(mysql_num_rows($return) == 0)$row[3] = '暫無小組';
    else $row[3] = mysql_result( $return, 0 ). "-". mysql_result( $return, 0, 1);
    for($i = 0 ; $i < 6 ; $i++){
      echo "<td>$row[$i]</td>";
    }
    echo "<td><form method='post'>";
    echo "<input type='hidden' name='memberID' value='$row[7]'>";
    $buttonValue = ($_SESSION['mod3']) ? '編輯' : '詳細';
    echo "<input type='button' value='$buttonValue' onClick='submitForm(this.form, this);'>";
    echo "&nbsp;<input type='button' value='訪談' onClick='submitForm(this.form, this);'>";
    echo "</form></td></tr>";
  }
  echo "</tbody></table>";
  $a->show_links("queryResult.php");      //顯示分頁連結

  echo "至第<select name='page' onchange='gotoPage(this.options[this.selectedIndex].value)' size='1'>";
  for($i=1;$i<=$numOfPages;$i++) 
  {
    if ($page==$i){
      echo "<option value=$i selected>$i";}
    else {
      echo "<option value=$i>$i";
    }			
  }
  echo "</select>頁";
}

include XOOPS_ROOT_PATH.'/footer.php';
?>
</body>
</html>
