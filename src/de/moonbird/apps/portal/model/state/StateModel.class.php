<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 04.12.12
 * Time: 09:53
 * @version: $Id$
 * Purpose: Retrieve state information from Portal database
 */
uses('de.moonbird.common.model.database.AbstractDatabaseModel');

class StateModel extends AbstractDatabaseModel
{

  public function getById($id) {
    $arrValues= $this->connection->select(sprintf('
      SELECT id FROM state WHERE name = \'%s\'
    ', $id));
    if (is_array($arrValues) && count($arrValues)>0) {
      return $arrValues[0]['name'];
    } else {
      return FALSE;
    }
  }

  public function getByName($name) {
    $arrValues= $this->connection->select(sprintf('
      SELECT id FROM state WHERE name = \'%s\'
    ', $name));
    if (is_array($arrValues) && count($arrValues)>0) {
      return $arrValues[0]['id'];
    } else {
      return FALSE;
    }
  }
}
