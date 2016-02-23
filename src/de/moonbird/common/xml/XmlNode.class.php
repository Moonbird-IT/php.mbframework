<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of XmlNode
 *
 * @author XDE11069
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
?>
