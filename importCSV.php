<?php
include '../../mainfile.php';
include XOOPS_ROOT_PATH.'/header.php';

if($xoopsUser){
  if(!$_SESSION['mod4']){
    redirect_header(XOOPS_URL, 3, _NOPERM);
  }
}
else redirect_header(XOOPS_URL, 3, _MD_NOT_LOGIN);
$xoops_module_header = 
  "<link rel='stylesheet' type='text/css' href='css/validationEngine.jquery.css'>
  <link rel='stylesheet' type='text/css' href='css/torchStyle.css'>
  <script type='text/javascript' src='js/jquery-1.7.1.min.js'></script>
  <script type='text/javascript' src='js/validation/jquery.validationEngine.js'></script>
  <script type='text/javascript' src='js/validation/jquery.validationEngine-zh_TW.js'></script>
  <script type='text/javascript' src='js/torch.js'></script>
  <script type='text/javascript'>
    $(document).ready(function(){
      $('#form1').validationEngine();
    });
  </script>";
$xoopsTpl->assign('xoops_module_header',$xoops_module_header);
?>
<div id='container'>
<div class='info'>
  <h2>批次匯入</h2>
</div>
<label style='color:red'>*使用此功能前請聯絡程式人員</label>
<form name='form1' id='form1' action='importCSVProcess.php' method='post' enctype='multipart/form-data'>
<input type='file' name='CSVfile' id='CSVfile' class='validate[required,custom[fileCSV]]'>
<input type='submit' value='上傳'>
</form>
</div>
<?php
  include XOOPS_ROOT_PATH.'/footer.php';
?>
