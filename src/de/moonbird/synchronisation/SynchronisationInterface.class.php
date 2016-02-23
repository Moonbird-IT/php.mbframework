<?php
/* 
 * Interface for synchronisers
 */
uses ('de.moonbird.synchronisation.enum.SynchronisationState');

interface SynchronisationInterface
{
  public function synchronise ();
  public function getState();
}
?>
