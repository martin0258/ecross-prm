<?php
/** 
 * Description:
 * 新人名單模組首頁
 * @author            Martin Ku
 * @package           page
 * @version           2012/02/28 Use checkPermission.php
 */
include 'include/checkPermission.php';
if(!$xoopsUser) { redirect_header(XOOPS_URL, 3, _MD_NOT_LOGIN); }
include XOOPS_ROOT_PATH.'/header.php';
?>
<h1>歡迎使用火把新人名單模組</h1>
<div>請利用左邊選單進行操作。</div>
<?php
include XOOPS_ROOT_PATH.'/footer.php';
?>
