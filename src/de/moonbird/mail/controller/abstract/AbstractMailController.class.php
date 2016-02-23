<?php

/**
 * AbstractMailController
 *
 * This class is intended to be used by all controllers delivering mail content.
 * When using it, you will have to point the controller to its assigned view, you may
 * also add several new includes (CSS/JS/...) that will be added to the HTML head before
 * showing the assigned view. All content will be buffered and can be loaded using getContents().
 * TODO: structure is currently too loose and no defaults are defined; rewrite to use defaults
 * @author Sascha Meyer Moonbird IT
 * @version 1.0
 * @package Mail
 * @extends AbstractViewController
 */
uses('de.moonbird.web.controller.abstract.AbstractViewController');

abstract class AbstractMailController extends AbstractViewController
{
  protected $contents= '';

  protected function head() {
    ob_clean();
    $headDirectives= implode("\n", $this->arrHeadIncludes);
    include (Configuration::get('layout', 'mail', 'header'));
  }

  protected function foot() {
    include (Configuration::get('layout', 'mail', 'footer'));
    $this->contents= ob_get_clean();
  }

  protected function getContents() {
    return $this->contents;
  }

}
