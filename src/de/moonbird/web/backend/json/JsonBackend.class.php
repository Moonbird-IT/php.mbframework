<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sascha Meyer Moonbird IT
 * Date: 02.09.12
 * Time: 16:41
 * Base class for JSON responses
 */
class JsonBackend
{
  protected $status = NULL;
  protected $data = '';
  protected $additionalData = '';

  protected function setStatus($status)
  {
    $this->status = $status;
  }

  protected function setData($data)
  {
    $this->data = $data;
  }

  protected function setAdditional($data)
  {
    $this->additionalData = $data;
  }

  protected function respond()
  {
    json_encode(array('status' => $this->status,
        'data' => $this->data,
        'additional' => $this->additionalData)
    );
  }
}
