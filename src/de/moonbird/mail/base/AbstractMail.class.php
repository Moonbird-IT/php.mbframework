<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 29.12.12
 * Time: 12:59
 * @version: $Id$
 * Purpose: Abstract class implemented through the specific mail types, offering base functionality like send() etc.
 */
abstract class AbstractMail
{

  // default from field
  protected $from = 'framework-no-reply@moonbird.de';
  // contains mail content
  protected $content = '';
  protected $variables = array();
  protected $title = '';
  protected $attachments = array();

  protected $recipients = array();

  abstract public function head();

  abstract public function foot();

  abstract public function addContent($content);

  public function __construct() {
    require_once('Mail.php');
    require_once('Mail/mime.php');
  }

  final public function setFrom($from)
  {
    $this->from = $from;
  }

  final public function addRecipient($email)
  {
    if (is_array($email)) {
      $this->recipients = array_merge($this->recipients, $email);
    } else {
      $this->recipients = array_merge(explode(";", str_replace(';', ',', $email)), $this->recipients);
    }
  }

  final public function setVariables($variables)
  {
    $this->variables = $variables;
  }

  final public function addAttachment($fileName, $content)
  {
    $this->attachments[$fileName] = $content;
  }

  final public function replaceStatic($fields)
  {

    if (is_array($fields)) {
      foreach ($fields as $field => $value) {
        $this->content = str_replace('[' . $field . ']', nl2br($value), $this->content);
      }
    }
  }

  final public function setTitle($title)
  {
    $this->title = $title;
  }

  /**
   * Return the generated contents
   *
   * @return string
   */
  public function getContent()
  {
    return $this->content;
  }

  final public function send()
  {
    $mime = new Mail_mime("\r\n");

    $mimeParameters = array();
    $mimeParameters['text_encoding'] = "7bit";
    $mimeParameters['text_charset'] = "UTF-8";
    $mimeParameters['html_charset'] = "UTF-8";
    $mimeParameters['head_charset'] = "UTF-8";

    $headers = array(
      'Subject' => $this->title,
      'From' => $this->from,
      'To' => $this->recipients,
      'MIME-Version' => '1.0',
    );
    $headers["Content-Type"] = 'text/html; charset=UTF-8';
    $headers["Content-Transfer-Encoding"] = "8bit";

    $mime->setHTMLBody($this->content);
    foreach ($this->attachments as $fileName => $content) {
      $mime->addAttachment($content, 'application/octet-stream', $fileName, FALSE);
    }
    $mailBody = $mime->get($mimeParameters);
    $mailHeaders = $mime->headers($headers);

    /*
    preg_match("/boundary=\"(.[^\"]*)\"/e", $mailHeaders["Content-Type"], $boundary);
    $boundary = $boundary[1];
    $bounderyNew = md5($boundary);
    $mailHeaders["Content-Type"] =
      preg_replace('/boundary="(.[^"]*)"/', 'boundary="' . $bounderyNew . '"', $mailHeaders["Content-Type"]);
    $mailBody = preg_replace("/^\-\-" . $boundary . "/s", "--" . $bounderyNew, $mailBody);
    $mailBody = preg_replace("/" . $boundary . "--$/s", $bounderyNew . "--", $mailBody);
    $mailBody = preg_replace("/" . $boundary . "--(\s*)--" . $boundary . "/s", $boundary . "--$1--" .
      $bounderyNew, $mailBody);*/

    $parameters = array(
      'host' => Configuration::get('mail', 'smtp', 'host'),
      'auth' => FALSE,
      'port' => (Configuration::get('mail', 'smtp', 'port') != '' ? Configuration::get('mail', 'smtp', 'port') : 25),
      'username' => Configuration::get('mail', 'smtp', 'user'),
      'password' => Configuration::get('mail', 'smtp', 'password')
    );

    /* @var $smtp Mail */
    $smtp = Mail::factory('smtp', $parameters);
    return $smtp->send($this->recipients, $mailHeaders, $mailBody);
  }
}
