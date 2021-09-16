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

}
