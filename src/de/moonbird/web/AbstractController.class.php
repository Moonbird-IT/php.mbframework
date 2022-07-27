<?php

throw new Exception("This controller is no longer supported", -2017);

abstract class AbstractController {
  private $view= FALSE;
  private $handlers= array();

  /**
   * attach a form handler
   *
   * @param Class $handler
   */
  public function addHandler ($handler) {
    $this->handlers[]= $handler;
  }

  /**
   * attach a view to the current controller
   *
   * @param String $view
   */
  public function attach ($view) {
    $this->view = $view;
  }

  /**
   * Performs operation
   */
  public function run () {

    if ($this->view) {
      
    }
  }


}
?>
