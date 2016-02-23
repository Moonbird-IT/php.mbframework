<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sascha Meyer Moonbird IT
 * Date: 02.09.12
 * Time: 16:41
 * Base class for JSON responses
 */

uses('de.moonbird.web.backend.type.base.BaseBackend');

class JsonBackend extends BaseBackend
{

  public function respond()
  {
    json_encode(array('status' => $this->status,
        'data' => $this->data,
        'additional' => $this->additionalData)
    );
  }
}
