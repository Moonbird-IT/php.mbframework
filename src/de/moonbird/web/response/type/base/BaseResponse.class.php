<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sascha Meyer Moonbird IT
 * Date: 02.09.12
 * Time: 16:41
 * Base class for backend calls (i.e. XML-/JSON-based AJAX calls)
 */
uses('de.moonbird.web.response.enum.ResponseState');

abstract class BaseResponse
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
    print 'State: ' . $this->status . "\n";
    print 'Data:  ' . $this->data . "\n";
  }

}
