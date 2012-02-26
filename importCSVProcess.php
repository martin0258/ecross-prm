<?php
include '../../mainfile.php';

// 如果確實上傳檔案的話，這個檔案的大小（$_FILES['CSVfile']['size']）將會大於 0 才對

if ($_FILES['CSVfile']['size'] > 0) {
  $temp_SQL = "";

  // 解析 CSV 檔之內容，並組成 SQL 字串
  $fp = fopen($_FILES['CSVfile']['tmp_name'], "r");
  while ( $ROW = fgetcsv($fp, $_FILES['CSVfile']['size']) ) {
    // 在資料列有內容時（長度大於 0），才做以下動作
    if ( strlen($ROW[7]) ) {
      $MemberID = $ROW[0];
      $IDNumber = $ROW[1];
      $GroupLists_GroupID = $ROW[2];
      $ChineseName = iconv("BIG5","UTF-8",$ROW[3]);
      //$ChineseName = $ROW[3];
      $EnglishName = iconv("BIG5","UTF-8",$ROW[4]);
      $Introducer = iconv("BIG5","UTF-8",$ROW[5]); 
      $IntroducerPhoneNumber = $ROW[6];
      if(($ROW[7])!='')	
        $Sex = iconv("BIG5","UTF-8",$ROW[7]);
      else 	
        $Sex = '不詳';
      $Birthday = $ROW[8];
      if(($ROW[9])!='')	
        $Marriage = iconv("BIG5","UTF-8",$ROW[9]);
      else 	
        $Marriage = '不詳';
      $CellPhoneNumber = $ROW[10];
      $HomePhoneNumber = $ROW[11];
      $MailingAddress_ZipCode = $ROW[12];
      $MailingAddress_Nationality = iconv("BIG5","UTF-8",$ROW[13]);
      $MailingAddress_Country = iconv("BIG5","UTF-8",$ROW[14]);
      $MailingAddress_Township = iconv("BIG5","UTF-8",$ROW[15]);
      $MailingAddress_Detail = iconv("BIG5","UTF-8",$ROW[16]);
      $Email = $ROW[17];
      $IM = $ROW[18];
      $FirstVisitDate = $ROW[19];
      if(($ROW[20])!='')	
        $Source = iconv("BIG5","UTF-8",$ROW[20]);
      else 	
        $Marriage = '特會';	
      if(($ROW[21])!='')	
        $BeliefStatus = iconv("BIG5","UTF-8",$ROW[21]);
      else 	
        $BeliefStatus = '訪客';	
      $BelongedChurch = iconv("BIG5","UTF-8",$ROW[22]);
      $BaptismDate = $ROW[23];
      //if(($ROW[23])!='')	
      $BaptismDate = $ROW[23];
      //else 
      //$BaptismDate = '1911/01/01';
      $PictureSavingPath = iconv("BIG5","UTF-8",$ROW[24]);
      $AuthorityStatus = $ROW[25];
      $Job = iconv("BIG5","UTF-8",$ROW[26]);
      if(($ROW[27])!='')	
        $Stability = iconv("BIG5","UTF-8",$ROW[27]);
      else 
        $Stability = '其他';
      //echo "Stability= ".$Stability;
      $Note = iconv("BIG5","UTF-8",$ROW[28]);
      $sql_1 = "Insert Into ".$xoopsDB->prefix("torch_MemberInformation")."(MemberID, IDNumber, GroupLists_GroupID, 
        ChineseName, EnglishName, Introducer, IntroducerPhoneNumber, Sex,";
        /*
        Birthday, Marriage, CellPhoneNumber, HomePhoneNumber, 
        MailingAddress_ZipCode, MailingAddress_Nationality, MailingAddress_Country, MailingAddress_Township, MailingAddress_Detail, 
        Email, IM, FirstVisitDate,

        Source, BeliefStatus, BelongedChurch, BaptismDate, PictureSavingPath, 
        AuthorityStatus, Job, Stability, Note, GroupID_TEMP) ";	*/
      $sql_2 = "Values('$MemberID', '$IDNumber', '$GroupLists_GroupID', '$ChineseName', 
        '$EnglishName', '$Introducer', '$IntroducerPhoneNumber','$Sex',";
/*
        '$Birthday', '$Marriage', '$CellPhoneNumber', '$HomePhoneNumber', 
        '$MailingAddress_ZipCode', '$MailingAddress_Nationality', '$MailingAddress_Country', '$MailingAddress_Township', '$MailingAddress_Detail', 
        '$Email', '$IM', '$FirstVisitDate',

        '$Source', '$BeliefStatus', '$BelongedChurch', '$BaptismDate', '$PictureSavingPath', 
        '$AuthorityStatus', '$Job', '$Stability', '$Note', '0')";	
 */
      //判斷生日
      if (($ROW[8])!='') {
        $sql_1 = $sql_1." Birthday,";
        $sql_2 = $sql_2." '$Birthday',";
      }
      $sql_1 = $sql_1." Marriage, CellPhoneNumber, HomePhoneNumber, 
        MailingAddress_ZipCode, MailingAddress_Nationality, MailingAddress_Country, MailingAddress_Township, MailingAddress_Detail, 
        Email, IM,";
      $sql_2 = $sql_2." '$Marriage', '$CellPhoneNumber', '$HomePhoneNumber', 
        '$MailingAddress_ZipCode', '$MailingAddress_Nationality', '$MailingAddress_Country', '$MailingAddress_Township', '$MailingAddress_Detail', 
        '$Email', '$IM',";
      //判斷第一次來教會
      if (($ROW[19])!=''){
        $sql_1 = $sql_1." FirstVisitDate,";
        $sql_2 = $sql_2." '$FirstVisitDate',";
      }
      $sql_1 = $sql_1." Source, BeliefStatus, BelongedChurch,";
      $sql_2 = $sql_2." '$Source', '$BeliefStatus', '$BelongedChurch',";
      //判斷受洗日期
      if (($ROW[23])!=''){
        $sql_1 = $sql_1." BaptismDate,";
        $sql_2 = $sql_2." '$BaptismDate',";
      }
      $sql_1 = $sql_1." PictureSavingPath, 
        AuthorityStatus, Job, Stability, Note, GroupID_TEMP) ";
      $sql_2 = $sql_2." '$PictureSavingPath', 
        '$AuthorityStatus', '$Job', '$Stability', '$Note', '0')";		
      $sql = $sql_1.$sql_2;
      $result = $xoopsDB->query($sql);  
      //echo "sql=".$sql;
      // 從新抓MemberID 更新到torch_MemberContact
      //$sql = "Select Max(MemberID) From ".$xoopsDB->prefix("torch_memberinformation");
      //$result = $xoopsDB->query($sql);
      //$row = $xoopsDB->fetchrow($result);
      //echo "Max(count)--->".$row[0];
      $sql = "Insert Into ".$xoopsDB->prefix("torch_membercontact")."(MemberInformation_MemberID, ContactNumber) Values ('$MemberID', '0')";
      $result = $xoopsDB->query($sql);
      //echo "sql=".$sql;
    }
  }
  fclose($fp);  
  redirect_header('import.php', 3, '轉檔成功');
}
else {
  // 未上傳檔案，或上傳一個空檔
  redirect_header('import.php', 3, '轉檔失敗，請聯絡程式人員');
}
?>
