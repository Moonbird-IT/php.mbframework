<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class IniFileReader
{
  private $settings= FALSE;

  public function __construct($ini) {
    // check the configuration file to use
    $stage= isset($_ENV['STAGE']) ? $_ENV['STAGE'] : 'prod';
    // build file name to be loaded
    $iniFilename= 'etc/'.$stage.'/'.$ini.'.ini';
    if (!file_exists($iniFilename)) {
      throw new FileException ('File '.$iniFilename.' does not exist', ExceptionCode::IO_FILENOTFOUND);
    } else {
      $this->settings= parse_ini_file($iniFilename);
    }
  }
}