<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sascha Meyer Moonbird IT
 * Date: 02.09.12
 * Time: 16:41
 * Base class for backend calls (i.e. XML-/JSON-based AJAX calls)
 */
class BackendFactory
{
  /** @var BaseBackend */
  private $backend= FALSE;

  protected function __construct($type) {

    // check if the given backend type exists; if it does, include it create a new instance
    if (check_dependency('de.moonbird.web.backend.type.'.$type.'.'.$type.'Backend')) {
      uses ('de.moonbird.web.backend.'.$type.'.'.$type.'Backend');
      $backendType= ucfirst(strtolower($type)).'Backend';
      $this->backend= new $backendType;
      if ($this->backend instanceof $backendType) {
        $this->initialized= TRUE;
      }
    }
  }

  protected function isInitialized()
  {
    return $this->initialized;
  }

  protected function setStatus($status)
  {
    $this->backend->setStatus($status);
  }

  protected function setData($data)
  {
    $this->backend->setData($data);
  }

  protected function setAdditional($data)
  {
    $this->setAdditional($data);
  }

}
