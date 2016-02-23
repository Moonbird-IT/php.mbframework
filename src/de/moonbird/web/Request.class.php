<?php
uses('de.moonbird.common.registry.Registry');

abstract class Request extends Registry
{

  /**
   * Initialise request class
   * @return void
   */
  public static function init()
  {
    $querystring= $_SERVER["QUERY_STRING"];
    if (strpos($querystring, "&")>-1) {
      $querystring= substr($querystring, 0, strpos($querystring, "&"));
      $additionalParams= explode("&", substr($_SERVER["QUERY_STRING"], strpos($querystring, "&")));
    } else {
      $additionalParams= array();
    }
    $moduleArray = @explode("/", $querystring);
    if (is_array($moduleArray)) {
      $module = array_shift($moduleArray);
      $file = array_shift($moduleArray);
      Request::set('module', $module);
      Request::set('file', $file);
      Request::set('params', $moduleArray+$additionalParams);
      Request::set('num_params', count($moduleArray)+count($additionalParams));
    }
    if (Request::get('file') == '') {
      Request::set('file', 'index', TRUE);
    }
    foreach ($_REQUEST as $key => $value) {
      // add additional values, allow overwriting previously added module and file params
      Request::set($key, $value, TRUE);
    }
  }

  /**
   * Return module name
   * @return string
   */
  public static function getModule () {
    return Request::get('module');
  }

  /**
   * Return module file
   * @return string
   */
  public static function getFile () {
    return Request::get('file');
  }
  /**
   * Get number of parameters
   * @return int
   */
  public static function getParameterCount()
  {
    return Request::get('num_params');
  }

  /**
   * Return one element
   * @param int $index
   * @return array
   */
  public static function getParam($index) {
    return Request::$registry['params'][$index];
    // return value of parameter
  }

}
?>