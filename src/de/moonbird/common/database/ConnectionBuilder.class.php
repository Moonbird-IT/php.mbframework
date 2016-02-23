<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 24.08.12
 * Time: 18:19
 *
 * Creates and returns a new Connection object based on called function
 */
uses(
  'de.moonbird.common.database.Connection',
  'de.moonbird.common.configuration.DatabaseConfiguration');

abstract class ConnectionBuilder
{

  public static function fromUrl ($url) {
    return new Connection($url);
  }

  public static function fromDatabaseIni ($connectionName) {
    return new Connection(DatabaseConfiguration::get($connectionName));
  }
}
