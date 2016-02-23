<?php

/**
 * User: Sascha Meyer Moonbird IT
 * Purpose: common function used when building dynamic forms
 */
abstract class FormBuilder
{
  public static function fillSelectFromArray($arrValues, $arrSelected)
  {
    asort($arrValues);

    // in case a value has been passed, but it is not an array, create an array of it
    if ($arrSelected != '' && !is_array($arrSelected)) {
      $arrSelected= array($arrSelected => $arrSelected);
    }
    print_r($arrSelected);
    $strOptions = '';
    try {
      if (is_array($arrValues)) {
        foreach ($arrValues as $key =>$value) {
          if (array_key_exists($key, $arrSelected)) {
            $strOptions .= sprintf('<option value="%s" selected="selected">%s</option>' . "\n",
              $key, $value);
          } else {
            $strOptions .= sprintf('<option value="%s">%s</option>' . "\n",
              $key, $value);
          }
        }

      }
    } catch (Exception $ex) {
      $strOptions= $ex->getMessage().', Line '.$ex->getLine();
    }
    return $strOptions;
  }

}