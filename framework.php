<?php
if(file_exists(dirname(__FILE__).'/../persohub/persohub.php')){
    include_once(dirname(__FILE__).'/../persohub/persohub.php');
    dd('Framework init');
}
// Framework loader
ini_set('include_path', ini_get('include_path') . ';' . getcwd());
include_once ("framework/exceptions.php");
include_once ("framework/loader.php");
uses(
  'de.moonbird.common.exception.enum.ExceptionCode',
  'de.moonbird.common.registry.Registry',
  'de.moonbird.common.configuration.IniFileLister',
  'de.moonbird.common.configuration.IniFileReader',
  'de.moonbird.common.configuration.Configuration'
);
// set the development stage (can be dev, qa, prod)
Registry::set('STAGE', (getenv('STAGE')!=FALSE) ? getenv('STAGE') : 'prod');

// set working directory
Registry::set('PROJECTPATH', getcwd().DIRECTORY_SEPARATOR);

// read ini files
Registry::set('CONFIGURATION', iniFileLister::getList());
?>
