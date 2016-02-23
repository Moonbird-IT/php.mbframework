<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 23.09.13
 * Time: 16:32
 * @version: $Id$
 * Purpose:
 */
uses(
  'de.moonbird.common.database.ConnectionBuilder',
  'biz.sig.portal.model.UserModel'
);

class UserLdapToolController
{

  public function run()
  {
    $this->assignNames();
  }

  /**
   * Add first and last name for users without
   */
  private function assignNames()
  {
    global $ldapHost, $ldapPort, $ldapBasedn, $ldapDomain;
    global $objCrypt;

    $connection = ConnectionBuilder::fromDatabaseIni('portal');

    $query = '
      SELECT
        username
      FROM
        users';
    $arrValues = $connection->select($query);
    if (is_array($arrValues) && count($arrValues) > 0) {

      // open ldap connection
      $ldap = new LdapConnector();
      $ldap->set_host($ldapHost);
      $ldap->set_port($ldapPort);
      $ldap->set_base($ldapBasedn);
      $ldap->set_domain($ldapDomain);


      $user = $_COOKIE["username"];
      $pass = base64_decode($objCrypt->decrypt($_COOKIE["hash"]));

      if ($ldap->ldap_authenticate($user, $pass)) {

        $model = new UserModel($connection);

        $dn = "OU=DELIN,DC=sig,DC=dom";
        $fields = array("ou", "sn", "givenname", "mail", "company", "sAMAccountName", 'displayname', 'department');

        foreach ($arrValues as $val) {
          $userName = trim($val['username']);
          $filter = sprintf('(&(objectClass=*)(sAMAccountName=%s))', $userName);
          $binding = ldap_search($ldap->connect, $ldapBasedn, $filter, $fields, 0);
          $arrEntries = ldap_get_entries($ldap->connect, $binding);
          if ($arrEntries['count'] > 0) {
            if ($model->update($userName, array(
              'firstname' => utf8_decode($arrEntries[0]['givenname'][0]),
              'lastname' => utf8_decode($arrEntries[0]['sn'][0]),
              'description' => $arrEntries[0]['displayname'][0],
              'company' => $arrEntries[0]['company'][0],
              'department' => $arrEntries[0]['department'][0],
            ))
            ) {
              print $userName . ' updated successfully<br/>';
            } else {
              print 'failed updating ' . $userName . '<br/>';
            };
          }
        }

      }
    }
    return TRUE;
  }
}