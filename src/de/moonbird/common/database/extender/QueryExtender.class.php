<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 30.09.12
 * Time: 18:53
 * @version: $Id$
 * Purpose:
 */
class QueryExtender
{
  private $arrStatements= array();

  /**
   * Construct part of a WHERE clause using a column name and single value or array
   * @param string $field
   * @param string|number|Array $value
   * @param string $connector
   * @param bool $treatAsCharacter
   */
  public function addDirectClause ($field, $value, $connector= 'AND', $treatAsCharacter=FALSE) {

    if ($value != '') {
      if (is_array($value)) {

        $boolNullGiven= FALSE;
        // check if the first element contains a numeric value; if yes, add quotes around all elements
        $intNullPos= array_search('[NULL]', $value);
        if ($intNullPos !== FALSE) {
          $boolNullGiven= TRUE;
          unset($value[$intNullPos]);
        }

        // only extend query if values given
        if (count($value)>0) {
          if (!is_numeric($value[0]) || $treatAsCharacter) {
            ArrayUtil::enquote($value);
          }
          //$this->add('('.$field . ' IN (' . implode(',', $value) . ') OR '.$field .' IS NULL)', $connector);
          if ($boolNullGiven) {
            $this->add('('.$field . ' IN (' . implode(',', $value) . ') OR '.$field .' IS NULL)', $connector);
          } else {
            $this->add('('.$field . ' IN (' . implode(',', $value) . '))', $connector);
          }
        } else {
          if ($boolNullGiven) {
            $this->add($field . ' IS NULL', 'AND');
          }
        }

        // WHERE ... IN (...,...)
      } else {
        if ($value != '[NULL]') {
          $this->add($field . ' = ' . (is_numeric($value) && !$treatAsCharacter ? $value : "'" . $value . "'"), $connector);
        } else {
          $this->add($field . ' IS NULL', $connector);
        }

      }
    }
  }

  public function addLikeClause ($field, $value, $connector= 'AND') {
    if ($value != '') {
      $this->add($field . ' LIKE \'%' . $value . '%\'', $connector);
    }
  }

  public function addUnparsedClause ($query, $connector= 'AND') {
    $this->add($query, $connector);
  }

  private function add ($statement, $connector) {
    // the first added extension will have the connector replaced with the WHERE clause
    if (count($this->arrStatements)==0) {
      $this->arrStatements[]= ' WHERE '.$statement;
    } else {
      $this->arrStatements[]= ' '.$connector.' '.$statement;
    }
  }

  public function get () {
    return implode("\n", $this->arrStatements);
  }

}
