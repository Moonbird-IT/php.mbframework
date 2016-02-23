<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sascha Meyer Moonbird IT
 * Date: 02.09.12
 * Time: 16:41
 * Base class for backend calls (i.e. XML-/JSON-based AJAX calls)
 */
abstract class BaseBackend
{
  protected $status = NULL;
  protected $data = '';
  protected $additionalData = '';

  public function setStatus($status)
  {
    $this->status = $status;
  }

  public function setData($data)
  {
    $this->data = $data;
  }

  public function setAdditional($data)
  {
    $this->additionalData = $data;
  }

  public function respond()
  {
    json_encode(array('status' => $this->status,
        'data' => $this->data,
        'additional' => $this->additionalData)
    );
  }
}
