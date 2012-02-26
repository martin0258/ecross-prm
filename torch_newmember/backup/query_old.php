<?php
include '../../mainfile.php';
include XOOPS_ROOT_PATH.'/header.php';
include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");     //date
?>

<html>
<head>
<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/selectboxes.js"></script>
<script type="text/javascript" src="js/cascadingDropdown.js"></script>
<script type="text/javascript" src="js/torch.js"></script>
</head>

<?php
if(!$xoopsUser) redirect_header(XOOPS_URL, 3, "尚未登入");
$queryType = (isset($_GET['queryType'])) ? $_GET['queryType'] : 'query';
if($queryType == 'delete'){
  $formname = '查詢 — 刪除';
  $action = 'queryResultDelete.php';
  if(!$_SESSION['mod5']){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}else if($queryType == 'address'){
  $formname = '查詢 — 地址輸出';
  $action = 'queryResultAddress.php';
  if(!$_SESSION['mod6']){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}else{
  $formname = '查詢';
  $action = 'queryResult.php';
  if(!$_SESSION['mod2'] && !$_SESSION['mod3']){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}
$form = new XoopsThemeForm($formname, 'form', $action, 'post', true);

//ifContact
$select=new XoopsFormSelect("聯絡狀況", "ifContact");
$select->addOption("", "請選擇");
$options["no"]="未聯絡";
$options["yes"]="已聯絡";
$select->addOptionArray($options);
$form->addElement($select);

//name
$form->addElement(new XoopsFormText("姓名", "name", 15, 50, ""));

//date
$today=getdate();
$form->addElement(new XoopsFormLabel("到訪時間", "")); 
$form->addElement(new XoopsFormTextDateSelect('從', 'startDate', 15 ));
$form->addElement(new XoopsFormTextDateSelect('到', 'endDate', 15, $today));

//cellgroup dynamic double layer select!!! Change somethings in themeform.php render()
$group_select = "
  <select id='group_select1' name='cellgroupCtg'>
  <option value=''>請選擇分類</option>
  <option value='社青'>社青</option>
  <option value='成人'>成人</option>
  <option value='國中'>國中</option>
  <option value='高中'>高中</option>
  <option value='大專'>大專</option>
  <option value='暫無小組'>暫無小組</option>
  </select>
  <select id='group_select2' name='cellgroup'>
  <option value='0'>請選擇小組</option>
  </select>";
$form->addElement($group_select);
$form->addElement(new XoopsFormButton('', 'submit_name', "查詢", 'submit'));
$form->display();

include XOOPS_ROOT_PATH.'/footer.php';
?>
</html>
