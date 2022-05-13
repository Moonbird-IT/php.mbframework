<?php
uses('de.moonbird.interfaces.common.database.IDatabaseConnection');

trigger_error("The MySQL connection class is no longer supported", E_ERROR);

class MysqlConnection extends Connection implements IDatabaseConnection
{
	private $parent = FALSE;
	private $internalConnection = FALSE;

	/**
	 * @param Connection $parent
	 */
	public function __construct($parent)
	{
		$this->parent = $parent;
		if ($this->parent->port=='') {
			$this->parent->port= 3306;
		}
		/** @var mysqli internalConnection */
		$this->internalConnection = new mysqli(
			$this->parent->host,
			$this->parent->username,
			$this->parent->password,
			$this->parent->database,
			$this->parent->port);

		if ($this->internalConnection) {
			$this->parent->state = ConnectionState::OPEN;
		} else {
			$this->parent->state = ConnectionState::FAILED;
		}
	}

	/**
	 * Return a record set
	 *
	 * @param String $query
	 * @return array
	 */
	public function select($query, $filters = FALSE, $arrLikeFilters = FALSE, $orderStatement = "")
	{
		// in case a string has been passed, convert it to an array with 1 element
		$queries = is_array($query) ? $query : array($query);

		foreach ($queries as $query) {
			$stmt = $this->internalConnection->query($query);
			if (!$stmt) {
				$this->parent->message = $this->internalConnection->error;
				return FALSE;
			} else {
				$arrValues= array();
				while ($arrValues[]=$stmt->fetch_array()) { }
				$stmt->free();
				return $arrValues;
			}
		}
	}

	public function execute($query)
	{
		$res = $this->internalConnection->query($query);
		if (!$res) {
			$this->parent->message = $this->internalConnection->error;
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function disconnect()
	{
		$this->internalConnection->close();
	}

	public function fetchCursor($query)
	{
		throw new NotImplementedException('Cursor functionality not implemented', NI_EXCEPTION);
	}

	public function getInsertId()
	{
		return $this->internalConnection->insert_id;
	}

	public function showTables()
	{
		$arrValues = $this->internalConnection->query('show full tables');
		$arrResult = array();
		if (is_array($arrValues)) {
			foreach ($arrValues as $val) {
				// we'll return all table info; to make sure table names can be loaded
				// independant of the underlying DBMS, the array key used will always be the table name itself
				$arrResult[$val[0]] = $val;
			}
		}
		unset($arrValues);
		return $arrResult;
	}

	/**
	 * TODO: vervollständigen
	 * @param $table
	 * @return array
	 */
	public function showColumns($table)
	{
		$arrValues = $this->internalConnection->query(sprintf('
      show full columns in = \'%s\'',
			$table));
		$arrResult = array();
		if (is_array($arrValues)) {
			foreach ($arrValues as $val) {
				// we'll return all table info; to make sure table names can be loaded
				// independant of the underlying DBMS, the array key used will always be the table name itself
				$arrResult[$val['Field']] = array(
					'name'    => $val['Field'],
					'type'    => $val['Type'],
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
}
