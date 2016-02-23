<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 18.10.12
 * Time: 08:58
 * @version: $Id$
 * Purpose: Simple encryption class
 */
class SimpleCrypt
{

  private $secret = "";

  function setSecret($pwd)
  {
    $this->secret = $pwd;
  }

  function encrypt($data, $reverse = false)
  {
    $cipher = "";

    $pwd_length = strlen($this->secret);
    $data_length = strlen($data);

    for ($i = 0; $i < $data_length; $i++) {
      $c = substr($data, $i, 1);
      $ci = substr($this->secret, ($i % $pwd_length), 1);
      if ($reverse == false) {
        $ni = ord($c) + ord($ci);
      } else {
        $ni = ord($c) - ord($ci);
      }
      $nc = chr($ni);
      $cipher .= $nc;
    }
    return $cipher;
  }

  function decrypt($data)
  {
    return $this->encrypt($data, true);
  }
}

