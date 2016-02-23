<?php
/**
 * User: Sascha Meyer Moonbird IT
 * Date: 09.09.12
 * Time: 11:26
 * @version: $Id$
 * Purpose: load translations for a given language
 */
uses('de.moonbird.common.database.ConnectionBuilder',
  'de.moonbird.web.backend.AbstractBackend',
  'de.moonbird.web.response.ResponseFactory',
  'de.moonbird.common.array.ArrayUtil');

class I18nBackend extends AbstractBackend
{
  private function load($lang) {
    $model= new I18nModel(ConnectionBuilder::fromDatabaseIni('portal'));
    $data= $model->get($lang);

    // load additional language for custom application
    if (isset($_GET['app']) && $_GET['app'] != '') {
      $appTranslations= $model->getAdditional($_GET['app'], $lang);
      if (is_array($appTranslations)) {
        $data= array_merge($appTranslations, $data);
      }
    }
    if (isset($_GET['external']) && $_GET['external'] != '') {
      $model= new I18nModel(ConnectionBuilder::fromDatabaseIni($_GET['external']));
      $appTranslations= $model->get($lang);
      if (is_array($appTranslations)) {
        $data= array_merge($appTranslations, $data);
      }
    }
    $this->response->setData(ArrayUtil::utf8Encode($data));
  }

  public function run() {
    $this->response= new ResponseFactory('json');

    switch (@$_GET['a']) {
      default:
        $this->load($_GET['lang']);
        break;
    }

    $this->response->respond();
  }
}
