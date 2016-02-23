<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 09.09.12
 * Time: 11:45
 * @version: $Id$
 * Purpose: load language from portal or additional databases
 */
uses(
  'de.moonbird.common.database.ConnectionBuilder',
  'de.moonbird.apps.portal.backend.i18n.I18nBackend',
  'de.moonbird.apps.portal.model.i18n.I18nModel');

class I18nController
{
  public function run()
  {
    if (isset($_GET['json'])) {
      $backend = new I18nBackend();
      $backend->run();
    }
  }

  public function load($connection, $language)
  {
    $model = new I18nModel($connection);
    $arrValues = $model->get($language);
    if (is_array($arrValues)) {
      foreach ($arrValues as $row) {
        define(trim($row["phrase"]), trim($row["translation"]));
      }
    }
  }
}
