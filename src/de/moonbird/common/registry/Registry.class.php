<?php

/**
 * store a list of variables and objects for use throughout the application.
 * Implemented as singleton.
 */
abstract class Registry
{

  public static $registry = array();

  private static function normalizeKey($key)
  {
    $key = (string)$key;
    if (function_exists('mb_strtolower')) {
      return mb_strtolower($key, 'UTF-8');
    }
    return strtolower($key);
  }

  private static function getArrayValueIgnoreCase($array, $key, &$found)
  {
    $found = false;
    if (!is_array($array)) {
      return null;
    }

    $normalizedKey = self::normalizeKey($key);

    if (array_key_exists($normalizedKey, $array)) {
      $found = true;
      return $array[$normalizedKey];
    }

    if (array_key_exists($key, $array)) {
      $found = true;
      return $array[$key];
    }

    foreach ($array as $currentKey => $value) {
      if (self::normalizeKey($currentKey) === $normalizedKey) {
        $found = true;
        return $value;
      }
    }

    return null;
  }

  public static function has($key)
  {
    return array_key_exists(self::normalizeKey($key), self::$registry);
  }

  public static function set($key, $value)
  {
    self::$registry[self::normalizeKey($key)] = $value;
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

  public static function get($key)
  {
    $key = self::normalizeKey($key);
    if (array_key_exists($key, self::$registry)) {
      return self::$registry[$key];
    }
    return null;
  }

  public static function remove($key)
  {
    unset(self::$registry[self::normalizeKey($key)]);
  }

  /**
   * Get an array element from the registry by combined key
   *
   * @return mixed
   */
  public static function getByArrayKeys()
  {
    $args = func_get_args();
    $argc = count($args);
    if ($argc === 0) {
      return null;
    }

    $subRegistry = self::getArrayValueIgnoreCase(self::$registry, $args[0], $found);
    if (!$found) {
      return null;
    }

    for ($i = 1; $i < $argc; $i++) {
      $subRegistry = self::getArrayValueIgnoreCase($subRegistry, $args[$i], $found);
      if (!$found) {
        return null;
      }
    }

    return $subRegistry;
  }
}