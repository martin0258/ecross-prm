<?php
set_time_limit(6000);
$letAnonymousPass = true;
include '../../mainfile.php';
include 'include/encrypt.php';
include 'function/funcs.php';

//log
$should_fp = fopen(getSysVar('logFilePath'), 'a+');
$mail_fp = fopen(getSysVar('logFilePath'), 'a+');

$mailSubject = '小組成員變動';
$changeList = array();

#建立二維陣列[組別][新朋友ID]
$sql_changeList = 
  "SELECT GroupLists_GroupID, MemberID FROM ".$xoopsDB->prefix("torch_memberinformation").
  " WHERE GroupLists_GroupID != GroupID_TEMP OR (GroupLists_GroupID>0 AND GroupID_TEMP IS NULL)".
  " ORDER BY GroupLists_GroupID, MemberID";
$result = $xoopsDB->query($sql_changeList);
while( $row = $xoopsDB->fetchrow($result) ){
  $groupID = $row[0];
  $memberID = $row[1];
  if(!isset($changeList[$groupID])){
    $changeList[$groupID] = array();
  }
  array_push($changeList[$groupID], $memberID);
}
//error_log(print_r($changeList, true));

# 建立各組人員連結，將對應的值填入newMember.tpl
foreach( $changeList as $groupID=>$memberIDList){
  $sql_groupDetail = 
    "SELECT GroupName, GroupLeaderMail FROM ".$xoopsDB->prefix("torch_GroupLists").
    " WHERE GroupID = '$groupID'";
  $result = $xoopsDB->query($sql_groupDetail);
  $groupName = mysql_result($result, 0, 0);
  $groupLeaderMail = mysql_result($result, 0, 1);
  $IDlist = "";
  foreach( $memberIDList as $memberID){
    $IDlist .= ($memberID.'j');
  }
  $IDlist = substr($IDlist, 0, strlen($IDlist)-1);
  $link = XOOPS_URL . '/modules/torch_newmember/index.php?l=' . authcode($IDlist, 'ENCODE');

  $xoopsMailer =& xoops_getMailer();
  $xoopsMailer->useMail();
  $xoopsMailer->setTemplateDir('language/'.$xoopsConfig['language'].'/mail_template/');
  $xoopsMailer->setTemplate('newMemberTemp.tpl');
  $xoopsMailer->assign("NEW_MEMBER_COUNT", count($memberIDList));
  $xoopsMailer->assign("GROUPNAME", $groupName);
  $xoopsMailer->assign("LINK", $link);

  # 過渡期的CODE區段:
  # 1. 仍然附送table 
  # 2. CC給預備領袖
  $xoopsMailer->addHeaders('Content-Type: text/html; charset=ISO-8859-7');
  //建立新人table
  $table = "<table border='1'><tr>
    <th align ='center'>第一次來教會日期</th>
    <th align ='center'>中文姓名</th>
    <th align ='center'>英文姓名</th>
    <th align ='center'>手機號碼</th>
    <th align ='center'>電子郵件</th>
    <th align ='center'>地址</th>
    </tr>";
  $sql_ID = str_replace('j',',',$IDlist);
  $sql="Select FirstVisitDate, ChineseName, EnglishName, CellPhoneNumber, Email,
    MailingAddress_Detail from ".$xoopsDB->prefix("torch_MemberInformation").
    " WHERE MemberID IN($sql_ID)";
  $result = $xoopsDB->query($sql);
  while( $row = $xoopsDB->fetchrow($result)){
    $table .= "<tr>";
    for ($j = 0; $j < 6; $j++) {
      $table .= "<td align='center'>$row[$j]</td>";
    }
    $table .= "</tr>";
  }
  $table .= "</table>";
  $xoopsMailer->assign("TABLE", $table);
  //寄信給預備領袖
  $strSQL = "SELECT ViceLeaderMail FROM ".$xoopsDB->prefix('torch_grouplists').
    " WHERE GroupID = '$groupID'";
  $result = $xoopsDB->query($strSQL);
  $viceMails = explode(',', mysql_result($result, 0) );
  $toEmails = array();
  foreach ($viceMails as $viceMail) {
    array_push($toEmails, $viceMail);
  }
  array_push($toEmails, $groupLeaderMail);
  # 過渡期的CODE結束

  $xoopsMailer->setToEmails($toEmails);
  $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
  $xoopsMailer->setFromName($xoopsConfig['sitename']);
  $xoopsMailer->setSubject($mailSubject);

  fwrite($should_fp, date("Y-m-d H:i:s").":Message should send to $groupID-$groupName $groupLeaderMail\n");
  if (!$xoopsMailer->send()) {
    error_log($xoopsMailer->getErrors());
    fwrite( $mail_fp, 
      "Fail on " . date("Y-m-d H:i:s").", Message sent to $groupID-$groupName $groupLeaderMail, ".
      "Error:".$xoopsMailer->getErrors(). "\n");
  }else{
    echo "Message sent to $groupLeaderMail Successfully!<BR>";
    fwrite($mail_fp, 
      "Success on " . date("Y-m-d H:i:s").", Message sent to $groupID-$groupName $groupLeaderMail\n");

    //Sync GroupID and GroupID_Temp
    foreach( $memberIDList as $memberID){
      $sql_IDlist .= "'$memberID',";
    }
    $sql_IDlist = substr($sql_IDlist, 0, strlen($sql_IDlist)-1);
    $sql_update = 
      " UPDATE ".$xoopsDB->prefix("torch_memberinformation").
      " SET GroupID_TEMP=GroupLists_GroupID".
      " WHERE MemberID IN($sql_IDlist)";
    $result = $xoopsDB->queryF($sql_update);
  }
}

fclose($mail_fp);
fclose($should_fp);

?>
