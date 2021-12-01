<?php
uses('de.moonbird.interfaces.common.database.IDatabaseConnection');

class SqlSrvConnection extends Connection implements IDatabaseConnection
{
  private $parent = FALSE;
  private $internalConnection = FALSE;

	/**
	 * @param Connection $parent
	 */
	public function __construct($parent)
	{
		$this->parent = $parent;
		$connectInfo= array(
			'Database' => $this->parent->database,
			'UID' => $this->parent->username,
			'PWD' => $this->parent->password
		);

	if ($this->parent->instance !== '') {
		$this->parent->host .= '\\' . $this->parent->instance;
	}

		$this->internalConnection = sqlsrv_connect(
			$this->parent->host, $connectInfo);

		if ($this->internalConnection) {
			$this->parent->state = ConnectionState::OPEN;
		} else {
			$this->parent->message= print_r(sqlsrv_errors(), TRUE);
			$this->parent->state = ConnectionState::FAILED;
		}
	}

	/**
	 * Return a recordset
	 *
	 * @param String $query
	 * @return array
   * TODO: refactor currently unused parameters
	 */
	public function select($query, $filters = false, $arrLikeFilters = false, $orderStatement = '')
	{
		// in case a string has been passed, convert it to an array with 1 element
		$queries = is_array($query) ? $query : array($query);

		$result = array();

		foreach ($queries as $query) {
			$res = @sqlsrv_query($this->internalConnection, $query);
			if (!$res) {
				$this->parent->message = print_r(sqlsrv_errors(), TRUE);
				return FALSE;
			} else {
				while ($row = sqlsrv_fetch_array ($res, ($this->fetchStyle !== NULL ? $this->fetchStyle : SQLSRV_FETCH_BOTH))) {
					$result[] = $row;
				}
				return $result;
			}
		}
	}

  public function execute($query)
  {
    $res = @sqlsrv_query($this->internalConnection, $query);
    if (!$res) {
      $this->parent->message = print_r(sqlsrv_errors(), TRUE);
      return FALSE;
    } else {
      return TRUE;
    }
  }

  public function disconnect()
  {
    sqlsrv_close($this->internalConnection);
  }

  public function fetchCursor($query)
  {
    throw new NotImplementedException('Cursor functionality not implemented', NI_EXCEPTION);
  }

  public function getInsertId()
  {
    $res= @sqlsrv_query($this->internalConnection, 'SELECT SCOPE_IDENTITY()');
    if (!$res) {
      $this->parent->message = print_r(sqlsrv_errors(), TRUE);
      return FALSE;
    } elseif (sqlsrv_num_rows($res) > 0) {
      $row = sqlsrv_fetch_array($res, SQLSRV_FETCH_NUMERIC);
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

	public function bindParameters($arrParameters)
	{
		// TODO: Implement bindParameters() method.
	}

	public function query($query, $typeMap = FALSE) {
	  return sqlsrv_query($this->internalConnection, $query);
  }

  public function fetch($stmt) {
	  try {
      return sqlsrv_fetch_array($stmt, ($this->fetchStyle !== NULL ? $this->fetchStyle : SQLSRV_FETCH_BOTH));
    } catch (Exception $ex) {
	    error_log('Failed reading data from statement, message was: '.$ex->getMessage());
	    return FALSE;
    }
  }
}
