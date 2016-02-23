<?php
/**
 * creates a new logger with a new logger object (file)
 * if an optional boolean $disabled is passed along, the logger will
 * simply do nothing (i.e. when logging is not required on prod. stage)
 */
uses(
  'de.moonbird.common.logging.LoggerInterface',
  'de.moonbird.common.io.FileAppender');

class FileLogger implements LoggerInterface
{
  private $logger=    FALSE;
  private $disabled=  FALSE;

  public function __construct ($filename, $disabled= FALSE) {
    $this->logger= new FileAppender($filename);
    $this->disabled= $disabled;
  }

  /**
   * log message (can be skipped if disabled through configuration)
   *
   * @param String $string
   * @param int $code (optional)
   */
  public function log ($string, $code= FALSE) {
    if (!$this->disabled) {
      $message= date('Y-m-d H:i:s').'|'.
         Registry::getByArrayKeys('CONFIGURATION', 'project', 'shortname').'|'.
         $string.'|'.$code;
      $this->logger->append($message);
    }
  }
}
?>
