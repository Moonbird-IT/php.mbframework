<?php

// simple date calculations

class DateUtil {

  public static function getEndOfWeek($week, $year) {
    $Jan1 = mktime(1, 1, 1, 1, 1, $year);
    $sundayOffset = (11 - date('w', $Jan1)) % 7 + 4;
    $desiredSunday = strtotime(($week - 1) . ' weeks ' . $sundayOffset . ' days', $Jan1);
    return date('Y-m-d 00:00:00', $desiredSunday);
  }

  public static function getEndOfMonth($month, $year) {
    $time = mktime(1, 1, 1, $month+1, 1, $year);
    return $year . '-' . date('m', $time) . '-01 00:00:00';
  }

  public static function timeStringToNumber($timeString)
  {
    $arrParts= explode(':', $timeString);
    $positiveNegativeMultiplier= 1;
    if ($arrParts[0]<0) {
        $positiveNegativeMultiplier= -1;
        $arrParts[0]= $arrParts[0]*-1;
    }
    return (($arrParts[0]*3600) + ($arrParts[1]*60) + $arrParts[2]) * $positiveNegativeMultiplier;
}

}