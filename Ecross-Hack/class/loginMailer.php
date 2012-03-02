<?php
/** 
 * Description:
 * Send notification Email and log the login action.
 *
 * @author            Martin Ku
 * @package           own-library
 * @version           2012/03/02 Move it to Ecross-Hack directory.
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class LoginMailer {
  private $login_account = "";    //登入帳號
  private $user_account = "";     //使用者帳號
  private $user_email = "";       //使用者Email
  private $mailSubject = "";      //Email主旨
  private $mailTemplate = "";     //Email樣板
  private $timeStamp = "";        //時間戳記

  //constructor
  public function loginMailer($uname){
    //取得所有會員uid
    $this->login_account = $uname;
    $module_handler = & xoops_gethandler('member');
    $users=$module_handler->getUsers();
    foreach($users as $user){
      if($user->getVar('loginname')) $members[$user->getVar('uid')]=$user->getVar('loginname');
      else $members[$user->getVar('uid')]=$user->getVar('uname');

      if($members[$user->getVar('uid')] == $uname){
        //找到符合帳號，設定使用者Email
        $this->user_email = $user->getVar('email');
        $this->user_account = $uname;
        break;
      }
    }
    $this->timeStamp = date("Y-m-d H:i:s");
  }

  public function loginSuccess(){
    $this->mailTemplate = "loginSuccess.tpl";
    $this->mailSubject = "火把新人系統 成功登入通知";
    $this->sendMail();
  }

  public function loginFail(){
    if($this->user_account == "" && $this->user_email == ""){
      //log message 
      $fp = fopen('error_login.txt', 'a+');
      fwrite($fp, "Fail login on " . $this->timeStamp . " using Account:".$this->login_account."\n");
      fclose($fp);
    }else{
      $this->mailTemplate = "loginFail.tpl";
      $this->mailSubject = "火把新人系統 登入錯誤通知";
      $this->sendMail();
    }
  }

  public function sendMail(){
    global $xoopsConfig;
    $xoopsMailer =& xoops_getMailer();
    $xoopsMailer->useMail();
    //$xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/mail_template/');
    $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH.'/Ecross-Hack/mail_template/');
    $xoopsMailer->setTemplate($this->mailTemplate);
    $xoopsMailer->assign("X_UNAME", $this->user_account);
    $xoopsMailer->assign("IP", $_SERVER['REMOTE_ADDR']);
    $xoopsMailer->assign("TIME", $this->timeStamp);
    $xoopsMailer->assign("SITENAME", $xoopsConfig['sitename']);
    $xoopsMailer->assign("ADMINMAIL", $xoopsConfig['adminmail']);
    $xoopsMailer->assign("SITEURL", XOOPS_URL . "/");
    $xoopsMailer->setToEmails($this->user_email);
    $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
    $xoopsMailer->setFromName($xoopsConfig['sitename']);
    $xoopsMailer->setSubject($this->mailSubject);
    if (! $xoopsMailer->send()) {
      $str = $xoopsMailer->getErrors();
      error_log("Fail:".$str);
    }else{
      error_log("Success:".$xoopsMailer->toEmails[0]);
    }
  }
}
?>
