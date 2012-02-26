<?php
include '../../mainfile.php';
$memberId = $_POST['memberId'];     //get the checkbox array
$list = array();
for($i=0;$i<count($memberId);$i++){
  $list[$i] = $memberId[$i];
}

//open CSV file
$filename = "tmpcsv/namelist_".date("YmdHis").".csv";
$fp = fopen($filename, "w");
fwrite($fp, "\xEF\xBB\xBF");    //utf-8 BOM, let the excel to read properly
fwrite($fp, "姓名,小組,手機,家裡電話,電子郵件,郵遞區號,地址\r\n");
$sql = $_SESSION['sqlString'];
$result = $xoopsDB->query($sql);
while($row = $xoopsDB->fetchRow($result)){
  if(in_array($row[7], $list)){
    //取得小組資訊
    $sql_group = "SELECT GroupLeaderName, GroupName FROM ".$xoopsDB->prefix("torch_grouplists")
      ." where GroupID = '$row[1]'";
    $return = $xoopsDB->query($sql_group);
    $row[1] = (mysql_num_rows($return)==0) ? '暫無小組' : mysql_result($return, 0)."-".mysql_result($return, 0, 1);

    //chr(127)，excel會把數字前面的0吃掉，因此在電話前面印一個看不到的字元
    $output_line = "";
    $row[2] = chr(127).$row[2];
    $row[3] = chr(127).$row[3];
    for( $i = 0 ; $i < 6 ; $i++){
      $output_line .= "\"$row[$i]\",";
    }
    $output_line .= "\"$row[$i]\"\r\n";
    fwrite($fp, $output_line);
  }
}
fclose($fp);
header("Location:$filename");
?>

