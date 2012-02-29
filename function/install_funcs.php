<?php
/** 
 * Description: 
 * This file contains the functions executed after the module has been installed
 * @author Martin Ku
 * @package xoops
 */

/**
 * Description: 
 * 將小組代碼0轉成NULL，設定Log file path
 * @param string $xoopsMod name of the module
 * @return bool true if all success otherwise false
 */
function xoops_module_install_torch_newmember($xoopsMod) {
  global $xoopsDB;
  # 將小組代碼0轉成NULL，才能符合foreign constraint
  $strSetNoGroup = "UPDATE ".$xoopsDB->prefix('torch_member_information').
    " SET GroupLists_GroupID=NULL, GroupID_TEMP=NULL WHERE GroupLists_GroupID=0 AND GroupID_TEMP=0";
  $result = $xoopsDB->query($strSetNoGroup);

  # 設定Log file path
  $logFilePath = 'C:/Users/Martin/Documents/torch.log';
  $strSetLogFilePath = "INSERT INTO ".$xoopsDB->prefix('torch_system_variable').
    " (VariableName, Value) VALUES ('logFilePath', '$logFilePath')";
  $result2 = $xoopsDB->query($strSetLogFilePath);

  # 設定foreign constraint: 先將table engine改成InnoDB，再加入constraint
  /*
  $tables = array('torch_group_lists', 'torch_member_information');
  foreach($tables as $table){
    $strChgEngine = "ALTER TABLE ".$xoopsDB->prefix($table)." ENGINE=InnoDB";
    $result3 &= $xoopsDB->query($strChgEngine);
  }*/
  return $result && $result2;
}
?>
