<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 27.03.2015
 * Time: 13:23
 * @version: $Id$
 * Purpose: 
 */

interface InterfaceRemoteStore {
  function connect($host, $userName, $passWord);
}