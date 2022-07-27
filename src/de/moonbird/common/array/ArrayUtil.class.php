<?php
abstract class ArrayUtil
{

  public static function resetValues(&$array, $start, $count, $newValue = '')
  {
    if ($count < 1) {
      throw (new IllegalArgumentException('count needs to be greater than 1', 'AR001'));
    } else {
      for ($i = 0; $i < $count; $i++) {
        // reset values
        $array[$start + $i] = $newValue;
      }
    }
  }

  private static function utf8EncodeValues(&$item, $key)
  {
    // only encode if not already encoded (without using mbstring extension)
    if (!preg_match('%(?:
        [\xC2-\xDF][\x80-\xBF]        # non-overlong 2-byte
        |\xE0[\xA0-\xBF][\x80-\xBF]               # excluding overlongs
        |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}      # straight 3-byte
        |\xED[\x80-\x9F][\x80-\xBF]               # excluding surrogates
        |\xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
        |[\xF1-\xF3][\x80-\xBF]{3}                  # planes 4-15
        |\xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
        )+%xs', $item)) {
      $item = utf8_encode($item);
    }
  }

  public static function utf8Encode($array)
  {
    if (is_array($array)) {
    array_walk_recursive($array, array('ArrayUtil', 'utf8EncodeValues'));
    }
    return $array;
  }

  private static function enquoteValues(&$item, $key)
  {
    $item = "'" . $item . "'";
  }

  public static function enquote(&$array)
  {
    return array_walk_recursive($array, array('ArrayUtil', 'enquoteValues'));
  }

  public static function fetchDimension($array, $field, $combineSign=FALSE)
  {
    if (!is_array($array)) return FALSE;
    $arrResult = array();
    if (is_array($field)) {

      // if several subfields should be combined ...
      foreach ($array as $val) {
        $arrayFields = array();
        foreach ($field as $fieldName) {
          $arrayFields[] = trim($val[$fieldName]);
        }
        $arrResult[] = implode($combineSign, $arrayFields);
      }
    } else {

      // ... or just one field given
      foreach ($array as $val) {
        $arrResult[] = trim($val[$field]);
      }

    }
    return $arrResult;
  }

  /**
   * Convert an array to a HTML table
   * @param array $arrValues
   * @param array $arrFields
   * @return string
   */
  public static function toTable ($arrValues, $arrFields) {
    $strResult = '';
    if (is_array($arrValues)) {
      foreach ($arrValues as $val) {
        $strResult .= '<tr>'."\n";
        foreach ($arrFields as $field) {
          $strResult .= '<td>'.$val[$field].'</td>';
        }
        $strResult .= "\n".'</tr>'."\n";
      }
    }
    return $strResult;
  }

  public static function getValuesWithoutKeys($array) {
    $result = array();
    if (is_array($array)) {
      foreach ($array as $value) {
        $value = is_array($value) ? self::getValuesWithoutKeys($value) : $value;
        array_push($result, $value);
      }
      return $result;
    }
    return FALSE;
  }
}
