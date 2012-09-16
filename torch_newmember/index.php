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
<style>
#home { background:url(images/lost_sheep.jpg) top right no-repeat; min-height:300px;}
</style>
<div>
<h1>歡迎使用火把新人名單模組</h1>
<div id='home'><pre>
請利用左邊選單進行操作。

歡迎來信詢問操作問題或給予建議-><input type="button" value="連絡Ecross" onclick="self.location.href='mailto:ecross.mail@gmail.com';"style='color:#0200A0'>
Email: <a href='mailto:ecross.mail@gmail.com'>ecross.mail@gmail.com</a>
</pre></div>
</div>
<?php
include XOOPS_ROOT_PATH.'/footer.php';
?>
