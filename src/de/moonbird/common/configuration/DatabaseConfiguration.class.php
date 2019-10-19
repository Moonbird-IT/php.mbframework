<?php
/**
 * Return a defined database configuration from the database.ini
 */
uses ('de.moonbird.common.configuration.Configuration');

class DatabaseConfiguration extends Configuration
{
  static function get () {
    $argv = func_get_args();
    $key= $argv[0];

    $databases= parent::get('database');
    if ($databases != NULL) {
      if (is_array($databases) && array_key_exists($key, $databases)) {
        // return the url string defined in the requested database connection
        return $databases[$key]['url'];
      } else {
        return NULL;
      }
    } else {
      return NULL;
    }
  }
}
?>
