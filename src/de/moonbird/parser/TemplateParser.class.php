<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 06.01.13
 * Time: 12:08
 * @version: $Id$
 * Purpose:
 */
class TemplateParser
{

  public function parse($template) {
    if (file_exists_include_path($template)) {
      $contents= file_get_contents($template);
      if (preg_match_all('#\[([\w\s]*)\]#', $contents, $matches)) {
        return $matches[1];
      } else {
        return FALSE;
      }
    } else {
      return FALSE;
    }
  }
}
