<?php
uses('de.moonbird.interfaces.common.database.IDatabaseConnection');

class MysqliConnection extends Connection implements IDatabaseConnection
{

	private $parent = FALSE;
	/** @var bool|Mysqli $internalConnection */
	private $internalConnection = FALSE;
	private $arrBindParameters = FALSE;

	/**
	 * MysqlConnection implements constructor.
	 *
	 * @param Connection $parent
	 */
	public function __construct($parent)
	{
		$this->parent = $parent;
		if (!is_numeric($this->parent->port))
		{
			$this->parent->port = 3306;
		}
		$this->internalConnection = new Mysqli(
			$this->parent->host,
			$this->parent->username,
			$this->parent->password,
			$this->parent->database,
			$this->parent->port);

		if ($this->internalConnection)
		{
			$this->parent->state = ConnectionState::OPEN;
		}
		else
		{
			$this->parent->state   = ConnectionState::FAILED;
			$this->parent->message = mysqli_connect_error();
		}
	}

	/**
	 * Return a record-set
	 *
	 * @param String $query *
	 *
	 * @return boolean|array
	 */
	public function select($query)
	{


		if ($this->arrBindParameters)
		{
			$statement = $this->internalConnection->prepare($query);
			$this->executeBind($statement);
			$statement->execute();
		} else {
			$statement = $this->internalConnection->query($query);
		}
		if ($statement)
		{
			$this->arrBindParameters = FALSE; // reset the bind parameters

			return $statement->fetch_all(MYSQLI_ASSOC);
		}
		else
		{
			$this->parent->message = $this->internalConnection->error() . "\n>>> " . $query;
			debug_print_backtrace();
			$this->arrBindParameters = FALSE; // reset the bind parameters

			return FALSE;
		}
	}

	/**
	 * Execute a statement on the connection
	 *
	 * @param String $query
	 *
	 * @return Boolean
	 */
	public function execute($query)
	{
		$returnValue = TRUE;
		// in case a string has been passed, convert it to an array with 1 element
		$queries = is_array($query) ? $query : array($query);

		foreach ($queries as $query)
		{
			if ($this->arrBindParameters)
			{
				$statement = $this->internalConnection->prepare($query);
				$this->executeBind($statement);
				$statement->execute();
			} else {
				$statement = $this->internalConnection->query($query);
			}
			if ($statement)
			{
				$returnValue = $returnValue ? TRUE : FALSE;
			}
			else
			{
				$this->parent->message = $this->internalConnection->error . "\n>>> " . $query;
				debug_print_backtrace();
				$returnValue = FALSE;
			}
		}
		$this->arrBindParameters = FALSE; // reset the bind parameters

		return $returnValue;
	}

	public function fetchCursor($query)
	{
		throw new NotImplementedException('MySQL does not support cursors.', NI_EXCEPTION);
	}

	public function disconnect()
	{
		$this->internalConnection->close();
	}

	public function showTables()
	{
		$arrValues = $this->select('SELECT * FROM information_schema.tables');
		$arrResult = array();
		if (is_array($arrValues))
		{
			foreach ($arrValues as $val)
			{
				// we'll return all table info; to make sure table names can be loaded
				// independent of the underlying DBMS, the array key used will always be the table name itself
				$arrResult[$val['TABLE_NAME']] = $val;
			}
		}
		unset($arrValues);

		return $arrResult;
	}

	public function bindParameters($arrParameters)
	{
		$this->arrBindParameters = $arrParameters;
	}

	private function executeBind(&$statement)
	{
		$bindParameterArray = array();
		$typeString         = '';
		if (is_array($this->arrBindParameters))
		{
			foreach ($this->arrBindParameters as $param)
			{
				$typeString .= $param['type'];
				print $param['type'] . '<br />';
			}
			$bindParameterArray[] = $typeString;

			foreach ($this->arrBindParameters as $param)
			{
				$bindParameterArray[] = &$param['value'];
				print $param['value'] . '<br />';
			}
			print_r($statement);
			call_user_func_array(array($statement, 'bind_param'), $bindParameterArray);

		}
	}

	public function getInsertId()
	{
		return $this->internalConnection->insert_id;
	}

	public function showColumns($table)
	{
		$query = sprintf('
			SELECT * FROM information_schema.tables 
			WHERE table_name =\'%s\' 
			AND table_schema = \'%s\'',
			$table, $this->parent->database);

		return $this->select($query);

	}
}
