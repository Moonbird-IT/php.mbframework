<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 13.11.12
 * Time: 11:04
 * @version: $Id$
 * Purpose:
 */
uses(
  'de.moonbird.common.database.ConnectionBuilder',
  'biz.sig.portal.model.UserModel');

class UserBackend
{
  public function run() {
    switch ($_GET['o']) {
      case 'get':
        $model = new UserModel(ConnectionBuilder::fromDatabaseIni('portal'));
        if (is_numeric($_GET['id'])) {
          $arrValues = $model->getById($_GET['id']);
        } else {
          $arrValues = $model->getByUserName($_GET['id']);
        }
        if (is_array($arrValues)) {
          print json_encode(
            array(
              'name' => utf8_encode($arrValues['description']),
              'email' => $arrValues['email'])
          );
        }
        break;

      case 'get-all':
        $model= new UserModel(ConnectionBuilder::fromDatabaseIni('portal'));
        print json_encode($model->get());
        break;

      case 'login':

        $user= $_POST['user'];
        $pass= $_POST['pass'];
        if (check_user_data($user, $pass)===TRUE) {
          ob_clean();
          print json_encode(TRUE);
        } else {
          ob_clean();
          print json_encode(FALSE);
        }
        break;
    }
  }
}
