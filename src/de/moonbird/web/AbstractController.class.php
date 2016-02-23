<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PageInterface
 *
 * @author XDE11069
 */
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
