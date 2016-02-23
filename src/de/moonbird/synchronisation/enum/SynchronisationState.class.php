<?php
/** All synchronisation states used within the application
 *
 */
class SynchronisationState
{
  const INITIAL=0;
  const STARTED=1;
  const WAITING=2;
  const FINISHED=3;
  const FAILED=4;
  const INTERRUPTED=5;
  const FINISHEDWITHERRORS=6;
  const CONNECTFAILED=7;
}
?>
