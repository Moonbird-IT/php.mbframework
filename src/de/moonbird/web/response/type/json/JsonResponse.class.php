<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sascha Meyer Moonbird IT
 * Date: 02.09.12
 * Time: 16:41
 * Base class for JSON responses
 */

uses('de.moonbird.web.response.type.base.BaseResponse');

class JsonResponse extends BaseResponse
{

  public function respond()
  {
	  header('Content-type: application/json');
    if (phpversion() >= '7.2') {
      print json_encode(array('status' => $this->status,
          'data' => $this->data,
          'additional' => $this->additionalData),
        JSON_INVALID_UTF8_IGNORE);
    } else {
      print json_encode(array('status' => $this->status,
          'data' => $this->data,
          'additional' => $this->additionalData));
    }
  }
}
