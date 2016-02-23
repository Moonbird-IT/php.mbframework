<?php
abstract class Formatter {

  public static function digitStringFromNumber($number, $countDigits= 2) {
    if (strlen($number)>=$countDigits) {
      return $number;
    } else {
      return str_repeat('0', $countDigits-strlen($number)).$number;
    }
  }

  public static function timeStringFromFloat($t) {
	if ($t<0){
		$prefix = "-";
		$t = $t*-1;
	} else {
		$prefix = "";
	}
	$time = self::digitStringFromNumber(floor($t*24)).":".
    self::digitStringFromNumber(floor(($t*1440) % 60)).":".
    self::digitStringFromNumber(floor(($t*86400)%60));
	return $prefix.$time;
  }
}
?>