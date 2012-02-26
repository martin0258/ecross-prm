<?php
include '../../mainfile.php';
include XOOPS_ROOT_PATH.'/header.php';

if($xoopsUser){
  if(!$_SESSION['mod2'] && !$_SESSION['mod3']){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}
else redirect_header(XOOPS_URL, 3, "尚未登入");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel=stylesheet type="text/css" href="css/style.css">
</head>
<?php
if(!isset($_SESSION['memContactID']) && !isset($_POST['memberID']))
  redirect_header(XOOPS_URL, 3, _NOPERM);
if(isset($_POST['memberID']))$_SESSION['memContactID'] = $_POST['memberID'];
$memberID = $_SESSION['memContactID'];

$sql = 
  "SELECT CASE WHEN tm.ChineseName != '' THEN tm.ChineseName ELSE tm.EnglishName END AS NAME, tct.ContactNumber FROM "
  .$xoopsDB->prefix("torch_membercontact").
  " tct INNER JOIN ".$xoopsDB->prefix('torch_memberinformation').
  " tm ON tct.MemberInformation_MemberID = tm.MemberID WHERE MemberInformation_MemberID = '$memberID'";
$result = $xoopsDB->query($sql);
$row = $xoopsDB->fetchRow($result);
if($row[1]>0)
{
  //有訪談記錄
  //echo "姓名:$row[0] (被訪談".$row[1]."次)";
  echo "新人姓名：$row[0]";
  echo "<table class='qTable'><tr>";
  echo "<th>訪談時間</th>";
  echo "<th>訪談者</th>";
  echo "<th width='60%'>訪談內容</th>";
  echo "<th width='1%'></th>";
  echo "</tr>";
  $sql = 
    "SELECT A.RecordTime, A.Carer, A.RecentSituation, A.RecordSerial FROM "
    .$xoopsDB->prefix("torch_pastoralrecords")." A Inner Join "
    .$xoopsDB->prefix("torch_memberinformation")." B ON B.MemberID = A.MemberInformation_MemberID ".
    "Where A.MemberInformation_MemberID ='$memberID' ORDER BY A.RecordTime DESC";
  $result = $xoopsDB->query($sql);
  while($row = $xoopsDB->fetchRow($result)){
    echo "<tr>";
    //只印出YYYY-MM-DD
    $row[0] = substr($row[0],0,10);
    for($i = 0 ; $i < 3 ; $i++){
      echo "<td>$row[$i]</td>";
    }
    echo "<td><form method='post' action='editContact.php'>";
    echo "<input type='hidden' name='serial' value='$row[3]'>";
    echo "<input type='submit' value='編輯'></form></td>";
    echo "</tr>";
  }
  echo "</table>";
}
else
{
  echo "目前尚無此新人之訪談紀錄";
}
echo "<table><tr><td><form method ='post' action='addContact.php'>";
echo "<input type='hidden' name='memberID' value='$memberID'>";
echo "<input type='submit' value='新增訪談紀錄'>&nbsp;";
echo "<input type='button' value='回上次查詢結果' 
  OnClick=\"window.location='queryResult.php?r=1'\"></form></td></tr>";
echo "</table>";

include XOOPS_ROOT_PATH.'/footer.php';
?>	
</html>
