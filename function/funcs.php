<?php
/** 
 * Description:
 * This file contains the functions used through the module.
 * @author Martin Ku
 * @package own-library
 */

/**
 * Description: 
 * 從torch_SystemVariable table傳回系統變數
 * @param string $varName name of the variable
 * @return mixed value of the variable(return NULL if cannot find the var)
 */
function getSysVar($varName) {
  global $xoopsDB;
  # 將小組代碼0轉成NULL，才能符合foreign constraint
  $strSQL = "SELECT Value FROM ".$xoopsDB->prefix('torch_SystemVariable').
    " WHERE VariableName='$varName'";
  $result = $xoopsDB->query($strSQL);
  return $result==false? NULL : mysql_result($result, 0);
}

/**
 * Description: 
 * 根據param從torch_code table傳回 array[CODE_ID]=CODE_NAME
 * @param string $code_kind_name 編碼種類名稱
 * @return mixed array[CODE_ID]=CODE_NAME(沒有該編碼的話回傳空陣列)
 * Usage example:
 *    getCode('提供服務') =>
 *    (
 *      'S1' => '祝福禱告'
 *      'S2' => '參加小組'
 *      'S3' => '寄送電子報'
 *      'S4' => '至家裡或公司探訪新會友'
 *      'S5' => '代禱'
 *    )
 */
function getCode($code_kind_name){
  global $xoopsDB;
  $sql = "SELECT CODE_ID, CODE_NAME FROM ".$xoopsDB->prefix('torch_code')." WHERE CODE_KIND_NAME='$code_kind_name'";
  $result = $xoopsDB->query($sql);
  $list = array();
  while( $row = $xoopsDB->fetchrow($result) ){
    $list[$row[0]] = $row[1];
  }
  return $list;
}

/**
 * Description: 
 * 取得新人某一欄的勾選結果陣列
 * @param string $decisionList 新人針對某一欄的勾選結果(用逗號隔開)
 * @param string $code_kind_name 編碼種類名稱
 * @return mixed array[CODE_ID] = 'Y' or 'N'(代表是否勾選該選項)
 * Usage example:
 *    getChecked('S1,S2,S5', '提供服務') =>
 *    (
 *      'S1' => 'Y'
 *      'S2' => 'Y'
 *      'S3' => 'N'
 *      'S4' => 'N'
 *      'S5' => 'Y'
 *    )
 */
function getChecked($decisionList, $code_kind_name){
  $codeArr = getCode($code_kind_name);
  foreach($codeArr as $key=>$value){
    $codeArr[$key] = (strstr($decisionList, $key)!=false) ? 'Y' : 'N';
  }
  return $codeArr;
}
?>
