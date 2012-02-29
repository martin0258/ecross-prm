<?php
/** 
 * Description:
 * 無SESSION['newMemberList']之時，push他們提供帳號跟小組對應關係。
 * @author            Martin Ku
 * @package           page
 * @version           2012/02/28 File created.
 */
include_once dirname(dirname(dirname(dirname(__FILE__)))).'/mainfile.php';
include XOOPS_ROOT_PATH.'/header.php';
?>
<h1>有關此功能</h1>
<h2>緣起</h2>
<div><pre>
  個資法前不久上路，於信中散發新人資料實為冒險。 
  這是一項<strong>開發中</strong>的功能，鼓勵大家登入系統查看小組新人。
</pre></div>
<h2>操作</h2>
<div><pre>
  目前作法：透過通知信內的連結啟動此功能，會根據連結顯示該次小組新人。
  未來作法：除了透過信內連結查看該次小組新人，此功能會記錄每次新人，日後隨時可登入系統查看。
</pre></div>
<?php
include XOOPS_ROOT_PATH.'/footer.php';
?>
