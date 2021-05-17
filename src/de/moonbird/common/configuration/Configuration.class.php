<?php
/* 
 * Retrieve an element of the Configuration section from the registry
 */
abstract class Configuration
{
  static function get($key=null)
  {
    $argc = func_num_args();
    $argv = func_get_args();

    if (Registry::has('CONFIGURATION')) {
      $value = Registry::get('CONFIGURATION');

      // iterate the list of arguments and extract the value from the next dimension
      for ($i = 0; $i < $argc; $i++) {
        if (is_array($value) && array_key_exists($argv[$i], $value)) {
          $value = $value[$argv[$i]];
        } else {
          return NULL;
        }
      }
      return $value;
    }
    return NULL;
  }
}