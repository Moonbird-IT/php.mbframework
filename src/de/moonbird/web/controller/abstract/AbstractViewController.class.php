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
 * @author Sascha Meyer
 * @package Web
 */
uses(
  'de.moonbird.common.configuration.Configuration',
  'de.moonbird.interfaces.IRunnable');

abstract class AbstractViewController
{
  protected $arrHeadIncludes = array();
  public $viewData = array();

  /**
   * Needs to be implemented by all controllers. Main handler to be automatically called by central controller handler.
   *
   * @abstract
   * @return mixed
   */
  public abstract function run();

  public function loadView($name, $relativeToController = FALSE)
  {
    if (!$relativeToController) {
      include(str_replace('.', DIRECTORY_SEPARATOR, 'src.' . $name) . 'View.php');
    } else {
      include(str_replace('.', DIRECTORY_SEPARATOR, $name) . 'View.php');
    }
  }

  protected function addHeadInclude($directive)
  {
    $this->arrHeadIncludes[] = $directive;
  }

  protected function addScript($scriptPath)
  {
    $this->addHeadInclude('<script src="' . $scriptPath . '"></script>');
  }

  protected function addStylesheet($styleSheet)
  {
    $this->addHeadInclude('<link rel="stylesheet" href="' . $styleSheet . '"/>');
  }

  private function escapeInput($variable)
  {
    return str_replace(array(';', '\\', "'"), '', $variable);
  }

  protected function head($header = 'header')
  {

    $headDirectives = implode("\n", $this->arrHeadIncludes);
    include(Configuration::get('layout', 'html', $header));
  }

  protected function foot($footer = 'footer')
  {
    include(Configuration::get('layout', 'html', $footer));
  }

  /**
   * Set variables used within a view
   * @param string $name
   * @param mixed $content
   */
  protected function addVariable($name, $content)
  {
    $this->viewData[$name] = $content;
  }

  protected function get($name)
  {
    return array_key_exists($name, $this->viewData) ? $this->viewData[$name] : null;
  }

}
