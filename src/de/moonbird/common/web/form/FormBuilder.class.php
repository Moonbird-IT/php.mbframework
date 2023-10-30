<?php

/**
 * User: Sascha Meyer Moonbird IT
 * Purpose: common function used when building dynamic forms
 */
abstract class FormBuilder
{
  public static function fillSelectFromArray($arrValues, $arrSelected)
  {
	if (isset($arrValues) && !empty($arrValues)){
		asort($arrValues);
	}

    // in case a value has been passed, but it is not an array, create an array of it
    if ($arrSelected != '' && !is_array($arrSelected)) {
      $arrSelected= array($arrSelected => $arrSelected);
    }
    $strOptions = '';
    try {
      if (is_array($arrValues)) {
        foreach ($arrValues as $key =>$value) {
          if (isset($arrSelected) && is_array($arrSelected) && array_key_exists($key, $arrSelected)) {
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

    /**
     * Initialize a value of a form field (main purpose).
     * Can also be used to set up any other variable anywhere in the application.
     * Accepts array or single parameter to be set as conditional parameter, if not present, default will be set.
     * @param $default
     * @param $alternateInput
     * @return array|mixed
     */
  public static function initializeValue($default, $alternateInput = null) {
      if ($alternateInput !== null && !is_array($alternateInput)) {
          $alternateInput = array($alternateInput);
      }
      if (is_array($alternateInput)) {
          foreach ($alternateInput as $input) {
              if (isset($input) && $input != '') {
                  return $input;
              }
          }
      }
      return $default;
  }

}