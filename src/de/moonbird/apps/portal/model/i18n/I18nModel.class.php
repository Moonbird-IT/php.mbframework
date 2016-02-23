<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 09.09.12
 * Time: 11:31
 * @version: $Id$
 * Purpose: Model for translations table
 */
uses('de.moonbird.common.model.database.AbstractDatabaseModel');

class I18nModel extends AbstractDatabaseModel
{
  public function get($lang) {
    $query= sprintf('SELECT phrase, translation FROM translations WHERE lang =\'%s\'', $lang);
    $arrValues= $this->connection->select($query);
    if (is_array($arrValues)) {
      return $arrValues;
    } else {
      return FALSE;
    }
  }
}
