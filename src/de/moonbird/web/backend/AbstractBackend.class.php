<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sascha
 * Date: 02.09.12
 * Time: 17:19
 * Most Backends will have one type of Response objects associated to it; functionality will be provided through
 * the AbstractBackend
 */
uses('de.moonbird.web.response.ResponseFactory',
  'de.moonbird.web.response.enum.ResponseState');

abstract class AbstractBackend
{
  /** @var ResponseFactory */
  protected $response= FALSE;

  protected function setResponse ($response) {
    $this->response= $response;
    ResponseFactory::create('json');
  }

  protected function isInitialized () {
    return $this->response->isInitialized();
  }
}
