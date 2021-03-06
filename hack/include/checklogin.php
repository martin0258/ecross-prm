<?php
/** 
 * Description:       Search torch for changes. See README for more info.
 *
 * @author            Martin Ku
 * @package           xoops
 * @version           2012/03/02 Redirect to torch_newmember after login.
 */

/**
 * XOOPS authentication/authorization
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         core
 * @since           2.0.0
 * @version         $Id: checklogin.php 8066 2011-11-06 05:09:33Z beckmi $
 * @todo            Will be refactored
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/* Load torch loginMailer */
require_once XOOPS_ROOT_PATH.'/Ecross-Hack/function/funcs.php';
if((bool)getSysVar('login_notification')){
  //系統變數設定1才啟動此功能
  if (!file_exists($file = XOOPS_ROOT_PATH . '/Ecross-Hack/class/loginMailer.php')) {
  }else{ include_once XOOPS_ROOT_PATH . '/Ecross-Hack/class/loginMailer.php'; }
}
/* End of Load torch loginMailer*/

xoops_loadLanguage('user');

$uname = !isset($_POST['uname']) ? '' : trim($_POST['uname']);
$pass = !isset($_POST['pass']) ? '' : trim($_POST['pass']);
if ($uname == '' || $pass == '') {
    redirect_header(XOOPS_URL.'/user.php', 1, _US_INCORRECTLOGIN);
    exit();
}

$member_handler =& xoops_gethandler('member');
$myts =& MyTextsanitizer::getInstance();

include_once $GLOBALS['xoops']->path('class/auth/authfactory.php');

xoops_loadLanguage('auth');

$xoopsAuth =& XoopsAuthFactory::getAuthConnection($myts->addSlashes($uname));
$user = $xoopsAuth->authenticate($myts->addSlashes($uname), $myts->addSlashes($pass));

if (false != $user) {
    /* Embedded code for torch loginMailer */
    if(class_exists('LoginMailer')){
      $loginMail = new LoginMailer($uname);
      $loginMail->loginSuccess();
    }
    /* End of torch loginMailer */
    if (0 == $user->getVar('level')) {
        redirect_header(XOOPS_URL.'/index.php', 5, _US_NOACTTPADM);
        exit();
    }
    if ($xoopsConfig['closesite'] == 1) {
        $allowed = false;
        foreach ($user->getGroups() as $group) {
            if (in_array($group, $xoopsConfig['closesite_okgrp']) || XOOPS_GROUP_ADMIN == $group) {
                $allowed = true;
                break;
            }
        }
        if (!$allowed) {
            redirect_header(XOOPS_URL.'/index.php', 1, _NOPERM);
            exit();
        }
    }
    $user->setVar('last_login', time());
    if (!$member_handler->insertUser($user)) {
    }
    /*Start Code of torch: We need to store session before regeneration*/
    if(isset($_SESSION['newMemberList']))$temp = $_SESSION['newMemberList'];
    /*End Code of torch*/
    // Regenrate a new session id and destroy old session
    $GLOBALS["sess_handler"]->regenerate_id(true);
    $_SESSION = array();
    $_SESSION['xoopsUserId'] = $user->getVar('uid');
    $_SESSION['xoopsUserGroups'] = $user->getGroups();
    $user_theme = $user->getVar('theme');
    if (in_array($user_theme, $xoopsConfig['theme_set_allowed'])) {
        $_SESSION['xoopsUserTheme'] = $user_theme;
    }
    /*Start Code of torch: restore session*/
    if(isset($temp))$_SESSION['newMemberList'] = $temp;
    /*End Code of torch*/

    // Set cookie for rememberme
    if (!empty($xoopsConfig['usercookie'])) {
        if (!empty($_POST["rememberme"])) {
            setcookie($xoopsConfig['usercookie'], $_SESSION['xoopsUserId'] . '-' . md5($user->getVar('pass') . XOOPS_DB_NAME . XOOPS_DB_PASS . XOOPS_DB_PREFIX), time() + 31536000, '/', XOOPS_COOKIE_DOMAIN, 0);
        } else {
            setcookie($xoopsConfig['usercookie'], 0, -1, '/', XOOPS_COOKIE_DOMAIN, 0);
        }
    }

    if (!empty($_POST['xoops_redirect']) && !strpos($_POST['xoops_redirect'], 'register')) {
        $xoops_redirect = trim(rawurldecode($_POST['xoops_redirect']));
        $parsed = parse_url(XOOPS_URL);
        $url = isset($parsed['scheme']) ? $parsed['scheme'].'://' : 'http://';
        if (isset( $parsed['host'])) {
            $url .= $parsed['host'];
            if (isset( $parsed['port'])) {
                $url .= ':' . $parsed['port'];
            }
        } else {
            $url .= $_SERVER['HTTP_HOST'];
        }
        if (@$parsed['path']) {
            if (strncmp($parsed['path'], $xoops_redirect, strlen( $parsed['path']))) {
                $url .= $parsed['path'];
            }
        }
        $url .= $xoops_redirect;
    } else {
        $url = XOOPS_URL . '/index.php';
    }

    // RMV-NOTIFY
    // Perform some maintenance of notification records
    $notification_handler =& xoops_gethandler('notification');
    $notification_handler->doLoginMaintenance($user->getVar('uid'));


    /*Start Code of torch*/
    if(isset($_SESSION['newMemberList'])){
      redirect_header($url.'/modules/torch_newmember/groupNewMember.php', 1,
      sprintf(_US_LOGGINGU, $user->getVar('uname')).'<br>以下是本次小組新人<br>要記得聯絡喔:)', true);
    }else{
      //新人名單模組存在的話，登入後導向模組首頁
      if(file_exists($module_url = XOOPS_ROOT_PATH.'/modules/torch_newmember/index.php'))
      {redirect_header(XOOPS_URL.'/modules/torch_newmember/', 1, sprintf(_US_LOGGINGU, $user->getVar('uname')), false);}
      else{ redirect_header($url, 1, sprintf(_US_LOGGINGU, $user->getVar('uname')), false); }
    }
    /*End Code of torch*/
    /* Orginal Code
      redirect_header($url, 1, sprintf(_US_LOGGINGU, $user->getVar('uname')), false);
     */
} else if (empty($_POST['xoops_redirect'])) {
    redirect_header(XOOPS_URL . '/user.php', 5, $xoopsAuth->getHtmlErrors());
} else {
    /* Embedded code for torch loginMailer */
    if(class_exists('LoginMailer')){
      $loginMail = new LoginMailer($uname);
      $loginMail->loginFail();
    }
    /* End of torch loginMailer */
    redirect_header(XOOPS_URL . '/user.php?xoops_redirect=' . urlencode(trim($_POST['xoops_redirect'])), 5, $xoopsAuth->getHtmlErrors(), false);
}
exit();

?>
