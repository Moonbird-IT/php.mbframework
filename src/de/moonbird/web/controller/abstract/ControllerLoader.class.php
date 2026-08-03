<?php
/**
 * Author: Sascha Meyer Moonbird IT
 * Purpose: load a given controller based on parameter input
 */

abstract class ControllerLoader
{

  /**
   * Load a controller based on GET parameters or CLI parameters.
   *
   * When calling from the CLI, use '-c="my.path.to.Controller"' to load,
   * via web, call it via '?c=my.path.to.Controller'.
   * "Controller" will be automatically appended to the name.
   * Example:
   * "c=login.Login" would load file "src/login/LoginController.class.php" and expects class name to be
   * "LoginController".
   *
   * @param String $controllerNS
   * @param String $paramControllerName
   * @param String|boolean $defaultController
   * @return AbstractController|boolean
   * @throws IllegalArgumentException
   */
  public static function load($controllerNS, $paramControllerName, $defaultController = FALSE)
  {

    // add the "controller" part to the default namespace
    $controllerNS .= '.controller';

    // define the controller based on usage, ...
    if (php_sapi_name() == 'cli') {
      $shortOptions = "c::";
      $longOptions = ["controller::"];
      $options = getopt($shortOptions, $longOptions);
      $paramController = filter_var($options[$paramControllerName] ?? $paramControllerName, FILTER_SANITIZE_STRING);
    } else {
      $paramController = filter_input(INPUT_GET, $paramControllerName, FILTER_SANITIZE_STRING);
    }
    $nameController = $paramController ?: $defaultController . 'Controller';

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
