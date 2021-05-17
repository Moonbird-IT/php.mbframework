<?php

/**
 * store a list of variables and objects for use throughout the application.
 * Implemented as singleton
 */
abstract class Registry {

  public static $registry = array();

  public static function has($key) {
    if (isset(self::$registry[$key])) {
      return true;
    }
    return false;
  }

  public static function set($key, $value) {
    self::$registry[$key] = $value;
    return true;
    /*
    if (!self::has($key)) {
      self::$registry[$key] = $value;
      return true;
    } else {
      throw new Exception('Variable ' . $key . ' already set');
    }
    */
  }

  public static function get($key) {
    if (self::has($key)) {
      return self::$registry[$key];
    }
    return null;
  }

  public static function remove($key) {
    unset(self::$registry[$key]);
  }

  /**
   * Get an array element from the registry by combined key
   *
   * @return String
   */
  public static function getByArrayKeys () {
    //
    $args= func_get_args();
    $argc= count($args);
    if (count($argc>0)){
      $subRegistry= self::$registry[$args[0]];
      for ($i=1;$i<$argc;$i++) {
        $subRegistry= $subRegistry[$args[$i]];
      }
    }
    return $subRegistry;
  }

}