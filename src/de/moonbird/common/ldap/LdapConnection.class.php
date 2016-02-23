<?php
uses(
  'de.moonbird.common.database.enum.ConnectionState',
  'de.moonbird.common.array.ArrayUtil'
);

class LdapConnection
{

  protected $connection = FALSE;
  protected $host = FALSE;
  protected $port = FALSE;
  protected $domain = FALSE;
  protected $baseDn = FALSE;
  protected $username = FALSE;
  protected $password = FALSE;
  public $message = FALSE;
  public $lastQuery = '';
  protected $state = ConnectionState::INITIAL;

  /**
   * Returns a new LdapConnection class object
   *
   * @param string $url
   * @return bool|LdapConnection
   */
  public function __construct($url) {
    // check the required class to be loaded
    if (strlen($url) > 0) {
      preg_match('|([\w\d\-\._]*)[:/]?(\d*)/{([\d\w-=,]*)}\?([\w\d]*)(@[\w\d.]*)=(.*)|', $url, $urlParts);
      // we should now have an array with 8 elements:
      // orig. url, driver, database, port (if applicable), empty field,
      // database name, username and password
      if (count($urlParts) > 5) {
        $this->host = $urlParts[1];
        $this->port = ($urlParts[2] != '') ? $urlParts[2] : 389;
        $this->baseDn = $urlParts[3];
        $this->username = $urlParts[4];
        $this->domain = $urlParts[5];
        $this->password = $urlParts[6];

        // open the LDAP connection
        $this->connection= ldap_connect($this->host, $this->port);
        if ($this->connection) {
          ldap_set_option($this->connection, LDAP_OPT_PROTOCOL_VERSION,3);
          ldap_set_option($this->connection, LDAP_OPT_REFERRALS,0);

          // authenticate
          $bind= ldap_bind($this->connection, $this->username.$this->domain, $this->password);

          if ($bind) {
            $this->state= ConnectionState::OPEN;
          } else {
            $this->message= ldap_error($this->connection);
            $this->state= ConnectionState::FAILED;
          }

        } else {
          $this->message= ldap_error($this->connection);
          $this->state= ConnectionState::FAILED;
        }
      } else {
        return FALSE;
      }
    } else {
      return FALSE;
    }
  }

  /**
   * query the database, return a result set
   *
   * @param String|array $query
   * @param array|bool $filters
   * @param int $numEntries
   * @return Array
   */
  public function select($query, $filters = array("mail","sn","givenName"), $numEntries=0) {
    $result= ldap_search($this->connection, $this->baseDn, str_replace($this->baseDn, '', $query), $filters, 0,
      $numEntries);
    if ($result) {
      return ldap_get_entries($this->connection, $result);
    }

  }


  /**
   * Disconnect LDAP connection
   */
  public function disconnect() {
    ldap_close($this->connection);
    $this->connection= FALSE;
  }

  /**
   * get the last error message
   *
   * @return String
   */
  public function lastMessage() {
    return $this->message;
  }

  /**
   * Return the connection's state
   *
   * @return String
   */
  public function getState() {
    return $this->state;
  }

}

?>
