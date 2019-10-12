<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 27.03.2015
 * Time: 13:17
 * @version: $Id$
 * Purpose: 
 */

abstract class AbstractStore {
  protected $message= '';


  public function getLastMessage() {
    return $this->message;
  }

  abstract public function changeDir($path);
  abstract public function getList();
  abstract public function load($name);
  abstract public function save($name, $content);
  abstract public function remove($name);

}