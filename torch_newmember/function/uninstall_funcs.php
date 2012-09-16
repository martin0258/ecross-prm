<?php
/** 
 * Description: 
 * This file contains the functions executed after the module has been uninstalled.
 * @author Martin Ku
 * @package xoops
 */

/**
 * Description: 
 * 刪除因為foreign key而無法刪除的table
 * @param string $xoopsMod name of the module
 * @return bool true if all success otherwise false
 */
function xoops_module_uninstall_torch_newmember($xoopsMod) {
  global $xoopsDB;
  $strDeleteSQL = "DROP TABLE IF EXISTS ";
  $tables = array(
    'torch_specialty_lists', 
    'torch_service_lists', 
    'torch_group_lists', 
    'torch_member_information');
  $sumResult = true;
  foreach($tables as $tableName){
    $sumResult &= $xoopsDB->query($strDeleteSQL . $xoopsDB->prefix($tableName));
  }
  return $sumResult;
}
?>
