<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 16.04.2019
 * Purpose: file-system related functionality, i.e. creating safe file names.
 */

class FSHelper {

  /**
   * Remove unsafe characters from string to save file in file system.
   * @param string $fileName
   * @return string|null
   */
  public static function createSafeFileName($fileName) {
    $fileName = preg_replace("([^\w\s\d\-_~,;:\[\]\(\).])", '', $fileName);
    $fileName= preg_replace('/[\x00-\x1F\x7F]/', '', $fileName);
    return preg_replace("([\.]{2,})", '', $fileName);
  }
}