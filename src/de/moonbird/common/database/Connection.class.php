<?php
uses(
  'de.moonbird.common.database.enum.ConnectionState',
  'de.moonbird.common.array.ArrayUtil',
  'de.moonbird.common.database.extender.QueryExtender'
);

class Connection
{

  protected $connection = FALSE;
  protected $driver = FALSE;
  protected $host = FALSE;
  protected $port = FALSE;
  protected $database = FALSE;
  protected $username = FALSE;
  protected $password = FALSE;
  protected $encoding = "AL32UTF8";
  public $message = FALSE;
  public $lastQuery = '';
  protected $state = ConnectionState::INITIAL;

  // logging actions
  private $boolLoggingActive = FALSE;

  /**
   * active logging of statements
   */
  public function activateLogging()
  {

    $this->boolLoggingActive = TRUE;

    // remove old contents from query log
    @file_put_contents("query.log", "");
  }

  public function isLoggingActive() {
    return $this->boolLoggingActive;
  }

  public function getQueryLog()
  {
    return file_get_contents("query.log");
  }

  private function addToQueryLog($query)
  {
    if ($this->boolLoggingActive) {
      file_put_contents("query.log", $query . "\r\n", FILE_APPEND);
    }
  }

  protected $arrFilter = array();

  /**
   * Returns a new Connection class object
   *
   * @param string $url
   * @return bool|Connection
   */
  public function __construct($url)
  {
    // check the required class to be loaded
    if (strlen($url) > 0) {
      preg_match('|(\w*)://([\w\.-]*)[:/]?(\d*)(/d*)([\w-.]*)\?([\w-]*)=([\w-]*)?([\w-]*)|', $url, $urlParts);
      // we should now have an array with 8 elements:
      // orig. url, driver, database, port (if applicable), empty field,
      // database name, username and password
      if (count($urlParts) > 7) {
        $this->driver = $urlParts[1];
        $this->host = $urlParts[2];
        $this->port = $urlParts[3];
        $this->database = $urlParts[5];
        $this->username = $urlParts[6];
        $this->password = $urlParts[7];
      } else {
        return FALSE;
      }

      if ($this->driver != '') {
        $driverClassSrc = dirname(__FILE__) . DIRECTORY_SEPARATOR
          . 'specific' . DIRECTORY_SEPARATOR
          . ucfirst($this->driver) . 'Connection.class.php';

        // check if a Connection class is available for the specific driver
        if (file_exists($driverClassSrc)) {
          include_once($driverClassSrc);
          $driverClass = ucfirst($this->driver) . 'Connection';
          eval('$this->connection= new ' . $driverClass . '($this);');
        } else {
          return FALSE;
        }
      } else {
        return FALSE;
      }
    }
  }


  /**
   * fetch from cursor, maybe not implemented in the driver
   *
   * @param String or array $query
   * @return Array
   */
  public function fetchCursor($query)
  {
    return $this->connection->fetchCursor($query);
  }

  /**
   * query the database, return a resultset
   *
   * @param String|array $query
   * @param array|bool|QueryExtender $filters
   * @param array|bool $arrLikeFilters
   * @return Array
   */
  public function select($query, $filters = FALSE, $arrLikeFilters = FALSE)
  {

    if (!$filters instanceof QueryExtender) {


      // initialise variables for WHERE clause
      $whereQuery = ' WHERE ';
      $subQueries = array();
      $subLikeQueries = array();

      // if an array of filters has been passed, add them to the base query
      if (is_array($filters) && count($filters) > 0) {
        foreach ($filters as $key => $val) {
          if ($val != '') {
            if (is_array($val)) {

              // check if the first element contains a numeric value; if yes, add quotes around all elements
              if (!is_numeric($val[0])) {
                ArrayUtil::enquote($val);
              }
              $subQueries[] = $key . ' IN (' . implode(',', $val) . ')';
              // WHERE ... IN (...,...)
            } else {
              $subQueries[] = $key . ' = ' . (is_numeric($val) ? $val : "'" . $val . "'");
            }
          }
        }
      }
      // if an array of like filters has been passed, add them to the base query
      if (is_array($arrLikeFilters) && count($arrLikeFilters) > 0) {
        foreach ($arrLikeFilters as $key => $val) {
          if (!empty($val)) {
            $subLikeQueries[] = $key . ' LIKE \'%' . $val . '%\'';
          }
        }
      }

      // avoid adding the WHERE clause if no queries have been added
      if (count($subQueries) > 0 || count($subLikeQueries) > 0) {
        $whereQuery .= implode(' AND ', $subQueries);

        // like queries are most likely not be as specific as direct filters and could search more than one field for a specific value
        $whereQuery .= implode(' OR ', $subLikeQueries);
        // if a GROUP BY condition has been found, add the WHERE clause before the GROUP BY
        $query = preg_replace('/GROUP\s+BY/i', $whereQuery . ' GROUP BY ', $query, 1, $count);
        if ($count == 0) {

          // If a placeholder for the where clause is defined, replace, otherwise append where clause
          if (strpos($query, '[WHERE-CLAUSE]')!==FALSE) {
            $query= str_replace('[WHERE-CLAUSE]', $whereQuery, $query);
          } else {
            $query .= $whereQuery;
          }
        }
      }
    } else {

      // if QueryExtender use, get the filter extension from class instance
      $whereQuery = $filters->get();
      $query = preg_replace('/GROUP\s+BY/i', $whereQuery . ' GROUP BY ', $query, 1, $count);
      if ($count == 0) {

        // If a placeholder for the where clause is defined, replace, otherwise append where clause
        if (strpos($query, '[WHERE-CLAUSE]')!==FALSE) {
          $query= str_replace('[WHERE-CLAUSE]', $whereQuery, $query);
        } else {
          $query .= $whereQuery;
        }
      }

    }
    $this->lastQuery = $query;
    $this->addToQueryLog($query);

    return $this->connection->select($query);
  }

  /**
   * Return a single row from the result set or false in case of no or too many rows
   * @param String $query
   * @param Array|bool|QueryExtender $filters
   * @param Array|bool $arrLikeFilters
   * @return Array|bool
   */

  public function selectOne($query, $filters = FALSE, $arrLikeFilters = FALSE) {
    $arrValues= $this->select($query, $filters, $arrLikeFilters);
    if (is_array($arrValues) && count($arrValues)==1) {
      return $arrValues[0];
    } else {
      return FALSE;
    }
  }

  /**
   * execute a command, returns success
   *
   * @param String $query
   * @return boolean
   */
  public function execute($query)
  {
    $this->addToQueryLog($query);
    return $this->connection->execute($query);
  }

  public function disconnect()
  {

    $this->connection->disconnect();
  }

  /**
   * Return the currently used driver
   * @return String
   */
  public function getDriver()
  {
    return $this->driver;
  }

  /**
   * get the last error message
   *
   * @return String
   */
  public function lastMessage()
  {
    return $this->message;
  }

  /**
   * Return the connection's state
   *
   * @return String
   */
  public function getState()
  {
    return $this->state;
  }

  /**
   *
   * Return the last insert ID
   *
   * @return int
   */
  public function getInsertId()
  {
    return $this->connection->getInsertId();
  }

  /**
   * Return a list of found tables
   *
   * @return array
   */
  public function showTables()
  {
    return $this->connection->showTables();
  }


  /**
   * Return a list of columns for a given table
   *
   * @param $table
   * @return array
   */
  public function showColumns($table)
  {
    return $this->connection->showColumns($table);
  }

  public function bindParameters($arrParameters)
  {
    $this->connection->bindParameters($arrParameters);
  }
}
