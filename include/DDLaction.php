<?php
/** 
 * Description:
 * This page would return JSON object to AJAX request.
 * Now we have two usages:
 *    1. 縣市->鄉鎮市區
 *    2. 牧區->小組
 * @author Martin Ku
 * @package ajax
 */
include '../../mainfile.php';

$data = array();
if ($_GET['zipCountry']!="") {
  // 當使用者選擇縣市後，填入對應的鄉鎮市區
  $country = $_GET['zipCountry'];
  $sql = "SELECT CODE_ID, CODE_NAME FROM ".$xoopsDB->prefix("torch_code")." WHERE CODE_KIND ='$country'";
  $result = $xoopsDB->query($sql);
  while ($row = mysql_fetch_assoc($result)) {
    // 將取得的資料放入陣列中
    $data[$row['CODE_ID']] = $row['CODE_ID'].' '.$row['CODE_NAME'];
  }
}else if ($_GET['category']!="") {
  // 當使用者選擇小組分類後，填入對應的小組
  $category = $_GET['category'];
  $sql = "SELECT GroupID, GroupLeaderName, GroupName FROM ".$xoopsDB->prefix("torch_group_lists").
    " WHERE GroupCategory ='$category' AND ActivE_Flag=true";
  $result = $xoopsDB->query($sql);
  while ($row = mysql_fetch_assoc($result)) {
    // 將取得的資料放入陣列中
    $data[$row['GroupID']] = $row['GroupLeaderName'].'-'.$row['GroupName'];
  }
}
// 將陣列轉換為 json 格式輸入
echo json_encode($data);
?>
