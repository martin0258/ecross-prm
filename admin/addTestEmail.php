<?php
/** 
 * Description:
 * This file adds the testing email and group to torch_GroupLists.
 * @author Martin Ku
 * @package process
 */
include '../../../include/cp_header.php';
if($xoopsUser){
  $mid = $xoopsModule->mid();
  if(!$xoopsUser->isAdmin($mid)){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}
else{
  redirect_header(XOOPS_URL, 3, _MD_NOT_LOGIN);
}

$email = $_POST['email'];
$closeFlag = (bool)$_POST['closeFlag'];
$sql = "SELECT Value FROM ".$xoopsDB->prefix('torch_SystemVariable')." WHERE VariableName='TestGroupID'";
$result = $xoopsDB->query($sql);
if(mysql_num_rows($result)>0){
  $testGroupID = mysql_result($result, 0);
  if($closeFlag){
    //刪除測試組別
    $sql = "DELETE FROM ".$xoopsDB->prefix('torch_SystemVariable')." WHERE VariableName='TestGroupID'";
    $result = $xoopsDB->query($sql);
    $sql = "DELETE FROM ".$xoopsDB->prefix('torch_GroupLists')." WHERE GroupID='$testGroupID'";
    $result = $xoopsDB->query($sql);
  }else{
    //更改測試用email
    $sql = "UPDATE ".$xoopsDB->prefix('torch_GroupLists')." SET GroupLeaderMail='$email' WHERE GroupID=$testGroupID";
    $result = $xoopsDB->query($sql);
  }
}else{
  //新增測試組別
  $strIDMaxSQL = "SELECT MAX(GroupID) FROM ".$xoopsDB->prefix('torch_GroupLists');
  $result = $xoopsDB->query($strIDMaxSQL);
  $maxGroupID = mysql_result($result, 0);
  $testGroupID = intval($maxGroupID)+100;
  $sql = "INSERT INTO ".$xoopsDB->prefix('torch_GroupLists')." (GroupID, GroupLeaderName, GroupName, GroupCategory, ".
    "GroupLeaderMail) VALUES ('$testGroupID', '耶穌', '耶穌自己帶小組', '測試', '$email')";
  $result = $xoopsDB->query($sql);
  //$testGroupID = mysql_insert_id();
  $sql = "INSERT INTO ".$xoopsDB->prefix('torch_SystemVariable')." (VariableName, Value) ".
    "VALUES ('TestGroupID', '$testGroupID')";
  $result = $xoopsDB->query($sql);
}

if($result)redirect_header('index.php', 3, '更新成功');
else redirect_header('index.php', 3, '更新失敗');

?>
