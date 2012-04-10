<?php
/** 
 * Description:
 * This file is the temporary of sendmail.php?�在大家習慣?�入?�查?�方式�?，�??�信中�?表格??
 *
 * @author          Martin Ku
 * @package         backend
 * @version         2012/03/02 Last update
 */
set_time_limit(6000);
include '../mainfile.php';
require_once 'function/encrypt.php';
require_once 'function/funcs.php';

# log
$mail_fp = fopen(getSysVar('logFilePath'), 'a+');

$mailSubject = '小�??�員變�?';
$changeList = array();

#建�?二維???[組別][?��??�ID]
$sql_changeList = 
  "SELECT GroupLists_GroupID, MemberID FROM ".$xoopsDB->prefix("torch_member_information").
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

# 建�??��?人員???，�?對�??��?填入newMember.tpl
foreach( $changeList as $groupID=>$memberIDList){
  $sql_groupDetail = 
    "SELECT GroupName, GroupLeaderMail FROM ".$xoopsDB->prefix("torch_group_lists").
    " WHERE GroupID = '$groupID'";
  $result = $xoopsDB->query($sql_groupDetail);
  $groupName = mysql_result($result, 0, 0);
  $groupLeaderMail = mysql_result($result, 0, 1);
  $IDlist = "";
  foreach( $memberIDList as $memberID){
    //?��??��??�人ID?��?
    $IDlist .= ($memberID.',');
  }
  $IDlist = substr($IDlist, 0, strlen($IDlist)-1);
  $link = XOOPS_URL . '/Ecross-Hack/mailLink.php?l=' . authcode($IDlist, 'ENCODE');

  $xoopsMailer =& xoops_getMailer();
  $xoopsMailer->useMail();
  $xoopsMailer->setTemplateDir('mail_template/');
  //$xoopsMailer->setTemplateDir('language/'.$xoopsConfig['language'].'/mail_template/');
  $xoopsMailer->setTemplate('newMemberTemp.tpl');
  $xoopsMailer->assign("NEW_MEMBER_COUNT", count($memberIDList));
  $xoopsMailer->assign("GROUPNAME", $groupName);
  $xoopsMailer->assign("LINK", $link);
  //$xoopsMailer->addHeaders('Content-Type: text/html; charset=ISO-8859-7');

  # ?�渡?��?CODE??��:
  # 1. 仍然?��?table 
  # 2. CC給�??��?�?
  //建�??�人table
  $table = "<table border='1'><tr>
    <th align ='center'>第�?次�??��??��?</th>
    <th align ='center'>中�?姓�?</th>
    <th align ='center'>?��?姓�?</th>
    <th align ='center'>?��??�碼</th>
    <th align ='center'>?��??�件</th>
    <th align ='center'>?��?</th>
    </tr>";
  $sql="Select FirstVisitDate, ChineseName, EnglishName, CellPhoneNumber, Email,
    MailingAddress_Detail from ".$xoopsDB->prefix("torch_member_information").
    " WHERE MemberID IN($IDlist)";
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
  //寄信給�??��?�?
  $strSQL = "SELECT ViceLeaderMail FROM ".$xoopsDB->prefix('torch_group_lists').
    " WHERE GroupID = '$groupID'";
  $result = $xoopsDB->query($strSQL);
  $viceMails = explode(',', mysql_result($result, 0) );
  $toEmails = array();
  foreach ($viceMails as $viceMail) {
    array_push($toEmails, $viceMail);
  }
  array_push($toEmails, $groupLeaderMail);
  # ?�渡?��?CODE結�?

  $xoopsMailer->setToEmails($toEmails);
  $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
  $xoopsMailer->setFromName($xoopsConfig['sitename']);
  $xoopsMailer->setSubject($mailSubject);
  $xoopsMailer->multimailer->isHTML(true);

  fwrite($mail_fp, date("Y-m-d H:i:s").":Message should send to $groupID-$groupName $groupLeaderMail\n");
  if (!$xoopsMailer->send()) {
    error_log("xoopsMailer Error: ".$xoopsMailer->getErrors());
    fwrite( $mail_fp, 
      "Fail on " . date("Y-m-d H:i:s").", Message sent to $groupID-$groupName $groupLeaderMail, ".
      "Error:".$xoopsMailer->getErrors(). "\n");
  }else{
    echo "Message sent to $groupLeaderMail Successfully!<BR>";
    error_log("Message sent to $groupLeaderMail Successfully");
    fwrite($mail_fp, 
      "Success on " . date("Y-m-d H:i:s").", Message sent to $groupID-$groupName $groupLeaderMail\n");

    //Sync GroupID and GroupID_Temp
    $sql_update = 
      " UPDATE ".$xoopsDB->prefix("torch_member_information").
      " SET GroupID_TEMP=GroupLists_GroupID".
      " WHERE MemberID IN($IDlist)";
    $result = $xoopsDB->queryF($sql_update);
  }
}

fclose($mail_fp);
?>
