<?php

/*
 * class parses a given file name template and returns the actual string
 */
uses('de.moonbird.common.registry.Registry');

abstract class FilenameConstructor
{
  private static $variables= array();

  public static function construct($filename) {
    self::$variables= array(
         '[ST]' => Registry::get('STAGE'),
         '[Y]' => date('Y'),
         '[m]' => date('m'),
         '[d]' => date('d'),
         '[H]' => date('H'),
         '[i]' => date('i'),
         '[s]' => date('s')
      );
    foreach (self::$variables as $k => $v) {
      // replace all instances of the 
      $filename= strpos($filename,$k)!== FALSE ? 
              str_replace($k, $v, $filename) : 
              $filename;
    }
    return $filename;
  }

  public static function getVariables() {
    return implode(',', array_keys($this->variables));
  }
}
?>
