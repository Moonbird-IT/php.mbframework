<?php
/* 
 * Logger interface for all loggers
 */

interface LoggerInterface
{
  public function log($string, $code= FALSE);
}
?>
