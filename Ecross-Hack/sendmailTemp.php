<?php
/** 
 * Description:
 * This file is the temporary of sendmail.php?‚åœ¨å¤§å®¶ç¿’æ…£?»å…¥?„æŸ¥?‹æ–¹å¼å?ï¼Œä??¼ä¿¡ä¸­é?è¡¨æ ¼??
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

$mailSubject = 'å°ç??å“¡è®Šå?';
$changeList = array();

#å»ºç?äºŒç¶­???[çµ„åˆ¥][?°æ??‹ID]
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

# å»ºç??„ç?äººå“¡???ï¼Œå?å°æ??„å?å¡«å…¥newMember.tpl
foreach( $changeList as $groupID=>$memberIDList){
  $sql_groupDetail = 
    "SELECT GroupName, GroupLeaderMail FROM ".$xoopsDB->prefix("torch_group_lists").
    " WHERE GroupID = '$groupID'";
  $result = $xoopsDB->query($sql_groupDetail);
  $groupName = mysql_result($result, 0, 0);
  $groupLeaderMail = mysql_result($result, 0, 1);
  $IDlist = "";
  foreach( $memberIDList as $memberID){
    //?¨é??Ÿå??°äººID?†é?
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

  # ?æ¸¡?Ÿç?CODE??®µ:
  # 1. ä»ç„¶?„é?table 
  # 2. CCçµ¦é??™é?è¢?
  //å»ºç??°äººtable
  $table = "<table border='1'><tr>
    <th align ='center'>ç¬¬ä?æ¬¡ä??™æ??¥æ?</th>
    <th align ='center'>ä¸­æ?å§“å?</th>
    <th align ='center'>?±æ?å§“å?</th>
    <th align ='center'>?‹æ??Ÿç¢¼</th>
    <th align ='center'>?»å??µä»¶</th>
    <th align ='center'>?°å?</th>
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
  //å¯„ä¿¡çµ¦é??™é?è¢?
  $strSQL = "SELECT ViceLeaderMail FROM ".$xoopsDB->prefix('torch_group_lists').
    " WHERE GroupID = '$groupID'";
  $result = $xoopsDB->query($strSQL);
  $viceMails = explode(',', mysql_result($result, 0) );
  $toEmails = array();
  foreach ($viceMails as $viceMail) {
    array_push($toEmails, $viceMail);
  }
  array_push($toEmails, $groupLeaderMail);
  # ?æ¸¡?Ÿç?CODEçµæ?

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
