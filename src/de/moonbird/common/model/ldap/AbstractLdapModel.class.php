<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 24.08.12
 * Time: 15:37
 * Purpose: Abstract LDAP model
 */
uses('de.moonbird.common.ldap.LdapConnection');

abstract class AbstractLdapModel
{
  /** @var LdapConnection */
  protected $connection = FALSE;

  public function __construct($connection)
  {
    $this->connection = $connection;
  }


  public function getLastMessage()
  {
    return $this->connection->lastMessage();
  }

}
