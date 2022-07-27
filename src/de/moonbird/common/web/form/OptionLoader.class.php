<?php

/**
 * User: Sascha Meyer Moonbird IT
 * Date: 25.09.2014
 * Time: 12:01
 * @version: $Id$
 * @TODO: check implementation and compatibility.
 */

trigger_error('OptionLoader should no longer be used.', E_USER_DEPRECATED);

class OptionLoader
{
  private $model = FALSE;
  private $function = FALSE;
  private $collSelected = array();
  private $valueField;
  private $displayField;

  public function setModel($model)
  {
    $this->model = $model;
    return $this;
  }

  public function setFunctionToLoad($function)
  {
    $this->function = $function;
    return $this;
  }

  public function setSelected($arrSelected)
  {
    $this->collSelected = $arrSelected;
    return $this;
  }

  public function setValueField($valueField)
  {
    $this->valueField = $valueField;
    return $this;
  }

  public function setDisplayField($displayField)
  {
    $this->displayField = $displayField;
    return $this;
  }


  public function getValues()
  {
    $strOptions = '';
    try {
      $arrValues = $this->model->$this->function();
      if (is_array($arrValues)) {
        foreach ($arrValues as $val) {
          if (array_key_exists($val[$this->valueField], $this->collSelected)) {
            $strOptions .= sprintf('<option value="%s" selected="selected">%s</option>' . "\n",
              $val[$this->valueField], $val[$this->displayField]);
          } else {
            $strOptions .= sprintf('<option value="%s">%s</option>' . "\n",
              $val[$this->valueField], $val[$this->displayField]);
          }
        }

      }
    } catch (Exception $ex) {
      $strOptions= $ex->getMessage().', Line '.$ex->getLine();
    }
    return $strOptions;
  }
}