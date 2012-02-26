<?php
/*
 *	程式目的 	: Encrypt or decrypt 
 *	使用環境	: PHP + MySQL
 *	作者		: Martin Ku@torch
 *	最後更新	: 2011.10.08(By Martin)
 *	使用說明	:
 *	$encrpt_string = authcode('string that need to encrypt', 'ENCODE');
 *	$decrpt_string = authcode('string that need to decrypt', 'DECODE');
 */

function authcode($string, $operation){
  $key = "金鑰字串，多長都可以，火把，天父，耶穌，聖靈，看見神的榮耀!!!";

  $key = md5($key ? $key : $GLOBALS['auth_key']);
  $key_length = strlen($key);

  $string = ($operation == 'DECODE') ? base64_decode($string) : substr(md5($string.$key), 0, 8).$string;
  $string_length = strlen($string);

  $rndkey = $box = array();
  $result = '';

  for($i = 0; $i < 256; $i++) {
    $rndkey[$i] = ord($key[$i % $key_length]);
    $box[$i] = $i;
  }

  for($j = $i = 0; $i < 256; $i++) {
    $j = ($j + $box[$i] + $rndkey[$i]) % 256;
    $tmp = $box[$i];
    $box[$i] = $box[$j];
    $box[$j] = $tmp;
  }

  for($a = $j = $i = 0; $i < $string_length; $i++) {
    $a = ($a + 1) % 256;
    $j = ($j + $box[$a]) % 256;
    $tmp = $box[$a];
    $box[$a] = $box[$j];
    $box[$j] = $tmp;
    $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
  }

  if($operation == 'DECODE') {
    if(substr($result, 0, 8) == substr(md5(substr($result, 8).$key), 0, 8)) {
      return substr($result, 8);
    } else {
      //decode error
      return '';
    }
  } else {
    return str_replace('=', '', base64_encode($result));
  }
}

?>
