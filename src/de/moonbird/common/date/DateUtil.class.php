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

}

?>
