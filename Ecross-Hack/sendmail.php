<?php
/** 
 * Description:
 * 寄發新人名單通知信給小組長。用排程的方式呼叫此檔案。
 *
 * @author          Martin Ku
 * @package         backend
 * @version         2012/03/10 Last update
 */
set_time_limit(6000);
include '../mainfile.php';
require_once 'function/encrypt.php';
require_once 'function/funcs.php';

# log
$mail_fp = fopen(getSysVar('logFilePath'), 'a+');

$mailSubject = '小組成員變動';
$changeList = array();

#建立二維陣列[組別][新朋友ID]
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

# 建立各組人員連結，將對應的值填入newMember.html
foreach( $changeList as $groupID=>$memberIDList){
  $sql_groupDetail = 
    "SELECT GroupName, GroupLeaderMail FROM ".$xoopsDB->prefix("torch_group_lists").
    " WHERE GroupID = '$groupID'";
  $result = $xoopsDB->query($sql_groupDetail);
  $groupName = mysql_result($result, 0, 0);
  $groupLeaderMail = mysql_result($result, 0, 1);
  $IDlist = "";
  foreach( $memberIDList as $memberID){
    //用逗號將新人ID分開
    $IDlist .= ($memberID.',');
  }
  $IDlist = substr($IDlist, 0, strlen($IDlist)-1);
  $link = XOOPS_URL . '/Ecross-Hack/mailLink.php?l=' . authcode($IDlist, 'ENCODE');

  $xoopsMailer =& xoops_getMailer();
  $xoopsMailer->useMail();
  $xoopsMailer->setTemplateDir('mail_template/');
  //$xoopsMailer->setTemplateDir('language/'.$xoopsConfig['language'].'/mail_template/');
  $xoopsMailer->setTemplate('newMember.html');
  $xoopsMailer->assign("NEW_MEMBER_COUNT", count($memberIDList));
  $xoopsMailer->assign("GROUPNAME", $groupName);
  $xoopsMailer->assign("LINK", $link);
  //$xoopsMailer->addHeaders('Content-Type: text/html; charset=ISO-8859-7');
  //寄信給預備領袖
  $strSQL = "SELECT ViceLeaderMail FROM ".$xoopsDB->prefix('torch_group_lists').
    " WHERE GroupID = '$groupID'";
  $result = $xoopsDB->query($strSQL);
  $viceMails = explode(',', mysql_result($result, 0) );
  $toEmails = array();
  foreach ($viceMails as $viceMail) {
    array_push($toEmails, $viceMail);
  }
  array_push($toEmails, $groupLeaderMail);

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
