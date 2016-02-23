<?php
// Framework loader
ini_set('include_path', ini_get('include_path') . ';' . getcwd());
include ("framework/exceptions.php");
include ("framework/loader.php");
uses(
  'de.moonbird.common.exception.enum.ExceptionCode',
  'de.moonbird.common.registry.Registry',
  'de.moonbird.common.configuration.IniFileLister',
  'de.moonbird.common.configuration.IniFileReader'
);
// set the development stage (can be dev, qa, prod)
Registry::set('STAGE', (getenv('STAGE')!=FALSE) ? getenv('STAGE') : 'prod');

// set working directory
Registry::set('PROJECTPATH', getcwd().DIRECTORY_SEPARATOR);

// read ini files
Registry::set('CONFIGURATION', iniFileLister::getList());
?>
