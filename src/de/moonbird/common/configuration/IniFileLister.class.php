<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class IniFileLister
{
  private static $files= array();

  public static function getList() {
    // check the configuration file to use

    // build file name to be loaded
    $iniFileDirectory= getcwd().DIRECTORY_SEPARATOR.'etc'.DIRECTORY_SEPARATOR.
      Registry::get('STAGE').DIRECTORY_SEPARATOR;

    $handle= opendir($iniFileDirectory);
    if ($handle) {
      while ($file= readdir($handle)) {
        if ($file[0] != '.') {
          // store all found ini files in files array
          self::$files[str_replace('.ini', '', strtolower($file))]=
                  @parse_ini_file($iniFileDirectory.$file, TRUE);
        }
      }
      return self::$files;
    }
  }
  
}

?>
