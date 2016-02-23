<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sascha
 * Date: 31.08.12
 * Time: 15:15
 * To change this template use File | Settings | File Templates.
 */
class ArraySorter
{
  private $sortField= 'id';
  private $sortOrder = 'asc';

  public function setField($field) {
    $this->sortField= $field;
  }

  public function setOrder($order) {
    $this->sortOrder= $order;
  }

  public function sort (&$array) {
    return usort ($array, array($this, 'compare'));
  }

  private function compare ($a, $b) {
    if (is_numeric($a[$this->sortField]) && is_numeric($a[$this->sortField])) {
        $result= ($a[$this->sortField] == $b[$this->sortField]) ? 0 : (($a[$this->sortField] > $b[$this->sortField]) ? 1 : -1);
    } else {
        $result= strcmp($a[$this->sortField], $b[$this->sortField]);
    }
    // invert result in case of
    return ($this->sortOrder=='asc') ? $result : ($result * -1);
  }

}
