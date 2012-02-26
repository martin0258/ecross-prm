<?php
# 如果有因為foreign key而無法刪除的table，在此刪除
function xoops_module_uninstall_torch_newmember( $xoopsMod ) {
  global $xoopsDB;
  $strDeleteSQL = "DROP TABLE IF EXISTS ";
  $tables = array(
    'torch_SpecialtyLists', 
    'torch_ServiceLists', 
    'torch_GroupLists', 
    'torch_MemberInformation');
  $sumResult = true;
  foreach($tables as $tableName){
    $sumResult &= $xoopsDB->query($strDeleteSQL . $xoopsDB->prefix($tableName));
  }
  return $sumResult;
}

?>
