<?php
/**
 * create a list of dependant includes for a class (not needing to be classes)
 */
function depends()
{
  $funcArgs = func_get_args();
  if (count($funcArgs) == 0) {
    throw new IllegalArgumentException ('depends() expects at least one argument',
      ExceptionCode::IA_MISSINGARGUMENT);
  }

  foreach ($funcArgs as $arg) {
    include_once ('src' . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $arg) . '.php');
  }
}

/**
 * include a list of classes for usage
 */
function uses()
{
  $funcArgs = func_get_args();
  if (count($funcArgs) == 0) {
    throw new IllegalArgumentException ('uses() expects at least one argument',
      ExceptionCode::IA_MISSINGARGUMENT);
  }

  foreach ($funcArgs as $arg) {
    include_once ('src' . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, strtolower($arg)) . '.class.php');
  }
}

/**
 * check a list of classes if they exist or not; especially required when
 * loading classes through factories
 *
 * @return boolean result
 */
function check_dependency()
{
  $funcArgs = func_get_args();
  if (count($funcArgs) == 0) {
    throw new IllegalArgumentException ('check_dependency() expects at least one argument',
      ExceptionCode::IA_MISSINGARGUMENT);
  }

  // initialize with TRUE
  $dependencyCheck = TRUE;

  foreach ($funcArgs as $arg) {
    $dependencyCheck = !$dependencyCheck ? FALSE :
      file_exists_include_path('src' . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, strtolower($arg)) . '.class.php');
  }
  return $dependencyCheck;
}

function file_exists_include_path($file)
{
  $ps = explode(";", ini_get('include_path'));
  foreach ($ps as $path) {
    if (file_exists($path . DIRECTORY_SEPARATOR . $file)) return TRUE;
  }
  if (file_exists($file)) return TRUE;
  return FALSE;
}

/**
 * Load a component from the class repository
 * @param string $component
 * @return bool|string
 */
function load($component)
{

  if (file_exists('src' . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, strtolower($component)) . '.php')) {
    return file_get_contents('src' . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, strtolower($component)) . '.php');
  } else {
    return FALSE;
  }
}

?>