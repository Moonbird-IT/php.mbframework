<?php

/**
 * Recordset class
 *
 * implements standard functions required for dataset operations
 */
class Recordset {

  private $connection = FALSE;

  /**
   * return a single row from a dataset
   */
  public function selectSingle($query) {
    if ($this->connection) {
      $result = $this->connection->select($query);
      return is_array($result) ? $result[0] : $result;
    } else {
      return FALSE;
    }
  }

  /**
   * return a complete recordset
   */
  public function selectMultiple($query) {
    if ($this->connection) {
      $result = $this->connection->select($query);
      return $result;
    } else {
      return FALSE;
    }
  }

  public function __construct($connection= FALSE) {
    $this->connection= $connection;
  }

}

?>
