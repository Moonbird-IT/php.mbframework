<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * XmlNode representation.
 * @deprecated never completed, do not use.
 */
class XmlNode {
    //put your code here

  // TODO: complete when needed
  public static function fromArray ($array) {
    $node= new DOMNode();
    foreach ($array as $key=> $value) {
      if (is_array($value)) {
        self::fromArray($value);
      } else {

      }
    }
  }
}