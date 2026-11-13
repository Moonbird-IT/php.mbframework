<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class OracleConnection extends Connection implements IDatabaseConnection
{

  /** @var Connection bool  */
  private $parent = FALSE;
  private $internalConnection = FALSE;
  private $arrBindParameters = FALSE;
  protected $mapFetchStyle = array(
    DatabaseFetchStyle::FETCH_BOTH => OCI_BOTH,
    DatabaseFetchStyle::FETCH_ASSOC => OCI_ASSOC,
    DatabaseFetchStyle::FETCH_ARRAY =>  OCI_NUM
  );
  
  private $queryAssociation = [];

  /** Connection $this->parent */
  public function __construct($parent)
  {
    $this->parent = $parent;
    $this->internalConnection = oci_connect(
      $this->parent->username,
      $this->parent->password,
      $this->parent->host . ':' . $this->parent->port . '/' .
      $this->parent->database,
      $this->parent->encoding !== '' ? $this->parent->encoding : null);

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
   * @return Array|bool
   * TODO: refactor currently unused parameters
   */
  public function select($query, $filters = false, $arrLikeFilters = false, $orderStatement = '')
  {
    // in case a string has been passed, convert it to an array with 1 element
    $queries = is_array($query) ? $query : array($query);

    $result = array();

    foreach ($queries as $query) {
      $stmt = oci_parse($this->internalConnection, $query);
      if ($stmt === FALSE) {
        $error = oci_error($this->internalConnection);
        $this->parent->message = $this->formatOciError($error, $query);
        $this->parent->lastQuery = $query;
        return FALSE;
      }

      $this->storeQuery($stmt,$query);
      $this->executeBind($stmt);

      if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        $this->parent->message = $this->formatOciError($error, $query);
        $this->parent->lastQuery = $query;
        debug_print_backtrace();
        return FALSE;
      }

      if (!$this->canFetchFromStatement($stmt)) {
        $this->parent->message = 'OracleConnection->select can only fetch rows from SELECT statements.' . "\n>>> " . $query;
        $this->parent->lastQuery = $query;
        return FALSE;
      }

      $statementResult = array();
      $fetchStatus = oci_fetch_all($stmt, $statementResult, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
      if ($fetchStatus === FALSE) {
        $error = oci_error($stmt);
        $this->parent->message = $this->formatOciError($error, $query);
        $this->parent->lastQuery = $this->retrieveQuery($stmt);
        return FALSE;
      }

      if (!empty($statementResult)) {
        $result = array_merge($result, $statementResult);
      }
    }

    // unset previously bound parameters
    $this->arrBindParameters= FALSE;
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

      $stmt = oci_parse($this->internalConnection, $query);
      $this->storeQuery($stmt, $query);
      $this->executeBind($stmt);

      if (oci_execute($stmt)) {
        $returnValue = (bool)$returnValue;
      } else {
        $error = oci_error($stmt);
        $this->parent->exception = new GenericException($error['code'], $error['message'], 'OracleConnection->execute');
        $this->parent->lastQuery =  $this->retrieveQuery($stmt);
        debug_print_backtrace();
        $returnValue = FALSE;
      }
    }

    // unset previously bound parameters
    $this->arrBindParameters= FALSE;

    return $returnValue;
  }

  /**
   * Fetch data from an Oracle cursor.
   * @param string $query
   * @return bool|array
   * @throws SqlException
   */
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
        $data = array();
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
   * Bind a set of parameters of a query
   * @param $arrParameters
   */
  public function bindParameters($arrParameters)
  {
    $this->arrBindParameters = $arrParameters;
  }

  /**
   * Bind a set of parameters by reference of a query
   * @param $arrParameters
   */
  public function bindParametersByReference(&$arrParameters)
  {
    print "Using reference binding\n";
    $this->arrBindParameters = $arrParameters;
  }

  /**
   * Return the internal Oracle connection instance
   * @return bool|resource
   */
  public function getWrappedConnection() {
    return $this->internalConnection;
  }

  public function query($query, $typeMap = array())
  {
    $stmt= oci_parse($this->internalConnection, $query);
    if ($stmt) {
      return oci_execute($stmt);
    } else {
      return FALSE;
    }
  }

  public function fetch($stmt) {
    try {
      return oci_fetch_array($stmt);
    } catch (Exception $ex) {
      error_log('Failed reading data from statement, message was: '.$ex->getMessage());
      return FALSE;
    }
  }

  /*---------------------- Privates -----------------------------------*/
  /**
   * Execute bound statement and bind named placeholders
   * @param $stmt
   */
  private function executeBind(&$stmt)
  {
    if (is_array($this->arrBindParameters)) {
      foreach ($this->arrBindParameters as $field => &$value) {
        oci_bind_by_name($stmt, $field, $value);
      }
      unset($value);
    }
  }

  /**
   * Store a prepared statement along with its query.
   * @param $stmt
   * @param string $query
   * @return void
   */
  private function storeQuery($stmt, $query) {
    $this->queryAssociation[(int)$stmt] = $query;
  }

  /**
   * Get the associated query for a given statement.
   * @param $stmt
   * @return string
   */
  private function retrieveQuery($stmt) {
    return $this->queryAssociation[(int)$stmt];
  }

  /**
   * Determine whether the statement can be fetched from safely.
   *
   * ORA-24374 can happen when a fetch is attempted on a statement that does
   * not define a result set, e.g. DML/DDL/PLSQL or an invalid parse/execute path.
   *
   * @param resource $stmt
   * @return bool
   */
  private function canFetchFromStatement($stmt)
  {
    return oci_statement_type($stmt) === 'SELECT';
  }

  /**
   * Build a readable OCI error message with query context.
   *
   * @param array|bool $error
   * @param string $query
   * @return string
   */
  private function formatOciError($error, $query)
  {
    if (is_array($error)) {
      return $error['message'] . "\n>>> " . $query;
    }

    return (string)$error . "\n>>> " . $query;
  }
}