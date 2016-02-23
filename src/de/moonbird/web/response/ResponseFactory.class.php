<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sascha Meyer Moonbird IT
 * Date: 02.09.12
 * Time: 16:41
 * Base class for backend call responses (i.e. XML-/JSON-based AJAX calls)
 */

uses('de.moonbird.web.response.enum.ResponseState');

class ResponseFactory
{
  /** @var BaseResponse */
  private $response= FALSE;

  public function __construct($type) {

    // throw a deprecated warning
    // trigger_error('ResponseFactory instantiation deprecated, use ::create()', E_DEPRECATED);

    uses ('de.moonbird.web.response.type.'.$type.'.'.$type.'Response');

    // check if the given backend type exists; if it does, include it create a new instance
    if (check_dependency('de.moonbird.web.response.type.'.$type.'.'.$type.'Response')) {
      uses ('de.moonbird.web.response.type.'.$type.'.'.$type.'Response');
      $responseType= ucfirst(strtolower($type)).'Response';
      $this->response= new $responseType;
      if ($this->response instanceof $responseType) {
        $this->initialized= TRUE;
        // preset state to successful which should be the case on most calls
        $this->setStatus(ResponseState::SUCCESS);
      }
    } else {
      print 'de.moonbird.web.response.type.'.$type.'.'.$type.'Response not found!';
    }
  }

  /**
   * Generates a new Response object for a given type
   *
   * @param String $type
   * @return JsonResponse|bool
   */
  public static function create($type) {
    // check if the given backend type exists; if it does, include it create a new instance
    if (check_dependency('de.moonbird.web.response.type.'.$type.'.'.$type.'Response')) {
      uses ('de.moonbird.web.response.type.'.$type.'.'.$type.'Response');
      $responseType= ucfirst(strtolower($type)).'Response';
      $response= new $responseType;
      if ($response instanceof $responseType) {
        $response->initialized= TRUE;
        // preset state to successful which should be the case on most calls
        $response->setStatus(ResponseState::SUCCESS);
        return $response;
      } else {
        print 'de.moonbird.web.response.type.'.$type.'.'.$type.'Response failed initialisation!';
        return FALSE;
      }
    } else {
      print 'de.moonbird.web.response.type.'.$type.'.'.$type.'Response not found!';
      return FALSE;
    }
  }

  public function isInitialized()
  {
    return $this->initialized;
  }

  public function setStatus($status)
  {
    $this->response->setStatus($status);
  }

  public function setData($data)
  {
    $this->response->setData($data);
  }

  public function setAdditional($data)
  {
    $this->response->setAdditional($data);
  }

  public function respond () {
    $this->response->respond();
  }

}
