<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 18.10.12
 * Time: 15:22
 * @version: $Id$
 * Purpose:
 */
uses(
  'de.moonbird.interfaces.restricted.IRestricted',
  'de.moonbird.web.response.ResponseFactory',
  'de.moonbird.web.backend.AbstractBackend',
  'biz.sig.portal.security.Permission',
  'biz.sig.gmdb.model.tableData.TableDataModel'
);

class TableDataBackend extends AbstractBackend implements IRestricted
{
  public function run() {
    if ($this->authenticate()) {
      $this->permit();
    } else {
      $this->forbid();
    }
  }


  public function authenticate() {
    $permission = new Permission();
    return $permission->setApplication(Configuration::get('project', 'shortname'))->setUser($_COOKIE['username'])->checkRight('admin');
  }

  public function permit() {

    $connection = ConnectionBuilder::fromDatabaseIni('gmdb');
    $this->setResponse(ResponseFactory::create('json'));
    switch (@$_GET['a']) {

      case 'table-info':
        // load all data for a given table
        $this->response->setData($connection->showColumns($_GET['table']));
        break;

      case 'table-data':
        // load all data for a given table
        $response = array();
        $arrValues = $connection->select(sprintf('SELECT * FROM %s', $_GET['table']));
        if (is_array($arrValues)) {
          $response['records'] = count($arrValues);
          $response['total'] = 1;
          $response['page'] = 1;
          foreach ($arrValues as $row) {
            $response['rows'][] = ArrayUtil::utf8Encode($row);
          }
          // TODO: quick fix; needs to conform to the JSON response of MBF, evaluate jqGrid to match format
          print json_encode($response);
          die();
        } else {
          $this->response->setStatus(ResponseState::FAILED);
        }
        $this->response->setData($response);
        break;

      case 'save':
        // save changes to a specified table
        $table = $_GET['table'];
        $model = new TableDataModel($connection);
        $model->setTable($table);

        switch ($_POST['oper']) {
          case 'add':
            $result = $model->insert($_POST);
            break;
          case 'edit':
            $result = $model->update($_POST);
            break;
          case 'del':
            $result = $model->delete($_POST);
            break;
        }
        // TODO: quick fix; needs to conform to the JSON response of MBF, evaluate jqGrid to match format
        print json_encode($result);
        die();
        break;

      default:
        // list all available tables
        $arrTables = $connection->showTables();
        asort($arrTables);
        $this->response->setData($arrTables);
        break;
    }
    $this->response->respond();
  }

  public function forbid() {
    // no response required
  }
}
