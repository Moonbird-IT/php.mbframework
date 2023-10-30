<?php

/**
 * Common date and time function helper class.
 */
class DateUtil
{

  /**
   * Return the week's end date for a given week and year.
   * @param int $week
   * @param int $year
   * @return false|string
   */
  public static function getEndOfWeek($week, $year)
  {
    $Jan1 = mktime(1, 1, 1, 1, 1, $year);
    $sundayOffset = (11 - date('w', $Jan1)) % 7 + 4;
    $desiredSunday = strtotime(($week - 1) . ' weeks ' . $sundayOffset . ' days', $Jan1);
    return date('Y-m-d 00:00:00', $desiredSunday);
  }

  /**
   * Return the month's end date for a given month and year.
   * @param int $month
   * @param int $year
   * @return string
   */
  public static function getEndOfMonth($month, $year)
  {
    $time = mktime(1, 1, 1, $month + 1, 1, $year);
    return $year . '-' . date('m', $time) . '-01 00:00:00';
  }

  /**
   * Convert a duration represented as hour string into a number of seconds.
   * @param int $timeString
   * @return float|int|null
   */
  public static function timeStringToNumber($timeString)
  {

    try {
      if (($timeString == 'NULL' || $timeString == '')) return null;

      $arrParts = explode(':', $timeString);

      $positiveNegativeMultiplier = 1;
      if ($arrParts[0] < 0) {
        $positiveNegativeMultiplier = -1;
        if (!is_numeric($arrParts[0])) {
          fwrite(STDOUT, PHP_EOL."TSTN - not a number: {$arrParts[0]}, {$timeString}" .PHP_EOL);
          return null;
        } else {
          $arrParts[0] = (int)$arrParts[0] * -1;
        }
      }
      return (((int)$arrParts[0] * 3600) + ((int)$arrParts[1] * 60) + (int)$arrParts[2]) * $positiveNegativeMultiplier;
    } catch (Exception $ex) {
      fwrite(STDOUT, "Exception occurred with value {$timeString}");
      return null;
    }
  }

  /**
   * Convert fraction of a day into a duration represented as hour string.
   * @param string $value
   * @return array
   */
  public static function numberToHourString($value)
  {
    if ($value < 0) {
      $value = 0;
    }
    $edSeconds = $value * (24 * 60 * 60);
    return self::secondsToHourString($edSeconds);
  }

  /**
   * Convert seconds into a duration represented as hour string.
   * @param int $edSeconds
   * @return array
   */
  public static function secondsToHourString($edSeconds) {
    // create an understandable time format


    $edSec = floor($edSeconds) % 60;
    $edMinutes = floor($edSeconds / 60) % 60;

    $edHours = floor($edSeconds / 60 / 60);

    $edHours = strlen($edHours > 2) ? $edHours : str_repeat('0', 2 - strlen($edHours));

    $strTime = $edHours . ":" . str_repeat('0', 2 - strlen($edMinutes)) . $edMinutes . ":" . str_repeat('0', 2 - strlen($edSec)) . $edSec;

    return array($strTime, $edSeconds);
  }

  public static function decimalHoursToHourString($value)
  {
    if ($value < 0) {
      $value = 0;
    }

    // create an understandable time format
    $edSeconds = $value * (60 * 60);

    $edSec = floor($edSeconds) % 60;
    $edMinutes = floor($edSeconds / 60) % 60;

    $edHours = floor($edSeconds / 60 / 60);
    $edHours = strlen($edHours > 2) ? $edHours : str_repeat('0', 2 - strlen($edHours));

    $strTime = $edHours . ":" . str_repeat('0', 2 - strlen($edMinutes)) . $edMinutes . ":" . str_repeat('0', 2 - strlen($edSec)) . $edSec;

    return array($strTime, $edSeconds);
  }

  /**
   * Take a user given date representation and convert it to ISO-8601 date string.
   * @throws InvalidArgumentException
   * @param string $value
   * @return false|string
   */
  public static function inputToIsoString($value) {
    return self::inputToGivenFormat($value, 'Y-m-d\TH:i:s');
  }

  /**
   * Take a user given date representation and convert it into given format.
   * @param string $value
   * @param string $format
   * @return false|string
   */
  public static function inputToGivenFormat($value, $format) {
    $date = strtotime($value);
    if ($date) {
      return date($format, $date);
    } else {
      throw new InvalidArgumentException('The passed value "'.$value.'" does not represent a valid date.');
    }

  }
}