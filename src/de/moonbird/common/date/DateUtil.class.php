<?php

// simple date calculations

class DateUtil
{

  public static function getEndOfWeek($week, $year)
  {
    $Jan1 = mktime(1, 1, 1, 1, 1, $year);
    $sundayOffset = (11 - date('w', $Jan1)) % 7 + 4;
    $desiredSunday = strtotime(($week - 1) . ' weeks ' . $sundayOffset . ' days', $Jan1);
    return date('Y-m-d 00:00:00', $desiredSunday);
  }

  public static function getEndOfMonth($month, $year)
  {
    $time = mktime(1, 1, 1, $month + 1, 1, $year);
    return $year . '-' . date('m', $time) . '-01 00:00:00';
  }

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

  public static function numberToHourString($value)
  {
    if ($value < 0) {
      $value = 0;
    }
    $edSeconds = $value * (24 * 60 * 60);
    return self::secondsToHourString($edSeconds);
  }

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
}