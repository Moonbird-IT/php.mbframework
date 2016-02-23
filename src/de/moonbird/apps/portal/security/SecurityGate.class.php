<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 18.10.12
 * Time: 11:31
 * @version: $Id$
 * Purpose: Permission check functionality
 */

uses(
  'de.moonbird.common.database.ConnectionBuilder',
  'de.moonbird.common.encryption.SimpleCrypt',
  'de.moonbird.common.ldap.LdapConnection'
);

class SecurityGate
{

  /** @var Connection $this->connection */
  private $connection = FALSE;
  private $user = FALSE;
  private $pass= FALSE;
  private $stayLoggedIn= FALSE;

  public function __construct($connection) {
    $this->connection= $connection;
  }

  public function setUser($user)
  {
    $this->user = strtolower($user);
    return $this;
  }

  /**
   * activate 30 days session duration
   */
  public function setLTCookie () {
    $this->stayLoggedIn= TRUE;
  }

  public function setPassWord($pass)
  {
    $this->pass = $pass;
    return $this;
  }

  public function hasRight($right)
  {
    if (!$this->user) {
      $this->validateCookie();
      $this->user = $this->getUser();
    }
    $query= sprintf(
        'SELECT COUNT(*) found from v_user_group
          WHERE lower(group_name) = \'%s\'
          AND lower(user_name) = \'%s\'',
        strtolower($right),
        $this->user
      );
    $arrValues = $this->connection->select($query);
    if (is_array($arrValues) && count($arrValues) > 0) {
      return $arrValues[0]['FOUND'] > 0;
    } else {
      return FALSE;
    }
  }

  public function getRights()
  {
    $arrRights = array();
    $arrValues = $this->connection->select(sprintf('
      SELECT group_name
      FROM v_user_group
      WHERE lower(user_name) = \'%s\'
      ',
      $this->user
    ));
    if (is_array($arrValues) && count($arrValues) > 0) {
      foreach ($arrValues as $row) {
        $arrRights[] = trim($row['GROUP_NAME']);
      }
      return $arrRights;
    } else {
      return FALSE;
    }
  }

  /**
   * Check if the user is logged in; at this stage cookie validity is not checked
   * @return bool
   */
  public function isLoggedIn()
  {
    $this->validateCookie();
    return isset($_COOKIE['username']) && isset($_COOKIE['accesskey']);
  }


  /**
   * Check cookie validity
   */
  public function validateCookie()
  {
    {
      $crypt = new SimpleCrypt();
      $crypt->setSecret(Configuration::get('crypt', 'cookie_secret'));
      // if user has a cookie but the authentication fails, invalidate cookie
      if (isset($_COOKIE["username"]) && $_COOKIE["accesskey"] != $crypt->encrypt(base64_decode($_COOKIE["username"]))
        ) {
        //print $crypt->encrypt(base64_decode($_COOKIE["username"]))."<br />";
        //print $_COOKIE["accesskey"]."<br />";
        print printf("Modified security credentials for %s --", $_COOKIE["username"]);
        //die();
        setcookie("username", "", -1, "/");
        setcookie("accesskey", "", -1, "/");
        die();
      }
    }
  }

  /**
   * Authenticate user against AD
   * @return bool
   */
  public function authenticate () {
    $urlAuthorisation= sprintf(Configuration::get('ldap', 'auth', 'url'), $this->user, $this->pass);
    $ldap= new LdapConnection($urlAuthorisation);
    $returnCode= $ldap->getState() == ConnectionState::OPEN;
    $ldap->disconnect();
    return $returnCode;
  }

  /**
   * Return user name from cookie or from previous run
   * @return bool|string
   */
  public function getUser()
  {
    if ($this->isLoggedIn()) {
      return base64_decode($_COOKIE['username']);
    } else {
      return $this->user;
    }
  }

  /**
   * Set cookie information
   */
  public function permit() {
    $crypt = new SimpleCrypt();
    $crypt->setSecret(Configuration::get('crypt', 'cookie_secret'));
    // if user has a cookie but the authentication fails, invalidate cookie
   $accessKey= $crypt->encrypt($this->user);

    if ($this->stayLoggedIn) {
      setcookie("username", base64_encode($this->user), time() + 360000, "/");
      setcookie("accesskey", $accessKey, time() + 360000, "/");
    } else {
      setcookie("username", base64_encode($this->user), 0, "/");
      setcookie("accesskey", $accessKey, 0, "/");
    }
    flush();
  }

  public function logout() {
    setcookie("username", "", -1, "/");
    setcookie("accesskey", "", -1, "/");
    header('location: ?');
  }

}