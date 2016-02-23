<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 04.09.12
 * Time: 13:32
 * Purpose: Base for ItemText table operations
 */
uses('de.moonbird.common.model.database.AbstractDatabaseModel');

class DataHelperModel extends AbstractDatabaseModel
{

  public function getDistinctValues($table, $column, $displayColumn= FALSE) {
    if (!$displayColumn) $displayColumn= $column;
    $query= sprintf('
      SELECT DISTINCT %s column_name, %s display_name
      FROM %s
      WHERE %s <> \'\'
      ORDER BY %s ASC
      ', $column, $displayColumn, $table, $displayColumn, $displayColumn);
    return $this->connection->select($query);
  }



}
