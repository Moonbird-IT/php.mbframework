<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 18.10.12
 * Time: 11:44
 * @version: $Id$
 * Purpose:
 */
interface IRestricted
{
  public function authenticate();
  public function permit();
  public function forbid();
}
