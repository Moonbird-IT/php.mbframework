<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class OracleConnection extends Connection
{

  private $parent = FALSE;
  private $internalConnection = FALSE;
  private $arrBindParameters = FALSE;

  public function __construct($parent)
  {
    $this->parent = $parent;
    $this->internalConnection = oci_connect(
      $this->parent->username,
      $this->parent->password,
      $this->parent->host . ':' . $this->parent->port . '/' .
      $this->parent->database);

    if ($this->internalConnection) {
      $this->parent->state = ConnectionState::OPEN;
    } else {
      $this->parent->state = ConnectionState::FAILED;
      $this->parent->message = oci_error();
    }
  }

  /**
   * Return a recordset
   *
   * @param String $query
   * @return Array
   * TODO: refactor currently unused parameters
   */
  public function select($query, $filters = false, $arrLikeFilters = false, $orderStatement = '')
  {
    // in case a string has been passed, convert it to an array with 1 element
    $queries = is_array($query) ? $query : array($query);

    $result = array();

    foreach ($queries as $query) {
      $statement = oci_parse($this->internalConnection, $query);
      $this->executeBind($statement);

      if (oci_execute($statement)) {

      } else {
        $this->parent->message = oci_error() . "\n>>> " . $query;
        debug_print_backtrace();
        return FALSE;
      }
    }

    // unset previously bound parameters
    $this->arrBindParameters= FALSE;

    if (!oci_fetch_all($statement, $result, 0, -1, OCI_FETCHSTATEMENT_BY_ROW)) {
      return FALSE;
    }
    return $result;
  }

  /**
   * Execute a statement on the connection
   *
   * @param String $query
   * @return Boolean
   */
  public function execute($query)
  {
    $returnValue = TRUE;
    // in case a string has been passed, convert it to an array with 1 element
    $queries = is_array($query) ? $query : array($query);

    foreach ($queries as $query) {

      $statement = oci_parse($this->internalConnection, $query);
      $this->executeBind($statement);

      if (oci_execute($statement)) {
        $returnValue = $returnValue ? TRUE : FALSE;
      } else {
        $this->parent->message = oci_error() . "\n>>> " . $query;
        debug_print_backtrace();
        $returnValue = FALSE;
      }
    }

    // unset previously bound parameters
    $this->arrBindParameters= FALSE;

    return $returnValue;
  }

  public function fetchCursor($query)
  {
    try {
      $cursor = oci_new_cursor($this->internalConnection);
      $stmt = oci_parse($this->internalConnection, $query);

      oci_bind_by_name($stmt, 'cursor', $cursor, -1, OCI_B_CURSOR);
      $this->executeBind($stmt);

      // unset previously bound parameters
      $this->arrBindParameters= FALSE;

      if (!@oci_execute($stmt)) {
        $this->parent->message = oci_error();
        return FALSE;
      } else {
        @oci_execute($cursor);
        @oci_fetch_all($cursor, $data, 0, -1, OCI_ASSOC + OCI_FETCHSTATEMENT_BY_ROW);
        return $data;
      }
    } catch (Exception $ex) {
      $this->parent->message = $ex->getMessage();
      throw new SqlException ($ex->getMessage(), $ex->getCode());
    }
  }

  public function disconnect()
  {
    oci_close($this->internalConnection);
  }

  public function showTables()
  {
    $arrValues = $this->select('select * from user_objects where object_type = \'TABLE\'');
    $arrResult = array();
    if (is_array($arrValues)) {
      foreach ($arrValues as $val) {
        // we'll return all table info; to make sure table names can be loaded
        // independant of the underlying DBMS, the array key used will always be the table name itself
        $arrResult[$val['OBJECT_NAME']] = $val;
      }
    }
    unset($arrValues);
    return $arrResult;
  }

  /**
   * Bind a set of parameters to a query
   * @param $arrParameters
   */
  public function bindParameters($arrParameters)
  {
    $this->arrBindParameters = $arrParameters;
  }


  /**
   * Execute bound statement and bind named placeholders
   * @param $statement
   */
  private function executeBind(&$statement)
  {
    if (is_array($this->arrBindParameters)) {
      foreach ($this->arrBindParameters as $field => $value) {
        oci_bind_by_name($statement, $field, $value);
      }
    }
  }

  /**
   * Return the internal Oracle connection instance
   * @return bool|resource
   */
  public function getWrappedConnection() {
    return $this->internalConnection;
  }

}