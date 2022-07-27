<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 24.08.12
 * Time: 15:37
 * Purpose: Abstract database model
 */
uses('de.moonbird.common.array.ArraySorter');

abstract class AbstractDatabaseModel
{
  /** @var Connection */
  protected $connection = FALSE;
  protected $data= FALSE;

  public function __construct($connection)
  {
    $this->connection = $connection;
  }

  public function getLastMessage()
  {
    return trim($this->connection->lastMessage());
  }

  public function sort($field, $order = 'ASC') {
    $sorter= new ArraySorter();
    $sorter->setField($field);
    $sorter->setOrder($order);
    return $sorter->sort($this->data);
  }

  public function fetch ($stmt) {
    return $this->connection->fetch($stmt);
  }

  /**
   * Switch the fetch mode to return the matching data structure to the client.
   * TODO: handle concurrency. Might overwrite the fetch style set in a different part of code.
   *
   * @param $fetchStyle
   * @return void
   */
  public function setFetchStyle($fetchStyle) {
    $this->connection->setFetchStyle($fetchStyle);
  }

}
