<?php

/**
 * HtmlMail
 *
 * @author Sascha Meyer Moonbird IT
 * @version 1.0
 * @package Mail
 * @extends AbstractMail
 */
uses('de.moonbird.mail.base.AbstractMail');

class HtmlMail extends AbstractMail
{

  /**
   * Add an HTML header
   */
  public function head() {
    ob_clean();
    include (Configuration::get('layout', 'mail', 'header'));
  }

  /**
   * Add a template footer
   */
  public function foot() {
    include (Configuration::get('layout', 'mail', 'footer'));
    $this->content= ob_get_clean();
  }

  /**
   * Include a mail template to define the content
   * @param string $view
   */
  public function addContent($view) {
    include (str_replace('.', DIRECTORY_SEPARATOR, 'src.'.$view).'View.php');
  }

}
