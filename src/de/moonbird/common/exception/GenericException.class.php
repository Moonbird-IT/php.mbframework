<?php

class GenericException
{
  private $code = null;
  private $message = null;
  private $context = null;

  /**
   * @var GenericException null
   */
  private $innerException = null;

  public function __construct($code, $message, $context)
  {
    $this->code = $code;
    $this->message = $message;
    $this->context = $context;
  }

  public function setInnerException($innerException) {
    $this->innerException = $innerException;
  }

  public function getCode()
  {
    return $this->code;
  }

  public function getMessage()
  {
    return $this->message;
  }

  public function getContext()
  {
    return $this->context;
  }

  public function getInnerException()
  {
    return $this->innerException;
  }

}