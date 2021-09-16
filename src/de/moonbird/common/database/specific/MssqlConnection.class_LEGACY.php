<?php
class MssqlConnection extends Connection
{
  private $parent = FALSE;
  private $internalConnection = FALSE;

  /**
   * @param Connection $parent
   */
  public function __construct($parent)
  {
    $this->parent = $parent;
    if ($this->parent->instance != '') {
      $this->internalConnection = mssql_connect(
        $this->parent->host.'\\'.$this->parent->instance,
        $this->parent->username,
        $this->parent->password);

    } else {
      $this->internalConnection = mssql_connect(
        $this->parent->host,
        $this->parent->username,
        $this->parent->password);
    }

    if ($this->internalConnection) {
      if (mssql_select_db('[' . $this->parent->database . ']', $this->internalConnection)) {
        $this->parent->state = ConnectionState::OPEN;
      } else {
        $this->parent->state = ConnectionState::FAILED;
      }
    } else {
      $this->parent->state = ConnectionState::FAILED;
    }
  }

  /**
   * Return a recordset
   *
   * @param String $query
   * @return Array
   */
  public function select($query)
  {
    // in case a string has been passed, convert it to an array with 1 element
    $queries = is_array($query) ? $query : array($query);

    $result = array();

    foreach ($queries as $query) {
      $res = @mssql_query($query, $this->internalConnection);
      if (!$res) {
        $this->parent->message = mssql_get_last_message();
        return FALSE;
      } else {
        while ($row = mssql_fetch_assoc($res)) {
          $result[] = $row;
        }
        return $result;
      }
    }
  }

  public function execute($query)
  {
    $res = mssql_query($query, $this->internalConnection);

    if (!$res) {
      $this->parent->message = mssql_get_last_message();
      return FALSE;
    } else {
      return TRUE;
    }
  }

  public function disconnect()
  {
    mssql_close($this->internalConnection);
  }

  public function fetchCursor($query)
  {
    throw new NotImplementedException('Cursor functionality not implemented', NI_EXCEPTION);
  }

  public function getInsertId()
  {
    $res = @mssql_query('SELECT SCOPE_IDENTITY()', $this->internalConnection);
    if (!$res) {
      $this->parent->message = mssql_get_last_message();
      return FALSE;
    } elseif (mssql_num_rows($res) > 0) {
      $row = mssql_fetch_row($res);
      return $row[0];
    } else {
      // no insert id available
      return FALSE;
    }
  }

  public function showTables()
  {
    $arrValues = $this->select('SELECT * FROM sys.Tables');
    $arrResult = array();
    if (is_array($arrValues)) {
      foreach ($arrValues as $val) {
        // we'll return all table info; to make sure table names can be loaded
        // independant of the underlying DBMS, the array key used will always be the table name itself
        $arrResult[$val['name']] = $val;
      }
    }
    unset($arrValues);
    return $arrResult;
  }

  public function showColumns($table)
  {
    $arrValues = $this->select(sprintf('
      SELECT column_name, data_type, character_maximum_length
      FROM information_schema.columns
      WHERE table_name = \'%s\'
      ORDER BY ordinal_position',
      $table));
    $arrResult = array();
    if (is_array($arrValues)) {
      foreach ($arrValues as $val) {
        // we'll return all table info; to make sure table names can be loaded
        // independant of the underlying DBMS, the array key used will always be the table name itself
        $arrResult[$val['column_name']] = array(
          'name'    => $val['column_name'],
          'type'    => $val['data_type'],
          'length'  => $val['character_maximum_length']
        );
      }
    }
    unset($arrValues);
    return $arrResult;
  }
}
