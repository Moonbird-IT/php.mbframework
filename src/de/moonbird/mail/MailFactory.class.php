<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 29.12.12
 * Time: 12:54
 * @version: $Id$
 * Purpose: create a Mail object based on the given mail type
 */
class MailFactory
{
  /**
   * Return a new Mail object
   * @param string $type
   * @return AbstractMail
   * @throws NotImplementedException
   */
  public static function create($type) {
    $type= ucfirst(strtolower($type));
    if (check_dependency('de.moonbird.mail.specific.'.$type.'.'.$type.'Mail')) {
      uses('de.moonbird.mail.specific.'.$type.'.'.$type.'Mail');
      $class= $type.'Mail';
      return new $class;
    } else {
      throw new NotImplementedException('Class '.$type.'Mail not implemented yet');
    }
  }
}
