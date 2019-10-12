<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 27.03.2015
 * Time: 13:16
 * @version: $Id$
 * Purpose: 
 */
uses('de.moonbird.component.io.abstract.AbstractStore',
  'de.moonbird.component.io.remote.abstract.InterfaceRemoteStore');

class FtpStore extends AbstractStore implements InterfaceRemoteStore {

  public function connect($host, $userName, $passWord) {

  }

  public function changeDir($path) {

  }

  public function getList()
  {
    // TODO: Implement getList() method.
  }

  public function load($name)
  {
    // TODO: Implement load() method.
  }

  public function save($name, $content)
  {
    // TODO: Implement save() method.
  }

  public function remove($name)
  {
    // TODO: Implement remove() method.
  }
}