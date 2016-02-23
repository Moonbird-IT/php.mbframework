<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 09.09.12
 * Time: 10:43
 * @version: $Id$
 * Purpose: Return user information from Portal database
 */
uses('de.moonbird.common.model.database.AbstractDatabaseModel');

class UserModel extends AbstractDatabaseModel
{

  public function getById($id)
  {
    $query = sprintf('SELECT * FROM users WHERE id = %d', $id);
    $arrValues = $this->connection->select($query);
    if (is_array($arrValues) && count($arrValues) > 0) {
      $row = $arrValues[0];
      return $row;
    } else {
      return FALSE;
    }
  }

  public function getByUserName($name)
  {
    $query = sprintf('SELECT * FROM users WHERE username = \'%s\'', $name);
    $arrValues = $this->connection->select($query);
    if (is_array($arrValues) && count($arrValues) > 0) {
      $row = $arrValues[0];
      return $row;
    } else {
      return FALSE;
    }
  }

  public function get($filter = '')
  {
    $query = sprintf('
      SELECT
        *
      FROM
        users %s
      ORDER BY
        lastname, firstname',
      $filter);
    $arrValues = $this->connection->select($query);
    if (is_array($arrValues) && count($arrValues) > 0) {
      return $arrValues;
    } else {
      return FALSE;
    }
  }

  /** Update fields of a given user
   * @param string $userName
   * @param array $arrFields
   * @return boolean
   */
  public function update($userName, $arrFields) {
    $arrUpdateFields= array();

    foreach ($arrFields as $key => $value) {

      // creates a key value pair to be used in update query i.e. "firstname = 'Horst'"
      $arrUpdateFields[]= sprintf('%s = \'%s\'', $key, $value);
    }
    $updateFields= implode(',', $arrUpdateFields);
    $query= sprintf('UPDATE users
      SET %s
      WHERE username = \'%s\'',
      $updateFields, $userName);
    return $this->connection->execute($query);
  }
}
