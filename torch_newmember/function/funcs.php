<?php
/* 
 * 程式用途：模組需要用到的函示
 * 歷史：
 *    2012/1/25 Martin Ku, file created.
 */

/* 目的：從torch_SystemVariable table傳回系統變數
 * @param name of the variable
 * @return value of the variable(找不到此變數的話回傳false)
 */
function getSysVar( $varName ) {
  global $xoopsDB;
  # 將小組代碼0轉成NULL，才能符合foreign constraint
  $strSQL = "SELECT Value FROM ".$xoopsDB->prefix('torch_SystemVariable').
    " WHERE VariableName='$varName'";
  $result = $xoopsDB->query($strSQL);
  return $result==false? false : mysql_result($result, 0);
}

?>
