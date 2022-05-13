<?php
/**
 * Interface IDatabaseConnection
 *
 * Interface class for all specific DBMS classes which might be instantiated via new {@see DatabaseConnection}.
 */
interface IDatabaseFacade
{

	public function __construct($parent);

	public function select($query);

	public function execute($query);

	public function disconnect();

	public function fetchCursor($query);

	public function bindParameters($arrParameters);

	public function getInsertId();

	public function showTables();

	public function showColumns($table);

	public function query($query, $typeMap = FALSE);

	public function fetch($stmt);

  public function getDriver();
}
