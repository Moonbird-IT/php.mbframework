<?php
/**
 * Class adds content to a file (and creates file in case it does not exist)
 */
uses (
   'de.moonbird.common.io.enum.FileState',
   'de.moonbird.common.io.FilenameConstructor'
);

class FileAppender
{
  private $filename= '';
  private $fp= FALSE;
  private $state= FileState::INITIAL;

  /**
   * open/reopen the file
   */
  private function open () {
    // open the file in append mode, create it if it does not exist
    $this->fp= fopen(Registry::get('PROJECTPATH').$this->filename, 'a');
    if ($this->fp) {
      $this->state= FileState::OPEN;
    } else {
      $this->state= FileState::FAILED;
      throw new IOException(
          'Log file '.$this->filename.' can not be opened', ExceptionCode::IO_FILEOPEN);
    }
  }

  /**
   * close the opened file
   */
  private function close () {
    fclose($this->fp);
    $this->state= FileState::CLOSED;
  }


  /**
   * Append a line to the log file
   * @param String $string
   */
  public function append ($string) {
    $this->open();
    if (!fwrite($this->fp, $string."\n")) {
      throw new IOException(
        'Log file '.$this->filename.', last state '.$this->state.' not writable', ExceptionCode::IO_WRITEERROR);
    }
    $this->close();
  }

  public function __construct ($filename) {
    $this->filename= FilenameConstructor::construct($filename);

  }
}
?>
