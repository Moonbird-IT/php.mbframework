<?php

/**
 * AbstractViewController
 *
 * This class is intended to be used by all controllers delivering web content.
 * When using it, you will have to point the controller to its assigned view, you may
 * also add several new includes (CSS/JS/...) that will be added to the HTML head pefore
 * showing the assigned view. All content will be output as soon as the loadView() function
 * has been called (will normally be part of the run() function).
 * TODO: structure is currently too loose and no defaults are defined; rewrite to use defaults
 * @author Sascha Meyer Moonbird IT
 * @version 1.1
 * @package Web
 */
uses(
  'de.moonbird.common.configuration.Configuration',
  'de.moonbird.interfaces.IRunnable');

abstract class AbstractViewController
{
  protected $arrHeadIncludes= array();
  public $viewData= array();

  public function loadView($name) {
    include (str_replace('.', DIRECTORY_SEPARATOR, 'src.'.$name).'View.php');
  }

  protected function addHeadInclude ($directive) {
    $this->arrHeadIncludes[]= $directive;
  }

  private function escapeInput($variable)
  {
    return str_replace(array(';', '\\', "'"), '', $variable);
  }

  /**
   * Needs to be implemented by all controllers
   *
   * @abstract
   * @return mixed
   */
  public abstract function run();

  protected function head() {
    $headDirectives= implode("\n", $this->arrHeadIncludes);
    include (Configuration::get('layout', 'html', 'header'));
  }

  protected function foot() {
    include (Configuration::get('layout', 'html', 'footer'));
  }

  /**
   * Set variables used within a view
   * @param string $name
   * @param mixed $content
   */
  protected function addVariable ($name, $content) {
    $this->viewData[$name]= $content;
  }

}
