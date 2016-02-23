<?php
/**
 * @author Sascha Meyer Moonbird IT
 * @version: $Id$
 * @extends_ AbstractDatabaseModel
 *
 * Provides a database model used in combination with pagination and sorting
 *
 * The DataSet model should normally define its base query inside of the init()
 * routine, along with defaults for pagination and sorting (if applicable).
 */
uses('de.moonbird.common.model.database.AbstractDatabaseModel');

abstract class AbstractDataSetModel extends AbstractDatabaseModel
{
  private $intTotal=0;
  private $intPage=0;
  private $intPerPage=0;

  // if set to "TRUE", no array sort will be applied
  private $isPresorted= FALSE;
  // no default order field defined; if it has not been set, return unordered
  private $orderField= FALSE;
  private $orderDirection= 'asc';

  private $query='';
  private $queryExtender=FALSE;

  protected $resultSet=array();

  /*----- Setter methods ------*/

  public function setPage($page) {
    $this->intPage= $page;
  }

  public function setPageSize($perPage) {
    $this->intPerPage= $perPage;
  }

  public function isPresorted($isPresorted) {
    $this->isPresorted= $isPresorted;
  }

  public function setSort($field, $direction) {
    $this->orderField= $field;
    $this->orderDirection= $direction;
  }

  public function setQuery($query) {
    $this->query= $query;
  }

  public function setExtender($queryExtender) {
    $this->queryExtender= $queryExtender;
  }

  /*---- Getter methods ----------*/

  public function getCount() {
    return $this->intTotal;
  }

  public function getPage() {
    return $this->intPage;
  }

  public function getPerPage() {
    return $this->intPerPage;
  }

  public function get() {
    return $this->resultSet;
  }

  /*--- Initialisation & Execution -----------*/

  /**
   * Assigns database connection and initialises defaults (base query, page size etc.)
   *
   * @param Connection $connection
   */
  public function __construct($connection) {
    $this->connection= $connection;
    $this->init();
  }

  /**
   * Must be implemented by classes inheriting AbstractDataSetModel to define presets
   */
  public abstract function init();

  public function execute() {
    $this->resultSet= $this->connection->select($this->query, $this->queryExtender);

    if (is_array($this->resultSet)) {
      $this->intTotal= count($this->resultSet);

      // do sorting
      if (!$this->isPresorted && $this->orderField) {
        $sorter = new ArraySorter();
        $sorter->setField($this->orderField);
        $sorter->setOrder($this->orderDirection);
        $sorter->sort($this->resultSet);
      }

      // return a slice if page size is greater than 0
      if ($this->intPerPage>0) {
        $this->resultSet= array_slice($this->resultSet, ($this->intPage-1)*$this->intPerPage, $this->intPerPage);
      }
      return TRUE;
    } else {
      return FALSE;
    }
  }

  /**
   * Add additional information after data has been loaded
   *
   * In some cases you may want to "enrich" the collected data with additional information.
   * This can be done through calling the upgrade() routine after the data has been
   * loaded and sliced according to the page size defined.
   * The function has not been defined abstract because it does not have to be present.
   */
  public function upgrade() {
    // default: nothing to do here
  }
}
