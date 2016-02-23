<?php
/**
 * Author: Sascha Meyer Moonbird IT
 * Purpose: load a given controller based on parameter input
 */

abstract class ControllerLoader
{

  /**
   * Load a controller based on GET parameters
   * @param String $controllerNS
   * @param String $paramControllerName
   * @param String|boolean $defaultController
   * @return AbstractController|boolean
   */
  public static function load($controllerNS, $paramControllerName, $defaultController = FALSE)
  {

    // add the "controller" part to the default namespace
    $controllerNS .= '.controller';
    // define the controller ...

    if (php_sapi_name() == 'cli') {
      $paramController = filter_var($paramControllerName, FILTER_SANITIZE_STRING);
    } else {
      $paramController = filter_input(INPUT_GET, $paramControllerName, FILTER_SANITIZE_STRING);
    }
    $nameController = (($paramController) ? $paramController : $defaultController) . 'Controller';

    // ... load ..
    uses($controllerNS . '.' . $nameController);

    $nameController = strrpos($nameController, '.') > 0 ?
      substr($nameController, strrpos($nameController, '.') + 1) : $nameController;

    if (class_exists($nameController)) {

      // ... and execute the controller (default: MainController)
      /** @var AbstractController $controller */
      $controller = new $nameController;
      return $controller;
    } else {
      return FALSE;
    }
  }
}
