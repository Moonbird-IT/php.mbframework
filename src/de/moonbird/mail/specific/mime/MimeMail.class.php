<?php

/**
 * MimeMail
 *
 * @author Sascha Meyer Moonbird IT
 * @version 1.0
 * @package Mail
 * @extends AbstractMail
 */
uses('de.moonbird.mail.base.AbstractMail');

class MimeMail extends AbstractMail
{

  /**
   * Add an HTML header
   */
  public function head() {

  }

  /**
   * Add a template footer
   */
  public function foot() {

  }

  /**
   * Include a mail template to define the content
   * @param string $view
   */
  public function addContent($content) {
    $this->content .= $content;
  }

}
